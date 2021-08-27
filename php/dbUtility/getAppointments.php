<?php
    session_start();
    if(!isset($_SESSION['email'])||$_SESSION['stato']!=-1){
        echo"ACCESSO NEGATO";
        return;
    }
    require("./queryManager.php");
    $result = getAppointments($_GET['day'],$_GET['month'],$_GET['year']);
    if($result==false) echo"[]";
    echo json_encode(fromSQLtoArray($result,'ASSOC'));
?>