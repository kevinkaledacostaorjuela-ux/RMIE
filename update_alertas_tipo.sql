-- Agregar campo tipo_alerta a la tabla alertas
ALTER TABLE alertas ADD COLUMN tipo_alerta VARCHAR(20) DEFAULT 'stock_bajo' AFTER id_alertas;

-- Actualizar registros existentes basándose en la lógica de negocio
-- Si la cantidad_minima es mayor, probablemente es alerta de stock
-- Si la fecha de caducidad está más cerca, es alerta de vencimiento
UPDATE alertas SET tipo_alerta = 'stock_bajo';

-- Puedes ajustar manualmente los registros que sean de vencimiento ejecutando:
-- UPDATE alertas SET tipo_alerta = 'vencimiento' WHERE id_alertas IN (lista_de_ids);
