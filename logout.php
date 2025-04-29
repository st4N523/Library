<?php
session_start();

unset($_SESSION['Admin_ID']);
session_destroy();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");


header("Location: login.php");
exit();
?>