
/**
 * Renames the corresponding shortList asynchronously
 * @param divID, the short list div number that will be modified
 * @param id, the id of the shortlist to be modified
 */
function renameList(divID, id){
    var name = prompt("Please enter the new name", "");

    if (name == null || name == "") {
        alert("User cancelled the prompt.");
    } else {
        xmlhttp = new XMLHttpRequest();

        xmlhttp.open("GET", "renameShortList.php?q=" + id+ "&name=" +name, true);

        xmlhttp.send();
        var nameChanged = "shortList"+divID;
        document.getElementById(nameChanged).innerText = name;
    }
}

/**
 * Changes the corresponding shortList description
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
 * @param listID, the id of the shortList the candidate will be deleted from
 * @param candidateID, the id of that candidate to be deleted
 * @param divID, the short list div number that will be modified
 * @param titleID, the corresponding shortList title number that will be changed
 */
function deleteFromShortList(listID, candidateID, divID, i) {
    //Checks to see if it's the final member of the shortList being deleted. If it is, then delete the title from the PHTML
    get(listID, candidateID, i, function () {
      //  document.getElementById(("candidates"+i)).innerHTML = "";
        document.getElementById(("candidates"+i)).innerHTML = this.responseText;
    })
}

/**
 * Deletes the corresponding candidate from a shortList
 * @param listID, the id of the shortList the candidate will be deleted from
 * @param candidateID, the id of that candidate to be deleted
 * @param divID, the short list div number that will be modified
 * @param titleID, the corresponding shortList title number that will be changed
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

function getCreateNewShortlist(name,ID,description,callback) {
    xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "newShortList.php?q=" + name +"&id=" + ID+"&description=" + description,true);
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