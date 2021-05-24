<?php
    require_once 'php/connection.php';
    require_once 'php/sessionReserve.php';

    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['submit'])){
        $nomeProdotto = mysqli_real_escape_string($con, $_POST["nomeProd"]);
        $tipologia = $_POST['tipologia'];
        $costoperunita = mysqli_real_escape_string($con, $_POST["costoPerUnita"]);
        $descrizione = mysqli_real_escape_string($con, $_POST["descrizione"]);
        $link = $_POST["image"];
        $nomeTradotto = mysqli_real_escape_string($con, $_POST["nomeTrad"]);

        $sqlNewItem = "CALL nuovoProdotto('$nomeProdotto', '$tipologia', '$costoperunita', '$descrizione', '$link', '$nomeTradotto')";
        $resultNewItem = mysqli_query($con, $sqlNewItem);
        if(!$resultJob){
            $message = 'Errore compilazione campi';
            echo "<script type='text/javascript'>alert('$message');window.location.href='newItem.php';</script>";
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
                <strong>Nuovo Prodotto</strong>
            </h1>    
        </header>

        <section class='type-b'>
            <div class="trattino"></div>

            <div class="module">
                <div class="col">
                    <form action='' method='post'>
                        <div class="form-group">
                            <label>Nome prodotto</label>
                            <input type="text" name="nomeProd" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Tipologia</label>
                            <select id='tipologia' name='tipologia' required>
                                <option value="cereale">Cereale</option>
                                <option value='latticino'>Latticino</option>
                            <select>
                        </div>
                        <div class="form-group">
                            <label>Costo per unità</label>
                            <input type="text" name="costoPerUnita" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Descrizione </br>(max 255 caratteri)</label>
                            <input type="text" name="descrizione" class="form-control" maxlength="255" required>
                        </div>
                        <div class="form-group">
                            <label>Link immagine</label>
                            <input type="url" name="image" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Nome tradotto</label>
                            <input type="text" name="nomeTrad" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn" value="Aggiungi prodotto">
                        </div>
                    </form>
                </div>
            </div>

            <div class="trattino"></div>
        </section>

        <!--Footer-->
        <?php include 'php/footer.php'; ?>
    </body>
</html>