<?php
// La vista asume que la sesión ya fue verificada por el controlador
// Obtener mensajes de sesión
$error_message = $_SESSION['error'] ?? '';
$success_message = $_SESSION['success'] ?? '';

// Limpiar mensajes de sesión
unset($_SESSION['error'], $_SESSION['success']);

// Datos por defecto para el formulario
$reporte = [
    'nombre' => '',
    'tipo' => '',
    'descripcion' => '',
    'fecha_creacion' => date('Y-m-d'),
    'estado' => 'activo'
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Reporte - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            padding: 20px 0;
        }
        
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .form-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
            overflow: hidden;
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
        
        .header-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px 30px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .header-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .header-title i {
            font-size: 2.8rem;
            opacity: 0.9;
        }
        
        .header-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            font-weight: 400;
        }
        
        .form-section {
            padding: 40px 30px;
        }
        
        .section-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .section-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.1);
        }
        
        .section-title {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .section-title i {
            font-size: 1.5rem;
            opacity: 0.9;
        }
        
        .form-row {
            display: grid;
            gap: 25px;
            margin-bottom: 25px;
        }
        
        .form-row-2 {
            grid-template-columns: 1fr 1fr;
        }
        
        .form-row-3 {
            grid-template-columns: 1fr 1fr 1fr;
        }
        
        @media (max-width: 768px) {
            .form-row-2, .form-row-3 {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
        
        .form-floating-modern {
            position: relative;
            margin-bottom: 20px;
        }
        
        .form-control-modern {
            width: 100%;
            padding: 18px 15px 8px 15px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            min-height: 56px;
        }
        
        .form-control-modern:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .form-select-modern {
            width: 100%;
            padding: 18px 15px 8px 15px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            min-height: 56px;
        }
        
        .form-select-modern:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        textarea.form-control-modern {
            resize: vertical;
            min-height: 120px;
            padding-top: 20px;
        }
        
        .form-floating-modern label {
            position: absolute;
            top: 12px;
            left: 15px;
            color: rgba(102, 126, 234, 0.8);
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-floating-modern label i {
            font-size: 16px;
        }
        
        .required {
            color: #ff6b6b;
            font-weight: bold;
        }
        
        .form-control-modern:focus ~ label,
        .form-control-modern:not(:placeholder-shown) ~ label,
        .form-select-modern:focus ~ label,
        .form-select-modern:not([value=""]) ~ label {
            top: 2px;
            font-size: 12px;
            color: #667eea;
        }
        
        textarea.form-control-modern:focus ~ label,
        textarea.form-control-modern:not(:placeholder-shown) ~ label {
            top: 2px;
        }
        
        .type-selector {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .type-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .type-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
        }
        
        .type-card.active {
            background: rgba(102, 126, 234, 0.3);
            border-color: #667eea;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .type-card i {
            font-size: 2.5rem;
            color: white;
            margin-bottom: 15px;
            display: block;
        }
        
        .type-card h6 {
            color: white;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .type-card p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            margin: 0;
        }
        
        .preview-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }
        
        .report-avatar-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 2.5rem;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }
        
        .report-avatar-large:hover {
            transform: scale(1.05);
        }
        
        .preview-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.9);
        }
        
        .preview-item:last-child {
            border-bottom: none;
        }
        
        .preview-label {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .preview-value {
            font-weight: 500;
            color: white;
            max-width: 60%;
            text-align: right;
            word-break: break-word;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            color: white;
        }
        
        .status-activo {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
        }
        
        .status-inactivo {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3);
        }
        
        .status-borrador {
            background: linear-gradient(135deg, #6c757d 0%, #adb5bd 100%);
            box-shadow: 0 3px 10px rgba(108, 117, 125, 0.3);
        }
        
        .info-panel {
            background: rgba(102, 126, 234, 0.2);
            border: 1px solid rgba(102, 126, 234, 0.4);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .info-panel h6 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-panel ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .info-panel li {
            margin-bottom: 5px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .buttons-section {
            padding: 20px 30px 40px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-modern {
            padding: 15px 35px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            min-width: 180px;
            justify-content: center;
        }
        
        .btn-create {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-create:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #5a67d8 0%, #667eea 100%);
            color: white;
        }
        
        .btn-cancel {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-cancel:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 255, 255, 0.2);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.2) 100%);
            color: white;
        }
        
        .alert-modern {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.4);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success-modern {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.4);
            color: white;
        }
        
        .character-count {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            text-align: right;
            margin-top: 5px;
        }
        
        .form-help {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .form-help i {
            color: #667eea;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                padding: 0 10px;
            }
            
            .form-container {
                border-radius: 20px;
            }
            
            .header-section {
                padding: 30px 20px;
            }
            
            .header-title {
                font-size: 2rem;
                flex-direction: column;
                gap: 10px;
            }
            
            .form-section {
                padding: 30px 20px;
            }
            
            .section-card {
                padding: 20px;
                margin-bottom: 20px;
            }
            
            .buttons-section {
                padding: 20px 20px 30px;
                flex-direction: column;
            }
            
            .btn-modern {
                width: 100%;
            }
            
            .type-selector {
                grid-template-columns: 1fr;
            }
        }
        
        /* Animaciones */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="form-container">
            <!-- Header -->
            <div class="header-section">
                <h1 class="header-title">
                    <i class="fas fa-chart-line"></i>
                    Crear Nuevo Reporte
                </h1>
                <p class="header-subtitle">Configure los parámetros para generar un reporte personalizado del sistema</p>
            </div>

            <!-- Alerts -->
            <?php if ($success_message): ?>
                <div class="form-section">
                    <div class="alert-success-modern">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>¡Éxito!</strong> <?php echo $success_message; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="form-section">
                    <div class="alert-modern">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Error:</strong> <?php echo $error_message; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <div class="form-section">
                <form method="POST" action="/RMIE/app/controllers/ReportController.php?accion=store" id="reportForm">
                    
                    <div class="form-row form-row-2">
                        <!-- Información Básica -->
                        <div class="section-card">
                            <div class="section-title">
                                <i class="fas fa-file-alt"></i>
                                Información del Reporte
                            </div>
                            
                            <div class="form-floating-modern">
                                <input type="text" 
                                       class="form-control-modern" 
                                       id="nombre" 
                                       name="nombre" 
                                       placeholder=" "
                                       maxlength="100"
                                       required
                                       value="<?php echo htmlspecialchars($reporte['nombre']); ?>">
                                <label for="nombre">
                                    <i class="fas fa-signature"></i>
                                    Nombre del Reporte <span class="required">*</span>
                                </label>
                                <div class="character-count">
                                    <span id="nombre-count">0</span>/100
                                </div>
                                <div class="form-help">
                                    <i class="fas fa-info-circle"></i>
                                    Nombre descriptivo para identificar el reporte
                                </div>
                            </div>

                            <div class="form-floating-modern">
                                <textarea class="form-control-modern" 
                                          id="descripcion" 
                                          name="descripcion" 
                                          placeholder=" "
                                          maxlength="500"><?php echo htmlspecialchars($reporte['descripcion']); ?></textarea>
                                <label for="descripcion">
                                    <i class="fas fa-align-left"></i>
                                    Descripción del Reporte
                                </label>
                                <div class="character-count">
                                    <span id="descripcion-count">0</span>/500
                                </div>
                                <div class="form-help">
                                    <i class="fas fa-text-height"></i>
                                    Describe el propósito y contenido del reporte
                                </div>
                            </div>

                            <div class="form-row form-row-2">
                                <div class="form-floating-modern">
                                    <input type="date" 
                                           class="form-control-modern" 
                                           id="fecha_creacion" 
                                           name="fecha_creacion" 
                                           placeholder=" "
                                           required
                                           value="<?php echo $reporte['fecha_creacion']; ?>">
                                    <label for="fecha_creacion">
                                        <i class="fas fa-calendar-plus"></i>
                                        Fecha de Creación <span class="required">*</span>
                                    </label>
                                </div>

                                <div class="form-floating-modern">
                                    <select class="form-select-modern" 
                                            id="estado" 
                                            name="estado"
                                            required>
                                        <option value="activo" <?php echo $reporte['estado'] === 'activo' ? 'selected' : ''; ?>>Activo</option>
                                        <option value="inactivo" <?php echo $reporte['estado'] === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                                        <option value="borrador" <?php echo $reporte['estado'] === 'borrador' ? 'selected' : ''; ?>>Borrador</option>
                                    </select>
                                    <label for="estado">
                                        <i class="fas fa-flag"></i>
                                        Estado <span class="required">*</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Configuración y Vista Previa -->
                        <div class="section-card">
                            <div class="section-title">
                                <i class="fas fa-cogs"></i>
                                Configuración y Vista Previa
                            </div>
                            
                            <!-- Tipos de Reporte -->
                            <div style="margin-bottom: 25px;">
                                <label style="color: rgba(255, 255, 255, 0.9); font-weight: 600; margin-bottom: 15px; display: block;">
                                    <i class="fas fa-tags"></i> Tipo de Reporte <span class="required">*</span>
                                </label>
                                <div class="type-selector">
                                    <div class="type-card" data-type="ventas">
                                        <i class="fas fa-dollar-sign"></i>
                                        <h6>Ventas</h6>
                                        <p>Reportes de transacciones y estadísticas de ventas</p>
                                    </div>
                                    <div class="type-card" data-type="productos">
                                        <i class="fas fa-box"></i>
                                        <h6>Productos</h6>
                                        <p>Inventario y información de productos</p>
                                    </div>
                                    <div class="type-card" data-type="clientes">
                                        <i class="fas fa-users"></i>
                                        <h6>Clientes</h6>
                                        <p>Base de datos y análisis de clientes</p>
                                    </div>
                                    <div class="type-card" data-type="inventario">
                                        <i class="fas fa-warehouse"></i>
                                        <h6>Inventario</h6>
                                        <p>Stock y movimientos de inventario</p>
                                    </div>
                                    <div class="type-card" data-type="alertas">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <h6>Alertas</h6>
                                        <p>Notificaciones y alertas del sistema</p>
                                    </div>
                                    <div class="type-card" data-type="general">
                                        <i class="fas fa-chart-pie"></i>
                                        <h6>General</h6>
                                        <p>Reportes generales del sistema</p>
                                    </div>
                                </div>
                                <input type="hidden" id="tipo" name="tipo" required>
                            </div>

                            <!-- Vista Previa -->
                            <div class="preview-section">
                                <div style="margin-bottom: 20px;">
                                    <div class="report-avatar-large" id="previewAvatar">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <h6 style="color: rgba(255, 255, 255, 0.9); margin: 0;">
                                        Vista Previa del Reporte
                                    </h6>
                                </div>
                                
                                <div class="preview-item">
                                    <span class="preview-label">
                                        <i class="fas fa-signature"></i>
                                        Nombre:
                                    </span>
                                    <span class="preview-value" id="preview-nombre">-</span>
                                </div>
                                
                                <div class="preview-item">
                                    <span class="preview-label">
                                        <i class="fas fa-tags"></i>
                                        Tipo:
                                    </span>
                                    <span class="preview-value" id="preview-tipo">-</span>
                                </div>
                                
                                <div class="preview-item">
                                    <span class="preview-label">
                                        <i class="fas fa-calendar"></i>
                                        Fecha:
                                    </span>
                                    <span class="preview-value" id="preview-fecha">-</span>
                                </div>
                                
                                <div class="preview-item" style="justify-content: center; border-bottom: none;">
                                    <span id="preview-estado-badge">
                                        <span class="status-badge status-activo">
                                            <i class="fas fa-check-circle"></i> Activo
                                        </span>
                                    </span>
                                </div>
                            </div>

                            <div class="info-panel">
                                <h6><i class="fas fa-lightbulb"></i> Consejos para Reportes</h6>
                                <ul>
                                    <li>Use nombres descriptivos que identifiquen claramente el contenido</li>
                                    <li>La descripción ayuda a otros usuarios a entender el propósito</li>
                                    <li>Seleccione el tipo correcto para obtener datos relevantes</li>
                                    <li>Los reportes en borrador pueden editarse antes de ser activados</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Configuración Avanzada -->
                    <div class="section-card">
                        <div class="section-title">
                            <i class="fas fa-sliders-h"></i>
                            Configuración Avanzada
                        </div>
                        
                        <div class="form-row form-row-2">
                            <div class="form-floating-modern">
                                <input type="date" 
                                       class="form-control-modern" 
                                       id="fecha_inicio" 
                                       name="parametros[fecha_inicio]" 
                                       placeholder=" ">
                                <label for="fecha_inicio">
                                    <i class="fas fa-calendar-alt"></i>
                                    Fecha de Inicio
                                </label>
                                <div class="form-help">
                                    <i class="fas fa-info-circle"></i>
                                    Rango inicial para filtrar datos (opcional)
                                </div>
                            </div>

                            <div class="form-floating-modern">
                                <input type="date" 
                                       class="form-control-modern" 
                                       id="fecha_fin" 
                                       name="parametros[fecha_fin]" 
                                       placeholder=" ">
                                <label for="fecha_fin">
                                    <i class="fas fa-calendar-check"></i>
                                    Fecha de Fin
                                </label>
                                <div class="form-help">
                                    <i class="fas fa-info-circle"></i>
                                    Rango final para filtrar datos (opcional)
                                </div>
                            </div>
                        </div>

                        <div class="form-floating-modern">
                            <select class="form-select-modern" 
                                    id="formato" 
                                    name="parametros[formato]">
                                <option value="pdf" selected>PDF</option>
                                <option value="excel">Excel</option>
                                <option value="csv">CSV</option>
                            </select>
                            <label for="formato">
                                <i class="fas fa-file-export"></i>
                                Formato de Exportación
                            </label>
                            <div class="form-help">
                                <i class="fas fa-download"></i>
                                Formato en que se exportará el reporte
                            </div>
                        </div>

                        <!-- Parámetros dinámicos según tipo -->
                        <div id="parametros-adicionales">
                            <!-- Se llenarán dinámicamente con JavaScript -->
                        </div>
                    </div>
                </form>
            </div>

            <!-- Buttons -->
            <div class="buttons-section">
                <button type="submit" form="reportForm" class="btn-modern btn-create">
                    <i class="fas fa-chart-line"></i>
                    CREAR REPORTE
                </button>
                <a href="/RMIE/app/controllers/ReportController.php?accion=index" class="btn-modern btn-cancel">
                    <i class="fas fa-times"></i>
                    CANCELAR
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Contadores de caracteres
            function setupCharacterCount(inputId, countId, maxLength) {
                const input = document.getElementById(inputId);
                const counter = document.getElementById(countId);
                
                if (input && counter) {
                    function updateCount() {
                        const count = input.value.length;
                        counter.textContent = count;
                        counter.style.color = count > maxLength * 0.8 ? '#ff9800' : 'rgba(255, 255, 255, 0.7)';
                    }
                    
                    input.addEventListener('input', updateCount);
                    updateCount(); // Inicializar
                }
            }
            
            setupCharacterCount('nombre', 'nombre-count', 100);
            setupCharacterCount('descripcion', 'descripcion-count', 500);

            // Selección de tipo de reporte
            const typeCards = document.querySelectorAll('.type-card');
            const tipoInput = document.getElementById('tipo');
            
            typeCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Remover selección anterior
                    typeCards.forEach(c => c.classList.remove('active'));
                    
                    // Agregar selección actual
                    this.classList.add('active');
                    const tipo = this.dataset.type;
                    tipoInput.value = tipo;
                    
                    // Actualizar vista previa
                    updatePreview();
                    
                    // Configurar parámetros específicos
                    setupParametrosAdicionales(tipo);
                });
            });

            // Vista previa en tiempo real
            function updatePreview() {
                const nombre = document.getElementById('nombre').value;
                const tipo = document.getElementById('tipo').value;
                const fecha = document.getElementById('fecha_creacion').value;
                const estado = document.getElementById('estado').value;
                
                // Actualizar avatar
                const avatar = document.getElementById('previewAvatar');
                if (nombre) {
                    avatar.innerHTML = nombre.charAt(0).toUpperCase();
                } else {
                    avatar.innerHTML = '<i class="fas fa-chart-line"></i>';
                }
                
                // Actualizar campos
                document.getElementById('preview-nombre').textContent = nombre || '-';
                
                // Tipo con nombre amigable
                const tipoNames = {
                    'ventas': 'Ventas',
                    'productos': 'Productos',
                    'clientes': 'Clientes',
                    'inventario': 'Inventario',
                    'alertas': 'Alertas',
                    'general': 'General'
                };
                document.getElementById('preview-tipo').textContent = tipoNames[tipo] || '-';
                
                // Fecha formateada
                if (fecha) {
                    const fechaObj = new Date(fecha);
                    const fechaFormatted = fechaObj.toLocaleDateString('es-ES');
                    document.getElementById('preview-fecha').textContent = fechaFormatted;
                } else {
                    document.getElementById('preview-fecha').textContent = '-';
                }
                
                // Estado
                const estadoBadge = document.getElementById('preview-estado-badge');
                const estadoClasses = {
                    'activo': 'status-activo',
                    'inactivo': 'status-inactivo',
                    'borrador': 'status-borrador'
                };
                const estadoIcons = {
                    'activo': 'fas fa-check-circle',
                    'inactivo': 'fas fa-times-circle',
                    'borrador': 'fas fa-edit'
                };
                const estadoTexts = {
                    'activo': 'Activo',
                    'inactivo': 'Inactivo',
                    'borrador': 'Borrador'
                };
                
                estadoBadge.innerHTML = `<span class="status-badge ${estadoClasses[estado] || 'status-activo'}">
                    <i class="${estadoIcons[estado] || 'fas fa-check-circle'}"></i> ${estadoTexts[estado] || 'Activo'}
                </span>`;
            }

            // Parámetros adicionales según tipo
            function setupParametrosAdicionales(tipo) {
                const container = document.getElementById('parametros-adicionales');
                let html = '';
                
                switch(tipo) {
                    case 'ventas':
                        html = `
                            <div class="form-floating-modern">
                                <select class="form-select-modern" name="parametros[include_canceled]">
                                    <option value="0" selected>No incluir</option>
                                    <option value="1">Incluir</option>
                                </select>
                                <label>
                                    <i class="fas fa-filter"></i>
                                    Incluir Ventas Canceladas
                                </label>
                            </div>
                        `;
                        break;
                    case 'productos':
                        html = `
                            <div class="form-row form-row-2">
                                <div class="form-floating-modern">
                                    <select class="form-select-modern" name="parametros[categoria]">
                                        <option value="" selected>Todas las categorías</option>
                                        <option value="activas">Solo activas</option>
                                        <option value="inactivas">Solo inactivas</option>
                                    </select>
                                    <label>
                                        <i class="fas fa-list"></i>
                                        Filtrar por Categoría
                                    </label>
                                </div>
                                <div class="form-floating-modern">
                                    <input type="number" class="form-control-modern" name="parametros[stock_minimo]" min="0" placeholder=" ">
                                    <label>
                                        <i class="fas fa-warehouse"></i>
                                        Stock Mínimo
                                    </label>
                                </div>
                            </div>
                        `;
                        break;
                    case 'inventario':
                        html = `
                            <div class="form-floating-modern">
                                <select class="form-select-modern" name="parametros[alertas_stock]">
                                    <option value="0" selected>Todos los productos</option>
                                    <option value="1">Solo con alertas de stock</option>
                                </select>
                                <label>
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Filtrar por Alertas
                                </label>
                            </div>
                        `;
                        break;
                    default:
                        html = '';
                }
                
                container.innerHTML = html;
                
                // Aplicar estilos a los nuevos elementos
                container.querySelectorAll('.form-select-modern').forEach(select => {
                    select.addEventListener('change', function() {
                        const label = this.parentElement.querySelector('label');
                        if (this.value) {
                            label.style.top = '2px';
                            label.style.fontSize = '12px';
                            label.style.color = '#667eea';
                        }
                    });
                });
            }

            // Agregar listeners para vista previa
            ['nombre', 'tipo', 'fecha_creacion', 'estado'].forEach(function(fieldId) {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', updatePreview);
                    field.addEventListener('change', updatePreview);
                }
            });

            // Validación de fechas
            const fechaInicio = document.getElementById('fecha_inicio');
            const fechaFin = document.getElementById('fecha_fin');
            const fechaCreacion = document.getElementById('fecha_creacion');
            
            fechaInicio.addEventListener('change', function() {
                if (fechaFin.value && this.value > fechaFin.value) {
                    fechaFin.value = this.value;
                }
                fechaFin.min = this.value;
            });
            
            fechaFin.addEventListener('change', function() {
                if (fechaInicio.value && this.value < fechaInicio.value) {
                    fechaInicio.value = this.value;
                }
                fechaInicio.max = this.value;
            });

            // Validación del formulario
            document.getElementById('reportForm').addEventListener('submit', function(e) {
                const nombre = document.getElementById('nombre').value.trim();
                const tipo = document.getElementById('tipo').value;

                const errors = [];

                if (!nombre) {
                    errors.push('El nombre del reporte es obligatorio');
                }

                if (!tipo) {
                    errors.push('Debe seleccionar un tipo de reporte');
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    alert('Por favor corrige los siguientes errores:\n\n' + errors.join('\n'));
                    return;
                }
            });

            // Efectos visuales
            document.querySelectorAll('.form-control-modern, .form-select-modern').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });

            // Manejo de labels para selects
            document.querySelectorAll('.form-select-modern').forEach(select => {
                select.addEventListener('change', function() {
                    const label = this.parentElement.querySelector('label');
                    if (this.value) {
                        label.style.top = '2px';
                        label.style.fontSize = '12px';
                        label.style.color = '#667eea';
                    } else {
                        label.style.top = '12px';
                        label.style.fontSize = '14px';
                        label.style.color = 'rgba(102, 126, 234, 0.8)';
                    }
                });
            });

            // Inicializar vista previa
            updatePreview();
        });
    </script>
</body>
</html>