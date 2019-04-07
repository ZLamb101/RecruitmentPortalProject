
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
 * Deletes the corresponding candidate from a shortList
 * @param listID, the id of the shortList the candidate will be deleted from
 * @param candidateID, the id of that candidate to be deleted
 * @param divID, the short list div number that will be modified
 * @param titleID, the corresponding shortList title number that will be changed
 */
function deleteFromShortList(listID, candidateID, divID, titleID) {
    //Checks to see if it's the final member of the shortList being deleted. If it is, then delete the title from the PHTML
    get(listID, candidateID, divID, titleID, function () {
        var test = this.responseText;
        if(test == "true"){
            var header = "shortList" + titleID;
            var elem = document.getElementById(header);
            elem.parentNode.removeChild(elem);

            var rename = "re-name" + titleID;
            elem = document.getElementById(rename);
            elem.parentNode.removeChild(elem);
        }
    })
    var str = "cand" + divID;
    document.getElementById(str).innerHTML = "";
    var buttonElement = "deleteCandidate"+divID;
    var elem = document.getElementById(buttonElement);
    elem.parentNode.removeChild(elem);
}

/**
 * Deletes the corresponding candidate from a shortList
 * @param listID, the id of the shortList the candidate will be deleted from
 * @param candidateID, the id of that candidate to be deleted
 * @param divID, the short list div number that will be modified
 * @param titleID, the corresponding shortList title number that will be changed
 * @param callback, the function to be called
 */
function get(listID, candidateID, divID, titleID, callback) {
    xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "deleteFromShortList.php?listID=" + listID+ "&candidateID=" +candidateID, true);
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