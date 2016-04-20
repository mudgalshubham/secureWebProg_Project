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

isset($_REQUEST['newrating'])?$newrating=strip_tags($_REQUEST['newrating']):$newrating="";
isset($_REQUEST['ratecount'])?$ratecount=strip_tags($_REQUEST['ratecount']):$ratecount="";
isset($_REQUEST['oldavgrating'])?$oldavgrating=strip_tags($_REQUEST['oldavgrating']):$oldavgrating="";
isset($_REQUEST['newreview'])?$newreview=strip_tags($_REQUEST['newreview']):$newreview="";
isset($_REQUEST['checkrating'])?$checkrating=strip_tags($_REQUEST['checkrating']):$checkrating="";
isset($_REQUEST['checkreview'])?$checkreview=strip_tags($_REQUEST['checkreview']):$checkreview="";


connect($db);

if(!isset($_SESSION['authenticated']))
{	
	header("Location:/project/index.php?s=1");
}

else
{
	$currentuserid = $_SESSION['userid'];
	
	
	//Query db for seller information
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
		
		echo "inside seller db query block\n sellerid= ".$sellerid;
	}
	
	
	//Add rating and reviews for this seller
	if($s==25)
	{
		echo "inside s=25 block";
		//Add Seller's Rating
/*		if($checkrating != NULL)
		{	
			$newrating=mysqli_real_escape_string($db,$newrating);
			$ratecount=mysqli_real_escape_string($db,$ratecount);
			$oldavgrating=mysqli_real_escape_string($db,$oldavgrating);
			
			$newcount = $ratecount +1;
			$newavgrating = ( ($oldavgrating * $ratecount) + ($newrating) ) / $newcount;
			
			if($stmt = mysqli_prepare($db, "INSERT INTO rating VALUES('', ?, ?, ?) 
											ON DUPLICATE KEY UPDATE avgrating=?, count=?"))
   			{
            	mysqli_stmt_bind_param($stmt, "diidi", $newavgrating, $newcount, $sellerid, $newavgrating, $newcount);
            	mysqli_stmt_execute($stmt);
            	mysqli_stmt_close($stmt);
            	echo "Rating updated for user " . $sellerid;
  			}
  			else
  				echo "Error in adding of Seller's rating!";
  		}
  		
  		//Add Review to Seller
  		if($checkreview != NULL)
		{	
			$newreview=mysqli_real_escape_string($db,$newreview);
			
			if($stmt = mysqli_prepare($db, "insert into reviews set reviewid='', review =?, buyerid=?, sellerid=?"))
   			{
            	mysqli_stmt_bind_param($stmt, "sii", $newreview, $currentuserid, $sellerid);
            	mysqli_stmt_execute($stmt);
            	mysqli_stmt_close($stmt);
            	echo "Review added for user " . $sellerid;
  			}
  			else
  				echo "Error in adding of Seller's review!";
  		}
  */
	}
	
	

	
	//Query db for ratings for this seller
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
	
		echo "<div style=\"border: 5px ridge silver;\"><table cellpadding=\"20\" border=1>
				<tr><td> <img src=\"".$picture."\" height=\"100\" width=\"100\"></td>
                <td><table cellpadding=\"10\" border=1>
        		<th>Product Info</th>
	        		<tr><td>Product Name :</td>
         				<td>".$itemname."</td></tr>
                		<tr><td>Price :</td>
                		<td>$".$price."</td></tr>
                		<tr><td>Description :</td>
                		<td>".$desc."</td></tr>  
                		</table></td>
                <td><table cellpadding=\"10\" border=1>
                	<th>Seller's Info</th>
                		<tr><td>Seller's Name :</td>
                		<td>".$sellername."</td></tr>
                		<tr><td>Email :</td>
		                <td>".$selleremail."</td></tr>
        		        <tr><td>Contact Number :</td>
                		<td>".$sellerphone."</td></tr></table></td>";
                			
                
                			
        //Option to add rating and review only if the current user is not the seller itself
        if($sellerid != $currentuserid)
        {
            echo "<td><table cellpadding=\"10\" border =1>
                	<form action=sellerinfo.php method=post>
    					<tr><td><input type=\"checkbox\" name=\"checkrating\" value=\"newrating\"></td> 
    						<td>Give rating(0-5)</td>
    						<td><input type=\"text\" name=\"newrating\" pattern=\"[0-5]\" 
									title=\"Only integer values from 0-5 are allowed\" /></td></tr>
    					<tr><td><input type=\"checkbox\" name=\"checkreview\" value=\"newreview\"></td> 
    						<td>Add reviews</td>
    						<td><textarea cols=\"10\" rows=\"10\" name=\"newreview\"></textarea></td></tr>
    						<input type=\"hidden\" name=\"oldavgrating\" value=\"$avgrating\"/>
    						<input type=\"hidden\" name=\"ratecount\" value=\"$ratecount\"/>
    						<input type=\"hidden\" name=\"s\" value=\"25\"/>	
    						<input type=\"hidden\" name=\"itemid\" value=\"$itemid\"/>
											<input type=\"hidden\" name=\"itemname\" value=\"$itemname\"/>
											<input type=\"hidden\" name=\"price\" value=\"$price\"/>
											<input type=\"hidden\" name=\"picture\" value=\"$picture\"/>
											<input type=\"hidden\" name=\"desc\" value=\"$desc\"/>			
                		<tr><td><input type=\"submit\" name=\"submit\" value=\"Submit\"/></td></tr>
                	</form>
                	</table>
                	</td>";
                		
        }
                
        echo "</tr>";
        
        //Display Seller's rating        
        if($avgrating != null)
        {			
            echo "<tr><td> Seller's Rating: </td>
                	<td> ".$avgrating."</td></tr>";
        }
        else
            echo "<tr><td> Seller not rated yet! </td></tr>";
                
       
    	
    	$currentReviewArray = array();
		$buyeridArray = array();
	//Query db for reviews for this seller
		if($stmt = mysqli_prepare($db, "select review,buyerid from reviews where sellerid= ?"))
		{
			mysqli_stmt_bind_param($stmt, "i", $sellerid);
			mysqli_stmt_execute($stmt);
        	mysqli_stmt_bind_result($stmt, $currentreview, $buyerid);
        	while(mysqli_stmt_fetch($stmt))
			{
				array_push($currentReviewArray, htmlspecialchars($currentreview));
				array_push($buyeridArray, htmlspecialchars($buyerid));
			}
			mysqli_stmt_close($stmt);
		 } 
		 
		 if(sizeof($currentReviewArray) !=0)
         {
         	echo "<tr><td> Seller's Reviews </td></tr>";
         	
         	for($i=0; $i < sizeof($currentReviewArray); $i++)
			{
                echo "<tr><td> ".$buyeridArray[$i]."</td>
                		<td border>".$currentReviewArray[$i] . "</td></tr>";
            }   
         }
         else
         	echo "<tr><td> No reviews for this Seller yet! </td></tr>";
         
       echo "</table></div> <br>";
                				
                				
}

?>