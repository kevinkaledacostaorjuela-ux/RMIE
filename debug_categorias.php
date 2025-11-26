<?php
/**
 * Script de Depuración - Categorías
 * Acceder a: http://localhost/RMIE/debug_categorias.php
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/app/models/Category.php';

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔍 Debug Categorías</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #1a1a2e;
            color: #00ff00;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #0f0f1e;
            padding: 30px;
            border: 2px solid #00ff00;
            border-radius: 10px;
        }
        h1 {
            color: #00ff00;
            text-align: center;
            text-shadow: 0 0 10px #00ff00;
        }
        .section {
            margin: 20px 0;
            padding: 15px;
            background: #1a1a2e;
            border-left: 4px solid #00ff00;
        }
        .ok { color: #00ff00; }
        .error { color: #ff0000; }
        .warning { color: #ffaa00; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #00ff00;
        }
        th {
            background: #16213e;
            color: #00ff00;
            font-weight: bold;
        }
        .btn-test {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 5px;
            background: #00ff00;
            color: #000;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .btn-test:hover {
            background: #00cc00;
            transform: scale(1.05);
        }
        pre {
            background: #000;
            color: #0f0;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 MODO DEBUG: SISTEMA DE CATEGORÍAS</h1>
        
        <?php
        echo '<div class="section">';
        echo '<h2 class="ok">📊 ESTADO DEL SISTEMA</h2>';
        echo '<p>Fecha/Hora: ' . date('Y-m-d H:i:s') . '</p>';
        echo '<p>Servidor: ' . $_SERVER['SERVER_SOFTWARE'] . '</p>';
        echo '<p>PHP: ' . PHP_VERSION . '</p>';
        echo '</div>';

        // Test 1: Conexión a base de datos
        echo '<div class="section">';
        echo '<h2>🔌 TEST 1: Conexión a Base de Datos</h2>';
        if (isset($conn) && $conn->ping()) {
            echo '<p class="ok">✅ Conexión exitosa a MySQL</p>';
        } else {
            echo '<p class="error">❌ Error de conexión: ' . (isset($conn) ? $conn->error : 'No hay conexión') . '</p>';
        }
        echo '</div>';

        // Test 2: Listar categorías
        echo '<div class="section">';
        echo '<h2>📋 TEST 2: Categorías en Base de Datos</h2>';
        try {
            $categorias = Category::getAll($conn);
            if (!empty($categorias)) {
                echo '<p class="ok">✅ Se encontraron ' . count($categorias) . ' categorías</p>';
                echo '<table>';
                echo '<tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>URL Edición</th><th>Acción</th></tr>';
                foreach ($categorias as $cat) {
                    $id = $cat->id_categoria ?? '';
                    $nombre = htmlspecialchars($cat->nombre ?? 'Sin nombre');
                    $desc = htmlspecialchars($cat->descripcion ?? 'Sin descripción');
                    $url = "/RMIE/app/controllers/CategoryController.php?accion=edit&id=" . urlencode($id);
                    echo '<tr>';
                    echo '<td>' . $id . '</td>';
                    echo '<td>' . $nombre . '</td>';
                    echo '<td>' . $desc . '</td>';
                    echo '<td><code>' . $url . '</code></td>';
                    echo '<td><a href="' . $url . '" class="btn-test" target="_blank">✏️ Editar</a></td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<p class="warning">⚠️ No hay categorías en la base de datos</p>';
            }
        } catch (Exception $e) {
            echo '<p class="error">❌ Error al obtener categorías: ' . $e->getMessage() . '</p>';
        }
        echo '</div>';

        // Test 3: Verificar archivos
        echo '<div class="section">';
        echo '<h2>📁 TEST 3: Archivos del Sistema</h2>';
        $files = [
            'Controlador' => __DIR__ . '/app/controllers/CategoryController.php',
            'Vista Index' => __DIR__ . '/app/views/categorias/index.php',
            'Vista Edit' => __DIR__ . '/app/views/categorias/edit.php',
            'Modelo' => __DIR__ . '/app/models/Category.php',
        ];
        foreach ($files as $name => $path) {
            if (file_exists($path)) {
                echo '<p class="ok">✅ ' . $name . ': <code>' . $path . '</code></p>';
            } else {
                echo '<p class="error">❌ ' . $name . ' NO EXISTE: <code>' . $path . '</code></p>';
            }
        }
        echo '</div>';

        // Test 4: Verificar sesión
        echo '<div class="section">';
        echo '<h2>🔐 TEST 4: Estado de Sesión</h2>';
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user'])) {
            echo '<p class="ok">✅ Usuario logueado: ' . htmlspecialchars($_SESSION['user']) . '</p>';
            echo '<p>Rol: ' . htmlspecialchars($_SESSION['rol'] ?? 'No definido') . '</p>';
        } else {
            echo '<p class="warning">⚠️ No hay sesión activa</p>';
        }
        echo '</div>';

        // Test 5: URLs de prueba
        echo '<div class="section">';
        echo '<h2>🔗 TEST 5: Enlaces de Prueba</h2>';
        echo '<p>Haz clic en estos botones para probar la navegación:</p>';
        echo '<a href="/RMIE/app/controllers/CategoryController.php?accion=index" class="btn-test">📋 Ver Categorías</a>';
        echo '<a href="/RMIE/app/controllers/CategoryController.php?accion=create" class="btn-test">➕ Crear Categoría</a>';
        if (!empty($categorias)) {
            $firstCat = $categorias[0];
            $id = $firstCat->id_categoria ?? '';
            echo '<a href="/RMIE/app/controllers/CategoryController.php?accion=edit&id=' . urlencode($id) . '" class="btn-test">✏️ Editar Primera Categoría</a>';
        }
        echo '</div>';

        // Test 6: Información del navegador
        echo '<div class="section">';
        echo '<h2>🌐 TEST 6: Información del Navegador (Cliente)</h2>';
        echo '<p>User Agent: ' . htmlspecialchars($_SERVER['HTTP_USER_AGENT'] ?? 'No disponible') . '</p>';
        echo '<p>IP: ' . htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'No disponible') . '</p>';
        echo '</div>';

        // Test 7: JavaScript Debug
        echo '<div class="section">';
        echo '<h2>💻 TEST 7: JavaScript - Console Log</h2>';
        echo '<p>Abre la consola del navegador (F12) para ver información adicional</p>';
        echo '<button class="btn-test" onclick="testLinks()">🔍 Analizar Enlaces</button>';
        echo '<button class="btn-test" onclick="clearCache()">🗑️ Limpiar Caché Local</button>';
        echo '<pre id="jsOutput">Esperando análisis...</pre>';
        echo '</div>';
        ?>

        <div class="section">
            <h2 class="warning">⚠️ DIAGNÓSTICO DEL PROBLEMA</h2>
            <p>Basado en tu descripción, aquí están las posibles causas:</p>
            <ol>
                <li><strong>Caché del Navegador:</strong> El navegador tiene guardada una versión antigua de la página</li>
                <li><strong>Sesión Anterior:</strong> Hay datos de sesión que apuntan a LocalController</li>
                <li><strong>Cookies Corruptas:</strong> Las cookies del navegador tienen información incorrecta</li>
            </ol>
            
            <h3>Solución Inmediata:</h3>
            <ol>
                <li>Presiona <kbd>Ctrl + Shift + Delete</kbd></li>
                <li>Selecciona "Todo" o "Todo el tiempo"</li>
                <li>Marca TODAS las opciones (caché, cookies, historial)</li>
                <li>Haz clic en "Borrar datos"</li>
                <li>Cierra TODAS las ventanas del navegador</li>
                <li>Abre de nuevo y vuelve a acceder</li>
            </ol>
        </div>
    </div>

    <script>
        console.log('=== DEBUG SCRIPT ACTIVO ===');
        
        function testLinks() {
            const output = document.getElementById('jsOutput');
            let result = '=== ANÁLISIS DE ENLACES ===\n\n';
            
            // Obtener todos los enlaces
            const allLinks = document.querySelectorAll('a[href]');
            result += `Total de enlaces: ${allLinks.length}\n\n`;
            
            // Filtrar enlaces de edición
            const editLinks = Array.from(allLinks).filter(a => a.href.includes('edit'));
            result += `Enlaces de edición encontrados: ${editLinks.length}\n`;
            
            editLinks.forEach((link, i) => {
                result += `\n${i + 1}. ${link.href}`;
                result += `\n   Texto: "${link.textContent.trim()}"`;
                
                // Verificar si es CategoryController
                if (link.href.includes('CategoryController')) {
                    result += '\n   ✅ Apunta a CategoryController (CORRECTO)';
                } else if (link.href.includes('LocalController')) {
                    result += '\n   ❌ Apunta a LocalController (INCORRECTO!)';
                } else {
                    result += '\n   ⚠️ Apunta a otro controlador';
                }
            });
            
            output.textContent = result;
            console.log(result);
        }
        
        function clearCache() {
            if (confirm('¿Deseas limpiar el LocalStorage y SessionStorage?\n\nEsto puede cerrar tu sesión.')) {
                localStorage.clear();
                sessionStorage.clear();
                alert('✅ Caché local limpiado.\n\nPresiona Ctrl+Shift+Delete para limpiar la caché del navegador.');
            }
        }
        
        // Auto-análisis al cargar
        window.addEventListener('load', function() {
            console.log('Página de debug cargada');
            console.log('URL actual:', window.location.href);
            console.log('User Agent:', navigator.userAgent);
        });
    </script>
</body>
</html>
