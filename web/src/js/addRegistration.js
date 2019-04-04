
/**
 * Create a <div> node with input fields "year" and "name"
 * increment the names of the inputs for each invokation.
 * append new <div> to qualification in phtml
 */
function addQualification(){
	var count = document.getElementById("qualification-count");
	var it = count.getAttribute("value");
	count.setAttribute("value", ++it);




	var qual = document.createElement("div");                       // Create a <div> node

	var prefP = document.createElement("p");
	var prefLabel = document.createTextNode("Preferred: ");
	var pref = document.createElement("input");
	pref.setAttribute("type", "radio");
	pref.setAttribute("name", "qualification-preference");
	pref.setAttribute("value", temp);
	prefP.appendChild(prefLabel);
	qual.appendChild(prefP);
	qual.appendChild(pref);

	var yearP = document.createElement("p");
	var yearLabel = document.createTextNode("Year.");      
	var year = document.createElement("input");
	var yearString = "year";
	yearString = yearString.concat(temp.toString(10));
	year.setAttribute("name",yearString);
	year.setAttribute("type","text");
	year.setAttribute("size",40);
	year.setAttribute("pattern", "^[0-9]{4}$");
	year.setAttribute("title", "YYYY format. Numeric characters only");

	yearP.appendChild(yearLabel);
	qual.appendChild(yearP);
	qual.appendChild(year);




	var level = document.createElement("select");
	var levelString = "level";
	levelString = levelString.concat(it.toString(10));

	qual.appendChild(level);
	level.setAttribute("name", levelString);
	level.setAttribute("id", levelString);


	var type = document.createElement("select");
	var typeString = "type";
	typeString = typeString.concat(it.toString(10));

	type.setAttribute("name", typeString);
	type.setAttribute("id", typeString);
	qual.appendChild(type);


	var yearP = document.createElement("p");
	var yearLabel = document.createTextNode("Year.");
	var year = document.createElement("input");
	var yearString = "year";
	yearString = yearString.concat(it.toString(10));
	year.setAttribute("name",yearString);
	year.setAttribute("type","text");
	year.setAttribute("size",40);
	year.setAttribute("pattern", "^[0-9]{4}$");
	year.setAttribute("title", "YYYY format. Numeric characters only");

	yearP.appendChild(yearLabel);
	qual.appendChild(yearP);
	qual.appendChild(year);




	document.getElementById("qualifications").appendChild(qual);           // Append <p> to <div> with id="myDIV"

	getLevels(function () {
		document.getElementById(levelString).innerHTML = this.responseText;
	})

	getTypes(function () {
		document.getElementById(typeString).innerHTML = this.responseText;
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
	count.setAttribute("value", ++it);

	var workex = document.createElement("div");                       // Create a <div> node

	var prefP = document.createElement("p");
	var prefLabel = document.createTextNode("Preferred: ");
	var pref = document.createElement("input");
	pref.setAttribute("type", "radio");
	pref.setAttribute("name", "work-experience-preference");
	pref.setAttribute("value", it);
	prefP.appendChild(prefLabel);
	workex.appendChild(prefP);
	workex.appendChild(pref);

	var roleP = document.createElement("p");
	var roleLabel = document.createTextNode("Role.");      // Create a text node
	var role = document.createElement("input");
	var roleString = "role";
	roleString = roleString.concat(it.toString(10));
	role.setAttribute("name", roleString);
	role.setAttribute("type", "text");
	role.setAttribute("size",40);
	role.setAttribute("pattern", "^[a-zA-Z\\s-]+$");
	role.setAttribute("title", "Alphabetic, '-' and space characters only");

	roleP.appendChild(roleLabel);
	workex.appendChild(roleP);
	workex.appendChild(role);

	var durationP = document.createElement("p");
	var durationLabel = document.createTextNode("Duration.");      // Create a text node
	var duration = document.createElement("input");
	var durationString = "duration";
	durationString = durationString.concat(it.toString(10));
	duration.setAttribute("name", durationString);
	duration.setAttribute("type","text");
	duration.setAttribute("size",40);
	duration.setAttribute("pattern", "^[0-9]+$");
	duration.setAttribute("title", "Numeric characters only");

	durationP.appendChild(durationLabel);
	workex.appendChild(durationP);
	workex.appendChild(duration);

	var employerP = document.createElement("p");
	var employerLabel = document.createTextNode("Employer.");      // Create a text node
	var employer = document.createElement("input");
	var employerString = "employer";
	employerString = employerString.concat(it.toString(10));
	employer.setAttribute("name", employerString);
	employer.setAttribute("type","text");
	employer.setAttribute("size",40);
	employer.setAttribute("pattern", "^[a-zA-Z0-9\\s-]+$");
	employer.setAttribute("title", "Alphanumeric, '-' and space characters only");

	employerP.appendChild(employerLabel);
	workex.appendChild(employerP);
	workex.appendChild(employer);

	document.getElementById("work-experience").appendChild(workex);


}



function addSkill(){
	var count = document.getElementById("skill-count");
	var it = count.getAttribute("value");
	count.setAttribute("value", ++it);

	var skill = document.createElement("div");                       // Create a <div> node

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
	var subFieldOptionLabel = document.createTextNode("all subcategories");
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
	contents.setAttribute("size",100);
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

function getTypes(callback) {
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET", "populateTypes.php?q=" , true);
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