//Script1
function _checkenterkey(event) {
    //This is solely for the "srchdemo" input and
    //"search-input" input elements. There's a separate one for the  srch_div_input
    if(event.key ==='Enter') { //If it's the enter key, call the _searchdb function
        event.preventDefault();
        try {
            _searchdb(document.getElementById('srchdemo').value);
        } catch(err) {
            _searchdb(document.getElementById('search-input').value);
        }
    }
}
function _checkenterkey2(event) {
    //This is solely for the "srch_div_input" input element
    if(event.key==='Enter') { //If it's the enter key, call the _searchdb function
        event.preventDefault();
        try {
            //Do here what the clicking search should've done.
            srch_dbfor_nondistinct_items();
        } catch(err) {
            //Unforeseen technical error!
            window.alert("A technical error occured. Try again later");
        }
    }
}

var itemNodeList;
function _searchdb(str) {
    //This only returns one Image per Item
    var xhttp = new XMLHttpRequest();
    xhttp.responseType = "document";//Only this way, shall we be able to return an XML/HTML document
    xhttp.onreadystatechange = function() { //If we get a reply from the server
        if(this.readyState===4 && this.status===200) { //Check the status and readystate
            if(this.responseXML!=null) { //Do we have any meaningful response other than null?
                var xmlDoc = this.responseXML;
                console.log(xmlDoc);
                var returnStatus = xmlDoc.getElementsByTagName("status")[0].childNodes[0].nodeValue;
                if(returnStatus===0) {
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
                            html+="</div>";<!--item-slide-header-->
                            html+="<div class='item-slide-content' id='itemNo"+i+"'>";
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
                            html+="<button onclick='displaymodal("+i+")'><i class='fa fa-plus-square-o'></i> Add an Item</button>";
                            html+="</div>";
                            html+="</div><!--item-slide-header-->";
                            html+="</div>";
                            document.getElementById("display-search-results").innerHTML+=html;
                        }
                    } else {
                        console.log("0 results were found");
                    }
                } else if(returnStatus===1) { //returnStatus (defined in the php). 1=No results found.
                    console.log("No matching results were found");
                } else if(returnStatus ===2) { //2=couldn't connect to the database
                    console.log("There was a problem connecting to the database");
                } else if(returnStatus ===11) {
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
    //Retrieves items in the users inventory using the user's UID.
    //This function calls the inventoryItemsDetails() function to display the details of each item
    console.log("The function is running");
    var xmlhttpr = new XMLHttpRequest();
    xmlhttpr.responseType = "document";
    xmlhttpr.onreadystatechange = function() {
        if(this.readyState ===4 && this.status ===200) {//The request was fulfilled
            var xmlDoc = this.responseXML;
            console.log(xmlDoc);
            var returnStatus = xmlDoc.getElementsByTagName("status")[0].childNodes[0].nodeValue;
            returnStatus = parseInt(returnStatus)
            if(returnStatus===0) {
                //Results were found
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
                        var elmt = "";
                        elmt = document.createElement("div");
                        elmt.classList.add("item-slide");
                        elmt.indexno = i;
                        elmt.addEventListener("click", function () {
                            inventoryItemDetails(this, itemNodeListr) //Display each inventory item in detail.
                        }, true) //Display Modal. Using only item's info. Seller is self

                        var img = "";
                        img = document.createElement("img");
                        var elmt2 = "";
                        elmt2 = document.createElement("div");
                        elmt2.classList.add("item-slide-image");
                        if(getValue(itemNodeListr, i, 'ImageURI')  === 'None') {
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
                        var h3Elmt = "";
                        h3Elmt = document.createElement("h3");
                        h3Elmt.classList.add("dash_item_name");
                        h3Elmt.innerHTML = getValue(itemNodeListr, i, 'ItemName');
                        elmt3.appendChild(h3Elmt);
                        elmt.appendChild(elmt3);
                        document.getElementById("inventory-display").appendChild(elmt);
                    }
                }

            } else if(returnStatus ===1) {//No Results found
                console.log("No results were found");
            } else if(returnStatus ===3) {//Problem connecting to the database
                console.log("There was a problem connecting to the database");
            } else if(returnStatus ===11) {//User is not logged in. Not even sure how that's possible
                console.log("WTF? Is that even possible");
            }
        } else {//The request wasn't fulfilled for some reason
            console.log("ReadyState = "+this.readyState);
            console.log("Status = "+this.status)
        }
        return null;
    }
    xmlhttpr.open("GET", "xhttp.php?table=Repository", true);
    xmlhttpr.send();
}
function getValue(nodeList, index, tagName) { //This function is just to make things shorter ^
    var value = nodeList[index].getElementsByTagName(tagName)[0].childNodes[0].nodeValue
    return value;
}
function displaymodal(i) {
    //This function displays the item details from the catalogue search. i identifies the item
    //Initially, I wanted to use the same function to display the item details for the user inventory search, but... Epic Fail!
    var html=""; //in the itemNodeList
    html="<div width=100%>";
    html+="<img src='"+getValue(itemNodeList, i, 'ImageURI')+"'>";
    html+="</div>";
    document.getElementById("eAI-11").innerHTML=html;
    html="<div width=100%>";
    html+="<span size=6em position='center'>"+getValue(itemNodeList, i, 'ItemName')+"</span>";
    html+="<br>Other names:\t"+getValue(itemNodeList, i, 'Aliases');
    html+="<br>Description:\t"+getValue(itemNodeList, i, 'Description');
    html+="</div>";
    document.getElementById("item_submit_button").innerHTML="<button type='submit' onclick='submit_add_form("+i+")'><i class='fa fa-plus-square-o'></i>  Add to repository</button>";
    document.getElementById("eAI-12").innerHTML=html;
    document.getElementById("editAddItem").style.display="block";
}

function inventoryItemDetails(elmt, nodelist) {
    //This function is called when an item in the user's inventory is clicked.
    //The function shows the details of the item
    console.log(nodelist);
    var i = elmt.indexno;
    var modal = document.createElement('div');
    modal.width = "100%";
    modal.classList.add("modal");
    modal.id = "inventory_item_details_id";
    var modal_content = document.createElement('div'); //width: 100%
    modal.appendChild(modal_content);
    //Add add an X inside a span to close the modal
    var closeButton = document.createElement('span'); //INCOMPLETE: STYLE THE CLOSE BUTTON OR REMOVE IT
    closeButton.innerHTML = "X";
    //Add an event listener to the span
    closeButton.onclick = function (event) {
        modal.style.display = "none";
    };
    modal.onclick = function (event) {//Event listener to close the modal if user clicks anywhere outside the modal content
        modal.style.display = "none";
    };
    modal.appendChild(closeButton);
    modal_content.classList.add("modal-content");
    var imgDiv = document.createElement('div'); //width: inherit
    modal_content.appendChild(imgDiv);
    var contentDiv = document.createElement('div');
    modal_content.appendChild(contentDiv);
        var table = document.createElement('table');
        table.classList.add("rep_item_details");
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
                        data.innerHTML="Alias:";
                    otherNamesRow.appendChild(data);
                    data = document.createElement('td');
                        data.innerHTML=getValue(nodelist, i, "aliases");
                    otherNamesRow.appendChild(data);
                var descriptionRow = document.createElement('tr');
                tbody.appendChild(descriptionRow);
                    var data = document.createElement('th');
                        data.innerHTML="Description:";
                    descriptionRow.appendChild(data);
                    data = document.createElement('td');
                        data.innerHTML=getValue(nodelist, i, "defaultdescription");
                        data.innerHTML+=getValue(nodelist, i, "description");
                    descriptionRow.appendChild(data);
                var quantityRow = document.createElement('tr');
                tbody.appendChild(quantityRow);
                    var data = document.createElement('th');
                        data.innerHTML="Quantity:";
                    quantityRow.appendChild(data);
                    data = document.createElement('td');
                        data.innerHTML=getValue(nodelist, i, "quantity");
                    quantityRow.appendChild(data);
                var priceRow = document.createElement('tr');
                tbody.appendChild(priceRow);
                    var data = document.createElement('th');
                        data.innerHTML="Price:";
                    priceRow.appendChild(data);
                    data = document.createElement('td');
                        data.innerHTML = getValue(nodelist, i, "unitprice")+"/"+getValue(nodelist, i, "units");
                    priceRow.appendChild(data);
                var dateRow = document.createElement('tr');
                tbody.appendChild(dateRow);
                    var data = document.createElement('th');
                        data.innerHTML="Date Added:";
                    dateRow.appendChild(data);
                    data = document.createElement('td');
                        data.innerHTML=getValue(nodelist, i, "dateadded");
                    dateRow.appendChild(data);
                var deliverRow = document.createElement('tr');
                tbody.appendChild(deliverRow);
                    var data = document.createElement('th');
                        data.innerHTML="Delivery:";
                    deliverRow.appendChild(data);
                    data = document.createElement('td');
                        data.innerHTML=getValue(nodelist, i, "deliverableareas");
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
    if(element.style.display ==="none" || element.style.display ==="") {
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
    if(idisplay.style.display ==='block' && iupdate.style.display ==='none') {
        console.log("Conditions fulfilled");
        idisplay.style.display='none';
        iupdate.style.display='block';
    } else if(idisplay.style.display ==='' && iupdate.style.display ==='none') { //Same statements as above instead of ||
        idisplay.style.display='none';
        iupdate.style.display='block';
    } else if(idisplay.style.display ==='' && iupdate.style.display ==='') { //For before javascript sets anything
        idisplay.style.display='block';
        iupdate.style.display='none';
    } else {
        console.log("Conditions unfulfilled")
        idisplay.style.display='block';
        iupdate.style.display='none';
    }
}
function rem_rep_item(i) {
    //Extract the node's itemID
    var RepID = getValue(itemNodeListr, i, 'RepID'); //Assuming the itemNodeListr object still exists
    //Access the db and delete the node;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.responseType = "document";
    xmlhttp.onreadystatechange = function() {
        //Check the return status for success/failure
        if(this.readyState ===4 && this.status ===200) {
            var xmlDoc = this.responseXML;
            console.log(xmlDoc);
            var return_status = xmlDoc.getElementsByTagName("status")[0].childNodes[0].nodeValue;
            if(return_status ===0) { //Success. Rerun the _srchdb() function
                alert("Item Deleted");
            } else if(return_status ===1) {
                alert("A problem occurred");
            } else {
                console.log(return_status);
                reveal1hide2345('inventory-container', 'prof-container', 'prof-orders', 'prof-msg', 'prof-pictures');
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
//Script4
// Get the modal
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    modalup = document.getElementById('id02');
        if (event.target  === modalup) {
            modalup.style.display = "none";
        }
    }
//Script5
var deliverable="";
function getradio(option) {
    console.log("getting radio");
    if(option ==="yes") {
        deliverable="Y";
    } else if(option==="no") {
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
        if(this.readyState ===4 && this.status ===200){
            console.log(this.responseText);
            if(this.responseText ===true){ //Success. Close the modal
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
    var file_input = document.getElementById("uploadprofpic"); //The real input button (hidden)
    file_input.click(); //This opens the file browser for image selection.
    //No need for the submit button
    //Create custom submit button to send canvas data to the webserver DONE
    //1. click the profile pic. click triggers the change_prof_pic() function which redirects click to input button DONE
    //2. select a file. input button has onchange event that calls edit_image
}
function edit_image(file_input) {
    //Function is triggered by onchange event listener
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
            if(this.readyState ===4 && this.status ===200) {
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

function display_item_images (input_elmt, context) {
    //trigger this function with onchange event handler on input button
    //This funciton displays the images and upon clicking on each of the images, editing should be possible. //LATER
    //Context is the string that tells whether the function is called during adding or editing of an item
    //This determines the div in which we draw the canvas images
    var file_array = input_elmt.files;
    if(context==='add') {
        //Adding an Item
        var imgs_container = document.getElementById('up_imgs_container');
    } else {
        //Editing
        var imgs_container = document.getElementById('db_img_up_dsp');
    }

    recursive_image_loader(0, file_array, imgs_container);

}
function recursive_image_loader(indx, file_array, imgs_container) {
    //This is used in the display_item_images function recursively
    //get the images one by one from the array
    //read the image data using fileReader
    var file_reader = new FileReader;
    file_reader.onload = function () {
        var img = document.createElement('img');
        img.src = file_reader.result;
        img.onload = function () {
            showImageInCanvas(img, imgs_container);
        }

        if(indx<(file_array.length-1)) {
            recursive_image_loader(indx+1, file_array, imgs_container);
        }
    }
    file_reader.readAsDataURL(file_array[indx]);
    //append the dataURL to an img elmt src
}
function showImageInCanvas(img, imgs_container) {
    //Show the scaled version of the image in a canvas.
    //It's triggered by after choosing files.
    //By now the image is already loaded into an img element. Put it in a canvas and display it.
    var canvas2d = document.createElement('canvas');
    //set dimensions of canvas. max is 200px
    var MAX_HEIGHT = 200;
    var MAX_WIDTH = 200;

    if(img.height>img.width) {//if the image height is greater than the width scale it to the maximum height
        img.width *= MAX_HEIGHT/img.height;
        img.height = MAX_HEIGHT;
    } else {//if the image width is greater than the height, scale it to the maximum width
        img.height *= MAX_WIDTH/img.width;
        img.width = MAX_WIDTH;
    }
    //Insert image in canvas
    var canvas = document.createElement('canvas');
    canvas.width = img.width;
    canvas.height = img.height;
    var ctx = canvas.getContext('2d');
    ctx.drawImage(img, 0, 0, img.width, img.height);
    imgs_container.appendChild(canvas);
    //Get the image from the canvas and upload it. Nah. Do it in a different function to upload everything at once
}
function uploadCanvasImages() {
    //This function gets all the data and selected images and uploads them at once
    //It's used when adding an element
    //1. Get form data as well and append it to the FormData object and send it
    //2. Get canvas array

    //Get input data
    var itemName = document.getElementById("itemname").value;
    var otherNames = document.getElementById("othernames").value;
    var category = document.getElementById("category").value;
    var description = document.getElementById("description").value
    var fd = new FormData(); //Create form data element

    //Append the input data to the Form Data object
    fd.append("ItemName", itemName);
    fd.append("OtherNames", otherNames);
    fd.append("Category", category);
    fd.append("Description", description);

    //Get the images and append them recursively one after another
    var canvasArray = document.getElementById("up_imgs_container").getElementsByTagName("canvas");
    var index = 0;
    //Check if there are any images
    if(canvasArray.length!==0) {
        //There are some images
        //recursively append the images to fd
        canvasArray[index].toBlob(function (blob) {
            recursiveBlobCallback(canvasArray, index, fd, blob, "add");
        }, "image/jpeg", 0.4);
    }
    else {
        //There are no images. Upload just the form data
        sendformdata(fd, "add");
    }
    //Theres a bunch of asynchronous functions ^
    //Call the displaymodal() function when upload is done. And that's after sending the form data
}
function editUploadCanvasImages(index) {
    //This function gets all the data and selected images and uploads them at once
    //It's used when editing an element
    //Get form data as well and append it to the FormData object and send it
    //Get canvas array

    //Get input data
    var itemName = document.getElementById("db_itemname").value;
    var otherNames = document.getElementById("db_othernames").value;
    var category = document.getElementById("db_category").value;
    var description = document.getElementById("db_description").value
    console.log(index);
    console.log("NodeList");
    console.log(nodeList[index].getElementsByTagName("itemid")[0]);
    var itemID = nodeList[index].getElementsByTagName("itemid")[0].childNodes[0].nodeValue;
    console.log(itemID);
    var fd = new FormData(); //Create form data element

    //Append the input data to the Form Data object
    fd.append("ItemName", itemName);
    fd.append("OtherNames", otherNames);
    fd.append("Category", category);
    fd.append("Description", description);
    fd.append("ItemID", itemID);

    //Get the Units data and append it to the fd. INCOMPLETE until I modify the upload.php script to accept the Units data
    //Get the Units data from two arrays: 1. The selectedUnits array which contains the indices of the selected Units
    //In the allUnitsList - an array that contains XML objects with Units' details
    var obj = {};
    var arr = [];
    if(selectedUnits.length>0) {
        //Some Units have been selected for an given Item. Append them to a JS Object

        for(i=0;i<selectedUnits.length;i++) {
            //Read the units into an array. JS object, then parse the object into a JSON string.
            arr.push(allUnitsList[selectedUnits[i]].getElementsByTagName('unitid')[0].childNodes[0].nodeValue);
        }
        //Append the array into an object and stringify it into JSON to append to the form
        obj["unitsarr"] = arr;
        var jsn = JSON.stringify(obj);
        fd.append("units", jsn);
    } else {
        fd.append("units", "");
    }
    //Do the same as above, but with the images set for deletion
    //Reusing the obj and arr parameters
    //But first, reinitialize
    arr=[];
    obj = {};
    if(imgs_for_deletion.length>0) {
        //Some images are set for deletion
        for(i=0;i<imgs_for_deletion.length;i++) {
            //Read the imageids into an array to be appended as JSON to be appended to fd
            arr.push(imgs_for_deletion[i]);
        }
    }
    if(arr.length>0) {
        obj["imgids"] = arr;
        jsn = JSON.stringify(obj);
        fd.append("delImgs", jsn); //DONE. Off to the sever side.
    } else {
        fd.append("delImgs", ""); //Append a null string
    }

    //Get the uploaded images and append them recursively one after another
    var canvasArray = document.getElementById("db_img_up_dsp").getElementsByTagName("canvas");
    var index = 0;
    //Check if there are any images
    if(canvasArray.length!==0) {
        //There are some images
        //recursively append the images to fd
        canvasArray[index].toBlob(function (blob) {
            recursiveBlobCallback(canvasArray, index, fd, blob, "edit");
        }, "image/jpeg", 0.4);
    }
    else {
        //There are no images. Upload just the form data
        sendformdata(fd, "edit");
    }
}
function recursiveBlobCallback(canvasArray, index, fd, blob, context) {
    //This function appends the images to fd recursively
    fd.append("myFile[]", blob, "pic"+index+".jpg");
    console.log("Just appended file number: "+index);

    if(index<(canvasArray.length-1)) {
        //There are still files in the array
        canvasArray[index+1].toBlob(function (blob) {
            recursiveBlobCallback(canvasArray, index+1, fd, blob, context);
        }, "image/jpeg", 1);
    } else {
        //Appending is done. Now send the files by AJAX

        sendformdata(fd, context);
    }
}
function sendformdata(fd, context) {
    //context tells which function is accessing the serverside script so that it
    //knows what functions to call. Two contexts exist so far. "add" for when the function is called to add an item to
    // the database and "edit" for when the funciton is called to edit an already existing item
    //Sends form data to the server
    var xhr = new XMLHttpRequest();
    xhr.responseType = "document";
    xhr.onreadystatechange = function () {
        if(this.readyState ===4 && this.status ===200) {
            var xmlDoc = this.responseXML;
            console.log(xmlDoc);
            //Values of returnStatus range from 1 ~ 7. Each is defined in upload.php
            var returnStatus = xmlDoc.getElementsByTagName('returnstatus')[0].childNodes[0].nodeValue;
            returnStatus = parseInt(returnStatus);
            //Split into (up to 7) cases.
            if(returnStatus===0) {
                //Everything went fine! Reload the page
                console.log("Everything went fine!");
                //Here, call the displaymodal function instead of reloading the page.
                var returning_function = xmlDoc.getElementsByTagName("functn")[0].childNodes[0].nodeValue;
                if(returning_function ==="add") {
                    //Get elmt.
                    //Write a separate function that searches the db but does something different upon return
                    //And calls the display_modal function in its readystate event listening callback function
                    var query = xmlDoc.getElementsByTagName("itemname")[0].childNodes[0].nodeValue;
                    edit_added_item(query); //This searches the db for the item by the itemname again and
                    //displays the item edit modal
                } else if(returning_function ==="edit") {
                    //Reload the page
                    location.reload();
                } else {
                    //Fatal programming error
                    window.alert("Fatal programming error!");
                }
            } else if(returnStatus===1) {
                //A connection to the database couldn't be established
                window.alert("A connection to the database could not be established. Please try again later");
            } else if(returnStatus===2) {
                window.alert("Some required fields are empty. Please submit all required data.");
            } else if(returnStatus===5) {
                //Illegal file type. Unlikely
                window.alert("Unacceptable file type");
            } else if(returnStatus===6){
                //Oversize file
                window.alert("One of the files you're trying to upload is bigger than 7 MB.");
            }
            else {
                console.log(returnStatus);
                window.alert("There was a technical error with the server. Please try again later");
            }
        } else {
            console.log("readyState = " + this.readyState + "and status = "+this.status);
        }
    }
    xhr.open("POST", "upload.php?context="+context, true);
    xhr.send(fd);
}
function edit_added_item(query) {
    //Non-distinct here means the function returns multiple images, if available for the items searched
    //In contrast the _searchdb() function only returns one image per Item
    var xhr = new XMLHttpRequest();
    xhr.responseType = "document";
    xhr.onreadystatechange = function () {
        if(this.readyState ===4 && this.status ===200) {
            //Everything set
            var xmlDoc = this.responseXML;
            console.log(xmlDoc);
            var return_status = xmlDoc.getElementsByTagName("status")[0].childNodes[0].nodeValue;
            if(return_status ===0) { //Success.
                //Extract the data from the document.
                nodeList = xmlDoc.getElementsByTagName('Items')[0].getElementsByTagName('Item');
                //Put check to trigger an error if nodeList.length>1. Only one distinct item has to be returned LATER
                //Create a minimalistic element with only the index. it's all we need from it to trigge the
                //display_modal() function
                var elmt = document.createElement('div');
                elmt.index = 0;
                //Trigger the display_modal() funcion here passing the element. The function takes the element and
                //Reads its index which is then used to read data from the returned node into a modal
                display_modal(elmt);
            } else if(return_status ===1) {
                console.log("No results were found");
            }
            else {
                console.log("A problem occured. Details comin' up.");
                console.log(return_status);
            }
        } else {
            //Houston, we have a problem
            console.log("readystate: "+this.readyState);
            console.log("status :"+this.status);
        }
    }
    xhr.open("POST", "Profiles/xhttp.php?table=editItems&q="+query, true);
    xhr.send();
}

function switch_panes (elmt) {
    var dbeditor_form = document.getElementById("dbeditor_form");
    var db_display = document.getElementById("db_display");

    //if the add div is hidden, show it and hide the edit div
    if(db_display.style.display ==="none" || db_display.style.display ==="") {
        db_display.style.display="block";
        dbeditor_form.style.display="none";
        elmt.innerHTML="Add";
    } else {
        db_display.style.display="none";
        dbeditor_form.style.display="block";
        elmt.innerHTML="Edit";
    }
}
var nodeList; //This way, other functions can access items using only the index appended to the html elements to look up
//The item's data in the nodeList array
function srch_dbfor_nondistinct_items() {
    //Non-distinct here means the function returns multiple images, if available for the items searched
    //In contrast the _searchdb() function only returns one image per Item
    var query = document.getElementById("srch_div_input").value;
    var xhr = new XMLHttpRequest();
    xhr.responseType = "document";
    xhr.onreadystatechange = function () {
        if(this.readyState ===4 && this.status ===200) {
            //Everything set
            var xmlDoc = this.responseXML;
            console.log(xmlDoc);
            var return_status = xmlDoc.getElementsByTagName("status")[0].childNodes[0].nodeValue;
            return_status = parseInt(return_status);
            if(return_status ===0) { //Success.
                //Extract the data from the document.
                nodeList = xmlDoc.getElementsByTagName('Items')[0].getElementsByTagName('Item');
                var display_div = document.getElementById('dsp_div'); //Container where the items will be put
                display_div.innerHTML = "";
                var count = 0;
                for(count=0;count<nodeList.length;count++) {
                    console.log(nodeList.length);
                    console.log("Count: "+count);
                    var elmt = document.createElement('div');
                    elmt.class="dbedit_elmt";
                    elmt.classList.add('item-slide');
                    //click event listener to open modal
                    elmt.onclick = function () { display_modal(this);  };
                    elmt.index = count;
                    var elmt2 = document.createElement('div');
                    elmt2.class = "dsp_img";
                    var img = document.createElement('img');
                    img.src = nodeList[count].getElementsByTagName('images')[0].getElementsByTagName('imagedata')[0].getElementsByTagName('imageuri')[0].childNodes[0].nodeValue;
                    elmt2.appendChild(img);
                    elmt.appendChild(elmt2);
                    elmt2 = document.createElement('div');
                    elmt2.class = "dsp_item_data";
                    //Insert the itemdata in elmt2
                    //Use a string
                    //Start of table
                    var innerhtml = "";
                    innerhtml = "<table>";
                    innerhtml += "<tr>"; //Row starts
                    innerhtml += "<th>Item name: </th>";
                    innerhtml += "<td>"+nodeList[count].getElementsByTagName('itemdata')[0].getElementsByTagName('itemname')[0].childNodes[0].nodeValue+"</td>";
                    innerhtml += "</tr>"; //Row ends
                    innerhtml += "<tr>"; //Row starts
                    innerhtml += "<th>Other names: </th>";
                    innerhtml += "<td>"+nodeList[count].getElementsByTagName('itemdata')[0].getElementsByTagName('aliases')[0].childNodes[0].nodeValue+"</td>";
                    innerhtml += "</tr>"; //Row ends
                    innerhtml += "<tr>"; //Row starts
                    innerhtml += "<th>Category: </th>";
                    innerhtml += "<td>"+nodeList[count].getElementsByTagName('itemdata')[0].getElementsByTagName('category')[0].childNodes[0].nodeValue+"</td>";
                    innerhtml += "</tr>"; //Row ends
                    innerhtml += "<tr>"; //Row starts
                    innerhtml += "<th>Description: </th>";
                    try {
                        innerhtml += "<td>"+nodeList[count].getElementsByTagName('itemdata')[0].getElementsByTagName('description')[0].childNodes[0].nodeValue+"</td>";
                    } catch (err) {
                        innerhtml += "<td>No description</td>";
                        console.log(err.message);
                    }
                    innerhtml += "</tr>"; //Row ends
                    innerhtml += "</table>";
                    //End of table
                    elmt2.innerHTML = innerhtml;
                    elmt.appendChild(elmt2);
                    display_div.appendChild(elmt);
                }
            } else if(return_status ===1) {
                console.log("No results were found");
            }
            else {
                console.log("A problem occured. Details comin' up.");
                console.log(return_status);
            }
        } else {
            //Houston, we have a problem
            console.log("readystate: "+this.readyState);
            console.log("status :"+this.status);
        }
    }
    xhr.open("POST", "Profiles/xhttp.php?table=editItems&q="+query, true);
    xhr.send();
}

function getunits(itemID) {
    //Function accesses database to retrieve all the Units associated with an item
    //Returns an array with the units or null if no units at all
    //Returns an xmlDoc array with units or false otherwise
    var xhr = new XMLHttpRequest();
    xhr.responseType = "document";
    xhr.onreadystatechange = function () {
        if(this.readyState ===4 && this.status ===200) {
            //Everything set
            var xmlDoc = this.responseXML;
            console.log(xmlDoc);
            var return_status = xmlDoc.getElementsByTagName("status")[0].childNodes[0].nodeValue;
            return_status = parseInt(return_status);
            if(return_status ===0) { //Success.
                //Extract the data from the document.
                return xmlDoc.getElementsByTagName("Unit"); //Return a node list of the Units
            } else if(return_status ===1) {
                console.log("No results were found");
                return false;
            }
            else {
                console.log("A problem occured. Details comin' up.");
                console.log(return_status);
                return false;
            }
        } else {
            //Houston, we have a problem
            console.log("readystate: "+this.readyState);
            console.log("status :"+this.status);
        }
    }
    xhr.open("POST", "Profiles/xhttp.php?table=getUnits&ItemID="+itemID, true);
    xhr.send();
}
var imgNodeList;
function display_modal(elmt) {
    //Fill in the data in the form in the modal using the nodeList array and the item's index
    //Then display the modal and call the listunits() function
    //Use the getUnits() function to fill in the selectedUnits array.

    var index = elmt.index;
    var modal = document.getElementById('db_modal');
    //Put the info into the modal first
    var elmt = document.getElementById('modal-content');
    var db_image = document.getElementById('db_image');
    db_image.innerHTML = ""; //Initialize and flush all children
    var db_info = document.getElementById('db_info');
    var db_units_all = document.getElementById('db_units_all');
    var img = document.createElement('img');
    var db_itemname = document.getElementById('db_itemname');
    var db_othernames = document.getElementById('db_othernames');
    var db_category = document.getElementById('db_category');
    var db_description = document.getElementById('db_description');
    //Get the span element and add to it an onclick event with the elmt as a parameter
    var up_btn = document.getElementById("db_up_btn"); //All the modal elements have the same id.
    up_btn.addEventListener("click", function () {
        editUploadCanvasImages(index);
    });
    //Insert the image into the modal
    img.src = nodeList[index].getElementsByTagName('images')[0].getElementsByTagName('imagedata')[0].getElementsByTagName('imageuri')[0].childNodes[0].nodeValue;
    db_image.appendChild(img);
    //Insert the data into the form elemnts' value
    db_itemname.value = nodeList[index].getElementsByTagName('itemdata')[0].getElementsByTagName('itemname')[0].childNodes[0].nodeValue;
    db_othernames.value = nodeList[index].getElementsByTagName('itemdata')[0].getElementsByTagName('aliases')[0].childNodes[0].nodeValue;
    db_category.value = nodeList[index].getElementsByTagName('itemdata')[0].getElementsByTagName('category')[0].childNodes[0].nodeValue;
    try {
        db_description.value = nodeList[index].getElementsByTagName('itemdata')[0].getElementsByTagName('description')[0].childNodes[0].nodeValue;
    } catch (err) {
        db_description.value = "";
    }
    //Put images. All of them.
    //First: get the images node array
    imgNodeList = nodeList[index].getElementsByTagName('images')[0].getElementsByTagName('imagedata');
    var db_images = document.getElementById('db_images');
    db_images.innerHTML = ""; //Initialize
    for(i=0;i<imgNodeList.length;i++) {
        img = document.createElement('img');
        img.src = imgNodeList[i].getElementsByTagName('imageuri')[0].childNodes[0].nodeValue;
        img.class = "db_images_class";
        img.index = i; //This will be like the pointer to the imgNodeList
        img.addEventListener("click", function () {
            markOrUnmarkImgForDeletion(this);
        })
        db_images.appendChild(img);
    }
    //Add a div for uploading more pictures
    var db_img_upload = document.getElementById('db_img_up');
    //Display the modal
    modal.style.display="block";
    listunits(index); //Search and display all the available Units
}
function initialize_available_units_list(index) {
    //First, initialize/empty selected_units
    selectedUnits = [];
    //After listing all the available Units, search for the units for the clicked item element in an array
    var itemID = nodeList[index].getElementsByTagName('itemdata')[0].getElementsByTagName('itemid')[0].childNodes[0].nodeValue;
    var xhr = new XMLHttpRequest();
    xhr.responseType = "document";
    xhr.onreadystatechange = function () {
        if(this.readyState ===4 && this.status ===200) {
            //Everything set
            var xmlDoc = this.responseXML;
            console.log(xmlDoc);
            var return_status = xmlDoc.getElementsByTagName("status")[0].childNodes[0].nodeValue;
            return_status = parseInt(return_status);
            if(return_status ===0) { //Success.
                //Extract the data from the document.
                //Initialize the selectedUnits array with the indices of the units in the allUnitsList array. Yeah. it's a bit complicated
                var tmp1;
                var tmp2;
                var selected_units_list = xmlDoc.getElementsByTagName("Unit");
                for(i=0;i<selected_units_list.length;i++) {
                    //For each of the units in the returned node list
                    //Find the index in the allUnitsList and "push" it to the selectedUnitsList array
                    tmp1 = selected_units_list[i].getElementsByTagName("UnitID")[0].childNodes[0].nodeValue;
                    for(j=0;j<allUnitsList.length;j++) {
                        tmp2 = allUnitsList[j].getElementsByTagName("UnitID")[0].childNodes[0].nodeValue;
                        if(tmp1 ===tmp2) {
                            //Then add j to the selected units' list
                            selectedUnits.push(j);
                            //Easy peasy. I only have to do this once, luckily to initialize the selectedUnits array.
                        }
                    }
                }
                //Display the Units inside the assigned div
                var db_units_all = document.getElementById('db_units_all');
                //Create an html table in a string and post it into a div in db_units_all. LATER: Create a function for thi
                //Reload the db_units_selected div
                var db_units_selected = document.getElementById('db_units_selected');
                //Create an html table in a string and post it into a div in db_units_all
                var html = "";
                html="<table>";
                html+="<tr>";
                html+="<th>Unit Name</th><th>Symbol</th>";
                html+="</tr>";
                for(i=0;i<selectedUnits.length;i++) {
                    html+="<tr onclick='unselectunit("+i+")'>"; //Each row in the table is tagged with its index in the units array
                    //INCOMPLETE until I write the selectunit(index) function to select the unit for the item;
                    html+="<td>";
                    html+=allUnitsList[selectedUnits[i]].getElementsByTagName("NamePlural")[0].childNodes[0].nodeValue;
                    html+="</td>";
                    html+="<td>";
                    try {
                        html+=allUnitsList[selectedUnits[i]].getElementsByTagName("Symbol")[0].childNodes[0].nodeValue;
                    } catch (err) {
                        console.log(err.message);
                        html+="N/A";
                    }
                    html+="</td>";
                    html+="</tr>";
                }
                html+="</table>";
                db_units_selected.innerHTML = html;
            } else if(return_status ===1) {
                console.log("No results were found");
                return false;
            }
            else {
                console.log("A problem occured. Details comin' up.");
                console.log(return_status);
                return false;
            }
        } else {
            //Houston, we have a problem
            console.log("readystate: "+this.readyState);
            console.log("status :"+this.status);
        }
    }
    xhr.open("POST", "Profiles/xhttp.php?table=getUnits&ItemID="+itemID, true);
    xhr.send();
}
var allUnitsList;
function listunits(index) {
    //Accesses the database from the xhttp script using table="allUnits"
    //Then lists the Units' names and symbols in the div id-d "db_units_all" for selection
    var db_units_all = document.getElementById('db_units_all');
    var xhr = new XMLHttpRequest();
    xhr.responseType = "document";
    xhr.onreadystatechange = function () {
        if(this.readyState===4 && this.status===200) {
            //Everything set
            var xmlDoc = this.responseXML;
            console.log("Units:");
            console.log(xmlDoc);
            var return_status = xmlDoc.getElementsByTagName("status")[0].childNodes[0].nodeValue;
            return_status = parseInt(return_status);
            if(return_status ===0) { //Success.
                //Extract the data from the document and build the table
                var db_units_all = document.getElementById('db_units_all');
                allUnitsList = xmlDoc.getElementsByTagName('Unit');
                //Create an html table in a string and post it into a div in db_units_all
                var html = "";
                html="<table>";
                html+="<tr>";
                html+="<th>Unit Name</th><th>Symbol</th>";
                html+="</tr>";
                for(i=0;i<allUnitsList.length;i++) {
                    html+="<tr onclick='selectunit("+i+")'>"; //Each row in the table is tagged with its index in the units array
                    //INCOMPLETE until I write the selectunit(index) function to select the unit for the item;
                    html+="<td>";
                    html+=allUnitsList[i].getElementsByTagName("NamePlural")[0].childNodes[0].nodeValue;
                    html+="</td>";
                    html+="<td>";
                    try {
                        html+=allUnitsList[i].getElementsByTagName("Symbol")[0].childNodes[0].nodeValue;
                    } catch (err) {
                        console.log(err.message);
                        html+="N/A";
                    }
                    html+="</td>";
                    html+="</tr>";
                }
                html+="</table>";
                db_units_all.innerHTML = html;
                //Display the units the item alredy has in the appropriate assigned div
                initialize_available_units_list(index);
            } else if(return_status ===1) {
                console.log("No results were found");
            }
            else {
                console.log("A problem occured. Details comin' up.");
                console.log(return_status);
            }
        } else {
            //Houston, we have a problem
            console.log("readystate: "+this.readyState);
            console.log("status :"+this.status);
        }

    }
    xhr.open("POST", "Profiles/xhttp.php?table=allUnits");
    xhr.send();
}
//Global array to hold the indices of the selected Units
var selectedUnits = [];
function selectunit(index) {
    //This function is called when a user clicks on one of the units to select it for an item
    //the single parameter, index is an integer representing the unit's index in the global array allUnitsList
    //Check first if the index is already in the array before adding
    //Instead of storing the item indices, store the UnitIDs
    if(!selectedUnits.contains(index)) {
        //If it doesn't contain the index. Add it. Instead of the index, use the UnitIDs
        selectedUnits.push(index); //How about pushing the index
        //Reload the table in the db_units_selected
        var db_units_selected = document.getElementById('db_units_selected');
        //Create an html table in a string and post it into a div in db_units_all
        var html = "";
        html="<table>";
        html+="<tr>";
        html+="<th>Unit Name</th><th>Symbol</th>";
        html+="</tr>";
        for(i=0;i<selectedUnits.length;i++) {
            html+="<tr onclick='unselectunit("+i+")'>"; //Each row in the table is tagged with its index in the units array
            //INCOMPLETE until I write the selectunit(index) function to select the unit for the item;
            html+="<td>";
            html+=allUnitsList[selectedUnits[i]].getElementsByTagName("NamePlural")[0].childNodes[0].nodeValue;
            html+="</td>";
            html+="<td>";
            try {
                html+=allUnitsList[selectedUnits[i]].getElementsByTagName("Symbol")[0].childNodes[0].nodeValue;
            } catch (err) {
                console.log(err.message);
                html+="N/A";
            }
            html+="</td>";
            html+="</tr>";
        }
        html+="</table>";
        db_units_selected.innerHTML = html;
    }
}
function unselectunit(index) {
    //This function is called when the user clicks on
    //Check if the index is in the array selectedUnits[]. Remove it. Reload the table.
    if(selectedUnits.contains(selectedUnits[index])) {
        //Remove the value from the array. Prototype an Array() function for that
        selectedUnits = selectedUnits.remove(index);
        //Reload the db_units_selected div
        var db_units_selected = document.getElementById('db_units_selected');
        //Create an html table in a string and post it into a div in db_units_all
        var html = "";
        html="<table>";
        html+="<tr>";
        html+="<th>Unit Name</th><th>Symbol</th>";
        html+="</tr>";
        for(i=0;i<selectedUnits.length;i++) {
            html+="<tr onclick='unselectunit("+i+")'>"; //Each row in the table is tagged with its index in the units array
            //INCOMPLETE until I write the selectunit(index) function to select the unit for the item;
            html+="<td>";
            html+=allUnitsList[selectedUnits[i]].getElementsByTagName("NamePlural")[0].childNodes[0].nodeValue;
            html+="</td>";
            html+="<td>";
            try {
                html+=allUnitsList[selectedUnits[i]].getElementsByTagName("Symbol")[0].childNodes[0].nodeValue;
            } catch (err) {
                console.log(err.message);
                html+="N/A";
            }
            html+="</td>";
            html+="</tr>";
        }
        html+="</table>";
        db_units_selected.innerHTML = html;
    }
}
//Adding a method to the Array object to check if a certain value is in the array
Array.prototype.contains = function (value) { //Checks whether the array contains the given value
    for(i=0;i<this.length;i++) {
        if(this[i] ===value) {
            return true;
        }
    }
    return false;
};
Array.prototype.remove = function (index) { //removes the value corresponding to the given index and returns a new array
    var arr = []; //New temporary array
    for(i=0;i<this.length;i++) {
        if(i!==index) {
            //Move to the new array
            arr.push(this[i]);
        }
    }
    return arr;
};
Array.prototype.delete = function (value) { //Deletes all the values corresponding to 'value' and returns a new array
    var arr = []; //New temporary array
    for(i=0;i<this.length;i++) {
        if(this[i]!==value) {
            //Move to the new array
            arr.push(this[i]);
        }
    }
    return arr;
};
var imgs_for_deletion = []; //An array with images marked for deletion
function markOrUnmarkImgForDeletion(img) {
    //This maintains the imgs_for_deletion array, which will be sent with the form data
    //To delete the images
    //Also visually make marked images stand out with CSS
    //1. Get the element's index
    var index = img.index;
    var imgID = imgNodeList[index].getElementsByTagName("imageid")[0].childNodes[0].nodeValue;
    if(!imgs_for_deletion.contains(imgID)) {
        //Add the image id
        imgs_for_deletion.push(imgID);
        //Mark the image with css
        img.style.opacity = 0.5;
    }
    else {
        //Remove the image from the list
        imgs_for_deletion = imgs_for_deletion.delete(imgID);
        //Unmark the image with css
        img.style.opacity = 1.0;
    }
}
var inboxMax = 0.5; //Initial is 1. A 0 was considered as an unset variable. Script knows how to handle it.
function inboxMessages() {
    //This shows all the ongoing message exchanges in the inbox
    //Set the inboxMax somewhere. It's got to be a global and static
    var context = "inbox";
    var fd = new FormData();
    fd.append("context", context);
    fd.append("inboxMax", inboxMax);

    var xht = new XMLHttpRequest();
    xht.responseType = "document";
    xht.onreadystatechange = function () {
        if(this.status===200 && this.readyState===4) {
            var xmlDoc = this.responseXML;
            console.log(xmlDoc);
            //Get the return status
            var returnStatus = xmlDoc.getElementsByTagName("returnstatus")[0].childNodes[0].nodeValue;
            //Convert return status to integer
            returnStatus = parseInt(returnStatus);
            //Analyze the return status for errors
            if(returnStatus===0) {
                //Success
                console.log("Success! Proceed!");
                var msgDisplay = document.getElementById("msg-display");
                //Fetch the node list
                var msg_node_list = xmlDoc.getElementsByTagName("message"); //Array
                //Fetch one by one from the array into the container
                console.log(msg_node_list.length+" nodes");
                for(var i=0;i<msg_node_list.length; i++) {
                    var elmt = document.createElement("div"); //Add an onclick event and an index number
                    elmt.recepID = msg_node_list[i].getElementsByTagName("theirid")[0].childNodes[0].nodeValue;
                    elmt.addEventListener("click", function () {
                        messageClient(this); //Attach the element on to the function
                    }, true);
                    elmt.className = "inbox-slide";
                    var elmt2 = document.createElement("div");
                    elmt2.className="ib-prof";
                    var elmt3 = document.createElement("img");
                    elmt3.src = "Pictures/"+msg_node_list[i].getElementsByTagName("theirid")[0].childNodes[0].nodeValue;
                    elmt2.appendChild(elmt3);
                    elmt.appendChild(elmt2);
                    elmt2 = document.createElement("div");
                    elmt2.className = "ib-msg";
                    elmt3 = document.createElement("div");
                    elmt3.className = "ib-title-time";
                    var elmt4 = document.createElement("div");
                    elmt4.className = "ib-title";
                    elmt4.innerHTML = msg_node_list[i].getElementsByTagName("name")[0].childNodes[0].nodeValue;
                    elmt3.appendChild(elmt4);
                    elmt4 = document.createElement("div");
                    elmt4.className = "ib-time";
                    elmt4.innerHTML = msg_node_list[i].getElementsByTagName("timestamp")[0].childNodes[0].nodeValue;
                    elmt3.appendChild(elmt4);
                    elmt2.appendChild(elmt3);
                    elmt3 = document.createElement("div");
                    elmt3.className = "ib-msg-txt";
                    elmt3.innerHTML = msg_node_list[i].getElementsByTagName("msgtext")[0].childNodes[0].nodeValue;
                    elmt2.appendChild(elmt3);
                    elmt.appendChild(elmt2);
                    msgDisplay.appendChild(elmt); //Append to the msg div. Make sure it's unique!! This script is shared.
                    console.log("Appended!");
                }
            } else {
                console.log(xmlDoc.getElementsByTagName("msg")[0].childNodes[0].nodeValue);
                console.log("Problem "+returnStatus+" occured.");
            }
        } else {
            //Analyze the status and ready states
            console.log(this.status);
            console.log(this.readyState);
        }
    };
    xht.open("POST", "../messages.php", true);
    xht.send(fd);
}
function messageClient(elmt) {
    //DEPENDENTS: isLogged, loadMessages(), itemNodeList[],
    //This opens the messaging API, accesses the database to get the recepient's name and then calls the loadMessages function
    //uses isLogged variable. This is defined in mprofile.php
    if(isLogged) {
        //User is logged in
        console.log("Is logged in");
        //Get seller ID as is appended on the element in the inbox.
        var recepID = elmt.recepID;
        //Open messenger interface with UserID embedded somewhere for retrieval by the sendMessage() and loadMessages()
        var msgInterface = document.getElementById("msg_iface");
        msgInterface.recepID = recepID; //Append the receipient ID to the element. This violates HTML5 integrity but meh!!
        //Get and set the recepient name and get the image with AJAX
        //Access the db
        var fd = new FormData();
        //Get the recepID here
        fd.append("recepID", recepID);
        fd.append("context", "get_recep_name");
        var xht = new XMLHttpRequest();
        xht.responseType = "document";
        xht.onreadystatechange = function () {
            if(this.status===200 && this.readyState===4) {
                var xmlDoc = this.responseXML;
                var returnStatus = xmlDoc.getElementsByTagName("returnStatus")[0].childNodes[0].nodeValue;
                returnStatus = parseInt(returnStatus);
                if(returnStatus===0) {
                    //Things went fine. I expect two things in the returned xmlDoc
                    //1. the name of the recepient
                    //2. the imageURI of the recepient
                    //var recep_img = xmlDoc.getElementsByTagName("recepimg")[0].childNodes[0].nodeValue;
                    document.getElementById("msg_name").innerHTML = xmlDoc.getElementsByTagName("recepname")[0].childNodes[0].nodeValue;
                    //Also set bind the imageURI to the element for subsequent reference.
                    msgInterface.style.display = "block";
                    loadMessages();
                } else {
                    console.log("Recep name, img retrieval failed.");
                    console.log("Return Status="+returnStatus);
                    //Show error to user. LATER
                }
            } else {
                console.log("Recep name, img retrieval failed. Server problem?");
                console.log("Status="+this.status+" Ready State="+this.readyState);
                //Show error to user. LATER
            }
        };
        xht.open("POST", "../messages.php", true);
        xht.send(fd);
    } else {
        //User is not logged in
        console.log("Not logged in!");
        //Prompt user to sign up or sign in
        // document.getElementById('orderItem').style.display = "none";
        document.getElementById('sgn_in_selector').style.display = "block";
    }
}
//This is to handle messages;
function sendMessage() {
    //From the perspective of a buyer messaging a seller. (Because of where the recepientID comes from)
    //This is all predicated from someone clicking "contact seller". A version for "reply to message", for the
    //seller's part will be handled too.
    //This function sends the message and refreshes
    var msgText = document.getElementById('msgtxt').value;
    //Send this text via AJAX to the db. The UserID/ClientID is already described in the $_SESSION['xxxID']
    //So sending it is unnecessary.
    //Get the seller's ID
    var recepID = document.getElementById("msg_iface").recepID; //Recepient ID
    //Create the Form Data object;
    var fd = new FormData;
    fd.append("context", "send");
    fd.append("recepID", recepID);
    fd.append("msgText", msgText);
    //Send the text via AJAX to messages.php
    var xht = new XMLHttpRequest();
    xht.responseType = "document";
    xht.onreadystatechange = function () {
        //Send and call refreshMessages();
        if(this.status===200 && this.readyState===4) {
            //Everything went according to plan
            //Get the return xml
            var xmlDoc = this.responseXML;
            console.log(xmlDoc);
            //Get the return status
            var returnStatus = xmlDoc.getElementsByTagName("returnstatus")[0].childNodes[0].nodeValue;
            //Convert return status to integer
            returnStatus = parseInt(returnStatus);
            //Analyze the return status for errors
            if(returnStatus===0) {
                //Everything went according to plan
                //Clear text area
                document.getElementById('msgtxt').value = "";
                //Call loadMessages and finish.
                console.log("The message was sent successfully");
                loadMessages();
            } else {
                console.log(xmlDoc.getElementsByTagName("msg")[0].childNodes[0].nodeValue);
                console.log("Problem "+returnStatus+" occured.");
            }
        } else {
            //Analyze the status and ready states
            console.log(this.status);
            console.log(this.readyState);
        }
    };
    xht.open("POST", "../messages.php",true);
    xht.send(fd);
}
var msgCapsuleContainer = document.getElementById("msg_capsule_container");
var offset = 0.5; //The lower limit for rows to be fetched, based on the Serial number 'cause the date would make
//things a little complicated
function loadMessages() {
    //This is from the perspective of the buyer messaging a seller. The other way round will be hadled semi-independently
    //This fetches messages into the msg_capsule_container div
    //Fetch the messages using AJAX
    var xht = new XMLHttpRequest();
    var recepID = document.getElementById("msg_iface").recepID; //Recepient ID
    //Create the Form Data object;
    var fd = new FormData;
    fd.append("context", "fetch");
    fd.append("recepID", recepID);
    fd.append("offset", offset);
    xht.responseType = "document";
    xht.onreadystatechange = function () {
        //Send and call refreshMessages();
        if(this.status===200 && this.readyState===4) {
            //Everything went according to plan
            //Get the return xml
            var xmlDoc = this.responseXML;
            console.log(xmlDoc);
            //Get the return status
            var returnStatus = xmlDoc.getElementsByTagName("returnstatus")[0].childNodes[0].nodeValue;
            //Convert return status to integer
            returnStatus = parseInt(returnStatus);
            //Analyze the return status for errors
            if(returnStatus===0) {
                //Everything went according to plan
                //load the div with messages
                //That depends on the returned XML, so return XML from PHP first.
                var msg_node_list = xmlDoc.getElementsByTagName("message"); //Contains 10 messages at a time
                //Get the message capsule container
                var msg_capsule_container = document.getElementById("msg_capsule_container");
                msg_capsule_container.innerHTML = "";
                //Create the message container capsules
                for(var i = 0; i<msg_node_list.length;i++) {
                    //Get all the <message> variables first
                    var timesent = msg_node_list[i].getElementsByTagName("timesent")[0].childNodes[0].nodeValue;
                    var msgtext = msg_node_list[i].getElementsByTagName("msgtext")[0].childNodes[0].nodeValue;
                    //  var pictureid = msg_node_list[i].getElementsByTagName("pictureid")[0].childNodes[0].nodeValue;
                    //  var imageuri = msg_node_list[i].getElementsByTagName("imageuri")[0].childNodes[0].nodeValue;
                    var bool_in_out = msg_node_list[i].getElementsByTagName("sender")[0].childNodes[0].nodeValue;

                    var elmt = document.createElement("div");
                    elmt.id = "cap_wrap"; //Capsule outer wrap
                    var elmt2 = document.createElement("div");
                    elmt2.id = "cap_col_left";
                    elmt.appendChild(elmt2);
                    elmt2 = document.createElement("div");
                    elmt2.id = "cap_col_center";
                    var elmt3 = document.createElement("div"); //Date and time of sending
                    elmt3.id="cap_col_center_top";
                    elmt3.innerHTML = timesent;
                    elmt2.appendChild(elmt3);
                    elmt3 = document.createElement("div"); //Actual message
                    elmt3.id="cap_col_center_middle";
                    var elmt4 = document.createElement("div");
                    elmt4.classList.add("cap_msg_text_wrapper");
                    //Change background color depending on whether message is in or outbound
                    if(bool_in_out==="inbound") {
                        elmt4.classList.add("cap_msg_text_inbound");
                    } else {
                        elmt4.classList.add("cap_msg_text_outbound");
                    }
                    elmt4.innerHTML = msgtext;
                    elmt3.appendChild(elmt4);
                    elmt2.appendChild(elmt3);
                    var elmt3 = document.createElement("div"); //"seen". Will be implemented later
                    elmt3.id="cap_col_center_bottom";
                    elmt2.appendChild(elmt3);
                    elmt.appendChild(elmt2);
                    elmt2 = document.createElement("div");
                    elmt2.id = "cap_col_right";
                    elmt.appendChild(elmt2);
                    msg_capsule_container.appendChild(elmt);
                }
            } else {
                console.log("A problem with returnStatus "+returnStatus+" occured.");
            }
        } else {
            //Analyze the status and ready states
            console.log(this.status);
            console.log(this.readyState);
        }
    };
    xht.open("POST", "../messages.php", true);
    xht.send(fd);
}
//Caution:
//I've used a about 3 different node list arrays, all with global scope. Hope they don't get mixed up.
//PROGRESS TAGS
//1. LATER
//2. INCOMPLETE
//3. PICKUP. Tags function being worked upon until its completion
//4. URGENT. The code might not work without fixing this and I'm forgetful

//GLOBAL VARIABLES
//1. allUnitsList
//2. itemNodeList
//3. itemNodeListr
//4. selectedUnits Contains indices of seleced units
//5. nodeList: Item nodes for when searching to edit the items

//URGENT: When the user clicks editUploadCanvasImages(), get the the so far available units
//And update the selectedUnits variable
