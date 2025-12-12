<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

// Limpiar mensajes de sesión
$success_message = $_SESSION['success'] ?? '';
$error_message = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papelera de Alertas - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            margin: 20px auto;
            max-width: 1400px;
            width: calc(100% - 40px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .page-title {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .alert-message {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            color: #fff;
            animation: slideDown 0.3s ease;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.3);
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.3);
            border-left: 4px solid #dc3545;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .trash-table {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow-x: auto;
        }

        .trash-table table {
            color: #fff;
            margin-bottom: 0;
        }

        .trash-table th {
            background: rgba(0, 0, 0, 0.3);
            border-color: rgba(255, 255, 255, 0.2);
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            font-weight: 600;
            padding: 15px;
        }

        .trash-table td {
            border-color: rgba(255, 255, 255, 0.1);
            padding: 15px;
            vertical-align: middle;
        }

        .trash-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .btn-modern-sm {
            padding: 6px 12px;
            font-size: 0.85rem;
            border-radius: 8px;
            border: none;
            color: #fff;
            transition: all 0.3s ease;
            display: inline-block;
            margin-right: 5px;
        }

        .btn-restore {
            background: rgba(40, 167, 69, 0.6);
        }

        .btn-restore:hover {
            background: rgba(40, 167, 69, 0.8);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
        }

        .btn-back {
            background: rgba(108, 117, 125, 0.6);
            padding: 12px 24px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 20px;
        }

        .btn-back:hover {
            background: rgba(108, 117, 125, 0.8);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #fff;
        }

        .empty-state i {
            font-size: 4rem;
            opacity: 0.6;
            margin-bottom: 20px;
            display: block;
        }

        .badge-eliminated {
            background: rgba(220, 53, 69, 0.6);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1 class="page-title">
            <i class="fas fa-trash"></i> Papelera de Alertas
        </h1>

        <?php if (!empty($success_message)): ?>
        <div class="alert-message alert-success">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success_message) ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
        <div class="alert-message alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error_message) ?>
        </div>
        <?php endif; ?>

        <a href="/RMIE/app/controllers/AlertController.php?accion=index" class="btn btn-modern btn-back">
            <i class="fas fa-arrow-left"></i> Volver a Alertas
        </a>

        <div class="trash-table">
            <?php if (!empty($alertas_papelera) && is_array($alertas_papelera)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th><i class="fas fa-box"></i> Producto</th>
                        <th><i class="fas fa-tag"></i> Tipo</th>
                        <th><i class="fas fa-truck"></i> Proveedor</th>
                        <th><i class="fas fa-calendar"></i> Fecha de Caducidad</th>
                        <th><i class="fas fa-trash"></i> Motivo</th>
                        <th><i class="fas fa-clock"></i> Eliminada</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alertas_papelera as $alerta): ?>
                    <tr>
                        <td>
                            <i class="fas fa-box" style="margin-right: 8px;"></i>
                            <?= htmlspecialchars($alerta['producto_nombre'] ?? 'Producto #' . $alerta['id_productos']) ?>
                        </td>
                        <td>
                            <?php if ($alerta['tipo_alerta'] === 'stock' || $alerta['tipo_alerta'] === 'stock_bajo'): ?>
                                <span style="background: rgba(102, 126, 234, 0.5); padding: 5px 12px; border-radius: 20px; font-size: 0.85rem;">
                                    <i class="fas fa-boxes"></i> Stock
                                </span>
                            <?php else: ?>
                                <span style="background: rgba(240, 147, 251, 0.5); padding: 5px 12px; border-radius: 20px; font-size: 0.85rem;">
                                    <i class="fas fa-calendar-times"></i> Vencimiento
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($alerta['proveedor_nombre'] ?? 'Sin proveedor') ?>
                        </td>
                        <td>
                            <?php if ($alerta['fecha_caducidad']): ?>
                                <?= date('d/m/Y', strtotime($alerta['fecha_caducidad'])) ?>
                            <?php else: ?>
                                <span style="opacity: 0.6;">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small style="opacity: 0.8;"><?= htmlspecialchars($alerta['motivo_eliminacion']) ?></small>
                        </td>
                        <td>
                            <small style="opacity: 0.8;">
                                <?= date('d/m/Y H:i', strtotime($alerta['fecha_eliminacion'])) ?>
                            </small>
                        </td>
                        <td style="text-align: center;">
                            <a href="/RMIE/app/controllers/AlertController.php?accion=trash&restaurar=<?= $alerta['id_papelera'] ?>" 
                               class="btn btn-modern-sm btn-restore"
                               title="Restaurar alerta"
                               onclick="return confirm('¿Deseas restaurar esta alerta?');">
                                <i class="fas fa-undo"></i> Restaurar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h4>Papelera Vacía</h4>
                <p style="opacity: 0.8;">No hay alertas eliminadas en la papelera</p>
                <a href="/RMIE/app/controllers/AlertController.php?accion=index" class="btn btn-modern btn-back" style="margin-top: 20px;">
                    <i class="fas fa-arrow-left"></i> Volver a Alertas
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
