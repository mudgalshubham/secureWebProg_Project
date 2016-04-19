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

echo " welcome to the Catalog";

?>