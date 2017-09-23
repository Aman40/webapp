<?php
session_start();
if(!isset($_SESSION['UserID']) || !isset($_POST["submit"])) {
	trigger_error("Nice try bro!");
	echo "Nice try";
	//later
} else {
	echo "<script>console.log('Logged in. Proceeding...');</script>";
}
$uploadOK = true;
$upload_error = "";
$directory  = "/var/www/html/HTML/Profiles/Pictures";
$basename = $_SESSION['UserID'];
$extension = pathinfo($_FILES['profpic']['name'], PATHINFO_EXTENSION);
//Check for allowed extensions
if($extension!='jpg' && $extension!='jpeg' && $extension!='png' && $extension!='gif'
	&& $extension!='bmp') {
		$uploadOK = false;
		$upload_error = "Illegal type";
} else {
}
//Check the size
if($_FILES['profpic']['size'] > 5000000) {
	$uploadOK = false;
	$upload_error = "Use an image less than 5 MB in size";
} else {
}
//Check if it's REALLLY an image
$check = getimagesize($_FILES["profpic"]["tmp_name"]);
if($check !== false) {
    $uploadOk = true;
} else {
    $upload_error="File is not an image.";
    $uploadOk = false;
}
if(file_exists($directory."/".$_SESSION['UserID'])){
//delete the file. User is probably uploading new one. So far only one can be uploaded at a time.
unlink($directory."/".$_SESSION['UserID']);
} else {
}
//move our file
if($uploadOK){ //Everything went well
	if(move_uploaded_file($_FILES['profpic']['tmp_name'], $directory."/".$basename)) {
		header("location: index.php");
	} else { //For some reason couldn't copy the file
        echo "<script>console.log('The file couldn\'t be copied');</script>";
		trigger_error("Can't move the uploaded file");

	}
} else {//an error occurred
	echo "<script>console.log('".$upload_error."')</script>";
	trigger_error($upload_error);
}
?>