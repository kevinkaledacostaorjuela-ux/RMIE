<?php
// Script para insertar usuarios admin y coordinador con contraseña encriptada
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
    ]
];
foreach ($usuarios as $u) {
    $stmt = $pdo->prepare('INSERT INTO usuarios (num_doc, tipo_doc, nombres, apellidos, correo, contrasena, num_cel, rol) VALUES (:num_doc, :tipo_doc, :nombres, :apellidos, :correo, :contrasena, :num_cel, :rol)');
    $stmt->execute($u);
}
echo "Usuarios insertados correctamente.";
?>