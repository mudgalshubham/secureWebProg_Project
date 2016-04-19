<?php

// Name: project/signup.php
// Author: Shubham Mudgal
// Purpose: Form for new users to sign up to the portal
// Version: 1.0
// Date: 04/04/2016

session_start();
session_regenerate_id();

include_once('header.php');
include_once('/var/www/html/project/project-lib.php');

isset($_REQUEST['optionadd'])?$optionadd=strip_tags($_REQUEST['optionadd']):$optionadd="";
isset($_REQUEST['uname'])?$uname=strip_tags($_REQUEST['uname']):$uname="";
isset($_REQUEST['email'])?$email=strip_tags($_REQUEST['email']):$email="";
isset($_REQUEST['newpass'])?$newpass=strip_tags($_REQUEST['newpass']):$newpass="";
isset($_REQUEST['phone'])?$phone=strip_tags($_REQUEST['phone']):$phone="";


if(!isset($_SESSION['authenticated']))				//Shows signup form only if session is not authenticated(not already logged in)
{
	if(is_numeric($optionadd))
	{
		switch($optionadd){
			case 91: addUsersForm();break;
		
			case 92: addUser(); break;
		}
	}
	else 
		echo "Invalid Data!";
}
else
	header("Location:/project/index.php");	
	

function addUsersForm()
{
	echo "<div align=center><table><tr><td>Add New User</td></tr>
		<form action=signup.php method=post>
		<tr><td>Name</td><td><input type=\"text\" name=\"uname\" required/></td></tr>
		<tr><td>CU Boulder Email ID</td><td><input type=\"email\" name=\"email\" required/></td></tr>
		<tr><td>Password</td><td><input type=\"password\" name=\"newpass\" required/></td></tr>
		<tr><td>Contact Number</td><td><input type=\"text\" name=\"phone\" /></td></tr>
		<tr><td><input type=\"hidden\" name=\"optionadd\" value=\"92\"/></td></tr>
		<tr><td><input type=\"submit\" name=\"submit\" value=\"submit\"/></td></tr>
		
		</form>
		</table>
		</div> ";
}

function addUser()
{
	global $db, $uname, $newpass, $email, $phone;
	connect($db);
	echo "Hello from addUser funtion!";
	if($stmt = mysqli_prepare($db, "select userid from users where email=?"))
	{
		mysqli_stmt_bind_param($stmt, "s", $email);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $userId);
		while(mysqli_stmt_fetch($stmt))
		{
			$userId =htmlspecialchars($userId);
		}
		mysqli_stmt_close($stmt);
	}
	
	if($userId == null)
	{	
		$newuname=mysqli_real_escape_string($db,$uname);
		$newpass=mysqli_real_escape_string($db,$newpass);
		$email=mysqli_real_escape_string($db,$email);
		$phone=mysqli_real_escape_string($db,$phone);
				
		$salt = rand(50,10000);
		$hash_salt=hash('sha256',$salt);
		$hash_pass=hash('sha256',$newpass.$hash_salt);
	
		if($stmt = mysqli_prepare($db, "insert into users set userid='', username=?, password=?, email=?, salt=?, phone=?"))
    	{
    	    mysqli_stmt_bind_param($stmt, "sssss", $uname,$hash_pass, $email, $hash_salt, $phone);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "Added new user " . $uname;
  		}
  		else
  			echo "Error in insertion!";
	}
	else 
  		echo "User with Email ID: ". $email . " already exists in the database!";

}

?>