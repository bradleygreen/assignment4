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
				$this->setSpecialNeeds($newSpecialNeeds);
        	}
			catch(Exception $exception)
			{
				//rethrow the exception to the caller, put the 0 in to get to $exception parameter
			 	echo "Cause: " . $exception->getMessage() . "<br />";
				var_dump($this);
				throw(new Exception("unable to build profile", 0, $exception));
			}	
		}	

// GETTER METHODS		
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
		/* static method to get user by email
		 * input: (pointer) to mysql
		 * input: (string) email to search by
		 * output: (object) user */
		public static function getProfileByUserId(&$mysqli, $userId)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT id, userId, firstName, lastName, birthday, specialNeeds FROM profile WHERE userId = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("i", $userId);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind paramenters."));
			}
			
			// ok, let's rock!
			if($statement->execute() === false)
			{
				throw(new Exception("Unable to execute the statement."));
			}
			
			// get the result and make a new object
			$result = $statement->get_result();
			if($result === false || $result->num_rows !== 1)
			{
				throw(new Exception("Unable to determine user: id not found."));
			}
			
			// get the row and set the id
			$row = $result->fetch_assoc();
			$profile = new Profile($row["id"], $row["userId"], $row["firstName"], $row["lastName"], $row["birthday"], $row["specialNeeds"]);
			
			$statement->close();
			
			return($profile);
		}
		
		/* static method to get user by id
		 * input: (pointer) to mysql
		 * input: (string) id to search by
		 * output: (object) user */
		public static function getProfileById(&$mysqli, $id)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT id, userId, firstName, lastName, birthday, specialNeeds FROM profile WHERE id = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("i", $id);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind paramenters."));
			}
			
			// ok, let's rock!
			if($statement->execute() === false)
			{
				throw(new Exception("Unable to execute the statement."));
			}
			
			// get the result and make a new object
			$result = $statement->get_result();
			if($result === false || $result->num_rows !== 1)
			{
				throw(new Exception("Unable to determine user: id not found."));
			}
			
			// get the row and set the id
			$row = $result->fetch_assoc();
			$profile = new Profile($row["id"], $row["userId"], $row["firstName"], $row["lastName"], $row["birthday"], $row["specialNeeds"]);
			
			$statement->close();
			
			return($profile);
		}
		
// SETTER METHODS
		/* mutator method for id
		* UNSIGNED NOT NULL AUTO_INCREMENT,
		* input: (integer) new id
		* output: N/A
		* throws: invalid input detected */ 
		public function setId($newId)
		{	
			// trim function cuts off spaces surrounding the input (sanitization1)
			$newId = trim($newId);
			//is_numeric is a sys func that will test if date coming in is a # 
			//it will return true for any numbers coming in, strings w/numbers will return true (sanitization2)
			if(is_numeric($newId) == false)
			{
				throw(new Exception("Invalid profile id detected: $newId"));
			}
			// convert the ID to an integer (sanitization3)
			$newId = intval($newId);			
			//throw out negative IDs except -1, which is our "new" profile
			if($newId < -1)
			{
				throw(new Exception("Invalid profile id detected: $newId"));			
			}
			// It's clean! 
			$this->id = $newId;
		}
		/* mutator method for userId
		 * input: (integer) new userId INT UNSIGNED NOT NULL,
		 * output: N/A
		 * throws: invalid input detected */ 
		public function setUserId ($newUserId)
		{
			// trim off leading and trailing spaces (sanitization1)
			$newUserId = trim($newUserId);
			// ensure the data is a number (sanitization2)
			if(is_numeric($newUserId) == false)
			{
				throw(new Exception("Invalid user id detected: $userId"));
			} 
			// convert the data to an integer (sanitization3)
			$newUserId = intval($newUserId);
			// throw out negative numbers (sanitization4)
			if($newUserId < -1)
			{
				throw(new Exception("Invalid user id detected: $userId is negative!"));
			}
			// now that the data is clean-ish (haven't checked it against database) assign the new UserId
			$this->userId = $newUserId;
		}
		/* mutator method for firstName
	     * input: (string) new firstName VARCHAR(32) NOT NULL,
	     * output: N/A
	     * throws: invalid input detected */
		public function setFirstName ($newFirstName)
		{
			// trim off leading and trailing spaces (sanitization1)
			$newFirstName = trim($newFirstName);
			// run by a regular expression (sanitization2)
			$regexp = "/^[A-Za-z]+$/";
			if(preg_match($regexp, $newFirstName) !==1)
			{
				throw(new Exception("Invalid first name detected: $newFirstName failed regular expression test."));
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
			// trim off leading and trailing spaces (sanitization1)
			$newLastName = trim($newLastName);
			// run by a regular expression (sanitization2)
			$regexp = "/^[A-Za-z]+$/";
			if(preg_match($regexp, $newLastName) !==1)
			{
				throw(new Exception("Invalid first name detected: $newLastName failed regular expression test."));
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
			// trim off leading and trailing spaces (sanitization1)
			$newBirthday = trim($newBirthday);
			// pass through a regular expression (sanitization2) this date regex was created by JoshG 04-15-14
			$regexp = "/^(\d{4})[-\/\.](0?[1-9]|1[0-2])[-\/\.](0?[1-9]?|[1-2]\d|3[01])$/";
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
		{	// SPECIAL NEEDS IS AN INT
			// trim just in case something got passed in with spaces (sanitization1)
			$specialNeeds = trim($specialNeeds);
			if(is_numeric($specialNeeds) == false) //(sanitization2)
			{
				throw(new Exception("Invalid special needs detected: $specialNeeds"));
			} 
			// convert the data to an integer (sanitization3)
			$specialNeeds = intval($specialNeeds);
			// throw out numbers that aren't 0 or 1(sanitization4)
			if($specialNeeds > 1 || $specialNeeds < 0)
			{
				throw(new Exception("Invalid specialNeeds detected: $specialNeeds"));
			}
			// now that the data is clean-ish (haven't checked it against database) assign the new UserId
			$this->specialNeeds = $specialNeeds;
			
		}
// CLASS METHODS 
		/* THIS IS THE INSERT METHOD
		 * inserts a new object into mySQL
		 * input: (pointer) mySQL connection, by reference 
		 * output: N/A
		 * throws: if the object could not be inserted */
		//& means by reference not by value 
		public function insert(&$mysqli)
		{	//handle degenerate cases 
		 	//this verifies that this is an object as another security measurement and
		 	//if we run into errors we know where it comes from 
		 	if(is_object($mysqli) === false    ||   get_class($mysqli) !== "mysqli")
		 	{
		 		throw(new Exception("Non mySQL pointer detected"));
		 	}
			// verify the id is -1 (i.e., a new profile) if not, it isn't a NEW user, can't be INSERTED
			if($this->id !== -1)
			{	//throws an exception if not a new profile
				var_dump($this);
				throw(new Exception("Non new id detected"));
			}
			//create a query template 
			//? ? ? are place holders 
			$query = "INSERT INTO profile(userId, firstName, lastName, birthday, specialNeeds) VALUES(?, ?, ?, ?, ?)";
			// prepare the query statement
			// this gives us a statement object that we can manipulate and use 
			// you could prepare the statement for 40 users 
			$statement = $mysqli->prepare($query);		 		
			if($statement === false)
			{
				var_dump($this);
				throw(new Exception("Unable to prepare statement."));
			}
			//bind parameters takes the user input and puts it to the prepare statement 
			//bind parameters to the query template 
			//issss says there are one int, and four strings here 
			// wasclean stores the result of bind_param true for success, false for fail
			$wasClean = $statement->bind_param("isssi", $this->userId, $this->firstName, $this->lastName, $this->birthday, $this->specialNeeds);
			if($wasClean === false)
			{
				var_dump($this);
				throw(new Exception("Unable to bind parameters"));
			}
			// executes and checks if execution succeeded in one step
			if($statement->execute() === false)
			{
				var_dump($this);
				throw(new Exception("Unable to execute statement"));
			}
			//clean up the statement - statements take up a lot of memory to close it frees the memory
			$statement->close();
			
			//trash the statement   (our first statement is no longer valid so we have to make a new stmt
			//statement = null forces to get rid of statement 
			$statement = null;
			$query = "SELECT id FROM profile WHERE userId = ?";
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				var_dump($this);
				throw(new Exception("Unable to prepare statement"));
			}
			$wasClean = $statement->bind_param("i" , $this-> userId);
			if($wasClean === false)
			{
				var_dump($this);
				throw(new Exception("Unable to bind parameters"));
			}
			//OK, let's get to it:
			if($statement->execute() === false)
			{	
				var_dump($this);
				throw(new Exception("Unable to execute statement"));
			}
			//get the results $ make sure only 1 row is there 
			//we selected by email and we should only get one email so if more than 1 row back there is a problemo 
			$result = $statement->get_result();
			if($result === false || $result->num_rows !== 1)
			{
				var_dump($this);
				throw(new Exception("Unable to determine user id: invalid result set, or wrong number of rows"));
			}
			$row = $result->fetch_assoc();
			$newId = $row["id"];
			try
			{
				$this->setId($newId);
			}
			catch(Exception $exception)
			{	//rethrow if the id is bad
				throw(new Exception("Unable to determine user id", 0, $exception));
			}
			//clean up the statement 
			$statement ->close();
		}	
		 			
		/* DELETE 	 
		 * this gives the ability to be able to delete profiles 
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
				var_dump($this);
				throw(new Exception("New id detected"));
			}
			//create a query template
			$query = "DELETE FROM profile WHERE id = ?";
			//prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				var_dump($this);
				throw(new Exception("Unable to prepare statement"));
			}
			//bind parameters to the query template 
			// i is for integer 
			// when you bind you make a promise so saying "i" promising one integer &
			// then putting in the id 
			$wasClean = $statement->bind_param("i", $this->id);
			if($wasClean === false)
			{
				var_dump($this);
				throw(new Exception("Unable to bind parameters"));
			}
			//OK, let's get to it:
			if($statement->execute() === false)
			{
				var_dump($this);
				throw(new Exception("Unable to execute statement"));
			}
			//clean up the statement
			//statements take up a lot of memory to close it frees the memory
			$statement->close();
		}

		/* UPDATE a profile object in mySQL
		 * input: (pointer) mySQL connection, by reference 
		 * output: N/A 
		 * throws: if the object could not be updated */
		public function update(&$mysqli)
		{
			if(is_object($mysqli) === false   ||   get_class($mysqli) !== "mysqli")
			{
				var_dump($this);
				throw(new Exception("New id detected"));
			}
			//create a query template 
			$query = "UPDATE profile SET userId = ?, firstName = ?, lastName = ?, birthday = ?, specialNeeds = ? WHERE id = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				var_dump($this);
				throw(new Exception("Unable to prepare statement"));
			}
			//bind parameters 
			$wasClean = $statement->bind_param("isssii", $this->userId, $this->firstName, $this->lastName, $this->birthday, $this->specialNeeds, $this->id);
			if($wasClean === false)
			{
				var_dump($this);
				throw(new Exception("Unable to bind parameters"));
			}
		
			//OK, let's get to it:
			if($statement->execute() === false)
			{
				var_dump($this);
				throw(new Exception("Unable to execute statement"));
			}
			// clean up the statement
			$statement->close();
		}	    		    
	}
?>

 