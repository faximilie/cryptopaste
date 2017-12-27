<?php

	/*** BEGIN REQUIRES ***/
	require("../inc/config.php");
	require("../inc/db.php");
	require("../inc/security.php");
	require("../inc/const.php");
	/***  END REQUIRES  ***/


	/**
	  * Does an SQL Lookup to get the encrypted paste from the database
	  * @param $id - The ID of the paste
	  * @param $pdo - The SQL Connection used to perform this query
	  * @return Encrypted content of the paste
	 **/
	function getPaste($id, $pdo){


		$sql_statement = $pdo->prepare('SELECT * FROM pastes WHERE id = :id LIMIT 1');
		$sql_statement->execute(['id'=> $id]);

		$paste = $sql_statement->fetch(PDO::FETCH_ASSOC);


	}

	/**
	  * Updates the last view of the paste
	  * @param $id - the ID of the paste to update
	  * @param $time - The time to update it to
	  * @param $pdo - The SQL connection used to perform this query
	 **/
	function updatePasteLastView($id, $time){

		$sql_query = "UPDATE pastes SET last_view = :last_view WHERE id = :id";
		$sql_statement = $pdo->prepare($sql_query);
		$sql_statement->execute(["id" => $id, "last_view" => $time]);
	}

	if(! INSTALLED)
		die("Please install cryptopaste first");
		
	$pdo = generatePDO(DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_CHAR);

	if(is_null($pdo))
		die(DB_ERROR);

	if(isClientBanned())
		die(BANNED_HTML);

	if(isset($_GET["id"]) && isset($_GET["key"])){

		if(! validKey($key)){
			punishClient();
			die(INVALID_KEY_HTML);
		}

		$paste = getPaste($_GET["id"], $_GET["key"], $pdo);

		if(is_null($paste)){
			die(NOT_FOUND_HTML);
		}
		
		updatePasteLastAccess($id, time());

		$cipher_text = $paste["content"];
		$cipher_type = $paste["enc_type"];

		$plain_text = decryptPaste($cipher_text, $key, $cipher_type);

		// If the plain_text decrypted is null, the decryption was unsuccessful, and as a result, punish the client.
		if(is_null($plain_text)){
			punishClient();
			die(INVALID_KEY_HTML);
		}
	}

?>
