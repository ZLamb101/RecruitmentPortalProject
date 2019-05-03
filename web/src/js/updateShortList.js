
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
    xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
             var list = document.getElementById("shortlist"+titleID);
             list.parentNode.removeChild(list);
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
    window.location.assign("writeEmail.php?list_id="+listId);
}
