/**
 * Sistema simplificado sin confirmaciones para Selenium
 * Elimina todos los popups de confirmación para pruebas automáticas
 */

// CATEGORÍAS
window.confirmDeleteCategory = function(categoryName) {
    console.log('Eliminando categoría:', categoryName);
    return true;
};

window.confirmSaveCategory = function() {
    console.log('Guardando cambios de categoría');
    return true;
};

// PRODUCTOS
window.confirmDeleteProduct = function(productName) {
    console.log('Eliminando producto:', productName);
    return true;
};

window.confirmSaveProduct = function() {
    console.log('Guardando cambios de producto');
    return true;
};

// CLIENTES
window.confirmDeleteClient = function(clientName) {
    console.log('Eliminando cliente:', clientName);
    return true;
};

window.confirmSaveClient = function() {
    console.log('Guardando cambios de cliente');
    return true;
};

// PROVEEDORES
window.confirmDeleteProvider = function(providerName) {
    console.log('Eliminando proveedor:', providerName);
    return true;
};

window.confirmSaveProvider = function() {
    console.log('Guardando cambios de proveedor');
    return true;
};

// LOCALES
window.confirmDeleteLocal = function(localName) {
    console.log('Eliminando local:', localName);
    return true;
};

window.confirmSaveLocal = function() {
    console.log('Guardando cambios de local');
    return true;
};

// RUTAS
window.confirmDeleteRoute = function(routeId) {
    console.log('Eliminando ruta:', routeId);
    return true;
};

window.confirmSaveRoute = function() {
    console.log('Guardando cambios de ruta');
    return true;
};

// USUARIOS
window.confirmDeleteUser = function(userName) {
    console.log('Eliminando usuario:', userName);
    return true;
};

window.confirmSaveUser = function() {
    console.log('Guardando cambios de usuario');
    return true;
};

// SUBCATEGORÍAS
window.confirmDeleteSubcategory = function(subcategoryName) {
    console.log('Eliminando subcategoría:', subcategoryName);
    return true;
};

window.confirmSaveSubcategory = function() {
    console.log('Guardando cambios de subcategoría');
    return true;
};

// FUNCIÓN GENÉRICA
window.confirmAction = function(action, itemName = '') {
    console.log('Acción confirmada:', action, itemName);
    return true;
};