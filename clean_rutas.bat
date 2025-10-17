@echo off
echo Limpiando archivos innecesarios de rutas...

echo.
echo Eliminando archivos vacios:
if exist "app\views\rutas\edit_fixed.php" (
    del "app\views\rutas\edit_fixed.php"
    echo - edit_fixed.php eliminado
) else (
    echo - edit_fixed.php no encontrado
)

if exist "app\views\rutas\edit_new.php" (
    del "app\views\rutas\edit_new.php"
    echo - edit_new.php eliminado
) else (
    echo - edit_new.php no encontrado
)

echo.
echo Eliminando archivos obsoletos:
if exist "app\views\rutas\index_old.php" (
    del "app\views\rutas\index_old.php"
    echo - index_old.php eliminado
) else (
    echo - index_old.php no encontrado
)

if exist "app\views\rutas\delete.php" (
    del "app\views\rutas\delete.php"
    echo - delete.php eliminado (obsoleto)
) else (
    echo - delete.php no encontrado
)

echo.
echo Limpieza completada. Archivos restantes:
dir "app\views\rutas\*.php" /b

echo.
echo Archivos mantenidos para el CRUD de rutas:
echo - index.php (listado principal)
echo - create.php (crear nueva ruta)
echo - edit.php (editar ruta)