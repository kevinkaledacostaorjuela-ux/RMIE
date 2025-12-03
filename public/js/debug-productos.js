/**
 * Script adicional de manejo de errores para productos
 * Se debe incluir después de selenium-messages.js
 */

// Función de debug mejorada
window.debugRMIE = {
    enabled: false, // Cambiado a false para ocultar logs normales
    verbose: false, // Solo true para debugging profundo
    
    log: function(message, type = 'info') {
        // Solo mostrar errores críticos y warnings importantes
        if (type === 'error') {
            console.error(`[RMIE Error]:`, message);
        } else if (type === 'warn' && this.enabled) {
            console.warn(`[RMIE Warning]:`, message);
        } else if (this.verbose) {
            // Solo logs detallados si verbose está activado
            const timestamp = new Date().toLocaleTimeString();
            console.log(`[${timestamp}] RMIE Debug:`, message);
        }
    },
    
    checkElement: function(id) {
        const element = document.getElementById(id);
        if (!element) {
            this.log(`Elemento con ID '${id}' no encontrado`, 'error');
            return false;
        }
        this.log(`Elemento '${id}' encontrado correctamente`);
        return true;
    },
    
    testFunction: function(funcName) {
        if (typeof window[funcName] === 'function') {
            this.log(`Función '${funcName}' disponible`);
            return true;
        }
        this.log(`Función '${funcName}' NO disponible`, 'error');
        return false;
    }
};

// Wrapper seguro para todas las funciones críticas
window.safeExecute = function(func, ...args) {
    try {
        if (typeof func === 'function') {
            return func.apply(this, args);
        } else if (typeof func === 'string' && typeof window[func] === 'function') {
            return window[func].apply(this, args);
        } else {
            debugRMIE.log(`Función no válida: ${func}`, 'error');
            return false;
        }
    } catch (error) {
        debugRMIE.log(`Error ejecutando función: ${error.message}`, 'error');
        return false;
    }
};

// Override de las funciones principales con manejo seguro
const originalToggleProductsView = window.toggleProductsView;
window.toggleProductsView = function(view) {
    return safeExecute(function() {
        debugRMIE.log(`Cambiando vista a: ${view}`);
        
        const elements = {
            cardsView: document.getElementById('cardsView'),
            tableView: document.getElementById('tableView'),
            btnCards: document.getElementById('btnCards'),
            btnTable: document.getElementById('btnTable')
        };
        
        // Verificar que todos los elementos existen
        for (let [key, element] of Object.entries(elements)) {
            if (!element) {
                debugRMIE.log(`Elemento ${key} no encontrado`, 'warn');
                return false;
            }
        }
        
        if (view === 'cards') {
            elements.cardsView.style.display = 'grid';
            elements.tableView.style.display = 'none';
            elements.btnCards.classList.add('active');
            elements.btnTable.classList.remove('active');
            localStorage.setItem('productosView', 'cards');
            debugRMIE.log('Vista cambiada a tarjetas');
        } else if (view === 'table') {
            elements.cardsView.style.display = 'none';
            elements.tableView.style.display = 'block';
            elements.btnCards.classList.remove('active');
            elements.btnTable.classList.add('active');
            localStorage.setItem('productosView', 'table');
            debugRMIE.log('Vista cambiada a tabla');
        }
        
        return true;
    });
};

const originalLimpiarFiltros = window.limpiarFiltros;
window.limpiarFiltros = function() {
    return safeExecute(function() {
        debugRMIE.log('Limpiando filtros');
        
        const form = document.getElementById('filterForm');
        if (!form) {
            debugRMIE.log('Formulario filterForm no encontrado', 'error');
            return false;
        }
        
        form.reset();
        window.location.href = '/RMIE/app/controllers/ProductController.php?accion=index';
        return true;
    });
};

const originalLimpiarProductos = window.limpiarProductos;
window.limpiarProductos = function() {
    return safeExecute(function() {
        debugRMIE.log('Iniciando limpieza de productos');
        
        const opcion = prompt(`¿Qué tipo de limpieza quieres hacer en PRODUCTOS?\n\nEscribe el número de tu opción:\n\n1 - Solo eliminar productos con STOCK CERO\n2 - Eliminar productos INACTIVOS\n3 - Eliminar TODOS los productos\n4 - Cancelar`);
        
        if (!opcion || opcion === '4') {
            debugRMIE.log('Limpieza cancelada por el usuario');
            return false;
        }
        
        const urls = {
            '1': '/RMIE/app/controllers/ProductController.php?accion=clean_no_stock',
            '2': '/RMIE/app/controllers/ProductController.php?accion=clean_inactive',
            '3': '/RMIE/app/controllers/ProductController.php?accion=clean_all'
        };
        
        if (urls[opcion]) {
            if (safeExecute('confirmAction', 'limpiar registros')) {
                debugRMIE.log(`Redirigiendo a: ${urls[opcion]}`);
                window.location.href = urls[opcion];
                return true;
            }
        } else {
            debugRMIE.log(`Opción inválida seleccionada: ${opcion}`, 'error');
        }
        
        return false;
    });
};

// Auto-diagnóstico silencioso al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        // Diagnóstico silencioso - solo ejecutar, no reportar
        if (debugRMIE.verbose) {
            debugRMIE.log('=== DIAGNÓSTICO AUTOMÁTICO ===');
        }
        
        // Verificar funciones críticas silenciosamente
        const functions = ['confirmAction', 'toggleProductsView', 'limpiarFiltros', 'limpiarProductos'];
        functions.forEach(func => {
            if (debugRMIE.verbose) debugRMIE.testFunction(func);
        });
        
        // Verificar elementos críticos silenciosamente
        const elements = ['filterForm', 'cardsView', 'tableView', 'btnCards', 'btnTable'];
        elements.forEach(id => {
            if (debugRMIE.verbose) debugRMIE.checkElement(id);
        });
        
        // Verificar localStorage silenciosamente
        try {
            const savedView = localStorage.getItem('productosView');
            if (savedView && (savedView === 'cards' || savedView === 'table')) {
                safeExecute('toggleProductsView', savedView);
            }
        } catch (e) {
            debugRMIE.log(`Error accediendo a localStorage: ${e.message}`, 'error');
        }
        
        if (debugRMIE.verbose) {
            debugRMIE.log('=== FIN DIAGNÓSTICO ===');
        }
        
    }, 1000); // Esperar 1 segundo para que todo cargue
});

// Manejo global de errores mejorado
window.addEventListener('error', function(event) {
    debugRMIE.log(`Error capturado: ${event.message} en ${event.filename}:${event.lineno}`, 'error');
});

window.addEventListener('unhandledrejection', function(event) {
    debugRMIE.log(`Promise rechazada: ${event.reason}`, 'error');
});

// Script debug-productos.js cargado - modo silencioso activado