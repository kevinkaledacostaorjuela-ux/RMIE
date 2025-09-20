<!DOCTYPE html>
<html>
<head>
    <title>Eliminar Local</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Eliminar Local</h1>
    <p>¿Estás seguro que deseas eliminar el local "<?= $local->nombre ?>"?</p>
    <form method="POST" action="">
        <button type="submit">Eliminar</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>
