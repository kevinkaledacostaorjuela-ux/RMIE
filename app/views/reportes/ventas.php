<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

require_once __DIR__ . '/../../models/Sale.php';

$ventas = Sale::getAll($conn);

// Aplicar filtros adicionales en PHP
if (!empty($filtros)) {
    $ventas = array_filter($ventas, function($v) use ($filtros) {
        // Filtro por cliente
        if (!empty($filtros['cliente'])) {
            if (stripos($v->cliente_nombre ?? '', $filtros['cliente']) === false) {
                return false;
            }
        }
        
        // Filtro por estado
        if (!empty($filtros['estado']) && strtolower($v->estado ?? '') !== strtolower($filtros['estado'])) {
            return false;
        }
        
        // Filtro por monto mínimo
        if (!empty($filtros['monto_min']) && ($v->total ?? 0) < (float)$filtros['monto_min']) {
            return false;
        }
        
        // Filtro por monto máximo
        if (!empty($filtros['monto_max']) && ($v->total ?? 0) > (float)$filtros['monto_max']) {
            return false;
        }
        
        // Filtro por fecha desde
        if (!empty($filtros['fecha_desde']) && !empty($v->fecha_venta)) {
            if (strtotime($v->fecha_venta) < strtotime($filtros['fecha_desde'])) {
                return false;
            }
        }
        
        // Filtro por fecha hasta
        if (!empty($filtros['fecha_hasta']) && !empty($v->fecha_venta)) {
            if (strtotime($v->fecha_venta) > strtotime($filtros['fecha_hasta'] . ' 23:59:59')) {
                return false;
            }
        }
        
        return true;
    });
}

$totalVentas = count($ventas);
$ventasCompletadas = count(array_filter($ventas, fn($v) => strtolower($v->estado ?? '') === 'completada'));
$ventasPendientes = count(array_filter($ventas, fn($v) => strtolower($v->estado ?? '') === 'pendiente'));
$totalIngresos = array_sum(array_map(fn($v) => $v->total ?? 0, $ventas));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
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
            border-bottom: 3px solid #ff9a9e;
        }
        .report-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ff9a9e;
        }
        .stat-card {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
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
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            color: white;
        }
        .table thead th {
            border: none;
            padding: 15px;
        }
        .badge-completada {
            background: #38ef7d;
            color: #000;
        }
        .badge-pendiente {
            background: #fee140;
            color: #000;
        }
        .badge-cancelada {
            background: #eb3349;
            color: #fff;
        }
        .btn-back {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
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
            background: rgba(255, 154, 158, 0.1);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 2px solid rgba(255, 154, 158, 0.3);
        }
        .filter-box h4 {
            color: #ff9a9e;
            margin-bottom: 20px;
        }
        .filter-box .form-control, .filter-box .form-select {
            border-radius: 10px;
            border: 2px solid rgba(255, 154, 158, 0.3);
        }
        .filter-box .form-control:focus, .filter-box .form-select:focus {
            border-color: #ff9a9e;
            box-shadow: 0 0 0 0.2rem rgba(255, 154, 158, 0.25);
        }
        .btn-filter {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
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
            <h1 class="report-title"><i class="fas fa-shopping-cart"></i> Reporte de Ventas</h1>
            <div>
                <button onclick="exportarPDF()" class="btn-back" style="margin-right: 10px;"><i class="fas fa-file-pdf"></i> Descargar PDF</button>
                <button onclick="exportarExcel()" class="btn-back" style="margin-right: 10px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);"><i class="fas fa-file-excel"></i> Descargar Excel</button>
                <a href="/RMIE/app/controllers/ReportController.php?action=index" class="btn-back"><i class="fas fa-arrow-left"></i> Volver</a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value"><?= $totalVentas ?></div>
                    <div class="stat-label">Total Ventas</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%)">
                    <div class="stat-value"><?= $ventasCompletadas ?></div>
                    <div class="stat-label">Completadas</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%)">
                    <div class="stat-value"><?= $ventasPendientes ?></div>
                    <div class="stat-label">Pendientes</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)">
                    <div class="stat-value">$<?= number_format($totalIngresos, 0) ?></div>
                    <div class="stat-label">Ingresos Totales</div>
                </div>
            </div>
        </div>

        <!-- Formulario de Filtros -->
        <div class="filter-box">
            <h4><i class="fas fa-filter"></i> Filtros de Búsqueda</h4>
            <form method="GET" action="/RMIE/app/controllers/ReportController.php">
                <input type="hidden" name="action" value="ventas">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label"><i class="fas fa-user"></i> Cliente</label>
                        <input type="text" name="cliente" class="form-control" placeholder="Buscar por cliente..." value="<?= htmlspecialchars($filtros['cliente'] ?? '') ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label"><i class="fas fa-toggle-on"></i> Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos</option>
                            <option value="completada" <?= ($filtros['estado'] ?? '') === 'completada' ? 'selected' : '' ?>>Completada</option>
                            <option value="pendiente" <?= ($filtros['estado'] ?? '') === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="cancelada" <?= ($filtros['estado'] ?? '') === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label"><i class="fas fa-dollar-sign"></i> Monto Mín</label>
                        <input type="number" name="monto_min" class="form-control" placeholder="0" step="0.01" value="<?= htmlspecialchars($filtros['monto_min'] ?? '') ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label"><i class="fas fa-dollar-sign"></i> Monto Máx</label>
                        <input type="number" name="monto_max" class="form-control" placeholder="9999" step="0.01" value="<?= htmlspecialchars($filtros['monto_max'] ?? '') ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label"><i class="fas fa-calendar"></i> Desde</label>
                        <input type="date" name="fecha_desde" class="form-control" value="<?= htmlspecialchars($filtros['fecha_desde'] ?? '') ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label"><i class="fas fa-calendar"></i> Hasta</label>
                        <input type="date" name="fecha_hasta" class="form-control" value="<?= htmlspecialchars($filtros['fecha_hasta'] ?? '') ?>">
                    </div>
                    <div class="col-md-1 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-filter w-100"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-end">
                        <a href="/RMIE/app/controllers/ReportController.php?action=ventas" class="btn btn-clear"><i class="fas fa-times"></i> Limpiar Filtros</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="data-table">
            <h3 class="mb-3"><i class="fas fa-table"></i> Listado de Ventas</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventas as $venta): ?>
                        <tr>
                            <td><?= htmlspecialchars($venta->id_ventas) ?></td>
                            <td><?= htmlspecialchars($venta->cliente_nombre ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($venta->fecha_venta ?? 'N/A') ?></td>
                            <td>$<?= number_format($venta->total ?? 0, 2) ?></td>
                            <td>
                                <?php 
                                $estado = strtolower($venta->estado ?? 'completada');
                                $badgeClass = 'badge-completada';
                                if ($estado === 'pendiente') $badgeClass = 'badge-pendiente';
                                if ($estado === 'cancelada') $badgeClass = 'badge-cancelada';
                                ?>
                                <span class="badge <?= $badgeClass ?>">
                                    <?= htmlspecialchars($venta->estado ?? 'Completada') ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($venta->usuario_nombre ?? 'N/A') ?></td>
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
            doc.text('Reporte de Ventas', 14, 20);
            
            const tabla = document.querySelector('.table');
            doc.autoTable({
                html: tabla,
                startY: 30,
                theme: 'grid',
                headStyles: { fillColor: [16, 185, 129] }
            });
            
            doc.save('reporte_ventas.pdf');
        }
        
        function exportarExcel() {
            const wb = XLSX.utils.book_new();
            const tabla = document.querySelector('.table');
            const ws = XLSX.utils.table_to_sheet(tabla);
            
            ws['!cols'] = [
                {wch: 12}, // ID
                {wch: 35}, // Cliente
                {wch: 22}, // Fecha
                {wch: 18}, // Total
                {wch: 18}, // Estado
                {wch: 30}  // Usuario
            ];
            
            XLSX.utils.book_append_sheet(wb, ws, "Ventas");
            const fecha = new Date().toLocaleDateString('es-ES').replace(/\//g, '-');
            XLSX.writeFile(wb, `Reporte_Ventas_${fecha}.xlsx`);
        }
    </script>
</body>
</html>
