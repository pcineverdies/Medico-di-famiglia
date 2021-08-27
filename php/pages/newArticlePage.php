<!DOCTYPE html>
<?php 
    session_start();
    if($_SESSION['stato']!=-1) header("location: ./homePage.php");
?>
<html lang="it">
    <head>
        <title>Nuovo articolo</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./../../css/navbar_header.css">
        <link rel="stylesheet" href="./../../css/rightSide.css">
        <link rel="stylesheet" href="./../../css/leftSide.css">
        <link rel="stylesheet" href="./../../css/global.css">
        <link rel="icon" href="./../../img/favicon.ico" type="image/ico">
        <script src="./../../js/newArticle.js"></script>
    </head>
    <body onload=set()>
        <?php require("./../layout/navbar_header.php") ?>
        <div id="containerBody">
            <div id="leftSide">
            <div>
                    <?php
                    if (isset($_SESSION['errorNewArticle'])){
                        unset($_SESSION['errorNewArticle']);
                        echo "<div class=\"alert\"><p>Non è stato possibile pubblicare l'articolo!</p></div>";
                    }
                    if (isset($_SESSION['successNewArticle'])){
                        unset($_SESSION['successNewArticle']);
                        echo "<div class=\"alert\" id=\"successAlert\"><p>L'articolo è stato pubblicato con successo!</p></div>";
                    }
                    if (isset($_SESSION['errorUpdateArticle'])){
                        unset($_SESSION['errorUpdateArticle']);
                        echo "<div class=\"alert\"><p>Non è stato possibile modificare l'articolo!</p></div>";
                    }
                    if (isset($_SESSION['successUpdateArticle'])){
                        unset($_SESSION['successUpdateArticle']);
                        echo "<div class=\"alert\" id=\"successAlert\"><p>L'articolo è stato modificato con successo!</p></div>";
                    }
                    ?>
                    <div id="formContainer">
                        <div id="formTitle"><h2>Aggiungi o modifica un articolo</h2></div>
                        <form action="./../dbUtility/newArticle.php" method="POST">
                            <select name="tipo" id="selectArticle">
                                <option value="Nuovo articolo">Nuovo articolo</option>
                            </select>
                            <h3>Titolo</h3>
                            <textarea name="titolo" id="inputTitleArticle"required></textarea>
                            <h3>Contenuto</h3>
                            <textarea name="testo" id="inputTextArticle" required></textarea>
                            <input id="submitArticle" type="submit" value="Pubblica"></input>
                        </form>
                    </div>
                </div>

            </div>
            <div id="rightSide">
                <?php require("./../layout/rightSide.php") ?>
            </div>
        </div>
    </body>
</html>