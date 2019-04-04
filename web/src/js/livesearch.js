/***
 * Processes a search request and generates the output
 */
function showResult() {
    document.getElementById("livesearch").innerHTML = "<p>Searching...</p>"; // Placeholder for spinner
    var str = document.getElementById("skill-search").value;
    var field = document.getElementById("field0").value;
    var sub_field = document.getElementById("sub-field0").value;

    if(field == "all"){
        alert("Must select a field");
        document.getElementById("livesearch").innerHTML = "";
        return;
    }

    xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            document.getElementById("livesearch").innerHTML=this.responseText;
        }
    }

    xmlhttp.open("GET","livesearch.php?query="+str+"&field="+field+"&sub_field="+sub_field,true);

    xmlhttp.send();
}