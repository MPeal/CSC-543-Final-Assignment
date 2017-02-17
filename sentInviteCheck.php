<?php
session_start();

function checkSentInvite()
{
	$invitedPlayer = "";

	// Create connection
	$conn = new mysqli('localhost', 'root', '', 'warzone');
	
	// Check connection
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

	//find the session user's table
	$request = "SELECT * FROM warzone.logged_in_users where USER='".$_SESSION["goodUser"]."'";
	$res = $conn->query($request);

	if($row = $res->fetch_assoc())
	{	
		if($row["PARTNERED"] == 'TRUE')
		{
			echo "accepted";
		}
		else if($row["INVITEDPLAYER"] != NULL)
		{
			$invitedPlayer = $row["INVITEDPLAYER"];
		
			$request2 = "SELECT * FROM warzone.logged_in_users where USER='".$invitedPlayer."'";
			$res = $conn->query($request2);

			if($row = $res->fetch_assoc())
			{
				if($row["OPPONENT"] == $_SESSION["goodUser"]) //if the invited player accepted the SESSION USER'S invite
				{
					echo "accepted";
				}
				else if($row["INVITESTATUS"] == "FALSE" && $row["PARTNERED"] == "FALSE") //if the invited player rejected the invite
				{
					$request = "UPDATE warzone.logged_in_users SET INVITESTATUS='FALSE', INVITEDPLAYER=NULL, INVITEDPLAYER=NULL WHERE USER='".$_SESSION["goodUser"]."'";
					$conn->query($request);
					echo "Player rejected your invitation";
				}
			}
		}
	}
}
checkSentInvite();
?>