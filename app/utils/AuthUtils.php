<?php
/**
 * Utilidades de Autenticación y Autorización
 * Funciones para verificar sesiones y roles de usuario
 */

class AuthUtils {
    
    /**
     * Verifica si el usuario coordinador puede realizar eliminaciones
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
        
        // El coordinador NO puede eliminar por políticas de seguridad
        return $_SESSION['rol'] !== 'coordinador';
    }
    
    /**
     * Bloquea eliminación para coordinadores con mensaje y redirección
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
        
        // Verificar si el rol es coordinador y restringir eliminación
        if ($_SESSION['rol'] === 'coordinador') {
            echo '<script>alert("El rol de coordinador no tiene permisos para eliminar registros por políticas de seguridad."); window.location.href = "' . $redirectUrl . '";</script>';
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
}
?>