<?php
/**
 * Configuración de Permisos por Rol
 * Define qué módulos y acciones puede realizar cada rol
 */

class PermissionsConfig {
    
    /**
     * Configuración de permisos por rol según casos de uso del diagrama
     */
    const ROLE_PERMISSIONS = [
        'admin' => [
            'usuarios' => ['create', 'read', 'update', 'delete'],
            'clientes' => ['create', 'read', 'update', 'delete'],
            'productos' => ['create', 'read', 'update', 'delete'],
            'proveedores' => ['create', 'read', 'update', 'delete'],
            'categorias' => ['create', 'read', 'update', 'delete'],
            'subcategorias' => ['create', 'read', 'update', 'delete'],
            'ventas' => ['create', 'read', 'update', 'delete'],
            'reportes' => ['create', 'read', 'update', 'delete'],
            'rutas' => ['create', 'read', 'update', 'delete'],
            'locales' => ['create', 'read', 'update', 'delete'],
            'alertas' => ['create', 'read', 'update', 'delete'],
            'profile' => ['read', 'update']
        ],
        'coordinador' => [
            'usuarios' => ['create', 'read', 'update'],
            'clientes' => ['create', 'read', 'update'],
            'productos' => ['create', 'read', 'update'],
            'proveedores' => ['create', 'read', 'update'],
            'categorias' => ['create', 'read', 'update'],
            'subcategorias' => ['create', 'read', 'update'],
            'ventas' => ['create', 'read', 'update'],
            'reportes' => ['create', 'read', 'update'],
            'rutas' => ['create', 'read', 'update'],
            'locales' => ['create', 'read', 'update'],
            'alertas' => ['create', 'read', 'update'],
            'profile' => ['read', 'update']
        ],
        'auxiliar' => [
            // Gestión completa de usuarios
            'usuarios' => ['create', 'read', 'update', 'delete'],
            
            // Gestión completa de ventas
            'ventas' => ['create', 'read', 'update', 'delete'],
            
            // Gestión completa de reportes
            'reportes' => ['create', 'read', 'update', 'delete'],
            
            // Gestión completa de alertas
            'alertas' => ['create', 'read', 'update', 'delete'],
            
            // Gestión completa de rutas
            'rutas' => ['create', 'read', 'update', 'delete'],
            
            // Consultar/Modificar (solo su perfil)
            'profile' => ['read', 'update']
        ]
    ];
    
    /**
     * Módulos disponibles en el dashboard auxiliar según diagrama
     */
    const AUXILIAR_MODULES = [
        'ventas' => [
            'title' => 'Ventas',
            'description' => 'Gestionar ventas del sistema',
            'icon' => 'fas fa-shopping-cart',
            'url' => '/RMIE/app/controllers/SaleController.php',
            'case_use' => 'CU6'
        ],
        'reportes' => [
            'title' => 'Reportes',
            'description' => 'Gestionar reportes del sistema',
            'icon' => 'fas fa-chart-bar',
            'url' => '/RMIE/app/controllers/ReportController.php',
            'case_use' => 'CU8'
        ],
        'alertas' => [
            'title' => 'Alertas',
            'description' => 'Gestionar alertas del sistema',
            'icon' => 'fas fa-exclamation-triangle',
            'url' => '/RMIE/app/controllers/AlertController.php',
            'case_use' => 'CU9'
        ],
        'rutas' => [
            'title' => 'Rutas',
            'description' => 'Gestionar rutas del sistema',
            'icon' => 'fas fa-route',
            'url' => '/RMIE/app/controllers/RouteController.php',
            'case_use' => 'CU7'
        ],
        'usuarios' => [
            'title' => 'Usuarios',
            'description' => 'Gestionar usuarios del sistema',
            'icon' => 'fas fa-users',
            'url' => '/RMIE/app/controllers/UserController.php',
            'case_use' => 'CU2'
        ],
        'profile' => [
            'title' => 'Mi Perfil',
            'description' => 'Modificar información personal',
            'icon' => 'fas fa-user-edit',
            'url' => '/RMIE/app/controllers/AuxiliarController.php?accion=modificar_perfil',
            'case_use' => 'Consultar/Modificar'
        ]
    ];
    
    /**
     * Verifica si un rol tiene permiso para una acción específica
     * @param string $role El rol del usuario
     * @param string $module El módulo a verificar
     * @param string $action La acción a verificar (create, read, update, delete)
     * @return bool true si tiene permiso, false si no
     */
    public static function hasPermission($role, $module, $action) {
        if (!isset(self::ROLE_PERMISSIONS[$role])) {
            return false;
        }
        
        if (!isset(self::ROLE_PERMISSIONS[$role][$module])) {
            return false;
        }
        
        return in_array($action, self::ROLE_PERMISSIONS[$role][$module]);
    }
    
    /**
     * Obtiene todos los módulos permitidos para un rol
     * @param string $role El rol del usuario
     * @return array Array de módulos permitidos
     */
    public static function getAllowedModules($role) {
        return self::ROLE_PERMISSIONS[$role] ?? [];
    }
    
    /**
     * Obtiene los módulos específicos del auxiliar según el diagrama
     * @return array Array de módulos del auxiliar
     */
    public static function getAuxiliarModules() {
        return self::AUXILIAR_MODULES;
    }
    
    /**
     * Verifica si el auxiliar puede acceder a un módulo específico
     * @param string $module El módulo a verificar
     * @return bool true si puede acceder, false si no
     */
    public static function auxiliarCanAccess($module) {
        return array_key_exists($module, self::AUXILIAR_MODULES);
    }
    
    /**
     * Obtiene información de un caso de uso específico
     * @param string $module El módulo
     * @return array|null Información del caso de uso o null si no existe
     */
    public static function getCaseUseInfo($module) {
        return self::AUXILIAR_MODULES[$module] ?? null;
    }
}