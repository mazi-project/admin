<?php
/*
 * This file processes requests from the System admin config page
 * If the inputs for the request are valid, they are passed to the script Bridge
 * for excecution
 * If not, the file will output a server error and exit
 * R. McLean
 */
namespace MaziAdmin\settings
{
	header('Content-Type: text/html; charset=utf-8');
	
	include_once './ScriptBridge.php';
	
	$request = $_REQUEST['request'];
	
	// Wifi Settings
	if ($request == 'save')
	{
		//If hostname is blank, dont include it for the script
		$hostname = $_REQUEST['hostname'];
		if (trim($hostname) == "")
		{
			$hostname = null;
		}
		
		// Channel must be a number between 1 and 13
		$channel = $_REQUEST['channel'];
		if (is_nan($channel) || $channel < 1 || $channel > 13)
		{
			exit("Invalid Channel");
		} else //Channel not included, so dont sent it to the script
			if (trim($channel) == "")
			{
				$channel = null;
			}
		
		// Only turn on security if the correct input is present
		$security = $_REQUEST['security'];
		$passkey;
		if ($security == "true")
		{
			// Security is enabled so read for passkey
			$passkey = $_REQUEST['passkey'];
			
			// Passkey must be entered, >8 chars and have no whitespace
			if ($passkey == "" || hasWhitespace($passkey) || strlen($passkey) < 8)
			{
				exit("Invalid Passkey");
			}
		} else
		{
			$passkey = null;
		}		
		// All checks passed, send inputs to script
		exit(saveSettings($hostname, $channel, $passkey));
	}	

	// Change Network Mode
	elseif ($request == 'netmode')
	{
		$netmode = $_REQUEST['netmode'];
		
		// Check it's actually in the correct format
		if ($netmode == "offline" || $netmode == "dual" || $netmode == "online")
		{
			
			// DEBUG LINE -
			// The meaning and settings of these three settings
			// May change - hence online is changed to the original name it has
			// in the script
			if ($netmode == "online")
			{
				$netmode = "restricted";
			}
			
			exit(changeNetmode($netmode));
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
		// No inputs needed
		exit(getCurrentSettings(true, true, true, true, false));
	} 	

	// Catch all for invalid requests
	else
	{
		header('HTTP/1.1 500 Internal Server Error');
		exit('Invalid Request');
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