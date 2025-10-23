-- Script para agregar el rol 'auxiliar' a la tabla usuarios
-- Fecha: 22 de octubre de 2025
-- Descripción: Modifica el ENUM de la columna rol para incluir 'auxiliar'

USE rmie;

-- Modificar la tabla usuarios para agregar el rol auxiliar
ALTER TABLE usuarios MODIFY COLUMN rol ENUM('admin','coordinador','auxiliar') NOT NULL;

-- Insertar un usuario auxiliar de ejemplo (opcional)
INSERT INTO usuarios (num_doc, tipo_doc, nombres, apellidos, correo, contrasena, num_cel, rol) 
VALUES (
    30000001,
    'CC',
    'Auxiliar',
    'Sistema',
    'auxiliar@rmie.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: auxiliar123
    '3000000001',
    'auxiliar'
) ON DUPLICATE KEY UPDATE 
    nombres = VALUES(nombres),
    apellidos = VALUES(apellidos),
    correo = VALUES(correo),
    rol = VALUES(rol);

-- Verificar que el cambio se aplicó correctamente
SHOW COLUMNS FROM usuarios LIKE 'rol';

-- Mostrar los usuarios con sus roles
SELECT num_doc, nombres, apellidos, correo, rol FROM usuarios ORDER BY rol;