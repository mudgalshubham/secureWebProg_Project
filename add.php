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

isset($_REQUEST['itemname'])?$itemname=strip_tags($_REQUEST['itemname']):$itemname="";
isset($_REQUEST['price'])?$price=strip_tags($_REQUEST['price']):$price="";
isset($_REQUEST['pictureurl'])?$pictureurl=strip_tags($_REQUEST['pictureurl']):$pictureurl="";
isset($_REQUEST['description'])?$description=strip_tags($_REQUEST['description']):$description="";

isset($_REQUEST['newuname'])?$newuname=strip_tags($_REQUEST['newuname']):$newuname="";
isset($_REQUEST['newpass'])?$newpass=strip_tags($_REQUEST['newpass']):$newpass="";
isset($_REQUEST['newphone'])?$newphone=strip_tags($_REQUEST['newphone']):$newphone="";
isset($_REQUEST['checkname'])?$checkname=strip_tags($_REQUEST['checkname']):$checkname="";
isset($_REQUEST['checkpass'])?$checkpass=strip_tags($_REQUEST['checkpass']):$checkpass="";
isset($_REQUEST['checkphone'])?$checkphone=strip_tags($_REQUEST['checkphone']):$checkphone="";
$IPAddress ='';

connect($db);
if(isset($_SESSION['authenticated']) && $_SESSION['authenticated']=="yes")
{
	projectMenu($s);

}
else
{		
	if($postEmail == null)
		{	
			header("Location:/project/login.php");
		}
	
	$IPAddress = $_SERVER['REMOTE_ADDR']; 
	//	echo "Ipadd= " .$IPAddress;
		
		$whiteListIPAddress = whiteList();
		$isWhiteListIP = in_array($IPAddress,$whiteListIPAddress);
		$attemptCount = incorrectAttempts($db,$IPAddress);
		if(!$isWhiteListIP  && $attemptCount >= 5)
		{
			logLogin($db, $postUser, "failure");
			header("Location:/project/login.php");		
		}
		else 
		{	
			authenticate();
			checkAuth();
			projectMenu($s);
		}
}

function projectMenu($s)
{	
	global $db ;
	
	if(is_numeric($s))
	{
		switch($s)
		{
			case 1:	 addItemForm(); break;

			case 2:	 addItem(); break;

			case 3:  updateProfileForm(); break;
	
			case 4:  updateProfile(); break;
			
			case 9:  header("Location:/project/index.php"); break;

			case 15: // Logout
				 		logout();
				 		break;		
				 
			case 91: if(isAdmin()) 
						showUsers(); 
				 	 else
				 		echo "User not authorized to use this functionality";
				 	
				 		break;
			
			case 92: if(isAdmin()) 
						loginFailureReport(); 
				 	 else
						echo "User not authorized to use this functionality";
						
						break;
						
			default: echo "Invalid Data Found";break;
		}
	}
	else
		echo "Invalid Data found!";
	
}

function updateProfile()
{
	global $checkname,$checkpass,$checkphone,$newuname, $newpass, $newphone ;
	$userid = $_SESSION['userid'];
	
	global $db, $newuname, $newpass;
	connect($db);

	//update User's Name
	if($checkname != NULL)
	{	
	  if($newuname != null)
	  {
		$newuname=mysqli_real_escape_string($db,$newuname);
		
		if($stmt = mysqli_prepare($db, "update users set username =? where userid=?"))
   		{
            mysqli_stmt_bind_param($stmt, "si", $newuname, $userid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "Name updated for user " . $_SESSION['uname'];
  		}
  		else
  			echo "Error in modification of User's name!";
  	  }
  	  else
  	  	echo "Error: User's Name cannot be null";
  	}
	
	//update Password
	if($checkpass != NULL)
	{	
	  if($newpass != null)
	  {
		$newpass=mysqli_real_escape_string($db,$newpass);
				
		$salt = rand(50,10000);
		$hash_salt=hash('sha256',$salt);
		$hash_pass=hash('sha256',$newpass.$hash_salt);
		
		if($stmt = mysqli_prepare($db, "update users set salt =?, password=? where userid=?"))
   		{
            mysqli_stmt_bind_param($stmt, "ssi", $hash_salt ,$hash_pass, $userid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "Password updated for user " . $_SESSION['uname'];
  		}
  		else
  			echo "Error in modification of password!";
  	  }
  	  else 
  	  		echo "Error: Password cannot be null";
  	}
	
	//update User's Phone Number
	if($checkphone != NULL)
	{	
		$newphone=mysqli_real_escape_string($db,$newphone);
		
		if($stmt = mysqli_prepare($db, "update users set phone =? where userid=?"))
   		{
            mysqli_stmt_bind_param($stmt, "si", $newphone, $userid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "Phone number updated for user " . $_SESSION['uname'];
  		}
  		else
  			echo "Error in modification of User's name!";
  	  }
  	  
  	  if($checkphone == NULL && $checkpass == NULL && $checkname == NULL)
  	  {
  	  		echo "Error: Please select checkboxes corresponding the respective field to update the values.";
  	  }
	
}

function updateProfileForm()
{
	echo "<div align=center><table><tr><td><b>Update User Profile</b></td></tr>
		<form action=add.php method=post>
		
		<tr><td><input type=\"checkbox\" name=\"checkname\" value=\"newuname\"></td> 
		<td>Update Name</td><td><input type=\"text\" name=\"newuname\"></td></tr>

		<tr><td><input type=\"checkbox\" name=\"checkpass\" value=\"pass\"></td> 
		<td>Update Password</td><td><input type=\"password\" name=\"newpass\"></td></tr>

		<tr><td><input type=\"checkbox\" name=\"checkphone\" value=\"phone\"></td> 
		<td>Update Phone</td><td><input type=\"text\" name=\"newphone\"></td></tr>

		<tr><td><input type=\"submit\" name=\"submit\" value=\"submit\"/></td></tr>
		<tr><td><input type=\"hidden\" name=\"s\" value=\"4\"/></td></tr>
		</form>
		</table>
		</div> ";
}

function addItem()
{
	global $db,$itemname,$price,$pictureurl,$description;
	connect($db);
	
		$sellerid = $_SESSION['userid'];
		$itemname = mysqli_real_escape_string($db, $itemname);
        $price = mysqli_real_escape_string($db, $price);
		$pictureurl = mysqli_real_escape_string($db, $pictureurl);
		$description = mysqli_real_escape_string($db, $description);
			
		if($stmt = mysqli_prepare($db, "insert into catalog set itemid='', itemname=?, price=?, picture=?, description=?, sellerid=?"))
        {
                mysqli_stmt_bind_param($stmt, "ssssi", $itemname,$price,$pictureurl,$description,$sellerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
        	
        		echo "Item added in catalog for ". $_SESSION['uname'];

		}
        else
            echo "Error in query";
		
}

function addItemForm()
{
	echo "<div align=center><table><tr><td><b>Add New Item</b></td></tr>
		<form action=add.php method=post>
		<tr><td>Name of the Item</td><td><input type=\"text\" name=\"itemname\" required/></td></tr>
		
		<tr><td>Price</td><td><input type=\"text\" name=\"price\" pattern=\"[0-9]+[.]?([0-9][0-9]?)?\" 
				title=\"Only decimal numbers with 2 decimal places allowed\" required/></td></tr>
		
		<tr><td>Add Photo URL</td><td><input type=\"text\" name=\"pictureurl\" /></td></tr>
		
		<tr><td>Item Description</td><td><textarea cols=\"50\" rows=\"4\" name=\"description\"></textarea></td></tr>
		
		<tr><td><input type=\"submit\" name=\"submit\" value=\"submit\"/></td></tr>
		<tr><td><input type=\"hidden\" name=\"s\" value=\"2\"/></td></tr>
		</form>
		</table>
		</div> ";
}


function showUsers()
{	global $db;

	connect($db);
	if($stmt = mysqli_prepare($db, "select username from users"))
        {
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $uname);
                echo "<table><th><b>Users of this application</b></th>";
                while(mysqli_stmt_fetch($stmt))
				{
					$uname = htmlspecialchars($uname);
					echo "<tr><td>$uname<br></td></tr><table>";
				}
				echo "</table>";
				mysqli_stmt_close($stmt);
        }
}


function logLogin($db, $user, $msg)
{
	$IPAddress = $_SERVER['REMOTE_ADDR'];
	
	if($stmt = mysqli_prepare($db,"insert into login set loginid='', ip=?, user=?, action=?, date=NOW()"))
	{
		mysqli_stmt_bind_param($stmt, "sss", $IPAddress, $user, $msg);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		
	}
	
	else
	{
		echo "Error"; 
	 	
	}
	return;
}

function incorrectAttempts($db, $IPAddress)
{	
	connect($db);
	if($stmt = mysqli_prepare($db,"select count(*) from login where action='failure' and ip=? and date > DATE_SUB(NOW(),INTERVAL 1 HOUR)"))
	{
			mysqli_stmt_bind_param($stmt, "s", $IPAddress);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $count);
			while(mysqli_stmt_fetch($stmt))
			{
				 $count = htmlspecialchars($count);
			}
			mysqli_stmt_close($stmt);
  			return $count;
	}
	else
	{
		echo "Error"; 
	 	exit;
	}
	return 0;
}

function loginFailureReport()
{
	global $db;
	connect($db);
	
	if($stmt = mysqli_prepare($db,"select ip, count(*) from login where action='failure' GROUP BY ip"))
	{				
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $IPAddress, $count);
		echo "<table><tr>Login Failure Details</tr>
				<tr><td><b>IP Address</b></td>
					<td><b>Number of Failed Attempts</b></td></tr>";
		while(mysqli_stmt_fetch($stmt))
		{
			$IPAddress = htmlspecialchars($IPAddress);
			$count = htmlspecialchars($count);
			echo "<tr><td>$IPAddress<br></td>
					<td>&nbsp&nbsp$count</td></tr>";
		}
		if($IPAddress == null)
			echo "<tr><td>No failed logins till now!</td></tr>";
			
		echo "</table>";
		mysqli_stmt_close($stmt);
	}
	else
	{
		echo "Error"; 
		exit;
	}

}


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
			logLogin($db, $postUser, "success");
  		}	
  		else	
  		{	
  			echo "Failed to Login";
  			logLogin($db, $postUser, "failure");
  			error_log("Error login to eCommerce Application. IP:" . $_SERVER['REMOTE_ADDRESS'], 0);
  			header("Location:/project/login.php");
  		}
  	}
}

?>
