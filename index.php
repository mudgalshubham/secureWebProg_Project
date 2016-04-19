<?php
// Name: project/index.php
// Author: Shubham Mudgal
// Purpose: Home page for eCommerce for CU Boulder application
// Version: 1.0
// Date: 04/04/2016



session_start();
session_regenerate_id();
include_once('header.php');
include_once('/var/www/html/project/project-lib.php');

isset($_REQUEST['s'])?$s=strip_tags($_REQUEST['s']):$s="";
isset($_REQUEST['sid'])?$sid=strip_tags($_REQUEST['sid']):$sid="";
isset($_REQUEST['bid'])?$bid=strip_tags($_REQUEST['bid']):$bid="";
isset($_REQUEST['cid'])?$cid=strip_tags($_REQUEST['cid']):$cid="";
isset($_REQUEST['cname'])?$cname=strip_tags($_REQUEST['cname']):$cname="";
isset($_REQUEST['side'])?$side=strip_tags($_REQUEST['side']):$side="";
isset($_REQUEST['race'])?$race=strip_tags($_REQUEST['race']):$race="";
isset($_REQUEST['url'])?$url=strip_tags($_REQUEST['url']):$url="";
isset($_REQUEST['bookid'])?$bookid=strip_tags($_REQUEST['bookid']):$bookid="";

connect($db);

echo " Welcome to the Catalog";

if($stmt = mysqli_prepare($db, "select itemid, itemname,price,picture,description from catalog"))
{
  //    mysqli_stmt_bind_param($stmt, "i", $cid);
		mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $itemid, $itemname, $price, $picture, $desc);
        while(mysqli_stmt_fetch($stmt))
		{
			$itemid= htmlspecialchars($itemid);
			$itemname= htmlspecialchars($itemname);
            $price= htmlspecialchars($price);
			$picture = htmlspecialchars($picture);
			$desc = htmlspecialchars($desc);
			echo "<div style=\"border: 5px ridge silver;\"><table cellpadding=\"20\">
					<tr><td> <img src=\"".$picture."\" height=\"100\" width=\"100\"></td>
                		<td><table cellpadding=\"10\"><tr><td>Product Name</td>
                				<td>".$itemname."</td></tr>
                				<tr><td>Price</td>
                				<td>".$price."</td></tr>
                				<tr><td>Description</td>
                				<td>".$desc."</td></tr> 
                		</table></td>
                		</tr></table></div> <br>";
        }
		mysqli_stmt_close($stmt);
}




?>