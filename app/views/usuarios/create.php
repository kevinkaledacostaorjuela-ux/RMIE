<!DOCTYPE html>
<html>
<head>
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Crear Usuario</h1>
    <form method="POST" action="create.php">
            <label>Nombre:</label>
            <input type="text" name="nombre" required><br>
            <label>Correo:</label>
            <input type="email" name="correo" required><br>
            <label>Contraseña:</label>
            <input type="password" name="contrasena" required><br>
            <label>Rol:</label>
            <input type="text" name="rol" required><br>
            <button type="submit" class="btn-categorias">Guardar</button>
        </form>
        <a href="index.php" class="btn-categorias">Volver al listado</a>
    </div>
</body>
</html>
