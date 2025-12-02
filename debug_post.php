<?php
// Script de debug para verificar el POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>POST Recibido:</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h2>GET Params:</h2>";
    echo "<pre>";
    print_r($_GET);
    echo "</pre>";
    
    echo "<h2>Request URI:</h2>";
    echo $_SERVER['REQUEST_URI'] ?? 'N/A';
    
} else {
    echo "<h2>Método: " . $_SERVER['REQUEST_METHOD'] . "</h2>";
    echo "No se recibió POST";
}
?>