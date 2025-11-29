<?php
/**
 * RouteController mejorado - Sistema de rutas por día de la semana
 * Requisitos: PHP 7.4+, MySQLi, MVC, POO
 */

class RouteControllerWeekly
{
    private $conn;
    private $diasValidos = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    
    public function __construct($connection)
    {
        $this->conn = $connection;
    }
    
    /**
     * Obtiene la ruta y clientes del día actual desde la base de datos
     * @return array|null
     */
    public function getRutaDelDia()
    {
        $diaActual = date('l'); // Monday, Tuesday, etc.
        
        $sql = "SELECT r.id, r.dia, r.nombre_ruta, r.descripcion, r.estado, 
                       r.fecha_creacion, r.fecha_actualizacion
                FROM rutas_semanales r 
                WHERE r.dia = ?";
        
        try {
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
            
            // Obtener clientes asociados
            $ruta['clientes'] = $this->getClientesDeRuta($ruta['id']);
            
            return $ruta;
            
        } catch (Exception $e) {
            error_log("Error en getRutaDelDia: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Crea nueva ruta y asigna clientes
     * @param string $dia Día de la semana (Monday, Tuesday, etc.)
     * @param array $ruta Datos de la ruta
     * @param array $clientes Array de IDs de clientes
     * @return int|false ID de la ruta creada o false en caso de error
     */
    public function crearRuta($dia, $ruta, $clientes = [])
    {
        // Validar día
        if (!in_array($dia, $this->diasValidos)) {
            throw new Exception("Día inválido. Debe ser: " . implode(', ', $this->diasValidos));
        }
        
        // Validar datos de ruta
        if (empty($ruta['nombre_ruta'])) {
            throw new Exception("El nombre de la ruta es requerido");
        }
        
        $this->conn->begin_transaction();
        
        try {
            // Verificar si ya existe una ruta para este día
            $existeRuta = $this->rutaExisteParaDia($dia);
            if ($existeRuta) {
                throw new Exception("Ya existe una ruta para el día $dia. Use modificarRuta() para actualizarla.");
            }
            
            // Insertar ruta
            $sql = "INSERT INTO rutas_semanales (dia, nombre_ruta, descripcion, estado, fecha_creacion, fecha_actualizacion) 
                    VALUES (?, ?, ?, ?, NOW(), NOW())";
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando consulta: " . $this->conn->error);
            }
            
            $estado = $ruta['estado'] ?? 'activa';
            $descripcion = $ruta['descripcion'] ?? '';
            
            $stmt->bind_param('ssss', $dia, $ruta['nombre_ruta'], $descripcion, $estado);
            
            if (!$stmt->execute()) {
                throw new Exception("Error ejecutando inserción: " . $stmt->error);
            }
            
            $rutaId = $this->conn->insert_id;
            
            // Asignar clientes
            if (!empty($clientes)) {
                $this->asignarClientesARuta($rutaId, $clientes);
            }
            
            $this->conn->commit();
            return $rutaId;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }
    
    /**
     * Modifica la ruta y reemplaza clientes
     * @param int $rutaId ID de la ruta
     * @param string $dia Día de la semana
     * @param array $ruta Datos actualizados de la ruta
     * @param array $clientes Nuevos clientes (reemplaza todos)
     * @return bool
     */
    public function modificarRuta($rutaId, $dia, $ruta, $clientes = [])
    {
        // Validar día
        if (!in_array($dia, $this->diasValidos)) {
            throw new Exception("Día inválido. Debe ser: " . implode(', ', $this->diasValidos));
        }
        
        // Verificar que la ruta existe
        if (!$this->rutaExistePorId($rutaId)) {
            throw new Exception("La ruta con ID $rutaId no existe");
        }
        
        $this->conn->begin_transaction();
        
        try {
            // Actualizar ruta
            $sql = "UPDATE rutas_semanales SET 
                    dia = ?, 
                    nombre_ruta = ?, 
                    descripcion = ?, 
                    estado = ?, 
                    fecha_actualizacion = NOW() 
                    WHERE id = ?";
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando consulta: " . $this->conn->error);
            }
            
            $estado = $ruta['estado'] ?? 'activa';
            $descripcion = $ruta['descripcion'] ?? '';
            
            $stmt->bind_param('ssssi', $dia, $ruta['nombre_ruta'], $descripcion, $estado, $rutaId);
            
            if (!$stmt->execute()) {
                throw new Exception("Error ejecutando actualización: " . $stmt->error);
            }
            
            // Eliminar clientes existentes y asignar nuevos
            $this->eliminarClientesDeRuta($rutaId);
            
            if (!empty($clientes)) {
                $this->asignarClientesARuta($rutaId, $clientes);
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }
    
    /**
     * Devuelve todas las rutas y clientes
     * @return array
     */
    public function getTodasRutas()
    {
        $sql = "SELECT r.id, r.dia, r.nombre_ruta, r.descripcion, r.estado, 
                       r.fecha_creacion, r.fecha_actualizacion
                FROM rutas_semanales r 
                ORDER BY 
                    CASE r.dia 
                        WHEN 'Monday' THEN 1 
                        WHEN 'Tuesday' THEN 2 
                        WHEN 'Wednesday' THEN 3 
                        WHEN 'Thursday' THEN 4 
                        WHEN 'Friday' THEN 5 
                    END,
                    r.nombre_ruta";
        
        try {
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
            
        } catch (Exception $e) {
            error_log("Error en getTodasRutas: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene ruta por día específico
     * @param string $dia
     * @return array|null
     */
    public function getRutaPorDia($dia)
    {
        if (!in_array($dia, $this->diasValidos)) {
            throw new Exception("Día inválido");
        }
        
        $sql = "SELECT r.id, r.dia, r.nombre_ruta, r.descripcion, r.estado, 
                       r.fecha_creacion, r.fecha_actualizacion
                FROM rutas_semanales r 
                WHERE r.dia = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando consulta: " . $this->conn->error);
            }
            
            $stmt->bind_param('s', $dia);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $ruta = $result->fetch_assoc();
            if (!$ruta) {
                return null;
            }
            
            $ruta['clientes'] = $this->getClientesDeRuta($ruta['id']);
            
            return $ruta;
            
        } catch (Exception $e) {
            error_log("Error en getRutaPorDia: " . $e->getMessage());
            return null;
        }
    }
    
    // ==================== MÉTODOS PRIVADOS ====================
    
    /**
     * Obtiene clientes asociados a una ruta
     */
    private function getClientesDeRuta($rutaId)
    {
        $sql = "SELECT cliente_id FROM ruta_clientes_semanales WHERE ruta_id = ? ORDER BY id";
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
     * Asigna clientes a una ruta
     */
    private function asignarClientesARuta($rutaId, $clientes)
    {
        if (empty($clientes)) {
            return;
        }
        
        // Validar que todos los clientes sean números válidos
        foreach ($clientes as $clienteId) {
            if (!is_numeric($clienteId) || $clienteId <= 0) {
                throw new Exception("ID de cliente inválido: $clienteId");
            }
        }
        
        $sql = "INSERT INTO ruta_clientes_semanales (ruta_id, cliente_id, fecha_asignacion) VALUES (?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparando consulta de clientes: " . $this->conn->error);
        }
        
        foreach ($clientes as $clienteId) {
            $clienteId = (int)$clienteId;
            $stmt->bind_param('ii', $rutaId, $clienteId);
            
            if (!$stmt->execute()) {
                throw new Exception("Error asignando cliente $clienteId: " . $stmt->error);
            }
        }
    }
    
    /**
     * Elimina todos los clientes de una ruta
     */
    private function eliminarClientesDeRuta($rutaId)
    {
        $sql = "DELETE FROM ruta_clientes_semanales WHERE ruta_id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparando consulta de eliminación: " . $this->conn->error);
        }
        
        $stmt->bind_param('i', $rutaId);
        
        if (!$stmt->execute()) {
            throw new Exception("Error eliminando clientes: " . $stmt->error);
        }
    }
    
    /**
     * Verifica si existe una ruta para un día específico
     */
    private function rutaExisteParaDia($dia)
    {
        $sql = "SELECT COUNT(*) as count FROM rutas_semanales WHERE dia = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparando consulta: " . $this->conn->error);
        }
        
        $stmt->bind_param('s', $dia);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'] > 0;
    }
    
    /**
     * Verifica si existe una ruta por ID
     */
    private function rutaExistePorId($rutaId)
    {
        $sql = "SELECT COUNT(*) as count FROM rutas_semanales WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparando consulta: " . $this->conn->error);
        }
        
        $stmt->bind_param('i', $rutaId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'] > 0;
    }
}
?>