<?php
    session_start();
    if(!isset($_SESSION['email'])||$_SESSION['stato']!=-1){
        echo"ACCESSO NEGATO";
        return;
    }
    $regExpTime = "/([01]?[0-9]|2[0-3]):0[0]/ ";
    if(!preg_match($regExpTime,$_GET['start']) || !preg_match($regExpTime,$_GET['end'])){
        header("location: ./../pages/calendarPage.php?Orario_inserito_non_corretto");
        return;
    }
    $start = substr($_GET['start'],0,2);
    $end = substr($_GET['end'],0,2);
    echo $start."<br>".$end;
    $start = (int)$start;
    $end = (int)$end;
    if($start>=$end){
        header("location: ./../pages/calendarPage.php?Orario_inserito_non_corretto");
        return;
    }
    require("./queryManager.php");
    $result = newDay($_GET['date'],$_GET['start'],$_GET['end']);
    echo $result;
    header("location: ./../pages/calendarPage.php")
    
?>