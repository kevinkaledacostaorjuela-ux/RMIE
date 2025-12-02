<?php
/**
 * Ejemplo de uso de RMIE con Composer
 * Demuestra la integración híbrida entre Composer y el sistema actual
 */

// Cargar bootstrap (funciona con y sin Composer)
require_once __DIR__ . '/bootstrap.php';

echo "🚀 RMIE - Sistema híbrido con Composer\n\n";

// Verificar estado de Composer
if (defined('RMIE_COMPOSER_LOADED') && RMIE_COMPOSER_LOADED) {
    echo "✅ Composer está activo - PSR-4 autoloading disponible\n";
} else {
    echo "🔄 Usando autoloader manual - compatibilidad completa\n";
}

// Ejemplos de uso
echo "\n📋 Ejemplos de uso:\n\n";

// 1. Usar las clases existentes (sin cambios)
echo "1. Método actual (sin cambios):\n";
echo "   require_once 'app/models/Category.php';\n";
echo "   \$category = new Category();\n\n";

// 2. Con Composer (futuro)
echo "2. Con namespaces PSR-4 (futuro):\n";
echo "   use RMIE\\Models\\Category;\n";
echo "   \$category = new Category();\n\n";

// 3. Compatibilidad (funciona ahora)
echo "3. Sistema híbrido (funciona ahora):\n";
try {
    // Esto funcionará tanto con Composer como sin él
    if (rmie_class_exists('Category')) {
        echo "   ✅ Clase Category disponible\n";
    } else {
        echo "   ❌ Clase Category no disponible\n";
    }
} catch (Exception $e) {
    echo "   ⚠️  Error: " . $e->getMessage() . "\n";
}

// Información del sistema
echo "\n📊 Información del sistema:\n";
echo "   PHP Version: " . PHP_VERSION . "\n";
echo "   Composer: " . (RMIE_COMPOSER_LOADED ? 'Activo' : 'Manual') . "\n";
echo "   Autoloader: " . (function_exists('spl_autoload_functions') ? 'Disponible' : 'No disponible') . "\n";

// Comandos útiles
echo "\n🔧 Comandos útiles:\n";
echo "   composer install           # Instalar dependencias\n";
echo "   composer dump-autoload     # Regenerar autoloader\n";
echo "   composer require package   # Agregar nueva dependencia\n";
echo "   php -S localhost:8000      # Servidor de desarrollo\n\n";

echo "🎉 Sistema configurado correctamente!\n";
?>