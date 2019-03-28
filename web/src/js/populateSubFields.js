function updateFields()
{
        get(function () {
            document.getElementById("sub-fields").innerHTML = this.responseText;
        })
        return false;
}


function get(callback) {
    xmlhttp = new XMLHttpRequest();
    var id = document.getElementById("fields").value;
    xmlhttp.open("GET", "populateSubFields.php?q=" + id, true);
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