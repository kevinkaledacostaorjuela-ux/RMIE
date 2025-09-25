<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../../index.php');
    exit();
}
require_once '../../models/Alert.php';
require_once '../../models/Product.php';
require_once '../../config/db.php';

// Filtro por producto
$filtro_producto = $_GET['producto'] ?? '';
$alertas = Alert::getFiltered($conn, $filtro_producto);
$productos = Product::getAll($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Alertas - RMIE</title>
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <h1 class="page-title">Gestión de Alertas</h1>
        <form method="GET">
            <select name="producto">
                <option value="">Todos los productos</option>
                <?php foreach ($productos as $prod): ?>
                    <option value="<?= $prod->id_productos ?>" <?= $filtro_producto == $prod->id_productos ? 'selected' : '' ?>><?= $prod->nombre ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filtrar</button>
        </form>
        <a href="create.php" class="btn-categorias">Nueva Alerta</a>
        <a href="../../dashboard.php" class="btn-categorias">Volver al Dashboard</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Cantidad mínima</th>
                    <th>Fecha caducidad</th>
                    <th>ID Cliente</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alertas as $alerta): ?>
                <tr>
                    <td><?= $alerta['id_alertas'] ?></td>
                    <td><?= htmlspecialchars($alerta['producto_nombre'] ?? $alerta['id_productos']) ?></td>
                    <td><?= htmlspecialchars($alerta['cantidad_minima']) ?></td>
                    <td><?= htmlspecialchars($alerta['fecha_caducidad']) ?></td>
                    <td><?= htmlspecialchars($alerta['id_clientes']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $alerta['id_alertas'] ?>" class="btn-categorias">Editar</a>
                        <a href="delete.php?id=<?= $alerta['id_alertas'] ?>" class="btn-categorias" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
