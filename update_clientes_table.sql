-- Script para agregar nuevos campos a la tabla clientes
-- Ejecutar este script en la base de datos rmie_db

USE rmie_db;

-- Agregar nuevas columnas si no existen
ALTER TABLE clientes 
ADD COLUMN IF NOT EXISTS direccion VARCHAR(200) DEFAULT NULL COMMENT 'Dirección del cliente',
ADD COLUMN IF NOT EXISTS ciudad VARCHAR(100) DEFAULT NULL COMMENT 'Ciudad del cliente', 
ADD COLUMN IF NOT EXISTS fecha_nacimiento DATE DEFAULT NULL COMMENT 'Fecha de nacimiento del cliente',
ADD COLUMN IF NOT EXISTS preferencias TEXT DEFAULT NULL COMMENT 'Preferencias y observaciones del cliente';

-- Verificar las columnas agregadas
DESCRIBE clientes;

-- Mostrar algunos clientes para verificar
SELECT id_clientes, nombre, correo, direccion, ciudad, fecha_nacimiento 
FROM clientes 
LIMIT 5;