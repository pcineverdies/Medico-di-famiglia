<?php
    session_start();
    unset($_SESSION['destinatario']);
    $_SESSION['destinatario']=$_GET['user'];
    header("location: ./../pages/chatPage.php");
?>