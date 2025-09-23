<?php
require_once '../config/db.php';
require_once '../models/Categoria.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    if (!empty($nombre)) {
        if (Categoria::create($pdo, $nombre)) {
            header('Location: list.php?msg=creada');
            exit;
        } else {
            $mensaje = 'Error al crear la categoría.';
        }
    } else {
        $mensaje = 'El nombre de la categoría es obligatorio.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Categoría</title>
</head>
<body>
    <h1>Crear nueva categoría</h1>
    <?php if ($mensaje): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="nombre">Nombre de la categoría:</label>
        <input type="text" name="nombre" id="nombre" required>
        <button type="submit">Crear</button>
    </form>
    <a href="list.php">Volver al listado</a>
</body>
</html>