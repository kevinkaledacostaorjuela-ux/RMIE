<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Test Enlaces - Dashboard RMIE</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { border: 1px solid #ddd; margin: 10px 0; padding: 15px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        .link-test { margin: 5px 0; padding: 10px; background: #f8f9fa; border-left: 4px solid #007bff; }
        button, .btn { background: #007bff; color: white; border: none; padding: 8px 15px; margin: 3px; cursor: pointer; text-decoration: none; display: inline-block; }
        button:hover, .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>Test de Enlaces del Dashboard - RMIE</h1>
    
    <div class="test-section">
        <h2>Problema Identificado</h2>
        <p class="error">❌ <strong>URL incorrecta:</strong> <code>http://localhost/RMIE/controllers/ClientController.php</code></p>
        <p class="success">✅ <strong>URL correcta:</strong> <code>http://localhost/RMIE/app/controllers/ClientController.php</code></p>
        <p class="info">🔍 <strong>Causa:</strong> Enlaces relativos incorrectos en dashboard.php</p>
    </div>
    
    <div class="test-section">
        <h2>Enlaces Corregidos del Dashboard</h2>
        <p>Todos los enlaces ahora usan rutas absolutas:</p>
        
        <div class="link-test">
            <strong>Clientes:</strong>
            <a href="/RMIE/app/controllers/ClientController.php?accion=index" class="btn">Ir a Clientes</a>
        </div>
        
        <div class="link-test">
            <strong>Categorías:</strong>
            <a href="/RMIE/app/controllers/CategoryController.php?accion=index" class="btn">Ir a Categorías</a>
        </div>
        
        <div class="link-test">
            <strong>Subcategorías:</strong>
            <a href="/RMIE/app/controllers/SubcategoryController.php?accion=index" class="btn">Ir a Subcategorías</a>
        </div>
        
        <div class="link-test">
            <strong>Productos:</strong>
            <a href="/RMIE/app/controllers/ProductController.php?accion=index" class="btn">Ir a Productos</a>
        </div>
        
        <div class="link-test">
            <strong>Proveedores:</strong>
            <a href="/RMIE/app/controllers/ProviderController.php?accion=index" class="btn">Ir a Proveedores</a>
        </div>
        
        <div class="link-test">
            <strong>Usuarios:</strong>
            <a href="/RMIE/app/controllers/UserController.php?accion=index" class="btn">Ir a Usuarios</a>
        </div>
        
        <div class="link-test">
            <strong>Reportes:</strong>
            <a href="/RMIE/app/controllers/ReportController.php?action=index" class="btn">Ir a Reportes</a>
        </div>
    </div>
    
    <div class="test-section">
        <h2>Test Específico - Editar Cliente</h2>
        <p class="info">Probando la edición de cliente ID 2:</p>
        <a href="/RMIE/app/controllers/ClientController.php?accion=edit&id=2" class="btn">
            ✏️ Editar Cliente ID 2
        </a>
        <p><small>Nota: Si el cliente con ID 2 no existe, debería mostrar un mensaje de error apropiado.</small></p>
    </div>
    
    <div class="test-section">
        <h2>Navegación del Sistema</h2>
        <a href="/RMIE/app/views/dashboard.php" class="btn">🏠 Ir al Dashboard Principal</a>
        <a href="/RMIE/app/controllers/ClientController.php?accion=index" class="btn">👥 Lista de Clientes</a>
        <a href="/RMIE/test_modulos.php" class="btn">🧪 Test General de Módulos</a>
    </div>
    
    <div class="test-section">
        <h2>Estado de Sesión</h2>
        <?php if (isset($_SESSION['user'])): ?>
            <p class="success">✅ Usuario logueado: <?= htmlspecialchars($_SESSION['user']['nombre'] ?? 'Usuario') ?></p>
        <?php else: ?>
            <p class="error">❌ No hay usuario logueado</p>
            <p class="info">Algunos módulos pueden requerir login. <a href="/RMIE/index.php" class="btn">Hacer Login</a></p>
        <?php endif; ?>
    </div>

</body>
</html>