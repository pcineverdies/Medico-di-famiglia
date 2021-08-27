<!DOCTYPE html>
<?php
        session_start();
        if(isset($_SESSION['email'])){
            header('location: ./homePage.php');
        }
?>
<html lang="it">
    <head>
        <title>Registrati</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./../../css/navbar_header.css">
        <link rel="stylesheet" href="./../../css/global.css">
        <link rel="stylesheet" href="./../../css/rightSide.css">
        <link rel="stylesheet" href="./../../css/leftSide.css">
        <link rel="icon" href="./../../img/favicon.ico" type="image/ico">

        <script src="./../../js/formCheck/signUpForm.js"></script>
    </head>
    <body >
        <?php require("./../layout/navbar_header.php");?>
        <div id="containerBody">
            <div id="leftSide">
            <form id="signUp" action="./../loginManager/signup.php" method="POST">
                <h3>Nome</h3>
                <input type="text" name="nome" required placeholder="Mario" pattern="^[A-Za-z ,.'-]+$" onblur="checkVal(this)">

                <h3>Cognome</h3>
                <input type="text" name="cognome" required placeholder="Rossi" pattern="^[A-Za-z ,.'-]+$" onblur="checkVal(this)">

                <h3>Codice Fiscale</h3>
                <input type="text" name="codiceFiscale" required placeholder="MRORSS65A19G842R" pattern="^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$"onblur="checkVal(this)">

                <h3>Email</h3>
                <input type="email" name="email" required placeholder="mario_rossi@example.com" onblur="checkVal(this)">

                <h3>Numero di telefono</h3>
                <input type="text" name="telefono" required placeholder="3289423583" pattern="[0-9]+" onblur="checkVal(this)">

                <h3>Password</h3>
                <input type="password" name="password" required placeholder="password" minlength="8" onblur="checkVal(this)">

                <h3>Conferma la password</h3>
                <input type="password" name="checkPassword" required placeholder="password" minlength="8" onblur="checkVal(this)">

                <input type="button" onclick="checkFormSignUp()" value="Registrati!">
        </form>
            </div>
            <div id="rightSide">
            <?php 
                require("./../layout/rightSide.php");
            ?>
            </div>
        </div>
    </body>
</html>
