

<?php
	/*
		Funzioni per la gestione del login
	*/

	//Imposta una nuova sessione
	function setSession($codiceFiscale,$email, $nome, $cognome, $stato,$codiceUtente){
		$_SESSION['codiceFiscale'] = $codiceFiscale;
		$_SESSION['email']=$email;
		$_SESSION['nome'] = $nome;
		$_SESSION['cognome'] = $cognome;
		$_SESSION['stato'] = $stato;
		$_SESSION['codiceUtente']=$codiceUtente;
	}

	//isLogged: check if user has logged in and if true returns the user id
	function isLogged(){		
		if(isset($_SESSION['email']))
			return $_SESSION['email'];
		else
			return FALSE;
	}

?>