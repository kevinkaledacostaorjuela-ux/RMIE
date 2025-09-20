<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reportes</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Listado de Reportes</h1>
    <form method="GET" action="">
        <label>Filtrar por Producto:</label>
        <select name="producto">
            <option value="">Todos</option>
            <?php foreach ($productos as $prod): ?>
                <option value="<?= $prod->id_productos ?>" <?= isset($_GET['producto']) && $_GET['producto'] == $prod->id_productos ? 'selected' : '' ?>><?= $prod->nombre ?></option>
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
            <th>Descripción</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>ID Venta</th>
        </tr>
        <?php foreach ($reportes as $item): ?>
        <tr>
            <td><?= $item['obj']->id_reportes ?></td>
            <td><?= $item['producto_nombre'] ?></td>
            <td><?= $item['obj']->nombre ?></td>
            <td><?= $item['obj']->descripcion ?></td>
            <td><?= $item['obj']->fecha ?></td>
            <td><?= $item['obj']->estado ?></td>
            <td><?= $item['obj']->id_ventas ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
