function validateForm()
{
        var password = document.forms["registration"]["password"].value;
        var password_confirm = document.forms["registration"]["password_confirm"].value;

        var submitButton = document.getElementById('submit-handle');


    if (password !== password_confirm) {
        alert("Passwords do not match!");
        return false;
    }

        get(function () {
            myVar = this.responseText;
            if (myVar === 'true') {
                submitButton.click();
            } else {
                alert("Username already exists");
            }
        })
        return false;
}

function get(callback) {
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
}