<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Proveedores</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Listado de Proveedores</h1>
    <a href="create.php">Agregar Proveedor</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre Distribuidor</th>
            <th>Correo</th>
            <th>Celular</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($proveedores as $prov): ?>
        <tr>
            <td><?= $prov->id_proveedores ?></td>
            <td><?= $prov->nombre_distribuidor ?></td>
            <td><?= $prov->correo ?></td>
            <td><?= $prov->cel_proveedor ?></td>
            <td><?= $prov->estado ?></td>
            <td>
                <a href="edit.php?id=<?= $prov->id_proveedores ?>">Editar</a> |
                <a href="delete.php?id=<?= $prov->id_proveedores ?>" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
