function addQualification{
	
	var qual = document.createElement("div");                       // Create a <div> node
	var yearP = document.createElement("p");
	var yearLabel = document.createTextNode("Year.");      // Create a text node
	var year = document.createElement("input");
	year.setAttribute("name","year");
	year.setAttribute("type","text");

	yearP.appendChild(yearLabel);
	qual.appendChild(yearP);
	qual.appendChild(year);



	var nameP = document.createElement("p");
	var nameLabel = document.createTextNode("Name");
	var name = document.createElement("input");
	name.setAttribute("name","name");
	name.setAttribute("type","text");

	nameP.appendChild(nameLabel);
	qual.appendChild(nameP);
	qual.appendChild(name);

	                                          // Append the text to <p>
	document.getElementById("qualifications").appendChild(qual);           // Append <p> to <div> with id="myDIV"

}



function addWorkExperience{

	var workex = document.createElement("div");                       // Create a <div> node

	var roleP = document.createElement("p");
	var roleLabel = document.createTextNode("Role.");      // Create a text node
	var role = document.createElement("input");
	role.setAttribute("name","role");
	role.setAttribute("type","text");

	roleP.appendChild(roleLabel);
	qual.appendChild(roleP);
	qual.appendChild(role);

	var durationP = document.createElement("p");
	var durationLabel = document.createTextNode("Duration.");      // Create a text node
	var duration = document.createElement("input");
	duration.setAttribute("name","duration");
	duration.setAttribute("type","text");

	durationP.appendChild(durationLabel);
	qual.appendChild(durationP);
	qual.appendChild(duration);

	var employerP = document.createElement("p");
	var employerLabel = document.createTextNode("Employer.");      // Create a text node
	var employer = document.createElement("input");
	employer.setAttribute("name","employer");
	employer.setAttribute("type","text");

	employerP.appendChild(employerLabel);
	qual.appendChild(employerP);
	qual.appendChild(employer);

	document.getElementById("work-experience")

}