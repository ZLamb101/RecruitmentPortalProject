/***
 * Processes a search request and generates the output
 * displays a "Searching..." prompt.
 * Gathers all relevant data entered into the search fields.
 * Creates an XML request to livesearch.php to get all the data for the search results.
 * Displays all the results in search InnerHtml
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




