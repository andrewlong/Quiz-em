<?php
include( "functions.php" ); 
include( "auth.php" ); 


$link = db_connect();
	$q = "";
	foreach ($_POST['order'] as $k => $v) 
			{	$x++;	
				if (is_numeric($k))//check to make sure it is numeric
				{	$id =mysqli_real_escape_string($link,$k); //also escape for good measure
					$q= $q . "UPDATE question SET  `sort_order` =$x WHERE  `id` =$id ; " ;
				}
			}
	if(!$q == "") //check and make sure $q is not empty
	{
		$res = mysqli_multi_query($link,$q);
		if (!$res) 
		{
			print 'Could not run query: ' . $q;
		    exit;
		}
	}
?>