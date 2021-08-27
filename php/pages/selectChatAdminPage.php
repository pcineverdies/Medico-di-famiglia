<!DOCTYPE html>
<?php 
    session_start(); 
    if(!isset($_SESSION['email'])){
        header('location: ./homePage.php');
    }
    if($_SESSION['stato']!=-1) header("location: ./homePage.php");
?>
<html lang="it">
    <head>
        <title>Utenti</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./../../css/navbar_header.css">
        <link rel="stylesheet" href="./../../css/global.css">
        <link rel="stylesheet" href="./../../css/rightSide.css">
        <link rel="stylesheet" href="./../../css/leftSide.css">
        <link rel="icon" href="./../../img/favicon.ico" type="image/ico">

        <script src="./../../js/selectChatAdmin.js"></script>
    </head>
    <body>
            <?php require("./../layout/navbar_header.php") ?>
            <div id="containerBody">
                <div id="leftSide">
                    <div id="usersContainer">
                    <?php include("./../dbUtility/getUsersAndUnread.php");?>
                    </div>
                </div>
                <div id="rightSide">
                    <?php 
                    require("./../layout/actionsAdmin.php") ?>
                </div>
            </div>
        </body>
</html>
