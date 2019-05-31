/**
 * Deletes the corresponding selected work experience and then clears the HTML of where it was displayed
 * Decreases all the IDs of elements after this one such that the save will still work
 *
 * @param number, the position of the work experience as displayed on the page
 * @param id, the id of the work experience as in the database
 */
function deleteWorkExperience(number, id){
    if(!confirm("Deleting this card is permanent. Are you sure you want to delete?")){
        return;
    }
    if((document.getElementById("work-experience-existing-count").value > 0 && id >= 0) || (document.getElementById("work-experience-count").value > 0 && id < 0)) {
        var prefferedCheck = document.getElementById("work-experience-preference"+number);
        if(prefferedCheck.checked){
            alert("You cannot delete your preferred work experience");
            return;
        }
        if(id >= 0) {
            xmlhttp = new XMLHttpRequest();


            xmlhttp.open("GET", "deleteWorkExperience.php?q=" + id, true);

            xmlhttp.send();
        }
        var divToDelete = "workExperience" + number;
        var elem = document.getElementById(divToDelete);
        elem.parentNode.removeChild(elem);


        var numOfExperience = document.getElementById("work-experience-count").value;
        for (var i = (number + 1); i < numOfExperience; i++) {
            var elementId = "role" + i;
            var newElementId = "role" + (i - 1);
            document.getElementById(elementId).id = newElementId;

            elementId = "duration" + i;
            newElementId = "duration" + (i - 1);
            document.getElementById(elementId).id = newElementId;

            elementId = "employer" + i;
            newElementId = "employer" + (i - 1);
            document.getElementById(elementId).id = newElementId;

            elementId = "workId" + i;
            newElementId = "workId" + (i - 1);
            document.getElementById(elementId).id = newElementId;
        }
        document.getElementById("work-experience-count").value = (numOfExperience - 1);
        if(id >= 0) {
            document.getElementById("work-experience-existing-count").value = (document.getElementById("work-experience-existing-count").value - 1);
        }
    }else{
        alert("You cannot delete your last work experience");
    }
}

/**
 * Deletes the corresponding selected skill and then clears the HTML of where it was displayed
 * Decreases all the IDs of elements after this one such that the save will still work
 * @param number, the position of the skill as displayed on the page
 * @param id, the id of the skill as in the database
 */
function deleteSkill(number,id){
    if(!confirm("Deleting this card is permanent. Are you sure you want to delete?")){
        return;
    }
    if((document.getElementById("skill-existing-count").value > 0 && id >= 0) || (document.getElementById("skill-count").value > 0 && id < 0)) {

        var prefferedCheck = document.getElementById("skill-preference"+number);
        if(prefferedCheck.checked){
            alert("You cannot delete your preferred skill");
            return;
        }
        if(id >= 0) {
            xmlhttp = new XMLHttpRequest();

            xmlhttp.open("GET", "deleteSkill.php?q=" + id, true);

            xmlhttp.send();
        }

        var divToDelete = "skill" + number;
        var elem = document.getElementById(divToDelete);
        elem.parentNode.removeChild(elem);

        var numOfExperience = document.getElementById("skill-count").value;
        for (var i = (number + 1); i < numOfExperience; i++) {
            var elementId = "sub-field" + i;
            var newElementId = "sub-field" + (i - 1);
            document.getElementById(elementId).id = newElementId;

            elementId = "field" + i;
            newElementId = "field" + (i - 1);
            document.getElementById(elementId).id = newElementId;

            elementId = "contents" + i;
            newElementId = "contents" + (i - 1);
            document.getElementById(elementId).id = newElementId;

            elementId = "skillId" + i;
            newElementId = "skillId" + (i - 1);
            document.getElementById(elementId).id = newElementId;
        }
        if(id >= 0) {
            document.getElementById("skill-existing-count").value = document.getElementById("skill-existing-count").value - 1;
        }
        document.getElementById("skill-count").value = (numOfExperience - 1);
    }else{
        alert("You cannot delete your last qualification");
    }
}

/**
 * Deletes the corresponding selected qualification and then clears the HTML of where it was displayed
 * Decreases all the IDs of elements after this one such that the save will still work
 * @param number, the position of the qualification as displayed on the page
 * @param id, the id of the qualification as in the database
 */
function deleteQualification(number, id){
    if(!confirm("Deleting this card is permanent. Are you sure you want to delete?")){
        return;
    }
    if((document.getElementById("qualification-existing-count").value > 0 && id >= 0) || (document.getElementById("qualification-count").value > 0 && id < 0)) {
        var prefferedCheck = document.getElementById("qualification-preference"+number);
        if(prefferedCheck.checked){
            alert("You cannot delete your preferred qualification");
            return;
        }
        if(id >= 0) {
            xmlhttp = new XMLHttpRequest();

            xmlhttp.open("GET", "deleteQualification.php?q=" + id, true);

            xmlhttp.send();
        }
        var divToDelete = "qualification" + number;
        var elem = document.getElementById(divToDelete);
        elem.parentNode.removeChild(elem);

        var numOfExperience = document.getElementById("qualification-count").value
        for (var i = (number + 1); i < numOfExperience; i++) {
            var elementId = "year" + i;
            var newElementId = "year" + (i - 1);
            document.getElementById(elementId).id = newElementId;

            elementId = "level" + i;
            newElementId = "level" + (i - 1);
            document.getElementById(elementId).id = newElementId;

            elementId = "type" + i;
            newElementId = "type" + (i - 1);
            document.getElementById(elementId).id = newElementId;

            elementId = "qualId" + i;
            newElementId = "qualId" + (i - 1);
            document.getElementById(elementId).id = newElementId;
        }
        if(id >= 0) {
            document.getElementById("qualification-existing-count").value = document.getElementById("qualification-existing-count").value - 1;
        }
        document.getElementById("qualification-count").value = (numOfExperience - 1);
    }else{
        alert("You cannot delete your last work experience");
    }
}