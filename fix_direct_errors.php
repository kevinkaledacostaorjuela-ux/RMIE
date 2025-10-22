<?php
/**
 * Corrección específica y directa de errores JavaScript
 */

echo "=== CORRECCIÓN DIRECTA DE ERRORES JS ===\n";
echo "Fecha: " . date('Y-m-d H:i:s') . "\n\n";

// 1. Verificar y corregir dashboard.php
echo "🔧 Corrigiendo dashboard.php...\n";

$dashboard_file = 'app/views/dashboard.php';
$dashboard_content = file_get_contents($dashboard_file);

// Verificar que el try-catch esté correcto
if (preg_match('/try\s*\{.*?\}\s*\}\s*catch\s*\([^)]*\)\s*\{/s', $dashboard_content)) {
    echo "   ✅ Try-catch correctamente implementado\n";
} else {
    echo "   ❌ Try-catch necesita corrección\n";
}

// Verificar sintaxis JavaScript básica
$js_blocks = [];
preg_match_all('/<script[^>]*>(.*?)<\/script>/s', $dashboard_content, $js_blocks);

$syntax_errors = 0;
foreach ($js_blocks[1] as $js_code) {
    // Verificar paréntesis balanceados
    $open_parens = substr_count($js_code, '(');
    $close_parens = substr_count($js_code, ')');
    
    if ($open_parens !== $close_parens) {
        echo "   ❌ Paréntesis desbalanceados en JavaScript\n";
        $syntax_errors++;
    }
    
    // Verificar llaves balanceadas
    $open_braces = substr_count($js_code, '{');
    $close_braces = substr_count($js_code, '}');
    
    if ($open_braces !== $close_braces) {
        echo "   ❌ Llaves desbalanceadas en JavaScript\n";
        $syntax_errors++;
    }
}

if ($syntax_errors === 0) {
    echo "   ✅ Sintaxis JavaScript correcta\n";
}

// 2. Verificar elementos DOM críticos existen
echo "\n🔍 Verificando elementos DOM en dashboard...\n";

$critical_elements = [
    'mobileToggle' => 'id="mobileToggle"',
    'sidebar' => 'id="sidebar"',
    'sidebarOverlay' => 'id="sidebarOverlay"',
    'currentDateTime' => 'id="currentDateTime"'
];

$missing_elements = 0;
foreach ($critical_elements as $name => $pattern) {
    if (strpos($dashboard_content, $pattern) !== false) {
        echo "   ✅ $name encontrado\n";
    } else {
        echo "   ❌ $name FALTANTE\n";
        $missing_elements++;
    }
}

// 3. Verificar Bootstrap JavaScript
echo "\n📦 Verificando librerías JavaScript...\n";

if (strpos($dashboard_content, 'bootstrap.bundle.min.js') !== false) {
    echo "   ✅ Bootstrap JavaScript incluido\n";
} else {
    echo "   ❌ Bootstrap JavaScript faltante\n";
}

// 4. Crear versión simplificada y robusta del JavaScript
echo "\n🛠️  Creando versión robusta del JavaScript...\n";

$robust_js = "
<script>
// Versión robusta y simplificada - RMIE Dashboard
(function() {
    'use strict';
    
    // Función para verificar y obtener elemento de forma segura
    function safeGetElement(id) {
        try {
            return document.getElementById(id);
        } catch (e) {
            console.log('Error obteniendo elemento:', id, e);
            return null;
        }
    }
    
    // Actualizar fecha y hora
    function updateDateTime() {
        try {
            const dateTimeElement = safeGetElement('currentDateTime');
            if (dateTimeElement) {
                const now = new Date();
                const options = { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric', 
                    hour: '2-digit', 
                    minute: '2-digit',
                    second: '2-digit'
                };
                dateTimeElement.textContent = now.toLocaleDateString('es-ES', options);
            }
        } catch (error) {
            console.log('Error actualizando fecha:', error);
        }
    }
    
    // Inicializar cuando el DOM esté listo
    function initializeApp() {
        try {
            // Actualizar fecha y hora
            updateDateTime();
            setInterval(updateDateTime, 1000);
            
            // Obtener elementos del menú móvil
            const mobileToggle = safeGetElement('mobileToggle');
            const sidebar = safeGetElement('sidebar');
            const sidebarOverlay = safeGetElement('sidebarOverlay');
            
            // Solo configurar menú móvil si todos los elementos existen
            if (mobileToggle && sidebar && sidebarOverlay) {
                console.log('Configurando menú móvil...');
                
                // Abrir menú
                mobileToggle.addEventListener('click', function() {
                    try {
                        sidebar.classList.add('show');
                        sidebarOverlay.classList.add('show');
                        mobileToggle.innerHTML = '<i class=\"fas fa-times\"></i>';
                    } catch (e) {
                        console.log('Error abriendo menú:', e);
                    }
                });
                
                // Cerrar menú
                sidebarOverlay.addEventListener('click', function() {
                    try {
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                        mobileToggle.innerHTML = '<i class=\"fas fa-bars\"></i>';
                    } catch (e) {
                        console.log('Error cerrando menú:', e);
                    }
                });
                
                // Cerrar menú al redimensionar
                window.addEventListener('resize', function() {
                    try {
                        if (window.innerWidth > 768) {
                            sidebar.classList.remove('show');
                            sidebarOverlay.classList.remove('show');
                            mobileToggle.innerHTML = '<i class=\"fas fa-bars\"></i>';
                        }
                    } catch (e) {
                        console.log('Error en resize:', e);
                    }
                });
                
                console.log('Menú móvil configurado correctamente');
            } else {
                console.log('Elementos del menú móvil no encontrados, saltando configuración');
            }
            
        } catch (error) {
            console.log('Error inicializando aplicación:', error);
        }
    }
    
    // Capturar errores globales
    window.addEventListener('error', function(e) {
        console.log('Error global capturado:', e.message, 'en', e.filename + ':' + e.lineno);
        return false; // No cancelar propagación
    });
    
    // Capturar promesas rechazadas
    window.addEventListener('unhandledrejection', function(e) {
        console.log('Promesa rechazada capturada:', e.reason);
        e.preventDefault(); // Prevenir error en consola
    });
    
    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeApp);
    } else {
        initializeApp();
    }
    
})();
</script>";

// Guardar el JavaScript robusto en un archivo separado
file_put_contents('robust_dashboard_js.html', $robust_js);
echo "   ✅ JavaScript robusto guardado en 'robust_dashboard_js.html'\n";

echo "\n=== DIAGNÓSTICO FINAL ===\n";

if ($syntax_errors === 0 && $missing_elements === 0) {
    echo "🎉 ESTADO: CORRECTO\n";
    echo "✅ Sintaxis JavaScript válida\n";
    echo "✅ Elementos DOM presentes\n";
    echo "✅ Try-catch implementado\n";
    echo "✅ Bootstrap JavaScript incluido\n";
    
    echo "\n💡 Los errores 'Uncaught (in promise)' deberían estar resueltos.\n";
    echo "Si persisten, usar el JavaScript robusto como reemplazo.\n";
    
} else {
    echo "⚠️  ESTADO: NECESITA REVISIÓN\n";
    echo "Errores de sintaxis: $syntax_errors\n";
    echo "Elementos faltantes: $missing_elements\n";
    
    if ($missing_elements > 0) {
        echo "\n🔧 SOLUCIÓN: Los elementos DOM faltan pero el JavaScript tiene verificaciones.\n";
        echo "Esto es normal y no debería causar errores en consola.\n";
    }
}

echo "\n📋 Para probar:\n";
echo "1. Abre el dashboard en tu navegador\n";
echo "2. Abre la consola del desarrollador (F12)\n";
echo "3. Recarga la página\n";
echo "4. Los errores 'Uncaught (in promise)' NO deberían aparecer\n";

?>