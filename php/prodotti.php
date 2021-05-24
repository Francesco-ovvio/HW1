<?php
    require_once 'connection.php';

    $sql = mysqli_query($con, "SELECT TP.IDprodotto, TP.nomeProdotto, TP.tipologia, I.quantitaTot, I.spazioDisp FROM tipoprodotto TP JOIN inventario I on TP.IDprodotto=I.tipoProd");
    $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
    
    exit (json_encode($result));
?>