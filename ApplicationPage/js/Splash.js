/**
 * Links the web page to the PHP functions
 */

var path = "./php/NetAdmin.php?request=";

$(document).ready(function()
		{
	loadVals();

	//Colect inputs, send to Php page for generation
	$("#btnGenerate").click(
			function()
			{

			});
		});


//Gets the curr vals from PHP, loads them
function loadVals()
{
	//Call php function
	$.get(path+"current", function(data) 
			{
		var values = JSON.parse(data);

		//Update tracked setting, removing the names from the values
		var actualHostname = values[0].replace("ssid", "").trim();
		var actualNetmode = values[2].replace("mode", "").trim();

		//no check on this one, it probably isn't THAT shocking
		document.getElementById('txtHostname').value = actualHostname;

		if(actualNetmode != "")
		{
			if(actualNetmode == "dual")
			{
			}
			else if(actualNetmode == "restricted")
			{
			}
		}

		$("#divError").text("");
			})
			.fail(function(data){
				console.log(data);
			});
};