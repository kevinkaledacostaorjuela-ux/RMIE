<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Subcategorías</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Listado de Subcategorías</h1>
    <a href="create.php">Agregar Subcategoría</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Categoría</th>
            <th>Fecha de Creación</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($subcategorias as $item): ?>
        <tr>
            <td><?= $item['obj']->id_subcategoria ?></td>
            <td><?= $item['obj']->nombre ?></td>
            <td><?= $item['obj']->descripcion ?></td>
            <td><?= $item['categoria_nombre'] ?></td>
            <td><?= $item['obj']->fecha_creacion ?></td>
            <td>
                <a href="edit.php?id=<?= $item['obj']->id_subcategoria ?>">Editar</a> |
                <a href="delete.php?id=<?= $item['obj']->id_subcategoria ?>" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
