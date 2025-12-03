<?php
/**
 * Script de verificación de errores JavaScript y enlaces
 * Detecta problemas comunes que pueden causar errores en consola
 */

// Lista de archivos PHP principales que usan JavaScript
$files_to_check = [
    'app/views/productos/index.php',
    'app/views/productos/create.php',
    'app/views/categorias/index.php',
    'app/views/ventas/index.php',
    'app/views/clientes/index.php'
];

echo "<html><head><title>RMIE - Verificación de Errores JS</title>";
echo "<style>
body { font-family: Arial; background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px; }
.container { max-width: 1000px; margin: 0 auto; background: rgba(255,255,255,0.1); padding: 30px; border-radius: 15px; }
.error { color: #ff6b6b; background: rgba(255,107,107,0.2); padding: 10px; margin: 5px 0; border-radius: 5px; }
.warning { color: #ffd93d; background: rgba(255,217,61,0.2); padding: 10px; margin: 5px 0; border-radius: 5px; }
.success { color: #6bcf7f; background: rgba(107,207,127,0.2); padding: 10px; margin: 5px 0; border-radius: 5px; }
.info { color: #4dabf7; background: rgba(77,171,247,0.2); padding: 10px; margin: 5px 0; border-radius: 5px; }
h2 { color: #ffffff; border-bottom: 2px solid rgba(255,255,255,0.3); padding-bottom: 10px; }
pre { background: rgba(0,0,0,0.3); padding: 15px; border-radius: 8px; font-size: 12px; overflow-x: auto; }
</style></head><body>";

echo "<div class='container'>";
echo "<h1>🔍 Verificación de Errores JavaScript - RMIE</h1>";

// Verificar que selenium-messages.js existe y es accesible
echo "<h2>1. Verificación de Archivos JavaScript</h2>";
$js_file = 'public/js/selenium-messages.js';
if (file_exists($js_file)) {
    echo "<div class='success'>✓ selenium-messages.js existe</div>";
    
    // Verificar el contenido del archivo
    $js_content = file_get_contents($js_file);
    if (strpos($js_content, 'confirmAction') !== false) {
        echo "<div class='success'>✓ Función confirmAction encontrada</div>";
    } else {
        echo "<div class='error'>✗ Función confirmAction NO encontrada</div>";
    }
    
    if (strpos($js_content, 'toggleProductsView') !== false) {
        echo "<div class='success'>✓ Función toggleProductsView encontrada</div>";
    } else {
        echo "<div class='error'>✗ Función toggleProductsView NO encontrada</div>";
    }
    
    if (strpos($js_content, 'limpiarFiltros') !== false) {
        echo "<div class='success'>✓ Función limpiarFiltros encontrada</div>";
    } else {
        echo "<div class='error'>✗ Función limpiarFiltros NO encontrada</div>";
    }
    
} else {
    echo "<div class='error'>✗ selenium-messages.js NO existe en: $js_file</div>";
}

// Verificar rutas en archivos PHP
echo "<h2>2. Verificación de Referencias JavaScript en Archivos PHP</h2>";

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "<h3>📄 " . basename($file) . "</h3>";
        $content = file_get_contents($file);
        
        // Verificar que incluye selenium-messages.js
        if (strpos($content, 'selenium-messages.js') !== false) {
            echo "<div class='success'>✓ Incluye selenium-messages.js</div>";
        } else {
            echo "<div class='warning'>⚠ NO incluye selenium-messages.js</div>";
        }
        
        // Buscar llamadas a funciones JavaScript
        $js_functions = ['confirmAction', 'toggleProductsView', 'limpiarFiltros', 'limpiarProductos'];
        foreach ($js_functions as $func) {
            if (strpos($content, $func) !== false) {
                echo "<div class='info'>ℹ️ Usa función: $func</div>";
            }
        }
        
        // Buscar onclick attributes problemáticos
        preg_match_all('/onclick\s*=\s*["\']([^"\']*)["\']/', $content, $matches);
        foreach ($matches[1] as $onclick) {
            if (strpos($onclick, 'return') !== false) {
                echo "<div class='info'>ℹ️ onclick con return: " . htmlspecialchars(substr($onclick, 0, 50)) . "...</div>";
            }
        }
        
    } else {
        echo "<div class='error'>✗ Archivo no encontrado: $file</div>";
    }
}

// Verificar rutas de enlaces en productos/index.php
echo "<h2>3. Verificación de Rutas de Enlaces</h2>";
$productos_index = 'app/views/productos/index.php';
if (file_exists($productos_index)) {
    $content = file_get_contents($productos_index);
    
    // Buscar enlaces href
    preg_match_all('/href\s*=\s*["\']([^"\']*)["\']/', $content, $matches);
    $unique_links = array_unique($matches[1]);
    
    foreach ($unique_links as $link) {
        if (strpos($link, '/RMIE/') === 0) {
            // Es una ruta relativa al proyecto
            $actual_path = '.' . $link;
            if (file_exists($actual_path)) {
                echo "<div class='success'>✓ Enlace válido: $link</div>";
            } else {
                echo "<div class='error'>✗ Enlace roto: $link</div>";
            }
        } else {
            echo "<div class='info'>ℹ️ Enlace externo o relativo: $link</div>";
        }
    }
}

// Verificar controladores referenciados
echo "<h2>4. Verificación de Controladores</h2>";
$controllers = [
    'app/controllers/ProductController.php',
    'app/controllers/CategoryController.php',
    'app/controllers/ClientController.php'
];

foreach ($controllers as $controller) {
    if (file_exists($controller)) {
        echo "<div class='success'>✓ Controlador existe: " . basename($controller) . "</div>";
        
        // Verificar métodos comunes
        $content = file_get_contents($controller);
        $methods = ['index', 'create', 'edit', 'delete'];
        foreach ($methods as $method) {
            if (strpos($content, "function $method") !== false || strpos($content, "public function $method") !== false) {
                echo "<div class='info'>ℹ️ Método $method encontrado</div>";
            }
        }
    } else {
        echo "<div class='error'>✗ Controlador no encontrado: $controller</div>";
    }
}

// Verificar configuración de base de datos
echo "<h2>5. Verificación de Configuración</h2>";
if (file_exists('config/db.php')) {
    echo "<div class='success'>✓ Archivo config/db.php existe</div>";
} else {
    echo "<div class='error'>✗ Archivo config/db.php NO encontrado</div>";
}

// Generar script de prueba JavaScript
echo "<h2>6. Script de Prueba JavaScript</h2>";
echo "<p>Copie este código en la consola del navegador para probar las funciones:</p>";
echo "<pre>";
echo "// Pruebas de funciones JavaScript\n";
echo "console.log('=== PRUEBAS DE FUNCIONES ===');\n\n";
echo "// Probar confirmAction\n";
echo "if (typeof confirmAction === 'function') {\n";
echo "    console.log('✓ confirmAction existe');\n";
echo "    console.log('Resultado:', confirmAction('test'));\n";
echo "} else {\n";
echo "    console.error('✗ confirmAction NO existe');\n";
echo "}\n\n";
echo "// Probar toggleProductsView\n";
echo "if (typeof toggleProductsView === 'function') {\n";
echo "    console.log('✓ toggleProductsView existe');\n";
echo "} else {\n";
echo "    console.error('✗ toggleProductsView NO existe');\n";
echo "}\n\n";
echo "// Probar limpiarFiltros\n";
echo "if (typeof limpiarFiltros === 'function') {\n";
echo "    console.log('✓ limpiarFiltros existe');\n";
echo "} else {\n";
echo "    console.error('✗ limpiarFiltros NO existe');\n";
echo "}\n\n";
echo "// Verificar elementos del DOM\n";
echo "const elements = {\n";
echo "    'filterForm': document.getElementById('filterForm'),\n";
echo "    'cardsView': document.getElementById('cardsView'),\n";
echo "    'tableView': document.getElementById('tableView'),\n";
echo "    'btnCards': document.getElementById('btnCards'),\n";
echo "    'btnTable': document.getElementById('btnTable')\n";
echo "};\n\n";
echo "Object.entries(elements).forEach(([name, element]) => {\n";
echo "    if (element) {\n";
echo "        console.log(`✓ ${name} encontrado`);\n";
echo "    } else {\n";
echo "        console.error(`✗ ${name} NO encontrado`);\n";
echo "    }\n";
echo "});\n";
echo "</pre>";

echo "<div class='info'><strong>Recomendaciones:</strong><br>";
echo "1. Abra la consola del navegador (F12) al cargar la página de productos<br>";
echo "2. Ejecute el script de prueba anterior<br>";
echo "3. Verifique que no hay errores en rojo<br>";
echo "4. Si hay errores, revise las rutas y nombres de archivos<br>";
echo "5. Asegúrese de que XAMPP esté ejecutándose correctamente</div>";

echo "</div></body></html>";
?>