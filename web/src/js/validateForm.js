
/**
 * Call a js function to check if passwords match
 * Call a php function to search rdb for duplicates of username.
 *
 * @return {Boolean}      Whether validation fails or passes
 */
function validateForm()
{
    var submitButton = document.getElementById('submit-handle');
    if (!validatePassword()) {
        return false;
    }

        getValidate(function () {
            myVar = this.responseText;
            if (myVar === 'true') {
                submitButton.click();
            } else {
                alert("Username already exists");
            }
        })
        return false;
}

function getValidate(callback) {
    xmlhttp = new XMLHttpRequest();

    var username = document.forms["registration"]["username"].value;

    xmlhttp.open("GET", "registrationValidation.php?name=" + username, true);
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
 * Compares passwords to make sure they match.
 *
 * @return {Boolean} returns false if passwords don't match, true if they do
 */
function validatePassword(){

    var password = document.forms["registration"]["password"].value;
    var password_confirm = document.forms["registration"]["password_confirm"].value;

    if (password !== password_confirm) {
        alert("Passwords do not match!");
        return false;
    }
    return true;

}