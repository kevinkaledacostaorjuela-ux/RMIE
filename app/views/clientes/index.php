<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Partículas de fondo decorativas -->
    <div class="particles-background">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="clientes-container">
        <!-- Header con diseño mejorado -->
        <div class="clientes-header">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="subcategorias-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/RMIE/app/views/dashboard.php">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-users"></i> Clientes
                    </li>
                </ol>
            </nav>

            <!-- Título principal con efectos -->
            <div class="main-title-section">
                <h1 class="main-title">
                    <span class="title-icon">
                        <i class="fas fa-users"></i>
                    </span>
                    <span class="title-text">Gestión de Clientes</span>
                    <span class="title-decoration"></span>
                </h1>
                <p class="title-subtitle">
                    Administra y gestiona toda la información de tus clientes de manera eficiente
                </p>
            </div>
        </div>
        
        <!-- Mostrar mensajes -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['deleted']) && $_GET['deleted'] == '1'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> Cliente eliminado exitosamente
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Estadísticas -->
        <?php if (isset($stats)): ?>
        <div class="clientes-stats">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-card stat-total">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['total'] ?></div>
                            <div class="stat-label">Total Clientes</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card stat-active">
                        <div class="stat-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['activos'] ?></div>
                            <div class="stat-label">Activos</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card stat-inactive">
                        <div class="stat-icon">
                            <i class="fas fa-user-times"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['inactivos'] ?></div>
                            <div class="stat-label">Inactivos</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card stat-new">
                        <div class="stat-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['nuevos_mes'] ?></div>
                            <div class="stat-label">Nuevos este mes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Acciones principales -->
        <div class="clientes-actions">
            <a href="/RMIE/app/controllers/ClientController.php?accion=create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Cliente
            </a>
            <button class="btn btn-success" onclick="exportarDatos()" title="Exportar listado">
                <i class="fas fa-file-excel"></i> Exportar
            </button>
            <button class="btn btn-info" onclick="window.print()" title="Imprimir listado">
                <i class="fas fa-print"></i> Imprimir
            </button>
        </div>
        
        <!-- Filtros avanzados -->
        <div class="clientes-filters">
            <form method="GET" action="/RMIE/app/controllers/ClientController.php" id="filtrosForm">
                <input type="hidden" name="accion" value="index">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="local">
                                <i class="fas fa-store"></i> Local:
                            </label>
                            <select id="local" name="local" class="form-control">
                                <option value="">Todos los locales</option>
                                <?php if (isset($locales)): ?>
                                    <?php foreach ($locales as $local): ?>
                                        <option value="<?= $local->id_locales ?>" 
                                                <?= (isset($_GET['local']) && $_GET['local'] == $local->id_locales) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($local->nombre_local) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="estado">
                                <i class="fas fa-toggle-on"></i> Estado:
                            </label>
                            <select id="estado" name="estado" class="form-control">
                                <option value="">Todos los estados</option>
                                <option value="activo" <?= isset($_GET['estado']) && $_GET['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                                <option value="inactivo" <?= isset($_GET['estado']) && $_GET['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="busqueda">
                                <i class="fas fa-search"></i> Buscar:
                            </label>
                            <input type="text" 
                                   id="busqueda" 
                                   name="busqueda" 
                                   placeholder="Nombre, correo o teléfono..."
                                   value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="filter-buttons">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filtrar
                                </button>
                                <a href="/RMIE/app/controllers/ClientController.php?accion=index" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Filtros de fecha -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_desde">
                                <i class="fas fa-calendar-alt"></i> Registrado desde:
                            </label>
                            <input type="date" 
                                   id="fecha_desde" 
                                   name="fecha_desde" 
                                   value="<?= htmlspecialchars($_GET['fecha_desde'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_hasta">
                                <i class="fas fa-calendar-alt"></i> Registrado hasta:
                            </label>
                            <input type="date" 
                                   id="fecha_hasta" 
                                   name="fecha_hasta" 
                                   value="<?= htmlspecialchars($_GET['fecha_hasta'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Tabla de clientes -->
        <div class="clientes-table">
            <?php if (isset($clientes) && count($clientes) > 0): ?>
                <div class="table-container">
                    <div class="table-header">
                        <h5><i class="fas fa-table"></i> Lista de Clientes (<?= count($clientes) ?> registros)</h5>
                        <div class="table-actions">
                            <button onclick="toggleTableView()" class="btn btn-sm btn-outline-secondary" id="viewToggle">
                                <i class="fas fa-th-list"></i> Vista Compacta
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive" id="clientesTable">
                        <table class="table table-hover table-striped clientes-data-table">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">
                                        <i class="fas fa-hashtag"></i> ID
                                    </th>
                                    <th width="25%">
                                        <i class="fas fa-user"></i> Cliente
                                    </th>
                                    <th width="25%">
                                        <i class="fas fa-envelope"></i> Contacto
                                    </th>
                                    <th width="15%">
                                        <i class="fas fa-store"></i> Local
                                    </th>
                                    <th width="10%">
                                        <i class="fas fa-toggle-on"></i> Estado
                                    </th>
                                    <th width="10%">
                                        <i class="fas fa-calendar-alt"></i> Registrado
                                    </th>
                                    <th width="10%">
                                        <i class="fas fa-cogs"></i> Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clientes as $cliente): ?>
                                    <tr class="cliente-row" data-id="<?= $cliente->id_clientes ?>">
                                        <td>
                                            <span class="client-id">#<?= str_pad($cliente->id_clientes, 3, '0', STR_PAD_LEFT) ?></span>
                                        </td>
                                        <td>
                                            <div class="client-info">
                                                <div class="client-avatar">
                                                    <?= strtoupper(substr($cliente->nombre, 0, 1)) ?>
                                                </div>
                                                <div class="client-details">
                                                    <div class="client-name"><?= htmlspecialchars($cliente->nombre) ?></div>
                                                    <?php if ($cliente->descripcion): ?>
                                                        <div class="client-description" title="<?= htmlspecialchars($cliente->descripcion) ?>">
                                                            <?= strlen($cliente->descripcion) > 50 ? 
                                                                htmlspecialchars(substr($cliente->descripcion, 0, 50)) . '...' : 
                                                                htmlspecialchars($cliente->descripcion) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="contact-info">
                                                <div class="contact-email" title="<?= htmlspecialchars($cliente->correo) ?>">
                                                    <i class="fas fa-envelope text-primary"></i> 
                                                    <span><?= htmlspecialchars($cliente->correo) ?></span>
                                                </div>
                                                <?php if ($cliente->cel_cliente): ?>
                                                    <div class="contact-phone">
                                                        <i class="fas fa-phone text-success"></i> 
                                                        <span><?= htmlspecialchars($cliente->cel_cliente) ?></span>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="contact-phone text-muted">
                                                        <i class="fas fa-phone-slash"></i> Sin teléfono
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="local-badge" title="Local asignado: <?= htmlspecialchars($cliente->local_nombre) ?>">
                                                <i class="fas fa-store"></i> 
                                                <?= htmlspecialchars($cliente->local_nombre) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?= $cliente->estado ?>">
                                                <?php if ($cliente->estado === 'activo'): ?>
                                                    <i class="fas fa-check-circle"></i> Activo
                                                <?php else: ?>
                                                    <i class="fas fa-times-circle"></i> Inactivo
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($cliente->fecha_creacion): ?>
                                                <div class="fecha-registro">
                                                    <span class="fecha-full" title="<?= date('d/m/Y H:i:s', strtotime($cliente->fecha_creacion)) ?>">
                                                        <i class="fas fa-calendar-alt text-info"></i>
                                                        <?= date('d/m/Y', strtotime($cliente->fecha_creacion)) ?>
                                                    </span>
                                                    <small class="fecha-time text-muted d-block">
                                                        <?= date('H:i', strtotime($cliente->fecha_creacion)) ?>
                                                    </small>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">
                                                    <i class="fas fa-question-circle"></i> No disponible
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <div class="btn-group" role="group">
                                                    <a href="/RMIE/app/controllers/ClientController.php?accion=edit&id=<?= $cliente->id_clientes ?>" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Editar cliente"
                                                       data-bs-toggle="tooltip">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-info"
                                                            onclick="verDetalles(<?= $cliente->id_clientes ?>)"
                                                            title="Ver detalles"
                                                            data-bs-toggle="tooltip">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="/RMIE/app/controllers/ClientController.php?accion=delete&id=<?= $cliente->id_clientes ?>" 
                                                       class="btn btn-sm btn-outline-danger" 
                                                       title="Eliminar cliente"
                                                       data-bs-toggle="tooltip"
                                                       onclick="return confirm('¿Está seguro de eliminar este cliente?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Información de paginación -->
                    <div class="table-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    Mostrando <?= count($clientes) ?> cliente(s) de <?= isset($stats) ? $stats['total'] : count($clientes) ?> total
                                </small>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">
                                    Última actualización: <?= date('d/m/Y H:i:s') ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-users fa-4x"></i>
                    <h3>No hay clientes registrados</h3>
                    <p>Comience agregando su primer cliente al sistema</p>
                    <a href="/RMIE/app/controllers/ClientController.php?accion=create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Agregar Primer Cliente
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript con efectos mejorados -->
    <script>
    // Pantalla de carga
    document.addEventListener('DOMContentLoaded', function() {
        // Crear pantalla de carga
        const loadingScreen = document.createElement('div');
        loadingScreen.id = 'loadingScreen';
        loadingScreen.innerHTML = `
            <div class="loading-content">
                <div class="loading-spinner">
                    <div class="spinner-ring"></div>
                    <div class="spinner-ring"></div>
                    <div class="spinner-ring"></div>
                </div>
                <h3>Cargando Clientes...</h3>
                <p>Preparando la mejor experiencia para ti</p>
            </div>
        `;
        document.body.appendChild(loadingScreen);
        
        // Estilo para la pantalla de carga
        const loadingStyles = `
            #loadingScreen {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
                transition: opacity 0.5s ease-out;
            }
            
            .loading-content {
                text-align: center;
                color: #2c3e50;
            }
            
            .loading-spinner {
                position: relative;
                width: 80px;
                height: 80px;
                margin: 0 auto 2rem;
            }
            
            .spinner-ring {
                position: absolute;
                width: 100%;
                height: 100%;
                border: 3px solid transparent;
                border-top: 3px solid #28a745;
                border-radius: 50%;
                animation: spin 1.5s linear infinite;
            }
            
            .spinner-ring:nth-child(2) {
                width: 70%;
                height: 70%;
                top: 15%;
                left: 15%;
                border-top-color: #20c997;
                animation-duration: 2s;
                animation-direction: reverse;
            }
            
            .spinner-ring:nth-child(3) {
                width: 40%;
                height: 40%;
                top: 30%;
                left: 30%;
                border-top-color: #17a2b8;
                animation-duration: 1s;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            .loading-content h3 {
                font-size: 1.5rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
                background: linear-gradient(135deg, #28a745, #20c997);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            .loading-content p {
                color: #6c757d;
                font-size: 1rem;
            }
        `;
        
        const styleSheet = document.createElement('style');
        styleSheet.textContent = loadingStyles;
        document.head.appendChild(styleSheet);
        
        // Remover pantalla de carga después de 2 segundos
        setTimeout(function() {
            loadingScreen.style.opacity = '0';
            setTimeout(function() {
                loadingScreen.remove();
                styleSheet.remove();
                
                // Animar elementos cuando se remueve la pantalla de carga
                animateElements();
            }, 500);
        }, 2000);
        
        // Función para animar elementos
        function animateElements() {
            const elements = document.querySelectorAll('.clientes-container > *');
            elements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(30px)';
                element.style.transition = 'all 0.6s ease-out';
                
                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }
        // Inicializar tooltips de Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Auto-envío del formulario al cambiar filtros
        const filtros = ['local', 'estado'];
        filtros.forEach(function(filtroId) {
            const elemento = document.getElementById(filtroId);
            if (elemento) {
                elemento.addEventListener('change', function() {
                    document.getElementById('filtrosForm').submit();
                });
            }
        });
        
        // Búsqueda en tiempo real con debounce
        let busquedaTimeout;
        const busquedaInput = document.getElementById('busqueda');
        if (busquedaInput) {
            busquedaInput.addEventListener('input', function() {
                clearTimeout(busquedaTimeout);
                busquedaTimeout = setTimeout(function() {
                    document.getElementById('filtrosForm').submit();
                }, 500);
            });
        }
        
        // Resaltar filas al pasar el mouse
        const filas = document.querySelectorAll('.cliente-row');
        filas.forEach(function(fila) {
            fila.addEventListener('mouseenter', function() {
                this.classList.add('table-row-highlighted');
            });
            
            fila.addEventListener('mouseleave', function() {
                this.classList.remove('table-row-highlighted');
            });
        });
        
        // Auto-ocultar alertas después de 5 segundos con animación suave
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert, index) {
                if (alert.classList.contains('show')) {
                    setTimeout(() => {
                        alert.style.animation = 'slideOutUp 0.5s ease-in';
                        setTimeout(() => {
                            alert.classList.remove('show');
                            alert.classList.add('fade');
                            alert.remove();
                        }, 500);
                    }, index * 200);
                }
            });
        }, 5000);
        
        // Efectos de partículas en botones
        addParticleEffects();
        
        // Efecto de escritura para el título
        typeWriter();
    });
    
    // Función para efectos de partículas en botones
    function addParticleEffects() {
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!this.disabled && !this.classList.contains('btn-outline-danger')) {
                    createClickEffect(e, this);
                }
            });
        });
    }
    
    // Crear efecto de click con partículas
    function createClickEffect(e, button) {
        const rect = button.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        for (let i = 0; i < 6; i++) {
            const particle = document.createElement('div');
            particle.className = 'click-particle';
            particle.style.cssText = `
                position: absolute;
                left: ${x}px;
                top: ${y}px;
                width: 4px;
                height: 4px;
                background: #fff;
                border-radius: 50%;
                pointer-events: none;
                z-index: 1000;
                animation: particleExplode 0.6s ease-out forwards;
            `;
            
            const angle = (Math.PI * 2 * i) / 6;
            const velocity = 30;
            particle.style.setProperty('--dx', Math.cos(angle) * velocity + 'px');
            particle.style.setProperty('--dy', Math.sin(angle) * velocity + 'px');
            
            button.style.position = 'relative';
            button.appendChild(particle);
            
            setTimeout(() => particle.remove(), 600);
        }
        
        // Agregar estilo de animación si no existe
        if (!document.getElementById('particleStyles')) {
            const style = document.createElement('style');
            style.id = 'particleStyles';
            style.textContent = `
                @keyframes particleExplode {
                    0% {
                        transform: translate(0, 0) scale(1);
                        opacity: 1;
                    }
                    100% {
                        transform: translate(var(--dx), var(--dy)) scale(0);
                        opacity: 0;
                    }
                }
                
                @keyframes slideOutUp {
                    from {
                        transform: translateY(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateY(-30px);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    // Efecto de escritura para el título
    function typeWriter() {
        const titleElement = document.querySelector('.title-text');
        if (titleElement) {
            const text = titleElement.textContent;
            titleElement.textContent = '';
            titleElement.style.borderRight = '2px solid #28a745';
            
            let i = 0;
            const timer = setInterval(() => {
                titleElement.textContent += text.charAt(i);
                i++;
                if (i >= text.length) {
                    clearInterval(timer);
                    setTimeout(() => {
                        titleElement.style.borderRight = 'none';
                    }, 500);
                }
            }, 100);
        }
    }
    
    // Función para alternar vista de tabla
    function toggleTableView() {
        const table = document.getElementById('clientesTable');
        const toggleBtn = document.getElementById('viewToggle');
        
        if (table.classList.contains('compact-view')) {
            table.classList.remove('compact-view');
            toggleBtn.innerHTML = '<i class="fas fa-th-list"></i> Vista Compacta';
        } else {
            table.classList.add('compact-view');
            toggleBtn.innerHTML = '<i class="fas fa-th"></i> Vista Expandida';
        }
    }
    
    // Función para ver detalles del cliente
    function verDetalles(clienteId) {
        // Aquí podrías implementar un modal con más detalles del cliente
        alert('Funcionalidad de detalles para cliente ID: ' + clienteId + '\n(Próximamente se implementará un modal con información detallada)');
    }
    
    // Función para exportar datos
    function exportarDatos() {
        // Obtener filtros actuales
        const params = new URLSearchParams(window.location.search);
        params.set('export', 'excel');
        
        // Crear enlace temporal para descarga
        const link = document.createElement('a');
        link.href = '/RMIE/app/controllers/ClientController.php?' + params.toString();
        link.click();
    }
    </script>
    
    <!-- Estilos adicionales para diseño avanzado -->
    <style>
    /* Partículas de fondo animadas */
    .particles-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
        overflow: hidden;
    }
    
    .particle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: linear-gradient(45deg, #28a745, #20c997);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
        opacity: 0.3;
    }
    
    .particle:nth-child(1) {
        left: 20%;
        animation-delay: 0s;
        animation-duration: 6s;
    }
    
    .particle:nth-child(2) {
        left: 40%;
        animation-delay: 2s;
        animation-duration: 8s;
    }
    
    .particle:nth-child(3) {
        left: 60%;
        animation-delay: 4s;
        animation-duration: 7s;
    }
    
    .particle:nth-child(4) {
        left: 80%;
        animation-delay: 1s;
        animation-duration: 9s;
    }
    
    .particle:nth-child(5) {
        left: 90%;
        animation-delay: 3s;
        animation-duration: 5s;
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(100vh) rotate(0deg);
            opacity: 0;
        }
        10%, 90% {
            opacity: 0.3;
        }
        50% {
            transform: translateY(-100px) rotate(180deg);
            opacity: 0.6;
        }
    }
    
    /* Header mejorado */
    .clientes-header {
        position: relative;
        z-index: 1;
        margin-bottom: 2rem;
    }
    
    .main-title-section {
        text-align: center;
        margin: 2rem 0;
        position: relative;
    }
    
    .main-title {
        position: relative;
        display: inline-block;
        margin-bottom: 1rem;
        padding: 0;
    }
    
    .title-icon {
        display: inline-block;
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        margin: 0 auto 1rem;
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        animation: pulse 2s infinite;
    }
    
    .title-text {
        display: block;
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(135deg, #28a745, #20c997);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .title-decoration {
        display: block;
        width: 100px;
        height: 4px;
        background: linear-gradient(90deg, #28a745, #20c997);
        margin: 1rem auto;
        border-radius: 2px;
        animation: expand 2s ease-in-out infinite alternate;
    }
    
    .title-subtitle {
        font-size: 1.2rem;
        color: #6c757d;
        font-weight: 400;
        margin-top: 0.5rem;
        opacity: 0.8;
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(40, 167, 69, 0.5);
        }
    }
    
    @keyframes expand {
        0% {
            width: 100px;
        }
        100% {
            width: 150px;
        }
    }
    
    /* Mejoras en estadísticas con glassmorphism */
    .stat-card {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transform: rotate(45deg);
        transition: all 0.5s;
        opacity: 0;
    }
    
    .stat-card:hover::after {
        animation: shine 0.5s ease-in-out;
    }
    
    @keyframes shine {
        0% {
            transform: translateX(-100%) rotate(45deg);
            opacity: 0;
        }
        50% {
            opacity: 1;
        }
        100% {
            transform: translateX(100%) rotate(45deg);
            opacity: 0;
        }
    }
    
    /* Efectos de glassmorphism para formularios */
    .clientes-filters,
    .clientes-form,
    .table-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        z-index: 1;
    }
    
    /* Botones con efectos de neomorfismo */
    .clientes-actions .btn,
    .clientes-buttons .btn {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        box-shadow: 
            0 8px 16px rgba(40, 167, 69, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.2),
            inset 0 -1px 0 rgba(0, 0, 0, 0.1);
    }
    
    .clientes-actions .btn::before,
    .clientes-buttons .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }
    
    .clientes-actions .btn:hover::before,
    .clientes-buttons .btn:hover::before {
        left: 100%;
    }
    
    /* Mejoras en la tabla con efectos avanzados */
    .table-container {
        position: relative;
        overflow: hidden;
    }
    
    .table-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        position: relative;
        overflow: hidden;
    }
    
    .table-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: repeating-linear-gradient(
            45deg,
            transparent,
            transparent 10px,
            rgba(255, 255, 255, 0.05) 10px,
            rgba(255, 255, 255, 0.05) 20px
        );
        animation: move 20s linear infinite;
    }
    
    @keyframes move {
        0% {
            transform: translate(-50%, -50%) rotate(0deg);
        }
        100% {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }
    
    /* Efectos en las filas de la tabla */
    .cliente-row:hover {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.05), rgba(32, 201, 151, 0.05));
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(40, 167, 69, 0.15);
    }
    
    /* Avatar con efectos especiales */
    .client-avatar {
        position: relative;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }
    
    .client-avatar::after {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        border-radius: 50%;
        background: linear-gradient(45deg, #28a745, #20c997, #17a2b8, #6f42c1);
        z-index: -1;
        animation: rotate 3s linear infinite;
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .client-info:hover .client-avatar::after {
        opacity: 1;
    }
    
    @keyframes rotate {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    
    /* Badges con efectos de brillo */
    .status-badge,
    .local-badge {
        position: relative;
        overflow: hidden;
    }
    
    .status-badge::after,
    .local-badge::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transform: rotate(45deg);
        transition: all 0.6s;
        opacity: 0;
    }
    
    .status-badge:hover::after,
    .local-badge:hover::after {
        animation: badgeShine 0.6s ease-in-out;
    }
    
    @keyframes badgeShine {
        0% {
            transform: translateX(-100%) rotate(45deg);
            opacity: 0;
        }
        50% {
            opacity: 1;
        }
        100% {
            transform: translateX(100%) rotate(45deg);
            opacity: 0;
        }
    }
    
    /* Responsive mejorado */
    @media (max-width: 768px) {
        .title-text {
            font-size: 2rem;
        }
        
        .title-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .particles-background {
            display: none;
        }
    }
    
    /* Modo oscuro (preparado para futuro) */
    @media (prefers-color-scheme: dark) {
        .clientes-container {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        }
        
        .title-text {
            color: #ffffff;
        }
        
        .title-subtitle {
            color: #b0b0b0;
        }
    }
    
    /* Estilos adicionales para la tabla */
    /* Estilos para la tabla de clientes */
    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .table-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .table-header h5 {
        margin: 0;
        font-weight: 600;
    }
    
    .table-actions .btn {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
    }
    
    .table-actions .btn:hover {
        background: rgba(255,255,255,0.3);
        border-color: rgba(255,255,255,0.5);
        color: white;
    }
    
    .clientes-data-table {
        margin-bottom: 0;
    }
    
    .clientes-data-table thead th {
        background: #343a40 !important;
        color: white;
        font-weight: 600;
        border-bottom: none;
        padding: 1rem 0.75rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .clientes-data-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #dee2e6;
    }
    
    .clientes-data-table tbody tr:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .table-row-highlighted {
        background-color: #e3f2fd !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(33, 150, 243, 0.2);
    }
    
    .client-id {
        font-weight: bold;
        color: #6c757d;
        font-family: 'Courier New', monospace;
        background: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.85rem;
    }
    
    .client-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .client-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    
    .client-details {
        flex: 1;
        min-width: 0;
    }
    
    .client-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .client-description {
        font-size: 0.85rem;
        color: #6c757d;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .contact-info {
        line-height: 1.4;
    }
    
    .contact-email, .contact-phone {
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .contact-email span, .contact-phone span {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
    }
    
    .local-badge {
        background: #e3f2fd;
        color: #1976d2;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 120px;
    }
    
    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }
    
    .status-badge.status-activo {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .status-badge.status-inactivo {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .fecha-registro {
        text-align: center;
    }
    
    .fecha-full {
        font-size: 0.9rem;
        color: #495057;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
    }
    
    .fecha-time {
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }
    
    .action-buttons .btn-group {
        gap: 0.25rem;
    }
    
    .action-buttons .btn {
        transition: all 0.2s ease;
    }
    
    .action-buttons .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .table-footer {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-top: 1px solid #dee2e6;
    }
    
    /* Vista compacta */
    .compact-view .client-description,
    .compact-view .contact-phone,
    .compact-view .fecha-time {
        display: none !important;
    }
    
    .compact-view .client-avatar {
        width: 32px;
        height: 32px;
        font-size: 0.9rem;
    }
    
    .compact-view .clientes-data-table tbody tr td {
        padding: 0.5rem 0.75rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .table-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .client-info {
            flex-direction: column;
            text-align: center;
            gap: 0.5rem;
        }
        
        .contact-email span, .contact-phone span {
            max-width: 100px;
        }
        
        .local-badge {
            max-width: 100px;
        }
    }
    
    /* Impresión */
    @media print {
        .table-header,
        .table-actions,
        .action-buttons,
        .clientes-filters,
        .clientes-actions {
            display: none !important;
        }
        
        .table-container {
            box-shadow: none;
            border: 1px solid #dee2e6;
        }
        
        .clientes-data-table {
            font-size: 0.8rem;
        }
        
        .client-avatar {
            background: #6c757d !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    /* Efectos de hover mejorados */
    .table tbody tr {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(0, 123, 255, 0.05));
    }

    /* Efectos de botones mejorados */
    .btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn:active {
        transform: scale(0.95);
    }

    /* Animaciones de entrada para las filas */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .table tbody tr {
        animation: fadeInUp 0.6s ease-out;
        animation-fill-mode: both;
    }

    .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
    .table tbody tr:nth-child(2) { animation-delay: 0.2s; }
    .table tbody tr:nth-child(3) { animation-delay: 0.3s; }
    .table tbody tr:nth-child(4) { animation-delay: 0.4s; }
    .table tbody tr:nth-child(5) { animation-delay: 0.5s; }

    /* Efecto de pulso para números importantes */
    .stat-number {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }

    /* Efectos de glassmorphism mejorados */
    .glassmorphism-enhanced {
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 
            0 8px 32px rgba(0, 0, 0, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.2),
            inset 0 -1px 0 rgba(0, 0, 0, 0.1);
    }

    /* Efecto de brillo en el encabezado */
    .header-glow {
        position: relative;
        overflow: hidden;
    }

    .header-glow::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        animation: shine 3s infinite;
        pointer-events: none;
    }

    @keyframes shine {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }

    /* Efectos de notificación */
    .notification-success {
        animation: bounceIn 0.6s ease-out;
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .notification-error {
        animation: shakeX 0.6s ease-out;
        background: linear-gradient(135deg, #dc3545, #e74c3c);
        border: none;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    @keyframes bounceIn {
        0% { transform: scale(0.3); opacity: 0; }
        50% { transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); opacity: 1; }
    }

    @keyframes shakeX {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
        20%, 40%, 60%, 80% { transform: translateX(10px); }
    }

    /* Efecto de carga mejorado */
    .loading-enhanced {
        position: relative;
    }

    .loading-enhanced::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: loading-shimmer 1.5s infinite;
    }

    @keyframes loading-shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }
    </style>
</body>
</html>
