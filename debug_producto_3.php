<?php
// Script de debug para verificar producto ID 3
require_once 'config/db.php';
require_once 'app/models/Product.php';

echo "<h2>🔍 Debug: Verificación Producto ID 3</h2>";

try {
    $id_producto = 3;
    
    // Verificar que el producto existe
    echo "<h3>📋 1. Verificar existencia del producto:</h3>";
    $producto = Product::getById($conn, $id_producto);
    
    if ($producto) {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h4>✅ Producto encontrado:</h4>";
        echo "<p><strong>ID:</strong> " . $producto->id_productos . "</p>";
        echo "<p><strong>Nombre:</strong> " . htmlspecialchars($producto->nombre) . "</p>";
        echo "<p><strong>Descripción:</strong> " . htmlspecialchars($producto->descripcion) . "</p>";
        echo "<p><strong>Stock:</strong> " . $producto->stock . "</p>";
        echo "<p><strong>Precio:</strong> $" . $producto->precio_unitario . "</p>";
        echo "<p><strong>Categoría ID:</strong> " . $producto->id_categoria . "</p>";
        echo "<p><strong>Subcategoría ID:</strong> " . $producto->id_subcategoria . "</p>";
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h4>❌ Producto NO encontrado con ID: $id_producto</h4>";
        echo "</div>";
    }
    
    // Listar todos los productos para referencia
    echo "<h3>📋 2. Todos los productos disponibles:</h3>";
    $todos_productos = Product::getAll($conn);
    
    if (!empty($todos_productos)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
        echo "<tr style='background: #f8f9fa;'>";
        echo "<th style='padding: 10px;'>ID</th>";
        echo "<th style='padding: 10px;'>Nombre</th>";
        echo "<th style='padding: 10px;'>Descripción</th>";
        echo "<th style='padding: 10px;'>Stock</th>";
        echo "<th style='padding: 10px;'>Precio</th>";
        echo "<th style='padding: 10px;'>Acción</th>";
        echo "</tr>";
        
        foreach ($todos_productos as $prod) {
            $bgColor = ($prod->id_productos == $id_producto) ? '#fff3cd' : '#ffffff';
            echo "<tr style='background: $bgColor;'>";
            echo "<td style='padding: 8px;'>" . htmlspecialchars($prod->id_productos) . "</td>";
            echo "<td style='padding: 8px;'>" . htmlspecialchars($prod->nombre) . "</td>";
            echo "<td style='padding: 8px;'>" . htmlspecialchars($prod->descripcion ?? 'Sin descripción') . "</td>";
            echo "<td style='padding: 8px;'>" . htmlspecialchars($prod->stock) . "</td>";
            echo "<td style='padding: 8px;'>$" . htmlspecialchars($prod->precio_unitario) . "</td>";
            echo "<td style='padding: 8px;'>";
            echo "<a href='/RMIE/app/controllers/ProductController.php?accion=edit&id=" . $prod->id_productos . "' style='background: #28a745; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; margin-right: 5px;'>Editar</a>";
            echo "<a href='/RMIE/app/views/productos/delete.php?id=" . $prod->id_productos . "' style='background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px;'>Eliminar</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ No se encontraron productos en la base de datos.</p>";
    }
    
    // Test de URLs generadas
    echo "<h3>🔗 3. Test de URLs:</h3>";
    $url_edit = "/RMIE/app/controllers/ProductController.php?accion=edit&id=$id_producto";
    $url_delete = "/RMIE/app/views/productos/delete.php?id=$id_producto";
    
    echo "<div style='background: #e2e3e5; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<p><strong>URL Editar:</strong> <code>$url_edit</code></p>";
    echo "<p><strong>URL Eliminar:</strong> <code>$url_delete</code></p>";
    echo "</div>";
    
    // Verificar estructura de tabla productos
    echo "<h3>🗄️ 4. Estructura de tabla productos:</h3>";
    $describeQuery = "DESCRIBE productos";
    $result = $conn->query($describeQuery);
    
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr style='background-color: #f0f0f0;'>";
    echo "<th style='padding: 8px;'>Campo</th>";
    echo "<th style='padding: 8px;'>Tipo</th>";
    echo "<th style='padding: 8px;'>Null</th>";
    echo "<th style='padding: 8px;'>Key</th>";
    echo "<th style='padding: 8px;'>Default</th>";
    echo "</tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='padding: 8px;'>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td style='padding: 8px;'>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td style='padding: 8px;'>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td style='padding: 8px;'>" . htmlspecialchars($row['Key']) . "</td>";
        echo "<td style='padding: 8px;'>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>🎯 5. Enlaces de prueba:</h3>";
    echo "<div style='margin: 20px 0;'>";
    echo "<a href='/RMIE/app/controllers/ProductController.php?accion=edit&id=$id_producto' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>✏️ Editar Producto ID $id_producto</a>";
    echo "<a href='/RMIE/app/controllers/ProductController.php?accion=index' style='background: #17a2b8; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>📋 Ver Listado</a>";
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