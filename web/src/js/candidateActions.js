/**
 * Deletes the corresponding selected work experience and then clears the HTML of where it was displayed
 * Decreases all the IDs of elements after this one such that the save will still work
 *
 * @param number, the position of the work experience as displayed on the page
 * @param id, the id of the work experience as in the database
 */
function deleteWorkExperience(number, id)
{
    if (!confirm("Deleting this card is permanent. Are you sure you want to delete?")) {
        return;
    }
    if ((document.getElementById("work-experience-existing-count").value > 0 && id >= 0) || (document.getElementById("work-experience-count").value > 0 && id < 0)) {
        var prefferedCheck = document.getElementById("work-experience-preference" + number);
        if (prefferedCheck.checked) {
            alert("You cannot delete your preferred work experience");
            return;
        }
        if (id >= 0) {
            xmlhttp = new XMLHttpRequest();


            xmlhttp.open("GET", "deleteWorkExperience.php?id=" + id, true);

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
        if (id >= 0) {
            document.getElementById("work-experience-existing-count").value = (document.getElementById("work-experience-existing-count").value - 1);
        }
    } else {
        alert("You cannot delete your last work experience");
    }
}

/**
 * Deletes the corresponding selected skill and then clears the HTML of where it was displayed
 * Decreases all the IDs of elements after this one such that the save will still work
 *
 * @param number, the position of the skill as displayed on the page
 * @param id, the id of the skill as in the database
 */
function deleteSkill(number,id)
{
    if (!confirm("Deleting this card is permanent. Are you sure you want to delete?")) {
        return;
    }
    if ((document.getElementById("skill-existing-count").value > 0 && id >= 0) || (document.getElementById("skill-count").value > 0 && id < 0)) {
        var prefferedCheck = document.getElementById("skill-preference" + number);
        if (prefferedCheck.checked) {
            alert("You cannot delete your preferred skill");
            return;
        }
        if (id >= 0) {
            xmlhttp = new XMLHttpRequest();

            xmlhttp.open("GET", "deleteSkill.php?id=" + id, true);

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
        if (id >= 0) {
            document.getElementById("skill-existing-count").value = document.getElementById("skill-existing-count").value - 1;
        }
        document.getElementById("skill-count").value = (numOfExperience - 1);
    } else {
        alert("You cannot delete your last qualification");
    }
}

/**
 * Deletes the corresponding selected qualification and then clears the HTML of where it was displayed
 * Decreases all the IDs of elements after this one such that the save will still work
 *
 * @param number, the position of the qualification as displayed on the page
 * @param id, the id of the qualification as in the database
 */
function deleteQualification(number, id)
{
    if (!confirm("Deleting this card is permanent. Are you sure you want to delete?")) {
        return;
    }
    if ((document.getElementById("qualification-existing-count").value > 0 && id >= 0) || (document.getElementById("qualification-count").value > 0 && id < 0)) {
        var prefferedCheck = document.getElementById("qualification-preference" + number);
        if (prefferedCheck.checked) {
            alert("You cannot delete your preferred qualification");
            return;
        }
        if (id >= 0) {
            xmlhttp = new XMLHttpRequest();

            xmlhttp.open("GET", "deleteQualification.php?id=" + id, true);

            xmlhttp.send();
        }
        var divToDelete = "qualification" + number;
        var elem = document.getElementById(divToDelete);
        elem.parentNode.removeChild(elem);

        var numOfExperience = document.getElementById("qualification-count").value;
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
        if (id >= 0) {
            document.getElementById("qualification-existing-count").value = document.getElementById("qualification-existing-count").value - 1;
        }
        document.getElementById("qualification-count").value = (numOfExperience - 1);
    } else {
        alert("You cannot delete your last work experience");
    }
}

/**
 * if the max number of skills is yet to be reached
 * Create a <div> node with input fields "year" ,"level", "name" and "major".
 * Call a php function to search rdb and inserts qualification level data.
 * increment the names of the inputs for each invocation.
 * append new <div> to qualification in phtml
 */
function addQualification()
{
    var count = document.getElementById("qualification-count");
    var it = count.getAttribute("value");
    if (it >= 8) {
        alert("You have reached maximum number of qualifications");
        return;
    }
    count.setAttribute("value", ++it);

    mainDiv = document.getElementById("qualifications");


    var qual = document.createElement("div");                       // Create outer <div> node
    qual.setAttribute("class","edit-box partition interior-box-format");
    var divString = "qualification" + it;
    qual.setAttribute("id",divString);

    var deleteButton = document.createElement("input");                //Create delete button
    deleteButton.setAttribute("type", "button");
    deleteButton.setAttribute("class", "btn btn-danger");
    var deleteQualificationNum = "delete-qualification" + it;
    deleteButton.setAttribute("id", deleteQualificationNum);
    deleteButton.setAttribute("value", "Delete");
    var functionName = "deleteQualification(" + it + ",-1)";
    deleteButton.setAttribute("onclick", functionName);
    qual.appendChild(deleteButton);

    var qualChild = document.createElement("div");                        // Create child <div> node (to put label input pairs within)
    qualChild.setAttribute("class","sm-pad form-group");


    var glyphicon = document.createElement("span");
    glyphicon.setAttribute("class","glyphicon glyphicon-info-sign");

    var toolTip = document.createElement("a");
    toolTip.setAttribute("href","#!");
    toolTip.setAttribute("data-toggle","tooltip");
    toolTip.setAttribute("data-placement", "top");
    toolTip.setAttribute("title","The preferred button indicates which card will be displayed when employers search for you!");

    toolTip.appendChild(glyphicon);



    var prefP = document.createElement("label");
    var prefLabel = document.createTextNode("  Preferred: ");
    var pref = document.createElement("input");
    pref.setAttribute("class", "pull-right");
    pref.setAttribute("type", "radio");
    var qualID = "qualification-preference" + it;
    prefP.setAttribute("for", qualID);
    pref.setAttribute("name", "qualification-preference");
    pref.setAttribute("id", qualID);
    pref.setAttribute("value", it);
    if (mainDiv.childNodes.length == 6) {
        pref.checked = true;
    }

    prefP.appendChild(toolTip);
    prefP.appendChild(prefLabel);

    qualChild.appendChild(prefP);                                        //Append Label and input into Child Div
    qualChild.appendChild(pref);

    qual.appendChild(qualChild);                                        //Append child div to parent div

    qualChild = document.createElement("div");                        // Create child <div> node (to put label input pairs within)
    qualChild.setAttribute("class","form-group");

    var levelP = document.createElement("label");
    var levelLabel = document.createTextNode("Level: ");
    var level = document.createElement("select");
    var levelString = "level" + it;
    levelP.setAttribute("for", levelString);
    level.setAttribute("name", levelString);
    level.setAttribute("class", "form-control");
    level.setAttribute("onclick", "updateTypes(this)");
    level.setAttribute("id", levelString);
    levelP.appendChild(levelLabel);
    qualChild.appendChild(levelP);
    qualChild.appendChild(level);

    qual.appendChild(qualChild);                                        //Append child div to parent div

    qualChild = document.createElement("div");                        // Create child <div> node (to put label input pairs within)
    qualChild.setAttribute("class","form-group");

    var typeP = document.createElement("label");
    var typeLabel = document.createTextNode("Field: ");
    var type = document.createElement("select");
    var typeString = "type" + it;
    typeP.setAttribute("for", typeString);
    type.setAttribute("name", typeString);
    type.setAttribute("id", typeString);
    type.setAttribute("class", "form-control");
    typeP.appendChild(typeLabel);

    var typeOption = document.createElement("option");
    var typeOptionLabel = document.createTextNode("All Sub-Categories");
    typeOption.setAttribute("value", "blank");
    typeOption.appendChild(typeOptionLabel);
    type.append(typeOption);

    qualChild.appendChild(typeP);
    qualChild.appendChild(type);

    qual.appendChild(qualChild);                                        //Append child div to parent div

    qualChild = document.createElement("div");                        // Create child <div> node (to put label input pairs within)
    qualChild.setAttribute("class","form-group");

    var yearP = document.createElement("label");
    var yearLabel = document.createTextNode("Year: ");
    var year = document.createElement("input");
    var yearString = "year" + it;
    yearP.setAttribute("for",yearString);
    year.setAttribute("name",yearString);
    year.setAttribute("id",yearString);
    year.setAttribute("type","text");
    year.setAttribute("size",40);
    year.setAttribute("placeholder","Year of completion");
    year.setAttribute("class","form-control");
    year.setAttribute("pattern", "^[0-9]{4}$");
    year.setAttribute("title", "YYYY format. Numeric characters only");
    yearP.appendChild(yearLabel);

    qualChild.appendChild(yearP);
    qualChild.appendChild(year);

    qual.appendChild(qualChild);                                        //Append child div to parent div

    qualChild = document.createElement("div");                        // Create child <div> node (to put label input pairs within)
    qualChild.setAttribute("class","form-group");

    var majorP = document.createElement("label");
    var majorLabel = document.createTextNode("Major:");
    var major = document.createElement("input");
    var majorString = "major" + it;
    majorP.setAttribute("for", majorString);
    major.setAttribute("name", majorString);
    major.setAttribute("id", majorString);
    major.setAttribute("type", "text");
    major.setAttribute("class", "form-control");
    major.setAttribute("size", 40);
    major.setAttribute("placeholder","E.g. Marketing");
    majorP.appendChild(majorLabel);
    qualChild.appendChild(majorP);
    qualChild.appendChild(major);

    qual.appendChild(qualChild);                                        //Append child div to parent div

    mainDiv.appendChild(qual);           // Append <p> to <div> with id="myDIV"


    getLevels(
        function () {
            document.getElementById(levelString).innerHTML = this.responseText;
        }
    )


    return false;

}



/**
 * if the max number of skills has yet to be reached
 * Create a <div> node with input fields "role","duration" and "employer"
 * increment the names of the inputs for each invocation.
 * append new <div> to work experience in phtml
 */
function addWorkExperience()
{
    var count = document.getElementById("work-experience-count");
    var it = count.getAttribute("value");
    if (it >= 8) {
        alert("You have reached maximum number of work-experiences");
        return;
    }

    count.setAttribute("value", ++it);

    var mainDiv = document.getElementById("work-experience");

    var workex = document.createElement("div");                               // Create outer <div> node
    workex.setAttribute("class","edit-box partition interior-box-format");
    var divName = "workExperience" + it;
    workex.setAttribute("id",divName);

    var deleteButton = document.createElement("input");                        //Create delete button
    deleteButton.setAttribute("type", "button");
    var deleteWorkNum = "delete-work-experience" + it;
    deleteButton.setAttribute("id", deleteWorkNum);
    deleteButton.setAttribute("value", "Delete");
    deleteButton.setAttribute("class", "btn btn-danger");
    var functionName = "deleteWorkExperience(" + it + ",-1)";
    deleteButton.setAttribute("onclick", functionName);
    workex.appendChild(deleteButton);

    var workexChild = document.createElement("div");                       // Create child <div> node (to put label input pairs within)
    workexChild.setAttribute("class","sm-pad form-group");

    var glyphicon = document.createElement("span");
    glyphicon.setAttribute("class","glyphicon glyphicon-info-sign");

    var toolTip = document.createElement("a");
    toolTip.setAttribute("href","#!");
    toolTip.setAttribute("data-toggle","tooltip");
    toolTip.setAttribute("data-placement", "top");
    toolTip.setAttribute("title","The preferred button indicates which card will be displayed when employers search for you!");

    toolTip.appendChild(glyphicon);


    var prefP = document.createElement("label");
    prefP.setAttribute("for", divName);
    var prefLabel = document.createTextNode(" Preferred: ");
    var pref = document.createElement("input");
    pref.setAttribute("type", "radio");
    pref.setAttribute("class", "pull-right");
    if (mainDiv.childNodes.length == 6) {
        pref.checked = true;
    }
    var radID = "work-experience-preference" + it;
    pref.setAttribute("id", radID);
    pref.setAttribute("name", "work-experience-preference");
    pref.setAttribute("value", it);
    prefP.appendChild(toolTip);
    prefP.appendChild(prefLabel);

    workexChild.appendChild(prefP);
    workexChild.appendChild(pref);

    workex.appendChild(workexChild);                                    //Append Child div to inner div

    workexChild = document.createElement("div");                       // Create child <div> node (to put label input pairs within)
    workexChild.setAttribute("class","form-group");

    var roleP = document.createElement("label");
    var roleString = "role" + it;
    roleP.setAttribute("for", roleString);
    var roleLabel = document.createTextNode("Role.");              // Create a text node
    var role = document.createElement("input");
    role.setAttribute("name", roleString);
    role.setAttribute("id", roleString);
    role.setAttribute("class", "form-control");
    role.setAttribute("type", "text");
    role.setAttribute("size",40);
    role.setAttribute("placeholder", "Alphabetic characters only");
    role.setAttribute("pattern", "^[a-zA-Z\\s-]+$");
    role.setAttribute("title", "Alphabetic, '-' and space characters only");

    roleP.appendChild(roleLabel);
    workexChild.appendChild(roleP);
    workexChild.appendChild(role);

    workex.appendChild(workexChild);                                    //Append Child div to inner div

    workexChild = document.createElement("div");                       // Create child <div> node (to put label input pairs within)
    workexChild.setAttribute("class","form-group");

    var durationP = document.createElement("label");
    var durationLabel = document.createTextNode("Duration.");      // Create a text node
    var duration = document.createElement("input");
    var durationString = "duration" + it;
    durationP.setAttribute("for", durationString)
    duration.setAttribute("name", durationString);
    duration.setAttribute("id", durationString);
    duration.setAttribute("type","text");
    duration.setAttribute("class", "form-control");
    duration.setAttribute("size",40);
    duration.setAttribute("placeholder","Number in months");
    duration.setAttribute("pattern", "^[0-9]+$");
    duration.setAttribute("title", "Numeric characters only");

    durationP.appendChild(durationLabel);
    workexChild.appendChild(durationP);
    workexChild.appendChild(duration);

    workex.appendChild(workexChild);                                    //Append Child div to inner div

    workexChild = document.createElement("div");                       // Create child <div> node (to put label input pairs within)
    workexChild.setAttribute("class","form-group");

    var employerP = document.createElement("label");
    var employerLabel = document.createTextNode("Employer.");      // Create a text node
    var employer = document.createElement("input");
    var employerString = "employer" + it;
    employerP.setAttribute("for", employerString);
    employer.setAttribute("name", employerString);
    employer.setAttribute("id", employerString);
    employer.setAttribute("type","text");
    employer.setAttribute("class", "form-control");
    employer.setAttribute("size",40);
    employer.setAttribute("placeholder","Alphanumeric characters only");
    employer.setAttribute("pattern", "^[a-zA-Z0-9\\s-]+$");
    employer.setAttribute("title", "Alphanumeric, '-' and space characters only");

    employerP.appendChild(employerLabel);
    workexChild.appendChild(employerP);
    workexChild.appendChild(employer);

    workex.appendChild(workexChild);                                    //Append Child div to inner div

    mainDiv.appendChild(workex);


}


/**
 * if the max number of skills has yet to be reached
 * Create a <div> node with input fields "field","sub-field" and "contents" for a skill
 * Call a php function to search rdb to fill the skill field with data.
 * increment the names of the inputs for each invocation.
 * append new <div> to skill in phtml
 */
function addSkill()
{
    var count = document.getElementById("skill-count");
    var it = count.getAttribute("value");
    if (it >= 8) {
        alert("You have reached maximum number of skills");
        return;
    }
    count.setAttribute("value", ++it);

    mainDiv = document.getElementById("skills");

    var skill = document.createElement("div");                       // Create a <div> node
    skill.setAttribute("class","edit-box partition interior-box-format ");
    var divName = "skill" + it;
    skill.setAttribute("id",divName);

    var deleteButton = document.createElement("input");
    deleteButton.setAttribute("type", "button");
    var deleteSkillNum = "delete-skill" + it;
    deleteButton.setAttribute("id", deleteSkillNum);
    deleteButton.setAttribute("value", "Delete");
    deleteButton.setAttribute("class", "btn btn-danger")
    var functionName = "deleteSkill(" + it + ",-1)";
    deleteButton.setAttribute("onclick", functionName);
    skill.appendChild(deleteButton);

    var skillChild = document.createElement("div");
    skillChild.setAttribute("class", "sm-pad form-group");

    var glyphicon = document.createElement("span");
    glyphicon.setAttribute("class","glyphicon glyphicon-info-sign");

    var toolTip = document.createElement("a");
    toolTip.setAttribute("href","#!");
    toolTip.setAttribute("data-toggle","tooltip");
    toolTip.setAttribute("data-placement", "top");
    toolTip.setAttribute("title","The preferred button indicates which card will be displayed when employers search for you!");

    toolTip.appendChild(glyphicon);


    var prefP = document.createElement("label");

    var prefLabel = document.createTextNode(" Preferred: ");
    var pref = document.createElement("input");
    pref.setAttribute("type", "radio");
    pref.setAttribute("class", "pull-right");
    var skillID = "skill-preference" + it;
    prefP.setAttribute("for", skillID);
    pref.setAttribute("id", skillID);
    pref.setAttribute("name", "skill-preference");
    pref.setAttribute("value", it);
    if (mainDiv.childNodes.length == 6) {
        pref.checked = true;
    }
    prefP.appendChild(toolTip);
    prefP.appendChild(prefLabel);
    skillChild.appendChild(prefP);
    skillChild.appendChild(pref);

    skill.appendChild(skillChild);

    skillChild = document.createElement("div");
    skillChild.setAttribute("class", "form-group");

    var fieldP = document.createElement("label");
    var fieldLabel = document.createTextNode("Field:");
    var field = document.createElement("select");
    var fieldString = "field";
    fieldString = fieldString.concat(it.toString(10));
    fieldP.setAttribute("for", fieldString);

    field.setAttribute("class", "form-control")
    field.setAttribute("name", fieldString);
    field.setAttribute("onclick", "updateFields(this)")
    field.setAttribute("id", fieldString);

    fieldP.appendChild(fieldLabel);
    skillChild.appendChild(fieldP);
    skillChild.appendChild(field);

    skill.append(skillChild);

    skillChild = document.createElement("div");
    skillChild.setAttribute("class", "form-group");

    var subFieldP = document.createElement("label");
    var subFieldLabel = document.createTextNode("Sub-Field:");
    var subField = document.createElement("select");
    var subFieldString = "sub-field";
    var subFieldOption = document.createElement("option");
    var subFieldOptionLabel = document.createTextNode("All Sub-Categories");
    subFieldOption.setAttribute("value", "blank");
    subFieldString = subFieldString.concat(it.toString(10));
    subFieldP.setAttribute("for", subFieldString);
    subField.setAttribute("name", subFieldString);
    subField.setAttribute("id", subFieldString);
    subField.setAttribute("class", "form-control");
    //subField.setAttribute("disabled", true);

    subFieldP.appendChild(subFieldLabel);
    subFieldOption.appendChild(subFieldOptionLabel);
    subField.append(subFieldOption);
    skillChild.appendChild(subFieldP);
    skillChild.appendChild(subField);

    skill.appendChild(skillChild);

    skillChild = document.createElement("div");
    skillChild.setAttribute("class", "form-group");

    var contentsP = document.createElement("label");
    var contentsLabel = document.createTextNode("About:");
    var contents = document.createElement("textarea");
    var contentsString = "contents";
    contentsString = contentsString.concat(it.toString(10));
    contentsP.setAttribute("for", contentsString);

    contents.setAttribute("name", contentsString);
    contents.setAttribute("class", "form-control")
    contents.setAttribute("type","text");
    contents.setAttribute("size",40);
    contents.setAttribute("rows",3);
    contents.setAttribute("placeholder","Alphanumeric characters only");
    contents.setAttribute("pattern", "^[a-zA-Z0-9\\s-]+$");
    //contents.setAttribute("disable", true);

    contentsP.appendChild(contentsLabel);
    skillChild.appendChild(contentsP);
    skillChild.appendChild(contents);

    skill.appendChild(skillChild);

    mainDiv.appendChild(skill);

    getFields(
        function () {
            document.getElementById(fieldString).innerHTML = this.responseText;
        }
    )
    return false;

}

/**
 * Create a <div> node
 * Creates an alert and attaches it to the div node
 * append new <div> to candidate page
 */
function missingInfoAlert()
{
    var alertBox = document.createElement("div");
    alertBox.setAttribute("class","small-box-format center alert alert-danger");

    var para = document.createElement("p");
    var node = document.createTextNode("It looks like you're missing some information. Click ");
    para.appendChild(node);

    var a = document.createElement('a');
    var linkText = document.createTextNode("Here");
    a.appendChild(linkText);
    a.href = "http://localhost:8000/edit-candidate-information";
    para.appendChild(a);

    var node3 = document.createTextNode(" to add what's missing");
    para.appendChild(node3);

    alertBox.appendChild(para);
    var pageDiv = document.getElementById("page2");
    pageDiv.insertBefore(alertBox, pageDiv.firstChild);
}
