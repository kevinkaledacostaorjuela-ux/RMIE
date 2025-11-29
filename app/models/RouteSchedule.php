<?php
class RouteSchedule {
    // Obtener asignaciones por usuario (num_doc) con verificación segura
    public static function getAssignmentsByUser($conn, $num_doc) {
        try {
            // Verificar primero la tabla nueva ruta_clientes_semanales
            $check = $conn->query("SHOW TABLES LIKE 'ruta_clientes_semanales'");
            if ($check && $check->num_rows > 0) {
                $sql = "SELECT dia_semana as dia, cliente_id as id_cliente FROM ruta_clientes_semanales WHERE usuario_id = ? ORDER BY dia_semana, orden";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    error_log('RouteSchedule::getAssignmentsByUser prepare error: ' . $conn->error);
                    return [];
                }
                $stmt->bind_param('i', $num_doc);
                $stmt->execute();
                $result = $stmt->get_result();
                $assignments = [];
                while ($row = $result->fetch_assoc()) {
                    $dia = $row['dia'];
                    if (!isset($assignments[$dia])) {
                        $assignments[$dia] = [];
                    }
                    $assignments[$dia][] = (int)$row['id_cliente'];
                }
                return $assignments;
            }
            
            // Fallback a tabla anterior si existe
            $check = $conn->query("SHOW TABLES LIKE 'rutas_planificacion'");
            if (!$check || $check->num_rows === 0) {
                // Ninguna tabla existe, retornar vacío sin error fatal
                return [];
            }
            $sql = "SELECT dia, id_cliente FROM rutas_planificacion WHERE num_doc_usuario = ? ORDER BY dia";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                error_log('RouteSchedule::getAssignmentsByUser prepare error: ' . $conn->error);
                return [];
            }
            $stmt->bind_param('i', $num_doc);
            $stmt->execute();
            $result = $stmt->get_result();
            $assignments = [];
            while ($row = $result->fetch_assoc()) {
                $dia = $row['dia'];
                if (!isset($assignments[$dia])) {
                    $assignments[$dia] = [];
                }
                $assignments[$dia][] = (int)$row['id_cliente'];
            }
            return $assignments;
        } catch (Exception $e) {
            error_log('RouteSchedule::getAssignmentsByUser exception: ' . $e->getMessage());
            return [];
        }
    }

    // Guardar asignaciones: reemplaza completamente las asignaciones del usuario
    public static function saveAssignments($conn, $num_doc, $assignments) {
        // Verificar existencia tabla
        $check = $conn->query("SHOW TABLES LIKE 'rutas_planificacion'");
        if (!$check || $check->num_rows === 0) {
            error_log('RouteSchedule::saveAssignments tabla rutas_planificacion no existe');
            return false;
        }
        $conn->begin_transaction();
        try {
            $delSql = "DELETE FROM rutas_planificacion WHERE num_doc_usuario = ?";
            $delStmt = $conn->prepare($delSql);
            $delStmt->bind_param('i', $num_doc);
            $delStmt->execute();

            $insSql = "INSERT INTO rutas_planificacion (num_doc_usuario, dia, id_cliente, created_at) VALUES (?, ?, ?, NOW())";
            $insStmt = $conn->prepare($insSql);
            foreach ($assignments as $dia => $idsClientes) {
                foreach ($idsClientes as $idCliente) {
                    $idCliente = (int)$idCliente;
                    if ($idCliente <= 0) continue;
                    $insStmt->bind_param('isi', $num_doc, $dia, $idCliente);
                    $insStmt->execute();
                }
            }
            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            error_log('Error saveAssignments: ' . $e->getMessage());
            return false;
        }
    }

    // Truncar asignaciones de un usuario (reset)
    public static function resetUserAssignments($conn, $num_doc) {
        $check = $conn->query("SHOW TABLES LIKE 'rutas_planificacion'");
        if (!$check || $check->num_rows === 0) {
            return true; // Nada que borrar
        }
        $sql = "DELETE FROM rutas_planificacion WHERE num_doc_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $num_doc);
        return $stmt->execute();
    }
    
    // Obtener estadísticas de planificación por usuario
    public static function getWeeklyStats($conn, $num_doc) {
        try {
            $check = $conn->query("SHOW TABLES LIKE 'rutas_planificacion'");
            if (!$check || $check->num_rows === 0) {
                return [];
            }
            
            $sql = "SELECT dia, COUNT(id_cliente) as total_clientes 
                    FROM rutas_planificacion 
                    WHERE num_doc_usuario = ? 
                    GROUP BY dia 
                    ORDER BY FIELD(dia, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $num_doc);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $stats = [];
            while ($row = $result->fetch_assoc()) {
                $stats[$row['dia']] = $row['total_clientes'];
            }
            return $stats;
        } catch (Exception $e) {
            error_log('RouteSchedule::getWeeklyStats exception: ' . $e->getMessage());
            return [];
        }
    }
    
    // Verificar si hay conflictos en las asignaciones
    public static function checkConflicts($conn, $num_doc, $dia, $id_cliente) {
        try {
            $check = $conn->query("SHOW TABLES LIKE 'rutas_planificacion'");
            if (!$check || $check->num_rows === 0) {
                return false;
            }
            
            $sql = "SELECT COUNT(*) as count FROM rutas_planificacion 
                    WHERE num_doc_usuario = ? AND dia = ? AND id_cliente = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('isi', $num_doc, $dia, $id_cliente);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            return $row['count'] > 0;
        } catch (Exception $e) {
            error_log('RouteSchedule::checkConflicts exception: ' . $e->getMessage());
            return false;
        }
    }
}
?>