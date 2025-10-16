-- Agregar campo ubicacion a la tabla proveedores
ALTER TABLE proveedores ADD COLUMN ubicacion TEXT AFTER estado;