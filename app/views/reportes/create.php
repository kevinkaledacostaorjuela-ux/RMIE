<!DOCTYPE html>
<html>
<head>
    <title>Crear Reporte</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Reporte - RMIE</title>
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
                                    <i class="fas fa-plus-circle me-2"></i>
                                    Crear Nuevo Reporte
                                </h2>
                                <p class="text-white-50 mb-0">Generar reporte personalizado del sistema</p>
                            </div>
                        </div>

                        <form action="../../controllers/ReportController.php?action=store" method="POST" class="slide-up">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label text-white">
                                        <i class="fas fa-heading me-2"></i>Título del Reporte
                                    </label>
                                    <input type="text" id="title" name="title" 
                                           class="glass-form-control" 
                                           placeholder="Ej: Reporte de Ventas Mensual"
                                           required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="type" class="form-label text-white">
                                        <i class="fas fa-tag me-2"></i>Tipo de Reporte
                                    </label>
                                    <select id="type" name="type" class="glass-select" required>
                                        <option value="" disabled selected>Seleccionar tipo...</option>
                                        <option value="ventas">Ventas</option>
                                        <option value="inventario">Inventario</option>
                                        <option value="clientes">Clientes</option>
                                        <option value="proveedores">Proveedores</option>
                                        <option value="productos">Productos</option>
                                        <option value="financiero">Financiero</option>
                                        <option value="operacional">Operacional</option>
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
                                           value="<?php echo date('Y-m-01'); ?>"
                                           required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label text-white">
                                        <i class="fas fa-calendar-alt me-2"></i>Fecha Fin
                                    </label>
                                    <input type="date" id="end_date" name="end_date" 
                                           class="glass-form-control" 
                                           value="<?php echo date('Y-m-t'); ?>"
                                           required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label text-white">
                                        <i class="fas fa-info-circle me-2"></i>Estado
                                    </label>
                                    <select id="status" name="status" class="glass-select" required>
                                        <option value="draft">Borrador</option>
                                        <option value="pending" selected>Pendiente</option>
                                        <option value="completed">Completado</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="priority" class="form-label text-white">
                                        <i class="fas fa-exclamation-circle me-2"></i>Prioridad
                                    </label>
                                    <select id="priority" name="priority" class="glass-select" required>
                                        <option value="low">Baja</option>
                                        <option value="medium" selected>Media</option>
                                        <option value="high">Alta</option>
                                        <option value="urgent">Urgente</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label text-white">
                                    <i class="fas fa-align-left me-2"></i>Descripción
                                </label>
                                <textarea id="description" name="description" 
                                          class="glass-form-control" 
                                          rows="4" 
                                          placeholder="Describe el propósito y alcance del reporte..."></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="parameters" class="form-label text-white">
                                    <i class="fas fa-cogs me-2"></i>Parámetros Adicionales
                                </label>
                                <textarea id="parameters" name="parameters" 
                                          class="glass-form-control" 
                                          rows="3" 
                                          placeholder="Parámetros específicos en formato JSON (opcional)">{}</textarea>
                                <small class="text-white-50">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Formato: {"filtro1": "valor1", "filtro2": "valor2"}
                                </small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="auto_generate" name="auto_generate" value="1">
                                    <label class="form-check-label text-white" for="auto_generate">
                                        <i class="fas fa-magic me-2"></i>Generar automáticamente
                                    </label>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <a href="../../controllers/ReportController.php?action=index" class="neo-btn neo-btn-danger">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="neo-btn neo-btn-success">
                                        <i class="fas fa-save me-2"></i>Crear Reporte
                                    </button>
                                </div>
                            </div>
                        </form>

                        <a href="/RMIE/app/controllers/MainController.php?action=dashboard" class="neo-btn neo-btn-secondary mb-3">
                            <i class="fas fa-home"></i> Volver al Dashboard
                        </a>
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

        // Sugerencias automáticas según el tipo
        document.getElementById('type').addEventListener('change', function() {
            const descriptions = {
                'ventas': 'Análisis detallado de transacciones de venta, ingresos y tendencias del período seleccionado.',
                'inventario': 'Estado actual del inventario, rotación de productos y alertas de stock.',
                'clientes': 'Segmentación de clientes, comportamiento de compra y análisis de retención.',
                'proveedores': 'Evaluación de proveedores, tiempos de entrega y calidad de servicio.',
                'productos': 'Performance de productos, más vendidos y análisis de rentabilidad.',
                'financiero': 'Estado financiero, flujo de caja y indicadores económicos clave.',
                'operacional': 'Eficiencia operativa, procesos y métricas de rendimiento.'
            };
            
            if (descriptions[this.value]) {
                document.getElementById('description').value = descriptions[this.value];
            }
        });
    </script>
</body>
</html>
    <form method="POST" action="create.php">
            <label>Nombre:</label>
            <input type="text" name="nombre" required><br>
            <label>Descripción:</label>
            <input type="text" name="descripcion" required><br>
            <label>Producto:</label>
            <input type="text" name="id_productos" required><br>
            <button type="submit" class="btn-categorias">Guardar</button>
        </form>
        <a href="index.php" class="btn-categorias">Volver al listado</a>
    </div>
</body>
</html>
