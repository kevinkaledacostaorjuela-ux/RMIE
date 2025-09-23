<?php
require_once '../config/db.php';
require_once '../models/Categoria.php';

// Mensaje de éxito según acción
$mensaje = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'creada') {
        $mensaje = 'Categoría creada correctamente.';
    } elseif ($_GET['msg'] === 'actualizada') {
        $mensaje = 'Categoría actualizada correctamente.';
    } elseif ($_GET['msg'] === 'eliminada') {
        $mensaje = 'Categoría eliminada correctamente.';
    } elseif ($_GET['msg'] === 'error') {
        $mensaje = 'Error al eliminar la categoría.';
    }
}

// Obtener todas las categorías
$categorias = Categoria::all($pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Categorías</title>
</head>
<body>
    <h1>Listado de categorías</h1>
    <?php if ($mensaje): ?>
        <p style="color:green;"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    <a href="create.php">Crear nueva categoría</a>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($categorias): ?>
                <?php foreach ($categorias as $cat): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cat['id']); ?></td>
                        <td><?php echo htmlspecialchars($cat['nombre']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $cat['id']; ?>">Editar</a>
                            <a href="delete.php?id=<?php echo $cat['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar esta categoría?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No hay categorías registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>