<?php
/**
 * Middleware de Autorización
 * Controla el acceso a módulos y acciones según el rol del usuario
 * Basado en el diagrama de casos de uso
 */

require_once __DIR__ . '/PermissionsConfig.php';
require_once __DIR__ . '/AuthUtils.php';

class AuthorizationMiddleware {
    
    /**
     * Verifica si el usuario tiene permisos para acceder a un módulo con una acción específica
     * @param string $module Nombre del módulo (usuarios, ventas, reportes, etc.)
     * @param string $action Acción a realizar (create, read, update, delete)
     * @return bool true si tiene permisos, false si no
     */
    public static function hasPermission($module, $action) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar si está logueado
        if (!AuthUtils::isLoggedIn()) {
            return false;
        }
        
        $role = AuthUtils::getCurrentUserRole();
        return PermissionsConfig::hasPermission($role, $module, $action);
    }
    
    /**
     * Middleware para verificar permisos antes de ejecutar una acción
     * @param string $module Nombre del módulo
     * @param string $action Acción a realizar
     * @param string $redirectUrl URL de redirección en caso de acceso denegado
     * @throws Exception Si no tiene permisos
     */
    public static function authorize($module, $action, $redirectUrl = null) {
        if (!self::hasPermission($module, $action)) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            $role = AuthUtils::getCurrentUserRole() ?? 'sin_sesion';
            $error = "Acceso denegado: El rol '$role' no tiene permisos para '$action' en el módulo '$module'.";
            
            if ($redirectUrl) {
                $_SESSION['error'] = $error;
                header('Location: ' . $redirectUrl);
                exit();
            } else {
                throw new Exception($error);
            }
        }
    }
    
    /**
     * Verifica específicamente si el auxiliar puede acceder según el diagrama
     * @param string $module Módulo a verificar
     * @return bool
     */
    public static function auxiliarCanAccess($module) {
        if (!AuthUtils::isAuxiliar()) {
            return false;
        }
        
        return PermissionsConfig::auxiliarCanAccess($module);
    }
    
    /**
     * Bloquea acceso a módulos no autorizados para auxiliares
     * @param string $module Módulo solicitado
     * @param string $dashboardUrl URL del dashboard auxiliar
     */
    public static function blockUnauthorizedAuxiliarAccess($module, $dashboardUrl) {
        if (AuthUtils::isAuxiliar() && !self::auxiliarCanAccess($module)) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            $caseUseInfo = PermissionsConfig::getCaseUseInfo($module);
            if ($caseUseInfo) {
                $mensaje = "Acceso autorizado solo a: " . $caseUseInfo['case_use'] . " - " . $caseUseInfo['title'];
            } else {
                $mensaje = "El módulo '$module' no está autorizado para el rol auxiliar según el diagrama de casos de uso.";
            }
            
            $_SESSION['error'] = $mensaje;
            header('Location: ' . $dashboardUrl);
            exit();
        }
    }
    
    /**
     * Obtiene los módulos permitidos para el rol actual
     * @return array Array de módulos permitidos
     */
    public static function getAllowedModulesForCurrentUser() {
        if (!AuthUtils::isLoggedIn()) {
            return [];
        }
        
        $role = AuthUtils::getCurrentUserRole();
        return PermissionsConfig::getAllowedModules($role);
    }
    
    /**
     * Verifica si el usuario actual puede eliminar registros
     * @return bool
     */
    public static function canDelete() {
        return AuthUtils::canDelete();
    }
    
    /**
     * Muestra mensaje de error de permisos y redirige
     * @param string $action Acción que se intentó realizar
     * @param string $module Módulo en el que se intentó la acción
     * @param string $redirectUrl URL de redirección
     */
    public static function showPermissionError($action, $module, $redirectUrl) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $role = AuthUtils::getCurrentUserRole();
        $actionNames = [
            'create' => 'crear',
            'read' => 'consultar',
            'update' => 'modificar',
            'delete' => 'eliminar'
        ];
        
        $actionText = $actionNames[$action] ?? $action;
        $message = "El rol '$role' no tiene permisos para $actionText registros en el módulo '$module'.";
        
        echo "<script>alert('$message'); window.location.href = '$redirectUrl';</script>";
        exit();
    }
}