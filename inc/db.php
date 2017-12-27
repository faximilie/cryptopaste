<?php

	
	
	function generatePDO($db_host, $db_name, $db_user, $db_pass $db_char){

		$dsn = "mysql:host=$db_host;dbname=$db;charset=$db_char";

		$opt = [
		    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		    PDO::ATTR_EMULATE_PREPARES   => false,
		];

		$pdo = new PDO($dsn, $db_user, $db_pass, $opt);


		return $pdo;

	}
?>
