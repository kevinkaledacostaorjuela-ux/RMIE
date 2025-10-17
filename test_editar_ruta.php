<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Directo - Editar Ruta 14</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .form-group { margin: 15px 0; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .result { margin: 20px 0; padding: 15px; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Test Directo - Editar Ruta ID 14</h1>
        
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        require_once 'config/db.php';
        require_once 'app/models/Route.php';
        
        // Obtener datos actuales
        $route = Route::getById($conn, 14);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = 14;
                $direccion = trim($_POST['direccion']);
                $nombre_local = trim($_POST['nombre_local']);
                $nombre_cliente = trim($_POST['nombre_cliente']);
                $id_clientes = intval($_POST['id_clientes']);
                $id_ventas = intval($_POST['id_ventas']);
                $id_reportes = $route['id_reportes']; // Mantener el existente
                
                echo "<div class='result'>";
                echo "<h3>📝 Datos que se van a actualizar:</h3>";
                echo "<ul>";
                echo "<li><strong>ID:</strong> $id</li>";
                echo "<li><strong>Dirección:</strong> $direccion</li>";
                echo "<li><strong>Local:</strong> $nombre_local</li>";
                echo "<li><strong>Cliente:</strong> $nombre_cliente</li>";
                echo "<li><strong>ID Clientes:</strong> $id_clientes</li>";
                echo "<li><strong>ID Ventas:</strong> $id_ventas</li>";
                echo "<li><strong>ID Reportes:</strong> " . ($id_reportes ?: 'NULL') . "</li>";
                echo "</ul>";
                
                $success = Route::update($conn, $id, $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_ventas, $id_reportes);
                
                if ($success) {
                    echo "<div class='success'>✅ ¡Ruta actualizada exitosamente!</div>";
                    
                    // Recargar datos actualizados
                    $route = Route::getById($conn, 14);
                    echo "<h4>📊 Datos después de la actualización:</h4>";
                    echo "<pre>" . print_r($route, true) . "</pre>";
                    
                } else {
                    echo "<div class='error'>❌ Error: No se pudo actualizar la ruta</div>";
                }
                echo "</div>";
                
            } catch (Exception $e) {
                echo "<div class='result error'>❌ Error: " . $e->getMessage() . "</div>";
            }
        }
        ?>
        
        <?php if ($route): ?>
            <div class="result">
                <h3>📋 Datos actuales de la ruta ID 14:</h3>
                <ul>
                    <li><strong>Dirección:</strong> <?= htmlspecialchars($route['direccion']) ?></li>
                    <li><strong>Local:</strong> <?= htmlspecialchars($route['nombre_local']) ?></li>
                    <li><strong>Cliente:</strong> <?= htmlspecialchars($route['nombre_cliente']) ?></li>
                    <li><strong>ID Clientes:</strong> <?= $route['id_clientes'] ?></li>
                    <li><strong>ID Ventas:</strong> <?= $route['id_ventas'] ?></li>
                    <li><strong>ID Reportes:</strong> <?= $route['id_reportes'] ?: 'NULL' ?></li>
                </ul>
            </div>
            
            <form method="POST">
                <h3>✏️ Editar Ruta:</h3>
                
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <textarea name="direccion" id="direccion" rows="3" required><?= htmlspecialchars($route['direccion']) ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="nombre_local">Nombre del Local:</label>
                    <input type="text" name="nombre_local" id="nombre_local" value="<?= htmlspecialchars($route['nombre_local']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="nombre_cliente">Nombre del Cliente:</label>
                    <input type="text" name="nombre_cliente" id="nombre_cliente" value="<?= htmlspecialchars($route['nombre_cliente']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="id_clientes">ID Cliente:</label>
                    <input type="number" name="id_clientes" id="id_clientes" value="<?= $route['id_clientes'] ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="id_ventas">ID Venta:</label>
                    <input type="number" name="id_ventas" id="id_ventas" value="<?= $route['id_ventas'] ?>" required>
                </div>
                
                <button type="submit">💾 Actualizar Ruta</button>
                <a href="<?= $_SERVER['PHP_SELF'] ?>" style="margin-left: 10px; text-decoration: none; color: #6c757d;">🔄 Refrescar</a>
            </form>
            
        <?php else: ?>
            <div class="result error">❌ No se encontró la ruta ID 14</div>
        <?php endif; ?>
    </div>
</body>
</html>