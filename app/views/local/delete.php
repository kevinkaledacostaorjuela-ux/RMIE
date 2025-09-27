<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../models/Local.php';

$id = $_GET['id'] ?? 0;

if (!$id) {
    header('Location: /RMIE/app/controllers/LocalController.php?action=index');
    exit();
}

try {
    $local = Local::getById($conn, $id);
    if ($local && Local::canDelete($conn, $id)) {
        Local::delete($conn, $id);
    }
} catch (Exception $e) {
    // Si ocurre un error, simplemente redirige
}
header('Location: /RMIE/app/controllers/LocalController.php?action=index');
exit();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Local</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Eliminar Local</h2>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $err): ?>
                    <div><?= htmlspecialchars($err) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if ($local && $canDelete && !$success): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">¿Seguro que deseas eliminar el local?</h5>
                    <p><strong>Nombre:</strong> <?= htmlspecialchars($local->nombre_local) ?></p>
                    <p><strong>Dirección:</strong> <?= htmlspecialchars($local->direccion) ?></p>
                    <p><strong>Localidad:</strong> <?= htmlspecialchars($local->localidad) ?></p>
                    <form method="post">
                        <button type="submit" name="confirmar_eliminar" class="btn btn-danger">Eliminar</button>
                        <a href="/RMIE/app/controllers/LocalController.php?action=index" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        <?php elseif ($local && !$canDelete): ?>
            <div class="alert alert-warning">
                No se puede eliminar este local porque tiene datos relacionados (clientes o productos asociados).
            </div>
            <a href="/RMIE/app/controllers/LocalController.php?action=index" class="btn btn-secondary">Volver</a>
        <?php endif; ?>
    </div>
    <script>
    window.addEventListener('load', function() {
        var loadingScreen = document.getElementById('loadingScreen');
        if (loadingScreen) {
            loadingScreen.style.opacity = '0';
            setTimeout(function() {
                loadingScreen.style.display = 'none';
            }, 500);
        }
    });
    </script>
</body>
</html>