<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: ../../../index.php');
    exit();
}

require_once __DIR__ . '/../../models/Local.php';
require_once __DIR__ . '/../../../config/db.php';

$id = $_GET['id'] ?? 0;
$errors = [];
$success = '';

if (!$id) {
    header('Location: /RMIE/app/controllers/LocalController.php?action=index');
    exit();
}

try {
    $local = Local::getById($conn, $id);
    
    if (!$local) {
        header('Location: /RMIE/app/controllers/LocalController.php?action=index');
        exit();
    }
} catch (Exception $e) {
    $errors[] = 'Error al cargar el local: ' . $e->getMessage();
    $local = [
        'id' => $id,
        'nombre' => '',
        'direccion' => '',
        'telefono' => '',
        'tipo' => 'sucursal',
        'estado' => 'activo',
        'descripcion' => ''
    ];
}

$originalLocal = $local; // Para detectar cambios

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $local = [
        'id' => $id,
        'nombre' => trim($_POST['nombre'] ?? ''),
        'direccion' => trim($_POST['direccion'] ?? ''),
        'telefono' => trim($_POST['telefono'] ?? ''),
        'tipo' => $_POST['tipo'] ?? 'sucursal',
        'estado' => $_POST['estado'] ?? 'activo',
        'descripcion' => trim($_POST['descripcion'] ?? '')
    ];
    
    // Validaciones
    if (empty($local['nombre'])) {
        $errors[] = 'El nombre es obligatorio';
    }
    if (empty($local['direccion'])) {
        $errors[] = 'La dirección es obligatoria';
    }
    if (!in_array($local['tipo'], ['sucursal', 'bodega', 'oficina'])) {
        $errors[] = 'Tipo de local inválido';
    }
    if (!in_array($local['estado'], ['activo', 'inactivo'])) {
        $errors[] = 'Estado inválido';
    }
    
    if (empty($errors)) {
        try {
            $result = Local::update($conn, $id, $local);
            if ($result) {
                $success = 'Local actualizado exitosamente';
                $originalLocal = $local; // Actualizar referencia original
            } else {
                $errors[] = 'Error al actualizar el local';
            }
        } catch (Exception $e) {
            $errors[] = 'Error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Local - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../../public/css/styles.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            margin: 20px auto;
            max-width: 900px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .page-title {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .form-label {
            color: #fff;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control-modern {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            color: #fff;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control-modern::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control-modern:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: #4facfe;
            box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
            color: #fff;
            outline: none;
        }

        .form-control-modern.changed {
            border-color: #ffd700;
            box-shadow: 0 0 0 0.1rem rgba(255, 215, 0, 0.3);
        }

        .btn-modern {
            padding: 12px 25px;
            border-radius: 25px;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-success-modern {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .btn-secondary-modern {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .btn-warning-modern {
            background: linear-gradient(45deg, #ff9a9e, #fecfef);
            color: white;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .alert-modern {
            border-radius: 15px;
            border: none;
            backdrop-filter: blur(10px);
            margin-bottom: 20px;
        }

        .alert-success-modern {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }

        .alert-danger-modern {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }

        .alert-warning-modern {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border: 1px solid #ffc107;
        }

        .comparison-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .comparison-title {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
        }

        .comparison-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .comparison-item:last-child {
            border-bottom: none;
        }

        .comparison-label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
            flex: 1;
        }

        .comparison-values {
            flex: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .comparison-original {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .comparison-new {
            font-weight: 600;
            color: #ffd700;
        }

        .comparison-arrow {
            margin: 0 10px;
            color: #4facfe;
        }

        .unchanged {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #4facfe;
        }

        .info-card .info-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .info-card .info-value {
            color: #fff;
            font-weight: 600;
        }

        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .loading-content {
            text-align: center;
            color: white;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-content">
            <div class="spinner"></div>
            <h3>Cargando Editor...</h3>
            <p>Preparando la edición del local</p>
        </div>
    </div>

    <div class="container">
        <div class="form-container">
            <h1 class="page-title">
                <i class="fas fa-edit"></i> Editar Local #<?php echo $local['id']; ?>
            </h1>

            <?php if ($success): ?>
                <div class="alert alert-modern alert-success-modern">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-modern alert-danger-modern">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <!-- Información del local -->
                    <div class="info-card">
                        <div class="info-label">Creado el:</div>
                        <div class="info-value">
                            <i class="fas fa-calendar"></i>
                            <?php echo isset($local['fecha_creacion']) ? date('d/m/Y H:i', strtotime($local['fecha_creacion'])) : 'No disponible'; ?>
                        </div>
                    </div>

                    <form method="POST" id="localForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-building"></i> Nombre del Local
                                </label>
                                <input type="text" 
                                       class="form-control form-control-modern" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="<?php echo htmlspecialchars($local['nombre']); ?>"
                                       data-original="<?php echo htmlspecialchars($originalLocal['nombre']); ?>"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tipo" class="form-label">
                                    <i class="fas fa-tag"></i> Tipo de Local
                                </label>
                                <select class="form-control form-control-modern" 
                                        id="tipo" 
                                        name="tipo"
                                        data-original="<?php echo $originalLocal['tipo']; ?>"
                                        required>
                                    <option value="sucursal" <?php echo $local['tipo'] === 'sucursal' ? 'selected' : ''; ?>>Sucursal</option>
                                    <option value="bodega" <?php echo $local['tipo'] === 'bodega' ? 'selected' : ''; ?>>Bodega</option>
                                    <option value="oficina" <?php echo $local['tipo'] === 'oficina' ? 'selected' : ''; ?>>Oficina</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Dirección
                            </label>
                            <input type="text" 
                                   class="form-control form-control-modern" 
                                   id="direccion" 
                                   name="direccion" 
                                   value="<?php echo htmlspecialchars($local['direccion']); ?>"
                                   data-original="<?php echo htmlspecialchars($originalLocal['direccion']); ?>"
                                   required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">
                                    <i class="fas fa-phone"></i> Teléfono
                                </label>
                                <input type="tel" 
                                       class="form-control form-control-modern" 
                                       id="telefono" 
                                       name="telefono" 
                                       value="<?php echo htmlspecialchars($local['telefono']); ?>"
                                       data-original="<?php echo htmlspecialchars($originalLocal['telefono']); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">
                                    <i class="fas fa-toggle-on"></i> Estado
                                </label>
                                <select class="form-control form-control-modern" 
                                        id="estado" 
                                        name="estado"
                                        data-original="<?php echo $originalLocal['estado']; ?>"
                                        required>
                                    <option value="activo" <?php echo $local['estado'] === 'activo' ? 'selected' : ''; ?>>Activo</option>
                                    <option value="inactivo" <?php echo $local['estado'] === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="form-label">
                                <i class="fas fa-align-left"></i> Descripción
                            </label>
                            <textarea class="form-control form-control-modern" 
                                      id="descripcion" 
                                      name="descripcion" 
                                      rows="3"
                                      data-original="<?php echo htmlspecialchars($originalLocal['descripcion']); ?>"><?php echo htmlspecialchars($local['descripcion']); ?></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/RMIE/app/controllers/LocalController.php?action=index" 
                               class="btn btn-modern btn-secondary-modern me-md-2">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="button" 
                                    class="btn btn-modern btn-warning-modern me-md-2"
                                    onclick="resetForm()">
                                <i class="fas fa-undo"></i> Resetear
                            </button>
                            <button type="submit" class="btn btn-modern btn-success-modern" id="submitBtn">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-md-4">
                    <div class="comparison-card">
                        <div class="comparison-title">
                            <i class="fas fa-exchange-alt"></i> Comparación de Cambios
                        </div>
                        <div id="changes-content">
                            <div class="text-center text-white-50 py-3">
                                <i class="fas fa-info-circle"></i><br>
                                Los cambios aparecerán aquí
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-modern alert-warning-modern mt-3">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Consejo:</strong> Los campos modificados se resaltan en dorado
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('load', function() {
            document.getElementById('loadingScreen').style.opacity = '0';
            setTimeout(function() {
                document.getElementById('loadingScreen').style.display = 'none';
            }, 500);
        });

        // Original values for comparison
        const originalValues = {
            nombre: document.getElementById('nombre').dataset.original,
            tipo: document.getElementById('tipo').dataset.original,
            direccion: document.getElementById('direccion').dataset.original,
            telefono: document.getElementById('telefono').dataset.original,
            estado: document.getElementById('estado').dataset.original,
            descripcion: document.getElementById('descripcion').dataset.original
        };

        // Check for changes and update UI
        function checkChanges() {
            const fields = ['nombre', 'tipo', 'direccion', 'telefono', 'estado', 'descripcion'];
            let hasChanges = false;
            let changesHtml = '';

            fields.forEach(field => {
                const input = document.getElementById(field);
                const currentValue = input.value;
                const originalValue = originalValues[field];
                
                if (currentValue !== originalValue) {
                    hasChanges = true;
                    input.classList.add('changed');
                    
                    const fieldName = input.previousElementSibling.textContent.replace(/[^\w\s]/gi, '').trim();
                    changesHtml += `
                        <div class="comparison-item">
                            <div class="comparison-label">${fieldName}:</div>
                            <div class="comparison-values">
                                <span class="comparison-original">${originalValue || '-'}</span>
                                <i class="fas fa-arrow-right comparison-arrow"></i>
                                <span class="comparison-new">${currentValue || '-'}</span>
                            </div>
                        </div>
                    `;
                } else {
                    input.classList.remove('changed');
                }
            });

            const changesContent = document.getElementById('changes-content');
            if (hasChanges) {
                changesContent.innerHTML = changesHtml;
                document.getElementById('submitBtn').disabled = false;
            } else {
                changesContent.innerHTML = `
                    <div class="text-center text-white-50 py-3">
                        <i class="fas fa-check-circle"></i><br>
                        Sin cambios detectados
                    </div>
                `;
                document.getElementById('submitBtn').disabled = true;
            }
        }

        // Reset form to original values
        function resetForm() {
            Object.keys(originalValues).forEach(field => {
                document.getElementById(field).value = originalValues[field];
            });
            checkChanges();
        }

        // Event listeners for all form fields
        document.getElementById('nombre').addEventListener('input', checkChanges);
        document.getElementById('tipo').addEventListener('change', checkChanges);
        document.getElementById('direccion').addEventListener('input', checkChanges);
        document.getElementById('telefono').addEventListener('input', checkChanges);
        document.getElementById('estado').addEventListener('change', checkChanges);
        document.getElementById('descripcion').addEventListener('input', checkChanges);

        // Form validation
        document.getElementById('localForm').addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const direccion = document.getElementById('direccion').value.trim();

            if (!nombre) {
                e.preventDefault();
                alert('El nombre del local es obligatorio');
                document.getElementById('nombre').focus();
                return;
            }

            if (!direccion) {
                e.preventDefault();
                alert('La dirección es obligatoria');
                document.getElementById('direccion').focus();
                return;
            }
        });

        // Initialize change detection
        checkChanges();
    </script>
</body>
</html>