<?php
$directory = "/uploads";
$basename = pathinfo($_FILES['upfile']['name'], PATHINFO_BASENAME);
$basename = basename($basename);
$extension = pathinfo($_FILES['upfile']['name'], PATHINFO_EXTENSION);
$uploadOK = 1;
//check it's the right extension
if($extension!="jpg" && $extension!="doc" && $extension!="docx" && $extension!="gif"
	 && $extension!="png" && $extension!="txt") {
	$uploadOK = 0;
	if($extension=="sh") {
		echo "Fuck off! Nobody's falling for that!<br>";
	}
	echo "Sorry, but \".".$extension." type files aren't allowed.<br>";
}
//check it's the right size
if ($_FILES['upfile']['size'] >= 7000000) {
	$uploadOK = 0;
	echo "Sorry, your file is too large<br>";
	echo "It's ".$_FILES['upfile']['size']." KB<br>";
}
//check it already exists
if(file_exists($directory."/".$basename)) {
	$uploadOK = 0;
	echo "This file already exists<br>";
}
//upload
if($uploadOK == 1) {
	move_uploaded_file($_FILES['upfile']['tmp_name'], $directory."/".$basename);
	echo "Your file has been uploaded successfuly<br>";
} else {
	echo "There was an error uploading your file<br>";
}
?>
