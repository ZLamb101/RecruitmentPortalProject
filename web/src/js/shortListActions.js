
/**
 * calls a function with a callback passed through
 * that either enables or disables the send invite button dependent on the output.
 *
 * @return {Boolean}      Whether validation fails or passes
 */
function updateShortList()
{
    getCandidates(
        function () {
            if (this.responseText == "<p>Shortlist has been invited previously</p>") {
                document.getElementById('send0').disabled = true;
                document.getElementById('shortlist-candidates').innerHTML = this.responseText;
            } else if (this.responseText == "Do not display") {
                document.getElementById('send0').disabled = true;
                document.getElementById('shortlist-candidates').innerHTML = "";
            } else {
                document.getElementById('send0').disabled = false;
                document.getElementById('shortlist-candidates').innerHTML = this.responseText;
            }
        }
    )
    return false;
}

/**
 * Creates an XML request to displayShortList.php
 * Sends XML request,
 * apply callback.
 *
 * @param callback, the function to be called
 **/
function getCandidates(callback)
{
    xmlhttp = new XMLHttpRequest();

    var shortlist = document.getElementById('shortlist0').value;

    xmlhttp.open("GET", "displayShortList.php?listID=" + shortlist, true);
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
 * Creates an XML request to sendInvites.php
 * Sends xml request which Sends the shortlist invites.
 *
 * @param shortlist, a list of candidates that are to be sent invites.
 */
function sendInvite(shortlist)
{
    var submitButton = document.getElementById('submit-handle');
    var emailContent = document.getElementById('content').value;

    xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", "sendInvites.php?listID=" + shortlist + "&content=" + emailContent, true);
    submitButton.click();
    xmlhttp.send();

}

/***
 * Deletes a shortlist from the database
 *
 * @param titleID,  The ID of the title.
 * @param listId, The ID ofthe list to be deleted.
 */
function deleteShortList(titleID, listId)
{
    if (!confirm("Deleting this short list is permanent. Are you sure you want to delete?")) {
        return;
    }
    xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
             var list = document.getElementById("shortlist" + titleID);
             list.parentNode.removeChild(list);
            document.getElementById("short-list-number").value = document.getElementById("short-list-number").value - 1;
            if (document.getElementById("short-list-number").value == 0) {
                var promptDiv = document.createElement("div");
                promptDiv.setAttribute("class", "partition center   interior-box-format");
                var shortlistPrompt = document.createElement("p");
                var promptText = document.createTextNode("Looks like you have no shortlists. Click below to create one and start searching!");
                shortlistPrompt.appendChild(promptText);
                promptDiv.appendChild(shortlistPrompt);
                document.getElementById("short-lists").appendChild(promptDiv);
            }
        }
    }

    xmlhttp.open("GET","deleteShortList.php?listId=" + listId,true);

    xmlhttp.send();
}

/***
 * Directs an employer to the write email page
 */
function writeEmail()
{
    listId = document.getElementById('shortlist0').value;
    window.location.assign("writeEmail.php?list_id=" + listId + "&from=search");
}

/***
 * Function to Add a single candidate to the selected shortlist
 *
 * @param candId, the ID of the candidate being added
 */
function addToShortlist(candId)
{
    var short_id = document.getElementById("shortlist0").value;
    xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            updateShortList();
        }
    }

    xmlhttp.open("GET","addToShortList.php?candId=" + candId + "&shortId=" + short_id,true);

    xmlhttp.send();

}

/***
 * Function to add all candidates to the selected shortlist
 *
 * @param candidates, all candidates to  be added
 */
function addAllToShortlist(candidates)
{
    var candidates = document.getElementById("cand-ids").value;
    var short_id = document.getElementById("shortlist0").value;
    xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            updateShortList();
        }
    }

    xmlhttp.open("GET","addAllToShortList.php?candidates=" + candidates + "&shortId=" + short_id,true);

    xmlhttp.send();
}


/**
 * Creates prompt for renaming of Shortlist
 * Creates an XML request to renameShortList.php
 * Sends XML request,
 * Sets the Html for the shortlist to the new Name.
 *
 * @param divID, the short list div number that will be modified
 * @param id, the id of the shortlist to be modified
 */
function renameList(divID, id)
{
    var name = prompt("Please enter the new name", "");

    if (name == null || name == "") {
        alert("User cancelled the prompt.");
    } else {
        xmlhttp = new XMLHttpRequest();

        xmlhttp.open("GET", "renameShortList.php?id=" + id + "&name=" + name, true);

        xmlhttp.send();
        var nameChanged = "shortList" + divID;
        document.getElementById(nameChanged).innerText = name;
    }
}

/**
 * Changes the corresponding shortList description
 * Creates an XML request to changeDescriptionShortlist.php
 * Sends XML request,
 * apply callback.
 *
 * @param divID, the short list div number that will be modified
 * @param id, the id of the shortlist to be modified
 */
function changeDescription(divID, id)
{
    // var description = prompt("Please enter the new description", "");
    var descriptionChanged = "shortListDescription" + divID;
    var button = document.getElementById("change-description" + divID);
    if (button.value == "Change Description") {
        button.value = "Save Description";
        document.getElementById(descriptionChanged).disabled = false;
    } else {
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
function deleteFromShortList(listID, candidateID, divID, i)
{
    //Checks to see if it's the final member of the shortList being deleted. If it is, then delete the title from the PHTML
    get(
        listID,
        candidateID,
        i,
        function () {
            document.getElementById(("candidates" + i)).innerHTML = this.responseText;
        }
    )
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
function get(listID, candidateID, i, callback)
{
    temp = 'num' + i;
    candCount = document.getElementById(temp).value;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "deleteFromShortList.php?listID=" + listID + "&candidateID=" + candidateID + "&candCount=" + candCount + "&divID=" + i, true);
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
function newShortList(ID,i)
{
    var name = prompt("Please enter the new name: ", "");

    if (name == null || name == "") {
        alert("User cancelled the prompt.");
    } else {
        var description = prompt("Please enter a description: ", "");
        getCreateNewShortlist(
            name,
            ID,
            description,
            function () {
                document.getElementById("short-lists").innerHTML = this.responseText;
                document.getElementById("short-list-number").value = document.getElementById("short-list-number").value + 1;
            }
        )
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
function getCreateNewShortlist(name,ID,description,callback)
{
    xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "newShortList.php?name=" + name + "&id=" + ID + "&description=" + description,true);
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
