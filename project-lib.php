<?php

// Name: project/project-lib.php
// Author: Shubham Mudgal
// Purpose: Library file for connection with the database
// Version: 1.0
// Date: 04/04/2016

# This is the library file.
function connect(&$db){
	$mycnf = "/etc/project-mysql.conf";
	if(!file_exists($mycnf)){
		echo "ERROR: DB Config file not found: $mycnf";
		exit;
	}
	
	$mysql_ini_array = parse_ini_file($mycnf);
	$db_host = $mysql_ini_array["host"];
	$db_user = $mysql_ini_array["user"];
	$db_pass = $mysql_ini_array["pass"];
	$db_port = $mysql_ini_array["port"];
	$db_name = $mysql_ini_array["dbName"];
	$db = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);
	
	if(!$db) {
		print "Error connecting DB: " . mysqli_connect_error();
		exit;
	}
}

function whiteList(){
	$ipAddressArray = array();
	array_push($ipAddressArray,'198.18.2.70');
	return $ipAddressArray;
}

function logout()
{
	session_destroy();
    header("Location:/project/login.php");
}

function isAdmin()
{
	if ( isset($_SESSION['userid']) && $_SESSION['userid'] == 6)
	{
		return true;
	}
	else
		return false;
}

function checkAuth()
{
	if(isset($_SESSION['HTTP_USER_AGENT']))
	{
		if($_SESSION['HTTP_USER_AGENT']!=md5($_SERVER['SERVER_ADDR'] . $_SERVER['HTTP_USER_AGENT']))
			logout();	
	} 
	else
		logout();

	if(isset($_SESSION['ip']))
	{
		if($_SESSION['ip']!=$_SERVER['REMOTE_ADDR'])
			logout();
	}
	else
		logout();

	if(isset($_SESSION['created']))
	{
		if((time()- $_SESSION['created']) > 1800)
			logout();	
	}
	else
		logout();

	if("POST" == $_SERVER['REQUEST_METHOD'])
	{
		if(isset($_SERVER['HTTP_ORIGIN']))
		{
				if($_SERVER['HTTP_ORIGIN'] != "https://100.66.1.23")
						logout();			
		}
		else
			logout();
	}
}

?>
