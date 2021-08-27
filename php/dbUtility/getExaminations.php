<?php
    session_start();
    if(!isset($_SESSION['email'])){
        echo"ACCESSO NEGATO";
        return;
    }
    if(!is_numeric($_GET['start'])||!is_numeric($_GET['howMany'])) header("location: ./../pages/homePage.php");
    require("./queryManager.php");
    $result = getExaminations($_GET['start'],$_GET['howMany'],$_SESSION['codiceUtente']);
    if($result==false){
        echo"[]";
        return;
    }
    echo json_encode($result);
?>