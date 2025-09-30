<?php
session_start();
require_once 'config/db.php';
require_once 'app/models/Product.php';

echo "<!DOCTYPE html><html><head><title>Test Productos Sin Proveedor - RMIE</title>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
    .container { background: white; padding: 20px; border-radius: 15px; margin: 20px auto; max-width: 1000px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
    .success { color: green; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
    .error { color: red; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0; }
    .info { color: blue; padding: 10px; background: #d1ecf1; border-radius: 5px; margin: 10px 0; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background-color: #f8f9fa; }
    .btn { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block; }
    .btn:hover { background: #0056b3; }
    .btn-success { background: #28a745; }
    .btn-success:hover { background: #1e7e34; }
</style>";
echo "</head><body>";

echo "<div class='container'>";
echo "<h1>🧪 Test Productos Sin Proveedor - Sistema RMIE</h1>";

try {
    if (isset($_GET['test']) && $_GET['test'] == '1') {
        echo "<h2>🚀 Probando creación de producto sin proveedor...</h2>";
        
        // Datos de prueba
        $nombre = "Producto Test " . date('H:i:s');
        $descripcion = "Producto de prueba sin proveedor";
        $fecha_entrada = date('Y-m-d');
        $fecha_fabricacion = date('Y-m-d');
        $fecha_caducidad = date('Y-m-d', strtotime('+1 year'));
        $stock = 1;
        $precio_unitario = 100;
        $precio_por_mayor = 80;
        $valor_unitario = 100;
        $marca = "Marca Test";
        $id_subcategoria = 5;
        $id_categoria = 25;
        $id_proveedores = null; // SIN PROVEEDOR
        $num_doc = 1;
        
        echo "<div class='info'>📝 Datos a insertar:</div>";
        echo "<table>";
        echo "<tr><th>Campo</th><th>Valor</th></tr>";
        echo "<tr><td>Nombre</td><td>$nombre</td></tr>";
        echo "<tr><td>Descripción</td><td>$descripcion</td></tr>";
        echo "<tr><td>Stock</td><td>$stock</td></tr>";
        echo "<tr><td>Marca</td><td>$marca</td></tr>";
        echo "<tr><td>ID Proveedor</td><td><strong>NULL (Sin proveedor)</strong></td></tr>";
        echo "<tr><td>ID Usuario</td><td>$num_doc</td></tr>";
        echo "</table>";
        
        try {
            $result = Product::create($conn, $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, 
                                    $fecha_caducidad, $stock, $precio_unitario, $precio_por_mayor, 
                                    $valor_unitario, $marca, $id_subcategoria, $id_categoria, 
                                    $id_proveedores, $num_doc);
            
            if ($result) {
                echo "<div class='success'>✅ ¡Producto creado exitosamente SIN proveedor específico!</div>";
                
                // Mostrar el último producto creado
                $sql = "SELECT p.*, pr.nombre_distribuidor as proveedor_nombre 
                        FROM productos p 
                        LEFT JOIN proveedores pr ON p.id_proveedores = pr.id_proveedores 
                        ORDER BY p.id_productos DESC LIMIT 1";
                
                $result = $conn->query($sql);
                if ($result && $row = $result->fetch_assoc()) {
                    echo "<h3>📦 Producto creado:</h3>";
                    echo "<table>";
                    echo "<tr><th>Campo</th><th>Valor</th></tr>";
                    foreach ($row as $campo => $valor) {
                        if ($campo == 'proveedor_nombre') {
                            echo "<tr><td><strong>Proveedor Asignado</strong></td><td>" . htmlspecialchars($valor) . "</td></tr>";
                        } else {
                            echo "<tr><td><strong>$campo</strong></td><td>" . htmlspecialchars($valor ?? 'NULL') . "</td></tr>";
                        }
                    }
                    echo "</table>";
                }
                
            } else {
                echo "<div class='error'>❌ No se pudo crear el producto</div>";
            }
        } catch (Exception $e) {
            echo "<div class='error'>❌ Error al crear producto: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
    
    // Mostrar proveedores existentes
    echo "<h2>📋 Proveedores en el sistema:</h2>";
    $proveedores = $conn->query("SELECT * FROM proveedores ORDER BY id_proveedores");
    if ($proveedores && $proveedores->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Teléfono</th><th>Estado</th></tr>";
        while($prov = $proveedores->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $prov['id_proveedores'] . "</td>";
            echo "<td>" . htmlspecialchars($prov['nombre_distribuidor']) . "</td>";
            echo "<td>" . htmlspecialchars($prov['correo']) . "</td>";
            echo "<td>" . htmlspecialchars($prov['cel_proveedor']) . "</td>";
            echo "<td>" . htmlspecialchars($prov['estado']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='info'>No hay proveedores en el sistema</div>";
    }
    
    // Mostrar últimos productos
    echo "<h2>📦 Últimos productos creados:</h2>";
    $sql = "SELECT p.id_productos, p.nombre, p.stock, p.marca, pr.nombre_distribuidor as proveedor 
            FROM productos p 
            LEFT JOIN proveedores pr ON p.id_proveedores = pr.id_proveedores 
            ORDER BY p.id_productos DESC LIMIT 10";
    
    $productos = $conn->query($sql);
    if ($productos && $productos->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Stock</th><th>Marca</th><th>Proveedor</th></tr>";
        while($prod = $productos->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $prod['id_productos'] . "</td>";
            echo "<td>" . htmlspecialchars($prod['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($prod['stock']) . "</td>";
            echo "<td>" . htmlspecialchars($prod['marca']) . "</td>";
            echo "<td>" . htmlspecialchars($prod['proveedor'] ?? 'Sin proveedor') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "<h2>🎮 Acciones:</h2>";
echo "<p>";
echo "<a href='?test=1' class='btn btn-success'>🧪 Crear Producto de Prueba (Sin Proveedor)</a> ";
echo "<a href='?' class='btn'>🔄 Recargar</a> ";
echo "<a href='app/controllers/ProductController.php?accion=create' class='btn'>➕ Ir a Crear Producto Real</a>";
echo "</p>";

echo "<div class='success'>";
echo "<h3>✅ Solución Implementada:</h3>";
echo "<p><strong>Problema resuelto:</strong> La columna 'id_proveedores' no permitía valores NULL en la base de datos.</p>";
echo "<p><strong>Solución:</strong> Se creó un sistema que automáticamente asigna un proveedor genérico 'Sin Proveedor' cuando no se selecciona ninguno.</p>";
echo "<p><strong>Funcionamiento:</strong> El sistema busca o crea un proveedor llamado 'Sin Proveedor' y lo asigna automáticamente.</p>";
echo "</div>";

echo "</div>";
echo "</body></html>";
?>