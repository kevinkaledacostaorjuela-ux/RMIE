<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

require_once __DIR__ . '/../../models/Client.php';

$clientes = Client::getAll($conn);

$totalClientes = count($clientes);
$clientesActivos = count(array_filter($clientes, fn($c) => strtolower($c->estado ?? 'activo') === 'activo'));
$clientesInactivos = $totalClientes - $clientesActivos;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Clientes - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
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
            border-bottom: 3px solid #a8edea;
        }
        .report-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #13547a;
        }
        .stat-card {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #333;
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
            opacity: 0.8;
        }
        .data-table {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .table thead {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #333;
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
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #333;
            border: none;
            padding: 10px 30px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            color: #333;
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <h1 class="report-title"><i class="fas fa-user-tie"></i> Reporte de Clientes</h1>
            <div>
                <button onclick="exportarPDF()" class="btn-back" style="margin-right: 10px;"><i class="fas fa-file-pdf"></i> Descargar PDF</button>
                <button onclick="exportarExcel()" class="btn-back" style="margin-right: 10px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);"><i class="fas fa-file-excel"></i> Descargar Excel</button>
                <a href="/RMIE/app/controllers/ReportController.php?action=index" class="btn-back"><i class="fas fa-arrow-left"></i> Volver</a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-value"><?= $totalClientes ?></div>
                    <div class="stat-label">Total Clientes</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                    <div class="stat-value"><?= $clientesActivos ?></div>
                    <div class="stat-label" style="opacity: 0.9;">Activos</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); color: white;">
                    <div class="stat-value"><?= $clientesInactivos ?></div>
                    <div class="stat-label" style="opacity: 0.9;">Inactivos</div>
                </div>
            </div>
        </div>

        <div class="data-table">
            <h3 class="mb-3"><i class="fas fa-table"></i> Listado de Clientes</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Dirección</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?= htmlspecialchars($cliente->id_clientes) ?></td>
                            <td><?= htmlspecialchars($cliente->nombre ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($cliente->local_nombre ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($cliente->cel_cliente ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($cliente->correo ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($cliente->descripcion ?? 'N/A') ?></td>
                            <td>
                                <span class="badge <?= strtolower($cliente->estado ?? 'activo') === 'activo' ? 'badge-activo' : 'badge-inactivo' ?>">
                                    <?= htmlspecialchars($cliente->estado ?? 'Activo') ?>
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
            doc.text('Reporte de Clientes', 14, 20);
            
            const tabla = document.querySelector('.table');
            doc.autoTable({
                html: tabla,
                startY: 30,
                theme: 'grid',
                headStyles: { fillColor: [59, 130, 246] }
            });
            
            doc.save('reporte_clientes.pdf');
        }
        
        function exportarExcel() {
            const wb = XLSX.utils.book_new();
            const tabla = document.querySelector('.table');
            const ws = XLSX.utils.table_to_sheet(tabla);
            
            ws['!cols'] = [
                {wch: 12}, // ID
                {wch: 35}, // Nombre
                {wch: 30}, // Local
                {wch: 18}, // Teléfono
                {wch: 35}, // Email
                {wch: 40}, // Descripción
                {wch: 18}  // Estado
            ];
            
            XLSX.utils.book_append_sheet(wb, ws, "Clientes");
            const fecha = new Date().toLocaleDateString('es-ES').replace(/\//g, '-');
            XLSX.writeFile(wb, `Reporte_Clientes_${fecha}.xlsx`);
        }
    </script>
</body>
</html>
