<?php
session_start();
include "customErrorHandler2.php";
set_error_handler("customErrorHandler2");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
if(isset($_SESSION['UserID'])) { //The User is logged in
	$UserID = $_SESSION['UserID'];

    $table = filter($_REQUEST['table']);
//Access the database in search for item var
    if($table=="closedorder") {
        placeClosedOrder($UserID);
    } else {
        _searchCatalog($table, $UserID); //'_searchCatalog is kind of a misnomer. Function is more of a selector
    }
}
else if(isset($_SESSION['ClientID'])) {
    $ClientID = $_SESSION['ClientID'];
    placeClosedOrder($ClientID);
}
else {
    //User is not logged in. I.e the fmh main dashboard.
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
        _searchCatalog($table, "");
    }
    else if($table=="editItems") {
        _searchCatalog($table, "");
    }
    else if($table=="allUnits") {
        _searchCatalog($table, "");
    }
    else if ($table=="getUnits") {
        _searchCatalog($table, "");
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
function setdefault($key, $default, $array) {
    //If a value is null, set it with the default given in the second parameter.
    if(isset($array[$key])) {
        return $array[$key];
    } else {
        return $default;
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
        //Repository, Items, ItemImages
        $sql = "SELECT 
                Transient.UserID AS UserID,
                Transient.ItemID AS ItemID,
                Transient.ItemName AS ItemName,
                Transient.Aliases AS Aliases,
                Transient.Category AS Category,
                Transient.DefaultDescription AS DefaultDescription,
                Transient.RepID AS RepID,
                Transient.Quantity AS Quantity,
                Transient.Units AS Units,
                Transient.UnitPrice AS UnitPrice,
                Transient.State AS State,
                Transient.DateAdded AS DateAdded,
                Transient.Description AS Description,
                Transient.Deliverable AS Deliverable,
                Transient.DeliverableAreas AS DeliverableAreas,
                ItemImages.ImageURI AS ImageURI
                FROM
                (SELECT Repository.UserID AS UserID, /*UserID will be used to retrieve userinfo*/
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
                 AS TempTable JOIN Repository ON TempTable.ItemID = Repository.ItemID)
                 AS Transient LEFT JOIN ItemImages ON Transient.ItemID = ItemImages.ItemID
                ORDER BY RepID"
					  ;
        $result = $conn->query($sql);
        if($result!=false) { //Query executed successfully
            if($result->num_rows>0) { //At least one record was found
                echo "<status>0</status>";
                $row1 = $result->fetch_assoc();
                $reply="<Items><Item><Images>";
                $reply=cmp_uid($reply, $result, $row1, "RepID");
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
            $Email = setdefault('Email', "None", $row);
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
    //Function is only accessible only if user with "member account" is logged in
    //Function name is sort of a misnomer. It's more of a selector, responding to different requests
    //depending on context specified in $table.
    $servername = "localhost"; //This is bound  change when I upload to the real website.
    $username = "aman";
    $password = "password";
    $database = "test";
    $conn = new mysqli($servername, $username, $password, $database);
    if(!$conn->connect_error) {  //Connection successful. Do stuff
        if($table=="Items") { //Browsing Items catalogue before adding items to the repository
            //Get the query
            $var = filter($_REQUEST['q']);
            //Items, ItemImages
            $sql = "SELECT
                    Transient.ItemID AS ItemID,
                    Transient.ItemName AS ItemName,
                    Transient.Aliases AS Aliases,
                    Transient.Category AS Category,
                    Transient.Description AS Description,
                    ItemImages.ImageURI AS ImageURI
                    FROM 
                    (select ItemID, ItemName, Aliases, Category, Description 
                    from Items 
                    where ItemName like '%".$var."%' 
                    or Aliases like '%".$var."%')
                    AS Transient LEFT JOIN ItemImages ON Transient.ItemID = ItemImages.ItemID
                    ORDER BY ItemID";
            $result = $conn->query($sql);
            if($result!=false) { //Check success
                if($result->num_rows > 0) { //Check the number of rows to return appropriate response when there are no results and when the query fails
                    echo "<status>0</status>"; //Results found
                    //It gets complicated from here. A recursive function inside recursive function
                    $row1 = $result->fetch_assoc();
                    $reply="<Items><Item><Images>";
                    $reply=cmp_uid($reply, $result, $row1, "ItemID");
                    //return the XML document to the requesting page
                    echo $reply;
                } else { //O results found
                    echo "<status>1</status>";
                }

            } else { //There's a problem with excecution of the query
                echo "<status>2</status>"; //No results found
                echo "<errormsg>.$conn->error.</errormsg>";
            }
        }
        else if($table=="Repository") { //For listing a particular users items
            //Repository, Items, ItemImages
            $sql = "SELECT DISTINCT 
                    Transient.ItemID AS ItemID,
                    Transient.ItemName AS ItemName,
                    Transient.Aliases AS Aliases,
                    Transient.Category AS Category,
                    Transient.DefaultDescription AS DefaultDescription,
                    Transient.RepID AS RepID,
                    Transient.Quantity AS Quantity,
                    Transient.Units AS Units,
                    Transient.UnitPrice AS UnitPrice,
                    Transient.State AS State,
                    Transient.DateAdded AS DateAdded,
                    Transient.Description AS Description,
                    Transient.Deliverable AS Deliverable,
                    Transient.DeliverableAreas AS DeliverableAreas,
                    ItemImages.ImageURI AS ImageURI
                    FROM
                    (SELECT Items.ItemID AS ItemID,
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
					 AS TempTable JOIN Items ON TempTable.ItemID = Items.ItemID)
					 AS Transient LEFT JOIN ItemImages ON ItemImages.ItemID = Transient.ItemID
					 ORDER BY RepID";
            $result = $conn->query($sql);
            if($result!=false) { //If everything went well
                if($result->num_rows>0) { //non-zero results found
                    echo "<status>0</status>";
                    echo "<res_total>$result->num_rows</res_total>";
                    $row1 = $result->fetch_assoc();
                    $reply="<Items><Item><Images>";
                    $reply=cmp_uid($reply, $result, $row1, "RepID");
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
        else if ($table=='editItems') { //table
            //Fetch multiple rows. Each item with its own units and images and put them all in one XML
            //Get the query
            $var = filter($_REQUEST['q']);

            $sql = "SELECT
                    Transient.ItemID AS ItemID,
                    Transient.ItemName AS ItemName,
                    Transient.Aliases AS Aliases,
                    Transient.Category AS Category,
                    Transient.Description AS Description,
                    ItemImages.ImgID AS ImageID,
                    ItemImages.ImageURI AS ImageURI
                    FROM 
                    (select ItemID, ItemName, Aliases, Category, Description 
                    from Items 
                    where ItemName like '%".$var."%' 
                    or Aliases like '%".$var."%')
                    AS Transient LEFT JOIN ItemImages ON Transient.ItemID = ItemImages.ItemID
                    ORDER BY ItemID";
            $result = $conn->query($sql);
            if($result!=false) { //Check success
                if($result->num_rows > 0) { //Check the number of rows to return appropriate response when there are no results and when the query fails
                    echo "<status>0</status>"; //Results found
                    //It gets complicated from here. A recursive function inside recursive function
                    $row1 = $result->fetch_assoc();
                    $reply="<Items><Item><Images>";
                    $reply=cmp_uid($reply, $result, $row1, "ItemID");
                    //return the XML document to the requesting page
                    echo $reply;
                } else { //O results found
                    echo "<status>1</status>";
                }

            } else { //There's a problem with execution of the query
                echo "<status>2</status>"; //No results found
                echo "<errormsg>.$conn->error.</errormsg>";
            }
        }
        else if($table=='getUnits') {
            //Should return the set of units for a particular item
            //First, get the ItemID for the Item
            if(isset($_REQUEST['ItemID'])) {
                $itemID = filter($_REQUEST['ItemID']);
            } else {
                //Forgot to set the ItemID
                trigger_error("Forgot to set the ItemID");
            }
            $sql = "SELECT
            Transient.UnitID,
            Name as UnitName,
            NamePlural,
            Symbol,
            Minimum,
            Maximum,  
            Fractions,
            SI
            FROM (SELECT * FROM UnitsJunct WHERE UnitsJunct.ItemID = '".$itemID."')
            AS Transient LEFT JOIN Units ON Transient.UnitID = Units.UnitID
            ";
            $result = $conn->query($sql);
            if($result!=FALSE) {
                //Query was successful
                //Extract and XML encode
                $reply = "<Units>";
                if($result->num_rows>0) {
                    //Found some results
                    echo "<status>0</status>";
                    while($row = $result->fetch_assoc()) {
                        $reply.="<Unit>";
                        $reply.="<UnitID>".$row['UnitID']."</UnitID>";
                        $reply.="<UnitName>".$row['UnitName']."</UnitName>";
                        $reply.="<NamePlural>".$row['NamePlural']."</NamePlural>";
                        $reply.="<Symbol>".$row['Symbol']."</Symbol>";
                        $reply.="<Minimum>".$row['Minimum']."</Minimum>";
                        $reply.="<Maximum>".$row['Maximum']."</Maximum>";
                        $reply.="<Fractions>".$row['Fractions']."</Fractions>";
                        $reply.="<SI>".$row['SI']."</SI>";
                        $reply.="</Unit>";
                    }
                    //close reply and send
                    $reply.="</Units>";
                    echo $reply;

                } else {
                    //No results found
                    //Return appropriate return status
                    echo "<status>1</status>";
                }
            } else {
                //Query failed
                //Return corresponding return status in xml
                echo "<err>".$conn->error."</err>";
                echo "<status>2</status>";
            }
        }
        else if($table=='allUnits') {
            //Should return all the units in the Units table for matching with Items
            $sql = "SELECT
            * FROM Units
            ";
            $result = $conn->query($sql);
            if($result!=FALSE) {
                //Query was successful
                //Extract and XML encode
                $reply = "<Units>";
                if($result->num_rows>0) {
                    //Found some results
                    echo "<status>0</status>";
                    while($row = $result->fetch_assoc()) {
                        $reply.="<Unit>";
                        $reply.="<UnitID>".$row['UnitID']."</UnitID>";
                        $reply.="<UnitName>".$row['Name']."</UnitName>";
                        $reply.="<NamePlural>".$row['NamePlural']."</NamePlural>";
                        $reply.="<Symbol>".$row['Symbol']."</Symbol>";
                        $reply.="<Minimum>".$row['Minimum']."</Minimum>";
                        $reply.="<Maximum>".$row['Maximum']."</Maximum>";
                        $reply.="<Fractions>".$row['Fractions']."</Fractions>";
                        $reply.="<SI>".$row['SI']."</SI>";
                        $reply.="</Unit>";
                    }
                    //close reply and send
                    $reply.="</Units>";
                    echo $reply;

                } else {
                    //No results found
                    //Return appropriate return status
                    echo "<status>1</status>";
                }
            } else {
                //Query failed
                //Return corresponding return status in xml
                echo "<status>2</status>";
            }
        }
        else if($table=="closed_orders") {
            //Fetches the user's order 10 at a time starting with newest.
            getClosedOrders($conn);
        }
        else {
            echo "<status>4</status>";
            echo "<err>There's no code for the provided context ('table')</err>";
        }
    }
    else {
        echo "<status>3</status>"; //There's a problem with the connection to the database
    }
}
function getClosedOrders($conn) {
    //Access "ClosedOrders" and get all data there for this user
    $offset = $_POST['page_number'];
    $UserID = $_SESSION['UserID'];
    $sql = "SELECT Transient.OrderID AS OrderID,
            Transient.RepID AS RepID,
            Transient.ItemName AS ItemName,
            Transient.Quantity AS Quantity,
            Transient.Units AS Units,
            Transient.ClientID AS ClientID,
            Transient.Delivery AS Delivery,
            Transient.ClientRemarks AS ClientRemarks,
            Transient.OrderTime AS OrderTime,
            Transient.OrderExpiration AS OrderExpiration,
            Transient.OrderSerial AS OrderSerial,
            Transient.ImageURI AS ImageURI,
            Transient.ConfirmationStatus as Confirmation
            FROM
            (SELECT * FROM ClosedOrders WHERE SellerID='".$UserID."')
            AS Transient WHERE Transient.OrderSerial>".$offset." ORDER BY OrderSerial ASC LIMIT 10";
    $result = $conn->query($sql);
    if($result!==false) {
        if($result->num_rows>0) {
            $reply = "<orders>";
            while($row = $result->fetch_assoc()) {
                $reply.="<order>";
                $reply.="<orderid>".setdefault('OrderID', 'N/A', $row)."</orderid>";
                $reply.="<repid>".setdefault('RepID', 'N/A', $row)."</repid>";
                $reply.="<itemname>".setdefault('ItemName', 'N/A', $row)."</itemname>";
                $reply.="<quantity>".setdefault('Quantity', 'N/A', $row)."</quantity>";
                $reply.="<units>".setdefault('Units', 'N/A', $row)."</units>";
                $reply.="<clientid>".setdefault('ClientID', 'N/A', $row)."</clientid>";
                $reply.="<delivery>".setdefault('Delivery', 'N/A', $row)."</delivery>";
                $reply.="<clientremarks>".setdefault('ClientRemarks', 'N/A', $row)."</clientremarks>";
                $reply.="<ordertime>".setdefault('OrderTime', 'N/A', $row)."</ordertime>";
                $reply.="<expiration>".setdefault('OrderExpiration', 'N/A', $row)."</expiration>";
                $reply.="<orderserial>".setdefault('OrderSerial', 'N/A', $row)."</orderserial>";
                $reply.="<imageuri>".setdefault('ImageURI', 'N/A', $row)."</imageuri>";
                $reply.="<confirmation>".setdefault('Confirmation', 'N/A', $row)."</confirmation>";
                $reply.="</order>";
            }
            $reply.="</orders>";
            echo $reply;
            echo "<status>0</status>"; //Successfully completed!
            } else {
            //No results found
            echo "<status>1</status>";
        }
    } else {
        //Query failed
        echo "<err>".$conn->error."</err>";
        echo "<status>2</status>";
    }
}
//$reply is already initialized to "<Items><Item><Images>"
//Further TESTING needed
function cmp_uid($reply, $result, $row1, $uid) {
    //$uid is either "ItemID" or "RepID" depending on the calling function. It's hard to explain why. Good luck future me.
    $row2=$result->fetch_assoc();
    if($row2!=NULL) {
        //compare the ItemIDs
        if($row1[$uid]==$row2[$uid]) {
            //Same ItemID, different Image
            $reply.="<ImageData>";
            $reply.="<ImageID>".setdefault('ImageID', 'N/A', $row1)."</ImageID>";
            $reply.="<ImageURI>".setdefault('ImageURI',"icons/placeholder.png", $row1)."</ImageURI>";
            $reply.="</ImageData>";
            $row1=$row2;
            $reply=cmp_uid($reply, $result, $row1, $uid);
            return $reply;
        } else {
            //New ItemID
            $reply.="<ImageData>";
            $reply.="<ImageID>".setdefault('ImageID', 'N/A', $row1)."</ImageID>";
            $reply.="<ImageURI>".setdefault('ImageURI',"icons/placeholder.png", $row1)."</ImageURI>";
            $reply.="</ImageData>";
            $reply.="</Images>";
            $reply.="<Itemdata>";

            $reply.="<ItemID>".$row1['ItemID']."</ItemID>";
            $reply.="<ItemName>".$row1['ItemName']."</ItemName>";
            $reply.="<Aliases>".setdefault('Aliases', 'N/A', $row1)."</Aliases>";
            $reply.="<Category>".setdefault('Category', 'N/A', $row1)."</Category>";
            $reply.="<Description>".setdefault('Description', 'N/A', $row1)."</Description>";

            $reply.="<UserID>".setdefault('UserID', 'N/A', $row1)."</UserID>";
            $reply.="<DefaultDescription>".setdefault('DefaultDescription', 'N/A', $row1)."</DefaultDescription>";
            $reply.="<RepID>".setdefault('RepID', 'N/A', $row1)."</RepID>";
            $reply.="<Quantity>".setdefault('Quantity', 'N/A', $row1)."</Quantity>";
            $reply.="<Units>".setdefault('Units', 'N/A', $row1)."</Units>";
            $reply.="<UnitPrice>".setdefault('UnitPrice', 'N/A', $row1)."</UnitPrice>";
            $reply.="<State>".setdefault('State', 'N/A', $row1)."</State>";
            $reply.="<DateAdded>".setdefault('DateAdded', 'N/A', $row1)."</DateAdded>";
            $reply.="<Deliverable>".setdefault('Deliverable', 'N/A', $row1)."</Deliverable>";
            $reply.="<DeliverableAreas>".setdefault('DeliverableAreas', 'N/A', $row1)."</DeliverableAreas>";
            $reply.="<ImageURI>".setdefault('ImageURI', 'N/A', $row1)."</ImageURI>"; //The default image

            $reply.="</Itemdata>";
            $reply.="</Item>";
            $reply.="<Item>";
            $reply.="<Images>";
            $row1=$row2;
            $reply=cmp_uid($reply, $result, $row1, $uid);
            return $reply;
        }
    } else {
        //No more rows to fetch
        //Store the last row
        //Close the last <Images> tag
        $reply.="<ImageData>";
        $reply.="<ImageID>".setdefault('ImageID', 'N/A', $row1)."</ImageID>";
        $reply.="<ImageURI>".setdefault('ImageURI',"icons/placeholder.png", $row1)."</ImageURI>";
        $reply.="</ImageData>";
        $reply.="</Images>";
        $reply.="<Itemdata>";
        $reply.="<ItemID>".$row1['ItemID']."</ItemID>";
        $reply.="<ItemName>".$row1['ItemName']."</ItemName>";
        $reply.="<Aliases>".setdefault('Aliases', 'N/A', $row1)."</Aliases>";
        $reply.="<Category>".setdefault('Category', 'N/A', $row1)."</Category>";
        $reply.="<Description>".setdefault('Description', 'N/A', $row1)."</Description>";

        $reply.="<UserID>".setdefault('UserID', 'N/A', $row1)."</UserID>";
        $reply.="<DefaultDescription>".setdefault('DefaultDescription', 'N/A', $row1)."</DefaultDescription>";
        $reply.="<RepID>".setdefault('RepID', 'N/A', $row1)."</RepID>";
        $reply.="<Quantity>".setdefault('Quantity', 'N/A', $row1)."</Quantity>";
        $reply.="<Units>".setdefault('Units', 'N/A', $row1)."</Units>";
        $reply.="<UnitPrice>".setdefault('UnitPrice', 'N/A', $row1)."</UnitPrice>";
        $reply.="<State>".setdefault('State', 'N/A', $row1)."</State>";
        $reply.="<DateAdded>".setdefault('DateAdded', 'N/A', $row1)."</DateAdded>";
        $reply.="<Deliverable>".setdefault('Deliverable', 'N/A', $row1)."</Deliverable>";
        $reply.="<DeliverableAreas>".setdefault('DeliverableAreas', 'N/A', $row1)."</DeliverableAreas>";
        $reply.="<ImageURI>".setdefault('ImageURI', 'N/A', $row1)."</ImageURI>"; //The default image

        $reply.="</Itemdata>";
        $reply.="</Item>";
        $reply.="</Items>";
        return $reply;
    }
}
function placeClosedOrder($id) { //No distinction between ClientID or UserID. All treated as $id
    //$id_type tells whether the id belongs to the client or
    $servername = "localhost";
    $username = "aman";
    $password = "password";
    $database = "test";
    $conn = new mysqli($servername, $username, $password, $database);
    if(!$conn->connect_error) {
        //Get the form data: quantity, expdate, delivery, comments, itemid
        $quantity = setdefault("quantity", "1", $_POST);
        $expdate = setdefault("expdate", "2018-01-16", $_POST); //Default date will be set in javascript
        $delivery =  $_POST['delivery'];
        $comments = $_POST['comments'];
        $repid = $_POST['repid'];
        $units = $_POST['units'];
        $itemname = $_POST['itemname'];
        $sellerid = $_POST['sellerid']; //This will be passed to the function that will update the users' new orders
        $imageuri = $_POST['imageuri'];
        //And now generate the dynamic data: orderid
        $orderid = uniqid("c");
        //Prepare the sql
        $sql = "INSERT INTO ClosedOrders(OrderID, RepID, SellerID, ItemName, Quantity, Units, ClientID,
                Delivery, ClientRemarks, OrderExpiration, ImageURI) VALUES 
                ('".$orderid."', '".$repid."', '".$sellerid."', '".$itemname."', '".$quantity."', '".$units."', '".$id."', '".$delivery."', '".$comments."', '".$expdate."', '".$imageuri."')";
        $result = $conn->query($sql);
        if($result!==FALSE) {
            //Call the function that updates the users number of unseen orders
            updateNewOrders($sellerid, $conn);
            echo "<status>0</status>";
        } else {//Query failed
            echo "<msg>".$conn->error."</msg>";
            echo "<status>2</status>";
        }
    } else {
        echo "<status>3</status>"; //There's a problem with the connection to the database
    }
}
function updateNewOrders($sellerid, $conn) {
    //This function goes to the table "Users" and updates just one value; "NewOrders"
    $sql = "SELECT NewOrders FROM Users WHERE UserID='".$sellerid."'";
    $result = $conn->query($sql);
    if($result!==false) {
        if($result->num_rows>0) {
            $curr_new_orders = $result->fetch_assoc()['NewOrders'];
            if($curr_new_orders===null) {
                $curr_new_orders = 1;
            } else {
                $curr_new_orders = $curr_new_orders+1; //increment
            }
            //Put back
            $sql = "UPDATE Users SET NewOrders='".$curr_new_orders."' WHERE UserID='".$sellerid."'";
            $rslt = $conn->query($sql); //No need to check the success/failure. Not important
            if($rslt===false) {
                echo "<msg>Something went wrong</msg>";
                echo "<err>$conn->error</err>";
            } else {
                echo "<msg>Number of new orders updated successfully</msg>";
            }
        } else {
            echo "<msg>Unable to update the new orders</msg>";
            echo "<err>$conn->error</err>";
        }
    } else {
        //Nothing much I can do. This isn't that important
        echo "<msg>Unable to update the new orders</msg>";
        echo "<err>$conn->error</err>";
    }
}
//Return Statuses summary
//0. Query was successful, results were found
//1. Query was successful, results not found
//2. Query was unsuccessful
//3. Connection to database failed
//4. No methods provided for given context "table"
//11. Context "table" not set. Programmer error
//Flags for improvement and completion
//1. INCOMPLETE
//2. LATER
//3. TESTING