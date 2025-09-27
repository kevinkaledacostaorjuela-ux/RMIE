<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}
// Las variables $productos, $alertas y $filtro_producto vienen desde el controlador
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
        <form method="GET" action="/RMIE/app/controllers/AlertController.php">
            <input type="hidden" name="action" value="index" />
            <select name="producto">
                <option value="">Todos los productos</option>
                <?php foreach ($productos as $prod): ?>
                    <option value="<?= $prod->id_productos ?>" <?= ($filtro_producto == $prod->id_productos ? 'selected' : '') ?>><?= htmlspecialchars($prod->nombre) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filtrar</button>
        </form>
        <div style="margin: 12px 0;">
            <a href="/RMIE/app/controllers/AlertController.php?action=create" class="btn-categorias">Nueva Alerta</a>
            <a href="/RMIE/app/views/dashboard.php" class="btn-categorias">Volver al Dashboard</a>
        </div>
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
                <?php if (!empty($alertas)): ?>
                    <?php foreach ($alertas as $alerta): ?>
                    <tr>
                        <td><?= htmlspecialchars($alerta['id_alertas']) ?></td>
                        <td><?= htmlspecialchars($alerta['producto_nombre'] ?? $alerta['id_productos']) ?></td>
                        <td><?= htmlspecialchars($alerta['cantidad_minima'] ?? '') ?></td>
                        <td><?= htmlspecialchars($alerta['fecha_caducidad'] ?? '') ?></td>
                        <td><?= htmlspecialchars($alerta['id_clientes']) ?></td>
                        <td>
                            <a href="/RMIE/app/controllers/AlertController.php?action=edit&id=<?= urlencode($alerta['id_alertas']) ?>" class="btn-categorias">Editar</a>
                            <a href="/RMIE/app/controllers/AlertController.php?action=delete&id=<?= urlencode($alerta['id_alertas']) ?>" class="btn-categorias" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No hay alertas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
