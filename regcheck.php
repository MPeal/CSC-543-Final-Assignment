<?php
session_start();

$_SESSION["user"] = $_POST['uName'];
$_SESSION["pWord"] = $_POST['pWord'];
$_SESSION["cpWord"] = $_POST['cpWord'];
$_SESSION["m"] = "";

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
	if ($passTest == true && $userTest == true)
	{
		$sqlAdd = "INSERT INTO warzone.registered_users values ('".$_POST["uName"]."', '".$_POST['cpWord']."')";
		$conn->query($sqlAdd);
		$_SESSION["m"] = "User successfully registered!";
	}

	$conn->close();
}

registerUser();
echo $_SESSION["m"];
?>