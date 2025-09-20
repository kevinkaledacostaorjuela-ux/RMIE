<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Locales</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Listado de Locales</h1>
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
    </table>
</body>
</html>
