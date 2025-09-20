<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Categorías</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Listado de Categorías</h1>
    <a href="../../dashboard.php" class="btn-categorias">Volver al menú principal</a>
    <a href="../../categorias.php?accion=create" class="btn-categorias">Agregar Categoría</a>
        <table class="table table-striped table-bordered table-categorias">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Fecha de Creación</th>
            <th>Acciones</th>
        </tr>
        <?php if (isset($categorias) && is_array($categorias)): ?>
            <?php foreach ($categorias as $cat): ?>
            <tr>
                <td><?= $cat->id_categoria ?></td>
                <td><?= $cat->nombre ?></td>
                <td><?= $cat->descripcion ?></td>
                <td><?= $cat->fecha_creacion ?></td>
                <td>
                    <a href="../../categorias.php?accion=edit&id=<?= $cat->id_categoria ?>">Editar</a> |
                    <a href="../../categorias.php?accion=delete&id=<?= $cat->id_categoria ?>" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>
</html>
