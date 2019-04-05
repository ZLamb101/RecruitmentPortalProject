
/**
 * Deletes the corresponding selected work experience and then clears the HTML of where it was displayed
 * Decreases all the IDs of elements after this one such that the save will still work
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

function deleteFromShortList(listID, candidateID, divID) {
    var deleteListHeader
    get(listID, candidateID, divID, function () {
        deleteListHeader = this.responseText;
    })

    var str = "cand" + divID;
    document.getElementById(str).innerHTML = "";

    //DELETE THE BUTTON TOO

    if(deleteListHeader == "true"){

    }
}

function get(listID, candidateID, divID, callback) {
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