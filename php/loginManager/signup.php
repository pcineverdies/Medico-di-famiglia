<?php

    require_once("./../dbUtility/queryManager.php");
    require_once("./../dbUtility/databaseManager.php"); //includes Database Class
    require_once("./sessionUtil.php"); //includes session utils

    //extract($_POST);
    insertUser();

    function insertUser(){
        if(!checkDouble()){
            header('location: ./../pages/signupFailPage.php');
            return;
        }
        if(checkValues()==-1){
            header('location: ./../pages/signupFailPage.php');
            return;
        }
        global $medicoDiFamigliaDB;
        $_POST['nome'] = $medicoDiFamigliaDB->sqlInjectionFilter($_POST['nome']);
		$_POST['cognome'] = $medicoDiFamigliaDB->sqlInjectionFilter($_POST['cognome']);
		$_POST['codiceFiscale'] = $medicoDiFamigliaDB->sqlInjectionFilter($_POST['codiceFiscale']);
		$_POST['email'] = $medicoDiFamigliaDB->sqlInjectionFilter($_POST['email']);
		$_POST['password'] = $medicoDiFamigliaDB->sqlInjectionFilter($_POST['password']);
        $_POST['telefono'] = $medicoDiFamigliaDB->sqlInjectionFilter($_POST['telefono']);
        
        $_POST['password']=password_hash($_POST['password'],PASSWORD_DEFAULT);

        $queryText = "INSERT INTO utente(nome,cognome,codiceFiscale,email,password,telefono,stato)VALUES ('{$_POST['nome']}','{$_POST['cognome']}','{$_POST['codiceFiscale']}','{$_POST['email']}','{$_POST['password']}','{$_POST['telefono']}','1');";
        $result = $medicoDiFamigliaDB->performQuery($queryText);
        $medicoDiFamigliaDB->closeConnection();
        if($result==true){
            header('location: ./../pages/signupSuccessPage.php');
        }
        else{
            header('location: ./../pages/signupFailPage.php');
        }
        

    }

    /*
        Il controllo del doppio si fa sulla email, andando a vedere che non ci siano già
        altri utenti con la medesima email
    */
    function checkDouble(){
        global $medicoDiFamigliaDB;
		$_POST['email'] = $medicoDiFamigliaDB->sqlInjectionFilter($_POST['email']);
        $queryText = "SELECT * FROM utente where email=\"{$_POST['email']}\";";
        $result = $medicoDiFamigliaDB->performQuery($queryText);
        $numRow = mysqli_num_rows($result);
        $medicoDiFamigliaDB->closeConnection();
		if ($numRow == 0)
            return true;
        return false;
    }

    /*
        Si verificano i diversi campi riportati
    */

    function checkValues(){
        if($_POST['nome']=="" || !preg_match("/^[A-Za-z ,.'-]+$/",$_POST['nome'])) return -1;
        echo "nomeok";
        if($_POST['cognome']=="" || !preg_match("/^[A-Za-z ,.'-]+$/",$_POST['cognome'])) return -1;
        echo "cognomeok";
        if($_POST['codiceFiscale']==""  || preg_match("/^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$/",$_POST['codiceFiscale'])==0) return -1;
        echo "codfisc ok";
        if($_POST['email']=="" || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) return -1;
        echo "emailok";
        if($_POST['password']!=$_POST['checkPassword'] || (strlen($_POST['password']))<8) return -1;
        echo "passok";
        return 1;
    }


?>