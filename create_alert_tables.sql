-- Tabla de papelera de alertas (historial de alertas eliminadas)
CREATE TABLE IF NOT EXISTS alertas_papelera (
    id_papelera INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_alertas INT,
    id_productos INT NOT NULL,
    producto_nombre VARCHAR(255),
    tipo_alerta VARCHAR(45),
    cantidad_minima INT NULL,
    fecha_caducidad DATE NULL,
    id_proveedores INT,
    proveedor_nombre VARCHAR(255),
    estado VARCHAR(45),
    prioridad VARCHAR(45),
    motivo_eliminacion VARCHAR(100),
    fecha_eliminacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_productos) REFERENCES productos(id_productos)
);

-- Tabla de notificaciones de alertas
CREATE TABLE IF NOT EXISTS notificaciones_alertas (
    id_notificacion INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_alertas INT,
    tipo_notificacion VARCHAR(50),
    mensaje TEXT,
    leida BOOLEAN DEFAULT FALSE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_lectura DATETIME NULL,
    FOREIGN KEY (id_alertas) REFERENCES alertas(id_alertas)
);
