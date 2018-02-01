<?php
//This script is for handling all messages between clients and sellers.
include "customErrorHandler.php";
set_error_handler(customErrorHandler); //Setting error handler
//Start session
session_start();
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
//Check for already existing sessionID
if(isset($_SESSION["UserID"]) || isset($_SESSION["ClientID"])) { //URGENT: Set appropriate session id
    //A session already exists.
    //Use the session ID to
    if($_SESSION["UserID"]) {
        $myID = $_SESSION["UserID"];
    } else {
        //It's one or the other. It's been checked already
        $myID = $_SESSION["ClientID"];
    }
    //There's need to send the recepient userID.
    //Get the context and perform the action
    $context = filter($_POST['context']) or die("<msg>Context is not set!</msg><returnstatus>2</returnstatus>");
    //Start with the receiving the sent message
    if($context=="send") {
        //Receiving a sent message
        //Get the recepient ID
        $recepID = filter($_POST['recepID']) or die("<msg>recepID is not set!</msg><returnstatus>2</returnstatus>");
        //Get the message text
        $msgText = addslashes(filter($_POST['msgText'])) or die("<msg>msgText is not set</msg><returnstatus>3</returnstatus>"); //URGENT: Find a way to deal with null messages
        //Generate the "ChannelID" by concatenating myID and recepID in alphabetical order, smaller one first
        if(strcmp($myID, $recepID)>0) {
            //myID is lexicographically smaller, i.e comes first alphabetically
            $channelID = $myID.$recepID;
            echo "<msg>".$myID.">".$recepID."</msg>";
        } else {
            $channelID = $recepID.$myID;
            echo "<msg>".$recepID.">".$myID."</msg>";
        }
        //Open a connection to the database
        $servername = "localhost";
        $username = "aman";
        $password = "password";
        $database = "test";

        $pictureID = "";
        $imageURI = "";
        $conn = new mysqli($servername, $username, $password, $database);
        if(!$conn->connect_error) {
            //Connected successfully
            //Create sql statement
            $sql = "INSERT INTO Messages(ChannelID, MsgText, PictureID, ImageURI, SenderID, ReceiverID) 
                    VALUES ('".$channelID."', '".$msgText."', '".$pictureID."', '".$imageURI."',
                    '".$myID."', '".$recepID."')";

            if($conn->query($sql)) {
                 //Successfully executed
                 //But wait...
                if(preg_match("/C[[:alnum:]]/", $recepID)) {
                    //Client account. Go there and update the Unread Messages count
                    $sql = "SELECT UnreadMessages FROM Clients WHERE ClientID='".$recepID."'";
                    $result = $conn->query($sql);
                    if($result) {
                      $row=$result->fetch_assoc();
                      $msg_count = $row['UnreadMessages'];
                      $msg_count++;
                      $sql = "UPDATE Clients SET UnreadMessages='".$msg_count."' WHERE ClientID='".$recepID."'";
                      //No need for success checks. Not significant.
                        $conn->query($sql);
                    } else {
                        echo "<msgerror>The query failed??</msgerror>";
                    }
                } else if (preg_match("/U[[:alnum:]]/", $recepID)) {
                    //Member account
                    $sql = "SELECT UnreadMessages FROM Users WHERE UserID='".$recepID."'";
                    $result = $conn->query($sql);
                    if($result) {
                        $row = $result->fetch_assoc();
                        $msg_count = $row['UnreadMessages'];
                        $msg_count++;
                        $sql = "UPDATE Users SET UnreadMessages='".$msg_count."' WHERE UserID='".$recepID."'";
                        //No need for success checks. Not significant.
                        $conn->query($sql);
                    } else {
                        echo "<msgerror>The query failed??</msgerror>";
                    }
                }
                echo "<msgerror>".$conn->error."</msgerror>";
                echo "<returnstatus>0</returnstatus>";
            } else {
                echo "<msg>".$conn->error."</msg>";
                echo "<returnstatus>2</returnstatus>";
            }
        } else {
            echo "<msg>Connection to the database was not successful</msg>";
            echo "<returnstatus>2</returnstatus>";
        }
    }
    else if($context=="fetch") {
        //fetching messages to the view port
        //fetch 10 messages at a time. HOW?
        //get the recepID and offset
        $recepID = filter($_POST['recepID']) or die("<msg>recepID is not set!</msg><returnstatus>2</returnstatus>");
        $offset = filter($_POST['offset']) or die("<msg>offset is not set!</msg><returnstatus>2</returnstatus>");
        //Fetch messages whose MsgSerial is greater than the offset
        //Limit the number to the top 10 and sort by the MsgSerial in ascending order
        //But first, let's get the channelID
        if(strcmp($myID, $recepID)>0) {
            //myID is lexicographically smaller, i.e comes first alphabetically
            $channelID = $myID.$recepID;
            echo "<msg>".$myID.">".$recepID."</msg>";
        } else {
            $channelID = $recepID.$myID;
            echo "<msg>".$recepID.">".$myID."</msg>";
        }
        //open a connection
        $servername = "localhost";
        $username = "aman";
        $password = "password";
        $database = "test";

        $conn = new mysqli($servername, $username, $password, $database);
        //Check for any errors
        if(!$conn->connect_error) {
            //No connection errors
            $sql = "SELECT
                    Transient.ChannelID AS ChannelID,
                    Transient.TimeStamp AS TimeSent,
                    Transient.MsgText AS MsgText,
                    Transient.PictureID AS PictureID,
                    Transient.ImageURI AS ImageURI,
                    Transient.SenderID AS SenderID,
                    Transient.MsgSerial AS SerialNo
                    FROM ( SELECT * FROM Messages WHERE ChannelID='".$channelID."') 
                    AS Transient WHERE Transient.MsgSerial>".$offset." ORDER BY SerialNo ASC LIMIT 10";
            $result = $conn->query($sql);
            if($result) {
                //$result contains an associative array of results.
                //Now check the array is not empty
                if($result->num_rows>0) {
                    //Got some results
                    $reply = "";
                    //Encode all the messages into $reply here
                    while($row=$result->fetch_assoc()) {
                        if($row['SenderID']==$myID) {
                            $senderID = "outbound";
                        } else {
                            $senderID = "inbound";
                        }
                        $reply.="<message>";
                            $reply.="<timesent>".$row['TimeSent']."</timesent>";
                            $reply.="<msgtext>".$row['MsgText']."</msgtext>";
                            $reply.="<pictureid>".$row['PictureID']."</pictureid>";
                            $reply.="<imageuri>".$row['ImageURI']."</imageuri>";
                            $reply.="<sender>".$senderID."</sender>";
                            $reply.="<serial>".$row['SerialNo']."</serial>";
                        $reply.="</message>";
                    }
                    //Return reply.
                    echo "<returnstatus>0</returnstatus>"; //Perfection!!
                    echo $reply;
                } else {
                    echo "<returnstatus>4</returnstatus>"; //No results
                }
            } else {
                //The query failed. Complain. LOUDLY
                echo "<msg>The mysql query failed: ".$conn->error."</msg>";
                echo "<returnstatus>2</returnstatus>";
            }
        } else {
            echo "<msg>Connection to the database was not successful</msg>";
            echo "<returnstatus>2</returnstatus>";
        }
    }
    else if($context=="get_recep_name") {
        //get the recepID and offset
        $recepID = filter($_POST['recepID']) or die("<msg>recepID is not set!</msg><returnstatus>2</returnstatus>");
        //open a connection
        echo "<recepID>$recepID</recepID>";
        $servername = "localhost";
        $username = "aman";
        $password = "password";
        $database = "test";

        $conn = new mysqli($servername, $username, $password, $database);
        //Check for any errors
        if(!$conn->connect_error) {
            //Here on the server side, the only way to tell the type of account the sender/receiver possesses
            //is by looking at their IDs. If the id begins with a U, it's a members' account. If it's with a 'c',
            //it's a client's account. This tells us which table to check when looking for the details of the
            if(preg_match("/C[[:alnum:]]/", $recepID)) {
                //The recepient has a clients' account.
                $sql = "SELECT DisplayName 
                                FROM Clients WHERE ClientID='".$recepID."'";
                $result = $conn->query($sql);
                if($result) {
                    //query was successful
                    $row = $result->fetch_assoc();
                    $reply.="<recepname>".$row['DisplayName']."</recepname>";
                    echo "<returnstatus>0</returnstatus>"; //Perfection!!
                }
                else {
                    echo "<msg>Recepient name query unsuccessful for Client</msg>";
                    echo "<returnstatus>2</returnstatus>";
                }
            } else if(preg_match("/U[[:alnum:]]/", $recepID)) {
                //The recepient has a members' account
                $sql = "SELECT FirstName 
                                FROM Users WHERE UserID='".$recepID."'"; //Later: FirstName can be Null if the Javascript constraints are bypassed
                $result = $conn->query($sql);
                if($result) {
                    //query was successful
                    $row = $result->fetch_assoc();
                    $reply.="<recepname>".$row['FirstName']."</recepname>";
                    echo "<returnstatus>0</returnstatus>"; //Perfection!!
                    echo $reply;
                }
                else {
                    echo "<msg>Recepient name query unsuccessful for Client</msg>";
                    echo "<returnstatus>2</returnstatus>";
                }
            } else {
                echo "<err>There is a problem with the recepID</err>";
            }
        } else {
            echo "<msg>Connection Error</msg>";
            echo "<returnstatus>2</returnstatus>";
        }
    }
    else if($context==="inbox") {
        //Name of recepient, last message sent + time + read status, recepient profile picture,
        $inbxMax = filter($_POST['inboxMax']) or die("<msg>offset is not set!</msg><returnstatus>2</returnstatus>");
        //Fetch messages whose MsgSerial is greater than the inbox-offset

        //open a connection
        $servername = "localhost";
        $username = "aman";
        $password = "password";
        $database = "test";

        $conn = new mysqli($servername, $username, $password, $database);
        if(!$conn->connect_error) {
            //Successfully connected
            if($inbxMax<10) {
                //First time accessing the db. $inbxMax is for pagination. ChannelID has to be Unique for each connection.
                $sql = "SELECT * FROM (SELECT MAX(MsgSerial) AS LastMsg FROM (SELECT * FROM Messages WHERE 
                    SenderID='".$myID."' OR ReceiverID='".$myID."') AS Table1 GROUP BY ChannelID)
                    AS Table2 LEFT JOIN Messages ON Table2.LastMsg=Messages.MsgSerial ORDER BY Messages.MsgSerial DESC";
            } else {
                $sql = "SELECT * FROM (SELECT MAX(MsgSerial) AS LastMsg FROM (SELECT * FROM Messages WHERE 
                    SenderID='".$myID."' OR ReceiverID='".$myID."') AS Table1 GROUP BY ChannelID)
                    AS Table2 LEFT JOIN Messages ON Table2.LastMsg=Messages.MsgSerial WHERE 
                    Table2.LastMsg<'".$inbxMax."' ORDER BY Messages.MsgSerial DESC";
            }

            //Query
            $result = $conn->query($sql);
            if($result!==false) {
                //Successfully executed
                //Now retrieve the profile picture and name of each Sender/Receiver that's not me
                $reply = "<messages>"; //To hold the XML;
                while($row=$result->fetch_assoc()) {
                    //Fetch row by row
                    //Get the ID of the other person that's not me. Could be the SenderID if they sent the message
                    //Or the ReceiverID if I sent the message. Store that shit in $receiver
                    if($row['SenderID']===$myID) {
                        //Use the recepient
                        $receiver = $row['ReceiverID'];
                    } else {
                        $receiver = $row['SenderID'];
                    }
                    //Now check if person is client or Member. Use PCRE
                    if(preg_match("/C[[:alnum:]]/", $receiver)) {
                        //receiver possesses client account
                        //Access Clients table in db for user info
                        $sql = "SELECT Honorific, DisplayName FROM Clients WHERE ClientID='".$receiver."'";
                        $result_tmp = $conn->query($sql);
                        if($result_tmp!==false) {
                            if($result_tmp->num_rows===1) {
                                $row_tmp = $result_tmp->fetch_assoc();
                                $reply.="<message>";
                                    $reply.="<msgserial>".$row['MsgSerial']."</msgserial>";
                                    $reply.="<timestamp>".$row['TimeStamp']."</timestamp>";
                                    $reply.="<msgtext>".$row['MsgText']."</msgtext>";
                                    $reply.="<imageuri>".$row['ImageURI']."</imageuri>";
                                    if($row['SenderID']==$myID) {
                                        $senderID = "outbound";
                                    } else {
                                        $senderID = "inbound";
                                    }
                                    $reply.="<senderid>".$senderID."</senderid>";
                                    $reply.="<theirid>".$receiver."</theirid>";
                                    $reply.="<honorific>".$row_tmp['Honorific']."</honorific>";
                                    $reply.="<name>".$row_tmp['DisplayName']."</name>";
                                $reply.="</message>";
                            } else {
                                echo "<msg>".$result_tmp->num_rows." results were found when exactly 1 expected!</msg>";
                                die("<returnstatus>2</returnstatus>");
                            }
                        } else {
                            echo "<msg>A secondary query failed</msg>";
                            echo "<err>".$conn->error."</err>";
                            die("<returnstatus>2</returnstatus>");
                        }
                    } else if(preg_match("/U[[:alnum:]]/", $receiver)) {
                        //receiver posseses Member account
                        //Access Users table in
                        //receiver possesses client account
                        //Access Clients table in db for user info
                        $sql = "SELECT FirstName, LastName FROM Users WHERE UserID='".$receiver."'";
                        $result_tmp = $conn->query($sql);
                        if($result_tmp!==false) {
                            if($result_tmp->num_rows===1) {
                                $row_tmp = $result_tmp->fetch_assoc();
                                $reply.="<message>";
                                $reply.="<msgserial>".$row['MsgSerial']."</msgserial>";
                                $reply.="<timestamp>".$row['TimeStamp']."</timestamp>";
                                $reply.="<msgtext>".$row['MsgText']."</msgtext>";
                                $reply.="<imageuri>".$row['ImageURI']."</imageuri>";
                                if($row['SenderID']==$myID) {
                                    $senderID = "outbound";
                                } else {
                                    $senderID = "inbound";
                                }
                                $reply.="<senderid>".$senderID."</senderid>";
                                $reply.="<theirid>".$receiver."</theirid>";
                                $reply.="<honorific>na</honorific>";
                                $reply.="<name>".$row_tmp['FirstName']." ".$row_tmp['LastName']."</name>";
                                $reply.="</message>";
                            } else {
                                echo "<msg>".$result_tmp->num_rows." results were found when exactly 1 expected!!</msg>";
                                die("<returnstatus>2</returnstatus>");
                            }
                        } else {
                            echo "<msg>A secondary query failed</msg>";
                            echo "<err>".$conn->error."</err>";
                            die("<returnstatus>2</returnstatus>");
                        }
                    }
                }
                $reply .= "</messages>";
                echo "<returnstatus>0</returnstatus>";
                echo $reply;
            } else {
                echo "<msg>The inbox query failed</msg>";
                echo "<err>".$conn->error."</err>";
                echo "<returnstatus>2</returnstatus>";
            }
        } else {
            echo "<msg>Connection Error</msg>";
            echo "<err>".$conn->error."</err>";
            echo "<returnstatus>2</returnstatus>";
        }
    }
} else {
    //Prompt the user to log in or sign up
    //So we can get an session ID for their identification
    //End the script here
    echo "<returnstatus>1</returnstatus>";
}
function filter($entry) {
    //Filter user input to safeguard against XXS and SQL injections.
    $entry = htmlspecialchars($entry); //Against any XSS and SQL injections
    $entry = trim($entry); //Against SQL injections
    $entry = stripslashes($entry);
    return $entry; //Sanitized input
}
//RETURN STATUSES EXPLAINED
//1. The user is not logged in
//2. A programming error or attempted system compromise or Connection error
//3. Empty message
?>

