<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Test Módulos RMIE</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { border: 1px solid #ddd; margin: 10px 0; padding: 15px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        button { background: #007bff; color: white; border: none; padding: 10px 15px; margin: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>Test de Módulos RMIE - Estado General</h1>
    
    <div class="test-section">
        <h2>1. Módulo de Reportes</h2>
        <p class="info">Verificar eliminación de reportes</p>
        <button onclick="window.location.href='app/controllers/ReportController.php?action=index'">
            Ir a Reportes
        </button>
        <button onclick="testReportes()">
            Test Eliminación
        </button>
    </div>
    
    <div class="test-section">
        <h2>2. Módulo de Proveedores</h2>
        <p class="info">Verificar que las propiedades estén correctas</p>
        <button onclick="window.location.href='app/controllers/ProviderController.php?accion=index'">
            Ir a Proveedores
        </button>
    </div>
    
    <div class="test-section">
        <h2>3. Módulo de Categorías</h2>
        <p class="success">✅ Funcionando correctamente</p>
        <button onclick="window.location.href='app/controllers/CategoryController.php?accion=index'">
            Ir a Categorías
        </button>
    </div>
    
    <div class="test-section">
        <h2>4. Módulo de Subcategorías</h2>
        <p class="success">✅ Funcionando correctamente</p>
        <button onclick="window.location.href='app/controllers/SubcategoryController.php?accion=index'">
            Ir a Subcategorías
        </button>
    </div>
    
    <div class="test-section">
        <h2>5. Módulo de Productos</h2>
        <p class="success">✅ Funcionando correctamente</p>
        <button onclick="window.location.href='app/controllers/ProductController.php?accion=index'">
            Ir a Productos
        </button>
    </div>
    
    <div class="test-section">
        <h2>Estado de Sesión</h2>
        <?php if (isset($_SESSION['user'])): ?>
            <p class="success">✅ Usuario logueado: <?= htmlspecialchars($_SESSION['user']['nombre'] ?? 'Usuario') ?></p>
        <?php else: ?>
            <p class="error">❌ No hay usuario logueado</p>
            <button onclick="window.location.href='index.php'">Login</button>
        <?php endif; ?>
    </div>

    <div class="test-section">
        <h2>Dashboard Principal</h2>
        <button onclick="window.location.href='app/views/dashboard.php'">
            Ir al Dashboard
        </button>
    </div>

    <script>
        function testReportes() {
            if (confirm('¿Quieres ir al test de eliminación de reportes?')) {
                window.location.href = 'test_eliminacion_reportes.php';
            }
        }
    </script>
</body>
</html>