<?php 
require_once 'php/sessionReserve.php'; 
require_once 'php/connection.php';

if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['submit'])){
    $cfDip = mysqli_real_escape_string($con, $_POST['cfDip']);
    $qtProd = mysqli_real_escape_string($con, $_POST['qtProd']);
    $idProd = mysqli_real_escape_string($con, $_POST['idProd']);

    $sqlJob = "CALL cambioProdotto('$cfDip','$qtProd','$idProd')";
    $resultJob = mysqli_query($con, $sqlJob);
    if(!$resultJob){
        $message = 'Errore compilazione campi';
        echo "<script type='text/javascript'>alert('$message');window.location.href='cambioProd.php';</script>";
        
    }else{
        header("Location: success.php");
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/mhw3.css">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Lora:ital@1&family=Open+Sans&display=swap" rel="stylesheet"> 
        <link rel="shortcut icon" href="https://i.imgur.com/an6fSnH.png">
        <script src="script/function.js" defer="true"></script>
        <script src="script/script_loadJobs.js" defer="true"></script>
        <script src="script/script_meteo.js" defer="true"></script>
        <title> - Fattoria Kent</title>
    </head>

    <body> 
        <header>
            <?php include 'php/navbar.php'; ?>

            <!--logo sull'immagine di header-->
            <div class="logo">
                <img src="https://i.imgur.com/nsxYyyC.png">
            </div>

            <h1>
                <strong>Registra produzione</strong></br>
            </h1>    
        </header>

        <section class='type-b'>
        <div class="trattino"></div>
            <h1>Tabelle riassuntive</h1>
            <div id='tabelle'>
                <div id='tabellaLavori'></div>
                <div id='tabellaProdotti'></div>
            </div>
            <h1>Nuova lavorazione</h1>
            <form action = '' class='inline' method='post'>
                <div class='form-group'>
                    <input type="text" name="cfDip" class="form-control" required>
                    <label>CF dipendente</label>
                </div>
                <div class='form-group'>
                    <input type="text" name="qtProd" class="form-control" required>
                    <label>Quantità prodotta</label>
                </div>
                <div class='form-group'>
                    <input type="text" name="idProd" class="form-control" required>
                    <label>ID Nuovo prodotto</label>
                </div>
                <div class='form-group'>
                <input type="submit" name="submit" class="btn" value="Inizia lavorazione">
                </div>
            </form>
        <div class="trattino"></div>
        </section>

        <?php include 'php/footer.php'; ?>
    </body>
</html>
