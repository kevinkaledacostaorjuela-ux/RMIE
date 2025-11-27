<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

require_once __DIR__ . '/../../models/Local.php';

$locales = Local::getAll($conn);

// Aplicar filtros adicionales en PHP
if (!empty($filtros)) {
    $locales = array_filter($locales, function($l) use ($filtros) {
        // Filtro por nombre
        if (!empty($filtros['nombre'])) {
            if (stripos($l->nombre_local ?? '', $filtros['nombre']) === false) {
                return false;
            }
        }
        
        // Filtro por ubicación/dirección
        if (!empty($filtros['ubicacion'])) {
            if (stripos($l->direccion ?? '', $filtros['ubicacion']) === false && 
                stripos($l->localidad ?? '', $filtros['ubicacion']) === false) {
                return false;
            }
        }
        
        // Filtro por estado
        if (!empty($filtros['estado']) && strtolower($l->estado ?? 'activo') !== strtolower($filtros['estado'])) {
            return false;
        }
        
        // Filtro por fecha desde
        if (!empty($filtros['fecha_desde']) && !empty($l->fecha_registro)) {
            if (strtotime($l->fecha_registro) < strtotime($filtros['fecha_desde'])) {
                return false;
            }
        }
        
        // Filtro por fecha hasta
        if (!empty($filtros['fecha_hasta']) && !empty($l->fecha_registro)) {
            if (strtotime($l->fecha_registro) > strtotime($filtros['fecha_hasta'] . ' 23:59:59')) {
                return false;
            }
        }
        
        return true;
    });
}

$totalLocales = count($locales);
$localesActivos = count(array_filter($locales, fn($l) => strtolower($l->estado ?? 'activo') === 'activo'));
$localesInactivos = $totalLocales - $localesActivos;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Locales - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .report-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            margin: 30px auto;
            max-width: 1400px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #30cfd0;
        }
        .report-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #330867;
        }
        .stat-card {
            background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
        }
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .data-table {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .table thead {
            background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
            color: white;
        }
        .table thead th {
            border: none;
            padding: 15px;
        }
        .badge-activo {
            background: #38ef7d;
            color: #000;
        }
        .badge-inactivo {
            background: #eb3349;
            color: #fff;
        }
        .btn-back {
            background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            color: white;
        }
        .filter-box {
            background: rgba(48, 207, 208, 0.1);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 2px solid rgba(48, 207, 208, 0.3);
        }
        .filter-box h4 {
            color: #330867;
            margin-bottom: 20px;
        }
        .filter-box .form-control, .filter-box .form-select {
            border-radius: 10px;
            border: 2px solid rgba(48, 207, 208, 0.3);
        }
        .filter-box .form-control:focus, .filter-box .form-select:focus {
            border-color: #30cfd0;
            box-shadow: 0 0 0 0.2rem rgba(48, 207, 208, 0.25);
        }
        .btn-filter {
            background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 10px;
            font-weight: 600;
        }
        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            color: white;
        }
        .btn-clear {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 10px;
            font-weight: 600;
        }
        .btn-clear:hover {
            background: #5a6268;
            color: white;
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <h1 class="report-title"><i class="fas fa-store"></i> Reporte de Locales</h1>
            <div>
                <button onclick="exportarPDF()" class="btn-back" style="margin-right: 10px;"><i class="fas fa-file-pdf"></i> Descargar PDF</button>
                <button onclick="exportarExcel()" class="btn-back" style="margin-right: 10px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);"><i class="fas fa-file-excel"></i> Descargar Excel</button>
                <a href="/RMIE/app/controllers/ReportController.php?action=index" class="btn-back"><i class="fas fa-arrow-left"></i> Volver</a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-value"><?= $totalLocales ?></div>
                    <div class="stat-label">Total Locales</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%)">
                    <div class="stat-value"><?= $localesActivos ?></div>
                    <div class="stat-label">Activos</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%)">
                    <div class="stat-value"><?= $localesInactivos ?></div>
                    <div class="stat-label">Inactivos</div>
                </div>
            </div>
        </div>

        <!-- Formulario de Filtros -->
        <div class="filter-box">
            <h4><i class="fas fa-filter"></i> Filtros de Búsqueda</h4>
            <form method="GET" action="/RMIE/app/controllers/ReportController.php">
                <input type="hidden" name="action" value="locales">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label"><i class="fas fa-store"></i> Nombre</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Buscar por nombre..." value="<?= htmlspecialchars($filtros['nombre'] ?? '') ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"><i class="fas fa-map-marker-alt"></i> Ubicación</label>
                        <input type="text" name="ubicacion" class="form-control" placeholder="Filtrar por ubicación..." value="<?= htmlspecialchars($filtros['ubicacion'] ?? '') ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label"><i class="fas fa-toggle-on"></i> Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos</option>
                            <option value="activo" <?= ($filtros['estado'] ?? '') === 'activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="inactivo" <?= ($filtros['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label"><i class="fas fa-calendar"></i> Desde</label>
                        <input type="date" name="fecha_desde" class="form-control" value="<?= htmlspecialchars($filtros['fecha_desde'] ?? '') ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label"><i class="fas fa-calendar"></i> Hasta</label>
                        <input type="date" name="fecha_hasta" class="form-control" value="<?= htmlspecialchars($filtros['fecha_hasta'] ?? '') ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-filter"><i class="fas fa-search"></i> Filtrar</button>
                        <a href="/RMIE/app/controllers/ReportController.php?action=locales" class="btn btn-clear"><i class="fas fa-times"></i> Limpiar Filtros</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="data-table">
            <h3 class="mb-3"><i class="fas fa-table"></i> Listado de Locales</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Encargado</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($locales as $local): ?>
                        <tr>
                            <td><?= htmlspecialchars($local->id_locales) ?></td>
                            <td><?= htmlspecialchars($local->nombre_local ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($local->direccion ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($local->cel_local ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($local->localidad ?? 'N/A') ?></td>
                            <td>
                                <span class="badge <?= strtolower($local->estado ?? 'activo') === 'activo' ? 'badge-activo' : 'badge-inactivo' ?>">
                                    <?= htmlspecialchars($local->estado ?? 'Activo') ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        function exportarPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            doc.setFontSize(18);
            doc.text('Reporte de Locales', 14, 20);
            
            const tabla = document.querySelector('.table');
            doc.autoTable({
                html: tabla,
                startY: 30,
                theme: 'grid',
                headStyles: { fillColor: [239, 68, 68] }
            });
            
            doc.save('reporte_locales.pdf');
        }
        
        function exportarExcel() {
            const wb = XLSX.utils.book_new();
            const tabla = document.querySelector('.table');
            const ws = XLSX.utils.table_to_sheet(tabla);
            
            ws['!cols'] = [
                {wch: 12}, // ID
                {wch: 35}, // Nombre
                {wch: 45}, // Dirección
                {wch: 18}, // Teléfono
                {wch: 30}, // Localidad
                {wch: 18}  // Estado
            ];
            
            XLSX.utils.book_append_sheet(wb, ws, "Locales");
            const fecha = new Date().toLocaleDateString('es-ES').replace(/\//g, '-');
            XLSX.writeFile(wb, `Reporte_Locales_${fecha}.xlsx`);
        }
    </script>
</body>
</html>
