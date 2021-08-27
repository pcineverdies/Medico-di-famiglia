<!DOCTYPE html>
<?php 
    session_start();
    if(!isset($_SESSION['email'])){
        header('location: ./homePage.php');
    }
    if($_SESSION['stato']==-1) header("location: ./homePage.php");
?>
<html lang="it">
    <head>
        <title>Prenotazioni</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./../../css/navbar_header.css">
        <link rel="stylesheet" href="./../../css/global.css">
        <link rel="stylesheet" href="./../../css/rightSide.css">
        <link rel="stylesheet" href="./../../css/leftSide.css">
        <link rel="icon" href="./../../img/favicon.ico" type="image/ico">
        <script src="./../../js/examinationsManager.js"></script>
        <script></script>
    </head>
    <body onload="showExaminations(2)">
            <?php require("./../layout/navbar_header.php") ?>
            <div id="containerBody">
                <div id="leftSide">
                    <div id="examinationsContainer">
                        <div id="formTitle"><h2>Le tue prenotazioni</h2></div>
                    </div>
                </div>
                <div id="rightSide">
                    <?php 
                    require("./../layout/actions.php") ?>
                </div>
            </div>
        </body>
</html>
