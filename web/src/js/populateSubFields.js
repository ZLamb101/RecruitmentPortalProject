function updateFields(button)
{

    var subFieldString = "sub-field";
    var index = button.id;

    subFieldString = subFieldString.concat(index[5]);

        get2(button , function () {
            document.getElementById(subFieldString).innerHTML = this.responseText;
        })
        return false;
}


function get2(button, callback) {

    var fieldString = "field";
    var temp = button.id;

    fieldString = fieldString.concat(temp[5]);

    xmlhttp = new XMLHttpRequest();
    var id = document.getElementById(fieldString).value;
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