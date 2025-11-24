<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

require_once __DIR__ . '/dashboard_card.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Reportes - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'admin'): ?>
        /* Ocultar botones de eliminar para roles que no sean admin */
        a[href*="accion=delete"],
        button[onclick*="delete"],
        button[onclick*="Eliminacion"],
        button[onclick*="eliminar"],
        .btn-danger[href*="delete"],
        .btn-danger-modern,
        button.btn-danger-modern {
            display: none !important;
        }
        <?php endif; ?>

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            margin: 30px auto;
            max-width: 1400px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
            margin: 0;
        }

        .btn-back {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            color: white;
        }

        .report-card {
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .report-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .card-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            color: white;
            text-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .card-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            font-weight: 500;
        }

        .card-arrow {
            position: absolute;
            bottom: 20px;
            right: 20px;
            font-size: 1.5rem;
            color: white;
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.3s ease;
        }

        .report-card:hover .card-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="page-header">
            <h1 class="page-title"><i class="fas fa-chart-line"></i> Dashboard de Reportes</h1>
            <a href="/RMIE/app/views/dashboard.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        <div class="row">
            <?php
            renderDashboardCard('Usuarios', 'fas fa-users', 'primary', '/RMIE/app/controllers/ReportController.php?action=usuarios');
            renderDashboardCard('Categorías', 'fas fa-folder', 'success', '/RMIE/app/controllers/ReportController.php?action=categorias');
            renderDashboardCard('Subcategorías', 'fas fa-sitemap', 'info', '/RMIE/app/controllers/ReportController.php?action=subcategorias');
            renderDashboardCard('Productos', 'fas fa-box', 'orange', '/RMIE/app/controllers/ReportController.php?action=productos');
            renderDashboardCard('Proveedores', 'fas fa-truck', 'warning', '/RMIE/app/controllers/ReportController.php?action=proveedores');
            renderDashboardCard('Clientes', 'fas fa-user-friends', 'purple', '/RMIE/app/controllers/ReportController.php?action=clientes');
            renderDashboardCard('Ventas', 'fas fa-shopping-cart', 'pink', '/RMIE/app/controllers/ReportController.php?action=ventas');
            renderDashboardCard('Rutas', 'fas fa-route', 'teal', '/RMIE/app/controllers/ReportController.php?action=rutas');
            renderDashboardCard('Locales', 'fas fa-store', 'indigo', '/RMIE/app/controllers/ReportController.php?action=locales');
            renderDashboardCard('Alertas', 'fas fa-bell', 'danger', '/RMIE/app/controllers/ReportController.php?action=alertas');
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
