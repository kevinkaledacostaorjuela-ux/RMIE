<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../../index.php');
    exit();
}

require_once '../../models/Local.php';

$id = $_GET['id'] ?? 0;
$errors = [];
$success = '';

if (!$id) {
    header('Location: ../../controllers/LocalController.php?action=index');
    exit();
}

require_once '../../config/db.php';

try {
    $local = Local::getById($conn, $id);
    
    if (!$local) {
        header('Location: ../../controllers/LocalController.php?action=index');
        exit();
    }
    
    // Verificar si se puede eliminar
    $canDelete = Local::canDelete($conn, $id);
    
} catch (Exception $e) {
    $errors[] = 'Error al cargar el local: ' . $e->getMessage();
    $local = [
        'id' => $id,
        'nombre' => 'Local no encontrado',
        'direccion' => '',
        'telefono' => '',
        'tipo' => '',
        'estado' => '',
        'descripcion' => ''
    ];
    $canDelete = false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_eliminar'])) {
    if ($canDelete) {
        try {
            $result = Local::delete($conn, $id);
            if ($result) {
                $success = 'Local eliminado exitosamente';
                // Redireccionar después de 2 segundos
                echo "<script>setTimeout(function(){ window.location.href = '../../controllers/LocalController.php?action=index'; }, 2000);</script>";
            } else {
                $errors[] = 'Error al eliminar el local';
            }
        } catch (Exception $e) {
            $errors[] = 'Error: ' . $e->getMessage();
        }
    } else {
        $errors[] = 'No se puede eliminar este local porque tiene registros asociados';
    }
}

// Obtener estadísticas del local
$localStats = [];
try {
    $localStats = Local::getLocalStats($conn, $id);
} catch (Exception $e) {
    // Si hay error, usar valores por defecto
    $localStats = [
        'productos' => 0,
        'ventas' => 0,
        'clientes' => 0
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Local - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../../public/css/styles.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .delete-container {
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

        .warning-icon {
            font-size: 4rem;
            color: #ff6b6b;
            text-align: center;
            margin-bottom: 20px;
        }

        .local-info-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .local-detail {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .local-detail:last-child {
            border-bottom: none;
        }

        .local-label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
        }

        .local-value {
            font-weight: 500;
            color: #fff;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .stat-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 5px;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
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

        .btn-danger-modern {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
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

        .btn-modern:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
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

        .confirmation-section {
            background: rgba(231, 76, 60, 0.1);
            border: 2px solid #e74c3c;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
        }

        .confirmation-text {
            color: #fff;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .dependencies-section {
            background: rgba(255, 193, 7, 0.1);
            border: 2px solid #ffc107;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
        }

        .dependencies-title {
            color: #ffc107;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
        }

        .dependency-item {
            color: #fff;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dependency-item:last-child {
            border-bottom: none;
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

        .shake {
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-content">
            <div class="spinner"></div>
            <h3>Cargando Confirmación...</h3>
            <p>Verificando dependencias</p>
        </div>
    </div>

    <div class="container">
        <div class="delete-container">
            <div class="warning-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>

            <h1 class="page-title">
                Eliminar Local #<?php echo $local['id']; ?>
            </h1>

            <?php if ($success): ?>
                <div class="alert alert-modern alert-success-modern">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    <br><small>Redirigiendo en unos segundos...</small>
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

            <!-- Información del Local -->
            <div class="local-info-card">
                <h3 class="text-white text-center mb-3">
                    <i class="fas fa-building"></i> Información del Local
                </h3>
                
                <div class="local-detail">
                    <span class="local-label">
                        <i class="fas fa-tag"></i> Nombre:
                    </span>
                    <span class="local-value"><?php echo htmlspecialchars($local['nombre']); ?></span>
                </div>
                
                <div class="local-detail">
                    <span class="local-label">
                        <i class="fas fa-map-marker-alt"></i> Dirección:
                    </span>
                    <span class="local-value"><?php echo htmlspecialchars($local['direccion']); ?></span>
                </div>
                
                <?php if (!empty($local['telefono'])): ?>
                <div class="local-detail">
                    <span class="local-label">
                        <i class="fas fa-phone"></i> Teléfono:
                    </span>
                    <span class="local-value"><?php echo htmlspecialchars($local['telefono']); ?></span>
                </div>
                <?php endif; ?>
                
                <div class="local-detail">
                    <span class="local-label">
                        <i class="fas fa-building"></i> Tipo:
                    </span>
                    <span class="local-value"><?php echo ucfirst($local['tipo']); ?></span>
                </div>
                
                <div class="local-detail">
                    <span class="local-label">
                        <i class="fas fa-toggle-on"></i> Estado:
                    </span>
                    <span class="local-value"><?php echo ucfirst($local['estado']); ?></span>
                </div>
                
                <?php if (!empty($local['descripcion'])): ?>
                <div class="local-detail">
                    <span class="local-label">
                        <i class="fas fa-align-left"></i> Descripción:
                    </span>
                    <span class="local-value"><?php echo htmlspecialchars($local['descripcion']); ?></span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Estadísticas -->
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $localStats['productos'] ?? 0; ?></div>
                    <div class="stat-label">Productos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $localStats['ventas'] ?? 0; ?></div>
                    <div class="stat-label">Ventas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $localStats['clientes'] ?? 0; ?></div>
                    <div class="stat-label">Clientes</div>
                </div>
            </div>

            <?php if (!$canDelete): ?>
                <!-- Sección de dependencias -->
                <div class="dependencies-section">
                    <div class="dependencies-title">
                        <i class="fas fa-link"></i> Este local tiene registros asociados
                    </div>
                    <div class="dependency-item">
                        <i class="fas fa-box"></i> Productos registrados en este local
                    </div>
                    <div class="dependency-item">
                        <i class="fas fa-shopping-cart"></i> Ventas realizadas en este local
                    </div>
                    <div class="dependency-item">
                        <i class="fas fa-users"></i> Clientes asignados a este local
                    </div>
                </div>

                <div class="alert alert-modern alert-warning-modern">
                    <i class="fas fa-info-circle"></i>
                    <strong>No se puede eliminar:</strong> Este local tiene información asociada. 
                    Elimine primero todos los registros relacionados o cambie el estado a "Inactivo".
                </div>
            <?php else: ?>
                <!-- Sección de confirmación -->
                <div class="confirmation-section">
                    <div class="confirmation-text">
                        <i class="fas fa-exclamation-triangle"></i>
                        ¿Está completamente seguro de que desea eliminar este local?
                    </div>
                    <div class="text-center text-white">
                        <strong>Esta acción no se puede deshacer</strong>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Botones de acción -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="../../controllers/LocalController.php?action=index" 
                   class="btn btn-modern btn-secondary-modern me-md-2">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                
                <?php if ($canDelete && !$success): ?>
                    <form method="POST" style="display: inline;" id="deleteForm">
                        <button type="button" 
                                class="btn btn-modern btn-danger-modern"
                                onclick="showConfirmation()">
                            <i class="fas fa-trash"></i> Eliminar Local
                        </button>
                        <input type="hidden" name="confirmar_eliminar" value="1">
                    </form>
                <?php else: ?>
                    <a href="../../controllers/LocalController.php?action=edit&id=<?php echo $local['id']; ?>" 
                       class="btn btn-modern btn-secondary-modern">
                        <i class="fas fa-edit"></i> Editar Local
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.2);">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-exclamation-triangle text-danger"></i>
                        Confirmación Final
                    </h5>
                </div>
                <div class="modal-body text-center">
                    <div class="text-white mb-3">
                        <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                        <h4>¿Confirma la eliminación?</h4>
                        <p>Esta acción eliminará permanentemente el local<br>
                        <strong>"<?php echo htmlspecialchars($local['nombre']); ?>"</strong></p>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-modern btn-secondary-modern" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-modern btn-danger-modern" onclick="confirmDelete()">
                        <i class="fas fa-check"></i> Sí, Eliminar
                    </button>
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
            }, 1000);
        });

        let confirmationModal;

        function showConfirmation() {
            confirmationModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            confirmationModal.show();
        }

        function confirmDelete() {
            confirmationModal.hide();
            
            // Mostrar loader
            const button = event.target;
            const originalContent = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Eliminando...';
            button.disabled = true;
            
            // Enviar formulario
            setTimeout(function() {
                document.getElementById('deleteForm').submit();
            }, 1000);
        }

        // Efecto de shake en elementos de alerta
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-danger-modern, .alert-warning-modern');
            alerts.forEach(alert => {
                alert.classList.add('shake');
            });
        });
    </script>
</body>
</html>