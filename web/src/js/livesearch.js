/***
 * Processes a search request and generates the output
 */
function showResult() {

    var str = document.getElementById("skill-search").value;
    var field = document.getElementById("fields").value;
    var sub_field = document.getElementById("sub-fields").value;

    if (str.length==0) {
        document.getElementById("livesearch").innerHTML="";
        document.getElementById("livesearch").style.border="0px";
        return;
    }

    xmlhttp=new XMLHttpRequest();

    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            document.getElementById("livesearch").innerHTML=this.responseText;
        }
    }

    xmlhttp.open("GET","livesearch.php?query="+str+"&field="+field+"&sub_field="+sub_field,true);

    xmlhttp.send();
}