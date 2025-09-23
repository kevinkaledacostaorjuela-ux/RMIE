<?php
require_once '../config/db.php';

$mensaje = '';
$id = $_GET['id'] ?? null;

// Si no hay ID, redirigir al listado
if (!$id) {
    header('Location: index.php');
    exit();
}

// Obtener la categoría actual
$stmt = $pdo->prepare('SELECT * FROM categorias WHERE id = :id');
$stmt->execute(['id' => $id]);
$categoria = $stmt->fetch();

if (!$categoria) {
    header('Location: index.php');
    exit();
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');

    if (!empty($nombre)) {
        $stmt = $pdo->prepare('UPDATE categorias SET nombre = :nombre WHERE id = :id');
        if ($stmt->execute(['nombre' => $nombre, 'id' => $id])) {
            $mensaje = 'Categoría actualizada correctamente.';
            // Actualizar el nombre mostrado
            $categoria['nombre'] = $nombre;
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
    <a href="index.php">Volver al listado</a>
</body>
</html>