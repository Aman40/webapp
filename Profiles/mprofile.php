<!doctype html>
<?php
include "../include.php";
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="mprofile.css">
    <script src="index.js"></script>
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
                    <div id="min-prof">

                        <div id="prof-pic">
                            <div id="prof-pic-img">
                                <?php
                                if($session_exists) {
                                    echo '<img src="Pictures/'.$_SESSION["UserID"].'">';
                                } else {
                                    echo '<img src="../icons/profile-pic-male.jpg">';
                                }
                                ?>
                            </div><!--prof-picimg-->
                        </div><!--prof-pic-->
                    </div><!--min-prof-->

                </div><!--r1c1-->

            </div><!--r1c2r1-->
            <div class="full-width" id="r1c2r2"><!--Insert an unordered list here for the menu-->
                <div id="hor-menu">
                    <div class="full-width">
                        <a href="../fmh.php">Feed</a>
                        <a href="#">Home</a>
                        <a href="#">About Us</a>
                    </div>

                </div><!--hor-menu-->

            </div><!--r1c2r2-->
        </div><!--r1c2-->

    </div><!--row-1-->
    <div id="row-2">
        <div class="col-9" id="r2c2">
            <div class="col-12 r2c2row">
                <div class="col-12" id="prof-page-main">
                    <div class="col-12" id="tab-row">
                        <a href="javascript:reveal1hide23('prof-container', 'inventory-container', 'prof-orders')" id="tab-1">Profile</a>
                        <a href="javascript:reveal1hide23('inventory-container', 'prof-container', 'prof-orders')" id="tab-2">Inventory</a>
                        <a href="javascript:reveal1hide23('prof-orders', 'inventory-container', 'prof-container')" id="tab-3">Messages</a>
                        <a href="javascript:void(0)" id="tab-4">Orders</a>
                        <a href="javascript:void(0)" id="tab-4">Pictures</a>
                    </div><!--tab-row-->
                    <div class="col-12 prof-content-row" id="prof-container">
                        <?php
                        if(!$session_exists) {
                            echo "<i>You have nothing to show!</i>";
                        } else {
                            echo
                                "
											<table id='profile_table'>
												<tr>
													<td><b>First Name:</b>
													<td>".isset_or_edit('FirstName')."
												</tr>
												<tr>
													<td><b>Middle Name:</b>
													<td>".isset_or_edit('MiddleName')."
												</tr>
												<tr>
													<td><b>Last Name:</b>
													<td>".isset_or_edit('LastName')."
												</tr>
												<tr>
													<td><b>Sex:</b>
													<td>".isset_or_edit('Sex')."
												</tr>
												<tr>
													<td><b>Birth Date:</b>
													<td>".isset_or_edit('DoB')."
												</tr>
												<tr>
													<td><b>Company name:</b>
													<td>".isset_or_edit('CoName')."
												</tr>
												<tr>
													<td><b>Email Address:</b>
													<td>".isset_or_edit('Email')."
												</tr>
												<tr>
													<td><b>Physical Address:</b>
													<td>".isset_or_edit('Address')."
												</tr>
												<tr>
													<td><b>District:</b>
													<td>".isset_or_edit('District')."
												</tr>
												<tr>
													<td><b>Website:</b>
													<td>".isset_or_edit('Website')."
												</tr>
												<tr>
													<td><b>Phone No.:</b>
													<td>".isset_or_edit('PhoneNo')."
												</tr>
												<tr>
													<td><b>About:</b>
													<td>".isset_or_edit('About')."
												</tr>
											</table>
										";
                            echo "
										<span id='edit-prof-data'>
											<a href='javascript:void(0)' onclick='document.getElementById(\"id02\").style.display=\"block\"'>
											<i class='fa fa-edit'></i>
											Edit
											</a>
										</span>";
                        }
                        function isset_or_edit ($variable) {
                            if(isset($_SESSION[$variable]) && $_SESSION[$variable]!= null) {
                                return $_SESSION[$variable];
                            } else {
                                return '';
                            }
                        }
                        ?>
                    </div><!-- prof-container-->
                    <div class="col-12 prof-content-row" id="inventory-container">

                        <div class="col-12" id="inventory-display">
                            <!--Send a request to the server for the data in the Repository table -->
                            <!--Actual search and fill in of data happens in the javasript script-->

                        </div><!--display-search-results-->
                        <div class="col-12" id="inventory-update">
                            <div class="col-4" id="inventory-browse"><!--invisible until user clicks add-->
                                <div class="col-12" id="inventory-search">
                                    <div class="full-width" id="searchdiv">
                                        <form class="search">
                                            <input type="text" id="search-input" onkeydown="_checkenterkey(event)" name="search" placeholder="Search..">
                                            <input id="uglyButton" style="display: none;" type="button" onclick="_searchdb(document.getElementById('search-input').value)" value="Search">
                                            <span id="beaut">Go</span>
                                            <script>
                                                var ugly = document.getElementById("uglyButton");
                                                var beaut = document.getElementById("beaut");
                                                beaut.onclick = function () {
                                                    ugly.click();
                                                }
                                            </script>

                                        </form>
                                    </div>

                                    <!--Script1-->
                                </div><!--Search by search-->
                                <div class="col-12" id="categories"> <!--search by category-->
                                    <div class="col-12" id="inventory-crops">
                                        <div class="col-12 lvl-1" onclick="hide_show('inventory-crops')">
                                            <i class="fa fa-caret-right"></i>
                                            Crops
                                        </div>

                                        <div class="col-12 inventory-hidden">

                                            <div class="col-12" id="inventory-food">
                                                <div class="col-12 lvl-2" onclick="hide_show('inventory-food')">
                                                    <i class="fa fa-caret-right	"></i>
                                                    Food crops
                                                </div>
                                                <div class="col-12 inventory-hidden"><!--Group starts here-->
                                                    <div class="col-12" id="starchy">
                                                        <!--Script2-->

                                                        <div class="col-12 lvl-3" onclick="hide_show('starchy')">
                                                            <i class="fa fa-caret-right	"></i>
                                                            Starchy foods
                                                        </div>
                                                        <div class="col-12 inventory-hidden">
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Bananas')">
                                                                Bananas/Matooke
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Cassava')">
                                                                Cassava
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Rice')">
                                                                Rice
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Sweet Potatoes')">
                                                                Sweet Potatoes
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Irish Potatoes')">
                                                                Irish Potatoes
                                                            </div>
                                                        </div>
                                                    </div><!--starchy-->
                                                    <div class="col-12" id="fruits">
                                                        <div class="col-12 lvl-3" onclick="hide_show('fruits')">
                                                            <i class="fa fa-caret-right	"></i>
                                                            Fruits
                                                        </div>
                                                        <div class="col-12 inventory-hidden">
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Yellow Bananas')">
                                                                Yellow Bananas
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Passion Fruits')">
                                                                Passion Fruits
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Tomatoes')">
                                                                Tomatoes
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Avocadoes')">
                                                                Avocadoes
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Egg Plant')">
                                                                Egg Plant
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Plantain')">
                                                                Plantain/Gonja
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Paprika')">
                                                                Paprika
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Mangoes')">
                                                                Mangoes
                                                            </div>
                                                        </div>
                                                    </div><!--fruits-->
                                                    <div class="col-12" id="veggies">
                                                        <div class="col-12 lvl-3" onclick="hide_show('veggies')">
                                                            <i class="fa fa-caret-right	"></i>
                                                            Vegetables
                                                        </div>
                                                        <div class="col-12 inventory-hidden">
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Cabbage')">
                                                                Cabbage
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Amaranthus')">
                                                                Dodo/Amaranthus
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Nakati')">
                                                                Nakati
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Sukuma Wiki')">
                                                                Sukuma Wiki
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Lettuce')">
                                                                Lettuce
                                                            </div>
                                                        </div>
                                                    </div><!--veggies-->
                                                    <div class="col-12" id="legumes">
                                                        <div class="col-12 lvl-3" onclick="javascript:hide_show('legumes')">
                                                            <i class="fa fa-caret-right	"></i>
                                                            Legumes
                                                        </div>
                                                        <div class="col-12 inventory-hidden">
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Beans')">
                                                                Beans
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Ground Nuts')">
                                                                Ground Nuts/Pea Nuts
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Peas')">
                                                                Peas
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Lentils')">
                                                                Lentils
                                                            </div>
                                                            <div class="col-12 lvl-4" onclick="_searchdb('Soy Beans')">
                                                                Soy Beans
                                                            </div>
                                                        </div><!--hidden-->
                                                    </div><!--legumes-->

                                                </div><!--Group ends here-->

                                            </div><!--inventory-food-->

                                            <div class="col-12" id="inventory-cash">
                                                <div class="lvl-2" onclick="javascript:hide_show('inventory-cash')">
                                                    <i class="fa fa-caret-right	"></i>
                                                    Cash Crops
                                                </div>
                                                <div class="col-12 inventory-hidden">
                                                    <div class="lvl-3" onclick="_searchdb('Coffee')">
                                                        Coffee
                                                    </div>
                                                    <div class="lvl-3" onclick="_searchdb('Cotton')">
                                                        Cotton
                                                    </div>
                                                    <div class="lvl-3" onclick="_searchdb('Tea')">
                                                        Tea
                                                    </div>
                                                </div>
                                            </div><!--inventory-cash-->

                                        </div>

                                    </div><!--inventory-crops-->

                                    <div class="col-12" id="inventory-animals">
                                        <div class="col-12 lvl-1" onclick="javascript:hide_show('inventory-animals')">
                                            <i class="fa fa-caret-right	"></i>
                                            Animals
                                        </div>
                                        <div class="col-12 inventory-hidden">
                                            <div class="col-12 lvl-2" onclick="_searchdb('Cows')">
                                                Cows
                                            </div>
                                            <div class="col-12 lvl-2" onclick="_searchdb('Goats')">
                                                Goats
                                            </div>
                                            <div class="col-12 lvl-2" onclick="_searchdb('Sheep')">
                                                Sheep
                                            </div>
                                        </div>
                                    </div><!--inventory-animals-->

                                    <div class="col-12" id="inventory-poultry">
                                        <div class="col-12 lvl-1" onclick="javascript:hide_show('inventory-poultry')">
                                            <i class="fa fa-caret-right	"></i>
                                            Poultry
                                        </div>
                                        <div class="col-12 inventory-hidden">
                                            <div class="col-12 lvl-2" onclick="_searchdb('Chicken')">
                                                Chicken
                                            </div>
                                            <div class="col-12 lvl-2" onclick="_searchdb('Ducks')">
                                                Ducks
                                            </div>
                                            <div class="col-12 lvl-2" onclick="_searchdb('Turkeys')">
                                                Turkeys
                                            </div>
                                        </div>
                                    </div><!--inventory-animals-->

                                    <div class="col-12" id="inventory-fish">
                                        <div class="col-12 lvl-1" onclick="javascript:hide_show('inventory-fish')">
                                            <i class="fa fa-caret-right	"></i>
                                            Fish
                                        </div>
                                        <div class="col-12 inventory-hidden">
                                            <div class="col-12 lvl-2" onclick="_searchdb('Tilapia')">
                                                Tilapia
                                            </div>
                                            <div class="col-12 lvl-2" onclick="_searchdb('Cat Fish')">
                                                Cat Fish
                                            </div>
                                            <div class="col-12 lvl-2" onclick="_searchdb('Mud Fish')">
                                                Mud Fish
                                            </div>
                                            <div class="col-12 lvl-2" onclick="_searchdb('Nile Perch')">
                                                Nile Perch
                                            </div>
                                        </div>
                                    </div><!--inventory-animals-->

                                </div><!--search by category-->
                            </div><!--inventory-browse-->

                            <div class="col-8" id="display-search-results">
                                <!--Display results here-->

                            </div><!--display-search-results-->
                        </div><!--Inventory update-->

                    </div><!--Inventory container-->
                    <div class="col-12 prof-content-row" id="prof-msg">
                        <div id="msg-title-bar"><!--This holds the search bar for the messages-->
                            <div id="msg-srch-div">
                                <form class="search">
                                    <input type="text" id="msg-search" onkeydown="" name="search" placeholder="Search messages...">
                                    <input id="msg-uglyButton" style="display: none;" type="button" onclick="javascript:_searchdb(document.getElementById('inventory-search').value)" value="Search">
                                    <span id="msg-beaut">Go</span>
                                    <script>
                                        var ugly = document.getElementById("uglyButton");
                                        var beaut = document.getElementById("msg-beaut");
                                        beaut.onclick = function () {
                                            ugly.click();
                                        }
                                    </script>
                                </form>
                            </div>
                        </div>
                        <div id="msg-display"><!--This holds the different message templates.-->
                            <script>
                                //Access the db to fetch the user's messages
                                //
                            </script>
                        </div>

                    </div><!--Messages div-->
                    <div class="col-12 prof-content-row" id="prof-orders">
                        <p>
                            This is the orders container. Still under development.
                        </p>
                    </div><!--Orders div-->
                    <!--script3-->
                    <script> //Script3
                        function reveal1hide23(div1, div2, div3) {
                            document.getElementById(div1).style.display="block";
                            document.getElementById(div2).style.display="none";
                            document.getElementById(div3).style.display="none";
                            if(div1==="inventory-container") { //
                                var idisplay=document.getElementById('inventory-display');
                                var iupdate=document.getElementById('inventory-update');
                                add_to_inventory();//Switches the visibilities of inventory-update and inventory-
                                //display back and forth.
                                <?php
                                if($session_exists) {
                                    echo "_getInventory(); //Retrieves database items"; //_getInventory doesn't return!! WHY?? Because it
                                    //runs an asynchronous function
                                    echo "console.log('The _getInventory() function returned');";
                                }
                                ?>

                            }
                        }
                    </script>
                </div><!--prof-page-main-->

            </div><!--col-12 r2c2row-->
        </div><!--r2c2-->
    </div><!--row-2-->
    <div id="r3-overlay"><!--Totally empty!-->
        <div class="footnote-col">© Farmer's Marketting Hub 2017</div>
        <div class="footnote-col">Contact</div>
        <div class="footnote-col">Privacy Policy</div>
    </div>
</div><!--Main wrapper-->

<!--************************************THE LOGIN FORM******************************-->
<?php
function fill_in_blanks ($postvariable, $sessionvariable) {
    if(isset($_POST[$postvariable]) && $_POST[$postvariable] !=null) {
        echo $_POST[$postvariable];
    } else if(isset($_SESSION[$sessionvariable]) && $_SESSION[$sessionvariable] !=null) {
        echo $_SESSION[$sessionvariable];
    } else {
        echo "";
    }
}
function _selected($var) {
    if(isset($_SESSION['Sex']) && $var == $_SESSION['Sex']) {
        echo "checked";
    }  else if (isset($_POST['Sex']) && $var==$_POST['Sex']) {
        echo "checked";
    }
}
?>
<div id="id02" class="modal">
    <span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close Modal">×</span>
    <form class="modal-content animate" action="index.php" method="post">
        <div class="container">
            <input type="hidden" name="formname" value="profedit"/>

            <label><b>First Name</b></label><span class="error"> *<?php echo $fname_error ?></span>
            <input type="text" class="required" placeholder="First Name" name="fname" value="<?php fill_in_blanks('fname','FirstName'); ?>" required>

            <label><b>Middle Name</b></label>
            <input type="text" placeholder="Middle Name" name="mname" value="<?php fill_in_blanks('mname','MiddleName'); ?>">

            <label><b>Last Name</b></label><span class="error"> *<?php echo " ".$lname_error ?></span>
            <input type="text" class="required" placeholder="Last Name" name="lname" value="<?php fill_in_blanks('lname','LastName'); ?>" required>

            <label><b>Company Name</b></label><span class="error"> *<?php echo " ".$coname_error ?></span>
            <input type="text" placeholder="First Name" name="coname" value="<?php fill_in_blanks('coname','CoName'); ?>">

            <label><b>Sex</b></label><span class="error"> *<?php echo " ".$sex_error; ?></span><br>
            <input type="radio" class="required" name="sex" value="M" required <?php _selected('M'); ?> >M<br>
            <input type="radio" class="required" name="sex" value="F" required <?php _selected('F'); ?> >F<br>
            <input type="radio" class="required" name="sex" value="C" required <?php _selected('C'); ?> >Company<br><br>

            <label><b>Date of Birth</b></label><span class="error"> *<?php echo " ".$dob_error; ?></span><br>
            <input type="date" class="required wide" name="dob" required value="<?php fill_in_blanks('dob','DoB'); ?>"><br><br>

            <label><b>District of Operation</b></label><span class="error"> *<?php echo " ".$district_error ?></span>
            <input type="text" class="required" placeholder="District" name="district" value="<?php fill_in_blanks('district','District'); ?>" required>

            <label><b>Email</b></label><span class="warning"><?php echo " ".$email_error ?></span>
            <input type="text" placeholder="Enter Email" name="email" value="<?php fill_in_blanks('email','Email'); ?>">

            <label><b>Address</b></label>
            <input type="text" placeholder="E.g Plot 35 Speke street, Kampala" name="address" value="<?php fill_in_blanks('address','Address'); ?>">

            <label><b>Phone Number</b></label><span class="error"> *<?php echo " ".$phoneno_error ?></span>
            <input type="text" class="required" placeholder="E.g 0784596469" name="phoneno" value="<?php fill_in_blanks('phoneno','PhoneNo'); ?>" required>

            <label><b>Website</b></label>
            <input type="text" placeholder="e.g www.domain.com" name="website" value="<?php fill_in_blanks('website','Website'); ?>">

            <label><b>About Yourself</b></label><br>
            <textarea style="width: 100%" name="about" placeholder="About yourself..."><?php fill_in_blanks('about','About'); ?></textarea><br>

            <input type="checkbox" checked="checked"> Remember me
            <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>

            <div class="clearfix">
                <button type="button" onclick="document.getElementById('id02').style.display='none'" class="cancelbtn">Cancel</button>
                <button type="submit" class="signupbtn">Update</button>
            </div>
        </div>
    </form>
</div> <!--This is the form for editing the user info-->
<!--Script4-->
<iframe name="hidden_iframe" style="display:none"></iframe>
<div class="modal" id="editAddItem">
    <span onclick="document.getElementById('editAddItem').style.display='none'" class="close" title="Close Modal">×</span>
    <div id="eAI-1" class="modal-content animate">
        <div id="eAI-11">
        </div>
        <div id="eAI-12">
        </div>
        <div id="eAI-13">
            <form target="hidden_iframe">
                <label>Stock Quantity</label><br>
                <input id="quantity" name="quantity" type="number" min=0 max=9999999 step=0.01>
                <select name="units" id="units">
                    <option value="kgs">Kilograms</option>
                    <option value="grams">Grams</option>
                    <option value="pieces">Pieces</option>
                    <option value="units">Units</option>
                    <option value="bunches">Bunches</option>
                    <option value="bags">Bags</option>
                    <option value="litres">Litres</option>

                </select>
                <br>
                <label>State</label><br>
                <select name="state" id="state">
                    <option value="fresh">Fresh (Unprocessed)</option>
                    <option value="dry">Dried</option>
                    <option value="preprocessed">Pre-processed</option>
                    <option value="prepackaged">Pre-packaged</option>
                    <option value="Salted">Salted</option>
                </select><br>
                <label>Unit Price (UGX)</label><br>
                <input name="price" type="number" min=0 max=9999999 step=1 id="price">
                <br>
                <label>Item Description</label><br>
                <input type="text" name="description" id="description"><br>
                <label>Do you deliver?</label><br>
                <input type="radio" name="deliverable" value="yes" onclick='getradio("yes")'>Yes<br>
                <input type="radio" name="deliverable" value="no" onclick='getradio("no")'>No<br>
                <label>Deliverable Places</label><br>
                <input type="text" name="dplace" id="dplace"><br>
                <span id="item_submit_button"></span>
            </form>
        </div>
    </div>
</div><!--Edit-->
<div class = "modal" id="edit-prof-pic">
    <div id="canvas-container">
        <canvas id = "canvas" width="600px" height="400px" style="background-color: bisque">
        </canvas>
        <span id="uploadimage" onclick="upload_prof_pic()">Upload</span>
        <span id = "cancelupload">Cancel</span>
    </div>

</div>
<script>
    inboxMessages();
</script>
<!--Below is the orders div-->

<!--Script5-->
<!--*************************************************************************-->
<!--Wakaru jouhou riron. Jouhou fugou angou riron. Kyoto uni. Nisa sensei. Online vids. Eng.-->
<!--****************************THE SIGNUP FORM******************************-->

<!--*************************************************************************-->

</body>
</html>
