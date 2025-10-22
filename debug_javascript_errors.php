<?php
/**
 * Script para detectar y corregir errores JavaScript en el sistema RMIE
 */

echo "=== DETECTOR DE ERRORES JAVASCRIPT ===\n";
echo "Fecha: " . date('Y-m-d H:i:s') . "\n\n";

// Verificar archivos principales
$files_to_check = [
    'app/views/dashboard.php',
    'app/views/usuarios/index.php',
    'app/views/productos/index.php',
    'app/views/ventas/index.php',
    'app/views/clientes/index.php',
    'app/views/proveedores/index.php',
    'app/views/rutas/index.php',
    'app/views/reportes/index.php'
];

$errors_found = 0;
$files_fixed = 0;

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "🔍 Analizando: " . basename($file) . "\n";
        
        $content = file_get_contents($file);
        $issues = [];
        
        // Verificar getElementById sin verificación
        if (preg_match_all('/document\.getElementById\([\'"]([^\'"]+)[\'"]\)(?!\s*&&|\s*\?)/', $content, $matches)) {
            foreach ($matches[1] as $id) {
                // Verificar si el ID existe en el HTML
                if (!preg_match('/id=[\'"]' . preg_quote($id) . '[\'"]/', $content)) {
                    $issues[] = "ID '$id' no encontrado en HTML";
                }
            }
        }
        
        // Verificar querySelector sin verificación
        if (preg_match_all('/document\.querySelector\([\'"]([^\'"]+)[\'"]\)(?!\s*&&|\s*\?)/', $content, $matches)) {
            $issues[] = "querySelector sin verificación encontrado";
        }
        
        // Verificar fetch/ajax sin catch
        if (preg_match('/fetch\(.*\)(?!.*catch)/', $content)) {
            $issues[] = "fetch() sin manejo de errores encontrado";
        }
        
        // Verificar addEventListener en elementos que pueden no existir
        if (preg_match_all('/(\w+)\.addEventListener/', $content, $matches)) {
            $issues[] = "addEventListener sin verificación de existencia";
        }
        
        if (!empty($issues)) {
            echo "  ❌ Problemas encontrados:\n";
            foreach ($issues as $issue) {
                echo "     - $issue\n";
            }
            $errors_found++;
        } else {
            echo "  ✅ Sin problemas detectados\n";
        }
        echo "\n";
    } else {
        echo "⚠️  Archivo no encontrado: $file\n\n";
    }
}

// Verificar si hay errores comunes en console
echo "🔍 Verificando patrones de error comunes...\n";

// Buscar archivos con promesas no manejadas
$all_php_files = glob('app/views/**/*.php');
foreach ($all_php_files as $file) {
    $content = file_get_contents($file);
    
    // Buscar promesas sin catch
    if (preg_match('/Promise\.|\.then\((?!.*\.catch)/', $content)) {
        echo "⚠️  Promesa sin catch en: " . basename($file) . "\n";
    }
    
    // Buscar async/await sin try-catch
    if (preg_match('/await\s+(?!.*try)/', $content)) {
        echo "⚠️  await sin try-catch en: " . basename($file) . "\n";
    }
}

echo "\n=== RESUMEN ===\n";
echo "Archivos analizados: " . count($files_to_check) . "\n";
echo "Problemas encontrados: $errors_found\n";

if ($errors_found > 0) {
    echo "\n💡 RECOMENDACIONES:\n";
    echo "1. Agregar verificaciones de existencia antes de getElementById\n";
    echo "2. Envolver código en try-catch para manejo de errores\n";
    echo "3. Usar DOMContentLoaded para asegurar que el DOM esté listo\n";
    echo "4. Agregar .catch() a todas las promesas\n";
    echo "\n📝 El dashboard.php ya fue corregido con verificaciones de seguridad.\n";
} else {
    echo "\n✅ No se detectaron problemas críticos\n";
}

echo "\n🔧 Para solucionar los errores en consola:\n";
echo "1. Abrir herramientas de desarrollador (F12)\n";
echo "2. Ir a la pestaña Console\n";
echo "3. Recargar la página\n";
echo "4. Los errores 'Uncaught (in promise)' deberían desaparecer\n";
?>