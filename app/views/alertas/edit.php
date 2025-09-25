<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../../index.php');
    exit();
}

require_once '../../models/Alert.php';
require_once '../../models/Product.php';
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

// Obtener productos para el select
$productos = Product::getAll($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alerta['id_productos'] = $_POST['id_productos'] ?? '';
    $alerta['cantidad_minima'] = $_POST['cantidad_minima'] ?? '';
    $alerta['fecha_caducidad'] = $_POST['fecha_caducidad'] ?? '';
    $alerta['id_clientes'] = $_POST['id_clientes'] ?? '';

    if (empty($alerta['id_productos'])) $errors[] = 'El producto es obligatorio';
    if (empty($alerta['cantidad_minima'])) $errors[] = 'La cantidad mínima es obligatoria';
    if (empty($alerta['fecha_caducidad'])) $errors[] = 'La fecha de caducidad es obligatoria';
    if (empty($alerta['id_clientes'])) $errors[] = 'El cliente es obligatorio';

    if (empty($errors)) {
        $result = Alert::update($conn, $id, $alerta);
        if ($result) $success = 'Alerta actualizada exitosamente';
        else $errors[] = 'Error al actualizar la alerta';
    }
}
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
        <h1>Editar Alerta #<?= isset($alerta['id_alertas']) ? $alerta['id_alertas'] : $id ?></h1>
        <?php if ($success): ?>
            <div class="alert alert-success"> <?= $success ?> </div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?><li><?= $error ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form method="POST">
            <label>Producto:</label>
            <select name="id_productos" required>
                <option value="">Selecciona un producto</option>
                <?php foreach ($productos as $prod): ?>
                    <option value="<?= $prod->id_productos ?>" <?= $alerta['id_productos'] == $prod->id_productos ? 'selected' : '' ?>><?= $prod->nombre ?></option>
                <?php endforeach; ?>
            </select><br>
            <label>Cantidad mínima para alerta:</label>
            <input type="number" name="cantidad_minima" min="1" value="<?= htmlspecialchars($alerta['cantidad_minima']) ?>" required><br>
            <label>Fecha de caducidad:</label>
            <input type="date" name="fecha_caducidad" value="<?= htmlspecialchars($alerta['fecha_caducidad']) ?>" required><br>
            <label>ID Cliente:</label>
            <input type="text" name="id_clientes" value="<?= htmlspecialchars($alerta['id_clientes']) ?>" required><br>
            <button type="submit" class="btn-categorias">Guardar Cambios</button>
            <a href="index.php" class="btn-categorias">Cancelar</a>
        </form>
    </div>
</body>
</html>