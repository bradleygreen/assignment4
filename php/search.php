<?php
    session_start();
    require_once("user.php");
    require_once("profile.php");
    require_once("flight.php");
	require_once("ticket.php");
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Air Train Search</title>
			<link rel="stylesheet" type="text/css" href="../css/stylesheet.css"/>
			<script src="../js/search.js" type="text/javascript"></script>
		</head>
		<body onLoad="searchLoad();">
            <h1><center>Welcome to Air Train</center></h1>
			<div class="page">
				<form id="searchBox" action="searchproc.php">
					<input id="searchInput" placeholder="Enter your ORIGIN here:" />
					<input id="searchButton" type="submit" value="Search" />
				</form>
			</div>
		</body>                                                    
	</html>