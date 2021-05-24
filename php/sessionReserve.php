<?php
if(session_status() != 2){
    session_start();
}
if(empty($_SESSION["username"])){
    header("Location: login.php");
    exit;
}
?>