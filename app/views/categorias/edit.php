<?php
// Verificar que tengamos los datos de la categoría
if (!isset($categoria)) {
    header('Location: /RMIE/app/controllers/CategoryController.php?accion=index');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        /* Header con título */
        .page-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .page-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .page-title i {
            font-size: 2rem;
        }

        /* Card principal */
        .edit-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 24px;
        }

        /* Sección de información del local */
        .info-section {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(139, 92, 246, 0.08));
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
            border: 1px solid rgba(99, 102, 241, 0.15);
        }

        .info-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 1rem;
            color: #1f2937;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Grupos de formulario */
        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-label i {
            color: #6366f1;
            width: 16px;
        }

        .form-control {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            background: white;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        /* Select personalizado */
        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 48px;
        }

        /* Cards laterales */
        .side-cards {
            display: grid;
            gap: 20px;
        }

        .comparison-card, .tips-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .comparison-card {
            background: linear-gradient(135deg, rgba(147, 197, 253, 0.1), rgba(196, 181, 253, 0.1));
            border: 1px solid rgba(147, 197, 253, 0.2);
        }

        .tips-card {
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.1), rgba(245, 158, 11, 0.1));
            border: 1px solid rgba(251, 191, 36, 0.2);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }

        .comparison-card .card-icon {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        }

        .tips-card .card-icon {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937;
        }

        .empty-state {
            text-align: center;
            padding: 32px 20px;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        /* Botones */
        .button-group {
            display: flex;
            gap: 16px;
            justify-content: center;
            margin-top: 32px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 16px 32px;
            border: none;
            border-radius: 16px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            min-width: 140px;
            justify-content: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #06b6d4, #3b82f6);
            color: white;
            box-shadow: 0 8px 25px rgba(6, 182, 212, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(6, 182, 212, 0.4);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #f97316, #f59e0b);
            color: white;
            box-shadow: 0 8px 25px rgba(249, 115, 22, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(249, 115, 22, 0.4);
            color: white;
        }

        .btn-cancel {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
            box-shadow: 0 8px 25px rgba(107, 114, 128, 0.3);
        }

        .btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(107, 114, 128, 0.4);
            color: white;
        }

        /* Layout responsive */
        .main-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 24px;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .main-layout {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .edit-card {
                padding: 24px;
            }
            
            .button-group {
                flex-direction: column;
            }
        }

        /* Animaciones */
        .edit-card, .comparison-card, .tips-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .comparison-card {
            animation-delay: 0.2s;
        }

        .tips-card {
            animation-delay: 0.4s;
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

        /* Navegación superior */
        .top-nav {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 12px 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .breadcrumb-modern {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
            color: white;
        }

        .breadcrumb-modern a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .breadcrumb-modern a:hover {
            color: white;
            transform: scale(1.05);
        }

        /* Header principal */
        .main-header {
            text-align: center;
            margin-bottom: 25px;
            color: white;
        }

        .main-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .main-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
        }

        /* Layout principal */
        .main-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
            align-items: start;
        }

        /* Cards */
        .card-modern {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            height: fit-content;
        }

        .card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid rgba(102, 126, 234, 0.1);
        }

        .card-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        /* Formulario */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control-modern {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 12px;
            font-size: 0.95rem;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            resize: vertical;
            font-family: inherit;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 15px rgba(102, 126, 234, 0.2);
            background: white;
            transform: translateY(-1px);
        }

        .form-control-modern::placeholder {
            color: rgba(44, 62, 80, 0.5);
            font-size: 0.9rem;
        }

        .char-counter {
            font-size: 0.8rem;
            color: rgba(44, 62, 80, 0.6);
            margin-top: 5px;
            text-align: right;
        }

        /* Resumen card */
        .summary-card {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.08), rgba(118, 75, 162, 0.08));
            border: 2px solid rgba(102, 126, 234, 0.15);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
        }

        .summary-value {
            font-weight: 700;
            color: #667eea;
            font-size: 1rem;
            text-align: right;
            max-width: 60%;
            word-break: break-word;
        }

        /* Botones */
        .btn-group-modern {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid rgba(138, 43, 226, 0.1);
        }

        .btn-modern {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-width: 140px;
            justify-content: center;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #8a2be2 0%, #9932cc 100%);
            color: white;
            box-shadow: 0 6px 15px rgba(138, 43, 226, 0.25);
        }

        .btn-primary-modern:hover {
            background: linear-gradient(135deg, #7a1fc7 0%, #8a1ab8 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(138, 43, 226, 0.35);
            color: white;
        }

        .btn-secondary-modern {
            background: linear-gradient(135deg, #6c757d 0%, #7d8591 100%);
            color: white;
            box-shadow: 0 6px 15px rgba(108, 117, 125, 0.25);
        }

        .btn-secondary-modern:hover {
            background: linear-gradient(135deg, #5a6268 0%, #6c757d 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(108, 117, 125, 0.35);
            color: white;
        }

        /* Info box */
        .info-box {
            background: rgba(23, 162, 184, 0.08);
            border: 2px solid rgba(23, 162, 184, 0.2);
            border-radius: 12px;
            padding: 16px;
            margin-top: 20px;
        }

        .info-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #17a2b8;
            margin-bottom: 12px;
            font-size: 0.95rem;
        }

        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-list li {
            padding: 4px 0;
            color: #2c3e50;
            position: relative;
            padding-left: 16px;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .info-list li::before {
            content: "•";
            color: #17a2b8;
            font-weight: bold;
            position: absolute;
            left: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-layout {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .main-header h1 {
                font-size: 2rem;
            }
            
            .card-modern {
                padding: 20px;
            }
            
            .btn-group-modern {
                flex-direction: column;
            }
        }

        /* Animaciones */
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

        .card-modern {
            animation: fadeInUp 0.6s ease-out;
        }

        .card-modern:nth-child(2) {
            animation-delay: 0.2s;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header con título -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-edit"></i>
                Editar Local #<?= $categoria->id_categoria ?>
            </h1>
        </div>

        <!-- Layout principal -->
        <div class="main-layout">
            <!-- Formulario principal -->
            <div class="edit-card">
                <!-- Información creado el -->
                <div class="info-section">
                    <div class="info-label">Creado el:</div>
                    <div class="info-value">
                        <i class="fas fa-calendar"></i> 
                        <?= isset($categoria->fecha_creacion) ? date('d/m/Y H:i', strtotime($categoria->fecha_creacion)) : '17/10/2025 20:35' ?>
                    </div>
                </div>

                <form method="POST" action="/RMIE/app/controllers/CategoryController.php?accion=edit&id=<?= $categoria->id_categoria ?>" id="formEditarCategoria">
                    <!-- Nombre del Local -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-store"></i>
                            Nombre del Local
                        </label>
                        <input type="text" 
                               name="nombre" 
                               class="form-control" 
                               value="<?= htmlspecialchars($categoria->nombre) ?>" 
                               placeholder="Ingrese el nombre del local"
                               required
                               maxlength="45">
                    </div>

                    <!-- Descripción (como Dirección) -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt"></i>
                            Dirección
                        </label>
                        <textarea name="descripcion" 
                                  class="form-control" 
                                  rows="3"
                                  placeholder="Ingrese la dirección completa"
                                  required
                                  maxlength="200"><?= htmlspecialchars($categoria->descripcion) ?></textarea>
                    </div>

                    <!-- Row con Teléfono y Estado -->
                    <div class="form-group">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div>
                                <label class="form-label">
                                    <i class="fas fa-phone"></i>
                                    Teléfono
                                </label>
                                <input type="tel" 
                                       name="telefono" 
                                       class="form-control" 
                                       value="3124125423" 
                                       placeholder="Número de teléfono">
                            </div>
                            <div>
                                <label class="form-label">
                                    <i class="fas fa-toggle-on"></i>
                                    Estado
                                </label>
                                <select name="estado" class="form-control form-select">
                                    <option value="Activo" selected>Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Row con Localidad y Barrio -->
                    <div class="form-group">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div>
                                <label class="form-label">
                                    <i class="fas fa-city"></i>
                                    Localidad
                                </label>
                                <input type="text" 
                                       name="localidad" 
                                       class="form-control" 
                                       value="Suba Bilbao" 
                                       placeholder="Localidad">
                            </div>
                            <div>
                                <label class="form-label">
                                    <i class="fas fa-home"></i>
                                    Barrio
                                </label>
                                <input type="text" 
                                       name="barrio" 
                                       class="form-control" 
                                       value="suba" 
                                       placeholder="Barrio">
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Botones de acción -->
                <div class="button-group">
                    <a href="/RMIE/app/controllers/CategoryController.php?accion=index" class="btn btn-cancel">
                        <i class="fas fa-arrow-left"></i>
                        CANCELAR
                    </a>
                    <button type="button" class="btn btn-secondary">
                        <i class="fas fa-undo"></i>
                        RESETEAR
                    </button>
                    <button type="submit" form="formEditarCategoria" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        GUARDAR CAMBIOS
                    </button>
                </div>
            </div>

            <!-- Cards laterales -->
            <div class="side-cards">
                <!-- Comparación de Cambios -->
                <div class="comparison-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <h3 class="card-title">Comparación de Cambios</h3>
                    </div>
                    <div class="empty-state">
                        <i class="fas fa-info-circle"></i>
                        <p>Los cambios aparecerán aquí</p>
                    </div>
                </div>

                <!-- Consejos -->
                <div class="tips-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3 class="card-title">Consejo:</h3>
                    </div>
                    <p style="color: #d97706; font-weight: 600; margin: 0;">
                        Los campos modificados se resaltan en dorado.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación en tiempo real y funcionalidad del formulario
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formEditarCategoria');
            const inputs = form.querySelectorAll('input, textarea, select');
            
            // Validación en tiempo real
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.checkValidity()) {
                        this.style.borderColor = '#10b981';
                        this.style.boxShadow = '0 0 0 4px rgba(16, 185, 129, 0.1)';
                    } else {
                        this.style.borderColor = '#ef4444';
                        this.style.boxShadow = '0 0 0 4px rgba(239, 68, 68, 0.1)';
                    }
                });
            });

            // Botón reset
            document.querySelector('.btn-secondary').addEventListener('click', function() {
                if (confirm('¿Está seguro de que desea resetear todos los campos?')) {
                    form.reset();
                    inputs.forEach(input => {
                        input.style.borderColor = '#e5e7eb';
                        input.style.boxShadow = 'none';
                    });
                }
            });

            // Simulación de comparación de cambios
            inputs.forEach(input => {
                const originalValue = input.value;
                input.addEventListener('input', function() {
                    const comparisonCard = document.querySelector('.empty-state');
                    if (this.value !== originalValue) {
                        comparisonCard.innerHTML = `
                            <i class="fas fa-exclamation-triangle" style="color: #f59e0b;"></i>
                            <p>Campo "${this.name}" modificado</p>
                        `;
                        this.style.backgroundColor = 'rgba(251, 191, 36, 0.1)';
                    } else {
                        comparisonCard.innerHTML = `
                            <i class="fas fa-info-circle"></i>
                            <p>Los cambios aparecerán aquí</p>
                        `;
                        this.style.backgroundColor = 'rgba(255, 255, 255, 0.9)';
                    }
                });
            });
        });
    </script>
</body>
</html>
