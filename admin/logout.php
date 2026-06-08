// ========== admin/logout.php - Выход из админки ========== 
<?php
session_start();
session_destroy();
header('Location: login.php');
exit;
?>