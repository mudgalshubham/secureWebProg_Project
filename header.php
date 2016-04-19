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
	echo "<div> Welcome ". $_SESSION['uname']." <p><a href=index.php>Home</a></p>
	<p style=\"float: right;\"><a href=add.php?s=1>Add Item to Sell |</a>
			<a href=add.php?s=3>Update Profile |</a>
 			<a href=add.php?s=15>Logout</a></p></div><br><hr>
 			";
  			
  
}
else
{
	echo "<div align=right><a href=login.php>Login |</a>
			<a href=signup.php?optionadd=91>Sign Up|</a></div><hr>
  			";
}

echo "</body></html>";

?>