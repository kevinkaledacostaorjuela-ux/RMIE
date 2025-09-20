<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Agregar Subcategoría</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Agregar Subcategoría</h1>
    <form method="POST" action="">
        <label>Nombre:</label>
        <input type="text" name="nombre" required><br>
        <label>Descripción:</label>
        <input type="text" name="descripcion" required><br>
        <label>Categoría:</label>
        <select name="id_categoria" required>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat->id_categoria ?>"><?= $cat->nombre ?></option>
            <?php endforeach; ?>
        </select><br>
        <button type="submit">Guardar</button>
    </form>
    <a href="index.php">Volver</a>
</body>
</html>
