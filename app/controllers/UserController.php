<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../config/db.php';

class UserController {
      public function index() {
        global $conn;
        
        try {
            require_once __DIR__ . '/../utils/FilterHelper.php';
            
            // Definir reglas de filtro actualizadas
            $filterRules = [
                'filtro_rol' => ['type' => 'select', 'options' => ['allowed_values' => ['admin', 'coordinador', 'auxiliar']]],
                'filtro_tipo_doc' => ['type' => 'select', 'options' => ['allowed_values' => ['CC', 'CE', 'TI', 'PP']]],
                'buscar' => ['type' => 'text', 'options' => ['max_length' => 100]],
                'filtro_celular' => ['type' => 'text', 'options' => ['max_length' => 20]],
                'fecha_desde' => ['type' => 'date'],
                'fecha_hasta' => ['type' => 'date'],
                'fecha' => ['type' => 'date'],
                'estado' => ['type' => 'select', 'options' => ['allowed_values' => ['activo', 'inactivo']]]
            ];
            
            // Procesar filtros del GET
            $filtros = FilterHelper::processFilters($_GET, $filterRules);
            
            // Mapear para compatibilidad con el modelo (con los nuevos nombres)
            $filtrosModelo = [
                'rol' => $filtros['filtro_rol'] ?? '',
                'tipo_doc' => $filtros['filtro_tipo_doc'] ?? '',
                'buscar' => $filtros['buscar'] ?? '',
                'num_cel' => $filtros['filtro_celular'] ?? '',
                'fecha_desde' => $filtros['fecha_desde'] ?? '',
                'fecha_hasta' => $filtros['fecha_hasta'] ?? '',
                'fecha' => $filtros['fecha'] ?? '',
                'estado' => $filtros['estado'] ?? ''
            ];
            
            // Si se especificó una fecha exacta, usarla como rango
            if (!empty($filtrosModelo['fecha'])) {
                $filtrosModelo['fecha_desde'] = $filtrosModelo['fecha'];
                $filtrosModelo['fecha_hasta'] = $filtrosModelo['fecha'];
            }
            
            // Obtener usuarios con filtros
            $usuarios = User::getAll($conn, $filtrosModelo);
            
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
                } elseif (!in_array($_POST['rol'], ['coordinador', 'auxiliar'])) {
                    $errores[] = "El rol seleccionado no es válido. Solo se permiten Coordinador o Auxiliar";
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
                } else {
                    // Si el usuario actual es admin, puede mantener su rol
                    if ($usuario->rol === 'admin' && $_POST['rol'] === 'admin') {
                        // Permitido: el admin puede mantener su rol
                    } elseif (!in_array($_POST['rol'], ['coordinador', 'auxiliar'])) {
                        $errores[] = "El rol seleccionado no es válido. Solo se permiten Coordinador o Auxiliar";
                    }
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
                        header('Location: /RMIE/app/controllers/UserController.php?accion=index&success=Usuario actualizado exitosamente');
                        exit;
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
        // Verificar sesión activa
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user']) || !isset($_SESSION['rol'])) {
            echo '<script>alert("Debe iniciar sesión para realizar esta acción."); window.location.href = "/RMIE/index.php";</script>';
            exit();
        }
        
        // Verificar si el rol es coordinador o auxiliar y restringir eliminación
        if ($_SESSION['rol'] === 'coordinador') {
            echo '<script>alert("El rol de coordinador no tiene permisos para eliminar registros por políticas de seguridad."); window.location.href = "/RMIE/app/controllers/UserController.php?accion=index";</script>';
            exit();
        }
        
        if ($_SESSION['rol'] === 'auxiliar') {
            echo '<script>alert("El rol de auxiliar no tiene permisos para eliminar registros por políticas de seguridad."); window.location.href = "/RMIE/app/controllers/UserController.php?accion=index";</script>';
            exit();
        }
        
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
                // Verificar si el usuario tiene registros asociados
                $relaciones = User::getAssociatedRecords($conn, $id);
                $mensajesError = [];
                
                if ($relaciones['productos'] > 0) {
                    $mensajesError[] = "{$relaciones['productos']} producto(s)";
                }
                
                if ($relaciones['ventas'] > 0) {
                    $mensajesError[] = "{$relaciones['ventas']} venta(s)";
                }
                
                if (!empty($mensajesError)) {
                    $error = "No se puede eliminar el usuario porque tiene registros asociados: " . implode(", ", $mensajesError) . ". Primero debe eliminar o reasignar estos registros.";
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
            // Capturar errores de restricción de clave foránea
            if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
                $error = "No se puede eliminar el usuario porque tiene registros asociados en el sistema. Primero debe eliminar o reasignar los productos, ventas u otros registros relacionados.";
            } else {
                $error = "Error al procesar la solicitud: " . $e->getMessage();
            }
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
