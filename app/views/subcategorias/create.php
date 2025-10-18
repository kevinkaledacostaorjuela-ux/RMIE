<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Subcategoría - RMIE</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .main-container {
            max-width: 750px;
            width: 100%;
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
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .header-title i {
            font-size: 2.5rem;
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
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
        
        .form-floating-modern {
            position: relative;
            margin-bottom: 25px;
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
            color: rgba(102, 126, 234, 0.8);
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
        
        .form-control-modern:focus ~ label,
        .form-control-modern:not(:placeholder-shown) ~ label,
        .form-select-modern:focus ~ label,
        .form-select-modern:not([value=""]) ~ label {
            top: 2px;
            font-size: 12px;
            color: #667eea;
        }
        
        .char-counter {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            text-align: right;
            margin-top: 5px;
        }
        
        .buttons-section {
            padding: 20px 30px 40px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-modern {
            padding: 15px 30px;
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
            min-width: 160px;
            justify-content: center;
        }
        
        .btn-create {
            background: linear-gradient(135deg, #00d4ff 0%, #667eea 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-create:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 212, 255, 0.4);
            background: linear-gradient(135deg, #00b8d4 0%, #5a67d8 100%);
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
        
        .info-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            margin-top: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .info-title {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-text {
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
            line-height: 1.5;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                margin: 10px;
                border-radius: 20px;
            }
            
            .header-section {
                padding: 30px 20px;
            }
            
            .header-title {
                font-size: 1.8rem;
            }
            
            .form-section {
                padding: 30px 20px;
            }
            
            .buttons-section {
                padding: 20px 20px 30px;
                flex-direction: column;
            }
            
            .btn-modern {
                width: 100%;
            }
        }
        
        /* Animaciones adicionales */
        .form-floating-modern {
            animation: slideIn 0.5s ease-out;
            animation-fill-mode: both;
        }
        
        .form-floating-modern:nth-child(1) {
            animation-delay: 0.1s;
        }
        
        .form-floating-modern:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .form-row .form-floating-modern:nth-child(1) {
            animation-delay: 0.2s;
        }
        
        .form-row .form-floating-modern:nth-child(2) {
            animation-delay: 0.3s;
        }
        
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
        <!-- Header -->
        <div class="header-section">
            <h1 class="header-title">
                <i class="fas fa-layer-group"></i>
                Crear Nueva Subcategoría
            </h1>
            <p class="header-subtitle">Complete los datos para crear una nueva subcategoría del sistema</p>
        </div>

        <!-- Form -->
        <div class="form-section">
            <form action="/RMIE/app/controllers/SubcategoryController.php?accion=create" method="POST" id="subcategoryForm">
                <div class="form-row">
                    <div class="form-floating-modern">
                        <input type="text" 
                               class="form-control-modern" 
                               id="nombre" 
                               name="nombre" 
                               placeholder=" "
                               maxlength="45"
                               required>
                        <label for="nombre">
                            <i class="fas fa-tags"></i>
                            Nombre de la Subcategoría
                        </label>
                        <div class="char-counter">
                            <span id="nombre-count">0</span>/45 caracteres
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
                                    <option value="<?= htmlspecialchars($cat->id_categoria) ?>">
                                        <?= htmlspecialchars($cat->nombre) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No hay categorías disponibles</option>
                            <?php endif; ?>
                        </select>
                        <label for="id_categoria">
                            <i class="fas fa-folder"></i>
                            Categoría Principal
                        </label>
                    </div>
                </div>

                <div class="form-floating-modern">
                    <input type="text" 
                           class="form-control-modern" 
                           id="descripcion" 
                           name="descripcion" 
                           placeholder=" "
                           maxlength="45"
                           required>
                    <label for="descripcion">
                        <i class="fas fa-align-left"></i>
                        Descripción
                    </label>
                    <div class="char-counter">
                        <span id="descripcion-count">0</span>/45 caracteres
                    </div>
                </div>

                <!-- Info Section -->
                <div class="info-section">
                    <div class="info-title">
                        <i class="fas fa-lightbulb"></i>
                        Consejos para crear subcategorías
                    </div>
                    <div class="info-text">
                        • Asegúrese de que el nombre sea descriptivo y único dentro de su categoría principal<br>
                        • Use nombres cortos pero claros para facilitar la navegación<br>
                        • La descripción debe explicar brevemente el propósito de la subcategoría
                    </div>
                </div>
            </form>
        </div>

        <!-- Buttons -->
        <div class="buttons-section">
            <button type="submit" form="subcategoryForm" class="btn-modern btn-create">
                <i class="fas fa-save"></i>
                CREAR SUBCATEGORÍA
            </button>
            <a href="/RMIE/app/controllers/SubcategoryController.php?accion=index" class="btn-modern btn-cancel">
                <i class="fas fa-times"></i>
                CANCELAR
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Contador de caracteres
        function updateCharCounter(inputId, counterId, maxLength) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(counterId);
            
            input.addEventListener('input', function() {
                const currentLength = this.value.length;
                counter.textContent = currentLength;
                
                if (currentLength > maxLength * 0.8) {
                    counter.style.color = '#ff6b6b';
                } else {
                    counter.style.color = 'rgba(255, 255, 255, 0.7)';
                }
            });
        }

        // Inicializar contadores
        updateCharCounter('nombre', 'nombre-count', 45);
        updateCharCounter('descripcion', 'descripcion-count', 45);

        // Validación del formulario
        document.getElementById('subcategoryForm').addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const descripcion = document.getElementById('descripcion').value.trim();
            const categoria = document.getElementById('id_categoria').value;
            
            if (nombre.length < 3) {
                e.preventDefault();
                alert('El nombre debe tener al menos 3 caracteres');
                return;
            }
            
            if (nombre.length > 45) {
                e.preventDefault();
                alert('El nombre no puede exceder 45 caracteres');
                return;
            }

            if (descripcion.length < 3) {
                e.preventDefault();
                alert('La descripción debe tener al menos 3 caracteres');
                return;
            }

            if (!categoria) {
                e.preventDefault();
                alert('Debe seleccionar una categoría principal');
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

        // Actualizar label del select cuando cambie
        document.getElementById('id_categoria').addEventListener('change', function() {
            const label = this.parentElement.querySelector('label');
            if (this.value) {
                label.style.top = '2px';
                label.style.fontSize = '12px';
                label.style.color = '#667eea';
            } else {
                label.style.top = '12px';
                label.style.fontSize = '14px';
                label.style.color = 'rgba(102, 126, 234, 0.8)';
            }
        });
    </script>
</body>
</html>