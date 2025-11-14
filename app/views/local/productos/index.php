<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

// Obtener mensajes de sesión
$error_message = $_SESSION['error'] ?? '';
$success_message = $_SESSION['success'] ?? '';

// Limpiar mensajes de sesión
unset($_SESSION['error'], $_SESSION['success']);

// Obtener estadísticas básicas
global $conn;
$statsQuery = $conn->query("SELECT 
    COUNT(*) as total_productos,
    COUNT(CASE WHEN precio_unitario > 0 THEN 1 END) as con_precio,
    COUNT(CASE WHEN stock > 0 THEN 1 END) as con_stock
    FROM productos");
$stats = $statsQuery->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
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
            padding: 20px;
        }

        .productos-container {
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Header con efecto glassmorphism */
        .page-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 35px 40px;
            margin-bottom: 25px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-title {
            color: white;
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 18px;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .page-title i {
            font-size: 2.8rem;
            opacity: 0.95;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
            font-size: 0.95rem;
        }

        .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: white;
        }

        .breadcrumb-item.active {
            color: rgba(255, 255, 255, 0.7);
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.6);
        }

        /* Stats Cards con glassmorphism */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            transition: all 0.4s ease;
            border: 1px solid rgba(255, 255, 255, 0.25);
            animation: fadeInUp 0.6s ease-out;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.2);
        }

        .stat-icon {
            font-size: 3.5rem;
            margin-bottom: 18px;
            color: white;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .stat-number {
            font-size: 3.2rem;
            font-weight: 900;
            color: white;
            margin-bottom: 8px;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        /* Filters con glassmorphism */
        .filters-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }

        .filters-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .form-label {
            color: rgba(255, 255, 255, 0.95);
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            padding: 12px 16px;
            color: #333;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: rgba(255, 255, 255, 0.8);
            background: white;
            box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3);
            color: #333;
        }

        /* Table con glassmorphism */
        .table-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 30px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .table-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .table-responsive {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .table-productos {
            margin-bottom: 0;
            background: rgba(255, 255, 255, 0.95);
        }

        .table-productos thead th {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            border: none;
            padding: 18px 12px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .table-productos tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table-productos tbody tr:hover {
            background: rgba(102, 126, 234, 0.1);
        }

        .table-productos tbody td {
            padding: 16px 12px;
            vertical-align: middle;
            color: #2c3e50;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Badges */
        .badge {
            padding: 8px 14px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .stock-alto { 
            background: rgba(40, 167, 69, 0.9);
            color: white;
        }
        
        .stock-medio { 
            background: rgba(255, 193, 7, 0.9);
            color: white;
        }
        
        .stock-bajo { 
            background: rgba(220, 53, 69, 0.9);
            color: white;
        }

        .bg-primary {
            background: rgba(102, 126, 234, 0.9) !important;
        }

        .bg-info {
            background: rgba(23, 162, 184, 0.9) !important;
        }

        .bg-secondary {
            background: rgba(108, 117, 125, 0.9) !important;
        }

        /* Buttons */
        .btn-productos {
            padding: 12px 24px;
            border-radius: 15px;
            font-weight: 700;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }

        .btn-productos:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        }

        .btn-primary-productos {
            background: rgba(102, 126, 234, 0.9);
            color: white;
        }

        .btn-success-productos {
            background: rgba(40, 167, 69, 0.9);
            color: white;
        }

        .btn-warning-productos {
            background: rgba(255, 193, 7, 0.9);
            color: white;
        }

        .btn-danger-productos {
            background: rgba(220, 53, 69, 0.9);
            color: white;
        }

        .btn-secondary-productos {
            background: rgba(108, 117, 125, 0.9);
            color: white;
        }

        .btn-action {
            padding: 8px 14px;
            border-radius: 12px;
            font-size: 0.8rem;
            margin: 0 3px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        /* Alerts */
        .alert {
            border-radius: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 18px 24px;
            margin-bottom: 20px;
            font-weight: 600;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(20px);
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.9);
            color: white;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.9);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: rgba(255, 255, 255, 0.9);
        }

        .empty-state i {
            font-size: 5rem;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 25px;
        }

        .empty-state h5 {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 12px;
        }

        .empty-state p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 25px;
        }
            max-width: 1500px;
            margin: 0 auto;
        }

        .page-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.95));
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 35px 40px;
            margin-bottom: 25px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .page-title {
            color: #2c3e50;
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 18px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .page-title i {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 2px 4px rgba(102, 126, 234, 0.3));
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
            font-size: 0.95rem;
        }

        .breadcrumb-item a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: #764ba2;
            transform: translateX(2px);
        }

        .breadcrumb-item.active {
            color: #6c757d;
            font-weight: 500;
        }

        /* Stats Cards - Mejoradas */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.95));
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-left: 5px solid;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, transparent 0%, rgba(102, 126, 234, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card:nth-child(1) { 
            border-left-color: #667eea;
            background: linear-gradient(135deg, #fff 0%, #f8f9ff 100%);
        }
        
        .stat-card:nth-child(2) { 
            border-left-color: #28a745;
            background: linear-gradient(135deg, #fff 0%, #f8fff9 100%);
        }
        
        .stat-card:nth-child(3) { 
            border-left-color: #ffc107;
            background: linear-gradient(135deg, #fff 0%, #fffef8 100%);
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.18);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 18px;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
        }

        .stat-card:nth-child(1) .stat-icon { 
            color: #667eea;
            text-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }
        
        .stat-card:nth-child(2) .stat-icon { 
            color: #28a745;
            text-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        }
        
        .stat-card:nth-child(3) .stat-icon { 
            color: #ffc107;
            text-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 8px;
            line-height: 1;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        /* Filters Section - Mejorada */
        .filters-section {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.95));
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .filters-title {
            color: #2c3e50;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
        }

        .filters-title i {
            color: #667eea;
            font-size: 1.3rem;
        }

        .form-label {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-label i {
            color: #667eea;
            font-size: 0.85rem;
        }

        .form-control, .form-select {
            border: 2px solid #e0e6ed;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
            background: #f8f9ff;
        }

        /* Table Section - Mejorada */
        .table-section {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.95));
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .table-title {
            color: #2c3e50;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .table-title i {
            color: #667eea;
            font-size: 1.3rem;
        }

        .table-responsive {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .table-productos {
            margin-bottom: 0;
            background: white;
        }

        .table-productos thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            border: none;
            padding: 15px 12px;
            white-space: nowrap;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .table-productos thead th i {
            margin-right: 6px;
            font-size: 0.9rem;
        }

        .table-productos tbody tr {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-bottom: 1px solid #e9ecef;
        }

        .table-productos tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            transform: scale(1.005);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        .table-productos tbody td {
            padding: 16px 12px;
            vertical-align: middle;
            color: #2c3e50;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Badges Mejorados */
        .badge {
            padding: 8px 14px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .stock-alto { 
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .stock-medio { 
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: white;
        }
        
        .stock-bajo { 
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }

        .bg-primary {
            background: linear-gradient(135deg, #667eea, #764ba2) !important;
        }

        .bg-info {
            background: linear-gradient(135deg, #17a2b8, #138496) !important;
        }

        .bg-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268) !important;
        }

        /* Buttons Mejorados */
        .btn-productos {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 700;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-productos:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        }

        .btn-productos:active {
            transform: translateY(-1px);
        }

        .btn-primary-productos {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-success-productos {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .btn-warning-productos {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: white;
        }

        .btn-danger-productos {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }

        .btn-info-productos {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
        }

        .btn-secondary-productos {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
        }

        /* Action Buttons in Table */
        .btn-action {
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 0.8rem;
            margin: 0 3px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        /* Alerts Mejoradas */
        .alert {
            border-radius: 15px;
            border: none;
            padding: 18px 24px;
            margin-bottom: 20px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert i {
            font-size: 1.3rem;
        }

        .alert-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }

        /* Empty State Mejorado */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 5rem;
            color: #dee2e6;
            margin-bottom: 25px;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
        }

        .empty-state h5 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 12px;
        }

        .empty-state p {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 25px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .productos-container {
                padding: 15px;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .table-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
            backdrop-filter: blur(10px);
            margin-bottom: 20px;
            padding: 20px 25px;
            font-weight: 600;
            font-size: 1.1rem;
            animation: slideInDown 0.5s ease-out;
            position: relative;
            overflow: hidden;
        }

        .alert-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }
            100% {
                left: 100%;
            }
        }

        .alert-success-modern {
            background: rgba(46, 204, 113, 0.3);
            color: #fff;
            border: 2px solid rgba(46, 204, 113, 0.6);
            box-shadow: 0 10px 30px rgba(46, 204, 113, 0.3);
        }

        .alert-danger-modern {
            background: rgba(231, 76, 60, 0.3);
            color: #fff;
            border: 2px solid rgba(231, 76, 60, 0.6);
            box-shadow: 0 10px 30px rgba(231, 76, 60, 0.3);
        }

        .product-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 1.2rem;
        }

        .badge-modern {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-success {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
        }

        .badge-warning {
            background: linear-gradient(45deg, #ff9a9e, #fecfef);
        }

        .badge-danger {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
        }

        .badge-info {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
        }

        .badge-secondary {
            background: linear-gradient(45deg, #667eea, #764ba2);
        }

        /* Scroll horizontal para móviles - Productos */
        @media (max-width: 768px) {
            .table-container {
                padding: 15px;
                overflow: visible;
            }
            
            .table-responsive {
                -webkit-overflow-scrolling: touch;
                overflow-x: auto !important;
                overflow-y: visible;
                border-radius: 10px;
                max-width: 100%;
                position: relative;
            }
            
            .table-responsive::-webkit-scrollbar {
                height: 12px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 6px;
            }
            
            .table-responsive::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.5);
                border-radius: 6px;
                border: 2px solid rgba(255, 255, 255, 0.1);
            }
            
            .table-responsive::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.7);
            }
            
            .table-modern {
                min-width: 1000px !important; /* Ancho mínimo para 7 columnas */
                margin-bottom: 0;
                width: 1000px;
            }
            
            .table-modern th,
            .table-modern td {
                white-space: nowrap !important;
                padding: 10px 12px;
                font-size: 0.85rem;
                min-width: 120px;
            }
            
            /* Anchos específicos para productos (7 columnas) */
            .table-modern th:nth-child(1),
            .table-modern td:nth-child(1) { min-width: 70px; }
            
            .table-modern th:nth-child(2),
            .table-modern td:nth-child(2) { min-width: 200px; }
            
            .table-modern th:nth-child(3),
            .table-modern td:nth-child(3) { min-width: 150px; }
            
            .table-modern th:nth-child(4),
            .table-modern td:nth-child(4) { min-width: 100px; }
            
            .table-modern th:nth-child(5),
            .table-modern td:nth-child(5) { min-width: 100px; }
            
            .table-modern th:nth-child(6),
            .table-modern td:nth-child(6) { min-width: 150px; }
            
            .table-modern th:nth-child(7),
            .table-modern td:nth-child(7) { min-width: 120px; }
            
            /* Scroll indicator */
            .scroll-hint {
                position: absolute;
                bottom: -30px;
                left: 50%;
                transform: translateX(-50%);
                color: rgba(255, 255, 255, 0.8);
                font-size: 0.8rem;
                font-style: italic;
                animation: pulse 2s ease-in-out infinite;
                background: rgba(0, 0, 0, 0.3);
                padding: 5px 10px;
                border-radius: 15px;
                backdrop-filter: blur(5px);
            }
            
            @keyframes pulse {
                0%, 100% { opacity: 0.6; }
                50% { opacity: 1; }
            }
        }
    </style>
</head>
<body>
    <div class="productos-container">
        <!-- Header Section -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-box"></i> Gestión de Productos
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active">Productos</li>
                </ol>
            </nav>
        </div>

        <!-- Mensajes -->
        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-number"><?php echo $stats['total_productos']; ?></div>
                <div class="stat-label">Total Productos</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-number"><?php echo $stats['con_precio']; ?></div>
                <div class="stat-label">Con Precio</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="stat-number"><?php echo $stats['con_stock']; ?></div>
                <div class="stat-label">Con Stock</div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters-section">
            <div class="filters-title">
                <i class="fas fa-filter"></i> Filtros de Búsqueda
            </div>
            <form method="GET" action="" id="filterForm"
                <input type="hidden" name="accion" value="index"
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-tags"></i> Categoría
                        </label>
                        <select name="categoria" class="form-control form-control-modern">
                            <option value="">Todas las categorías</option>
                            <?php if (isset($categorias) && is_array($categorias)): ?>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat->id_categoria ?>" <?= isset($_GET['categoria']) && $_GET['categoria'] == $cat->id_categoria ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat->nombre) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-layer-group"></i> Subcategoría
                        </label>
                        <select name="subcategoria" class="form-control form-control-modern">
                            <option value="">Todas las subcategorías</option>
                            <?php if (isset($subcategorias) && is_array($subcategorias)): ?>
                                <?php foreach ($subcategorias as $subcat): ?>
                                    <option value="<?= $subcat->id_subcategoria ?>" <?= isset($_GET['subcategoria']) && $_GET['subcategoria'] == $subcat->id_subcategoria ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($subcat->nombre) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <div class="w-100">
                            <button type="submit" class="btn btn-modern btn-primary-modern me-2">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <button type="button" class="btn btn-modern btn-warning-modern" onclick="limpiarFiltros()">
                                <i class="fas fa-times"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabla de Productos -->
        <div class="table-section">
            <div class="table-header">
                <h2 class="table-title">
                    <i class="fas fa-list"></i> Lista de Productos
                </h2>
                <div>
                    <a href="/RMIE/app/controllers/ProductController.php?accion=create" class="btn btn-productos btn-success-productos">
                        <i class="fas fa-plus"></i> Nuevo Producto
                    </a>
                    <a href="/RMIE/app/views/dashboard.php" class="btn btn-productos btn-secondary-productos">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-productos">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-box"></i> Producto</th>
                            <th><i class="fas fa-tags"></i> Categoría</th>
                            <th><i class="fas fa-dollar-sign"></i> Precio</th>
                            <th><i class="fas fa-warehouse"></i> Stock</th>
                            <th><i class="fas fa-calendar-alt"></i> Fecha</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($productos) && is_array($productos) && !empty($productos)): ?>
                            <?php foreach ($productos as $prodData): ?>
                            <?php 
                            // Extraer el objeto producto y los nombres relacionados
                            $prod = $prodData['obj'];
                            $categoria_nombre = $prodData['categoria_nombre'];
                            $subcategoria_nombre = $prodData['subcategoria_nombre'];
                            $proveedor_nombre = $prodData['proveedor_nombre'];
                            $usuario_nombre = $prodData['usuario_nombre'];
                            ?>
                            <tr>
                                <td>
                                    <span class="badge bg-primary">#<?= htmlspecialchars($prod->id_productos) ?></span>
                                </td>
                                <td>
                                    <div>
                                        <strong><?= htmlspecialchars($prod->nombre) ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= htmlspecialchars(substr($prod->descripcion ?? '', 0, 50)) ?>...
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <?= htmlspecialchars($categoria_nombre ?? 'Sin categoría') ?>
                                    </span>
                                    <br>
                                    <small>
                                        <span class="badge bg-secondary mt-1">
                                            <?= htmlspecialchars($subcategoria_nombre ?? 'Sin subcategoría') ?>
                                        </span>
                                    </small>
                                </td>
                                <td>
                                    <strong class="text-success">
                                        $<?= number_format($prod->precio_unitario ?? 0, 0, ',', '.') ?>
                                    </strong>
                                </td>
                                <td>
                                    <?php 
                                    $stock = $prod->stock ?? 0;
                                    if ($stock > 50) {
                                        $badge_class = 'stock-alto';
                                    } elseif ($stock > 10) {
                                        $badge_class = 'stock-medio';
                                    } else {
                                        $badge_class = 'stock-bajo';
                                    }
                                    ?>
                                    <span class="badge <?= $badge_class ?>">
                                        <?= $stock ?> uds
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        <?= date('d/m/Y', strtotime($prod->fecha_entrada ?? 'now')) ?>
                                        <strong><?= date('d/m/Y', strtotime($prod->fecha_creacion ?? 'now')) ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                    </small>
                                </td>
                                <td>
                                    <a href="/RMIE/app/controllers/ProductController.php?accion=edit&id=<?= urlencode($prod->id_productos) ?>" 
                                       class="btn btn-productos btn-warning-productos btn-action" 
                                       title="Editar producto">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                    <a href="/RMIE/app/views/local/productos/delete.php?id=<?= urlencode($prod->id_productos) ?>" 
                                       class="btn btn-productos btn-danger-productos btn-action" 
                                       onclick="return confirm('¿Está seguro de eliminar este producto?')"
                                       title="Eliminar producto">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <h5>No hay productos disponibles</h5>
                                        <p>No se encontraron productos que coincidan con los filtros aplicados.</p>
                                        <a href="/RMIE/app/controllers/ProductController.php?accion=create" class="btn btn-productos btn-success-productos">
                                            <i class="fas fa-plus"></i> Crear Primer Producto
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function limpiarFiltros() {
            document.getElementById('filterForm').reset();
            window.location.href = '/RMIE/app/controllers/ProductController.php?accion=index';
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s, transform 0.5s';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>