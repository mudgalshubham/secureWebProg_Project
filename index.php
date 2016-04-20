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

connect($db);

echo " Welcome to the Catalog";


$itemidArray = array();
$itemnameArray = array();
$priceArray = array();
$pictureArray = array();
$descArray = array();

if($stmt = mysqli_prepare($db, "select itemid,itemname,price,picture,description from catalog"))
{
		mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $itemid, $itemname, $price, $picture, $desc);
        while(mysqli_stmt_fetch($stmt))
		{
			array_push($itemidArray, htmlspecialchars($itemid));
			array_push($itemnameArray, htmlspecialchars($itemname));
			array_push($priceArray, htmlspecialchars($price));
			array_push($pictureArray, htmlspecialchars($picture));
			array_push($descArray, htmlspecialchars($desc));
		}
		mysqli_stmt_close($stmt);
			
		for($i=0; $i < sizeof($itemidArray); $i++)
		{
			echo "<div style=\"border: 5px ridge silver;\"><table cellpadding=\"20\">
					<tr><td> <img src=\"".$pictureArray[$i]."\" height=\"100\" width=\"100\"></td>
                		<td><table cellpadding=\"10\"><tr><td>Product Name</td>
                				<td>".$itemnameArray[$i]."</td></tr>
                				<tr><td>Price</td>
                				<td>$".$priceArray[$i]."</td></tr>
                				<tr><td>Description</td>
                				<td>".$descArray[$i]."</td></tr>  
                				</table></td>";
                			
                			
                if($s==1)
                {
                	echo "<td><table cellpadding=\"10\">
                			<tr><td>Please login first to view Seller's Info</td>
                			</tr></table></td>";	
                }
                else
                {
                	echo "<td><table cellpadding=\"10\">
                			<tr><td>
                			<form action=sellerinfo.php method=post>
                				<tr><td><input type=\"submit\" name=\"submit\" value=\"View Seller's Info\"/></td></tr>
								<input type=\"hidden\" name=\"itemid\" value=\"$itemidArray[$i]\"/>
								<input type=\"hidden\" name=\"itemname\" value=\"$itemnameArray[$i]\"/>
								<input type=\"hidden\" name=\"price\" value=\"$priceArray[$i]\"/>
								<input type=\"hidden\" name=\"picture\" value=\"$pictureArray[$i]\"/>
								<input type=\"hidden\" name=\"desc\" value=\"$descArray[$i]\"/>
                				</td></tr>
                			</table></td>";
                }
                
                echo " </tr></table></div> <br>";
        }
		mysqli_stmt_close($stmt);
}
         

function getSellerInfo($sellerid)
{
	global $db;
	$output = '';
	if($stmt = mysqli_prepare($db, "select username,email,phone from users where userid=?"))
									{
									mysqli_stmt_bind_param($stmt, "s", $sellerid);
									mysqli_stmt_execute($stmt);
							        mysqli_stmt_bind_result($stmt, $sellername, $selleremail, $sellerphone);
							        while(mysqli_stmt_fetch($stmt))
									{
										$sellername= htmlspecialchars($sellername);
										$selleremail= htmlspecialchars($selleremail);
							            $sellerphone= htmlspecialchars($sellerphone);
        							}
							        mysqli_stmt_close($stmt);
        
       							 	echo "<td><table cellpadding=\"10\"><tr><td>Seller\'s Name</td>
                						<td>".$sellername."</td></tr>
                						<tr><td>Email</td>
		                				<td>$".$selleremail."</td></tr>
        		        				<tr><td>Contact Number</td>
                						<td>".$sellerphone."</td></tr></table></td>";
        
    								}
        
}


?>