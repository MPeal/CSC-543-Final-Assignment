





<!-- Some PHP TIPS -->

<?php


/******************************************** Database connection *********************************************************/

// connect to MySQL	
$conn = new mysqli("localhost", "root", "", "warzone");

// see if connection was successful
if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: " . $conn->connect_error;
	die('Error');
}else{
	// in case of no error, handle DB activity
	
	// the sql statement(here, a query) to be executed	
	$sql = "select productName, prodPrice from Products where prodCategory='$category' and prodPrice < 100;";
	
	// execute a query and retrieve the result. For an insert/update/delete, simply use $conn->query($sql)
	$res = $conn->query($sql);
	
	// fetch a row as an associative array and iterate
	while($row = $res->fetch_assoc()){
		echo "product name".$row["productName"].", product price: ".$row["productPrice"]."<br />";
	}
	
	// close the connection
	$conn->close();
	
	
}
/************************************************************************************************************************************************/


// VARIOUS TASKS USING PHP

// 1. defining constants in PHP
define('LOGIN_FAILURE_INVALID_CREDENTIALS', 100 );  // value 100 can be a string as well



// 2. retrieving data from a POST request:
if (isset($_POST["username"]) && isset($_POST["password"]) ){
	// do something with the data
	$username = $_POST["username"];
	// ...
	
}


// 3. forward to a page from PHP (in this case, home.php)
header( 'Location: home.php' ) ;


// 4. session management:
session_start();    // always need to have this in order to access session variables using $_SESSION[]

if(loginSuccess)    // save username to session if login was successful 
	$_SESSION['loggedInUserName']=$username;


// 5. delete a variable from scope
unset($_SESSION['signup_error']); 
unset($username);


	

?>


<!-- SOME JAVASCRIPT TIPS -->

<!-- LINK TO JQUERY LIBRARY -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	
<script type="text/javascript">
	// retrieve variables from php to javascript

	var invitingPlayer = '<?php echo $_POST["invitingPlayer"]; ?>';
	
	// logout AJAX call		
	function logout(){
		$.ajax({
			type: "Post",
			url: "logout.php",
			data:{ }, 
			success: function(data){
				alert('You have been logged out');	
				window.location.href="index.php";				
			}
		})

	}
	
	
	// keep checking whether someone has invited me to a game
	function pollForGameInvitations(){
		checkInvitationIntervalId = setInterval(checkForInvitations,200);	
	}
	
	// for checking invitations from other players 
	function checkForInvitations(){
		
		// retrieve username from php session
		var invitedPlayer = '<?php echo $_SESSION["loggedInUserName"]; ?>'
		
		// send an ajax request
		$.ajax({
			
			type: "Post",
			
			url: "checkInvitations.php",
			
			data:{ invitedPlayer: invitedPlayer },     // send username to check pending invitations for the player
			
			success: function(data){
				// parse json string to javascript object
				var invitations = JSON.parse(data);
				
				// check the invitations to/from
				invitingPlayer = invitations.invitationFrom;
				invitationStatus = invitations.invitationStatus;
				//here is the php code that generates the json string decoded above:
				/*
					$invitations = array();
					$res = $conn->query($sql);
					if($row = $res->fetch_assoc()){
						$invitations["invitationFrom"]=$row["invitingPlayer"];
						$invitations["invitationStatus"]='true';		
					}else{
						$invitations["invitationFrom"]='none';
						$invitations["invitationStatus"]='false';
					}
					echo json_encode($invitations);		
				 */
				
				if(invitationStatus!='false'){
					clearInterval(checkInvitationIntervalId);
					
					// javascript confirmation window
					confirm_yes = confirm(invitingPlayer+" invited you to a game. Accept ?");
						
					$.ajax({
						type: "Post",
						url: "respondToInvitation.php",
						data:{ invitedPlayer: invitedPlayer,
							   invitingPlayer: invitingPlayer,
							   decision: confirm_yes }, 
						success: function(data){
							if(confirm_yes){
								
								// forward user to the game 
								// TODO
							}else{
								pollForGameInvitations();		
							}				
						}
					});																			
				}
						

			}
		});
		
	}

</script>	






















