<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	header('Content-Type: text/html');
	session_start();

	//end session
	$_SESSION = array();
	session_destroy();
	header("Location: gamenight.php", true);
	die();
?>