<?php
session_start();
require_once 'config/db.php';

echo "<!DOCTYPE html><html><head><title>Test Clientes Mejorados - RMIE</title>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
    .container { background: white; padding: 20px; border-radius: 15px; margin: 20px auto; max-width: 1200px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
    .success { color: green; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
    .error { color: red; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0; }
    .info { color: blue; padding: 10px; background: #d1ecf1; border-radius: 5px; margin: 10px 0; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background-color: #f8f9fa; font-weight: bold; }
    .btn { background: #007bff; color: white; padding: 8px 16px; text-decoration: none; border-radius: 5px; margin: 2px; display: inline-block; }
    .btn:hover { background: #0056b3; color: white; }
    .btn-success { background: #28a745; }
    .btn-success:hover { background: #1e7e34; }
    .feature-list { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 20px 0; }
    .feature-item { background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #007bff; }
</style>";
echo "</head><body>";

echo "<div class='container'>";
echo "<h1>🎉 Test de Clientes Mejorados - Sistema RMIE</h1>";

// Verificar estado de la sesión
if (isset($_SESSION['user'])) {
    echo "<div class='success'>✅ Usuario logueado: " . htmlspecialchars($_SESSION['user']['nombre'] ?? 'Usuario') . "</div>";
} else {
    echo "<div class='error'>❌ No hay usuario logueado - <a href='index.php' class='btn'>Login</a></div>";
}

echo "<h2>🚀 Nuevas Funcionalidades Implementadas</h2>";
echo "<div class='feature-list'>";

echo "<div class='feature-item'>";
echo "<h3>📝 Campos Adicionales</h3>";
echo "<ul>";
echo "<li>📍 Dirección completa</li>";
echo "<li>🏙️ Ciudad</li>";
echo "<li>🎂 Fecha de nacimiento</li>";
echo "<li>❤️ Preferencias del cliente</li>";
echo "</ul>";
echo "</div>";

echo "<div class='feature-item'>";
echo "<h3>⚡ Funciones Inteligentes</h3>";
echo "<ul>";
echo "<li>💾 Guardado automático</li>";
echo "<li>👁️ Vista previa en tiempo real</li>";
echo "<li>✅ Validación instantánea</li>";
echo "<li>📊 Contador de caracteres</li>";
echo "<li>🎯 Cálculo automático de edad</li>";
echo "</ul>";
echo "</div>";

echo "<div class='feature-item'>";
echo "<h3>🎨 Mejoras de UI/UX</h3>";
echo "<ul>";
echo "<li>🌈 Animaciones suaves</li>";
echo "<li>📱 Diseño responsive</li>";
echo "<li>⌨️ Atajos de teclado (Ctrl+S)</li>";
echo "<li>🔔 Notificaciones en tiempo real</li>";
echo "<li>🚨 Alertas antes de salir sin guardar</li>";
echo "</ul>";
echo "</div>";

echo "</div>";

echo "<h2>👥 Clientes Disponibles para Editar</h2>";

// Listar clientes
try {
    $sql = "SELECT id_clientes, nombre, correo, cel_cliente, estado, ciudad, fecha_nacimiento FROM clientes ORDER BY id_clientes DESC LIMIT 10";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Teléfono</th><th>Ciudad</th><th>Edad</th><th>Estado</th><th>Acciones</th></tr>";
        
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><strong>#" . $row['id_clientes'] . "</strong></td>";
            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($row['correo']) . "</td>";
            echo "<td>" . htmlspecialchars($row['cel_cliente'] ?: 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['ciudad'] ?: 'No especificada') . "</td>";
            
            // Calcular edad
            $edad = 'N/A';
            if ($row['fecha_nacimiento']) {
                $hoy = new DateTime();
                $nacimiento = new DateTime($row['fecha_nacimiento']);
                $edad = $hoy->diff($nacimiento)->y . ' años';
            }
            echo "<td>" . $edad . "</td>";
            
            $estadoClass = $row['estado'] === 'activo' ? 'success' : 'error';
            echo "<td><span class='" . $estadoClass . "'>" . ucfirst($row['estado']) . "</span></td>";
            echo "<td>";
            
            // Enlace para editar con las mejoras
            echo "<a href='/RMIE/app/controllers/ClientController.php?accion=edit&id=" . $row['id_clientes'] . "' class='btn btn-success'>✏️ Editar (Mejorado)</a>";
            
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='info'>ℹ️ No hay clientes disponibles. <a href='/RMIE/app/controllers/ClientController.php?accion=create' class='btn'>Crear nuevo cliente</a></div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Error al cargar clientes: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "<h2>🛠️ Herramientas de Desarrollo</h2>";
echo "<div class='info'>";
echo "<h3>📋 Instrucciones para probar:</h3>";
echo "<ol>";
echo "<li>📄 <strong>Ejecutar SQL:</strong> Ejecuta el archivo <code>update_clientes_table.sql</code> en tu base de datos para agregar las nuevas columnas</li>";
echo "<li>✏️ <strong>Editar Cliente:</strong> Haz clic en 'Editar (Mejorado)' en cualquier cliente de la tabla</li>";
echo "<li>🔍 <strong>Probar Funcionalidades:</strong>";
echo "<ul>";
echo "<li>Escribe en los campos y observa la vista previa actualizarse</li>";
echo "<li>Mira los contadores de caracteres</li>";
echo "<li>Prueba las validaciones (email inválido, etc.)</li>";
echo "<li>Intenta salir sin guardar para ver la alerta</li>";
echo "<li>Usa Ctrl+S para guardar rápidamente</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<h2>🔗 Enlaces de Navegación</h2>";
echo "<p>";
echo "<a href='/RMIE/app/controllers/ClientController.php?accion=index' class='btn'>📋 Módulo de Clientes</a> ";
echo "<a href='/RMIE/app/views/dashboard.php' class='btn'>🏠 Dashboard</a> ";
echo "<a href='test_clientes_mejorados.php' class='btn'>🔄 Recargar Test</a>";
echo "</p>";

echo "<div class='success'>";
echo "<h3>🎯 Resumen de Mejoras Implementadas:</h3>";
echo "<p><strong>✅ Formulario completamente mejorado</strong> con campos adicionales, validaciones en tiempo real, guardado automático y una experiencia de usuario moderna.</p>";
echo "<p><strong>✅ Modelo y controlador actualizados</strong> para manejar los nuevos campos de dirección, ciudad, fecha de nacimiento y preferencias.</p>";
echo "<p><strong>✅ JavaScript avanzado</strong> con funciones de validación, vista previa, contadores y notificaciones.</p>";
echo "<p><strong>✅ CSS moderno</strong> con animaciones, estados de validación y diseño responsive.</p>";
echo "</div>";

echo "</div>";
echo "</body></html>";
?>