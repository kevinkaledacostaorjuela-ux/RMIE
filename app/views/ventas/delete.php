<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Venta - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="ventas-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="subcategorias-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/RMIE/app/controllers/SaleController.php?accion=index">Ventas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Eliminar</li>
            </ol>
        </nav>

        <h1><i class="fas fa-trash-alt text-danger"></i> Eliminar Venta #<?= htmlspecialchars($venta->id_ventas) ?></h1>
        
        <!-- Alerta de confirmación -->
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>¡Advertencia!</strong> Esta acción no se puede deshacer.
        </div>
        
        <div class="delete-confirmation">
            <div class="confirmation-message">
                <h4><i class="fas fa-question-circle"></i> ¿Está seguro de que desea eliminar esta venta?</h4>
                <p class="text-muted">Una vez eliminada, toda la información de esta venta se perderá permanentemente.</p>
            </div>
            
            <!-- Información de la venta a eliminar -->
            <div class="sale-details-card">
                <h5><i class="fas fa-info-circle"></i> Detalles de la Venta</h5>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-group">
                            <label>ID de la venta:</label>
                            <span class="value">#<?= htmlspecialchars($venta->id_ventas) ?></span>
                        </div>
                        
                        <div class="detail-group">
                            <label>Nombre:</label>
                            <span class="value"><?= htmlspecialchars($venta->nombre) ?></span>
                        </div>
                        
                        <div class="detail-group">
                            <label>Cliente:</label>
                            <span class="value"><?= htmlspecialchars($venta->cliente_nombre ?? 'N/A') ?></span>
                        </div>
                        
                        <div class="detail-group">
                            <label>Dirección:</label>
                            <span class="value"><?= htmlspecialchars($venta->direccion) ?></span>
                        </div>
                        
                        <div class="detail-group">
                            <label>Fecha de venta:</label>
                            <span class="value"><?= htmlspecialchars(date('d/m/Y', strtotime($venta->fecha_venta))) ?></span>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="detail-group">
                            <label>Producto:</label>
                            <span class="value"><?= htmlspecialchars($venta->producto_nombre ?? 'N/A') ?></span>
                        </div>
                        
                        <div class="detail-group">
                            <label>Cantidad:</label>
                            <span class="value"><?= htmlspecialchars($venta->cantidad) ?> unidades</span>
                        </div>
                        
                        <div class="detail-group">
                            <label>Precio unitario:</label>
                            <span class="value">$<?= number_format($venta->precio_unitario ?? 0, 2) ?></span>
                        </div>
                        
                        <div class="detail-group">
                            <label>Total:</label>
                            <span class="value total-amount">$<?= number_format($venta->total ?? 0, 2) ?></span>
                        </div>
                        
                        <div class="detail-group">
                            <label>Estado:</label>
                            <span class="badge-status status-<?= strtolower($venta->estado) ?>">
                                <?php
                                $estados = [
                                    'pendiente' => ['text' => 'Pendiente', 'icon' => 'fas fa-clock'],
                                    'procesando' => ['text' => 'Procesando', 'icon' => 'fas fa-spinner'],
                                    'completada' => ['text' => 'Completada', 'icon' => 'fas fa-check-circle'],
                                    'cancelada' => ['text' => 'Cancelada', 'icon' => 'fas fa-times-circle']
                                ];
                                $estadoInfo = $estados[$venta->estado] ?? ['text' => $venta->estado, 'icon' => 'fas fa-question'];
                                ?>
                                <i class="<?= $estadoInfo['icon'] ?>"></i>
                                <?= $estadoInfo['text'] ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <?php if (isset($venta->fecha_creacion)): ?>
                <div class="detail-group">
                    <label>Fecha de creación:</label>
                    <span class="value"><?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($venta->fecha_creacion))) ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Advertencias adicionales -->
            <div class="alert alert-warning">
                <i class="fas fa-info-circle"></i>
                <strong>Tenga en cuenta:</strong>
                <ul class="mb-0 mt-2">
                    <li>Esta venta será eliminada permanentemente del sistema</li>
                    <li>Los datos no podrán ser recuperados</li>
                    <li>Esta acción puede afectar los reportes de ventas</li>
                    <?php if ($venta->estado === 'completada'): ?>
                        <li class="text-danger"><strong>Esta venta está completada</strong> - eliminarla puede afectar el inventario</li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <!-- Formulario de confirmación -->
            <form method="POST" action="/RMIE/app/controllers/SaleController.php?accion=delete&id=<?= $venta->id_ventas ?>" id="deleteForm">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                    <label class="form-check-label" for="confirmDelete">
                        Confirmo que deseo eliminar esta venta permanentemente
                    </label>
                </div>
                
                <div class="ventas-buttons">
                    <button type="submit" class="btn btn-danger" id="deleteBtn" disabled>
                        <i class="fas fa-trash-alt"></i> Sí, Eliminar Venta
                    </button>
                    <a href="/RMIE/app/controllers/SaleController.php?accion=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript para confirmación -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('confirmDelete');
        const deleteBtn = document.getElementById('deleteBtn');
        const form = document.getElementById('deleteForm');
        
        // Habilitar/deshabilitar botón según checkbox
        checkbox.addEventListener('change', function() {
            deleteBtn.disabled = !this.checked;
        });
        
        // Confirmación adicional al enviar
        form.addEventListener('submit', function(e) {
            if (!checkbox.checked) {
                e.preventDefault();
                alert('Debe confirmar que desea eliminar la venta');
                return;
            }
            
            const confirmed = confirm('¿Está absolutamente seguro de que desea eliminar esta venta?\n\nEsta acción NO se puede deshacer.');
            if (!confirmed) {
                e.preventDefault();
            }
        });
        
        // Enfocar el checkbox al cargar la página
        setTimeout(() => {
            checkbox.focus();
        }, 500);
    });
    </script>
    
    <style>
        .delete-confirmation {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .confirmation-message {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .sale-details-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #dc3545;
        }
        
        .detail-group {
            margin-bottom: 1rem;
        }
        
        .detail-group label {
            font-weight: 600;
            color: #495057;
            display: inline-block;
            min-width: 120px;
        }
        
        .detail-group .value {
            color: #212529;
        }
        
        .total-amount {
            font-weight: bold;
            font-size: 1.1em;
            color: #28a745;
        }
        
        .form-check-label {
            font-weight: 500;
        }
        
        .btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
    </style>
</body>
</html>
