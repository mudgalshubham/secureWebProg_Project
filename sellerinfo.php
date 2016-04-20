<?php
// Name: project/sellerinfo.php
// Author: Shubham Mudgal
// Purpose: Seller's informtation for the selected item
// Version: 1.0
// Date: 04/04/2016



session_start();
session_regenerate_id();
include_once('header.php');
include_once('/var/www/html/project/project-lib.php');

isset($_REQUEST['s'])?$s=strip_tags($_REQUEST['s']):$s="";
isset($_REQUEST['itemid'])?$itemid=strip_tags($_REQUEST['itemid']):$itemid="";
isset($_REQUEST['itemname'])?$itemname=strip_tags($_REQUEST['itemname']):$itemname="";
isset($_REQUEST['price'])?$price=strip_tags($_REQUEST['price']):$price="";
isset($_REQUEST['picture'])?$picture=strip_tags($_REQUEST['picture']):$picture="";
isset($_REQUEST['desc'])?$desc=strip_tags($_REQUEST['desc']):$desc="";


connect($db);

if(!isset($_SESSION['authenticated']))
{	
	header("Location:/project/index.php?s=1");
}

else
{
	if($stmt = mysqli_prepare($db, "select u.userid,u.username,u.email,u.phone from 
		users as u, catalog as c where c.sellerid=u.userid and c.itemid= ?"))
	{
		mysqli_stmt_bind_param($stmt, "i", $itemid);
		mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $sellerid, $sellername, $selleremail, $sellerphone);
        while(mysqli_stmt_fetch($stmt))
		{
			$sellerid= htmlspecialchars($sellerid);
			$sellername= htmlspecialchars($sellername);
            $selleremail= htmlspecialchars($selleremail);
			$sellerphone = htmlspecialchars($sellerphone);
		}
		mysqli_stmt_close($stmt);
		
	}
	
	if($stmt = mysqli_prepare($db, "select avgrating,count from rating where sellerid= ?"))
	{
		mysqli_stmt_bind_param($stmt, "i", $sellerid);
		mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $avgrating, $ratecount);
        while(mysqli_stmt_fetch($stmt))
		{
			$avgrating= htmlspecialchars($avgrating);
			$ratecount= htmlspecialchars($ratecount);
		}
		mysqli_stmt_close($stmt);
		
	}
	
			echo "<div style=\"border: 5px ridge silver;\"><table cellpadding=\"20\">
					<tr><td> <img src=\"".$picture."\" height=\"100\" width=\"100\"></td>
                		<td><table cellpadding=\"10\"><tr><td>Product Name</td>
                				<td>".$itemname."</td></tr>
                				<tr><td>Price</td>
                				<td>$".$price."</td></tr>
                				<tr><td>Description</td>
                				<td>".$desc."</td></tr>  
                				</table></td>
                			<td><table cellpadding=\"10\"><tr><td>Seller's Name</td>
                					<td>".$sellername."</td></tr>
                					<tr><td>Email</td>
		                			<td>$".$selleremail."</td></tr>
        		        			<tr><td>Contact Number</td>
                					<td>".$sellerphone."</td></tr></table></td>
                			</tr>
                			<tr><td> Seller's Rating</td>
                				<td> ".$avgrating."</td></tr>
                				</table></div> <br>
}