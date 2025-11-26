<?php
session_start();
echo "<h1>Información de Sesión</h1>";
echo "<p><strong>Usuario:</strong> " . ($_SESSION['user'] ?? 'No definido') . "</p>";
echo "<p><strong>Rol:</strong> " . ($_SESSION['rol'] ?? 'No definido') . "</p>";
echo "<p><strong>Puede eliminar:</strong> " . (($_SESSION['rol'] ?? '') !== 'coordinador' ? 'SÍ ✅' : 'NO ❌') . "</p>";
echo "<hr>";
echo "<h2>Sesión completa:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>
