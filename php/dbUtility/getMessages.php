<?php
    session_start();
    if(!isset($_SESSION['email'])){
        echo"ACCESSO NEGATO";
        return;
    }
    require("./queryManager.php");
    $result = getMessages($_GET['howMany'],$_SESSION['codiceUtente'],$_SESSION['destinatario']);
    readMessages($_SESSION['codiceUtente'],$_SESSION['destinatario']);
    if($result==false) echo"[]";
    echo json_encode(fromSQLtoArray($result,'ASSOC'));
?>