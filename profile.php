<?php
    require_once 'php/sessionReserve.php';
    require_once 'php/connection.php';

    if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['confPag'])){
        $idord = $_POST['orderID'];
        $sqlPaga = "CALL pagaOrdine('$idord')";
        $resultPaga = mysqli_query($con, $sqlPaga);
        header("Location: profile.php");
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
                <strong>Profilo</strong>
            </h1>    
        </header>
        
        
        <section class = 'type-b'>
            <div class='trattino'></div>

            <div class='profileinfo'>
                <p>Il tuo profilo</p>
                <h1>Partita IVA: <span id="userData"><?php echo $_SESSION['piva']; ?></span></h1>
                <h1>Nome: <span id="userData"><?php echo $_SESSION['nome']; ?></span></h1>
                <h1>Cognome: <span id="userData"><?php echo $_SESSION['cognome']; ?></span></h1>
                <h1>E-Mail: <span id="userData"><?php echo $_SESSION['email']; ?></span></h1>
                <h1>Indirizzo: <span id="userData"><?php echo $_SESSION['indirizzo']; ?></span></h1>
            </div>
            <?php 
                if($_SESSION['privileges'] == 1){
                    echo "<h1>
                            <a class='button' href='newItem.php'>Nuovo prodotto</a>
                            <a class='button' href='cambioProd.php'>Registra produzione</a>
                        </h1>";
                }
            ?>
            <div class='trattino'></div>
        </section>

        <section class='type-b'>
            <div class='trattino'></div>

            <div class='profileinfo' id='ordini'>
                <p>I tuoi ordini</p>
                <input id='searchBar' onkeyup='searchTable()' type='text'>
                <?php 
                    if($_SESSION['privileges']==0){
                        $cliente = $_SESSION['piva'];
                        $sqlC = mysqli_query($con, "SELECT IDordine, nomeProdotto, quantitaRichiesta, dataOrdine, costoTot, pagato FROM oldbuy WHERE IDcliente='$cliente'");
                        $result = mysqli_fetch_all($sqlC, MYSQLI_ASSOC);
                    }else{
                        $sqlA = mysqli_query($con, "SELECT IDordine, IDcliente, nomeProdotto, quantitaRichiesta, dataOrdine, costoTot, pagato FROM oldbuy");
                        $result = mysqli_fetch_all($sqlA, MYSQLI_ASSOC);
                    }
                    echo "<table id='cartTable'><tr id='tableHeader'><th>ID Ordine</th><th>Nome</th><th>Quantità</th><th>Data</th><th>Costo Totale</th><th>Evaso</th>";
                        if($_SESSION['privileges']==1){ echo "<th>ID Cliente</th>";}
                    echo "</tr>";
                        for($j = 0; $j<count($result); $j++){
                            echo "<tr>";
                                echo "<td>".$result[$j]["IDordine"]."</td>";
                                echo "<td>".$result[$j]["nomeProdotto"]."</td>";
                                echo "<td>".$result[$j]["quantitaRichiesta"]."</td>";
                                echo "<td>".$result[$j]["dataOrdine"]."</td>";
                                echo "<td>".$result[$j]["costoTot"]."</td>";
                                echo "<td>";
                                    if($result[$j]["pagato"]==1){
                                        echo "<img id='icon' src='https://i.imgur.com/agJPs4q.png'";
                                    }else{
                                        echo "<img id='icon' src='https://i.imgur.com/vtMUSVX.png'";
                                    }
                                echo "</td>";
                                if($_SESSION['privileges']==1){ 
                                    echo "<td>".$result[$j]['IDcliente']."</td>";
                                    if($result[$j]['pagato']==0){
                                        echo "<td><form id='formCart' method='post'><input type='submit' id='btnOrder' class='btn' name='confPag' value='Conferma pagamento'>";
                                        echo "<input type='hidden' name='orderID' value='".$result[$j]['IDordine']."'></td></form>";
                                    }
                                }
                            echo "</tr>";
                        }
                    echo "</table>";
                ?>
            </div>
                
            <div class='trattino'></div>
        </section>

        <!--Footer-->
        <?php include 'php/footer.php'; ?>
    </body>
</html>