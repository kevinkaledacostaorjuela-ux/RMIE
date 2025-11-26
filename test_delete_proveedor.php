<?php
/**
 * Script de prueba para eliminar proveedor
 * URL: http://localhost/RMIE/test_delete_proveedor.php?id=19
 */

session_start();

echo "<h1>🧪 Test de Eliminación de Proveedor</h1>";
echo "<style>body { font-family: monospace; background: #1a1a2e; color: #0f0; padding: 20px; }</style>";

// Verificar sesión
echo "<h2>1. Verificación de Sesión:</h2>";
if (isset($_SESSION['user'])) {
    echo "✅ Usuario: " . htmlspecialchars($_SESSION['user']) . "<br>";
    echo "✅ Rol: " . htmlspecialchars($_SESSION['rol']) . "<br>";
    
    if ($_SESSION['rol'] === 'coordinador') {
        echo "<p style='color: red;'>❌ ERROR: Los coordinadores NO pueden eliminar registros</p>";
        echo "<p>Necesitas iniciar sesión con un usuario de rol 'admin'</p>";
        exit();
    }
} else {
    echo "<p style='color: red;'>❌ No hay sesión activa</p>";
    echo "<p><a href='/RMIE/index.php'>Iniciar sesión</a></p>";
    exit();
}

// Verificar ID
echo "<h2>2. Verificación de ID:</h2>";
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<p style='color: red;'>❌ No se proporcionó ID en la URL</p>";
    echo "<p>Usa: ?id=19</p>";
    exit();
}
echo "✅ ID a eliminar: " . htmlspecialchars($id) . "<br>";

// Conectar a la base de datos
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/app/models/Provider.php';

echo "<h2>3. Verificación de Base de Datos:</h2>";
if ($conn->ping()) {
    echo "✅ Conexión a MySQL exitosa<br>";
} else {
    echo "<p style='color: red;'>❌ Error de conexión: " . $conn->error . "</p>";
    exit();
}

// Buscar el proveedor
echo "<h2>4. Búsqueda del Proveedor:</h2>";
$proveedor = Provider::getById($conn, $id);
if ($proveedor) {
    echo "✅ Proveedor encontrado:<br>";
    echo "   - ID: " . $proveedor->id_proveedores . "<br>";
    echo "   - Nombre: " . htmlspecialchars($proveedor->nombre_distribuidor) . "<br>";
    echo "   - Email: " . htmlspecialchars($proveedor->correo) . "<br>";
} else {
    echo "<p style='color: red;'>❌ No se encontró el proveedor con ID: $id</p>";
    exit();
}

// Verificar productos asociados
echo "<h2>5. Verificación de Dependencias:</h2>";
$productos = Provider::getProductosByProveedor($conn, $id);
if (!empty($productos)) {
    echo "<p style='color: orange;'>⚠️ ADVERTENCIA: Este proveedor tiene " . count($productos) . " producto(s) asociado(s)</p>";
    echo "<p>Productos:</p><ul>";
    foreach ($productos as $prod) {
        echo "<li>" . htmlspecialchars($prod->nombre ?? 'Sin nombre') . "</li>";
    }
    echo "</ul>";
    echo "<p style='color: orange;'>La eliminación puede fallar si hay restricciones de clave foránea</p>";
} else {
    echo "✅ No hay productos asociados<br>";
}

// Intentar eliminar (solo si se confirma)
if (isset($_GET['confirmar']) && $_GET['confirmar'] == 'si') {
    echo "<h2>6. Eliminando Proveedor...</h2>";
    
    try {
        $resultado = Provider::delete($conn, $id);
        
        if ($resultado) {
            echo "<p style='color: lime; font-size: 20px;'>✅ ¡PROVEEDOR ELIMINADO EXITOSAMENTE!</p>";
            echo "<p><a href='/RMIE/app/controllers/ProviderController.php?accion=index' style='color: cyan;'>Volver a la lista de proveedores</a></p>";
        } else {
            echo "<p style='color: red;'>❌ Error al eliminar: La consulta SQL no se ejecutó correctamente</p>";
            echo "<p>Error MySQL: " . $conn->error . "</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Excepción: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<h2>6. Confirmación:</h2>";
    echo "<p>¿Realmente deseas eliminar este proveedor?</p>";
    echo "<p>
        <a href='?id=$id&confirmar=si' style='background: red; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>
            🗑️ SÍ, ELIMINAR
        </a>
        &nbsp;&nbsp;
        <a href='/RMIE/app/controllers/ProviderController.php?accion=index' style='background: gray; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>
            ❌ CANCELAR
        </a>
    </p>";
}
?>
