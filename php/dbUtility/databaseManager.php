<?php  
    require "dbConfig.php"; 					//Includo le informazioni per l'accesso al database
    $medicoDiFamigliaDB = new medicoDiFamigliaDBManger(); 	

	class medicoDiFamigliaDBManger {
		private $mysqli_conn = null;
	
		function medicoDiFamigliaDBManger(){
			$this->openConnection();
		}
    
    	function openConnection(){
    		if (!$this->isOpened()){
    			global $dbHostname;
    			global $dbUsername;
    			global $dbPassword;
    			global $dbName;
				
				//Gestione degli errori di connessione al database
    			$this->mysqli_conn = new mysqli($dbHostname, $dbUsername, $dbPassword);
				if ($this->mysqli_conn->connect_error) 
					die('Errore di connessione (' . $this->mysqli_conn->connect_errno . ') ' . $this->mysqli_conn->connect_error);

				$this->mysqli_conn->select_db($dbName) or
					die ('Non è stato possibile selezionare il database ' . mysqli_error());
				$this->mysqli_conn->set_charset("utf8");
			}
    	}
		
		//Le funzioni possono essere usate solo nel caso in cui il database sia stato aperto correttamente.
    	function isOpened(){
       		return ($this->mysqli_conn != null);
    	}

		//Ritorna il risultato di una query come oggetto mysqli
		function performQuery($queryText) {
			if (!$this->isOpened())
				$this->openConnection();
			return $this->mysqli_conn->query($queryText);
		}
		
		//Per evitare sql-injection
		function sqlInjectionFilter($parameter){
			if(!$this->isOpened())
				$this->openConnection();
				
			return $this->mysqli_conn->real_escape_string($parameter);
		}

		function closeConnection(){
			//Chiusura della connessione con il database
 	       	if($this->mysqli_conn !== null)
				$this->mysqli_conn->close();
			
			$this->mysqli_conn = null;
		}
	}

?>