<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
			
	//grab the function(s) under scrutiny
	require_once("../php/user.php");
	require_once("../php/flight.php")
	require_once("../php/ticket.php")
	class ticketTest extends UnitTestCase
	{
		//variable to hold our mySQL instance
		private $mysqli;
		// variable to hold the mySQL user
		private $sqlTicket;
		
		private $testSeat = "46b";
		private $testCost = "242.93";
		private $testUserId = 1;
		private $testFlightId = 3;
		
		// use the estUp() to connect to mySQL
		public function setUp()
		{
			mysqli_report(MYSQLI_REPORT_STRICT);
			try
			{
				$this->mysqli = new mysqli("localhost", "airline_bradg", "1CODingF\$\$L", "airline_bradg");
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "unable to connect to mySQL: " . $exception->getMessage();
			}
		}
		// this user should end up in mySQL... well the first time we run it!
		public function testCreateValidTicket()
		{
			// create & insert the ticket
			$user = new Ticket($this->testFlightId, $this->testUserId, $this->testSeat, $this->testCost);
			$user->insert($this->mysqli);
			
			// select the ticket from mySQL and assert it was inserted properly
			$query = "SELECT flightId, userId, seat, cost FROM ticket WHERE userId = ? AND flightId = ?";
			$statement = $this->mysqli->prepare($query);
			$this->assertNotEqual($statement, false);
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("ii", $this->testFlightId, $this->testUserId);
			$this->assertNotEqual($wasClean, false);
			
			// execute the statement
			$executed = $statement->execute();
			$this->assertNotEqual($executed, false);
			
			// get the result & verify we only had 1
			$result = $statement->get_result();
			$this->assertNotEqual($result, false);
			$this->assertIdentical($result->num_rows, 1);
			
			// examine the result & assert we got what we want
			$row = $result->fetch_assoc();
			$this->sqlTicket = new Ticket($row["flightId"], $row["userId"], $row["seat"], $row["cost"]);
			$this->assertIdentical($this->sqlTicket->getFlightId(), $this->flightId);
			$this->assertIdentical($this->sqlTicket->getUserId(), $this->userId);
			$this->assertIdentical($this->sqlTicket->getSeat(), $this->seat);
			$this->assertIdentical($this->sqlTicket->getCost(), $this->cost);
			$this->assertTrue($this->sqlTicket->getUserId() > 0);
			$this->assertTrue($this->sqlTicket->getFlightId() > 0);
			$statement->close();
		}
		
		public function testUpdateValidTicket()
		{
			// create & insert the ticket
			$ticket = new Ticket($this->flightId, $this->userId, $this->seat, $this->cost);
			$ticket->insert($this->mysqli);
			
			//change the ticket's cost
			$newCost = "99.12";
			$ticket->setCost($newCost);
			$ticket->update($this->mysqli);
			
			// select the ticket from mySQL and assert it was inserted properly
			$query = "SELECT flightId, userId, seat, cost FROM ticket WHERE flightId = ? AND userId = ?";
// --resume from here Brad--
			$statement = $this->mysqli->prepare($query);
			$this->assertNotEqual($statement, false);
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("s", $newEmail);
			$this->assertNotEqual($wasClean, false);
			
			// execute the statement
			$executed = $statement->execute();
			$this->assertNotEqual($executed, false);
			
			// get the result & verify we only had 1
			$result = $statement->get_result();
			$this->assertNotEqual($result, false);
			$this->assertIdentical($result->num_rows, 1);
			
			// examine the result & assert we got what we want
			$row = $result->fetch_assoc();
			$this->sqlUser = new User($row["id"], $row["email"], $row["password"], $row["salt"]);
			
			// verify the email was changed
			$this->assertIdentical($this->sqlUser->getEmail(), $newEmail);
			$this->assertIdentical($this->sqlUser->getPassword(), $this->password);
			$this->assertIdentical($this->sqlUser->getSalt(), $this->salt);
			$this->assertTrue($this->sqlUser->getId() > 0);
			$statement->close();
		}
		
		// use the tearDown() to close mySQL
		public function tearDown()
		{
			$this->sqlUser->delete($this->mysqli);
			$this->mysqli->close();
		}
	}
?>