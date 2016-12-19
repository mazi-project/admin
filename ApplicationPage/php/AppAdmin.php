<?php
/*
 * This file forwards requests and input for the application admin page
 *
 */
namespace MaziApplication
{

	include_once 'PageGeneration.php';
	include_once 'FileReader.php';
	
	//This if statement ensures that the applications
	//are actually available.
	if (loadApplications())
	{
		
		header('Content-Type: text/html; charset=utf-8');
		$request = $_REQUEST["request"];
		
		//Request to generate the splash page
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
		
		else 
			//Rewuewt for a list of available applications
			if ($request == "applications")
			{
				header("HTTP/1.0 200");
				//Returned to the js function as JSON
				exit(json_encode(getListOfApplications()));
			}
			
			// Catch all for invalid requests
			else
			{
				header('HTTP/1.1 500 Internal Server Error');
				exit('Invalid Request');
			}
	}
	//If this fails, check the .applications.csv
	else 
	{
		header('HTTP/1.1 500 Internal Server Error');
		exit('Application list failed to load');
	}
}
?>