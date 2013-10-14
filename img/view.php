<?php

include ('../include/functions.php');

    // just so we know it is broken
    error_reporting(0);
    // some basic sanity checks
    if(isset($_GET['file']) && is_numeric($_GET['file'])) {
        //connect to the db
		$mysql_host = "localhost";
		$mysql_database = 'quiz';
		$mysql_user = "dbreader";
		$mysql_password = "b10s";
			
			mysql_connect($mysql_host,$mysql_user,$mysql_password); // Connect to the MySQL server
			mysql_select_db($mysql_database); // Select your Database	
 		
 		$file = mysql_real_escape_string($_GET['file']);
        $table = mysql_real_escape_string($_GET["cat"]);

        // get the file from the db
        $sql = "SELECT file, file_type, file_name FROM files WHERE id=$file";
 
        // the result of the query
        $result = mysql_query("$sql") or die();
 		$row = mysql_fetch_assoc($result);
        $file_type = $row['file_type'];
        $file_name = $row['file_name'];
		
		
		
		//set to not display thumbnails for non-image files
		if (isset($_GET['thumb']) && !strstr($file_type,"image"))
		{	
			//generate single transparent pixel and exit
			header('Content-Type: image/gif');
			echo base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');
			exit;
		}        
        
        // set the header for the file
        header("Content-type: $file_type");
        header("Content-Disposition: filename= $file_name");
		if(isset($_GET['thumb']))
		{   
			
			
		   	$fileContent = $row['file']; 
		 
		    //$fileType = str_replace(".","",strtolower(substr( $file_Type,strrpos( $file_Type,"."))));
		 
		    //$filename = $file_Type;
		 
		 //   header("Content-type: $fileType"); 
		 
		    // get originalsize of image 
		    $im = imagecreatefromstring($fileContent);
		    $width  = imagesx($im); 
		    $height = imagesy($im);
		 
		    // Set thumbnail-width to 100 pixel 
		    $imgw = 150; 
		 
		    // calculate thumbnail-height from given width to maintain aspect ratio 
		    $imgh = $height / $width * $imgw; 
		 
		    // create new image using thumbnail-size 
		    $thumb=imagecreatetruecolor($imgw,$imgh); 
		    $filename = addslashes (file_get_contents($fileContent));
		    $image_name= stripslashes($fileContent);
		    // copy original image to thumbnail 
		    imagecopyresampled($thumb,$im,0,0,0,0,$imgw,$imgh,ImageSX($im),ImageSY($im)); 
		 
		    // show thumbnail on screen 
		    $out = imagejpeg($thumb); 
		    print($out); 
		 
		    // clean memory 
		    imagedestroy ($im); 
		    imagedestroy ($thumb); 
				
    }	
	else
	echo $row['file'];
        // close the db link
        mysql_close($link);
    }
    else {
        echo 'Please use a real id number';
    }
?>