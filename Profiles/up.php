<?php
session_start();
if(!isset($_SESSION['UserID'])) {
    trigger_error("Nice try bro!");
    echo "Nice try";
    //later
} else {
    echo "<script>console.log('Logged in. Proceeding...');</script>";
}
if(isset($_FILES['myfile'])) {
    echo "The file was received";
    echo "The file name is ".$_FILES['myfile']['name'];

    try {
        if(move_uploaded_file($_FILES['myfile']['tmp_name'], "/var/www/html/HTML/Profiles/Pictures/".$_SESSION['UserID'])) {
            echo "The file was successfully moved";
        } else {
            echo "The file couldn't be copied. Most probably permission problems.";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} else {
    echo "The file wasn't received";
}
?>