
/**
 * Creates prompt for renaming of Shortlist
 * Creates an XML request to renameShortList.php
 * Sends XML request,
 * Sets the Html for the shortlist to the new Name.
 *
 * @param divID, the short list div number that will be modified
 * @param id, the id of the shortlist to be modified
 */
function renameList(divID, id){
    var name = prompt("Please enter the new name", "");

    if (name == null || name == "") {
        alert("User cancelled the prompt.");
    } else {
        xmlhttp = new XMLHttpRequest();

        xmlhttp.open("GET", "renameShortList.php?id=" + id+ "&name=" +name, true);

        xmlhttp.send();
        var nameChanged = "shortList"+divID;
        document.getElementById(nameChanged).innerText = name;
    }
}

/**
 * Changes the corresponding shortList description
 * Creates an XML request to changeDescriptionShortlist.php
 * Sends XML request,
 * apply callback.
 * @param divID, the short list div number that will be modified
 * @param id, the id of the shortlist to be modified
 */
function changeDescription(divID, id){
   // var description = prompt("Please enter the new description", "");
    var descriptionChanged = "shortListDescription"+divID;
    var button = document.getElementById("change-description"+divID);
    if(button.value == "Change Description"){
        button.value = "Save Description";
        document.getElementById(descriptionChanged).disabled = false;
    }else{
        button.value = "Change Description";

        xmlhttp = new XMLHttpRequest();
        var description = document.getElementById(descriptionChanged).value;
        let formData = new FormData();
        formData.append("id", id);
        formData.append("description", description);

        if (description == null || description == "") {
              alert("User cancelled the prompt.");
        } else {
            xmlhttp.open("POST", "changeDescriptionShortList.php", true);

            xmlhttp.send(formData);

            document.getElementById(descriptionChanged).innerText = description;
        }
        document.getElementById(descriptionChanged).disabled = true;
    }
}

/**
 * Deletes the corresponding candidate from a shortList
 *
 * @param listID, the id of the shortList the candidate will be deleted from
 * @param candidateID, the id of that candidate to be deleted
 * @param divID, the short list div number that will be modified
 * @param i, the corresponding shortList title number that will be changed
 */
function deleteFromShortList(listID, candidateID, divID, i) {
    //Checks to see if it's the final member of the shortList being deleted. If it is, then delete the title from the PHTML
    get(listID, candidateID, i, function () {
        document.getElementById(("candidates"+i)).innerHTML = this.responseText;
    })
}

/**
 * Deletes the corresponding candidate from a shortList
 * Creates an XML request to deletefromShortList.php
 * Sends XML request,
 * apply callback.
 *
 * @param listID, the id of the shortList the candidate will be deleted from
 * @param candidateID, the id of that candidate to be deleted
 * @param i, the short list div number that will be modified
 * @param callback, the function to be called
 */
function get(listID, candidateID, i, callback) {
    temp = 'num' + i;
    candCount = document.getElementById(temp).value;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "deleteFromShortList.php?listID=" + listID + "&candidateID=" +candidateID + "&candCount=" + candCount +"&divID=" + i, true);
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // defensive check
            if (typeof callback === "function") {
                // apply() sets the meaning of "this" in the callback
                callback.apply(xmlhttp);
            }
        }
    };

    xmlhttp.send();
}

/**
 * Creates a prompt to get the new shortlists Name.
 * if name is input, Creates prompt for description.
 * Calls a function which Creates a new shortlist, and In its callback adds the html.
 * Increments the number of shortlists on the page.
 * 
 * @param ID, the ID of the candidate, that the shortlist will belong too.
 * @param i, the iteration of the shortlist assigned to the candiate.
 */
function newShortList(ID,i) {
    var name = prompt("Please enter the new name: ", "");

    if (name == null || name == "") {
        alert("User cancelled the prompt.");
    } else {
        var description = prompt("Please enter a description: ", "");
        getCreateNewShortlist(name,ID,description, function () {
            document.getElementById("short-lists").innerHTML = this.responseText;
            document.getElementById("short-list-number").value = document.getElementById("short-list-number").value + 1;
        })
        return false;
    }
}

/**
 * Function to Create new XML Request, to newShortlist.php passes through name, id and Description.
 * Sends XML request
 * On call when completed, apply the callback
 *
 * @param name, the Name associated to the new shortlist
 * @param ID, the ID of the user associated with the Shortlist
 * @param description, a string which holds the description created for a shortlist
 * @param callback, the function to be called
 **/
function getCreateNewShortlist(name,ID,description,callback) {
    xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "newShortList.php?name=" + name +"&id=" + ID+"&description=" + description,true);
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // defensive check
            if (typeof callback === "function") {
                // apply() sets the meaning of "this" in the callback
                callback.apply(xmlhttp);
            }
        }
    };
    xmlhttp.send();
}