<script type="text/javascript">
    <!--
    if (screen.width <= 1000) {
        window.location = "http://localhost/HTML/mobile.php";
    }
    //-->
</script>
<!doctype html>
<?php
include "include.php";
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="fmh.css">
</head>
<body>
<div id="main-wrapper">
    <div id="row-1">

        <div class="col-12" id="r1c2">
            <div id="r1c2r1">
                <div class="col-3" id="r1c1">
                    <h1>
                        F<span>MH</span>
                    </h1>
                </div><!--r1c1-->

                <div id="min-prof">

                </div><!--min-prof-->
            </div><!--r1c2r1-->
            <div id="r1c2r2"><!--Insert an unorered list here for the menu-->
                <div id="hor-menu">

                </div><!--hor-menu-->

            </div><!--r1c2r2-->
        </div><!--r1c2-->

    </div><!--row-1-->
    <div id="row-2">
        <div id="dbeditor_form">
            <form>
                <label>Item name:</label><br>
                <input type="text" name="ItemName" id="itemname"><br>
                <label>Other names:</label>
                <input type="text" name="OtherNames" id="othernames">
                <label>Category</label>
                <input type="text" name="Category" id="category">
                <label>Description:</label>
                <input type="text" name="Description" id="description">
                <label>Images:</label><br>
                <input type="file" id="upload_item_images" multiple onchange="display_item_images(this)">
            </form>
            <div id="show_progress">
                <span onclick="uploadCanvasImages()" style="height:30px; background-color: burlywood; cursor:pointer;">Click to upload</span>
            </div>
            <div id="up_imgs_container">

            </div>
        </div>
        <script>


        </script>
    </div> <!--row-2-->
    <div id="row-3"> <!--This will contain the footer-->
        <div id="r3-overlay"><!--Totally empty!-->
            <div class="footnote-col">&copy; Farmer's Marketting Hub 2017</div>
            <div class="footnote-col">Contact</div>
            <div class="footnote-col">Privacy Policy</div>
        </div>
    </div><!--row-3-->
</div><!--Main wrapper-->

<!--*************************************************************************-->
<script src="Profiles/index.js"></script>
<script>
    // Get the modal
    var modalin = document.getElementById('id01'); //The signin modal
    var modalup = document.getElementById('id02'); //The signup modal
    var modalOrder = document.getElementById('orderItem');

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modalup) {
            console.log("It's the modalup");
            modalup.style.display = "none";
        } else if (event.target === modalin) {
            console.log("It's the modalin");
            modalin.style.display = "none";
        } else if (event.target === modalOrder){
            modalOrder.style.display = "none";
        }
    }
</script>
</body>
</html>
