<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

require_once __DIR__ . '/../../models/Alert.php';

$alertas = Alert::getAll($conn);

$totalAlertas = count($alertas);
$alertasActivas = count(array_filter($alertas, fn($a) => strtolower($a->estado ?? 'activo') === 'activo'));
$alertasCriticas = count(array_filter($alertas, fn($a) => strtolower($a->prioridad ?? '') === 'alta'));
$alertasResueltas = count(array_filter($alertas, fn($a) => strtolower($a->estado ?? '') === 'resuelta'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Alertas - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
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
            border-bottom: 3px solid #eb3349;
        }
        .report-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #eb3349;
        }
        .stat-card {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
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
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            color: white;
        }
        .table thead th {
            border: none;
            padding: 15px;
        }
        .badge-activo {
            background: #fee140;
            color: #000;
        }
        .badge-resuelta {
            background: #38ef7d;
            color: #000;
        }
        .badge-critica {
            background: #eb3349;
            color: #fff;
        }
        .badge-alta {
            background: #fa709a;
            color: #fff;
        }
        .badge-media {
            background: #fee140;
            color: #000;
        }
        .badge-baja {
            background: #4facfe;
            color: #fff;
        }
        .btn-back {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
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
    </style>
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <h1 class="report-title"><i class="fas fa-bell"></i> Reporte de Alertas</h1>
            <div>
                <button onclick="exportarPDF()" class="btn-back" style="margin-right: 10px;"><i class="fas fa-file-pdf"></i> Descargar PDF</button>
                <button onclick="exportarExcel()" class="btn-back" style="margin-right: 10px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);"><i class="fas fa-file-excel"></i> Descargar Excel</button>
                <a href="/RMIE/app/controllers/ReportController.php?action=index" class="btn-back"><i class="fas fa-arrow-left"></i> Volver</a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value"><?= $totalAlertas ?></div>
                    <div class="stat-label">Total Alertas</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%)">
                    <div class="stat-value"><?= $alertasActivas ?></div>
                    <div class="stat-label">Activas</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%)">
                    <div class="stat-value"><?= $alertasCriticas ?></div>
                    <div class="stat-label">Críticas/Altas</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%)">
                    <div class="stat-value"><?= $alertasResueltas ?></div>
                    <div class="stat-label">Resueltas</div>
                </div>
            </div>
        </div>

        <div class="data-table">
            <h3 class="mb-3"><i class="fas fa-table"></i> Listado de Alertas</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Prioridad</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alertas as $alerta): ?>
                        <tr>
                            <td><?= htmlspecialchars($alerta['id_alertas']) ?></td>
                            <td><?= htmlspecialchars($alerta['cliente_no_disponible'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($alerta['cantidad_minima'] ?? 'N/A') ?></td>
                            <td>
                                <span class="badge badge-info">
                                    Stock: <?= htmlspecialchars($alerta['cantidad_minima'] ?? 'N/A') ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-activo">
                                    Activo
                                </span>
                            </td>
                            <td><?= htmlspecialchars($alerta['fecha_caducidad'] ?? 'N/A') ?></td>
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
            doc.text('Reporte de Alertas', 14, 20);
            
            const tabla = document.querySelector('.table');
            doc.autoTable({
                html: tabla,
                startY: 30,
                theme: 'grid',
                headStyles: { fillColor: [220, 38, 38] }
            });
            
            doc.save('reporte_alertas.pdf');
        }
        
        function exportarExcel() {
            const wb = XLSX.utils.book_new();
            const tabla = document.querySelector('.table');
            const ws = XLSX.utils.table_to_sheet(tabla);
            
            ws['!cols'] = [
                {wch: 12}, // ID
                {wch: 40}, // Cliente No Disponible
                {wch: 22}, // Cantidad Mínima
                {wch: 25}, // Descripción
                {wch: 18}, // Estado
                {wch: 22}  // Fecha
            ];
            
            XLSX.utils.book_append_sheet(wb, ws, "Alertas");
            const fecha = new Date().toLocaleDateString('es-ES').replace(/\//g, '-');
            XLSX.writeFile(wb, `Reporte_Alertas_${fecha}.xlsx`);
        }
    </script>
</body>
</html>
