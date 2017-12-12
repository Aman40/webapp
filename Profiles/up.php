<?php
session_start();
if(!isset($_SESSION['UserID'])) {
    trigger_error("Nice try bro!");
    echo "<msg>Nice try</msg>";
    //later
} else {
    echo "<msg>console.log('Logged in. Proceeding...');</msg>";
}
if(isset($_FILES['myfile'])) {
    echo "<msg>The file was received</msg>";
    echo "<msg>The file name is ".$_FILES['myfile']['name']."</msg>";

    try {
        if(move_uploaded_file($_FILES['myfile']['tmp_name'], "/var/www/html/HTML/Profiles/Pictures/".$_SESSION['UserID'])) {
            echo "<msg>The file was successfully moved</msg>";
        } else {
            echo "<msg>The file couldn't be copied. Most probably permission problems.</msg>";
        }
    } catch (Exception $e) {
        echo "<msg>".$e->getMessage()."</msg>";
        echo "<returnstatus>1</returnstatus>";
    }
} else {
    echo "<msg>The file wasn't received</msg>";
}