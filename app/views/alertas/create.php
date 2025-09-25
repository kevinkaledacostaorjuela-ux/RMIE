<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../../index.php');
    exit();
}
require_once '../../models/Alert.php';
require_once '../../models/Product.php';
require_once '../../config/db.php';

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_producto = $_POST['id_productos'] ?? '';
    $cantidad_minima = $_POST['cantidad_minima'] ?? '';
    $fecha_caducidad = $_POST['fecha_caducidad'] ?? '';
    $id_cliente = $_POST['id_clientes'] ?? '';
    if ($id_producto && $cantidad_minima && $fecha_caducidad && $id_cliente) {
        $resultado = Alert::create($conn, $id_producto, $cantidad_minima, $fecha_caducidad, $id_cliente);
        if ($resultado) {
            $mensaje = '¡Alerta creada exitosamente!';
        } else {
            $mensaje = 'Error al crear la alerta.';
        }
    } else {
        $mensaje = 'Por favor, completa todos los campos.';
    }
}
$productos = Product::getAll($conn);
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
        <?php if ($mensaje): ?>
            <div class="alert"> <?= $mensaje ?> </div>
        <?php endif; ?>
        <form method="POST" action="">
            <label>Producto:</label>
            <select name="id_productos" required>
                <option value="">Selecciona un producto</option>
                <?php foreach ($productos as $prod): ?>
                    <option value="<?= $prod->id_productos ?>"> <?= $prod->nombre ?> </option>
                <?php endforeach; ?>
            </select><br>
            <label>Cantidad mínima para alerta:</label>
            <input type="number" name="cantidad_minima" min="1" required><br>
            <label>Fecha de caducidad:</label>
            <input type="date" name="fecha_caducidad" required><br>
            <label>ID Cliente:</label>
            <input type="text" name="id_clientes" required><br>
            <button type="submit" class="btn-categorias">Guardar</button>
        </form>
        <a href="index.php" class="btn-categorias">Volver al listado</a>
    </div>
</body>
</html>