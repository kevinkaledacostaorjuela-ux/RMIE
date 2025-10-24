<?php
/**
 * Script automático para agregar columna tipo_alerta y clasificar alertas
 * Ejecutar visitando: http://localhost/RMIE/setup_alertas_automatico.php
 */

require_once __DIR__ . '/config/db.php';

$resultados = [];
$errores = [];

try {
    // Paso 1: Verificar y agregar la columna tipo_alerta si no existe
    $check = $conn->query("SHOW COLUMNS FROM alertas LIKE 'tipo_alerta'");
    
    if (!$check || $check->num_rows == 0) {
        $sql = "ALTER TABLE alertas ADD COLUMN tipo_alerta VARCHAR(20) DEFAULT 'stock_bajo' AFTER id_alertas";
        if ($conn->query($sql)) {
            $resultados[] = "✅ Columna 'tipo_alerta' agregada exitosamente";
        } else {
            $errores[] = "❌ Error al agregar columna: " . $conn->error;
        }
    } else {
        $resultados[] = "ℹ️ La columna 'tipo_alerta' ya existe";
    }
    
    // Paso 2: Obtener todas las alertas y analizarlas
    $query = "SELECT id_alertas, cantidad_minima, fecha_caducidad, tipo_alerta FROM alertas";
    $result = $conn->query($query);
    
    if ($result) {
        $total_alertas = $result->num_rows;
        $alertas_stock = [];
        $alertas_vencimiento = [];
        
        // Analizar cada alerta
        while ($row = $result->fetch_assoc()) {
            $id = $row['id_alertas'];
            $cantidad = $row['cantidad_minima'];
            $fecha = $row['fecha_caducidad'];
            
            // Criterio: Si la fecha está próxima (menos de 60 días) o la cantidad es baja (menos de 50), es vencimiento
            $dias_restantes = (strtotime($fecha) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);
            
            // Clasificación inteligente:
            // - Si días restantes < 60 días -> probablemente es alerta de vencimiento
            // - Si cantidad_minima > 50 -> probablemente es alerta de stock
            if ($dias_restantes < 60 && $cantidad < 50) {
                $alertas_vencimiento[] = $id;
            } else {
                $alertas_stock[] = $id;
            }
        }
        
        // Paso 3: Actualizar alertas a 'stock_bajo'
        if (!empty($alertas_stock)) {
            $ids_stock = implode(',', $alertas_stock);
            $update_stock = "UPDATE alertas SET tipo_alerta = 'stock_bajo' WHERE id_alertas IN ($ids_stock)";
            if ($conn->query($update_stock)) {
                $count_stock = count($alertas_stock);
                $resultados[] = "✅ {$count_stock} alertas configuradas como 'Stock Bajo' (IDs: $ids_stock)";
            }
        }
        
        // Paso 4: Actualizar alertas a 'expiration' (vencimiento)
        if (!empty($alertas_vencimiento)) {
            $ids_vencimiento = implode(',', $alertas_vencimiento);
            $update_vencimiento = "UPDATE alertas SET tipo_alerta = 'expiration' WHERE id_alertas IN ($ids_vencimiento)";
            if ($conn->query($update_vencimiento)) {
                $count_vencimiento = count($alertas_vencimiento);
                $resultados[] = "✅ {$count_vencimiento} alertas configuradas como 'Vencimiento' (IDs: $ids_vencimiento)";
            }
        }
        
        $resultados[] = "📊 Total de alertas procesadas: $total_alertas";
        
    } else {
        $errores[] = "❌ Error al consultar alertas: " . $conn->error;
    }
    
} catch (Exception $e) {
    $errores[] = "❌ Error inesperado: " . $e->getMessage();
}

$conn->close();

// Mostrar resultados
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración Automática de Alertas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 50px;
            border-radius: 25px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
            max-width: 700px;
            width: 100%;
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        h1 {
            color: #667eea;
            margin-bottom: 30px;
            text-align: center;
            font-size: 2.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .result-box {
            background: #d4edda;
            border: 2px solid #c3e6cb;
            color: #155724;
            padding: 15px 20px;
            border-radius: 12px;
            margin: 12px 0;
            font-size: 1rem;
            line-height: 1.6;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .error-box {
            background: #f8d7da;
            border: 2px solid #f5c6cb;
            color: #721c24;
            padding: 15px 20px;
            border-radius: 12px;
            margin: 12px 0;
            font-size: 1rem;
            line-height: 1.6;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .info-box {
            background: #d1ecf1;
            border: 2px solid #bee5eb;
            color: #0c5460;
            padding: 15px 20px;
            border-radius: 12px;
            margin: 12px 0;
            font-size: 1rem;
            line-height: 1.6;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
        }
        
        .btn {
            display: inline-block;
            padding: 15px 35px;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        
        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(245, 87, 108, 0.4);
        }
        
        .icon {
            font-size: 2.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>
            <span class="icon">⚙️</span>
            Configuración Completada
        </h1>
        
        <?php if (!empty($resultados)): ?>
            <?php foreach ($resultados as $resultado): ?>
                <?php if (strpos($resultado, 'ℹ️') !== false): ?>
                    <div class="info-box"><?= $resultado ?></div>
                <?php else: ?>
                    <div class="result-box"><?= $resultado ?></div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if (!empty($errores)): ?>
            <?php foreach ($errores as $error): ?>
                <div class="error-box"><?= $error ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if (empty($errores)): ?>
            <div class="info-box">
                <strong>🎉 ¡Todo listo!</strong><br><br>
                Las alertas ahora están correctamente clasificadas:<br>
                • <strong>Stock Bajo</strong>: Badge morado (🟣)<br>
                • <strong>Vencimiento</strong>: Badge rosa (🩷)<br><br>
                Las nuevas alertas se clasificarán automáticamente según el tipo que selecciones al crearlas.
            </div>
        <?php endif; ?>
        
        <div class="btn-container">
            <a href="/RMIE/app/controllers/AlertController.php?accion=index" class="btn btn-primary">
                📋 Ver Alertas
            </a>
            <a href="/RMIE/app/controllers/AlertController.php?accion=create" class="btn btn-secondary">
                ➕ Crear Alerta
            </a>
        </div>
    </div>
</body>
</html>
