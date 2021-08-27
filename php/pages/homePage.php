<!DOCTYPE html>
<?php session_start()?>
<html lang="it">
    <head>
        <title>Il tuo medico di famiglia</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./../../css/navbar_header.css">
        <link rel="stylesheet" href="./../../css/rightSide.css">
        <link rel="stylesheet" href="./../../css/leftSide.css">
        <link rel="stylesheet" href="./../../css/global.css">
        <link rel="icon" href="./../../img/favicon.ico" type="image/ico">
    </head>
    <body>
        <?php require("./../layout/navbar_header.php") ?>
        <div id="containerBody">
            <div id="leftSide">
            <?php
            if(isset($_SESSION['email'])){?>
                        <script src="./../../js/displayArticles.js"></script>
                        <div id="articlesContainer">
                            <div id="buttonArticlesContainer">
                                <button onclick="showArticles(0)">Pagina Precedente</button>
                                <button onclick="showArticles(1)">Pagina Successiva</button>
                            </div>
                        </div>
                        <script>showArticles(2)</script>
            <?php } ?>
            </div>
            <div id="rightSide">
                <?php require("./../layout/rightSide.php") ?>
            </div>
        </div>
    </body>
</html>
