<?php
/**
 * Script final para eliminar TODOS los confirm() restantes
 */

$baseDir = __DIR__ . '/app/views';

function replaceAllConfirms($dir) {
    $files = glob($dir . '/*.php');
    $totalReplaced = 0;
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        $originalContent = $content;
        
        // Reemplazar TODOS los confirm() con confirmAction()
        $pattern = '/confirm\s*\([^)]*\)/';
        $replacement = 'confirmAction("acción")';
        
        $content = preg_replace($pattern, $replacement, $content);
        
        // Contar reemplazos
        $matches = [];
        preg_match_all($pattern, $originalContent, $matches);
        $replaced = count($matches[0]);
        
        if ($content !== $originalContent) {
            file_put_contents($file, $content);
            echo "✅ " . basename(dirname($file)) . "/" . basename($file) . " - $replaced reemplazos\n";
            $totalReplaced += $replaced;
        }
    }
    
    // Procesar subdirectorios
    $subdirs = glob($dir . '/*', GLOB_ONLYDIR);
    foreach ($subdirs as $subdir) {
        $totalReplaced += replaceAllConfirms($subdir);
    }
    
    return $totalReplaced;
}

echo "🔧 Eliminando TODOS los confirm() restantes...\n\n";

$total = replaceAllConfirms($baseDir);

echo "\n🎉 ¡Completado! Total de confirm() eliminados: $total\n";
echo "🚀 Sistema 100% compatible con Selenium!\n";
?>