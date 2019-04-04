
/**
 * Deletes the corresponding selected work experience and then clears the HTML of where it was displayed
 * Decreases all the IDs of elements after this one such that the save will still work
 */
function deleteWorkExperience(number, id){
    xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", "deleteWorkExperience.php?q=" + id, true);

    xmlhttp.send();
    var divToDelete = "workExperience" + number;
    document.getElementById(divToDelete).innerHTML = "";

    var numOfExperience = document.getElementById("work-experience-count").value
    for(var i = (number+1); i < numOfExperience; i++){
        var temp = "role" + i;
        var temp2 = "role" + (i-1);
        document.getElementById(temp).id = temp2;

        var temp = "duration" + i;
        var temp2 = "duration" + (i-1);
        document.getElementById(temp).id = temp2;

        var temp = "employer" + i;
        var temp2 = "employer" + (i-1);
        document.getElementById(temp).id = temp2;

        var temp = "workId" + i;
        var temp2 = "workId" + (i-1);
        document.getElementById(temp).id = temp2;
    }
    document.getElementById("work-experience-count").value = (numOfExperience-1);
}

/**
 * Deletes the corresponding selected skill and then clears the HTML of where it was displayed
 * Decreases all the IDs of elements after this one such that the save will still work
 */
function deleteSkill(number, id){
    xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", "deleteSkill.php?q=" + id, true);

    xmlhttp.send();
    var divToDelete = "skill" + number;
    document.getElementById(divToDelete).innerHTML = "";

    var numOfExperience = document.getElementById("skill-count").value
    for(var i = (number+1); i < numOfExperience; i++){
        var temp = "sub-field" + i;
        var temp2 = "sub-field" + (i-1);
        document.getElementById(temp).id = temp2;

        var temp = "field" + i;
        var temp2 = "field" + (i-1);
        document.getElementById(temp).id = temp2;

        var temp = "contents" + i;
        var temp2 = "contents" + (i-1);
        document.getElementById(temp).id = temp2;

        var temp = "skillId" + i;
        var temp2 = "skillId" + (i-1);
        document.getElementById(temp).id = temp2;
    }
    document.getElementById("skill-count").value = (numOfExperience-1);
}

/**
 * Deletes the corresponding selected qualification and then clears the HTML of where it was displayed
 * Decreases all the IDs of elements after this one such that the save will still work
 */
function deleteQualification(number, id){
    xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", "deleteQualification.php?q=" + id, true);

    xmlhttp.send();
    var divToDelete = "qualification" + number;
    document.getElementById(divToDelete).innerHTML = "";

    var numOfExperience = document.getElementById("qualification-count").value
    for(var i = (number+1); i < numOfExperience; i++){
        var temp = "year" + i;
        var temp2 = "year" + (i-1);
        document.getElementById(temp).id = temp2;

        var temp = "name" + i;
        var temp2 = "name" + (i-1);
        document.getElementById(temp).id = temp2;

        var temp = "qualId" + i;
        var temp2 = "qualId" + (i-1);
        document.getElementById(temp).id = temp2;
    }
    document.getElementById("qualification-count").value = (numOfExperience-1);
}