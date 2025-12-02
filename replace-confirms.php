<?php
/**
 * Script para reemplazar todos los confirm() con las funciones de selenium-messages
 */

$baseDir = __DIR__ . '/app/views';

function replaceConfirmMessages($dir) {
    $files = glob($dir . '/*.php');
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        $originalContent = $content;
        
        // Reemplazos específicos por módulo
        $replacements = [
            // Productos
            '/confirm\(\'¿Está seguro de eliminar el producto[^\']*\'\)/' => 'confirmDeleteProduct(\'Producto\')',
            '/confirm\(`¿Está seguro de eliminar el producto[^`]*`\)/' => 'confirmDeleteProduct(\'Producto\')',
            
            // Proveedores  
            '/confirm\(\'¿Está seguro de eliminar el proveedor[^\']*\'\)/' => 'confirmDeleteProvider(\'Proveedor\')',
            '/confirm\(`¿Está seguro de eliminar el proveedor[^`]*`\)/' => 'confirmDeleteProvider(\'Proveedor\')',
            
            // Locales
            '/confirm\(\'¿Está seguro de eliminar el local[^\']*\'\)/' => 'confirmDeleteLocal(\'Local\')',
            '/confirm\(`¿Está seguro de eliminar el local[^`]*`\)/' => 'confirmDeleteLocal(\'Local\')',
            
            // Subcategorías
            '/confirm\(\'¿Está seguro de eliminar[^\']*subcategoría[^\']*\'\)/' => 'confirmDeleteSubcategory(\'Subcategoría\')',
            '/confirm\(`¿Está seguro de eliminar[^`]*subcategoría[^`]*`\)/' => 'confirmDeleteSubcategory(\'Subcategoría\')',
            
            // Ventas
            '/confirm\(\'¿Está seguro de eliminar la venta[^\']*\'\)/' => 'confirmAction(\'eliminar venta\')',
            '/confirm\(`¿Está seguro de eliminar la venta[^`]*`\)/' => 'confirmAction(\'eliminar venta\')',
            
            // Guardar cambios generales
            '/confirm\(\'¿Está seguro de guardar[^\']*\'\)/' => 'confirmAction(\'guardar cambios\')',
            '/confirm\(`¿Está seguro de guardar[^`]*`\)/' => 'confirmAction(\'guardar cambios\')',
            
            // Acciones de limpieza/eliminar todos
            '/confirm\(\'¡ATENCIÓN![^\']*\'\)/' => 'confirmAction(\'eliminar todos\')',
            '/confirm\(`¡ATENCIÓN![^`]*`\)/' => 'confirmAction(\'eliminar todos\')',
            '/confirm\(\'Se eliminarán[^\']*\'\)/' => 'confirmAction(\'limpiar registros\')',
            '/confirm\(`Se eliminarán[^`]*`\)/' => 'confirmAction(\'limpiar registros\')',
            
            // Otros confirms generales
            '/confirm\(\'¿Está[^\']*\'\)/' => 'confirmAction(\'acción general\')',
            '/confirm\(`¿Está[^`]*`\)/' => 'confirmAction(\'acción general\')'
        ];
        
        foreach ($replacements as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }
        
        // Solo escribir si hubo cambios
        if ($content !== $originalContent) {
            file_put_contents($file, $content);
            echo "✅ Actualizado: " . basename(dirname($file)) . "/" . basename($file) . "\n";
        }
    }
    
    // Procesar subdirectorios
    $subdirs = glob($dir . '/*', GLOB_ONLYDIR);
    foreach ($subdirs as $subdir) {
        replaceConfirmMessages($subdir);
    }
}

echo "🔄 Reemplazando mensajes de confirmación...\n\n";
replaceConfirmMessages($baseDir);
echo "\n✅ Todos los confirm() reemplazados!\n";
?>