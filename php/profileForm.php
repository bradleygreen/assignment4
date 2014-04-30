 <?php
       //start session
       session_start();
       session_start();
       require_once("../user.php");
       require_once("../profile.php");
       require_once("../flight.php");
       require_once("../ticket.php");
       if(!isset($_SESSION["id"]))
	   {
		header("location: loginForm.php");
	   }
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{			
	    $mysqli = new mysqli("localhost", "airline_kirstene", "1CODingF\$\$L",  "airline_kirstene");
	}
	catch(mysqli_sql_exception $exception)
	{
	    echo "Unable to connect to mySQL: " . $exception->getMessage();
	}
	$id = intval($_SESSION["id"]);
	$user = User::getUserById($mysqli, $id);
	$profile = Profile::getProfileByUserId($mysqli, $id);
	$mysqli->close();
	?>
	<!DOCTYPE html>
       <html>
	<head>
		<title>Air Train</title>
		<link rel="stylesheet" type="text/css" href="../stylesheet.css"/>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="http://malsup.github.com/jquery.form.js"></script>
		<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
		<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
		<script src="../profilevalidation.js" type="text/javascript"></script>
	</head>
	<body>
	      <div class="page">
		 
		     <h1><center>Air Train</center></h1>
		     <p><em><small>We don't stop until you're there!</small></em></p>
           <?php
	    $firstName = $profile->getFirstName();
	    $lastName = $profile->getLastName();
	    $bday = $profile->getBirthday();
	    $bday = explode("-", $bday);
	    $bday = "$bday[1]/$bday[2]/$bday[0]";
	    echo "Welcome $firstName $lastName!<br />";
	    echo "Birthdate: $bday";
	    ?>
		     <h2>Choose a destiation:</h2>
		     <form id="flight">
			 Origin: <select name="origin">
			 <option value="na">Origin</option>
			 <option value="ABQ">ABQ</option>
			 <option value="MSP">MSP</option>
			 <option value="LAX">LAX</option>
			 </select><br />
			 Destination: <select name="destination">
			 <option value="na">Destination</option>
			 <option value="ABQ">ABQ</option>
			 <option value="MSP">MSP</option>
			 <option value="LAX">LAX</option>
			 </select><br />
			 Day: <select name="day">
			 <option value="na">Day</option>
			 <option value="2014-05-14">May 14th</option>
			 <option value="2014-05-15">May 15th</option>
			 </select><br />
			 <button type="submit">Search</button><br />
	    </form>
        </body>
    </html>
 
