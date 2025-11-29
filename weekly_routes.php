<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Rutas Semanales - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        .form-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 30px;
        }
        
        .page-title {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        
        .form-label {
            color: white;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            padding: 12px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.95);
            border-color: #4facfe;
            box-shadow: 0 0 20px rgba(79, 172, 254, 0.3);
        }
        
        .btn-save {
            background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            color: white;
            padding: 15px 40px;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(79, 172, 254, 0.4);
        }
        
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(79, 172, 254, 0.6);
            color: white;
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 12px 30px;
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            transform: translateY(-2px);
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            padding: 20px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.9);
            color: white;
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.9);
            color: white;
        }
        
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
        
        .day-info {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .clients-display {
            color: white;
            font-size: 0.9rem;
            margin-top: 10px;
        }
        
        .client-tag {
            background: rgba(79, 172, 254, 0.8);
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            display: inline-block;
            margin: 2px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <h1 class="page-title">
            <i class="fas fa-route"></i> Gestión de Rutas Semanales
        </h1>
        
        <!-- Alertas -->
        <div id="alertContainer"></div>
        
        <!-- Formulario Principal -->
        <div class="form-container">
            <form id="routeForm">
                <input type="hidden" id="rutaId" name="ruta_id">
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="dia" class="form-label">
                            <i class="fas fa-calendar-day"></i> Día de la Semana
                        </label>
                        <select class="form-select" id="dia" name="dia" required>
                            <option value="">Selecciona un día</option>
                            <option value="Monday">Lunes</option>
                            <option value="Tuesday">Martes</option>
                            <option value="Wednesday">Miércoles</option>
                            <option value="Thursday">Jueves</option>
                            <option value="Friday">Viernes</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <label for="estado" class="form-label">
                            <i class="fas fa-flag"></i> Estado
                        </label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="activa">Activa</option>
                            <option value="inactiva">Inactiva</option>
                            <option value="completada">Completada</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="nombreRuta" class="form-label">
                        <i class="fas fa-map-marked-alt"></i> Nombre de la Ruta
                    </label>
                    <input type="text" class="form-control" id="nombreRuta" name="nombre_ruta" 
                           placeholder="Ej: Ruta Centro - Zona Norte" required>
                </div>
                
                <div class="mb-4">
                    <label for="descripcion" class="form-label">
                        <i class="fas fa-clipboard-list"></i> Descripción
                    </label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                              placeholder="Descripción detallada de la ruta..."></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="clientes" class="form-label">
                        <i class="fas fa-users"></i> IDs de Clientes (separados por coma)
                    </label>
                    <input type="text" class="form-control" id="clientes" name="clientes" 
                           placeholder="Ej: 1, 5, 12, 25, 33">
                    <div class="form-text text-light">
                        Ingrese los IDs de los clientes separados por comas
                    </div>
                </div>
                
                <!-- Información del día seleccionado -->
                <div id="dayInfo" class="day-info" style="display: none;">
                    <h6 class="text-white mb-3">
                        <i class="fas fa-info-circle"></i> Información del día seleccionado
                    </h6>
                    <div id="routeInfo" class="text-white"></div>
                    <div id="clientsDisplay" class="clients-display"></div>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-save me-3" id="submitBtn">
                        <i class="fas fa-save"></i> 
                        <span id="submitText">Guardar Ruta</span>
                        <span id="submitSpinner" class="spinner-border spinner-border-sm ms-2" style="display: none;"></span>
                    </button>
                    
                    <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">
                        <i class="fas fa-broom"></i> Limpiar
                    </button>
                    
                    <a href="/RMIE/rutas.php?accion=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Tabla de rutas existentes -->
        <div class="form-container">
            <h4 class="text-white mb-4">
                <i class="fas fa-list"></i> Rutas Existentes
            </h4>
            <div id="rutasExistentes" class="table-responsive">
                <!-- Se carga dinámicamente -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Variables globales
        let rutasData = [];
        
        // Cargar datos iniciales
        document.addEventListener('DOMContentLoaded', function() {
            cargarRutasExistentes();
            
            // Event listener para cambio de día
            document.getElementById('dia').addEventListener('change', function() {
                const dia = this.value;
                if (dia) {
                    cargarRutaDelDia(dia);
                } else {
                    ocultarInfoDia();
                }
            });
            
            // Event listener para envío del formulario
            document.getElementById('routeForm').addEventListener('submit', function(e) {
                e.preventDefault();
                procesarFormulario();
            });
        });
        
        /**
         * Al seleccionar un día, cargar automáticamente la ruta y clientes desde PHP (JSON)
         */
        async function cargarRutaDelDia(dia) {
            try {
                const response = await fetch(`/RMIE/api/obtener_ruta.php?dia=${dia}`);
                const data = await response.json();
                
                if (data.success && data.ruta) {
                    // Llenar formulario con datos existentes
                    document.getElementById('rutaId').value = data.ruta.id;
                    document.getElementById('nombreRuta').value = data.ruta.nombre_ruta;
                    document.getElementById('descripcion').value = data.ruta.descripcion || '';
                    document.getElementById('estado').value = data.ruta.estado;
                    
                    // Mostrar clientes
                    if (data.ruta.clientes && data.ruta.clientes.length > 0) {
                        document.getElementById('clientes').value = data.ruta.clientes.join(', ');
                    } else {
                        document.getElementById('clientes').value = '';
                    }
                    
                    // Mostrar información del día
                    mostrarInfoDia(data.ruta);
                    
                    // Cambiar texto del botón
                    document.getElementById('submitText').textContent = 'Actualizar Ruta';
                    
                } else {
                    // No existe ruta para este día
                    limpiarCamposRuta();
                    ocultarInfoDia();
                    document.getElementById('submitText').textContent = 'Crear Ruta';
                }
                
            } catch (error) {
                console.error('Error cargando ruta del día:', error);
                mostrarAlerta('Error cargando datos del día seleccionado', 'danger');
            }
        }
        
        /**
         * Enviar el formulario con fetch sin recargar la página
         */
        async function procesarFormulario() {
            const formData = new FormData(document.getElementById('routeForm'));
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');
            
            // Mostrar spinner
            submitBtn.disabled = true;
            submitSpinner.style.display = 'inline-block';
            submitText.textContent = 'Procesando...';
            
            try {
                const response = await fetch('/RMIE/api/procesar_ruta.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    mostrarAlerta(result.message, 'success');
                    cargarRutasExistentes(); // Recargar tabla
                    
                    // Si es creación nueva, limpiar formulario
                    if (!formData.get('ruta_id')) {
                        limpiarFormulario();
                    }
                } else {
                    mostrarAlerta(result.message || 'Error procesando la ruta', 'danger');
                }
                
            } catch (error) {
                console.error('Error procesando formulario:', error);
                mostrarAlerta('Error de conexión. Inténtalo de nuevo.', 'danger');
            } finally {
                // Ocultar spinner
                submitBtn.disabled = false;
                submitSpinner.style.display = 'none';
                submitText.textContent = document.getElementById('rutaId').value ? 'Actualizar Ruta' : 'Crear Ruta';
            }
        }
        
        /**
         * Cargar todas las rutas existentes
         */
        async function cargarRutasExistentes() {
            try {
                const response = await fetch('/RMIE/api/obtener_ruta.php?todas=1');
                const data = await response.json();
                
                if (data.success) {
                    rutasData = data.rutas || [];
                    mostrarTablaRutas(rutasData);
                } else {
                    document.getElementById('rutasExistentes').innerHTML = 
                        '<p class="text-white text-center">No hay rutas registradas</p>';
                }
                
            } catch (error) {
                console.error('Error cargando rutas:', error);
                document.getElementById('rutasExistentes').innerHTML = 
                    '<p class="text-danger text-center">Error cargando rutas</p>';
            }
        }
        
        /**
         * Mostrar tabla de rutas
         */
        function mostrarTablaRutas(rutas) {
            if (!rutas || rutas.length === 0) {
                document.getElementById('rutasExistentes').innerHTML = 
                    '<p class="text-white text-center">No hay rutas registradas</p>';
                return;
            }
            
            const diasEs = {
                'Monday': 'Lunes',
                'Tuesday': 'Martes',
                'Wednesday': 'Miércoles', 
                'Thursday': 'Jueves',
                'Friday': 'Viernes'
            };
            
            let html = `
                <table class="table table-dark table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Día</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Clientes</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            rutas.forEach(ruta => {
                const clientesHtml = ruta.clientes.map(id => 
                    `<span class="client-tag">${id}</span>`
                ).join('');
                
                html += `
                    <tr>
                        <td><strong>${diasEs[ruta.dia] || ruta.dia}</strong></td>
                        <td>${ruta.nombre_ruta}</td>
                        <td>
                            <span class="badge bg-${ruta.estado === 'activa' ? 'success' : ruta.estado === 'inactiva' ? 'secondary' : 'primary'}">
                                ${ruta.estado}
                            </span>
                        </td>
                        <td>${clientesHtml || '<span class="text-muted">Sin clientes</span>'}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-light" onclick="editarRuta('${ruta.dia}')">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            html += '</tbody></table>';
            document.getElementById('rutasExistentes').innerHTML = html;
        }
        
        /**
         * Editar ruta existente
         */
        function editarRuta(dia) {
            document.getElementById('dia').value = dia;
            document.getElementById('dia').dispatchEvent(new Event('change'));
            
            // Scroll al formulario
            document.querySelector('.form-container').scrollIntoView({ 
                behavior: 'smooth' 
            });
        }
        
        /**
         * Mostrar información del día seleccionado
         */
        function mostrarInfoDia(ruta) {
            const dayInfo = document.getElementById('dayInfo');
            const routeInfo = document.getElementById('routeInfo');
            const clientsDisplay = document.getElementById('clientsDisplay');
            
            routeInfo.innerHTML = `
                <strong>Ruta:</strong> ${ruta.nombre_ruta}<br>
                <strong>Estado:</strong> ${ruta.estado}<br>
                <strong>Descripción:</strong> ${ruta.descripcion || 'Sin descripción'}
            `;
            
            if (ruta.clientes && ruta.clientes.length > 0) {
                const clienteTags = ruta.clientes.map(id => 
                    `<span class="client-tag">${id}</span>`
                ).join('');
                clientsDisplay.innerHTML = `<strong>Clientes asignados:</strong><br>${clienteTags}`;
            } else {
                clientsDisplay.innerHTML = '<strong>Clientes:</strong> Ninguno asignado';
            }
            
            dayInfo.style.display = 'block';
        }
        
        /**
         * Ocultar información del día
         */
        function ocultarInfoDia() {
            document.getElementById('dayInfo').style.display = 'none';
        }
        
        /**
         * Limpiar campos de ruta
         */
        function limpiarCamposRuta() {
            document.getElementById('rutaId').value = '';
            document.getElementById('nombreRuta').value = '';
            document.getElementById('descripcion').value = '';
            document.getElementById('clientes').value = '';
            document.getElementById('estado').value = 'activa';
        }
        
        /**
         * Limpiar formulario completo
         */
        function limpiarFormulario() {
            document.getElementById('routeForm').reset();
            document.getElementById('rutaId').value = '';
            ocultarInfoDia();
            document.getElementById('submitText').textContent = 'Crear Ruta';
        }
        
        /**
         * Mostrar alertas
         */
        function mostrarAlerta(mensaje, tipo) {
            const alertContainer = document.getElementById('alertContainer');
            const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
            const icon = tipo === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
            
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <i class="${icon}"></i> ${mensaje}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            alertContainer.innerHTML = alertHtml;
            
            // Auto-ocultar después de 5 segundos
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }
    </script>
</body>
</html>