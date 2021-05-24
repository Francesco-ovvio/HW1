<?php
    require_once 'connection.php';

    $sql = mysqli_query($con, "SELECT TP.*, I.quantitaTot FROM tipoprodotto TP JOIN inventario I on I.tipoProd=TP.IDprodotto");
    $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
    
    exit (json_encode($result));
?>