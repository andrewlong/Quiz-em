<?php

include( "include/functions.php" ); 
include( "include/auth.php" ); 
include( "include/header.php" ); 

if (isset($_GET['page']))
{
	$page = "include/" . $_GET['page'] . ".php";
	if (file_exists($page)) 
	{
    	include($page);
	} else {
    	include( "include/home.php" ); 	
	}
}else {
	include( "include/home.php" ); 
}

include( "include/footer.php" ); 

?>


