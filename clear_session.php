<?php
session_start();
unset($_SESSION['error']);
unset($_SESSION['success']);
session_destroy();
header('Location: /RMIE/index.php');
exit();
?>
