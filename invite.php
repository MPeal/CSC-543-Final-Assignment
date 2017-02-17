<?php
session_start();

$invitedPlayer = $_POST["invitedPlayer"];

// Create connection
$conn = new mysqli('localhost', 'root', '', 'warzone');
	
// Check connection
if ($conn->connect_error) 
{
	die("Connection failed: " . $conn->connect_error);
}

//change session user's table info
$request = "UPDATE warzone.logged_in_users SET INVITESTATUS='TRUE', INVITEDPLAYER='".$invitedPlayer."' WHERE USER='".$_SESSION["goodUser"]."'"; 
$conn->query($request);

//change invited player's table info
$request2 = "UPDATE warzone.logged_in_users SET INVITESTATUS='TRUE', INVITING_PLAYER='".$_SESSION["goodUser"]."' WHERE USER='".$invitedPlayer."'";
$conn->query($request2);

header("Location: home1.php");
?>