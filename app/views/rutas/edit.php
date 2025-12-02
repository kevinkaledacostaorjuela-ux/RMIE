<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ruta - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <!-- Bootstrap 5.3.0 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
            --text-primary: #2d3748;
            --text-secondary: #4a5568;
            --shadow-light: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            --shadow-medium: 0 15px 35px 0 rgba(31, 38, 135, 0.2);
            --border-radius: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .glass-container {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: var(--border-radius);
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow-medium);
            transition: var(--transition);
            max-width: 1200px;
            margin: 0 auto;
        }

        .glass-container:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
        }

        /* Breadcrumb moderno */
        .modern-breadcrumb {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
        }

        .breadcrumb {
            margin: 0;
            background: none;
        }

        .breadcrumb-item a {
            color: white;
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: #f0f0f0;
            transform: translateX(2px);
        }

        .breadcrumb-item.active {
            color: rgba(255, 255, 255, 0.8);
        }

        .form-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2.5rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .form-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        .form-header h1 {
            position: relative;
            z-index: 1;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            position: relative;
            z-index: 1;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .form-content {
            padding: 2rem;
        }

        .form-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            transition: var(--transition);
        }

        .form-section:hover {
            transform: translateY(-2px);
        }
        
        .cliente-item {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            position: relative;
        }
        
        .cliente-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            font-weight: 600;
            color: white;
        }
        
        .cliente-nombre {
            font-size: 1.1rem;
        }
        
        .btn-remove-cliente {
            background: rgba(220, 53, 69, 0.8);
            border: none;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-remove-cliente:hover {
            background: rgba(220, 53, 69, 1);
        }
        
        .locales-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 0.75rem;
        }
        
        .local-checkbox {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .local-checkbox:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .local-checkbox input[type="checkbox"] {
            margin-right: 0.75rem;
            transform: scale(1.2);
        }
        
        .local-info {
            flex: 1;
        }
        
        .local-nombre {
            font-weight: 600;
            color: white;
            margin-bottom: 0.25rem;
        }
        
        .local-direccion {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        #filtro_cliente {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            transition: all 0.3s ease;
        }
        
        #filtro_cliente:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        #filtro_cliente::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .row .col-md-6 {
            margin-bottom: 1rem;
        }
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(102, 126, 234, 0.3);
        }

        .section-title i {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.4rem;
        }

        .rutas-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: start;
        }

        .form-group {
            margin-bottom: 1.8rem;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .form-group label i {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.1rem;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 1rem;
            transition: var(--transition);
            color: var(--text-primary);
            font-weight: 500;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.95);
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
            outline: none;
        }

        .form-control::placeholder {
            color: rgba(77, 85, 108, 0.6);
        }

        .form-control.is-valid {
            border-color: #28a745;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .form-text {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .char-counter {
            text-align: right;
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }

        /* Resumen de información actual */
        .rutas-summary {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            height: fit-content;
        }

        .rutas-summary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--success-gradient);
            border-radius: 2px;
        }

        .summary-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .summary-header h5 {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: var(--transition);
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-item:hover {
            transform: translateX(5px);
            padding-left: 10px;
            background: rgba(255, 255, 255, 0.05);
        }

        .summary-label {
            color: var(--text-secondary);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .summary-value {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.1rem;
        }

        /* Vista previa de cambios */
        .changes-preview {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .change-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .old-value {
            color: #dc3545;
            font-size: 0.9rem;
        }

        .new-value {
            color: #28a745;
            font-size: 0.9rem;
        }

        /* Botones mejorados */
        .btn {
            border-radius: 12px;
            padding: 14px 35px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: none;
            position: relative;
            overflow: hidden;
            transition: var(--transition);
            cursor: pointer;
            min-width: 180px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: var(--transition);
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .btn:active {
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--success-gradient);
            color: white;
            box-shadow: 0 6px 20px rgba(79, 172, 254, 0.4);
        }

        .btn-success:hover {
            box-shadow: 0 12px 30px rgba(79, 172, 254, 0.6);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        }

        .btn-secondary:hover {
            box-shadow: 0 12px 30px rgba(108, 117, 125, 0.6);
        }

        .btn-warning {
            background: var(--warning-gradient);
            color: white;
            box-shadow: 0 6px 20px rgba(67, 233, 123, 0.4);
        }

        .btn-warning:hover {
            box-shadow: 0 12px 30px rgba(67, 233, 123, 0.6);
        }

        .rutas-buttons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Select2 con diseño limpio y ordenado */
        .select2-container--bootstrap-5 .select2-dropdown {
            border: 1px solid #dee2e6 !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden !important;
        }
        
        /* Campo de búsqueda limpio en la parte superior */
        .select2-search--dropdown {
            display: block !important;
            padding: 16px !important;
            background: #ffffff !important;
            border-bottom: 1px solid #e9ecef !important;
            position: relative !important;
        }
        
        /* Ocultar elementos duplicados o no deseados */
        .search-label,
        .select2-search-helper,
        .select2-search__field + .search-label {
            display: none !important;
        }
        
        .select2-search__field {
            width: 100% !important;
            padding: 12px 16px !important;
            border: 1px solid #ced4da !important;
            border-radius: 6px !important;
            font-size: 14px !important;
            background-color: #f8f9fa !important;
            color: #495057 !important;
            outline: none !important;
        }
        
        .select2-search__field:focus {
            border-color: #86b7fe !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
            background-color: #ffffff !important;
        }
        
        .select2-search__field::placeholder {
            color: #6c757d !important;
            font-weight: 500 !important;
            font-size: 14px !important;
        }
        
        .select2-search__field::-webkit-input-placeholder {
            color: #6c757d !important;
            font-weight: 500 !important;
        }
        
        .select2-search__field::-moz-placeholder {
            color: #6c757d !important;
            font-weight: 500 !important;
        }
        
        /* Lista de resultados ordenada */
        .select2-results {
            max-height: 300px !important;
            overflow-y: auto !important;
        }
        
        .select2-results__option {
            padding: 12px 16px !important;
            border-bottom: 1px solid #f1f3f4 !important;
            font-size: 14px !important;
            color: #495057 !important;
        }
        
        .select2-results__option:last-child {
            border-bottom: none !important;
        }
        
        .select2-results__option--highlighted {
            background-color: #f8f9fa !important;
            color: #0d6efd !important;
        }
        
        .select2-results__option--selected {
            background-color: #e7f3ff !important;
            color: #0d6efd !important;
            font-weight: 500 !important;
        }
        
        /* Ocultar elementos no deseados que pueden aparecer */
        .select2-dropdown .select2-search__field:focus + *,
        .select2-dropdown .search-tooltip,
        .select2-dropdown .search-overlay {
            display: none !important;
        }

        /* Alerta informativa */
        .info-alert {
            background: rgba(23, 162, 184, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(23, 162, 184, 0.3);
            border-radius: 12px;
            border-left: 4px solid #17a2b8;
            color: var(--text-primary);
            padding: 1.5rem;
            margin-top: 1.5rem;
            transition: var(--transition);
        }

        .info-alert:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(23, 162, 184, 0.2);
        }

        .info-alert i {
            color: #17a2b8;
            margin-right: 0.7rem;
            font-size: 1.1rem;
        }

        .info-alert strong {
            color: var(--text-primary);
        }

        .info-alert ul {
            margin-left: 1.5rem;
            margin-top: 1rem;
            margin-bottom: 0;
        }

        .info-alert li {
            margin-bottom: 0.5rem;
        }

        /* Alertas de Bootstrap personalizadas */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .alert-info {
            background: rgba(23, 162, 184, 0.15);
            border-left: 4px solid #17a2b8;
            color: var(--text-primary);
        }

        .alert-warning {
            background: rgba(255, 193, 7, 0.15);
            border-left: 4px solid #ffc107;
            color: var(--text-primary);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .rutas-grid {
                grid-template-columns: 1fr;
            }

            .rutas-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                min-width: unset;
            }

            .form-header h1 {
                font-size: 1.8rem;
            }
        }

        /* Select2 Personalizados */
        .select2-container--bootstrap-5 .select2-selection {
            background: white !important;
            border: 2px solid #e0e0e0 !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
            min-height: 44px !important;
            transition: all 0.3s ease !important;
        }

        .select2-container--bootstrap-5 .select2-selection:focus-within {
            border-color: #667eea !important;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
        }

        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: #667eea !important;
        }

        .select2-container--bootstrap-5 .select2-selection__rendered {
            padding: 0 !important;
            color: var(--text-primary) !important;
            font-weight: 500 !important;
        }

        .select2-container--bootstrap-5 .select2-selection__choice {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
            border-radius: 6px !important;
            padding: 4px 10px !important;
            color: white !important;
            font-weight: 500 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            margin: 3px !important;
        }

        .select2-container--bootstrap-5 .select2-selection__choice__remove {
            color: white !important;
            margin-right: 4px !important;
            font-weight: bold !important;
            cursor: pointer !important;
        }

        .select2-container--bootstrap-5 .select2-selection__choice__remove:hover {
            opacity: 0.8 !important;
        }

        .select2-dropdown--below {
            border-radius: 8px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2) !important;
            border: 2px solid #e0e0e0 !important;
            margin-top: 5px !important;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            background: white !important;
            border-radius: 8px !important;
        }

        .select2-container--bootstrap-5 .select2-results__option {
            padding: 10px 12px !important;
            color: var(--text-primary) !important;
            font-weight: 500 !important;
        }

        .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
        }

        /* Estilos para el dropdown de Select2 */
        .select2-container {
            z-index: 10000 !important;
        }

        .select2-container--open {
            z-index: 10000 !important;
        }

        .select2-dropdown {
            z-index: 10001 !important;
            background-color: white !important;
            border: 2px solid #667eea !important;
            border-radius: 0 0 12px 12px !important;
            padding: 0 !important;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3) !important;
        }

        .select2-dropdown--below {
            border-top: none !important;
            border-radius: 0 0 12px 12px !important;
            top: auto !important;
        }

        /* Estilos para el campo de búsqueda en Select2 */
        .select2-search--dropdown {
            padding: 12px !important;
            display: block !important;
            visibility: visible !important;
            border-bottom: 1px solid #f0f0f0 !important;
        }

        .select2-search--dropdown .select2-search__field {
            background-color: #f8f9fa !important;
            border: 2px solid #e0e0e0 !important;
            border-radius: 8px !important;
            padding: 12px 16px !important;
            color: #1a1a1a !important;
            font-weight: 500 !important;
            font-size: 14px !important;
            width: calc(100% - 4px) !important;
            box-sizing: border-box !important;
            display: block !important;
            visibility: visible !important;
            transition: all 0.3s ease !important;
        }

        .select2-search--dropdown .select2-search__field::placeholder {
            color: #667eea !important;
            opacity: 1 !important;
            font-weight: 700 !important;
            font-size: 14px !important;
        }

        /* Asegurar que el placeholder sea visible */
        .select2-search__field {
            -webkit-text-fill-color: unset !important;
        }

        .select2-search__field::placeholder {
            -webkit-text-fill-color: #667eea !important;
            color: #667eea !important;
        }

        /* Cuando el input está vacío, mostrar el placeholder */
        .select2-search__field:placeholder-shown {
            color: #667eea !important;
        }

        .select2-search__field::-webkit-input-placeholder {
            color: #667eea !important;
            font-weight: 700 !important;
            opacity: 1 !important;
        }

        .select2-search__field::-moz-placeholder {
            color: #667eea !important;
            font-weight: 700 !important;
            opacity: 1 !important;
        }

        .select2-search__field:-ms-input-placeholder {
            color: #667eea !important;
            font-weight: 700 !important;
            opacity: 1 !important;
        }

        .select2-search__field::-ms-input-placeholder {
            color: #667eea !important;
            font-weight: 700 !important;
            opacity: 1 !important;
        }
        }

        .select2-search--dropdown .select2-search__field:focus {
            outline: none !important;
            border-color: #667eea !important;
            background-color: white !important;
            color: #1a1a1a !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15) !important;
        }

        /* Contenedor de resultados */
        .select2-results {
            display: block !important;
            visibility: visible !important;
            max-height: 300px !important;
            overflow-y: auto !important;
            padding: 8px 0 !important;
        }

        .select2-results__options {
            display: block !important;
        }

        /* Estilos de las opciones */
        .select2-results__option {
            padding: 12px 16px !important;
            color: #1a1a1a !important;
            font-weight: 500 !important;
            border-bottom: 1px solid #f5f5f5 !important;
            transition: all 0.2s ease !important;
            background-color: white !important;
        }

        .select2-results__option:hover {
            background-color: #f8f9fa !important;
        }

        .select2-results__option--highlighted[aria-selected] {
            background-color: #667eea !important;
            color: white !important;
        }

        .select2-results__option[aria-selected=true] {
            background-color: rgba(102, 126, 234, 0.1) !important;
            color: #1a1a1a !important;
            border-left: 4px solid #667eea !important;
            padding-left: 12px !important;
        }

        /* Estilos mejorados para las selecciones múltiples */
        .select2-container--bootstrap-5 .select2-selection__choice {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-color: transparent !important;
            color: white !important;
            font-weight: 600 !important;
            padding: 8px 12px !important;
            border-radius: 6px !important;
            margin: 4px 4px !important;
            font-size: 13px !important;
            box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3) !important;
            display: inline-flex !important;
            align-items: center !important;
        }

        .select2-container--bootstrap-5 .select2-selection__choice__remove {
            color: white !important;
            font-weight: 700 !important;
            margin-right: 6px !important;
            margin-left: 0 !important;
            padding: 0 4px !important;
        }

        .select2-container--bootstrap-5 .select2-selection__choice__remove:hover {
            color: #fff !important;
            opacity: 0.8 !important;
        }

        .select2-container--bootstrap-5 .select2-selection {
            padding: 8px 12px !important;
            min-height: auto !important;
            background-color: white !important;
            border-color: #e0e0e0 !important;
            border-radius: 8px !important;
            transition: all 0.3s ease !important;
        }

        .select2-container--bootstrap-5 .select2-selection:focus-within {
            border-color: #667eea !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15) !important;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: #667eea !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15) !important;
        }

        /* Barra de desplazamiento personalizada */
        .select2-results::-webkit-scrollbar {
            width: 8px !important;
        }

        .select2-results::-webkit-scrollbar-track {
            background: #f1f1f1 !important;
        }

        .select2-results::-webkit-scrollbar-thumb {
            background: #667eea !important;
            border-radius: 4px !important;
        }

        .select2-results::-webkit-scrollbar-thumb:hover {
            background: #764ba2 !important;
        }

        /* Forzar visibilidad del dropdown */
        .select2-dropdown.select2-dropdown--above,
        .select2-dropdown.select2-dropdown--below {
            display: block !important;
            visibility: visible !important;
            position: absolute !important;
        }

        body .select2-container--open .select2-dropdown {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
    </style>
</head>
<body>
    <?php
    // Capturar mensajes de sesión si existen
    $error_message = $_SESSION['error'] ?? '';
    $success_message = $_SESSION['success'] ?? '';
    
    // Limpiar mensajes después de capturarlos
    unset($_SESSION['error'], $_SESSION['success']);
    ?>

    <!-- Breadcrumb moderno -->
    <nav class="modern-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/RMIE/app/views/dashboard.php">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="/RMIE/app/controllers/RouteController.php?accion=index">
                    <i class="fas fa-route"></i> Rutas
                </a>
            </li>
            <li class="breadcrumb-item active">
                <i class="fas fa-edit"></i> Editar Ruta #<?= $route['id_ruta'] ?? 'N/A' ?>
            </li>
        </ol>
    </nav>

    <!-- Contenedor principal con efecto glass -->
    <div class="glass-container">
        <!-- Header del formulario -->
        <div class="form-header">
            <h1><i class="fas fa-edit"></i> Editar Ruta de Entrega</h1>
            <p>Modifica la información de la ruta y visualiza los cambios en tiempo real</p>
        </div>

        <!-- Contenido del formulario -->
        <div class="form-content">
            <!-- Mensajes de éxito o error -->
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-check-circle"></i>
                    <strong>¡Éxito!</strong> <?= htmlspecialchars($success_message) ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Error:</strong> <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($route) && $route): ?>
                <!-- Grid principal: Formulario y Resumen -->
                <div class="rutas-grid">
                    <!-- Columna izquierda: Formulario -->
                    <div>
                        <form action="rutas.php?accion=edit&id=<?= $route['id_ruta'] ?>" method="POST" id="editRouteForm">
                            <!-- Campo oculto para ID de ruta -->
                            <input type="hidden" name="id_ruta" value="<?= $route['id_ruta'] ?>">
                            <!-- Campo oculto para preservar el día original de la ruta -->
                            <input type="hidden" name="dia_original" value="<?= $dia_ruta ?>">
                            
                            <!-- Sección: Clientes y Locales Dinámicos -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="fas fa-users"></i> Clientes y Locales
                                </h3>
                                
                                <!-- Filtro y selector de cliente -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="filtro_cliente">
                                                <i class="fas fa-search"></i> Buscar Cliente
                                            </label>
                                            <input 
                                                type="text" 
                                                id="filtro_cliente" 
                                                class="form-control"
                                                placeholder="Escribe para buscar por nombre..."
                                                autocomplete="off">
                                            <small class="form-text">
                                                <i class="fas fa-info-circle"></i>
                                                Filtra los clientes por nombre
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cliente_selector">
                                                <i class="fas fa-user-plus"></i> Seleccionar Cliente
                                            </label>
                                            <select id="cliente_selector" class="form-select">
                                                <option value="">-- Selecciona un cliente para agregar --</option>
                                                <?php if (isset($available_clients) && is_array($available_clients)): ?>
                                                    <?php foreach ($available_clients as $client): ?>
                                                        <option value="<?= htmlspecialchars($client['id_clientes']) ?>" data-nombre="<?= htmlspecialchars($client['nombre']) ?>">
                                                            <?= htmlspecialchars($client['nombre']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <small class="form-text">
                                                <i class="fas fa-info-circle"></i>
                                                Selecciona un cliente para ver y agregar sus locales
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Lista dinámica de clientes y locales seleccionados -->
                                <div class="clientes-locales-container">
                                    <div class="alert alert-info" id="no-selection-message">
                                        <i class="fas fa-info-circle"></i>
                                        No hay clientes seleccionados. Usa el selector de arriba para agregar clientes y sus locales.
                                    </div>
                                </div>
                            </div>

                            <!-- Sección: Estado -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="fas fa-flag"></i> Estado de la Ruta
                                </h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="estado" class="form-label">
                                                <i class="fas fa-flag"></i> Estado
                                            </label>
                                            <select name="estado" id="estado" class="form-select">
                                                <option value="activa" <?= (isset($route['estado']) && $route['estado'] == 'activa') ? 'selected' : '' ?>>Activa</option>
                                                <option value="pendiente" <?= (isset($route['estado']) && $route['estado'] == 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="rutas-buttons">
                                <button type="submit" class="btn btn-success" id="submitBtn">
                                    <i class="fas fa-save"></i> Actualizar Ruta
                                </button>
                                <button type="button" class="btn btn-warning" id="resetBtn">
                                    <i class="fas fa-undo"></i> Restaurar
                                </button>
                                <a href="/RMIE/app/controllers/RouteController.php?accion=index" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Columna derecha: Resumen e información -->
                    <div>


                        <!-- Vista previa de cambios -->
                        <div class="form-section" style="margin-top: 2rem;">
                            <h3 class="section-title">
                                <i class="fas fa-eye"></i> Vista Previa de Cambios
                            </h3>
                            <div class="changes-preview" id="changesPreview">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Modifica los campos para ver los cambios aquí.
                                </div>
                            </div>
                        </div>

                        <!-- Información importante -->
                        <div class="info-alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Precauciones Importantes:</strong>
                            <ul>
                                <li>Verifica que la dirección sea correcta y completa</li>
                                <li>Los cambios en IDs pueden afectar relaciones con otros registros</li>
                                <li>Si la ruta está en proceso, coordina los cambios con el equipo</li>
                                <li>Los cambios se aplicarán inmediatamente al confirmar</li>
                            </ul>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- Error: Ruta no encontrada -->
                <div class="alert alert-warning" style="text-align: center; padding: 2rem;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                    <h3>Ruta No Encontrada</h3>
                    <p>La ruta que intentas editar no existe o ha sido eliminada.</p>
                    <a href="/RMIE/app/controllers/RouteController.php?accion=index" class="btn btn-secondary" style="margin-top: 1rem;">
                        <i class="fas fa-arrow-left"></i> Volver a Rutas
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- jQuery (requerido por Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 Bootstrap Theme CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5.min.css" rel="stylesheet" />
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        // Esperar a que jQuery esté completamente cargado
        if (typeof jQuery !== 'undefined') {
            jQuery(document).ready(function($) {
                // Inicializar Select2 en ambos campos con configuración optimizada
                setTimeout(function() {
                    // Configuración común para ambos Select2
                    const commonConfig = {
                        theme: 'bootstrap-5',
                        allowClear: true,
                        width: '100%',
                        dropdownAutoWidth: false,
                        dropdownParent: $('body'),
                        minimumInputLength: 0,
                        language: {
                            noResults: function() {
                                return 'No se encontraron resultados';
                            },
                            searching: function() {
                                return 'Buscando...';
                            },
                            loadingMore: function() {
                                return 'Cargando más resultados...';
                            }
                        }
                    };
                    
                    // Inicializar Select2 para locales
                    if ($('#id_locales').length) {
                        $('#id_locales').select2({
                            ...commonConfig,
                            placeholder: 'Selecciona uno o más locales',
                            multiple: true,
                            closeOnSelect: false,
                            maximumSelectionLength: 5, // Límite razonable
                            language: {
                                ...commonConfig.language,
                                noResults: function() {
                                    return 'No se encontraron locales';
                                },
                                maximumSelected: function() {
                                    return 'Solo puedes seleccionar hasta 5 locales';
                                }
                            }
                        });
                    }

                    // Inicializar Select2 para clientes  
                    if ($('#id_clientes').length) {
                        $('#id_clientes').select2({
                            ...commonConfig,
                            placeholder: 'Selecciona uno o más clientes',
                            multiple: true,
                            closeOnSelect: false,
                            maximumSelectionLength: 10, // Límite razonable
                            language: {
                                ...commonConfig.language,
                                noResults: function() {
                                    return 'No se encontraron clientes';
                                },
                                maximumSelected: function() {
                                    return 'Solo puedes seleccionar hasta 10 clientes';
                                }
                            }
                        });
                    }
                    // Forzar que los select siempre tengan búsqueda visible
                    setTimeout(function() {
                        // Forzar la creación del campo de búsqueda en Select2
                        $('#id_clientes, #id_locales').each(function() {
                            var $this = $(this);
                            if ($this.hasClass('select2-hidden-accessible')) {
                                // Abrir y cerrar para forzar la creación del dropdown
                                $this.select2('open').select2('close');
                            }
                        });
                    }, 100);
                }, 300);

                // Configurar el placeholder del campo de búsqueda dentro del dropdown
                // Mejorar experiencia de búsqueda
                $(document).on('select2:open', function(e) {
                    var $element = $(e.target);
                    var elementId = $element.attr('id');
                    
                    setTimeout(function() {
                        var $dropdown = $element.data('select2').$dropdown;
                        
                        if ($dropdown && $dropdown.length) {
                            var placeholder = elementId === 'id_locales' ? 'Búsqueda de local aquí' : 'Búsqueda de cliente aquí';
                            var labelText = elementId === 'id_locales' ? 'Búsqueda de local aquí' : 'Búsqueda de cliente aquí';
                            
                            // Limpiar cualquier elemento duplicado
                            $dropdown.find('.search-label, .select2-search-helper').remove();
                            
                            var $search = $dropdown.find('input.select2-search__field');
                            if ($search.length > 0) {
                                $search.attr('placeholder', placeholder);
                                $search.val('');
                                
                                // Remover cualquier evento o elemento que pueda causar duplicación
                                $search.off('focus.searchHelper');
                                
                                setTimeout(function() {
                                    $search.focus();
                                }, 100);
                            }
                        }
                    }, 50);
                });
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($route) && $route): ?>
                // Datos originales para comparación
                const originalData = {
                    direccion: <?= json_encode($route['direccion']) ?>,
                    id_locales_json: <?= json_encode($route['id_locales_json']) ?>,
                    id_clientes_json: <?= json_encode($route['id_clientes_json']) ?>,
                    estado: <?= json_encode($route['estado']) ?>
                };

                // Referencias a elementos del formulario
                const form = document.getElementById('editRouteForm');
                const submitBtn = document.getElementById('submitBtn');
                const clienteSelector = document.getElementById('cliente_selector');
                const filtroCliente = document.getElementById('filtro_cliente');
                const clientesLocalesContainer = document.querySelector('.clientes-locales-container');
                const noSelectionMessage = document.getElementById('no-selection-message');
                
                // Almacenar todas las opciones originales del select
                let todasLasOpciones = [];
                if (clienteSelector) {
                    todasLasOpciones = Array.from(clienteSelector.options).slice(1); // Excluir la primera opción "-- Selecciona..."
                }
                
                // Datos de clientes y sus locales
                let clientesData = {};
                let clientesSeleccionados = new Map();
                
                // Cargar datos de clientes y locales iniciales
                <?php 
                $clientes_con_locales = [];
                if (isset($available_clients) && is_array($available_clients)) {
                    foreach ($available_clients as $cliente) {
                        $cliente_locales = [];
                        // Obtener locales del cliente
                        $query_locales = "SELECT l.id_locales, l.nombre_local, l.direccion 
                                         FROM locales l 
                                         INNER JOIN clientes_locales cl ON l.id_locales = cl.id_locales 
                                         WHERE cl.id_clientes = " . intval($cliente['id_clientes']);
                        $result_locales = $conn->query($query_locales);
                        if ($result_locales) {
                            while ($local = $result_locales->fetch_assoc()) {
                                $cliente_locales[] = $local;
                            }
                        }
                        $clientes_con_locales[$cliente['id_clientes']] = [
                            'nombre' => $cliente['nombre'],
                            'locales' => $cliente_locales
                        ];
                    }
                }
                ?>
                clientesData = <?= json_encode($clientes_con_locales) ?>;
                
                // Cargar datos existentes de la ruta
                const routeData = {
                    clientes: <?= json_encode(!empty($route['id_clientes_json']) ? json_decode($route['id_clientes_json'], true) : []) ?>,
                    locales: <?= json_encode(!empty($route['id_locales_json']) ? json_decode($route['id_locales_json'], true) : []) ?>,
                    // Fallback: usar cliente principal si no hay JSON
                    clientePrincipal: <?= json_encode($route['id_clientes'] ?? null) ?>
                };
                


                // Funciones para manejar clientes y locales
                function actualizarVistaClientes() {
                    if (clientesSeleccionados.size === 0) {
                        noSelectionMessage.style.display = 'block';
                        return;
                    }
                    
                    noSelectionMessage.style.display = 'none';
                    let html = '';
                    
                    clientesSeleccionados.forEach((localesSeleccionados, clienteId) => {
                        const cliente = clientesData[clienteId];
                        if (!cliente) return;
                        
                        html += `
                            <div class="cliente-item" data-cliente-id="${clienteId}">
                                <div class="cliente-header">
                                    <span class="cliente-nombre">
                                        <i class="fas fa-user"></i> ${cliente.nombre}
                                    </span>
                                    <button type="button" class="btn-remove-cliente" onclick="removerCliente('${clienteId}')">
                                        <i class="fas fa-times"></i> Remover
                                    </button>
                                </div>
                                <div class="locales-grid">
                        `;
                        
                        cliente.locales.forEach(local => {
                            const localIdStr = local.id_locales.toString();
                            const isChecked = localesSeleccionados.has(localIdStr);
                            

                            
                            html += `
                                <label class="local-checkbox">
                                    <input type="checkbox" 
                                           name="id_locales[]" 
                                           value="${local.id_locales}"
                                           ${isChecked ? 'checked' : ''}
                                           onchange="toggleLocal('${clienteId}', '${local.id_locales}', this.checked)">
                                    <div class="local-info">
                                        <div class="local-nombre">${local.nombre_local}</div>
                                        <div class="local-direccion">${local.direccion || 'Sin dirección'}</div>
                                    </div>
                                </label>
                            `;
                        });
                        
                        html += `
                                </div>
                                <input type="hidden" name="id_clientes[]" value="${clienteId}">
                            </div>
                        `;
                    });
                    
                    // Reemplazar todo el contenido excepto el mensaje de "no selection"
                    const existingItems = clientesLocalesContainer.querySelectorAll('.cliente-item');
                    existingItems.forEach(item => item.remove());
                    clientesLocalesContainer.insertAdjacentHTML('beforeend', html);
                }
                
                function agregarCliente(clienteId) {
                    if (!clienteId || clientesSeleccionados.has(clienteId)) return;
                    
                    const cliente = clientesData[clienteId];
                    if (!cliente) return;
                    
                    // Agregar cliente con todos sus locales seleccionados por defecto
                    const localesSet = new Set();
                    cliente.locales.forEach(local => {
                        localesSet.add(local.id_locales.toString());
                    });
                    
                    clientesSeleccionados.set(clienteId, localesSet);
                    actualizarVistaClientes();
                    
                    // Resetear selector
                    clienteSelector.value = '';
                }
                
                function removerCliente(clienteId) {
                    clientesSeleccionados.delete(clienteId);
                    actualizarVistaClientes();
                }
                
                function toggleLocal(clienteId, localId, isChecked) {
                    const localesSet = clientesSeleccionados.get(clienteId);
                    if (!localesSet) return;
                    
                    if (isChecked) {
                        localesSet.add(localId.toString());
                    } else {
                        localesSet.delete(localId.toString());
                    }
                }
                
                // Función para filtrar clientes
                function filtrarClientes(termino) {
                    // Limpiar el select (mantener solo la primera opción)
                    clienteSelector.innerHTML = '<option value="">-- Selecciona un cliente para agregar --</option>';
                    
                    if (termino.trim() === '') {
                        // Si no hay término de búsqueda, mostrar todos los clientes
                        todasLasOpciones.forEach(opcion => {
                            clienteSelector.appendChild(opcion.cloneNode(true));
                        });
                    } else {
                        // Filtrar opciones por nombre
                        const terminoLower = termino.toLowerCase();
                        const opcionesFiltradas = todasLasOpciones.filter(opcion => {
                            const nombre = opcion.getAttribute('data-nombre').toLowerCase();
                            return nombre.includes(terminoLower);
                        });
                        
                        // Agregar opciones filtradas
                        opcionesFiltradas.forEach(opcion => {
                            clienteSelector.appendChild(opcion.cloneNode(true));
                        });
                        
                        // Mostrar mensaje si no hay resultados
                        if (opcionesFiltradas.length === 0) {
                            const noResultOption = document.createElement('option');
                            noResultOption.value = '';
                            noResultOption.textContent = 'No se encontraron clientes';
                            noResultOption.disabled = true;
                            clienteSelector.appendChild(noResultOption);
                        }
                    }
                }
                
                // Event listener para el filtro de búsqueda
                if (filtroCliente) {
                    filtroCliente.addEventListener('input', function() {
                        filtrarClientes(this.value);
                    });
                    
                    // Limpiar filtro cuando se hace clic en el campo
                    filtroCliente.addEventListener('focus', function() {
                        if (this.value) {
                            this.select(); // Seleccionar todo el texto para fácil reemplazo
                        }
                    });
                }
                
                // Event listener para el selector de cliente
                if (clienteSelector) {
                    clienteSelector.addEventListener('change', function() {
                        if (this.value) {
                            agregarCliente(this.value);
                            // Limpiar filtro después de seleccionar
                            if (filtroCliente) {
                                filtroCliente.value = '';
                                filtrarClientes(''); // Mostrar todos los clientes de nuevo
                            }
                        }
                    });
                }
                
                // Cargar datos existentes al inicio
                if (routeData.clientes && routeData.clientes.length > 0) {
                    
                    routeData.clientes.forEach(clienteId => {
                        const localesSet = new Set();
                        const clienteIdStr = clienteId.toString();
                        

                        
                        const cliente = clientesData[clienteIdStr];
                        if (cliente) {
                            // Si hay locales específicos en JSON, usarlos
                            if (routeData.locales && routeData.locales.length > 0) {
                                
                                cliente.locales.forEach(local => {
                                    const localId = parseInt(local.id_locales);
                                    const localIdStr = local.id_locales.toString();
                                    
                                    if (routeData.locales.includes(localId) || routeData.locales.includes(localIdStr)) {
                                        localesSet.add(localIdStr);
                                    }
                                });
                            } else {
                                // Si no hay locales JSON, buscar el local específico por nombre_local de la ruta
                                const nombreLocalRuta = <?= json_encode($route['nombre_local'] ?? '') ?>;
                                
                                if (nombreLocalRuta) {
                                    cliente.locales.forEach(local => {
                                        const localIdStr = local.id_locales.toString();
                                        // Solo marcar el local que coincida con el nombre_local de la ruta
                                        if (local.nombre_local === nombreLocalRuta) {
                                            localesSet.add(localIdStr);
                                        }
                                    });
                                }
                            }
                        }
                        
                        clientesSeleccionados.set(clienteIdStr, localesSet);
                    });
                    actualizarVistaClientes();
                } else if (routeData.clientePrincipal) {
                    // Fallback: usar cliente principal si existe
                    const clienteIdStr = routeData.clientePrincipal.toString();
                    const cliente = clientesData[clienteIdStr];
                    const nombreLocalRuta = <?= json_encode($route['nombre_local'] ?? '') ?>;
                    
                    if (cliente) {
                        const localesSet = new Set();
                        
                        if (nombreLocalRuta) {
                            // Solo marcar el local específico que coincida con nombre_local
                            cliente.locales.forEach(local => {
                                const localIdStr = local.id_locales.toString();
                                if (local.nombre_local === nombreLocalRuta) {
                                    localesSet.add(localIdStr);
                                }
                            });
                        }
                        
                        clientesSeleccionados.set(clienteIdStr, localesSet);
                        actualizarVistaClientes();
                    }
                }
                
                // Exponer funciones al scope global
                window.removerCliente = removerCliente;
                window.toggleLocal = toggleLocal;



                // Validación en tiempo real
                function validateField(field, minLength, maxLength) {
                    const value = field.value.trim();
                    field.classList.remove('is-valid', 'is-invalid');
                    
                    if (value.length < minLength || value.length > maxLength) {
                        field.classList.add('is-invalid');
                        return false;
                    } else {
                        field.classList.add('is-valid');
                        return true;
                    }
                }

                // Vista previa de cambios
                function showChanges() {
                    const changes = [];
                    const changesContainer = document.getElementById('changesPreview');
                    
                    if (!changesContainer) return;
                    
                    // Comparar clientes y locales seleccionados dinámicamente
                    const currentClientes = Array.from(clientesSeleccionados.keys()).map(id => parseInt(id));
                    const originalClientes = JSON.parse(originalData.id_clientes_json || '[]');
                    
                    if (JSON.stringify(currentClientes.sort()) !== JSON.stringify(originalClientes.sort())) {
                        const clientesNames = currentClientes.map(id => {
                            const cliente = clientesData[id.toString()];
                            return cliente ? cliente.nombre : 'Cliente #' + id;
                        });
                        changes.push({
                            field: 'Clientes',
                            icon: 'fas fa-users',
                            original: originalClientes.length + ' clientes seleccionados',
                            current: currentClientes.length + ' clientes (' + clientesNames.join(', ') + ')'
                        });
                    }
                    
                    // Comparar locales seleccionados
                    const currentLocales = [];
                    clientesSeleccionados.forEach((localesSet, clienteId) => {
                        localesSet.forEach(localId => {
                            currentLocales.push(parseInt(localId));
                        });
                    });
                    const originalLocales = JSON.parse(originalData.id_locales_json || '[]');
                    
                    if (JSON.stringify(currentLocales.sort()) !== JSON.stringify(originalLocales.sort())) {
                        changes.push({
                            field: 'Locales',
                            icon: 'fas fa-store',
                            original: originalLocales.length + ' locales seleccionados',
                            current: currentLocales.length + ' locales seleccionados'
                        });
                    }

                    if (changes.length === 0) {
                        changesContainer.innerHTML = `
                            <div style="text-align: center; color: var(--text-secondary); padding: 1rem;">
                                <i class="fas fa-check-circle" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
                                <p>Modifica los campos para ver los cambios aquí.</p>
                            </div>
                        `;
                        return;
                    }

                    let html = '<div style="padding: 1rem;">';
                    changes.forEach(change => {
                        html += `
                            <div style="margin-bottom: 1rem; padding: 1rem; background: rgba(255, 255, 255, 0.1); border-radius: 8px; border-left: 4px solid #667eea;">
                                <div style="font-weight: 600; margin-bottom: 0.5rem;">
                                    <i class="${change.icon}" style="margin-right: 0.5rem;"></i> ${change.field}
                                </div>
                                <div style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                                    <strong>Original:</strong> ${change.original || 'N/A'}
                                </div>
                                <div style="font-size: 0.9rem; color: #28a745; font-weight: 600;">
                                    <strong>Nuevo:</strong> ${change.current || 'N/A'}
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    changesContainer.innerHTML = html;
                }



                // Listeners para Select2
                $(document).on('select2:select select2:unselect', function() {
                    showChanges();
                });

                // Form submission handling
                if (form) {
                    form.addEventListener('submit', function(e) {
                        // Prevenir el envío automático para hacer validaciones primero
                        e.preventDefault();
                        
                        let hasErrors = false;
                        
                        // Validar selección de clientes
                        if (clientesSeleccionados.size === 0) {
                            alert('Debes seleccionar al menos un cliente');
                            hasErrors = true;
                        }
                        
                        // Validar selección de locales
                        let totalLocales = 0;
                        clientesSeleccionados.forEach((localesSet) => {
                            totalLocales += localesSet.size;
                        });
                        
                        if (totalLocales === 0) {
                            alert('Debes seleccionar al menos un local');
                            hasErrors = true;
                        }
                        
                        if (hasErrors) {
                            return false;
                        }
                        
                        // Limpiar campos hidden existentes
                        const existingHiddenInputs = form.querySelectorAll('input[name="id_clientes[]"], input[name="id_locales[]"]');
                        existingHiddenInputs.forEach(input => input.remove());
                        
                        // Agregar campos hidden para clientes y locales seleccionados
                        clientesSeleccionados.forEach((localesSet, clienteId) => {
                            // Agregar input para el cliente
                            const clienteInput = document.createElement('input');
                            clienteInput.type = 'hidden';
                            clienteInput.name = 'id_clientes[]';
                            clienteInput.value = clienteId;
                            form.appendChild(clienteInput);
                            
                            // Agregar inputs para cada local seleccionado
                            localesSet.forEach(localId => {
                                const localInput = document.createElement('input');
                                localInput.type = 'hidden';
                                localInput.name = 'id_locales[]';
                                localInput.value = localId;
                                form.appendChild(localInput);
                            });
                        });
                        

                        
                        // Mostrar indicador de carga
                        const submitBtn = document.getElementById('submitBtn');
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
                        }
                        
                        // Enviar el formulario manualmente después de agregar los campos
                        setTimeout(() => {
                            form.submit();
                        }, 100);
                    });
                }

                // Botón restaurar funcionalidad
                const resetBtn = document.getElementById('resetBtn');
                if (resetBtn) {
                    resetBtn.addEventListener('click', function() {
                        if (confirmAction('acción general')) {
                            
                            // Restaurar clientes y locales
                            clientesSeleccionados.clear();
                            const originalClientes = JSON.parse(originalData.id_clientes_json || '[]');
                            const originalLocales = JSON.parse(originalData.id_locales_json || '[]');
                            
                            originalClientes.forEach(clienteId => {
                                const localesSet = new Set();
                                const cliente = clientesData[clienteId.toString()];
                                if (cliente) {
                                    cliente.locales.forEach(local => {
                                        if (originalLocales.includes(parseInt(local.id_locales))) {
                                            localesSet.add(local.id_locales.toString());
                                        }
                                    });
                                }
                                clientesSeleccionados.set(clienteId.toString(), localesSet);
                            });
                            
                            actualizarVistaClientes();
                            
                            // Actualizar vista previa
                            showChanges();
                        }
                    });
                }

                // Mostrar cambios al inicio
                showChanges();
            <?php endif; ?>
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
