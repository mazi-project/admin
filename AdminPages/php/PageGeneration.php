<?php
/*
 * This file contains the function to generate the splash page
 * with the selected applications
 *
 * The Splash page format is:
 * header.html
 * Generated Content
 * footer.html
 */
namespace MaziAdmin\applications
{
	include_once 'FileReader.php';
	
	// ids are a sub list of id's of applications stored in applications.csv
	// Each application who's id is present will be included
	function GeneratePage ($ids)
	{
		try
		{
			$splash = "";
			
			$splash = file_get_contents("../templates/header.html");
			
			$body = '';
			
			foreach ($ids as $id)
			{
				$app = getApplicationById($id);
				
				// Generated Application List
				$body = $body . "\r\n" . getApplicationLink($app[0], $app[1]);
			}
			
			$splash = ($splash . $body);
			
			$splash = ($splash . file_get_contents("../templates/footer.html"));
			
			//Only suceed if the file is actually written
			if (file_put_contents('../splash.html', $splash))
			{
				return true;
			} 

			else
			{
				return false;
			}
			
		} catch (Exception $e)
		{
			return false;
		}
	}

	/*
	 * Takes the name and accesible location of an application and packages
	 * it into the format used on the page
	 */
	function getApplicationLink ($name, $loc)
	{
		$link = '<a href="' . $loc . '" class="btn btn-default appBtn"> ' . $name .
				 '</a> <br>';
		
		return $link;
	}
}
?>