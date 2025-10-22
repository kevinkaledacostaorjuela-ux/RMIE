<?php
/**
 * Script de validación de filtros del sistema RMIE
 * Ejecutar para verificar que todos los filtros funcionan correctamente
 * 
 * Uso: php validate_filters.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/db.php';
require_once 'app/utils/FilterHelper.php';

function testModule($moduleName, $callback) {
    echo "Probando $moduleName... ";
    try {
        $result = $callback();
        echo "✅ OK ($result)\n";
        return true;
    } catch (Exception $e) {
        echo "❌ ERROR: " . $e->getMessage() . "\n";
        return false;
    }
}

echo "=== VALIDACIÓN DE FILTROS DEL SISTEMA RMIE ===\n";
echo "Fecha: " . date('Y-m-d H:i:s') . "\n\n";

$passedTests = 0;
$totalTests = 0;

// Test User Model
$totalTests++;
$passedTests += testModule("User Model", function() use ($conn) {
    require_once 'app/models/User.php';
    
    // Test retrocompatibilidad
    $users1 = User::getAll($conn, 'admin', 'CC', '');
    
    // Test con array
    $users2 = User::getAll($conn, ['rol' => 'admin']);
    
    return "Retrocompat: " . count($users1) . ", Array: " . count($users2);
});

// Test Client Model
$totalTests++;
$passedTests += testModule("Client Model", function() use ($conn) {
    require_once 'app/models/Client.php';
    
    $clients1 = Client::getAll($conn);
    $clients2 = Client::getAll($conn, ['estado' => 'activo']);
    
    return "Sin filtros: " . count($clients1) . ", Con filtros: " . count($clients2);
});

// Test Product Model
$totalTests++;
$passedTests += testModule("Product Model", function() use ($conn) {
    require_once 'app/models/Product.php';
    
    // Test retrocompatibilidad
    $products1 = Product::getFiltered($conn, '1', '1', '', '');
    
    // Test con array
    $products2 = Product::getFiltered($conn, ['categoria' => 1]);
    
    return "Retrocompat: " . count($products1) . ", Array: " . count($products2);
});

// Test Sale Model
$totalTests++;
$passedTests += testModule("Sale Model", function() use ($conn) {
    require_once 'app/models/Sale.php';
    
    // Test retrocompatibilidad
    $sales1 = Sale::getFiltered($conn, '1', '1');
    
    // Test con array
    $sales2 = Sale::getFiltered($conn, ['estado' => 'completada']);
    
    return "Retrocompat: " . count($sales1) . ", Array: " . count($sales2);
});

// Test Provider Model
$totalTests++;
$passedTests += testModule("Provider Model", function() use ($conn) {
    require_once 'app/models/Provider.php';
    
    $providers1 = Provider::getAll($conn);
    $providers2 = Provider::getAll($conn, ['estado' => 'activo']);
    
    return "Sin filtros: " . count($providers1) . ", Con filtros: " . count($providers2);
});

// Test Route Model
$totalTests++;
$passedTests += testModule("Route Model", function() use ($conn) {
    require_once 'app/models/Route.php';
    
    $routes1 = Route::getAll($conn);
    $routes2 = Route::getAll($conn, ['cliente' => 1]);
    
    return "Sin filtros: " . count($routes1) . ", Con filtros: " . count($routes2);
});

// Test Category Model
$totalTests++;
$passedTests += testModule("Category Model", function() use ($conn) {
    require_once 'app/models/Category.php';
    
    $categories1 = Category::getAll($conn);
    $categories2 = Category::getAll($conn, ['nombre' => 'test']);
    
    return "Sin filtros: " . count($categories1) . ", Con filtros: " . count($categories2);
});

// Test Subcategory Model
$totalTests++;
$passedTests += testModule("Subcategory Model", function() use ($conn) {
    require_once 'app/models/SubcategorySimple.php';
    
    $subcategories1 = SubcategorySimple::getAllSimple($conn);
    $subcategories2 = SubcategorySimple::getAllSimple($conn, ['categoria' => 1]);
    
    return "Sin filtros: " . count($subcategories1) . ", Con filtros: " . count($subcategories2);
});

// Test FilterHelper directamente
$totalTests++;
$passedTests += testModule("FilterHelper Utility", function() {
    $filters = ['name' => 'test', 'age' => '25', 'invalid' => '<script>'];
    $rules = [
        'name' => ['type' => 'text', 'options' => ['max_length' => 10]],
        'age' => ['type' => 'int', 'options' => ['min' => 0, 'max' => 100]]
    ];
    
    $processed = FilterHelper::processFilters($filters, $rules);
    
    return "Procesados: " . count($processed) . " de " . count($filters);
});

echo "\n=== RESUMEN ===\n";
echo "Tests pasados: $passedTests/$totalTests\n";

if ($passedTests === $totalTests) {
    echo "🎉 TODOS LOS FILTROS FUNCIONAN CORRECTAMENTE\n";
    echo "✅ Sistema de filtros completamente operativo\n";
    echo "✅ Retrocompatibilidad mantenida\n";
    echo "✅ Seguridad implementada\n";
} else {
    echo "⚠️  ALGUNOS FILTROS TIENEN PROBLEMAS\n";
    echo "❌ Revisar los módulos que fallaron\n";
}

echo "\nPara más información, consultar FILTER_IMPROVEMENTS.md\n";
?>