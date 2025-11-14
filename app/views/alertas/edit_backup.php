<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}
// Variables: $alerta, $productos, $errors, $success provienen del controlador
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Alerta - RMIE</title>
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="form-container">
        <h1>Editar Alerta #<?= isset($alerta['id_alertas']) ? htmlspecialchars($alerta['id_alertas']) : '' ?></h1>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"> <?= htmlspecialchars($success) ?> </div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form method="POST" action="/RMIE/app/controllers/AlertController.php?action=edit&id=<?= urlencode($alerta['id_alertas']) ?>">
            <label>Producto:</label>
            <select name="id_productos" required>
                <option value="">Selecciona un producto</option>
                <?php foreach ($productos as $prod): ?>
                    <option value="<?= $prod->id_productos ?>" <?= ($alerta['id_productos'] == $prod->id_productos ? 'selected' : '') ?>><?= htmlspecialchars($prod->nombre) ?></option>
                <?php endforeach; ?>
            </select><br>
            <label>Cantidad mínima para alerta:</label>
            <input type="number" name="cantidad_minima" min="1" value="<?= htmlspecialchars($alerta['cantidad_minima']) ?>" required><br>
            <label>Fecha de caducidad:</label>
            <input type="date" name="fecha_caducidad" value="<?= htmlspecialchars($alerta['fecha_caducidad']) ?>" required><br>
            <label>Cliente:</label>
            <select name="id_clientes" required>
                <option value="">Selecciona un cliente</option>
                <?php if (!empty($clientes)) { foreach ($clientes as $cli): ?>
                    <option value="<?= $cli->id_clientes ?>" <?= ($alerta['id_clientes'] == $cli->id_clientes ? 'selected' : '') ?>><?= htmlspecialchars($cli->nombre) ?></option>
                <?php endforeach; } ?>
            </select><br>
            <button type="submit" class="btn-categorias">Guardar Cambios</button>
            <a href="/RMIE/app/controllers/AlertController.php" class="btn-categorias">Cancelar</a>
        </form>
    </div>
</body>
</html>