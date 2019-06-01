/***
 * Processes a search request and generates the output
 */
function showResult() {
    document.getElementById("livesearch").innerHTML = "<p>Searching...</p>";
    var str = document.getElementById("skill-search").value;
    var field = document.getElementById("field0").value;
    var sub_field = document.getElementById("sub-field0").value;
    var qual = document.getElementById("qual0").value;

    var avail = 0;
    if(document.getElementById('full-time').checked){
        avail += 8;
    }
    if(document.getElementById('part-time').checked){
        avail += 4;
    }
    if(document.getElementById('casual').checked){
        avail += 2;
    }
    if(document.getElementById('contractor').checked){
        avail += 1;
    }

    if(field == "all"){
        alert("Must select a category");
        document.getElementById("livesearch").innerHTML = "";

        document.getElementById("search").innerHTML = "<h4><b>Search Results</b></h4>";
        document.getElementById("search").setAttribute("class", "center");

        return;
    }

    xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            document.getElementById("livesearch").innerHTML=this.responseText;
            if(document.getElementById("livesearch").innerHTML != ""){

                document.getElementById("search").innerHTML = "<h4><b>Search Results</b></h4>" +
                    "<div class=\"center small-box-format\"><input class=\"btn btn-info\" type='button' id='add-all-to-shortlist' value='Add all to Short List' onclick='addAllToShortlist()'></div>";
                document.getElementById("search").setAttribute("class", "space-between");
            }



        }
    }

    xmlhttp.open("GET","livesearch.php?query="+str+"&field="+field+"&sub_field="+sub_field+"&qual="+qual+"&avail="+avail,true);

    xmlhttp.send();

}

/***
 * Function to Add a single candidate to the selected shortlist
 * @param candId, the ID of the candidate being added
 */
function addToShortlist(candId){
    var short_id = document.getElementById("shortlist0").value;
    xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            updateShortList();
        }
    }

    xmlhttp.open("GET","addToShortList.php?candId="+candId+"&shortId="+short_id,true);

    xmlhttp.send();

}

/***
 * Function to add all candidates to the selected shortlist
 * @param candidates, all candidates to  be added
 */
function addAllToShortlist(candidates){
    var candidates = document.getElementById("cand-ids").value;
    var short_id = document.getElementById("shortlist0").value;
    xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            updateShortList();
        }
    }

    xmlhttp.open("GET","addAllToShortList.php?candidates="+candidates+"&shortId="+short_id,true);

    xmlhttp.send();
}

/***
 * Function to view more information on the corresponding candidate
 *
 * @param url, the url to be opened
 * @param candidatId, the ID of the candidate who's information is to be displayed
 */
function displayCandidate(url, candidateId){

    getDisplay(function () {
        newwindow=window.open(url,'name','height=800,width=750');
        if (window.focus) {newwindow.focus()}

    }, candidateId)
    return false;
}

/***
 * Function calls a php function retrieve the candidates information
 *
 * @param callback, a function that describes what to do with the data retrieved
 * @param candidateId, the ID of the candidate being displayed
 */
function getDisplay(callback, candidateId) {
    xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET","selectCandidateToView.php?q="+candidateId ,true);
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



