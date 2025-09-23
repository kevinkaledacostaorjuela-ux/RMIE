<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Subcategoría - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="subcategorias-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="subcategorias-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/RMIE/app/controllers/SubcategoryController.php?accion=index">Subcategorías</a></li>
                <li class="breadcrumb-item active" aria-current="page">Eliminar</li>
            </ol>
        </nav>

        <h1><i class="fas fa-trash text-danger"></i> Eliminar Subcategoría</h1>
        
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>¡Atención!</strong> Esta acción no se puede deshacer. Al eliminar una subcategoría, también se eliminarán todos los productos asociados a ella.
        </div>

        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-question-circle fa-4x text-warning mb-3"></i>
                <h4>¿Está seguro de que desea eliminar esta subcategoría?</h4>
                <p class="text-muted">Esta acción es permanente y no se puede revertir.</p>
                
                <div class="mt-4">
                    <a href="javascript:history.back()" class="btn-subcategorias btn-secondary me-3">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <a href="#" onclick="confirmarEliminacion()" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Sí, eliminar
                    </a>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="/RMIE/app/controllers/SubcategoryController.php?accion=index" class="btn-subcategorias btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
            <a href="/RMIE/app/views/dashboard.php" class="btn-subcategorias btn-secondary">
                <i class="fas fa-home"></i> Menú principal
            </a>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarEliminacion() {
            const urlParams = new URLSearchParams(window.location.search);
            const id = urlParams.get('id');
            
            if (id) {
                if (confirm('¿Está completamente seguro de eliminar esta subcategoría? Esta acción no se puede deshacer.')) {
                    window.location.href = `/RMIE/app/controllers/SubcategoryController.php?accion=delete&id=${id}`;
                }
            } else {
                alert('Error: No se encontró el ID de la subcategoría a eliminar.');
            }
        }
    </script>
</body>
</html>
