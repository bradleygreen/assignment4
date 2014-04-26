//validate the form using jQuery
$(document).ready(function()
{
	 
	//setup the form validation
	$("#signupForm").validate(
 	{
	 
		//rules dictate what is (in)valid
		rules:
		{
			 email:
			 {
			 	required: true,
			 	email: true	
			  },
			  password:
			  {
			  	required: true,
			  },
			  
			  confirmPassword:
			  {
			  	required: true, 
			  	equalTo: "#password"
			  }
			  
		  
		},
		
		//messages are what are displayed to the user 
		messages:
		{	
		
			email: "Please enter a valid Email",
			password: "Please enter a password",
			confirmPassword: 
			{
				//confirm password was empty
				required: "Please confirm a password",
				
				//password didn't match
				equalTo: "Passwords do not match"
			}
			
		}
	});
});

