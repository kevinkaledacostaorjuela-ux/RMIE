<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rutas</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <h1>Listado de Rutas</h1>
    <a href="../dashboard.php" class="btn-categorias">Volver al menú principal</a>
    <form method="GET" action="">
        <label>Filtrar por Reporte:</label>
        <select name="reporte">
            <option value="">Todos</option>
            <?php foreach ($reportes as $rep): ?>
                <option value="<?= $rep['obj']->id_reportes ?>" <?= isset($_GET['reporte']) && $_GET['reporte'] == $rep['obj']->id_reportes ? 'selected' : '' ?>><?= $rep['obj']->nombre ?></option>
            <?php endforeach; ?>
        </select>
        <label>Filtrar por Venta:</label>
        <select name="venta">
            <option value="">Todas</option>
            <?php foreach ($ventas as $ven): ?>
                <option value="<?= $ven['obj']->id_ventas ?>" <?= isset($_GET['venta']) && $_GET['venta'] == $ven['obj']->id_ventas ? 'selected' : '' ?>><?= $ven['obj']->nombre ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtrar</button>
    </form>
    <br>
        <div class="categorias-container">
            <h1>Listado de Rutas</h1>
            <a href="create.php" class="btn-categorias">Agregar Ruta</a>
            <table class="table table-striped table-bordered table-categorias">
        <tr>
            <th>ID</th>
            <th>Dirección</th>
            <th>Local</th>
            <th>Cliente</th>
            <th>Reporte</th>
            <th>Venta</th>
        </tr>
        <?php if (isset($rutas) && is_array($rutas)): ?>
            <?php foreach ($rutas as $item): ?>
            <tr>
                <td><?= $item['obj']->id_ruta ?></td>
                <td><?= $item['obj']->direccion ?></td>
                <td><?= $item['obj']->nombre_local ?></td>
                <td><?= $item['obj']->nombre_cliente ?></td>
                <td><?= $item['reporte_nombre'] ?></td>
                <td><?= $item['venta_nombre'] ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
        </div>
</body>
</html>
