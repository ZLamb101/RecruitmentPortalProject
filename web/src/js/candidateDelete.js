
/**
 * Deletes the corresponding selected work experience and then clears the HTML of where it was displayed
 * Decreases all the IDs of elements after this one such that the save will still work
 */
function deleteWorkExperience(number, id){
    if(document.getElementById("work-experience-count").value > 1) {
        xmlhttp = new XMLHttpRequest();


        xmlhttp.open("GET", "deleteWorkExperience.php?q=" + id, true);

        xmlhttp.send();
        var divToDelete = "workExperience" + number;
        document.getElementById(divToDelete).innerHTML = "";

        var numOfExperience = document.getElementById("work-experience-count").value
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
    }else{
        alert("You cannot delete your last work experience");
    }
}


/**
 * Clears the HTML of where it was displayed
 * Decreases all the IDs of elements after this one such that the save will still work
 */
function deleteWorkExperienceHTML(number){
    if(document.getElementById("work-experience-count").value > 1) {

        var divToDelete = "workExperience" + number;
        var elem = document.getElementById(divToDelete);
        elem.parentNode.removeChild(elem);

        var numOfExperience = document.getElementById("work-experience-count").value
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
    }else{
        alert("You cannot delete your last work experience");
    }
}

/**
 * Clears the HTML of where it was displayed
 * Decreases all the IDs of elements after this one such that the save will still work
 */
function deleteSkillHTML(number, id){
    if(document.getElementById("skill-count").value > 1) {
        xmlhttp = new XMLHttpRequest();

        xmlhttp.open("GET", "deleteSkill.php?q=" + id, true);

        xmlhttp.send();
        var divToDelete = "skill" + number;
        var elem = document.getElementById(divToDelete);
        elem.parentNode.removeChild(elem);

        var numOfExperience = document.getElementById("skill-count").value
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
        document.getElementById("skill-count").value = (numOfExperience - 1);
    }else{
        alert("You cannot delete your last qualification");
    }
}


/**
 * Deletes the corresponding selected skill and then clears the HTML of where it was displayed
 * Decreases all the IDs of elements after this one such that the save will still work
 */
function deleteSkill(number){
    if(document.getElementById("skill-count").value > 1) {
        var divToDelete = "skill" + number;
        document.getElementById(divToDelete).innerHTML = "";

        var numOfExperience = document.getElementById("skill-count").value
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
        document.getElementById("skill-count").value = (numOfExperience - 1);
    }else{
        alert("You cannot delete your last qualification");
    }
}

/**
 * Deletes the corresponding selected qualification and then clears the HTML of where it was displayed
 * Decreases all the IDs of elements after this one such that the save will still work
 */
function deleteQualification(number, id){
    if(document.getElementById("qualification-count").value > 1) {
        xmlhttp = new XMLHttpRequest();

        xmlhttp.open("GET", "deleteQualification.php?q=" + id, true);

        xmlhttp.send();
        var divToDelete = "qualification" + number;
        document.getElementById(divToDelete).innerHTML = "";

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
        document.getElementById("qualification-count").value = (numOfExperience - 1);
    }else{
        alert("You cannot delete your last work experience");
    }
}


/**
 * Clears the HTML of where it was displayed
 * Decreases all the IDs of elements after this one such that the save will still work
 */
function deleteQualificationHTML(number){
    if(document.getElementById("qualification-count").value > 1) {
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
        document.getElementById("qualification-count").value = (numOfExperience - 1);
    }else{
        alert("You cannot delete your last work experience");
    }
}