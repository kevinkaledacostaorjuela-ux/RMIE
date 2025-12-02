<?php
/**
 * Script para verificar que no quedan confirm() sin reemplazar
 */

$baseDir = __DIR__ . '/app/views';

function checkConfirms($dir) {
    $files = glob($dir . '/*.php');
    $foundConfirms = [];
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        $lines = explode("\n", $content);
        
        foreach ($lines as $lineNum => $line) {
            if (preg_match('/confirm\s*\(/', $line)) {
                $foundConfirms[] = [
                    'file' => str_replace(__DIR__ . '/', '', $file),
                    'line' => $lineNum + 1,
                    'content' => trim($line)
                ];
            }
        }
    }
    
    // Procesar subdirectorios
    $subdirs = glob($dir . '/*', GLOB_ONLYDIR);
    foreach ($subdirs as $subdir) {
        $foundConfirms = array_merge($foundConfirms, checkConfirms($subdir));
    }
    
    return $foundConfirms;
}

echo "🔍 Verificando que no queden confirm() sin reemplazar...\n\n";

$confirms = checkConfirms($baseDir);

if (empty($confirms)) {
    echo "✅ ¡Perfecto! No se encontraron confirm() sin reemplazar.\n";
    echo "🚀 El sistema está listo para Selenium!\n";
} else {
    echo "⚠️ Se encontraron " . count($confirms) . " confirm() pendientes:\n\n";
    foreach ($confirms as $confirm) {
        echo "📄 " . $confirm['file'] . " (línea " . $confirm['line'] . ")\n";
        echo "   " . $confirm['content'] . "\n\n";
    }
}

// También verificar archivos principales
$mainFiles = [
    'index.php',
    'rutas.php'
];

foreach ($mainFiles as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (preg_match('/confirm\s*\(/', $content)) {
            echo "⚠️ Encontrado confirm() en archivo principal: $file\n";
        }
    }
}

echo "\n✅ Verificación completada!\n";
?>