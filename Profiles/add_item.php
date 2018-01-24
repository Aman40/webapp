<?php
session_start();
$session_exists = false;
if(isset($_SESSION['UserID'])) {
	$session_exists = true;
}
include "customErrorHandler.php";
set_error_handler("customErrorHandler");
if(isset($_REQUEST['context'])) {
	$context = $_REQUEST['context'];
} else {
	echo "<err>Context is not set</err>";
	die("<returnstatus>1</returnstatus>");
}
if($context==='add_item') {
	add_item();
} else if($context==="edit_item") {
	edit_item();
}
function edit_item() {
    $problem=false;
    if(isset($_GET['itemID'])) {
        $itemID = filter($_GET['itemID']);
    } else {
        echo "<err>The field 'itemid' is not set</err>";
        $problem = true;
    }
    if(isset($_GET['quantity'])) {
        $quantity=filter($_GET['quantity']);
    } else {
        echo "<err>The field 'quantity' is not set</err>";
        $problem = true;
    }
    if(isset($_GET['units'])) {
        $units = filter($_GET['units']);
    } else {
        echo "<err>The field 'units' is not set</err>";
        $problem = true;
    }
    $state = setdefault('state', 'N/A', $_GET);
    if(isset($_GET['price'])) {
        $price = filter($_GET['price']);
    } else {
        echo "<err>price is not set</err>";
        $problem = true;
    }
    $description = setdefault('description', "None", $_GET);
    if(isset($_GET['deliverable'])) {
        $deliverable = filter($_GET['deliverable']);
    } else {
        $problem = true;
    }
    $dplace = setdefault('dplace', "None", $_GET);
    if(isset($_GET['repid'])) {
        $repID = filter($_GET['repid']);
    } else {
    	echo "<err>repid is not set</err>";
        $problem = true;
    }
//Get user ID from $_SESSION
    $userID = $_SESSION['UserID'];
//Get the current date from with php
    $date = date("Y-m-d H:i:s");

    $servername = "localhost"; //This is bound  change when I upload to the real website.
    $username = "aman";
    $password = "password";
    $database = "test";
    if($problem==false) { //If there's no problem with the data extraction
        $conn = new mysqli($servername, $username, $password, $database);
        if(!$conn->connect_error) {
            $sql = "UPDATE TABLE Repository SET RepID='".$repID."', 
            UserID='".$userID."', 
            ItemID='".$itemID."', 
            Quantity=".$quantity.", 
            Units='".$units."', 
            UnitPrice=".$price.", 
            State='".$state."', 
            DateAdded='".$date."', 
            Description='".$description."', 
            Deliverable='".$deliverable."', 
            DeliverableAreas='".$dplace."'";
            $result = $conn->query($sql);
            if($result) { //Successful
                echo "<returnstatus>0</returnstatus>";
            } else { //Failure
                echo "<err>The query failed</err>";
                echo "<returnstatus>2</returnstatus>";
            }
        } else {
            echo "<err>Connection to the database failed</err>";
            die ("<returnstatus>4</returnstatus>");
        }
    } else {
        echo "<err>A problem with the data occurred</err>";
        die ("<returnstatus>4</returnstatus>");
    }
}
function add_item() {
    $problem=false;
    if(isset($_GET['itemID'])) {
        $itemID = filter($_GET['itemID']);
    } else {
        echo "<err>The field 'itemid' is not set</err>";
        $problem = true;
    }
    if(isset($_GET['quantity'])) {
        $quantity=filter($_GET['quantity']);
    } else {
        echo "<err>The field 'quantity' is not set</err>";
        $problem = true;
    }
    if(isset($_GET['units'])) {
        $units = filter($_GET['units']);
    } else {
        echo "<err>The field 'units' is not set</err>";
        $problem = true;
    }
    $state = setdefault('state', 'N/A', $_GET);
    if(isset($_GET['price'])) {
        $price = filter($_GET['price']);
    } else {
        echo "<err>price is not set</err>";
        $problem = true;
    }
    $description = setdefault('description', "None", $_GET);
    if(isset($_GET['deliverable'])) {
        $deliverable = filter($_GET['deliverable']);
    } else {
        $problem = true;
    }
    $dplace = setdefault('dplace', "None", $_GET);

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
                echo "<returnstatus>0</returnstatus>";
            } else { //Failure
				echo "<err>The query failed</err>";
                echo "<returnstatus>2</returnstatus>";
            }
        } else {
            echo "<err>Connection to the database failed</err>";
            die ("<returnstatus>4</returnstatus>");
        }
    } else {
        echo "<err>An unknown problem with the data occurred</err>";
        die ("<returnstatus>4</returnstatus>");
    }
}
function filter($entry) {
$entry = htmlspecialchars($entry); //Against any XSS and SQL injections
$entry = trim($entry); //Against SQL injections
$entry = stripslashes($entry);
return $entry; //Sanitized input
}
function setdefault($key, $default, $array) {
    //If a value is null, set it with the default given in the second parameter.
    if(isset($array[$key])) {
        return filter($array[$key]);
    } else {
        return $default;
    }
}
//Error Messages Summary
//1: Context not set
//0: Success
//2: Query failed
//3: Connection to db failed
//4: Problem with the data occurred.
