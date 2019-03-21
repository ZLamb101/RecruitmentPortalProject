function addQualification{
	
	var qual = document.createElement("div");                       // Create a <div> node
	var t = document.createElement("p");
	var yearLabel = document.createTextNode("Year.");      // Create a text node
	var year = document.createElement("input");
	year.setAttribute("name","year");
	year.setAttribute("type","text");

	t.appendChild(yearLabel);
	qual.appendChild(t);
	qual.appendChild(year);



	var workex = document.createElement("div");
	var y = document.createElement("p");
	var nameLabel = document.createTextNode("Name");
	var name = document.createElement("input");
	name.setAttribute("name","name");
	name.setAttribute("type","text");

	y.appendChild(nameLabel);
	workex.appendChild(y);
	workex.appendChild(name);

	                                          // Append the text to <p>
	document.getElementById("qualifications").appendChild(qual);           // Append <p> to <div> with id="myDIV"
	document.getElementById("work-experience").appendChild(workex);
}