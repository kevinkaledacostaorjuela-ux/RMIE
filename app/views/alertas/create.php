<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}
// Variables esperadas: $productos, $clientes, $mensaje
?>
<!DOCTYPE html>
<html>
<head>
    <title>Crear Alerta</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Crear Alerta</h1>
        <?php if (!empty($mensaje)): ?>
            <div class="alert"> <?= htmlspecialchars($mensaje) ?> </div>
        <?php endif; ?>
        <form method="POST" action="/RMIE/app/controllers/AlertController.php?action=create">
            <label>Producto:</label>
            <select name="id_productos" required>
                <option value="">Selecciona un producto</option>
                <?php foreach ($productos as $prod): ?>
                    <option value="<?= $prod->id_productos ?>"> <?= htmlspecialchars($prod->nombre) ?> </option>
                <?php endforeach; ?>
            </select><br>
            <label>Cantidad mínima para alerta:</label>
            <input type="number" name="cantidad_minima" min="1" required><br>
            <label>Fecha de caducidad:</label>
            <input type="date" name="fecha_caducidad" required><br>
            <label>Cliente:</label>
            <select name="id_clientes" required>
                <option value="">Selecciona un cliente</option>
                <?php if (!empty($clientes)) { foreach ($clientes as $cli): ?>
                    <option value="<?= $cli->id_clientes ?>"> <?= htmlspecialchars($cli->nombre) ?> </option>
                <?php endforeach; } ?>
            </select><br>
            <button type="submit" class="btn-categorias">Guardar</button>
            <a href="/RMIE/app/controllers/AlertController.php" class="btn-categorias">Cancelar</a>
        </form>
    </div>
</body>
</html>