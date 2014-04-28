<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
			
	//grab the function(s) under scrutiny
	require_once("../php/user.php");
	
	class UserTest extends UnitTestCase
	{
		//variable to hold our mySQL instance
		private $mysqli;
		// variable to hold the mySQL user
		private $sqlUser;
		
		private $email = "brad.is@correct.com";
		private $password = "8cef652a7d3130bb234778794a5d41a3f59264d7c3221f25c34b391e87ace0b034556c9bc9c415642f810f3c38ad2759a042c7f6a6f43c66f3a079d3f2bf9fd8";
		private $salt = "136c67657614311f32238751044a0a3c0294f2a521e573afa8e496992d3786ba";
	
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
		public function testCreateValidUser()
		{
			// create & insert the user
			$user = new User(-1, $this->email, $this->password, $this->salt);
			$user->insert($this->mysqli);
			
			// select the user from mySQL and assert it was inserted properly
			$query = "SELECT id, email, password, salt FROM user WHERE email = ?";
			$statement = $this->mysqli->prepare($query);
			$this->assertNotEqual($statement, false);
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("s", $this->email);
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
			$this->assertIdentical($this->sqlUser->getEmail(), $this->email);
			$this->assertIdentical($this->sqlUser->getPassword(), $this->password);
			$this->assertIdentical($this->sqlUser->getSalt(), $this->salt);
			$this->assertTrue($this->sqlUser->getId() > 0);
			$statement->close();
		}
		
		public function testUpdateValidUser()
		{
			// create & insert the user
			$user = new User(-1, $this->email, $this->password, $this->salt);
			$user->insert($this->mysqli);
			
			//change the user's email
			$newEmail = "eric@is.correct.com";
			$user->setEmail($newEmail);
			$user->update($this->mysqli);
			
			// select the user from mySQL and assert it was inserted properly
			$query = "SELECT id, email, password, salt FROM user WHERE email = ?";
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