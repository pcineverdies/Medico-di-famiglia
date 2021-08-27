<?php
    session_start();
    if(!isset($_SESSION['email'])){
        echo"ACCESSO NEGATO";
        return;
    }
    require("./queryManager.php");
    $result = getRangeAndUsedTime($_GET['data']);
    if($result==false) echo"[]";
    $result = fromSQLtoArray($result,'ASSOC');
    echo json_encode($result);
?>