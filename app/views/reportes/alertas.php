<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

require_once __DIR__ . '/../../models/Alert.php';

$alertas = Alert::getAll($conn);

// Aplicar filtros adicionales en PHP
if (!empty($filtros)) {
    $alertas = array_filter($alertas, function($a) use ($filtros) {
        // Filtro por tipo (usando el campo cliente_no_disponible como referencia)
        if (!empty($filtros['tipo'])) {
            if (stripos($a['cliente_no_disponible'] ?? '', $filtros['tipo']) === false) {
                return false;
            }
        }
        
        // Filtro por prioridad (usando cantidad_minima como referencia de prioridad)
        if (!empty($filtros['prioridad'])) {
            $cantidad = (int)($a['cantidad_minima'] ?? 0);
            if ($filtros['prioridad'] === 'alta' && $cantidad > 10) {
                return false;
            }
            if ($filtros['prioridad'] === 'media' && ($cantidad <= 5 || $cantidad > 10)) {
                return false;
            }
            if ($filtros['prioridad'] === 'baja' && $cantidad > 5) {
                return false;
            }
        }
        
        // Filtro por estado (todas activas por defecto)
        if (!empty($filtros['estado']) && strtolower($filtros['estado']) !== 'activo') {
            return false;
        }
        
        // Filtro por fecha desde
        if (!empty($filtros['fecha_desde']) && !empty($a['fecha_caducidad'])) {
            if (strtotime($a['fecha_caducidad']) < strtotime($filtros['fecha_desde'])) {
                return false;
            }
        }
        
        // Filtro por fecha hasta
        if (!empty($filtros['fecha_hasta']) && !empty($a['fecha_caducidad'])) {
            if (strtotime($a['fecha_caducidad']) > strtotime($filtros['fecha_hasta'] . ' 23:59:59')) {
                return false;
            }
        }
        
        return true;
    });
}

$totalAlertas = count($alertas);
$alertasActivas = count(array_filter($alertas, fn($a) => true)); // Todas activas por defecto
$alertasCriticas = count(array_filter($alertas, fn($a) => ((int)($a['cantidad_minima'] ?? 0)) <= 5));
$alertasResueltas = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Alertas - Gráficos Estadísticos</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .chart-container {
            width: 80%;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="chart-container">
        <h1>Gestión de Alertas</h1>
        <canvas id="alertChart"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('alertChart').getContext('2d');
        const alertChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Activas', 'Críticas', 'Resueltas'],
                datasets: [{
                    label: 'Cantidad de Alertas',
                    data: [
                        <?= $alertasActivas ?>,
                        <?= $alertasCriticas ?>,
                        <?= $alertasResueltas ?>
                    ],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Distribución de Alertas'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
