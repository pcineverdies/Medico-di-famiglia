<?php

	/*
		Questo file contiene tutte le query necessarie per il funzionamento del sito.
		Si include poi una funzione per convertire gli oggetti mysqli in oggetti php
		facilmente manipolabili.
	*/
    require_once "databaseManager.php"; 
	 

	function checkUser($email){
		global $medicoDiFamigliaDB;
		$email = $medicoDiFamigliaDB->sqlInjectionFilter($email);
		$password = $medicoDiFamigliaDB->sqlInjectionFilter($password);
		$queryText = "SELECT * FROM utente where email=\"$email\";";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return  $result;

	}

	function deleteExamination($data,$user){
		global $medicoDiFamigliaDB;
		$data = $medicoDiFamigliaDB->sqlInjectionFilter($data);
		$user = $medicoDiFamigliaDB->sqlInjectionFilter($user);
		$queryText = 	"
						SELECT V.*, G.stato
						FROM visita V
							 INNER JOIN
							 giornoDisponibile G ON G.data = V.data
						WHERE V.codiceUtente = $user
							  AND V.data = \"$data\";
						";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		$result = fromSQLtoArray($result,'ASSOC');
		if (count($result)==0 || $result[0]['stato']==0){
			return false;
		}
		/*
			SE ho superato questa fase, allora posso procedere con l'eliminazione
		*/
		$queryText = 	"
						DELETE 
						FROM visita
						WHERE codiceUtente = $user
							  AND data = \"$data\";
						";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return $result;
	}

	/*
		Questa funzione serve per ricavare $howMany articoli a partire da $start ordinati dal più
		recente al più vecchio tramite la order by e la funzione limit
	*/
	function getArticles($start,$howMany){  
		global $medicoDiFamigliaDB;
		$start = $medicoDiFamigliaDB->sqlInjectionFilter($start);
		$howMany = $medicoDiFamigliaDB->sqlInjectionFilter($howMany);
		$queryText="";
		if($howMany==-1){
			$queryText = 	"
			SELECT * FROM `articolo` 
			ORDER BY timestampPubblicazione DESC
				";
		}
		else{
			$queryText = 	"
						SELECT * FROM `articolo` 
						ORDER BY timestampPubblicazione DESC
						LIMIT $start,$howMany
							";
		}
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return fromSQLtoArray($result,'ASSOC');
	}

	function getExaminations($start,$howMany,$codiceUtente){  
		global $medicoDiFamigliaDB;
		$start = $medicoDiFamigliaDB->sqlInjectionFilter($start);
		$howMany = $medicoDiFamigliaDB->sqlInjectionFilter($howMany);
		$codiceUtente = $medicoDiFamigliaDB->sqlInjectionFilter($codiceUtente);
		$queryText = 	"
							SELECT V.*, G.stato
							FROM visita V
								 INNER JOIN
								 giornoDisponibile G on G.data = V.data
							WHERE V.codiceUtente = $codiceUtente
							ORDER BY V.data DESC
							LIMIT $start,$howMany;

						";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return fromSQLtoArray($result,'ASSOC');
	}

	function getUser($codiceUtente){  
		global $medicoDiFamigliaDB;
		$codiceUtente = $medicoDiFamigliaDB->sqlInjectionFilter($codiceUtente);
		$queryText = "SELECT * FROM utente WHERE codiceUtente = $codiceUtente;";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return fromSQLtoArray($result,'ASSOC'); 
	}
	
	function fromSQLtoArray($SQLresult,$type){
		if($SQLresult==false) return [];
		if($type=='BOTH')return $SQLresult->fetch_all(MYSQLI_BOTH);
		else return $SQLresult->fetch_all(MYSQLI_ASSOC);
	}

	function getDatesExaminations(){
		global $medicoDiFamigliaDB;
		$queryText = 	"
							SELECT *
							FROM giornoDisponibile G
							WHERE G.data > CURRENT_DATE
							AND G.stato=1;
						";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return $result;
	}

	function getRangeAndUsedTime($data){
		global $medicoDiFamigliaDB;
		$data = $medicoDiFamigliaDB->sqlInjectionFilter($data);
		$queryText = 	"
							SELECT G.orarioInizio, G.orarioFine
							FROM giornoDisponibile G
							WHERE G.data = \"$data\"
							UNION ALL
							SELECT V.FasciaOraria, 1
							FROM visita V
							WHERE V.data = \"$data\";
						";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return $result;
	}

	function isFreeDate($data, $codiceUtente){
		global $medicoDiFamigliaDB;
		$data = $medicoDiFamigliaDB->sqlInjectionFilter($data);
		$codiceUtente = $medicoDiFamigliaDB->sqlInjectionFilter($codiceUtente);
		$queryText = 	"
							SELECT *
							FROM visita
							WHERE codiceUtente = $codiceUtente
								  AND data = \"$data\";
						";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return $result;
	}

	function verifyTime($data, $orario){
		global $medicoDiFamigliaDB;
		$data = $medicoDiFamigliaDB->sqlInjectionFilter($data);
		$orario = $medicoDiFamigliaDB->sqlInjectionFilter($orario);
		$queryText = 	"
							SELECT *
							FROM visita
							WHERE fasciaOraria = \"$orario\"
								  AND data = \"$data\";
						";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return $result;
	}

	function verifyRangeTime($data, $orario){
		global $medicoDiFamigliaDB;
		$data = $medicoDiFamigliaDB->sqlInjectionFilter($data);
		$orario = $medicoDiFamigliaDB->sqlInjectionFilter($orario);
		$queryText = 	"
							SELECT IF(\"$orario\">=orarioInizio AND \"$orario\"<orarioFine,0,1) AS result
							FROM visita
							WHERE data = \"$data\";
						";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return $result;
	}

	function enterExamination($data,$orario,$utente,$note){
		global $medicoDiFamigliaDB;
		$data = $medicoDiFamigliaDB->sqlInjectionFilter($data);
		$orario = $medicoDiFamigliaDB->sqlInjectionFilter($orario);
		$utente = $medicoDiFamigliaDB->sqlInjectionFilter($utente);
		$note = $medicoDiFamigliaDB->sqlInjectionFilter($note);
		$queryText = 	"
							INSERT INTO `visita`(`fasciaOraria`,`note`,`codiceUtente`,`data`)							
							VALUES(\"$orario\",\"$note\",$utente,\"$data\");
						";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return $result;
	}
	
	function enterArticle($titolo, $testo){
		global $medicoDiFamigliaDB;
		$titolo = $medicoDiFamigliaDB->sqlInjectionFilter($titolo);
		$testo = $medicoDiFamigliaDB->sqlInjectionFilter($testo);
		$queryText = 		"
						INSERT INTO `articolo`(`titolo`,`testo`,`timestampPubblicazione`,`autore`)
						VALUES(\"$titolo\",\"$testo\",CURRENT_TIMESTAMP,1);
							";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return $result;					
	}

	function updateArticle($titolo, $testo, $codiceArticolo){
		global $medicoDiFamigliaDB;
		$titolo = $medicoDiFamigliaDB->sqlInjectionFilter($titolo);
		$testo = $medicoDiFamigliaDB->sqlInjectionFilter($testo);
		$codiceArticolo = $medicoDiFamigliaDB->sqlInjectionFilter($codiceArticolo);
		$queryText  = 	"
					UPDATE articolo
					SET testo = \"$testo\",titolo = \"$titolo\",modificato=1
					WHERE codiceArticolo = $codiceArticolo;
						";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return $result;	

	}

	function getUsedDates($month,$year){
		global $medicoDiFamigliaDB;
		$month = $medicoDiFamigliaDB->sqlInjectionFilter($month);
		$year = $medicoDiFamigliaDB->sqlInjectionFilter($year);
		$queryText  = 	"
					SELECT *
					FROM giornoDisponibile G
					WHERE 	MONTH(G.data)=$month
							AND YEAR(G.data)=$year
							AND G.Data>=CURRENT_DATE;
						";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return $result;	
	}

	function getAppointments($day,$month,$year){
		global $medicoDiFamigliaDB;
		$month = $medicoDiFamigliaDB->sqlInjectionFilter($month);
		$year = $medicoDiFamigliaDB->sqlInjectionFilter($year);
		$day = $medicoDiFamigliaDB->sqlInjectionFilter($day);
		$queryText  = 	"
					SELECT G.stato, V.data, V.fasciaOraria, U.nome, U.cognome, U.codiceFiscale, V.note
					FROM visita V
						 INNER JOIN
						 utente U USING(codiceUtente)
						 INNER JOIN
						 giornoDisponibile G ON G.data=V.data
					WHERE 	DAY(V.Data) = $day
							AND MONTH(V.Data) = $month
							AND YEAR(V.Data) = $year
					ORDER BY V.fasciaOraria
						";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return $result;	
	}

	function changeStatusDay($date){
		global $medicoDiFamigliaDB;
		$date = $medicoDiFamigliaDB->sqlInjectionFilter($date);
		$queryText  = 	"
					CALL modificaGiornoDisponibile(\"$date\");
						";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return $result;	
	}

	function newDay($date,$start,$end){
		global $medicoDiFamigliaDB;
		$date = $medicoDiFamigliaDB->sqlInjectionFilter($date);
		$start = $medicoDiFamigliaDB->sqlInjectionFilter($start);
		$end = $medicoDiFamigliaDB->sqlInjectionFilter($end);
		$queryText  = 	"
					INSERT INTO giornoDisponibile
					VALUES(\"$date\",\"$start\",\"$end\",1);
						";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return $result;	
	}

	function getMessages($howMany,$mittente,$destinatario){
		global $medicoDiFamigliaDB;
		$howMany = $medicoDiFamigliaDB->sqlInjectionFilter($howMany);
		$mittente = $medicoDiFamigliaDB->sqlInjectionFilter($mittente);
		$queryText="";
		if($howMany=="all"){
					$queryText=	"
					SELECT *, $mittente AS codiceUtente
					FROM messaggio M
					WHERE 		(
									M.mittente = $mittente
									AND M.destinatario = $destinatario)
							OR 	(
									M.destinatario = $mittente 
									AND M.mittente = $destinatario
								)
					ORDER BY timestampInvio DESC;
						";
		}
		else{
			$queryText=	"
				SELECT *, $mittente AS codiceUtente
				FROM messaggio M
				WHERE 		(
								M.mittente = $mittente
								AND M.destinatario = $destinatario)
						OR 	(
								M.destinatario = $mittente 
								AND M.mittente = $destinatario
							)
				ORDER BY timestampInvio DESC
				LIMIT $howMany
			";
		}
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return $result;	
	}

	function readMessages($utente,$destinatario){
		global $medicoDiFamigliaDB;
		$utente = $medicoDiFamigliaDB->sqlInjectionFilter($utente);
		$destinatario = $medicoDiFamigliaDB->sqlInjectionFilter($destinatario);
		$queryText="    
						UPDATE messaggio M
						SET M.letto=1
						WHERE M.destinatario=$utente
							  AND M.mittente = $destinatario;
					";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return $result;	
	}

	function sendMessage($testo, $mittente,$destinatario){
		global $medicoDiFamigliaDB;
		$testo = $medicoDiFamigliaDB->sqlInjectionFilter($testo);
		$mittente = $medicoDiFamigliaDB->sqlInjectionFilter($mittente);
		$destinatario = $medicoDiFamigliaDB->sqlInjectionFilter($destinatario);
		$queryText = 		"
						INSERT INTO `messaggio`(`testo`,`timestampInvio`,`destinatario`,`mittente`)
						VALUES(\"$testo\",CURRENT_TIMESTAMP,\"$destinatario\",\"$mittente\");
							";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return $result;					
	}

	function getUsersAndUnread(){
		global $medicoDiFamigliaDB;
		$queryText = 		"
					SELECT U.codiceUtente, U.nome, U.cognome, U.codiceFiscale, (
												SELECT COUNT(*)
												FROM messaggio M
												WHERE 	M.letto=0
														AND M.mittente=U.codiceUtente
												) AS quantiNonLetti
					FROM utente U
					WHERE U.stato<>-1
					ORDER BY quantiNonLetti DESC
							";
		$result = $medicoDiFamigliaDB->performQuery($queryText);
		$medicoDiFamigliaDB->closeConnection();
		return fromSQLtoArray($result,'ASSOC');
	}
?>