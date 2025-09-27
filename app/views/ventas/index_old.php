<?php
// Calcular estadísticas
$totalVentas = count($ventas ?? []);
$ventasCompletadas = 0;
$ventasPendientes = 0;
$ventasCanceladas = 0;
$montoTotal = 0;

if (isset($ventas) && is_array($ventas)) {
    foreach ($ventas as $venta) {
        $estado = strtolower($venta->estado ?? 'pendiente');
        switch ($estado) {
            case 'completada':
                $ventasCompletadas++;
                break;
            case 'pendiente':
                $ventasPendientes++;
                break;
            case 'cancelada':
                $ventasCanceladas++;
                break;
        }
        $montoTotal += floatval($venta->total ?? 0);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="ventas-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="subcategorias-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/RMIE/app/controllers/MainController.php?action=dashboard"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ventas</li>
            </ol>
        </nav>

        <h1><i class="fas fa-shopping-cart"></i> Gestión de Ventas</h1>
        
        <!-- Estadísticas -->
        <div class="ventas-stats">
            <div class="venta-stat-card">
                <h3><?= $totalVentas ?></h3>
                <p><i class="fas fa-shopping-cart"></i> Total Ventas</p>
            </div>
            <div class="venta-stat-card">
                <h3><?= $ventasCompletadas ?></h3>
                <p><i class="fas fa-check-circle"></i> Completadas</p>
            </div>
            <div class="venta-stat-card">
                <h3><?= $ventasPendientes ?></h3>
                <p><i class="fas fa-clock"></i> Pendientes</p>
            </div>
            <div class="venta-stat-card">
                <h3>$<?= number_format($montoTotal, 2) ?></h3>
                <p><i class="fas fa-dollar-sign"></i> Monto Total</p>
            </div>
        </div>

        <!-- Mensajes de estado -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php
                switch ($_GET['success']) {
                    case 'created': echo '<i class="fas fa-check"></i> Venta creada exitosamente'; break;
                    case 'updated': echo '<i class="fas fa-edit"></i> Venta actualizada exitosamente'; break;
                    case 'deleted': echo '<i class="fas fa-trash"></i> Venta eliminada exitosamente'; break;
                }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($_GET['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Filtros -->
        <div class="ventas-filters">
            <form method="GET" action="/RMIE/app/controllers/SaleController.php">
                <input type="hidden" name="accion" value="index">
                <div class="row">
                    <div class="col-md-3">
                        <label for="filtro_producto">
                            <i class="fas fa-box"></i> Buscar producto:
                        </label>
                        <input type="text" 
                               id="filtro_producto" 
                               name="filtro_producto" 
                               value="<?= htmlspecialchars($_GET['filtro_producto'] ?? '') ?>"
                               placeholder="Nombre del producto">
                    </div>
                    <div class="col-md-3">
                        <label for="filtro_cliente">
                            <i class="fas fa-user"></i> Buscar cliente:
                        </label>
                        <input type="text" 
                               id="filtro_cliente" 
                               name="filtro_cliente" 
                               value="<?= htmlspecialchars($_GET['filtro_cliente'] ?? '') ?>"
                               placeholder="Nombre del cliente">
                    </div>
                    <div class="col-md-2">
                        <label for="filtro_fecha">
                            <i class="fas fa-calendar"></i> Fecha:
                        </label>
                        <input type="date" 
                               id="filtro_fecha" 
                               name="filtro_fecha" 
                               value="<?= htmlspecialchars($_GET['filtro_fecha'] ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="filtro_estado">
                            <i class="fas fa-filter"></i> Estado:
                        </label>
                        <select id="filtro_estado" name="filtro_estado">
                            <option value="">Todos</option>
                            <option value="pendiente" <?= ($_GET['filtro_estado'] ?? '') === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="completada" <?= ($_GET['filtro_estado'] ?? '') === 'completada' ? 'selected' : '' ?>>Completada</option>
                            <option value="cancelada" <?= ($_GET['filtro_estado'] ?? '') === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                            <option value="procesando" <?= ($_GET['filtro_estado'] ?? '') === 'procesando' ? 'selected' : '' ?>>Procesando</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-light">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <a href="/RMIE/app/controllers/SaleController.php?accion=index" class="btn btn-outline-light">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Botón agregar -->
        <div class="text-center mb-3">
            <a href="/RMIE/app/controllers/SaleController.php?accion=create" class="btn btn-light btn-lg">
                <i class="fas fa-plus"></i> Registrar Nueva Venta
            </a>
        </div>

        <!-- Tabla de ventas -->
        <div class="ventas-table-container">
            <table class="table ventas-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-id-badge"></i> ID</th>
                        <th><i class="fas fa-user"></i> Cliente</th>
                        <th><i class="fas fa-box"></i> Producto</th>
                        <th><i class="fas fa-calendar"></i> Fecha</th>
                        <th><i class="fas fa-cubes"></i> Cantidad</th>
                        <th><i class="fas fa-dollar-sign"></i> Precio Unit.</th>
                        <th><i class="fas fa-calculator"></i> Total</th>
                        <th><i class="fas fa-info-circle"></i> Estado</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($ventas) && is_array($ventas) && count($ventas) > 0): ?>
                        <?php foreach ($ventas as $venta): ?>
                        <tr>
                            <td>
                                <span class="badge bg-secondary">#<?= htmlspecialchars($venta->id_ventas ?? 'N/A') ?></span>
                            </td>
                            <td>
                                <div class="venta-cliente">
                                    <i class="fas fa-user"></i> <?= htmlspecialchars($venta->cliente_nombre ?? 'Sin cliente') ?>
                                </div>
                                <?php if (!empty($venta->direccion)): ?>
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($venta->direccion) ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="venta-producto">
                                    <i class="fas fa-box"></i> <?= htmlspecialchars($venta->producto_nombre ?? 'Sin producto') ?>
                                </div>
                            </td>
                            <td>
                                <div class="venta-fecha">
                                    <i class="fas fa-calendar"></i> 
                                    <?= htmlspecialchars($venta->fecha_venta ?? 'Sin fecha') ?>
                                </div>
                            </td>
                            <td>
                                <span class="venta-cantidad">
                                    <?= htmlspecialchars($venta->cantidad ?? '0') ?>
                                </span>
                            </td>
                            <td>
                                <span class="venta-precio">
                                    $<?= number_format(floatval($venta->precio_unitario ?? 0), 2) ?>
                                </span>
                            </td>
                            <td>
                                <span class="venta-total">
                                    $<?= number_format(floatval($venta->total ?? 0), 2) ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                $estado = strtolower($venta->estado ?? 'pendiente');
                                $claseEstado = '';
                                $iconoEstado = '';
                                
                                switch ($estado) {
                                    case 'completada':
                                        $claseEstado = 'estado-completada';
                                        $iconoEstado = 'fas fa-check-circle';
                                        break;
                                    case 'pendiente':
                                        $claseEstado = 'estado-pendiente';
                                        $iconoEstado = 'fas fa-clock';
                                        break;
                                    case 'cancelada':
                                        $claseEstado = 'estado-cancelada';
                                        $iconoEstado = 'fas fa-times-circle';
                                        break;
                                    case 'procesando':
                                        $claseEstado = 'estado-procesando';
                                        $iconoEstado = 'fas fa-spinner';
                                        break;
                                    default:
                                        $claseEstado = 'estado-pendiente';
                                        $iconoEstado = 'fas fa-question-circle';
                                }
                                ?>
                                <span class="estado-venta-badge <?= $claseEstado ?>">
                                    <i class="<?= $iconoEstado ?>"></i> <?= ucfirst($estado) ?>
                                </span>
                            </td>
                            <td>
                                <div class="ventas-actions">
                                    <a href="/RMIE/app/controllers/SaleController.php?accion=edit&id=<?= htmlspecialchars($venta->id_ventas) ?>" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="/RMIE/app/controllers/SaleController.php?accion=delete&id=<?= htmlspecialchars($venta->id_ventas) ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('¿Está seguro de eliminar esta venta? Esta acción no se puede deshacer.')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No se encontraron ventas</p>
                                <a href="/RMIE/app/controllers/SaleController.php?accion=create" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Registrar la primera venta
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Información adicional -->
        <div class="text-center mt-4">
            <p class="text-white-50">
                <i class="fas fa-info-circle"></i> 
                Total de ventas mostradas: <?= count($ventas ?? []) ?>
                <?php if ($montoTotal > 0): ?>
                    | Monto total: $<?= number_format($montoTotal, 2) ?>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript para funcionalidades adicionales -->
    <script>
    // Auto-ocultar alertas después de 5 segundos
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            if (alert.classList.contains('show')) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 150);
            }
        });
    }, 5000);
    
    // Confirmar eliminación con más detalles
    document.querySelectorAll('a[onclick*="confirm"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const clienteNombre = this.closest('tr').querySelector('.venta-cliente').textContent.trim();
            const total = this.closest('tr').querySelector('.venta-total').textContent.trim();
            if (confirm(`¿Está seguro de eliminar la venta del cliente "${clienteNombre}" por ${total}?\n\nEsta acción no se puede deshacer.`)) {
                window.location.href = this.href;
            }
        });
    });
    </script>
</body>
</html>

