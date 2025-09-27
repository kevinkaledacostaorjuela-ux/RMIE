<?php
// test_acceso_web.php - Prueba de acceso web completa
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba de Acceso - Módulo Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h2>🧪 Prueba de Acceso al Módulo Clientes</h2>
            <div class="card mt-4">
                <div class="card-body">
                    <h5>Enlaces de Prueba:</h5>
                    
                    <!-- Prueba 1: Acceso directo sin sesión -->
                    <div class="mb-3">
                        <h6>1. Acceso Directo (sin sesión):</h6>
                        <a href="app/controllers/ClientController.php?accion=index" class="btn btn-primary" target="_blank">
                            Ir a Clientes (sin sesión)
                        </a>
                    </div>
                    
                    <!-- Prueba 2: Acceso con sesión automática -->
                    <div class="mb-3">
                        <h6>2. Acceso con Sesión Automática:</h6>
                        <a href="acceso_directo_clientes.php" class="btn btn-success" target="_blank">
                            Ir a Clientes (con sesión)
                        </a>
                    </div>
                    
                    <!-- Prueba 3: Dashboard -->
                    <div class="mb-3">
                        <h6>3. Dashboard:</h6>
                        <a href="app/views/dashboard.php" class="btn btn-warning" target="_blank">
                            Ir al Dashboard
                        </a>
                    </div>
                    
                    <!-- Prueba 4: Login -->
                    <div class="mb-3">
                        <h6>4. Página de Login:</h6>
                        <a href="index.php" class="btn btn-info" target="_blank">
                            Ir al Login
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Estado del sistema -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5>Estado del Sistema:</h5>
                    <?php
                    // Verificar archivos clave
                    $archivos_clave = [
                        'app/controllers/ClientController.php' => 'ClientController',
                        'app/models/Client.php' => 'Modelo Client',
                        'app/views/clientes/index.php' => 'Vista de Clientes',
                        'config/db.php' => 'Configuración DB'
                    ];
                    
                    echo "<ul>";
                    foreach ($archivos_clave as $archivo => $descripcion) {
                        $estado = file_exists($archivo) ? "✅" : "❌";
                        echo "<li>$estado $descripcion ($archivo)</li>";
                    }
                    echo "</ul>";
                    
                    // Verificar conexión DB
                    try {
                        require_once 'config/db.php';
                        global $conn;
                        if (!$conn->connect_error) {
                            echo "<p>✅ Base de datos: Conectada correctamente</p>";
                        } else {
                            echo "<p>❌ Base de datos: Error - " . $conn->connect_error . "</p>";
                        }
                    } catch (Exception $e) {
                        echo "<p>❌ Base de datos: Excepción - " . $e->getMessage() . "</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Agregar información de debugging
console.log('Página de prueba cargada');
console.log('URL actual:', window.location.href);
console.log('Timestamp:', new Date());
</script>

</body>
</html>