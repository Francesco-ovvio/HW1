<?php
require_once "php/connection.php";
if(session_status()==2){
    header("Location: index.php");
}
$error = '';

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
    $username = mysqli_real_escape_string($con, $_POST["username"]);
    $email = mysqli_real_escape_string($con, $_POST["email"]);
    $password = mysqli_real_escape_string($con, $_POST["password"]);
    $confPass = mysqli_real_escape_string($con, $_POST["confPass"]);
    $piva = mysqli_real_escape_string($con, $_POST["piva"]);
    $nome = mysqli_real_escape_string($con, $_POST["nome"]);
    $cognome = mysqli_real_escape_string($con, $_POST["cognome"]);
    $indirizzo = mysqli_real_escape_string($con, $_POST["indirizzo"]);
    $password_hash = md5($password);
    

    $sql = "SELECT * FROM utente WHERE username = '$username' OR pIvaCliente = '$piva'";
    $result = mysqli_query($con, $sql);
    $countRow = mysqli_num_rows($result);
    if($countRow == 1){
        $error .='<p class="error">Utente o Partita iva già presente.</p>';
    }else{
        if(strlen($password)<8){
            $error .= '<p class="error">La password deve avere almeno 8 caratteri.</p>';
        }
        if(empty($confPass)){
            $error .='<p class="error">Inserisci password di conferma.</p>';
        }else{
            if(empty($error) && ($password!=$confPass)){
                $error .= '<p class="error">Le password non coincidono.</p>';
            }
        }
        if(empty($piva) || empty($nome) || empty($cognome) || empty($indirizzo)){
            $error .= '<p class="error">Tutti i campi cliente sono obbligatori.</p>';
        }
        if(empty($error)){
            $ins1 = "INSERT INTO cliente (pIva, nome, cognome, indirizzo) values ('$piva', '$nome', '$cognome', '$indirizzo')";
            $resultins1 = mysqli_query($con, $ins1);
            if($resultins1){
                $ins2 = "INSERT INTO utente (username, password, email, pIvaCliente) values ('$username', '$password_hash', '$email', '$piva')";
                $resultins2 = mysqli_query($con, $ins2);
                if($resultins2){
                    $error .='<p class="error">Account creato con successo.</p>';
                }else{
                    $error .='<p class="error">Impossibile creare account.</p>';
                }
            }else{
                $error .= '<p class="error">Impossibile creare cliente.</p>';
            }
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
                <strong>Registrazione</strong>
            </h1>    
        </header>

        <section class="type-b">
            <div class="trattino"></div>
                <div class='module'>
                    <div class='col'>
                        <p>Compila i campi per creare il tuo account</p>
                        <?php echo $error;?>
                        <form action='' method='post'>
                            <div class='form-group'>
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class='form-group'>
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class='form-group'>
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class='form-group'>
                                <label>Conferma password</label>
                                <input type="password" name="confPass" class="form-control" required>
                            </div>
                            <div class='form-group'>
                                <label>Partita IVA</label>
                                <input type="text" name="piva" class="form-control" required>
                            </div>
                            <div class='form-group'>
                                <label>Nome</label>
                                <input type="text" name="nome" class="form-control" required>
                            </div>
                            <div class='form-group'>
                                <label>Cognome</label>
                                <input type="text" name="cognome" class="form-control" required>
                            </div>
                            <div class='form-group'>
                                <label>Indirizzo</label>
                                <input type="text" name="indirizzo" class="form-control" required>
                            </div>
                            <div class='form-group'>
                                <input type='submit' name='submit' class='btn btn-primary' value='Iscriviti'>
                            </div>
                            <p>Hai già un account?</br><a href="login.php">Logga qui</a></p>
                        </form>
                    </div>
                </div>
            <div class="trattino"></div>
        </section>

        <?php include 'php/footer.php'; ?>
    </body>
</html>