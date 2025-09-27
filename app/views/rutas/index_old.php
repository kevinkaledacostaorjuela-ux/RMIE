<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Rutas - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Pantalla de carga animada -->
    <div id="loader-rutas" style="position:fixed;top:0;left:0;width:100vw;height:100vh;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);z-index:9999;display:flex;align-items:center;justify-content:center;flex-direction:column;">
        <div style="background:rgba(255,255,255,0.15);padding:2.5rem 3rem;border-radius:2rem;box-shadow:0 8px 32px rgba(102,126,234,0.18);display:flex;flex-direction:column;align-items:center;">
            <i class="fas fa-route fa-4x fa-spin" style="color:#fff;margin-bottom:1.5rem;"></i>
            <h2 style="color:#fff;font-weight:700;margin-bottom:0.5rem;">Cargando gestión de rutas...</h2>
            <p style="color:#e0e0e0;">Por favor espera un momento</p>
        </div>
    </div>
    <script>
        window.addEventListener('load',function(){
            setTimeout(function(){
                var loader = document.getElementById('loader-rutas');
                if(loader) loader.style.opacity='0';
                setTimeout(function(){
                    if(loader) loader.style.display='none';
                },400);
            },900);
        });
    </script>

    <div class="container">
        <div class="rutas-container">
            <!-- Breadcrumb -->
            <div class="rutas-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/RMIE/app/views/dashboard.php">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item current">
                            <i class="fas fa-route"></i> Gestión de Rutas
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Header con estadísticas -->
            <div class="page-header">
                <div class="header-content">
                    <h1><i class="fas fa-route"></i> Gestión de Rutas</h1>
                    <p>Administra y organiza las rutas de entrega y distribución</p>
                </div>
                <div class="header-actions">
                    <a href="/RMIE/app/controllers/RouteController.php?accion=create" class="btn-rutas">
                        <i class="fas fa-plus"></i> Agregar Ruta
                    </a>
                </div>
            </div>

            <!-- Botón para regresar al dashboard -->
            <div style="margin-bottom: 1.5rem;">
                <a href="/RMIE/app/views/dashboard.php" class="btn-rutas">
                    <i class="fas fa-arrow-left"></i> Regresar al Dashboard
                </a>
            </div>

            <!-- Panel de estadísticas -->
            <div class="ruta-stats">
                <?php 
                $total_rutas = count($routes);
                $rutas_activas = 0;
                $clientes_unicos = [];
                $locales_unicos = [];

                foreach ($routes as $route) {
                    $rutas_activas++;
                    if (!in_array($route['nombre_cliente'], $clientes_unicos)) {
                        $clientes_unicos[] = $route['nombre_cliente'];
                    }
                    if (!in_array($route['nombre_local'], $locales_unicos)) {
                        $locales_unicos[] = $route['nombre_local'];
                    }
                }
                ?>
                <div class="stat-card">
                    <div class="stat-number"><?= $total_rutas ?></div>
                    <div class="stat-label"><i class="fas fa-route"></i> Total Rutas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= count($clientes_unicos) ?></div>
                    <div class="stat-label"><i class="fas fa-users"></i> Clientes Únicos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= count($locales_unicos) ?></div>
                    <div class="stat-label"><i class="fas fa-store"></i> Locales Únicos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $rutas_activas ?></div>
                    <div class="stat-label"><i class="fas fa-check-circle"></i> Rutas Activas</div>
                </div>
            </div>

            <!-- Filtros avanzados -->
            <div class="rutas-filters">
                <h3><i class="fas fa-filter"></i> Filtros de Búsqueda</h3>
                <form method="GET" action="/RMIE/app/controllers/RouteController.php">
                    <input type="hidden" name="accion" value="index">
                    <div class="filter-row">
                        <div class="form-group">
                            <label for="cliente"><i class="fas fa-user"></i> Cliente</label>
                            <input type="text" id="cliente" name="cliente" placeholder="Buscar por cliente..." class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="local"><i class="fas fa-store"></i> Local</label>
                            <input type="text" id="local" name="local" placeholder="Buscar por local..." class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="direccion"><i class="fas fa-map-marker-alt"></i> Dirección</label>
                            <input type="text" id="direccion" name="direccion" placeholder="Buscar por dirección..." class="form-control">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn-rutas">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabla de rutas -->
            <div class="table-container">
                <?php if (!empty($routes) && is_array($routes)): ?>
                    <table class="rutas-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-map-marker-alt"></i> Dirección</th>
                                <th><i class="fas fa-store"></i> Local</th>
                                <th><i class="fas fa-user"></i> Cliente</th>
                                <th><i class="fas fa-shopping-cart"></i> Venta</th>
                                <th><i class="fas fa-tools"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($routes as $route): ?>
                                <tr>
                                    <td>
                                        <div class="ruta-avatar">
                                            <?= substr($route['id_ruta'], 0, 2) ?>
                                        </div>
                                        <span class="badge-ruta">#<?= $route['id_ruta'] ?></span>
                                    </td>
                                    <td>
                                        <div class="ruta-direccion">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?= htmlspecialchars($route['direccion']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ruta-local">
                                            <i class="fas fa-store"></i>
                                            <?= htmlspecialchars($route['nombre_local']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ruta-cliente">
                                            <i class="fas fa-user"></i>
                                            <?= htmlspecialchars($route['nombre_cliente']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-ruta">
                                            <i class="fas fa-shopping-cart"></i>
                                            Venta #<?= $route['id_ventas'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="/RMIE/app/controllers/RouteController.php?accion=edit&id=<?= $route['id_ruta'] ?>" 
                                               class="btn-action btn-edit" title="Editar ruta">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="/RMIE/app/controllers/RouteController.php?accion=delete&id=<?= $route['id_ruta'] ?>" 
                                               class="btn-action btn-delete" 
                                               onclick="return confirm('¿Estás seguro de que deseas eliminar esta ruta?\\n\\nRuta: <?= htmlspecialchars($route['direccion']) ?>\\nCliente: <?= htmlspecialchars($route['nombre_cliente']) ?>')" 
                                               title="Eliminar ruta">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-route"></i>
                        </div>
                        <h3>No hay rutas registradas</h3>
                        <p>Comienza creando tu primera ruta de entrega.</p>
                        <a href="/RMIE/app/controllers/RouteController.php?accion=create" class="btn-rutas">
                            <i class="fas fa-plus"></i> Crear Primera Ruta
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Panel de información -->
            <div class="ruta-info-panel">
                <h3><i class="fas fa-info-circle"></i> Información del Sistema</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <i class="fas fa-route"></i>
                        <strong>Rutas Totales:</strong> <?= $total_rutas ?> registradas
                    </div>
                    <div class="info-item">
                        <i class="fas fa-users"></i>
                        <strong>Clientes Activos:</strong> <?= count($clientes_unicos) ?> diferentes
                    </div>
                    <div class="info-item">
                        <i class="fas fa-store"></i>
                        <strong>Locales Asociados:</strong> <?= count($locales_unicos) ?> únicos
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <strong>Última Actualización:</strong> <?= date('d/m/Y H:i') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Filtros en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.rutas-filters input[type="text"]');
            
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    filterTable();
                });
            });

            function filterTable() {
                const clienteFilter = document.getElementById('cliente').value.toLowerCase();
                const localFilter = document.getElementById('local').value.toLowerCase();
                const direccionFilter = document.getElementById('direccion').value.toLowerCase();
                const rows = document.querySelectorAll('.rutas-table tbody tr');

                rows.forEach(row => {
                    const cliente = row.cells[3].textContent.toLowerCase();
                    const local = row.cells[2].textContent.toLowerCase();
                    const direccion = row.cells[1].textContent.toLowerCase();

                    const matchCliente = cliente.includes(clienteFilter);
                    const matchLocal = local.includes(localFilter);
                    const matchDireccion = direccion.includes(direccionFilter);

                    if (matchCliente && matchLocal && matchDireccion) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        });

        // Animaciones de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.stat-card, .rutas-table tr');
            elements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    element.style.transition = 'all 0.5s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>
