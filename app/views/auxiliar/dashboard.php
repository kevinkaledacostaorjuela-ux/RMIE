<?php
// Vista del Dashboard para Auxiliares
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que sea auxiliar
if (!isset($_SESSION['user']) || $_SESSION['rol'] !== 'auxiliar') {
    header('Location: ../../index.php');
    exit();
}

$usuario_nombre = $_SESSION['nombres'] ?? 'Auxiliar';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Auxiliar - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
            padding: 30px;
            margin: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .welcome-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .role-badge {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            color: white;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.15);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #28a745;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .modules-section {
            margin-bottom: 40px;
        }

        .section-title {
            color: white;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 25px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .module-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
            display: block;
        }

        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
        }

        .module-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #28a745;
        }

        .module-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .module-description {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 10px;
        }

        .case-use-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin-top: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .activity-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin-bottom: 10px;
            color: white;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .activity-venta {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .activity-reporte {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        }

        .btn-navigation {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-navigation:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        .alert-info-aux {
            background: rgba(32, 201, 151, 0.2);
            border: 1px solid rgba(32, 201, 151, 0.4);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            color: white;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                margin: 10px;
                padding: 20px;
            }
            
            .welcome-title {
                font-size: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .modules-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sección de Bienvenida -->
        <div class="welcome-section">
            <h1 class="welcome-title">
                <i class="fas fa-user"></i>
                Bienvenido, <?= htmlspecialchars($usuario_nombre) ?>
            </h1>
            <p class="welcome-subtitle">Panel de Consultas - Sistema RMIE</p>
            <div class="role-badge">
                <i class="fas fa-eye"></i>
                Auxiliar - Solo Lectura
            </div>
        </div>

        <!-- Alerta informativa -->
        <div class="alert-info-aux">
            <h6><i class="fas fa-info-circle"></i> Permisos del Rol Auxiliar</h6>
            <p>Según el diagrama de casos de uso, tienes acceso a:</p>
            <ul style="margin: 10px 0 0 20px;">
                <li><strong>CU2 - Gestionar usuarios:</strong> Solo consulta de información</li>
                <li><strong>CU6 - Gestión de ventas:</strong> Consulta de historial de ventas</li>
                <li><strong>CU8 - Gestión de reportes:</strong> Visualización de reportes</li>
                <li><strong>Consultar/Modificar:</strong> Tu perfil personal únicamente</li>
            </ul>
        </div>

        <!-- Estadísticas Rápidas -->
        <?php if (isset($stats)): ?>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-number"><?= $stats['total_usuarios'] ?? 0 ?></div>
                <div class="stat-label">Usuarios Totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-user-friends"></i></div>
                <div class="stat-number"><?= $stats['total_clientes'] ?? 0 ?></div>
                <div class="stat-label">Clientes Registrados</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-box"></i></div>
                <div class="stat-number"><?= $stats['total_productos'] ?? 0 ?></div>
                <div class="stat-label">Productos Disponibles</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="stat-number"><?= $stats['ventas_hoy'] ?? 0 ?></div>
                <div class="stat-label">Ventas Hoy</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-number"><?= $stats['reportes_pendientes'] ?? 0 ?></div>
                <div class="stat-label">Reportes Pendientes</div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Módulos Disponibles según Diagrama de Casos de Uso -->
        <div class="modules-section">
            <h2 class="section-title">
                <i class="fas fa-th-large"></i>
                Módulos Autorizados
            </h2>
            <div class="modules-grid">
                <?php
                require_once __DIR__ . '/../../utils/PermissionsConfig.php';
                $modulos_auxiliar = PermissionsConfig::getAuxiliarModules();
                foreach ($modulos_auxiliar as $key => $module):
                ?>
                <a href="<?= $module['url'] ?>" class="module-card">
                    <div class="module-icon"><i class="<?= $module['icon'] ?>"></i></div>
                    <div class="module-title"><?= $module['title'] ?></div>
                    <div class="module-description"><?= $module['description'] ?></div>
                    <div class="case-use-badge"><?= $module['case_use'] ?></div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Actividad Reciente -->
        <?php if (isset($actividad_reciente) && !empty($actividad_reciente)): ?>
        <div class="activity-section">
            <h3 class="section-title">
                <i class="fas fa-clock"></i>
                Actividad Reciente
            </h3>
            <?php foreach (array_slice($actividad_reciente, 0, 5) as $actividad): ?>
            <div class="activity-item">
                <div class="activity-icon activity-<?= $actividad['tipo'] ?>">
                    <i class="fas fa-<?= $actividad['tipo'] === 'venta' ? 'shopping-cart' : 'chart-bar' ?>"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-description"><?= htmlspecialchars($actividad['descripcion']) ?></div>
                    <div class="activity-date"><?= date('d/m/Y H:i', strtotime($actividad['fecha'])) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Navegación -->
        <div class="text-center">
            <a href="/RMIE/logout.php" class="btn-navigation">
                <i class="fas fa-sign-out-alt"></i>
                Cerrar Sesión
            </a>
        </div>
    </div>
</body>
</html>