<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Alertas</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Listado de Alertas</h1>
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
            <th>Cliente No Disponible</th>
            <th>ID Cliente</th>
        </tr>
        <?php foreach ($alertas as $item): ?>
        <tr>
            <td><?= $item['obj']->id_alertas ?></td>
            <td><?= $item['producto_nombre'] ?></td>
            <td><?= $item['obj']->cliente_no_disponible ?></td>
            <td><?= $item['obj']->id_clientes ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
