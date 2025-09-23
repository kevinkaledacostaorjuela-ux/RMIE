<?php
require_once '../../config/db.php';

class AlertaController
{
    // Registrar una nueva alerta
    public function crear($mensaje, $tipo = 'info')
    {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO alertas (mensaje, tipo, fecha) VALUES (:mensaje, :tipo, NOW())');
        $stmt->execute([
            'mensaje' => $mensaje,
            'tipo' => $tipo
        ]);
        return $pdo->lastInsertId();
    }

    // Obtener todas las alertas
    public function listar()
    {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM alertas ORDER BY fecha DESC');
        return $stmt->fetchAll();
    }

    // Eliminar una alerta por ID
    public function eliminar($id)
    {
        global $pdo;
        $stmt = $pdo->prepare('DELETE FROM alertas WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}

// Ejemplo de uso:
// $alerta = new AlertaController();
// $alerta->crear('Mensaje de prueba', 'warning');
// $todas = $alerta->listar();
// print_r($todas);