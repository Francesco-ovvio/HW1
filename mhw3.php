<?php
    require_once 'php/sessionReserve.php';
    require_once 'php/connection.php';

    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['submitCart'])){
        if(!isset($_SESSION['shopCart'])){
            $_SESSION['shopCart'] = array();
        }
        $nomeProd = $_POST['itemName'];
        for($i = 0; $i<count($_SESSION['shopCart']); $i++){
            if($_SESSION['shopCart'][$i][5] == $nomeProd){
                $err=$i;
            }
        }
        if(!isset($err)){
            /*controllare se è già nel carrello */
            /*Assegno un magazziniere a caso che si occuperà del singolo ordine di un prodotto*/
            $sqlM = "SELECT CF from dipendente where mansione='magazziniere' order by rand() limit 1";
            $resultM = mysqli_query($con, $sqlM);
            $rowM = mysqli_fetch_assoc($resultM);
            /*cerco il settore del magazzino in cui si trova l'oggetto ordinato*/
            $sqlS = "SELECT I.settoreDeposito, TP.costoPerUnita, TP.nomeProdotto FROM inventario I join tipoprodotto TP on I.tipoProd=TP.IDprodotto WHERE TP.nomeProdotto='$nomeProd'";
            $resultS = mysqli_query($con, $sqlS);
            $rowS = mysqli_fetch_assoc($resultS);
            $arrayProd = array();
            array_push($arrayProd, $_SESSION['piva'], $rowS['settoreDeposito'], $_POST['qty'], $rowM['CF'], $rowS['costoPerUnita'], $rowS['nomeProdotto']);
            array_push($_SESSION['shopCart'], $arrayProd);
        }else{
            $_SESSION['shopCart'][$err][2] = $_SESSION['shopCart'][$err][2]+$_POST['qty'];
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
        <script src="script/script_load2.js" defer="true"></script>
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
                <strong>I nostri prodotti</strong>
            </h1>    
        </header>

        <!--Catalogo + Favorite-->
        <section class="type-b" name="catalogo">
            <div class="trattino"></div>
            <div class="favorite" id="favSection" >
                <h1>Preferiti</h1>
                <div class="product-grid" id="prefProd">
                    
                    <!--Qui si caricano gli elementi quando viene premuto il pulsante +-->

                </div>
            </div>

            <div class="catalogo">
                <h1>Catalogo</h1>
                <input type="text" id="searchBar" onkeyup="search()" placeholder="Inserisci il nome del prodotto..">

                <div class="product-grid" id='product-grid'>

                <!--Qui si caricano gli elementi in modo dinamico-->

                </div>  

                <div class="trattino"></div>
            </div>
        </section>

        <!--Footer-->
        <?php include 'php/footer.php'; ?>
    </body>
</html>