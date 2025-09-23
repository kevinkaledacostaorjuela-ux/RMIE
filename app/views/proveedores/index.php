<?php
// Calcular estadísticas
$totalProveedores = count($proveedores ?? []);
$proveedoresActivos = 0;
$proveedoresInactivos = 0;
$proveedoresPendientes = 0;

if (isset($proveedores) && is_array($proveedores)) {
    foreach ($proveedores as $prov) {
        switch (strtolower($prov->estado)) {
            case 'activo':
                $proveedoresActivos++;
                break;
            case 'inactivo':
                $proveedoresInactivos++;
                break;
            case 'pendiente':
                $proveedoresPendientes++;
                break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="proveedores-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="subcategorias-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Proveedores</li>
            </ol>
        </nav>

        <h1><i class="fas fa-truck"></i> Gestión de Proveedores</h1>
        
        <!-- Estadísticas -->
        <div class="proveedores-stats">
            <div class="proveedor-stat-card">
                <h3><?= $totalProveedores ?></h3>
                <p><i class="fas fa-truck"></i> Total Proveedores</p>
            </div>
            <div class="proveedor-stat-card">
                <h3><?= $proveedoresActivos ?></h3>
                <p><i class="fas fa-check-circle"></i> Activos</p>
            </div>
            <div class="proveedor-stat-card">
                <h3><?= $proveedoresInactivos ?></h3>
                <p><i class="fas fa-times-circle"></i> Inactivos</p>
            </div>
            <div class="proveedor-stat-card">
                <h3><?= $proveedoresPendientes ?></h3>
                <p><i class="fas fa-clock"></i> Pendientes</p>
            </div>
        </div>

        <!-- Mensajes de estado -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php
                switch ($_GET['success']) {
                    case 'created': echo '<i class="fas fa-check"></i> Proveedor creado exitosamente'; break;
                    case 'updated': echo '<i class="fas fa-edit"></i> Proveedor actualizado exitosamente'; break;
                    case 'deleted': echo '<i class="fas fa-trash"></i> Proveedor eliminado exitosamente'; break;
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
        <div class="proveedores-filters">
            <form method="GET" action="/RMIE/app/controllers/ProviderController.php">
                <input type="hidden" name="accion" value="index">
                <div class="row">
                    <div class="col-md-3">
                        <label for="filtro_nombre">
                            <i class="fas fa-search"></i> Buscar por nombre:
                        </label>
                        <input type="text" 
                               id="filtro_nombre" 
                               name="filtro_nombre" 
                               value="<?= htmlspecialchars($_GET['filtro_nombre'] ?? '') ?>"
                               placeholder="Nombre del proveedor">
                    </div>
                    <div class="col-md-3">
                        <label for="filtro_estado">
                            <i class="fas fa-filter"></i> Estado:
                        </label>
                        <select id="filtro_estado" name="filtro_estado">
                            <option value="">Todos los estados</option>
                            <option value="activo" <?= ($_GET['filtro_estado'] ?? '') === 'activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="inactivo" <?= ($_GET['filtro_estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                            <option value="pendiente" <?= ($_GET['filtro_estado'] ?? '') === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filtro_email">
                            <i class="fas fa-envelope"></i> Email:
                        </label>
                        <input type="text" 
                               id="filtro_email" 
                               name="filtro_email" 
                               value="<?= htmlspecialchars($_GET['filtro_email'] ?? '') ?>"
                               placeholder="Correo electrónico">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-light">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <a href="/RMIE/app/controllers/ProviderController.php?accion=index" class="btn btn-outline-light">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Botón agregar -->
        <div class="text-center mb-3">
            <a href="/RMIE/app/controllers/ProviderController.php?accion=create" class="btn btn-light btn-lg">
                <i class="fas fa-plus"></i> Agregar Nuevo Proveedor
            </a>
        </div>

        <!-- Tabla de proveedores -->
        <div class="proveedores-table-container">
            <table class="table proveedores-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-id-badge"></i> ID</th>
                        <th><i class="fas fa-building"></i> Empresa</th>
                        <th><i class="fas fa-address-card"></i> Contacto</th>
                        <th><i class="fas fa-info-circle"></i> Estado</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($proveedores) && is_array($proveedores) && count($proveedores) > 0): ?>
                        <?php foreach ($proveedores as $prov): ?>
                        <tr>
                            <td>
                                <span class="badge bg-secondary">#<?= htmlspecialchars($prov->id_proveedores ?? 'N/A') ?></span>
                            </td>
                            <td>
                                <div class="proveedor-nombre">
                                    <i class="fas fa-truck"></i> <?= htmlspecialchars($prov->nombre_distribuidor ?? 'Sin nombre') ?>
                                </div>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <div class="proveedor-email">
                                        <i class="fas fa-envelope"></i> <?= htmlspecialchars($prov->correo ?? 'Sin email') ?>
                                    </div>
                                    <div class="proveedor-telefono">
                                        <i class="fas fa-phone"></i> <?= htmlspecialchars($prov->cel_proveedor ?? 'Sin teléfono') ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php
                                $estado = strtolower($prov->estado ?? 'pendiente');
                                $claseEstado = '';
                                $iconoEstado = '';
                                
                                switch ($estado) {
                                    case 'activo':
                                        $claseEstado = 'estado-activo';
                                        $iconoEstado = 'fas fa-check-circle';
                                        break;
                                    case 'inactivo':
                                        $claseEstado = 'estado-inactivo';
                                        $iconoEstado = 'fas fa-times-circle';
                                        break;
                                    case 'pendiente':
                                        $claseEstado = 'estado-pendiente';
                                        $iconoEstado = 'fas fa-clock';
                                        break;
                                    default:
                                        $claseEstado = 'estado-pendiente';
                                        $iconoEstado = 'fas fa-question-circle';
                                }
                                ?>
                                <span class="estado-badge <?= $claseEstado ?>">
                                    <i class="<?= $iconoEstado ?>"></i> <?= ucfirst($estado) ?>
                                </span>
                            </td>
                            <td>
                                <div class="proveedores-actions">
                                    <a href="/RMIE/app/controllers/ProviderController.php?accion=edit&id=<?= htmlspecialchars($prov->id_proveedores) ?>" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="/RMIE/app/controllers/ProviderController.php?accion=delete&id=<?= htmlspecialchars($prov->id_proveedores) ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('¿Está seguro de eliminar este proveedor? Esta acción no se puede deshacer.')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No se encontraron proveedores</p>
                                <a href="/RMIE/app/controllers/ProviderController.php?accion=create" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Agregar el primer proveedor
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
                Total de proveedores mostrados: <?= count($proveedores ?? []) ?>
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
            const proveedorNombre = this.closest('tr').querySelector('.proveedor-nombre').textContent.trim();
            if (confirm(`¿Está seguro de eliminar el proveedor "${proveedorNombre}"?\n\nEsta acción no se puede deshacer y puede afectar a los productos asociados.`)) {
                window.location.href = this.href;
            }
        });
    });
    </script>
</body>
</html>
