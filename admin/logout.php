<?php
// ========== admin/logout.php - Выход из админки ==========
session_start();
session_destroy();
header('Location: login.php');
exit;
