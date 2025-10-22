@echo off
echo Eliminando archivos de debug y prueba...

rem Archivos de debug
del /f /q debug_clientes.php 2>nul
del /f /q debug_delete_report.php 2>nul
del /f /q debug_producto_3.php 2>nul
del /f /q debug_proveedor_view.php 2>nul
del /f /q debug_proveedores.php 2>nul
del /f /q debug_reportes.php 2>nul
del /f /q debug_route_edit.php 2>nul
del /f /q debug_ruta_edit_access.php 2>nul
del /f /q debug_rutas_update.php 2>nul
del /f /q debug_subcategoria_4.php 2>nul
del /f /q debug_usuarios.php 2>nul

rem Archivos de test
del /f /q test.php 2>nul
del /f /q test_acceso_web.php 2>nul
del /f /q test_clientes.php 2>nul
del /f /q test_clientes_local.php 2>nul
del /f /q test_clientes_mejorados.php 2>nul
del /f /q test_controller_direct.php 2>nul
del /f /q test_delete_report.php 2>nul
del /f /q test_editar_ruta.php 2>nul
del /f /q test_edit_simple.php 2>nul
del /f /q test_eliminacion.php 2>nul
del /f /q test_eliminacion_reportes.php 2>nul
del /f /q test_enlaces_clientes.php 2>nul
del /f /q test_enlaces_dashboard.php 2>nul
del /f /q test_enlaces_locales.html 2>nul
del /f /q test_locales.html 2>nul
del /f /q test_locales_final.html 2>nul
del /f /q test_locales_mobile.html 2>nul
del /f /q test_locales_sistema.php 2>nul
del /f /q test_mobile_views.html 2>nul
del /f /q test_modulos.php 2>nul
del /f /q test_productos_sin_proveedor.php 2>nul
del /f /q test_proveedores_mobile.html 2>nul
del /f /q test_reportes_completo.php 2>nul
del /f /q test_report_controller.php 2>nul
del /f /q test_report_creation.php 2>nul
del /f /q test_route_update_complete.php 2>nul
del /f /q test_rutas_correccion.html 2>nul
del /f /q test_session_clear.php 2>nul
del /f /q test_timezone.php 2>nul
del /f /q test_ultra_simple.php 2>nul
del /f /q test_update_rutas.php 2>nul

rem Archivos temporales y de desarrollo
del /f /q temp_reportes.php 2>nul
del /f /q acceso_directo_clientes.php 2>nul
del /f /q vista_previa_rutas.html 2>nul
del /f /q diagnostic_subcategorias.php 2>nul
del /f /q fix_productos_proveedor.php 2>nul
del /f /q fix_subcategorias_table.php 2>nul

rem Archivos de actualización que ya se aplicaron
del /f /q update_alertas_table.php 2>nul
del /f /q update_categorias_table.php 2>nul

echo Limpieza completada.
pause