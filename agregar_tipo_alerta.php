<?php
/**
 * Script para agregar la columna tipo_alerta a la tabla alertas
 * Ejecutar una sola vez visitando: http://localhost/RMIE/agregar_tipo_alerta.php
 */

require_once __DIR__ . '/config/db.php';

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Actualización de Base de Datos</title>
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
            max-width: 600px;
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
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class='container'>";

echo "<h1>🔄 Actualización de Base de Datos</h1>";

try {
    // Verificar si la columna ya existe
    $check = $conn->query("SHOW COLUMNS FROM alertas LIKE 'tipo_alerta'");
    
    if ($check && $check->num_rows > 0) {
        echo "<div class='info'>
                <strong>ℹ️ Información:</strong><br>
                La columna 'tipo_alerta' ya existe en la tabla 'alertas'.<br>
                No es necesario ejecutar la actualización nuevamente.
              </div>";
    } else {
        // Agregar la columna tipo_alerta
        $sql = "ALTER TABLE alertas ADD COLUMN tipo_alerta VARCHAR(20) DEFAULT 'stock_bajo' AFTER id_alertas";
        
        if ($conn->query($sql)) {
            echo "<div class='success'>
                    <strong>✅ ¡Éxito!</strong><br>
                    La columna 'tipo_alerta' ha sido agregada correctamente a la tabla 'alertas'.
                  </div>";
            
            echo "<div class='info'>
                    <strong>📝 Nota:</strong><br>
                    Las alertas existentes se han configurado con el valor por defecto 'stock_bajo'.<br>
                    A partir de ahora, las nuevas alertas se crearán con el tipo correcto automáticamente.
                  </div>";
            
            echo "<h3>SQL Ejecutado:</h3>";
            echo "<pre>$sql</pre>";
            
            // Contar alertas actualizadas
            $count = $conn->query("SELECT COUNT(*) as total FROM alertas");
            $total = $count->fetch_assoc()['total'];
            
            echo "<div class='info'>
                    <strong>📊 Estadísticas:</strong><br>
                    Total de alertas en el sistema: <strong>$total</strong>
                  </div>";
            
        } else {
            echo "<div class='error'>
                    <strong>❌ Error:</strong><br>
                    No se pudo agregar la columna: " . $conn->error . "
                  </div>";
        }
    }
    
    echo "<a href='/RMIE/app/controllers/AlertController.php?accion=index' class='btn'>
            Ir a Módulo de Alertas
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
