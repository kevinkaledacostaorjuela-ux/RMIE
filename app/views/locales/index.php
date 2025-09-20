<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Locales</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <h1>Listado de Locales</h1>
    <a href="../dashboard.php" class="btn-categorias">Volver al menú principal</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Dirección</th>
            <th>Nombre</th>
            <th>Celular</th>
            <th>Estado</th>
            <th>Localidad</th>
            <th>Barrio</th>
        </tr>
        <?php if (isset($locales) && is_array($locales)): ?>
            <?php foreach ($locales as $local): ?>
            <tr>
                <td><?= $local->id_locales ?></td>
                <td><?= $local->direccion ?></td>
                <td><?= $local->nombre_local ?></td>
                <td><?= $local->cel_local ?></td>
                <td><?= $local->estado ?></td>
                <td><?= $local->localidad ?></td>
                <td><?= $local->barrio ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>
</html>
