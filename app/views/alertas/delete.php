<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../../index.php');
    exit();
}
require_once '../../models/Alert.php';
require_once '../../config/db.php';

$id = $_GET['id'] ?? 0;
$errors = [];
$success = '';
if (!$id) {
    header('Location: index.php');
    exit();
}
$alerta = Alert::getById($conn, $id);
if (!$alerta) {
    header('Location: index.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_eliminar'])) {
    $result = Alert::delete($conn, $id);
    if ($result) {
        $success = 'Alerta eliminada exitosamente';
        echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 2000);</script>";
    } else {
        $errors[] = 'Error al eliminar la alerta';
    }
}
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
        <h1>Eliminar Alerta #<?= isset($alerta['id_alertas']) ? $alerta['id_alertas'] : $id ?></h1>
        <?php if ($success): ?>
            <div class="alert alert-success"> <?= $success ?> <br><small>Redirigiendo...</small></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?><li><?= $error ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div>
            <strong>Producto:</strong> <?= htmlspecialchars($alerta['id_productos']) ?><br>
            <strong>Cantidad mínima:</strong> <?= htmlspecialchars($alerta['cantidad_minima']) ?><br>
            <strong>Fecha de caducidad:</strong> <?= htmlspecialchars($alerta['fecha_caducidad']) ?><br>
            <strong>ID Cliente:</strong> <?= htmlspecialchars($alerta['id_clientes']) ?><br>
        </div>
        <form method="POST">
            <input type="hidden" name="confirmar_eliminar" value="1">
            <button type="submit" class="btn-categorias" onclick="return confirm('¿Está seguro de eliminar esta alerta?')">Eliminar Alerta</button>
            <a href="index.php" class="btn-categorias">Volver</a>
        </form>
    </div>
</body>
</html>