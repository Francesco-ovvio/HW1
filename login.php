<?php
    require_once 'php/connection.php';
    require_once 'php/session.php';
    $error = '';

    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['submit'])){
        $username = mysqli_real_escape_string($con, $_POST["username"]);
        $password = mysqli_real_escape_string($con, $_POST["password"]);
        $password_hash = md5($password);
    
        if(empty($username)){
            $error .= '<p class="error">Inserire username</p>';
        }
        if(empty($password)){
            $error .= '<p class="error">Inserire password</p>';
        }
        if(empty($error)){
            $sql = "SELECT * FROM utente U JOIN cliente C ON U.pIvaCliente = C.pIva WHERE U.username='$username' AND U.password='$password_hash'";
            $result = mysqli_query($con, $sql);
            $countRow = mysqli_num_rows($result);
            if($countRow == 1){
                session_start();
                $_SESSION['username'] = $username;
                $row = mysqli_fetch_assoc($result);
                $_SESSION['privileges'] = $row['adminFlag'];
                $_SESSION['piva'] = $row['pIvaCliente'];
                $_SESSION['nome'] = $row['nome'];
                $_SESSION['cognome'] = $row['cognome'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['indirizzo'] = $row['indirizzo'];
                header("Location: /sitoHW1/index.php");
                exit;
            } else{
                $error .= '<p class"error">Username o password non valide</p>';
            }
        }
        mysqli_close($con);
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
                <strong>Login</strong>
            </h1>    
        </header>

        <section class="type-b">
            <div class="trattino"></div>
            <div class="module">
                <div class="col">
                    <p>Inserisci email e password</p>
                    <?php echo $error;?>
                    <form action="" method="post">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="username" name="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn" value="Accedi">
                        </div>
                        <p>Non hai un account?</br><a href="registration.php"> Registrati qui</a></p>
                    </form>
                </div>
            </div>
            <div class="trattino"></div>
        </section>

        <?php include 'php/footer.php'; ?>
    </body>
</html>