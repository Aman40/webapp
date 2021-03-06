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
                    <a id="itm_add" href="#" onclick="switch_panes(this)">Add</a>
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
                <input type="file" id="upload_item_images" multiple onchange="display_item_images(this, 'add')">
            </form>
            <div id="show_progress">
                <span onclick="uploadCanvasImages()" style="height:30px; background-color: burlywood; cursor:pointer;">Next</span>
            </div>
            <div id="up_imgs_container">

            </div>
        </div>
        <div id="db_display"><!--This displays all the items by default. Select a disired item to edit in a modal-->
            <!--Or search for item name or group name to edit in modal-->
            <div id="ctrl_div"><!--The control with the search button and display controls, if any-->
                <div id="srch_div">
                    <input id="srch_div_input" type="text" placeholder="Search the catalog..." onkeydown="_checkenterkey2(event)">
                    <button onclick="srch_dbfor_nondistinct_items()">Search</button>
                </div>
            </div>
            <div id="dsp_div"><!--The display div where the items are displayed. The real editing happens in a modal-->
                Display here
            </div>
        </div>
    </div> <!--row-2-->
    <div id="row-3"> <!--This will contain the footer-->
        <div id="r3-overlay"><!--Totally empty!-->
            <div class="footnote-col">&copy; Farmer's Marketting Hub 2017</div>
            <div class="footnote-col">Contact</div>
            <div class="footnote-col">Privacy Policy</div>
        </div>
    </div><!--row-3-->
</div><!--Main wrapper-->
<div id="db_modal" class="modal">
    <div class="modal-content">
        <div id="db_image"><!--This is for the image-->

        </div>
        <div id="db_info"><!--This is for the info-->
            <form>
                <label>Item name:</label><br>
                <input type="text" name="ItemName" id="db_itemname"><br>
                <label>Other names:</label>
                <input type="text" name="OtherNames" id="db_othernames">
                <label>Category</label>
                <input type="text" name="Category" id="db_category">
                <label>Description:</label>
                <input type="text" name="Description" id="db_description">
            </form>

        </div >

        <div id="db_units"><!--For the units-->
            <div id="db_units_all">
                <!--Displays all the current units-->
            </div>
            <div id="db_units_selected">
                <!--Displays the units selected for the item being edited-->
            </div>

        </div>
        <div id="db_images"><!--Display all the item's images here and add an option for adding more images-->

        </div>
        <div id="db_img_up">
            <div id="db_img_up_ctr">
                <label>Images:</label><br>
                <input type="file" id="db_imgs_up" multiple onchange="display_item_images(this, 'edit')">
                <span id="db_up_btn" style="height:30px; background-color: burlywood; cursor:pointer;">Click to upload</span>
            </div>
            <div id="db_img_up_dsp">

            </div>

        </div>
    </div>
</div>

<!--*************************************************************************-->
<script src="Profiles/index.js"></script>
<script>
    // Get the modal
    var modal = document.getElementById("db_modal");
    var parentNode = modal.parentNode;
    var modal_clone = modal.cloneNode(true); //This is not gonna change. It holds the initial state of the modal, to which the modal will be
    //reset after closing, i.e destroying and recreating it.
    // When the user clicks anywhere outside of the modal content yet inside the modal, close it.
    //Found a solution. Get the element as a global at the start, and always reset to that after closing the modal.
    window.onclick = function(event) {
        if(event.target == modal) {
            parentNode.removeChild(modal); //Destroy the modal
            modal = modal_clone.cloneNode(true); //Clone the element in it's original state
            parentNode.appendChild(modal); //Respawn
        }

    }
</script>
</body>
</html>
