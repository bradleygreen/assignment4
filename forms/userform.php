<html>
	<head>
		<title>New Account</title>
		<link rel="stylesheet" type="text/css" href="stylesheet.css"/>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="http://malsup.github.com/jquery.form.js"></script>
		<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
		<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
		<script src="formvalidation.js" type="text/javascript"></script>
	</head>
	<body>
		<div class="page">
		 
			<h1>Create User Account</h1>
			<form method="post" id="signupForm" />
			      	<fieldset>
					<h2 class="Emailandpassword">Email and Password</h2>
			
					<div class="fields">
						<p class="row">
							<label for="email">Email</label>
							<input type="text" id="email" name="email" class="field-large" placeholder="name@email.com"/><p><em>*Please enter your email address for user name.</em></p>
					
						</p>
						<p class="row">
							<label for="password">Password</label>
							<input type="password" id="password" name="password" />	
					
						</p>
						<p class="row">
							<label for="confirmPassword">Confirm Password</label>
							<input type="password" id="confirmPassword" name="confirmPassword" />	
						</p>		
					</div>
					 
					<input type="submit" value="Create Account" class="btn" />
				 ?>
			</form>	
		</div>
	</body>                                                    
</html>