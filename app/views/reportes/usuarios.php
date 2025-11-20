<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

require_once __DIR__ . '/../../models/User.php';

$usuarios = User::getAll($conn);

$totalUsuarios = count($usuarios);
$usuariosActivos = count(array_filter($usuarios, fn($u) => strtolower($u->estado ?? 'activo') === 'activo'));
$usuariosInactivos = $totalUsuarios - $usuariosActivos;
$administradores = count(array_filter($usuarios, fn($u) => strtolower($u->rol ?? '') === 'administrador'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Usuarios - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            border-bottom: 3px solid #667eea;
        }
        .report-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            <h1 class="report-title"><i class="fas fa-users"></i> Reporte de Usuarios</h1>
            <div>
                <button onclick="exportarPDF()" class="btn-back" style="margin-right: 10px;"><i class="fas fa-file-pdf"></i> Descargar PDF</button>
                <button onclick="exportarExcel()" class="btn-back" style="margin-right: 10px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);"><i class="fas fa-file-excel"></i> Descargar Excel</button>
                <a href="/RMIE/app/controllers/ReportController.php?action=index" class="btn-back"><i class="fas fa-arrow-left"></i> Volver</a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value"><?= $totalUsuarios ?></div>
                    <div class="stat-label">Total Usuarios</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%)">
                    <div class="stat-value"><?= $usuariosActivos ?></div>
                    <div class="stat-label">Activos</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%)">
                    <div class="stat-value"><?= $usuariosInactivos ?></div>
                    <div class="stat-label">Inactivos</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%)">
                    <div class="stat-value"><?= $administradores ?></div>
                    <div class="stat-label">Administradores</div>
                </div>
            </div>
        </div>

        <div class="data-table">
            <h3 class="mb-3"><i class="fas fa-table"></i> Listado de Usuarios</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Documento</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Fecha Creación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario->num_doc) ?></td>
                            <td><?= htmlspecialchars($usuario->nombres . ' ' . $usuario->apellidos) ?></td>
                            <td><?= htmlspecialchars($usuario->correo ?? 'N/A') ?></td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($usuario->rol ?? 'N/A') ?></span></td>
                            <td>
                                <span class="badge <?= strtolower($usuario->estado ?? 'activo') === 'activo' ? 'badge-activo' : 'badge-inactivo' ?>">
                                    <?= htmlspecialchars($usuario->estado ?? 'Activo') ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($usuario->fecha_creacion ?? 'N/A') ?></td>
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
            doc.text('Reporte de Usuarios', 14, 20);
            
            const tabla = document.querySelector('.table');
            doc.autoTable({
                html: tabla,
                startY: 30,
                theme: 'grid',
                headStyles: { fillColor: [102, 126, 234] }
            });
            
            doc.save('reporte_usuarios.pdf');
        }
        
        function exportarExcel() {
            // Crear un nuevo libro de trabajo
            const wb = XLSX.utils.book_new();
            
            // Obtener los datos de la tabla
            const tabla = document.querySelector('.table');
            const ws = XLSX.utils.table_to_sheet(tabla);
            
            // Aplicar estilos a las celdas de encabezado
            const range = XLSX.utils.decode_range(ws['!ref']);
            
            // Definir anchos de columna
            ws['!cols'] = [
                {wch: 20}, // Documento
                {wch: 35}, // Nombre
                {wch: 35}, // Correo
                {wch: 25}, // Rol
                {wch: 18}, // Estado
                {wch: 22}  // Fecha Creación
            ];
            
            // Agregar el worksheet al workbook
            XLSX.utils.book_append_sheet(wb, ws, "Usuarios");
            
            // Generar fecha actual para el nombre del archivo
            const fecha = new Date().toLocaleDateString('es-ES').replace(/\//g, '-');
            
            // Descargar el archivo
            XLSX.writeFile(wb, `Reporte_Usuarios_${fecha}.xlsx`);
        }
    </script>
</body>
</html>
