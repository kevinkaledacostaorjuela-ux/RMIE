<!DOCTYPE html>
<html>
<head>
    <title>Crear Cliente</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Crear Cliente</h1>
    <form method="POST" action="create.php">
            <label>Nombre:</label>
            <input type="text" name="nombre" required><br>
            <label>Descripción:</label>
            <input type="text" name="descripcion" required><br>
            <label>Celular:</label>
            <input type="text" name="cel_cliente" required><br>
            <label>Correo:</label>
            <input type="email" name="correo" required><br>
            <label>Estado:</label>
            <input type="text" name="estado" required><br>
            <label>Local:</label>
            <input type="text" name="id_locales" required><br>
            <button type="submit" class="btn-categorias">Guardar</button>
        </form>
        <a href="index.php" class="btn-categorias">Volver al listado</a>
    </div>
</body>
</html>
