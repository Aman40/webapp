<?php
include "customErrorHandler.php";
set_error_handler("customErrorHandler");
$itemName = get_if_set('ItemName');
$otherNames = get_if_set('OtherNames');
$category = get_if_set('Category');
$description = get_if_set('Description');
add_item_details($itemName, $otherNames, $category, $description);
echo "<msg>....FILES has ".count($_FILES)." files</msg>";
echo "<msg>POST has ".count($_POST)." files....</msg>";

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
            echo "<msg>The item was successfully added to the database.</msg>";

            if(count($_FILES)!==0){
                //Check that any files are appended. If yes, upload them
                upload_pics($itemID);
            } else {
                //Else, end it here returning a 0 return status.
                echo "<msg>Successfully completed. No files appended.</msg>";
                echo "<returnstatus>0</returnstatus>";
            }
        }
        else {
            //The query was unsuccessful
            echo "<msg>The query was unsuccessful</msg>";
            echo "<returnstatus>4</returnstatus>";
            trigger_error($conn->error);
        }
    }
    else {
        //Problems with connection LATER. INCOMPLETE
        echo "<msg>There was a problem connecting to the database</msg>";
        echo "<returnstatus>1</returnstatus>";
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
        if($extension!="jpg" && $extension!="gif" && $extension!="png") {
            $uploadOK = 0;
            if($extension=="sh") {
                echo "<msg>Fuck off! Nobody's falling for that!;</msg>";
                trigger_error("Intrusion attempt detected");
            } else {
                echo "<msg>Illegal file type.</msg>";
                echo "<returnstatus>5</returnstatus>";
                trigger_error("Illegal file type");
            }
        }
        //check it's the right size
        if ($_FILES['myFile']['size'][$i] >= 7000000) {
            $uploadOK = 0;
            echo "<msg>Oversized file.</msg>";
            echo "<returnstatus>6</returnstatus>";
            trigger_error("Oversized file");
        }
        //upload
        if($uploadOK == 1) {
            $ImageID = uniqid("I");
            $ImageURI = $directory."/".$ImageID;
            if(move_uploaded_file($_FILES['myFile']['tmp_name'][$i], $ImageURI.".jpg")) {
                //Successfully moved file

                add_to_db($ItemID, $ImageID, "/HTML/uploads/".$ImageID.".jpg"); //MACHINE SPECIFIC
                echo "<msg>Your file has been uploaded successfully;</msg>";
                echo "<returnstatus>0</returnstatus>"; //File successfully uploaded
            } else {
                //File couldn't be moved
                echo "<msg>File couldn't be moved from temporary location. Permission or ownership crap!</msg>";
                echo "<returnstatus>7</returnstatus>";
                trigger_error("The file couldn't be moved");
            }
        } else {
            echo "<msg>There was an error uploading your file;</msg>";
            trigger_error("The file couldn't meet the required specifications");
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
            echo "<msg>The query was successful</msg>";
        }
        else {
            //The query was unsuccessful
            echo "<msg>The query on ItemImages was unsuccessful</msg>";
            echo "<returnstatus>2</returnstatus>";
            trigger_error("The item images query failed: ".$conn->error);
        }
	}
	else {
		//Problems with connection LATER. INCOMPLETE
        echo "<returnstatus>1</returnstatus>";
        echo "<msg>Connection to the database could not be established.</msg>";
        trigger_error("The connection to the database could not be established.");
	}
	$conn->close();
}
function get_if_set($var) {
    if(isset($_REQUEST[$var])) {
        return filter($_REQUEST[$var]);
    } else {
        echo "<returnstatus>3</returnstatus>";
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

//Summary of returnStatus
//0 File upload successful. Both queries successful.
//1 connection to the database could not be established
//2 The query on the ItemImages was unsuccessful
//3 The Some required data is not entered.
//4 The query on the Items table was unsuccessful
//5 Illegal file type
//6 Oversize file
//7 Couldn't move file perhaps due to permission issues.
