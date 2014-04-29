<?php
	class Flight
	{
		//state flight variables 
		private $id;
		private $flightNumber;
		private $origin;
		private $destination;	
		private $numberSeats;	
		private $departureTime;	
		
		/*constructor for a User object 
		 * input: (integer) new Id
		 * input: (integer) new flightNumber
		 * input: (string) new origin
		 * input: (string) new destination
		 * input: (integer) new numberSeats
		 * input: (string)  new departureTime
		 * ouput: N/A
		 * throws: when invalid input detected */
		public function __construct($newId, $newFlightNumber, $newOrigin, $newDestination, $newNumberSeats, $newDepartureTime)
		{
			try
			{
				$this->setId($newId);
				$this->setFlightNumber($newFlightNumber);
				$this->setOrigin($newOrigin);
				$this->setDestination($newDestination);
				$this->setNumberSeats($newNumberSeats);
				$this->setDepartureTime($newDepartureTime);	
			}					 
			catch(Exception $exception)
			{	//rethrow the exception to the caller, put the 0 in to get to $exception parameter
				//we want to determine where the problems are onclick will catch it here 
				//will rethrow to give as much info as possible
				echo $exception->getTraceAsString() . "<br />";
				echo "Cause: " . $exception->getMessage() . "<br />";
				throw(new Exception("unable to build flight", 0, $exception));
			}	
		}
// GETTER METHODS
		/* accessor method for id
		 * input: N/A
		 * output: (integer)id of the user */
		public function getId() 
		{
			return($this->id);
		}
		/* accessor method for id
		 * input: N/A
		 * output: (integer)id of the user */
		public function getFlightNumber()
		{
			return($this->flightNumber);
		}
		/* accessor method for origin
		 * input: N/A
		 * output: (string) origin of flight */
		public function getOrigin()
		{
			return($this->origin);
		}
		/* accessor method for destination
		 * input: N/A
		 * output:(string) destination of flight */
		public function getDestination()
		{
			return($this->destination);
		}
		/* accessor method for number of seats
		 * input: N/A
		 * output:(integer) number of seats */
		public function getNumberSeats()
		{
			return($this->numberSeats);
		}
		/* accessor method for date and time of flight
		 * input: N/A
		 * output:(string) date and time of flight */
		public function getDepartureTime()
		{
			return($this->departureTime);
		}
// SETTER METHODS
		/* mutator method for id
		 * input: (integer) new id
		 * output: N/A
		 * throws: invalid input detected */ 
		public function setId ($newId)
		{	//is_numeric is a sys func that will test if date coming in is a # 
			//  it will return true for any numbers coming in, strings w/numbers will return true
			// then convert by using floatval for a double and inval for string to an integer 
			//throws out obviously bad IDs
			if(is_numeric($newId) === false)
			{
				throw(new Exception("Invalid flight id detected: $newId"));
			}
			// convert the ID to an integer
			$newId = intval($newId);
			//throw out negative IDS
			// except -1, which is our "new" flight
			if($newId < -1)
			{
				throw(new Exception("Invalid flight id detected: $newId"));			
			}
			// sanitized; assign the value 
			$this->id = $newId;
		}
		/* mutator method for flightNumber
		 * flightNo MEDIUMINT UNSIGNED NOT NULL,
		 * input: you can specify a width up to 5 digits
		 * input: (integer) new flightNumber
		 * output: N/A
		 * throws: invalid input detected */ 
		public function setFlightNumber ($newFlightNumber)
		{
			$newFlightNumber = trim($newFlightNumber);
			if(is_numeric($newFlightNumber) === false)
			{
				throw(new Exception("invalid flight number detected: $newFlightNumber is not a number!"));
			}
			$newFlightNumber = intval($newFlightNumber);
			$regexp = "/^[\d]{1,8}$/";
			if(preg_match($regexp, $newFlightNumber) !==1)
			{
				throw(new Exception("Invalid flight number detected: $newFlightNumber"));
			}	
			// sanitized; assign the value 
			$this->flightNumber= $newFlightNumber; 
		}
		/* mutator method for origin
		 * input: origin CHAR(3) NOT NULL
		 * input: (string) new origin
		 * output: N/A
		 * throws: invalid input detected */ 
		public function setOrigin($newOrigin)
		{
			$regexp = "/^[a-zA-Z]{3}$/";
			if(preg_match($regexp, $newOrigin) !==1)
			{
				throw(new Exception("Invalid city detected: $newOrigin"));
			}			
			// sanitized; assign the value 
			$this->origin= $newOrigin;
		}
		/* mutator method for origin
		 * input: origin CHAR(3) NOT NULL
		 * input: (string) new origin
		 * output: N/A
		 * throws: invalid input detected */ 
		public function setDestination($newDestination)
		{
			$regexp = "/^[a-zA-Z]{3}$/";
			if(preg_match($regexp, $newDestination) !==1)
			{
				var_dump($this);
				throw(new Exception("Invalid destination detected: $newDestination"));
			}	
			// sanitized; assign the value 
			$this->destination= $newDestination;
		}
		/* mutator method for new number of seats
		 * input: (integer) new number of seats
		 * output: N/A
		 * throws: invalid input detected */ 
		public function setNumberSeats ($newNumberSeats)
		{
			if(is_numeric($newNumberSeats) == false)
			{
				throw(new Exception("Invalid number of seats: $newNumberSeats"));
			}
			// convert the ID to an integer
			$newNumberSeats = intval($newNumberSeats);	
			if($newNumberSeats < 3)
			{
				throw(new Exception("Invalid number of seats: $newNumberSeats"));			
			}
			// sanitized; assign the value 
			$this->numberSeats = $newNumberSeats;			
		}
		public function setDepartureTime($newDepartureTime)
		{ 	//regex matches 00/00/0000, 00/00/00, or w/our slashes
                        $regexp ="/^[\d]{4}-[\d]{2}-[\d]{2} [\d]{2}:[\d]{2}:[\d]{2}$/";
				if(preg_match($regexp, $newDepartureTime) !==1)
                                {
					throw(new Exception("Invalid departure time detected: $newDepartureTime"));
				}
			 //sanitized; assign the value
                        $this->departureTime = $newDepartureTime;
                }
		/* THIS IS THE INSERT METHOD
		 * inserts a new object into mySQL
		 * input: (pointer) mySQL connection, by reference 
		 * output: N/A
		 * throws: if the object could not be insterted 
		 * & means by reference not by value */
		 public function insert(&$mysqli)
		 {	//handle degenerate cases 
		 	//this verifies that this is an object as another security measurement and
		 	//if we run into errors we know where it comes from 
		 	if(is_object($mysqli) === false    ||   get_class($mysqli) !== "mysqli")
		 	{
		 		throw(new Exception("Non mySQL pointer detected"));
		 	}		
			// verify the id is -1 (i.e., a new flight)
			//checks to see if object is assigned to the right id -1
			// we check against -1 to say -1 is a special case 
			if($this->id !== -1)
			{
				//throws an exception if not a new user
				throw(new Exception("Non new id detected"));
			}
			//create a query template 
			//? ? ? are place holders 
			$query = "INSERT INTO flight (flightNo, origin, destination, noSeats, departureTime) VALUES( ?, ?, ?, ?, ?)";
			// prepare the query statement
			// this gives us a statement object that we can manipulate and use 
			// you could prepare the statement for 40 flights  
			$statement = $mysqli->prepare($query);		 		
			if($statement  ===  false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			//bind parameters takes the flight input and puts it to the prepare statment 
			//bind parameters to the query template 
			//ssssss says there are three strings here 
			//wasclean verifys that the email password salt are good 
			$wasClean = $statement->bind_param("issis", $this->flightNumber, $this->origin, $this->destination, $this->numberSeats, $this->departureTime);
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
			$statement = null;
			$query = "SELECT id FROM flight WHERE flightNo = ? AND origin = ? AND destination = ? AND noSeats = ?  AND departureTime = ?";
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement"));
			}
			$wasClean = $statement->bind_param("issis" , $this->flightNumber, $this->origin, $this->destination, $this->numberSeats, $this->departureTime);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind parameters"));
			}
			//OK, let's get to it:
			if($statement->execute() === false)
			{	
				throw(new Exception("Unable to execute statement"));
			}
			$result = $statement->get_result();
			if($result->num_rows > 1)
			{
				throw(new Exception("too many rows in result."));
			}
			if($result->num_rows < 1)
			{
				throw(new Exception("too few rows in result."));
			}
			if($result === false)
			{	
				throw(new Exception("Unable to determine flight id: invalid result set"));
			}
			$row = $result->fetch_assoc();
			$newId = $row["id"];
			try
			{
				$this->setId($newId);
			}
			catch(Exception $exception)
			{	//rethrow if the id is bad
				throw(new Exception("Unable to determine flight id", 0, $exception));
			}
			//clean up the statement 
			$statement ->close();
		}	
		 			
		/* DELETE 	 
		 * this gives the ability to be able to delete flights  
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
			$query = "DELETE FROM flight WHERE id = ?";	
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
			$query = "UPDATE flight SET flightNo = ?, origin = ?, destination = ?, noSeats = ?, departureTime = ? WHERE id = ?";
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement"));
			}
			//bind parameters this->departure
			$wasClean = $statement->bind_param("issisi", $this->flightNumber, $this->origin, $this->destination, $this->numberSeats, $this->departureTime, $this->id );
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