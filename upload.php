<?php
include "customErrorHandler.php";
set_error_handler("customErrorHandler");
//Get context above all
if(isset($_REQUEST['context'])) {
    $context = $_REQUEST['context'];
} else {
    //WTF is happening?
    echo "<msg>Context wasn't set</msg>";
    die("<returnstatus>9</returnstatus>");
}
//Now check if the value is coherent with what's expected
if($context=="add") {
    //Trying to add item to db
    //Get the item details
    $itemName = get_if_set('ItemName');
    $otherNames = get_if_set('OtherNames');
    $category = get_if_set('Category');
    $description = get_if_set('Description');
    add_item_details($itemName, $otherNames, $category, $description);
} else if($context=="edit") {
    //Trying to edit already existing item
    $itemName = get_if_set('ItemName');
    $otherNames = get_if_set('OtherNames');
    $category = get_if_set('Category');
    $description = get_if_set('Description');
    $itemID = get_if_set('ItemID');
    echo "<msg>The ItemID=".$itemID."</msg>";
    edit_item_details($itemID, $itemName, $otherNames, $category, $description); //Then call upload_units() here
    //or in after success of edit_item_details?
} else if($context=="getUnits") {

} else {
    die("<returnstatus>9</returnstatus>");
}

//Add a function to upload the units
function upload_units($ItemID) {
    //Get the units from the $_REQUEST global. Problem is, their number is uncertain
    //Extract the units from the array by parsing the JSON string.
    //$_POST has one more key, "units" whose value is the JSON
    //The JSON has one key, "unitsarr", an array containing all the units for the item,
    //Or an empty string if no units were selected. In that case, add nothing to the db.
    //JavaScript your way out of that.
    if(isset($_POST["units"])) {
        //It's hard coded, so it almost certainly should be set, except in an attempt to break the system
        if($_POST["units"]!="") {
            //Some units are appended
            $json = json_decode($_POST["units"], true);
            //Now we have an assoc array with one key, "unitsarr" whose value is an indexed array
            //Get the indexed array into $arr
            $arr = $json["unitsarr"];
            //Open connection. Remember to close it later
            $servername = "localhost";
            $username = "aman";
            $dbname = "test";
            $passwd = "password";
            $conn = new mysqli($servername, $username, $passwd, $dbname);
            if(!$conn->connect_error) {
                //Connection successful. Proceed
                //First delete all the existing units belonging to the Item before re-adding
                $sql = "DELETE FROM UnitsJunct WHERE ItemID='".$ItemID."'";
                $result = $conn->query($sql);
                if(!$result) {
                    die("<returnstatus>12</returnstatus>"); //End the script here
                }
                echo "<itemid>".$ItemID."</itemid>";
                for($i=0;$i<count($arr);$i++) {
                    $sql = "INSERT INTO UnitsJunct (ItemID, UnitID) VALUES ('".$ItemID."', '".$arr[$i]."')";
                    $result = $conn->query($sql);
                    echo "<err>".$conn->error."</err>";
                    echo "<err>".$result."</err>";
                    if(!$result) {
                        //If any of the queries fails.
                        //Unlikely but what to do if only one of the queries fails? Roll back everything? LATER
                        //Report how many of the queries completed and die
                        echo "<msg>Only ".$i." of the ".count($arr)." queries completed successfully.</msg>";
                        die("<returnstatus>8</returnstatus>"); //End the script here
                    }
                }
                //At this point, all the queries have completed successfully. Return the appropriate status
                echo "<msg>Units inserted successfully.</msg>";
                echo "<returnstatus>0</returnstatus>";
            } else {
                //Connection failed. Return appropriate status in XML
                echo "<returnstatus>1</returnstatus>";
            }
            $conn->close();
        } else {
            //Empty string. No units. End here
            echo "<msg>No units to append. Finished successfully.</msg>";
            echo "<returnstatus>0</returnstatus>";
        }

    } else {
        die ("<returnstatus>9</returnstatus>");
    }
}
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
                //If there's any error, the function won't return and will kill the script. Function doesn't return
                // returnstatus 0
                echo "<returnstatus>0</returnstatus>";
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
function edit_item_details($itemID, $itemName, $otherNames, $category, $description) {
    $servername = "localhost";
    $username = "aman";
    $dbname = "test";
    $passwd = "password";

    $conn = new mysqli($servername, $username, $passwd, $dbname);
    if(!$conn->connect_error) {
        //Successfully connected
        $sql = "UPDATE Items
                SET ItemName='".$itemName."', 
                Aliases='".$otherNames."', 
                Category='".$category."', 
                Description='".$description."'
                WHERE ItemID='".$itemID."'";
        if($conn->query($sql)===TRUE) {
            //The query successfully completed. Now let's upload the files, if any
            echo "<msg>The item was successfully added to the database.</msg>";
            //Add files. And maybe the units?
            if(count($_FILES)!==0){
                //Check that any files are appended. If yes, upload them
                upload_pics($itemID);
                upload_units($itemID);
            } else {
                //Else, end it here returning a 0 return status.
                echo "<msg>Successfully completed. No files appended.</msg>";
                upload_units($itemID);
            }
        }
        else {
            //The query was unsuccessful
            echo "<msg>The query was unsuccessful.</msg>";
            die("<returnstatus>4</returnstatus>");
        }
    }
    else {
        //Problems with connection LATER. INCOMPLETE
        echo "<msg>There was a problem connecting to the database</msg>";
        die("<returnstatus>1</returnstatus>");
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
                die("<msg>Fuck off! Nobody's falling for that!</msg>");
            } else {
                echo "<msg>Illegal file type.</msg>";
                echo "<returnstatus>5</returnstatus>";
            }
        }
        //check it's the right size
        if ($_FILES['myFile']['size'][$i] >= 7000000) {
            echo "<msg>Oversized file.</msg>";
            die("<returnstatus>6</returnstatus>");
        }
        //upload
        if($uploadOK == 1) {
            $ImageID = uniqid("I");
            $ImageURI = $directory."/".$ImageID;
            if(move_uploaded_file($_FILES['myFile']['tmp_name'][$i], $ImageURI.".jpg")) {
                //Successfully moved file

                add_to_db($ItemID, $ImageID, "/HTML/uploads/".$ImageID.".jpg"); //MACHINE SPECIFIC
                echo "<msg>Your file has been uploaded successfully;</msg>";
            } else {
                //File couldn't be moved
                echo "<msg>File couldn't be moved from temporary location. Permission or ownership crap!</msg>";
                die("<returnstatus>7</returnstatus>");
            }
        } else {
            echo "<msg>There was an unspecified error uploading your file;</msg>";
            die("<returnstatus>11</returnstatus>");
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
        die("<returnstatus>3</returnstatus>");
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
//8 One or more queries on the UnitsJunct table failed
//9 Active attempted system malice because missing value is hard-coded and shouldn't be missing or other than expected
//11 There was an unspecified error uploading the file.
//12 Couldn't delete the Units in the db
