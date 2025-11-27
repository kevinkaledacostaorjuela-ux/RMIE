<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

require_once __DIR__ . '/../../models/Product.php';

$productos = Product::getAll($conn);

// Aplicar filtros adicionales en PHP
if (!empty($filtros)) {
    $productos = array_filter($productos, function($p) use ($filtros) {
        // Filtro por nombre
        if (!empty($filtros['nombre'])) {
            if (stripos($p->nombre ?? '', $filtros['nombre']) === false) {
                return false;
            }
        }
        
        // Filtro por categoría
        if (!empty($filtros['categoria'])) {
            if (stripos($p->categoria_nombre ?? '', $filtros['categoria']) === false) {
                return false;
            }
        }
        
        // Filtro por subcategoría
        if (!empty($filtros['subcategoria'])) {
            if (stripos($p->subcategoria_nombre ?? '', $filtros['subcategoria']) === false) {
                return false;
            }
        }
        
        // Filtro por proveedor
        if (!empty($filtros['proveedor'])) {
            if (stripos($p->proveedor_nombre ?? '', $filtros['proveedor']) === false) {
                return false;
            }
        }
        
        // Filtro por estado
        if (!empty($filtros['estado']) && strtolower($p->estado ?? 'activo') !== strtolower($filtros['estado'])) {
            return false;
        }
        
        return true;
    });
}

$totalProductos = count($productos);
$productosActivos = count(array_filter($productos, fn($p) => strtolower($p->estado ?? 'activo') === 'activo'));
$productosInactivos = $totalProductos - $productosActivos;
$totalStock = array_sum(array_map(fn($p) => $p->stock ?? 0, $productos));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Productos - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
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
            border-bottom: 3px solid #fa709a;
        }
        .report-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #fa709a;
        }
        .stat-card {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
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
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
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
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
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
            background: rgba(250, 112, 154, 0.1);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 2px solid rgba(250, 112, 154, 0.3);
        }
        .filter-box h4 {
            color: #fa709a;
            margin-bottom: 20px;
        }
        .filter-box .form-control, .filter-box .form-select {
            border-radius: 10px;
            border: 2px solid rgba(250, 112, 154, 0.3);
        }
        .filter-box .form-control:focus, .filter-box .form-select:focus {
            border-color: #fa709a;
            box-shadow: 0 0 0 0.2rem rgba(250, 112, 154, 0.25);
        }
        .btn-filter {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
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
            <h1 class="report-title"><i class="fas fa-box"></i> Reporte de Productos</h1>
            <div>
                <button onclick="exportarPDF()" class="btn-back" style="margin-right: 10px;"><i class="fas fa-file-pdf"></i> Descargar PDF</button>
                <button onclick="exportarExcel()" class="btn-back" style="margin-right: 10px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);"><i class="fas fa-file-excel"></i> Descargar Excel</button>
                <a href="/RMIE/app/controllers/ReportController.php?action=index" class="btn-back"><i class="fas fa-arrow-left"></i> Volver</a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value"><?= $totalProductos ?></div>
                    <div class="stat-label">Total Productos</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%)">
                    <div class="stat-value"><?= $productosActivos ?></div>
                    <div class="stat-label">Activos</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%)">
                    <div class="stat-value"><?= $productosInactivos ?></div>
                    <div class="stat-label">Inactivos</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)">
                    <div class="stat-value"><?= $totalStock ?></div>
                    <div class="stat-label">Stock Total</div>
                </div>
            </div>
        </div>

        <!-- Formulario de Filtros -->
        <div class="filter-box">
            <h4><i class="fas fa-filter"></i> Filtros de Búsqueda</h4>
            <form method="GET" action="/RMIE/app/controllers/ReportController.php">
                <input type="hidden" name="action" value="productos">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="form-label"><i class="fas fa-box"></i> Nombre</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Buscar..." value="<?= htmlspecialchars($filtros['nombre'] ?? '') ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label"><i class="fas fa-tag"></i> Categoría</label>
                        <input type="text" name="categoria" class="form-control" placeholder="Categoría..." value="<?= htmlspecialchars($filtros['categoria'] ?? '') ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label"><i class="fas fa-tags"></i> Subcategoría</label>
                        <input type="text" name="subcategoria" class="form-control" placeholder="Subcategoría..." value="<?= htmlspecialchars($filtros['subcategoria'] ?? '') ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label"><i class="fas fa-truck"></i> Proveedor</label>
                        <input type="text" name="proveedor" class="form-control" placeholder="Proveedor..." value="<?= htmlspecialchars($filtros['proveedor'] ?? '') ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label"><i class="fas fa-toggle-on"></i> Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos</option>
                            <option value="activo" <?= ($filtros['estado'] ?? '') === 'activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="inactivo" <?= ($filtros['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-filter w-100"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-end">
                        <a href="/RMIE/app/controllers/ReportController.php?action=productos" class="btn btn-clear"><i class="fas fa-times"></i> Limpiar Filtros</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="data-table">
            <h3 class="mb-3"><i class="fas fa-table"></i> Listado de Productos</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Subcategoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?= htmlspecialchars($producto->id_productos) ?></td>
                            <td><?= htmlspecialchars($producto->nombre ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($producto->categoria_nombre ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($producto->subcategoria_nombre ?? 'N/A') ?></td>
                            <td>$<?= number_format($producto->precio_unitario ?? 0, 2) ?></td>
                            <td><?= htmlspecialchars($producto->stock ?? 0) ?></td>
                            <td>
                                <span class="badge <?= strtolower($producto->estado ?? 'activo') === 'activo' ? 'badge-activo' : 'badge-inactivo' ?>">
                                    <?= htmlspecialchars($producto->estado ?? 'Activo') ?>
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
            doc.text('Reporte de Productos', 14, 20);
            
            const tabla = document.querySelector('.table');
            doc.autoTable({
                html: tabla,
                startY: 30,
                theme: 'grid',
                headStyles: { fillColor: [236, 72, 153] }
            });
            
            doc.save('reporte_productos.pdf');
        }
        
        function exportarExcel() {
            const wb = XLSX.utils.book_new();
            const tabla = document.querySelector('.table');
            const ws = XLSX.utils.table_to_sheet(tabla);
            
            ws['!cols'] = [
                {wch: 12}, // ID
                {wch: 35}, // Nombre
                {wch: 25}, // Categoría
                {wch: 25}, // Subcategoría
                {wch: 18}, // Precio
                {wch: 12}, // Stock
                {wch: 18}  // Estado
            ];
            
            XLSX.utils.book_append_sheet(wb, ws, "Productos");
            const fecha = new Date().toLocaleDateString('es-ES').replace(/\//g, '-');
            XLSX.writeFile(wb, `Reporte_Productos_${fecha}.xlsx`);
        }
    </script>
</body>
</html>
