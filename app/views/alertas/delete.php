<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}
// Variables esperadas: $alerta, $errors, $success
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Alerta - RMIE</title>
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="delete-container">
        <h1>Eliminar Alerta #<?= isset($alerta['id_alertas']) ? htmlspecialchars($alerta['id_alertas']) : '' ?></h1>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"> <?= htmlspecialchars($success) ?> <br><small>Redirigiendo...</small></div>
            <script>setTimeout(function(){ window.location.href = '/RMIE/app/controllers/AlertController.php'; }, 1800);</script>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div>
            <strong>Producto:</strong> <?= htmlspecialchars($alerta['id_productos'] ?? '') ?><br>
            <strong>Cantidad mínima:</strong> <?= htmlspecialchars($alerta['cantidad_minima'] ?? '') ?><br>
            <strong>Fecha de caducidad:</strong> <?= htmlspecialchars($alerta['fecha_caducidad'] ?? '') ?><br>
            <strong>ID Cliente:</strong> <?= htmlspecialchars($alerta['id_clientes'] ?? '') ?><br>
        </div>
        <form method="POST" action="/RMIE/app/controllers/AlertController.php?action=delete&id=<?= urlencode($alerta['id_alertas']) ?>">
            <input type="hidden" name="confirmar_eliminar" value="1">
            <button type="submit" class="btn-categorias" onclick="return confirm('¿Está seguro de eliminar esta alerta?')">Eliminar Alerta</button>
            <a href="/RMIE/app/controllers/AlertController.php" class="btn-categorias">Volver</a>
        </form>
    </div>
</body>
</html>