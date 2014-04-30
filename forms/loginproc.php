     <?php
        //start sessions
        session_start();
        //require the user class to optain user info
        require_once("user.php");
        function login()
        {//Tells mysqli to throw exceptions; verifys a connection to mySQL
            mysqli_report(MYSQLI_REPORT_ERROR);
            try
            { //gives me access to mysqli on server
                $mysqli = new mysqli("localhost", "airline_kirstene", "1CODingF\$\$L", "airline_kirstene"); 
            }
            catch(mysqli_sql_exception $exception)
            {//was there a connection?
                echo "No connection to mySQL:" . $exception->getMessage();
            }
            //POST get email and put it in $email
            $email = $_POST["email"];
            //trims white space
            $email = trim($email);
            try
            {//Get the user by email using $mysqli
                    $user = User::getUserByEmail($mysqli, $email);
            }
            catch(Exception $exception)
            {
                    echo"<p style='color: red'>Email or password do not match records</p>";
                    return;
            }
            //get this info from user.class
            $salt = $user->getSalt();
            //POST if there is a password will you add it to salt and put it in $password
            $password = $_POST["password"] . $salt;
            //get the password's hash and put it in var $password but what does false mean??
            $password = hash("sha512", $password, false);
            if($user->getPassword() == $password)
            {
                $id = $user->getId();
                $_SESSION["id"] = $id;
                $location = $_SERVER['HTTP_REFERER'];
                if($location == "http://studentsdeepdivecoders.com/~kirstene/assignment4/forms/loginForm.php")
                {
                        header ("location: profileForm.php");
                }
                else
                {
                        header("location: $location");
                }
            }
            else
            {
                echo"<p style='color: red'>Email or password do not match our records.</p>";
            }
            $mysqli->close();
    }
    login();
    ?>