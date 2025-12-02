<?php
/**
 * Script para actualizar todas las vistas con selenium-messages.js
 */

$baseDir = __DIR__ . '/app/views';
$scriptTag = '<script src="/RMIE/public/js/selenium-messages.js"></script>';

function updateViewFiles($dir) {
    global $scriptTag;
    $files = glob($dir . '/*.php');
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        
        // Solo actualizar si no tiene ya el script
        if (strpos($content, 'selenium-messages.js') === false) {
            // Buscar donde insertar el script (después de styles.css)
            $patterns = [
                '/(<link href="\/RMIE\/public\/css\/styles\.css" rel="stylesheet">)\s*\n(\s*<style>)/i',
                '/(<link href="..\/..\/public\/css\/styles\.css" rel="stylesheet">)\s*\n(\s*<style>)/i'
            ];
            
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $content)) {
                    $content = preg_replace($pattern, "$1\n    $scriptTag\n$2", $content);
                    file_put_contents($file, $content);
                    echo "✅ Actualizado: " . basename($file) . "\n";
                    break;
                }
            }
        }
    }
    
    // Procesar subdirectorios
    $subdirs = glob($dir . '/*', GLOB_ONLYDIR);
    foreach ($subdirs as $subdir) {
        updateViewFiles($subdir);
    }
}

echo "🚀 Actualizando archivos de vista...\n\n";
updateViewFiles($baseDir);
echo "\n✅ Proceso completado!\n";
?>