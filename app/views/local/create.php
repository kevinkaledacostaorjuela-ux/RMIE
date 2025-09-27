<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../../index.php');
    exit();
}

$errors = [];
$success = '';
$local = [
    'nombre_local' => '',
    'direccion' => '',
    'cel_local' => '',
    'localidad' => '',
    'barrio' => '',
    'estado' => 'activo',
    'descripcion' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $conn;
    require_once '../../config/db.php';
    require_once __DIR__ . '/../../../app/models/Local.php';
    
    $local = [
        'nombre_local' => trim($_POST['nombre'] ?? ''),
        'direccion' => trim($_POST['direccion'] ?? ''),
        'cel_local' => trim($_POST['telefono'] ?? ''),
        'localidad' => $_POST['localidad'] ?? '',
        'barrio' => $_POST['barrio'] ?? '',
        'estado' => $_POST['estado'] ?? 'activo',
        'descripcion' => $_POST['descripcion'] ?? ''
    ];
    
    // Validaciones
    if (empty($local['nombre_local'])) {
        $errors[] = 'El nombre es obligatorio';
    }
    if (empty($local['direccion'])) {
        $errors[] = 'La dirección es obligatoria';
    }
    if (!in_array($local['estado'], ['activo', 'inactivo'])) {
        $errors[] = 'Estado inválido';
    }
    
    if (empty($errors)) {
        try {
            $id = Local::create($conn, $local);
            if ($id) {
                $success = 'Local creado exitosamente';
                $local = [
                    'nombre_local' => '',
                    'direccion' => '',
                    'cel_local' => '',
                    'localidad' => '',
                    'barrio' => '',
                    'estado' => 'activo',
                    'descripcion' => ''
                ];
            } else {
                $errors[] = 'Error al crear el local';
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
    <title>Crear Local - RMIE</title>
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
            max-width: 800px;
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

        .preview-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .preview-title {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
        }

        .preview-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .preview-item:last-child {
            border-bottom: none;
        }

        .preview-label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
        }

        .preview-value {
            font-weight: 500;
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

        .form-floating-modern {
            position: relative;
        }

        .form-floating-modern .form-control-modern {
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
        }

        .form-floating-modern > label {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 1rem 0.75rem;
            overflow: hidden;
            text-align: start;
            text-overflow: ellipsis;
            white-space: nowrap;
            pointer-events: none;
            border: 1px solid transparent;
            transform-origin: 0 0;
            transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
        }

        .form-floating-modern > .form-control-modern:focus ~ label,
        .form-floating-modern > .form-control-modern:not(:placeholder-shown) ~ label {
            opacity: 0.65;
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-content">
            <div class="spinner"></div>
            <h3>Cargando Formulario...</h3>
            <p>Preparando la creación de local</p>
        </div>
    </div>

    <div class="container">
        <div class="form-container">
            <h1 class="page-title">
                <i class="fas fa-plus-circle"></i> Crear Nuevo Local
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
                    <form method="POST" id="localForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating-modern">
                                    <input type="text" 
                                           class="form-control form-control-modern" 
                                           id="nombre" 
                                           name="nombre" 
                                           value="<?php echo htmlspecialchars($local['nombre_local']); ?>"
                                           placeholder="Nombre del local"
                                           required>
                                    <label for="nombre">
                                        <i class="fas fa-building"></i> Nombre del Local
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating-modern">
                                    <select class="form-control form-control-modern" 
                                            id="tipo" 
                                            name="tipo"
                                            required>
                                        <option value="sucursal" <?php echo $local['tipo'] === 'sucursal' ? 'selected' : ''; ?>>Sucursal</option>
                                        <option value="bodega" <?php echo $local['tipo'] === 'bodega' ? 'selected' : ''; ?>>Bodega</option>
                                        <option value="oficina" <?php echo $local['tipo'] === 'oficina' ? 'selected' : ''; ?>>Oficina</option>
                                    </select>
                                    <label for="tipo">
                                        <i class="fas fa-tag"></i> Tipo de Local
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating-modern">
                                <input type="text" 
                                       class="form-control form-control-modern" 
                                       id="direccion" 
                                       name="direccion" 
                                       value="<?php echo htmlspecialchars($local['direccion']); ?>"
                                       placeholder="Dirección completa"
                                       required>
                                <label for="direccion">
                                    <i class="fas fa-map-marker-alt"></i> Dirección
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating-modern">
                                    <input type="tel" 
                                           class="form-control form-control-modern" 
                                           id="telefono" 
                                           name="telefono" 
                                           value="<?php echo htmlspecialchars($local['cel_local']); ?>"
                                           placeholder="Número de teléfono">
                                    <label for="telefono">
                                        <i class="fas fa-phone"></i> Teléfono
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating-modern">
                                    <select class="form-control form-control-modern" 
                                            id="estado" 
                                            name="estado"
                                            required>
                                        <option value="activo" <?php echo $local['estado'] === 'activo' ? 'selected' : ''; ?>>Activo</option>
                                        <option value="inactivo" <?php echo $local['estado'] === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                                    </select>
                                    <label for="estado">
                                        <i class="fas fa-toggle-on"></i> Estado
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-floating-modern">
                                <textarea class="form-control form-control-modern" 
                                          id="descripcion" 
                                          name="descripcion" 
                                          rows="3"
                                          placeholder="Descripción adicional"><?php echo htmlspecialchars($local['descripcion']); ?></textarea>
                                <label for="descripcion">
                                    <i class="fas fa-align-left"></i> Descripción (Opcional)
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/RMIE/app/controllers/LocalController.php?action=index" 
                               class="btn btn-modern btn-secondary-modern me-md-2">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-modern btn-success-modern">
                                <i class="fas fa-save"></i> Crear Local
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-md-4">
                    <div class="preview-card">
                        <div class="preview-title">
                            <i class="fas fa-eye"></i> Vista Previa
                        </div>
                        <div id="preview-content">
                            <div class="preview-item">
                                <span class="preview-label">Nombre:</span>
                                <span class="preview-value" id="preview-nombre">-</span>
                            </div>
                            <div class="preview-item">
                                <span class="preview-label">Tipo:</span>
                                <span class="preview-value" id="preview-tipo">Sucursal</span>
                            </div>
                            <div class="preview-item">
                                <span class="preview-label">Dirección:</span>
                                <span class="preview-value" id="preview-direccion">-</span>
                            </div>
                            <div class="preview-item">
                                <span class="preview-label">Teléfono:</span>
                                <span class="preview-value" id="preview-telefono">-</span>
                            </div>
                            <div class="preview-item">
                                <span class="preview-label">Estado:</span>
                                <span class="preview-value" id="preview-estado">Activo</span>
                            </div>
                            <div class="preview-item">
                                <span class="preview-label">Descripción:</span>
                                <span class="preview-value" id="preview-descripcion">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Loading screen
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loadingScreen').style.opacity = '0';
                setTimeout(function() {
                    document.getElementById('loadingScreen').style.display = 'none';
                }, 500);
            }, 800);
        });

        // Real-time preview
        function updatePreview() {
            const nombre = document.getElementById('nombre').value || '-';
            const tipo = document.getElementById('tipo').value || 'Sucursal';
            const direccion = document.getElementById('direccion').value || '-';
            const telefono = document.getElementById('telefono').value || '-';
            const estado = document.getElementById('estado').value || 'Activo';
            const descripcion = document.getElementById('descripcion').value || '-';

            document.getElementById('preview-nombre').textContent = nombre;
            document.getElementById('preview-tipo').textContent = tipo.charAt(0).toUpperCase() + tipo.slice(1);
            document.getElementById('preview-direccion').textContent = direccion;
            document.getElementById('preview-telefono').textContent = telefono;
            document.getElementById('preview-estado').textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
            document.getElementById('preview-descripcion').textContent = descripcion;
        }

        // Event listeners for real-time preview
        document.getElementById('nombre').addEventListener('input', updatePreview);
        document.getElementById('tipo').addEventListener('change', updatePreview);
        document.getElementById('direccion').addEventListener('input', updatePreview);
        document.getElementById('telefono').addEventListener('input', updatePreview);
        document.getElementById('estado').addEventListener('change', updatePreview);
        document.getElementById('descripcion').addEventListener('input', updatePreview);

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

        // Initialize preview
        updatePreview();
    </script>
</body>
</html>