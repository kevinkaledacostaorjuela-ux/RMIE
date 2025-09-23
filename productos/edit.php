<?php
require_once '../config/db.php';
require_once '../models/Producto.php';
require_once '../models/Categoria.php';

$mensaje = '';
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: list.php');
    exit();
}

$producto = Producto::find($pdo, $id);
if (!$producto) {
    header('Location: list.php');
    exit();
}
$categorias = Categoria::all($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $categoria_id = $_POST['categoria_id'] ?? '';
    if (!empty($nombre) && !empty($categoria_id)) {
        if (Producto::update($pdo, $id, $nombre, $categoria_id)) {
            header('Location: list.php?msg=actualizado');
            exit;
        } else {
            $mensaje = 'Error al actualizar el producto.';
        }
    } else {
        $mensaje = 'Todos los campos son obligatorios.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
</head>
<body>
    <h1>Editar producto</h1>
    <?php if ($mensaje): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="nombre">Nombre del producto:</label>
        <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
        <br>
        <label for="categoria_id">Categoría:</label>
        <select name="categoria_id" id="categoria_id" required>
            <option value="">Seleccione una categoría</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?php echo $cat['id']; ?>" <?php if ($producto['categoria_id'] == $cat['id']) echo 'selected'; ?>><?php echo htmlspecialchars($cat['nombre']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <button type="submit">Actualizar</button>
    </form>
    <a href="list.php">Volver al listado</a>
</body>
</html>
