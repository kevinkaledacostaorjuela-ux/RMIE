<?php
// Iniciar sesión antes de cualquier output
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['email'] = 'test@test.com';
$_SESSION['rol'] = 'admin';
$_SESSION['user'] = 'admin@test.com'; // Requerido por la vista

require_once __DIR__ . '/config/db.php';

echo "=== TEST DE CARGA DE PÁGINA DE ALERTAS ===\n\n";

// 1. Verificar que el controlador carga
try {
    require_once __DIR__ . '/app/controllers/AlertController.php';
    echo "✓ AlertController cargado\n";
} catch (Exception $e) {
    echo "✗ Error cargando AlertController: " . $e->getMessage() . "\n";
    exit;
}

// 2. Instanciar el controlador
try {
    $controller = new AlertController();
    echo "✓ AlertController instanciado\n";
} catch (Exception $e) {
    echo "✗ Error instanciando AlertController: " . $e->getMessage() . "\n";
    exit;
}

// 3. Simular GET request sin filtros
$_GET = ['accion' => 'index'];

// 4. Simular lo que hace el controlador (sin incluir la vista)
echo "\n4. PROBANDO LÓGICA DEL CONTROLADOR...\n";
ob_start();

$filtros = [
    'tipo' => $_GET['tipo'] ?? '',
    'prioridad' => $_GET['prioridad'] ?? '',
    'estado' => $_GET['estado'] ?? '',
    'fecha' => $_GET['fecha'] ?? '',
    'producto' => $_GET['producto'] ?? '',
    'nombre_producto' => $_GET['nombre_producto'] ?? '',
    'cantidad_min' => $_GET['cantidad_min'] ?? '',
    'cantidad_max' => $_GET['cantidad_max'] ?? '',
    'fecha_desde' => $_GET['fecha_desde'] ?? '',
    'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
];

// Obtener alertas
require_once __DIR__ . '/app/models/Alert.php';
try {
    $alertas = Alert::getFiltered($conn, $filtros);
    echo "✓ Alertas obtenidas: " . count($alertas) . " registros\n";
} catch (Exception $e) {
    echo "✗ Error obteniendo alertas: " . $e->getMessage() . "\n";
    exit;
}

// Obtener productos
require_once __DIR__ . '/app/models/Product.php';
try {
    $productos = Product::getAll($conn);
    echo "✓ Productos obtenidos: " . count($productos) . " registros\n";
} catch (Exception $e) {
    echo "✗ Error obteniendo productos: " . $e->getMessage() . "\n";
    exit;
}

// Obtener tipos disponibles
$tipos_disponibles = array();
$sql_tipos = "SELECT DISTINCT tipo_alerta FROM alertas WHERE tipo_alerta IS NOT NULL AND tipo_alerta != ''";
$result = $conn->query($sql_tipos);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tipos_disponibles[] = $row['tipo_alerta'];
    }
}
echo "✓ Tipos disponibles: " . implode(", ", $tipos_disponibles ?: ['Ninguno']) . "\n";

// Valores fijos para prioridad y estado
$prioridades_disponibles = array('Alta', 'Media', 'Baja');
$estados_disponibles = array('Activo', 'Vencida', 'Crítica', 'Próxima', 'Normal');
echo "✓ Prioridades: " . implode(", ", $prioridades_disponibles) . "\n";
echo "✓ Estados: " . implode(", ", $estados_disponibles) . "\n";

// Calcular estadísticas
$total_alertas = count($alertas);
$alertas_proximas = 0;
$alertas_vencidas = 0;
$fecha_actual = date('Y-m-d');

foreach ($alertas as $alerta) {
    if ($alerta['fecha_caducidad'] < $fecha_actual) {
        $alertas_vencidas++;
    } elseif ($alerta['fecha_caducidad'] <= date('Y-m-d', strtotime('+30 days'))) {
        $alertas_proximas++;
    }
}

$estadisticas = [
    'total' => $total_alertas,
    'proximas' => $alertas_proximas,
    'vencidas' => $alertas_vencidas
];
echo "✓ Estadísticas calculadas\n";

ob_end_clean();

echo "\n" . str_repeat("=", 50) . "\n";
echo "✓ LA PÁGINA DE ALERTAS DEBERÍA CARGAR SIN ERRORES\n";
echo str_repeat("=", 50) . "\n";
