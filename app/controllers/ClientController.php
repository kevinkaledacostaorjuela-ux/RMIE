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
            // Capturar mensaje de éxito de la URL
            if (isset($_GET['success'])) {
                $_SESSION['success'] = $_GET['success'];
            }
            
            require_once __DIR__ . '/../utils/FilterHelper.php';
            
            // Definir reglas de filtro
            $filterRules = [
                'local' => ['type' => 'int', 'options' => ['min' => 1]],
                'estado' => ['type' => 'select', 'options' => ['allowed_values' => ['activo', 'inactivo', 'pendiente']]],
                'busqueda' => ['type' => 'text', 'options' => ['max_length' => 100]],
                'fecha_desde' => ['type' => 'date'],
                'fecha_hasta' => ['type' => 'date']
            ];
            
            // Procesar filtros del GET
            $filtros = FilterHelper::processFilters($_GET, $filterRules);
            
            // Validar rango de fechas si ambas están presentes
            if (!empty($filtros['fecha_desde']) && !empty($filtros['fecha_hasta'])) {
                $dateRange = FilterHelper::validateDateRange($filtros['fecha_desde'], $filtros['fecha_hasta']);
                $filtros = array_merge($filtros, $dateRange);
            }
            
            // Obtener estadísticas
            $stats = Client::getStats($conn);
            
            // Obtener locales para filtros
            $locales = Local::getAll($conn);
            
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
        
        // Obtener locales para el formulario
        $locales = Local::getAll($conn);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validar datos requeridos
                $required_fields = ['nombre', 'correo'];
                foreach ($required_fields as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("El campo " . ucfirst($field) . " es requerido");
                    }
                }
                
                // Validar que se hayan seleccionado locales
                if (empty($_POST['id_locales']) || !is_array($_POST['id_locales'])) {
                    throw new Exception("Debe seleccionar al menos un local");
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
                    'estado' => $_POST['estado'] ?? 'activo'
                ]);
                
                // Asignar locales
                if ($clienteId) {
                    Client::updateLocales($conn, $clienteId, $_POST['id_locales']);
                }
                
                // Redirigir al index con mensaje de éxito
                header('Location: /RMIE/app/controllers/ClientController.php?accion=index&success=Cliente creado exitosamente');
                exit;
                
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
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
            
            // Obtener locales asignados al cliente
            $locales_asignados = Client::getLocales($conn, $id);
            $locales_asignados_ids = array_map(function($l) { return $l->id_locales; }, $locales_asignados);
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validar datos requeridos
                $required_fields = ['nombre', 'correo'];
                foreach ($required_fields as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("El campo " . ucfirst($field) . " es requerido");
                    }
                }
                
                // Validar que se hayan seleccionado locales
                if (empty($_POST['id_locales']) || !is_array($_POST['id_locales'])) {
                    throw new Exception("Debe seleccionar al menos un local");
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
                    'estado' => $_POST['estado'] ?? 'activo'
                ]);
                
                // Actualizar locales asignados
                Client::updateLocales($conn, $id, $_POST['id_locales']);
                
                // Redirigir al index con mensaje de éxito
                header('Location: /RMIE/app/controllers/ClientController.php?accion=index&success=Cliente actualizado exitosamente');
                exit;
            }
            
        } catch (Exception $e) {
            $error = $e->getMessage();
            $locales_asignados_ids = $locales_asignados_ids ?? [];
        }
        
        // Obtener locales para el formulario
        $locales = Local::getAll($conn);
        include __DIR__ . '/../views/clientes/edit.php';
    }
    
    public function delete() {
        // Verificar sesión activa
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Log para depuración
        error_log("DELETE REQUEST - Usuario: " . ($_SESSION['user'] ?? 'no definido') . ", Rol: " . ($_SESSION['rol'] ?? 'no definido'));
        
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user']) || !isset($_SESSION['rol'])) {
            $_SESSION['error'] = 'Debe iniciar sesión para realizar esta acción';
            header('Location: /RMIE/app/controllers/ClientController.php?accion=index');
            exit();
        }
        
        // Verificar permisos por rol
        if ($_SESSION['rol'] === 'coordinador') {
            $_SESSION['error'] = 'El rol de coordinador no tiene permisos para eliminar clientes';
            header('Location: /RMIE/app/controllers/ClientController.php?accion=index');
            exit();
        }
        
        if ($_SESSION['rol'] === 'auxiliar') {
            $_SESSION['error'] = 'El rol de auxiliar no tiene permisos para eliminar clientes';
            header('Location: /RMIE/app/controllers/ClientController.php?accion=index');
            exit();
        }
        
        global $conn;
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID de cliente no especificado';
            header('Location: /RMIE/app/controllers/ClientController.php?accion=index');
            exit;
        }
        
        error_log("DELETE REQUEST - Intentando eliminar cliente ID: $id");
        
        try {
            $cliente = Client::getById($conn, $id);
            if (!$cliente) {
                throw new Exception("Cliente no encontrado con ID: $id");
            }
            
            error_log("DELETE REQUEST - Cliente encontrado: " . $cliente->nombre);
            
            // Intentar eliminar el cliente (el modelo verificará las restricciones)
            $resultado = Client::delete($conn, $id);
            
            if ($resultado) {
                error_log("DELETE REQUEST - Cliente eliminado exitosamente");
                $_SESSION['success'] = 'Cliente eliminado exitosamente';
            } else {
                throw new Exception("No se pudo eliminar el cliente");
            }
            
            header('Location: /RMIE/app/controllers/ClientController.php?accion=index');
            exit;
            
        } catch (Exception $e) {
            // Guardar el error en la sesión para mostrarlo en la vista
            error_log("DELETE REQUEST - Error: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            header('Location: /RMIE/app/controllers/ClientController.php?accion=index');
            exit;
        }
    }
}

// Procesar la solicitud si se accede directamente
if ($_SERVER['SCRIPT_NAME'] === '/RMIE/app/controllers/ClientController.php') {
    $controller = new ClientController();
    $controller->handleRequest();
}
?>
