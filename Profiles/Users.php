<!doctype html>
<?php
include "customErrorHandler.php";
set_error_handler("customErrorHandler");
$cookie_name = "user";
$cookie_value = "value";
setcookie($cookie_name,$cookie_value,time()+3600);
session_start();
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
				<a href="#link">Home</a>
				<a href="#link">About Us</a>
				<a href="#link">Buy</a>
				<a href="#">Sell</a>
				<a href="#">Take a tour</a>
				<a href="#" style="float:right">Sign Up</a>
			</div>			
		</div> 
		<div class="formdiv">
			<?php
					$pushOK = true; //Defines whether it's OK to push the data to the database
					$fname = "";
					$mname = "";
					$lname = "";
					$upassword = "";
					$coname = "";
					$sex = "";
					$dob = "";
					$email = "";
					$district = "";
					$address = "";
					$phoneno = "";
					$website = "";
					$about = "";
					
					$server = "localhost";
					$username = "aman";
					$password = "password";
					$database = "test";
					
					$conn = mysqli($server, $username, $password, $database);
					
					if(!$conn->connect_error) {//Connection successful
						echo "<script>console.log('Connection successful');</script>";
					} else { //couldn't connect to the database. Do something
						die "The connection to the database couldn't be established";
					}
					$query = "SELECT * FROM Users where 
			?>
	
			 
			 <Input type="submit" value="Send">
			</form>
		</div>
		
	</div>
	<script src="Profilesjs.js"></script>
	</body>
</html>
