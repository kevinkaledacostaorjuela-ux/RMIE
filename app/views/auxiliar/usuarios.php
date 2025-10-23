<?php
// Vista de Consulta de Usuarios para Auxiliares
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que sea auxiliar
if (!isset($_SESSION['user']) || $_SESSION['rol'] !== 'auxiliar') {
    header('Location: ../../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Usuarios - Auxiliar RMIE</title>
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

        .page-title {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .role-info {
            background: rgba(32, 201, 151, 0.2);
            border: 1px solid rgba(32, 201, 151, 0.4);
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 25px;
            color: white;
            text-align: center;
        }

        .filters-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-control-modern {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            color: #333;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .table-modern {
            background: transparent;
            color: #fff;
        }

        .table-modern th {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border: none;
            padding: 15px 10px;
            font-weight: 600;
        }

        .table-modern td {
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 12px 10px;
            vertical-align: middle;
        }

        .table-modern tbody tr:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .badge-role {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
        }

        .badge-admin {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%);
        }

        .badge-coordinador {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .badge-auxiliar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .btn-modern {
            padding: 8px 16px;
            border-radius: 25px;
            border: none;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-back {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-2px);
            text-decoration: none;
        }

        .stats-row {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .stat-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            color: white;
            flex: 1;
            min-width: 150px;
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

        @media (max-width: 768px) {
            .dashboard-container {
                margin: 10px;
                padding: 20px;
            }

            .page-title {
                font-size: 2rem;
            }

            .stats-row {
                flex-direction: column;
            }

            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1 class="page-title">
            <i class="fas fa-users"></i> Consulta de Usuarios
        </h1>

        <div class="role-info">
            <i class="fas fa-eye"></i> <strong>Modo Solo Lectura:</strong> Puedes consultar la información pero no realizar modificaciones
        </div>

        <!-- Estadísticas -->
        <?php if (isset($stats)): ?>
        <div class="stats-row">
            <div class="stat-box">
                <div class="stat-number"><?= $stats['total'] ?? 0 ?></div>
                <div class="stat-label">Total Usuarios</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?= $stats['administradores'] ?? 0 ?></div>
                <div class="stat-label">Administradores</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?= $stats['coordinadores'] ?? 0 ?></div>
                <div class="stat-label">Coordinadores</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?= $stats['auxiliares'] ?? 0 ?></div>
                <div class="stat-label">Auxiliares</div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Filtros -->
        <div class="filters-container">
            <form method="GET" action="">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-search"></i> Buscar
                        </label>
                        <input type="text" 
                               name="buscar" 
                               class="form-control form-control-modern" 
                               placeholder="Buscar por nombre o email..."
                               value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-user-tag"></i> Rol
                        </label>
                        <select name="rol" class="form-control form-control-modern">
                            <option value="">Todos los roles</option>
                            <option value="admin" <?= ($_GET['rol'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
                            <option value="coordinador" <?= ($_GET['rol'] ?? '') === 'coordinador' ? 'selected' : '' ?>>Coordinador</option>
                            <option value="auxiliar" <?= ($_GET['rol'] ?? '') === 'auxiliar' ? 'selected' : '' ?>>Auxiliar</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-modern btn-back">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabla de Usuarios -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-modern table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-id-card"></i> Documento</th>
                            <th><i class="fas fa-user"></i> Nombre Completo</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-phone"></i> Teléfono</th>
                            <th><i class="fas fa-user-tag"></i> Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($usuarios) && !empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($usuario->tipo_doc ?? 'CC') ?>:</strong>
                                    <?= htmlspecialchars($usuario->num_doc) ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #28a745, #20c997); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                                <?= strtoupper(substr($usuario->nombres ?? 'U', 0, 1)) ?>
                                            </div>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($usuario->nombres . ' ' . $usuario->apellidos) ?></strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($usuario->correo)): ?>
                                        <i class="fas fa-envelope text-info"></i>
                                        <?= htmlspecialchars($usuario->correo) ?>
                                    <?php else: ?>
                                        <span class="text-muted">Sin email</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($usuario->num_cel)): ?>
                                        <i class="fas fa-phone text-success"></i>
                                        <?= htmlspecialchars($usuario->num_cel) ?>
                                    <?php else: ?>
                                        <span class="text-muted">Sin teléfono</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge-role badge-<?= $usuario->rol ?>">
                                        <?php
                                        $roleIcons = [
                                            'admin' => 'fas fa-user-shield',
                                            'coordinador' => 'fas fa-user-tie',
                                            'auxiliar' => 'fas fa-user'
                                        ];
                                        $roleNames = [
                                            'admin' => 'Administrador',
                                            'coordinador' => 'Coordinador',
                                            'auxiliar' => 'Auxiliar'
                                        ];
                                        ?>
                                        <i class="<?= $roleIcons[$usuario->rol] ?? 'fas fa-user' ?>"></i>
                                        <?= $roleNames[$usuario->rol] ?? ucfirst($usuario->rol) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <h5>No hay usuarios disponibles</h5>
                                        <p>No se encontraron usuarios que coincidan con los filtros aplicados.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Navegación -->
        <div class="text-center mt-4">
            <a href="/RMIE/app/controllers/AuxiliarController.php?accion=dashboard" class="btn btn-modern btn-back">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </div>

    <!-- Error/Success Messages -->
    <?php if (isset($error)): ?>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
        </div>
    </div>
    <?php endif; ?>
</body>
</html>