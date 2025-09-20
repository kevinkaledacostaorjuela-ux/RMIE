<?php
// ...existing code...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Proveedor</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Editar Proveedor</h1>
    <form method="POST" action="">
        <label>Nombre Distribuidor:</label>
        <input type="text" name="nombre_distribuidor" value="<?= $proveedor->nombre_distribuidor ?>" required><br>
        <label>Correo:</label>
        <input type="email" name="correo" value="<?= $proveedor->correo ?>" required><br>
        <label>Celular:</label>
        <input type="text" name="cel_proveedor" value="<?= $proveedor->cel_proveedor ?>" required><br>
        <label>Estado:</label>
        <input type="text" name="estado" value="<?= $proveedor->estado ?>" required><br>
        <button type="submit">Actualizar</button>
    </form>
    <a href="index.php">Volver</a>
</body>
</html>
