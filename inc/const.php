<?php
	function getHTML($html_name){
		$file = fopen("html/".$html_name, "r") or die("Unable to read HTML template")
		$html = fread($file, filesize("html/".$html_name));
		
		fclose($file);

		return $html;
	}	

	$not_found_html = getHTML("404.html");
	$invalid_key_html = getHTML("invalid_key.html");
	$banned_html = getHTML("banned.html");
	$db_error = getHTML("500.html");

	define("NOT_FOUND_HTML", $not_found_html);
	define("INVALID_KEY_HTML", $invalid_key_html);
	define("BANNED_HTML", $banned_html);
	define("DB_ERROR", $db_error);
	
?>
