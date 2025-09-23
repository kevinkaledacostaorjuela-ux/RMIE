<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../config/db.php';

class UserController {
    
    public function index() {
        global $conn;
        
        try {
            // Obtener parámetros de filtrado
            $filtro_rol = $_GET['filtro_rol'] ?? '';
            $filtro_tipo_doc = $_GET['filtro_tipo_doc'] ?? '';
            $buscar = $_GET['buscar'] ?? '';
            
            // Obtener usuarios con filtros
            $usuarios = User::getAll($conn, $filtro_rol, $filtro_tipo_doc, $buscar);
            
            // Obtener estadísticas
            $stats = User::getStats($conn);
            
            include __DIR__ . '/../views/usuarios/index.php';
        } catch (Exception $e) {
            $error = "Error al cargar usuarios: " . $e->getMessage();
            include __DIR__ . '/../views/usuarios/index.php';
        }
    }
    
    public function create() {
        global $conn;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validar campos requeridos
                $errores = [];
                
                if (empty($_POST['num_doc'])) {
                    $errores[] = "El número de documento es requerido";
                }
                
                if (empty($_POST['nombres'])) {
                    $errores[] = "Los nombres son requeridos";
                }
                
                if (empty($_POST['apellidos'])) {
                    $errores[] = "Los apellidos son requeridos";
                }
                
                if (empty($_POST['correo'])) {
                    $errores[] = "El correo es requerido";
                } elseif (!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
                    $errores[] = "El formato del correo no es válido";
                }
                
                if (empty($_POST['contrasena'])) {
                    $errores[] = "La contraseña es requerida";
                } elseif (strlen($_POST['contrasena']) < 6) {
                    $errores[] = "La contraseña debe tener al menos 6 caracteres";
                }
                
                if (empty($_POST['rol'])) {
                    $errores[] = "El rol es requerido";
                }
                
                // Verificar si ya existe un usuario con ese documento
                if (!empty($_POST['num_doc'])) {
                    $usuarioExistente = User::getById($conn, $_POST['num_doc']);
                    if ($usuarioExistente) {
                        $errores[] = "Ya existe un usuario con ese número de documento";
                    }
                }
                
                // Verificar si ya existe un usuario con ese correo
                if (!empty($_POST['correo'])) {
                    $usuarioCorreo = User::getByEmail($conn, $_POST['correo']);
                    if ($usuarioCorreo) {
                        $errores[] = "Ya existe un usuario con ese correo electrónico";
                    }
                }
                
                if (empty($errores)) {
                    // Crear el usuario
                    $resultado = User::create($conn, $_POST);
                    
                    if ($resultado) {
                        header('Location: /RMIE/app/controllers/UserController.php?accion=index&success=Usuario creado exitosamente');
                        exit;
                    } else {
                        $error = "Error al crear el usuario";
                    }
                } else {
                    $error = implode(", ", $errores);
                }
                
            } catch (Exception $e) {
                $error = "Error al procesar la solicitud: " . $e->getMessage();
            }
        }
        
        include __DIR__ . '/../views/usuarios/create.php';
    }
    
    public function edit() {
        global $conn;
        
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /RMIE/app/controllers/UserController.php?accion=index&error=ID de usuario no especificado');
            exit;
        }
        
        try {
            $usuario = User::getById($conn, $id);
            
            if (!$usuario) {
                header('Location: /RMIE/app/controllers/UserController.php?accion=index&error=Usuario no encontrado');
                exit;
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validar campos requeridos
                $errores = [];
                
                if (empty($_POST['nombres'])) {
                    $errores[] = "Los nombres son requeridos";
                }
                
                if (empty($_POST['apellidos'])) {
                    $errores[] = "Los apellidos son requeridos";
                }
                
                if (empty($_POST['correo'])) {
                    $errores[] = "El correo es requerido";
                } elseif (!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
                    $errores[] = "El formato del correo no es válido";
                }
                
                if (empty($_POST['rol'])) {
                    $errores[] = "El rol es requerido";
                }
                
                // Verificar si el correo ya existe en otro usuario
                if (!empty($_POST['correo'])) {
                    $usuarioCorreo = User::getByEmail($conn, $_POST['correo']);
                    if ($usuarioCorreo && $usuarioCorreo->num_doc != $id) {
                        $errores[] = "Ya existe otro usuario con ese correo electrónico";
                    }
                }
                
                // Validar contraseña si se proporciona
                if (!empty($_POST['contrasena']) && strlen($_POST['contrasena']) < 6) {
                    $errores[] = "La contraseña debe tener al menos 6 caracteres";
                }
                
                if (empty($errores)) {
                    // Actualizar el usuario
                    $resultado = User::update($conn, $id, $_POST);
                    
                    if ($resultado) {
                        $success = "Usuario actualizado exitosamente";
                        // Recargar los datos del usuario
                        $usuario = User::getById($conn, $id);
                    } else {
                        $error = "Error al actualizar el usuario";
                    }
                } else {
                    $error = implode(", ", $errores);
                }
            }
            
        } catch (Exception $e) {
            $error = "Error al procesar la solicitud: " . $e->getMessage();
        }
        
        include __DIR__ . '/../views/usuarios/edit.php';
    }
    
    public function delete() {
        global $conn;
        
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /RMIE/app/controllers/UserController.php?accion=index&error=ID de usuario no especificado');
            exit;
        }
        
        try {
            $usuario = User::getById($conn, $id);
            
            if (!$usuario) {
                header('Location: /RMIE/app/controllers/UserController.php?accion=index&error=Usuario no encontrado');
                exit;
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Verificar si el usuario tiene ventas asociadas
                $ventasAsociadas = User::hasAssociatedSales($conn, $id);
                
                if ($ventasAsociadas > 0) {
                    $error = "No se puede eliminar el usuario porque tiene $ventasAsociadas venta(s) asociada(s)";
                } else {
                    $resultado = User::delete($conn, $id);
                    
                    if ($resultado) {
                        header('Location: /RMIE/app/controllers/UserController.php?accion=index&success=Usuario eliminado exitosamente');
                        exit;
                    } else {
                        $error = "Error al eliminar el usuario";
                    }
                }
            }
            
        } catch (Exception $e) {
            $error = "Error al procesar la solicitud: " . $e->getMessage();
        }
        
        include __DIR__ . '/../views/usuarios/delete.php';
    }
    
    // Método para manejar las rutas
    public function handleRequest() {
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
}

// Ejecutar el controlador
if (basename($_SERVER['PHP_SELF']) === 'UserController.php') {
    $controller = new UserController();
    $controller->handleRequest();
}
?>
