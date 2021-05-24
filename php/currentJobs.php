<?php
    require_once 'connection.php';

    $sql = mysqli_query($con, "SELECT D.CF, D.nome, D.cognome, TP.IDprodotto, TP.nomeProdotto FROM dipendente D JOIN tipoprodotto TP on D.prodottoAttuale=TP.IDprodotto WHERE D.mansione !='magazziniere'");
    $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
    $currentJobs = json_encode($result);
    exit (json_encode($result));
?>