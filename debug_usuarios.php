<?php
session_start();
require_once 'config/db.php';

echo "<!DOCTYPE html><html><head><title>Debug Usuarios - RMIE</title>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .error{color:red;} .success{color:green;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:8px;}</style>";
echo "</head><body>";

echo "<h1>Debug - Módulo de Usuarios</h1>";

echo "<h2>Problemas identificados:</h2>";
echo "<div class='error'>";
echo "<p>❌ <strong>Error:</strong> Undefined property: User::\$id_usuario</p>";
echo "<p>🔍 <strong>Causa:</strong> El modelo User usa la propiedad \$num_doc, no \$id_usuario</p>";
echo "<p>🔍 <strong>También afectadas:</strong> \$nombre (debería ser \$nombres), \$email (debería ser \$correo), etc.</p>";
echo "</div>";

echo "<h2>Propiedades correctas del modelo User:</h2>";
echo "<div class='success'>";
echo "<ul>";
echo "<li>✅ \$num_doc (identificador único)</li>";
echo "<li>✅ \$tipo_doc (tipo de documento)</li>";
echo "<li>✅ \$nombres (nombre completo)</li>";
echo "<li>✅ \$apellidos</li>";
echo "<li>✅ \$correo (no \$email)</li>";
echo "<li>✅ \$contrasena</li>";
echo "<li>✅ \$num_cel (número celular)</li>";
echo "<li>✅ \$rol</li>";
echo "<li>✅ \$fecha_creacion (no \$fecha_registro)</li>";
echo "</ul>";
echo "</div>";

// Mostrar estructura real de la tabla
echo "<h2>Estructura real de la tabla usuarios:</h2>";
$sql = "DESCRIBE usuarios";
$result = $conn->query($sql);

if ($result) {
    echo "<table>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td><strong>" . $row['Field'] . "</strong></td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Mostrar algunos usuarios de ejemplo
echo "<h2>Usuarios existentes (primeros 3):</h2>";
$sql = "SELECT * FROM usuarios LIMIT 3";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<table>";
    $first_row = $result->fetch_assoc();
    
    // Encabezados
    echo "<tr>";
    foreach($first_row as $key => $value) {
        echo "<th>$key</th>";
    }
    echo "</tr>";
    
    // Primera fila
    echo "<tr>";
    foreach($first_row as $value) {
        echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
    }
    echo "</tr>";
    
    // Resto de filas
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach($row as $value) {
            echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

echo "<h2>Recomendaciones:</h2>";
echo "<div class='success'>";
echo "<p>1. ✅ Corregir todas las referencias \$id_usuario → \$num_doc</p>";
echo "<p>2. ✅ Corregir todas las referencias \$nombre → \$nombres</p>";
echo "<p>3. ✅ Corregir todas las referencias \$email → \$correo</p>";
echo "<p>4. ✅ Revisar propiedades inexistentes como \$estado, \$fecha_registro, etc.</p>";
echo "<p>5. ✅ Actualizar el modelo User si hay campos faltantes en la base de datos</p>";
echo "</div>";

echo "<hr>";
echo "<h2>Enlaces de navegación:</h2>";
echo "<p><a href='app/controllers/UserController.php?accion=index'>Intentar ir a módulo de usuarios</a></p>";
echo "<p><a href='test_modulos.php'>Test general de módulos</a></p>";
echo "<p><a href='debug_usuarios.php'>Recargar debug</a></p>";

echo "</body></html>";
?>