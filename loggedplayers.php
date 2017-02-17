<?php
session_start();

function findLoggedInUsers()
{
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "warzone";

	// Create connection
	$conn = mysqli_connect('localhost', 'root', '', 'warzone');
	
	// Check connection
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}
	
	$bool = false;
	
	mysqli_select_db($conn, 'warzone');
	$sql1 = "SELECT * from warzone.logged_in_users WHERE USER='".$_SESSION["goodUser"]."'";
	$res1 = $conn->query($sql1);
	
	if($row = mysqli_fetch_array($res1))
	{
		if($row["INVITESTATUS"] != 'FALSE') //check if the player sent an invite
		{
			$bool = true; //if they did, set this boolean to true..will be used later
		}
		else
		{
			$bool = false;
		}
	}
	
	mysqli_select_db($conn, 'warzone');
	$sql = "SELECT * from warzone.logged_in_users";
	$res = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_array($res)) //this fetches all the data from each row of the result set
	{
		$loggedInUser = $row["USER"];
		if($row["USER"] != $_SESSION["goodUser"] && $row["OPPONENT"] == NULL && $bool == true) //if the boolean set to the user's invitestatus is true, button is disabled, says "waiting" and name is changed so it can't post anything useful
		{
			echo "<form method='POST' action='invite.php'><label for='invitedUser'>".$row['USER']."</label>  <input type='hidden' name='invitedPlayer' value='".$row['USER']."'></input> <button type='submit' id='".$loggedInUser."' name='waiting' disabled>Waiting</button></form>";
		}
		else if ($row["USER"] != $_SESSION["goodUser"] && $row["OPPONENT"] == NULL) //list all logged in users that aren't the current session user and aren't in a game
		{
			echo "<form method='POST' action='invite.php'><label for='invitedUser'>".$row['USER']."</label>  <input type='hidden' name='invitedPlayer' value='".$row['USER']."'></input> <button type='submit' id='".$loggedInUser."' name='Invite'>Invite to Game</button></form>";
		}
		else if($row["USER"] == $_SESSION["goodUser"])
		{
			echo ""; //exclude the current session user from seeing himself/herself in the available users
		}
	}
	mysqli_close($conn);
}

findLoggedInUsers();
?>