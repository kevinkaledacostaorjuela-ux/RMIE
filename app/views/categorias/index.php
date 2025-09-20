<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Categorías</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Listado de Categorías</h1>
    <a href="create.php">Agregar Categoría</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Fecha de Creación</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($categorias as $cat): ?>
        <tr>
            <td><?= $cat->id_categoria ?></td>
            <td><?= $cat->nombre ?></td>
            <td><?= $cat->descripcion ?></td>
            <td><?= $cat->fecha_creacion ?></td>
            <td>
                <a href="edit.php?id=<?= $cat->id_categoria ?>">Editar</a> |
                <a href="delete.php?id=<?= $cat->id_categoria ?>" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
