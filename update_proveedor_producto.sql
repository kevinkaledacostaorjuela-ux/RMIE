-- Script para permitir múltiples proveedores por producto
-- Crear tabla intermedia para relación muchos-a-muchos

-- Tabla intermedia proveedores_productos
CREATE TABLE IF NOT EXISTS proveedores_productos (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_proveedor INT NOT NULL,
    id_producto INT NOT NULL,
    fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    activo TINYINT(1) DEFAULT 1,
    FOREIGN KEY (id_proveedor) REFERENCES proveedores(id_proveedores) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id_productos) ON DELETE CASCADE,
    UNIQUE KEY unique_proveedor_producto (id_proveedor, id_producto)
);

-- Migrar datos existentes de productos a la tabla intermedia
INSERT IGNORE INTO proveedores_productos (id_proveedor, id_producto)
SELECT id_proveedores, id_productos 
FROM productos 
WHERE id_proveedores IS NOT NULL AND id_proveedores > 0;

-- Hacer opcional el campo id_proveedores en productos (para mantener compatibilidad)
ALTER TABLE productos MODIFY id_proveedores INT NULL;

-- Opcional: Comentar la siguiente línea si quieres mantener la referencia directa
-- ALTER TABLE productos DROP FOREIGN KEY productos_ibfk_3;