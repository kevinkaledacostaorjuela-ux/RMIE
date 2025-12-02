<?php
// Verificar que tengamos los datos de la categoría
if (!isset($categoria)) {
    header('Location: /RMIE/app/controllers/CategoryController.php?accion=index');
    exit();
}

// Asegurar sesión y usuario
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

// Preparar datos seguros
$categoria_id_safe = htmlspecialchars($categoria->id_categoria ?? '', ENT_QUOTES, 'UTF-8');
$categoria_nombre_safe = htmlspecialchars($categoria->nombre ?? '', ENT_QUOTES, 'UTF-8');
$categoria_descripcion_safe = htmlspecialchars($categoria->descripcion ?? '', ENT_QUOTES, 'UTF-8');
$fecha_creacion_raw = $categoria->fecha_creacion ?? null;
$fecha_creacion_formatted = $fecha_creacion_raw ? date('d/m/Y H:i', strtotime($fecha_creacion_raw)) : 'N/D';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .edit-container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .edit-header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }
        
        .edit-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .edit-header .subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
        }
        
        .info-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: white;
        }
        
        .info-item i {
            width: 30px;
            color: #4facfe;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            color: white;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-label i {
            color: #4facfe;
        }
        
        .required {
            color: #ff6b6b;
        }
        
        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            padding: 12px 15px;
            color: #333;
            font-size: 1rem;
        }
        
        .form-control:focus {
            background: white;
            border-color: #4facfe;
            box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .char-counter {
            text-align: right;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        .btn-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn-modern {
            padding: 12px 30px;
            border-radius: 25px;
            border: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-success-modern {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }
        
        .btn-success-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 172, 254, 0.4);
        }
        
        .btn-secondary-modern {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }
        
        .btn-secondary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .alert {
            border-radius: 15px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <div class="edit-header">
            <h1>
                <i class="fas fa-edit"></i>
                Editar Categoría
            </h1>
            <p class="subtitle">Modifica los datos de la categoría</p>
        </div>

        <!-- Información Actual -->
        <div class="info-card">
            <h5 style="color: white; margin-bottom: 15px;">
                <i class="fas fa-info-circle"></i> Información Actual
            </h5>
            <div class="info-item">
                <i class="fas fa-hashtag"></i>
                <strong>ID:</strong>&nbsp;<?= $categoria_id_safe ?>
            </div>
            <div class="info-item">
                <i class="fas fa-tag"></i>
                <strong>Nombre:</strong>&nbsp;<?= $categoria_nombre_safe ?>
            </div>
            <div class="info-item">
                <i class="fas fa-calendar"></i>
                <strong>Creada:</strong>&nbsp;<?= $fecha_creacion_formatted ?>
            </div>
        </div>

        <!-- Formulario de Edición -->
        <form method="POST" action="/RMIE/app/controllers/CategoryController.php?accion=edit&id=<?= $categoria_id_safe ?>" id="editForm">
            
            <!-- Nombre -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-tag"></i>
                    Nombre de la Categoría <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    class="form-control" 
                    name="nombre" 
                    id="nombre"
                    value="<?= $categoria_nombre_safe ?>"
                    required
                    minlength="3"
                    maxlength="45"
                    placeholder="Ingrese el nombre de la categoría">
                <div class="char-counter">
                    <span id="nombreCount">0</span>/45 caracteres
                </div>
            </div>

            <!-- Descripción -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-align-left"></i>
                    Descripción
                </label>
                <textarea 
                    class="form-control" 
                    name="descripcion" 
                    id="descripcion"
                    maxlength="255"
                    placeholder="Ingrese una descripción (opcional)"><?= $categoria_descripcion_safe ?></textarea>
                <div class="char-counter">
                    <span id="descripcionCount">0</span>/255 caracteres
                </div>
            </div>

            <!-- Botones -->
            <div class="btn-actions">
                <button type="submit" class="btn btn-modern btn-success-modern">
                    <i class="fas fa-save"></i>
                    Guardar Cambios
                </button>
                <a href="/RMIE/app/controllers/CategoryController.php?accion=index" class="btn btn-modern btn-secondary-modern">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Contadores de caracteres
        function updateCharCount(inputId, countId) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(countId);
            if (input && counter) {
                counter.textContent = input.value.length;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const nombreInput = document.getElementById('nombre');
            const descripcionInput = document.getElementById('descripcion');

            if (nombreInput) {
                updateCharCount('nombre', 'nombreCount');
                nombreInput.addEventListener('input', () => updateCharCount('nombre', 'nombreCount'));
            }

            if (descripcionInput) {
                updateCharCount('descripcion', 'descripcionCount');
                descripcionInput.addEventListener('input', () => updateCharCount('descripcion', 'descripcionCount'));
            }

            // Validación del formulario
            const form = document.getElementById('editForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const nombre = nombreInput.value.trim();

                    if (nombre.length < 3) {
                        e.preventDefault();
                        alert('El nombre debe tener al menos 3 caracteres');
                        nombreInput.focus();
                        return false;
                    }

                    if (nombre.length > 45) {
                        e.preventDefault();
                        alert('El nombre no puede exceder 45 caracteres');
                        nombreInput.focus();
                        return false;
                    }

                    // Confirmar cambios
                    if (!confirm('\u00bfEst\u00e1 seguro de guardar los cambios en esta categor\u00eda?')) {
                        e.preventDefault();
                        return false;
                    }
                });
            }
        });

        // console.log('✅ Vista de edición de CATEGORÍA cargada correctamente');
        // console.log('ID Categoría:', '<?= $categoria_id_safe ?>');
        // console.log('Nombre:', '<?= $categoria_nombre_safe ?>');
    </script>
</body>
</html>
