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

                    <!-- Lista de combinaciones agregadas -->
                    <div class="form-group">
                        <label><i class="fas fa-list"></i> Combinaciones Cliente-Local Agregadas</label>
                        <div id="combinaciones_agregadas" class="border rounded p-3" style="min-height: 100px; background-color: #f8f9fa;">
                            <p class="text-muted text-center m-0">No hay combinaciones agregadas aún. Selecciona un cliente y un local, luego presiona "Agregar".</p>
                        </div>
                    </div>

                    <!-- Días de la semana -->
                    <div class="form-group">
                        <label for="dias_semana"><i class="fas fa-calendar-week"></i> Días de la Semana *</label>
                        <select name="dias_semana[]" id="dias_semana" class="form-control" multiple required>
                            <option value="Lunes">Lunes</option>
                            <option value="Martes">Martes</option>
                            <option value="Miercoles">Miércoles</option>
                            <option value="Jueves">Jueves</option>
                            <option value="Viernes">Viernes</option>
                            <option value="Sabado">Sábado</option>
                            <option value="Domingo">Domingo</option>
                        </select>
                        <small class="form-text text-muted">Selecciona los días para las rutas. Se crearán rutas para cada combinación cliente-local en cada día seleccionado.</small>
                    </div>

                    <!-- Campos hidden para enviar las combinaciones -->
                    <input type="hidden" name="combinaciones_data" id="combinaciones_data" value="[]">
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
        // Variables globales
        let clientes = [];
        let combinaciones = [];

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

        // Cargar locales por cliente
        function cargarLocalesPorCliente(clienteId) {
            const localSelect = document.getElementById('local_select');
            document.getElementById('local_loading').style.display = 'block';
            
            if (!clienteId) {
                localSelect.innerHTML = '<option value="">-- Primero selecciona un cliente --</option>';
                localSelect.disabled = true;
                document.getElementById('local_loading').style.display = 'none';
                // Destruir Select2 si existe
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
                        
                        // Mostrar mensaje informativo
                        const infoDiv = document.createElement('div');
                        infoDiv.className = 'alert alert-warning mt-2';
                        infoDiv.innerHTML = `
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Información:</strong> El cliente seleccionado no tiene locales asignados. 
                            Para asignar locales a este cliente, ve al módulo de gestión de clientes.
                        `;
                        
                        // Remover mensaje previo si existe
                        const existingAlert = localSelect.parentNode.querySelector('.alert');
                        if (existingAlert) {
                            existingAlert.remove();
                        }
                        
                        localSelect.parentNode.appendChild(infoDiv);
                        
                        setTimeout(() => {
                            if (infoDiv.parentNode) {
                                infoDiv.remove();
                            }
                        }, 5000);
                        
                    } else {
                        // Remover mensaje de alerta si existe
                        const existingAlert = localSelect.parentNode.querySelector('.alert');
                        if (existingAlert) {
                            existingAlert.remove();
                        }
                        
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

        // Agregar combinación cliente-local
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
            
            // Verificar si la combinación ya existe
            const existe = combinaciones.some(c => c.clienteId == clienteId && c.localId == localId);
            if (existe) {
                alert('Esta combinación cliente-local ya fue agregada');
                return;
            }
            
            // Agregar la combinación
            const combinacion = {
                clienteId: clienteId,
                localId: localId,
                clienteNombre: clienteNombre,
                localNombre: localNombre,
                localDireccion: localDireccion,
                localTelefono: localTelefono
            };
            
            combinaciones.push(combinacion);
            actualizarListaCombinaciones();
            
            // Limpiar selecciones
            $('#cliente_select').val('').trigger('change');
            $('#local_select').val('').trigger('change');
            document.getElementById('local_select').disabled = true;
            document.getElementById('btn_agregar_combinacion').disabled = true;
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

        // Document ready
        $(document).ready(function() {
            // Cargar clientes al iniciar
            cargarClientes();
            
            // Inicializar Select2 para días
            $('#dias_semana').select2({
                placeholder: 'Seleccionar uno o varios días...',
                allowClear: true,
                width: '100%',
                multiple: true,
                closeOnSelect: false,
                tags: false
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
            }, 500); // Delay de 500ms para asegurar que Select2 esté listo
            
            // Validar formulario antes de enviar
            $('form').on('submit', function(e) {
                const diasSelected = $('#dias_semana').val();
                
                if (combinaciones.length === 0) {
                    e.preventDefault();
                    alert('Por favor agrega al menos una combinación cliente-local.');
                    return false;
                }
                
                if (!diasSelected || diasSelected.length === 0) {
                    e.preventDefault();
                    alert('Por favor selecciona al menos un día de la semana.');
                    return false;
                }
                
                return true;
            });
        });
    </script>
</body>
</html>