<?php
/**
 * Script de prueba para verificar enlaces en la página de categorías
 * Acceder a: http://localhost/RMIE/test_enlaces.php
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Enlaces - Categorías</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 900px;
            margin: 0 auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        .test-link {
            display: block;
            padding: 15px;
            margin: 10px 0;
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            border-radius: 5px;
        }
        .test-link:hover {
            background: #e9ecef;
        }
        .correct {
            border-left-color: #28a745;
            background: #d4edda;
        }
        .incorrect {
            border-left-color: #dc3545;
            background: #f8d7da;
        }
        .label {
            font-weight: bold;
            color: #495057;
            display: block;
            margin-bottom: 5px;
        }
        .url {
            font-family: monospace;
            color: #0066cc;
            word-break: break-all;
        }
        .button-test {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 5px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-weight: bold;
        }
        .btn-categoria {
            background: linear-gradient(45deg, #667eea, #764ba2);
        }
        .btn-local {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
        }
        #result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            display: none;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1><i class="fas fa-flask"></i> Prueba de Enlaces de Categorías</h1>
        
        <p>Este script te ayudará a identificar qué enlaces están configurados en tu página de categorías.</p>
        
        <h2>Enlaces Esperados (Correctos)</h2>
        <div class="test-link correct">
            <span class="label">Listado de Categorías:</span>
            <span class="url">/RMIE/app/controllers/CategoryController.php?accion=index</span>
        </div>
        <div class="test-link correct">
            <span class="label">Editar Categoría (ejemplo ID 40):</span>
            <span class="url">/RMIE/app/controllers/CategoryController.php?accion=edit&id=40</span>
        </div>
        <div class="test-link correct">
            <span class="label">Crear Categoría:</span>
            <span class="url">/RMIE/app/controllers/CategoryController.php?accion=create</span>
        </div>
        
        <h2>Enlaces Incorrectos (Si ves estos, hay un error)</h2>
        <div class="test-link incorrect">
            <span class="label">❌ Editar Local (INCORRECTO):</span>
            <span class="url">/RMIE/app/controllers/LocalController.php?accion=edit&id=...</span>
        </div>
        
        <hr style="margin: 30px 0;">
        
        <h2>Prueba en Vivo</h2>
        <p>Haz clic en estos botones de prueba para verificar a dónde te llevan:</p>
        
        <a href="/RMIE/app/controllers/CategoryController.php?accion=edit&id=40" 
           class="button-test btn-categoria"
           onclick="logClick(this); return true;">
            📝 Editar Categoría (ID 40)
        </a>
        
        <a href="/RMIE/app/controllers/CategoryController.php?accion=index" 
           class="button-test btn-categoria"
           onclick="logClick(this); return true;">
            📋 Ver Categorías
        </a>
        
        <div id="result"></div>
        
        <hr style="margin: 30px 0;">
        
        <h2>Instrucciones de Depuración</h2>
        <ol>
            <li>Abre la página de categorías: <a href="/RMIE/app/controllers/CategoryController.php?accion=index" target="_blank">Click aquí</a></li>
            <li>Presiona <kbd>F12</kbd> para abrir las Herramientas de Desarrollo</li>
            <li>Ve a la pestaña <strong>Console</strong></li>
            <li>Busca el mensaje: <code>=== DEBUG: Enlaces de Categorías ===</code></li>
            <li>Pasa el mouse sobre el botón de editar (lápiz) sin hacer clic</li>
            <li>Mira la esquina inferior izquierda del navegador, debe mostrar: <code>CategoryController.php</code></li>
            <li>Si muestra <code>LocalController.php</code>, entonces hay un problema de caché</li>
        </ol>
        
        <h2>Soluciones si el problema persiste:</h2>
        <div style="background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
            <h3>1. Limpiar caché del navegador</h3>
            <ul>
                <li>Chrome/Edge: <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>Delete</kbd></li>
                <li>Marca "Imágenes y archivos en caché"</li>
                <li>Clic en "Borrar datos"</li>
            </ul>
            
            <h3>2. Recarga forzada</h3>
            <ul>
                <li>Presiona <kbd>Ctrl</kbd> + <kbd>F5</kbd> en la página de categorías</li>
                <li>O <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>R</kbd></li>
            </ul>
            
            <h3>3. Modo incógnito</h3>
            <ul>
                <li>Abre una ventana de incógnito: <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>N</kbd></li>
                <li>Accede a: <code>http://localhost/RMIE/app/controllers/CategoryController.php?accion=index</code></li>
                <li>Inicia sesión y prueba el botón de editar</li>
            </ul>
        </div>
        
        <hr style="margin: 30px 0;">
        
        <div style="background: #d1ecf1; padding: 15px; border-radius: 5px; border-left: 4px solid #0c5460;">
            <h3>📞 Información adicional necesaria</h3>
            <p>Para ayudarte mejor, necesito que me digas:</p>
            <ol>
                <li><strong>¿Qué URL aparece en la barra del navegador</strong> cuando te redirige incorrectamente?</li>
                <li><strong>¿Qué navegador estás usando?</strong> (Chrome, Firefox, Edge, etc.)</li>
                <li><strong>¿Desde dónde haces clic?</strong> (tarjeta o tabla de categorías)</li>
                <li><strong>¿El problema ocurre con TODAS las categorías</strong> o solo con algunas?</li>
            </ol>
        </div>
    </div>
    
    <script>
        function logClick(element) {
            const url = element.getAttribute('href');
            const result = document.getElementById('result');
            result.style.display = 'block';
            result.className = 'success';
            result.innerHTML = `
                <strong>✅ Clic detectado</strong><br>
                <strong>URL destino:</strong> ${url}<br>
                <em>Redirigiendo en 2 segundos...</em>
            `;
        }
    </script>
</body>
</html>
