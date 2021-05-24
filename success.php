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
                <strong>Azione eseguita con successo</strong></br>
                <p><span id='timer'></span></p>
                <script type = "text/javascript">
                    var count = 5;
                    var redirect = "profile.php";
                    function countDown(){
                        var timer = document.getElementById("timer");
                        if(count>0){
                            count--;
                            timer.innerHTML = "Verrai reindirizzato alla home in "+count+" secondi";
                            setTimeout("countDown()", 1000);
                        }else{
                            window.location.href = redirect;
                        }
                    }
                countDown();
                </script>
            </h1>  
        </header>

        <?php include 'php/footer.php'; ?>
    </body>
</html>