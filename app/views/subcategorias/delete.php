<?php
// Página de confirmación de eliminación de subcategoría
session_start();
require_once '../../../config/db.php';
require_once '../../models/SubcategorySimple.php';
require_once '../../models/Category.php';

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: /RMIE/app/controllers/SubcategoryController.php?accion=index');
    exit();
}

$id_subcategoria = $_GET['id'];
$subcategoria = SubcategorySimple::getById($conn, $id_subcategoria);

if (!$subcategoria) {
    $_SESSION['error'] = 'Subcategoría no encontrada.';
    header('Location: /RMIE/app/controllers/SubcategoryController.php?accion=index');
    exit();
}

// Verificar dependencias
$dependencies = SubcategorySimple::checkDependencies($conn, $id_subcategoria);

// Obtener información de la categoría padre
$categoria = Category::getById($conn, $subcategoria['id_categoria']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Subcategoría - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
    <script src="/RMIE/public/js/selenium-messages.js"></script>
    <style>
        .delete-confirmation-page {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .confirmation-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .alert-confirmation {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-lg {
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-danger {
            background: linear-gradient(45deg, #ff416c, #ff4757);
            border: none;
            box-shadow: 0 4px 15px rgba(255, 65, 108, 0.4);
        }
        
        .btn-danger:hover {
            background: linear-gradient(45deg, #ff3742, #ff3838);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 65, 108, 0.6);
        }
        
        .btn-secondary {
            background: linear-gradient(45deg, #6c757d, #495057);
            border: none;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
        }
        
        .btn-secondary:hover {
            background: linear-gradient(45deg, #5a6268, #3d4043);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.6);
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, #0056b3, #004085);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.6);
        }
    </style>
</head>
<body class="delete-confirmation-page">

    <div class="container mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="breadcrumb-custom">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/RMIE/app/views/dashboard.php">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="/RMIE/app/controllers/SubcategoryController.php?accion=index">
                        <i class="fas fa-layer-group"></i> Subcategorías
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    <i class="fas fa-trash"></i> Eliminar Subcategoría
                </li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card subcategorias-form">
                    <div class="card-header text-center">
                        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Confirmar Eliminación</h3>
                    </div>
                    <div class="card-body">
                        
                        <!-- Información de la subcategoría -->
                        <div class="alert alert-info alert-confirmation">
                            <h5><i class="fas fa-info-circle"></i> Subcategoría a eliminar:</h5>
                            <p><strong>ID:</strong> <?php echo $subcategoria['id_subcategoria']; ?></p>
                            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($subcategoria['nombre']); ?></p>
                            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($subcategoria['descripcion'] ?? 'Sin descripción'); ?></p>
                            <p><strong>Categoría Padre:</strong> <?php echo htmlspecialchars($subcategoria['categoria_nombre'] ?? 'No disponible'); ?></p>
                        </div>

                        <?php if (!empty($dependencies)): ?>
                            <!-- Mostrar dependencias -->
                            <div class="alert alert-warning alert-confirmation">
                                <h5><i class="fas fa-exclamation-triangle warning-icon"></i> Dependencias encontradas:</h5>
                                
                                <?php if (isset($dependencies['productos'])): ?>
                                    <p><i class="fas fa-box"></i> <strong><?php echo $dependencies['productos']; ?></strong> producto(s) asociado(s)</p>
                                <?php endif; ?>
                                
                                <?php if (isset($dependencies['ventas'])): ?>
                                    <div class="alert alert-danger alert-confirmation mt-2">
                                        <p><i class="fas fa-shopping-cart"></i> <strong><?php echo $dependencies['ventas']; ?></strong> venta(s) relacionada(s)</p>
                                        <small><strong>¡ATENCIÓN!</strong> Esta subcategoría no puede ser eliminada porque tiene ventas asociadas.</small>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (!isset($dependencies['ventas'])): ?>
                                <!-- Opciones de eliminación -->
                                <div class="alert alert-warning alert-confirmation">
                                    <h6><i class="fas fa-cogs"></i> Opciones de eliminación:</h6>
                                    <ul>
                                        <li>Los productos asociados serán desvinculados (se establecerá subcategoría como NULL)</li>
                                        <li>Los productos mantendrán su categoría padre</li>
                                    </ul>
                                </div>
                                
                                <div class="d-flex gap-3 justify-content-center mt-4">
                                    <a href="/RMIE/app/controllers/SubcategoryController.php?accion=delete&id=<?php echo $id_subcategoria; ?>&force=1" 
                                       class="btn btn-danger btn-lg"
                                       onclick="return confirmAction('acción general')">
                                        <i class="fas fa-trash-alt"></i> Eliminar y Desasociar Productos
                                    </a>
                                    <a href="/RMIE/app/controllers/SubcategoryController.php?accion=index" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                </div>
                            <?php else: ?>
                                <!-- No se puede eliminar -->
                                <div class="text-center mt-4">
                                    <p class="text-danger fs-5 mb-4"><strong>Esta subcategoría no puede ser eliminada debido a las ventas asociadas.</strong></p>
                                    <a href="/RMIE/app/controllers/SubcategoryController.php?accion=index" class="btn btn-primary btn-lg">
                                        <i class="fas fa-arrow-left"></i> Regresar
                                    </a>
                                </div>
                            <?php endif; ?>

                        <?php else: ?>
                            <!-- Sin dependencias -->
                            <div class="alert alert-success alert-confirmation">
                                <p><i class="fas fa-check-circle"></i> Esta subcategoría no tiene dependencias y puede ser eliminada de forma segura.</p>
                            </div>
                            
                            <div class="d-flex gap-3 justify-content-center mt-4">
                                <a href="/RMIE/app/controllers/SubcategoryController.php?accion=delete&id=<?php echo $id_subcategoria; ?>" 
                                   class="btn btn-danger btn-lg"
                                   onclick="return confirmAction('acción general')">
                                    <i class="fas fa-trash-alt"></i> Confirmar Eliminación
                                </a>
                                <a href="/RMIE/app/controllers/SubcategoryController.php?accion=index" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        <?php endif; ?>

                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar que los botones existen y tienen los enlaces correctos
            const deleteButtons = document.querySelectorAll('a[href*="delete"]');
            
            deleteButtons.forEach(function(button, index) {
                
                // Verificar que el enlace no esté vacío o malformado
                if (!button.href || button.href.includes('undefined') || button.href === window.location.href + '#') {
                    console.error('❌ ERROR: Enlace malformado encontrado:', button.href);
                    button.style.backgroundColor = '#ff0000';
                    button.style.color = '#fff';
                    button.textContent = 'ERROR EN ENLACE';
                }
            });
            
            // Agregar evento click para debug
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    console.log('👆 DEBUG: Click en botón:', this.href);
                    console.log('🎯 DEBUG: Texto del botón:', this.textContent.trim());
                    
                    // Verificar que el href no esté vacío
                    if (!this.href || this.href === window.location.href + '#') {
                        e.preventDefault();
                        alert('ERROR: El enlace del botón está mal configurado. Revise la consola para más detalles.');
                        return false;
                    }
                });
            });
        });
    </script>

</body>
</html>
