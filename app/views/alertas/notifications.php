<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones de Alertas - RMIE</title>
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
            max-width: 1000px;
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

        .notification-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            color: #fff;
            transition: all 0.3s ease;
            animation: slideUp 0.4s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .notification-card:hover {
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .notification-card.unread {
            border-left: 4px solid #ffc107;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }

        .notification-type {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .notification-type.vencimiento_proximo {
            background: rgba(255, 193, 7, 0.6);
        }

        .notification-message {
            font-size: 1.1rem;
            margin-bottom: 12px;
            line-height: 1.5;
        }

        .notification-time {
            font-size: 0.9rem;
            opacity: 0.7;
            color: rgba(255, 255, 255, 0.8);
        }

        .notification-actions {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            gap: 10px;
        }

        .btn-modern-sm {
            padding: 6px 12px;
            font-size: 0.85rem;
            border-radius: 8px;
            border: none;
            color: #fff;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-mark-read {
            background: rgba(40, 167, 69, 0.6);
        }

        .btn-mark-read:hover {
            background: rgba(40, 167, 69, 0.8);
            transform: translateY(-2px);
        }

        .btn-back {
            background: rgba(108, 117, 125, 0.6);
            padding: 12px 24px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .btn-back:hover {
            background: rgba(108, 117, 125, 0.8);
            transform: translateY(-2px);
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

        .notification-badge {
            display: inline-block;
            background: rgba(255, 193, 7, 0.8);
            color: #000;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1 class="page-title">
            <i class="fas fa-bell"></i> Notificaciones de Alertas
            <?php if (!empty($notificaciones)): ?>
            <span class="notification-badge"><?= count($notificaciones) ?></span>
            <?php endif; ?>
        </h1>

        <a href="/RMIE/app/controllers/AlertController.php?accion=index" class="btn btn-modern btn-back">
            <i class="fas fa-arrow-left"></i> Volver a Alertas
        </a>

        <?php if (!empty($notificaciones) && is_array($notificaciones)): ?>
            <?php foreach ($notificaciones as $notif): ?>
            <div class="notification-card unread">
                <div class="notification-header">
                    <div>
                        <span class="notification-type notification-type.<?= htmlspecialchars($notif['tipo_notificacion']) ?>">
                            <i class="fas fa-exclamation-triangle"></i>
                            <?php 
                            if ($notif['tipo_notificacion'] === 'vencimiento_proximo') {
                                echo 'Próximo a Vencer';
                            } else {
                                echo htmlspecialchars($notif['tipo_notificacion']);
                            }
                            ?>
                        </span>
                    </div>
                    <div class="notification-time">
                        <i class="fas fa-clock"></i>
                        <?php 
                        $date = new DateTime($notif['fecha_creacion']);
                        $now = new DateTime();
                        $diff = $now->diff($date);
                        
                        if ($diff->d > 0) {
                            echo "Hace " . $diff->d . " día" . ($diff->d > 1 ? "s" : "");
                        } elseif ($diff->h > 0) {
                            echo "Hace " . $diff->h . " hora" . ($diff->h > 1 ? "s" : "");
                        } else {
                            echo "Hace " . $diff->i . " minuto" . ($diff->i > 1 ? "s" : "");
                        }
                        ?>
                    </div>
                </div>

                <div class="notification-message">
                    <i class="fas fa-info-circle"></i> <?= htmlspecialchars($notif['mensaje']) ?>
                </div>

                <div class="notification-actions">
                    <a href="/RMIE/app/controllers/AlertController.php?accion=notifications&marcar_leida=<?= $notif['id_notificacion'] ?>" 
                       class="btn btn-modern-sm btn-mark-read"
                       title="Marcar como leída">
                        <i class="fas fa-check"></i> Marcar como Leída
                    </a>
                    <a href="/RMIE/app/controllers/AlertController.php?accion=index" 
                       class="btn btn-modern-sm" 
                       style="background: rgba(108, 117, 125, 0.6);"
                       title="Ir a alertas">
                        <i class="fas fa-eye"></i> Ver Alertas
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-bell-slash"></i>
                <h4>No hay Notificaciones</h4>
                <p style="opacity: 0.8;">Todas las notificaciones han sido leídas</p>
                <a href="/RMIE/app/controllers/AlertController.php?accion=index" class="btn btn-modern btn-back" style="margin-top: 20px;">
                    <i class="fas fa-arrow-left"></i> Volver a Alertas
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
