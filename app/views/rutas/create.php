<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Ruta - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .form-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px 30px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .header-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .header-title i {
            font-size: 2.8rem;
            opacity: 0.9;
        }
        
        .header-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            font-weight: 400;
        }
        
        .form-section {
            padding: 40px 30px;
        }
        
        .section-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .section-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.1);
        }
        
        .section-title {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .section-title i {
            font-size: 1.5rem;
            opacity: 0.9;
        }
        
        .form-row {
            display: grid;
            gap: 25px;
            margin-bottom: 25px;
        }
        
        .form-row-2 {
            grid-template-columns: 1fr 1fr;
        }
        
        .form-row-3 {
            grid-template-columns: 1fr 1fr 1fr;
        }
        
        @media (max-width: 768px) {
            .form-row-2, .form-row-3 {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
        
        .form-floating-modern {
            position: relative;
            margin-bottom: 20px;
        }
        
        .form-control-modern {
            width: 100%;
            padding: 18px 15px 8px 15px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .form-control-modern:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .form-select-modern {
            width: 100%;
            padding: 18px 15px 8px 15px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
        }
        
        .form-select-modern:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .form-textarea-modern {
            width: 100%;
            padding: 18px 15px 8px 15px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            resize: vertical;
            min-height: 120px;
        }
        
        .form-textarea-modern:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .form-floating-modern label {
            position: absolute;
            top: 12px;
            left: 15px;
            color: rgba(102, 126, 234, 0.8);
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-floating-modern label i {
            font-size: 16px;
        }
        
        .required {
            color: #ff6b6b;
            font-weight: bold;
        }
        
        .form-control-modern:focus ~ label,
        .form-control-modern:not(:placeholder-shown) ~ label,
        .form-select-modern:focus ~ label,
        .form-select-modern:not([value=""]) ~ label,
        .form-textarea-modern:focus ~ label,
        .form-textarea-modern:not(:placeholder-shown) ~ label {
            top: 2px;
            font-size: 12px;
            color: #667eea;
        }
        
        .preview-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 20px;
        }
        
        .route-avatar-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #9c27b0 0%, #673ab7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 2.5rem;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(156, 39, 176, 0.3);
            transition: all 0.3s ease;
        }
        
        .route-avatar-large:hover {
            transform: scale(1.05);
        }
        
        .preview-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.9);
        }
        
        .preview-item:last-child {
            border-bottom: none;
        }
        
        .preview-label {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .preview-value {
            font-weight: 500;
            color: white;
            max-width: 60%;
            text-align: right;
            word-break: break-word;
        }
        
        .info-panel {
            background: rgba(255, 193, 7, 0.2);
            border: 1px solid rgba(255, 193, 7, 0.4);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .info-panel h6 {
            color: #ffc107;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .warning-panel {
            background: rgba(255, 152, 0, 0.2);
            border: 1px solid rgba(255, 152, 0, 0.4);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .warning-panel h6 {
            color: #ff9800;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .buttons-section {
            padding: 20px 30px 40px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-modern {
            padding: 15px 35px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            min-width: 180px;
            justify-content: center;
        }
        
        .btn-create {
            background: linear-gradient(135deg, #9c27b0 0%, #673ab7 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-create:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(156, 39, 176, 0.4);
            background: linear-gradient(135deg, #8e24aa 0%, #5e35b1 100%);
            color: white;
        }
        
        .btn-create:disabled {
            background: linear-gradient(135deg, #9e9e9e 0%, #757575 100%);
            cursor: not-allowed;
            transform: none;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .btn-cancel {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-cancel:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 255, 255, 0.2);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.2) 100%);
            color: white;
        }
        
        .btn-clear {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-clear:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 193, 7, 0.4);
            background: linear-gradient(135deg, #e0a800 0%, #dc6309 100%);
            color: white;
        }
        
        .character-count {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            text-align: right;
            margin-top: 5px;
        }
        
        .form-help {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .form-help i {
            color: #e1bee7;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                padding: 0 10px;
            }
            
            .form-container {
                border-radius: 20px;
            }
            
            .header-section {
                padding: 30px 20px;
            }
            
            .header-title {
                font-size: 2rem;
                flex-direction: column;
                gap: 10px;
            }
            
            .form-section {
                padding: 30px 20px;
            }
            
            .section-card {
                padding: 20px;
                margin-bottom: 20px;
            }
            
            .buttons-section {
                padding: 20px 20px 30px;
                flex-direction: column;
            }
            
            .btn-modern {
                width: 100%;
            }
        }
        
        /* Animaciones */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Select2 Personalizados */
        .select2-container--bootstrap-5 .select2-selection {
            background: rgba(255, 255, 255, 0.9) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 15px !important;
            padding: 10px 12px !important;
            min-height: 55px !important;
            transition: all 0.3s ease !important;
        }

        .select2-container--bootstrap-5 .select2-selection:focus-within {
            border-color: rgba(255, 255, 255, 0.8) !important;
            box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3) !important;
        }

        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: rgba(255, 255, 255, 0.8) !important;
        }

        .select2-container--bootstrap-5 .select2-selection__rendered {
            padding: 0 !important;
            color: #2d3748 !important;
            font-weight: 500 !important;
        }

        .select2-container--bootstrap-5 .select2-selection__choice {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 6px 12px !important;
            color: white !important;
            font-weight: 500 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            margin: 4px !important;
        }

        .select2-container--bootstrap-5 .select2-selection__choice__remove {
            color: white !important;
            margin-right: 6px !important;
            font-weight: bold !important;
            cursor: pointer !important;
        }

        .select2-container--bootstrap-5 .select2-selection__choice__remove:hover {
            opacity: 0.8 !important;
        }

        .select2-dropdown--below {
            border-radius: 15px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            margin-top: 5px !important;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            background: rgba(255, 255, 255, 0.95) !important;
            border-radius: 15px !important;
        }

        .select2-container--bootstrap-5 .select2-results__option {
            padding: 12px 15px !important;
            color: #2d3748 !important;
            font-weight: 500 !important;
        }

        .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
        }

        .select2-search--dropdown .select2-search__field {
            background: rgba(255, 255, 255, 0.9) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 10px !important;
            padding: 12px 15px !important;
            color: #2d3748 !important;
            font-weight: 500 !important;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #667eea !important;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.2) !important;
        }

        .select2-container--open .select2-dropdown--below {
            border-top: none !important;
            border-radius: 0 0 15px 15px !important;
        }

        /* Mejorar placeholders de bÍºsqueda */
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

        /* Limpiar elementos duplicados */
        .search-label,
        .select2-search-helper,
        .select2-dropdown .search-tooltip,
        .select2-dropdown .search-overlay {
            display: none !important;
        }

        /* Estilos para Planificación Semanal */
        .planificacion-semanal-container {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .planificacion-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .planificacion-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .planificacion-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
        }

        .dias-semana-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .dia-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .dia-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .dia-card.dia-selected {
            border-color: #4facfe;
            background: linear-gradient(135deg, rgba(79, 172, 254, 0.2) 0%, rgba(79, 172, 254, 0.1) 100%);
            box-shadow: 0 10px 25px rgba(79, 172, 254, 0.3);
        }

        .dia-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .dia-nombre {
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .dia-contador {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 4px 10px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .clientes-lista {
            min-height: 100px;
            max-height: 200px;
            overflow-y: auto;
            margin-bottom: 15px;
        }

        .cliente-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s ease;
        }

        .cliente-item:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .cliente-nombre {
            color: white;
            font-size: 0.9rem;
            font-weight: 500;
            flex: 1;
        }

        .cliente-remove {
            background: rgba(255, 107, 107, 0.8);
            border: none;
            border-radius: 6px;
            color: white;
            padding: 4px 8px;
            font-size: 0.7rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .cliente-remove:hover {
            background: rgba(255, 107, 107, 1);
        }

        .agregar-cliente-btn {
            width: 100%;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 10px;
            color: white;
            padding: 10px 15px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .agregar-cliente-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.3);
        }

        .planificacion-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: white;
            padding: 12px 20px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .btn-warning-modern {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        }

        .btn-info-modern {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }

        .btn-success-modern {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-left-color: #4facfe;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .empty-state {
            text-align: center;
            padding: 20px;
            color: rgba(255, 255, 255, 0.6);
            font-style: italic;
        }

        .modal-select-cliente {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content-cliente {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .cliente-option {
            background: rgba(255, 255, 255, 0.8);
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cliente-option:hover {
            background: rgba(79, 172, 254, 0.1);
            border-color: #4facfe;
            transform: translateY(-1px);
        }

        .cliente-option-info {
            flex: 1;
        }

        .cliente-option-nombre {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 4px;
        }

        .cliente-option-local {
            font-size: 0.85rem;
            color: #666;
        }

        .search-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid rgba(79, 172, 254, 0.3);
            border-radius: 12px;
            font-size: 1rem;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.9);
        }

        .search-input:focus {
            outline: none;
            border-color: #4facfe;
            box-shadow: 0 5px 20px rgba(79, 172, 254, 0.2);
        }

        @media (max-width: 768px) {
            .dias-semana-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .planificacion-actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .btn-modern {
                justify-content: center;
            }
        }
        
        /* Estilos para opciones de locales */
        .local-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .local-option:hover {
            background: #f8fafc;
            border-color: #4facfe;
            box-shadow: 0 2px 8px rgba(79, 172, 254, 0.15);
            transform: translateY(-1px);
        }
        
        .local-option-info {
            flex: 1;
        }
        
        .local-option-nombre {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 4px;
        }
        
        .local-option-direccion {
            font-size: 0.85rem;
            color: #666;
        }
        
        .search-input {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #4facfe;
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
        }
        
        .btn-secondary-modern {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
        }
        
        .btn-secondary-modern:hover {
            background: linear-gradient(135deg, #5a6268, #495057);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }
        
        .btn-warning-modern {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: #212529;
        }
        
        .btn-warning-modern:hover {
            background: linear-gradient(135deg, #e0a800, #d39e00);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
        }
        
        .btn-success-modern {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .btn-success-modern:hover {
            background: linear-gradient(135deg, #20c997, #17a2b8);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }
        
        .creacion-individual-container .form-control {
            transition: all 0.3s ease;
        }
        
        .creacion-individual-container .form-control:focus {
            box-shadow: 0 0 0 2px rgba(79, 172, 254, 0.3);
            border-color: #4facfe;
        }
        
        .local-checkbox-item {
            display: flex;
            align-items: center;
            padding: 10px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .local-checkbox-item:hover {
            background: rgba(255,255,255,0.15);
            border-color: rgba(255,255,255,0.4);
        }
        
        .local-checkbox-item input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
        }
        
        .local-checkbox-info {
            flex: 1;
            color: white;
        }
        
        .local-checkbox-nombre {
            font-weight: 500;
            margin-bottom: 2px;
        }
        
        .local-checkbox-direccion {
            font-size: 0.85rem;
            opacity: 0.8;
        }

    </style>
</head>
<body>
    <div class="main-container">
        <div class="form-container">
            <!-- Header -->
            <div class="header-section">
                <h1 class="header-title">
                    <i class="fas fa-route"></i>
                    Crear Nueva Ruta
                </h1>
                <p class="header-subtitle">Registra una nueva ruta de entrega en el sistema RMIE</p>
            </div>

            <!-- Sección de Creación Individual de Ruta -->
            <div class="creacion-individual-container" style="margin-bottom: 30px;">
                <div class="section-card">
                    <h3 class="section-title" style="color: white; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-plus-circle"></i> Crear Ruta Individual
                    </h3>
                    <p style="color: rgba(255,255,255,0.8); margin-bottom: 25px;">Selecciona un cliente y múltiples locales para crear una ruta específica</p>
                    
                    <form action="/RMIE/rutas.php?accion=create" method="POST" id="createRouteForm">
                        <!-- Campos ocultos para datos dinámicos -->
                        <label for="cliente_id_hidden" style="display: none;">Cliente seleccionado</label>
                        <input type="hidden" id="cliente_id_hidden" name="id_clientes[]" value="">
                        <div id="locales_hidden_container"></div>
                        
                        <!-- Selector de Día -->
                        <div class="form-group" style="margin-bottom: 25px;">
                            <label for="dia_plan" style="color: white; font-weight: 500; margin-bottom: 10px; display: block;">
                                <i class="fas fa-calendar-day"></i> Día de la Semana
                            </label>
                            <select id="dia_plan" name="dia_plan" class="form-control" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); color: white; border-radius: 10px; padding: 12px;">
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miercoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sabado">Sábado</option>
                            </select>
                        </div>
                        
                        <!-- Selector de Cliente -->
                        <div class="form-group" style="margin-bottom: 25px;">
                            <label for="filtro_cliente_create" style="color: white; font-weight: 500; margin-bottom: 10px; display: block;">
                                <i class="fas fa-user"></i> Seleccionar Cliente
                            </label>
                            <input type="text" id="filtro_cliente_create" name="filtro_cliente" class="form-control" placeholder="Escribe para buscar cliente..." style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); color: white; border-radius: 10px; padding: 12px; margin-bottom: 10px;">
                            <select id="cliente_selector_create" name="cliente_id" class="form-control" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); color: white; border-radius: 10px; padding: 12px;">
                                <option value="">Selecciona un cliente...</option>
                            </select>
                        </div>
                        
                        <!-- Selector de Locales -->
                        <div class="form-group" style="margin-bottom: 25px;">
                            <label for="locales_container_create" style="color: white; font-weight: 500; margin-bottom: 10px; display: block;">
                                <i class="fas fa-map-marker-alt"></i> Locales del Cliente
                            </label>
                            <div id="locales_container_create" name="locales_container" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.2); border-radius: 10px; padding: 15px; min-height: 60px;">
                                <p style="color: rgba(255,255,255,0.6); text-align: center; margin: 0;">Selecciona un cliente para ver sus locales</p>
                            </div>
                        </div>
                        
                        <!-- Resumen de Selección -->
                        <div class="form-group" style="margin-bottom: 25px;">
                            <label for="resumen_seleccion_create" style="color: white; font-weight: 500; margin-bottom: 10px; display: block;">
                                <i class="fas fa-list-check"></i> Resumen de la Ruta
                            </label>
                            <div id="resumen_seleccion_create" name="resumen_seleccion" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.2); border-radius: 10px; padding: 15px;">
                                <p style="color: rgba(255,255,255,0.6); margin: 0;">No hay selecciones aún</p>
                            </div>
                        </div>
                        
                        <!-- Estado de la Ruta -->
                        <div class="form-group" style="margin-bottom: 25px;">
                            <label for="estado_ruta" style="color: white; font-weight: 500; margin-bottom: 10px; display: block;">
                                <i class="fas fa-toggle-on"></i> Estado
                            </label>
                            <select id="estado_ruta" name="estado" class="form-control" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); color: white; border-radius: 10px; padding: 12px;">
                                <option value="activa">Activa</option>
                                <option value="pendiente">Pendiente</option>
                            </select>
                        </div>
                        
                        <!-- Botones -->
                        <div style="display: flex; gap: 15px; justify-content: center;">
                            <button type="submit" class="btn-modern btn-success-modern" id="submitCreateBtn" disabled>
                                <i class="fas fa-save"></i> Crear Ruta
                            </button>
                            <button type="button" class="btn-modern btn-secondary-modern" onclick="limpiarFormulario()">
                                <i class="fas fa-eraser"></i> Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Separador -->
            <div style="text-align: center; margin: 40px 0;">
                <div style="height: 1px; background: rgba(255,255,255,0.2); margin: 20px 0;"></div>
                <p style="color: rgba(255,255,255,0.6); font-style: italic;">O utiliza la planificación semanal para crear múltiples rutas</p>
                <div style="height: 1px; background: rgba(255,255,255,0.2); margin: 20px 0;"></div>
            </div>
            
            <!-- SecciÍ³n de Planificación Semanal -->
            <div class="planificacion-semanal-container" style="margin-bottom: 30px;">
                <div class="planificacion-header">
                    <h3 class="planificacion-title">
                        <i class="fas fa-calendar-week"></i> Planificación Semanal de Rutas
                    </h3>
                    <div class="planificacion-subtitle">
                        Gestiona los clientes asignados para cada Día de la semana y utilÍ­zalos para crear rutas
                    </div>
                </div>

                <div class="dias-semana-grid" id="diasSemanaGrid">
                    <!-- Estado de carga inicial -->
                    <div class="loading-inicial" style="
                        grid-column: 1 / -1; 
                        text-align: center; 
                        padding: 60px 20px; 
                        color: rgba(255,255,255,0.8);
                    ">
                        <div class="loading-spinner" style="margin: 0 auto 20px auto;"></div>
                        <div style="font-size: 1.1rem; font-weight: 500;">Cargando Planificación semanal...</div>
                        <div style="font-size: 0.9rem; opacity: 0.7; margin-top: 8px;">Obteniendo datos de clientes y asignaciones</div>
                    </div>
                </div>


            </div>

            <!-- Botones de navegación -->
            <div class="navigation-section" style="margin-top: 30px;">
                <div class="buttons-section">
                    <a href="/RMIE/rutas.php?accion=index" class="btn-modern btn-primary-modern">
                        <i class="fas fa-arrow-left"></i>
                        VOLVER AL DASHBOARD DE RUTAS
                    </a>
                    <button class="btn-modern btn-success-modern" onclick="crearRutasDesdeActual()" id="crearRutasBtn">
                        <i class="fas fa-plus-circle"></i>
                        CREAR RUTAS DESDE PLANIFICACIÓN
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (requerido por Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar Planificación semanal
            cargarPlanificacion();
            
            // Inicializar formulario de creación individual
            inicializarCreacionIndividual();
        });

        // Variables globales para Planificación
        let planificacionData = {};
        let clientesDisponibles = [];
        let diaSeleccionadoActual = null;
        
        // Variables globales para creación individual
        let clientesCreate = [];
        let localesCreate = [];
        let clienteSeleccionadoCreate = null;
        let localesSeleccionadosCreate = new Set();
        
        // Inicializar creación individual
        async function inicializarCreacionIndividual() {
            try {
                // Cargar clientes disponibles
                const response = await fetch('/RMIE/app/api/test_get_available_clients.php');
                const data = await response.json();
                
                if (data.success) {
                    clientesCreate = data.clients;
                    cargarClientesEnSelector();
                }
                
                // Configurar eventos
                configurarEventosCreacion();
                
            } catch (error) {
                console.error('Error al inicializar creación individual:', error);
            }
        }
        
        function cargarClientesEnSelector() {
            const selector = document.getElementById('cliente_selector_create');
            selector.innerHTML = '<option value="">Selecciona un cliente...</option>';
            
            clientesCreate.forEach(cliente => {
                const option = document.createElement('option');
                option.value = cliente.id;
                option.textContent = cliente.nombre;
                option.dataset.cliente = JSON.stringify(cliente);
                selector.appendChild(option);
            });
        }
        
        function configurarEventosCreacion() {
            // Filtro de clientes
            const filtroCliente = document.getElementById('filtro_cliente_create');
            const selectorCliente = document.getElementById('cliente_selector_create');
            
            filtroCliente.addEventListener('input', function() {
                const busqueda = this.value.toLowerCase();
                const opciones = selectorCliente.querySelectorAll('option');
                
                opciones.forEach((opcion, index) => {
                    if (index === 0) return; // Skip primera opción
                    const texto = opcion.textContent.toLowerCase();
                    opcion.style.display = texto.includes(busqueda) ? 'block' : 'none';
                });
            });
            
            // Selección de cliente
            selectorCliente.addEventListener('change', function() {
                const clienteId = this.value;
                if (clienteId) {
                    const clienteData = JSON.parse(this.options[this.selectedIndex].dataset.cliente);
                    seleccionarClienteCreate(clienteData);
                } else {
                    limpiarLocalesCreate();
                }
            });
            
            // Envío de formulario
            const form = document.getElementById('createRouteForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                enviarFormularioCreacion();
            });
        }
        
        async function seleccionarClienteCreate(cliente) {
            clienteSeleccionadoCreate = cliente;
            localesSeleccionadosCreate.clear();
            
            try {
                // Cargar locales del cliente
                const response = await fetch(`/RMIE/app/api/get_client_locals.php?cliente_id=${cliente.id}`);
                const data = await response.json();
                
                if (data.success) {
                    localesCreate = data.locals;
                    mostrarLocalesCreate();
                } else {
                    mostrarErrorLocales('No se pudieron cargar los locales del cliente');
                }
            } catch (error) {
                console.error('Error al cargar locales:', error);
                mostrarErrorLocales('Error al cargar locales');
            }
        }
        
        function mostrarLocalesCreate() {
            const container = document.getElementById('locales_container_create');
            
            if (localesCreate.length === 0) {
                container.innerHTML = '<p style="color: rgba(255,255,255,0.6); text-align: center; margin: 0;">Este cliente no tiene locales asignados</p>';
                return;
            }
            
            let html = '';
            localesCreate.forEach(local => {
                html += `
                    <div class="local-checkbox-item" onclick="toggleLocal(${local.id})">
                        <label for="local_${local.id}" style="display: none;">Local ${local.nombre_local}</label>
                        <input type="checkbox" id="local_${local.id}" name="locales[]" onchange="actualizarSeleccionLocal(${local.id})">
                        <div class="local-checkbox-info">
                            <div class="local-checkbox-nombre">${local.nombre_local}</div>
                            <div class="local-checkbox-direccion">${local.direccion}</div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
            actualizarResumenCreate();
        }
        
        function mostrarErrorLocales(mensaje) {
            const container = document.getElementById('locales_container_create');
            container.innerHTML = `<p style="color: #ff6b6b; text-align: center; margin: 0;">${mensaje}</p>`;
        }
        
        function toggleLocal(localId) {
            const checkbox = document.getElementById(`local_${localId}`);
            checkbox.checked = !checkbox.checked;
            actualizarSeleccionLocal(localId);
        }
        
        function actualizarSeleccionLocal(localId) {
            const checkbox = document.getElementById(`local_${localId}`);
            
            if (checkbox.checked) {
                localesSeleccionadosCreate.add(localId);
            } else {
                localesSeleccionadosCreate.delete(localId);
            }
            
            actualizarResumenCreate();
        }
        
        function actualizarResumenCreate() {
            const resumen = document.getElementById('resumen_seleccion_create');
            const submitBtn = document.getElementById('submitCreateBtn');
            
            if (!clienteSeleccionadoCreate || localesSeleccionadosCreate.size === 0) {
                resumen.innerHTML = '<p style="color: rgba(255,255,255,0.6); margin: 0;">No hay selecciones aún</p>';
                submitBtn.disabled = true;
                return;
            }
            
            const localesSeleccionados = localesCreate.filter(local => localesSeleccionadosCreate.has(local.id));
            
            let html = `
                <div style="color: white;">
                    <div style="margin-bottom: 10px;">
                        <strong><i class="fas fa-user"></i> Cliente:</strong> ${clienteSeleccionadoCreate.nombre}
                    </div>
                    <div>
                        <strong><i class="fas fa-map-marker-alt"></i> Locales (${localesSeleccionados.length}):</strong>
                        <ul style="margin: 5px 0 0 20px; padding: 0;">
            `;
            
            localesSeleccionados.forEach(local => {
                html += `<li style="margin: 2px 0;">${local.nombre_local} - ${local.direccion}</li>`;
            });
            
            html += '</ul></div></div>';
            
            resumen.innerHTML = html;
            submitBtn.disabled = false;
        }
        
        async function enviarFormularioCreacion() {
            if (!clienteSeleccionadoCreate || localesSeleccionadosCreate.size === 0) {
                alert('Debe seleccionar un cliente y al menos un local');
                return;
            }
            
            const submitBtn = document.getElementById('submitCreateBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando...';
            
            try {
                // Actualizar campos hidden antes de enviar
                if (clienteSeleccionadoCreate) {
                    document.getElementById('cliente_id_hidden').value = clienteSeleccionadoCreate.id;
                }
                
                // Limpiar y actualizar locales hidden
                const localesContainer = document.getElementById('locales_hidden_container');
                localesContainer.innerHTML = '';
                
                localesSeleccionadosCreate.forEach((localId, index) => {
                    // Crear label oculto para el input hidden
                    const hiddenLabel = document.createElement('label');
                    hiddenLabel.setAttribute('for', `local_hidden_${index}`);
                    hiddenLabel.style.display = 'none';
                    hiddenLabel.textContent = `Local seleccionado ${index + 1}`;
                    
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'id_locales[]';
                    hiddenInput.id = `local_hidden_${index}`;
                    hiddenInput.value = localId;
                    
                    localesContainer.appendChild(hiddenLabel);
                    localesContainer.appendChild(hiddenInput);
                });
                
                const formData = new FormData(document.getElementById('createRouteForm'));
                
                // Enviar formulario
                const response = await fetch('/RMIE/rutas.php?accion=create', {
                    method: 'POST',
                    body: formData
                });
                
                if (response.ok) {
                    // Verificar si hay redirección
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        alert('Ruta creada exitosamente');
                        limpiarFormulario();
                    }
                } else {
                    throw new Error('Error en la respuesta del servidor');
                }
                
            } catch (error) {
                console.error('Error al enviar formulario:', error);
                alert('Error al crear la ruta. Inténtalo de nuevo.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }
        
        function limpiarFormulario() {
            // Limpiar formulario
            document.getElementById('createRouteForm').reset();
            
            // Limpiar variables
            clienteSeleccionadoCreate = null;
            localesSeleccionadosCreate.clear();
            
            // Limpiar interfaz
            document.getElementById('filtro_cliente_create').value = '';
            document.getElementById('cliente_selector_create').value = '';
            limpiarLocalesCreate();
            actualizarResumenCreate();
        }
        
        function limpiarLocalesCreate() {
            const container = document.getElementById('locales_container_create');
            container.innerHTML = '<p style="color: rgba(255,255,255,0.6); text-align: center; margin: 0;">Selecciona un cliente para ver sus locales</p>';
            localesSeleccionadosCreate.clear();
            actualizarResumenCreate();
        }

        // FunciÍ³n para cargar la Planificación semanal
        async function cargarPlanificacion() {
            try {
                const container = document.querySelector('.planificacion-semanal-container');
                if (!container) return;

                // Mostrar estado de carga
                const grid = document.getElementById('diasSemanaGrid');
                grid.innerHTML = `
                    <div class="loading-inicial" style="
                        grid-column: 1 / -1; 
                        text-align: center; 
                        padding: 60px 20px; 
                        color: rgba(255,255,255,0.8);
                    ">
                        <div class="loading-spinner" style="margin: 0 auto 20px auto;"></div>
                        <div style="font-size: 1.1rem; font-weight: 500;">Cargando Planificación semanal...</div>
                        <div style="font-size: 0.9rem; opacity: 0.7; margin-top: 8px;">Obteniendo datos de clientes y asignaciones</div>
                    </div>
                `;

                // Cargar datos de Planificación y clientes en paralelo
                const [planResponse, clientesResponse] = await Promise.all([
                    fetch('/RMIE/app/api/test_route_info.php'),
                    fetch('/RMIE/app/api/test_get_available_clients.php')
                ]);

                if (!planResponse.ok || !clientesResponse.ok) {
                    throw new Error('Error al cargar datos');
                }

                const planData = await planResponse.json();
                const clientesData = await clientesResponse.json();

                if (planData.success) {
                    planificacionData = planData.data;
                }

                if (clientesData.success) {
                    // Adaptar estructura de datos de clientes
                    clientesDisponibles = clientesData.clients.map(cliente => ({
                        id_clientes: cliente.id,
                        nombre: cliente.nombre,
                        telefono: cliente.telefono,
                        correo: cliente.correo,
                        estado: cliente.estado
                    }));
                }

                // Renderizar la Planificación
                renderizarPlanificacion();

            } catch (error) {
                console.error('Error al cargar planificación:', error);
                
                const grid = document.getElementById('diasSemanaGrid');
                grid.innerHTML = `
                    <div class="empty-state" style="grid-column: 1 / -1;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 10px; color: #ff6b6b;"></i>
                        <div>Error al cargar la Planificación semanal</div>
                        <div style="font-size: 0.8rem; color: rgba(255,255,255,0.7); margin: 10px 0;">${error.message}</div>
                        <button onclick="cargarPlanificacion()" class="btn-modern btn-info-modern" style="margin-top: 15px;">
                            <i class="fas fa-sync-alt"></i> Reintentar
                        </button>
                    </div>
                `;
            }
        }

        // Función para renderizar la planificación
        function renderizarPlanificacion() {
            const grid = document.getElementById('diasSemanaGrid');
            const diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            
            let html = '';
            
            diasSemana.forEach(dia => {
                const clientesDia = planificacionData[dia] || [];
                const isSelected = diaSeleccionadoActual === dia;
                
                html += `
                    <div class="dia-card ${isSelected ? 'dia-selected' : ''}" data-dia="${dia}">
                        <div class="dia-header">
                            <div class="dia-nombre">${dia}</div>
                            <div class="dia-contador">${clientesDia.length} clientes</div>
                        </div>
                        
                        <div class="clientes-lista" id="clientes-${dia}">
                            ${clientesDia.length > 0 ? 
                                clientesDia.map(cliente => `
                                    <div class="cliente-item">
                                        <div class="cliente-nombre">${cliente.nombre}</div>
                                        <button class="cliente-remove" onclick="removerClienteDeDia('${dia}', ${cliente.id_clientes})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                `).join('') : 
                                '<div class="empty-state">No hay clientes asignados</div>'
                            }
                        </div>
                        
                        <button class="agregar-cliente-btn" onclick="agregarClienteADia('${dia}')">
                            <i class="fas fa-plus"></i> Agregar Cliente
                        </button>
                    </div>
                `;
            });
            
            grid.innerHTML = html;
        }

        // Función para agregar cliente a un día específico
        function agregarClienteADia(dia, crearNuevoModal = true) {
            
            if (!clientesDisponibles || clientesDisponibles.length === 0) {
                alert('No hay clientes disponibles. Los datos aún se están cargando.');
                return;
            }
            
            // Obtener clientes ya asignados a este día
            const clientesAsignados = planificacionData[dia] || [];
            const idsAsignados = clientesAsignados.map(c => c.id_clientes);
            
            // Filtrar clientes disponibles que no estén ya asignados
            const clientesParaMostrar = clientesDisponibles.filter(c => !idsAsignados.includes(c.id_clientes));
            
            if (clientesParaMostrar.length === 0) {
                alert('No hay más clientes disponibles para asignar a este día');
                if (crearNuevoModal) cerrarModalCliente();
                return;
            }

            const contenidoModal = `
                <h4 style="text-align: center; margin-bottom: 20px; color: #2d3748;">
                    <i class="fas fa-user-plus"></i> Seleccionar Cliente para ${dia}
                </h4>
                
                ${clientesAsignados.length > 0 ? `
                    <div style="background: #e8f5e8; padding: 10px; border-radius: 6px; margin-bottom: 15px; text-align: center; font-size: 0.9rem;">
                        <i class="fas fa-check-circle" style="color: #28a745;"></i>
                        <strong>${clientesAsignados.length}</strong> cliente(s) ya agregado(s) a ${dia}
                    </div>
                ` : ''}
                
                <label for="buscar_cliente_modal" style="display: none;">Buscar cliente</label>
                <input type="text" id="buscar_cliente_modal" name="buscar_cliente" class="search-input" placeholder="Buscar cliente..." 
                       onkeyup="filtrarClientesModal(this.value)">
                
                <div id="clientesModalList">
                    ${clientesParaMostrar.map(cliente => `
                        <div class="cliente-option" onclick="mostrarLocalesDisponibles(${cliente.id_clientes}, '${cliente.nombre}', '${dia}')">
                            <div class="cliente-option-info">
                                <div class="cliente-option-nombre">${cliente.nombre}</div>
                                <div class="cliente-option-descripcion" style="font-size: 0.8rem; color: #666;">
                                    Haz clic para ver locales disponibles
                                </div>
                            </div>
                            <i class="fas fa-chevron-right" style="color: #4facfe;"></i>
                        </div>
                    `).join('')}
                </div>
                
                <div style="text-align: center; margin-top: 20px; display: flex; gap: 10px; justify-content: center;">
                    ${clientesAsignados.length > 0 ? `
                        <button class="btn-modern btn-success-modern" onclick="cerrarModalCliente()">
                            <i class="fas fa-check"></i> Terminar
                        </button>
                    ` : ''}
                    <button class="btn-modern btn-warning-modern" onclick="cerrarModalCliente()">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            `;
            
            if (crearNuevoModal) {
                // Crear nuevo modal
                const modal = document.createElement('div');
                modal.className = 'modal-select-cliente';
                modal.innerHTML = `<div class="modal-content-cliente">${contenidoModal}</div>`;
                document.body.appendChild(modal);
            } else {
                // Reutilizar modal existente
                const modalContent = document.querySelector('.modal-content-cliente');
                if (modalContent) {
                    modalContent.innerHTML = contenidoModal;
                }
            }
        }

        // Función para mostrar locales disponibles de un cliente
        async function mostrarLocalesDisponibles(idCliente, nombreCliente, dia) {
            try {
                // Obtener locales asignados específicamente a este cliente
                const response = await fetch(`/RMIE/test_get_client_locals.php?id_cliente=${idCliente}`);
                const data = await response.json();
                
                if (!data.success || !data.locals || data.locals.length === 0) {
                    alert(`El cliente ${nombreCliente} no tiene locales asignados`);
                    return;
                }
                
                // Actualizar el contenido del modal con la lista de locales
                const modal = document.querySelector('.modal-content-cliente');
                modal.innerHTML = `
                    <h4 style="text-align: center; margin-bottom: 20px; color: #2d3748;">
                        <i class="fas fa-store"></i> Seleccionar Local para ${nombreCliente}
                    </h4>
                    
                    <div style="background: #f8f9fa; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                        <strong>Cliente:</strong> ${nombreCliente} → <strong>Día:</strong> ${dia}
                    </div>
                    
                    <label for="buscar_local_modal" style="display: none;">Buscar local</label>
                    <input type="text" id="buscar_local_modal" name="buscar_local" class="search-input" placeholder="Buscar local..." 
                           onkeyup="filtrarLocalesModal(this.value)">
                    
                    <div id="localesModalList" style="max-height: 300px; overflow-y: auto;">
                        ${data.locals.map(local => `
                            <div class="local-option" onclick="confirmarSeleccion(${idCliente}, '${nombreCliente}', ${local.id}, '${local.nombre}', '${local.direccion || ''}', '${dia}')">
                                <div class="local-option-info">
                                    <div class="local-option-nombre" style="font-weight: 600; color: #2d3748;">
                                        <i class="fas fa-store" style="margin-right: 8px; color: #4facfe;"></i>
                                        ${local.nombre}
                                    </div>
                                    <div class="local-option-direccion" style="font-size: 0.85rem; color: #666; margin-top: 4px;">
                                        <i class="fas fa-map-marker-alt" style="margin-right: 6px;"></i>
                                        ${local.direccion || 'Sin dirección especificada'}
                                    </div>
                                </div>
                                <i class="fas fa-check-circle" style="color: #28a745; font-size: 1.2rem;"></i>
                            </div>
                        `).join('')}
                    </div>
                    
                    <div style="text-align: center; margin-top: 20px; display: flex; gap: 10px; justify-content: center;">
                        <button class="btn-modern btn-secondary-modern" onclick="volverASeleccionCliente('${dia}')">
                            <i class="fas fa-arrow-left"></i> Volver
                        </button>
                        <button class="btn-modern btn-warning-modern" onclick="cerrarModalCliente()">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                `;
                
            } catch (error) {
                console.error('Error al cargar locales:', error);
                alert('Error al cargar los locales disponibles');
            }
        }

        // Función para confirmar la selección de cliente y local
        function confirmarSeleccion(idCliente, nombreCliente, idLocal, nombreLocal, direccionLocal, dia) {
            // Agregar cliente al día con la información del local seleccionado
            if (!planificacionData[dia]) {
                planificacionData[dia] = [];
            }
            
            // Verificar que no esté ya agregado
            const yaExiste = planificacionData[dia].find(c => c.id_clientes === idCliente);
            if (yaExiste) {
                alert('Este cliente ya está asignado a este día');
                return;
            }
            
            // Agregar cliente con información del local
            planificacionData[dia].push({
                id_clientes: idCliente,
                nombre: nombreCliente,
                local: {
                    id: idLocal,
                    nombre: nombreLocal,
                    direccion: direccionLocal
                }
            });
            
            // Re-renderizar la planificación para mostrar el nuevo cliente agregado
            renderizarPlanificacion();
            
            // Volver a mostrar la selección de clientes para agregar más
            volverASeleccionCliente(dia);
        }

        // Función para volver a la selección de cliente
        function volverASeleccionCliente(dia) {
            // No cerrar el modal, solo volver a cargar la lista de clientes
            agregarClienteADia(dia, false); // false = no abrir nuevo modal, reutilizar el existente
        }

        // Función para filtrar locales en el modal
        function filtrarLocalesModal(busqueda) {
            const opciones = document.querySelectorAll('.local-option');
            const termino = busqueda.toLowerCase();
            
            opciones.forEach(opcion => {
                const nombre = opcion.querySelector('.local-option-nombre').textContent.toLowerCase();
                const direccion = opcion.querySelector('.local-option-direccion').textContent.toLowerCase();
                const visible = nombre.includes(termino) || direccion.includes(termino);
                opcion.style.display = visible ? 'flex' : 'none';
            });
        }

        // Función para cargar información del local del cliente
        async function cargarInfoLocalCliente(idCliente) {
            try {
                const response = await fetch(`/RMIE/app/api/get_cliente_local.php?cliente_id=${idCliente}`);
                const data = await response.json();
                
                const localInfoElement = document.getElementById(`local-info-${idCliente}`);
                if (localInfoElement) {
                    if (data.success && data.local) {
                        const local = data.local;
                        localInfoElement.innerHTML = `
                            <i class="fas fa-store" style="margin-right: 5px;"></i>
                            ${local.nombre} - ${local.localidad || local.direccion || 'Sin ubicación'}
                        `;
                        localInfoElement.style.color = '#4facfe';
                    } else {
                        localInfoElement.innerHTML = `
                            <i class="fas fa-exclamation-triangle" style="margin-right: 5px; color: #ff6b6b;"></i>
                            Sin local asignado
                        `;
                        localInfoElement.style.color = '#ff6b6b';
                    }
                }
            } catch (error) {
                console.error('Error al cargar local del cliente:', error);
                const localInfoElement = document.getElementById(`local-info-${idCliente}`);
                if (localInfoElement) {
                    localInfoElement.innerHTML = `
                        <i class="fas fa-times" style="margin-right: 5px; color: #ff6b6b;"></i>
                        Error al cargar local
                    `;
                    localInfoElement.style.color = '#ff6b6b';
                }
            }
        }

        // FunciÍ³n para seleccionar cliente con informaciÍ³n de local
        function seleccionarClienteConLocal(idCliente, nombreCliente, dia) {
            // Agregar cliente al Día
            if (!planificacionData[dia]) {
                planificacionData[dia] = [];
            }
            
            // Verificar que no estÍ© ya agregado
            const yaExiste = planificacionData[dia].find(c => c.id_clientes === idCliente);
            if (yaExiste) {
                alert('Este cliente ya estÍ¡ asignado a este Día');
                return;
            }
            
            // Agregar cliente
            planificacionData[dia].push({
                id_clientes: idCliente,
                nombre: nombreCliente
            });
            
            // Cerrar modal y re-renderizar
            cerrarModalCliente();
            renderizarPlanificacion();
        }

        // FunciÍ³n para filtrar clientes en el modal
        function filtrarClientesModal(busqueda) {
            const opciones = document.querySelectorAll('.cliente-option');
            const termino = busqueda.toLowerCase();
            
            opciones.forEach(opcion => {
                const nombre = opcion.querySelector('.cliente-option-nombre').textContent.toLowerCase();
                const visible = nombre.includes(termino);
                opcion.style.display = visible ? 'flex' : 'none';
            });
        }

        // FunciÍ³n para cerrar modal de clientes
        function cerrarModalCliente() {
            const modal = document.querySelector('.modal-select-cliente');
            if (modal) {
                modal.remove();
            }
        }

        // FunciÍ³n para remover cliente de un Día
        function removerClienteDeDia(dia, idCliente) {
            if (planificacionData[dia]) {
                planificacionData[dia] = planificacionData[dia].filter(c => c.id_clientes !== idCliente);
                renderizarPlanificacion();
            }
        }

        // Función para guardar planificación
        async function guardarPlanificacion() {
            // Verificar que hay datos para guardar
            if (Object.keys(planificacionData).length === 0) {
                alert('No hay planificación para guardar. Primero asigna clientes a los días.');
                return;
            }

            // Contar total de asignaciones
            let totalAsignaciones = 0;
            for (const dia in planificacionData) {
                if (planificacionData[dia] && Array.isArray(planificacionData[dia])) {
                    totalAsignaciones += planificacionData[dia].length;
                }
            }

            if (totalAsignaciones === 0) {
                alert('No hay clientes asignados para guardar.');
                return;
            }

            try {
                console.log('Guardando planificación:', planificacionData);
                
                const response = await fetch('/RMIE/app/api/test_save_planificacion.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        planificacion: planificacionData
                    })
                });

                const result = await response.json();
                console.log('Resultado del guardado:', result);
                
                if (result.success) {
                    alert(`Planificación guardada exitosamente!\n\nSe guardaron ${result.total_asignaciones} asignaciones de clientes.`);
                } else {
                    alert('Error al guardar la planificación: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al guardar la planificación');
            }
        }

        // FunciÍ³n para resetear Planificación
        function resetearPlanificacion() {
            if (confirmAction("acción")) {
                planificacionData = {};
                renderizarPlanificacion();
            }
        }

        // Función para crear rutas desde Planificación actual
        async function crearRutasDesdeActual() {
            if (Object.keys(planificacionData).length === 0) {
                alert('No hay Planificación para crear rutas. Primero asigna clientes a los días.');
                return;
            }

            // Contar total de clientes asignados
            let totalClientes = 0;
            for (const dia in planificacionData) {
                if (planificacionData[dia] && Array.isArray(planificacionData[dia])) {
                    totalClientes += planificacionData[dia].length;
                }
            }

            if (totalClientes === 0) {
                alert('No hay clientes asignados en la planificación para crear rutas.');
                return;
            }

            if (!confirmAction("acción").length} días con ${totalClientes} clientes total.`)) {
                return;
            }

            try {
                console.log('Enviando planificación:', planificacionData);
                
                const response = await fetch('/RMIE/app/api/crear_rutas_desde_planificacion.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        planificacion: planificacionData
                    })
                });

                const result = await response.json();
                console.log('Respuesta de la API:', result);
                
                if (result.success) {
                    alert(`Se crearon ${result.rutas_creadas} rutas exitosamente`);
                    // Redirigir al índice de rutas
                    window.location.href = '/RMIE/rutas.php?accion=index';
                } else {
                    alert('Error al crear las rutas: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al crear las rutas');
            }
        }
    </script>
</body>
</html>
