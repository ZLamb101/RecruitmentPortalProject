/***
 * Function to update the subfields based off of the option the user has selected
 * @param button, the dropdown the user selects the main field. The users selection triggers this function
 */
function updateFields(button)
{

    var subFieldString = "sub-field";
    var index = button.id;  //Get the users selection

    subFieldString = subFieldString.concat(index[5]);

        get2(button , function () {
            document.getElementById(subFieldString).innerHTML = this.responseText;
        })
        return false;
}

/***
 * Function to call the php function to populate the subfields based upon the input given
 * @param button, the dropdown the user selects the main field. The users selection triggers this function
 * @param callback, a function that describes what to do with the data retrieved
 */
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