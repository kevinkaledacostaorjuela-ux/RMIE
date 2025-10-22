<?php
echo "=== LIMPIEZA DEL WORKSPACE RMIE ===\n";
echo "Iniciando eliminación de archivos innecesarios...\n\n";

// Archivos de debug para eliminar
$debug_files = [
    'debug_clientes.php',
    'debug_delete_report.php', 
    'debug_producto_3.php',
    'debug_proveedor_view.php',
    'debug_proveedores.php',
    'debug_reportes.php',
    'debug_route_edit.php',
    'debug_ruta_edit_access.php',
    'debug_rutas_update.php',
    'debug_subcategoria_4.php',
    'debug_usuarios.php'
];

// Archivos de test para eliminar
$test_files = [
    'test.php',
    'test_acceso_web.php',
    'test_clientes.php',
    'test_clientes_local.php',
    'test_clientes_mejorados.php',
    'test_controller_direct.php',
    'test_delete_report.php',
    'test_editar_ruta.php',
    'test_edit_simple.php',
    'test_eliminacion.php',
    'test_eliminacion_reportes.php',
    'test_enlaces_clientes.php',
    'test_enlaces_dashboard.php',
    'test_enlaces_locales.html',
    'test_locales.html',
    'test_locales_final.html',
    'test_locales_mobile.html',
    'test_locales_sistema.php',
    'test_mobile_views.html',
    'test_modulos.php',
    'test_productos_sin_proveedor.php',
    'test_proveedores_mobile.html',
    'test_reportes_completo.php',
    'test_report_controller.php',
    'test_report_creation.php',
    'test_route_update_complete.php',
    'test_rutas_correccion.html',
    'test_session_clear.php',
    'test_timezone.php',
    'test_ultra_simple.php',
    'test_update_rutas.php'
];

// Archivos temporales para eliminar
$temp_files = [
    'temp_reportes.php',
    'acceso_directo_clientes.php',
    'vista_previa_rutas.html',
    'diagnostic_subcategorias.php',
    'fix_productos_proveedor.php',
    'fix_subcategorias_table.php',
    'update_alertas_table.php',
    'update_categorias_table.php'
];

$deleted_count = 0;
$not_found_count = 0;

function deleteFiles($files, $category) {
    global $deleted_count, $not_found_count;
    echo "--- Eliminando archivos de $category ---\n";
    
    foreach ($files as $file) {
        if (file_exists($file)) {
            if (unlink($file)) {
                echo "✓ Eliminado: $file\n";
                $deleted_count++;
            } else {
                echo "✗ Error al eliminar: $file\n";
            }
        } else {
            echo "- No encontrado: $file\n";
            $not_found_count++;
        }
    }
    echo "\n";
}

// Eliminar archivos por categorías
deleteFiles($debug_files, 'DEBUG');
deleteFiles($test_files, 'TEST');
deleteFiles($temp_files, 'TEMPORALES');

echo "=== RESUMEN ===\n";
echo "Archivos eliminados: $deleted_count\n";
echo "Archivos no encontrados: $not_found_count\n";
echo "Total procesados: " . ($deleted_count + $not_found_count) . "\n\n";

echo "Limpieza completada. El workspace ahora está más organizado.\n";
echo "Archivos conservados: index.php, logout.php, db.sql, README.md, etc.\n";
?>