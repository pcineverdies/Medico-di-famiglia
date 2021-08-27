<?php
    session_start();
    if(!isset($_SESSION['email'])){
        echo "ACCESSO NEGATO";
        return;
    }

    if(!is_numeric($_GET['start'])||!is_numeric($_GET['howMany'])) header("location: ./../pages/homePage.php");
    require("./queryManager.php");
    $result = getArticles($_GET['start'],$_GET['howMany']);
    echo json_encode($result);
?>