<?php

// Name: project/header.php
// Author: Shubham Mudgal
// Purpose: Adding header to the application
// Version: 1.0
// Date: 04/04/2016

session_start();
session_regenerate_id();

echo "<html><head></head><body>
		<div align=center><h1>e-Commerce for CU Boulder</h1></div><hr>";


if(isset($_SESSION['authenticated']) && $_SESSION['authenticated']=="yes")
{
	echo " Welcome ". $_SESSION['uname']."<br><p style=\"float: right;\">
			<a href=add.php?s=1>Add Item to Sell |</a>
			<a href=add.php?s=3>Update Profile |</a>
 			<a href=add.php?s=15>Logout</a></p> 
 			<p><a href=index.php>Home</a></p>
			<hr>
 			";
  			
  
}
else
{
	echo "<br><p style=\"float: right;\"><a href=login.php>Login </a>|
			<a href=signup.php?optionadd=91> Sign Up</a></p>
			<p><a href=index.php>Home</a></p> 
			<hr>
  			";
}

echo "</body></html>";

?>