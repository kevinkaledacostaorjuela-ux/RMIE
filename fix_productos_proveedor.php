<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/db.php';

echo "<!DOCTYPE html><html><head><title>Arreglar Tabla Productos - RMIE</title>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
    .container { background: white; padding: 20px; border-radius: 15px; margin: 20px auto; max-width: 800px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
    .success { color: green; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
    .error { color: red; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0; }
    .info { color: blue; padding: 10px; background: #d1ecf1; border-radius: 5px; margin: 10px 0; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background-color: #f8f9fa; }
    pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
    .btn { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block; }
    .btn:hover { background: #0056b3; }
</style>";
echo "</head><body>";

echo "<div class='container'>";
echo "<h1>🔧 Arreglar Tabla Productos - Permitir Proveedores Opcionales</h1>";

try {
    echo "<h2>📋 1. Estructura actual de la tabla productos:</h2>";
    $result = $conn->query("DESCRIBE productos");
    if ($result) {
        echo "<table>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while($row = $result->fetch_assoc()) {
            $nullClass = ($row['Null'] == 'NO') ? 'error' : 'success';
            echo "<tr>";
            echo "<td><strong>" . htmlspecialchars($row['Field']) . "</strong></td>";
            echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
            echo "<td class='" . $nullClass . "'>" . htmlspecialchars($row['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Default']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Verificar si id_proveedores permite NULL
        $result->data_seek(0);
        $needsFix = false;
        while($row = $result->fetch_assoc()) {
            if ($row['Field'] == 'id_proveedores' && $row['Null'] == 'NO') {
                $needsFix = true;
                echo "<div class='error'>❌ La columna 'id_proveedores' NO permite NULL - Necesita ser corregida</div>";
                break;
            }
        }
        
        if (!$needsFix) {
            echo "<div class='success'>✅ La columna 'id_proveedores' ya permite NULL - No necesita corrección</div>";
        }
    }
    
    // Ejecutar la corrección si se solicita
    if (isset($_GET['fix']) && $_GET['fix'] == '1') {
        echo "<h2>🛠️ 2. Aplicando corrección...</h2>";
        
        $sql = "ALTER TABLE productos MODIFY COLUMN id_proveedores INT NULL";
        
        if ($conn->query($sql) === TRUE) {
            echo "<div class='success'>✅ Tabla modificada correctamente. La columna 'id_proveedores' ahora permite valores NULL.</div>";
            
            echo "<h3>📋 Estructura actualizada:</h3>";
            $result = $conn->query("DESCRIBE productos");
            if ($result) {
                echo "<table>";
                echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
                while($row = $result->fetch_assoc()) {
                    $nullClass = ($row['Null'] == 'NO') ? 'error' : 'success';
                    echo "<tr>";
                    echo "<td><strong>" . htmlspecialchars($row['Field']) . "</strong></td>";
                    echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
                    echo "<td class='" . $nullClass . "'>" . htmlspecialchars($row['Null']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Default']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            
        } else {
            echo "<div class='error'>❌ Error al modificar la tabla: " . htmlspecialchars($conn->error) . "</div>";
        }
    }
    
    echo "<h2>🔬 3. Probar creación de producto sin proveedor:</h2>";
    if (isset($_GET['test']) && $_GET['test'] == '1') {
        // Incluir las clases necesarias
        require_once 'app/models/Product.php';
        
        echo "<div class='info'>🧪 Probando creación de producto de prueba sin proveedor...</div>";
        
        $nombre = "Producto Test Sin Proveedor";
        $descripcion = "Producto de prueba para validar funcionamiento";
        $fecha_entrada = date('Y-m-d');
        $fecha_fabricacion = date('Y-m-d');
        $fecha_caducidad = date('Y-m-d', strtotime('+1 year'));
        $stock = 1;
        $precio_unitario = 100;
        $precio_por_mayor = 80;
        $valor_unitario = 100;
        $marca = "Marca Test";
        $id_subcategoria = 1;
        $id_categoria = 1;
        $id_proveedores = null; // SIN PROVEEDOR
        $num_doc = 1;
        
        try {
            $result = Product::create($conn, $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, 
                                    $fecha_caducidad, $stock, $precio_unitario, $precio_por_mayor, 
                                    $valor_unitario, $marca, $id_subcategoria, $id_categoria, 
                                    $id_proveedores, $num_doc);
            
            if ($result) {
                echo "<div class='success'>✅ Producto de prueba creado exitosamente SIN proveedor!</div>";
                
                // Mostrar el producto creado
                echo "<h3>🎯 Producto creado:</h3>";
                $lastProduct = $conn->query("SELECT * FROM productos ORDER BY id_productos DESC LIMIT 1");
                if ($lastProduct && $row = $lastProduct->fetch_assoc()) {
                    echo "<table>";
                    echo "<tr><th>Campo</th><th>Valor</th></tr>";
                    foreach ($row as $campo => $valor) {
                        echo "<tr><td><strong>$campo</strong></td><td>" . htmlspecialchars($valor ?? 'NULL') . "</td></tr>";
                    }
                    echo "</table>";
                }
                
            } else {
                echo "<div class='error'>❌ No se pudo crear el producto de prueba</div>";
            }
        } catch (Exception $e) {
            echo "<div class='error'>❌ Error al crear producto de prueba: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error de conexión: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "<h2>🎮 Acciones Disponibles:</h2>";
echo "<p>";
if (!isset($_GET['fix'])) {
    echo "<a href='?fix=1' class='btn'>🛠️ Aplicar Corrección a la Tabla</a> ";
}
if (!isset($_GET['test'])) {
    echo "<a href='?test=1' class='btn'>🧪 Probar Creación sin Proveedor</a> ";
}
echo "<a href='?' class='btn'>🔄 Recargar Estado</a> ";
echo "<a href='app/controllers/ProductController.php?accion=create' class='btn'>➕ Crear Producto Real</a>";
echo "</p>";

echo "<h2>📝 Resumen de Cambios Realizados:</h2>";
echo "<div class='info'>";
echo "<h3>✅ Modificaciones en el Código:</h3>";
echo "<ul>";
echo "<li>🔧 <strong>ProductController.php:</strong> Removida validación obligatoria de proveedor</li>";
echo "<li>🎨 <strong>create.php y edit.php:</strong> Campo proveedor marcado como opcional</li>";
echo "<li>🔍 <strong>Product.php:</strong> Consulta cambiada de JOIN a LEFT JOIN para proveedores</li>";
echo "<li>🛡️ <strong>Manejo de datos:</strong> Conversión correcta de string vacío a NULL</li>";
echo "</ul>";

echo "<h3>🗃️ Modificación en Base de Datos:</h3>";
echo "<ul>";
echo "<li>📊 <strong>Tabla productos:</strong> Columna 'id_proveedores' ahora permite NULL</li>";
echo "</ul>";
echo "</div>";

echo "</div>";
echo "</body></html>";
?>