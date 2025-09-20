<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Producto</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Editar Producto</h1>
    <form method="POST" action="">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= $producto->nombre ?>" required><br>
        <label>Descripción:</label>
        <input type="text" name="descripcion" value="<?= $producto->descripcion ?>" required><br>
        <label>Fecha Entrada:</label>
        <input type="text" name="fecha_entrada" value="<?= $producto->fecha_entrada ?>"><br>
        <label>Fecha Fabricación:</label>
        <input type="text" name="fecha_fabricacion" value="<?= $producto->fecha_fabricacion ?>"><br>
        <label>Fecha Caducidad:</label>
        <input type="text" name="fecha_caducidad" value="<?= $producto->fecha_caducidad ?>"><br>
        <label>Stock:</label>
        <input type="text" name="stock" value="<?= $producto->stock ?>"><br>
        <label>Precio Unitario:</label>
        <input type="text" name="precio_unitario" value="<?= $producto->precio_unitario ?>"><br>
        <label>Precio Mayor:</label>
        <input type="text" name="precio_por_mayor" value="<?= $producto->precio_por_mayor ?>"><br>
        <label>Valor Unitario:</label>
        <input type="text" name="valor_unitario" value="<?= $producto->valor_unitario ?>"><br>
        <label>Marca:</label>
        <input type="text" name="marca" value="<?= $producto->marca ?>"><br>
        <label>Categoría:</label>
        <select name="id_categoria" required>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat->id_categoria ?>" <?= $producto->id_categoria == $cat->id_categoria ? 'selected' : '' ?>><?= $cat->nombre ?></option>
            <?php endforeach; ?>
        </select><br>
        <label>Subcategoría:</label>
        <select name="id_subcategoria" required>
            <?php foreach ($subcategorias as $sub): ?>
                <option value="<?= $sub['obj']->id_subcategoria ?>" <?= $producto->id_subcategoria == $sub['obj']->id_subcategoria ? 'selected' : '' ?>><?= $sub['obj']->nombre ?></option>
            <?php endforeach; ?>
        </select><br>
        <button type="submit">Actualizar</button>
    </form>
    <a href="index.php">Volver</a>
</body>
</html>
