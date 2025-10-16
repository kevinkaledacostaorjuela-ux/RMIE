<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Categoría - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        
        .categorias-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }
        
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
        
        .categorias-container h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .categorias-container h1 i {
            font-size: 32px;
        }
        
        form {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 25px;
            animation: slideIn 0.5s ease-out;
            animation-fill-mode: both;
        }
        
        .form-group:nth-child(1) {
            animation-delay: 0.1s;
        }
        
        .form-group:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        label {
            display: block;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        label i {
            color: #667eea;
            font-size: 18px;
            width: 20px;
        }
        
        label::after {
            content: "*";
            color: #e74c3c;
            margin-left: 5px;
            font-weight: bold;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s;
            background: white;
            font-family: inherit;
        }
        
        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }
        
        input[type="text"]:hover {
            border-color: #764ba2;
        }
        
        input[type="text"]::placeholder {
            color: #95a5a6;
        }
        
        .char-counter {
            font-size: 12px;
            color: #95a5a6;
            text-align: right;
            margin-top: 5px;
        }
        
        .form-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 2px solid #e0e0e0;
            animation: slideIn 0.5s ease-out 0.3s both;
        }
        
        .btn {
            padding: 14px 35px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            font-family: inherit;
        }
        
        .btn i {
            font-size: 18px;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }
        
        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(17, 153, 142, 0.4);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #868f96 0%, #596164 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(89, 97, 100, 0.4);
        }
        
        .btn-success:active,
        .btn-primary:active {
            transform: translateY(-1px);
        }
        
        /* Validación visual */
        input.error {
            border-color: #e74c3c;
            animation: shake 0.5s;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 8px;
            display: none;
            align-items: center;
            gap: 5px;
        }
        
        .error-message.show {
            display: flex;
        }
        
        .success-message {
            color: #27ae60;
            font-size: 13px;
            margin-top: 8px;
            display: none;
            align-items: center;
            gap: 5px;
        }
        
        .success-message.show {
            display: flex;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .categorias-container {
                margin: 10px;
            }
            
            .categorias-container h1 {
                font-size: 24px;
                padding: 20px;
            }
            
            form {
                padding: 25px;
            }
            
            .form-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
        
        /* Efecto de carga */
        .btn-success.loading {
            pointer-events: none;
            opacity: 0.7;
            position: relative;
        }
        
        .btn-success.loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid white;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.6s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="categorias-container">
        <h1>
            <i class="fas fa-plus-circle"></i>
            Nueva Categoría
        </h1>
        
        <form method="POST" action="/RMIE/app/controllers/CategoryController.php?accion=create" id="categoryForm">
            <div class="form-group">
                <label for="nombre">
                    <i class="fas fa-tag"></i>
                    Nombre de la Categoría
                </label>
                <input 
                    type="text" 
                    id="nombre" 
                    name="nombre" 
                    required 
                    maxlength="45"
                    placeholder="Ej: Electrónica, Ropa, Alimentos..."
                    autocomplete="off">
                <div class="char-counter">
                    <span id="nombreCount">0</span> / 45 caracteres
                </div>
                <div class="error-message" id="nombreError">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>El nombre debe tener entre 3 y 45 caracteres</span>
                </div>
                <div class="success-message" id="nombreSuccess">
                    <i class="fas fa-check-circle"></i>
                    <span>Nombre válido</span>
                </div>
            </div>
            
            <div class="form-group">
                <label for="descripcion">
                    <i class="fas fa-align-left"></i>
                    Descripción
                </label>
                <input 
                    type="text" 
                    id="descripcion" 
                    name="descripcion" 
                    required 
                    maxlength="45"
                    placeholder="Breve descripción de la categoría..."
                    autocomplete="off">
                <div class="char-counter">
                    <span id="descripcionCount">0</span> / 45 caracteres
                </div>
                <div class="error-message" id="descripcionError">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>La descripción debe tener entre 3 y 45 caracteres</span>
                </div>
                <div class="success-message" id="descripcionSuccess">
                    <i class="fas fa-check-circle"></i>
                    <span>Descripción válida</span>
                </div>
            </div>
            
            <div class="form-buttons">
                <button type="submit" class="btn btn-success" id="submitBtn">
                    <i class="fas fa-save"></i>
                    Guardar Categoría
                </button>
                <a href="/RMIE/app/controllers/CategoryController.php?accion=index" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i>
                    Volver al Listado
                </a>
            </div>
        </form>
    </div>
    
    <script>
        // Elementos del formulario
        const form = document.getElementById('categoryForm');
        const nombreInput = document.getElementById('nombre');
        const descripcionInput = document.getElementById('descripcion');
        const submitBtn = document.getElementById('submitBtn');
        
        // Función para validar un campo
        function validateField(input, minLength = 3, maxLength = 45) {
            const value = input.value.trim();
            const fieldName = input.id;
            const errorDiv = document.getElementById(fieldName + 'Error');
            const successDiv = document.getElementById(fieldName + 'Success');
            const counterSpan = document.getElementById(fieldName + 'Count');
            
            // Actualizar contador
            if (counterSpan) {
                counterSpan.textContent = value.length;
            }
            
            // Validar longitud
            if (value.length === 0) {
                input.classList.remove('error');
                errorDiv.classList.remove('show');
                successDiv.classList.remove('show');
                return false;
            } else if (value.length < minLength || value.length > maxLength) {
                input.classList.add('error');
                errorDiv.classList.add('show');
                successDiv.classList.remove('show');
                return false;
            } else {
                input.classList.remove('error');
                errorDiv.classList.remove('show');
                successDiv.classList.add('show');
                return true;
            }
        }
        
        // Validación en tiempo real
        nombreInput.addEventListener('input', function() {
            validateField(this, 3, 45);
        });
        
        descripcionInput.addEventListener('input', function() {
            validateField(this, 3, 45);
        });
        
        // Validación al enviar el formulario
        form.addEventListener('submit', function(e) {
            const nombreValid = validateField(nombreInput, 3, 45);
            const descripcionValid = validateField(descripcionInput, 3, 45);
            
            if (!nombreValid || !descripcionValid) {
                e.preventDefault();
                
                // Agregar animación de error
                if (!nombreValid) nombreInput.classList.add('error');
                if (!descripcionValid) descripcionInput.classList.add('error');
                
                return;
            }
            
            // Mostrar estado de carga
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        });
        
        // Limpiar animación de error después de corregir
        nombreInput.addEventListener('focus', function() {
            this.classList.remove('error');
        });
        
        descripcionInput.addEventListener('focus', function() {
            this.classList.remove('error');
        });
        
        // Auto-focus en el primer campo
        nombreInput.focus();
    </script>
</body>
</html>
