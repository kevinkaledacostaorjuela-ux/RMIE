<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Subcategoría</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Editar Subcategoría</h1>
    <form method="POST" action="">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= $subcategoria->nombre ?>" required><br>
        <label>Descripción:</label>
        <input type="text" name="descripcion" value="<?= $subcategoria->descripcion ?>" required><br>
        <label>Categoría:</label>
        <select name="id_categoria" required>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat->id_categoria ?>" <?= $subcategoria->id_categoria == $cat->id_categoria ? 'selected' : '' ?>><?= $cat->nombre ?></option>
            <?php endforeach; ?>
        </select><br>
        <button type="submit">Actualizar</button>
    </form>
    <a href="index.php">Volver</a>
</body>
</html>
