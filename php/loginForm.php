<?php
    session_start();
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Air Train login</title>
		<link rel="stylesheet" type="text/css" href="../css/stylesheet.css"/>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="http://malsup.github.com/jquery.form.js"></script>
		<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
		<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
		<script src="../js/formvalidation.js" type="text/javascript"></script>
	</head>
	<body>
            <h1><center>Welcome to Air Train</center></h1>
		<div class="page">
		 
			<h3><em>Because we won't stop until we get you there!</em></h3>
			<form method="post" id="signupForm" />
			      	<fieldset>
					<h2 class="Emailandpassword">Login to Air Train</h2>
			
					<div class="fields">
						<p class="row">
							<label for="email">Email</label>
							<input type="text" id="email" name="email" class="field-large" placeholder="name@email.com"/><p><em>*Please enter your email address for user name.</em></p>
					
						</p>
						<p class="row">
							<label for="password">Password</label>
							<input type="password" id="password" name="password" />	
					
						</p>
					    </div>
					<input type="submit" value="Create Account" class="btn" />
                                </fieldset>
    			 </div>
	    </body>                                                    
</html>