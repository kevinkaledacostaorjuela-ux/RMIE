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
	<title>Categorías</title>
	<link href="../../../public/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
	<h2>Gestión de Categorías</h2>
	<a href="/RMIE/app/controllers/CategoriaController.php?action=create" class="btn btn-success mb-2">Nueva Categoría</a>
	<div class="card p-3">
	<table class="table table-categorias mb-0">
		<thead>
			<tr>
				<th>ID</th><th>Nombre</th><th>Descripción</th><th>Acciones</th>
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
						<a href="/RMIE/app/controllers/CategoriaController.php?action=edit&id=<?= $cat['id_categoria'] ?>" class="btn btn-primary btn-sm">Editar</a>
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
	<a href="/RMIE/app/views/dashboard.php" class="btn btn-secondary">Volver al dashboard</a>
</div>
</body>
</html>
