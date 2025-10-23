# 🔐 Implementación de Permisos para Rol Auxiliar

## 📋 Resumen
Implementación del sistema de permisos basado en el diagrama de casos de uso proporcionado, que restringe el acceso del rol auxiliar únicamente a los módulos autorizados.

## 🎯 Casos de Uso Autorizados para Auxiliar

Según el diagrama proporcionado, el rol auxiliar tiene acceso a:

### 1. **CU2 - Gestionar usuarios**
- **Permiso:** Solo lectura (`read`)
- **Descripción:** Puede consultar información de usuarios del sistema
- **URL:** `/RMIE/app/controllers/AuxiliarController.php?accion=consultar_usuarios`

### 2. **CU6 - Gestión de ventas** 
- **Permiso:** Solo lectura (`read`)
- **Relación:** Include desde el actor auxiliar
- **Descripción:** Puede consultar historial de ventas realizadas
- **URL:** `/RMIE/app/controllers/AuxiliarController.php?accion=consultar_ventas`

### 3. **CU8 - Gestión de reportes**
- **Permiso:** Solo lectura (`read`)
- **Relación:** Include desde el actor auxiliar
- **Descripción:** Puede visualizar reportes generados del sistema
- **URL:** `/RMIE/app/controllers/AuxiliarController.php?accion=consultar_reportes`

### 4. **Consultar/Modificar (Perfil)**
- **Permiso:** Lectura y modificación (`read`, `update`)
- **Descripción:** Puede consultar y modificar únicamente su información personal
- **URL:** `/RMIE/app/controllers/AuxiliarController.php?accion=modificar_perfil`

## 🚫 Módulos NO Autorizados

El auxiliar **NO** tiene acceso a:
- Gestión de clientes
- Gestión de productos
- Gestión de proveedores
- Gestión de categorías/subcategorías
- Gestión de rutas
- Gestión de locales
- Gestión de alertas
- Funciones administrativas

## 📁 Archivos Modificados/Creados

### 1. **`/app/utils/PermissionsConfig.php`** ✨ NUEVO
- Configuración centralizada de permisos por rol
- Definición de módulos específicos del auxiliar según diagrama
- Métodos de validación de permisos

### 2. **`/app/utils/AuthorizationMiddleware.php`** ✨ NUEVO
- Middleware para verificar permisos antes de ejecutar acciones
- Bloqueo automático de accesos no autorizados
- Mensajes específicos de error según el caso de uso

### 3. **`/app/utils/AuthUtils.php`** 📝 MODIFICADO
- Agregadas funciones: `isAuxiliar()`, `isAdmin()`, `isCoordinador()`
- Mejor organización de validaciones de rol

### 4. **`/app/controllers/AuxiliarController.php`** 📝 MODIFICADO
- Integración con sistema de permisos
- Validación automática de módulos según diagrama
- Redirección con error para módulos no autorizados

### 5. **`/app/views/auxiliar/dashboard.php`** 📝 MODIFICADO
- Dashboard actualizado con módulos específicos del diagrama
- Badges de casos de uso (CU2, CU6, CU8, etc.)
- Información detallada de permisos

## 🔧 Implementación Técnica

### Configuración de Permisos
```php
const ROLE_PERMISSIONS = [
    'auxiliar' => [
        'usuarios' => ['read'],           // CU2
        'ventas' => ['read'],            // CU6  
        'reportes' => ['read'],          // CU8
        'profile' => ['read', 'update']  // Consultar/Modificar
    ]
];
```

### Validación en Controladores
```php
// Verificar acceso al módulo
$this->validateModuleAccess('usuarios');

// Usar middleware de autorización
AuthorizationMiddleware::authorize('ventas', 'read', $redirectUrl);
```

### Dashboard Dinámico
```php
// Cargar solo módulos autorizados
$modulos_auxiliar = PermissionsConfig::getAuxiliarModules();
foreach ($modulos_auxiliar as $module) {
    // Renderizar módulo con badge de caso de uso
}
```

## 🛡️ Seguridad Implementada

### 1. **Validación de Sesión**
- Verificación automática de usuario logueado
- Redirección a login si no hay sesión válida

### 2. **Validación de Rol**
- Solo usuarios con rol 'auxiliar' pueden acceder al dashboard auxiliar
- Verificación en cada acción del controlador

### 3. **Validación de Módulos**
- Bloqueo automático de módulos no autorizados
- Mensajes específicos según el caso de uso intentado

### 4. **Validación de Acciones**
- Control granular por tipo de acción (create, read, update, delete)
- Auxiliar limitado solo a 'read' excepto en su perfil

## 🔄 Flujo de Autorización

1. **Login:** Usuario se autentica y se establece rol en sesión
2. **Dashboard:** Sistema carga solo módulos autorizados según rol
3. **Navegación:** Cada clic valida permisos antes de mostrar contenido
4. **Acciones:** Middleware verifica permisos antes de ejecutar operaciones
5. **Error:** Acceso denegado redirige con mensaje específico

## ✅ Casos de Prueba

### Acceso Autorizado ✅
- Auxiliar accede a CU2 (Gestionar usuarios) → ✅ Permitido
- Auxiliar accede a CU6 (Gestión de ventas) → ✅ Permitido  
- Auxiliar accede a CU8 (Gestión de reportes) → ✅ Permitido
- Auxiliar modifica su perfil → ✅ Permitido

### Acceso Denegado ❌
- Auxiliar intenta acceder a gestión de productos → ❌ Bloqueado
- Auxiliar intenta acceder a gestión de clientes → ❌ Bloqueado
- Auxiliar intenta eliminar cualquier registro → ❌ Bloqueado
- Auxiliar intenta crear usuarios → ❌ Bloqueado

## 🚀 URLs de Acceso para Auxiliar

```
Dashboard:        /RMIE/app/controllers/AuxiliarController.php?accion=dashboard
Usuarios (CU2):   /RMIE/app/controllers/AuxiliarController.php?accion=consultar_usuarios  
Ventas (CU6):     /RMIE/app/controllers/AuxiliarController.php?accion=consultar_ventas
Reportes (CU8):   /RMIE/app/controllers/AuxiliarController.php?accion=consultar_reportes
Perfil:           /RMIE/app/controllers/AuxiliarController.php?accion=modificar_perfil
```

## 📊 Estado de Implementación

- ✅ **Configuración de permisos** - Implementada según diagrama
- ✅ **Dashboard auxiliar** - Actualizado con casos de uso específicos  
- ✅ **Validación de accesos** - Middleware de autorización funcionando
- ✅ **Mensajes de error** - Específicos por caso de uso
- ✅ **Documentación** - Completa y actualizada

---

**Nota:** Esta implementación garantiza que el rol auxiliar tenga acceso únicamente a los procesos y módulos especificados en el diagrama de casos de uso proporcionado, cumpliendo con los principios de menor privilegio y seguridad por diseño.