<?php
session_start();
$session_exists = false;
if(isset($_SESSION['UserID'])) {
	$session_exists = true;
}
include "customErrorHandler.php";
set_error_handler("customErrorHandler");

//******************************************************************************
if(isset($_GET['itemID'])) {
	$itemID = filter($_GET['itemID']);
} else {
	$problem = true;
}
$problem=false;
if(isset($_GET['quantity'])) {
	$quantity=filter($_GET['quantity']);
} else {
	$problem = true;
}
if(isset($_GET['units'])) {
	$units = filter($_GET['units']);
} else {
	$problem = true;
}
if(isset($_GET['state'])) {
	$state = filter($_GET['state']);
} else {
	$state = "unapplicable";
}
if(isset($_GET['price'])) {
	$price = filter($_GET['price']);
} else {
	$problem = true;
}
if(isset($_GET['description'])){
	$description = filter($_GET['description']);
	$description = set_default($description, "None");
} else {
	$problem = true;
}
if(isset($_GET['deliverable'])) {
	$deliverable = filter($_GET['deliverable']);
} else {
	$problem = true;
}
if(isset($_GET['dplace'])) {
	$dplace = filter($_GET['dplace']);
	$dplace = set_default($dplace, "None");
} else {
	$problem = true;
}

//Get user ID from $_SESSION
$userID = $_SESSION['UserID'];
$repID = uniqid("R");
//Get the current date from with php
$date = date("Y-m-d H:i:s");

$servername = "localhost"; //This is bound  change when I upload to the real website.
$username = "aman";
$password = "password";
$database = "test";
if($problem==false) { //If there's no problem with the data extraction
	$conn = new mysqli($servername, $username, $password, $database);
	if(!$conn->connect_error) {
		$sql = "INSERT INTO Repository(RepID, UserID, ItemID, Quantity, Units, UnitPrice, State, DateAdded, Description, Deliverable, DeliverableAreas) VALUES('".$repID."','".$userID."','".$itemID."',".$quantity.",'".$units."',".$price.",'".$state."','".$date."','".$description."','".$deliverable."','".$dplace."')";
		$result = $conn->query($sql);
		if($result) { //Successful
			echo true;
		} else { //Failure
			echo false;
		}
	} else {
		die("Connection to the database failed");
	}
} else {
	die("An unknown error occurred");
}

function filter($entry) {
$entry = htmlspecialchars($entry); //Against any XSS and SQL injections
$entry = trim($entry); //Against SQL injections
$entry = stripslashes($entry);
return $entry; //Sanitized input
}
function set_default($value, $default) {
	if($value==null) {
		return $default;
	} else {
		return $value;
	}
}
?>
