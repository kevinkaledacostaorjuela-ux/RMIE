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
	<title>Nueva Categoría</title>
	<link href="../../../public/css/styles.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 500px;">
	<div class="card p-4 shadow-sm">
		<h2 class="mb-4 text-center">Nueva Categoría</h2>
		<form method="POST">
			<div class="mb-3">
				<label class="form-label">Nombre</label>
				<input type="text" name="nombre" class="form-control" required>
			</div>
			<div class="mb-3">
				<label class="form-label">Descripción</label>
				<input type="text" name="descripcion" class="form-control">
			</div>
			<div class="d-flex justify-content-between">
				<button type="submit" class="btn btn-success px-4">Guardar</button>
				<a href="/RMIE/app/controllers/CategoriaController.php?action=list" class="btn btn-secondary px-4">Cancelar</a>
			</div>
		</form>
	</div>
</div>
</body>
</html>
