<?php
class Route {
    // Método mejorado con filtros
    public static function getAll($conn, $filtros = []) {
        require_once __DIR__ . '/../utils/FilterHelper.php';
        
        // Definir reglas de validación para filtros
        $filterRules = [
            'id_clientes' => ['type' => 'int', 'options' => ['min' => 1]],
            'id_locales' => ['type' => 'int', 'options' => ['min' => 1]],
            'nombre_cliente' => ['type' => 'text', 'options' => ['max_length' => 100]],
            'venta' => ['type' => 'int', 'options' => ['min' => 1]],
            'reporte' => ['type' => 'int', 'options' => ['min' => 1]],
            'direccion' => ['type' => 'text', 'options' => ['max_length' => 200]],
            'nombre_local' => ['type' => 'text', 'options' => ['max_length' => 100]],
            'estado' => ['type' => 'text', 'options' => ['max_length' => 20]],
            'dia_semana' => ['type' => 'text', 'options' => ['max_length' => 20]],
            'buscar' => ['type' => 'text', 'options' => ['max_length' => 100]]
        ];
        
        // Procesar filtros
        $filtrosProcesados = FilterHelper::processFilters($filtros, $filterRules);
        
        // Mapeo de campos a columnas SQL
        $mapping = [
            'id_clientes' => ['column' => 'r.id_clientes', 'operator' => '=', 'type' => 'i'],
            // Filtrar por id_locales en la tabla rutas directamente (más confiable)
            'id_locales' => ['column' => 'r.id_locales', 'operator' => '=', 'type' => 'i'],
            'nombre_cliente' => ['column' => 'c.nombre', 'operator' => '=', 'type' => 's'],
            'venta' => ['column' => 'r.id_ventas', 'operator' => '=', 'type' => 'i'],
            'reporte' => ['column' => 'r.id_reportes', 'operator' => '=', 'type' => 'i'],
            'direccion' => ['column' => 'r.direccion', 'operator' => 'LIKE', 'type' => 's'],
            'nombre_local' => ['column' => 'COALESCE(l1.nombre_local, l2.nombre_local, r.nombre_local)', 'operator' => 'LIKE', 'type' => 's'],
            'estado' => ['column' => 'r.estado', 'operator' => '=', 'type' => 's'],
            'dia_semana' => ['column' => 'r.dia_semana', 'operator' => '=', 'type' => 's'],
            'buscar' => [
                'columns' => ['r.direccion', 'COALESCE(l1.nombre_local, l2.nombre_local, r.nombre_local)', 'c.nombre'],
                'operator' => 'MULTIPLE_LIKE'
            ]
        ];
        
        // Construir consulta base con JOINs para obtener información relacionada de clientes y locales
        // Usar GROUP BY r.id_ruta para evitar duplicados por múltiples relaciones cliente-local
        $sql = "SELECT r.*, 
                       c.nombre as cliente_nombre,
                       c.cel_cliente as cliente_celular,
                       c.correo as cliente_correo,
                       c.estado as cliente_estado,
                       COALESCE(l1.nombre_local, l2.nombre_local, r.nombre_local) as local_nombre,
                       COALESCE(l1.direccion, l2.direccion, r.direccion) as local_direccion,
                       COALESCE(l1.cel_local, l2.cel_local) as local_celular,
                       COALESCE(l1.estado, l2.estado) as local_estado,
                       COALESCE(l1.localidad, l2.localidad) as local_localidad,
                       COALESCE(l1.barrio, l2.barrio) as local_barrio,
                       v.nombre as venta_nombre,
                       v.cantidad as venta_cantidad,
                       v.fecha_venta as venta_fecha,
                       rep.nombre as reporte_nombre
                FROM rutas r 
                LEFT JOIN clientes c ON r.id_clientes = c.id_clientes
                LEFT JOIN locales l1 ON c.id_locales = l1.id_locales
                LEFT JOIN clientes_locales cl ON c.id_clientes = cl.id_clientes AND cl.id_locales = (
                    SELECT MIN(cl2.id_locales) 
                    FROM clientes_locales cl2 
                    WHERE cl2.id_clientes = c.id_clientes
                )
                LEFT JOIN locales l2 ON cl.id_locales = l2.id_locales
                LEFT JOIN ventas v ON r.id_ventas = v.id_ventas
                LEFT JOIN reportes rep ON r.id_reportes = rep.id_reportes
                WHERE 1=1";
        
        // Construir WHERE con filtros
        $whereData = FilterHelper::buildWhereClause($filtrosProcesados, $mapping);
        
        if (!empty($whereData['where'])) {
            $sql .= " AND " . implode(" AND ", $whereData['where']);
        }
        
        $sql .= " GROUP BY r.id_ruta ORDER BY r.id_ruta DESC";
        
        $stmt = $conn->prepare($sql);
        if (!empty($whereData['params'])) {
            $stmt->bind_param($whereData['types'], ...$whereData['params']);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Método original sin filtros para retrocompatibilidad
    public static function getAllSimple($conn) {
        $sql = "SELECT * FROM rutas ORDER BY fecha_creacion DESC";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Método específico para obtener rutas filtradas (retrocompatibilidad)
    public static function getFiltered($conn, $filtroVenta = '') {
        $filtros = [];
        if (!empty($filtroVenta)) {
            $filtros['venta'] = $filtroVenta;
        }
        return self::getAll($conn, $filtros);
    }



    public static function getById($conn, $id) {
        $sql = "SELECT * FROM rutas WHERE id_ruta = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function update($conn, $id, $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_ventas, $id_reportes = null, $estado = null) {
        try {
            // Depuración: verificar datos de entrada
            error_log("DEBUG Update - ID: $id, Direccion: $direccion, Local: $nombre_local, Cliente: $nombre_cliente, ID_Clientes: $id_clientes, ID_Ventas: $id_ventas, ID_Reportes: $id_reportes");
            
            // Verificar que la ruta existe antes de actualizar
            $checkSql = "SELECT * FROM rutas WHERE id_ruta = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param('i', $id);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            $existing = $result->fetch_assoc();
            
            if (!$existing) {
                throw new Exception("La ruta con ID $id no existe en la base de datos.");
            }
            
            error_log("DEBUG - Ruta existente encontrada: " . json_encode($existing));
            
            // Actualizar incluyendo id_reportes
            $sql = "UPDATE rutas SET direccion = ?, nombre_local = ?, nombre_cliente = ?, id_clientes = ?, id_ventas = ?, id_reportes = ?, estado = ? WHERE id_ruta = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $conn->error);
            }
            
            error_log("DEBUG - SQL preparado: $sql");
            
            $stmt->bind_param('sssiiiss', $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_ventas, $id_reportes, $estado, $id);
            
            if (!$stmt->execute()) {
                error_log("DEBUG - Error en execute: " . $stmt->error);
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
            
            $affected = $stmt->affected_rows;
            error_log("DEBUG - Filas afectadas: $affected");
            
            // Verificar que se actualizó al menos una fila
            if ($affected === 0) {
                error_log("DEBUG - No se actualizó ninguna fila. Puede ser que no hubo cambios en los datos.");
                // En lugar de lanzar excepción, vamos a verificar si realmente los datos son diferentes
                $newCheckSql = "SELECT * FROM rutas WHERE id_ruta = ?";
                $newCheckStmt = $conn->prepare($newCheckSql);
                $newCheckStmt->bind_param('i', $id);
                $newCheckStmt->execute();
                $newResult = $newCheckStmt->get_result();
                $updated = $newResult->fetch_assoc();
                error_log("DEBUG - Datos después de update: " . json_encode($updated));
                
                // Si los datos son iguales, no hay problema
                if ($updated['direccion'] === $direccion && 
                    $updated['nombre_local'] === $nombre_local && 
                    $updated['nombre_cliente'] === $nombre_cliente && 
                    $updated['id_clientes'] == $id_clientes && 
                    $updated['id_ventas'] == $id_ventas &&
                    ($id_reportes === null ? $updated['id_reportes'] === null : $updated['id_reportes'] == $id_reportes)) {
                    error_log("DEBUG - Los datos ya eran iguales, update exitoso conceptualmente");
                    return true;
                } else {
                    throw new Exception("No se pudo actualizar la ruta. Datos no coinciden después del update.");
                }
            }
            
            error_log("DEBUG - Update exitoso, $affected filas afectadas");
            return true; // Retornar true en caso de éxito
        } catch (Exception $e) {
            error_log("ERROR al actualizar ruta ID $id: " . $e->getMessage());
            throw new Exception("Error al actualizar la ruta: " . $e->getMessage());
        }
    }

    public static function updateMultiple($conn, $id, $direccion, $nombre_local, $nombre_cliente, $id_locales_array, $id_clientes_array, $id_ventas_array, $estado, $direcciones_array = []) {
        try {
            // Convertir arrays a JSON
            $id_locales_json = json_encode($id_locales_array);
            $id_clientes_json = json_encode($id_clientes_array);
            $id_ventas_json = json_encode($id_ventas_array);
            $direcciones_json = !empty($direcciones_array) ? json_encode($direcciones_array) : json_encode([]);
            
            // Usar el primer cliente y venta como valores por defecto para compatibilidad
            $id_clientes = intval($id_clientes_array[0]);
            $id_ventas = !empty($id_ventas_array) ? intval($id_ventas_array[0]) : null;
            
            $sql = "UPDATE rutas SET direccion = ?, nombre_local = ?, nombre_cliente = ?, id_clientes = ?, id_ventas = ?, estado = ?, id_locales_json = ?, id_clientes_json = ?, id_ventas_json = ?, direcciones_json = ? WHERE id_ruta = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $conn->error);
            }
            $stmt->bind_param('sssissssssi', $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_ventas, $estado, $id_locales_json, $id_clientes_json, $id_ventas_json, $direcciones_json, $id);
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
            return true;
        } catch (Exception $e) {
            error_log("Error al actualizar ruta múltiple: " . $e->getMessage());
            throw new Exception("Error al actualizar la ruta: " . $e->getMessage());
        }
    }

    public static function updateMultiplePreservingDay($conn, $id, $direccion, $nombre_local, $nombre_cliente, $id_locales_array, $id_clientes_array, $id_ventas_array, $estado, $direcciones_array = [], $cliente_principal_original = null, $dia_semana = null) {
        try {
            error_log("DEBUG UPDATE - Iniciando actualización para ruta ID: $id");
            error_log("DEBUG UPDATE - Cliente principal original: " . ($cliente_principal_original ?? 'NULL'));
            error_log("DEBUG UPDATE - Nuevos clientes: " . json_encode($id_clientes_array));
            
            // Convertir arrays a JSON
            $id_locales_json = json_encode($id_locales_array);
            $id_clientes_json = json_encode($id_clientes_array);
            $id_ventas_json = json_encode($id_ventas_array);
            $direcciones_json = !empty($direcciones_array) ? json_encode($direcciones_array) : json_encode([]);
            
            // Mantener el cliente principal original para preservar el día, o usar el primero si no se especifica
            $id_clientes = $cliente_principal_original ?? (!empty($id_clientes_array) ? intval($id_clientes_array[0]) : null);
            $id_ventas = !empty($id_ventas_array) ? intval($id_ventas_array[0]) : null;
            
            error_log("DEBUG UPDATE - Cliente principal final: " . ($id_clientes ?? 'NULL'));
            error_log("DEBUG UPDATE - Día a actualizar: " . ($dia_semana ?? 'NULL'));
            
            // Construir SQL con día si está especificado
            if ($dia_semana) {
                $sql = "UPDATE rutas SET direccion = ?, nombre_local = ?, nombre_cliente = ?, id_clientes = ?, id_ventas = ?, estado = ?, id_locales_json = ?, id_clientes_json = ?, id_ventas_json = ?, direcciones_json = ?, dia_semana = ? WHERE id_ruta = ?";
            } else {
                $sql = "UPDATE rutas SET direccion = ?, nombre_local = ?, nombre_cliente = ?, id_clientes = ?, id_ventas = ?, estado = ?, id_locales_json = ?, id_clientes_json = ?, id_ventas_json = ?, direcciones_json = ? WHERE id_ruta = ?";
            }
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $conn->error);
            }
            
            if ($dia_semana) {
                $stmt->bind_param('sssisssssssi', $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_ventas, $estado, $id_locales_json, $id_clientes_json, $id_ventas_json, $direcciones_json, $dia_semana, $id);
            } else {
                $stmt->bind_param('sssissssssi', $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_ventas, $estado, $id_locales_json, $id_clientes_json, $id_ventas_json, $direcciones_json, $id);
            }
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
            
            error_log("DEBUG UPDATE - Ruta actualizada exitosamente. Filas afectadas: " . $stmt->affected_rows);
            return true;
        } catch (Exception $e) {
            error_log("Error al actualizar ruta múltiple preservando día: " . $e->getMessage());
            throw new Exception("Error al actualizar la ruta: " . $e->getMessage());
        }
    }

    public static function delete($conn, $id) {
        $sql = "DELETE FROM rutas WHERE id_ruta = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
    
    // Obtener el día de una ruta directamente de la tabla rutas
    public static function getDayFromRoute($conn, $route_id) {
        try {
            // Primero intentar obtener el día directamente de la tabla rutas
            $sql = "SELECT dia_semana FROM rutas WHERE id_ruta = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $route_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                if ($row['dia_semana']) {
                    return $row['dia_semana'];
                }
            }
            
            // Si no hay día almacenado, obtener la ruta y buscar basándose en el cliente
            $route = self::getById($conn, $route_id);
            if (!$route || !$route['id_clientes']) {
                return null;
            }
            
            $cliente_id = intval($route['id_clientes']);
            
            // Buscar en qué día está asignado este cliente en ruta_clientes_semanales
            $sql = "SELECT dia_semana FROM ruta_clientes_semanales WHERE cliente_id = ? LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $cliente_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                return $row['dia_semana'];
            }
            
            return null;
        } catch (Exception $e) {
            error_log("Error al obtener día de ruta: " . $e->getMessage());
            return null;
        }
    }
    
    // Verificar si una ruta tiene día almacenado
    public static function hasStoredDay($conn, $route_id) {
        try {
            $sql = "SELECT dia_semana FROM rutas WHERE id_ruta = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $route_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                return !empty($row['dia_semana']);
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    // Actualizar el día de una ruta
    public static function updateDay($conn, $route_id, $dia_semana) {
        try {
            $sql = "UPDATE rutas SET dia_semana = ? WHERE id_ruta = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $dia_semana, $route_id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar día de ruta: " . $e->getMessage());
            return false;
        }
    }

    // Crear una nueva ruta con múltiples locales y clientes
    public static function createMultiple($conn, $direccion, $nombre_local, $nombre_cliente, $id_locales_array, $id_clientes_array, $id_ventas_array, $estado, $direcciones_array = [], $dia_semana = null) {
        try {
            // Convertir arrays a JSON
            $id_locales_json = json_encode($id_locales_array);
            $id_clientes_json = json_encode($id_clientes_array);
            $id_ventas_json = json_encode($id_ventas_array);
            $direcciones_json = !empty($direcciones_array) ? json_encode($direcciones_array) : json_encode([]);
            
            // Usar el primer cliente como cliente principal
            $id_clientes = !empty($id_clientes_array) ? intval($id_clientes_array[0]) : null;
            $id_ventas = !empty($id_ventas_array) ? intval($id_ventas_array[0]) : null;
            
            // Construir SQL con día si está especificado
            if ($dia_semana) {
                $sql = "INSERT INTO rutas (direccion, nombre_local, nombre_cliente, id_clientes, id_ventas, estado, id_locales_json, id_clientes_json, id_ventas_json, direcciones_json, dia_semana) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            } else {
                $sql = "INSERT INTO rutas (direccion, nombre_local, nombre_cliente, id_clientes, id_ventas, estado, id_locales_json, id_clientes_json, id_ventas_json, direcciones_json) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            }
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $conn->error);
            }
            
            if ($dia_semana) {
                $stmt->bind_param('sssisssssss', $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_ventas, $estado, $id_locales_json, $id_clientes_json, $id_ventas_json, $direcciones_json, $dia_semana);
            } else {
                $stmt->bind_param('sssissssss', $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_ventas, $estado, $id_locales_json, $id_clientes_json, $id_ventas_json, $direcciones_json);
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
            
            return $conn->insert_id;
        } catch (Exception $e) {
            error_log("Error al crear ruta múltiple: " . $e->getMessage());
            throw new Exception("Error al crear la ruta: " . $e->getMessage());
        }
    }

    // Métodos para obtener datos relacionados
    public static function getAvailableClients($conn) {
        try {
            $sql = "SELECT id_clientes, nombre FROM clientes ORDER BY nombre";
            $result = $conn->query($sql);
            if (!$result) {
                throw new Exception("Error al obtener clientes: " . $conn->error);
            }
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getAvailableClients: " . $e->getMessage());
            return [];
        }
    }

    public static function getAvailableSales($conn) {
        try {
            $sql = "SELECT id_ventas, CONCAT('Venta #', id_ventas, ' - $', total) as descripcion FROM ventas ORDER BY id_ventas DESC";
            $result = $conn->query($sql);
            if (!$result) {
                throw new Exception("Error al obtener ventas: " . $conn->error);
            }
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getAvailableSales: " . $e->getMessage());
            return [];
        }
    }

    public static function getAvailableLocals($conn) {
        try {
            $sql = "SELECT id_locales, nombre_local, direccion, localidad, barrio FROM locales WHERE estado = 'activo' ORDER BY nombre_local";
            $result = $conn->query($sql);
            if (!$result) {
                throw new Exception("Error al obtener locales: " . $conn->error);
            }
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getAvailableLocals: " . $e->getMessage());
            return [];
        }
    }

    // Validar si existe un cliente
    public static function clientExists($conn, $id_cliente) {
        try {
            $sql = "SELECT COUNT(*) as count FROM clientes WHERE id_clientes = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id_cliente);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'] > 0;
        } catch (Exception $e) {
            error_log("Error en clientExists: " . $e->getMessage());
            return false;
        }
    }

    // Validar si existe una venta
    public static function saleExists($conn, $id_venta) {
        try {
            $sql = "SELECT COUNT(*) as count FROM ventas WHERE id_ventas = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id_venta);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'] > 0;
        } catch (Exception $e) {
            error_log("Error en saleExists: " . $e->getMessage());
            return false;
        }
    }
}
?>
