<?php
require_once '../config/db.php';

// Obtener todas las categorías
$stmt = $pdo->query('SELECT * FROM categorias ORDER BY id DESC');
$categorias = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Categorías</title>
</head>
<body>
    <h1>Listado de categorías</h1>
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
                            <!-- Puedes agregar aquí un enlace para eliminar si lo necesitas -->
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