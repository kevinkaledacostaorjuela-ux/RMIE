/**
 * Sistema simplificado sin confirmaciones para Selenium
 * Elimina todos los popups de confirmación para pruebas automáticas
 * Version 2.0 - Con manejo de errores mejorado
 */

// CONTROL DE ERRORES
window.addEventListener('error', function(event) {
    if (event.error && event.error.stack) {
        console.error('JavaScript Error captured:', {
            message: event.message,
            filename: event.filename,
            lineno: event.lineno,
            colno: event.colno,
            stack: event.error.stack
        });
    }
});

// OVERRIDE GLOBAL DE CONFIRM PARA EVITAR ERRORES
window.confirm = function(message) {
    // Silencioso en producción
    return true;
};

window.alert = function(message) {
    // Silencioso en producción
};

// CATEGORÍAS
window.confirmDeleteCategory = function(categoryName) {
    // Silencioso para producción
    return true;
};

window.confirmSaveCategory = function() {
    // Silencioso para producción
    return true;
};

// PRODUCTOS
window.confirmDeleteProduct = function(productName) {
    // Silencioso para producción
    return true;
};

window.confirmSaveProduct = function() {
    // Silencioso para producción
    return true;
};

// CLIENTES
window.confirmDeleteClient = function(clientName) {
    // Silencioso para producción
    return true;
};

window.confirmSaveClient = function() {
    // Silencioso para producción
    return true;
};

// PROVEEDORES
window.confirmDeleteProvider = function(providerName) {
    // Silencioso para producción
    return true;
};

window.confirmSaveProvider = function() {
    // Silencioso para producción
    return true;
};

// LOCALES
window.confirmDeleteLocal = function(localName) {
    // Silencioso para producción
    return true;
};

window.confirmSaveLocal = function() {
    // Silencioso para producción
    return true;
};

// RUTAS
window.confirmDeleteRoute = function(routeId) {
    // Silencioso para producción
    return true;
};

window.confirmSaveRoute = function() {
    // Silencioso para producción
    return true;
};

// USUARIOS
window.confirmDeleteUser = function(userName) {
    // Silencioso para producción
    return true;
};

window.confirmSaveUser = function() {
    // Silencioso para producción
    return true;
};

// SUBCATEGORÍAS
window.confirmDeleteSubcategory = function(subcategoryName) {
    // Silencioso para producción
    return true;
};

window.confirmSaveSubcategory = function() {
    // Silencioso para producción
    return true;
};

// FUNCIÓN GENÉRICA - VERSIÓN SILENCIOSA
window.confirmAction = function(action, itemName = '') {
    // Silencioso para producción - solo devolver true
    return true;
};

// FUNCIONES ADICIONALES PARA EVITAR ERRORES
window.limpiarFiltros = function() {
    try {
        const form = document.getElementById('filterForm');
        if (form) {
            form.reset();
            window.location.href = '/RMIE/app/controllers/ProductController.php?accion=index';
        } else {
            // filterForm no encontrado - modo silencioso
            return;
        }
    } catch (e) {
        console.error('Error in limpiarFiltros:', e);
    }
};

window.toggleProductsView = function(view) {
    try {
        const cardsView = document.getElementById('cardsView');
        const tableView = document.getElementById('tableView');
        const btnCards = document.getElementById('btnCards');
        const btnTable = document.getElementById('btnTable');
        
        if (!cardsView || !tableView || !btnCards || !btnTable) {
            // Elementos de vista no encontrados - modo silencioso
            return;
        }
        
        if (view === 'cards') {
            cardsView.style.display = 'grid';
            tableView.style.display = 'none';
            btnCards.classList.add('active');
            btnTable.classList.remove('active');
            localStorage.setItem('productosView', 'cards');
        } else {
            cardsView.style.display = 'none';
            tableView.style.display = 'block';
            btnCards.classList.remove('active');
            btnTable.classList.add('active');
            localStorage.setItem('productosView', 'table');
        }
    } catch (e) {
        console.error('Error in toggleProductsView:', e);
    }
};

window.limpiarProductos = function() {
    try {
        const opcion = prompt(`¿Qué tipo de limpieza quieres hacer en PRODUCTOS?\n\nEscribe el número de tu opción:\n\n1 - Solo eliminar productos con STOCK CERO\n2 - Eliminar productos INACTIVOS\n3 - Eliminar TODOS los productos\n4 - Cancelar`);
        
        if (opcion === '1') {
            if (confirmAction('limpiar registros')) {
                window.location.href = '/RMIE/app/controllers/ProductController.php?accion=clean_no_stock';
            }
        } else if (opcion === '2') {
            if (confirmAction('limpiar registros')) {
                window.location.href = '/RMIE/app/controllers/ProductController.php?accion=clean_inactive';
            }
        } else if (opcion === '3') {
            if (confirmAction('eliminar todos')) {
                window.location.href = '/RMIE/app/controllers/ProductController.php?accion=clean_all';
            }
        }
    } catch (e) {
        console.error('Error in limpiarProductos:', e);
    }
};

// INICIALIZACIÓN SEGURA
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Restaurar vista guardada
        const savedView = localStorage.getItem('productosView') || 'cards';
        if (typeof toggleProductsView === 'function') {
            toggleProductsView(savedView);
        }
        
        // Auto-hide alerts después de 5 segundos
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert && alert.style) {
                    alert.style.transition = 'opacity 0.5s, transform 0.5s';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        if (alert && alert.parentNode) {
                            alert.remove();
                        }
                    }, 500);
                }
            });
        }, 5000);
        
    } catch (e) {
        console.error('Error in DOMContentLoaded:', e);
    }
});

// selenium-messages.js v2.0 loaded - modo silencioso