<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Agregar Categoría</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Agregar Categoría</h1>
    <form method="POST" action="">
        <label>Nombre:</label>
        <input type="text" name="nombre" required><br>
        <label>Descripción:</label>
        <input type="text" name="descripcion" required><br>
        <button type="submit">Guardar</button>
    </form>
    <a href="index.php">Volver</a>
</body>
</html>
