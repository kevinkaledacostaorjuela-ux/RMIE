<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../../index.php');
    exit();
}

require_once '../../config/db.php';
require_once __DIR__ . '/../../../app/models/Local.php';

$filtros = [
    'nombre' => $_GET['nombre'] ?? '',
    'estado' => $_GET['estado'] ?? '',
    'buscar' => $_GET['buscar'] ?? '',
    'tipo' => $_GET['tipo'] ?? ''
];

// Usar la conexión global y obtener locales
$locales = Local::getAll($conn, $filtros);

// Obtener estadísticas básicas
$statsQuery = $conn->query("SELECT 
    COUNT(*) as total_locales,
    SUM(CASE WHEN estado = 'activo' THEN 1 ELSE 0 END) as locales_activos
    FROM locales");
$stats = $statsQuery->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Locales - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../../public/css/styles.css" rel="stylesheet">
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .stat-card:hover::before {
            left: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 10px;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            font-weight: 500;
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
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            padding: 15px 10px;
        }

        .table-modern td {
            border: none;
            padding: 15px 10px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .table-modern tbody tr {
            transition: all 0.3s ease;
        }

        .table-modern tbody tr:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: scale(1.02);
        }

        .btn-modern {
            padding: 8px 16px;
            border-radius: 25px;
            border: none;
            font-weight: 500;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.8rem;
        }

        .btn-primary-modern {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .btn-success-modern {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .btn-warning-modern {
            background: linear-gradient(45deg, #ff9a9e, #fecfef);
            color: white;
        }

        .btn-danger-modern {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            color: white;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .filters-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-control-modern {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            color: #fff;
            padding: 10px 15px;
        }

        .form-control-modern::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control-modern:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: #4facfe;
            box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
            color: #fff;
        }

        .badge-status {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-activo {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }

        .badge-inactivo {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }

        .page-title {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .loading-content {
            text-align: center;
            color: white;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-content">
            <div class="spinner"></div>
            <h3>Cargando Gestión de Locales...</h3>
            <p>Preparando el dashboard</p>
        </div>
    </div>

    <div class="dashboard-container">
        <h1 class="page-title floating">
            <i class="fas fa-building"></i> Gestión de Locales
        </h1>

        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-number"><?php echo $stats['total_locales']; ?></div>
                <div class="stat-label">Total Locales</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number"><?php echo $stats['locales_activos']; ?></div>
                <div class="stat-label">Locales Activos</div>
            </div>
            <!-- <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-store"></i>
                </div>
                <div class="stat-number">-</div>
                <div class="stat-label">Sucursales</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="stat-number">-</div>
                <div class="stat-label">Bodegas</div>
            </div> -->
        </div>

        <!-- Filtros -->
        <div class="filters-container">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label text-white">
                        <i class="fas fa-search"></i> Buscar
                    </label>
                    <input type="text" class="form-control form-control-modern" 
                           name="buscar" value="<?php echo htmlspecialchars($filtros['buscar']); ?>" 
                           placeholder="Nombre, dirección o teléfono">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-white">
                        <i class="fas fa-tag"></i> Tipo
                    </label>
                    <select class="form-control form-control-modern" name="tipo">
                        <option value="">Todos los tipos</option>
                        <option value="sucursal" <?php echo $filtros['tipo'] === 'sucursal' ? 'selected' : ''; ?>>Sucursal</option>
                        <option value="bodega" <?php echo $filtros['tipo'] === 'bodega' ? 'selected' : ''; ?>>Bodega</option>
                        <option value="oficina" <?php echo $filtros['tipo'] === 'oficina' ? 'selected' : ''; ?>>Oficina</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-white">
                        <i class="fas fa-toggle-on"></i> Estado
                    </label>
                    <select class="form-control form-control-modern" name="estado">
                        <option value="">Todos</option>
                        <option value="activo" <?php echo $filtros['estado'] === 'activo' ? 'selected' : ''; ?>>Activo</option>
                        <option value="inactivo" <?php echo $filtros['estado'] === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-white">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-modern btn-primary-modern">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Botón Nuevo -->
        <div class="mb-3">
            <a href="/RMIE/app/controllers/LocalController.php?action=create" 
               class="btn btn-modern btn-success-modern">
                <i class="fas fa-plus"></i> Nuevo Local
            </a>
            <a href="/RMIE/app/controllers/MainController.php?action=dashboard" class="btn btn-modern btn-primary-modern">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        <!-- Tabla de Locales -->
        <div class="table-container">
            <?php if (empty($locales)): ?>
                <div class="text-center text-white py-5">
                    <i class="fas fa-building fa-3x mb-3" style="opacity: 0.5;"></i>
                    <h3>No hay locales registrados</h3>
                    <p>Comienza agregando tu primer local</p>
                    <a href="/RMIE/app/controllers/LocalController.php?action=create" 
                       class="btn btn-modern btn-success-modern">
                        <i class="fas fa-plus"></i> Crear Primer Local
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-building"></i> Nombre</th>
                                <th><i class="fas fa-map-marker-alt"></i> Dirección</th>
                                <th><i class="fas fa-phone"></i> Teléfono</th>
                                <th><i class="fas fa-tag"></i> Tipo</th>
                                <th><i class="fas fa-toggle-on"></i> Estado</th>
                                <th><i class="fas fa-calendar"></i> Creado</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($locales as $local): ?>
                            <tr>
                                <td><strong>#<?php echo $local->id_locales; ?></strong></td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($local->nombre_local); ?></div>
                                    <?php if (!empty($local->barrio)): ?>
                                        <small class="text-muted"><?php echo htmlspecialchars($local->barrio); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                    <?php echo htmlspecialchars($local->direccion); ?>
                                </td>
                                <td>
                                    <?php if (!empty($local->cel_local)): ?>
                                        <i class="fas fa-phone text-success"></i>
                                        <?php echo htmlspecialchars($local->cel_local); ?>
                                    <?php else: ?>
                                        <span class="text-muted">Sin teléfono</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-status">
                                        -
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-status badge-<?php echo $local->estado; ?>">
                                        <?php echo ucfirst($local->estado); ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="fas fa-calendar text-info"></i>
                                    <?php echo date('d/m/Y', strtotime($local->fecha_creacion)); ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/RMIE/app/controllers/LocalController.php?action=edit&id=<?php echo $local->id_locales; ?>" 
                                           class="btn btn-sm btn-modern btn-warning-modern" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="/RMIE/app/controllers/LocalController.php?action=destroy" method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?php echo $local->id_locales; ?>">
                                            <button type="submit" class="btn btn-sm btn-modern btn-danger-modern" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este local?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Loading screen
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loadingScreen').style.opacity = '0';
                setTimeout(function() {
                    document.getElementById('loadingScreen').style.display = 'none';
                }, 500);
            }, 1000);
        });

        // Smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stat-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate__animated', 'animate__fadeInUp');
            });
        });

        // Table row hover effect
        document.querySelectorAll('.table-modern tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.background = 'rgba(255, 255, 255, 0.1)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.background = 'transparent';
            });
        });
    </script>
</body>
</html>