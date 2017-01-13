/**
 * Links the web page to the PHP functions
 */

var path = "./php/NetAdmin.php?request=";

var actualHostname =false;
var actualPasskey = false;
var actualSecurity = false;
var actualNetmode = false;

$(document).ready(function()
		{
	loadCurrentVals();

	//Enable Passkey input when Security is enabled
	$('input[name=rdoSecurity]').change(
			function()
			{
				document.getElementById('txtPasskey').disabled  = !(this.value == "true");
				if(this.value != actualSecurity)
				{
					$("#btnSave").text("Save Settings");
				}
			});

	/*
	 * For all of these controls-
	 * Load the currents on page load, and if they are changed away then back to current
	 * Reset the button to 'save settings'
	 * */
	//Return Netmode button to default if settings are changed
	$('input[name=rdoNetmode]').change(
			function()
			{
				if(this.value != actualNetmode)
				{
					$("#btnChangeNetmode").text("Set Network Mode");
				}
			});

	//Return Save Settings button to default if settings are changed
	$('#txtHostname').change(
			function()
			{
				if(this != actualHostname)
				{
					$("#btnSave").text("Save Settings");
				}
			});

	$('#txtPasskey').change(
			function()
			{
				if(this != actualPasskey)
				{
					$("#btnSave").text("Save Settings");
				}
			});

	//Gather all Wifi details, check them and send to server
	//This functions returns out on an invalid value rather than having nested ifs
	$("#btnSave").click(
			function()
			{
				var hostname = $("#txtHostname").val();
				//Basic check for now - Will likely add a whitelist Regex
				if(hostname == "")
				{
					$("#divError").text("Hostname must be entered");
					return;
				}

				//So well automated the user doesn't even have to input the 6
				//This will instead be implemented in advanced settings
				var channel = 6;
				/*
				var channel = $("#txtChannel").val();
				//Could add region limitations
				if(channel == "" || channel < 0 || channel > 13 || isNaN(channel))
				{
					alert("Invalid Channel");
					return;
				}
				 */

				//Find out if user has enabled security or not
				var security = (document.querySelector('input[name="rdoSecurity"]:checked').value == "true");

				var passkey ="";
				if(security)
				{
					//Basic check for now - Will likely add a whitelist Regex
					passkey = $("#txtPasskey").val();
					if(passkey == "" || passkey.length <8 || passkey.length >= 32)
					{
						$("#divError").text("Invalid Passkey");
						return;
					}
				}

				//Call php function
				$.get(path+"save", {hostname: hostname, channel:channel, security:security, passkey:passkey}, function(data) 
						{
					$("#btnSave").text("Settings Saved");
					$("#divError").text("");

					//Track Setting Changes
					actualHostname = hostname;
					actualPasskey = passkey;
					actualSecurity = security;
						})
						.fail(function(data){
							$("#divError").text("Error");
							//alert("Error");	
						});
			});

	//Netmode Change
	$("#btnChangeNetmode").click(
			function()
			{
				//Dont bother checking this input because it can only be one of three anyway
				//and is checked on server side for any big failure
				var netmode = document.querySelector('input[name="rdoNetmode"]:checked').value;

				//Call php function
				$.get(path+"netmode", {netmode: netmode}, function(data) 
						{
					$("#btnChangeNetmode").text("Mode Saved");
					$("#divError").text("");

					//Update tracked setting
					actualNetmode = netmode;
						})
						.fail(function(data){
							$("#divError").text("Network mode could not be changed");
							//alert("Error");	
						});
			});
		});

//Gets the curr vals from PHP, loads them
function loadCurrentVals()
{
	//Call php function
	$.get(path+"current", function(data) 
			{
		var values = JSON.parse(data);

		//Update tracked setting, removing the names from the values
		actualHostname = values[1].replace("ssid", "").trim();
		actualPasskey = values[2].replace("password", "").trim();
		actualNetmode = values[3].replace("mode", "").trim();

		//no check on this one, it probably isn't THAT shocking
		document.getElementById('txtHostname').value = actualHostname;

		//If the passkey and netmode are not available, only the name is returned
		if(actualPasskey != "")
		{

			document.getElementById('rdoSecOn').checked = true;
			document.getElementById('txtPasskey').value = actualPasskey;
		}
		else
		{
			document.getElementById('rdoSecOff').checked = true;
			document.getElementById('txtPasskey').disabled  = true;
		}

		if(actualNetmode != "")
		{
			if(actualNetmode == "offline")
			{
				$('#rdoNetOffline').checked = true;
			}
			else if(actualNetmode == "dual")
			{
				$('#rdoNetDual').checked = true;
			}
			else if(actualNetmode == "restricted")
			{
				$('#rdoNetRestricted').checked = true;
			}
		}

		$("#divError").text("");
			})
			.fail(function(data){
				$("#divError").text("Current Settings could not be loaded. Error Has been logged.");
				console.log(data);
				//alert("Error");	
			});
}
