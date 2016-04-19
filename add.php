<?php

// Name: project/add.php
// Author: Shubham Mudgal
// Purpose: User's profile with available actions
// Version: 1.0
// Date: 04/04/2016

session_start();
session_regenerate_id();
include('header.php');
include_once('/var/www/html/project/project-lib.php');

isset($_REQUEST['s'])?$s=strip_tags($_REQUEST['s']):$s="";
isset($_REQUEST['postEmail'])?$postEmail=strip_tags($_REQUEST['postEmail']):$postEmail="";
isset($_REQUEST['postPass'])?$postPass=strip_tags($_REQUEST['postPass']):$postPass="";

isset($_REQUEST['newuname'])?$newuname=strip_tags($_REQUEST['newuname']):$newuname="";
isset($_REQUEST['newpass'])?$newpass=strip_tags($_REQUEST['newpass']):$newpass="";
isset($_REQUEST['email'])?$email=strip_tags($_REQUEST['email']):$email="";
$IPAddress ='';

connect($db);
if(isset($_SESSION['authenticated']) && $_SESSION['authenticated']=="yes")
{
	//authenticate($db, $postUser, $postPass);
	addCharacterMenu($s);
//	header("Location:/project/index.php");
}
else
{		
	if($postEmail == null)
		{	
			echo "email not found-> Auth pending";
			//header("Location:/project/login.php");
		}
		
			authenticate();
		//	checkAuth();
		//	addCharacterMenu($s);
			echo "postEmail= ". $postEmail ;
			echo "\npostPass= ". $postPass ;
			echo "after authenticate\n";
			echo "authenticated session= ". $_SESSION['authenticated'] ;
			echo "\nsession_uname= ".$_SESSION['uname']=$uname;		//added now	
			echo "\nsession_uid= ".$_SESSION['userid']=$userid;

}

function addCharacterMenu($s)
{	
	global $db, $cname, $side, $race, $cid,$url ;
	
	if(is_numeric($s))
	{
		switch($s)
		{
			case 1:	 addItemForm(); break;

			case 2:	 addItem(); break;

			case 3:  updateProfileForm(); break;
	
			case 4:  updateProfile(); break;
			
			case 9:  header("Location:/project/index.php"); break;	//echo "Inside case 9";break;

			case 15: // Logout
				 		logout();
				 		break;		
				 
			default: echo "Inside default case";break;
		}
	}
	
}

function addItemForm(){}

function addItem()
{}

function updateProfileForm()
{}

function updateProfile()
{}


function authenticate()
{
	global $db,$postEmail,$postPass;
	
	connect($db);
	
	$postEmail=mysqli_real_escape_string($db,$postEmail);
	$postPass=mysqli_real_escape_string($db,$postPass);
	
	$query="select userid, username, password, salt from users where email=?";
	if($stmt = mysqli_prepare($db, $query))	
	{
		mysqli_stmt_bind_param($stmt, "s", $postEmail);	
		mysqli_stmt_execute($stmt);	
  		mysqli_stmt_bind_result($stmt, $userid, $uname, $password, $salt);
  		while(mysqli_stmt_fetch($stmt))	
  		{
  			$userid=$userid;
  			$uname=$uname;
  			$password=$password;
  			$salt=$salt;
  		}
  		mysqli_stmt_close($stmt);
  		$epass=hash('sha256', $postPass.$salt);
  		if($epass == $password)	
  		{	
  			session_regenerate_id();
  			$_SESSION['uname']=$uname;		//added now	
			$_SESSION['userid']=$userid;
			$_SESSION['authenticated']="yes";
			$_SESSION['ip']=$_SERVER['REMOTE_ADDR'];
			$_SESSION['HTTP_USER_AGENT']=md5($_SERVER['SERVER_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
			$_SESSION['created']=time();
		//	logLogin($db, $postUser, "success");
  		}	
  		else	
  		{	
  			echo "Failed to Login";
  		//	logLogin($db, $postUser, "failure");
  		//	error_log("Error login to Tolkien. IP:" . $_SERVER['REMOTE_ADDRESS'], 0);
  			//header("Location:/project/login.php");
  			
  		}
  	}
}

?>
