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
            default:
                $this->index();
                break;
        }
    }
    
    public function index() {
        global $conn;
        
        $filtros = [
            'buscar' => $_GET['buscar'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
            'fecha_fin' => $_GET['fecha_fin'] ?? '',
            'tipo' => $_GET['tipo'] ?? ''
        ];
        
        $reportes = Report::getAll($conn, $filtros);
        $stats = Report::getStats($conn);
        
        include __DIR__ . '/../views/reportes/index.php';
    }
    
    public function create() {
        global $conn;
        
        include __DIR__ . '/../views/reportes/create.php';
    }
    
    public function store() {
        global $conn;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'tipo' => $_POST['tipo'] ?? 'general',
                'estado' => $_POST['estado'] ?? 'activo',
                'parametros' => json_encode($_POST['parametros'] ?? [])
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
            header('Location: /RMIE/app/controllers/ReportController.php?accion=index');
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
                header('Location: /RMIE/app/controllers/ReportController.php?accion=index');
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
            
            header('Location: /RMIE/app/controllers/ReportController.php?accion=index');
            exit();
        }
    }
    
    public function delete() {
        global $conn;
        
        $id = $_GET['id'] ?? 0;
        $reporte = Report::getById($conn, $id);
        
        if (!$reporte) {
            $_SESSION['error'] = 'Reporte no encontrado';
            header('Location: ReportController.php?action=index');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = Report::delete($conn, $id);
            
            if ($result) {
                $_SESSION['success'] = 'Reporte eliminado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al eliminar el reporte';
            }
            
            header('Location: /RMIE/app/controllers/ReportController.php?action=index');
            exit();
        }
        
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
}

// Manejo de la solicitud
if (basename($_SERVER['PHP_SELF']) === 'ReportController.php') {
    $controller = new ReportController();
    $controller->handleRequest();
}
?>
