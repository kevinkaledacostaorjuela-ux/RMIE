/**
 * Control de Debug para RMIE
 * Ejecuta estos comandos en la consola del navegador para controlar los logs
 */

// Para activar logs detallados temporalmente:
// debugRMIE.enabled = true; debugRMIE.verbose = true; console.log('Debug activado');

// Para desactivar todos los logs:
// debugRMIE.enabled = false; debugRMIE.verbose = false; console.log('Debug desactivado');

// Para solo mostrar errores críticos (modo por defecto):
// debugRMIE.enabled = false; debugRMIE.verbose = false; console.log('Solo errores críticos');

// Para probar una función específica:
// debugRMIE.testFunction('nombreDeLaFuncion');

// Para verificar un elemento específico:
// debugRMIE.checkElement('idDelElemento');

window.toggleDebugMode = function() {
    debugRMIE.enabled = !debugRMIE.enabled;
    debugRMIE.verbose = debugRMIE.enabled;
    console.log(`Debug ${debugRMIE.enabled ? 'ACTIVADO' : 'DESACTIVADO'}`);
};

// Función rápida para diagnosticar problemas
window.quickDiagnose = function() {
    const originalEnabled = debugRMIE.enabled;
    const originalVerbose = debugRMIE.verbose;
    
    debugRMIE.enabled = true;
    debugRMIE.verbose = true;
    
    console.log('=== DIAGNÓSTICO RÁPIDO ===');
    
    // Verificar funciones críticas
    ['confirmAction', 'toggleProductsView', 'limpiarFiltros', 'limpiarProductos'].forEach(func => {
        debugRMIE.testFunction(func);
    });
    
    // Verificar elementos críticos
    ['filterForm', 'cardsView', 'tableView', 'btnCards', 'btnTable'].forEach(id => {
        debugRMIE.checkElement(id);
    });
    
    console.log('=== FIN DIAGNÓSTICO ===');
    
    // Restaurar configuración original
    debugRMIE.enabled = originalEnabled;
    debugRMIE.verbose = originalVerbose;
};

console.log('Debug Control cargado. Usa toggleDebugMode() o quickDiagnose() en la consola.');