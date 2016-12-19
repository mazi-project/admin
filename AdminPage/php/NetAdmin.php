<?php
/*
 * This file processes requests from the System admin config page
 * If the arguements are correct they are then passed to the shell scripts for
 * processing.
 * R. McLean
 */
namespace MaziAdmin
{
	header('Content-Type: text/html; charset=utf-8');
	
	//The status of a correct script output
	$STATUS_SUCESS = 0;
	
	//Used to redirect the save script to null output, so it doesn't block
	$NULLREDIRECT = " > out.txt &";
	///dev/null
	// Enable to redirect output to php script for debugging
	$REDIRECT = " 2>&1";
	//$HANGUP = "";
			
	// The folder the scripts are stored in
	$path = realpath("../scripts");
	
	$request = $_REQUEST['request'];
	
	// Wifi Settings
	if ($request == 'save')
	{
		// Empty variable for building the script to be run
		$scriptPath = ($path . "/wifiap.sh");
		$arguements = "";
		
		// Read all of the mandatory fields
		
		// Hostname must simply not be blank
		$hostname = $_REQUEST['hostname'];
		if ($hostname == "")
		{
			exit("Invalid Hostname");
		}
		
		// Channel must be a number between 1 and 13
		$channel = $_REQUEST['channel'];
		if ($channel == "" || is_nan($channel) || $channel < 1 || $channel > 13)
		{
			exit("Invalid Channel");
		}
		
		// Only turn on security if the correct input is present
		$security = $_REQUEST['security'];
		if ($security == "true")
		{
			// Security is enabled so read for passkey
			$passkey = $_REQUEST['passkey'];
			
			// Passkey must be entered, >8 chars and have no whitespace
			if ($passkey == "" || hasWhitespace($passkey) || strlen($passkey) < 8)
			{
				exit("Invalid Passkey");
			}
			
			// Create script with new passkey
			$arguements = (' -s ' . $hostname . ' -c ' . $channel . ' -p ' .
					 $passkey);
		} else
		{
			// No security, so turn it off
			$arguements = (' -s ' . $hostname . ' -c ' . $channel . ' -w OFF');
		}
		
		//This command must be run in Bg with nohup and redir so that the request doesn't timeout
		$script = ('sudo nohup sh ' . $scriptPath . $arguements . $NULLREDIRECT);
		
		/*
		 * This May have to be replaced with a non-returning exec, and script
		 * run in background
		 * Unless status can be retrieved before the network goes down..
		 */
		
		// Use the header for now to avoid false erors from network going down
		// Excecute the script, record status (and output)
		exec($script, $out, $status);
		if ($STATUS_SUCESS === $status)
		{
			header("HTTP/1.0 200");
			// Include some diagnostic within exit(x) if neccesary
			exit(true);
		} else
		{
			header('HTTP/1.1 500 Internal Server Error');
			outputError(
					"Error: \r\n Status " . $status . "\r\n Output: \r\n" .
							 var_dump($out));
			exit("Command failed with status: $status");
		}
		header("HTTP/1.0 200");
		exit(true);
	}	

	// Change Network Mode
	elseif ($request == 'netmode')
	{
		$netmode = $_REQUEST['netmode'];
		
		// Check its actually in the correct format
		if ($netmode == "offline" || $netmode == "dual" ||
				 $netmode == "restricted")
		{
			$scriptPath = ($path . '/internet.sh');
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
		} else
		{
			// Incorrect format, just exit
			header('HTTP/1.1 500 Internal Server Error');
			exit("Invalid Netmode Input");
		}
	}	

	// Return the current values of each setting
	elseif ($request == "current")
	{
		$scriptPath = ($path . '/current.sh');
		
		$arguements = (' -s -p -m ');
		
		$script = ('sudo sh ' . $scriptPath . $arguements . $REDIRECT);
		
		exec($script, $out, $status);
		
		if ($STATUS_SUCESS === $status)
		{
			header("HTTP/1.0 200");
			exit(Json_Encode($out));
		} else
		{
			header('HTTP/1.1 500 Internal Server Error');
			outputError(
					"Error: \r\n Status " . $status . "\r\n Output: \r\n" .
							 var_dump($out));
			exit("Command failed with status: $status");
		}
	} 	

	// Catch all for invalid requests
	else
	{
		header('HTTP/1.1 500 Internal Server Error');
		exit('Invalid Request');
	}

	function outputError ($message)
	{
		echo ($message);
		file_put_contents('./log/err.log', $message, FILE_APPEND);
	}

	/**
	 * Checks string for whitespace characters.
	 *
	 * @param string $text
	 *        	The string to test.
	 * @return bool TRUE if any character creates some sort of whitespace;
	 *         otherwise, FALSE.
	 */
	function hasWhitespace ($text)
	{
		for ($idx = 0; $idx < strlen($text); $idx += 1)
			if (ctype_space($text[$idx]))
				return TRUE;
		
		return FALSE;
	}
}
?>