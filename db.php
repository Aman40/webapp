<?php
$servername = "localhost";
$username = "aman";
$password = "password";
$conn = new mysqli($servername, $username, $password);

if(!$conn->connect_error) {
	die("Connection to database failed");
} else {
	echo "Connection to database established\n";
}
$stat = $conn->prepare("SELECT ?, ?, ? FROM Students;");
$stat->bind_param("isi", $Id, $FirstName, $Age);
//
$Id = "Id";
$FirstName = "FirstName";
$Age = "Age";
$stat->execute($Id, $FirstName, $Age);

$stat->close();
$conn->close();
?>
