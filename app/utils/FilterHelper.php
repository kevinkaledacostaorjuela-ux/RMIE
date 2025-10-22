<?php

/**
 * Utilidad para manejo estandarizado de filtros en todos los módulos
 */
class FilterHelper {
    
    /**
     * Sanitiza y valida un valor de filtro
     * @param mixed $value Valor a sanitizar
     * @param string $type Tipo de dato esperado (text, email, date, number, select)
     * @param array $options Opciones adicionales (min, max, allowed_values)
     * @return mixed Valor sanitizado o null si es inválido
     */
    public static function sanitize($value, $type = 'text', $options = []) {
        if (empty($value) && $value !== '0') {
            return null;
        }
        
        switch ($type) {
            case 'text':
                $value = trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
                if (isset($options['max_length']) && strlen($value) > $options['max_length']) {
                    $value = substr($value, 0, $options['max_length']);
                }
                return $value;
                
            case 'email':
                $value = filter_var(trim($value), FILTER_VALIDATE_EMAIL);
                return $value ?: null;
                
            case 'date':
                $date = DateTime::createFromFormat('Y-m-d', $value);
                if ($date && $date->format('Y-m-d') === $value) {
                    return $value;
                }
                return null;
                
            case 'datetime':
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $value);
                if ($date && $date->format('Y-m-d H:i:s') === $value) {
                    return $value;
                }
                // Intentar con formato solo fecha
                $date = DateTime::createFromFormat('Y-m-d', $value);
                if ($date && $date->format('Y-m-d') === $value) {
                    return $value . ' 00:00:00';
                }
                return null;
                
            case 'number':
            case 'int':
                $value = filter_var($value, FILTER_VALIDATE_INT);
                if ($value === false) return null;
                
                if (isset($options['min']) && $value < $options['min']) {
                    return null;
                }
                if (isset($options['max']) && $value > $options['max']) {
                    return null;
                }
                return $value;
                
            case 'float':
            case 'decimal':
                $value = filter_var($value, FILTER_VALIDATE_FLOAT);
                if ($value === false) return null;
                
                if (isset($options['min']) && $value < $options['min']) {
                    return null;
                }
                if (isset($options['max']) && $value > $options['max']) {
                    return null;
                }
                return $value;
                
            case 'select':
                if (isset($options['allowed_values']) && 
                    is_array($options['allowed_values']) && 
                    in_array($value, $options['allowed_values'])) {
                    return $value;
                }
                return null;
                
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                
            default:
                return trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
        }
    }
    
    /**
     * Procesa un array de filtros
     * @param array $input Array de entrada ($_GET, $_POST)
     * @param array $rules Reglas de validación
     * @return array Array de filtros sanitizados
     */
    public static function processFilters($input, $rules) {
        $filters = [];
        
        foreach ($rules as $field => $rule) {
            $value = $input[$field] ?? null;
            $type = $rule['type'] ?? 'text';
            $options = $rule['options'] ?? [];
            
            $sanitized = self::sanitize($value, $type, $options);
            
            if ($sanitized !== null) {
                $filters[$field] = $sanitized;
            }
        }
        
        return $filters;
    }
    
    /**
     * Construye la cláusula WHERE para filtros
     * @param array $filters Filtros procesados
     * @param array $mapping Mapeo de campos a columnas SQL
     * @return array [sql_where, params, types]
     */
    public static function buildWhereClause($filters, $mapping) {
        $where = [];
        $params = [];
        $types = '';
        
        foreach ($filters as $field => $value) {
            if (!isset($mapping[$field])) continue;
            
            $config = $mapping[$field];
            $operator = $config['operator'] ?? '=';
            $param_type = $config['type'] ?? 's';
            
            // Para MULTIPLE_LIKE no hay column, usar columns
            if ($operator === 'MULTIPLE_LIKE') {
                if (isset($config['columns']) && is_array($config['columns'])) {
                    $likes = [];
                    foreach ($config['columns'] as $col) {
                        $likes[] = "$col LIKE ?";
                        $params[] = '%' . $value . '%';
                        $types .= 's';
                    }
                    $where[] = '(' . implode(' OR ', $likes) . ')';
                }
                continue;
            }
            
            $column = $config['column'];
            
            switch ($operator) {
                case 'LIKE':
                    $where[] = "$column LIKE ?";
                    $params[] = '%' . $value . '%';
                    $types .= 's';
                    break;
                    
                case 'LIKE_START':
                    $where[] = "$column LIKE ?";
                    $params[] = $value . '%';
                    $types .= 's';
                    break;
                    
                case 'LIKE_END':
                    $where[] = "$column LIKE ?";
                    $params[] = '%' . $value;
                    $types .= 's';
                    break;
                    
                case '>=':
                case '<=':
                case '>':
                case '<':
                case '=':
                case '!=':
                    $where[] = "$column $operator ?";
                    $params[] = $value;
                    $types .= $param_type;
                    break;
                    
                case 'IN':
                    if (is_array($value)) {
                        $placeholders = str_repeat('?,', count($value) - 1) . '?';
                        $where[] = "$column IN ($placeholders)";
                        $params = array_merge($params, $value);
                        $types .= str_repeat($param_type, count($value));
                    }
                    break;
                    
                case 'BETWEEN':
                    if (is_array($value) && count($value) === 2) {
                        $where[] = "$column BETWEEN ? AND ?";
                        $params[] = $value[0];
                        $params[] = $value[1];
                        $types .= $param_type . $param_type;
                    }
                    break;
            }
        }
        
        return [
            'where' => $where,
            'params' => $params,
            'types' => $types
        ];
    }
    
    /**
     * Aplica ordenamiento
     * @param string $sort_by Campo de ordenamiento
     * @param string $sort_order Dirección del ordenamiento
     * @param array $allowed_sorts Campos permitidos para ordenar
     * @return string Cláusula ORDER BY
     */
    public static function buildOrderBy($sort_by, $sort_order = 'ASC', $allowed_sorts = []) {
        if (empty($sort_by) || !in_array($sort_by, $allowed_sorts)) {
            return '';
        }
        
        $sort_order = strtoupper($sort_order);
        if (!in_array($sort_order, ['ASC', 'DESC'])) {
            $sort_order = 'ASC';
        }
        
        return " ORDER BY $sort_by $sort_order";
    }
    
    /**
     * Aplica paginación
     * @param int $page Página actual
     * @param int $per_page Elementos por página
     * @return array [limit_clause, offset]
     */
    public static function buildPagination($page = 1, $per_page = 20) {
        $page = max(1, intval($page));
        $per_page = min(100, max(1, intval($per_page))); // Límite máximo de 100
        
        $offset = ($page - 1) * $per_page;
        
        return [
            'limit' => " LIMIT $per_page OFFSET $offset",
            'offset' => $offset,
            'per_page' => $per_page,
            'page' => $page
        ];
    }
    
    /**
     * Genera URL con parámetros de filtro mantenidos
     * @param array $current_params Parámetros actuales
     * @param array $new_params Nuevos parámetros a agregar/sobreescribir
     * @param string $base_url URL base
     * @return string URL completa
     */
    public static function buildFilterUrl($current_params, $new_params = [], $base_url = '') {
        $params = array_merge($current_params, $new_params);
        
        // Remover parámetros vacíos
        $params = array_filter($params, function($value) {
            return $value !== '' && $value !== null;
        });
        
        if (empty($params)) {
            return $base_url;
        }
        
        return $base_url . '?' . http_build_query($params);
    }
    
    /**
     * Valida rango de fechas
     * @param string $fecha_inicio
     * @param string $fecha_fin
     * @return array Fechas validadas o array vacío si inválidas
     */
    public static function validateDateRange($fecha_inicio, $fecha_fin) {
        $inicio = self::sanitize($fecha_inicio, 'date');
        $fin = self::sanitize($fecha_fin, 'date');
        
        if (!$inicio && !$fin) {
            return [];
        }
        
        if ($inicio && $fin && $inicio > $fin) {
            // Intercambiar si están al revés
            return ['fecha_inicio' => $fin, 'fecha_fin' => $inicio];
        }
        
        $result = [];
        if ($inicio) $result['fecha_inicio'] = $inicio;
        if ($fin) $result['fecha_fin'] = $fin;
        
        return $result;
    }
}