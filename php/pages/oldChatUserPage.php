<!DOCTYPE html>
<?php 
    session_start(); 
    if(!isset($_SESSION['email'])){
        header('location: ./homePage.php');
    }
    if($_SESSION['stato']==-1) header("location: ./homePage.php");
    $_SESSION['destinatario']=1;
?>
<html lang="it">
    <head>
        <title>Chat</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./../../css/navbar_header.css">
        <link rel="stylesheet" href="./../../css/global.css">
        <link rel="stylesheet" href="./../../css/rightSide.css">
        <link rel="stylesheet" href="./../../css/leftSide.css">
        <link rel="stylesheet" href="./../../css/chat.css">
        <link rel="icon" href="./../../img/favicon.ico" type="image/ico">
        <script src="./../../js/chat.js"></script>
    </head>
    <body onload="loadMessages()">
            <?php require("./../layout/navbar_header.php") ?>
            <div id="containerBody">
                <div id="leftSide">
                    <div id="chatContainer">
                        <div id="messagesContainer">

                        </div>
                        <div id="inputChatContainer">
                            <textarea name="testo" id="inputChat" required></textarea>
                            <button id="sendButton">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-return-left" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5z"/>
                                </svg>
                            </button>
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
