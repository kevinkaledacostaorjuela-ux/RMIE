-- Script para crear la tabla ventas_productos
CREATE TABLE IF NOT EXISTS ventas_productos (
    id_ventas INT NOT NULL,
    id_productos INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id_ventas, id_productos),
    FOREIGN KEY (id_ventas) REFERENCES ventas(id_ventas) ON DELETE CASCADE,
    FOREIGN KEY (id_productos) REFERENCES productos(id_productos) ON DELETE CASCADE
);
