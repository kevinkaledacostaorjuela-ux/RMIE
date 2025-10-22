<?php
/**
 * Script para corregir errores de JavaScript en archivos PHP
 * Busca archivos que usan Bootstrap CSS pero les falta el JavaScript
 */

echo "=== CORRECTOR DE ERRORES JAVASCRIPT ===\n";
echo "Fecha: " . date('Y-m-d H:i:s') . "\n\n";

// Directorio base
$baseDir = __DIR__;
$viewsDir = $baseDir . '/app/views';

// Función para escanear directorios recursivamente
function scanDirectory($dir) {
    $files = [];
    if (is_dir($dir)) {
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item != '.' && $item != '..') {
                $path = $dir . '/' . $item;
                if (is_dir($path)) {
                    $files = array_merge($files, scanDirectory($path));
                } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
                    $files[] = $path;
                }
            }
        }
    }
    return $files;
}

// Obtener todos los archivos PHP en views
$phpFiles = scanDirectory($viewsDir);

$errorsFound = 0;
$filesFixed = 0;

echo "Analizando " . count($phpFiles) . " archivos PHP...\n\n";

foreach ($phpFiles as $file) {
    $content = file_get_contents($file);
    $needsJavaScript = false;
    $hasBootstrapCSS = false;
    $hasBootstrapJS = false;
    
    // Verificar si tiene Bootstrap CSS
    if (preg_match('/bootstrap.*\.css/', $content)) {
        $hasBootstrapCSS = true;
    }
    
    // Verificar si tiene Bootstrap JS
    if (preg_match('/bootstrap.*\.js/', $content)) {
        $hasBootstrapJS = true;
    }
    
    // Verificar si tiene elementos que requieren JavaScript
    if (preg_match('/(modal|dropdown|collapse|tooltip|popover|carousel|tab)/i', $content)) {
        $needsJavaScript = true;
    }
    
    // Si tiene Bootstrap CSS pero no JS, y parece necesitar JS
    if ($hasBootstrapCSS && !$hasBootstrapJS && $needsJavaScript) {
        echo "❌ PROBLEMA: " . basename($file) . "\n";
        echo "   - Tiene Bootstrap CSS: ✅\n";
        echo "   - Tiene Bootstrap JS: ❌\n";
        echo "   - Necesita JS: ✅\n";
        
        // Intentar agregar Bootstrap JS antes del </body>
        if (preg_match('/<\/body>/i', $content)) {
            $jsScript = '<!-- Bootstrap JavaScript -->' . "\n" . 
                       '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>' . "\n" . 
                       '</body>';
            
            $newContent = preg_replace('/<\/body>/i', $jsScript, $content);
            
            if (file_put_contents($file, $newContent)) {
                echo "   ✅ CORREGIDO: Bootstrap JS agregado\n";
                $filesFixed++;
            } else {
                echo "   ❌ ERROR: No se pudo escribir el archivo\n";
            }
        }
        
        $errorsFound++;
        echo "\n";
    } elseif ($hasBootstrapCSS && !$hasBootstrapJS) {
        echo "⚠️  ADVERTENCIA: " . basename($file) . "\n";
        echo "   - Tiene Bootstrap CSS pero no JS (puede no necesitarlo)\n\n";
    }
}

echo "=== RESUMEN ===\n";
echo "Archivos analizados: " . count($phpFiles) . "\n";
echo "Problemas encontrados: $errorsFound\n";
echo "Archivos corregidos: $filesFixed\n";

if ($filesFixed > 0) {
    echo "\n🎉 Se corrigieron $filesFixed archivos\n";
    echo "Los errores de JavaScript deberían estar resueltos.\n";
} else {
    echo "\n✅ No se encontraron problemas adicionales\n";
}

echo "\nRevisa tu navegador - los errores 404 de JavaScript deberían desaparecer.\n";
?>