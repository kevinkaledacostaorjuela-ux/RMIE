#!/usr/bin/env php
<?php
/**
 * Script de instalación/configuración para RMIE
 */

echo "🚀 Configurando RMIE con Composer...\n\n";

// Verificar PHP
$phpVersion = PHP_VERSION;
echo "📋 Verificando requisitos:\n";
echo "   PHP Version: {$phpVersion}\n";

if (version_compare($phpVersion, '7.4.0', '<')) {
    echo "❌ ERROR: Se requiere PHP 7.4 o superior\n";
    exit(1);
}

// Verificar extensiones requeridas
$requiredExtensions = ['mysqli', 'json', 'session'];
$missingExtensions = [];

foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $missingExtensions[] = $ext;
    } else {
        echo "   ✅ Extensión {$ext}: OK\n";
    }
}

if (!empty($missingExtensions)) {
    echo "❌ ERROR: Faltan las siguientes extensiones PHP:\n";
    foreach ($missingExtensions as $ext) {
        echo "   - {$ext}\n";
    }
    exit(1);
}

// Verificar Composer
echo "\n📦 Verificando Composer...\n";
$composerPath = trim(shell_exec('where composer 2>nul') ?: shell_exec('which composer 2>/dev/null') ?: '');

if (empty($composerPath)) {
    echo "❌ Composer no está instalado o no está en el PATH\n";
    echo "   Por favor instale Composer desde: https://getcomposer.org/\n";
    echo "   El sistema funcionará sin Composer usando el autoloader manual\n\n";
} else {
    echo "   ✅ Composer encontrado: {$composerPath}\n";
    
    // Instalar dependencias
    echo "\n📥 Instalando dependencias...\n";
    $output = shell_exec('composer install --no-dev --optimize-autoloader 2>&1');
    echo $output;
    
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        echo "   ✅ Dependencias instaladas correctamente\n";
    } else {
        echo "   ⚠️  Advertencia: Hubo problemas al instalar dependencias\n";
    }
}

// Crear directorios necesarios
echo "\n📁 Verificando estructura de directorios...\n";
$directories = [
    'logs',
    'temp',
    'uploads',
    'tests',
    'vendor'
];

foreach ($directories as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (!is_dir($path)) {
        if (mkdir($path, 0755, true)) {
            echo "   ✅ Directorio creado: {$dir}/\n";
        } else {
            echo "   ❌ Error creando directorio: {$dir}/\n";
        }
    } else {
        echo "   ✅ Directorio existe: {$dir}/\n";
    }
}

// Verificar permisos de escritura
echo "\n🔐 Verificando permisos...\n";
$writableDirs = ['logs', 'temp', 'uploads'];

foreach ($writableDirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (is_writable($path)) {
        echo "   ✅ Escritura OK: {$dir}/\n";
    } else {
        echo "   ❌ Sin permisos de escritura: {$dir}/\n";
    }
}

echo "\n🎉 Configuración completada!\n";
echo "\n📖 Próximos pasos:\n";
echo "   1. Configurar base de datos en config/db.php\n";
echo "   2. Ejecutar: php -S localhost:8000 (servidor de desarrollo)\n";
echo "   3. Visitar: http://localhost:8000\n\n";

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "🔄 Composer está activo - usando PSR-4 autoloading\n";
} else {
    echo "🔄 Usando autoloader manual - funcionalidad completa disponible\n";
}
echo "\n";
?>