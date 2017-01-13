/**
 * Links the web page to the PHP functions
 */

var path = "./php/AppAdmin.php?request=";

$(document).ready(function()
		{
	loadVals();
		});


//Gets the curr vals from PHP, loads them
function loadVals()
{
	//Call php function
	$.get(path+"current", function(data) 
			{
		var values = JSON.parse(data);

		if(values[0] != null)
		{
			//Update tracked setting, removing the names from the values
			var actualHostname = values[0].replace("ssid", "").trim();

			//no check on this one, it probably isn't THAT shocking
			document.getElementById('txtHostname').innerHTML = actualHostname;
		}


		if (values[1] != null)
		{
			var actualNetmode = values[1].replace("mode", "").trim();

			if(actualNetmode != "")
			{
				if(actualNetmode == "dual")
				{
					//load everything normaly
				}
				else if(actualNetmode == "online")
				{
					//Show only internet button
				}
				else if(actualNetmode == "offline")
				{
					//Show only app buttons
				}
			}
		}

		$("#divError").text("");
			})
			.fail(function(data){
				$("#divError").text("Some items could not be loaded, Please try again later ");
				console.log(data);
			});
};