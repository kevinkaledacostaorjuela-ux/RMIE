<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Cliente - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="clientes-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="subcategorias-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/RMIE/app/controllers/ClientController.php?accion=index">Clientes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Eliminar</li>
            </ol>
        </nav>

        <h1><i class="fas fa-user-times text-danger"></i> Eliminar Cliente</h1>
        
        <!-- Mostrar mensajes de error -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="delete-confirmation">
            <!-- Información del cliente a eliminar -->
            <div class="client-info-card">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-4">
                            <div class="client-avatar-large">
                                <?= strtoupper(substr($cliente->nombre, 0, 1)) ?>
                            </div>
                            <div class="ms-3">
                                <h4 class="mb-1"><?= htmlspecialchars($cliente->nombre) ?></h4>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-hashtag"></i> 
                                    ID: <?= htmlspecialchars($cliente->id_clientes) ?>
                                </p>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-envelope"></i> 
                                    <?= htmlspecialchars($cliente->correo) ?>
                                </p>
                                <?php if ($cliente->cel_cliente): ?>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-phone"></i> 
                                    <?= htmlspecialchars($cliente->cel_cliente) ?>
                                </p>
                                <?php endif; ?>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-store"></i> 
                                    <?= htmlspecialchars($cliente->local_nombre) ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="client-details">
                            <div class="detail-item">
                                <span class="label">Estado:</span>
                                <span class="status-badge status-<?= $cliente->estado ?>">
                                    <?php if ($cliente->estado === 'activo'): ?>
                                        <i class="fas fa-check-circle"></i> Activo
                                    <?php else: ?>
                                        <i class="fas fa-times-circle"></i> Inactivo
                                    <?php endif; ?>
                                </span>
                            </div>
                            
                            <?php if ($cliente->descripcion): ?>
                            <div class="detail-item">
                                <span class="label">Descripción:</span>
                                <span class="value"><?= htmlspecialchars($cliente->descripcion) ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($cliente->fecha_creacion): ?>
                            <div class="detail-item">
                                <span class="label">Fecha de registro:</span>
                                <span class="value">
                                    <i class="fas fa-calendar-alt"></i>
                                    <?= date('d/m/Y H:i', strtotime($cliente->fecha_creacion)) ?>
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="stats-summary">
                            <h6><i class="fas fa-chart-bar"></i> Actividad del Cliente</h6>
                            <?php if (isset($stats) && $stats): ?>
                                <div class="stat-item">
                                    <span class="number"><?= $stats['ventas_realizadas'] ?? 0 ?></span>
                                    <span class="label">Compras realizadas</span>
                                </div>
                                <div class="stat-item">
                                    <span class="number">$<?= number_format($stats['total_ventas'] ?? 0, 2) ?></span>
                                    <span class="label">Total gastado</span>
                                </div>
                            <?php else: ?>
                                <div class="stat-item">
                                    <span class="number">0</span>
                                    <span class="label">Compras realizadas</span>
                                </div>
                                <div class="stat-item">
                                    <span class="number">$0.00</span>
                                    <span class="label">Total gastado</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Advertencia de eliminación -->
            <div class="danger-warning">
                <div class="alert alert-danger" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-3x me-3"></i>
                        <div>
                            <h5 class="alert-heading">¡Advertencia: Acción Irreversible!</h5>
                            <p class="mb-2">
                                Está a punto de eliminar permanentemente al cliente 
                                <strong><?= htmlspecialchars($cliente->nombre) ?></strong>.
                            </p>
                            <ul class="mb-0">
                                <li>Esta acción no se puede deshacer</li>
                                <li>Se eliminará toda la información del cliente</li>
                                <?php if (isset($stats) && $stats && ($stats['ventas_realizadas'] > 0)): ?>
                                <li class="text-warning">
                                    <strong>Atención:</strong> Este cliente tiene <?= $stats['ventas_realizadas'] ?> compras asociadas
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Verificaciones adicionales -->
            <div class="safety-checks">
                <h6><i class="fas fa-clipboard-check"></i> Verificaciones de Seguridad</h6>
                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                    <label class="form-check-label" for="confirmDelete">
                        Confirmo que deseo eliminar este cliente permanentemente
                    </label>
                </div>
                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="confirmBackup" required>
                    <label class="form-check-label" for="confirmBackup">
                        He verificado que no necesito conservar la información de este cliente
                    </label>
                </div>
                
                <?php if (isset($stats) && $stats && ($stats['ventas_realizadas'] > 0)): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="confirmSales" required>
                    <label class="form-check-label text-warning" for="confirmSales">
                        <strong>Entiendo que las compras asociadas a este cliente se mantendrán pero sin referencia al cliente</strong>
                    </label>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Confirmación con contraseña del administrador -->
            <div class="admin-confirmation">
                <h6><i class="fas fa-key"></i> Confirmación de Administrador</h6>
                <div class="form-group">
                    <label for="admin_password">Ingrese su contraseña para confirmar:</label>
                    <input type="password" 
                           id="admin_password" 
                           name="admin_password" 
                           class="form-control" 
                           required
                           placeholder="Contraseña de administrador">
                    <small class="form-text text-muted">
                        Se requiere la contraseña del administrador actual para eliminar clientes
                    </small>
                </div>
            </div>
        </div>
        
        <!-- Formulario de eliminación -->
        <form method="POST" action="/RMIE/app/controllers/ClientController.php?accion=delete&id=<?= $cliente->id_clientes ?>" id="deleteForm">
            <input type="hidden" name="admin_password_confirm" id="admin_password_confirm">
            
            <div class="clientes-buttons danger-zone">
                <button type="submit" class="btn btn-danger" id="deleteButton" disabled>
                    <i class="fas fa-trash-alt"></i> Eliminar Cliente Permanentemente
                </button>
                <a href="/RMIE/app/controllers/ClientController.php?accion=index" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
                <button type="button" class="btn btn-info" onclick="window.print()" title="Imprimir información del cliente">
                    <i class="fas fa-print"></i> Imprimir Info
                </button>
            </div>
        </form>
        
        <!-- Modal de confirmación final -->
        <div class="modal fade" id="finalConfirmModal" tabindex="-1" aria-labelledby="finalConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="finalConfirmModalLabel">
                            <i class="fas fa-exclamation-triangle"></i> Confirmación Final
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-user-times fa-4x text-danger mb-3"></i>
                        <h5>¿Está completamente seguro?</h5>
                        <p>Esta acción eliminará permanentemente al cliente:</p>
                        <div class="alert alert-warning">
                            <strong><?= htmlspecialchars($cliente->nombre) ?></strong><br>
                            <small>ID: <?= htmlspecialchars($cliente->id_clientes) ?> | <?= htmlspecialchars($cliente->correo) ?></small>
                        </div>
                        <p class="text-muted">
                            Escriba <strong>ELIMINAR</strong> para confirmar:
                        </p>
                        <input type="text" id="finalConfirmText" class="form-control" placeholder="Escriba ELIMINAR">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="finalDeleteButton" disabled>
                            Eliminar Definitivamente
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript para validaciones y confirmaciones -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButton = document.getElementById('deleteButton');
        const deleteForm = document.getElementById('deleteForm');
        const checkboxes = document.querySelectorAll('.form-check-input');
        const adminPassword = document.getElementById('admin_password');
        const finalConfirmModal = new bootstrap.Modal(document.getElementById('finalConfirmModal'));
        const finalConfirmText = document.getElementById('finalConfirmText');
        const finalDeleteButton = document.getElementById('finalDeleteButton');
        
        // Habilitar botón de eliminar solo cuando todas las verificaciones estén completas
        function checkFormValidity() {
            let allChecked = true;
            checkboxes.forEach(function(checkbox) {
                if (!checkbox.checked) {
                    allChecked = false;
                }
            });
            
            const hasPassword = adminPassword.value.length >= 6;
            deleteButton.disabled = !(allChecked && hasPassword);
        }
        
        // Agregar listeners a las verificaciones
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', checkFormValidity);
        });
        
        adminPassword.addEventListener('input', checkFormValidity);
        
        // Interceptar envío del formulario para mostrar confirmación final
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Copiar la contraseña al campo oculto
            document.getElementById('admin_password_confirm').value = adminPassword.value;
            
            // Mostrar modal de confirmación final
            finalConfirmModal.show();
        });
        
        // Validar texto de confirmación final
        finalConfirmText.addEventListener('input', function() {
            const isValid = this.value.toUpperCase() === 'ELIMINAR';
            finalDeleteButton.disabled = !isValid;
            
            if (isValid) {
                finalDeleteButton.classList.remove('btn-secondary');
                finalDeleteButton.classList.add('btn-danger');
            } else {
                finalDeleteButton.classList.remove('btn-danger');
                finalDeleteButton.classList.add('btn-secondary');
            }
        });
        
        // Envío final del formulario
        finalDeleteButton.addEventListener('click', function() {
            if (finalConfirmText.value.toUpperCase() === 'ELIMINAR') {
                // Mostrar mensaje de procesamiento
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Eliminando...';
                this.disabled = true;
                
                // Enviar formulario
                deleteForm.submit();
            }
        });
        
        // Limpiar modal al cerrarlo
        document.getElementById('finalConfirmModal').addEventListener('hidden.bs.modal', function() {
            finalConfirmText.value = '';
            finalDeleteButton.disabled = true;
            finalDeleteButton.classList.remove('btn-danger');
            finalDeleteButton.classList.add('btn-secondary');
            finalDeleteButton.innerHTML = 'Eliminar Definitivamente';
        });
        
        // Auto-ocultar alertas después de 5 segundos
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-danger');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('show')) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 150);
                }
            });
        }, 5000);
    });
    </script>
    
    <!-- Estilos adicionales -->
    <style>
    .delete-confirmation {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .client-info-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #dc3545;
    }
    
    .client-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 2rem;
    }
    
    .client-details {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }
    
    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .detail-item .label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
    }
    
    .detail-item .value {
        font-weight: 600;
        color: #333;
    }
    
    .stats-summary {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        border: 1px solid #dee2e6;
    }
    
    .stat-item {
        margin-bottom: 1rem;
    }
    
    .stat-item .number {
        display: block;
        font-size: 1.5rem;
        font-weight: bold;
        color: #dc3545;
    }
    
    .stat-item .label {
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .danger-warning {
        margin: 2rem 0;
    }
    
    .safety-checks {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
        padding: 1.5rem;
        margin: 1.5rem 0;
    }
    
    .safety-checks h6 {
        color: #856404;
        margin-bottom: 1rem;
    }
    
    .form-check {
        margin-bottom: 1rem;
    }
    
    .form-check-label {
        font-weight: 500;
    }
    
    .admin-confirmation {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 8px;
        padding: 1.5rem;
        margin: 1.5rem 0;
    }
    
    .admin-confirmation h6 {
        color: #721c24;
        margin-bottom: 1rem;
    }
    
    .danger-zone {
        background: #f8d7da;
        border-radius: 8px;
        padding: 1.5rem;
        border: 2px dashed #dc3545;
    }
    
    .btn-danger:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }
    
    /* Estilos para impresión */
    @media print {
        .clientes-buttons, .modal, .alert { display: none; }
        nav { display: none; }
        body { margin: 0; }
        .clientes-container { margin: 0; padding: 20px; }
        
        .client-info-card {
            background: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
        }
        
        .stats-summary {
            background: white !important;
            -webkit-print-color-adjust: exact;
        }
    }
    </style>
</body>
</html>
