<?php 
    require_once 'php/sessionReserve.php';
    require_once 'php/connection.php';

    if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['remCart'])){
        $entry = $_POST['itemName'];
        unset($_SESSION['shopCart'][$entry]);
        $array = array_values($_SESSION['shopCart']);
        $_SESSION['shopCart'] = $array;
        header("Location: cart.php");
    }
    if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['paga'])){
        $error = 0;
        for($j = 0; $j<count($_SESSION['shopCart']); $j++){
            $piva = $_SESSION['piva'];
            $settDep = $_SESSION['shopCart'][$j][1];
            $qty = $_SESSION['shopCart'][$j][2];
            $dip = $_SESSION['shopCart'][$j][3];
            $sqlOrd = "call nuovoOrdine('$piva', '$settDep', '$qty', '$dip')";
            $resultOrd = mysqli_query($con, $sqlOrd);
            if(!$resultOrd){
                $error = 1;
            }
        }
        if($error == 1){
            $message = "Errore: Impossibile effettuare l'operazione";
            echo "<script type='text/javascript'>alert('$message');window.location.href='cart.php';</script>";
            
        }else{
            header("Location: thankyou.php");
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
                <strong>Carrello</strong>
            </h1>    
        </header>

        <section class='type-b'>
            <div class='trattino'></div>
            <div id='carrello'>
                <h1>Il tuo carrello</h1>
                <?php
                    if(empty($_SESSION['shopCart'])){
                        echo "<p class='error'>Il tuo carrello è vuoto</p>"; 
                    }else{
                        $totale = 0;
                        echo "<table id='cartTable'><tr><th>Nome Prodotto</th><th>Quantità</th><th>Costo totale</th></tr>";
                        for($i = 0; $i<count($_SESSION['shopCart']); $i++){
                            echo "<tr>";
                                echo "<td>".$_SESSION['shopCart'][$i][5]."</td>";
                                echo "<td>".$_SESSION['shopCart'][$i][2]."</td>";
                                echo "<td>".($_SESSION['shopCart'][$i][4] * $_SESSION['shopCart'][$i][2])." €</td>";
                                $totale = $totale+($_SESSION['shopCart'][$i][4] * $_SESSION['shopCart'][$i][2]);
                                echo "<td><form id='formCart' method='post'><input type='submit' id='btnOrder' class='btn' name='remCart' value='Rimuovi'>";
                                echo "<input type='hidden' name='itemName' value='".$i."'></td></form>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        echo "<h1>Totale: ".$totale." €</h1>";

                        echo "<form method='post'><input type='submit' id='btnOrder' class='btn' name='paga' value='Paga adesso'></form>";
                    }
                ?>
            </div>
            <div class='trattino'></div>
        </section>

        <?php include 'php/footer.php'; ?>
    </body>
</html>