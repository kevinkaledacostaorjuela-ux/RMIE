<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Categoría</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Editar Categoría</h1>
    <form method="POST" action="">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= $categoria->nombre ?>" required><br>
        <label>Descripción:</label>
        <input type="text" name="descripcion" value="<?= $categoria->descripcion ?>" required><br>
        <button type="submit">Actualizar</button>
    </form>
    <a href="/RMIE/app/controllers/CategoryController.php?accion=index">Volver al listado de categorías</a>
</body>
</html>
