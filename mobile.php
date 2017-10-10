<!doctype html>
<?php
include "include.php";
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="mobile.css">
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
                                if($session_exists) { //There's a better way but meh! This works too.
                                    //If picture exists
                                    if(file_exists("Profiles/Pictures/'".$_SESSION['UserID'])) {
                                        echo '<img src="Profiles/Pictures/'.$_SESSION["UserID"].'">';
                                    }
                                    else {
                                        echo '<img src="icons/profile-pic-male.jpg">';
                                    }
                                    //Else, use the default

                                } else {
                                    echo '<img src="icons/profile-pic-male.jpg">';
                                }
                                ?>
                            </div><!--prof-picimg-->
                        </div><!--prof-pic-->
                    </div><!--min-prof-->

                </div><!--r1c1-->
                <div class="full-width" id="searchdiv">
                    <form class="search">
                        <input type="text" id="inventory-search" onkeydown="_checkenterkey(event, 'feed')" name="search" placeholder="Search..">
                        <input id="uglyButton" style="display: none;" type="button" onclick="javascript:_searchdb(document.getElementById('inventory-search').value)" value="Search">
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
            </div><!--r1c2r1-->
            <div class="full-width" id="r1c2r2"><!--Insert an unordered list here for the menu-->
                <div id="hor-menu">
                    <div class="full-width" id="mob-menu">
                        <a href="#" id="feedtab" onclick="showOrdersDiv(this)">Feed</a>
                        <?php
                        if($session_exists) {
                            echo "<a href='Profiles/mprofile.php'>Home</a>";
                        }
                        ?>
                        <a href="#">About Us</a>
                        <a href="#" id="orderitemtab" onclick="showOrdersDiv(this)">Order Item</a>
                        <script>
                            //upon clicking Order Item, hide the main panel and display the other.
                            function showOrdersDiv(elmt){
                                console.log("Checkpoint 1")
                                var row2 = document.getElementById('row-2');
                                var roworders = document.getElementById('row-orders');
                                if(elmt.id==="feedtab") {
                                    //Clicked element is the feed tab. Show the feed, then reload the items in there because
                                    //itemNodeList is being shared by two functions [_searchdb() and _searchCatalog()]
                                    row2.style.display='block';
                                    roworders.style.display='none';
                                    _searchdb("");
                                    console.log("itemNodeList has been refreshed")
                                } else {
                                    row2.style.display='none';
                                    roworders.style.display='block';
                                }
                            }
                        </script>
                        <a href="#">More stuff</a>
                        <?php //If the user logs in (session_exists=true) hide the following
                        if(!$session_exists) {
                            echo "
							<a href=\"javascript:void(0)\" onclick=
							\"document.getElementById('id01').style.display='block'\">Sign In</a>
							<a href=\"javascript:void(0)\" onclick=
							\"document.getElementById('id02').style.display='block'\">Sign up</a>
								";
                        }
                        ?>
                    </div>
                </div><!--hor-menu-->

            </div><!--r1c2r2-->
        </div><!--r1c2-->

    </div><!--row-1-->
    <div id="row-2">
        <div class="col-12" id = "toolbar">
            <div id="id_change_view" onclick="change_view()" style="height: 100%; width: 40px; display: inline-flex; padding: 2px; flex-wrap: wrap">
                <div style="background-color: gray; width: 40%; height: 40%; margin: 5%"></div>
                <div style="background-color: gray; width: 40%; height: 40%; margin: 5%"></div>
                <div style="background-color: gray; width: 40%; height: 40%; margin: 5%"></div>
                <div style="background-color: gray; width: 40%; height: 40%; margin: 5%"></div>
            </div>
            <div class="fa fa-angle-double-down" onclick="change_class(this)" style="height: 100%; color: grey; float: left; font-size: 3em; padding: 0px 10px;"></div>
            <script>
                function change_class(element) {
                    var categories = document.getElementById("categories");
                    //First change the arrows to point up/down
                    if(element.classList.contains("fa-angle-double-down")) { //If down, point up and show "categories"
                        element.classList.remove("fa-angle-double-down");
                        element.classList.add("fa-angle-double-up");
                        categories.style.display="block";

                    } else if(element.classList.contains("fa-angle-double-up")) { //If up, point down and hide "categories"
                        element.classList.remove("fa-angle-double-up");
                        element.classList.add("fa-angle-double-down");
                        categories.style.display="none";
                    } else {
                        console.log("Your code is shit!!");
                    }

                }
            </script>
        </div>
        <div class="col-3" id="categories" class="cat-slide-in"> <!--search by category-->
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

                                <script>
                                    function hide_show(elmtId)
                                    {
                                        var element = document.getElementById(elmtId);
                                        var arrow = element.getElementsByTagName("i")[0];
                                        element = element.getElementsByClassName('inventory-hidden')[0];
                                        //if it's hidden show it. If it's visible, hide it.
                                        if(element.style.display==="none" || element.style.display==="") {
                                            element.style.display="block";
                                            arrow.className="fa fa-caret-down";
                                        } else {
                                            element.style.display="none";
                                            arrow.className="fa fa-caret-right";
                                        }
                                    }
                                    function add_to_inventory() { //Hide inventory data onclick
                                        var x=document.getElementById('inventory-display');
                                        var y=document.getElementById('inventory-update');
                                        console.log(x.style.display);
                                        console.log(y.style.display);
                                        if(x.style.display==='block' && y.style.display==='none') {
                                            console.log("Conditions fulfilled");
                                            x.style.display='none';
                                            y.style.display='block';
                                        } else {
                                            x.style.display='block';
                                            y.style.display='none';
                                        }
                                    }
                                    function rem_rep_item(i) {
                                        //Extract the node's itemID
                                        var RepID = getValue(repItemNodeList, i, 'RepID'); //Assuming the iremNodeListr object still exists
                                        //Access the db and delete the node;
                                        var xmlhttp = new XMLHttpRequest();
                                        xmlhttp.responseType = "document";
                                        xmlhttp.onreadystatechange = function() {
                                            //Check the return status for success/failure
                                            if(this.readyState===4 && this.status===200) {
                                                var xmlDoc = this.responseXML;
                                                console.log(xmlDoc);
                                                var return_status = xmlDoc.getElementsByTagName("status")[0].childNodes[0].nodeValue;
                                                if(return_status===0) { //Success. Rerun the _srchdb() function
                                                    alert("Item Deleted");
                                                } else if(return_status===1) {
                                                    alert("A problem occurred");
                                                } else {
                                                    console.log(return_status);
                                                    reveal1hide23('inventory-container', 'prof-container', 'prof-orders');
                                                }
                                            } else { //There was a problem at the server end
                                                console.log("There was a problem!");
                                                console.log(this.readyState);
                                                console.log(this.status);
                                            }
                                        }
                                        xmlhttp.open("GET", "xhttp.php?table=delete_item&RepID="+RepID, true);
                                        xmlhttp.send();
                                    }
                                    function update_rep_item(i) {
                                        itemID = getValue(repItemNodeList, i, 'itemID');
                                    }
                                </script>

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
                                <div class="col-12 lvl-3" onclick="hide_show('legumes')">
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
                        <div class="lvl-2" onclick="hide_show('inventory-cash')">
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
                <div class="col-12 lvl-1" onclick="hide_show('inventory-animals')">
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
                <div class="col-12 lvl-1" onclick="hide_show('inventory-poultry')">
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
                <div class="col-12 lvl-1" onclick="hide_show('inventory-fish')">
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

        <div class="col-9 slide-container-view-list" id="r2c2">
            <div class="r2c2row col-12">
                <div class="r2c2row-content col-12" id="inventory-display">
                    <!--Replaced with code to extract available items from database. Populated using id-->
                </div><!--r2c2row-content col-12-->
                <script>
                    function _checkenterkey(event, func_name) {
                        if(event.key==='Enter') { //If it's the enter key, call the _searchdb function
                            //The _searchdb function will extract the info, call the appropriate
                            //div by id and display the data.
                            event.preventDefault(); //Prevents the defaul of submitting the form + refreshing
                            if(func_name === 'orders') {
                                _searchCatalog(document.getElementById('search-input').value);
                            }
                            else {
                                _searchdb(document.getElementById('inventory-search').value);
                            }

                        }
                    }

                    var itemNodeList; //Holds item nodes from the repository search for when the user is nonlogged. The
                                        //items held are thus those added by the registered sellers.
                    //TODO: Edit _searchdb function to allow to send a different table (dashboard?) with the query
                    function _searchdb(str) {
                        //This function searches the database table, Repository for All items.
                        var xhttp = new XMLHttpRequest();
                        xhttp.responseType = "document";//Only this way, shall we be able to return an XML/HTML document
                        xhttp.onreadystatechange = function() { //If we get a reply from the server
                            if(this.readyState===4 && this.status===200) { //Check the status and readystate
                                if(this.responseXML!=null) { //Do we have any meaningful response other than null?
                                    var xmlDoc = this.responseXML;
                                    console.log(xmlDoc);
                                    var returnStatus = xmlDoc.getElementsByTagName("status")[0].childNodes[0].nodeValue;
                                    returnStatus = parseInt(returnStatus); //Convert the string to an int
                                    if(returnStatus===0) {
                                        //get an itemNodeList object
                                        itemNodeList = xmlDoc.getElementsByTagName("Items")[0].getElementsByTagName("Item");
                                        //Purge the 'html' variable of previous search data
                                        document.getElementById("inventory-display").innerHTML="";

                                        if(itemNodeList.length>0) {
                                            var i=0;
                                            for(i=0;i<itemNodeList.length;i++) {
                                                var elmt = "";
                                                elmt = document.createElement("div");
                                                elmt.classList.add("item-slide");
                                                elmt.indexno = i;
                                                elmt.addEventListener("click", function () {
                                                    _getUserInfo(this)
                                                }, true)

                                                var img = "";
                                                img = document.createElement("img");
                                                var elmt2 = "";
                                                elmt2 = document.createElement("div");
                                                elmt2.classList.add("item-slide-image");
                                                if(getValue(itemNodeList, i, 'ImageURI') === 'None') {
                                                    img.src = 'icons/placeholder.png'
                                                }
                                                else {
                                                    img.src=getValue(itemNodeList, i, 'ImageURI');
                                                }
                                                elmt2.appendChild(img);
                                                elmt.appendChild(elmt2);
                                                var elmt3 = "";
                                                elmt3 = document.createElement("div");
                                                elmt3.classList.add('item-slide-content');
                                                elmt3.id="itemNo"+i;
                                                var spanElmt = "";
                                                spanElmt = document.createElement("span");
                                                spanElmt.classList.add("dash_item_name");
                                                spanElmt.innerHTML = getValue(itemNodeList, i, 'ItemName');
                                                elmt3.appendChild(spanElmt);
                                                elmt.appendChild(elmt3);
                                                document.getElementById("inventory-display").appendChild(elmt);
                                            }
                                        } else {
                                            console.log("0 results were found");
                                        }
                                    } else if(returnStatus===1) { //returnStatus (defined in the php). 1=No results found.
                                        console.log("No matching results were found");
                                    } else if(returnStatus===2) { //2=Problem with the mysql query
                                        console.log("There was a problem with the mysql query")
                                    } else if(returnStatus===3) { //3=couldn't connect to the database
                                        console.log("There was a problem connecting to the database");
                                    } else if(returnStatus===11) {
                                        window.alert("Please log in");
                                    } else {
                                        console.log("AND NOW WHAT?!");
                                    }
                                } else { //For some weird reason, no XML, null returned.
                                    console.log("The is no response XML");
                                }
                            }
                        }
                        xhttp.open("GET", "Profiles/xhttp.php?table=nonlogged&q="+str, true);
                        xhttp.send();
                    }
                    var repItemNodeList;
                    var userInfoNode;
                    function getValue(nodeList, index, tagName) { //This function is just to make things shorter ^
                        return nodeList[index].getElementsByTagName(tagName)[0].childNodes[0].nodeValue
                    } //It's called in _searchdb() to shorten code
                    function displaymodal(i) {
                        //This function sets the data in the modal template. i identifies the item's index in the
                        //itemNodeList array.
                        var html=""; //in the itemNodeList
                        html+="<img src='"+getValue(itemNodeList, i, 'ImageURI')+"'>";//Get image URI from node list
                        document.getElementById('oi-11').innerHTML = html; //Insert image
                        html="<h3>"+getValue(itemNodeList, i, 'ItemName')+"</h3>";//Get item name
                        document.getElementById('oi-12').innerHTML = html; //Insert item name

                        //Add the product and seller details to oi-13
                        html="<table class='oi-table'>";
                        html+="<tr>";
                        html+="<th>";
                        html+="Description: ";
                        html+="</th>";
                        html+="<td>";
                        html+=getValue(itemNodeList,i,'description')
                        html+="</td>";
                        html+="</tr>";
                        html+="<tr>";
                        html+="<th>";
                        html+="<span title='Minimum and Maximum order size'>Min - Max</span> order size:";
                        html+="</th>";
                        html+="<td>";
                        html+="</td>";
                        html+="</tr>";
                        html+="<tr>";
                        html+="<th>";
                        html+="Price: ";
                        html+="</th>";
                        html+="<td>";
                        html+=getValue(itemNodeList, i, 'unitprice');
                        html+="UGX/";
                        html+=getValue(itemNodeList,i,'units');
                        html+="</td>";
                        html+="</tr>";
                        html+="<tr>";
                        html+="<th>";
                        html+="Places Deliverable: ";
                        html+="</th>";
                        html+="<td>";
                        html+=getValue(itemNodeList,i,'deliverableareas')
                        html+="</td>";
                        html+="</tr>";
                        html+="<tr>";
                        html+="<th>";
                        html+="Seller:";
                        html+="</th>";
                        html+="<td>";
                        html+=userInfoNode.getElementsByTagName('firstname')[0].childNodes[0].nodeValue;
                        html+="</td>";
                        html+="</tr>";
                        html+="<tr>";
                        html+="<th>";
                        html+="Location:";
                        html+="</th>";
                        html+="<td>";
                        html+=userInfoNode.getElementsByTagName('district')[0].childNodes[0].nodeValue;
                        html+="</td>";
                        html+="</tr>"
                        html+="<tr>"
                        html+="<th>";
                        html+="Contact Email:";
                        html+="</th>";
                        html+="<td>";
                        html+=userInfoNode.getElementsByTagName('email')[0].childNodes[0].nodeValue;
                        html+="</td>";
                        html+="</tr>";
                        html+="<tr>";
                        html+="<th>";
                        html+="Contact Phone No.:";
                        html+="</th>";
                        html+="<td>";
                        html+=userInfoNode.getElementsByTagName('phoneno')[0].childNodes[0].nodeValue;
                        html+="</td>";
                        html+="</tr>";
                        html+="<tr>";
                        html+="<th>";
                        html+="Feedback:";
                        html+="</th>";
                        html+="<td>";
                        html+="</td>";
                        html+="</tr>";
                        html+="</table>";
                        html+="<button type='submit' ><i class='fa fa-plus-square-o'></i> Contact Seller</button>";
                        //Insert into oi-13
                        document.getElementById('oi-13').innerHTML = html;
                        //Display the whole modal
                        document.getElementById("orderItem").style.display="block";
                    }
                    function catalogItem(i){
                        //This displays a modal with the item's details when the user clicks on one of the search
                        //results from the catalog search
                        console.log("catalogItem check")
                        var html=""; //in the itemNodeList
                        html+="<img src='"+getValue(itemNodeList, i, 'ImageURI')+"'>";//Get image URI from node list
                        document.getElementById('oi-11').innerHTML = html; //Insert image
                        html="<h3>"+getValue(itemNodeList, i, 'ItemName')+"</h3>";//Get item name
                        document.getElementById('oi-12').innerHTML = html; //Insert item name

                        //Add the product and seller details to oi-13
                        var oi_13 = document.getElementById('oi-13');
                        oi_13.innerHTML = "";
                        var table = newElmt("table");
                        table.classList.add('oi-table');
                        var tr = newElmt("tr");
                        var th = newElmt("th");
                        var td = newElmt("td");
                        th.innerHTML = "Description";
                        td.innerHTML = getValue(itemNodeList,i,'description');
                        tr.appendChild(th);
                        tr.appendChild(td);
                        table.appendChild(tr);
                        oi_13.appendChild(table);

                        //Create and append form
                        var form = newElmt('form');
                            oi_13.appendChild(form);
                        var label = newElmt('label');
                            form.appendChild(label);
                            label.innerHTML="Quantity:"
                            form.appendChild(newElmt('br'));
                        var input = newElmt('input');
                            form.appendChild(input);
                            input.type='text';
                            //Another input


                        //Create and append button
                        var button = newElmt("button");
                        button.indexno = i;
                        button.type = 'submit';
                        button.innerHTML = "Place Open Order";
                        button.onclick = function () {
                            place_open_order(this.indexno);
                        }
                        var icon = newElmt("i");
                        icon.classList.add('fa'); //set class part 1
                        icon.classList.add('fa-plus-square-o'); //set class part 2
                        button.appendChild(icon); //Append the i to the button
                        oi_13.appendChild(button);
                        //Display the whole modal
                        document.getElementById("orderItem").style.display="block";
                    }
                    //What lays below is read as the page loads, hence displaying the inventory
                    _searchdb(""); //Pre-load the "dashboard" with db items when the page loads
                    //Define a function to access the database and extract the user information
                    function _getUserInfo(elmt) { 
                        //Uses the index no of the item to get the user (seller) info
                        //incomplete
                        console.log(elmt.indexno);
                        var i = elmt.indexno; //get the index number
                        var userid = getValue(itemNodeList, i, 'userid');
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.responseType = "document";
                        xmlhttp.onreadystatechange = function() {
                            if(this.readyState===4 && this.status===200) {
                                if(this.responseXML!=null) { //OK
                                    var doc = this.responseXML;
                                    var returnStatus = doc.getElementsByTagName('status')[0].childNodes[0].nodeValue; //This is a string. Cast it into an integer
                                        returnStatus = parseInt(returnStatus);
                                    if(returnStatus===0) { //Success
                                        //get an item node list object
                                        userInfoNode = doc.getElementsByTagName("userdata")[0];
                                        displaymodal(i);
                                    }
                                    else {
                                        //later
                                        console.log(returnStatus);
                                    }
                                }
                                else { //No xml
                                    //later
                                    console.log("No xml");
                                }
                            }
                            else { //request not fulfilled. Print readyState & status to console
                                //later
                            }
                        }
                        xmlhttp.open("GET", "Profiles/xhttp.php?table=sellerdata&UserID="+userid, true);
                        xmlhttp.send();
                    }
                    function _searchCatalog(str) {
                        //This function searches the db table Items for item templates for users to define the items
                        //they want to order. It's used by the orders div. It's almost exactly the same as the _searchdb()
                        //function in Profiles/index.
                        console.log("Is the function even called?!")
                        var xhttp = new XMLHttpRequest();
                        xhttp.responseType = "document";//Only this way, shall we be able to return an XML/HTML document
                        xhttp.onreadystatechange = function() { //If we get a reply from the server
                            if(this.readyState==4 && this.status==200) { //Check the status and readystate
                                if(this.responseXML!=null) { //Do we have any meaningful response other than null?
                                    var xmlDoc = this.responseXML;
                                    console.log(xmlDoc);
                                    var returnStatus = xmlDoc.getElementsByTagName("status")[0].childNodes[0].nodeValue;
                                        returnStatus = parseInt(returnStatus); //Parse it as an int
                                    if(returnStatus===0) {
                                        //get an itemNodeList object
                                        itemNodeList = xmlDoc.getElementsByTagName("Items")[0].getElementsByTagName("Item");
                                        //Purge the 'html' variable of previous search data
                                        document.getElementById("display-search-results").innerHTML="";

                                        if(itemNodeList.length>0) {
                                            var html="";
                                            var i=0;
                                            for(i=0;i<itemNodeList.length;i++) {
                                                html="<div class='item-slide' onclick='catalogItem("+i+")'>";
                                                html+="<div class='item-slide-image'>";
                                                html+="<img src='"+getValue(itemNodeList, i, 'ImageURI')+"'>";
                                                html+="</div><!--item-slide-header-->"
                                                html+="<div class='item-slide-content' id='itemNo"+i+"'>"
                                                html+="<div id='addToRep'>";//ID means 'Add to repository'
                                                html+="<button onclick='displaymodal("+i+")'><i class='fa fa-plus-square-o'></i> Add an Item</button>";
                                                html+="</div>";
                                                html+="</div><!--item-slide-header-->"
                                                html+="</div>";
                                                document.getElementById("display-search-results").innerHTML+=html;
                                            }
                                        } else {
                                            console.log("0 results were found");
                                        }
                                    } else if(returnStatus==1) { //returnStatus (defined in the php). 1=No results found.
                                        console.log("No matching results were found");
                                    } else if(returnStatus==2) { //2=couldn't connect to the database
                                        console.log("There was a problem connecting to the database");
                                    } else if(returnStatus==11) {
                                        window.alert("Please log in");
                                    } else {
                                        console.log("Weird!");
                                    }
                                } else { //For some weird reason, no XML, null returned.
                                    console.log("There is no response XML");
                                }
                            }
                        }
                        xhttp.open("GET", "Profiles/xhttp.php?table=Items&q="+str, true);
                        xhttp.send();
                    }
                    function place_open_order(i){
                        //This function will get the item by the index from itemsNodeList
                        //Get the info the user intered in the form in the modal
                        //Open an xhttp request and place the order
                        console.log("Index No.:"+i);
                    }
                    function newElmt(elmt) {
                        //This creates an element whose name is specified in the parameter
                        return document.createElement(elmt);
                    }
                </script>

            </div>

        </div><!--r2c2-->

    </div><!--row-2-->
    <div id="row-orders" style="display: none">
        <div class="col-12" id="orders-update" style="display: block;">
            <div class="col-4" id="orders-browse"><!--invisible until user clicks add-->
                <div class="col-12" id="orders-search">
                    <div class="full-width" id="orders-searchdiv">
                        <form class="search">
                            <input type="text" id="search-input" onkeydown="_checkenterkey(event, 'orders')" name="search" placeholder="Search..">
                            <input id="orders-uglyButton" style="display: none;" type="button" onclick="_searchCatalog(document.getElementById('search-input').value)" value="Search">
                            <span id="orders-beaut">Go</span>
                            <script>
                                var ugly = document.getElementById("orders-uglyButton");
                                var beaut = document.getElementById("orders-beaut");
                                beaut.onclick = function () {
                                    ugly.click();
                                }
                            </script>

                        </form>
                    </div>

                    <!--Script1-->
                </div><!--Search by search-->
                <div class="col-12" id="orders-categories"> <!--search by category-->
                    <div class="col-12" id="orders-crops">
                        <div class="col-12 lvl-1" onclick="hide_show('orders-crops')">
                            <i class="fa fa-caret-right"></i>
                            Crops
                        </div>

                        <div class="col-12 inventory-hidden">

                            <div class="col-12" id="orders-food">
                                <div class="col-12 lvl-2" onclick="hide_show('orders-food')">
                                    <i class="fa fa-caret-right	"></i>
                                    Food crops
                                </div>
                                <div class="col-12 inventory-hidden"><!--Group starts here-->
                                    <div class="col-12" id="orders-starchy">
                                        <!--Script2-->

                                        <div class="col-12 lvl-3" onclick="hide_show('orders-starchy')">
                                            <i class="fa fa-caret-right	"></i>
                                            Starchy foods
                                        </div>
                                        <div class="col-12 inventory-hidden">
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Bananas')">
                                                Bananas/Matooke
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Cassava')">
                                                Cassava
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Rice')">
                                                Rice
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Sweet Potatoes')">
                                                Sweet Potatoes
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Irish Potatoes')">
                                                Irish Potatoes
                                            </div>
                                        </div>
                                    </div><!--starchy-->
                                    <div class="col-12" id="orders-fruits">
                                        <div class="col-12 lvl-3" onclick="hide_show('orders-fruits')">
                                            <i class="fa fa-caret-right	"></i>
                                            Fruits
                                        </div>
                                        <div class="col-12 inventory-hidden">
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Yellow Bananas')">
                                                Yellow Bananas
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Passion Fruits')">
                                                Passion Fruits
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Tomatoes')">
                                                Tomatoes
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Avocadoes')">
                                                Avocadoes
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Egg Plant')">
                                                Egg Plant
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Plantain')">
                                                Plantain/Gonja
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Paprika')">
                                                Paprika
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Mangoes')">
                                                Mangoes
                                            </div>
                                        </div>
                                    </div><!--fruits-->
                                    <div class="col-12" id="orders-veggies">
                                        <div class="col-12 lvl-3" onclick="hide_show('orders-veggies')">
                                            <i class="fa fa-caret-right	"></i>
                                            Vegetables
                                        </div>
                                        <div class="col-12 inventory-hidden">
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Cabbage')">
                                                Cabbage
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Amaranthus')">
                                                Dodo/Amaranthus
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Nakati')">
                                                Nakati
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Sukuma Wiki')">
                                                Sukuma Wiki
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Lettuce')">
                                                Lettuce
                                            </div>
                                        </div>
                                    </div><!--veggies-->
                                    <div class="col-12" id="orders-legumes">
                                        <div class="col-12 lvl-3" onclick="hide_show('orders-legumes')">
                                            <i class="fa fa-caret-right	"></i>
                                            Legumes
                                        </div>
                                        <div class="col-12 inventory-hidden">
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Beans')">
                                                Beans
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Ground Nuts')">
                                                Ground Nuts/Pea Nuts
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Peas')">
                                                Peas
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Lentils')">
                                                Lentils
                                            </div>
                                            <div class="col-12 lvl-4" onclick="_searchCatalog('Soy Beans')">
                                                Soy Beans
                                            </div>
                                        </div><!--hidden-->
                                    </div><!--legumes-->

                                </div><!--Group ends here-->

                            </div><!--inventory-food-->

                            <div class="col-12" id="orders-cash">
                                <div class="lvl-2" onclick="hide_show('orders-cash')">
                                    <i class="fa fa-caret-right	"></i>
                                    Cash Crops
                                </div>
                                <div class="col-12 inventory-hidden">
                                    <div class="lvl-3" onclick="_searchCatalog('Coffee')">
                                        Coffee
                                    </div>
                                    <div class="lvl-3" onclick="_searchCatalog('Cotton')">
                                        Cotton
                                    </div>
                                    <div class="lvl-3" onclick="_searchCatalog('Tea')">
                                        Tea
                                    </div>
                                </div>
                            </div><!--inventory-cash-->

                        </div>

                    </div><!--inventory-crops-->

                    <div class="col-12" id="orders-animals">
                        <div class="col-12 lvl-1" onclick="hide_show('orders-animals')">
                            <i class="fa fa-caret-right	"></i>
                            Animals
                        </div>
                        <div class="col-12 inventory-hidden">
                            <div class="col-12 lvl-2" onclick="_searchCatalog('Cows')">
                                Cows
                            </div>
                            <div class="col-12 lvl-2" onclick="_searchCatalog('Goats')">
                                Goats
                            </div>
                            <div class="col-12 lvl-2" onclick="_searchCatalog('Sheep')">
                                Sheep
                            </div>
                        </div>
                    </div><!--inventory-animals-->

                    <div class="col-12" id="orders-poultry">
                        <div class="col-12 lvl-1" onclick="hide_show('orders-poultry')">
                            <i class="fa fa-caret-right	"></i>
                            Poultry
                        </div>
                        <div class="col-12 inventory-hidden">
                            <div class="col-12 lvl-2" onclick="_searchCatalog('Chicken')">
                                Chicken
                            </div>
                            <div class="col-12 lvl-2" onclick="_searchCatalog('Ducks')">
                                Ducks
                            </div>
                            <div class="col-12 lvl-2" onclick="_searchCatalog('Turkeys')">
                                Turkeys
                            </div>
                        </div>
                    </div><!--inventory-animals-->

                    <div class="col-12" id="orders-fish">
                        <div class="col-12 lvl-1" onclick="hide_show('orders-fish')">
                            <i class="fa fa-caret-right	"></i>
                            Fish
                        </div>
                        <div class="col-12 inventory-hidden">
                            <div class="col-12 lvl-2" onclick="_searchCatalog('Tilapia')">
                                Tilapia
                            </div>
                            <div class="col-12 lvl-2" onclick="_searchCatalog('Cat Fish')">
                                Cat Fish
                            </div>
                            <div class="col-12 lvl-2" onclick="_searchCatalog('Mud Fish')">
                                Mud Fish
                            </div>
                            <div class="col-12 lvl-2" onclick="_searchCatalog('Nile Perch')">
                                Nile Perch
                            </div>
                        </div>
                    </div><!--inventory-animals-->

                </div><!--search by category-->
            </div><!--inventory-browse-->

            <div class="col-8" id="display-search-results">
                <!--Display results here-->

            </div><!--display-search-results-->
        </div>
    </div>
    <div id="row-3"> <!--This will contain the footer-->
        <div id="r3-overlay"><!--Totally empty!-->
            <div class="footnote-col">&copy; Farmer's Marketting Hub 2017</div>
            <div class="footnote-col">Contact</div>
            <div class="footnote-col">Privacy Policy</div>
        </div>
    </div><!--row-3-->
</div><!--Main wrapper-->

<!--************************************THE LOGIN FORM******************************-->
<!-- The Modal -->
<div id="id01" class="modal">
  <span onclick="document.getElementById('id01').style.display='none'"
        class="close" title="Close Modal">&times;</span>

    <!-- Modal Content -->
    <form class="modal-content animate" action="fmh.php" method="post">
        <div class="imgcontainer">
            <img src="icons/img_avatar2.png" alt="Avatar" class="avatar">
        </div>

        <div class="container">
            <input type="hidden" name="formname" value="login"/>

            <label><b>Phone number</b></label><?php echo $phoneno_error.'<br>' ?>
            <input type="text" placeholder="Enter Username" name="phoneno" required>

            <label><b>Password</b></label><?php echo $upassword_error.'<br>' ?>
            <input type="password" placeholder="Enter Password" name="upassword" required>

            <button type="submit">Login</button>
            <input type="checkbox" checked="checked"> Remember me
        </div>

        <div class="container" style="background-color:#f1f1f1">
            <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
            <span class="psw">Forgot <a href="#">password?</a></span>
        </div>
    </form>
</div>
<!--*************************************************************************-->

<!--****************************THE SIGNUP FORM******************************-->
<div id="id02" class="modal">
    <span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close Modal">×</span>
    <form class="modal-content animate" action="fmh.php" method="post">
        <div class="container">
            <input type="hidden" name="formname" value="signup"/>

            <lable><b>First Name</b></lable><span class="error"> *<?php echo " ".$fname_error ?></span>
            <input type="text" class="required" placeholder="First Name" name="fname" value="<?php if(isset($_POST['fname']) && $_POST['fname'] != null) echo $_POST['fname']; ?>" required>

            <lable><b>Middle Name</b></lable><span>
		<input type="text" placeholder="Middle Name" name="mname" value="<?php if(isset($_POST['mname']) && $_POST['mname'] != null) echo $_POST['mname']; ?>">

		<lable><b>Last Name</b></lable><span class="error"> *<?php echo " ".$lname_error ?></span>
		<input type="text" class="required" placeholder="Last Name" name="lname" value="<?php if(isset($_POST['lname']) && $_POST['lname'] != null) echo $_POST['lname']; ?>" required>

		<lable><b>Company Name</b></lable><span class="error"> <?php echo " ".$coname_error ?></span>
		<input type="text" placeholder="First Name" name="coname" value="<?php if(isset($_POST['coname']) && $_POST['coname'] != null) echo $_POST['coname']; ?>">

		<lable><b>Sex</b></lable><span class="error"> *<?php echo $sex_error ?></span><br>
		<input type="radio" class="required" name="sex" value="M" required >M<br>
		<input type="radio" class="required" name="sex" value="F" required >F<br>
		<input type="radio" class="required" name="sex" value="C" required >Company<br><br>

		<lable><b>Date of Birth</b></lable><span class="error"> *<?php echo $dob_error ?></span><br>
		<input type="date" class="required" class="wide" name="dob" required value="<?php if(isset($_POST['dob']) && $_POST['dob'] != null) echo $_POST['dob']; ?>"><br><br>

		<label><b>District of Operation</b></label><span class="error"> *<?php echo " ".$district_error ?></span>
		<input type="text" class="required" placeholder="District" name="district" value="<?php if(isset($_POST['district']) && $_POST['district'] != null) echo $_POST['district']; ?>" required>

		<label><b>Email</b></label><span class="warning"><?php echo " ".$email_error ?></span>
		<input type="text" placeholder="Enter Email" name="email" value="<?php if(isset($_POST['email']) && $_POST['email'] != null) echo $_POST['email']; ?>">

		<label><b>Address</b></label>
		<input type="text" placeholder="E.g Plot 35 Speke street, Kampala" name="address" value="<?php if(isset($_POST['address']) && $_POST['address'] != null) echo $_POST['address']; ?>">

		<label><b>Phone Number</b></label><span class="error"> *<?php echo " ".$phoneno_error ?></span>
		<input type="text" class="required" placeholder="E.g 0784596469" name="phoneno" value="<?php if(isset($_POST['phoneno']) && $_POST['phoneno'] != null) echo $_POST['phoneno']; ?>" required>

		<label><b>Website</b></label>
		<input type="text" placeholder="e.g www.domain.com" name="website" value="<?php if(isset($_POST['website']) && $_POST['website'] != null) echo $_POST['website']; ?>">

		<label><b>About Yourself</b></label><br>
		<textarea style="width: 100%" placeholder="About yourself..." name="about" value="<?php if(isset($_POST['about']) && $_POST['about'] != null) echo $_POST['about']; ?>"></textarea><br>

		<label><b>Password</b></label><span class="error"> * <?php echo " ".$upassword_error ?></span>
		<input type="password" class="required" placeholder="Enter Password" name="upassword" required>

		<label><b>Repeat Password</b></label><span class="error"> * </span><br>
		<input type="password" class="required" placeholder="Repeat Password" name="upassword2" required>
		<span class="warning">* = required</span><br>
		<input type="checkbox" checked="checked"> Remember me
		<p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>

      <div class="clearfix">
        <button type="button" onclick="document.getElementById('id02').style.display='none'" class="cancelbtn">Cancel</button>
        <button type="submit" class="signupbtn">Sign Up</button>
      </div>
        </div>
    </form>
</div>

<div class="modal" id="orderItem"> <!--Modal that opens up when the user wants to order an item-->
    <span onclick="document.getElementById('orderItem').style.display='none'" class="close" title="Close Modal">×</span>
    <div id="oi-1" class="modal-content animate">
        <div id="oi-11"> <!--The image goes here? Put a default image-->
            <img src="headerimage.jpg">
        </div>
        <div id="oi-12">
        </div>
        <div id="oi-13"><!--This is where the user fills in details of their order: A form-->
            <!--User order details to be entered here.-->
        </div>
    </div>
</div><!--Edit-->
<!--*************************************************************************-->
<script>
    // Get the modal
    var modalin = document.getElementById('id01'); //The signin modal
    var modalup = document.getElementById('id02'); //The signup modal
    var modalOrder = document.getElementById('orderItem');

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target === modalup) {
            console.log("It's the modalup");
            modalup.style.display = "none";
        } else if (event.target === modalin) {
            console.log("It's the modalin");
            modalin.style.display = "none";
        } else if (event.target === modalOrder){
            modalOrder.style.display = "none";
        }
    }

    function change_view() {
        var slide_container = document.getElementById("r2c2");
        if(slide_container.classList.contains("slide-container-view-list")) {
            slide_container.classList.remove("slide-container-view-list");

        } else {
            slide_container.classList.add("slide-container-view-list");
        }
    }
</script>
</body>
</html>

<!--Average length < H + 1 report. Fuck this! Right? Yup! -->
<!--Now create the onclick() function to instantiate the modal "classes"-->
<!--Remember to clear itemNodeList when user leaves pages to avoid conflict with similarly
    named variable on sister page(s)-->
<!--Contact seller by sending them a notification-->
<!--I know a guy.com-->
<!--Textbook sharing site for uni's in Japan-->
