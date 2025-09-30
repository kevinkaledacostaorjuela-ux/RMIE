-- Modificar la tabla productos para permitir NULL en id_proveedores
-- Esto permitirá crear productos sin proveedor asignado

USE rmie;

-- Verificar estructura actual
DESCRIBE productos;

-- Modificar la columna id_proveedores para permitir NULL
ALTER TABLE productos 
MODIFY COLUMN id_proveedores INT NULL;

-- Verificar que el cambio se aplicó correctamente
DESCRIBE productos;

-- Mostrar productos existentes para verificar
SELECT id_productos, nombre, id_proveedores FROM productos LIMIT 5;