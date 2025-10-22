# Análisis de Limpieza del Workspace RMIE

## ✅ ARCHIVOS ESENCIALES (CONSERVAR):
- index.php - Página de login principal
- logout.php - Funcionalidad de logout
- insert_users.php - Script de inserción de usuarios (útil para admin)
- db.sql - Estructura de base de datos
- README.md - Documentación del proyecto
- .gitattributes - Configuración de Git
- 0001-avance.patch - Parche de desarrollo
- clean_rutas.bat - Script de limpieza de rutas
- IA.txt - Conversaciones de IA (puede ser útil para referencia)

### Archivos SQL importantes:
- fix_productos_table.sql - Corrección de tabla productos
- update_clientes_table.sql - Actualización de tabla clientes
- update_proveedores_ubicacion.sql - Actualización de proveedores

## ❌ ARCHIVOS PARA ELIMINAR:

### Archivos de Debug (11 archivos):
- debug_clientes.php
- debug_delete_report.php
- debug_producto_3.php
- debug_proveedor_view.php
- debug_proveedores.php
- debug_reportes.php
- debug_route_edit.php
- debug_ruta_edit_access.php
- debug_rutas_update.php
- debug_subcategoria_4.php
- debug_usuarios.php

### Archivos de Test (30+ archivos):
- test.php
- test_acceso_web.php
- test_clientes.php
- test_clientes_local.php
- test_clientes_mejorados.php
- test_controller_direct.php
- test_delete_report.php
- test_editar_ruta.php
- test_edit_simple.php
- test_eliminacion.php
- test_eliminacion_reportes.php
- test_enlaces_clientes.php
- test_enlaces_dashboard.php
- test_enlaces_locales.html
- test_locales.html
- test_locales_final.html
- test_locales_mobile.html
- test_locales_sistema.php
- test_mobile_views.html
- test_modulos.php
- test_productos_sin_proveedor.php
- test_proveedores_mobile.html
- test_reportes_completo.php
- test_report_controller.php
- test_report_creation.php
- test_route_update_complete.php
- test_rutas_correccion.html
- test_session_clear.php
- test_timezone.php
- test_ultra_simple.php
- test_update_rutas.php

### Archivos Temporales:
- temp_reportes.php
- acceso_directo_clientes.php
- vista_previa_rutas.html
- diagnostic_subcategorias.php

### Archivos de Fix aplicados:
- fix_productos_proveedor.php
- fix_subcategorias_table.php
- update_alertas_table.php
- update_categorias_table.php

### Archivo de limpieza:
- cleanup.bat (este mismo archivo después de usar)

## RESUMEN:
- CONSERVAR: ~12 archivos esenciales
- ELIMINAR: ~50+ archivos temporales/debug/test

Esto reducirá significativamente el desorden del workspace.