<?php
// Script para insertar usuarios admin, coordinador y auxiliar con contraseña encriptada
require_once 'config/db.php';
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
foreach ($usuarios as $u) {
    $stmt = $pdo->prepare('INSERT INTO usuarios (num_doc, tipo_doc, nombres, apellidos, correo, contrasena, num_cel, rol) VALUES (:num_doc, :tipo_doc, :nombres, :apellidos, :correo, :contrasena, :num_cel, :rol) ON DUPLICATE KEY UPDATE nombres=VALUES(nombres), apellidos=VALUES(apellidos), correo=VALUES(correo), rol=VALUES(rol)');
    $stmt->execute($u);
}
echo "Usuarios insertados/actualizados correctamente.";
?>