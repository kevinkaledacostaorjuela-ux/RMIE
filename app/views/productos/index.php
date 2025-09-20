<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Productos</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Listado de Productos</h1>
    <a href="create.php">Agregar Producto</a>
    <form method="GET" action="">
        <label>Filtrar por Categoría:</label>
        <select name="categoria">
            <option value="">Todas</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat->id_categoria ?>" <?= isset($_GET['categoria']) && $_GET['categoria'] == $cat->id_categoria ? 'selected' : '' ?>><?= $cat->nombre ?></option>
            <?php endforeach; ?>
        </select>
        <label>Filtrar por Subcategoría:</label>
        <select name="subcategoria">
            <option value="">Todas</option>
            <?php foreach ($subcategorias as $sub): ?>
                <option value="<?= $sub['obj']->id_subcategoria ?>" <?= isset($_GET['subcategoria']) && $_GET['subcategoria'] == $sub['obj']->id_subcategoria ? 'selected' : '' ?>><?= $sub['obj']->nombre ?></option>
            <?php endforeach; ?>
        </select>
        <label>Filtrar por Proveedor:</label>
        <select name="proveedor">
            <option value="">Todos</option>
            <?php foreach ($proveedores as $prov): ?>
                <option value="<?= $prov->id_proveedores ?>" <?= isset($_GET['proveedor']) && $_GET['proveedor'] == $prov->id_proveedores ? 'selected' : '' ?>><?= $prov->nombre_distribuidor ?></option>
            <?php endforeach; ?>
        </select>
        <label>Filtrar por Usuario:</label>
        <select name="usuario">
            <option value="">Todos</option>
            <?php foreach ($usuarios as $user): ?>
                <option value="<?= $user->num_doc ?>" <?= isset($_GET['usuario']) && $_GET['usuario'] == $user->num_doc ? 'selected' : '' ?>><?= $user->nombres ?> <?= $user->apellidos ?></option>
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
            <th>Fecha Entrada</th>
            <th>Fecha Fabricación</th>
            <th>Fecha Caducidad</th>
            <th>Stock</th>
            <th>Precio Unitario</th>
            <th>Precio Mayor</th>
            <th>Valor Unitario</th>
            <th>Marca</th>
            <th>Categoría</th>
            <th>Subcategoría</th>
            <th>Proveedor</th>
            <th>Usuario</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($productos as $item): ?>
        <tr>
            <td><?= $item['obj']->id_productos ?></td>
            <td><?= $item['obj']->nombre ?></td>
            <td><?= $item['obj']->descripcion ?></td>
            <td><?= $item['obj']->fecha_entrada ?></td>
            <td><?= $item['obj']->fecha_fabricacion ?></td>
            <td><?= $item['obj']->fecha_caducidad ?></td>
            <td><?= $item['obj']->stock ?></td>
            <td><?= $item['obj']->precio_unitario ?></td>
            <td><?= $item['obj']->precio_por_mayor ?></td>
            <td><?= $item['obj']->valor_unitario ?></td>
            <td><?= $item['obj']->marca ?></td>
            <td><?= $item['categoria_nombre'] ?></td>
            <td><?= $item['subcategoria_nombre'] ?></td>
            <td><?= $item['proveedor_nombre'] ?></td>
            <td><?= $item['usuario_nombre'] ?></td>
            <td>
                <a href="edit.php?id=<?= $item['obj']->id_productos ?>">Editar</a> |
                <a href="delete.php?id=<?= $item['obj']->id_productos ?>" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
