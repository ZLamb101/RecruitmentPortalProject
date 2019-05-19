
/**
 * Compares passwords to make sure they match. Call a php function to search rdb for duplicates of username. 
 *
 * @return {Boolean}      Whether validation fails or passes
 */
function validateForm()
{
    var submitButton = document.getElementById('submit-handle');
    if (validatePassword()) {
        return false;
    }

        get1(function () {
            myVar = this.responseText;
            if (myVar === 'true') {
                submitButton.click();
            } else {
               // alert(myVar);
                alert("Username already exists");
            }
        })
        return false;
}

function get1(callback) {
    xmlhttp = new XMLHttpRequest();

    var username = document.forms["registration"]["username"].value;

    xmlhttp.open("GET", "registrationValidation.php?q=" + username, true);
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

function validatePassword(){
    alert("hey");
    var password = document.forms["registration"]["password"].value;
    var password_confirm = document.forms["registration"]["password_confirm"].value;

    if (password !== password_confirm) {
        alert("Passwords do not match!");
        return false;
    }
    return true;

}