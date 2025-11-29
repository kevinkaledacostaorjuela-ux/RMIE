<?php

class RouteController
{
    private $conn;
    
    public function __construct($connection)
    {
        $this->conn = $connection;
    }
    
    /**
     * Obtiene la ruta y clientes del día actual
     * @return array|null
     */
    public function getRutaDelDia()
    {
        $diaActual = date('l'); // Monday, Tuesday, etc.
        
        $sql = "SELECT r.id, r.dia, r.nombre_ruta, r.descripcion, r.estado, r.fecha_creacion, r.fecha_actualizacion
                FROM rutas r 
                WHERE r.dia = ?";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparando consulta: " . $this->conn->error);
        }
        
        $stmt->bind_param('s', $diaActual);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $ruta = $result->fetch_assoc();
        if (!$ruta) {
            return null;
        }
        
        // Obtener clientes asociados a esta ruta
        $ruta['clientes'] = $this->getClientesDeRuta($ruta['id']);
        
        return $ruta;
    }
    
    /**
     * Crea una nueva ruta con clientes asignados
     * @param string $dia Día de la semana (Monday, Tuesday, etc.)
     * @param array $rutaData Array con datos de la ruta (nombre_ruta, descripcion, etc.)
     * @param array $clientes Array de IDs de clientes
     * @return int ID de la ruta creada
     */
    public function crearRuta($dia, $rutaData, $clientes = [])
    {
        // Validar día
        $diasValidos = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        if (!in_array($dia, $diasValidos)) {
            throw new Exception("Día inválido. Debe ser uno de: " . implode(', ', $diasValidos));
        }
        
        // Validar datos requeridos
        if (empty($rutaData['nombre_ruta'])) {
            throw new Exception("El nombre de la ruta es requerido");
        }
        
        $this->conn->begin_transaction();
        
        try {
            // Insertar ruta
            $sql = "INSERT INTO rutas (dia, nombre_ruta, descripcion, estado, fecha_creacion, fecha_actualizacion) 
                    VALUES (?, ?, ?, ?, NOW(), NOW())";
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando consulta de ruta: " . $this->conn->error);
            }
            
            $estado = $rutaData['estado'] ?? 'activa';
            $descripcion = $rutaData['descripcion'] ?? '';
            
            $stmt->bind_param('ssss', $dia, $rutaData['nombre_ruta'], $descripcion, $estado);
            
            if (!$stmt->execute()) {
                throw new Exception("Error ejecutando inserción de ruta: " . $stmt->error);
            }
            
            $rutaId = $this->conn->insert_id;
            
            // Insertar clientes asociados
            if (!empty($clientes)) {
                $this->insertarClientesEnRuta($rutaId, $clientes);
            }
            
            $this->conn->commit();
            return $rutaId;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }
    
    /**
     * Modifica una ruta existente y reemplaza sus clientes
     * @param int $rutaId ID de la ruta a modificar
     * @param string $dia Nuevo día de la semana
     * @param array $rutaData Nuevos datos de la ruta
     * @param array $clientes Nuevos clientes (reemplaza todos los existentes)
     * @return bool
     */
    public function modificarRuta($rutaId, $dia, $rutaData, $clientes = [])
    {
        // Validar día
        $diasValidos = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        if (!in_array($dia, $diasValidos)) {
            throw new Exception("Día inválido. Debe ser uno de: " . implode(', ', $diasValidos));
        }
        
        // Verificar que la ruta existe
        if (!$this->rutaExiste($rutaId)) {
            throw new Exception("La ruta con ID $rutaId no existe");
        }
        
        $this->conn->begin_transaction();
        
        try {
            // Actualizar ruta
            $sql = "UPDATE rutas SET 
                    dia = ?, 
                    nombre_ruta = ?, 
                    descripcion = ?, 
                    estado = ?, 
                    fecha_actualizacion = NOW() 
                    WHERE id = ?";
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando consulta de actualización: " . $this->conn->error);
            }
            
            $estado = $rutaData['estado'] ?? 'activa';
            $descripcion = $rutaData['descripcion'] ?? '';
            
            $stmt->bind_param('ssssi', 
                $dia, 
                $rutaData['nombre_ruta'], 
                $descripcion, 
                $estado, 
                $rutaId
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Error ejecutando actualización de ruta: " . $stmt->error);
            }
            
            // Eliminar todos los clientes existentes de esta ruta
            $this->eliminarClientesDeRuta($rutaId);
            
            // Insertar nuevos clientes
            if (!empty($clientes)) {
                $this->insertarClientesEnRuta($rutaId, $clientes);
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }
    
    /**
     * Obtiene todas las rutas con sus clientes
     * @return array
     */
    public function getTodasRutas()
    {
        $sql = "SELECT id, dia, nombre_ruta, descripcion, estado, fecha_creacion, fecha_actualizacion
                FROM rutas 
                ORDER BY 
                    CASE dia 
                        WHEN 'Monday' THEN 1 
                        WHEN 'Tuesday' THEN 2 
                        WHEN 'Wednesday' THEN 3 
                        WHEN 'Thursday' THEN 4 
                        WHEN 'Friday' THEN 5 
                    END,
                    nombre_ruta";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparando consulta: " . $this->conn->error);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $rutas = [];
        while ($ruta = $result->fetch_assoc()) {
            $ruta['clientes'] = $this->getClientesDeRuta($ruta['id']);
            $rutas[] = $ruta;
        }
        
        return $rutas;
    }
    
    /**
     * Obtiene todas las rutas de un día específico
     * @param string $dia Día de la semana
     * @return array
     */
    public function getRutasPorDia($dia)
    {
        $diasValidos = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        if (!in_array($dia, $diasValidos)) {
            throw new Exception("Día inválido. Debe ser uno de: " . implode(', ', $diasValidos));
        }
        
        $sql = "SELECT id, dia, nombre_ruta, descripcion, estado, fecha_creacion, fecha_actualizacion
                FROM rutas 
                WHERE dia = ?
                ORDER BY nombre_ruta";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparando consulta: " . $this->conn->error);
        }
        
        $stmt->bind_param('s', $dia);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $rutas = [];
        while ($ruta = $result->fetch_assoc()) {
            $ruta['clientes'] = $this->getClientesDeRuta($ruta['id']);
            $rutas[] = $ruta;
        }
        
        return $rutas;
    }
    
    /**
     * Elimina una ruta y todos sus clientes asociados
     * @param int $rutaId ID de la ruta a eliminar
     * @return bool
     */
    public function eliminarRuta($rutaId)
    {
        if (!$this->rutaExiste($rutaId)) {
            throw new Exception("La ruta con ID $rutaId no existe");
        }
        
        $this->conn->begin_transaction();
        
        try {
            // Eliminar clientes asociados
            $this->eliminarClientesDeRuta($rutaId);
            
            // Eliminar ruta
            $sql = "DELETE FROM rutas WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando consulta de eliminación: " . $this->conn->error);
            }
            
            $stmt->bind_param('i', $rutaId);
            
            if (!$stmt->execute()) {
                throw new Exception("Error ejecutando eliminación de ruta: " . $stmt->error);
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }
    
    // ==================== MÉTODOS PRIVADOS ====================
    
    /**
     * Obtiene los clientes asociados a una ruta
     * @param int $rutaId ID de la ruta
     * @return array Array de IDs de clientes
     */
    private function getClientesDeRuta($rutaId)
    {
        $sql = "SELECT cliente_id FROM ruta_clientes WHERE ruta_id = ? ORDER BY id";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparando consulta de clientes: " . $this->conn->error);
        }
        
        $stmt->bind_param('i', $rutaId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $clientes = [];
        while ($row = $result->fetch_assoc()) {
            $clientes[] = (int)$row['cliente_id'];
        }
        
        return $clientes;
    }
    
    /**
     * Inserta clientes en una ruta
     * @param int $rutaId ID de la ruta
     * @param array $clientes Array de IDs de clientes
     */
    private function insertarClientesEnRuta($rutaId, $clientes)
    {
        if (empty($clientes)) {
            return;
        }
        
        // Validar que todos los elementos sean números enteros
        foreach ($clientes as $clienteId) {
            if (!is_numeric($clienteId) || $clienteId <= 0) {
                throw new Exception("ID de cliente inválido: $clienteId");
            }
        }
        
        $sql = "INSERT INTO ruta_clientes (ruta_id, cliente_id, fecha_asignacion) VALUES (?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparando consulta de inserción de clientes: " . $this->conn->error);
        }
        
        foreach ($clientes as $clienteId) {
            $clienteId = (int)$clienteId;
            $stmt->bind_param('ii', $rutaId, $clienteId);
            
            if (!$stmt->execute()) {
                throw new Exception("Error insertando cliente $clienteId en ruta $rutaId: " . $stmt->error);
            }
        }
    }
    
    /**
     * Elimina todos los clientes de una ruta
     * @param int $rutaId ID de la ruta
     */
    private function eliminarClientesDeRuta($rutaId)
    {
        $sql = "DELETE FROM ruta_clientes WHERE ruta_id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparando consulta de eliminación de clientes: " . $this->conn->error);
        }
        
        $stmt->bind_param('i', $rutaId);
        
        if (!$stmt->execute()) {
            throw new Exception("Error eliminando clientes de la ruta $rutaId: " . $stmt->error);
        }
    }
    
    /**
     * Verifica si una ruta existe
     * @param int $rutaId ID de la ruta
     * @return bool
     */
    private function rutaExiste($rutaId)
    {
        $sql = "SELECT COUNT(*) as count FROM rutas WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparando consulta de verificación: " . $this->conn->error);
        }
        
        $stmt->bind_param('i', $rutaId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'] > 0;
    }
    
    /**
     * Obtiene estadísticas de rutas por día
     * @return array
     */
    public function getEstadisticasPorDia()
    {
        $sql = "SELECT 
                    dia,
                    COUNT(*) as total_rutas,
                    SUM(CASE WHEN estado = 'activa' THEN 1 ELSE 0 END) as rutas_activas,
                    SUM(CASE WHEN estado = 'completada' THEN 1 ELSE 0 END) as rutas_completadas,
                    SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as rutas_pendientes
                FROM rutas 
                GROUP BY dia
                ORDER BY 
                    CASE dia 
                        WHEN 'Monday' THEN 1 
                        WHEN 'Tuesday' THEN 2 
                        WHEN 'Wednesday' THEN 3 
                        WHEN 'Thursday' THEN 4 
                        WHEN 'Friday' THEN 5 
                    END";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparando consulta de estadísticas: " . $this->conn->error);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $estadisticas = [];
        while ($row = $result->fetch_assoc()) {
            $estadisticas[$row['dia']] = $row;
        }
        
        return $estadisticas;
    }
}

?>