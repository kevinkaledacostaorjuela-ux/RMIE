<?php
// ...existing code...
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Rutas</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <h1>Lista de Rutas</h1>
    <a href="create.php">Crear Nueva Ruta</a>
    <a href="/app/views/dashboard.php" class="btn btn-primary">Regresar al Dashboard</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Dirección</th>
                <th>Nombre Local</th>
                <th>Nombre Cliente</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($routes) && is_array($routes)): ?>
                <?php foreach ($routes as $route): ?>
                    <tr>
                        <td><?= $route['id_ruta'] ?></td>
                        <td><?= $route['direccion'] ?></td>
                        <td><?= $route['nombre_local'] ?></td>
                        <td><?= $route['nombre_cliente'] ?></td>
                        <td>
                            <a href="edit.php?id=<?= $route['id_ruta'] ?>">Editar</a>
                            <a href="delete.php?id=<?= $route['id_ruta'] ?>" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No hay rutas disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
