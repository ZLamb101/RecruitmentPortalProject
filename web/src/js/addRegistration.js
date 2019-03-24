
/**
 * Create a <div> node with input fields "year" and "name"
 * increment the names of the inputs for each invokation.
 * append new <div> to qualification in phtml
 */
function addQualification(){
	var count = document.getElementById("qualification-count");
	var temp = count.getAttribute("value");
	count.setAttribute("value", ++temp);

	var qual = document.createElement("div");                       // Create a <div> node
	var yearP = document.createElement("p");
	var yearLabel = document.createTextNode("Year.");      
	var year = document.createElement("input");
	var yearString = "year";
	yearString = yearString.concat(temp.toString(10));
	year.setAttribute("name",yearString);
	year.setAttribute("type","text");

	yearP.appendChild(yearLabel);
	qual.appendChild(yearP);
	qual.appendChild(year);



	var nameP = document.createElement("p");
	var nameLabel = document.createTextNode("Name");
	var name = document.createElement("input");
	var nameString = "name";
	nameString = nameString.concat(temp.toString(10));
	name.setAttribute("type","text");
	name.setAttribute("name", nameString);

	nameP.appendChild(nameLabel);
	qual.appendChild(nameP);
	qual.appendChild(name);


	document.getElementById("qualifications").appendChild(qual);           // Append <p> to <div> with id="myDIV"

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

	var roleP = document.createElement("p");
	var roleLabel = document.createTextNode("Role.");      // Create a text node
	var role = document.createElement("input");
	var roleString = "role";
	roleString = roleString.concat(it.toString(10));
	role.setAttribute("name", roleString);
	role.setAttribute("type", "text");

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

	employerP.appendChild(employerLabel);
	workex.appendChild(employerP);
	workex.appendChild(employer);

	document.getElementById("work-experience").appendChild(workex);

}