/**
 * Links the web page to the PHP functions
 */

var path = "./php/AppAdmin.php?request=";

$(document).ready(function()
		{
	loadApplications();

	//Colect inputs, send to Php page for generation
	$("#btnGenerate").click(
			function()
			{
				var ids = getInput();

				//Call php function
				$.post(path + "generate", {'ids': ids}, function(data) 
						{
					$("#btnGenerate").text("Splash Page Generated");
						})
						.fail(function(data){
							alert("Error");
							console.log(data);
						});
			});
		});

/*
 * This function collects the id's of all selected applications
 * and returns them as a basic array (easiest format for the php)
 * */
function getInput()
{
	var inputs = [];

	var options = document.getElementsByName("appChk");
	$.each(options, function(index, checkbox)
			{
		if(checkbox.checked)
		{
			inputs.push(parseInt(checkbox.value));
		}
			})
			
			return inputs;
}

/*
 * This function runs on page load
 * It retrieves a list of available applications from the server
 * and displays them on the page
 * */
function loadApplications()
{
	//This is the table the apps are shown in
	var appTbl = document.getElementById('tblApplications');
	var headRow = appTbl.insertRow();
	headRow.insertCell();
	var headCell = headRow.insertCell();
	headCell.innerHTML="Select to include";
	
	//Call php function
	$.get(path + "applications", function(data) 
			{
		var apps = JSON.parse(data);
		$.each(apps, function(index, value)
				{
			var row = appTbl.insertRow();
			var cellDesc = row.insertCell();
			var cellChk = row.insertCell();
			
			var description = document.createTextNode(value);
			var checkbox = document.createElement("input");

			checkbox.type = "checkbox";    // make the element a checkbox
			checkbox.name = "appChk";      // give it a name we can check on the server side
			checkbox.value = index;         // make its value "pair"

			cellChk.appendChild(checkbox); // add the box to the element
			cellDesc.appendChild(description);// add the description to the element
				}
		);
			})
			.fail(function(data){
				alert("Error");
				console.log(data);
			});
}