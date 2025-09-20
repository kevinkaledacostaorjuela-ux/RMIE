<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Subcategorías</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Listado de Subcategorías</h1>
        <a href="../dashboard.php" class="btn-categorias">Volver al menú principal</a>
        <a href="create.php" class="btn-categorias">Agregar Subcategoría</a>
        <table class="table table-striped table-bordered table-categorias">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Categoría</th>
            <th>Fecha de Creación</th>
            <th>Acciones</th>
        </tr>
        <?php if (isset($subcategorias) && is_array($subcategorias)): ?>
            <?php foreach ($subcategorias as $sub): ?>
            <tr>
                <td><?= $sub->id_subcategoria ?></td>
                <td><?= $sub->nombre ?></td>
                <td><?= $sub->descripcion ?></td>
                <td><?= $sub->fecha_creacion ?></td>
                <td><?= $sub->id_categoria ?></td>
                <td>
                    <a href="edit.php?id=<?= $sub->id_subcategoria ?>">Editar</a> |
                    <a href="delete.php?id=<?= $sub->id_subcategoria ?>" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>
</html>
