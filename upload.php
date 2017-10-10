<?php
include "customErrorHandler.php";
set_error_handler("customErrorHandler");
$itemName = get_if_set('ItemName');
$otherNames = get_if_set('OtherNames');
$category = get_if_set('Category');
$description = get_if_set('Description');
add_item_details($itemName, $otherNames, $category, $description);
echo "....FILES has ".count($_FILES)." files";
echo "POST has ".count($_POST)." files....";

function add_item_details($itemName, $otherNames, $category, $description) {
    $servername = "localhost";
    $username = "aman";
    $dbname = "test";
    $passwd = "password";

    $conn = new mysqli($servername, $username, $passwd, $dbname);
    if(!$conn->connect_error) {
        //Successfully connected
        $itemID = uniqid("I");
        $sql = "INSERT INTO Items(ItemID, ItemName, Aliases, Category, Description) VALUES ( '".$itemID."', '".$itemName."', '".$otherNames."', '".$category."', '".$description."' )";
        if($conn->query($sql)===TRUE) {
            //The query successfully completed. Now let's upload the files.
            echo "The item was successfully added to the database.";
            upload_pics($itemID);
        }
        else {
            //The query was unsuccessful
            echo "The query was unsuccessful";
            trigger_error($conn->error);
        }
    }
    else {
        //Problems with connection LATER. INCOMPLETE
        echo "There was a problem connecting to the database";
        trigger_error("There was a problem connecting to the database");
    }
    $conn->close();
}
function upload_pics($ItemID) {
    $directory = "/var/www/html/HTML/uploads";
    for ($i=0; $i<count($_FILES['myFile']['name']); $i++) {
        $extension = pathinfo($_FILES['myFile']['name'][$i], PATHINFO_EXTENSION);
        $uploadOK = 1;

        //check it's the right extension
        if($extension!="jpg" && $extension!="doc" && $extension!="docx" && $extension!="gif"
            && $extension!="png" && $extension!="txt") {
            $uploadOK = 0;
            if($extension=="sh") {
                echo "Fuck off! Nobody's falling for that!;";
            } else {
                echo "Sorry, but \".".$extension." type files aren't allowed.";
            }
        }
        //check it's the right size
        if ($_FILES['myFile']['size'][$i] >= 7000000) {
            $uploadOK = 0;
            echo "The file is too large! Hows that even possible!";
            echo "It's ".$_FILES['myFile']['size'][$i]." ;";
        }
        //upload
        if($uploadOK == 1) {
            $ImageID = uniqid("I");
            $ImageURI = $directory."/".$ImageID;
            if(move_uploaded_file($_FILES['myFile']['tmp_name'][$i], $ImageURI.".jpg")) {
                //Successfully moved file

                add_to_db($ItemID, $ImageID, $ImageURI);
                echo "Your file has been uploaded successfully;";
            } else {
                //File couldn't be moved
                echo "File couldn't be moved from temporary location. Permission or ownership crap!";
                trigger_error("The file couldn't be moved");
            }
        } else {
            echo "There was an error uploading your file;";
            trigger_error("There was an unspecified error uploading the image");
        }
    }
}
function add_to_db($ItemID, $ImageID, $ImageURI)
{
    $servername = "localhost";
    $username = "aman";
    $dbname = "test";
    $passwd = "password";

	$conn = new mysqli($servername, $username, $passwd, $dbname);
	if(!$conn->connect_error) {
        //Successfully connected
        $sql = "INSERT INTO ItemImages(ItemID, ImgID, ImageURI) VALUES ('".$ItemID."', '".$ImageID."', '".$ImageURI."')";
        if($conn->query($sql)===TRUE) {
            //The query successfully completed
            echo "The query was successful";
        }
        else {
            //The query was unsuccessful
            echo "The query was unsuccessful";
            trigger_error("The item images query failed: ".$conn->error);
        }
	}
	else {
		//Problems with connection LATER. INCOMPLETE
        trigger_error("The connection to the database could not be established.");
	}
	$conn->close();
}
function get_if_set($var) {
    if(isset($_REQUEST[$var])) {
        return filter($_REQUEST[$var]);
    } else {
        trigger_error($var." is not set!"); //There sure is a better way. INCOMPLETE; LATER.
        return null;
    }
}
function filter($entry) {
    //Filter user input to safeguard against XXS and SQL injections.
    $entry = htmlspecialchars($entry); //Against any XSS and SQL injections
    $entry = trim($entry); //Against SQL injections
    $entry = stripslashes($entry);
    return $entry; //Sanitized input
}

