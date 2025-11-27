<?php
class Route {
    // Método mejorado con filtros
    public static function getAll($conn, $filtros = []) {
        require_once __DIR__ . '/../utils/FilterHelper.php';
        
        // Definir reglas de validación para filtros
        $filterRules = [
            'nombre_cliente' => ['type' => 'text', 'options' => ['max_length' => 100]],
            'venta' => ['type' => 'int', 'options' => ['min' => 1]],
            'reporte' => ['type' => 'int', 'options' => ['min' => 1]],
            'direccion' => ['type' => 'text', 'options' => ['max_length' => 200]],
            'nombre_local' => ['type' => 'text', 'options' => ['max_length' => 100]],
            'estado' => ['type' => 'text', 'options' => ['max_length' => 20]],
            'buscar' => ['type' => 'text', 'options' => ['max_length' => 100]]
        ];
        
        // Procesar filtros
        $filtrosProcesados = FilterHelper::processFilters($filtros, $filterRules);
        
        // Mapeo de campos a columnas SQL
        $mapping = [
            'nombre_cliente' => ['column' => 'c.nombre', 'operator' => '=', 'type' => 's'],
            'venta' => ['column' => 'r.id_ventas', 'operator' => '=', 'type' => 'i'],
            'reporte' => ['column' => 'r.id_reportes', 'operator' => '=', 'type' => 'i'],
            'direccion' => ['column' => 'r.direccion', 'operator' => 'LIKE', 'type' => 's'],
            'nombre_local' => ['column' => 'l.nombre_local', 'operator' => '=', 'type' => 's'],
            'estado' => ['column' => 'r.estado', 'operator' => '=', 'type' => 's'],
            'buscar' => [
                'columns' => ['r.direccion', 'l.nombre_local', 'c.nombre'],
                'operator' => 'MULTIPLE_LIKE'
            ]
        ];
        
        // Construir consulta base con JOINs para obtener información relacionada de clientes y locales
        $sql = "SELECT r.*, 
                       c.nombre as cliente_nombre,
                       c.cel_cliente as cliente_celular,
                       c.correo as cliente_correo,
                       c.estado as cliente_estado,
                       l.nombre_local as local_nombre,
                       l.direccion as local_direccion,
                       l.cel_local as local_celular,
                       l.estado as local_estado,
                       l.localidad as local_localidad,
                       l.barrio as local_barrio,
                       v.nombre as venta_nombre,
                       v.cantidad as venta_cantidad,
                       v.fecha_venta as venta_fecha,
                       rep.nombre as reporte_nombre
                FROM rutas r 
                LEFT JOIN clientes c ON r.id_clientes = c.id_clientes
                LEFT JOIN locales l ON r.nombre_local = l.nombre_local
                LEFT JOIN ventas v ON r.id_ventas = v.id_ventas
                LEFT JOIN reportes rep ON r.id_reportes = rep.id_reportes
                WHERE 1=1";
        
        // Construir WHERE con filtros
        $whereData = FilterHelper::buildWhereClause($filtrosProcesados, $mapping);
        
        if (!empty($whereData['where'])) {
            $sql .= " AND " . implode(" AND ", $whereData['where']);
        }
        
        $sql .= " ORDER BY r.id_ruta DESC";
        
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

    public static function create($conn, $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_ventas, $estado) {
        try {
            $sql = "INSERT INTO rutas (direccion, nombre_local, nombre_cliente, id_clientes, id_ventas, estado) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $conn->error);
            }
            $stmt->bind_param('sssiss', $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_ventas, $estado);
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
            return $conn->insert_id; // Retornar el ID de la nueva ruta creada
        } catch (Exception $e) {
            error_log("Error al crear ruta: " . $e->getMessage());
            throw new Exception("Error al crear la ruta: " . $e->getMessage());
        }
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

    public static function delete($conn, $id) {
        $sql = "DELETE FROM rutas WHERE id_ruta = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
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
