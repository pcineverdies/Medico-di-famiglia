<?php
    session_start();
    if(!isset($_SESSION['email']) || $_SESSION['stato']!=-1) header("location: ./../pages/homePage.php"); 
    include ("./queryManager.php");
    if($_POST["tipo"]=="Nuovo articolo"){
        $result = enterArticle($_POST['titolo'],$_POST['testo']);
        if($result==false){
            $_SESSION['errorNewArticle']=true;
        }
        else{
            $_SESSION['successNewArticle']=true;
        }
        header("location: ./../pages/newArticlePage.php");
    }
    else{
        //Nella form che permette l'invio della richesta, il valore associato a $_POST['tipo]
        //è il codice dell'articolo
        $result = updateArticle($_POST['titolo'],$_POST['testo'],$_POST['tipo']);
        if($result==false){
            $_SESSION['errorUpdateArticle']=true;
        }
        else{
            $_SESSION['successUpdateArticle']=true;
        }
        header("location: ./../pages/newArticlePage.php");
    }
?>