<?php
echo "<h2>Datos recibidos por POST:</h2>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

if (isset($_POST['clientes'])) {
    echo "<p><strong>Campo clientes es array:</strong> " . (is_array($_POST['clientes']) ? 'SÍ' : 'NO') . "</p>";
    echo "<p><strong>Valores:</strong> " . json_encode($_POST['clientes']) . "</p>";
}
?>