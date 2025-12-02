<?php
// Vista moderna para crear rutas - RMIE v3.0
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
    <title>Crear Ruta - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- jQuery y Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <style>
        .day-selector {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 10px 0;
        }
        
        .day-btn {
            flex: 1;
            min-width: 120px;
            padding: 10px 15px;
            border: 2px solid #dee2e6;
            background: #fff;
            color: #495057;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .day-btn:hover {
            border-color: #007bff;
            color: #007bff;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,123,255,0.2);
        }
        
        .day-btn.active {
            background: #007bff;
            border-color: #007bff;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,123,255,0.4);
        }
        
        .day-btn.configured::after {
            content: '✓';
            position: absolute;
            top: -5px;
            right: -5px;
            background: #28a745;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }
        
        .day-config-panel {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            background: #f8f9fa;
        }
        
        .panel-header h5 {
            margin: 0 0 20px 0;
            color: #007bff;
            font-weight: 600;
        }
        
        .combinacion-item {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .combinacion-item:hover {
            border-color: #007bff;
            box-shadow: 0 2px 8px rgba(0,123,255,0.1);
        }
        
        .combinacion-info {
            flex: 1;
        }
        
        .combinacion-info strong {
            color: #007bff;
            font-size: 16px;
        }
        
        .combinacion-info small {
            color: #6c757d;
            display: block;
            margin-top: 4px;
        }
        
        .btn-remove-combinacion {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 12px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .btn-remove-combinacion:hover {
            background: #c82333;
        }
        
        .resumen-day {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #007bff;
        }
        
        .resumen-day h6 {
            color: #007bff;
            margin: 0 0 10px 0;
            font-weight: 600;
        }
        
        .resumen-count {
            background: #007bff;
            color: white;
            border-radius: 20px;
            padding: 2px 8px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="rutas-container">
        <!-- Breadcrumb de Navegación -->
        <div class="rutas-breadcrumb">
            <a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
            <span class="separator">/</span>
            <a href="/RMIE/app/controllers/RouteControllerModern.php?accion=index"><i class="fas fa-route"></i> Rutas</a>
            <span class="separator">/</span>
            <span>Crear Ruta</span>
        </div>

        <!-- Header con Título y Botones de Navegación -->
        <div class="rutas-header">
            <div>
                <h1><i class="fas fa-plus-circle"></i> Crear Nueva Ruta</h1>
                <p>Completa los datos para crear una nueva ruta de entrega</p>
            </div>
            <div class="rutas-nav-buttons">
                <a href="/RMIE/app/views/dashboard.php" class="btn-rutas btn-rutas-info">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="/RMIE/app/controllers/RouteControllerModern.php?accion=index" class="btn-rutas btn-rutas-warning">
                    <i class="fas fa-list"></i> Ver Rutas
                </a>
            </div>
        </div>

        <!-- Formulario de Crear Ruta -->
        <div class="rutas-form">
            <form method="POST" action="/RMIE/app/controllers/RouteControllerModern.php?accion=create">
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Información Básica de la Ruta
                    </div>
                    
                    <!-- Selector de día activo -->
                    <div class="form-group">
                        <label><i class="fas fa-calendar-day"></i> Seleccionar Día para Configurar *</label>
                        <div class="day-selector">
                            <button type="button" class="btn btn-outline-primary day-btn active" data-day="Lunes">
                                <i class="fas fa-calendar"></i> Lunes
                            </button>
                            <button type="button" class="btn btn-outline-primary day-btn" data-day="Martes">
                                <i class="fas fa-calendar"></i> Martes
                            </button>
                            <button type="button" class="btn btn-outline-primary day-btn" data-day="Miercoles">
                                <i class="fas fa-calendar"></i> Miércoles
                            </button>
                            <button type="button" class="btn btn-outline-primary day-btn" data-day="Jueves">
                                <i class="fas fa-calendar"></i> Jueves
                            </button>
                            <button type="button" class="btn btn-outline-primary day-btn" data-day="Viernes">
                                <i class="fas fa-calendar"></i> Viernes
                            </button>
                            <button type="button" class="btn btn-outline-primary day-btn" data-day="Sabado">
                                <i class="fas fa-calendar"></i> Sábado
                            </button>
                            <button type="button" class="btn btn-outline-primary day-btn" data-day="Domingo">
                                <i class="fas fa-calendar"></i> Domingo
                            </button>
                        </div>
                        <small class="form-text text-muted">Haz clic en cada día para configurar sus clientes y locales específicos</small>
                    </div>

                    <!-- Panel para el día activo -->
                    <div class="day-config-panel">
                        <div class="panel-header">
                            <h5><i class="fas fa-cog"></i> Configurando: <span id="current-day">Lunes</span></h5>
                        </div>
                        
                        <!-- Nueva interfaz para agregar combinaciones cliente-local -->
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="cliente_select"><i class="fas fa-user"></i> Seleccionar Cliente *</label>
                                    <select id="cliente_select" class="form-control">
                                        <option value="">-- Seleccionar Cliente --</option>
                                    </select>
                                    <div id="cliente_loading" class="mt-2 text-center" style="display:none;">
                                        <i class="fas fa-spinner fa-spin"></i> Cargando clientes...
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="local_select"><i class="fas fa-store"></i> Seleccionar Local *</label>
                                    <select id="local_select" class="form-control" disabled>
                                        <option value="">-- Primero selecciona un cliente --</option>
                                    </select>
                                    <div id="local_loading" class="mt-2 text-center" style="display:none;">
                                        <i class="fas fa-spinner fa-spin"></i> Cargando locales...
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" id="btn_agregar_combinacion" class="btn btn-success btn-block" disabled>
                                        <i class="fas fa-plus"></i> Agregar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de combinaciones agregadas para el día actual -->
                        <div class="form-group">
                            <label><i class="fas fa-list"></i> Combinaciones para <span class="day-label">Lunes</span></label>
                            <div id="combinaciones_day" class="border rounded p-3" style="min-height: 100px; background-color: #f8f9fa;">
                                <p class="text-muted text-center m-0">No hay combinaciones para este día. Selecciona un cliente y un local, luego presiona "Agregar".</p>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen de todos los días -->
                    <div class="form-group mt-4">
                        <label><i class="fas fa-calendar-week"></i> Resumen de Configuración</label>
                        <div id="resumen_dias" class="border rounded p-3" style="background-color: #e3f2fd;">
                            <div id="resumen_content">
                                <p class="text-muted text-center m-0">Configura al menos un día para ver el resumen</p>
                            </div>
                        </div>
                    </div>

                    <!-- Campos hidden para enviar las combinaciones por día -->
                    <input type="hidden" name="dias_data" id="dias_data" value="{}">
                </div>

                <!-- Botones de Acción -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="/RMIE/app/controllers/RouteControllerModern.php?accion=index" 
                       class="btn-rutas btn-rutas-warning">
                        <i class="fas fa-arrow-left"></i> Volver al Listado
                    </a>
                    
                    <div class="d-flex gap-2">
                        <button type="reset" class="btn-rutas btn-rutas-danger">
                            <i class="fas fa-times"></i> Limpiar Formulario
                        </button>
                        <button type="submit" class="btn-rutas btn-rutas-success" id="btn-crear-rutas">
                            <i class="fas fa-save"></i> <span id="btn-text">Crear Rutas</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Mostrar mensajes de error/éxito -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Éxito:</strong> <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <script>
        // Variables globales para el sistema por días
        let clientes = [];
        let currentDay = 'Lunes';
        let diasData = {}; // Objeto para almacenar combinaciones por día

        // Inicializar días vacíos
        ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'].forEach(dia => {
            diasData[dia] = [];
        });

        // Cargar clientes al iniciar
        function cargarClientes() {
            document.getElementById('cliente_loading').style.display = 'block';
            
            fetch('/RMIE/app/api/get_clientes.php')
                .then(response => response.json())
                .then(data => {
                    clientes = data;
                    const select = document.getElementById('cliente_select');
                    select.innerHTML = '<option value="">-- Seleccionar Cliente --</option>';
                    
                    data.forEach(cliente => {
                        const option = document.createElement('option');
                        option.value = cliente.id_clientes || cliente.id;
                        option.textContent = cliente.nombre;
                        select.appendChild(option);
                    });
                    
                    document.getElementById('cliente_loading').style.display = 'none';
                    
                    // Inicializar Select2 para cliente
                    $('#cliente_select').select2({
                        placeholder: '-- Seleccionar Cliente --',
                        allowClear: true,
                        width: '100%'
                    });
                })
                .catch(error => {
                    document.getElementById('cliente_loading').style.display = 'none';
                    alert('Error cargando clientes: ' + error.message);
                });
        }

        // Cambiar día activo
        function cambiarDia(dia) {
            currentDay = dia;
            
            // Actualizar botones
            document.querySelectorAll('.day-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.day === dia) {
                    btn.classList.add('active');
                }
            });
            
            // Actualizar labels
            document.getElementById('current-day').textContent = dia;
            document.querySelector('.day-label').textContent = dia;
            
            // Actualizar lista de combinaciones del día
            actualizarCombinacionesDia();
            
            // Limpiar selecciones actuales
            $('#cliente_select').val('').trigger('change');
            $('#local_select').val('').trigger('change');
            document.getElementById('local_select').disabled = true;
            document.getElementById('btn_agregar_combinacion').disabled = true;
        }

        // Cargar locales por cliente
        function cargarLocalesPorCliente(clienteId) {
            const localSelect = document.getElementById('local_select');
            document.getElementById('local_loading').style.display = 'block';
            
            if (!clienteId) {
                localSelect.innerHTML = '<option value="">-- Primero selecciona un cliente --</option>';
                localSelect.disabled = true;
                document.getElementById('local_loading').style.display = 'none';
                if ($('#local_select').hasClass('select2-hidden-accessible')) {
                    $('#local_select').select2('destroy');
                }
                return;
            }
            
            fetch(`/RMIE/app/api/get_locales_by_cliente.php?cliente_id=${clienteId}`)
                .then(response => response.json())
                .then(data => {
                    localSelect.innerHTML = '<option value="">-- Seleccionar Local --</option>';
                    
                    if (data.length === 0) {
                        localSelect.innerHTML = '<option value="" style="color: #dc3545; font-weight: bold;">⚠️ Este cliente no tiene locales asignados</option>';
                        localSelect.disabled = true;
                    } else {
                        data.forEach(local => {
                            const option = document.createElement('option');
                            option.value = local.id;
                            option.textContent = local.text;
                            option.dataset.nombre = local.nombre_local;
                            option.dataset.direccion = local.direccion;
                            option.dataset.telefono = local.telefono;
                            localSelect.appendChild(option);
                        });
                        localSelect.disabled = false;
                    }
                    
                    document.getElementById('local_loading').style.display = 'none';
                    
                    // Re-inicializar Select2 para local
                    if ($('#local_select').hasClass('select2-hidden-accessible')) {
                        $('#local_select').select2('destroy');
                    }
                    $('#local_select').select2({
                        placeholder: data.length === 0 ? 'Sin locales disponibles' : '-- Seleccionar Local --',
                        allowClear: true,
                        width: '100%'
                    });
                })
                .catch(error => {
                    document.getElementById('local_loading').style.display = 'none';
                    alert('Error cargando locales: ' + error.message);
                });
        }

        // Agregar combinación al día actual
        function agregarCombinacion() {
            const clienteSelect = document.getElementById('cliente_select');
            const localSelect = document.getElementById('local_select');
            
            const clienteId = clienteSelect.value;
            const localId = localSelect.value;
            
            if (!clienteId || !localId) {
                alert('Por favor selecciona un cliente y un local');
                return;
            }
            
            const clienteNombre = clienteSelect.options[clienteSelect.selectedIndex].text;
            const localOption = localSelect.options[localSelect.selectedIndex];
            const localNombre = localOption.dataset.nombre || localOption.text;
            const localDireccion = localOption.dataset.direccion || '';
            const localTelefono = localOption.dataset.telefono || '';
            
            // Verificar si la combinación ya existe en el día actual
            const existe = diasData[currentDay].some(c => c.clienteId == clienteId && c.localId == localId);
            if (existe) {
                alert(`Esta combinación ya fue agregada para el día ${currentDay}`);
                return;
            }
            
            // Agregar la combinación al día actual
            const combinacion = {
                clienteId: clienteId,
                localId: localId,
                clienteNombre: clienteNombre,
                localNombre: localNombre,
                localDireccion: localDireccion,
                localTelefono: localTelefono
            };
            
            diasData[currentDay].push(combinacion);
            actualizarCombinacionesDia();
            actualizarBotonesDia();
            actualizarResumen();
            
            // Limpiar selecciones
            $('#cliente_select').val('').trigger('change');
            $('#local_select').val('').trigger('change');
            document.getElementById('local_select').disabled = true;
            document.getElementById('btn_agregar_combinacion').disabled = true;
        }

        // Actualizar combinaciones del día actual
        function actualizarCombinacionesDia() {
            const container = document.getElementById('combinaciones_day');
            const combinaciones = diasData[currentDay];
            
            if (combinaciones.length === 0) {
                container.innerHTML = '<p class="text-muted text-center m-0">No hay combinaciones para este día. Selecciona un cliente y un local, luego presiona "Agregar".</p>';
            } else {
                let html = '';
                combinaciones.forEach((combo, index) => {
                    html += `
                        <div class="combinacion-item">
                            <div class="combinacion-info">
                                <strong><i class="fas fa-user"></i> ${combo.clienteNombre}</strong> → 
                                <strong><i class="fas fa-store"></i> ${combo.localNombre}</strong>
                                <small><i class="fas fa-map-marker-alt"></i> ${combo.localDireccion}</small>
                            </div>
                            <button type="button" class="btn-remove-combinacion" onclick="eliminarCombinacionDia(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                });
                container.innerHTML = html;
            }
        }

        // Eliminar combinación del día actual
        function eliminarCombinacionDia(index) {
            diasData[currentDay].splice(index, 1);
            actualizarCombinacionesDia();
            actualizarBotonesDia();
            actualizarResumen();
        }

        // Actualizar indicadores visuales de los botones de día
        function actualizarBotonesDia() {
            document.querySelectorAll('.day-btn').forEach(btn => {
                const dia = btn.dataset.day;
                const tieneCombinaciones = diasData[dia].length > 0;
                
                if (tieneCombinaciones) {
                    btn.classList.add('configured');
                } else {
                    btn.classList.remove('configured');
                }
            });
        }

        // Actualizar resumen de todos los días
        function actualizarResumen() {
            const container = document.getElementById('resumen_content');
            let html = '';
            let totalDias = 0;
            let totalCombinaciones = 0;
            
            Object.keys(diasData).forEach(dia => {
                const combinaciones = diasData[dia];
                if (combinaciones.length > 0) {
                    totalDias++;
                    totalCombinaciones += combinaciones.length;
                    
                    html += `
                        <div class="resumen-day">
                            <h6><i class="fas fa-calendar-day"></i> ${dia} <span class="resumen-count">${combinaciones.length}</span></h6>
                            <div class="row">
                    `;
                    
                    combinaciones.forEach(combo => {
                        html += `
                            <div class="col-md-6">
                                <small><i class="fas fa-user"></i> ${combo.clienteNombre} → <i class="fas fa-store"></i> ${combo.localNombre}</small>
                            </div>
                        `;
                    });
                    
                    html += '</div></div>';
                }
            });
            
            if (totalDias === 0) {
                container.innerHTML = '<p class="text-muted text-center m-0">Configura al menos un día para ver el resumen</p>';
            } else {
                const resumenHeader = `
                    <div class="alert alert-info mb-3">
                        <strong><i class="fas fa-info-circle"></i> Resumen:</strong> 
                        ${totalDias} día(s) configurado(s) con ${totalCombinaciones} ruta(s) total.
                    </div>
                `;
                container.innerHTML = resumenHeader + html;
            }
            
            // Actualizar campo hidden
            document.getElementById('dias_data').value = JSON.stringify(diasData);
        }

        // Actualizar la lista visual de combinaciones
        function actualizarListaCombinaciones() {
            const container = document.getElementById('combinaciones_agregadas');
            
            if (combinaciones.length === 0) {
                container.innerHTML = '<p class="text-muted text-center m-0">No hay combinaciones agregadas aún. Selecciona un cliente y un local, luego presiona "Agregar".</p>';
            } else {
                let html = '';
                combinaciones.forEach((combo, index) => {
                    html += `
                        <div class="card mb-2" style="border-left: 4px solid #007bff;">
                            <div class="card-body p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-user text-success"></i> ${combo.clienteNombre}</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-store text-primary"></i> ${combo.localNombre}</strong><br>
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt"></i> ${combo.localDireccion}
                                            ${combo.localTelefono ? `<br><i class="fas fa-phone"></i> ${combo.localTelefono}` : ''}
                                        </small>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <button type="button" class="btn btn-sm btn-danger" onclick="eliminarCombinacion(${index})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            }
            
            // Actualizar campo hidden
            document.getElementById('combinaciones_data').value = JSON.stringify(combinaciones);
        }

        // Eliminar combinación
        function eliminarCombinacion(index) {
            combinaciones.splice(index, 1);
            actualizarListaCombinaciones();
        }

        // Verificar si se puede agregar la combinación
        function verificarPuedeAgregar() {
            const clienteId = document.getElementById('cliente_select').value;
            const localId = document.getElementById('local_select').value;
            const btnAgregar = document.getElementById('btn_agregar_combinacion');
            
            btnAgregar.disabled = !clienteId || !localId;
        }

        // Verificar si se puede agregar la combinación
        function verificarPuedeAgregar() {
            const clienteId = document.getElementById('cliente_select').value;
            const localId = document.getElementById('local_select').value;
            const btnAgregar = document.getElementById('btn_agregar_combinacion');
            
            btnAgregar.disabled = !clienteId || !localId;
        }

        // Document ready
        $(document).ready(function() {
            // Cargar clientes al iniciar
            cargarClientes();
            
            // Configurar eventos de botones de días
            document.querySelectorAll('.day-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const dia = this.dataset.day;
                    cambiarDia(dia);
                });
            });
            
            // Configurar eventos después de un pequeño delay para asegurar que Select2 esté listo
            setTimeout(function() {
                // Eventos
                $('#cliente_select').on('change', function() {
                    const clienteId = $(this).val();
                    cargarLocalesPorCliente(clienteId);
                    verificarPuedeAgregar();
                });
                
                $('#local_select').on('change', function() {
                    verificarPuedeAgregar();
                });
                
                $('#btn_agregar_combinacion').on('click', function() {
                    agregarCombinacion();
                });
            }, 500);
            
            // Validar formulario antes de enviar
            $('form').on('submit', function(e) {
                let hayDatosConfigured = false;
                let totalRutas = 0;
                
                Object.keys(diasData).forEach(dia => {
                    if (diasData[dia].length > 0) {
                        hayDatosConfigured = true;
                        totalRutas += diasData[dia].length;
                    }
                });
                
                if (!hayDatosConfigured) {
                    e.preventDefault();
                    alert('Por favor configura al menos un día con combinaciones cliente-local.');
                    return false;
                }
                
                // Confirmar creación
                if (!confirm(`¿Crear ${totalRutas} rutas en total?`)) {
                    e.preventDefault();
                    return false;
                }
                
                return true;
            });
        });
    </script>
</body>
</html>