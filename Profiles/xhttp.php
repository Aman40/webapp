<?php
session_start();
include "customErrorHandler2.php";
set_error_handler("customErrorHandler2");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
if(isset($_SESSION['UserID'])) { //The User is logged in
	$UserID = $_SESSION['UserID'];

    $table = filter($_REQUEST['table']);
//Access the database in search for item var
    _searchCatalog($table, $UserID);
}
else { //User is not logged in. I.e the fmh main dashboard.
    //Search the database for given item or all where q=""
	//Create a function to probe the database regardless of whether or not the user is logged in
    //Check the "table" value
    if(isset($_REQUEST['table'])) {
        $table=filter($_REQUEST['table']);
    } else {
        $table=""; //returns status 11. Please log in.
    }
    if($table=="nonlogged") {
        $str = filter($_REQUEST['q']);
        _search_all_db($str);
    }
    else if($table=="sellerdata") {
        //incomplete
        if(isset($_REQUEST['UserID'])) {
            $userid = $_REQUEST['UserID'];
        } else {
            //later. It'll almost always be set unless there's an active attempt to hack the system
        }
        _getuserinfo($userid);
    }
    else if($table=="Items") {
        _searchCatalog($table);
    }
    else {
        echo "<status>11</status>";
    }
}
//Filter user input data to prevent XSS or SQL Injections
function filter($entry) {
    //Filter user input to safeguard against XXS and SQL injections.
    $entry = htmlspecialchars($entry); //Against any XSS and SQL injections
    $entry = trim($entry); //Against SQL injections
    $entry = stripslashes($entry);
    return $entry; //Sanitized input
}
//When optional fields are empty, fill in default values
function setdefault($value, $default) {
    //If a value is null, set it with the default given in the second parameter.
    if($value==null) {
        return $default;
    } else {
        return $value;
    }
}
//_search_all_db() searches for all items available for sale, i.e items in the Repository
function _search_all_db($str)
{
    //Searches the db for Items registered by sellers. i.e cross-checks the items table with the Repository database
    $servername = "localhost";
    $username = "aman";
    $password = "password";
    $database = "test";
    $reply="<Items>";
    $conn = new mysqli($servername, $username, $password, $database);
    if(!$conn->connect_error) { //connection succeeded
        $sql = "SELECT Repository.UserID AS UserID, /*UserID will be used to retrieve userinfo*/
                    TempTable.ItemID AS ItemID,
					TempTable.ItemName AS ItemName,
					TempTable.Aliases AS Aliases,
					TempTable.Category AS Category,
					TempTable.Description AS DefaultDescription,
					Repository.RepID AS RepID,
					Repository.Quantity AS Quantity,
					Repository.Units AS Units,
					Repository.UnitPrice AS UnitPrice,
					Repository.State AS State,
					Repository.DateAdded AS DateAdded,
					Repository.Description AS Description,
					Repository.Deliverable AS Deliverable,
					Repository.DeliverableAreas AS DeliverableAreas
					 FROM (SELECT * FROM Items 
					 WHERE ItemName LIKE '%".$str."%' OR Aliases LIKE '%".$str."%')
					 AS TempTable JOIN Repository ON TempTable.ItemID = Repository.ItemID
					 "
					  ;
        $result = $conn->query($sql);
        if($result!=false) { //Query executed successfully
            if($result->num_rows>0) { //At least one record was found
                echo "<status>0</status>";
                while($row=$result->fetch_assoc()) { //Fetch row by row
                    global $UserID;
                    $UserID = $row['UserID'];
                    $ItemID = $row['ItemID'];
                    $ItemName = $row['ItemName'];
                    $Aliases = setdefault($row['Aliases'], "None");
                    $Category = $row['Category'];
                    $DefaultDescription = setdefault($row['DefaultDescription'], "None");
                    $ImageURI =	"None";
                    $RepID = $row['RepID'];
                    $Quantity = $row['Quantity'];
                    $Units = $row['Units'];
                    $UnitPrice = $row['UnitPrice'];
                    $State = setdefault($row['State'], "None");
                    $DateAdded = $row['DateAdded'];
                    $Description = setdefault($row['Description'], "None");
                    $Deliverable = $row['Deliverable'];
                    $DeliverableAreas = $row['DeliverableAreas'];
                    $reply.="<Item>";
                    $reply.="<UserID>".$UserID."</UserID>";
                    $reply.="<ItemID>".$ItemID."</ItemID>";
                    $reply.="<ItemName>".$ItemName."</ItemName>";
                    $reply.="<Aliases>".$Aliases."</Aliases>";
                    $reply.="<Category>".$Category."</Category>";
                    $reply.="<DefaultDescription>".$DefaultDescription."</DefaultDescription>";
                    $reply.="<ImageURI>".$ImageURI."</ImageURI>";
                    $reply.="<RepID>".$RepID."</RepID>";
                    $reply.="<Quantity>".$Quantity."</Quantity>";
                    $reply.="<Units>".$Units."</Units>";
                    $reply.="<UnitPrice>".$UnitPrice."</UnitPrice>";
                    $reply.="<State>".$State."</State>";
                    $reply.="<DateAdded>".$DateAdded."</DateAdded>";
                    $reply.="<Description>".$Description."</Description>";
                    $reply.="<Deliverable>".$Deliverable."</Deliverable>";
                    $reply.="<DeliverableAreas>".$DeliverableAreas."</DeliverableAreas>";
                    $reply.="</Item>";
                }
                //Close the xml
                $reply.="</Items>";
                //Return the xml
                echo $reply;


            } else { //No results were found
                echo "<status>1</status>";
            }
        }
        else { //There was a problem with the query
            echo "<status>2</status>";
            echo "<errormsg>$conn->error</errormsg>";
        }
        $conn->close(); //Close the connection
    } else { //connection failed
        echo "<status>3</status>";
    }
}

function _getuserinfo($userid) { //SHOULD IT ECHO TO OUTPUT OR RETURN TO CALLING FUNCTION?
    $conn = new mysqli("localhost", "aman", "password", "test");
    if(!$conn->connect_error) {
        $query = "SELECT * FROM Users where UserID  = '".$userid."'";
        $result = $conn->query($query);//Returns null (not an object) when there's nothing
        if($result!=null) { //If "username" matches, compare passwords and set session data.
            $row = $result->fetch_assoc(); //$result will hold a row of the data or null
            $Sex = $row['Sex'];
            $FirstName = $row['FirstName'];
            $LastName = $row['LastName'];
            $Website = $row['Website'];
            $PhoneNo = $row['PhoneNo'];
            $About = $row['About'];
            $CoName = $row['CoName'];
            $District = $row['District'];
            $Email = setdefault($row['Email'], "None");
            //Echo the extracted values into a return string
            $userData="<userdata>";
            $userData.="<sex>".$Sex."</sex>";
            $userData.="<firstname>".$FirstName."</firstname>";
            $userData.="<lastname>".$LastName."</lastname>";
            $userData.="<website>".$Website."</website>";
            $userData.="<phoneno>".$PhoneNo."</phoneno>";
            $userData.="<about>".$About."</about>";
            $userData.="<coname>".$CoName."</coname>";
            $userData.="<district>".$District."</district>";
            $userData.="<email>".$Email."</email>";
            $userData.="</userdata>";
            //Send results
            echo $userData;
            echo "<status>0</status>";

        } else { //Error in Username?
            echo "<script>console.log('I dont even know what the problem is');</script>";
        }
    } else { //Connection wasn't established
        echo "<status>3</status>";
    }
    //END HERE
}
function _searchCatalog($table, $UserID) {
    $servername = "localhost"; //This is bound  change when I upload to the real website.
    $username = "aman";
    $password = "password";
    $database = "test";
    $reply="<Items>";
    $conn = new mysqli($servername, $username, $password, $database);
    if(!$conn->connect_error) {  //Connection successful. Do stuff
        if($table=="Items") { //Browsing Items catalogue before adding items to the repository
            //Get the query
            $var = filter($_REQUEST['q']);

            $sql = "select ItemID, ItemName, Aliases, Category, Description 
                    from Items 
                    where ItemName like '%".$var."%' 
                    or Aliases like '%".$var."%'";
            $result = $conn->query($sql);
            if($result!=false) { //Check success
                if($result->num_rows > 0) { //Check the number of rows to return appropriate response when there are no results and when the query fails
                    echo "<status>0</status>"; //Results found
                    while($row = $result->fetch_assoc()) {
                        $ItemID = $row['ItemID'];
                        $ItemName = $row['ItemName'];
                        $Aliases = setdefault($row['Aliases'], "empty");
                        $Category = $row['Category'];
                        $Description = setdefault($row['Description'], "No description");
                        $ImageURI = "../icons/placeholder.png";
                        //Convert to XML
                        $reply=$reply."<Item><ItemID>".$ItemID."</ItemID><ItemName>".$ItemName."</ItemName><Aliases>".$Aliases."</Aliases><Category>".$Category."</Category><Description>".$Description."</Description><ImageURI>".$ImageURI."</ImageURI></Item>";//Build the XML
                    }
                    //Close the xml doc
                    $reply=$reply."</Items>";
                    //return the XML document to the requesting page
                    echo $reply;
                } else { //O results found
                    echo "<status>1</status>";
                }

            } else { //There's a problem with excecution of the query
                echo "<status>2</status>"; //No results found
                echo "<errormsg>.$conn->error.</errormsg>";
            }
            $conn->close(); //Close the connection
        }
        else if($table=="Repository") { //For listing a particular users items
            $sql = "SELECT Items.ItemID AS ItemID,
					Items.ItemName AS ItemName,
					Items.Aliases AS Aliases,
					Items.Category AS Category,
					Items.Description AS DefaultDescription,
					TempTable.RepID AS RepID,
					TempTable.Quantity AS Quantity,
					TempTable.Units AS Units,
					TempTable.UnitPrice AS UnitPrice,
					TempTable.State AS State,
					TempTable.DateAdded AS DateAdded,
					TempTable.Description AS Description,
					TempTable.Deliverable AS Deliverable,
					TempTable.DeliverableAreas AS DeliverableAreas
					 FROM (SELECT * FROM Repository WHERE UserID='".$UserID."')
					 AS TempTable JOIN Items ON TempTable.ItemID = Items.ItemID";
            $result = $conn->query($sql);
            if($result!=false) { //If everything went well
                if($result->num_rows>0) { //non-zero results found
                    echo "<status>0</status>";

                    while($row=$result->fetch_assoc()) {//Fetch row by row
                        $ItemID = $row['ItemID'];
                        $ItemName = $row['ItemName'];
                        $Aliases = setdefault($row['Aliases'], "None");
                        $Category = $row['Category'];
                        $DefaultDescription = setdefault($row['DefaultDescription'], "None");
                        $ImageURI =	"None";
                        $RepID = $row['RepID'];
                        $Quantity = $row['Quantity'];
                        $Units = $row['Units'];
                        $UnitPrice = $row['UnitPrice'];
                        $State = setdefault($row['State'], "None");
                        $DateAdded = $row['DateAdded'];
                        $Description = setdefault($row['Description'], "None");
                        $Deliverable = $row['Deliverable'];
                        $DeliverableAreas = $row['DeliverableAreas'];
                        $reply.="<Item>";
                        $reply.="<ItemID>".$ItemID."</ItemID>";
                        $reply.="<ItemName>".$ItemName."</ItemName>";
                        $reply.="<Aliases>".$Aliases."</Aliases>";
                        $reply.="<Category>".$Category."</Category>";
                        $reply.="<DefaultDescription>".$DefaultDescription."</DefaultDescription>";
                        $reply.="<ImageURI>".$ImageURI."</ImageURI>";
                        $reply.="<RepID>".$RepID."</RepID>";
                        $reply.="<Quantity>".$Quantity."</Quantity>";
                        $reply.="<Units>".$Units."</Units>";
                        $reply.="<UnitPrice>".$UnitPrice."</UnitPrice>";
                        $reply.="<State>".$State."</State>";
                        $reply.="<DateAdded>".$DateAdded."</DateAdded>";
                        $reply.="<Description>".$Description."</Description>";
                        $reply.="<Deliverable>".$Deliverable."</Deliverable>";
                        $reply.="<DeliverableAreas>".$DeliverableAreas."</DeliverableAreas>";
                        $reply.="</Item>";
                    }
                    $reply.="</Items>";
                    echo $reply;
                } else { //No results were found
                    echo "<status>1</status>";
                }

            } else {//A problem occured during execution of the query
                echo "<status>2</status>";
                echo "<errormsg>.$conn->error.</errormsg>";
            }
        }
        else if($table=='delete_item') { //Particular logged in user deleting repository item
            //Get the itemID
            $RepID = filter($_REQUEST['RepID']);
            $sql = "DELETE FROM Repository WHERE RepID='".$RepID."'";
            $result=$conn->query($sql);
            if($result==true) {
                echo "<status>0</status>";//Success
            } else {
                echo "<status>1</status>";//Failure
            }
            $conn->close();
        }
        else if ($table=='nonlogged') { //logged in and accessing db Repository (Dashboard/Feed)
            if(isset($_REQUEST['q'])) {
                $str = $_REQUEST['q'];
            } else {
                $str = "";
            }
            echo "<sstr>".$str."</sstr>";
            _search_all_db($str);
        }
        else if ($table=="sellerdata") {
            //incomplete
            if(isset($_REQUEST['UserID'])) {
                $userid = $_REQUEST['UserID'];
            } else {
                //later. It'll almost always be set unless there's an active attempt to hack the system
            }
            _getuserinfo($userid);
        }
    }
    else {
        echo "<status>3</status>"; //There's a problem with the connection to the database
    }
}
