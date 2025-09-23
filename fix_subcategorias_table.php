<?php
// Script para verificar y corregir la tabla subcategorias
require_once 'config/db.php';

echo "<h2>Verificación y Corrección de la Tabla Subcategorias</h2>";

// Verificar si la tabla existe
$check_table = "SHOW TABLES LIKE 'subcategorias'";
$result = $conn->query($check_table);

if ($result->num_rows == 0) {
    echo "<p style='color: red;'>❌ La tabla 'subcategorias' no existe.</p>";
    echo "<p>Creando la tabla...</p>";
    
    $create_table = "
    CREATE TABLE subcategorias (
        id_subcategoria INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        nombre VARCHAR(45),
        descripcion VARCHAR(45),
        fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
        id_categoria INT NOT NULL,
        FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
    )";
    
    if ($conn->query($create_table)) {
        echo "<p style='color: green;'>✅ Tabla 'subcategorias' creada correctamente.</p>";
    } else {
        echo "<p style='color: red;'>❌ Error al crear la tabla: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color: green;'>✅ La tabla 'subcategorias' existe.</p>";
    
    // Verificar estructura de la tabla
    echo "<h3>Estructura actual de la tabla:</h3>";
    $describe = "DESCRIBE subcategorias";
    $result = $conn->query($describe);
    
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Por defecto</th><th>Extra</th></tr>";
        
        $has_fecha_creacion = false;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
            
            if ($row['Field'] == 'fecha_creacion') {
                $has_fecha_creacion = true;
            }
        }
        echo "</table>";
        
        // Verificar si falta la columna fecha_creacion
        if (!$has_fecha_creacion) {
            echo "<p style='color: orange;'>⚠️ La columna 'fecha_creacion' no existe.</p>";
            echo "<p>Agregando la columna...</p>";
            
            $add_column = "ALTER TABLE subcategorias ADD COLUMN fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP";
            if ($conn->query($add_column)) {
                echo "<p style='color: green;'>✅ Columna 'fecha_creacion' agregada correctamente.</p>";
            } else {
                echo "<p style='color: red;'>❌ Error al agregar la columna: " . $conn->error . "</p>";
            }
        } else {
            echo "<p style='color: green;'>✅ La columna 'fecha_creacion' existe.</p>";
        }
    }
}

// Verificar datos existentes
echo "<h3>Datos existentes en la tabla:</h3>";
$select_data = "SELECT COUNT(*) as total FROM subcategorias";
$result = $conn->query($select_data);

if ($result) {
    $row = $result->fetch_assoc();
    echo "<p>Total de subcategorías: <strong>" . $row['total'] . "</strong></p>";
    
    if ($row['total'] > 0) {
        echo "<h4>Primeras 5 subcategorías:</h4>";
        $select_sample = "SELECT * FROM subcategorias LIMIT 5";
        $result = $conn->query($select_sample);
        
        if ($result && $result->num_rows > 0) {
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
            echo "<tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Fecha Creación</th><th>ID Categoría</th></tr>";
            
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id_subcategoria'] . "</td>";
                echo "<td>" . $row['nombre'] . "</td>";
                echo "<td>" . $row['descripcion'] . "</td>";
                echo "<td>" . (isset($row['fecha_creacion']) ? $row['fecha_creacion'] : 'N/A') . "</td>";
                echo "<td>" . $row['id_categoria'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    }
} else {
    echo "<p style='color: red;'>❌ Error al consultar datos: " . $conn->error . "</p>";
}

echo "<hr>";
echo "<p><strong>Resumen:</strong></p>";
echo "<ul>";
echo "<li>Si la tabla no existía, se ha creado con la estructura correcta.</li>";
echo "<li>Si faltaba la columna fecha_creacion, se ha agregado.</li>";
echo "<li>El sistema de subcategorías debería funcionar correctamente ahora.</li>";
echo "</ul>";

echo "<p><a href='app/controllers/SubcategoryController.php?accion=index'>🔗 Ir al sistema de subcategorías</a></p>";
?>