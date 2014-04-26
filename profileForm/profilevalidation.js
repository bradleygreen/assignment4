//validate the form using jQuery
$(document).ready(function()
{
	 
	//setup the form validation
	$("#signupForm").validate(
 	{
	 
		//rules dictate what is (in)valid
		rules:
		{
			firstname:
			 {
			 	required: true,
			 	minlength: 2,
			 	pattern: /^([A-Za-z])*(\s+[A-Za-z])?$/
			  },
			lastname:
			  {
			  	required: true,
			  	pattern: /^([A-z\.\-])*$/
			  },
		},
		
		//messages are what are displayed to the user 
		messages:
		{	
			
			
			firstname: "Please enter a valid first name",
			lastname: "Please enter a valid last name",
			birthday: "Please enter a valid birth day",
			
		}
      });
});

