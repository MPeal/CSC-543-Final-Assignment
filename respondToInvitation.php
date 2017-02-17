<?php
session_start();

// Create connection
$conn = new mysqli('localhost', 'root', '', 'warzone');
	
// Check connection
if ($conn->connect_error) 
{
	die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST["invitedPlayer"]) && isset($_POST["invitingPlayer"]) && isset($_POST["decision"]))
{
	if ($_POST["decision"] == "false")
	{
		//reset table data for the invited user
		$request = "UPDATE warzone.logged_in_users SET INVITESTATUS='FALSE', INVITING_PLAYER=NULL, INVITEDPLAYER=NULL WHERE USER='".$_POST["invitedPlayer"]."'";
		$conn->query($request);
		
		//reset table data for invite sender
		//$request = "UPDATE warzone.logged_in_users SET INVITESTATUS='FALSE', INVITING_PLAYER=NULL, INVITEDPLAYER=NULL WHERE USER='".$_POST["invitingPlayer"]."'";
		//$conn->query($request);
		
		//inform invite sender the game request was declined
		//echo "User denied your invite.";
	}
	else if($_POST["decision"] == "true")
	{
		//update table info for user
		$request = "UPDATE warzone.logged_in_users SET PARTNERED='TRUE', OPPONENT='".$_POST["invitingPlayer"]."', INVITESTATUS='FALSE', INVITING_PLAYER=NULL, INVITEDPLAYER=NULL WHERE USER='".$_POST["invitedPlayer"]."'";
		$conn->query($request);
		
		//update table info for invite sender
		$request = "UPDATE warzone.logged_in_users SET PARTNERED='TRUE', OPPONENT='".$_POST["invitedPlayer"]."', INVITESTATUS='FALSE', INVITING_PLAYER=NULL, INVITEDPLAYER=NULL WHERE USER='".$_POST["invitingPlayer"]."'";
		$conn->query($request);
	}
}


?>