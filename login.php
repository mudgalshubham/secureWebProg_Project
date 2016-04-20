<?php

// Name: project/login.php
// Author: Shubham Mudgal
// Purpose: File to show login form
// Version: 1.0
// Date: 04/04/2016

session_start();
session_regenerate_id();

include_once('header.php');
if(!isset($_SESSION['authenticated']))							//Shows login form only if session is not authenticated(not already logged in)
{
	echo "<div align=center><table><tr><td><b>User Login</b></td></tr>
		<form action=add.php method=post>
		<tr><td>Email</td><td><input type=\"text\" name=\"postEmail\" 
		pattern=\"[A-Za-z0-9._-]+@colorado.edu\" title=\"Enter CU boulder email id only\" required/></td></tr>
		<tr><td>Password</td><td><input type=\"password\" name=\"postPass\" /></td></tr>
		<tr><td><input type=\"hidden\" name=\"s\" value=\"9\"/></td></tr>
		<tr><td><input type=\"submit\" name=\"submit\" value=\"submit\"/></td></tr>
		</form>
		</table>
		</div> ";
}
else 
	header("Location:/project/index.php");							// If user is already is already logged in then redirects to the add character page
		
?>