<?php
    session_start();
    if(!isset($_SESSION['email'])||$_SESSION['stato']!=-1){
        echo"ACCESSO NEGATO";
        return;
    }
    require("./queryManager.php");
    $result = changeStatusDay($_GET['date']);
    echo $result;
    header("location: ./../pages/calendarPage.php")
?>