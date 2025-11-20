<?php
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../../config/db.php';

class ReportController {
    
    public function handleRequest() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user'])) {
            header('Location: /RMIE/index.php');
            exit();
        }
        
        $action = $_GET['action'] ?? $_GET['accion'] ?? 'index';
        
        switch ($action) {
            case 'index':
                $this->index();
                break;
            case 'create':
                $this->create();
                break;
            case 'store':
                $this->store();
                break;
            case 'edit':
                $this->edit();
                break;
            case 'update':
                $this->update();
                break;
            case 'delete':
                $this->delete();
                break;
            case 'generate':
                $this->generate();
                break;
            case 'export':
                $this->export();
                break;
            case 'usuarios':
                $this->reporteUsuarios();
                break;
            case 'categorias':
                $this->reporteCategorias();
                break;
            case 'subcategorias':
                $this->reporteSubcategorias();
                break;
            case 'productos':
                $this->reporteProductos();
                break;
            case 'proveedores':
                $this->reporteProveedores();
                break;
            case 'clientes':
                $this->reporteClientes();
                break;
            case 'ventas':
                $this->reporteVentas();
                break;
            case 'rutas':
                $this->reporteRutas();
                break;
            case 'locales':
                $this->reporteLocales();
                break;
            case 'alertas':
                $this->reporteAlertas();
                break;
            default:
                $this->index();
                break;
        }
    }
    
    public function index() {
        global $conn;
        
        try {
            require_once __DIR__ . '/../utils/FilterHelper.php';
            
            // Definir reglas de filtro
            $filterRules = [
                'buscar' => ['type' => 'text', 'options' => ['max_length' => 100]],
                'estado' => ['type' => 'select', 'options' => ['allowed_values' => ['activo', 'inactivo', 'pendiente', 'completado', 'cancelado']]],
                'fecha_inicio' => ['type' => 'date'],
                'fecha_fin' => ['type' => 'date'],
                'tipo' => ['type' => 'select', 'options' => ['allowed_values' => ['venta', 'inventario', 'cliente', 'proveedor', 'financiero']]]
            ];
            
            // Procesar filtros del GET
            $filtros = FilterHelper::processFilters($_GET, $filterRules);
            
            // Validar rango de fechas
            if (!empty($filtros['fecha_inicio']) && !empty($filtros['fecha_fin'])) {
                $dateRange = FilterHelper::validateDateRange($filtros['fecha_inicio'], $filtros['fecha_fin']);
                if (!empty($dateRange)) {
                    $filtros['fecha_inicio'] = $dateRange['fecha_inicio'] ?? $filtros['fecha_inicio'];
                    $filtros['fecha_fin'] = $dateRange['fecha_fin'] ?? $filtros['fecha_fin'];
                }
            }
            
            $reportes = Report::getAll($conn, $filtros);
            $stats = Report::getStats($conn);
            
        } catch (Exception $e) {
            error_log("Error en ReportController::index: " . $e->getMessage());
            // Fallback con filtros básicos sin validación
            $filtros = [
                'buscar' => $_GET['buscar'] ?? '',
                'estado' => $_GET['estado'] ?? '',
                'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
                'fecha_fin' => $_GET['fecha_fin'] ?? '',
                'tipo' => $_GET['tipo'] ?? ''
            ];
            $reportes = Report::getAll($conn, $filtros);
            $stats = Report::getStats($conn);
        }
        
        include __DIR__ . '/../views/reportes/index.php';
    }
    
    public function create() {
        global $conn;
        
        include __DIR__ . '/../views/reportes/create.php';
    }
      public function store() {
        global $conn;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar la fecha de creación
            $fecha_creacion = '';
            if (!empty($_POST['fecha_creacion'])) {
                // Usar la fecha especificada por el usuario
                $fecha_creacion = $_POST['fecha_creacion'] . ' ' . date('H:i:s');
            }
            
            $data = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'tipo' => $_POST['tipo'] ?? 'general',
                'estado' => $_POST['estado'] ?? 'activo',
                'parametros' => json_encode($_POST['parametros'] ?? []),
                'fecha_creacion' => $fecha_creacion
            ];
            
            $result = Report::create($conn, $data);
            
            if ($result) {
                $_SESSION['success'] = 'Reporte creado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al crear el reporte';
            }
            
            header('Location: /RMIE/app/controllers/ReportController.php?action=index');
            exit();
        }
    }
    
    public function edit() {
        global $conn;
        
        $id = $_GET['id'] ?? 0;
        $reporte = Report::getById($conn, $id);
        
        if (!$reporte) {
            $_SESSION['error'] = 'Reporte no encontrado';
            header('Location: /RMIE/app/controllers/ReportController.php?action=index');
            exit();
        }
        
        // Procesar parámetros para la vista
        $parametros = json_decode($reporte['parametros'] ?? '{}', true);
        
        include __DIR__ . '/../views/reportes/edit.php';
    }
    
    public function update() {
        global $conn;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? 0; // Obtener ID de GET en lugar de POST
            
            if (!$id) {
                $_SESSION['error'] = 'ID de reporte no especificado';
                header('Location: /RMIE/app/controllers/ReportController.php?action=index');
                exit();
            }
            
            $data = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'tipo' => $_POST['tipo'] ?? 'general',
                'estado' => $_POST['estado'] ?? 'activo',
                'parametros' => json_encode($_POST['parametros'] ?? [])
            ];
            
            $result = Report::update($conn, $id, $data);
            
            if ($result) {
                $_SESSION['success'] = 'Reporte actualizado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al actualizar el reporte';
            }
            
            header('Location: /RMIE/app/controllers/ReportController.php?action=index');
            exit();
        }
    }
    
    public function delete() {
        // Verificar sesión activa
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user']) || !isset($_SESSION['rol'])) {
            $_SESSION['error'] = 'Debe iniciar sesión para realizar esta acción';
            header('Location: /RMIE/index.php');
            exit();
        }
        
        // Verificar si el rol es coordinador y restringir eliminación
        if ($_SESSION['rol'] === 'coordinador') {
            $_SESSION['error'] = 'El rol de coordinador no tiene permisos para eliminar reportes por políticas de seguridad';
            header('Location: ReportController.php?action=index');
            exit();
        }
        
        global $conn;
        
        $id = $_GET['id'] ?? $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['error'] = 'ID de reporte no válido';
            header('Location: ReportController.php?action=index');
            exit();
        }
        
        // Verificar si existe el reporte
        $reporte = Report::getById($conn, $id);
        
        if (!$reporte) {
            $_SESSION['error'] = 'Reporte no encontrado';
            header('Location: ReportController.php?action=index');
            exit();
        }
        
        // Si viene con confirmación (confirmado por JavaScript), procesar eliminación
        if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
            $result = Report::delete($conn, $id);
            
            if ($result) {
                $_SESSION['success'] = 'Reporte eliminado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al eliminar el reporte';
            }
            
            header('Location: ReportController.php?action=index');
            exit();
        }
        
        // Si no viene confirmación, mostrar página de confirmación
        include __DIR__ . '/../views/reportes/delete.php';
    }
    
    public function generate() {
        global $conn;
        
        $id = $_GET['id'] ?? 0;
        $reporte = Report::getById($conn, $id);
        
        if (!$reporte) {
            $_SESSION['error'] = 'Reporte no encontrado';
            header('Location: ReportController.php?action=index');
            exit();
        }
        
        $parametros = json_decode($reporte['parametros'] ?? '{}', true);
        $data = Report::generateReportData($conn, $reporte['tipo'], $parametros);
        
        include __DIR__ . '/../views/reportes/generate.php';
    }
    
    public function export() {
        global $conn;
        
        $id = $_GET['id'] ?? 0;
        $format = $_GET['format'] ?? 'pdf';
        
        $reporte = Report::getById($conn, $id);
        
        if (!$reporte) {
            $_SESSION['error'] = 'Reporte no encontrado';
            header('Location: ReportController.php?action=index');
            exit();
        }
        
        $parametros = json_decode($reporte['parametros'] ?? '{}', true);
        $data = Report::generateReportData($conn, $reporte['tipo'], $parametros);
        
        switch ($format) {
            case 'pdf':
                $this->exportPDF($reporte, $data);
                break;
            case 'excel':
                $this->exportExcel($reporte, $data);
                break;
            case 'csv':
                $this->exportCSV($reporte, $data);
                break;
            default:
                $this->exportPDF($reporte, $data);
                break;
        }
    }
    
    private function exportPDF($reporte, $data) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="reporte_' . $reporte['nombre'] . '.pdf"');
        echo "PDF Export functionality would be implemented here";
    }
    
    private function exportExcel($reporte, $data) {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="reporte_' . $reporte['nombre'] . '.xlsx"');
        echo "Excel Export functionality would be implemented here";
    }
    
    private function exportCSV($reporte, $data) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="reporte_' . $reporte['nombre'] . '.csv"');
        echo "CSV Export functionality would be implemented here";
    }
    
    // Métodos para reportes individuales de los 11 módulos
    public function reporteUsuarios() {
        global $conn;
        
        // Obtener filtros
        $filtros = [
            'nombre' => $_GET['nombre'] ?? '',
            'rol' => $_GET['rol'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
        ];
        
        include __DIR__ . '/../views/reportes/usuarios.php';
    }
    
    public function reporteCategorias() {
        global $conn;
        
        // Obtener filtros
        $filtros = [
            'nombre' => $_GET['nombre'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
        ];
        
        include __DIR__ . '/../views/reportes/categorias.php';
    }
    
    public function reporteSubcategorias() {
        global $conn;
        
        // Obtener filtros
        $filtros = [
            'nombre' => $_GET['nombre'] ?? '',
            'categoria' => $_GET['categoria'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
        ];
        
        include __DIR__ . '/../views/reportes/subcategorias.php';
    }
    
    public function reporteProductos() {
        global $conn;
        
        // Obtener filtros
        $filtros = [
            'nombre' => $_GET['nombre'] ?? '',
            'categoria' => $_GET['categoria'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'stock_min' => $_GET['stock_min'] ?? '',
            'stock_max' => $_GET['stock_max'] ?? '',
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
        ];
        
        include __DIR__ . '/../views/reportes/productos.php';
    }
    
    public function reporteProveedores() {
        global $conn;
        
        // Obtener filtros
        $filtros = [
            'nombre' => $_GET['nombre'] ?? '',
            'ubicacion' => $_GET['ubicacion'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
        ];
        
        include __DIR__ . '/../views/reportes/proveedores.php';
    }
    
    public function reporteClientes() {
        global $conn;
        
        // Obtener filtros
        $filtros = [
            'nombre' => $_GET['nombre'] ?? '',
            'ciudad' => $_GET['ciudad'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
        ];
        
        include __DIR__ . '/../views/reportes/clientes.php';
    }
    
    public function reporteVentas() {
        global $conn;
        
        // Obtener filtros
        $filtros = [
            'cliente' => $_GET['cliente'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'monto_min' => $_GET['monto_min'] ?? '',
            'monto_max' => $_GET['monto_max'] ?? '',
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
        ];
        
        include __DIR__ . '/../views/reportes/ventas.php';
    }
    
    public function reporteRutas() {
        global $conn;
        
        // Obtener filtros
        $filtros = [
            'nombre' => $_GET['nombre'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
        ];
        
        include __DIR__ . '/../views/reportes/rutas.php';
    }
    
    public function reporteLocales() {
        global $conn;
        
        // Obtener filtros
        $filtros = [
            'nombre' => $_GET['nombre'] ?? '',
            'ubicacion' => $_GET['ubicacion'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
        ];
        
        include __DIR__ . '/../views/reportes/locales.php';
    }
    
    public function reporteAlertas() {
        global $conn;
        
        // Obtener filtros
        $filtros = [
            'tipo' => $_GET['tipo'] ?? '',
            'prioridad' => $_GET['prioridad'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
        ];
        
        include __DIR__ . '/../views/reportes/alertas.php';
    }
}

// Manejo de la solicitud
if (basename($_SERVER['PHP_SELF']) === 'ReportController.php') {
    $controller = new ReportController();
    $controller->handleRequest();
}
?>
