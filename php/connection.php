<?php      
    $hostDB = "localhost";  
    $userDB = "root";  
    $passwordDB = '';  
    $db_name = "esamefunzionante";  
          
    $con = mysqli_connect($hostDB, $userDB, $passwordDB, $db_name);  
    if(mysqli_connect_errno()) {  
        die("Failed to connect with MySQL: ". mysqli_connect_error());  
    }
?>  