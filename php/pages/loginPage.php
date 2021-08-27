<!DOCTYPE html>
<?php 
    session_start();
    if(isset($_SESSION['email'])){
        header('location: ./homePage.php');
    }
?>
<html lang="it">
    <head>
        <title>Accedi</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./../../css/navbar_header.css">
        <link rel="stylesheet" href="./../../css/global.css">
        <link rel="stylesheet" href="./../../css/rightSide.css">
        <link rel="stylesheet" href="./../../css/leftSide.css">
        <link rel="icon" href="./../../img/favicon.ico" type="image/ico">


    </head>
    <body>
        <?php require("./../layout/navbar_header.php");
        ?>
        <div id="containerBody">
            <div id="leftSide">
                <?php
                    if(isset($_SESSION['loginError'])){
                        echo "<div id=\"errorLogin\"><p>Non è stato possibile effettuare il login. La email inserita o la password potrebbero essere errati. Si prega di riprovare.</p></div>";
                        unset($_SESSION['loginError']);
                    }
                ?>
                <form action="./../loginManager/login.php" method="POST" id="login">
                        <h3>Email</h3>
                        <input type="text" name="email" placeholder="mario_rossi@example.com">
                        <h3>Password</h3>
                        <input type="password" name="password" placeholder="password">
                    <input type="submit" value="Accedi">
                </form>
            </div>
            <div id="rightSide">
            <?php 
                require("./../layout/rightSide.php");
            ?>
            </div>
        </div>
    </body>
</html>
