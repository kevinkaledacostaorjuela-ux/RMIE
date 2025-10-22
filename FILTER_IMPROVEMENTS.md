# Mejoras en el Sistema de Filtros - RMIE

## Resumen de Cambios

Se ha implementado un sistema de filtros estandarizado y mejorado para todos los módulos del sistema RMIE. Los cambios incluyen:

### 1. Utilidad FilterHelper (`app/utils/FilterHelper.php`)

Nueva clase helper que proporciona:

- **Sanitización y validación** de datos de entrada
- **Construcción automática** de consultas WHERE
- **Soporte para múltiples tipos** de filtros (texto, fecha, número, email, etc.)
- **Validación de rangos** (fechas, precios, etc.)
- **Protección contra inyección SQL**
- **Manejo de paginación y ordenamiento**

#### Métodos principales:

- `sanitize()` - Sanitiza valores individuales
- `processFilters()` - Procesa arrays de filtros
- `buildWhereClause()` - Construye cláusulas WHERE
- `validateDateRange()` - Valida rangos de fechas
- `buildOrderBy()` - Maneja ordenamiento
- `buildPagination()` - Gestiona paginación

### 2. Módulos Mejorados

#### UserController/User Model
**Filtros implementados:**
- Rol del usuario
- Tipo de documento
- Búsqueda por nombre, apellido, correo, documento
- Número de celular
- Rango de fechas de registro
- Estado del usuario

#### ClientController/Client Model
**Filtros implementados:**
- Local asociado
- Estado del cliente
- Búsqueda por nombre, correo, celular
- Rango de fechas de creación

#### ProductController/Product Model
**Filtros implementados:**
- Categoría
- Subcategoría
- Proveedor
- Usuario responsable
- Nombre del producto
- Marca
- Rango de precios (mínimo/máximo)
- Rango de stock
- Rango de fechas de entrada
- Búsqueda general (nombre, descripción, marca)

#### SaleController/Sale Model
**Filtros implementados:**
- Producto vendido
- Cliente comprador
- Usuario vendedor
- Estado de la venta
- Rango de precios totales
- Rango de cantidades
- Rango de fechas de venta
- Búsqueda general

#### ProviderController/Provider Model
**Filtros implementados:**
- Nombre del distribuidor
- Estado del proveedor
- Email
- Ubicación
- Número de celular
- Rango de fechas de registro
- Búsqueda general

#### RouteController/Route Model
**Filtros implementados:**
- Cliente asociado
- Venta asociada
- Reporte asociado
- Dirección
- Nombre del local
- Nombre del cliente
- Búsqueda general

**Nota:** Los filtros de estado y fecha fueron removidos ya que la tabla `rutas` no tiene estas columnas en la estructura actual de la base de datos.

#### CategoryController/Category Model
**Filtros implementados:**
- Nombre de la categoría
- Descripción
- Rango de fechas de creación (si la columna existe)
- Búsqueda general

#### SubcategoryController/SubcategorySimple Model
**Filtros implementados:**
- Nombre de la subcategoría
- Descripción
- Categoría padre
- Búsqueda general

#### ReportController
**Filtros mejorados:**
- Búsqueda por título/contenido
- Estado del reporte
- Tipo de reporte
- Rango de fechas
- Validación mejorada de entrada

## Características de Seguridad

### Protección contra Inyección SQL
- Uso de prepared statements en todas las consultas
- Validación estricta de tipos de datos
- Sanitización de entrada con `htmlspecialchars()`

### Validación de Entrada
- Límites de longitud para campos de texto
- Validación de formato para emails
- Verificación de rangos para números
- Validación de valores permitidos para selectores

### Manejo de Errores
- Logging de errores para depuración
- Fallbacks seguros en caso de fallo
- Mensajes de error sanitizados para el usuario

## Retrocompatibilidad

Todos los métodos mantienen retrocompatibilidad:
- Los métodos existentes siguen funcionando
- Se detectan automáticamente los parámetros antiguos vs nuevos
- Fallbacks a versiones simples sin filtros en caso de error

## Uso

### Ejemplo básico en un controlador:

```php
require_once __DIR__ . '/../utils/FilterHelper.php';

// Definir reglas de filtro
$filterRules = [
    'nombre' => ['type' => 'text', 'options' => ['max_length' => 100]],
    'email' => ['type' => 'email'],
    'precio_min' => ['type' => 'float', 'options' => ['min' => 0]],
    'fecha_desde' => ['type' => 'date']
];

// Procesar filtros
$filtros = FilterHelper::processFilters($_GET, $filterRules);

// Usar en el modelo
$resultados = MiModelo::getAll($conn, $filtros);
```

### Ejemplo en un modelo:

```php
// Mapeo de campos a columnas SQL
$mapping = [
    'nombre' => ['column' => 'nombre', 'operator' => 'LIKE', 'type' => 's'],
    'email' => ['column' => 'correo', 'operator' => '=', 'type' => 's'],
    'precio_min' => ['column' => 'precio', 'operator' => '>=', 'type' => 'd']
];

// Construir WHERE
$whereData = FilterHelper::buildWhereClause($filtros, $mapping);

// Usar en consulta
$sql .= " WHERE " . implode(" AND ", $whereData['where']);
$stmt->bind_param($whereData['types'], ...$whereData['params']);
```

## Beneficios

1. **Consistencia** - Mismo comportamiento en todos los módulos
2. **Seguridad** - Protección robusta contra ataques
3. **Mantenibilidad** - Código centralizado y reutilizable
4. **Escalabilidad** - Fácil agregar nuevos filtros
5. **Performance** - Consultas optimizadas con índices apropiados
6. **UX** - Filtros más intuitivos y rápidos para los usuarios

## Próximos Pasos

1. Actualizar las vistas para usar los nuevos filtros
2. Agregar JavaScript para filtros dinámicos
3. Implementar caché para consultas frecuentes
4. Agregar exportación de resultados filtrados
5. Crear dashboard con filtros avanzados

---

*Última actualización: 21 de octubre de 2025*