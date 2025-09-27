<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Reporte - RMIE</title>
    <!-- Bootstrap 5.3.0 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6.0.0 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
            --text-primary: #2d3748;
            --text-secondary: #4a5568;
            --shadow-light: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            --shadow-medium: 0 15px 35px 0 rgba(31, 38, 135, 0.2);
            --border-radius: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .glass-container {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: var(--border-radius);
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow-medium);
            transition: var(--transition);
            max-width: 800px;
            margin: 0 auto;
        }

        .glass-container:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
        }

        .form-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .form-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        .form-header h2 {
            position: relative;
            z-index: 1;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            position: relative;
            z-index: 1;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .form-content {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.7rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .form-group label i {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.1rem;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: var(--transition);
            color: var(--text-primary);
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.95);
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
            outline: none;
        }

        .form-control::placeholder {
            color: rgba(77, 85, 108, 0.6);
        }

        .btn {
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            position: relative;
            overflow: hidden;
            transition: var(--transition);
            cursor: pointer;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: var(--transition);
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-success {
            background: var(--success-gradient);
            color: white;
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .alert {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: var(--text-primary);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            border-left: 4px solid #dc3545;
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .form-header h2 {
                font-size: 1.5rem;
            }
            
            .form-content {
                padding: 1rem;
            }
            
            .button-group {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="glass-container animate-fade-in">
        <!-- Header -->
        <div class="form-header">
            <h2><i class="fas fa-file-alt"></i> Crear Nuevo Reporte</h2>
            <p>Complete los datos para generar un nuevo reporte del sistema</p>
        </div>

        <!-- Content -->
        <div class="form-content">
            <!-- Mostrar mensajes si existen -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form action="/RMIE/app/controllers/ReportController.php?action=store" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">
                                <i class="fas fa-signature"></i> Nombre del Reporte:
                            </label>
                            <input type="text" id="nombre" name="nombre" class="form-control" 
                                   placeholder="Ingrese el nombre del reporte" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tipo">
                                <i class="fas fa-tags"></i> Tipo de Reporte:
                            </label>
                            <select id="tipo" name="tipo" class="form-select" required>
                                <option value="">Seleccione un tipo</option>
                                <option value="ventas">Reporte de Ventas</option>
                                <option value="productos">Reporte de Productos</option>
                                <option value="clientes">Reporte de Clientes</option>
                                <option value="inventario">Reporte de Inventario</option>
                                <option value="alertas">Reporte de Alertas</option>
                                <option value="general">Reporte General</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="descripcion">
                        <i class="fas fa-align-left"></i> Descripción:
                    </label>
                    <textarea id="descripcion" name="descripcion" class="form-control" rows="4"
                              placeholder="Describa el contenido y propósito del reporte..."></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_inicio">
                                <i class="fas fa-calendar-alt"></i> Fecha Inicio:
                            </label>
                            <input type="date" id="fecha_inicio" name="parametros[fecha_inicio]" class="form-control">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_fin">
                                <i class="fas fa-calendar-check"></i> Fecha Fin:
                            </label>
                            <input type="date" id="fecha_fin" name="parametros[fecha_fin]" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="estado">
                                <i class="fas fa-flag"></i> Estado:
                            </label>
                            <select id="estado" name="estado" class="form-select">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                                <option value="borrador">Borrador</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="formato">
                                <i class="fas fa-file-export"></i> Formato de Exportación:
                            </label>
                            <select id="formato" name="parametros[formato]" class="form-select">
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Parámetros adicionales según el tipo -->
                <div id="parametros-adicionales" class="mt-3">
                    <!-- Se llenarán dinámicamente con JavaScript -->
                </div>

                <!-- Botones -->
                <div class="button-group">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Crear Reporte
                    </button>
                    <a href="/RMIE/app/controllers/ReportController.php?action=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript para funcionalidad dinámica -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipoSelect = document.getElementById('tipo');
        const parametrosContainer = document.getElementById('parametros-adicionales');
        
        // Configurar parámetros específicos según el tipo de reporte
        tipoSelect.addEventListener('change', function() {
            const tipo = this.value;
            let html = '';
            
            switch(tipo) {
                case 'ventas':
                    html = `
                        <div class="form-group">
                            <label for="include_canceled">
                                <i class="fas fa-filter"></i> Incluir ventas canceladas:
                            </label>
                            <select name="parametros[include_canceled]" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Sí</option>
                            </select>
                        </div>
                    `;
                    break;
                case 'productos':
                    html = `
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categoria">
                                        <i class="fas fa-list"></i> Categoría:
                                    </label>
                                    <select name="parametros[categoria]" class="form-select">
                                        <option value="">Todas las categorías</option>
                                        <option value="activas">Solo activas</option>
                                        <option value="inactivas">Solo inactivas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock_minimo">
                                        <i class="fas fa-warehouse"></i> Stock mínimo:
                                    </label>
                                    <input type="number" name="parametros[stock_minimo]" class="form-control" min="0">
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                case 'inventario':
                    html = `
                        <div class="form-group">
                            <label for="alertas_stock">
                                <i class="fas fa-exclamation-triangle"></i> Solo productos con alertas de stock:
                            </label>
                            <select name="parametros[alertas_stock]" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Sí</option>
                            </select>
                        </div>
                    `;
                    break;
                default:
                    html = '';
            }
            
            parametrosContainer.innerHTML = html;
        });
        
        // Validar fechas
        const fechaInicio = document.getElementById('fecha_inicio');
        const fechaFin = document.getElementById('fecha_fin');
        
        fechaInicio.addEventListener('change', function() {
            fechaFin.min = this.value;
        });
        
        fechaFin.addEventListener('change', function() {
            fechaInicio.max = this.value;
        });
        
        // Efecto en campos del formulario
        const formFields = document.querySelectorAll('.form-control, .form-select');
        formFields.forEach(field => {
            field.addEventListener('focus', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            field.addEventListener('blur', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
    </script>
</body>
</html>