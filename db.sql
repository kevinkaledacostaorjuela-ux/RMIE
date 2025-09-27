-- Base de datos RMIE
CREATE DATABASE IF NOT EXISTS rmie;
USE rmie;

-- Tabla de usuarios
CREATE TABLE usuarios (
    num_doc INT NOT NULL PRIMARY KEY,
    tipo_doc VARCHAR(45),
    nombres VARCHAR(45),
    apellidos VARCHAR(45),
    correo VARCHAR(45),
    contrasena VARCHAR(255),
    num_cel VARCHAR(45),
    rol ENUM('admin','coordinador') NOT NULL
);

-- Tabla de categorías
CREATE TABLE categorias (
    id_categoria INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45),
    descripcion VARCHAR(45)
);

-- Tabla de subcategorías

CREATE TABLE subcategorias (
    id_subcategoria INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45),
    descripcion VARCHAR(45),
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_categoria INT NOT NULL,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
);

-- Tabla de productos
CREATE TABLE productos (
    id_productos INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45),
    descripcion VARCHAR(45),
    fecha_entrada VARCHAR(45),
    fecha_fabricacion VARCHAR(45),
    fecha_caducidad VARCHAR(45),
    stock VARCHAR(45),
    precio_unitario VARCHAR(45),
    precio_por_mayor VARCHAR(45),
    valor_unitario VARCHAR(45),
    marca VARCHAR(45),
    id_subcategoria INT NOT NULL,
    id_categoria INT NOT NULL,
    id_proveedores INT NOT NULL,
    num_doc INT NOT NULL,
    FOREIGN KEY (id_subcategoria) REFERENCES subcategorias(id_subcategoria),
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria),
    FOREIGN KEY (id_proveedores) REFERENCES proveedores(id_proveedores),
    FOREIGN KEY (num_doc) REFERENCES usuarios(num_doc)
);

-- Tabla de proveedores
CREATE TABLE proveedores (
    id_proveedores INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre_distribuidor VARCHAR(45),
    correo VARCHAR(45),
    cel_proveedor VARCHAR(45),
    estado VARCHAR(45)
);

-- Tabla de locales
CREATE TABLE locales (
    id_locales INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    direccion VARCHAR(45),
    nombre_local VARCHAR(45),
    cel_local VARCHAR(45),
    estado VARCHAR(45),
    localidad VARCHAR(45),
    barrio VARCHAR(45)
);

-- Tabla de clientes
CREATE TABLE clientes (
    id_clientes INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45),
    descripcion VARCHAR(45),
    cel_cliente VARCHAR(45),
    correo VARCHAR(45),
    estado VARCHAR(45),
    id_locales INT NOT NULL,
    FOREIGN KEY (id_locales) REFERENCES locales(id_locales)
);

-- Tabla de rutas
CREATE TABLE rutas (
    id_ruta INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    direccion VARCHAR(45),
    nombre_local VARCHAR(45),
    nombre_cliente VARCHAR(45),
    id_clientes INT NOT NULL,
    id_reportes INT,
    id_ventas INT,
    FOREIGN KEY (id_clientes) REFERENCES clientes(id_clientes),
    FOREIGN KEY (id_reportes) REFERENCES reportes(id_reportes),
    FOREIGN KEY (id_ventas) REFERENCES ventas(id_ventas)
);

-- Tabla de ventas
CREATE TABLE ventas (
    id_ventas INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45),
    direccion VARCHAR(45),
    cantidad VARCHAR(45),
    fecha_venta VARCHAR(45),
    id_clientes INT NOT NULL,
    id_reportes INT,
    id_ruta INT,
    id_productos INT NOT NULL,
    FOREIGN KEY (id_clientes) REFERENCES clientes(id_clientes),
    FOREIGN KEY (id_ruta) REFERENCES rutas(id_ruta),
    FOREIGN KEY (id_productos) REFERENCES productos(id_productos)
);

-- Tabla de reportes
CREATE TABLE reportes (
    id_reportes INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45),
    descripcion VARCHAR(45),
    fecha VARCHAR(45),
    estado VARCHAR(45),
    id_ventas INT,
    id_productos INT NOT NULL,
    FOREIGN KEY (id_ventas) REFERENCES ventas(id_ventas),
    FOREIGN KEY (id_productos) REFERENCES productos(id_productos)
);

-- Tabla de alertas
CREATE TABLE alertas (
    id_alertas INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cliente_no_disponible VARCHAR(45),
    id_clientes INT NOT NULL,
    id_productos INT NOT NULL,
    cantidad_minima INT NULL,
    fecha_caducidad DATE NULL,
    FOREIGN KEY (id_clientes) REFERENCES clientes(id_clientes),
    FOREIGN KEY (id_productos) REFERENCES productos(id_productos)
);