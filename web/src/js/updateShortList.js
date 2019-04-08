
/**
 * Compares passwords to make sure they match. Call a php function to search rdb for duplicates of username. 
 *
 * @return {Boolean}      Whether validation fails or passes
 */
function updateShortList()
{
    getCandidates(function () {
        document.getElementById('shortlist-candidates').innerHTML = this.responseText;
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
 * Sends Invites to all Candidates in a the selected Shortlist
 */
function sendInvite(){
    alert("Invitation's Sent to candidates!")
   // xmlhttp = new XMLHttpRequest();
   // var shortlist = document.getElementById('shortList').value;
   // xmlhttp.open("GET", "sendInvites.php?q=" + shortlist, true);
  //  xmlhttp.send();

}

/***
 * Deletes a shortlist from the database
 */
function deleteShortList(listId){
    xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            //updateShortList();
             var test = this.responseText;
            if(test == "true"){
                 var header = "shortList" + titleID;
                 var elem = document.getElementById(header);
                 elem.parentNode.removeChild(elem);

                 var rename = "re-name" + titleID;
                 elem = document.getElementById(rename);
                 elem.parentNode.removeChild(elem);
             }
        }
    }

    xmlhttp.open("GET","deleteShortList.php?listId="+listId,true);

    xmlhttp.send();
}
