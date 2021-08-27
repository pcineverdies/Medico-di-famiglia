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
        <script src="./../../js/newExamination.js"></script>
    </head>
    <body onload="set()">
            <?php require("./../layout/navbar_header.php") ?>
            <div id="containerBody">
                <div id="leftSide">
                <div>
                    <?php
                    if (isset($_SESSION['errorNewExamination'])){
                        unset($_SESSION['errorNewExamination']);
                        echo "<div class=\"alert\"><p>Non è stato possibile effettuare la prenotazione richiesta. Si prega di riprovare, o di contattare l'amministratore del sito.</p></div>";
                    }
                    if (isset($_SESSION['errorNewExaminationAlreadyUsed'])){
                        unset($_SESSION['errorNewExaminationAlreadyUsed']);
                        echo "<div class=\"alert\"><p>Ha già prenotato una visita per la data scelta. Se vuole cambiare l'orario, si prega di eliminare la visita precedente nella pagina 'Prenotazioni' e di eseguirla nuovamente.</p></div>";
                    }
                    if (isset($_SESSION['successNewExamination'])){
                        unset($_SESSION['successNewExamination']);
                        echo "<div class=\"alert\" id=\"successAlert\"><p>La prenotazione della visita è andata a buon fine!</p></div>";
                    }
                    ?>
                    <div id="formContainer">
                    <div id="formTitle"><h2>Prenota una visita</h2></div>
                        <form action="./../dbUtility/newExamination.php" method="POST">
                            <h3>Scegli una data tra quelle disponibili:</h3>
                            <select id="data" name="data">
                                <option value="-"> - </option>
                                <?php include("./../dbUtility/getDatesExaminations.php");?>
                            </select>
                            <h3>Scegli un orario tra quelli disponibili:</h3>
                            <select id="orario" name="orario" disabled required>
                                <option value=""> - </option>
                            </select>
                            <h3>Note da aggiungere alla prenotazione:</h3>
                            <textarea name="note"></textarea>
                            <input type="submit" value="Invia"></input>
                        </form>
                    </div>
                </div>
                </div>
                <div id="rightSide">
                    <?php 
                    require("./../layout/actions.php") ?>
                </div>
            </div>
        </body>
</html>
