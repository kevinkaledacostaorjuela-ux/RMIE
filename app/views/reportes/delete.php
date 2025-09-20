<!DOCTYPE html>
<html>
<head>
    <title>Eliminar Reporte</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Eliminar Reporte</h1>
        <p>¿Estás seguro que deseas eliminar el reporte "<?= $reporte->nombre ?>"?</p>
        <form method="POST" action="">
            <button type="submit" class="btn-categorias">Eliminar</button>
            <a href="index.php" class="btn-categorias">Cancelar</a>
        </form>
    </div>
</body>
</html>
