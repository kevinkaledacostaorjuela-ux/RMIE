# RMIE - Sistema de Gestión de Rutas y Entregas

Sistema completo de gestión empresarial con módulos de inventario, rutas, clientes, proveedores y reportes.

## 🚀 Instalación Rápida

### Opción 1: Con Composer (Recomendado)
```bash
# Clonar el proyecto
git clone [repository-url] rmie
cd rmie

# Instalar dependencias
composer install

# Ejecutar configuración automática
php install.php

# Iniciar servidor de desarrollo
php -S localhost:8000
```

### Opción 2: Sin Composer (Compatibilidad total)
```bash
# Solo descargar y configurar base de datos
# El sistema funciona completamente sin Composer
```

## 📋 Requisitos del Sistema

- **PHP**: >= 7.4
- **Extensiones PHP**: mysqli, json, session
- **Base de datos**: MySQL 5.7+ / MariaDB 10.2+
- **Servidor web**: Apache, Nginx o PHP built-in server
- **Composer**: Opcional pero recomendado

## 🔧 Configuración

1. **Base de datos**: Copiar `config/db.example.php` a `config/db.php` y configurar
2. **Permisos**: Asegurar que `logs/`, `temp/`, `uploads/` tengan permisos de escritura
3. **Servidor web**: Configurar document root a la raíz del proyecto

## 🏗️ Arquitectura

```
rmie/
├── app/
│   ├── controllers/     # Controladores MVC
│   ├── models/         # Modelos de datos
│   ├── views/          # Vistas PHP
│   └── utils/          # Utilidades y helpers
├── config/             # Configuración
├── public/             # Assets públicos (CSS, JS, imágenes)
├── vendor/             # Dependencias Composer (auto-generado)
├── logs/               # Archivos de log
├── temp/               # Archivos temporales
└── uploads/            # Archivos subidos
```

## 📦 Gestión de Dependencias

El sistema utiliza **autoloading híbrido**:

- **Con Composer**: PSR-4 autoloading automático
- **Sin Composer**: Autoloader manual con compatibilidad completa

## 🔨 Comandos Disponibles

```bash
# Instalar/actualizar dependencias
composer install

# Ejecutar tests (requiere PHPUnit)
composer test

# Verificar estándares de código
composer cs-check

# Corregir estándares de código automáticamente
composer cs-fix

# Configuración inicial
php install.php
```

## 🚦 Estados del Sistema

- ✅ **Producción**: Listo para usar
- 🔄 **Composer**: Integrado opcionalmente
- 📱 **Responsive**: Diseño móvil completo
- 🔐 **Seguridad**: Sistema de roles implementado

## 📖 Documentación

- **Login**: Sistema de autenticación con roles (admin, coordinador, auxiliar)
- **Dashboard**: Panel principal con estadísticas
- **Módulos**: Categorías, Productos, Clientes, Proveedores, Rutas, Reportes
- **Responsive**: Compatible con dispositivos móviles
- **API**: Endpoints para operaciones AJAX

## 🤝 Desarrollo

### Estructura de clases con Composer:
```php
<?php
// Con Composer
use RMIE\Models\Route;
use RMIE\Controllers\RouteController;

// Sin Composer (mantiene compatibilidad)
require_once 'app/models/Route.php';
$route = new Route();
```

### Agregar nuevas dependencias:
```bash
# Para producción
composer require vendor/package

# Para desarrollo
composer require --dev vendor/package-dev
```

## 📞 Soporte

- **Logs**: Revisar `logs/` para errores del sistema
- **Debug**: Activar `display_errors` en desarrollo
- **Performance**: Usar `composer dump-autoload --optimize` en producción
