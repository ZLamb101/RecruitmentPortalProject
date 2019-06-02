/***
 * Function to view more information on the corresponding candidate
 *
 * @param url, the url to be opened
 * @param candidatId, the ID of the candidate who's information is to be displayed
 */
function displayCandidate(url, candidateId)
{

    getDisplay(
        function () {
            newwindow = window.open(url,'name','height=800,width=750');
            if (window.focus) {
                newwindow.focus()}

        },
        candidateId
    )
    return false;
}

/***
 * Function calls a php function retrieve the candidates information
 *
 * @param callback, a function that describes what to do with the data retrieved
 * @param candidateId, the ID of the candidate being displayed
 */
function getDisplay(callback, candidateId)
{
    xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET","selectCandidateToView.php?id=" + candidateId ,true);
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

