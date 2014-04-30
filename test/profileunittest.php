<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
			
	//grab the function(s) under scrutiny
	require_once("../php/user.php");
	require_once("../php/profile.php");
	
	class ProfileTest extends UnitTestCase
	{
		//variable to hold our mySQL instance
		private $mysqli;
		// variable to hold the mySQL profile
		private $sqlProfile;
		// variables to hold the user data for test
		private $userId = 1;
		private $firstName = "John";
		private $lastName = "Galt";
		private $birthday = "1970-01-01";
		private $specialNeeds = 1;
	
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
		// this profile should end up in mySQL... well the first time we run it!
		public function testCreateValidProfile()
		{	// create & insert the profile
			$profile = new Profile(-1, $this->userId, $this->firstName, $this->lastName, $this->birthday, $this->specialNeeds);
			$profile->insert($this->mysqli);
			// select the profile from mySQL and assert it was inserted properly
			$query = "SELECT id, userId, firstName, lastName, birthday, specialNeeds FROM profile WHERE id = ?";
			$statement = $this->mysqli->prepare($query);
			if($statement === false)
			{
				var_dump($this);
				throw(new Exception("Unable to prepare statement"));
			}
			$this->assertNotEqual($statement, false);
			// bind parameters to the query template
			$profileId = $profile->getId();
			$wasClean = $statement->bind_param("i", $profileId);
			if($wasClean === false)
			{
				var_dump($this);
				throw(new Exception("Unable to bind parameters"));
			}
			$this->assertNotEqual($wasClean, false);
			// execute the statement
			$executed = $statement->execute();
			if($executed === false)
			{
				var_dump($this);
				throw(new Exception("Unable to execute statement"));
			}
			$this->assertNotEqual($executed, false);
			// get the result & verify we only had 1
			$result = $statement->get_result();
			if($result === false || $result->num_rows !== 1)
			{	echo "Number of rows is " . $result->num_rows . "<br /><br />";
				var_dump($this);
				throw(new Exception("Unable to get result: invalid result set or wrong number of rows"));				
			}
			$this->assertNotEqual($result, false);
			$this->assertIdentical($result->num_rows, 1);
			// examine the result & assert we got what we want
			$row = $result->fetch_assoc();
			$this->sqlProfile = new Profile($row["id"], $row["userId"], $row["firstName"], $row["lastName"], $row["birthday"], $row["specialNeeds"]);
			$this->assertIdentical($this->sqlProfile->getUserId(), $this->userId);
			$this->assertIdentical($this->sqlProfile->getFirstName(), $this->firstName);
			$this->assertIdentical($this->sqlProfile->getLastName(), $this->lastName);
			$this->assertIdentical($this->sqlProfile->getBirthday(), $this->birthday);
			$this->assertIdentical($this->sqlProfile->getSpecialNeeds(), $this->specialNeeds);
			// tests to ensure the new ID created doesn't still have the -1 id indicating new
			$this->assertTrue($this->sqlProfile->getId() > 0); 
			$statement->close();
		}
		// test updating variables in the profile
		public function testUpdateValidProfile()
		{	// create & insert the profile USING 58 AS USERID
			$profile = new Profile(-1, 58, $this->firstName, $this->lastName, $this->birthday, $this->specialNeeds);
			$profile->insert($this->mysqli);
			
			// create new variables with data to update
			$newUserId = 59;
			$newFirstName = "firstnamechanged";
			$newLastName = "lastnamechanged";
			$newBirthday = "1980-03-03";
			$newSpecialNeeds = 0;
			//change the profile's information using setters
			$profile->setUserId($newUserId);
			$profile->setFirstName($newFirstName);
			$profile->setLastName($newLastName);
			$profile->setBirthday($newBirthday);
			$profile->setSpecialNeeds($newSpecialNeeds);
			//push updates to the server
			$profile->update($this->mysqli);
			// select the profile from mySQL and assert it was inserted properly
			$query = "SELECT id, userId, firstName, lastName, birthday, specialNeeds FROM profile WHERE id = ?";
			$statement = $this->mysqli->prepare($query);
			if($statement === false)
			{
				var_dump($this);
				throw(new Exception("Unable to prepare statement"));
			}
			$this->assertNotEqual($statement, false);
			// bind parameters to the query template
			$profileId = $profile->getId();
			$wasClean = $statement->bind_param("i", $profileId);
			if($wasClean === false)
			{
				var_dump($this);
				throw(new Exception("Unable to bind parameters"));
			}
			$this->assertNotEqual($wasClean, false);
			// execute the statement
			$executed = $statement->execute();
			if($executed === false)
			{
				var_dump($this);
				throw(new Exception("Unable to execute statement"));
			}
			$this->assertNotEqual($executed, false);
			// get the result & verify we only had 1
			$result = $statement->get_result();
			if($result === false || $result->num_rows !== 1)
			{
				throw(new Exception("Unable to get result: invalid result set or wrong number of rows"));				
			}
			$this->assertNotEqual($result, false);
			$this->assertIdentical($result->num_rows, 1);
			
			// examine the result & assert we got what we want
			$row = $result->fetch_assoc();
			$this->sqlProfile = new Profile($row["id"], $row["userId"], $row["firstName"], $row["lastName"], $row["birthday"], $row["specialNeeds"]);
		
			// verify the info was changed
			$this->assertIdentical($this->sqlProfile->getUserId(), $newUserId);
			$this->assertIdentical($this->sqlProfile->getFirstName(), $newFirstName);
			$this->assertIdentical($this->sqlProfile->getLastName(), $newLastName);
			$this->assertIdentical($this->sqlProfile->getBirthday(), $newBirthday);
			$this->assertIdentical($this->sqlProfile->getSpecialNeeds(), $newSpecialNeeds);
			// tests to ensure the new ID created doesn't still have the -1 id indicating new
			$this->assertTrue($this->sqlProfile->getId() > 0); 
			$statement->close();
		}
		// use the tearDown() to close mySQL
		public function tearDown()
		{
			$this->sqlProfile->delete($this->mysqli);
			$this->mysqli->close();
		}
	}
?>