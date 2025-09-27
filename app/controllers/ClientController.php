<?php
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Local.php';
require_once __DIR__ . '/../../config/db.php';

class ClientController {
    
    public function handleRequest() {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar que el usuario esté autenticado
        if (!isset($_SESSION['user'])) {
            header('Location: ../../index.php');
            exit();
        }
        
        $accion = $_GET['accion'] ?? 'index';
        
        switch ($accion) {
            case 'index':
                $this->index();
                break;
            case 'create':
                $this->create();
                break;
            case 'edit':
                $this->edit();
                break;
            case 'delete':
                $this->delete();
                break;
            default:
                $this->index();
                break;
        }
    }
    
    public function index() {
        global $conn;
        
        try {
            // Obtener estadísticas
            $stats = Client::getStats($conn);
            
            // Obtener locales para filtros
            $locales = Local::getAll($conn);
            
            // Procesar filtros
            $filtros = [
                'local' => $_GET['local'] ?? '',
                'estado' => $_GET['estado'] ?? '',
                'busqueda' => $_GET['busqueda'] ?? '',
                'fecha_desde' => $_GET['fecha_desde'] ?? '',
                'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
            ];
            
            // Obtener clientes con filtros aplicados
            $clientes = Client::getAll($conn, $filtros);
            
            include __DIR__ . '/../views/clientes/index.php';
            
        } catch (Exception $e) {
            $error = "Error al cargar los clientes: " . $e->getMessage();
            include __DIR__ . '/../views/clientes/index.php';
        }
    }
    
    public function create() {
        global $conn;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validar datos requeridos
                $required_fields = ['nombre', 'correo', 'id_locales'];
                foreach ($required_fields as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("El campo " . ucfirst($field) . " es requerido");
                    }
                }
                
                // Validar formato de correo
                if (!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("El formato del correo electrónico no es válido");
                }
                
                // Verificar si el correo ya existe
                if (Client::getByEmail($conn, $_POST['correo'])) {
                    throw new Exception("Ya existe un cliente con ese correo electrónico");
                }
                
                // Crear cliente
                $clienteId = Client::create($conn, [
                    'nombre' => trim($_POST['nombre']),
                    'descripcion' => trim($_POST['descripcion'] ?? ''),
                    'cel_cliente' => trim($_POST['cel_cliente'] ?? ''),
                    'correo' => trim($_POST['correo']),
                    'estado' => $_POST['estado'] ?? 'activo',
                    'id_locales' => (int)$_POST['id_locales']
                ]);
                
                $success = "Cliente creado exitosamente";
                
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        // Obtener locales para el formulario
        $locales = Local::getAll($conn);
        include __DIR__ . '/../views/clientes/create.php';
    }
    
    public function edit() {
        global $conn;
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /RMIE/app/controllers/ClientController.php?accion=index');
            exit;
        }
        
        try {
            $cliente = Client::getById($conn, $id);
            if (!$cliente) {
                throw new Exception("Cliente no encontrado");
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validar datos requeridos
                $required_fields = ['nombre', 'correo', 'id_locales'];
                foreach ($required_fields as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("El campo " . ucfirst($field) . " es requerido");
                    }
                }
                
                // Validar formato de correo
                if (!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("El formato del correo electrónico no es válido");
                }
                
                // Verificar si el correo ya existe (excluyendo el cliente actual)
                $existingClient = Client::getByEmail($conn, $_POST['correo']);
                if ($existingClient && $existingClient->id_clientes != $id) {
                    throw new Exception("Ya existe otro cliente con ese correo electrónico");
                }
                
                // Actualizar cliente
                Client::update($conn, $id, [
                    'nombre' => trim($_POST['nombre']),
                    'descripcion' => trim($_POST['descripcion'] ?? ''),
                    'cel_cliente' => trim($_POST['cel_cliente'] ?? ''),
                    'correo' => trim($_POST['correo']),
                    'estado' => $_POST['estado'] ?? 'activo',
                    'id_locales' => (int)$_POST['id_locales']
                ]);
                
                $success = "Cliente actualizado exitosamente";
                
                // Recargar datos del cliente
                $cliente = Client::getById($conn, $id);
            }
            
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        
        // Obtener locales para el formulario
        $locales = Local::getAll($conn);
        include __DIR__ . '/../views/clientes/edit.php';
    }
    
    public function delete() {
        global $conn;
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /RMIE/app/controllers/ClientController.php?accion=index');
            exit;
        }
        
        try {
            $cliente = Client::getById($conn, $id);
            if (!$cliente) {
                throw new Exception("Cliente no encontrado");
            }
            
            // Verificar si el cliente tiene ventas asociadas
            $stats = Client::getClientStats($conn, $id);
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Verificar contraseña del administrador si es necesario
                if (isset($_POST['admin_password_confirm']) && !empty($_POST['admin_password_confirm'])) {
                    // Aquí podrías verificar la contraseña del administrador
                    // Por ahora, procedemos con la eliminación
                }
                
                Client::delete($conn, $id);
                header('Location: /RMIE/app/controllers/ClientController.php?accion=index&deleted=1');
                exit;
            }
            
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        
        include __DIR__ . '/../views/clientes/delete.php';
    }
}

// Procesar la solicitud si se accede directamente
if ($_SERVER['SCRIPT_NAME'] === '/RMIE/app/controllers/ClientController.php') {
    $controller = new ClientController();
    $controller->handleRequest();
}
?>
