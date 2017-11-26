<?php
//This script is for handling all messages between clients and sellers.
include "customErrorHandler.php";
set_error_handler(customErrorHandler.php); //Setting error handler
//Start session
session_start();
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
} else {
    //Prompt the user to log in or sign up
    //So we can get an session ID for their identification
    //End the script here
    return "<returnstatus>1</returnstatus>";
}
//RETURN STATUSES EXPLAINED
//1. The user is not logged in
?>

