/**
 * Links the web page to the PHP functions
 */

var path = "./php/admin.php?request=";

$(document).ready(function()
		{

	//Enable Passkey input when Security is enabled
	$('input[name=rdoSecurity]').change(
			function()
			{
				document.getElementById('txtPasskey').disabled  = !(this.value == "true");
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
					alert("Hostname must be entered");
					return;
				}
				
				//So well automated the user doesn't even have to input the 6
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
						alert("Invalid Passkey");
						return;
					}
				}

				//Call php function
				$.get(path+"save", {hostname: hostname, channel:channel, security:security, passkey:passkey}, function(data) 
						{
					//alert("Success: " + data);
					$("#btnSave").text("Settings Saved");
						})
						.fail(function(data){
							alert("Error");	
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
					//alert("Network Mode Changed to: " + data);
					$("#btnChangeNetmode").text("Mode Changed");
						})
						.fail(function(data){
							alert("Error");	
						});
			});
		});
