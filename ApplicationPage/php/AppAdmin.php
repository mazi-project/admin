<?php
/*
 * This file forwards requests and input for the application admin page
 *
 */
namespace MaziAdmin\applications
{

	include_once './PageGeneration.php';
	include_once './FileReader.php';
	include_once './ScriptBridge.php';
	
	// This if statement ensures that the applications
	// are actually available and loads them from the .csv.
	if (loadApplications())
	{
		header('Content-Type: text/html; charset=utf-8');
		
		$request = $_REQUEST["request"];
		
		// Request to generate the splash page
		if ($request == "generate")
		{
			// Extract the array of ids from post request
			$ids = $_POST["ids"];
			
			// Generate page will return false for any errors
			if (GeneratePage($ids))
			{
				header("HTTP/1.0 200");
				// Include some diagnostic within exit(x) if neccesary
				exit();
			}
			else
			{
				header('HTTP/1.1 500 Internal Server Error');
				exit("Page Generation Failed");
			}
		}
		
		// Return the current values of each setting
		else if ($request == "current")
		{
			// Get only the hostname
			exit(\MaziAdmin\settings\getCurrentSettings(true, false, true, false, false));
		}
		
		// Request for a list of available applications
		else if ($request == "applications")
			{
				header("HTTP/1.0 200");
				// Returned to the js function as JSON
				exit(json_encode(getListOfApplications()));
			}
			
			// Catch all for invalid requests
			else
			{
				header('HTTP/1.1 500 Internal Server Error');
				exit('Invalid Request');
			}
	}
	// This will run if applications cannot be loaded - check the .csv
	else
	{
		header('HTTP/1.1 500 Internal Server Error');
		exit('Application list failed to load');
	}
}
?>