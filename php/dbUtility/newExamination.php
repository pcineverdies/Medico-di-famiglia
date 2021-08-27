<?php
    session_start();
    if(!isset($_SESSION['email'])) header("./../pages/homePage.php");
    include ("./queryManager.php");
    //Se c'è già una visita in quella data, non inviare
    $regExpTime = "/([01]?[0-9]|2[0-3]):([03]?[0]|[14]?[5])/ ";
    if(!preg_match($regExpTime,$_POST['orario'])){
        $_SESSION['errorNewExamination']=true;
        header("location: ./../pages/newExaminationPage.php");
        return;
    }
    //verifico se l'utente non ha già una visita prenotata per quella data
    $resultCheck = isFreeDate($_POST['data'],$_SESSION['codiceUtente']);
    if(count(fromSQLtoArray($resultCheck,'ASSOC'))!=0){
        $_SESSION['errorNewExaminationAlreadyUsed']=true;
        header("location: ./../pages/newExaminationPage.php");
        return;
    }

    //verifico che non ci sia una visita in quella data e in quell'orario
    $resultCheck = verifyTime($_POST['data'],$_POST['orario']);
    if(count(fromSQLtoArray($resultCheck,'ASSOC'))!=0){
        $_SESSION['errorNewExamination']=true;
        header("location: ./../pages/newExaminationPage.php");
        return;
    }

    $resultCheck = verifyRangeTime($_POST['data'],$_POST['orario']);
    if((fromSQLtoArray($resultCheck,'ASSOC')[0]['result'])==1){
        $_SESSION['errorNewExamination']=true;
        header("location: ./../pages/newExaminationPage.php");
        return;
    }

    //invia
    $result = enterExamination($_POST['data'],$_POST['orario'],$_SESSION['codiceUtente'],$_POST['note']);
    if($result==false){
        $_SESSION['errorNewExamination']=true;
    }
    else{
        $_SESSION['successNewExamination']=true;
    }
    header("location: ./../pages/newExaminationPage.php");
?>