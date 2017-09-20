//Script1
function _checkenterkey(event) {
    if(event.key=='Enter') { //If it's the enter key, call the _searchdb function
        event.preventDefault();
        try {
            _searchdb(document.getElementById('srchdemo').value);
            console.log("Desktop version accessing the function.");
        } catch(err) {
            console.log(err.message);
            console.log("Mobile version accessing the function.")
            console.log(document.getElementById('search-input'));
            _searchdb(document.getElementById('search-input').value);
        }
    }
}

var itemNodeList;
function _searchdb(str) {
    var xhttp = new XMLHttpRequest();
    xhttp.responseType = "document";//Only this way, shall we be able to return an XML/HTML document
    xhttp.onreadystatechange = function() { //If we get a reply from the server
        if(this.readyState==4 && this.status==200) { //Check the status and readystate
            if(this.responseXML!=null) { //Do we have any meaningful response other than null?
                var xmlDoc = this.responseXML;
                console.log(xmlDoc);
                var returnStatus = xmlDoc.getElementsByTagName("status")[0].childNodes[0].nodeValue;
                if(returnStatus==0) {
                    //get an itemNodeList object
                    itemNodeList = xmlDoc.getElementsByTagName("Items")[0].getElementsByTagName("Item");
                    //Purge the 'html' variable of previous search data
                    document.getElementById("display-search-results").innerHTML="";

                    if(itemNodeList.length>0) {
                        var html="";
                        var i=0;
                        for(i=0;i<itemNodeList.length;i++) {
                            html="<div class='item-slide'>";
                            html+="<div class='item-slide-image'>";
                            html+="<img src='"+getValue(itemNodeList, i, 'ImageURI')+"'>";
                            html+="</div><!--item-slide-header-->"
                            html+="<div class='item-slide-content' id='itemNo"+i+"'>"
                            /**html+="<table>";
                            html+="<tr>";
                            html+="<th>Name</th>";
                            html+="<td>"+getValue(itemNodeList, i, 'ItemName')+"</td>";
                            html+="</tr>";
                            html+="<tr>";
                            html+="<th>Other Names</th>";
                            html+="<td>"+getValue(itemNodeList, i, 'Aliases')+"</td>";
                            html+="</tr>";
                            html+="<tr>";
                            html+="<th>Description</th>";
                            html+="<td>"+getValue(itemNodeList, i, 'Description')+"</td>";
                            html+="</tr>";
                            html+="</table>";**/
                            html+="<div id='addToRep'>";//ID means 'Add to repository'
                            html+="<button indexno="+i+" onclick='displaymodal("+this+", \"itemNodeList\")'><i class='fa fa-plus-square-o'></i> Add Item</button>";
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
                }
            } else { //For some weird reason, no XML, null returned.
                console.log("There is no response XML");
            }
        }
    }
    xhttp.open("GET", "xhttp.php?table=Items&q="+str, true);
    xhttp.send();
}

var itemNodeListr;
function _getInventory() {
    //This function
    console.log("The function is running");
    var xmlhttpr = new XMLHttpRequest();
    xmlhttpr.responseType = "document";
    xmlhttpr.onreadystatechange = function() {
        if(this.readyState==4 && this.status==200) {//The request was fulfilled
            var xmlDoc = this.responseXML;
            console.log(xmlDoc);
            var returnStatus = xmlDoc.getElementsByTagName("status")[0].childNodes[0].nodeValue;
            if(returnStatus==0) {//Results were found
                //get an itemNodeList object
                itemNodeListr = xmlDoc.getElementsByTagName("Items")[0].getElementsByTagName("Item");
                //Purge the 'html' variable of previous search data
                var invDisplay = document.getElementById("inventory-display");
                var html = "<div class='item-slide' onclick='javascript:add_to_inventory()' id='edit-inv-data'>";
                html+="<img src='../icons/add.png'>"
                html+="</div>"; //APPROPRIATE ID
                invDisplay.innerHTML=html;

                if(itemNodeListr.length>0) {
                    var i = 0;
                    var html="";
                    for(i=0;i<itemNodeListr.length; i++) {
                        //html="<div class='item-slide'>";
                        //html+="<div class='item-slide-image'>";
                        //html+="<img src='"+getValue(itemNodeListr, i, 'ImageURI')+"'>";
                        //html+="</div><!--item-slide-header-->"
                        //html+="<div class='item-slide-content' id='itemid"+i+"'>"
                        //html+="</div><!--item-slide-header-->"
                        //html+="<div id='addToRep'>";//ID means 'Add to repository'
                        //html+="<button onclick='void(0)'><i class='fa fa-edit'></i> Edit</button>";
                        //html+="</div>";
                        //html+="<span onclick='rem_rep_item("+i+")' id='rem-rep-item"+i+"' class='close' title='Delete Item'>×</span>";
                        //html+="</div>";
                        //document.getElementById("inventory-display").innerHTML+=html;
                        //From here
                        var elmt = "";
                        elmt = document.createElement("div");
                        elmt.classList.add("item-slide");
                        elmt.indexno = i;
                        elmt.addEventListener("click", function () {
                            inventoryItemDetails(this, itemNodeListr)
                        }, true) //Display Modal. Using only item's info. Seller is self

                        var img = "";
                        img = document.createElement("img");
                        var elmt2 = "";
                        elmt2 = document.createElement("div");
                        elmt2.classList.add("item-slide-image");
                        if(getValue(itemNodeListr, i, 'ImageURI') == 'None') {
                            img.src = '../icons/placeholder.png'
                        }
                        else {
                            img.src=getValue(itemNodeListr, i, 'ImageURI');
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
                        spanElmt.innerHTML = getValue(itemNodeListr, i, 'ItemName');
                        elmt3.appendChild(spanElmt);
                        elmt.appendChild(elmt3);
                        document.getElementById("inventory-display").appendChild(elmt);
                    }
                    console.log("Done 1");
                }

            } else if(returnStatus==1) {//No Results found
                console.log("No results were found");
            } else if(returnStatus==3) {//Problem connecting to the database
                console.log("There was a problem connecting to the database");
            } else if(returnStatus==11) {//User is not logged in. Not even sure how that's possible
                console.log("WTF? Is that even possible");
            }
        } else {//The request wasn't fulfilled for some reason
            console.log("ReadyState = "+this.readyState);
            console.log("Status = "+this.status)
        }
        console.log("Done 2");
        return null;
    }
    xmlhttpr.open("GET", "xhttp.php?table=Repository", true);
    xmlhttpr.send();
}
function getValue(nodeList, index, tagName) { //This function is just to make things shorter ^
    var value = nodeList[index].getElementsByTagName(tagName)[0].childNodes[0].nodeValue
    return value;
}
function displaymodal(elmt, nodelist) { //This function sets the data in the modal. i identifies the item
    var i = elmt.indexno; //Carries the number of the item.
    var html=""; //in the itemNodeList
    html="<div width=100%>";
    html+="<img src='"+getValue(nodelist, i, 'ImageURI')+"'>";
    html+="</div>";
    document.getElementById("eAI-11").innerHTML=html;
    html="<div width=100%>";
    html+="<font size=6em position='center'>"+getValue(nodelist, i, 'ItemName')+"</font>";
    html+="<br>Other names:\t"+getValue(nodelist, i, 'Aliases');
    html+="<br>Description:\t"+getValue(nodelist, i, 'Description');
    html+="</div>";
    document.getElementById("item_submit_button").innerHTML="<button type='submit' onclick='submit_add_form("+i+")'><i class='fa fa-plus-square-o'></i>  Add to repository</button>";
    document.getElementById("eAI-12").innerHTML=html;
    document.getElementById("editAddItem").style.display="block";
}
function inventoryItemDetails(elmt, nodelist) {
    var i = elmt.indexno;
    var modal = document.createElement('div');
    modal.width = "100%";
    modal.classList.add("modal");
    var modal_content = document.createElement('div'); //width: 100%
    modal.appendChild(modal_content);
    modal_content.classList.add("modal-content");
    var imgDiv = document.createElement('div'); //width: inherit
    modal_content.appendChild(imgDiv);
    var contentDiv = document.createElement('div');
    modal_content.appendChild(contentDiv);
        var table = document.createElement('table');
        contentDiv.appendChild(table);
            var tbody = document.createElement('tbody');
            table.appendChild(tbody);
                var nameRow = document.createElement('tr');
                tbody.appendChild(nameRow);
                    var data = document.createElement('th');
                        data.innerHTML = "Name:";
                    nameRow.appendChild(data);
                    data = document.createElement('td');
                        data.innerHTML=getValue(nodelist, i, "itemname");
                    nameRow.appendChild(data);
                var otherNamesRow = document.createElement('tr');
                tbody.appendChild(otherNamesRow);
                    var data = document.createElement('th');
                        data.innerHTML="Alias";
                    otherNamesRow.appendChild(data);
                    data = document.createElement('td');
                        data.innerHTML="";
                    otherNamesRow.appendChild(data);
                var descriptionRow = document.createElement('tr');
                tbody.appendChild(descriptionRow);
                    var data = document.createElement('th');
                        data.innerHTML="Description";
                    descriptionRow.appendChild(data);
                    data = document.createElement('td');
                        data.innerHTML="";
                    descriptionRow.appendChild(data);
                var quantityRow = document.createElement('tr');
                tbody.appendChild(quantityRow);
                    var data = document.createElement('th');
                        data.innerHTML="Quantity";
                    quantityRow.appendChild(data);
                    data = document.createElement('td');
                        data.innerHTML="";
                    quantityRow.appendChild(data);
                var priceRow = document.createElement('tr');
                tbody.appendChild(priceRow);
                    var data = document.createElement('th');
                        data.innerHTML="Price";
                    priceRow.appendChild(data);
                    data = document.createElement('td');
                        data.innerHTML = "";
                    priceRow.appendChild(data);
                var dateRow = document.createElement('tr');
                tbody.appendChild(dateRow);
                    var data = document.createElement('th');
                        data.innerHTML="Date Added";
                    dateRow.appendChild(data);
                    data = document.createElement('td');
                        data.innerHTML="";
                    dateRow.appendChild(data);
                var deliverRow = document.createElement('tr');
                tbody.appendChild(deliverRow);
                    var data = document.createElement('th');
                        data.innerHTML="Delivery";
                    deliverRow.appendChild(data);
                    data = document.createElement('td');
                        data.innerHTML="";
                    deliverRow.appendChild(data);
    document.getElementsByTagName("body")[0].appendChild(modal);
    modal.style.display="block";
}

//Script 2
function hide_show(elmtId) {
    var element = document.getElementById(elmtId);
    var arrow = element.getElementsByTagName("i")[0];
    element = element.getElementsByClassName('inventory-hidden')[0];
    //if it's hidden show it. If it's visible, hide it.
    if(element.style.display=="none" || element.style.display=="") {
        element.style.display="block";
        arrow.className="fa fa-caret-down";
    } else {
        element.style.display="none";
        arrow.className="fa fa-caret-right";
    }
}
function add_to_inventory() { //Hide inventory data onclick
    var idisplay=document.getElementById('inventory-display');
    var iupdate=document.getElementById('inventory-update');
    console.log(idisplay.style.display);
    console.log(iupdate.style.display);
    if(idisplay.style.display=='block' && iupdate.style.display=='none') {
        console.log("Check Point 1");
        console.log("Conditions fulfilled");
        idisplay.style.display='none';
        iupdate.style.display='block';
    } else if(idisplay.style.display=='' && iupdate.style.display=='none') { //Same statements as above instead of ||
        idisplay.style.display='none';
        iupdate.style.display='block';
        console.log("Check Point 2");
    } else if(idisplay.style.display=='' && iupdate.style.display=='') { //For before javascript sets anything
        idisplay.style.display='block';
        iupdate.style.display='none';
        console.log("Check Point 3");
    } else {
        console.log("Conditions unfulfilled")
        idisplay.style.display='block';
        iupdate.style.display='none';
        console.log("Check Point 4");
    }
}
function rem_rep_item(i) {
    //Extract the node's itemID
    var RepID = getValue(itemNodeListr, i, 'RepID'); //Assuming the iremNodeListr object still exists
    //Access the db and delete the node;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.responseType = "document";
    xmlhttp.onreadystatechange = function() {
        //Check the return status for success/failure
        if(this.readyState==4 && this.status==200) {
            var xmlDoc = this.responseXML;
            console.log(xmlDoc);
            var return_status = xmlDoc.getElementsByTagName("status")[0].childNodes[0].nodeValue;
            if(return_status==0) { //Success. Rerun the _srchdb() function
                alert("Item Deleted");
            } else if(return_status==1) {
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
    itemID = getValue(itemNodeListr, i, 'itemID');
}
//Script4
// Get the modal
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    modalup = document.getElementById('id02');
        if (event.target == modalup) {
            modalup.style.display = "none";
        }
    }
//Script5
var deliverable="";
function getradio(option) {
    console.log("getting radio");
    if(option=="yes") {
        deliverable="Y";
    } else if(option=="no") {
        deliverable="N";
    }
}
function submit_add_form(i) {
    var quantity=readval("quantity");
    var units=readval("units");
    var state=readval("state");
    var price=readval("price");
    var description=readval("description");
    var dplace=readval("dplace");
    var itemID = getValue(itemNodeList, i, "itemID");

    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () { //When we get a reply from the webserver
        //Display success/failure status
        if(this.readyState==4 && this.status==200){
            console.log(this.responseText);
            if(this.responseText==true){ //Success. Close the modal
                document.getElementById("editAddItem").style.display="none";
                window.alert("Item successfully added");
            } else { //Failure. alert an error
                window.alert("There was a problem");
            }
        }
    }
    xhttp.open("GET", "add_item.php?itemID="+itemID+"&quantity="+quantity+"&units="+units+"&state="+
        state+"&price="+price+"&description="+description+"&deliverable="+deliverable+
        "&dplace="+dplace, true);
    xhttp.send();
}
function readval(id) {
    return	document.getElementById(id).value;
}
function place_order() {
    var orderItem = document.getElementById("orderItem");
}
function change_prof_pic(event) {
    event.preventDefault();
    var file_input = document.getElementById("uploadprofpic");
    file_input.click();
    //No need for the submit button
    //Create custom submit button to send canvas data to the webserver DONE
    //1. click the profile pic. click triggers the change_prof_pic() function which redirects click to input button DONE
    //2. select a file. input button has onchange event that calls edit_image
}
function edit_image(file_input) {
    //1. Get input element
    //2. get file
    var imagefile = file_input.files[0];
    //3. create invisible image element
    var img = document.createElement("img");
    //4. copy image to canvas
    var freader = new FileReader();
    freader.onload = function () {
        img.src = freader.result; //Start unload event
        if (img.complete) {
            console.log("The image is loaded");
            image_proc(img);
        } else img.onload = function () {
            console.log("Will run when the image loads");
            //make modal+canvas visible
            image_proc(img);
        };
    };
    freader.readAsDataURL(imagefile);
    //5. make modal and canvas visible
    //6. do any possible edits
    //7. submit with custom submit button
}
function image_proc(img) {
    console.log("Has the image loaded? "+img.complete);
    var modal = document.getElementById("edit-prof-pic");
    modal.style.display = "block";
    //Load image into canvas.
    //Set fixed canvas dimensions
    var canvas = document.getElementById("canvas");
    //move data from img to canvas. CanvasRenderingContext2D
    var ctx = canvas.getContext('2d');
    var MAX_WIDTH = 600;
    var MAX_HEIGHT = 480;
    var width = img.width;
    var height = img.height;

    if (width > height) {
        if (width > MAX_WIDTH) {
            height *= MAX_WIDTH / width;
            width = MAX_WIDTH;
        }
    } else {
        if (height > MAX_HEIGHT) {
            width *= MAX_HEIGHT / height;
            height = MAX_HEIGHT;
        }
    }
    canvas.width = width;
    canvas.height = height;
    var ctx = canvas.getContext("2d");
    ctx.drawImage(img, 0, 0, width, height);
}
//MODAL MUST BE DISPLAYED FIRST BEFORE CANVAS IS DRAWN ON. IF CANVAS IS REVEALED FROM HIDING,
//ALL DATA ON CANVAS IS GONE! TOOK ME HOURS TO REALIZE :(
function upload_prof_pic() {
    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext('2d');
    canvas.toBlob(function (blob) {
        var xhr = new XMLHttpRequest();
        var fd = new FormData();
        fd.append("myfile", blob, "profpic.jpg");
        xhr.open("POST", "up.php", true);
        xhr.onreadystatechange = function () {
            if(this.readyState==4 && this.status==200) {
                console.log("Upload was successful");
                console.log("The server says "+this.responseText);
            } else {
                console.log("readyState = "+this.readyState+" and status = "+this.status);
            }
        }
        xhr.send(fd);
    }, "image/jpeg", 0.4    )
}

function change_view() {
    var slide_container = document.getElementById("r2c2");
    if(slide_contaner.classList.contains("slide-container-view-list")) {
        slide_container.classList.remove("slide-container-view-list");
    } else {
        slide_container.classList.add("slide-container-view-list");
    }
}

function useless_function() {
    console.log("I'm a fucking useless function");
}
