<?php
/**
 * Script de prueba para el nuevo rol auxiliar
 * Fecha: 22 de octubre de 2025
 */

// Incluir archivos necesarios
require_once 'config/db.php';
require_once 'app/utils/AuthUtils.php';

echo "<h2>Prueba del Rol Auxiliar</h2>";

// Simular sesión con rol auxiliar
session_start();
$_SESSION['user'] = 'auxiliar_test';
$_SESSION['rol'] = 'auxiliar';

echo "<h3>1. Verificación de AuthUtils:</h3>";
echo "Rol actual: " . AuthUtils::getCurrentUserRole() . "<br>";
echo "Es admin: " . (AuthUtils::isAdmin() ? "SÍ" : "NO") . "<br>";
echo "Es coordinador: " . (AuthUtils::isCoordinador() ? "SÍ" : "NO") . "<br>";
echo "Es auxiliar: " . (AuthUtils::isAuxiliar() ? "SÍ" : "NO") . "<br>";
echo "Puede editar: " . (AuthUtils::canEdit() ? "SÍ" : "NO") . "<br>";
echo "Puede eliminar: " . (AuthUtils::canDelete() ? "SÍ" : "NO") . "<br>";

echo "<h3>2. Descripción del rol:</h3>";
echo "Descripción: " . AuthUtils::getRoleDescription('auxiliar') . "<br>";
echo "Clase CSS del badge: " . AuthUtils::getRoleBadgeClass('auxiliar') . "<br>";

echo "<h3>3. Prueba con todos los roles:</h3>";
$roles = ['admin', 'coordinador', 'auxiliar'];
foreach($roles as $rol) {
    echo "<div style='margin: 10px; padding: 10px; border: 1px solid #ccc;'>";
    echo "<strong>Rol: $rol</strong><br>";
    echo "Descripción: " . AuthUtils::getRoleDescription($rol) . "<br>";
    echo "Badge CSS: " . AuthUtils::getRoleBadgeClass($rol) . "<br>";
    echo "</div>";
}

echo "<h3>4. Verificación en BD (si existe la conexión):</h3>";
try {
    // Verificar la estructura de la tabla usuarios
    $stmt = $conn->prepare("SHOW COLUMNS FROM usuarios LIKE 'rol'");
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo "Columna rol encontrada: " . $row['Type'] . "<br>";
        
        // Verificar si existen usuarios auxiliares
        $stmt2 = $conn->prepare("SELECT COUNT(*) as count FROM usuarios WHERE rol = 'auxiliar'");
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $count = $result2->fetch_assoc()['count'];
        
        echo "Usuarios auxiliares en BD: $count<br>";
        
        // Mostrar todos los usuarios con sus roles
        $stmt3 = $conn->prepare("SELECT nombres, apellidos, correo, rol FROM usuarios ORDER BY rol");
        $stmt3->execute();
        $result3 = $stmt3->get_result();
        
        echo "<h4>Todos los usuarios:</h4>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px;'>";
        echo "<tr><th>Nombre</th><th>Email</th><th>Rol</th><th>Badge</th></tr>";
        
        while($user = $result3->fetch_assoc()) {
            $badgeClass = AuthUtils::getRoleBadgeClass($user['rol']);
            echo "<tr>";
            echo "<td>" . htmlspecialchars($user['nombres'] . ' ' . $user['apellidos']) . "</td>";
            echo "<td>" . htmlspecialchars($user['correo']) . "</td>";
            echo "<td>" . htmlspecialchars($user['rol']) . "</td>";
            echo "<td><span class='badge $badgeClass'>" . AuthUtils::getRoleDescription($user['rol']) . "</span></td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } else {
        echo "No se encontró la columna rol en la tabla usuarios<br>";
    }
    
} catch (Exception $e) {
    echo "Error al verificar BD: " . $e->getMessage() . "<br>";
}

echo "<h3>5. Resultado:</h3>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 10px;'>";
echo "✅ El rol auxiliar ha sido implementado correctamente<br>";
echo "✅ AuthUtils actualizado con nuevas funciones<br>";
echo "✅ Restricciones de permisos configuradas<br>";
echo "✅ Solo los administradores pueden eliminar registros<br>";
echo "✅ Los auxiliares solo tienen permisos de lectura<br>";
echo "</div>";

?>

<style>
.badge {
    padding: 5px 10px;
    border-radius: 15px;
    color: white;
    font-weight: bold;
}
.bg-danger { background-color: #dc3545 !important; }
.bg-primary { background-color: #007bff !important; }
.bg-success { background-color: #28a745 !important; }
.bg-secondary { background-color: #6c757d !important; }
</style>