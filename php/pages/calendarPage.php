<!DOCTYPE html>
<?php 
    session_start();
    if($_SESSION['stato']!=-1) header("location: ./homePage.php");
?>
<html lang="it">
    <head>
        <title>Calendario</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./../../css/navbar_header.css">
        <link rel="stylesheet" href="./../../css/rightSide.css">
        <link rel="stylesheet" href="./../../css/leftSide.css">
        <link rel="stylesheet" href="./../../css/global.css">
        <link rel="stylesheet" href="./../../css/calendar.css">
        <link rel="icon" href="./../../img/favicon.ico" type="image/ico">
        <script src="./../../js/calendar.js"></script>
    </head>
    <body onload="set()">
        <?php require("./../layout/navbar_header.php") ?>
        <div id="containerBody">
            <div id="leftSide">
                <div id="calendarContainer">
                    <div id="calendarTitle"><h2></h2></div>
                    <table id="calendar">
                        <thead>
                            <th>L</th>
                            <th>M</th>
                            <th>M</th>
                            <th>G</th>
                            <th>V</th>
                            <th>S</th>
                            <th>D</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                        <div id="buttonsCalendarContainer">
                            <button onclick="prev()">Mese precedente</button>
                            <button onclick="next()">Mese successivo</button>
                        </div>
                </div>
                <div id="examinationsContainer">
                    <div id="examinationsTitle"><h2>Non hai ancora selezionato un giorno</h2></div>
                </div>
            </div>
            <div id="rightSide">
                <?php require("./../layout/rightSide.php") ?>
            </div>
        </div>
    </body>
</html>