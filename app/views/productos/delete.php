<?php
// Página de confirmación de eliminación de producto
session_start();
require_once '../../../config/db.php';
require_once '../../models/Product.php';
require_once '../../models/Category.php';
require_once '../../models/SubcategorySimple.php';

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: /RMIE/app/controllers/ProductController.php?accion=index');
    exit();
}

$id_producto = $_GET['id'];
$producto = Product::getByIdArray($conn, $id_producto);

if (!$producto) {
    $_SESSION['error'] = 'Producto no encontrado.';
    header('Location: /RMIE/app/controllers/ProductController.php?accion=index');
    exit();
}

// Verificar dependencias
$dependencies = Product::checkDependencies($conn, $id_producto);

// Obtener información adicional del producto
$categoria = null;
$subcategoria = null;
// La información de categoría y subcategoría ya viene en el array del producto
// No necesitamos consultas adicionales
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Producto - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
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
        
        .product-info-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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
        
        .product-detail {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        
        .product-detail:last-child {
            border-bottom: none;
        }
        
        .stock-badge {
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .stock-high { background: #d4edda; color: #155724; }
        .stock-medium { background: #fff3cd; color: #856404; }
        .stock-low { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body class="delete-confirmation-page">

    <div class="container mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="breadcrumb-custom">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/RMIE/app/views/dashboard.php" style="color: white;">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="/RMIE/app/controllers/ProductController.php?accion=index" style="color: white;">
                        <i class="fas fa-box"></i> Productos
                    </a>
                </li>
                <li class="breadcrumb-item active" style="color: rgba(255,255,255,0.8);">
                    <i class="fas fa-trash"></i> Eliminar Producto
                </li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="confirmation-container">
                    <div class="text-center mb-4">
                        <h2 class="text-white mb-3">
                            <i class="fas fa-exclamation-triangle text-warning"></i> 
                            Confirmar Eliminación de Producto
                        </h2>
                    </div>
                    
                    <!-- Información del producto -->
                    <div class="product-info-card">
                        <h5><i class="fas fa-info-circle text-info"></i> Información del Producto</h5>
                        
                        <div class="product-detail">
                            <strong>ID:</strong>
                            <span class="badge bg-primary">#<?php echo $producto['id_productos']; ?></span>
                        </div>
                        
                        <div class="product-detail">
                            <strong>Nombre:</strong>
                            <span><?php echo htmlspecialchars($producto['nombre']); ?></span>
                        </div>
                        
                        <div class="product-detail">
                            <strong>Descripción:</strong>
                            <span><?php echo htmlspecialchars($producto['descripcion'] ?? 'Sin descripción'); ?></span>
                        </div>
                        
                        <div class="product-detail">
                            <strong>Categoría:</strong>
                            <span><?php echo !empty($producto['categoria_nombre']) ? htmlspecialchars($producto['categoria_nombre']) : 'No asignada'; ?></span>
                        </div>
                        
                        <div class="product-detail">
                            <strong>Subcategoría:</strong>
                            <span><?php echo !empty($producto['subcategoria_nombre']) ? htmlspecialchars($producto['subcategoria_nombre']) : 'No asignada'; ?></span>
                        </div>
                        
                        <div class="product-detail">
                            <strong>Stock:</strong>
                            <?php 
                            $stock = (int)$producto['stock'];
                            $stockClass = $stock <= 5 ? 'stock-low' : ($stock <= 20 ? 'stock-medium' : 'stock-high');
                            ?>
                            <span class="stock-badge <?php echo $stockClass; ?>"><?php echo $stock; ?> unidades</span>
                        </div>
                        
                        <div class="product-detail">
                            <strong>Precio Unitario:</strong>
                            <span class="text-success fw-bold">$<?php echo number_format($producto['precio_unitario'], 2); ?></span>
                        </div>
                        
                        <div class="product-detail">
                            <strong>Marca:</strong>
                            <span><?php echo htmlspecialchars($producto['marca'] ?? 'No especificada'); ?></span>
                        </div>
                    </div>

                    <?php if (!empty($dependencies)): ?>
                        <!-- Mostrar dependencias -->
                        <div class="alert alert-danger alert-confirmation">
                            <h5><i class="fas fa-exclamation-triangle"></i> ¡No se puede eliminar!</h5>
                            
                            <?php if (isset($dependencies['ventas'])): ?>
                                <p><i class="fas fa-shopping-cart"></i> <strong><?php echo $dependencies['ventas']; ?></strong> venta(s) registrada(s) con este producto</p>
                                <hr>
                                <p><strong>⚠️ IMPORTANTE:</strong> Los productos no pueden ser eliminados si tienen ventas asociadas.</p>
                                <p>Esto es para mantener la integridad y trazabilidad de las transacciones comerciales.</p>
                            <?php endif; ?>
                        </div>

                        <!-- No se puede eliminar -->
                        <div class="text-center mt-4">
                            <p class="text-white fs-5 mb-4"><strong>Este producto no puede ser eliminado.</strong></p>
                            <a href="/RMIE/app/controllers/ProductController.php?accion=index" class="btn btn-primary btn-lg">
                                <i class="fas fa-arrow-left"></i> Regresar al Listado
                            </a>
                        </div>

                    <?php else: ?>
                        <!-- Sin dependencias -->
                        <div class="alert alert-success alert-confirmation">
                            <p><i class="fas fa-check-circle"></i> Este producto no tiene ventas asociadas y puede ser eliminado de forma segura.</p>
                            <p><strong>Nota:</strong> Esta acción eliminará permanentemente el producto del inventario.</p>
                        </div>
                        
                        <div class="d-flex gap-3 justify-content-center mt-4">
                            <a href="/RMIE/app/controllers/ProductController.php?accion=delete&id=<?php echo $id_producto; ?>" 
                               class="btn btn-danger btn-lg"
                               onclick="return confirm('¿Está completamente seguro de eliminar el producto \'<?php echo addslashes($producto['nombre']); ?>\'?\n\nEsta acción no se puede deshacer y el producto será eliminado permanentemente del inventario.')">
                                <i class="fas fa-trash-alt"></i> Confirmar Eliminación
                            </a>
                            <a href="/RMIE/app/controllers/ProductController.php?accion=index" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🔧 DEBUG: Página de eliminación de producto cargada');
            console.log('📦 DEBUG: ID Producto: <?php echo $id_producto; ?>');
            console.log('🛒 DEBUG: Tiene ventas: <?php echo !empty($dependencies["ventas"]) ? "Sí (" . $dependencies["ventas"] . ")" : "No"; ?>');
            
            // Verificar botones de eliminación
            const deleteButtons = document.querySelectorAll('a[href*="delete"]');
            console.log(`🔍 DEBUG: Se encontraron ${deleteButtons.length} enlaces de eliminación`);
            
            deleteButtons.forEach(function(button, index) {
                console.log(`🔗 DEBUG: Enlace ${index + 1}: ${button.href}`);
            });
        });
    </script>

</body>
</html>
