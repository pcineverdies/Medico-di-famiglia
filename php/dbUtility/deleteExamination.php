<?php
    session_start();
    if(!isset($_SESSION['email']) || $_SESSION['codiceUtente']!=$_GET['user'])header("./../pages/homePage.php");
    require("./queryManager.php");
    $result = deleteExamination($_GET['data'],$_GET['user']);
    if($result) echo "successo";
    else echo "ERRORE: si sta provando ad elimare una visita che non esiste o che è stata già eliminata";
?>