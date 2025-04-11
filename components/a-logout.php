<?php
// a-logout.php
session_start();
session_destroy();
header("Location: /pages/user/adminlogin.php");
exit;
?>
