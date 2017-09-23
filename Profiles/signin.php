<!doctype html>
<?php
session_start();
include "customErrorHandler.php";
set_error_handler("customErrorHandler");
/***********************LOGIN PROCESS STARTS HERE***************************/
					$server = "localhost";
					$username = "aman";
					$password = "password";
					$database = "test";
					$upassword_error = "";
					$phoneno = "";
					$upassword = "";
					if($_SERVER['REQUEST_METHOD']=='POST') {
							//Get data from the form
							if(isset($_POST['upassword'])) $upassword = filter($_POST['upassword']);
							if(isset($_POST['phoneno'])) $phoneno = filter($_POST['phoneno']);
							
							$conn = new mysqli($server, $username, $password, $database);
							
							if(!$conn->connect_error) {//Connection successful
							} else { //couldn't connect to the database. Do something
								die ("The connection to the database couldn't be established");
							}
							$query = "SELECT * FROM Users where PhoneNo  = ".$phoneno.";";
							$result = $conn->query($query);//Returns null (not an object) when there's nothing
							
							if($result!=null) { //If "username" matches, compare passwords and set session data.
								$row = $result->fetch_assoc(); //$result will hold a row of the data or null
								$db_password = $row['UserPassword']; 
								
								if(password_verify($upassword, $db_password)) { //Password correct. Set session data
										session_start();
										$_SESSION['UserID'] = $row['UserID'];
										$_SESSION['Sex'] = $row['Sex'];
										$_SESSION['DoB'] = $row['DoB'];
										$_SESSION['FirstName'] = $row['FirstName'];
										$_SESSION['LastName'] = $row['LastName'];
										$_SESSION['CoName'] = $row['CoName'];
										$_SESSION['District'] = $row['District'];
										$_SESSION['Email'] = $row['Email'];
										//Then redirect to the home page
										header("location: ../sample.php");
									} else { //Passwords don't match
										
										$upassword_error = "Wrong password";
									}
							} else { //Error in Username or password
								echo "<script>console.log('Connection error');</script>";
								$upassword_error = "Wrong phone number";
							}
					
							//rant about incorrect password and close the connections
							echo "<font color='red'>".$upassword_error."</font>";
							$conn->close();
					}
					function filter ($variable) {
						$variable = stripslashes($variable);
						$variable = htmlspecialchars($variable);
						$variable = trim($variable);
						return $variable;
					}

if(isset($_SESSION['UserID'])) {
	//redirect the user
	header("location: ../sample.php");
}
$cookie_name = "user";
$cookie_value = "value";
setcookie($cookie_name,$cookie_value,time()+3600);
?>
<html id="html">
	<head>
	<link rel="stylesheet" type="text/css" href="Profcss.css">
	</head>
	<body id="body">
		<div class="maindiv"> 
		<div class="headdiv">
			<h1>
				FMH
			</h1>	
			
			<div class="hornavbar" id="hornavbarid"> <!-- The Horizontal Navigation bar--> 
				<div id="reveal-navpane">
					<hr>
					<hr>
					<hr>
				</div>
				<a href="../sample.php">Home</a>
				<a href="#link">About Us</a>
				<a href="#link">Buy</a>
				<a href="#">Sell</a>
				<a href="#">Take a tour</a>
				<a href="signup.php" style="float:right">Sign Up</a>
			</div>			
		</div> 
		<div class="formdiv">
			
			<form target="_self" action="signin.php" method="post">
				Phone Number: <br>
				<input type="text" name="phoneno"><br>
				Password: <br>
				<input type="password" name="upassword"><br>
				<input type="submit">
			</form>

		</div>
		
	</div>
	<script src="Profilesjs.js"></script>
	</body>
</html>
