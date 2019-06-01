
/**
 * Compares passwords to make sure they match. Call a php function to search rdb for duplicates of username. 
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
 * Sends Invites to all Candidates in the selected Shortlist
 */
function sendInvite(shortlist){
    var submitButton = document.getElementById('submit-handle');
    var emailContent = document.getElementById('content').value;

    xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // defensive check
            if (typeof callback === "function") {

            }
        }
    };

    xmlhttp.open("GET", "sendInvites.php?q=" + shortlist + "&content="+emailContent, true);
    submitButton.click();
    xmlhttp.send();

}

/***
 * Deletes a shortlist from the database
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
