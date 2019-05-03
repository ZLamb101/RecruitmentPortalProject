
/**
 * Create a <div> node with input fields "year" and "name"
 * increment the names of the inputs for each invokation.
 * append new <div> to qualification in phtml
 */
function addQualification(){
	var count = document.getElementById("qualification-count");
	var it = count.getAttribute("value");
    if(it >= 8){
        alert("You have reached maximum number of qualifications");
        return;
    }
    count.setAttribute("value", ++it);




	var qual = document.createElement("div");                       // Create outer <div> node
	qual.setAttribute("class","edit-box partition interior-box-format");
	var divString = "qualification"+it;
	qual.setAttribute("id",divString);

    var deleteButton = document.createElement("input");				//Create delete button
    deleteButton.setAttribute("type", "button");
    deleteButton.setAttribute("class", "btn btn-danger");
    var deleteQualificationNum = "delete-qualification"+it;
    deleteButton.setAttribute("id", deleteQualificationNum);
    deleteButton.setAttribute("value", "Delete");
    var functionName = "deleteQualificationHTML("+it+")";
    deleteButton.setAttribute("onclick", functionName);
    qual.appendChild(deleteButton);

    var qualChild = document.createElement("div");						// Create child <div> node (to put label input pairs within)
    qualChild.setAttribute("class","sm-pad form-group");

	var prefP = document.createElement("label");
	var prefLabel = document.createTextNode("Preferred: ");
	var pref = document.createElement("input");
	pref.setAttribute("class", "pull-right");
	pref.setAttribute("type", "radio");
	prefP.setAttribute("for", "qualification-preference");
	pref.setAttribute("name", "qualification-preference");
	pref.setAttribute("id", "qualification-preference");
	pref.setAttribute("value", it);
	prefP.appendChild(prefLabel);
	qualChild.appendChild(prefP);										//Append Label and input into Child Div
	qualChild.appendChild(pref);

    qual.appendChild(qualChild);										//Append child div to parent div

    qualChild = document.createElement("div");						// Create child <div> node (to put label input pairs within)
    qualChild.setAttribute("class","form-group");

    var levelP = document.createElement("label");
    var levelLabel = document.createTextNode("Level: ");
	var level = document.createElement("select");
	var levelString = "level"+it;
	levelP.setAttribute("for", levelString);
	level.setAttribute("name", levelString);
	level.setAttribute("class", "form-control");
    level.setAttribute("onclick", "updateTypes(this)");
	level.setAttribute("id", levelString);
    levelP.appendChild(levelLabel);
    qualChild.appendChild(levelP);
    qualChild.appendChild(level);

    qual.appendChild(qualChild);										//Append child div to parent div

    qualChild = document.createElement("div");						// Create child <div> node (to put label input pairs within)
    qualChild.setAttribute("class","form-group");

    var typeP = document.createElement("label");
    var typeLabel = document.createTextNode("Field: ");
	var type = document.createElement("select");
	var typeString = "type"+it;
	typeP.setAttribute("for", typeString);
    type.setAttribute("name", typeString);
    type.setAttribute("id", typeString);
    type.setAttribute("class", "form-control");
    typeP.appendChild(typeLabel);

    var typeOption = document.createElement("option");
    var typeOptionLabel = document.createTextNode("All subcategories");
    typeOption.setAttribute("value", "blank");
    typeOption.appendChild(typeOptionLabel);
    type.append(typeOption);

	qualChild.appendChild(typeP);
	qualChild.appendChild(type);

    qual.appendChild(qualChild);										//Append child div to parent div

    qualChild = document.createElement("div");						// Create child <div> node (to put label input pairs within)
    qualChild.setAttribute("class","form-group");

	var yearP = document.createElement("label");
	var yearLabel = document.createTextNode("Year: ");
	var year = document.createElement("input");
	var yearString = "year"+it;
	yearP.setAttribute("for",yearString);
	year.setAttribute("name",yearString);
	year.setAttribute("id",yearString);
	year.setAttribute("type","text");
	year.setAttribute("size",40);
	year.setAttribute("class","form-control");
	year.setAttribute("pattern", "^[0-9]{4}$");
	year.setAttribute("title", "YYYY format. Numeric characters only");
	yearP.appendChild(yearLabel);

	qualChild.appendChild(yearP);
	qualChild.appendChild(year);

    qual.appendChild(qualChild);										//Append child div to parent div

    qualChild = document.createElement("div");						// Create child <div> node (to put label input pairs within)
    qualChild.setAttribute("class","form-group");

	var majorP = document.createElement("label");
	var majorLabel = document.createTextNode("Major:");
	var major = document.createElement("input");
	var majorString = "major"+it;
	majorP.setAttribute("for", majorString);
	major.setAttribute("name", majorString);
	major.setAttribute("id", majorString);
	major.setAttribute("type", "text");
	major.setAttribute("class", "form-control");
	major.setAttribute("size", 40);
	majorP.appendChild(majorLabel);
	qualChild.appendChild(majorP);
	qualChild.appendChild(major);

    qual.appendChild(qualChild);										//Append child div to parent div

	document.getElementById("qualifications").appendChild(qual);           // Append <p> to <div> with id="myDIV"


    getLevels(function () {
        document.getElementById(levelString).innerHTML = this.responseText;
    })


    return false;

}



/**
 * Create a <div> node with input fields "role","duration" and "employer"
 * increment the names of the inputs for each invokation.
 * append new <div> to work experience in phtml
 */
function addWorkExperience(){
	var count = document.getElementById("work-experience-count");
	var it = count.getAttribute("value");
    if(it >= 8){
        alert("You have reached maximum number of work-experiences");
        return;
    }

    count.setAttribute("value", ++it);


	var workex = document.createElement("div");              		         // Create outer <div> node
    workex.setAttribute("class","edit-box partition interior-box-format");
    var divName = "workExperience" + it;
    workex.setAttribute("id",divName);

    var deleteButton = document.createElement("input");						//Create delete button
    deleteButton.setAttribute("type", "button");
    var deleteWorkNum = "delete-work-experience"+it;
    deleteButton.setAttribute("id", deleteWorkNum);
    deleteButton.setAttribute("value", "Delete");
    deleteButton.setAttribute("class", "btn btn-danger");
    var functionName = "deleteWorkExperienceHTML("+it+")";
    deleteButton.setAttribute("onclick", functionName);
    workex.appendChild(deleteButton);

    var workexChild = document.createElement("div");                       // Create child <div> node (to put label input pairs within)
    workexChild.setAttribute("class","sm-pad form-group");


	var prefP = document.createElement("label");
	prefP.setAttribute("for", divName);
	var prefLabel = document.createTextNode("Preferred: ");
	var pref = document.createElement("input");
	pref.setAttribute("type", "radio");
	pref.setAttribute("class", "pull-right");
	var radID = "work-experience-preference"+it;
	pref.setAttribute("id", radID);
	pref.setAttribute("name", "work-experience-preference");
	pref.setAttribute("value", it);
	prefP.appendChild(prefLabel);
	workexChild.appendChild(prefP);
	workexChild.appendChild(pref);

	workex.appendChild(workexChild);									//Append Child div to inner div

    workexChild = document.createElement("div");                       // Create child <div> node (to put label input pairs within)
    workexChild.setAttribute("class","form-group");

	var roleP = document.createElement("label");
	var roleString = "role"+it;
	roleP.setAttribute("for", roleString);
	var roleLabel = document.createTextNode("Role.");     		 // Create a text node
	var role = document.createElement("input");
	role.setAttribute("name", roleString);
	role.setAttribute("id", roleString);
	role.setAttribute("class", "form-control");
	role.setAttribute("type", "text");
	role.setAttribute("size",40);
	role.setAttribute("pattern", "^[a-zA-Z\\s-]+$");
	role.setAttribute("title", "Alphabetic, '-' and space characters only");

	roleP.appendChild(roleLabel);
	workexChild.appendChild(roleP);
	workexChild.appendChild(role);

    workex.appendChild(workexChild);									//Append Child div to inner div

    workexChild = document.createElement("div");                       // Create child <div> node (to put label input pairs within)
    workexChild.setAttribute("class","form-group");

	var durationP = document.createElement("label");
	var durationLabel = document.createTextNode("Duration.");      // Create a text node
	var duration = document.createElement("input");
	var durationString = "duration"+it;
	durationP.setAttribute("for", durationString)
	duration.setAttribute("name", durationString);
	duration.setAttribute("id", durationString);
	duration.setAttribute("type","text");
	duration.setAttribute("class", "form-control");
	duration.setAttribute("size",40);
	duration.setAttribute("pattern", "^[0-9]+$");
	duration.setAttribute("title", "Numeric characters only");

	durationP.appendChild(durationLabel);
	workexChild.appendChild(durationP);
	workexChild.appendChild(duration);

    workex.appendChild(workexChild);									//Append Child div to inner div

    workexChild = document.createElement("div");                       // Create child <div> node (to put label input pairs within)
    workexChild.setAttribute("class","form-group");

	var employerP = document.createElement("label");
	var employerLabel = document.createTextNode("Employer.");      // Create a text node
	var employer = document.createElement("input");
	var employerString = "employer"+it;
	employerP.setAttribute("for", employerString);
	employer.setAttribute("name", employerString);
	employer.setAttribute("id", employerString);
	employer.setAttribute("type","text");
	employer.setAttribute("class", "form-control");
	employer.setAttribute("size",40);
	employer.setAttribute("pattern", "^[a-zA-Z0-9\\s-]+$");
	employer.setAttribute("title", "Alphanumeric, '-' and space characters only");

	employerP.appendChild(employerLabel);
	workexChild.appendChild(employerP);
	workexChild.appendChild(employer);

    workex.appendChild(workexChild);									//Append Child div to inner div

	document.getElementById("work-experience").appendChild(workex);


}



function addSkill(){
	var count = document.getElementById("skill-count");
	var it = count.getAttribute("value");
    if(it >= 8){
        alert("You have reached maximum number of skills");
        return;
    }
	count.setAttribute("value", ++it);

	var skill = document.createElement("div");                       // Create a <div> node
    skill.setAttribute("class","partition ");
    var divName = "skill" + it;
    skill.setAttribute("id",divName);

    var deleteButton = document.createElement("input");
    deleteButton.setAttribute("type", "button");
    var deleteSkillNum = "delete-skill"+it;
    deleteButton.setAttribute("id", deleteSkillNum);
    deleteButton.setAttribute("value", "Delete");
    var functionName = "deleteSkillHTML("+it+")";
    deleteButton.setAttribute("onclick", functionName);
    skill.appendChild(deleteButton);

	var prefP = document.createElement("p");
	var prefLabel = document.createTextNode("Preferred: ");
	var pref = document.createElement("input");
	pref.setAttribute("type", "radio");
	pref.setAttribute("name", "skill-preference");
	pref.setAttribute("value", it);
	prefP.appendChild(prefLabel);
	skill.appendChild(prefP);
	skill.appendChild(pref);

	var fieldP = document.createElement("p");
	var fieldLabel = document.createTextNode("Field:");      
	var field = document.createElement("select");
	var fieldString = "field";
	fieldString = fieldString.concat(it.toString(10));

	field.setAttribute("name", fieldString);
	field.setAttribute("onclick", "updateFields(this)")
	field.setAttribute("id", fieldString);

	fieldP.appendChild(fieldLabel);
	skill.appendChild(fieldP);
	skill.appendChild(field);

	var subFieldP = document.createElement("p");
	var subFieldLabel = document.createTextNode("Sub-Field:");
	var subField = document.createElement("select");
	var subFieldString = "sub-field";
	var subFieldOption = document.createElement("option");
	var subFieldOptionLabel = document.createTextNode("All subcategories");
	subFieldOption.setAttribute("value", "blank");
	subFieldString = subFieldString.concat(it.toString(10));
	subField.setAttribute("name", subFieldString);
	subField.setAttribute("id", subFieldString);
	//subField.setAttribute("disabled", true);

	subFieldP.appendChild(subFieldLabel);
	subFieldOption.appendChild(subFieldOptionLabel);
	subField.append(subFieldOption);
	skill.appendChild(subFieldP);
	skill.appendChild(subField);

	var contentsP = document.createElement("p");
	var contentsLabel = document.createTextNode("About:");      
	var contents = document.createElement("input");
	var contentsString = "contents";
	contentsString = contentsString.concat(it.toString(10));
	contents.setAttribute("name", contentsString);
	contents.setAttribute("type","text");
	contents.setAttribute("size",40);
	//contents.setAttribute("disable", true);

	contentsP.appendChild(contentsLabel);
	skill.appendChild(contentsP);
	skill.appendChild(contents);

	document.getElementById("skills").appendChild(skill);

	getFields(function () {
        document.getElementById(fieldString).innerHTML = this.responseText;
    })
    return false;

}



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


function updateTypes(button)
{
	if(button.value != "all") {
		var typeString = "type";
		var index = button.id;

		typeString = typeString.concat(index[5]);
		// subFieldString = subFieldString.concat(count.toString(10));

		getTypes(button, function () {
			document.getElementById(typeString).innerHTML = this.responseText;
		})
	}
    return false;
}

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