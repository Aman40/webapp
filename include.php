<?php
session_start();
$session_exists = false;
if(isset($_SESSION['UserID'])) { //The session_exists variable is used in the index.php and other pages where this script is used. This is to hide/display content accordingly
	$session_exists = true;
}
include "customErrorHandler.php";
set_error_handler("customErrorHandler");

/************************LOGIN STARTS HERE**********************************/
//The error handling parameters have to be globalized in order to be accessed by the
//Forms.
		$server = "localhost";
		$username = "aman";
		$password = "password";
		$database = "test";
		$upassword_error = "";
		$phoneno = "";
		$upassword = "";
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

		$honorific = "";
		$display_name = "";
		$email = "";
		$phoneno = "";
		$upassword = "";
		$upassword2 = "";
		$honorific_error = "";
		$display_name_error = "";
		$email_error = "";
		$phoneno_error = "";
		$upassword_error = "";
		$upassword2_error = "";
$reload_gst_up = FALSE;
$reload_gst_in = FALSE;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	if(isset($_POST['formname']) && $_POST['formname'] == "profedit") {
	
	$pushOK = true; //falsified in case there's a problem with the form data
			//First name, Last name, Company name, Sex
								echo "<script>console.log('Signup form processing...');</script>";
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
				
			} else { //Not 'M' not 'F', ergo, Company
						if(isset($_POST['coname']) && $_POST['coname']!=NULL) { //Company name is set, OK.
							$coname = filter($_POST['coname']);
						} else {//Company name is not set. Return error. Company name required			 	
							$coname_error = "Fill in the name of the company";
							$pushOK = false;
							echo "<script>console.log('Theres a problem with the company name');</script>";
						}
					
			} 
			 
		} else { //Sex is not set. false
			$sex_error="It's literary just one fucking click yo!";
			$PushOK = false;
		}
		
		if(isset($_POST['coname']) && $_POST['coname']!=NULL) { //Company name is set, OK.
			$coname = filter($_POST['coname']);//This is a little redundant but not a problem
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
		} else { //Set to null. Still allow pushing
			$mname = null;
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
		} else { //It's Optional
			//Later
			$email = "none";
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
			$PushOK = false;
		}
	
		//Get the website url
		if(isset($_POST['website']) && $_POST['website']!=NULL) {
			$website = filter($_POST['website']);
			if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=
			~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
				$website_error = "Invalid URL";
				$PushOK = false;
			}
		} else { //report an error
			//Nothing to do. Default "" will do
		}
	
		//Get the address
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
							echo "<script>console.log('Data extraction and filtering complete');</script>";
		if($pushOK == true) { //If no error has occured
			$servername = "localhost"; //This is bound  change when I upload to the real website.
			$username = "aman";
			$password = "password";
			$database = "test";
		
			$conn = new mysqli($servername, $username, $password, $database); //db password
		
			if(!$conn->connect_error) { //We connected successfully
				echo "<script>console.log('Connected successfully');</script>";
				$sql = "UPDATE Users
								SET
								FirstName=?,
								MiddleName=?,
								LastName=?,
								Sex=?,
								DoB=?,
								CoName=?,
								Email=?,
								Address=?,
								District=?,
								Website=?,
								PhoneNo=?,
								About=?
								WHERE
								UserID=?";
				$stat = $conn->prepare($sql);
				if($stat === false ) {
					echo "<script>console.log('Statement preparation failed');</script>";
				} else {
					echo "<script>console.log('Statement preparation succeeded. Preparing to bind');</script>";
				}
				$stat->bind_param("sssssssssssss", 
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
										$about,
										$_SESSION['UserID']);
				$success = $stat->execute();
				//Check for success
				if($success) {
				echo "<script>console.log('SQL excecution successful')</script>";
					//Reaccess the database and extract the user information
						//START HERE
						$query = "SELECT * FROM Users where UserID  = '".$_SESSION['UserID']."'";
						$result = $conn->query($query);//Returns null (not an object) when there's nothing
		
						if($result!=null) { //If "username" matches, compare passwords and set session data.
							$row = $result->fetch_assoc(); //$result will hold a row of the data or null
							echo "<script>console.log('Setting new session variables');</script>";

									$_SESSION['UserID'] = $row['UserID'];
									$_SESSION['Sex'] = $row['Sex'];
									$_SESSION['DoB'] = $row['DoB'];
									$_SESSION['FirstName'] = $row['FirstName'];
									$_SESSION['MiddleName'] = $row['MiddleName'];
									$_SESSION['LastName'] = $row['LastName'];
									$_SESSION['Sex'] = $row['Sex'];
									$_SESSION['Address'] = $row['Address'];
									$_SESSION['Website'] = $row['Website'];
									$_SESSION['PhoneNo'] = $row['PhoneNo'];
									$_SESSION['About'] = $row['About'];
									$_SESSION['CoName'] = $row['CoName'];
									$_SESSION['District'] = $row['District'];
									$_SESSION['Email'] = $row['Email'];
				echo "<script>console.log('Done setting new session');</script>";
						} else { //Error in Username
							echo "<script>console.log('I dont even know what the problem is');</script>";
						}
						//END HERE
				} else {
					echo $stat->error;
					echo "<script>console.log('Problem executing the query')</script>";
				}
				//Close the statement and connection
				$stat->close();
				$conn->close();
			} else {
			echo "<script>console.log('Connection fatal error')</script>";
				die ("The connection to the database could not be established");
			}
		
		}
		else //A simple whisper into the console is not enough. Display a modal with an error.
		{
			echo "<script>console.log('Theres a problem with the data. Wrong form?'); console.log('WTF?')</script>";
			echo "<script>window.onload = function()
					{document.getElementById('id02').style.display='block';}</script>"; //Re-display the form after detecting an error
		}
	}

	if(isset($_POST['formname']) && $_POST['formname'] == "login") {
			//Get data from the form
			if(isset($_POST['upassword'])) $upassword = filter($_POST['upassword']);
			if(isset($_POST['phoneno'])) $phoneno = filter($_POST['phoneno']);
		
			$conn = new mysqli($server, $username, $password, $database);
		
			if(!$conn->connect_error) {//Connection successful
				echo "<script>console.log('Connected to database');</script>";
			} else { //couldn't connect to the database. Do something
				die ("The connection to the database couldn't be established");
			}
			$query = "SELECT * FROM Users where PhoneNo  = ".$phoneno.";";
			$result = $conn->query($query);//Returns null (not an object) when there's nothing
		
			if($result!=null) { //If "username" matches, compare passwords and set session data.
				$row = $result->fetch_assoc(); //$result will hold a row of the data or null
				$db_password = $row['UserPassword']; 
			
				if(password_verify($upassword, $db_password)) { //Password correct. Set session data
						$_SESSION['UserID'] = $row['UserID'];
						$_SESSION['Sex'] = $row['Sex'];
						$_SESSION['DoB'] = $row['DoB'];
						$_SESSION['FirstName'] = $row['FirstName'];
						$_SESSION['MiddleName'] = $row['MiddleName'];
						$_SESSION['LastName'] = $row['LastName'];
						$_SESSION['Sex'] = $row['Sex'];
						$_SESSION['Address'] = $row['Address'];
						$_SESSION['Website'] = $row['Website'];
						$_SESSION['PhoneNo'] = $row['PhoneNo'];
						$_SESSION['About'] = $row['About'];
						$_SESSION['CoName'] = $row['CoName'];
						$_SESSION['District'] = $row['District'];
						$_SESSION['Email'] = $row['Email'];
						//Then redirect to the home page
						$session_exists=true;
					} else { //Passwords don't match
					
						$upassword_error = "Wrong password";
						echo "<script>console.log('Wrong Password.');</script>";
					}
			} else { //Error in Username
				echo "<script>console.log('Wrong username');</script>";
				$upassword_error = "Wrong phone number";
			}

			//rant about incorrect password and close the connections
			echo "<span color='red'>".$upassword_error."</span>";
			$conn->close();
	} else {
	}
	/*****************************FORM PROCESSING STARTS HERE*******************************/

	$pushOK = true; //Defines whether it's OK to push the data to the database

	if(isset($_POST['formname']) && $_POST['formname'] == "signup") { //has the form been submitted?
		//First name, Last name, Company name, Sex
								echo "<script>console.log('Signup form processing...');</script>";
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
			$pushOK =false;
		}
		
		if(isset($_POST['coname']) && $_POST['coname']!=NULL) { //Company name is set, OK.
			$coname = filter($_POST['coname']);//This is a little redundant but not a problem
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
		} else { //It's Optional
			$email = "none";

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
	
		//Get the address
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
							echo "<script>console.log('Data extraction and filtering complete');</script>";
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
				echo "<script>console.log('Connected successfully');</script>";
				$sql = "INSERT INTO Users (UserID,
																		UserPassword,
																		JoinDate,
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
				) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$stat = $conn->prepare($sql);
				
				if($stat === false ) {
					echo "<script>console.log('Statement preparation failed');</script>";
				} else {
					echo "<script>console.log('Statement preparation succeeded. Preparing to bind');</script>";
					}
				
				$stat->bind_param("sssssssssssssss", 
										$userid,
										$upassword,
										$joindate,
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
				//get the join date as the current date time
				$joindate = date("Y-m-d H:i:s");
				$success = $stat->execute();
				//Check for success
				if($success) {
				echo "<script>console.log('SQL excecution successful')</script>";
					//START HERE
						$query = "SELECT * FROM Users where PhoneNo  = '".$phoneno."'";
						$result = $conn->query($query);//Returns null (not an object) when there's nothing
		
						if($result!=null) { //If "username" matches, compare passwords and set session data.
							$row = $result->fetch_assoc(); //$result will hold a row of the data or null
							echo "<script>console.log('Setting new session variables');</script>";
			
									$_SESSION['UserID'] = $row['UserID'];
									$_SESSION['Sex'] = $row['Sex'];
									$_SESSION['DoB'] = $row['DoB'];
									$_SESSION['FirstName'] = $row['FirstName'];
									$_SESSION['MiddleName'] = $row['MiddleName'];
									$_SESSION['LastName'] = $row['LastName'];
									$_SESSION['JoinDate'] = $row['JoinDate'];
									$_SESSION['Sex'] = $row['Sex'];
									$_SESSION['Address'] = $row['Address'];
									$_SESSION['Website'] = $row['Website'];
									$_SESSION['PhoneNo'] = $row['PhoneNo'];
									$_SESSION['About'] = $row['About'];
									$_SESSION['CoName'] = $row['CoName'];
									$_SESSION['District'] = $row['District'];
									$_SESSION['Email'] = $row['Email'];
									
									$session_exists = true;
				echo "<script>console.log('Done setting new session');</script>";
						} else { //Error in Username
							echo "<script>console.log('I dont even know what the problem is');</script>";
						}
						//END HERE
						
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
		else {
            echo "<script>console.log('Theres a problem with the data. Wrong form?');</script>";
            echo "<script>window.onload = function()
                    {document.getElementById('id02').style.display='block';}</script>";
		}
	}

    if(isset($_POST['formname']) && $_POST['formname'] == "gst_sgn_up") {
		//honorific, display_name, email, phoneno, upassword, upassword2
		$honorific = "";
		$display_name = "";
		$email = "";
		$phoneno = "";
		$upassword = "";
		$upassword2 = "";

		//get the honorific
		if(isset($_POST['honorific']) && $_POST['honorific'] != NULL) {
			$honorific = filter($_POST['honorific']);
		} else {
			//trigger an error
			$pushOK = FALSE;
			$honorific_error = "How The Fuck is that even possible?";
		}
		//get the display name
		if(isset($_POST['display_name']) && $_POST['display_name'] != NULL) {
			$display_name = filter($_POST['display_name']);
		} else {
			$pushOK = FALSE;
			$display_name_error = "This field is required";
		}
		//get the email
        if(isset($_POST['email']) && $_POST['email']!=NULL) {
            //sanitize email address
            $email = filter($_POST['email']);
            //validate email address
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email_error = "Invalid email format";
                $pushOK = false;
            }
        } else { //It's Optional
            $email = "none";
        }
        //Get the phone number
		if(isset($_POST['phoneno']) && $_POST['phoneno']!=NULL) {
			$phoneno = filter($_POST['phoneno']);
		} else {
			$phoneno_error = "There's a proble with the phone number";
			$PushOK = false;
		}
        //get the password. plagiarize code
        if(isset($_POST['upassword']) && $_POST['upassword']!=NULL) {
            $upassword = filter($_POST['upassword']);
        } else { //report an error
            $upassword_error = "Enter a password bruh!";
            $pushOK = false;
        }
        //get the repeated password
        if(isset($_POST['upassword2']) && $_POST['upassword2']!=NULL) {
            $upassword2 = filter($_POST['upassword2']);
        } else { //report an error
            $upassword2_error = "Please repeat the password!";
            $pushOK = false;
        }
        //Check for consistency between the two passwords
		if($upassword != $upassword2) {
			$pushOK = FALSE;
			$upassword2_error = "Your passwords do not match!";
		}

		if($pushOK == true) {
			//Upload the data to the database
			$conn = new mysqli($server, $username, $password, $database);
			if(!$conn->error) {
				//Successfully connected
				$clientID = uniqid("C");
				$password = password_hash($upassword, PASSWORD_DEFAULT);
				$joindate = date("Y-m-d H:i:s");
				$sql = "INSERT INTO Clients(ClientID, DisplayName, PhoneNo, Email, JoinDate, PwordHash, Honorific)
						VALUES ('".$clientID."', '".$display_name."', '".$phoneno."', '".$email."', '".$joindate."', '".$password."', '".$honorific."')";
				if($conn->query($sql)) {
					//Query was successful. Load session data.
					$_SESSION["ClientID"] = $clientID;
					$_SESSION["DisplayName"] = $display_name;
					$_SESSION["Honorific"] = $honorific;
					$_SESSION["PhoneNo"] = $phoneno;
				} else {
					//Query failed!
                    echo "<script>console.log(\"The Query failed because ".filter($conn->error)."\");</script>";
				}
			} else {
				//Connection to db failed LATER
                echo "<script>console.log('Connection to the database failed!');</script>";
			}
			$conn->close();
		} else {
			//Display the errors where they should be AND
			//Reload the modal
			$reload_gst_up = TRUE;
        }
	}
	else {
        echo "<script>console.log('".$_POST['formname']."');</script>";
    }
    if(isset($_POST['formname']) && $_POST['formname']=="gst_sgn_in") {
		//Sign in
		//get the phone number
        if(isset($_POST['phoneno']) && $_POST['phoneno']!=NULL) {
            $phoneno = filter($_POST['phoneno']);
        } else {
            $phoneno_error = "There's a proble with the phone number";
            $PushOK = false;
        }
        //get the password. plagiarize code
        if(isset($_POST['upassword']) && $_POST['upassword']!=NULL) {
            $upassword = filter($_POST['upassword']);
        } else { //report an error
            $upassword_error = "Enter a password bruh!";
            $pushOK = false;
        }
        //Get the values form the database using the phone number
		if($pushOK) {
        	//The data was extracted successfully
			//Create a connection to the db
			$conn = new mysqli($server, $username, $password, $database);
			if(!$conn->connect_errno) {
				//No problem
                $sql = "SELECT * FROM Clients where Clients.PhoneNo='".$phoneno."'"; //Only one row should be returned
                $result = $conn->query($sql); //No connection opened yet
                if($result!=null) {
                    //Get and compare passwords
                    $row = $result->fetch_assoc(); //Gives an associative array with the client's info from the db
                    if(password_verify($password, $row['PwordHash'])) {
                        //Phone no. and password correct. Set session data
						$_SESSION['ClientID'] = $row['ClientID'];
						$_SESSION['DisplayName'] = $row['DisplayName'];
						$_SESSION['PhoneNo'] = $row['PhoneNo'];
						$_SESSION['Email'] = $row['Email'];
						$_SESSION['JoinDate'] = $row['JoinDate'];
						$_SESSION['Honorific'] = $row['Honorific'];
                    } else {
                        //The password is wrong
						$upassword_error = "Password Error!";
                        $reload_gst_in = true;
                    }
                } else {
                    //The phone number is wrong
					$phoneno_error = "Your phone number is wrong";
                    $reload_gst_in = true;
                }
			} else {
				//Couldn't establish a connection to the db
				$phoneno_error = $upassword_error = "There was a technical problem. Try again later or contact management";
				$reload_gst_in = true;
			}
			$conn->close();
		} else {
        	//Didn't enter phone number or password. LATER
            $phoneno_error = $upassword_error = "Phone number or password wasn't entered";
            $reload_gst_in = true;
		}
	}
} else {
    echo "<script>console.log('Yup! Its definitely the request method!');</script>";
}
function filter($entry) {
	$entry = htmlspecialchars($entry); //Against any XSS and SQL injections
	$entry = trim($entry); //Against SQL injections
	$entry = stripslashes($entry);
	return $entry; //Sanitized input
}
?>
