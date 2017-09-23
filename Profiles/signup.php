<!doctype html>
<?php
session_start();
include "customErrorHandler.php";
set_error_handler("customErrorHandler");
if(isset($_SESSION['UserID'])) {
	//redirect the user after setting a session for them
	header("location: ../sample.php");
}
/*****************************FORM PROCESSING STARTS HERE*******************************/

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
					
					$fname_error = "";
					$lname_error = "";
					$upassword_error = "";
					$coname_error = "";
					$sex_error = "";
					$dob_error = "";
					$email_error = "";
					$district_error = "";					
					$phoneno_error = "";
					
				if($_SERVER['REQUEST_METHOD']=="POST"){ //has the form been submitted?
					//First name, Last name, Company name, Sex
					if(isset($_POST['sex'])) { //'sex' is set
					
						$sex = filter($_POST['sex']);
						if($sex == 'M' || $sex == 'F') { //Male or female i.e individual 
								if(isset($_POST['fname']) && $_POST['fname']!=NULL) { //Individual has set name and sex, OK
									//Sanitize
									$fname = filter($_POST['fname']);
									//Validate name
									if (!preg_match("/^[a-zA-Z ]*$/",$fname)) {
									$fname_error = "Only letters and white space allowed"; 
									$pushOK = false;
									}
								} else { //Individual hasn't set name error. name necessary;
									$fname_error = "You've gotta have a first name, don't ya?";
									$pushOK = false;
								}
							
								//Repeat for the last name what we did for the first name
								if(isset($_POST['lname']) && $_POST['lname']!=NULL) { //Individual has set name and sex, OK
									//Sanitize
									$lname = filter($_POST['lname']);
									//Validate name
									if (!preg_match("/^[a-zA-Z ]*$/",$lname)) {
									$lname_error = "Only letters and white space allowed"; 
									$pushOK = false;
									}
								} else { //Individual hasn't set name error. name necessary;
									$lname_error = "You've gotta have a last name, don't ya?";
									$pushOK = false;
								}
								
						} else { //Not 'M' not 'C', ergo, Company
									if(isset($_POST['coname']) && $_POST['coname']!=NULL) { //Company name is set, OK.
										$coname = filter($_POST['coname']);
									} else {//Company name is not set. Return error. Company name required			 	
										$coname_error = "Fill in the name of the company";
										$pushOK = false;
									}
									
						} 
						 
					} else { //Sex is not set. false
						$sex_error="It's literary just one fucking click yo!";
					}
								
					//The middle name
					if(isset($_POST['mname']) && $_POST['mname']!=NULL) {
						//sanitize
						$mname = filter($_POST['mname']);
						//validate middle name
						//Validate name
						if (!preg_match("/^[a-zA-Z ]*$/",$mname)) {
						$mname_error = "Only letters and white space allowed"; 
						$pushOK = false;
						}
					} else { //Set to null
						$mname = null;
					}
					
					//The password ALOT MORE CODING IS GONNA BE NEEDED TO VALIDATE IT
					if(isset($_POST['upassword']) && $_POST['upassword']!=NULL) {
						$upassword = filter($_POST['upassword']);
					} else { //report an error
						$upassword_error = "Enter a password bruh!";
						$pushOK = false;
					}
					
					//Get the date of birth
					if(isset($_POST['dob']) && $_POST['dob']!=NULL) {
						$dob = filter($_POST['dob']);
					} else { //report an error
						$dob_error = "Please enter your date of birth";
						$pushOK = false;
					}
					
					//Get the email TO BE VALIDATED
					if(isset($_POST['email']) && $_POST['email']!=NULL) {
						//sanitize email address
						$email = filter($_POST['email']);
						//validate email address
						if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
							$email_error = "Invalid email format"; 
							$pushOK = false;
						}
					} else { //report an error
						$email_error = "This field can't be empty";
						$pushOK = false;
					}
					
					//Get the district
					if(isset($_POST['district']) && $_POST['district']!=NULL) {
						$district = filter($_POST['district']);
					} else { //report an error
						$district_error = "Where do you/your business operate?";
						$pushOK = false;
					}
					
					//Get the phone number
					if(isset($_POST['phoneno']) && $_POST['phoneno']!=NULL) {
						$phoneno = filter($_POST['phoneno']);
					} else { //report an error
						$phoneno_error = "A phone number is required";
					}
					
					//Get the website url
					if(isset($_POST['website']) && $_POST['website']!=NULL) {
						$website = filter($_POST['website']);
						if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=
						~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
							$website_error = "Invalid URL"; 
						}
					} else { //report an error
						//Nothing to do. Default "" will do
					}
					
					//Get the website url
					if(isset($_POST['address']) && $_POST['address']!=NULL) {
						$address = filter($_POST['address']);
					} else { //report an error
						//Nothing to do. Default "" will do
					}
					
					//Get the About
					if(isset($_POST['about']) && $_POST['about']!=NULL) {
						$about = filter($_POST['about']);
					} else { //report an error
						//Nothing to do. Default "" will do
					}
				//Data extraction complete
				//Time for soooomeeee S... Q... L....
					if($pushOK == true) { //If no error has occured
						$servername = "localhost"; //This is bound  change when I upload to the real website.
						$username = "aman";
						$password = "password";
						$database = "test";
						
						$userid = uniqid("U"); //Generate using 
						/*$joindate = date(Y-m-d h:i:s);*/
						$upassword = password_hash($upassword, PASSWORD_DEFAULT); //Hash the user password
						
						$conn = new mysqli($servername, $username, $password, $database); //db password
						
						if(!$conn->connect_error) { //We connected successfully
							$sql = "INSERT INTO Users (UserID,
																					UserPassword,
																					FirstName,
																					MiddleName,
																					LastName,
																					Sex,
																					DoB,
																					CoName,
																					Email,
																					Address,
																					District,
																					Website,
																					PhoneNo,
																					About
							) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
							$stat = $conn->prepare($sql);
							$stat->bind_param("sssssssssssssb", 
													$userid,
													$upassword,
												/*	$joindate,*/ // Will be updated automatically in mysql
													$fname,
													$mname,
													$lname,
													$sex,
													$dob,
													$coname,
													$email,
													$address,
													$district,
													$website,
													$phoneno,
													$about);
							$success = $stat->execute();
							//Check for success
							if($success) {
								//Redirect user to a different page after setting the session data
									$_SESSION['UserID'] = $userid;
										$_SESSION['Sex'] = $sex;
										$_SESSION['DoB'] = $dob;
										$_SESSION['FirstName'] = $fname;
										$_SESSION['LastName'] = $lname;
										$_SESSION['CoName'] = $coname;
										$_SESSION['District'] = $district;
										$_SESSION['Email'] = $email;
								header("location: ../sample.php");
							} else {
								echo $stat->error;
							}
							//Close the statement and connection
							$stat->close();
							$conn->close();
						} else {
						echo "<script>console.log('Connection fatal error')</script>";
							die ("The connection to the database could not be established");
						}
						
					}
				}
				
				function filter($entry) {
					$entry = htmlspecialchars($entry); //Against any XSS and SQL injections
					$entry = trim($entry); //Against SQL injections
					$entry = stripslashes($entry);
					return $entry; //Sanitized input
				}

				function _value($nametag) {
					if(isset($_POST[$nametag]) && $_POST[$nametag] != null) {
						echo $_POST[$nametag];
					}
				}
				
				function _sex($sex_value) {
					if(isset($_POST['sex']) && $_POST['sex']==$sex_value) {
			 			echo "checked";
			 		}
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
				<a href="signin.php" style="float:right">Sign In</a>
			</div>			
		</div> 
		<div class="formdiv">
		
<!--*****************************FORM BEGINS HERE******************************************-->

			<form class="form" autocomplete="off" method="post" target="_self">
			
			 First Name: <br>
			 <input type="text" class="wide" name="fname" value="<?php _value('fname') ?>"><?php echo $fname_error ?><br><br>
			 
			 Middle Name: <br>
			 <input type="text" class="wide" name="mname" value="<?php if(isset($_POST['mname']) && $_POST['mname'] != null) echo $_POST['mname']; ?>"><br><br>
			 
			 Last Name: <br>
			 <input type="text" class="wide" name="lname" value="<?php if(isset($_POST['lname']) && $_POST['lname'] != null) echo $_POST['lname']; ?>" placeholder="Last name"><?php echo $lname_error ?><br><br>
			 
			 Password: <br>
			 <input type="password" class="wide" name="upassword" value="<?php if(isset($_POST['upassword']) && $_POST['upassword'] != null) echo $_POST['upassword']; ?>" required placeholder="password"><?php echo $upassword_error ?><br><br>
			 
			 Company Name: <br>
			 <input type="text" class="wide" name="coname" value="<?php if(isset($_POST['mname']) && $_POST['mname'] != null) echo $_POST['coname']; ?>" placeholder="Company Name"><?php echo $coname_error ?><br><br>
			 
			 Sex: <?php echo " ".$sex_error ?><br>
			 <input type="radio" name="sex" value="M" required <?php _sex('M') ?>>M<br>
			 
			 <input type="radio" name="sex" value="F" required <?php _sex('F') ?>>F<br>
			 <input type="radio" name="sex" value="C" required <?php _sex('C') ?>>Company<br><br>
			 
			 Date of Birth: <br>
			 <input type="date" class="wide" name="dob" required><?php echo $dob_error?><br><br>
			 			 
			 Email Address: <br>
			 <input type="text" class="wide" name="email" value="<?php _value('email'); ?>" placeholder="E.g example@domain.com" required><?php echo $email_error ?><br><br>
			 
			 District of Operation: <br>
			 <input type="text" class="wide" name="district" value="<?php _value('district'); ?>" placeholder="District"><?php echo $district_error ?><br><br>
			 
			 Address: <br>
			 <input type="text" class="wide" name="address" value="<?php _value('address'); ?>" placeholder="Address"> <br><br>
			 
			 Phone Number: <br>
			 <input type="tel" class="wide" name="phoneno" value="<?php _value('phoneno'); ?>" placeholder="E.g 0784596469" required><?php echo $phoneno_error ?><br><br>
			 
			 Website: <br>
			 <input type="text" class="wide" name="website" value="<?php _value('website'); ?>" placeholder="e.g www.example.com"><br><br>
			 
			 About Yourself: <br>
			 <textarea name="about" value="<?php echo $_POST['about']; ?>" placeholder="Say something about yourself"></textarea><br><br>
			 
			 <Input type="submit" value="Send">
			</form>
		</div>
		
	</div>
	<script src="Profilesjs.js"></script>
	</body>
</html>
