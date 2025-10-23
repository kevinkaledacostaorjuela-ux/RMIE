<?php
// Vista de Consulta de Clientes para Auxiliares (solo lectura)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    <title>Consulta de Clientes - Auxiliar RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../../../../public/css/styles.css" rel="stylesheet">
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

        .badge-modern {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-success {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
        }

        .badge-danger {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        }

        .btn-back {
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
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-2px);
            text-decoration: none;
        }

        .client-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                margin: 10px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1 class="page-title">
            <i class="fas fa-user-friends"></i> Consulta de Clientes
        </h1>

        <div class="role-info">
            <i class="fas fa-eye"></i> <strong>Modo Solo Lectura:</strong> Puedes consultar la información de clientes pero no realizar modificaciones
        </div>

        <!-- Tabla de Clientes -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-modern table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-user"></i> Cliente</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-phone"></i> Teléfono</th>
                            <th><i class="fas fa-store"></i> Local</th>
                            <th><i class="fas fa-toggle-on"></i> Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($clientes) && !empty($clientes)): ?>
                            <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><strong>#<?= htmlspecialchars($cliente->id_clientes) ?></strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="client-icon">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($cliente->nombre) ?></strong>
                                            <?php if (!empty($cliente->descripcion)): ?>
                                                <br><small class="text-muted"><?= htmlspecialchars($cliente->descripcion) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($cliente->correo)): ?>
                                        <i class="fas fa-envelope text-info"></i>
                                        <small><?= htmlspecialchars($cliente->correo) ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">Sin email</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($cliente->cel_cliente)): ?>
                                        <i class="fas fa-phone text-success"></i>
                                        <small><?= htmlspecialchars($cliente->cel_cliente) ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">Sin teléfono</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($cliente->local_nombre)): ?>
                                        <span class="badge badge-modern badge-success">
                                            <i class="fas fa-store"></i> <?= htmlspecialchars($cliente->local_nombre) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">Sin local</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($cliente->estado === 'activo'): ?>
                                        <span class="badge badge-modern badge-success">
                                            <i class="fas fa-check-circle"></i> Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-modern badge-danger">
                                            <i class="fas fa-times-circle"></i> Inactivo
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <h5>No hay clientes disponibles</h5>
                                        <p>No se encontraron clientes en el sistema.</p>
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
            <a href="/RMIE/app/controllers/AuxiliarController.php?accion=dashboard" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </div>

    <?php if (isset($error)): ?>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
        </div>
    </div>
    <?php endif; ?>
</body>
</html>