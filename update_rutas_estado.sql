-- Agregar campo 'estado' a la tabla rutas
ALTER TABLE rutas ADD COLUMN estado VARCHAR(20) NOT NULL DEFAULT 'activa';
