<?php
/**
 * Script para actualizar el tipo de alertas existentes
 * Ejecutar visitando: http://localhost/RMIE/actualizar_tipos_alertas.php
 */

require_once __DIR__ . '/config/db.php';

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Actualizar Tipos de Alertas</title>
    <style>
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
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 800px;
            width: 100%;
        }
        h1 {
            color: #667eea;
            margin-bottom: 20px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #c3e6cb;
            margin: 10px 0;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #f5c6cb;
            margin: 10px 0;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #bee5eb;
            margin: 10px 0;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 10px;
            margin-top: 20px;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #667eea;
            color: white;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-stock {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .badge-vencimiento {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
    </style>
</head>
<body>
    <div class='container'>";

echo "<h1>🔄 Actualizar Tipos de Alertas</h1>";

try {
    // Verificar si la columna existe
    $check = $conn->query("SHOW COLUMNS FROM alertas LIKE 'tipo_alerta'");
    
    if (!$check || $check->num_rows == 0) {
        echo "<div class='error'>
                <strong>❌ Error:</strong><br>
                La columna 'tipo_alerta' no existe en la tabla 'alertas'.<br>
                Por favor, ejecuta primero: <a href='/RMIE/agregar_tipo_alerta.php'>agregar_tipo_alerta.php</a>
              </div>";
    } else {
        // Obtener todas las alertas
        $result = $conn->query("SELECT id_alertas, cantidad_minima, fecha_caducidad, tipo_alerta FROM alertas ORDER BY id_alertas DESC");
        
        echo "<h3>📋 Lista de Alertas y Clasificación Sugerida</h3>";
        echo "<div class='info'>
                <strong>ℹ️ Criterio de clasificación:</strong><br>
                Por defecto, todas se consideran 'Stock Bajo' a menos que las cambies manualmente.
              </div>";
        
        echo "<table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cantidad Mínima</th>
                        <th>Fecha Caducidad</th>
                        <th>Tipo Actual</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>";
        
        $stock_count = 0;
        $vencimiento_count = 0;
        
        while ($row = $result->fetch_assoc()) {
            $tipo_actual = $row['tipo_alerta'];
            $tipo_badge = $tipo_actual === 'expiration' || $tipo_actual === 'vencimiento' ? 'vencimiento' : 'stock';
            $tipo_texto = $tipo_badge === 'stock' ? 'Stock Bajo' : 'Vencimiento';
            
            if ($tipo_badge === 'stock') {
                $stock_count++;
            } else {
                $vencimiento_count++;
            }
            
            echo "<tr>
                    <td>#{$row['id_alertas']}</td>
                    <td>{$row['cantidad_minima']} unidades</td>
                    <td>" . date('d/m/Y', strtotime($row['fecha_caducidad'])) . "</td>
                    <td><span class='badge badge-{$tipo_badge}'>$tipo_texto</span></td>
                    <td>
                        <form method='POST' style='display:inline;'>
                            <input type='hidden' name='id_alerta' value='{$row['id_alertas']}'>
                            <select name='nuevo_tipo' style='padding: 5px; border-radius: 5px;'>
                                <option value='stock_bajo' " . ($tipo_badge === 'stock' ? 'selected' : '') . ">Stock Bajo</option>
                                <option value='expiration' " . ($tipo_badge === 'vencimiento' ? 'selected' : '') . ">Vencimiento</option>
                            </select>
                            <button type='submit' name='actualizar' style='padding: 5px 10px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer;'>Cambiar</button>
                        </form>
                    </td>
                  </tr>";
        }
        
        echo "</tbody></table>";
        
        echo "<div class='success'>
                <strong>📊 Resumen:</strong><br>
                Alertas de Stock Bajo: <strong>$stock_count</strong><br>
                Alertas de Vencimiento: <strong>$vencimiento_count</strong>
              </div>";
        
        // Procesar actualización individual
        if (isset($_POST['actualizar'])) {
            $id_alerta = (int)$_POST['id_alerta'];
            $nuevo_tipo = $_POST['nuevo_tipo'] === 'expiration' ? 'expiration' : 'stock_bajo';
            
            $update = $conn->prepare("UPDATE alertas SET tipo_alerta = ? WHERE id_alertas = ?");
            $update->bind_param('si', $nuevo_tipo, $id_alerta);
            
            if ($update->execute()) {
                echo "<div class='success'>
                        <strong>✅ Alerta #{$id_alerta} actualizada correctamente a: {$nuevo_tipo}</strong>
                      </div>";
                echo "<script>setTimeout(function(){ window.location.reload(); }, 2000);</script>";
            }
        }
        
        // Botón para actualizar múltiples
        echo "<form method='POST' style='margin-top: 20px;'>
                <h3>🔧 Acciones Masivas</h3>
                <div class='info'>
                    Puedes actualizar manualmente cada alerta usando los controles de arriba, o puedes:<br><br>
                    <label>
                        IDs de alertas para cambiar a 'Vencimiento' (separados por coma):<br>
                        <input type='text' name='ids_vencimiento' placeholder='Ej: 18,19,20' style='width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ddd;'>
                    </label>
                    <button type='submit' name='actualizar_masivo' style='padding: 12px 30px; background: #f5576c; color: white; border: none; border-radius: 10px; cursor: pointer; font-size: 16px;'>
                        Actualizar a Vencimiento
                    </button>
                </div>
              </form>";
        
        // Procesar actualización masiva
        if (isset($_POST['actualizar_masivo']) && !empty($_POST['ids_vencimiento'])) {
            $ids = explode(',', $_POST['ids_vencimiento']);
            $ids = array_map('trim', $ids);
            $ids = array_filter($ids, 'is_numeric');
            
            if (!empty($ids)) {
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $types = str_repeat('i', count($ids));
                
                $update = $conn->prepare("UPDATE alertas SET tipo_alerta = 'expiration' WHERE id_alertas IN ($placeholders)");
                $update->bind_param($types, ...$ids);
                
                if ($update->execute()) {
                    $affected = $update->affected_rows;
                    echo "<div class='success'>
                            <strong>✅ Se actualizaron {$affected} alertas a 'Vencimiento'</strong>
                          </div>";
                    echo "<script>setTimeout(function(){ window.location.reload(); }, 2000);</script>";
                }
            }
        }
    }
    
    echo "<a href='/RMIE/app/controllers/AlertController.php?accion=index' class='btn'>
            Ver Módulo de Alertas
          </a>";
    
} catch (Exception $e) {
    echo "<div class='error'>
            <strong>❌ Error inesperado:</strong><br>
            " . $e->getMessage() . "
          </div>";
}

$conn->close();

echo "    </div>
</body>
</html>";
?>
