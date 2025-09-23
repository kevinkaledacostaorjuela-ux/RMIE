<?php
require_once 'config/db.php';

echo "<h2>Diagnóstico de la tabla subcategorias</h2>";

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
echo "<p style='color: green;'>✅ Conexión a la base de datos OK</p>";

// Verificar si la tabla existe
$tables = $conn->query("SHOW TABLES LIKE 'subcategorias'");
if ($tables->num_rows == 0) {
    echo "<p style='color: red;'>❌ La tabla 'subcategorias' NO existe</p>";
    echo "<p>Ejecuta este SQL para crearla:</p>";
    echo "<pre style='background: #f0f0f0; padding: 10px;'>";
    echo "CREATE TABLE subcategorias (
    id_subcategoria INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45),
    descripcion VARCHAR(45),
    id_categoria INT NOT NULL,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
);</pre>";
} else {
    echo "<p style='color: green;'>✅ La tabla 'subcategorias' existe</p>";
    
    // Mostrar estructura
    echo "<h3>Estructura de la tabla:</h3>";
    $columns = $conn->query("DESCRIBE subcategorias");
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Por defecto</th></tr>";
    
    while ($row = $columns->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Contar registros
    $count = $conn->query("SELECT COUNT(*) as total FROM subcategorias");
    $total = $count->fetch_assoc()['total'];
    echo "<p>Total de registros: <strong>$total</strong></p>";
    
    // Test de inserción simple
    echo "<h3>Test de inserción:</h3>";
    
    // Verificar si existe alguna categoría
    $cat_check = $conn->query("SELECT id_categoria FROM categorias LIMIT 1");
    if ($cat_check->num_rows > 0) {
        $cat_row = $cat_check->fetch_assoc();
        $test_id_categoria = $cat_row['id_categoria'];
        echo "<p>Usando categoría ID: $test_id_categoria para el test</p>";
        
        // Intentar inserción de prueba
        $test_nombre = "Test_" . time();
        $test_descripcion = "Desc test";
        
        $stmt = $conn->prepare("INSERT INTO subcategorias (nombre, descripcion, id_categoria) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $test_nombre, $test_descripcion, $test_id_categoria);
        
        if ($stmt->execute()) {
            echo "<p style='color: green;'>✅ Test de inserción OK</p>";
            $test_id = $conn->insert_id;
            
            // Eliminar el registro de prueba
            $conn->query("DELETE FROM subcategorias WHERE id_subcategoria = $test_id");
            echo "<p>Test limpio - registro eliminado</p>";
        } else {
            echo "<p style='color: red;'>❌ Error en test de inserción: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p style='color: orange;'>⚠️ No hay categorías en la base de datos. Crea al menos una categoría primero.</p>";
    }
}

echo "<hr>";
echo "<p><strong>Sistema listo para usar:</strong></p>";
echo "<p><a href='app/controllers/SubcategoryController.php?accion=index'>🔗 Ir al sistema de subcategorías</a></p>";
?>