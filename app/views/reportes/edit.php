<?php
session_start();
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
    <title>Editar Reporte - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../public/css/reportes.css" rel="stylesheet">
</head>
<body>
    <div class="reportes-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="glassmorphism-card p-4 fade-in">
                        <div class="d-flex align-items-center mb-4">
                            <a href="../../controllers/ReportController.php?action=index" class="neo-btn neo-btn-primary me-3">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <div>
                                <h2 class="text-white mb-0">
                                    <i class="fas fa-edit me-2"></i>
                                    Editar Reporte
                                </h2>
                                <p class="text-white-50 mb-0">Modificar configuración del reporte</p>
                            </div>
                        </div>

                        <form action="../../controllers/ReportController.php?action=update" method="POST" class="slide-up">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($report['id_reportes']); ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label text-white">
                                        <i class="fas fa-heading me-2"></i>Título del Reporte
                                    </label>
                                    <input type="text" id="title" name="title" 
                                           class="glass-form-control" 
                                           value="<?php echo htmlspecialchars($report['title']); ?>"
                                           required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="type" class="form-label text-white">
                                        <i class="fas fa-tag me-2"></i>Tipo de Reporte
                                    </label>
                                    <select id="type" name="type" class="glass-select" required>
                                        <option value="ventas" <?php echo $report['type'] === 'ventas' ? 'selected' : ''; ?>>Ventas</option>
                                        <option value="inventario" <?php echo $report['type'] === 'inventario' ? 'selected' : ''; ?>>Inventario</option>
                                        <option value="clientes" <?php echo $report['type'] === 'clientes' ? 'selected' : ''; ?>>Clientes</option>
                                        <option value="proveedores" <?php echo $report['type'] === 'proveedores' ? 'selected' : ''; ?>>Proveedores</option>
                                        <option value="productos" <?php echo $report['type'] === 'productos' ? 'selected' : ''; ?>>Productos</option>
                                        <option value="financiero" <?php echo $report['type'] === 'financiero' ? 'selected' : ''; ?>>Financiero</option>
                                        <option value="operacional" <?php echo $report['type'] === 'operacional' ? 'selected' : ''; ?>>Operacional</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label text-white">
                                        <i class="fas fa-calendar-alt me-2"></i>Fecha Inicio
                                    </label>
                                    <input type="date" id="start_date" name="start_date" 
                                           class="glass-form-control" 
                                           value="<?php echo $report['start_date']; ?>"
                                           required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label text-white">
                                        <i class="fas fa-calendar-alt me-2"></i>Fecha Fin
                                    </label>
                                    <input type="date" id="end_date" name="end_date" 
                                           class="glass-form-control" 
                                           value="<?php echo $report['end_date']; ?>"
                                           required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label text-white">
                                        <i class="fas fa-info-circle me-2"></i>Estado
                                    </label>
                                    <select id="status" name="status" class="glass-select" required>
                                        <option value="draft" <?php echo $report['status'] === 'draft' ? 'selected' : ''; ?>>Borrador</option>
                                        <option value="pending" <?php echo $report['status'] === 'pending' ? 'selected' : ''; ?>>Pendiente</option>
                                        <option value="completed" <?php echo $report['status'] === 'completed' ? 'selected' : ''; ?>>Completado</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="priority" class="form-label text-white">
                                        <i class="fas fa-exclamation-circle me-2"></i>Prioridad
                                    </label>
                                    <select id="priority" name="priority" class="glass-select" required>
                                        <option value="low" <?php echo $report['priority'] === 'low' ? 'selected' : ''; ?>>Baja</option>
                                        <option value="medium" <?php echo $report['priority'] === 'medium' ? 'selected' : ''; ?>>Media</option>
                                        <option value="high" <?php echo $report['priority'] === 'high' ? 'selected' : ''; ?>>Alta</option>
                                        <option value="urgent" <?php echo $report['priority'] === 'urgent' ? 'selected' : ''; ?>>Urgente</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label text-white">
                                    <i class="fas fa-align-left me-2"></i>Descripción
                                </label>
                                <textarea id="description" name="description" 
                                          class="glass-form-control" 
                                          rows="4"><?php echo htmlspecialchars($report['description']); ?></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="parameters" class="form-label text-white">
                                    <i class="fas fa-cogs me-2"></i>Parámetros Adicionales
                                </label>
                                <textarea id="parameters" name="parameters" 
                                          class="glass-form-control" 
                                          rows="3"><?php echo htmlspecialchars($report['parameters'] ?? '{}'); ?></textarea>
                                <small class="text-white-50">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Formato: {"filtro1": "valor1", "filtro2": "valor2"}
                                </small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    <a href="../../controllers/ReportController.php?action=show&id=<?php echo $report['id_reportes']; ?>" 
                                       class="neo-btn neo-btn-info">
                                        <i class="fas fa-eye me-2"></i>Ver Reporte
                                    </a>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <a href="../../controllers/ReportController.php?action=index" class="neo-btn neo-btn-danger">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="neo-btn neo-btn-success">
                                        <i class="fas fa-save me-2"></i>Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación de fechas
        document.getElementById('start_date').addEventListener('change', function() {
            const startDate = new Date(this.value);
            const endDateInput = document.getElementById('end_date');
            const endDate = new Date(endDateInput.value);
            
            if (endDate < startDate) {
                endDateInput.value = this.value;
            }
            endDateInput.min = this.value;
        });

        document.getElementById('end_date').addEventListener('change', function() {
            const endDate = new Date(this.value);
            const startDateInput = document.getElementById('start_date');
            const startDate = new Date(startDateInput.value);
            
            if (startDate > endDate) {
                startDateInput.value = this.value;
            }
            startDateInput.max = this.value;
        });

        // Validación de JSON en parámetros
        document.getElementById('parameters').addEventListener('blur', function() {
            try {
                if (this.value.trim() && this.value.trim() !== '{}') {
                    JSON.parse(this.value);
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            } catch (e) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
                alert('Formato JSON inválido en parámetros');
            }
        });
    </script>
</body>
</html>
