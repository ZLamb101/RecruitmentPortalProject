
/**
 * Deletes the corresponding selected work experience and then clears the HTML of where it was displayed
 */
function deleteWorkExperience(element, id){
    xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", "deleteWorkExperience.php?q=" + id, true);

    xmlhttp.send();
    element.innerHTML = "";
}

function deleteSkill(element, id){
    xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", "deleteSkill.php?q=" + id, true);

    xmlhttp.send();
    element.innerHTML = "";
}

function deleteQualification(element, id){
    xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", "deleteQualification.php?q=" + id, true);

    xmlhttp.send();
    element.innerHTML = "";
}