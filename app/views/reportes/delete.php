<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../index.php');
    exit();
}

require_once '../../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: ../../controllers/ReportController.php?action=index');
    exit();
}

// Crear conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "rmie_db");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT *, 
    nombre as title, 
    descripcion as description, 
    fecha as start_date, 
    fecha as end_date, 
    estado as status, 
    'medium' as priority,
    fecha_creacion as created_at,
    fecha_actualizacion as updated_at,
    tipo as type
    FROM reportes WHERE id_reportes = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$report = $result->fetch_assoc();

if (!$report) {
    header('Location: ../../controllers/ReportController.php?action=index');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Reporte - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../public/css/reportes.css" rel="stylesheet">
</head>
<body>
    <div class="reportes-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="glassmorphism-card p-4 fade-in">
                        <div class="text-center mb-4">
                            <div class="stats-icon text-danger mb-3">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h2 class="text-white mb-2">
                                <i class="fas fa-trash me-2"></i>
                                Eliminar Reporte
                            </h2>
                            <p class="text-white-50 mb-0">Esta acción no se puede deshacer</p>
                        </div>

                        <div class="slide-up">
                            <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <div>
                                    ¿Está seguro de que desea eliminar permanentemente este reporte?
                                </div>
                            </div>

                            <div class="glassmorphism-card p-3 mb-4">
                                <h5 class="text-white mb-3">
                                    <i class="fas fa-file-alt me-2"></i>
                                    Detalles del Reporte
                                </h5>
                                
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <strong class="text-white-50">Título:</strong>
                                        <span class="text-white ms-2"><?php echo htmlspecialchars($report['title']); ?></span>
                                    </div>
                                    
                                    <div class="col-md-6 mb-2">
                                        <strong class="text-white-50">Tipo:</strong>
                                        <span class="text-white ms-2"><?php echo ucfirst($report['type']); ?></span>
                                    </div>
                                    
                                    <div class="col-md-6 mb-2">
                                        <strong class="text-white-50">Estado:</strong>
                                        <span class="status-badge badge-<?php echo $report['status'] === 'completed' ? 'active' : ($report['status'] === 'pending' ? 'pending' : 'inactive'); ?>">
                                            <?php 
                                            $statusLabels = [
                                                'draft' => 'Borrador',
                                                'pending' => 'Pendiente', 
                                                'completed' => 'Completado'
                                            ];
                                            echo $statusLabels[$report['status']] ?? $report['status'];
                                            ?>
                                        </span>
                                    </div>
                                    
                                    <div class="col-md-6 mb-2">
                                        <strong class="text-white-50">Prioridad:</strong>
                                        <span class="text-white ms-2">
                                            <?php 
                                            $priorityLabels = [
                                                'low' => 'Baja',
                                                'medium' => 'Media',
                                                'high' => 'Alta',
                                                'urgent' => 'Urgente'
                                            ];
                                            echo $priorityLabels[$report['priority']] ?? $report['priority'];
                                            ?>
                                        </span>
                                    </div>
                                    
                                    <div class="col-md-6 mb-2">
                                        <strong class="text-white-50">Creado:</strong>
                                        <span class="text-white ms-2"><?php echo date('d/m/Y H:i', strtotime($report['created_at'])); ?></span>
                                    </div>
                                    
                                    <?php if (!empty($report['description'])): ?>
                                    <div class="col-12 mt-2">
                                        <strong class="text-white-50">Descripción:</strong>
                                        <p class="text-white mt-1 mb-0"><?php echo htmlspecialchars($report['description']); ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center gap-3">
                                <a href="../../controllers/ReportController.php?action=index" class="neo-btn neo-btn-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Cancelar
                                </a>
                                
                                <form action="../../controllers/ReportController.php?action=delete" method="POST" class="d-inline">
                                    <input type="hidden" name="id" value="<?php echo $report['id_reportes']; ?>">
                                    <button type="submit" class="neo-btn neo-btn-danger" onclick="return confirm('⚠️ ATENCIÓN: Esta acción eliminará permanentemente el reporte &quot;<?php echo htmlspecialchars($report['nombre'] ?? $report['title']); ?>&quot; y todos sus datos asociados.\\n\\n¿Está absolutamente seguro de que desea continuar?');">
                                        <i class="fas fa-trash me-2"></i>Eliminar Permanentemente
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-focus en el botón cancelar para evitar eliminaciones accidentales
        document.addEventListener('DOMContentLoaded', function() {
            const cancelButton = document.querySelector('.neo-btn-primary');
            if (cancelButton) {
                cancelButton.focus();
            }
        });
    </script>
</body>
</html>
