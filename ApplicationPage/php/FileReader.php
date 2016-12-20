<?php
/*
 * This file controls the loading and saving of external files
 */
namespace MaziApplication
{
	
	// This is made global so that different functions in this file can access the list of
	// applications
	// The list is used both on page load and on generation, so it is just saved
	// here to avoid multiple loads.
	// Format: Name, Location
	$applications = array();

	/*
	 * This reads the full list of available applications
	 * from applications.csv, and stores them in the global variable
	 */
	function loadApplications ()
	{
		global $applications;
		
		//Run if the first application record is empty
		if($applications[0][0] == "")
		try
		{
			$file = fopen("../db/applications.csv", "r");
			
			while (! feof($file))
			{
				$csv = (fgetcsv($file));
				if (! $csv == null)
				{
					$app = array(
							$csv[0],
							$csv[1]
					);
					$applications[] = $app;
				}
			}
			return true;
		} catch (Exception $e)
		{
			return false;
		}
	}

	/* Returns a list of the names of applications */
	function getListOfApplications ()
	{
		global $applications;
		
		$output = array();
		foreach ($applications as $application)
		{
			$output[] = $application[0];
		}
		
		return $output;
	}

	/*
	 * Returns the name and location of a simgle application by id
	 */
	function getApplicationById ($id)
	{
		global $applications;
		
		return $applications[$id];
	}

	//Log an error to text file
	function outputError ($message)
	{
		echo ($message);
		file_put_contents('./log/err.log', $message, FILE_APPEND);
	}
}
?>