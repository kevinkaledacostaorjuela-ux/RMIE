<?php
// Activar visualización de errores para desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Local.php';
require_once __DIR__ . '/../../config/db.php';

class LocalController {
    
    public function index() {
        global $conn;
        
        try {
            // Verificar sesión
            session_start();
            if (!isset($_SESSION['user'])) {
                header('Location: /RMIE/index.php');
                exit();
            }

            // Filtros actualizados para coincidir con la vista
            $filtros = [
                'nombre' => $_GET['buscar'] ?? '', // El campo 'buscar' busca en nombre
                'localidad' => $_GET['localidad'] ?? '',
                'estado' => $_GET['estado'] ?? '',
                'barrio' => $_GET['barrio'] ?? ''
            ];
            
            $locales = Local::getAll($conn, $filtros);
            
            include __DIR__ . '/../views/local/index.php';
        } catch (Exception $e) {
            die("Error en LocalController::index(): " . $e->getMessage());
        }
    }
    
    public function create() {
        try {
            session_start();
            if (!isset($_SESSION['user'])) {
                header('Location: /RMIE/index.php');
                exit();
            }
            
            include __DIR__ . '/../views/local/create.php';
        } catch (Exception $e) {
            die("Error en LocalController::create(): " . $e->getMessage());
        }
    }
    
    public function store() {
        global $conn;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            
            // Mapear campos del formulario a campos del modelo
            $data = [
                'direccion' => trim($_POST['direccion'] ?? ''),
                'nombre_local' => trim($_POST['nombre'] ?? ''), // nombre -> nombre_local
                'cel_local' => trim($_POST['telefono'] ?? ''), // telefono -> cel_local
                'estado' => trim($_POST['estado'] ?? 'activo'),
                'localidad' => trim($_POST['localidad'] ?? ''),
                'barrio' => trim($_POST['barrio'] ?? '')
            ];
            
            // Validaciones
            $errors = [];
            
            if (empty($data['nombre_local'])) {
                $errors[] = "El nombre del local es obligatorio";
            }
            
            if (empty($data['direccion'])) {
                $errors[] = "La dirección es obligatoria";
            }
            
            if (empty($data['cel_local'])) {
                $errors[] = "El teléfono es obligatorio";
            }
            
            if (empty($data['localidad'])) {
                $errors[] = "La localidad es obligatoria";
            }
            
            if (empty($data['barrio'])) {
                $errors[] = "El barrio es obligatorio";
            }
            
            // Verificar si ya existe un local con el mismo nombre
            if (Local::getByName($conn, $data['nombre_local'])) {
                $errors[] = "Ya existe un local con ese nombre";
            }
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode(', ', $errors);
                header('Location: /RMIE/app/controllers/LocalController.php?accion=create');
                exit();
            }
            
            // Crear local
            if (Local::create($conn, $data)) {
                $_SESSION['success'] = "Local '" . $data['nombre_local'] . "' creado exitosamente";
                header('Location: /RMIE/app/controllers/LocalController.php?accion=index');
            } else {
                $_SESSION['error'] = "Error al crear el local";
                header('Location: /RMIE/app/controllers/LocalController.php?accion=create');
            }
            exit();
        }
    }
    
    public function edit() {
        global $conn;
        
        try {
            session_start();
            if (!isset($_SESSION['user'])) {
                header('Location: /RMIE/index.php');
                exit();
            }
            
            $id = $_GET['id'] ?? null;
            
            // Debug: mostrar información
            if (isset($_GET['debug'])) {
                echo "ID recibido: " . $id . "<br>";
            }
            
            if (!$id) {
                $_SESSION['error'] = "ID de local no válido (ID: " . $id . ")";
                header('Location: /RMIE/app/controllers/LocalController.php?accion=index');
                exit();
            }
            
            $local = Local::getById($conn, $id);
            
            // Debug: mostrar resultado de la consulta
            if (isset($_GET['debug'])) {
                echo "Local encontrado: ";
                if ($local) {
                    echo "SÍ - Nombre: " . $local->nombre_local . "<br>";
                } else {
                    echo "NO<br>";
                }
            }
            
            if (!$local) {
                $_SESSION['error'] = "Local no encontrado (ID: " . $id . ")";
                header('Location: /RMIE/app/controllers/LocalController.php?accion=index');
                exit();
            }
            
            // Debug: verificar si la vista existe
            $viewPath = __DIR__ . '/../views/local/edit.php';
            if (isset($_GET['debug'])) {
                echo "Ruta de la vista: " . $viewPath . "<br>";
                echo "Vista existe: " . (file_exists($viewPath) ? "SÍ" : "NO") . "<br><br>";
            }
            
            include $viewPath;
        } catch (Exception $e) {
            die("Error en LocalController::edit(): " . $e->getMessage());
        }
    }
    
    public function update() {
        global $conn;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                $_SESSION['error'] = "ID de local no válido";
                header('Location: /RMIE/app/controllers/LocalController.php?accion=index');
                exit();
            }
            
            // Mapear campos del formulario a campos del modelo
            $data = [
                'direccion' => trim($_POST['direccion'] ?? ''),
                'nombre_local' => trim($_POST['nombre'] ?? ''), // nombre -> nombre_local
                'cel_local' => trim($_POST['telefono'] ?? ''), // telefono -> cel_local
                'estado' => trim($_POST['estado'] ?? ''),
                'localidad' => trim($_POST['localidad'] ?? ''),
                'barrio' => trim($_POST['barrio'] ?? '')
            ];
            
            // Validaciones
            $errors = [];
            
            if (empty($data['nombre_local'])) {
                $errors[] = "El nombre del local es obligatorio";
            }
            
            if (empty($data['direccion'])) {
                $errors[] = "La dirección es obligatoria";
            }
            
            if (empty($data['cel_local'])) {
                $errors[] = "El teléfono es obligatorio";
            }
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode(', ', $errors);
                header('Location: /RMIE/app/controllers/LocalController.php?accion=edit&id=' . $id);
                exit();
            }
            
            // Actualizar local
            if (Local::update($conn, $id, $data)) {
                $_SESSION['success'] = "Local actualizado exitosamente";
                header('Location: /RMIE/app/controllers/LocalController.php?accion=index');
            } else {
                $_SESSION['error'] = "Error al actualizar el local";
                header('Location: /RMIE/app/controllers/LocalController.php?accion=edit&id=' . $id);
            }
            exit();
        }
    }
    
    public function delete() {
        global $conn;
        
        try {
            session_start();
            if (!isset($_SESSION['user'])) {
                header('Location: /RMIE/index.php');
                exit();
            }
            
            $id = $_GET['id'] ?? null;
            
            // Debug: mostrar información
            if (isset($_GET['debug'])) {
                echo "ID para eliminar: " . $id . "<br>";
            }
            
            if (!$id) {
                $_SESSION['error'] = "ID de local no válido para eliminar (ID: " . $id . ")";
                header('Location: /RMIE/app/controllers/LocalController.php?accion=index');
                exit();
            }
            
            // Verificar que el local exista
            $local = Local::getById($conn, $id);
            
            if (!$local) {
                $_SESSION['error'] = "Local no encontrado para eliminar (ID: " . $id . ")";
                header('Location: /RMIE/app/controllers/LocalController.php?accion=index');
                exit();
            }
            
            // Debug: mostrar información del local a eliminar
            if (isset($_GET['debug'])) {
                echo "Local a eliminar: " . $local->nombre_local . "<br>";
                echo "Estado: " . $local->estado . "<br><br>";
            }
            
            // Verificar si se puede eliminar el local (sin registros relacionados)
            if (!Local::canDelete($conn, $id)) {
                // Obtener información específica sobre qué está bloqueando la eliminación
                $dependencias = [];
                
                // Verificar clientes
                $sql_clientes = "SELECT COUNT(*) as count FROM clientes WHERE id_locales = ?";
                $stmt = $conn->prepare($sql_clientes);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $clientes = $stmt->get_result()->fetch_assoc()['count'];
                
                if ($clientes > 0) {
                    $dependencias[] = "$clientes cliente(s)";
                }
                
                // Verificar productos (si la tabla existe)
                try {
                    $sql_productos = "SELECT COUNT(*) as count FROM productos WHERE id_locales = ?";
                    $stmt = $conn->prepare($sql_productos);
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $productos = $stmt->get_result()->fetch_assoc()['count'];
                    
                    if ($productos > 0) {
                        $dependencias[] = "$productos producto(s)";
                    }
                } catch (Exception $e) {
                    // La tabla productos puede no existir o no tener la columna id_locales
                }
                
                $mensaje_dependencias = implode(' y ', $dependencias);
                $_SESSION['error'] = "No se puede eliminar el local '" . $local->nombre_local . "' porque tiene " . $mensaje_dependencias . " asociado(s). Primero debe reasignar o eliminar estos registros.";
                header('Location: /RMIE/app/controllers/LocalController.php?accion=index');
                exit();
            }
            
            // Intentar eliminar el local
            $resultado = Local::delete($conn, $id);
            
            if ($resultado) {
                $_SESSION['success'] = "Local '" . $local->nombre_local . "' eliminado exitosamente";
            } else {
                $_SESSION['error'] = "Error al eliminar el local '" . $local->nombre_local . "'";
            }
            
            header('Location: /RMIE/app/controllers/LocalController.php?accion=index');
            exit();
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al eliminar el local: " . $e->getMessage();
            header('Location: /RMIE/app/controllers/LocalController.php?accion=index');
            exit();
        }
    }
}

// Manejo de acciones por parámetro GET (soporta tanto 'accion' como 'action')
$accion = $_GET['accion'] ?? $_GET['action'] ?? 'index';

// Debug: mostrar parámetros recibidos
if (isset($_GET['debug'])) {
    echo "Parámetros GET recibidos: ";
    print_r($_GET);
    echo "<br>Acción detectada: " . $accion . "<br><br>";
}

$controller = new LocalController();

switch($accion) {
    case 'index':
        $controller->index();
        break;
    case 'create':
        $controller->create();
        break;
    case 'store':
        $controller->store();
        break;
    case 'edit':
        $controller->edit();
        break;
    case 'update':
        $controller->update();
        break;
    case 'delete':
        $controller->delete();
        break;
    default:
        $controller->index();
        break;
}
?>