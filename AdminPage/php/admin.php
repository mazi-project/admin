<?php
/*
 * This file processes requests from the System admin config page
 * If the arguements are correct they are then passed to the shell scripts for processing.
 * R. McLean
 * */
namespace MaziAdmin
{
	header('Content-Type: text/html; charset=utf-8');
	
	$STATUS_SUCESS = 0;
	
	$request = $_REQUEST['request'];
	
	// Wifi Settings
	if ($request == 'save')
	{
		//Empty variable for building the script to be run
		$path = realpath("../scripts/wifiap.sh");
		$arguements = "";
		
		//Read all of the mandatory fields
		$hostname = $_REQUEST['hostname'];
		if($hostname == "")
		{
			exit("Invalid Hostname");
		}
		
		$channel = $_REQUEST['channel'];
		if($channel == "")
		{
			exit("Invalid Channel");
		}
		
		$security = $_REQUEST['security'];
		if ($security == "true")
		{
			//Security is enabled so read for passkey
			$passkey = $_REQUEST['passkey'];
			
			if($passkey == "")
			{
				exit("Invalid Passkey");
			}
			
			//Create script with new passkey
			$arguements = (' -s ' . $hostname .
					' -c ' . $channel .
					' -p ' . $passkey);
		}
		else 
		{
			//No security, so turn it off
			$arguements = (' -s ' . $hostname .
					' -c ' . $channel .
					' -w OFF');
		}
		
		$script = ('sudo sh ' . $path . $arguements . " 2>&1");
		
		//Excecute the script, record status (and output)
		exec($script , $out, $status);
		if ($STATUS_SUCESS === $status)
		{
			header("HTTP/1.0 200");
			//Include some diagnostic within exit(x) if neccesary
			exit(true);
		} else
		{
			header('HTTP/1.1 500 Internal Server Error');
			exit ("Command failed with status: $status");
		}
	} 
	
	// Change Network Mode
	elseif ($request == 'netmode')
	{
		$netmode = $_REQUEST['netmode'];
		
		//Check its actually in the correct format
		if ($netmode == "offline" || $netmode == "dual" || $netmode == "restricted")
		{
			$path = realpath('../scripts/internet.sh'); 
			$arguements = (' -m ' . $netmode);
			$script = ('sudo sh ' . $path . $arguements);
			
			exec($script, $out, $status);

			if ($STATUS_SUCESS === $status)
			{
				header("HTTP/1.0 200");
				exit($netmode);
			} else
			{
				header('HTTP/1.1 500 Internal Server Error');
				exit ("Command failed with status: $status");
			}
		}
		else 
		{
			//Incorrect format, just exit
			header('HTTP/1.1 500 Internal Server Error');
			exit ("Invalid Netmode Input");
		}
	}

	//Catch all for invalid requests
	else
	{
		header('HTTP/1.1 500 Internal Server Error');
		exit('Invalid Request');
	}
}
?>