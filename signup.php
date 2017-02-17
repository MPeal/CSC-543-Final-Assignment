<?php
session_start();

$_SESSION["m"] = ""; //session feedback message

function registerUser()
{
	/*--------------------------ESTABLISH CONNECTION TO DATABASE---------------------------------*/
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "warzone";

	// Create connection
	$conn = new mysqli('localhost', 'root', '', 'warzone');
	
	// Check connection
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	} 

	//beginning statement...if all 3 items have been posted & have some kind of value, do the rest
	if(isset($_POST["pWord"]) && isset($_POST["cpWord"]) && isset($_POST["uName"]) && $_POST["uName"] != "" && $_POST["cpWord"] != "" && $_POST["pWord"] != "") 
	{
		/*-----------------------------CHECK IF passwords MATCH--------------------------------------*/
		$pass = $_POST["pWord"];
		$cPass = $_POST["cpWord"];
		$passTest = "undefined";
	
		if ($pass == $cPass)
		{
			$passTest = true;
		}
		else if ($pass != $cPass)
		{
			$passTest = false;
			$_SESSION["m"] = "Passwords do not match.";
		}
	
		/*-------------------------------CHECK IF SUBMITTED username IS ALREADY USED------------------*/
		$userTest = "undefined";
		$sqlCheck = "SELECT UNAME FROM warzone.registered_users WHERE UNAME='".$_POST["uName"]."'";
		$res = $conn->query($sqlCheck);
	
		if ($res->num_rows == 0)
		{
			$userTest = true;
		}
		else if ($res->num_rows != 0)
		{
			$userTest = false;
		}
	
		if ($userTest == false)
		{
			$_SESSION["m"] = "Username already taken.";
		}
	
		/*-------------------------------INSERT DATA INTO registered_users----------------------------*/	
		if ($passTest == true && $userTest == true) //if both tests pass, add the user
		{
			$sqlAdd = "INSERT INTO warzone.registered_users values ('".$_POST["uName"]."', '".$_POST['cpWord']."')";
			$conn->query($sqlAdd);
			//$_SESSION["m"] = "User successfully registered!";
			
			//and now log them in, according to assignment requirements
			$_SESSION["goodUser"] = $_POST["uName"]; //establishes the session username variable
			$logInQuery = "INSERT INTO warzone.logged_in_users (USER) values ('".$_POST["uName"]."')"; //add user to the logged in table
			$conn->query($logInQuery);
			header("Location: home1.php"); //and send them to the next page
		}
	}
	else //if not all of the textboxes were set/filled in...
	{
		$_SESSION["m"] = "Information missing.";
	}
		$conn->close();
}

registerUser();
?>

<html>
<body>
<center><h1>Welcome to the Warzone!</h1></center>
<br>
<center><div style='border: 1px green solid; width: 30%; padding: 8px;'>
New User Signup
<br><br>
<form method="POST" enctype="multipart/form-data">
Username: <input type='text' name='uName'></input>
<br><br>
Password:   <input type='password' name='pWord'></input>
<br>
<br>
Confirm Password:  <input type='password' name='cpWord'></input>
<br><br>
<input type='submit' name='submit' value="Sign Up"></input>
</form>
</div></center>

<br>
<center><div id="regFeedback" style='width: 30%; padding: 8px;'>
<?php
//once the user has submitted something, enable session feedback
if(isset($_POST['submit']))
{
	echo $_SESSION["m"];
}
?>
</div></center>

</body>
</html>