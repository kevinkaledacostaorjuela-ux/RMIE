<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Agregar Producto</h1>
    <form method="POST" action="../../controllers/ProductController.php?accion=create">
        <label>Nombre:</label>
        <input type="text" name="nombre" required><br>
        <label>Descripción:</label>
        <input type="text" name="descripcion" required><br>
        <label>Fecha Entrada:</label>
        <input type="text" name="fecha_entrada"><br>
        <label>Fecha Fabricación:</label>
        <input type="text" name="fecha_fabricacion"><br>
        <label>Fecha Caducidad:</label>
        <input type="text" name="fecha_caducidad"><br>
        <label>Stock:</label>
        <input type="text" name="stock"><br>
        <label>Precio Unitario:</label>
        <input type="text" name="precio_unitario"><br>
        <label>Precio Mayor:</label>
        <input type="text" name="precio_por_mayor"><br>
        <label>Valor Unitario:</label>
        <input type="text" name="valor_unitario"><br>
        <label>Marca:</label>
        <input type="text" name="marca"><br>
        <label>Categoría:</label>
        <select name="id_categoria" required>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat->id_categoria ?>"><?= $cat->nombre ?></option>
            <?php endforeach; ?>
        </select><br>
        <label>Subcategoría:</label>
        <select name="id_subcategoria" required>
            <?php foreach ($subcategorias as $sub): ?>
                <option value="<?= $sub['obj']->id_subcategoria ?>"><?= $sub['obj']->nombre ?></option>
            <?php endforeach; ?>
        </select><br>
        <button type="submit">Guardar</button>
    </form>
    <a href="index.php">Volver</a>
</body>
</html>
