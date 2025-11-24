<?php
/**
 * Controlador específico para funciones del rol Auxiliar
 * Basado en el diagrama de casos de uso
 * Fecha: 22 de octubre de 2025
 */

require_once __DIR__ . '/../utils/AuthUtils.php';
require_once __DIR__ . '/../utils/PermissionsConfig.php';
require_once __DIR__ . '/../../config/db.php';

class AuxiliarController {
    
    /**
     * Verifica si el auxiliar tiene acceso a un módulo específico
     * @param string $module Nombre del módulo
     * @return bool
     */
    private function hasModuleAccess($module) {
        return PermissionsConfig::auxiliarCanAccess($module);
    }
    
    /**
     * Bloquea acceso si no tiene permisos para el módulo
     * @param string $module Nombre del módulo
     */
    private function validateModuleAccess($module) {
        if (!$this->hasModuleAccess($module)) {
            $this->accessDenied($module);
        }
    }
    
    /**
     * Maneja acceso denegado
     * @param string $module Módulo al que se intentó acceder
     */
    private function accessDenied($module) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['error'] = "Acceso denegado. El módulo '$module' no está autorizado para el rol auxiliar.";
        header('Location: /RMIE/app/controllers/AuxiliarController.php?accion=dashboard');
        exit();
    }
    
    /**
     * Maneja las solicitudes para el rol auxiliar
     */
    public function handleRequest() {
        // Verificar sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar que sea auxiliar
        if (!AuthUtils::isAuxiliar()) {
            header('Location: ../../index.php?error=acceso_denegado');
            exit();
        }
        
        $accion = $_GET['accion'] ?? 'dashboard';
        
        switch ($accion) {
            case 'dashboard':
                $this->dashboardAuxiliar();
                break;
            case 'consultar_usuarios':
                $this->validateModuleAccess('usuarios');
                $this->consultarUsuarios();
                break;
            case 'consultar_ventas':
                $this->validateModuleAccess('ventas');
                $this->consultarVentas();
                break;
            case 'consultar_reportes':
                $this->validateModuleAccess('reportes');
                $this->consultarReportes();
                break;
            case 'modificar_perfil':
                $this->validateModuleAccess('profile');
                $this->modificarPerfil();
                break;
            // Módulos no autorizados según diagrama - redirigir con error
            case 'consultar_productos':
            case 'consultar_clientes':
            case 'consultar_modulos':
                $this->accessDenied($accion);
                break;
            default:
                $this->dashboardAuxiliar();
                break;
        }
    }
    
    /**
     * Dashboard específico para auxiliares - redirige al dashboard principal
     */
    public function dashboardAuxiliar() {
        // Redirigir al dashboard principal que maneja todos los roles
        header('Location: /RMIE/app/views/dashboard.php');
        exit();
    }
    
    /**
     * Consultar módulos disponibles para auxiliar
     */
    public function consultarModulos() {
        $modulos = [
            [
                'nombre' => 'Consulta de Usuarios',
                'descripcion' => 'Ver información de usuarios del sistema',
                'icono' => 'fas fa-users',
                'url' => '/RMIE/app/controllers/AuxiliarController.php?accion=consultar_usuarios',
                'activo' => true
            ],
            [
                'nombre' => 'Consulta de Clientes',
                'descripcion' => 'Ver información de clientes',
                'icono' => 'fas fa-user-friends',
                'url' => '/RMIE/app/controllers/AuxiliarController.php?accion=consultar_clientes',
                'activo' => true
            ],
            [
                'nombre' => 'Consulta de Productos',
                'descripcion' => 'Ver catálogo de productos',
                'icono' => 'fas fa-box',
                'url' => '/RMIE/app/controllers/AuxiliarController.php?accion=consultar_productos',
                'activo' => true
            ],
            [
                'nombre' => 'Consulta de Ventas',
                'descripcion' => 'Ver historial de ventas',
                'icono' => 'fas fa-shopping-cart',
                'url' => '/RMIE/app/controllers/AuxiliarController.php?accion=consultar_ventas',
                'activo' => true
            ],
            [
                'nombre' => 'Consulta de Reportes',
                'descripcion' => 'Ver reportes generados',
                'icono' => 'fas fa-chart-bar',
                'url' => '/RMIE/app/controllers/AuxiliarController.php?accion=consultar_reportes',
                'activo' => true
            ],
            [
                'nombre' => 'Modificar Perfil',
                'descripcion' => 'Actualizar información personal',
                'icono' => 'fas fa-user-edit',
                'url' => '/RMIE/app/controllers/AuxiliarController.php?accion=modificar_perfil',
                'activo' => true
            ]
        ];
        
        include __DIR__ . '/../views/auxiliar/modulos.php';
    }
    
    /**
     * Consultar usuarios (solo lectura)
     */
    public function consultarUsuarios() {
        global $conn;
        
        try {
            require_once __DIR__ . '/../models/User.php';
            
            // Obtener filtros
            $filtros = [
                'rol' => $_GET['rol'] ?? '',
                'buscar' => $_GET['buscar'] ?? ''
            ];
            
            $usuarios = User::getAll($conn, $filtros);
            $stats = User::getStats($conn);
            
            include __DIR__ . '/../views/auxiliar/usuarios.php';
            
        } catch (Exception $e) {
            $error = "Error al consultar usuarios: " . $e->getMessage();
            include __DIR__ . '/../views/auxiliar/usuarios.php';
        }
    }
    
    /**
     * Consultar clientes (solo lectura)
     */
    public function consultarClientes() {
        global $conn;
        
        try {
            require_once __DIR__ . '/../models/Client.php';
            
            // Obtener filtros
            $filtros = [
                'estado' => $_GET['estado'] ?? '',
                'buscar' => $_GET['buscar'] ?? '',
                'local' => $_GET['local'] ?? ''
            ];
            
            $clientes = Client::getAll($conn, $filtros);
            $stats = Client::getStats($conn);
            
            include __DIR__ . '/../views/auxiliar/clientes.php';
            
        } catch (Exception $e) {
            $error = "Error al consultar clientes: " . $e->getMessage();
            include __DIR__ . '/../views/auxiliar/clientes.php';
        }
    }
    
    /**
     * Consultar productos (solo lectura)
     */
    public function consultarProductos() {
        global $conn;
        
        try {
            require_once __DIR__ . '/../models/Product.php';
            
            $productos = Product::getAll($conn);
            
            // Estadísticas básicas
            $stats = [
                'total_productos' => count($productos),
                'con_stock' => 0,
                'sin_stock' => 0
            ];
            
            foreach($productos as $producto) {
                if (isset($producto->stock) && $producto->stock > 0) {
                    $stats['con_stock']++;
                } else {
                    $stats['sin_stock']++;
                }
            }
            
            include __DIR__ . '/../views/auxiliar/productos.php';
            
        } catch (Exception $e) {
            $error = "Error al consultar productos: " . $e->getMessage();
            include __DIR__ . '/../views/auxiliar/productos.php';
        }
    }
    
    /**
     * Consultar ventas (solo lectura)
     */
    public function consultarVentas() {
        global $conn;
        
        try {
            require_once __DIR__ . '/../models/Sale.php';
            
            $ventas = Sale::getAll($conn);
            
            // Estadísticas básicas
            $stats = [
                'total_ventas' => count($ventas),
                'ventas_hoy' => 0,
                'ventas_semana' => 0,
                'ventas_mes' => 0
            ];
            
            $hoy = date('Y-m-d');
            $hace_7_dias = date('Y-m-d', strtotime('-7 days'));
            $mes_actual = date('Y-m');
            
            foreach($ventas as $venta) {
                if (isset($venta->fecha_venta)) {
                    if (strpos($venta->fecha_venta, $hoy) === 0) {
                        $stats['ventas_hoy']++;
                    }
                    if ($venta->fecha_venta >= $hace_7_dias) {
                        $stats['ventas_semana']++;
                    }
                    if (strpos($venta->fecha_venta, $mes_actual) === 0) {
                        $stats['ventas_mes']++;
                    }
                }
            }
            
            include __DIR__ . '/../views/auxiliar/ventas.php';
            
        } catch (Exception $e) {
            $error = "Error al consultar ventas: " . $e->getMessage();
            include __DIR__ . '/../views/auxiliar/ventas.php';
        }
    }
    
    /**
     * Consultar reportes (solo lectura)
     */
    public function consultarReportes() {
        global $conn;
        
        try {
            require_once __DIR__ . '/../models/Report.php';
            
            $filtros = [
                'estado' => $_GET['estado'] ?? '',
                'fecha_desde' => $_GET['fecha_desde'] ?? '',
                'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
            ];
            
            $reportes = Report::getAll($conn, $filtros);
            
            // Calcular estadísticas
            $stats = [
                'total_reportes' => count($reportes),
                'reportes_pendientes' => 0,
                'reportes_completados' => 0
            ];
            
            foreach ($reportes as $reporte) {
                if (isset($reporte->estado)) {
                    if ($reporte->estado == 'pendiente') {
                        $stats['reportes_pendientes']++;
                    } elseif ($reporte->estado == 'completado') {
                        $stats['reportes_completados']++;
                    }
                }
            }
            
            include __DIR__ . '/../views/auxiliar/reportes.php';
            
        } catch (Exception $e) {
            $error = "Error al consultar reportes: " . $e->getMessage();
            $reportes = [];
            $stats = ['total_reportes' => 0, 'reportes_pendientes' => 0, 'reportes_completados' => 0];
            include __DIR__ . '/../views/auxiliar/reportes.php';
        }
    }
    
    /**
     * Modificar perfil (funcionalidad permitida para auxiliar)
     */
    public function modificarPerfil() {
        global $conn;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                require_once __DIR__ . '/../models/User.php';
                
                $num_doc = $_SESSION['user'];
                $datos = [
                    'nombres' => $_POST['nombres'] ?? '',
                    'apellidos' => $_POST['apellidos'] ?? '',
                    'correo' => $_POST['correo'] ?? '',
                    'num_cel' => $_POST['num_cel'] ?? ''
                ];
                
                // Validaciones básicas
                if (empty($datos['nombres']) || empty($datos['apellidos']) || empty($datos['correo'])) {
                    throw new Exception("Todos los campos obligatorios deben estar completos");
                }
                
                if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("El formato del correo no es válido");
                }
                
                // Si hay nueva contraseña
                if (!empty($_POST['nueva_contrasena'])) {
                    if (strlen($_POST['nueva_contrasena']) < 6) {
                        throw new Exception("La contraseña debe tener al menos 6 caracteres");
                    }
                    $datos['contrasena'] = password_hash($_POST['nueva_contrasena'], PASSWORD_DEFAULT);
                }
                
                $resultado = User::update($conn, $num_doc, $datos);
                
                if ($resultado) {
                    $success = "Perfil actualizado correctamente";
                } else {
                    $error = "Error al actualizar el perfil";
                }
                
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        // Obtener datos actuales del usuario
        try {
            require_once __DIR__ . '/../models/User.php';
            $usuario = User::getById($conn, $_SESSION['user']);
        } catch (Exception $e) {
            $error = "Error al cargar datos del usuario";
        }
        
        include __DIR__ . '/../views/auxiliar/perfil.php';
    }
    
    // Métodos auxiliares para estadísticas
    
    private function getTotalUsuarios() {
        global $conn;
        $result = $conn->query("SELECT COUNT(*) as total FROM usuarios");
        return $result->fetch_assoc()['total'];
    }
    
    private function getTotalClientes() {
        global $conn;
        $result = $conn->query("SELECT COUNT(*) as total FROM clientes");
        return $result->fetch_assoc()['total'];
    }
    
    private function getClientesActivos() {
        global $conn;
        $result = $conn->query("SELECT COUNT(*) as total FROM clientes WHERE estado = 'activo'");
        return $result->fetch_assoc()['total'] ?? 0;
    }
    
    private function getTotalProductos() {
        global $conn;
        $result = $conn->query("SELECT COUNT(*) as total FROM productos");
        return $result->fetch_assoc()['total'];
    }
    
    private function getProductosBajoStock() {
        global $conn;
        $result = $conn->query("SELECT COUNT(*) as total FROM productos WHERE CAST(stock AS UNSIGNED) < 10");
        return $result->fetch_assoc()['total'] ?? 0;
    }
    
    private function getVentasHoy() {
        global $conn;
        $result = $conn->query("SELECT COUNT(*) as total FROM ventas WHERE DATE(fecha_venta) = CURDATE()");
        return $result->fetch_assoc()['total'] ?? 0;
    }
    
    private function getVentasSemana() {
        global $conn;
        $result = $conn->query("SELECT COUNT(*) as total FROM ventas WHERE YEARWEEK(fecha_venta) = YEARWEEK(NOW())");
        return $result->fetch_assoc()['total'] ?? 0;
    }
    
    private function getVentasMes() {
        global $conn;
        $result = $conn->query("SELECT COUNT(*) as total FROM ventas WHERE MONTH(fecha_venta) = MONTH(NOW()) AND YEAR(fecha_venta) = YEAR(NOW())");
        return $result->fetch_assoc()['total'] ?? 0;
    }
    
    private function getTotalVentas() {
        global $conn;
        $result = $conn->query("SELECT COUNT(*) as total FROM ventas");
        return $result->fetch_assoc()['total'] ?? 0;
    }
    
    private function getReportesPendientes() {
        global $conn;
        $result = $conn->query("SELECT COUNT(*) as total FROM reportes WHERE estado = 'pendiente'");
        return $result->fetch_assoc()['total'] ?? 0;
    }
    
    private function getReportesCompletados() {
        global $conn;
        $result = $conn->query("SELECT COUNT(*) as total FROM reportes WHERE estado = 'completado'");
        return $result->fetch_assoc()['total'] ?? 0;
    }
    
    private function getTotalProveedores() {
        global $conn;
        $result = $conn->query("SELECT COUNT(*) as total FROM proveedores");
        return $result->fetch_assoc()['total'] ?? 0;
    }
    
    private function getTotalRutas() {
        global $conn;
        $result = $conn->query("SELECT COUNT(*) as total FROM rutas");
        return $result->fetch_assoc()['total'] ?? 0;
    }
    
    private function getAlertasActivas() {
        global $conn;
        $result = $conn->query("SELECT COUNT(*) as total FROM alertas");
        return $result->fetch_assoc()['total'] ?? 0;
    }
    
    private function getActividadReciente() {
        global $conn;
        $actividad = [];
        
        try {
            // Últimas ventas
            $ventas = $conn->query("SELECT 'venta' as tipo, fecha_venta as fecha, CONCAT('Venta #', id_ventas, ' - ', nombre) as descripcion FROM ventas ORDER BY fecha_venta DESC LIMIT 5");
            if ($ventas) {
                while ($row = $ventas->fetch_assoc()) {
                    $actividad[] = $row;
                }
            }
            
            // Últimos reportes
            $reportes = $conn->query("SELECT 'reporte' as tipo, fecha as fecha, CONCAT('Reporte: ', nombre, ' - ', estado) as descripcion FROM reportes ORDER BY fecha DESC LIMIT 5");
            if ($reportes) {
                while ($row = $reportes->fetch_assoc()) {
                    $actividad[] = $row;
                }
            }
            
            // Ordenar por fecha
            usort($actividad, function($a, $b) {
                return strtotime($b['fecha']) - strtotime($a['fecha']);
            });
            
        } catch (Exception $e) {
            // Si hay error, retornar array vacío
        }
        
        return array_slice($actividad, 0, 10);
    }
    
    private function getVentasUltimosDias($dias = 7) {
        global $conn;
        $ventas_por_dia = [];
        
        try {
            $query = "SELECT DATE(fecha_venta) as fecha, COUNT(*) as total 
                     FROM ventas 
                     WHERE fecha_venta >= DATE_SUB(NOW(), INTERVAL $dias DAY)
                     GROUP BY DATE(fecha_venta)
                     ORDER BY fecha ASC";
            
            $result = $conn->query($query);
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $ventas_por_dia[] = $row;
                }
            }
        } catch (Exception $e) {
            // Si hay error, retornar array vacío
        }
        
        return $ventas_por_dia;
    }
    
    private function getProductosMasVendidos($limit = 5) {
        global $conn;
        $productos = [];
        
        try {
            $query = "SELECT p.nombre, COUNT(v.id_ventas) as total_ventas 
                     FROM productos p
                     LEFT JOIN ventas v ON p.id_productos = v.id_productos
                     GROUP BY p.id_productos, p.nombre
                     ORDER BY total_ventas DESC
                     LIMIT $limit";
            
            $result = $conn->query($query);
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $productos[] = $row;
                }
            }
        } catch (Exception $e) {
            // Si hay error, retornar array vacío
        }
        
        return $productos;
    }
    
    private function getClientesFrecuentes($limit = 5) {
        global $conn;
        $clientes = [];
        
        try {
            $query = "SELECT c.nombre, COUNT(v.id_ventas) as total_compras 
                     FROM clientes c
                     LEFT JOIN ventas v ON c.id_clientes = v.id_clientes
                     GROUP BY c.id_clientes, c.nombre
                     ORDER BY total_compras DESC
                     LIMIT $limit";
            
            $result = $conn->query($query);
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $clientes[] = $row;
                }
            }
        } catch (Exception $e) {
            // Si hay error, retornar array vacío
        }
        
        return $clientes;
    }
}

// Procesar la solicitud si se accede directamente
if ($_SERVER['SCRIPT_NAME'] === '/RMIE/app/controllers/AuxiliarController.php') {
    $controller = new AuxiliarController();
    $controller->handleRequest();
}
?>