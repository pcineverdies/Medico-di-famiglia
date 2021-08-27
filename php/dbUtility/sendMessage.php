<?php
    session_start();
    if(!isset($_SESSION['email'])){
        echo"ACCESSO NEGATO";
        return;
    }
    require("./queryManager.php");
    sendMessage($_GET['testo'],$_SESSION['codiceUtente'],$_SESSION['destinatario']);
?>