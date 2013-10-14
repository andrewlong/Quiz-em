
<?php

include( "../../include/functions.php" ); 
include( "../../include/auth.php" ); 
$link = db_connect();

//mysql information
$table = 'files';
$id = mysqli_real_escape_string($link, $_GET['id']);
$constraint = 'quiz_id';


/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');
$upload_handler = new UploadHandler();

mysqli_close($link);
?>