<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ventas</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Listado de Ventas</h1>
    <form method="GET" action="">
        <label>Filtrar por Producto:</label>
        <select name="producto">
            <option value="">Todos</option>
            <?php foreach ($productos as $prod): ?>
                <option value="<?= $prod->id_productos ?>" <?= isset($_GET['producto']) && $_GET['producto'] == $prod->id_productos ? 'selected' : '' ?>><?= $prod->nombre ?></option>
            <?php endforeach; ?>
        </select>
        <label>Filtrar por Cliente:</label>
        <select name="cliente">
            <option value="">Todos</option>
            <?php foreach ($clientes as $cli): ?>
                <option value="<?= $cli->id_clientes ?>" <?= isset($_GET['cliente']) && $_GET['cliente'] == $cli->id_clientes ? 'selected' : '' ?>><?= $cli->nombre ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtrar</button>
    </form>
    <br>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Producto</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Cantidad</th>
            <th>Fecha Venta</th>
            <th>Cliente</th>
            <th>ID Reporte</th>
            <th>ID Ruta</th>
        </tr>
        <?php foreach ($ventas as $item): ?>
        <tr>
            <td><?= $item['obj']->id_ventas ?></td>
            <td><?= $item['producto_nombre'] ?></td>
            <td><?= $item['obj']->nombre ?></td>
            <td><?= $item['obj']->direccion ?></td>
            <td><?= $item['obj']->cantidad ?></td>
            <td><?= $item['obj']->fecha_venta ?></td>
            <td><?= $item['cliente_nombre'] ?></td>
            <td><?= $item['obj']->id_reportes ?></td>
            <td><?= $item['obj']->id_ruta ?></td>
            <td><?= $item['obj']->cantidad ?></td>
            <td><?= $item['obj']->fecha_venta ?></td>
            <td><?= $item['obj']->id_clientes ?></td>
            <td><?= $item['obj']->id_reportes ?></td>
            <td><?= $item['obj']->id_ruta ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
