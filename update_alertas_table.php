<?php
// Script de actualización de la tabla 'alertas'
// - Asegura el uso de id_productos
// - Agrega columnas nuevas: cantidad_minima (INT NULL), fecha_caducidad (DATE NULL)
// - Asegura índice y llave foránea a productos(id_productos)

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/db.php'; // Debe definir $conn (mysqli)

function esc(mysqli $conn, string $value): string { return $conn->real_escape_string($value); }

function columnExists(mysqli $conn, string $table, string $column): bool {
    $sql = "SELECT COUNT(*) AS c FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '" . esc($conn, $table) . "' AND COLUMN_NAME = '" . esc($conn, $column) . "'";
    $res = $conn->query($sql);
    return $res && ($row = $res->fetch_assoc()) && intval($row['c']) > 0;
}

function indexExists(mysqli $conn, string $table, string $index): bool {
    $sql = "SELECT COUNT(*) AS c FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '" . esc($conn, $table) . "' AND INDEX_NAME = '" . esc($conn, $index) . "'";
    $res = $conn->query($sql);
    return $res && ($row = $res->fetch_assoc()) && intval($row['c']) > 0;
}

function foreignKeyExists(mysqli $conn, string $table, string $column, string $refTable, string $refColumn): bool {
    $sql = "SELECT COUNT(*) AS c FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '" . esc($conn, $table) . "' AND COLUMN_NAME = '" . esc($conn, $column) . "' AND REFERENCED_TABLE_NAME = '" . esc($conn, $refTable) . "' AND REFERENCED_COLUMN_NAME = '" . esc($conn, $refColumn) . "'";
    $res = $conn->query($sql);
    return $res && ($row = $res->fetch_assoc()) && intval($row['c']) > 0;
}

function getForeignKeyNames(mysqli $conn, string $table, array $columns): array {
    $inCols = "'" . implode("','", array_map(fn($c)=>esc($conn,$c), $columns)) . "'";
    $sql = "SELECT DISTINCT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '" . esc($conn, $table) . "' AND COLUMN_NAME IN ($inCols) AND REFERENCED_TABLE_NAME IS NOT NULL";
    $res = $conn->query($sql);
    $names = [];
    if ($res) {
        while ($row = $res->fetch_assoc()) { $names[] = $row['CONSTRAINT_NAME']; }
    }
    return $names;
}

function dropForeignKeys(mysqli $conn, string $table, array $fkNames): void {
    foreach ($fkNames as $fk) {
        $sql = "ALTER TABLE `$table` DROP FOREIGN KEY `$fk`";
        $conn->query($sql);
        echo "- FK eliminada: $fk\n";
    }
}

function ensureAlertasSchema(mysqli $conn): void {
    echo "Actualizando esquema de 'alertas'...\n";

    // 1) Estándar de columna id_productos (renombrar si existe id_producto)
    $hasIdProductos = columnExists($conn, 'alertas', 'id_productos');
    $hasIdProducto  = columnExists($conn, 'alertas', 'id_producto');

    if (!$hasIdProductos && $hasIdProducto) {
        echo "Detectado 'id_producto'. Procediendo a renombrar a 'id_productos'...\n";
        // Quitar FK que refiera a la columna antes de renombrar
        $fks = getForeignKeyNames($conn, 'alertas', ['id_producto']);
        if (!empty($fks)) {
            dropForeignKeys($conn, 'alertas', $fks);
        }
        $sql = "ALTER TABLE alertas CHANGE COLUMN id_producto id_productos INT NOT NULL";
        if (!$conn->query($sql)) {
            throw new Exception("Error al renombrar columna: " . $conn->error);
        }
        echo "Columna renombrada a 'id_productos'.\n";
        $hasIdProductos = true;
    }

    // 2) Columnas nuevas: cantidad_minima y fecha_caducidad
    if (!columnExists($conn, 'alertas', 'cantidad_minima')) {
        $sql = "ALTER TABLE alertas ADD COLUMN cantidad_minima INT NULL AFTER id_productos";
        if (!$conn->query($sql)) { throw new Exception("Error agregando cantidad_minima: " . $conn->error); }
        echo "Columna agregada: cantidad_minima INT NULL.\n";
    } else {
        echo "Columna ya existe: cantidad_minima.\n";
    }

    if (!columnExists($conn, 'alertas', 'fecha_caducidad')) {
        $sql = "ALTER TABLE alertas ADD COLUMN fecha_caducidad DATE NULL AFTER cantidad_minima";
        if (!$conn->query($sql)) { throw new Exception("Error agregando fecha_caducidad: " . $conn->error); }
        echo "Columna agregada: fecha_caducidad DATE NULL.\n";
    } else {
        echo "Columna ya existe: fecha_caducidad.\n";
    }

    // 3) Índice sobre id_productos
    if ($hasIdProductos && !indexExists($conn, 'alertas', 'idx_alertas_id_productos')) {
        $sql = "ALTER TABLE alertas ADD INDEX idx_alertas_id_productos (id_productos)";
        if (!$conn->query($sql)) { throw new Exception("Error agregando índice: " . $conn->error); }
        echo "Índice agregado: idx_alertas_id_productos.\n";
    } else {
        echo "Índice ya existe o columna no disponible: idx_alertas_id_productos.\n";
    }

    // 4) FK a productos(id_productos)
    if ($hasIdProductos && !foreignKeyExists($conn, 'alertas', 'id_productos', 'productos', 'id_productos')) {
        // Quitar cualquier FK existente sobre id_productos hacia otra tabla o inválida
        $fks = getForeignKeyNames($conn, 'alertas', ['id_productos']);
        if (!empty($fks)) {
            dropForeignKeys($conn, 'alertas', $fks);
        }
        $fkName = 'fk_alertas_productos';
        // Evitar colisión de nombre
        $i = 1;
        while (true) {
            $sql = "SELECT COUNT(*) c FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'alertas' AND CONSTRAINT_NAME = '" . esc($conn, $fkName) . "'";
            $res = $conn->query($sql);
            $exists = $res && ($row = $res->fetch_assoc()) && intval($row['c']) > 0;
            if (!$exists) break;
            $fkName = 'fk_alertas_productos_' . $i++;
        }
        $sql = "ALTER TABLE alertas ADD CONSTRAINT `$fkName` FOREIGN KEY (id_productos) REFERENCES productos(id_productos) ON DELETE CASCADE ON UPDATE CASCADE";
        if (!$conn->query($sql)) { throw new Exception("Error agregando FK: " . $conn->error); }
        echo "Llave foránea agregada: $fkName (alertas.id_productos -> productos.id_productos).\n";
    } else {
        echo "Llave foránea ya existe o columna no disponible.\n";
    }

    echo "Actualización de 'alertas' completada.\n";
}

try {
    ensureAlertasSchema($conn);
} catch (Throwable $e) {
    http_response_code(500);
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

?>
