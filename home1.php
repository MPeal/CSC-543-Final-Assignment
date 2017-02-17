<?php
session_start();
$_SESSION["opponent"] = "";

//if no session user is logged in, automatically redirect back to log-in page(index)
if ($_SESSION["goodUser"] == "")
{
	session_destroy();
	header("Location: index.php");
}

if(isset($_POST["logout"])) //if the user clicks log out
{
	logout();
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
?>

<html>
<body>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" type="text/javascript">
</script>

<script>
function checkForInvitations()
{
	var invitedPlayer = '<?php echo $_SESSION["goodUser"];?>'; //invitedPlayer = the logged-in user
	
	$.ajax({
		type: "post",
		url: "checkInvitations.php",
		data: {invitedPlayer: invitedPlayer},

		success: function(data)
		{
			// parse json string to javascript object
			var invitations = JSON.parse(data);
			
			// check the invitations to/from
			invitingPlayer = invitations.invitationFrom;
			invitationStatus = invitations.invitationStatus;
			
			//if player has received an invite, pop up a screen asking them to confirm/accept the invite
			if(invitationStatus != 'false')
			{
				//clearInterval(checkInvitationIntervalId);
				confirm_invite = confirm(invitingPlayer+" invited you to a game. Accept ?");
				decision = "";
				if (confirm_invite == true)
				{
					decision = "true";
				}
				else if(confirm_invite == false)
				{
					decision = "false";
				}
				console.log("sending: "+invitedPlayer+", "+invitingPlayer+", "+decision);
				$.ajax({
					type: "Post",
					url: "respondToInvitation.php",
					data: {invitedPlayer: invitedPlayer, invitingPlayer: invitingPlayer, decision: decision},
						success: function(data)
						{
							if(confirm_invite == true)
							{
								clearInterval(inviteCheckInterval);
								window.location="game.php";
							}
							else if(confirm_invite == false)
							{
								inviteCheckInterval = setInterval(checkForInvitations, 500); //need more time to reset table if rejected
							}
						}
					})
					
			}	
		}
	})
	console.log("checked invites");
}

function getPlayers()
{
  var req;

    req = new XMLHttpRequest();
	req.onreadystatechange = populateDiv;
    req.open('GET', 'loggedplayers.php', true);
    req.send();

  function populateDiv() 
  {
    if (req.readyState == XMLHttpRequest.DONE) 
	{
      if (req.status == 200) //if the HTTP request is 'OK'
	  {
        document.getElementById('playerList').innerHTML = req.responseText; //populate the div w/ available players
      } 
    }
  }
}

function checkPendingInvite()
{
	 var req;
	 var empty;

    req = new XMLHttpRequest();
	req.onreadystatechange = alertUser;
    req.open('GET', 'sentInviteCheck.php', true);
    req.send();

  function alertUser() 
  {
    if (req.readyState == XMLHttpRequest.DONE) 
	{
      if (req.status == 200) //if the HTTP request is 'OK'
	  {
		if(req.responseText == "accepted")
		{
			window.location="game.php";
		}
		else if(req.responseText =="Player rejected your invitation") //if invite was rejected
		{
			alert(req.responseText); //alert the response text
		}
		else
		{
			empty = ""; //token variable re-initialize...just to have the script do nothing in case the response is blank/null
		}
      } 
    }
  }
  console.log("checked pending invites");
}

getPlayers();
setInterval(getPlayers, 200);
inviteCheckInterval = setInterval(checkForInvitations, 3000); //need more time to reset table if rejected
setInterval(checkPendingInvite, 200);

</script>
</head>

<center><h1>Welcome to the Warzone!</h1></center>
<br>
<center><div style='width: 30%; border: 1px solid green; padding: 10px;'>
<?php
	echo "Welcome, ".$_SESSION["goodUser"];
?>

<br><br>
Available players
<hr>
<center><div id="playerList">
<br><br>
</div></center>
<div id="feedbackDiv" style="display:none;">
</div>
</div></center>
<br><br>
<center><form method="POST" action="">
<input type="submit" name="logout" value="Log Out"></input>
</form></center>

</body>
</html>