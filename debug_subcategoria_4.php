<?php
// Script de debug para verificar subcategoría ID 4
require_once 'config/db.php';
require_once 'app/models/SubcategorySimple.php';

echo "<h2>🔍 Debug: Verificación Subcategoría ID 4</h2>";

try {
    $id_subcategoria = 4;
    
    // Verificar que la subcategoría existe
    echo "<h3>📋 1. Verificar existencia de subcategoría:</h3>";
    $subcategoria = SubcategorySimple::getById($conn, $id_subcategoria);
    
    if ($subcategoria) {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h4>✅ Subcategoría encontrada:</h4>";
        echo "<pre>" . print_r($subcategoria, true) . "</pre>";
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h4>❌ Subcategoría NO encontrada con ID: $id_subcategoria</h4>";
        echo "</div>";
    }
    
    // Verificar dependencias
    echo "<h3>📦 2. Verificar dependencias:</h3>";
    $dependencies = SubcategorySimple::checkDependencies($conn, $id_subcategoria);
    
    if (!empty($dependencies)) {
        echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h4>⚠️ Dependencias encontradas:</h4>";
        echo "<pre>" . print_r($dependencies, true) . "</pre>";
        echo "</div>";
    } else {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h4>✅ Sin dependencias - Eliminación segura</h4>";
        echo "</div>";
    }
    
    // Listar todas las subcategorías para referencia
    echo "<h3>📋 3. Todas las subcategorías disponibles:</h3>";
    $todas_subcategorias = SubcategorySimple::getAllSimple($conn);
    
    if (!empty($todas_subcategorias)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
        echo "<tr style='background: #f8f9fa;'>";
        echo "<th style='padding: 10px;'>ID</th>";
        echo "<th style='padding: 10px;'>Nombre</th>";
        echo "<th style='padding: 10px;'>Descripción</th>";
        echo "<th style='padding: 10px;'>Categoría</th>";
        echo "<th style='padding: 10px;'>Acción</th>";
        echo "</tr>";
        
        foreach ($todas_subcategorias as $sub) {
            $bgColor = ($sub['id_subcategoria'] == $id_subcategoria) ? '#fff3cd' : '#ffffff';
            echo "<tr style='background: $bgColor;'>";
            echo "<td style='padding: 8px;'>" . htmlspecialchars($sub['id_subcategoria']) . "</td>";
            echo "<td style='padding: 8px;'>" . htmlspecialchars($sub['nombre']) . "</td>";
            echo "<td style='padding: 8px;'>" . htmlspecialchars($sub['descripcion'] ?? 'Sin descripción') . "</td>";
            echo "<td style='padding: 8px;'>" . htmlspecialchars($sub['categoria_nombre']) . "</td>";
            echo "<td style='padding: 8px;'>";
            echo "<a href='/RMIE/app/views/subcategorias/delete.php?id=" . $sub['id_subcategoria'] . "' style='background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px;'>Eliminar</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ No se encontraron subcategorías en la base de datos.</p>";
    }
    
    // Test de URLs generadas
    echo "<h3>🔗 4. Test de URLs generadas:</h3>";
    $url_sin_force = "/RMIE/app/controllers/SubcategoryController.php?accion=delete&id=$id_subcategoria";
    $url_con_force = "/RMIE/app/controllers/SubcategoryController.php?accion=delete&id=$id_subcategoria&force=1";
    
    echo "<div style='background: #e2e3e5; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<p><strong>URL sin force:</strong> <code>$url_sin_force</code></p>";
    echo "<p><strong>URL con force:</strong> <code>$url_con_force</code></p>";
    echo "</div>";
    
    echo "<h3>🎯 5. Enlaces de prueba:</h3>";
    echo "<div style='margin: 20px 0;'>";
    echo "<a href='/RMIE/app/views/subcategorias/delete.php?id=$id_subcategoria' style='background: #17a2b8; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>🔍 Ver Página de Eliminación</a>";
    echo "<a href='/RMIE/app/controllers/SubcategoryController.php?accion=index' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>📋 Ver Listado</a>";
    echo "<a href='/RMIE/app/views/dashboard.php' style='background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🏠 Dashboard</a>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h4>❌ Error durante la verificación:</h4>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

$conn->close();
?>