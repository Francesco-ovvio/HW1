<?php 
    if(session_status() !== 2){
        session_start();
    }
    if(!empty($_SESSION["username"])) {
        echo '<h1>Benvenuto ' . $_SESSION["username"] . '</h1>
            <a class="button" href="php/logout.php">Logout</a>
            <a class="button" href="profile.php">Profilo</a>
            <a class="button" href="cart.php">Carrello</a>';
    } else {
        echo '<a class="button" href="login.php">Login</a>';
    }
?>