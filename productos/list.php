<?php
require_once '../config/db.php';
require_once '../models/Producto.php';
require_once '../models/Categoria.php';

$mensaje = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'creado') {
        $mensaje = 'Producto creado correctamente.';
    } elseif ($_GET['msg'] === 'actualizado') {
        $mensaje = 'Producto actualizado correctamente.';
    } elseif ($_GET['msg'] === 'eliminado') {
        $mensaje = 'Producto eliminado correctamente.';
    } elseif ($_GET['msg'] === 'error') {
        $mensaje = 'Error al eliminar el producto.';
    }
}

$productos = Producto::all($pdo);
$categorias = [];
foreach (Categoria::all($pdo) as $cat) {
    $categorias[$cat['id']] = $cat['nombre'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Productos</title>
</head>
<body>
    <h1>Listado de productos</h1>
    <?php if ($mensaje): ?>
        <p style="color:green;"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    <a href="create.php">Crear nuevo producto</a>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($productos): ?>
                <?php foreach ($productos as $prod): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($prod['id']); ?></td>
                        <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($categorias[$prod['categoria_id']] ?? ''); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $prod['id']; ?>">Editar</a>
                            <a href="delete.php?id=<?php echo $prod['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar este producto?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No hay productos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
