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



if($stmt = mysqli_prepare($db, "select itemid,itemname,price,picture,description from catalog"))
{
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
                				<td>$".$price."</td></tr>
                				<tr><td>Description</td>
                				<td>".$desc."</td></tr>  
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
											<input type=\"hidden\" name=\"itemid\" value=\"$itemid\"/>
											<input type=\"hidden\" name=\"itemname\" value=\"$itemname\"/>
											<input type=\"hidden\" name=\"price\" value=\"$price\"/>
											<input type=\"hidden\" name=\"picture\" value=\"$picture\"/>
											<input type=\"hidden\" name=\"desc\" value=\"$desc\"/>
										</form>
                						</td></tr>
                					</table></td>";
                			}
                		echo " </tr></table></div> <br>";
        }
		mysqli_stmt_close($stmt);
}
         

?>