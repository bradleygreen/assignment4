<?php
    session_start();
    require_once("user.php");
    require_once("profile.php");
    require_once("flight.php");
	require_once("ticket.php");

	$requestedOrigin = $_POST["searchInput"];
	$userId = $_SESSION["userId"];

	
	

?>