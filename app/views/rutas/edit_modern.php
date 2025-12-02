<?php
// Vista moderna para editar rutas - RMIE v3.0
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ruta #<?php echo htmlspecialchars($ruta['id_ruta'] ?? 'N/A'); ?> - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .add-local-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            color: white;
        }
        
        .btn-add-local {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .btn-add-local:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        .add-local-help {
            margin-top: 10px;
            font-size: 14px;
            opacity: 0.9;
        }
        
        .local-card-new {
            border-left: 4px solid #28a745 !important;
            position: relative;
        }
        
        .new-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            margin-left: 10px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .local-card-new .local-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .cliente-select-new, .local-select-new {
            border: 2px solid #28a745 !important;
        }
    </style>
    
    <!-- jQuery y Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>
    <div class="rutas-container">
        <!-- Breadcrumb de Navegación -->
        <div class="rutas-breadcrumb">
            <a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
            <span class="separator">/</span>
            <a href="/RMIE/app/controllers/RouteControllerModern.php?accion=index"><i class="fas fa-route"></i> Rutas</a>
            <span class="separator">/</span>
            <span>Editar Ruta #<?php echo htmlspecialchars($ruta['id_ruta'] ?? 'N/A'); ?></span>
        </div>

        <!-- Header con Título y Botones de Navegación -->
        <div class="rutas-header">
            <div>
                <h1><i class="fas fa-edit"></i> Editar Ruta</h1>
                <p>Modificar los datos de la ruta #<?php echo htmlspecialchars($ruta['id_ruta'] ?? 'N/A'); ?></p>
            </div>
            <div class="rutas-nav-buttons">
                <a href="/RMIE/app/views/dashboard.php" class="btn-rutas btn-rutas-info">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="/RMIE/app/controllers/RouteControllerModern.php?accion=index" class="btn-rutas btn-rutas-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Listado
                </a>
            </div>
        </div>

        <!-- Mensajes de Estado -->
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="rutas-alert rutas-alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="rutas-alert rutas-alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de Edición -->
        <div class="rutas-form">
            <form method="POST" action="/RMIE/app/controllers/RouteControllerModern.php?accion=edit&id=<?php echo $ruta['id_ruta']; ?>" id="form-edit-ruta">
                
                <!-- Locales Asignados al Día -->
                <div class="form-section">
                    <div class="section-header">
                        <h3><i class="fas fa-store"></i> Locales Asignados - <?php echo ucfirst($ruta['dia_semana'] ?? 'N/A'); ?></h3>
                        <p>Todos los locales programados para este día</p>
                    </div>

                    <div class="locales-container">
                        <!-- Botón para agregar nuevo cliente/local -->
                        <div class="add-local-section">
                            <button type="button" class="btn-add-local" id="btnAgregarLocal">
                                <i class="fas fa-plus-circle"></i> Agregar Cliente/Local al Día
                            </button>
                            <p class="add-local-help">
                                <i class="fas fa-info-circle"></i> 
                                Puedes agregar nuevos clientes o locales que hayan surgido de último momento
                            </p>
                        </div>

                        <?php if (!empty($rutas_dia)): ?>
                            <?php foreach ($rutas_dia as $index => $ruta_item): ?>
                            <div class="local-card" data-ruta-id="<?php echo $ruta_item['id_ruta']; ?>">
                                <div class="local-header">
                                    <span class="local-number"><?php echo ($index + 1); ?></span>
                                    <h4><?php echo htmlspecialchars($ruta_item['nombre_cliente_real'] ?? $ruta_item['nombre_cliente'] ?? 'Cliente sin nombre'); ?></h4>
                                </div>
                                
                                <div class="local-details">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>
                                                <i class="fas fa-user"></i> Cliente <span class="required">*</span>
                                            </label>
                                            <select class="form-control cliente-select" 
                                                    name="clientes[<?php echo $ruta_item['id_ruta']; ?>][id_cliente]"
                                                    data-ruta-id="<?php echo $ruta_item['id_ruta']; ?>"
                                                    data-current-cliente="<?php echo htmlspecialchars($ruta_item['nombre_cliente'] ?? ''); ?>"
                                                    data-cliente-id="<?php echo $ruta_item['id_clientes'] ?? ''; ?>"
                                                    required>
                                                <?php if (!empty($ruta_item['nombre_cliente'])): ?>
                                                <option value="<?php echo $ruta_item['id_clientes']; ?>" selected>
                                                    <?php echo htmlspecialchars($ruta_item['nombre_cliente']); ?>
                                                </option>
                                                <?php endif; ?>
                                            </select>
                                            <input type="hidden" 
                                                   name="clientes[<?php echo $ruta_item['id_ruta']; ?>][nombre_cliente]" 
                                                   value="<?php echo htmlspecialchars($ruta_item['nombre_cliente_real'] ?? $ruta_item['nombre_cliente'] ?? ''); ?>"
                                                   class="cliente-nombre-hidden">
                                        </div>

                                        <div class="form-group">
                                            <label>
                                                <i class="fas fa-store"></i> Local <span class="required">*</span>
                                            </label>
                                            <select class="form-control local-select" 
                                                    name="clientes[<?php echo $ruta_item['id_ruta']; ?>][id_local]"
                                                    data-ruta-id="<?php echo $ruta_item['id_ruta']; ?>"
                                                    data-current-local="<?php echo htmlspecialchars($ruta_item['nombre_local_real'] ?? $ruta_item['nombre_local'] ?? ''); ?>"
                                                    data-local-id="<?php echo $ruta_item['id_local_real'] ?? ''; ?>"
                                                    data-cliente-id="<?php echo $ruta_item['id_clientes'] ?? ''; ?>"
                                                    required>
                                                <?php if (!empty($ruta_item['nombre_local_real'])): ?>
                                                <option value="<?php echo $ruta_item['id_local_real']; ?>" selected>
                                                    <?php echo htmlspecialchars($ruta_item['nombre_local_real'] . ' - ' . $ruta_item['direccion_real']); ?>
                                                </option>
                                                <?php endif; ?>
                                            </select>
                                            <input type="hidden" 
                                                   name="clientes[<?php echo $ruta_item['id_ruta']; ?>][nombre_local]" 
                                                   value="<?php echo htmlspecialchars($ruta_item['nombre_local_real'] ?? $ruta_item['nombre_local'] ?? ''); ?>"
                                                   class="local-nombre-hidden">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>
                                            <i class="fas fa-map-marker-alt"></i> Dirección
                                        </label>
                                        <textarea class="form-control" 
                                                  name="clientes[<?php echo $ruta_item['id_ruta']; ?>][direccion]"
                                                  rows="2"
                                                  placeholder="Dirección completa"><?php echo htmlspecialchars($ruta_item['direccion_real'] ?? $ruta_item['direccion'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="local-actions">
                                    <button type="button" class="btn-local-remove" onclick="eliminarLocal(<?php echo $ruta_item['id_ruta']; ?>)">
                                        <i class="fas fa-trash"></i> Eliminar Local
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-locales">
                                <p>No hay locales asignados a este día</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="direccion" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Dirección <span class="required">*</span>
                            </label>
                            <textarea class="form-control" 
                                      id="direccion" 
                                      name="direccion" 
                                      rows="3"
                                      placeholder="Dirección completa de entrega"
                                      required><?php echo htmlspecialchars($ruta['direccion'] ?? ''); ?></textarea>
                            <small class="form-text">Dirección completa donde se realizará la entrega</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="dia_semana" class="form-label">
                                <i class="fas fa-calendar-day"></i> Día de la Semana <span class="required">*</span>
                            </label>
                            <select class="form-control" id="dia_semana" name="dia_semana" required>
                                <option value="">Seleccionar día...</option>
                                <option value="Lunes" <?php echo ($ruta['dia_semana'] ?? '') === 'Lunes' ? 'selected' : ''; ?>>Lunes</option>
                                <option value="Martes" <?php echo ($ruta['dia_semana'] ?? '') === 'Martes' ? 'selected' : ''; ?>>Martes</option>
                                <option value="Miercoles" <?php echo ($ruta['dia_semana'] ?? '') === 'Miercoles' ? 'selected' : ''; ?>>Miércoles</option>
                                <option value="Jueves" <?php echo ($ruta['dia_semana'] ?? '') === 'Jueves' ? 'selected' : ''; ?>>Jueves</option>
                                <option value="Viernes" <?php echo ($ruta['dia_semana'] ?? '') === 'Viernes' ? 'selected' : ''; ?>>Viernes</option>
                                <option value="Sabado" <?php echo ($ruta['dia_semana'] ?? '') === 'Sabado' ? 'selected' : ''; ?>>Sábado</option>
                                <option value="Domingo" <?php echo ($ruta['dia_semana'] ?? '') === 'Domingo' ? 'selected' : ''; ?>>Domingo</option>
                            </select>
                            <small class="form-text">Día de la semana programado para la entrega</small>
                        </div>

                        <div class="form-group">
                            <label for="estado" class="form-label">
                                <i class="fas fa-flag"></i> Estado <span class="required">*</span>
                            </label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="pendiente" <?php echo ($ruta['estado'] ?? '') === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="activa" <?php echo ($ruta['estado'] ?? '') === 'activa' ? 'selected' : ''; ?>>Activa</option>
                                <option value="completada" <?php echo ($ruta['estado'] ?? '') === 'completada' ? 'selected' : ''; ?>>Completada</option>
                                <option value="cancelada" <?php echo ($ruta['estado'] ?? '') === 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                                <option value="inactiva" <?php echo ($ruta['estado'] ?? '') === 'inactiva' ? 'selected' : ''; ?>>Inactiva</option>
                            </select>
                            <small class="form-text">Estado actual de la ruta</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="id_clientes" class="form-label">
                                <i class="fas fa-hashtag"></i> ID Cliente
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="id_clientes" 
                                   name="id_clientes" 
                                   value="<?php echo htmlspecialchars($ruta['id_clientes'] ?? ''); ?>"
                                   placeholder="ID del cliente (opcional)">
                            <small class="form-text">ID numérico del cliente en el sistema</small>
                        </div>

                        <div class="form-group">
                            <label for="id_reportes" class="form-label">
                                <i class="fas fa-chart-line"></i> ID Reporte
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="id_reportes" 
                                   name="id_reportes" 
                                   value="<?php echo htmlspecialchars($ruta['id_reportes'] ?? ''); ?>"
                                   placeholder="ID del reporte (opcional)">
                            <small class="form-text">ID del reporte asociado (si existe)</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="id_ventas" class="form-label">
                                <i class="fas fa-shopping-cart"></i> ID Venta
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="id_ventas" 
                                   name="id_ventas" 
                                   value="<?php echo htmlspecialchars($ruta['id_ventas'] ?? ''); ?>"
                                   placeholder="ID de la venta (opcional)">
                            <small class="form-text">ID de la venta asociada (si existe)</small>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="form-actions">
                    <a href="/RMIE/app/controllers/RouteControllerModern.php?accion=view&id=<?php echo $ruta['id_ruta']; ?>" 
                       class="btn-rutas btn-rutas-info">
                        <i class="fas fa-eye"></i> Ver Detalles
                    </a>
                    
                    <div class="d-flex gap-2">
                        <a href="/RMIE/app/controllers/RouteControllerModern.php?accion=index" 
                           class="btn-rutas btn-rutas-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn-rutas btn-rutas-success" id="btn-guardar-cambios">
                            <i class="fas fa-save"></i> <span id="btn-text">Guardar Cambios</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- CSS adicional -->
    <style>
        .form-section {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(0,0,0,0.1);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        
        .section-header {
            margin-bottom: 20px;
            text-align: center;
        }
        
        .section-header h3 {
            color: #2c3e50;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .section-header p {
            color: #7f8c8d;
            margin: 0;
            font-size: 0.95em;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            align-items: start;
        }
        
        .form-group {
            flex: 1;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95em;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e8ecf1;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.9);
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-text {
            display: block;
            margin-top: 5px;
            font-size: 0.8em;
            color: #6c757d;
        }
        
        .required {
            color: #e74c3c;
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }
        
        .d-flex {
            display: flex;
        }
        
        .gap-2 {
            gap: 10px;
        }
        
        /* Estilos para locales */
        .locales-container {
            display: grid;
            gap: 20px;
        }
        
        .local-card {
            background: white;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .local-card:hover {
            border-color: #667eea;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        }
        
        .local-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .local-number {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .local-header h4 {
            margin: 0;
            font-size: 1.1em;
        }
        
        .local-details {
            padding: 20px;
        }
        
        .local-actions {
            padding: 15px 20px;
            background: #f8f9fa;
            border-top: 1px solid #e0e0e0;
            text-align: right;
        }
        
        .btn-local-remove {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            transition: all 0.3s ease;
        }
        
        .btn-local-remove:hover {
            background: #c82333;
        }
        
        .empty-locales {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        /* Estilos para Select2 personalizados */
        .select2-result-cliente,
        .select2-result-local {
            padding: 8px 12px;
        }
        
        .select2-result-cliente__title,
        .select2-result-local__title {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }
        
        .select2-result-cliente__description,
        .select2-result-local__address {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 4px;
        }
        
        .select2-result-cliente__meta,
        .select2-result-local__meta {
            font-size: 0.8em;
            color: #999;
        }
        
        .select2-container--default .select2-results__option--highlighted {
            background-color: #667eea;
            color: white;
        }
        
        .select2-container {
            width: 100% !important;
        }
        
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 15px;
            }
            
            .form-actions {
                flex-direction: column;
                gap: 15px;
            }
            
            .local-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>

    <!-- Scripts -->
    <script>
        $(document).ready(function() {
            // Inicializar Select2 para los selects básicos
            $('#dia_semana, #estado').select2({
                placeholder: 'Seleccionar opción...',
                allowClear: false,
                width: '100%'
            });
            
            // Inicializar Select2 para clientes con búsqueda AJAX
            $('.cliente-select').each(function() {
                var $select = $(this);
                var clienteId = $select.data('cliente-id');
                var currentCliente = $select.data('current-cliente');
                
                $select.select2({
                    placeholder: 'Buscar cliente...',
                    allowClear: true,
                    width: '100%',
                    ajax: {
                        url: '/RMIE/app/api/get_clientes_edit.php',
                        dataType: 'json',
                        delay: 300,
                        data: function (params) {
                            return {
                                q: params.term,
                                page: params.page
                            };
                        },
                        processResults: function (data, params) {
                            return {
                                results: data.results,
                                pagination: {
                                    more: (params.page * 50) < data.total_count
                                }
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 0,
                    templateResult: formatClienteResult,
                    templateSelection: formatClienteSelection
                });
                
                // Mantener el valor seleccionado si existe
                if (clienteId && currentCliente) {
                    // Asegurar que la opción seleccionada permanezca
                    if ($select.find('option[value="' + clienteId + '"]').length === 0) {
                        $select.append(new Option(currentCliente, clienteId, true, true));
                    }
                    $select.trigger('change');
                }
            });
            
            // Inicializar Select2 para locales con búsqueda AJAX
            $('.local-select').each(function() {
                var $select = $(this);
                var localId = $select.data('local-id');
                var currentLocal = $select.data('current-local');
                var clienteId = $select.data('cliente-id');
                
                // Función para inicializar Select2
                function initializeLocalSelect2() {
                    
                    $select.select2({
                        placeholder: 'Buscar local...',
                        allowClear: true,
                        width: '100%',
                        ajax: {
                            url: '/RMIE/app/api/get_locales_edit.php',
                            dataType: 'json',
                            delay: 300,
                            data: function (params) {
                                var currentClienteId = $(this).closest('.local-card').find('.cliente-select').val() || clienteId;

                                return {
                                    q: params.term,
                                    id_cliente: currentClienteId,
                                    page: params.page
                                };
                            }.bind(this),
                            processResults: function (data, params) {

                                return {
                                    results: data.results || [],
                                    pagination: {
                                        more: (params.page * 50) < (data.total_count || 0)
                                    }
                                };
                            },
                            cache: false // Deshabilitamos cache para evitar problemas
                        },
                        minimumInputLength: 0,
                        templateResult: formatLocalResult,
                        templateSelection: formatLocalSelection
                    });
                    
                    // Mantener el valor seleccionado si existe
                    if (localId && currentLocal) {

                        // Asegurar que la opción seleccionada permanezca
                        if ($select.find('option[value="' + localId + '"]').length === 0) {
                            $select.append(new Option(currentLocal, localId, true, true));
                        }
                        $select.val(localId).trigger('change.select2');
                    }
                }
                
                // Verificar si hay un cliente seleccionado
                var $clienteSelect = $select.closest('.local-card').find('.cliente-select');
                var currentClienteId = $clienteSelect.val() || clienteId;
                

                
                if (currentClienteId) {
                    // Si hay cliente, inicializar directamente
                    initializeLocalSelect2();
                } else {
                    // Si no hay cliente, esperar a que se seleccione uno
                    $select.select2({
                        placeholder: 'Primero selecciona un cliente...',
                        allowClear: true,
                        width: '100%'
                    }).prop('disabled', true);
                }
            });
            
            // Actualizar locales cuando cambia el cliente
            $('.cliente-select').on('select2:select', function (e) {
                var data = e.params.data;
                var rutaId = $(this).data('ruta-id');
                
                // Actualizar campo oculto del nombre
                $(this).siblings('.cliente-nombre-hidden').val(data.nombre || data.text);
                
                // Obtener el select de locales correspondiente
                var $localSelect = $(this).closest('.local-card').find('.local-select');
                
                // Destruir Select2 existente si existe
                if ($localSelect.hasClass('select2-hidden-accessible')) {
                    $localSelect.select2('destroy');
                }
                
                // Limpiar opciones
                $localSelect.empty().append('<option value="">Seleccionar local...</option>');
                
                // Reinicializar Select2 con el nuevo cliente
                $localSelect.select2({
                    placeholder: 'Buscar local...',
                    allowClear: true,
                    width: '100%',
                    ajax: {
                        url: '/RMIE/app/api/get_locales_edit.php',
                        dataType: 'json',
                        delay: 300,
                        data: function (params) {
                            return {
                                q: params.term,
                                id_cliente: data.id,
                                page: params.page
                            };
                        },
                        processResults: function (dataResp, params) {
                            return {
                                results: dataResp.results,
                                pagination: {
                                    more: (params.page * 50) < dataResp.total_count
                                }
                            };
                        },
                        cache: false
                    },
                    minimumInputLength: 0,
                    templateResult: formatLocalResult,
                    templateSelection: formatLocalSelection
                }).prop('disabled', false);
            });
            
            // Manejar cuando se deselecciona un cliente
            $('.cliente-select').on('select2:unselect', function (e) {
                var $localSelect = $(this).closest('.local-card').find('.local-select');
                
                // Destruir Select2 existente
                if ($localSelect.hasClass('select2-hidden-accessible')) {
                    $localSelect.select2('destroy');
                }
                
                // Reinicializar como deshabilitado
                $localSelect.empty().append('<option value="">Primero selecciona un cliente...</option>')
                    .select2({
                        placeholder: 'Primero selecciona un cliente...',
                        allowClear: true,
                        width: '100%'
                    }).prop('disabled', true);
                
                // Limpiar campo oculto del nombre
                $(this).siblings('.cliente-nombre-hidden').val('');
            });
            
            // Auto-ocultar alertas después de 5 segundos
            setTimeout(function() {
                $('.rutas-alert').fadeOut();
            }, 5000);
            
            // Manejar selección de locales
            $('.local-select').on('select2:select', function (e) {
                var data = e.params.data;
                var rutaId = $(this).data('ruta-id');
                
                // Actualizar campos ocultos
                $(this).siblings('.local-nombre-hidden').val(data.nombre_local || data.text);
                
                // Auto-llenar dirección si está disponible
                var direccionField = $(this).closest('.local-card').find('textarea[name*="[direccion]"]');
                if (data.direccion && direccionField.val().trim() === '') {
                    direccionField.val(data.direccion);
                }
            });
            
            // Validación del formulario (simplificada para múltiples locales)
            $('form').on('submit', function(e) {

                
                let valid = true;
                let errorMessage = '';
                
                // Validar día de semana y estado
                if (!$('#dia_semana').val()) {
                    errorMessage = 'El día de la semana es obligatorio.';
                    valid = false;
                } else if (!$('#estado').val()) {
                    errorMessage = 'El estado es obligatorio.';
                    valid = false;
                }
                
                // Validar que al menos haya un local (solo advertencia)
                if ($('.local-card').length === 0) {
                    if (confirm('No hay locales asignados. ¿Desea continuar de todas formas?')) {
                        valid = true;
                    } else {
                        valid = false;
                    }
                }
                
                if (!valid && errorMessage) {
                    alert(errorMessage);
                    e.preventDefault();
                    return false;
                } else if (!valid) {
                    e.preventDefault();
                    return false;
                }
                

                
                // Mostrar indicador de carga en el botón
                var $submitBtn = $('#btn-guardar-cambios');
                var $btnText = $('#btn-text');
                $submitBtn.prop('disabled', true);
                $btnText.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                
                return true;
            });
        });
        
        // Función para eliminar local individual
        function eliminarLocal(idRuta) {
            if (confirm('¿Está seguro de eliminar este local de la ruta?')) {
                // Eliminar via AJAX
                $.post('/RMIE/app/controllers/RouteControllerModern.php?accion=delete&id=' + idRuta)
                    .done(function() {
                        // Remover la card del DOM
                        $(`[data-ruta-id="${idRuta}"]`).fadeOut(function() {
                            $(this).remove();
                            
                            // Si no quedan locales, mostrar mensaje
                            if ($('.local-card').length === 0) {
                                $('.locales-container').html('<div class="empty-locales"><p>No hay locales asignados a este día</p></div>');
                            }
                        });
                        
                        alert('Local eliminado exitosamente');
                    })
                    .fail(function() {
                        alert('Error al eliminar el local');
                    });
            }
        }
        
        // Contador para nuevos locales
        var nuevoLocalCounter = 0;
        
        // Función para agregar nuevo local
        $('#btnAgregarLocal').on('click', function() {
            nuevoLocalCounter++;
            var currentDay = $('#dia_semana').val() || '<?php echo $ruta["dia_semana"] ?? ""; ?>';
            var newId = 'nuevo_' + nuevoLocalCounter;
            
            var nuevoLocalHtml = `
                <div class="local-card local-card-new" data-ruta-id="${newId}">
                    <div class="local-header">
                        <span class="local-number">${$('.local-card').length + 1}</span>
                        <h4>Nuevo Cliente/Local</h4>
                        <span class="new-badge">NUEVO</span>
                    </div>
                    
                    <div class="local-details">
                        <div class="form-row">
                            <div class="form-group">
                                <label>
                                    <i class="fas fa-user"></i> Cliente <span class="required">*</span>
                                </label>
                                <select class="form-control cliente-select-new" 
                                        name="nuevos_clientes[${newId}][id_cliente]"
                                        data-ruta-id="${newId}"
                                        required>
                                    <option value="">Seleccionar cliente...</option>
                                </select>
                                <input type="hidden" 
                                       name="nuevos_clientes[${newId}][nombre_cliente]" 
                                       class="cliente-nombre-hidden">
                            </div>

                            <div class="form-group">
                                <label>
                                    <i class="fas fa-store"></i> Local <span class="required">*</span>
                                </label>
                                <select class="form-control local-select-new" 
                                        name="nuevos_clientes[${newId}][id_local]"
                                        data-ruta-id="${newId}"
                                        data-cliente-id=""
                                        required
                                        disabled>
                                    <option value="">Selecciona primero un cliente...</option>
                                </select>
                                <input type="hidden" 
                                       name="nuevos_clientes[${newId}][nombre_local]" 
                                       class="local-nombre-hidden">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>
                                <i class="fas fa-map-marker-alt"></i> Dirección
                            </label>
                            <textarea class="form-control" 
                                      name="nuevos_clientes[${newId}][direccion]"
                                      rows="2"
                                      placeholder="Dirección completa"></textarea>
                        </div>
                        
                        <!-- Campo hidden para indicar que es nuevo -->
                        <input type="hidden" name="nuevos_clientes[${newId}][es_nuevo]" value="1">
                        <input type="hidden" name="nuevos_clientes[${newId}][dia_semana]" value="${currentDay}">
                    </div>
                    
                    <div class="local-actions">
                        <button type="button" class="btn-local-remove" onclick="eliminarLocalNuevo('${newId}')">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </div>
                </div>
            `;
            
            // Remover mensaje de vacío si existe
            $('.empty-locales').remove();
            
            // Agregar el nuevo local
            $('.locales-container').append(nuevoLocalHtml);
            
            // Inicializar Select2 para el nuevo cliente
            initializeNewClienteSelect($(`[data-ruta-id="${newId}"] .cliente-select-new`));
            
            // Scroll suave hacia el nuevo local
            $('html, body').animate({
                scrollTop: $(`[data-ruta-id="${newId}"]`).offset().top - 100
            }, 500);
        });
        
        // Función para eliminar nuevo local
        window.eliminarLocalNuevo = function(localId) {
            if (confirm('¿Está seguro de eliminar este local?')) {
                $(`[data-ruta-id="${localId}"]`).fadeOut(function() {
                    $(this).remove();
                    
                    // Renumerar los locales
                    $('.local-card').each(function(index) {
                        $(this).find('.local-number').text(index + 1);
                    });
                });
            }
        };
        
        // Función para inicializar Select2 en nuevos clientes
        function initializeNewClienteSelect($select) {
            $select.select2({
                placeholder: 'Buscar cliente...',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '/RMIE/app/api/get_clientes.php',
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function(cliente) {
                                return {
                                    id: cliente.id_clientes || cliente.id,
                                    text: cliente.nombre,
                                    nombre: cliente.nombre
                                };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0
            });
            
            // Evento cuando se selecciona un cliente nuevo
            $select.on('select2:select', function(e) {
                var clienteData = e.params.data;
                var $card = $(this).closest('.local-card');
                var $localSelect = $card.find('.local-select-new');
                var $clienteNombreHidden = $card.find('.cliente-nombre-hidden');
                
                // Actualizar nombre hidden
                $clienteNombreHidden.val(clienteData.text);
                
                // Cargar locales del cliente seleccionado
                cargarLocalesNuevoCliente(clienteData.id, $localSelect);
            });
        }
        
        // Función para cargar locales de cliente nuevo
        function cargarLocalesNuevoCliente(clienteId, $localSelect) {
            $localSelect.prop('disabled', true).html('<option value="">Cargando locales...</option>');
            
            $.get('/RMIE/app/api/get_locales_by_cliente.php?cliente_id=' + clienteId)
                .done(function(data) {
                    $localSelect.html('<option value="">Seleccionar local...</option>');
                    
                    if (data && data.length > 0) {
                        data.forEach(function(local) {
                            $localSelect.append(new Option(local.text, local.id));
                        });
                        $localSelect.prop('disabled', false);
                        
                        // Inicializar Select2 para el select de locales
                        $localSelect.select2({
                            placeholder: 'Seleccionar local...',
                            allowClear: true,
                            width: '100%'
                        });
                        
                        // Evento para actualizar dirección y nombre hidden
                        $localSelect.on('select2:select', function(e) {
                            var $card = $(this).closest('.local-card');
                            var $direccion = $card.find('textarea[name*="[direccion]"]');
                            var $localNombreHidden = $card.find('.local-nombre-hidden');
                            
                            // Buscar datos del local seleccionado
                            var localSeleccionado = data.find(l => l.id == e.params.data.id);
                            if (localSeleccionado) {
                                $direccion.val(localSeleccionado.direccion);
                                $localNombreHidden.val(localSeleccionado.nombre_local);
                            }
                        });
                    } else {
                        $localSelect.html('<option value="">Este cliente no tiene locales</option>');
                    }
                })
                .fail(function() {
                    $localSelect.html('<option value="">Error cargando locales</option>');
                    alert('Error al cargar los locales del cliente');
                });
        }

        // Funciones de formato para Select2
        function formatClienteResult(cliente) {
            if (cliente.loading) {
                return cliente.text;
            }
            
            var markup = '<div class="select2-result-cliente">' +
                '<div class="select2-result-cliente__title">' + cliente.nombre + '</div>';
            
            if (cliente.descripcion) {
                markup += '<div class="select2-result-cliente__description">' + cliente.descripcion + '</div>';
            }
            
            if (cliente.telefono) {
                markup += '<div class="select2-result-cliente__meta">Tel: ' + cliente.telefono + '</div>';
            }
            
            markup += '</div>';
            return $(markup);
        }
        
        function formatClienteSelection(cliente) {
            return cliente.nombre || cliente.text;
        }
        
        function formatLocalResult(local) {
            if (local.loading) {
                return local.text;
            }
            
            var markup = '<div class="select2-result-local">' +
                '<div class="select2-result-local__title">' + local.nombre_local + '</div>';
            
            if (local.direccion) {
                markup += '<div class="select2-result-local__address">' + local.direccion + '</div>';
            }
            
            if (local.localidad || local.barrio) {
                markup += '<div class="select2-result-local__meta">';
                if (local.localidad) markup += local.localidad;
                if (local.barrio) markup += (local.localidad ? ' - ' : '') + local.barrio;
                markup += '</div>';
            }
            
            markup += '</div>';
            return $(markup);
        }
        
        function formatLocalSelection(local) {
            return local.nombre_local || local.text;
        }
    </script>
</body>
</html>