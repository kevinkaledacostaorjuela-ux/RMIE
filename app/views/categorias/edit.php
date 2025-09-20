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
	<title>Editar Categoría</title>
	<link href="../../../public/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
	<h2>Editar Categoría</h2>
	<form method="POST">
		<div class="mb-3">
			<label>Nombre</label>
			<input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($categoria['nombre']) ?>" required>
		</div>
		<div class="mb-3">
			<label>Descripción</label>
			<input type="text" name="descripcion" class="form-control" value="<?= htmlspecialchars($categoria['descripcion']) ?>">
		</div>
		<button type="submit" class="btn btn-primary">Actualizar</button>
		<a href="/RMIE/app/controllers/CategoriaController.php?action=list" class="btn btn-secondary">Cancelar</a>
	</form>
</div>
</body>
</html>
