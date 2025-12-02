<?php
/**
 * Autoloader Bootstrap para RMIE
 * Integra Composer de forma gradual con el sistema existente
 */

// Verificar si Composer está disponible
$vendorAutoload = __DIR__ . '/vendor/autoload.php';

if (file_exists($vendorAutoload)) {
    // Cargar autoloader de Composer
    require_once $vendorAutoload;
    
    // Definir constante para indicar que Composer está disponible
    define('RMIE_COMPOSER_LOADED', true);
    
    // Los aliases se crearán cuando sean necesarios para evitar conflictos
} else {
    // Fallback al sistema actual si Composer no está instalado
    define('RMIE_COMPOSER_LOADED', false);
    
    // Mantener el sistema de includes actual
    function rmie_autoload($className) {
        $paths = [
            __DIR__ . '/app/models/' . $className . '.php',
            __DIR__ . '/app/controllers/' . $className . '.php',
            __DIR__ . '/app/utils/' . $className . '.php',
            __DIR__ . '/config/' . $className . '.php'
        ];
        
        foreach ($paths as $path) {
            if (file_exists($path)) {
                require_once $path;
                return true;
            }
        }
        return false;
    }
    
    // Registrar autoloader personalizado
    spl_autoload_register('rmie_autoload');
}

// Función helper para verificar si una clase está disponible
function rmie_class_exists($className) {
    if (RMIE_COMPOSER_LOADED) {
        return class_exists('RMIE\\Models\\' . $className) || class_exists($className);
    }
    return class_exists($className);
}