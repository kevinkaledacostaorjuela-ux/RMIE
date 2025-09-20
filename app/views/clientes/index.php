<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Clientes</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <h1>Listado de Clientes</h1>
    <a href="../dashboard.php" class="btn-categorias">Volver al menú principal</a>
    <a href="../../controllers/ClientController.php?accion=create" class="btn-categorias">Agregar Cliente</a>
    <form method="GET" action="">
        <label>Filtrar por Local:</label>
        <select name="local">
            <option value="">Todos</option>
            <?php foreach ($locales as $local): ?>
                <option value="<?= $local->id_locales ?>" <?= isset($_GET['local']) && $_GET['local'] == $local->id_locales ? 'selected' : '' ?>><?= $local->nombre_local ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtrar</button>
    </form>
    <br>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Celular</th>
            <th>Correo</th>
            <th>Estado</th>
            <th>Local</th>
        </tr>
        <?php if (isset($clientes) && is_array($clientes)): ?>
            <?php foreach ($clientes as $item): ?>
            <tr>
                <td><?= $item['obj']->id_clientes ?></td>
                <td><?= $item['obj']->nombre ?></td>
                <td><?= $item['obj']->descripcion ?></td>
                <td><?= $item['obj']->cel_cliente ?></td>
                <td><?= $item['obj']->correo ?></td>
                <td><?= $item['obj']->estado ?></td>
                <td><?= $item['local_nombre'] ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>
</html>
