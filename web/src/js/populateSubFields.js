function updateFields()
{


    var count = document.getElementById("skill-count");
    count = count.getAttribute("value");

    var subFieldString = "sub_field";
    subFieldString = subFieldString.concat(count.toString(10));

        get2(function () {
            document.getElementById(subFieldString).innerHTML = this.responseText;
        })
        return false;
}


function get2(callback) {

    var count = document.getElementById("skill-count");
    count = count.getAttribute("value");
    var fieldString = "field";
    fieldString = fieldString.concat(count.toString(10));

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