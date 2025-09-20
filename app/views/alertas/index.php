<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Alertas</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <h1>Listado de Alertas</h1>
    <a href="../dashboard.php" class="btn-categorias">Volver al menú principal</a>
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
        <div class="categorias-container">
            <h1>Listado de Alertas</h1>
            <a href="create.php" class="btn-categorias">Agregar Alerta</a>
            <table class="table table-striped table-bordered table-categorias">
        <tr>
            <th>ID</th>
            <th>Producto</th>
            <th>Cliente No Disponible</th>
            <th>ID Cliente</th>
        </tr>
        <?php if (isset($alertas) && is_array($alertas)): ?>
            <?php foreach ($alertas as $item): ?>
            <tr>
                <td><?= $item['obj']->id_alertas ?></td>
                <td><?= $item['producto_nombre'] ?></td>
                <td><?= $item['obj']->cliente_no_disponible ?></td>
                <td><?= $item['obj']->id_clientes ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
        </div>
</body>
</html>
