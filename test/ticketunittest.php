<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
			
	//grab the classes used  by the function(s) under scrutiny
	require_once("../php/user.php");
	require_once("../php/flight.php");
	require_once("../php/ticket.php");
	class ticketTest extends UnitTestCase
	{
		//variable to hold our mySQL instance
		private $mysqli;
		// variable to hold the mySQL ticket
		private $sqlTicket;
		
		private $testSeat = "46B";
		private $testCost = 242.93;
		private $testUserId = 1;
		private $testFlightId = 3;
		private $updateFlightId = 5;
		
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
		// this ticket should end up in mySQL... well the first time we run it!
		public function testCreateValidTicket()
		{
			// create & insert the ticket
			$testCreateTicket = new Ticket($this->testFlightId, $this->testUserId, $this->testSeat, $this->testCost);
			$testCreateTicket->insert($this->mysqli);
			
			// select the ticket from mySQL and assert it was inserted properly
			$query = "SELECT flightId, userId, seat, cost FROM ticket WHERE userId = ? AND flightId = ?";
			$statement = $this->mysqli->prepare($query);
			$this->assertNotEqual($statement, false);
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("ii", $this->testUserId, $this->testFlightId);
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
			$this->assertIdentical($this->sqlTicket->getFlightId(), $this->testFlightId);
			$this->assertIdentical($this->sqlTicket->getUserId(), $this->testUserId);
			$this->assertIdentical($this->sqlTicket->getSeat(), $this->testSeat);
			$this->assertIdentical($this->sqlTicket->getCost(), $this->testCost);
			$this->assertTrue($this->sqlTicket->getUserId() > 0);
			$this->assertTrue($this->sqlTicket->getFlightId() > 0);
			$statement->close();
			
			//delete test object
			//$this->sqlTicket->delete($this->mysqli);
			//$this->mysqli->close();
		}
		
		public function testUpdateValidTicket()
		{
			// create & insert a new ticket
			$testUpdateTicket = new Ticket($this->updateFlightId, $this->testUserId, $this->testSeat, $this->testCost);
			$testUpdateTicket->insert($this->mysqli);
			
			//change the ticket's cost
			$newCost = 12.12;
			$testUpdateTicket->setCost($newCost);
			$testUpdateTicket->update($this->mysqli);
			
			// select the ticket from mySQL and assert it was inserted properly
			$query = "SELECT flightId, userId, seat, cost FROM ticket WHERE cost = ? AND flightId = ? AND userId = ?";

			$statement = $this->mysqli->prepare($query);
			$this->assertNotEqual($statement, false);
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("dii", $newCost, $this->updateFlightId, $this->testUserId);
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
			
			// verify the cost was changed
			$this->assertIdentical($this->sqlTicket->getFlightId(), $this->updateFlightId);
			$this->assertIdentical($this->sqlTicket->getUserId(), $this->testUserId);
			$this->assertIdentical($this->sqlTicket->getSeat(), $this->testSeat);
			$this->assertIdentical($this->sqlTicket->getCost(), $newCost);
			$statement->close();
		}
		
		// use the tearDown() to close mySQL
		public function tearDown()
		{
			$this->sqlTicket->delete($this->mysqli);
			$this->mysqli->close();
		}
	}
?>