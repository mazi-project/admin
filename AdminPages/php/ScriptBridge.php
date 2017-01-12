<?php
/*
 * This file is where inputs are converted to correct script arguements, and
 * scripts are run
 * */
namespace MaziAdmin\settings
{
	// The status of a correct script output
	$STATUS_SUCESS = 0;
	
	// Used to redirect the save script to null output or out.txt, so it doesn't block
	$NULLREDIRECT = " > ./log/out.txt &";
	// $NULLREDIRECT = " > /dev/null &";
	//$NULLREDIRECT = " 2>&1";
	
	// Enable to redirect output to php script for debugging
	$REDIRECT = " 2>&1";
	// $REDIRECT = "";
	
	// Get proper path to The folder the scripts are stored in and add a trailing '/'
	$path = (realpath("../scripts") . '/');
	//$path = ("");
	
	/*
	 * Save for the common settings
	 * */
	function saveSettings($hostname, $channel, $passkey)
	{
		global $STATUS_SUCESS;
		global $path;
		global $NULLREDIRECT;
		
		//Variable for building arguements in
		$arguements = "";
		
		//Add hostname if present
		if($hostname != null)
		{
			// Create script with new passkey
			$arguements = $arguements . (' -s ' . $hostname);
		}
		
		//add channel if present
		if($channel != null)
		{
			// Create script with new passkey
			$arguements = $arguements . (' -c ' . $channel);
		}
		
		//If a passkey is present, add it. If not, send the security off parameter
		if($passkey != null)
		{
			// Create script with new passkey
			$arguements = $arguements . (' -p ' . $passkey);
		}
		else 
		{
			// No security, so turn it off
			$arguements = $arguements . (' -w OFF');
		}
		
		$scriptPath = ($path . "wifiap.sh");
		
		/* This command must be run in Background with nohup and 
		 * null or textfile redirection so that the request from the admin page
		 * doesnt timeout as the network is restarted
		*/
		$script = ('sudo nohup sh ' . $scriptPath . $arguements . $NULLREDIRECT);
				
		echo var_dump ($script);
		// Excecute the script, record status (and output)
		exec($script, $out, $status);
		if ($STATUS_SUCESS === $status)
		{
			header("HTTP/1.0 200");
			// Include some diagnostic within exit(x) if neccesary
			exit(true);
		}
		else
		{
			header('HTTP/1.1 500 Internal Server Error');
			outputError("Error: \r\n Status " . $status . "\r\n Output: \r\n" . var_dump($out));
			exit("Command failed with status: $status");
		}
	}
	
	/*
	 * This function takes an arguement of one of the three netmodes
	 * and runs it in the script - it assumes the arguement has already been checked*/
	function changeNetmode($netmode)
	{
		global $STATUS_SUCESS;
		global $path;
		global $REDIRECT;
		
		$scriptPath = ($path . 'internet.sh');
		$arguements = (' -m ' . $netmode);
		$script = ('sudo sh ' . $scriptPath . $arguements . $REDIRECT);
			
		exec($script, $out, $status);
			
		if ($STATUS_SUCESS === $status)
		{
			header("HTTP/1.0 200");
			exit($netmode);
		} else
		{
			header('HTTP/1.1 500 Internal Server Error');
			outputError(
				"Error: \r\n Status " . $status . "\r\n Output: \r\n" .
				var_dump($out));
			exit("Command failed with status: $status");
		}
	}
	
	/*
	 * This function runs current.sh with the arguements for the common settings
	 * and returns them
	 * Inputs - flag for each requested setting
	 * */
	function getCurrentSettings($host, $pass, $netmode, $channel, $interface)
	{
		global $STATUS_SUCESS;
		global $path;
		global $REDIRECT;
		
		$scriptPath = ($path . 'current.sh');
		
		//Build requested arguement list based on inputs
		$arguements = "";
		if($host)
		{
			$arguements = ($arguements . " -s");
		}
		
		if($pass)
		{
			$arguements = ($arguements . " -p");
		}
		
		if($netmode)
		{
			$arguements = ($arguements . " -m");
		}
		
		if($channel)
		{
			$arguements = ($arguements . " -c");
		}
		
		if($interface)
		{
			$arguements = ($arguements . " -i");
		}
		
		$script = ('sudo sh ' . $scriptPath . $arguements . $REDIRECT);
		
		exec($script, $out, $status);
		
		if ($STATUS_SUCESS === $status)
		{
			header("HTTP/1.0 200");
			exit(Json_Encode($out));
		}
		else
		{
			header('HTTP/1.1 500 Internal Server Error');
			outputError("Error: \r\n Status " . $status . "\r\n Output: \r\n" . var_dump($out));
			exit("Command failed with status: $status");
		}
	}
	
	// Output errors to the log
	function outputError ($message)
	{
		echo ($message);
		file_put_contents('./log/err.log', $message, FILE_APPEND);
	}
}


?>