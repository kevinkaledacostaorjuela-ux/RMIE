<?php
/**
 * Utilidades de Autenticación y Autorización
 * Funciones para verificar sesiones y roles de usuario
 */

class AuthUtils {
    
    /**
     * Verifica si el usuario puede realizar eliminaciones
     * @return bool true si puede eliminar, false si no puede
     */
    public static function canDelete() {
        // Verificar sesión activa
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user']) || !isset($_SESSION['rol'])) {
            return false;
        }
        
        // Solo el admin puede eliminar, coordinador y auxiliar NO
        return $_SESSION['rol'] === 'admin';
    }
    
    /**
     * Bloquea eliminación para coordinadores y auxiliares con mensaje y redirección
     * @param string $redirectUrl URL a la que redirigir si no puede eliminar
     */
    public static function blockDeleteForCoordinator($redirectUrl) {
        // Verificar sesión activa
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user']) || !isset($_SESSION['rol'])) {
            echo '<script>alert("Debe iniciar sesión para realizar esta acción."); window.location.href = "/RMIE/index.php";</script>';
            exit();
        }
        
        // Verificar si el rol es coordinador o auxiliar y restringir eliminación
        if ($_SESSION['rol'] === 'coordinador') {
            echo '<script>alert("El rol de coordinador no tiene permisos para eliminar registros por políticas de seguridad."); window.location.href = "' . $redirectUrl . '";</script>';
            exit();
        }
        
        if ($_SESSION['rol'] === 'auxiliar') {
            echo '<script>alert("El rol de auxiliar no tiene permisos para eliminar registros por políticas de seguridad."); window.location.href = "' . $redirectUrl . '";</script>';
            exit();
        }
    }
    
    /**
     * Obtiene el rol del usuario actual
     * @return string|null El rol del usuario o null si no está logueado
     */
    public static function getCurrentUserRole() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return $_SESSION['rol'] ?? null;
    }
    
    /**
     * Verifica si el usuario está logueado
     * @return bool true si está logueado, false si no
     */
    public static function isLoggedIn() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['user']) && isset($_SESSION['rol']);
    }
    
    /**
     * Verifica si el usuario actual es administrador
     * @return bool true si es admin, false si no
     */
    public static function isAdmin() {
        return self::getCurrentUserRole() === 'admin';
    }
    
    /**
     * Verifica si el usuario actual es coordinador
     * @return bool true si es coordinador, false si no
     */
    public static function isCoordinador() {
        return self::getCurrentUserRole() === 'coordinador';
    }
    
    /**
     * Verifica si el usuario actual es auxiliar
     * @return bool true si es auxiliar, false si no
     */
    public static function isAuxiliar() {
        return self::getCurrentUserRole() === 'auxiliar';
    }
    
    /**
     * Verifica si el usuario puede editar (admin y coordinador sí, auxiliar solo lectura)
     * @return bool true si puede editar, false si no
     */
    public static function canEdit() {
        $rol = self::getCurrentUserRole();
        return $rol === 'admin' || $rol === 'coordinador';
    }
    
    /**
     * Obtiene la descripción legible del rol
     * @param string $rol El rol a describir
     * @return string Descripción del rol
     */
    public static function getRoleDescription($rol) {
        switch ($rol) {
            case 'admin':
                return 'Administrador';
            case 'coordinador':
                return 'Coordinador';
            case 'auxiliar':
                return 'Auxiliar';
            default:
                return 'Desconocido';
        }
    }
    
    /**
     * Obtiene el color del badge para el rol
     * @param string $rol El rol
     * @return string Clase CSS para el color del badge
     */
    public static function getRoleBadgeClass($rol) {
        switch ($rol) {
            case 'admin':
                return 'bg-danger'; // Rojo para admin
            case 'coordinador':
                return 'bg-primary'; // Azul para coordinador
            case 'auxiliar':
                return 'bg-success'; // Verde para auxiliar
            default:
                return 'bg-secondary';
        }
    }
}
?>