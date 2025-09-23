<?php
// ...existing code...
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Subcategoría - RMIE</title>
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
                <li class="breadcrumb-item active" aria-current="page">Agregar</li>
            </ol>
        </nav>

        <h1><i class="fas fa-plus-circle"></i> Agregar Nueva Subcategoría</h1>
        
        <div class="subcategorias-form">
            <form method="POST" action="/RMIE/app/controllers/SubcategoryController.php?accion=create" id="formSubcategoria">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">
                                <i class="fas fa-tag"></i> Nombre de la Subcategoría:
                            </label>
                            <input type="text" 
                                   id="nombre" 
                                   name="nombre" 
                                   required 
                                   placeholder="Ingrese el nombre de la subcategoría"
                                   maxlength="45">
                            <small class="form-text text-muted">Máximo 45 caracteres</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_categoria">
                                <i class="fas fa-folder"></i> Categoría Principal:
                            </label>
                            <select id="id_categoria" name="id_categoria" required>
                                <option value="">Seleccione una categoría</option>
                                <?php if (isset($categorias) && is_array($categorias)): ?>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= htmlspecialchars($cat->id_categoria) ?>">
                                            <?= htmlspecialchars($cat->nombre) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No hay categorías disponibles</option>
                                <?php endif; ?>
                            </select>
                            <small class="form-text text-muted">Seleccione la categoría a la que pertenecerá esta subcategoría</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="descripcion">
                        <i class="fas fa-align-left"></i> Descripción:
                    </label>
                    <input type="text" 
                           id="descripcion" 
                           name="descripcion" 
                           required 
                           placeholder="Ingrese una descripción de la subcategoría"
                           maxlength="45">
                    <small class="form-text text-muted">Máximo 45 caracteres. Descripción breve de la subcategoría.</small>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="me-3">
                        <i class="fas fa-save"></i> Guardar Subcategoría
                    </button>
                </div>
            </form>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="/RMIE/app/controllers/SubcategoryController.php?accion=index" class="btn-subcategorias btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
            <a href="/RMIE/app/views/dashboard.php" class="btn-subcategorias btn-secondary">
                <i class="fas fa-home"></i> Menú principal
            </a>
        </div>

        <!-- Información adicional -->
        <div class="alert alert-info mt-4">
            <i class="fas fa-lightbulb"></i>
            <strong>Consejo:</strong> Asegúrese de que el nombre de la subcategoría sea descriptivo y único dentro de su categoría. Esto facilitará la organización de sus productos.
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación del formulario
        document.getElementById('formSubcategoria').addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const descripcion = document.getElementById('descripcion').value.trim();
            const categoria = document.getElementById('id_categoria').value;

            if (!nombre || !descripcion || !categoria) {
                e.preventDefault();
                alert('Por favor, complete todos los campos obligatorios.');
                return false;
            }

            if (nombre.length < 3) {
                e.preventDefault();
                alert('El nombre de la subcategoría debe tener al menos 3 caracteres.');
                return false;
            }

            if (descripcion.length < 3) {
                e.preventDefault();
                alert('La descripción debe tener al menos 3 caracteres.');
                return false;
            }
        });

        // Contador de caracteres
        document.getElementById('descripcion').addEventListener('input', function() {
            const maxLength = 45;
            const currentLength = this.value.length;
            const remaining = maxLength - currentLength;
            
            // Agregar contador visual si no existe
            let counter = document.getElementById('charCounter');
            if (!counter) {
                counter = document.createElement('small');
                counter.id = 'charCounter';
                counter.className = 'form-text text-muted';
                this.parentNode.appendChild(counter);
            }
            
            counter.textContent = `${remaining} caracteres restantes`;
        });
    </script>
</body>
</html>
</body>
</html>
