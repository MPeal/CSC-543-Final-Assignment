<?php
session_start();
$_SESSION["m"] = ""; //session feedback message
$_SESSION["goodUser"] = ""; //will be assigned when a user enters proper credentials


function logCheck()
{
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
	
if(isset($_POST["pWord"]) && isset($_POST["uName"]) && isset($_POST["submit"]))
	{
		if($_POST["uName"] == "" || $_POST["pWord"] == "")
		{
			$_SESSION["m"] = "Missing some credentials";
			session_destroy();
		}
	}
	//beginning statement...if both items have been posted & have some kind of value, do the rest
	if(isset($_POST["pWord"]) && isset($_POST["uName"]) && $_POST["uName"] != "" && $_POST["pWord"] != "")
	{
		/*-------------------------------USERNAME & PASSWORD CHECK------------------*/
		$user = $_POST["uName"];
		$pword = $_POST["pWord"];
		
		$userPasses = "undefined";
		//search for a row from registered_users that matches the entered username and password
		$sqlCheck = "SELECT UNAME FROM warzone.registered_users WHERE UNAME='".$user."' AND PWORD='".$pword."'";
		$res = $conn->query($sqlCheck);
	
		//if a row is returned, the username and corresponding password match and the test passes
		if ($res->num_rows != 0)
		{
			$userPasses = true;
		}
		else if ($res->num_rows == 0)
		{
			$userPasses = false;
		}
		
		//if the user passes the test, move on to log-in checks
		if ($userPasses == true)
		{
			$loggedIn = "undefined";
			//check if user is already logged in
			$loggedInCheck = "SELECT USER FROM warzone.logged_in_users WHERE USER='".$_POST["uName"]."'";
			$res = $conn->query($loggedInCheck);
			
			if ($res->num_rows != 0) //if the result set shows the user is already logged in
			{
				$_SESSION["m"] = "You're already logged in somewhere else!";
				//$_SESSION["goodUser"] = $user;
				//DO NOT ADD THEM TO THE LOGGED IN TABLE
			}
			else if($res->num_rows == 0) //if no logged in users match the one trying to log in, add them to log in table and send 'em forward
			{
				$_SESSION["goodUser"] = $user;
				$logInQuery = "INSERT INTO warzone.logged_in_users (USER) values ('".$user."')"; //add user to the logged in table
				$conn->query($logInQuery);
				header("Location: home1.php"); //and send them to the next page
			}
		}
		else
		{
			$_SESSION["m"] = "Invalid credentials";
			session_destroy();
		}
	}
}

logCheck();
?>

<html>
<body>

<center><h1>Welcome to the Warzone!</h1></center>
<br>
<center><div style='border: 1px green solid; width: 30%; padding: 8px;'>
Log in to a game session
<br><br>
<form method='POST' enctype="multipart/form-data">
Username: <input type='text' name='uName'></input>
<br><br>
Password:   <input type='password' name='pWord'></input>
<br><br>
<input type='submit' value="Log In" name='submit'></input>
</form>
</div></center>
<br>
<center><div>
<?php
if(isset($_POST['submit']))
{
	echo $_SESSION["m"];
}
?>
</div></center>
<br>
<center><div style='width: 30%; border: 1px red solid; padding: 10px;'>
<a href='signup.php'><button>New User Signup</button></a>
</form>
</div></center>";

</body>
</html>