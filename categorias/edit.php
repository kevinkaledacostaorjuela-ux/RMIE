<?php
require_once '../config/db.php';
require_once '../models/Categoria.php';

$mensaje = '';
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: list.php');
    exit();
}

$categoria = Categoria::find($pdo, $id);
if (!$categoria) {
    header('Location: list.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    if (!empty($nombre)) {
        if (Categoria::update($pdo, $id, $nombre)) {
            header('Location: list.php?msg=actualizada');
            exit;
        } else {
            $mensaje = 'Error al actualizar la categoría.';
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
    <title>Editar Categoría</title>
</head>
<body>
    <h1>Editar categoría</h1>
    <?php if ($mensaje): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="nombre">Nombre de la categoría:</label>
        <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($categoria['nombre']); ?>" required>
        <button type="submit">Actualizar</button>
    </form>
    <a href="list.php">Volver al listado</a>
</body>
</html>