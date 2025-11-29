-- SQL para crear las tablas del sistema de rutas semanales
-- Ejecutar en MySQL/MariaDB

-- Tabla principal de rutas semanales
CREATE TABLE IF NOT EXISTS `rutas_semanales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dia` enum('Monday','Tuesday','Wednesday','Thursday','Friday') NOT NULL,
  `nombre_ruta` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('activa','inactiva','completada') DEFAULT 'activa',
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_dia` (`dia`),
  INDEX `idx_estado` (`estado`),
  INDEX `idx_dia_estado` (`dia`, `estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de relación entre rutas y clientes
CREATE TABLE IF NOT EXISTS `ruta_clientes_semanales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ruta_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `fecha_asignacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`ruta_id`) REFERENCES `rutas_semanales`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX `idx_ruta_id` (`ruta_id`),
  INDEX `idx_cliente_id` (`cliente_id`),
  UNIQUE KEY `unique_ruta_cliente` (`ruta_id`, `cliente_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar datos de ejemplo (opcional)
INSERT INTO `rutas_semanales` (`dia`, `nombre_ruta`, `descripcion`, `estado`) VALUES
('Monday', 'Ruta Centro - Zona Norte', 'Recorrido por el centro de la ciudad y zona norte', 'activa'),
('Tuesday', 'Ruta Sur - Periferia', 'Cobertura de la zona sur y áreas periféricas', 'activa'),
('Wednesday', 'Ruta Este - Comercial', 'Área comercial del este de la ciudad', 'activa'),
('Thursday', 'Ruta Oeste - Industrial', 'Zona industrial del oeste', 'activa'),
('Friday', 'Ruta Completa - Revisión', 'Ruta de revisión y pendientes de la semana', 'activa');

-- Insertar clientes de ejemplo para las rutas (opcional)
INSERT INTO `ruta_clientes_semanales` (`ruta_id`, `cliente_id`) VALUES
(1, 1), (1, 5), (1, 12), (1, 25),
(2, 2), (2, 8), (2, 15), (2, 30),
(3, 3), (3, 7), (3, 18), (3, 22),
(4, 4), (4, 10), (4, 20), (4, 35),
(5, 6), (5, 11), (5, 16), (5, 28);

-- Verificar las tablas creadas
-- SELECT * FROM rutas_semanales;
-- SELECT * FROM ruta_clientes_semanales;