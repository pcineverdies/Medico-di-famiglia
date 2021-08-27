<?php
	require_once "./../dbUtility/queryManager.php";
    require_once "./../dbUtility/databaseManager.php"; //includes Database Class
	require_once "./sessionUtil.php"; //includes session utils
 
	$email_user = $_POST['email'];
	$pass_user = $_POST['password'];

	$errorMessage = login();
	if($errorMessage === null){
		header('location: ./../pages/homePage.php');
	}
	else{
		session_start();
		$_SESSION['loginError'] = 1;
		header('location: ./../pages/loginPage.php');
		return;
	}


	function login(){   
		global $email_user;
		global $pass_user;
		if ($email_user != null && $pass_user != null){
			$user = authenticate();
    		if ($user!==-1){
				$user = $user[0];
    			session_start();
    			setSession($user['codiceFiscale'],$user['email'],$user['nome'],$user['cognome'],$user['stato'],$user['codiceUtente']);
    			return null;
    		}
    	} else
    		return 'Input vuoto';
    	
    	return 'Input non validi';
	}
	
	function authenticate (){ 
		global $email_user;
		global $pass_user; 
		$user = checkUser($email_user);
		if (mysqli_num_rows($user) != 1)
			return -1;
		$user = fromSQLtoArray($user,"ASSOC");
		if(!password_verify($pass_user,$user[0]['password'])) return -1;
		return $user;
	}

?>