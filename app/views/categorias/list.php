<?php
if (!isset($_SESSION['user'])) {
	header('Location: ../../../index.php');
	exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Gestión de Categorías</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="../../../public/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
	<div class="categorias-panel">
		<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
			<h2 class="categorias-title mb-0">Gestión de Categorías</h2>
			<a href="/RMIE/app/controllers/CategoriaController.php?action=create" class="btn btn-success shadow-sm">+ Nueva Categoría</a>
		</div>
		<form method="get" class="categorias-toolbar row g-3 mb-4 justify-content-center text-center">
			<input type="hidden" name="action" value="list">
			<div class="col-md-5 col-12 mx-auto">
				<input type="text" name="filtro_nombre" class="form-control" placeholder="Filtrar por nombre" value="<?= htmlspecialchars($_GET['filtro_nombre'] ?? '') ?>">
			</div>
			<div class="col-md-3 col-6 mx-auto">
				<select name="orden" class="form-select">
					<option value="asc" <?= (($_GET['orden'] ?? 'asc') === 'asc') ? 'selected' : '' ?>>A-Z</option>
					<option value="desc" <?= (($_GET['orden'] ?? '') === 'desc') ? 'selected' : '' ?>>Z-A</option>
				</select>
			</div>
			<div class="col-md-2 col-6 mx-auto">
				<button type="submit" class="btn btn-primary w-100">Filtrar</button>
			</div>
			<div class="col-md-2 col-12 mx-auto">
				<a href="/RMIE/app/controllers/CategoriaController.php?action=list" class="btn btn-secondary w-100">Limpiar</a>
			</div>
		</form>
		<div class="table-responsive">
			<table class="table table-categorias mb-0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Descripción</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
				<?php if (!empty($categorias)): ?>
					<?php foreach ($categorias as $cat): ?>
						<tr>
							<td><?= htmlspecialchars($cat['id_categoria']) ?></td>
							<td><?= htmlspecialchars($cat['nombre']) ?></td>
							<td><?= htmlspecialchars($cat['descripcion']) ?></td>
							<td>
								<a href="/RMIE/app/controllers/CategoriaController.php?action=edit&id=<?= $cat['id_categoria'] ?>" class="btn btn-primary btn-sm me-1">Editar</a>
								<a href="/RMIE/app/controllers/CategoriaController.php?action=delete&id=<?= $cat['id_categoria'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar categoría?')">Eliminar</a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="4" class="text-center text-muted">No hay categorías registradas.</td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
		<a href="/RMIE/app/views/dashboard.php" class="btn btn-outline-secondary mt-4">Volver al dashboard</a>
	</div>
</div>
</body>
</html>
