<?php
// Página de confirmación de eliminación de categoría
session_start();
require_once '../../../config/db.php';
require_once '../../models/Category.php';

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: /RMIE/app/controllers/CategoryController.php?accion=index');
    exit();
}

$id_categoria = $_GET['id'];
$categoria = Category::getById($conn, $id_categoria);

if (!$categoria) {
    $_SESSION['error'] = 'Categoría no encontrada.';
    header('Location: /RMIE/app/controllers/CategoryController.php?accion=index');
    exit();
}

// Verificar dependencias
$dependencies = Category::checkDependencies($conn, $id_categoria);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Categoría - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/bootstrap.min.css">
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="delete-confirmation-page">

<div class="categorias-container confirmation-container">
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
                    <a href="/RMIE/app/controllers/CategoryController.php?accion=index">
                        <i class="fas fa-tags"></i> Categorías
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    <i class="fas fa-trash"></i> Eliminar Categoría
                </li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card categorias-form">
                    <div class="card-header text-center">
                        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Confirmar Eliminación</h3>
                    </div>
                    <div class="card-body">
                        
                        <!-- Información de la categoría -->
                        <div class="alert alert-info alert-confirmation">
                            <h5><i class="fas fa-info-circle"></i> Categoría a eliminar:</h5>
                            <p><strong>ID:</strong> <?php echo $categoria->id_categoria; ?></p>
                            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($categoria->nombre); ?></p>
                            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($categoria->descripcion ?? 'Sin descripción'); ?></p>
                            <p><strong>Fecha de creación:</strong> <?php echo $categoria->fecha_creacion ? date('d/m/Y H:i', strtotime($categoria->fecha_creacion)) : 'No disponible'; ?></p>
                        </div>

                        <?php if (!empty($dependencies)): ?>
                            <!-- Mostrar dependencias -->
                            <div class="alert alert-warning alert-confirmation">
                                <h5><i class="fas fa-exclamation-triangle warning-icon"></i> Dependencias encontradas:</h5>
                                
                                <?php if (isset($dependencies['productos'])): ?>
                                    <p><i class="fas fa-box"></i> <strong><?php echo $dependencies['productos']; ?></strong> producto(s) asociado(s)</p>
                                <?php endif; ?>
                                
                                <?php if (isset($dependencies['subcategorias'])): ?>
                                    <p><i class="fas fa-layer-group"></i> <strong><?php echo $dependencies['subcategorias']; ?></strong> subcategoría(s) asociada(s)</p>
                                <?php endif; ?>
                                
                                <?php if (isset($dependencies['ventas'])): ?>
                                    <div class="alert alert-danger alert-confirmation mt-2">
                                        <p><i class="fas fa-shopping-cart"></i> <strong><?php echo $dependencies['ventas']; ?></strong> venta(s) relacionada(s)</p>
                                        <small><strong>¡ATENCIÓN!</strong> Esta categoría no puede ser eliminada porque tiene ventas asociadas.</small>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (!isset($dependencies['ventas'])): ?>
                                <!-- Opciones de eliminación -->
                                <div class="alert alert-warning alert-confirmation">
                                    <h6><i class="fas fa-cogs"></i> Opciones de eliminación:</h6>
                                    <ul>
                                        <li>Los productos asociados serán desvinculados (se establecerá categoría como NULL)</li>
                                        <li>Las subcategorías asociadas serán eliminadas</li>
                                    </ul>
                                </div>
                                
                                <div class="confirmation-buttons">
                                    <a href="/RMIE/app/controllers/CategoryController.php?accion=delete&id=<?php echo $id_categoria; ?>&force=1" 
                                       class="btn btn-confirm-delete">
                                        <i class="fas fa-trash-alt"></i> Eliminar con Dependencias
                                    </a>
                                    <a href="/RMIE/app/controllers/CategoryController.php?accion=index" class="btn btn-cancel-delete">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                </div>
                            <?php else: ?>
                                <!-- No se puede eliminar -->
                                <div class="confirmation-buttons">
                                    <p class="text-danger"><strong>Esta categoría no puede ser eliminada debido a las ventas asociadas.</strong></p>
                                    <a href="/RMIE/app/controllers/CategoryController.php?accion=index" class="btn btn-cancel-delete">
                                        <i class="fas fa-arrow-left"></i> Regresar
                                    </a>
                                </div>
                            <?php endif; ?>

                        <?php else: ?>
                            <!-- Sin dependencias -->
                            <div class="alert alert-success alert-confirmation">
                                <p><i class="fas fa-check-circle"></i> Esta categoría no tiene dependencias y puede ser eliminada de forma segura.</p>
                            </div>
                            
                            <div class="confirmation-buttons">
                                <a href="/RMIE/app/controllers/CategoryController.php?accion=delete&id=<?php echo $id_categoria; ?>" 
                                   class="btn btn-confirm-delete">
                                    <i class="fas fa-trash-alt"></i> Confirmar Eliminación
                                </a>
                                <a href="/RMIE/app/controllers/CategoryController.php?accion=index" class="btn btn-cancel-delete">
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

</body>
</html>
