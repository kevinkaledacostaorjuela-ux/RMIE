<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Proveedores</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Listado de Proveedores</h1>
        <a href="../dashboard.php" class="btn-categorias">Volver al menú principal</a>
        <a href="../../controllers/ProviderController.php?accion=create" class="btn-categorias">Agregar Proveedor</a>
        <table class="table table-striped table-bordered table-categorias">
        <tr>
            <th>ID</th>
            <th>Nombre Distribuidor</th>
            <th>Correo</th>
            <th>Celular</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php if (isset($proveedores) && is_array($proveedores)): ?>
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
        <?php endif; ?>
    </table>
</body>
</html>
