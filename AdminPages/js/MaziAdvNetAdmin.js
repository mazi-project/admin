/**
 * 
 */

var path = "./php/NetAdmin.php?request=";

var actualChannel = false;

$(document).ready(function()
		{
	loadCurrentVals();

	$('#txtChannel').change(
			function()
			{
				if(this != actualChannel)
				{
					$("#btnSave").text("Save Settings");
				}
			});

	//Save the new channel
	$("#btnSave").click(
			function()
			{
				//Dont bother checking this input because it can only be one of three anyway
				//and is checked on server side for any big failure
				var channel = $("#txtChannel").val();

				//Call php function
				$.get(path+"save", {channel: channel}, function(data) 
						{
					$("#btnSave").text("Settings Saved");
					$("#divError").text("");

					//Update tracked setting
					actualChannel = channel;
						})
						.fail(function(data){
							$("#divError").text("Settings could not be saved");
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
		actualChannel = values[0].replace("channel", "").trim();

		document.getElementById('txtChannel').value = actualChannel;

		$("#divError").text("");
			})
			.fail(function(data){
				$("#divError").text("Current could not be loaded");
				console.log(data);
			});
}