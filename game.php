<?php
session_start();

// Create connection
$conn = new mysqli('localhost', 'root', '', 'warzone');
	
// Check connection
if ($conn->connect_error) 
{
	die("Connection failed: " . $conn->connect_error);
}

$request = "SELECT * FROM warzone.logged_in_users WHERE USER='".$_SESSION["goodUser"]."'";
$res = $conn->query($request);

if($row = $res->fetch_assoc())
{
	$_SESSION["opponent"] = $row["OPPONENT"];
}

function logout()
{
	// Create connection
	$conn = new mysqli('localhost', 'root', '', 'warzone');
	
	// Check connection
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}
	
	$logOut = "DELETE FROM warzone.logged_in_users where USER='".$_SESSION["goodUser"]."'"; //remove user from logged_in_users table
	$conn->query($logOut);
	$_SESSION["goodUser"] = ""; //reset the session username
	header("Location: index.php");
}

if(isset($_POST["logout"])) //if the user clicks log out
{
	logout();
}
?>

<html>
<body>
<style>
.main{
	margin: auto;
	border: solid blue 1px;
	padding: 5px;
	width: 20%;
}
</style>
<head>
<script>
/*function oppLogCheck() //listens to see if the player's opponent has logged out
{
	 var req;
	 var empty = ""; //token var

    req = new XMLHttpRequest();
	req.onreadystatechange = getFeedback;
    req.open('GET', 'oppLogCheck.php', true);
    req.send();

  function getFeedback() 
  {
    if (req.readyState == XMLHttpRequest.DONE) 
	{
      if (req.status == 200) //if the HTTP request is 'OK'
	  {
        if(req.responseText != "none" && req.responseText != "accepted") 
		{
			empty = ""; //nothing should happen, so just re-initialized the token var
		}
		else if(req.responseText == "none")
		{
			empty = ""; //nothing should happen
		}
		else if(req.responseText == "redirect") //if the php page says the oppponent has logged out, redirect to home1.php
		{
			window.location = "home1.php";
		}
      } 
    }
  }
}
oppLogCheck();
setInterval(oppLogCheck, 500);
*/
</script>
</head>
	
<center><div class="main">
	<?php 
		echo $_SESSION["goodUser"]."<br><br>would be playing<br><br>".$_SESSION["opponent"].""
	?>
</div></center>
<br><br>
<center><form method="POST" action="">
<input type="submit" name="logout" value="Log Out"></input>
</form></center>
</body>
</html>