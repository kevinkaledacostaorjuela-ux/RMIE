<?php
// ...existing code...
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Subcategoría - RMIE</title>
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
                <li class="breadcrumb-item active" aria-current="page">Editar</li>
            </ol>
        </nav>

        <h1><i class="fas fa-edit"></i> Editar Subcategoría</h1>
        
        <?php if (isset($subcategoria)): ?>
            <div class="alert alert-warning mb-4">
                <i class="fas fa-info-circle"></i>
                <strong>Editando:</strong> <?= htmlspecialchars($subcategoria->nombre) ?>
                <small class="d-block mt-1">ID: <?= htmlspecialchars($subcategoria->id_subcategoria) ?></small>
            </div>
        <?php endif; ?>
        
        <div class="subcategorias-form">
            <form method="POST" action="" id="formEditSubcategoria">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">
                                <i class="fas fa-tag"></i> Nombre de la Subcategoría:
                            </label>
                            <input type="text" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="<?= htmlspecialchars($subcategoria->nombre ?? '') ?>" 
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
                                        <option value="<?= htmlspecialchars($cat->id_categoria) ?>" 
                                                <?= (isset($subcategoria) && $subcategoria->id_categoria == $cat->id_categoria) ? 'selected' : '' ?>>
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
                           value="<?= htmlspecialchars($subcategoria->descripcion ?? '') ?>"
                           required 
                           placeholder="Ingrese una descripción de la subcategoría"
                           maxlength="45">
                    <small class="form-text text-muted">Máximo 45 caracteres. Descripción breve de la subcategoría.</small>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="me-3">
                        <i class="fas fa-save"></i> Actualizar Subcategoría
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

        <!-- Información de la subcategoría -->
        <?php if (isset($subcategoria)): ?>
        <div class="alert alert-info mt-4">
            <i class="fas fa-info-circle"></i>
            <strong>Información de la subcategoría:</strong>
            <ul class="mb-0 mt-2">
                <li><strong>Fecha de creación:</strong> 
                    <?php 
                    if (isset($subcategoria->fecha_creacion) && !empty($subcategoria->fecha_creacion)) {
                        echo date('d/m/Y H:i', strtotime($subcategoria->fecha_creacion));
                    } else {
                        echo 'No disponible';
                    }
                    ?>
                </li>
                <li><strong>ID:</strong> <?= htmlspecialchars($subcategoria->id_subcategoria) ?></li>
            </ul>
        </div>
        <?php endif; ?>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación del formulario
        document.getElementById('formEditSubcategoria').addEventListener('submit', function(e) {
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

            // Confirmación antes de actualizar
            if (!confirm('¿Está seguro de actualizar esta subcategoría?')) {
                e.preventDefault();
                return false;
            }
        });

        // Contador de caracteres para descripción
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
            
            // Cambiar color si se acerca al límite
            if (remaining < 10) {
                counter.className = 'form-text text-warning';
            } else if (remaining < 5) {
                counter.className = 'form-text text-danger';
            } else {
                counter.className = 'form-text text-muted';
            }
        });

        // Disparar el evento input al cargar la página para mostrar el contador inicial
        document.getElementById('descripcion').dispatchEvent(new Event('input'));
    </script>
</body>
</html>
</body>
</html>
