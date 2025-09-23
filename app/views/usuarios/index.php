<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="usuarios-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="subcategorias-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
            </ol>
        </nav>

        <h1><i class="fas fa-users"></i> Gestión de Usuarios</h1>
        
        <!-- Mostrar mensajes -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($_GET['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_GET['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Estadísticas -->
        <?php if (isset($stats)): ?>
            <div class="usuarios-stats">
                <div class="stat-card total">
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="value"><?= $stats['total'] ?? 0 ?></span>
                    <span class="label">Total Usuarios</span>
                </div>
                
                <div class="stat-card admin">
                    <div class="icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <span class="value"><?= $stats['por_rol']['admin'] ?? 0 ?></span>
                    <span class="label">Administradores</span>
                </div>
                
                <div class="stat-card coordinador">
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <span class="value"><?= $stats['por_rol']['coordinador'] ?? 0 ?></span>
                    <span class="label">Coordinadores</span>
                </div>
                
                <div class="stat-card active">
                    <div class="icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <span class="value"><?= count($usuarios ?? []) ?></span>
                    <span class="label">Usuarios Activos</span>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Top vendedores -->
        <?php if (isset($stats['top_vendedores']) && !empty($stats['top_vendedores'])): ?>
            <div class="top-sellers">
                <h4><i class="fas fa-trophy"></i> Top Vendedores</h4>
                <?php foreach ($stats['top_vendedores'] as $vendedor): ?>
                    <div class="seller-item">
                        <div class="seller-info">
                            <div class="user-avatar">
                                <?= strtoupper(substr($vendedor['nombres'], 0, 1)) ?>
                            </div>
                            <span class="seller-name"><?= htmlspecialchars($vendedor['nombres'] . ' ' . $vendedor['apellidos']) ?></span>
                        </div>
                        <div class="sales-count"><?= $vendedor['total_ventas'] ?> ventas</div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Filtros -->
        <div class="usuarios-filters">
            <h3><i class="fas fa-filter"></i> Filtros de Búsqueda</h3>
            <form method="GET" action="/RMIE/app/controllers/UserController.php">
                <input type="hidden" name="accion" value="index">
                <div class="filter-row">
                    <div class="form-group">
                        <label for="filtro_rol">
                            <i class="fas fa-user-tag"></i> Filtrar por rol:
                        </label>
                        <select id="filtro_rol" name="filtro_rol">
                            <option value="">Todos los roles</option>
                            <option value="admin" <?= ($_GET['filtro_rol'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
                            <option value="coordinador" <?= ($_GET['filtro_rol'] ?? '') === 'coordinador' ? 'selected' : '' ?>>Coordinador</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="filtro_tipo_doc">
                            <i class="fas fa-id-card"></i> Tipo de documento:
                        </label>
                        <select id="filtro_tipo_doc" name="filtro_tipo_doc">
                            <option value="">Todos los tipos</option>
                            <option value="CC" <?= ($_GET['filtro_tipo_doc'] ?? '') === 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                            <option value="TI" <?= ($_GET['filtro_tipo_doc'] ?? '') === 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                            <option value="CE" <?= ($_GET['filtro_tipo_doc'] ?? '') === 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                            <option value="PA" <?= ($_GET['filtro_tipo_doc'] ?? '') === 'PA' ? 'selected' : '' ?>>Pasaporte</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="buscar">
                            <i class="fas fa-search"></i> Buscar:
                        </label>
                        <input type="text" 
                               id="buscar" 
                               name="buscar" 
                               value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>"
                               placeholder="Buscar por nombre, documento o correo">
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="/RMIE/app/controllers/UserController.php?accion=index" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Botón para crear usuario -->
        <div class="usuarios-buttons">
            <a href="/RMIE/app/controllers/UserController.php?accion=create" class="btn btn-success">
                <i class="fas fa-plus"></i> Nuevo Usuario
            </a>
        </div>
        
        <!-- Tabla de usuarios -->
        <div class="usuarios-table-container">
            <?php if (isset($usuarios) && !empty($usuarios)): ?>
                <table class="usuarios-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-user"></i> Usuario</th>
                            <th><i class="fas fa-id-card"></i> Documento</th>
                            <th><i class="fas fa-envelope"></i> Correo</th>
                            <th><i class="fas fa-phone"></i> Celular</th>
                            <th><i class="fas fa-user-tag"></i> Rol</th>
                            <th><i class="fas fa-calendar"></i> Fecha</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <?= strtoupper(substr($usuario->nombres, 0, 1)) ?>
                                        </div>
                                        <div class="details">
                                            <div class="name"><?= htmlspecialchars($usuario->nombres . ' ' . $usuario->apellidos) ?></div>
                                            <div class="doc"><?= htmlspecialchars($usuario->tipo_doc) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($usuario->num_doc) ?></td>
                                <td>
                                    <a href="mailto:<?= htmlspecialchars($usuario->correo) ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($usuario->correo) ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if ($usuario->num_cel): ?>
                                        <a href="tel:<?= htmlspecialchars($usuario->num_cel) ?>" class="text-decoration-none">
                                            <?= htmlspecialchars($usuario->num_cel) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No registrado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="role-badge role-<?= strtolower($usuario->rol) ?>">
                                        <?php if ($usuario->rol === 'admin'): ?>
                                            <i class="fas fa-user-shield"></i>
                                        <?php else: ?>
                                            <i class="fas fa-user-tie"></i>
                                        <?php endif; ?>
                                        <?= ucfirst($usuario->rol) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($usuario->fecha_creacion): ?>
                                        <?= date('d/m/Y', strtotime($usuario->fecha_creacion)) ?>
                                    <?php else: ?>
                                        <span class="text-muted">No disponible</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="usuarios-actions">
                                        <a href="/RMIE/app/controllers/UserController.php?accion=edit&id=<?= $usuario->num_doc ?>" 
                                           class="btn btn-sm btn-primary" 
                                           title="Editar usuario">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/RMIE/app/controllers/UserController.php?accion=delete&id=<?= $usuario->num_doc ?>" 
                                           class="btn btn-sm btn-danger" 
                                           title="Eliminar usuario"
                                           onclick="return confirm('¿Está seguro de que desea eliminar este usuario?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center p-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No se encontraron usuarios</h5>
                    <p class="text-muted">No hay usuarios registrados que coincidan con los criterios de búsqueda.</p>
                    <a href="/RMIE/app/controllers/UserController.php?accion=create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Primer Usuario
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Información adicional -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Información:</strong> 
                    Total de usuarios registrados: <?= count($usuarios ?? []) ?>. 
                    Los usuarios pueden gestionar ventas y acceder al sistema según su rol asignado.
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript para funcionalidades -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-ocultar alertas después de 5 segundos
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('show')) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 150);
                }
            });
        }, 5000);
        
        // Confirmación de eliminación mejorada
        const deleteLinks = document.querySelectorAll('a[onclick*="confirm"]');
        deleteLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const userName = this.closest('tr').querySelector('.name').textContent;
                if (confirm(`¿Está seguro de que desea eliminar al usuario "${userName}"?\n\nEsta acción no se puede deshacer.`)) {
                    window.location.href = this.href;
                }
            });
        });
        
        // Búsqueda en tiempo real
        const searchInput = document.getElementById('buscar');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.length >= 3 || this.value.length === 0) {
                        // Auto-submit después de 500ms de inactividad
                        document.querySelector('.usuarios-filters form').submit();
                    }
                }, 500);
            });
        }
    });
    </script>
</body>
</html>
