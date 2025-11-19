<?php
// Script para insertar usuarios admin, coordinador y auxiliar con contraseña encriptada
require_once 'config/db.php';  // Usa la conexión existente

try {
    // Iniciar transacción usando la conexión existente
    $conn->begin_transaction();
    
    $usuarios = [
        [
            'num_doc' => 1,
            'tipo_doc' => 'CC',
            'nombres' => 'Admin',
            'apellidos' => 'Principal',
            'correo' => 'admin',
            'contrasena' => password_hash('admin123', PASSWORD_DEFAULT),
            'num_cel' => '1234567890',
            'rol' => 'admin'
        ],
        [
            'num_doc' => 2,
            'tipo_doc' => 'CC',
            'nombres' => 'Coordinador',
            'apellidos' => 'Secundario',
            'correo' => 'coordinador',
            'contrasena' => password_hash('coordinador123', PASSWORD_DEFAULT),
            'num_cel' => '0987654321',
            'rol' => 'coordinador'
        ],
        [
            'num_doc' => 3,
            'tipo_doc' => 'CC',
            'nombres' => 'Auxiliar',
            'apellidos' => 'Apoyo',
            'correo' => 'auxiliar',
            'contrasena' => password_hash('auxiliar123', PASSWORD_DEFAULT),
            'num_cel' => '5555555555',
            'rol' => 'auxiliar'
        ]
    ];

    // Preparar la consulta usando la conexión existente
    $stmt = $conn->prepare('INSERT INTO usuarios 
        (num_doc, tipo_doc, nombres, apellidos, correo, contrasena, num_cel, rol) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE 
            nombres=VALUES(nombres),
            apellidos=VALUES(apellidos),
            correo=VALUES(correo),
            rol=VALUES(rol)');

    foreach ($usuarios as $usuario) {
        $stmt->bind_param('ssssssss', 
            $usuario['num_doc'],
            $usuario['tipo_doc'],
            $usuario['nombres'],
            $usuario['apellidos'],
            $usuario['correo'],
            $usuario['contrasena'],
            $usuario['num_cel'],
            $usuario['rol']
        );
        $stmt->execute();
    }

    // Confirmar la transacción
    $conn->commit();
    echo "Usuarios insertados/actualizados correctamente.";

} catch (Exception $e) {
    // Revertir cambios si hay un error
    $conn->rollback();
    throw new Exception("Error al insertar usuarios: " . $e->getMessage());
}