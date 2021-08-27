<!DOCTYPE html>
<?php

        session_start();
        if(isset($_SESSION['email'])){
            header('location: ./homePage.php');
        }
        header("Refresh:4;URL=./homePage.php");
?>
<html lang="it">
    <head>
        <title>Registrazione avvenuta con successo!</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./../../css/global.css">
        <link rel="stylesheet" href="./../../css/signupSuccessFail.css">
        <link rel="icon" href="./../../img/favicon.ico" type="image/ico">
    </head>
    <body>
        <div id="containerText">
            <section class="successSignup">
            La registrazione è avvenuta con successo! Entro 5 secondi, sarai reindirizzato alla homepage, dove potrai effettuare il login. Se ciò non avviene, si prema <a href="./homePage.php">qui</a>.
            </section>
        </div>
    </body>
</html>
