<?php
// La vista asume que la sesión ya fue verificada por el controlador
// Obtener mensajes de sesión
$error_message = $_SESSION['error'] ?? '';
$success_message = $_SESSION['success'] ?? '';

// Limpiar mensajes de sesión
unset($_SESSION['error'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Subcategoría - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #9c27b0 0%, #ba68c8 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .form-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px 30px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .header-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .header-title i {
            font-size: 2.8rem;
            opacity: 0.9;
        }
        
        .header-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            font-weight: 400;
        }
        
        .form-section {
            padding: 40px 30px;
        }
        
        .section-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .section-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.1);
        }
        
        .section-title {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .section-title i {
            font-size: 1.5rem;
            opacity: 0.9;
        }
        
        .form-row {
            display: grid;
            gap: 25px;
            margin-bottom: 25px;
        }
        
        .form-row-2 {
            grid-template-columns: 1fr 1fr;
        }
        
        @media (max-width: 768px) {
            .form-row-2 {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
        
        .form-floating-modern {
            position: relative;
            margin-bottom: 20px;
        }
        
        .form-control-modern {
            width: 100%;
            padding: 18px 15px 8px 15px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .form-control-modern:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .form-select-modern {
            width: 100%;
            padding: 18px 15px 8px 15px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
        }
        
        .form-select-modern:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .form-floating-modern label {
            position: absolute;
            top: 12px;
            left: 15px;
            color: rgba(156, 39, 176, 0.8);
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-floating-modern label i {
            font-size: 16px;
        }
        
        .required {
            color: #ff6b6b;
            font-weight: bold;
        }
        
        .form-control-modern:focus ~ label,
        .form-control-modern:not(:placeholder-shown) ~ label,
        .form-select-modern:focus ~ label,
        .form-select-modern:not([value=""]) ~ label {
            top: 2px;
            font-size: 12px;
            color: #9c27b0;
        }
        
        .preview-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }
        
        .subcategoria-avatar-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #9c27b0 0%, #ba68c8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 2.5rem;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(156, 39, 176, 0.3);
            transition: all 0.3s ease;
        }
        
        .subcategoria-avatar-large:hover {
            transform: scale(1.05);
        }
        
        .preview-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.9);
        }
        
        .preview-item:last-child {
            border-bottom: none;
        }
        
        .preview-label {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .preview-value {
            font-weight: 500;
            color: white;
            max-width: 60%;
            text-align: right;
            word-break: break-word;
        }
        
        .info-panel {
            background: rgba(156, 39, 176, 0.2);
            border: 1px solid rgba(156, 39, 176, 0.4);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .info-panel h6 {
            color: #9c27b0;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-panel ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .info-panel li {
            margin-bottom: 5px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .buttons-section {
            padding: 20px 30px 40px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-modern {
            padding: 15px 35px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            min-width: 180px;
            justify-content: center;
        }
        
        .btn-update {
            background: linear-gradient(135deg, #9c27b0 0%, #ba68c8 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-update:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(156, 39, 176, 0.4);
            background: linear-gradient(135deg, #7b1fa2 0%, #9c27b0 100%);
            color: white;
        }
        
        .btn-cancel {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-cancel:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 255, 255, 0.2);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.2) 100%);
            color: white;
        }
        
        .alert-modern {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.4);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success-modern {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.4);
            color: white;
        }
        
        .alert-warning-modern {
            background: rgba(255, 193, 7, 0.2);
            border: 1px solid rgba(255, 193, 7, 0.4);
            color: white;
        }
        
        .character-count {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            text-align: right;
            margin-top: 5px;
        }
        
        .form-help {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .form-help i {
            color: #9c27b0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                padding: 0 10px;
            }
            
            .form-container {
                border-radius: 20px;
            }
            
            .header-section {
                padding: 30px 20px;
            }
            
            .header-title {
                font-size: 2rem;
                flex-direction: column;
                gap: 10px;
            }
            
            .form-section {
                padding: 30px 20px;
            }
            
            .section-card {
                padding: 20px;
                margin-bottom: 20px;
            }
            
            .buttons-section {
                padding: 20px 20px 30px;
                flex-direction: column;
            }
            
            .btn-modern {
                width: 100%;
            }
        }
        
        /* Animaciones */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="form-container">
            <!-- Header -->
            <div class="header-section">
                <h1 class="header-title">
                    <i class="fas fa-edit"></i>
                    Editar Subcategoría #<?php echo isset($subcategoria) ? htmlspecialchars($subcategoria->id_subcategoria) : ''; ?>
                </h1>
                <p class="header-subtitle">Modifique la información de la subcategoría seleccionada</p>
            </div>

            <!-- Alerts -->
            <?php if ($success_message): ?>
                <div class="form-section">
                    <div class="alert-success-modern">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>¡Éxito!</strong> <?php echo $success_message; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="form-section">
                    <div class="alert-modern">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Error:</strong> <?php echo $error_message; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($subcategoria)): ?>
                <div class="form-section">
                    <div class="alert-warning-modern">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Editando:</strong> <?php echo htmlspecialchars($subcategoria->nombre); ?>
                            <small style="display: block; margin-top: 5px; opacity: 0.8;">
                                Creado el: <?php 
                                if (isset($subcategoria->fecha_creacion) && !empty($subcategoria->fecha_creacion)) {
                                    echo date('d/m/Y H:i', strtotime($subcategoria->fecha_creacion));
                                } else {
                                    echo 'No disponible';
                                }
                                ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <div class="form-section">
                <form method="POST" action="" id="subcategoriaForm">
                    
                    <div class="form-row form-row-2">
                        <!-- Información Básica -->
                        <div class="section-card">
                            <div class="section-title">
                                <i class="fas fa-tag"></i>
                                Información de la Subcategoría
                            </div>
                            
                            <div class="form-floating-modern">
                                <input type="text" 
                                       class="form-control-modern" 
                                       id="nombre" 
                                       name="nombre" 
                                       placeholder=" "
                                       maxlength="45"
                                       required
                                       value="<?php echo htmlspecialchars($subcategoria->nombre ?? ''); ?>">
                                <label for="nombre">
                                    <i class="fas fa-tag"></i>
                                    Nombre de la Subcategoría <span class="required">*</span>
                                </label>
                                <div class="character-count">
                                    <span id="nombre-count">0</span>/45
                                </div>
                                <div class="form-help">
                                    <i class="fas fa-info-circle"></i>
                                    Nombre único y descriptivo de la subcategoría
                                </div>
                            </div>

                            <div class="form-floating-modern">
                                <select class="form-select-modern" 
                                        id="id_categoria" 
                                        name="id_categoria"
                                        required>
                                    <option value="">Seleccione una categoría</option>
                                    <?php if (isset($categorias) && is_array($categorias)): ?>
                                        <?php foreach ($categorias as $cat): ?>
                                            <option value="<?php echo htmlspecialchars($cat->id_categoria); ?>" 
                                                    <?php echo (isset($subcategoria) && $subcategoria->id_categoria == $cat->id_categoria) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($cat->nombre); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>No hay categorías disponibles</option>
                                    <?php endif; ?>
                                </select>
                                <label for="id_categoria">
                                    <i class="fas fa-folder"></i>
                                    Categoría Principal <span class="required">*</span>
                                </label>
                                <div class="form-help">
                                    <i class="fas fa-sitemap"></i>
                                    Categoría a la que pertenecerá esta subcategoría
                                </div>
                            </div>

                            <div class="form-floating-modern">
                                <input type="text" 
                                       class="form-control-modern" 
                                       id="descripcion" 
                                       name="descripcion" 
                                       placeholder=" "
                                       maxlength="45"
                                       required
                                       value="<?php echo htmlspecialchars($subcategoria->descripcion ?? ''); ?>">
                                <label for="descripcion">
                                    <i class="fas fa-align-left"></i>
                                    Descripción <span class="required">*</span>
                                </label>
                                <div class="character-count">
                                    <span id="descripcion-count">0</span>/45
                                </div>
                                <div class="form-help">
                                    <i class="fas fa-text-height"></i>
                                    Descripción breve de la subcategoría
                                </div>
                            </div>
                        </div>

                        <!-- Vista Previa y Comparación -->
                        <div class="section-card">
                            <div class="section-title">
                                <i class="fas fa-eye"></i>
                                Comparación de Cambios
                            </div>

                            <div style="margin-bottom: 20px;">
                                <h6 style="color: rgba(255, 255, 255, 0.8); text-align: center; margin-bottom: 15px;">
                                    <i class="fas fa-sync-alt"></i> Los cambios aparecerán aquí
                                </h6>
                            </div>
                            
                            <!-- Vista Previa -->
                            <div class="preview-section">
                                <div style="margin-bottom: 20px;">
                                    <div class="subcategoria-avatar-large" id="previewAvatar">
                                        <?php echo isset($subcategoria->nombre) ? strtoupper(substr($subcategoria->nombre, 0, 1)) : 'S'; ?>
                                    </div>
                                    <h6 style="color: rgba(255, 255, 255, 0.9); margin: 0;">
                                        Vista Previa de Cambios
                                    </h6>
                                </div>
                                
                                <div class="preview-item">
                                    <span class="preview-label">
                                        <i class="fas fa-tag"></i>
                                        Nombre:
                                    </span>
                                    <span class="preview-value" id="preview-nombre"><?php echo htmlspecialchars($subcategoria->nombre ?? '-'); ?></span>
                                </div>
                                
                                <div class="preview-item">
                                    <span class="preview-label">
                                        <i class="fas fa-folder"></i>
                                        Categoría:
                                    </span>
                                    <span class="preview-value" id="preview-categoria">-</span>
                                </div>
                                
                                <div class="preview-item">
                                    <span class="preview-label">
                                        <i class="fas fa-align-left"></i>
                                        Descripción:
                                    </span>
                                    <span class="preview-value" id="preview-descripcion"><?php echo htmlspecialchars($subcategoria->descripcion ?? '-'); ?></span>
                                </div>
                            </div>

                            <div class="info-panel">
                                <h6><i class="fas fa-lightbulb"></i> Consejos para Edición</h6>
                                <ul>
                                    <li>Los campos modificados se resaltarán en dorado</li>
                                    <li>Asegúrese de que el nombre sea único</li>
                                    <li>La descripción debe ser clara y concisa</li>
                                    <li>Verifique que la categoría principal sea correcta</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Buttons -->
            <div class="buttons-section">
                <button type="submit" form="subcategoriaForm" class="btn-modern btn-update">
                    <i class="fas fa-save"></i>
                    GUARDAR CAMBIOS
                </button>
                <a href="/RMIE/app/controllers/SubcategoryController.php?accion=index" class="btn-modern btn-cancel">
                    <i class="fas fa-times"></i>
                    CANCELAR
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Valores originales para comparación
            const originalValues = {
                nombre: '<?php echo addslashes($subcategoria->nombre ?? ''); ?>',
                descripcion: '<?php echo addslashes($subcategoria->descripcion ?? ''); ?>',
                categoria: '<?php echo $subcategoria->id_categoria ?? ''; ?>'
            };

            // Contadores de caracteres
            function setupCharacterCount(inputId, countId, maxLength) {
                const input = document.getElementById(inputId);
                const counter = document.getElementById(countId);
                
                if (input && counter) {
                    function updateCount() {
                        const count = input.value.length;
                        counter.textContent = count;
                        counter.style.color = count > maxLength * 0.8 ? '#ff9800' : 'rgba(255, 255, 255, 0.7)';
                    }
                    
                    input.addEventListener('input', updateCount);
                    updateCount(); // Inicializar
                }
            }
            
            setupCharacterCount('nombre', 'nombre-count', 45);
            setupCharacterCount('descripcion', 'descripcion-count', 45);

            // Vista previa y detección de cambios
            function updatePreview() {
                const nombre = document.getElementById('nombre').value;
                const descripcion = document.getElementById('descripcion').value;
                const categoriaSelect = document.getElementById('id_categoria');
                const categoriaTexto = categoriaSelect.options[categoriaSelect.selectedIndex]?.text || '-';
                
                // Actualizar avatar
                const avatar = document.getElementById('previewAvatar');
                if (nombre) {
                    avatar.textContent = nombre.charAt(0).toUpperCase();
                } else {
                    avatar.textContent = 'S';
                }
                
                // Actualizar campos con detección de cambios
                const nombreElement = document.getElementById('preview-nombre');
                const categoriaElement = document.getElementById('preview-categoria');
                const descripcionElement = document.getElementById('preview-descripcion');
                
                // Comparar y resaltar cambios
                nombreElement.textContent = nombre || '-';
                nombreElement.style.color = nombre !== originalValues.nombre ? '#ffd700' : 'white';
                nombreElement.style.fontWeight = nombre !== originalValues.nombre ? 'bold' : '500';
                
                categoriaElement.textContent = categoriaTexto;
                categoriaElement.style.color = categoriaSelect.value !== originalValues.categoria ? '#ffd700' : 'white';
                categoriaElement.style.fontWeight = categoriaSelect.value !== originalValues.categoria ? 'bold' : '500';
                
                descripcionElement.textContent = descripcion || '-';
                descripcionElement.style.color = descripcion !== originalValues.descripcion ? '#ffd700' : 'white';
                descripcionElement.style.fontWeight = descripcion !== originalValues.descripcion ? 'bold' : '500';
            }

            // Agregar listeners para vista previa
            ['nombre', 'descripcion', 'id_categoria'].forEach(function(fieldId) {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', updatePreview);
                    field.addEventListener('change', updatePreview);
                }
            });

            // Validación del formulario
            document.getElementById('subcategoriaForm').addEventListener('submit', function(e) {
                const nombre = document.getElementById('nombre').value.trim();
                const descripcion = document.getElementById('descripcion').value.trim();
                const categoria = document.getElementById('id_categoria').value;

                const errors = [];

                if (!nombre) {
                    errors.push('El nombre de la subcategoría es obligatorio');
                } else if (nombre.length < 3) {
                    errors.push('El nombre debe tener al menos 3 caracteres');
                }

                if (!descripcion) {
                    errors.push('La descripción es obligatoria');
                } else if (descripcion.length < 3) {
                    errors.push('La descripción debe tener al menos 3 caracteres');
                }

                if (!categoria) {
                    errors.push('Debe seleccionar una categoría principal');
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    alert('Por favor corrige los siguientes errores:\n\n' + errors.join('\n'));
                    return;
                }

                // Verificar si hay cambios
                const hasChanges = nombre !== originalValues.nombre || 
                                 descripcion !== originalValues.descripcion || 
                                 categoria !== originalValues.categoria;

                if (!hasChanges) {
                    e.preventDefault();
                    alert('No se han detectado cambios para guardar.');
                    return;
                }

                // Confirmación
                if (!confirm('¿Está seguro de guardar los cambios realizados?')) {
                    e.preventDefault();
                    return;
                }
            });

            // Efectos visuales
            document.querySelectorAll('.form-control-modern, .form-select-modern').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });

            // Manejo de labels para selects
            document.querySelectorAll('.form-select-modern').forEach(select => {
                select.addEventListener('change', function() {
                    const label = this.parentElement.querySelector('label');
                    if (this.value) {
                        label.style.top = '2px';
                        label.style.fontSize = '12px';
                        label.style.color = '#9c27b0';
                    } else {
                        label.style.top = '12px';
                        label.style.fontSize = '14px';
                        label.style.color = 'rgba(156, 39, 176, 0.8)';
                    }
                });
            });

            // Inicializar vista previa
            updatePreview();
        });
    </script>
</body>
</html>

