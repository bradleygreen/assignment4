<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
			
	//grab the function(s) under scrutiny
	require_once("../user.php");
	require_once("../flight.php");
	//require_once("../php/ticket.php");
        //require_once("../php/profile.php");
        class FlightTest extends UnitTestCase
        {
            //variable to hold our mySQL instance
		private $mysqli;
		// variable to hold the mySQL flight
		private $sqlFlight = null;
            
                //These variables are all null, and belong to the FlightTest class, which is "$this"
                private $flightNumber = 124;
		private $origin = "ORD";
		private $destination = "DFW";
		private $numberSeats= 4;	
		private $departureTime = "2015-12-28 15:30:15";	
            
                // this is to actually connect to the database to run test
                // happens when setUp gets called by auto.run which will also call all test and the teardown
		public function setUp()
		{
			mysqli_report(MYSQLI_REPORT_STRICT);
			try
			{
				$this->mysqli = new mysqli("localhost", "airline_kirstene", "1CODingF\$\$L", "airline_kirstene");
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "unable to connect to mySQL: " . $exception->getMessage();
			}
		}
                // this user should end up in mySQL... well the first time we run it!
		public function testCreateValidFlight()
		{
			//We are creating a new "instance" of the Flight class, defined in flight.php
                        //We must provide the Flight class (from flight.php) with its starting data, and are using the FlightTest values
                        //The values passed in to the new Flight are required by the Flight "constructor" defined in flight.php as "__construct"
                        //The order of the values passed to the constructor is mandatory
                        $flight = new Flight(-1, $this->flightNumber, $this->origin, $this->destination, $this->numberSeats, $this->departureTime);
			//now insert this new flight into new database
			$flight->insert($this->mysqli);
			//now select the flight row on database to make sure it was put there properly
			// asks the database for any row that meets these qualifications
			$query = "SELECT id, flightNo, origin, destination, noSeats, departureTime FROM flight WHERE flightNo = ? AND origin = ? AND destination = ? AND noSeats = ? AND departureTime = ?";
			// we are preparing a statment to send to database and putting our query in its parameter
			$statement = $this->mysqli->prepare($query);
			//checks to make sure database is ready for a statement 
			$this->assertNotEqual($statement, false);
			//fill in the blanks where we put '?' before in our query
			$wasClean = $statement->bind_param("issis", $this->flightNumber, $this->origin, $this->destination, $this->numberSeats, $this->departureTime);
			//check to make sure the bind worked
			$this->assertNotEqual($wasClean, false);
			//actually send full request to the database
			$executed = $statement->execute();
			//check response to make sure it executed
			$this->assertNotEqual($executed, false);
			//what did the database show when queried result holds the all returned information including associative array or other response if error
			$result = $statement->get_result();
			//make sure there is something there, if false, there is nothing
			$this->assertNotEqual($result, false);
			//make sure our query didn't return duplicates only one row accepted, should not ever be two identical rows.
			$this->assertIdentical($result->num_rows, 1);
			
			//examine the information returned
			//converts returned information into an associative array
			$row = $result->fetch_assoc();
			//create a new Flight object using the information returned from the database
			$this->sqlFlight = new Flight($row["id"], $row["flightNo"], $row["origin"], $row["destination"], $row["noSeats"], $row["departureTime"]);
			
			//test the crap out of it. check every  piece of data to ensure it's what we put in
			// is sqlFlightNumber, $this->flightNumber Identical? 
			$this->assertIdentical($this->sqlFlight->getFlightNumber(), $this->flightNumber);
			$this->assertIdentical($this->sqlFlight->getOrigin(), $this->origin);
			$this->assertIdentical($this->sqlFlight->getDestination(), $this->destination);
			$this->assertIdentical($this->sqlFlight->getNumberSeats(), $this->numberSeats);
			$this->assertIdentical($this->sqlFlight->getDepartureTime(), $this->departureTime);
			
					
			$statement->close();
		     
		}
		
		public function tearDown()
		{
			if($this->sqlFlight != null)
			{
				$this->sqlFlight->delete($this->mysqli);
			}
			$this->mysqli->close();
		}
            
            
        }
        