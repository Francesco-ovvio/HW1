<?php
session_start();
$_SESSION['username'] = '';
session_destroy();
header("Location: /sitoHW1/index.php");
?>