
/**
 * calls a function with a callback passed through
 * that either enables or disables the send invite button dependent on the output.
 *
 * @return {Boolean}      Whether validation fails or passes
 */
function updateShortList()
{
    getCandidates(function () {
        if(this.responseText == "<p>Shortlist has been invited previously</p>"){
            document.getElementById('send0').disabled = true;
            document.getElementById('shortlist-candidates').innerHTML = this.responseText;
        } else if(this.responseText == "Do not display"){
			document.getElementById('send0').disabled = true;
			document.getElementById('shortlist-candidates').innerHTML = "";
        }else {
            document.getElementById('send0').disabled = false;
            document.getElementById('shortlist-candidates').innerHTML = this.responseText;
        }
    })
    return false;
}

/**
* Creates an XML request to displayShortList.php
* Sends XML request,
* apply callback.
* 
* @param callback, the function to be called
**/
function getCandidates(callback) {
    xmlhttp = new XMLHttpRequest();

    var shortlist = document.getElementById('shortlist0').value;

    xmlhttp.open("GET", "displayShortList.php?q=" + shortlist, true);
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
function sendInvite(shortlist){
    var submitButton = document.getElementById('submit-handle');
    var emailContent = document.getElementById('content').value;

    xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", "sendInvites.php?q=" + shortlist + "&content="+emailContent, true);
    submitButton.click();
    xmlhttp.send();

}

/***
 * Deletes a shortlist from the database
 *
 * @param titleID,  The ID of the title.
 * @param listID, The ID ofthe list to be deleted.
 */
function deleteShortList(titleID, listId){
    if(!confirm("Deleting this short list is permanent. Are you sure you want to delete?")){
        return;
    }
    xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
             var list = document.getElementById("shortlist"+titleID);
             list.parentNode.removeChild(list);
            document.getElementById("short-list-number").value = document.getElementById("short-list-number").value - 1;
            if(document.getElementById("short-list-number").value == 0){
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

    xmlhttp.open("GET","deleteShortList.php?listId="+listId,true);

    xmlhttp.send();
}

/***
 * Directs an employer to the write email page
 */
function writeEmail(){
    listId = document.getElementById('shortlist0').value;
    window.location.assign("writeEmail.php?list_id="+listId+"&from=search");
}
