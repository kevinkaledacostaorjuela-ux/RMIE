<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Usuarios</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Listado de Usuarios</h1>
    <table border="1">
        <tr>
            <th>Documento</th>
            <th>Tipo</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Correo</th>
            <th>Celular</th>
            <th>Rol</th>
        </tr>
        <?php foreach ($usuarios as $user): ?>
        <tr>
            <td><?= $user->num_doc ?></td>
            <td><?= $user->tipo_doc ?></td>
            <td><?= $user->nombres ?></td>
            <td><?= $user->apellidos ?></td>
            <td><?= $user->correo ?></td>
            <td><?= $user->num_cel ?></td>
            <td><?= $user->rol ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
