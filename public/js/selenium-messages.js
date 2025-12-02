/**
 * Configuración de mensajes estándar para pruebas Selenium
 * Centraliza todos los mensajes de confirmación para consistencia
 */

// Configuración global de mensajes
window.SELENIUM_MESSAGES = {
    DELETE_CATEGORY: '¿Está seguro de eliminar la categoría \'{name}\'? ¿Esta acción no se puede deshacer',
    SAVE_CATEGORY: '¿Está seguro de guardar los cambios en esta categoría?',
    DELETE_ALL: '¿Está seguro de eliminar todas las categorías? Esta acción no se puede deshacer'
};

// Función helper para mensajes consistentes
window.confirmAction = function(messageType, categoryName = '') {
    let message = window.SELENIUM_MESSAGES[messageType];
    if (categoryName && message.includes('{name}')) {
        message = message.replace('{name}', categoryName);
    }
    return confirm(message);
};

// Función específica para eliminar categoría
window.confirmDeleteCategory = function(categoryName) {
    return window.confirmAction('DELETE_CATEGORY', categoryName);
};

// Función específica para guardar categoría  
window.confirmSaveCategory = function() {
    return window.confirmAction('SAVE_CATEGORY');
};