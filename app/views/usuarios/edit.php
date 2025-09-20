<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Editar Usuario</h1>
        <form method="POST" action="">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= $usuario->nombre ?>" required><br>
            <label>Correo:</label>
            <input type="email" name="correo" value="<?= $usuario->correo ?>" required><br>
            <label>Contraseña:</label>
            <input type="password" name="contrasena" value="<?= $usuario->contrasena ?>" required><br>
            <label>Rol:</label>
            <input type="text" name="rol" value="<?= $usuario->rol ?>" required><br>
            <button type="submit" class="btn-categorias">Actualizar</button>
        </form>
        <a href="index.php" class="btn-categorias">Volver al listado</a>
    </div>
</body>
</html>
