<?php

	class Profile
	{
		//profile variables 
		private $id;
		private $userId;
		private $firstName;
		private $lastName;
                private $birthday;
                private $specialNeeds;
	
	
	   /* constructor profile 
		* input: (integer) new Id
		* input: (integer) new userId
		* input: (string) new firstName
		* input: (string) new lastName
		* input: (string) new birthday
		* input: (string) new specialNeeds
		* output: N/A
		* throws: when invalid input detected */
		public function __construct($newId, $newUserId, $newFirstName, $newLastName, $newBirthday, $newSpecialNeeds)
		{
		
			try
			{
				$this->setId($newId);
				$this->setUserId($newUserId);
				$this->setFirstName($newFirstName);
				$this->setLastName($newLastName);
                                $this->setBirthday($newBirthday);                               
                                $this->set($newSpecialNeeds);
        		}
			catch(Exception $exception)
			{
				//rethrow the exception to the caller, put the 0 in to get to $exception parameter
			 	echo "Cause: " . $exception->getMessage() . "<br />";
				throw(new Exception("unable to build profile", 0, $exception));
			}	
		}	
	   
	       /* accessor method for id profile
		* input: N/A
		* output: (integer)id of the  profile*/
		public function getId()
		{
			return($this->id);
		}
	       /* accessor method for userId
		* input: N/A
		* output: (integer)userId of the profile*/
		public function getUserId()
		{
			return($this->userId);
		}
	       /* accessor method for firstName profile 
		* input: N/A
		* output: (string)first name of the user profile*/
		public function getFirstName()
		{
			return($this->firstName);
		}
	       /* accessor method for lastName profile
		* input: N/A
		* output: (string) lastName for profile*/
		public function getLastName()
		{
			return($this->lastName);
		}
               /* accessor method for birthday profile
		* input: N/A
		* output: (string)  birthday for profile*/
		public function getbirthday()
		{
			return($this->birthday);
		}
                 /* accessor method for specialNeeds profile
		* input: N/A
		* output: (string)  specialNeeds for profile*/
		public function getSpecialNeeds()
		{
			return($this->specialNeeds);
		}
                
                
                
                /* mutator method for id
                 * UNSIGNED NOT NULL AUTO_INCREMENT,
                 * input: (integer) new id
                 * output: N/A
                 * throws: invalid input detected */ 
                 public function setId ($newId)
		{	
			//is_numeric is a sys func that will test if date coming in is a # 
			//  it will return true for any numbers coming in, strings w/numbers will return true
			// then convert by using floatval for a double and inval for string to an integer 
			//throws out obviously bad IDs
			if(is_numeric($newId) == false)
			{
				throw(new Exception("Invalid profile id detected: $newId"));
			}
			// convert the ID to an integer
			$newId = intval($newId);
			
			//throw out negative IDS
			// except -1, which is our "new"user
			if($newId < -1)
			{
				throw(new Exception("Invalid profile id detected: $newId"));			
			}
			// It's clean! 
			$this->id = $newId;
		}
		/* mutator method for userId
                 * input: (integer) new userId
                 * INT UNSIGNED NOT NULL,
                 * output: N/A
                 * throws: invalid input detected */ 
                 public function setUserId ($newUserId)
		{
			//This throws exception if profile userId and user userId do not match
			if($userId !== $userId)
			{
				throw(new Exception("Invalid user Id detected: $newUserId"));
			}
		
			// It's clean! set value
			$this->id = $newId;
		}
	   /* mutator method for firstName
	    * input: (istring) new firstName
	    * input: VARCHAR(32) NOT NULL,
	    * output: N/A
	    * throws: invalid input detected */ 
		public function setFirstName ($newFirstName)
		{
			//first name 
			$regexp = "/^[A-Za-z]*$/";
			if(preg_match($regexp, $newFirstName) !==1)
			{
				throw(new Exception("Invalid first name detected: $newFirstName"));
			}
			
			// sanitized; assign the value 
			$this->firstName = $newFirstName;
			
		}
  
                
	   /* mutator method for lastName
	    * input: (string) new lastName
	    * input: VARCHAR (32) NOT NULL
	    * output: N/A
	    * throws: invalid input detected */ 
	    public function setLastName ($newLastName)
		{
			$regexp = "/^[A-Za-z]*$/";
			if(preg_match($regexp, $newLastName) !==1)
			{
				throw(new Exception("Invalid last name detected: $newLastName"));
			}
			
			// sanitized; assign the value 
			$this->lastName = $newLastName;
                }        
            /* mutator method for birthday
             * input (string/date) new birthday
             * output: N/A
             * throws: invalid input detected */
             public function setBirthday ($newBirthday)
		{
                        //regex matches 00/00/0000, 00/00/00, or w/our slashes
                        $regexp = "/^20\d\d-(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)-(0[1-9]|[1-2][0-9]|3[01])$/";
                        if(preg_match($regexp, $newBirthday) !==1)
                        {
                                throw(new Exception("Invalid birthday detected: $newBirthday"));
                        }
                       
                       
                        //sanitized; assign the value
                        $this->birthday = $newBirthday;
                }
                /* mutator method for specialNeeds
                 * input (boolean) new specialNeeds
                 * output N/A
                 * throws: invalid input detected */
                public function setSpecialNeeds ($specialNeeds)
                {
                    if($newSpecialNeeds !== 0 && $newSpecialNeeds !== 1)
                    {
                            throw(new Exception("Invalid special needs detected: $newSpecialNeeds"));
                    }
                        //sanitized; assign the value
                        $this->specialNeeds = $newSpecialNeeds;
                }
                
                
		/* THIS IS THE INSERT METHOD
		 * inserts a new object into mySQL
		 * input: (pointer) mySQL connection, by reference 
		 * output: N/A
		 * throws: if the object could not be insterted */
		 //& means by reference not by value 
		 public function insert(&$mysqli)
		 {
		 	//handle degenerate cases 
		 	//this verifies that this is an object as another security measurement and
		 	//if we run into errors we know where it comes from 
		 	if(is_object($mysqli) === false    ||   get_class($mysqli) !== "mysqli")
		 	{
		 		throw(new Exception("Non mySQL pointer detected"));
		 	}
		 		
			// verify the id is -1 (i.e., a new user)
			//checks to see if object is assigned to the right id -1
			// we check against -1 to say -1 is a special case 
			if($this->id !== -1)
			{
				//throws an exception if not a new profile
				throw(new Exception("Non new id detected"));
			}
			
			//create a query template 
			//? ? ? are place holders 
			$query = "INSERT INTO user (userId, firstName, lastName, birthday, specialNeeds) VALUES(?, ?, ?, ?, ?)";
			
			// prepare the query statement
			// this gives us a statement object that we can manipulate and use 
			// you could prepare the statement for 40 users 
			$statement = $mysqli->prepare($query);		 		
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			//bind parameters takes the user input and puts it to the prepare statment 
			//bind parameters to the query template 
			//sss says there are three strings here 
			//wasclean verifys that the email password salt are good 
			$wasClean = $statement->bind_param("issss", $this->userId, $this->firstName, $this->lastName, $this->birthday, $this->specialNeeds);
			
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind parameters"));
			}
			if($statement->execute() === false)
			{
				 
				throw(new Exception("Unable to execute statement"));
			}
								
			//clean up the statement
			//statements take up a lot of memory to close it frees the memory
			$statement->close();
			
			//trash the statement   (our first statement is no longer valid so we have to make a new stmt
			//statement = null forces to get rid of statement 
			$statement = null;
			$query = "SELECT id FROM user WHERE userId = ?";
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prerpare statement"));
			}
			$wasClean = $statement->bind_param("i" , $this-> userId);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind parameters"));
			}
			
			//OK, let's get to it:
			if($statement->execute() === false)
			{	
				throw(new Exception("Unable to execute statement"));
			}
			
			//get the results $ make sure only 1 row is there 
			//we selected by email and we should only get one email so if more than 1 row back
			// there is a problemo 
			$result = $statement->get_result();
			if($result === false || $result->num_rows !== 1)
			{
				var_dump($result);
				throw(new Exception("Unable to determine user id: invalid result set"));
			}
			// we do a while loop incase there is more than one row of data 
			$row = $result->fetch_assoc();
			$newId = $row["id"];
			try
			{
				$this->setId($newId);
			}
			catch(Exception $exception)
			{
				//rethrow if the id is bad
				throw(new Exception("Unable to determine user id", 0, $exception));
			}
			//clean up the statement 
			$statement ->close();
		}	
		 			
		/* DELETE 	 
		 * this gives the ability to be able to delete users 
		 * delete this object into mySQL
		 * input: (pointer) mySQL connection, by reference 
		 * output: n/a
		 * throws: if the object couldn't be inserted */
		 public function delete(&$mysqli)
		 {
			//handle degenerate cases
			if(is_object ($mysqli) === false  ||  get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected"));
			}
			//verify the id is not -1 (i.e., an existing user)
			if($this->id === -1)
			{
				throw(new Exception("New id detected"));
			}
			//create a query template
			$query = "DELETE FROM user WHERE id = ?";
			
			//prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement"));
			}
			
			//bind parameters to the query template 
			// i is for integer 
			// when you bind you make a promise so saying "i" promising one integer &
			// then putting in the id 
			$wasClean = $statement->bind_param("i", $this->id);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind parameters"));
			}
				
			//OK, let's get to it:
			if($statement->execute() === false)
			{	
				throw(new Exception("Unable to execute statement"));
			}
			//clean up the statement
			//statements take up a lot of memory to close it frees the memory
			$statement->close();
		}	    		    
	   
		/* UPDATE a user object in mySQL
		 * input: (pointer) mySQL connection, by reference 
		 * output: N/A 
		 * throws: if the object could not be updated */
		 public function update(&$mysqli)
		 {
			if(is_object($mysqli) === false   ||   get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("New id detected"));
			}		    
			
			//create a query template 
			$query = "UPDATE user SET userId = ?, firstName= ?, lastName= ? birthday= ?, specialNeeds, WHERE id = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement"));
			}
			//bind parameters 
			$wasClean = $statement->bind_param("issis", $this->userID, $this->firstName, $this->lastName, $this->birthday, $this->specialNeeds, $this->id);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind parameters"));
			}
		
			//OK, let's get to it:
			if($statement->execute() === false)
			{	
			 
				throw(new Exception("Unable to execute statement"));
			}
			// clean up the statement
			$statement->close();
		}	    		    
}
?>

 