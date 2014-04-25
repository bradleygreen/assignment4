<?php
	class Ticket
	{
/*		flightId INT UNSIGNED NOT NULL,
		userId INT UNSIGNED NOT NULL,
		seat CHAR(6) NOT NULL,
		cost DECIMAL(7,2) NOT NULL,
*/		private $flightId;
		private $userId;
		private $seat;
		private $cost;
		
		/* constructor for a ticket object
		 * input: (int) flightId (from flights class) *primarykey with userId*foreignkey also*
		 * input: (int) userId (from user class) *primarykey with flightId*foreignkey also*
		 * input: (string) seat
		 * input: (double) cost
		 * throws: when invalid input detected */
		public function __construct($ticketFlightId, $ticketUserId, $ticketSeat, $ticketCost)
		{
			try
			{	// use the mutator methods since they have all input sanitization built in
				$this->setFlightId($ticketFlightId);
				$this->setUserId($ticketUserId);
				$this->setSeat($ticketSeat);
				$this->setCost($ticketCost);
			}
			catch(Exception $exception)
			{	// rethrow the exception to the caller
				throw(new Exception("Unable to build ticket", 0, $exception));
			}
		}
//getters
		/* getter method for flightId
		 * input: N/A
		 * output: (id) id of the flight */
		public function getFlightId()
		{
			return($this->flightId);
		}
		/* getter method for userId
		 * input: N/A
		 * output: (id) id of the user */
		public function getUserId()
		{
			return($this->userId);
		}
		/* getter method for seat
		 * input: N/A
		 * output: the seat number (25C) */
		public function getSeat()
		{
			return($this->seat);
		}
		/* getter method for cost
		 * input: N/A
		 * output: cost of the ticket */
		public function getCost()
		{
			return($this->cost);
		}
		
//setters
		/* setter method for flightId
		 * input: (int) flightId (primarykey)
		 * output: N/A */
		public function setFlightId($ticketFlightId)
		{	// throw out leading and trailing spaces (sanitization1)
			$ticketFlightId = trim($ticketFlightId);
			// test to ensure the input is numeric (sanitization2)
			if(is_numeric($ticketUserId) === false)
			{
				throw(new Exception("Invalid user id detected: $ticketUserId is not numeric"));
			}
			// convert the ID to an integer (sanitization3)
			$ticketUserId = intval($ticketUserId);
			// throw out bad IDs by testing against regular expression (sanitization4)
			$regex = "/^\d{1,5}$/";
			if(preg_match($regexp, $ticketFlightId) !== 1)
			{
				throw(new Exception("Invalid flightId detected: $ticketFlightId , failed RegEx test."));
			}
			// sanitized, assign the value
			$this->flightId = $ticketFlightId;
		}
		/* setter method for userId
		 * input: (int) userId
		 * output: N/A */
		public function setUserId($ticketUserId)
		{	// throw out leading and trailing spaces  (sanitization1)
			$ticketUserId = trim($ticketUserId);
			// throw out obviously bad IDs (sanitization2)
			if(is_numeric($ticketUserId) === false)
			{
				throw(new Exception("Invalid user id detected: $ticketUserId is not numeric"));
			}
			// convert the ID to an integer (sanitization3)
			$ticketUserId = intval($ticketUserId);
			// throw out negative IDs, only established user IDs shouls be passed in. (sanitization4)
			if($ticketUserId < 0)
			{
				throw(new Exception("Invalid user id detected: $ticketUserId is less than 0"));
			}
			// sanitized, assign the value
			$this->userId = $ticketUserId;
		}
		/* setter method for seat
		 * input: (string) seat number (i.e... 25C)
		 * output: N/A */
		public function setSeat($ticketSeat)
		{	// throw out leading and trailing spaces  (sanitization1)
			$ticketSeat = trim($ticketSeat);
			// throw out bad IDs by testing against regular expression (sanitization2)
			$regex = "/^\d{1,2}[A-K]$/";
			if(preg_match($regexp, $ticketSeat) !== 1)
			{
				throw(new Exception("Invalid seat detected: $ticketSeat , failed RegEx test."));
			}
			// sanitized, assign the value
			$this->seat = $ticketSeat;
		}
		/* setter method for cost
		 * input: (double) cost of ticket
		 * output: N/A */
		public function setCost($ticketCost)
		{	// throw out leading and trailing spaces  (sanitization1)
			$ticketCost = trim($ticketCost);
			// throw out obviously bad IDs (sanitization2)
			if(is_numeric($ticketCost) === false)
			{
				throw(new Exception("Invalid user id detected: $ticketCost is not numeric"));
			}
			// make sure there are at least two trailing digits(sanitization3)
			$ticketCost = number_format((float)$ticketCost, 2, '.', '');
			// convert the ID to a double (sanitization4)
			$ticketCost = floatval($ticketCost);
			// Round trailing digits so there are ONLY two (sanitization5)
			$ticketCost = round($ticketCost, 2);
			// throw out bad prices by testing against regular expression (sanitization6)
			$regex = "/^\d{1,5}\.\d{2}$/";
			if(preg_match($regexp, $ticketCost) !== 1)
			{
				throw(new Exception("Invalid cost detected: $ticketCost , failed RegEx test."));
			}
			// sanitized, assign the value
			$this->cost = $ticketCost;
		}

// database manipulation functions		
		/* inserts a new object into mySQl
		 * input: (pointer) mySQL connection, by reference
		 * output: N/A
		 * throws: if the object could not be inserted */
		public function insert(&$mysqli)
		{
			// handle degenerate cases
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected"));
			}
			// create a query template
			$query = "INSERT INTO ticket(flightId, userId, seat, cost) VALUES(?, ?, ?, ?)";
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement. $query"));
			}
			// bind parameters to the query template
			$wasClean = $statement->bind_param("iisd", $this->flightId, $this->userId, $this->seat, $this->cost);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind parameters"));
			}
			// okay now do it
			if($statement->execute() === false)
			{
				throw(new Exception("Unable to execute statement"));
			}
			// clean up the statement
			$statement->close();
		}
		
		public function delete(&$mysqli)
		{
			// handle degenerate cases
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected"));
			}
			// create a query template
			$query = "DELETE FROM ticket WHERE userId  = ? AND flightId = ?";
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			// bind parameters to the query template
			$wasClean = $statement->bind_param("ii", $this->userId, $this->flightId);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind parameters"));
			}
			// okay now do it
			if($statement->execute() === false)
			{
				throw(new Exception("Unable to execute statement"));
			}
			// clean up the statement
			$statement->close();
		}
		/* updates this object in mySQL
		 * input (pointer) mySQL connection, by reference
		 * output: N/A
		 * throws: if the object could not be updated */
		public function update(&$mysqli)
		{
			// handle degenerate cases
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected"));
			}
			// make sure we're working with a valid objects
			if($this->userId < 0 || $this->flightId < 0)
			{
				if($this->userId < 0)
				{
					throw(new Exception("invalid userId"));
				}
				else
				{
					throw(new Exception("invalid flightId"));
				}
			}
			// create a query template
			$query = "UPDATE flight SET seat = ?, cost = ? WHERE userId = ? AND flightId";
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement. DOH!"));
			}
			// bind parameters to the query template
			$wasClean = $statement->bind_param("sdii", $this->seat, $this->cost, $this->userId, $this->flightId);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind parameters"));
			}
			// okay now do it
			if($statement->execute() === false)
			{
				throw(new Exception("Unable to execute statement"));
			}
			// clean up the statement
			$statement->close();
		}
	}
?>