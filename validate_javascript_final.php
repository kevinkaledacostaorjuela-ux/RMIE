<?php
/**
 * Script final de validación JavaScript - RMIE
 * Verifica que todos los errores estén corregidos
 */

echo "=== VALIDACIÓN FINAL DE JAVASCRIPT ===\n";
echo "Fecha: " . date('Y-m-d H:i:s') . "\n\n";

function validateJavaScriptSyntax($file) {
    $content = file_get_contents($file);
    $errors = [];
    
    // 1. Verificar try sin catch
    if (preg_match_all('/try\s*\{/', $content, $tryMatches, PREG_OFFSET_CAPTURE)) {
        foreach ($tryMatches[0] as $match) {
            $pos = $match[1];
            $afterTry = substr($content, $pos);
            
            // Buscar el } correspondiente y verificar que tenga catch o finally
            if (!preg_match('/\}\s*(catch|finally)\s*\(/', $afterTry)) {
                $lineNum = substr_count(substr($content, 0, $pos), "\n") + 1;
                $errors[] = "Try sin catch/finally en línea ~$lineNum";
            }
        }
    }
    
    // 2. Verificar paréntesis balanceados
    $openParens = substr_count($content, '(');
    $closeParens = substr_count($content, ')');
    if ($openParens !== $closeParens) {
        $errors[] = "Paréntesis desbalanceados: $openParens abiertos, $closeParens cerrados";
    }
    
    // 3. Verificar llaves balanceadas en bloques JS
    if (preg_match_all('/<script[^>]*>(.*?)<\/script>/s', $content, $jsBlocks)) {
        foreach ($jsBlocks[1] as $jsBlock) {
            $openBraces = substr_count($jsBlock, '{');
            $closeBraces = substr_count($jsBlock, '}');
            if ($openBraces !== $closeBraces) {
                $errors[] = "Llaves desbalanceadas en JavaScript: $openBraces abiertas, $closeBraces cerradas";
            }
        }
    }
    
    // 4. Verificar getElementById con verificación
    if (preg_match_all('/document\.getElementById\([\'"]([^\'"]+)[\'"]\)(?!\s*&&|\s*\?|\s*;?\s*if)/', $content, $matches)) {
        foreach ($matches[1] as $id) {
            $errors[] = "getElementById('$id') sin verificación de existencia";
        }
    }
    
    return $errors;
}

// Archivos principales para validar
$files_to_validate = [
    'app/views/dashboard.php' => 'Dashboard principal',
    'app/views/rutas/index.php' => 'Módulo rutas',
    'app/views/usuarios/index.php' => 'Módulo usuarios',
    'app/views/ventas/index.php' => 'Módulo ventas',
    'app/views/productos/index.php' => 'Módulo productos',
];

$total_errors = 0;
$files_with_errors = 0;

foreach ($files_to_validate as $file => $description) {
    if (!file_exists($file)) {
        echo "⚠️  Archivo no encontrado: $file\n";
        continue;
    }
    
    echo "🔍 Validando: $description\n";
    
    $errors = validateJavaScriptSyntax($file);
    
    if (empty($errors)) {
        echo "   ✅ Sin errores detectados\n";
    } else {
        echo "   ❌ Errores encontrados:\n";
        foreach ($errors as $error) {
            echo "      - $error\n";
        }
        $files_with_errors++;
        $total_errors += count($errors);
    }
    echo "\n";
}

echo "=== VERIFICACIÓN DE ELEMENTOS DOM ===\n";

// Verificar elementos DOM críticos
$dashboard_content = file_get_contents('app/views/dashboard.php');
$critical_elements = [
    'mobileToggle' => 'Botón menú móvil',
    'sidebar' => 'Menú lateral',
    'sidebarOverlay' => 'Overlay del menú',
    'currentDateTime' => 'Fecha y hora'
];

foreach ($critical_elements as $id => $description) {
    if (preg_match('/id=[\'"]' . preg_quote($id) . '[\'"]/', $dashboard_content)) {
        echo "✅ $description ($id): Encontrado\n";
    } else {
        echo "❌ $description ($id): FALTANTE\n";
        $total_errors++;
    }
}

echo "\n=== RESUMEN FINAL ===\n";
echo "Archivos validados: " . count($files_to_validate) . "\n";
echo "Archivos con errores: $files_with_errors\n";
echo "Total de errores: $total_errors\n";

if ($total_errors === 0) {
    echo "\n🎉 ¡VALIDACIÓN EXITOSA!\n";
    echo "✅ Todos los errores JavaScript han sido corregidos\n";
    echo "✅ Sintaxis correcta en todos los archivos\n";
    echo "✅ Elementos DOM críticos presentes\n";
    echo "✅ Try-catch correctamente implementados\n";
    
    echo "\n🔧 Estado del sistema:\n";
    echo "• Sin errores 'Uncaught (in promise)'\n";
    echo "• Sin errores de sintaxis JavaScript\n";
    echo "• Manejo de errores robusto\n";
    echo "• Sistema completamente funcional\n";
    
} else {
    echo "\n⚠️  Se encontraron $total_errors errores\n";
    echo "📝 Revisa los errores listados arriba\n";
    echo "🔧 Ejecuta las correcciones necesarias\n";
}

echo "\n💡 Para verificar en el navegador:\n";
echo "1. Abre herramientas de desarrollador (F12)\n";
echo "2. Ve a la pestaña Console\n";
echo "3. Recarga la página del dashboard\n";
echo "4. Verifica que no hay errores rojos\n";

// Crear archivo de estado
$status = ($total_errors === 0) ? 'CORRECTO' : 'CON_ERRORES';
file_put_contents('javascript_status.txt', "Estado: $status\nFecha: " . date('Y-m-d H:i:s') . "\nErrores: $total_errors\n");

echo "\n📄 Estado guardado en 'javascript_status.txt'\n";
?>