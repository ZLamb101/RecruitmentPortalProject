/***
 * Function to update the subfields based off of the option the user has selected
 * @param button, the dropdown the user selects the main field. The users selection triggers this function
 */
function updateFields(button)
{

    var subFieldString = "sub-field";
    var index = button.id;  //Get the users selection

    subFieldString = subFieldString.concat(index[5]);

        getSubFields(button , function () {
            document.getElementById(subFieldString).innerHTML = this.responseText;
        })
        return false;
}

/***
 * Function to call the php function to populate the subfields based upon the input given
 * @param button, the dropdown the user selects the main field. The users selection triggers this function
 * @param callback, a function that describes what to do with the data retrieved
 */
function getSubFields(button, callback) {

    var fieldString = "field";
    var temp = button.id;

    fieldString = fieldString.concat(temp[5]);

    xmlhttp = new XMLHttpRequest();
    var id = document.getElementById(fieldString).value;
    xmlhttp.open("GET", "populateSubFields.php?id=" + id, true);
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
 * Creates an XML request to populatefields.php
 * Sends XML request,
 * apply callback.
 * @param callback, the function to be called
 **/
function getFields(callback) {
    xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "populateFields.php?q=" , true);
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
 * Aslong as a level is selected
 * calls a function, on callback sets all of the type element's inner Html with the response text.
 * @param {Object}      button, Contains what Qualification level is Selected.
 **/
function updateTypes(button)
{
    if(button.value != "all") {
        var typeString = "type";
        var index = button.id;

        typeString = typeString.concat(index[5]);

        getTypes(button, function () {
            document.getElementById(typeString).innerHTML = this.responseText;
        })
    }
    return false;
}

/**
 * Creates an XML request to populateTypes.php
 * Sends XML request,
 * apply callback.
 *
 * @param callback, the function to be called
 **/
function getTypes(button, callback) {

    xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "populateTypes.php?q=", true);
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

    return false

}


/**
 * Creates an XML request to populateLevels.php
 * Sends XML request,
 * apply callback.
 * @param callback, the function to be called
 **/
function getLevels(callback) {
    xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "populateLevels.php?q=" , true);
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