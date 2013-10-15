<?php

function db_connect()
{
	$db='quiz';
	$mysql_host = "localhost";
	$mysql_user = "quiz";
	$mysql_password = "none";
	
	$mysqli = mysqli_connect($mysql_host,$mysql_user,$mysql_password, $db);
	if (mysqli_connect_errno($mysqli)) {
	    echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	return $mysqli;	

}

function getBetween($content,$start,$end){
    $r = explode($start, $content);
    if (isset($r[1])){
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
}

function get_url()
{
$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') 
                === FALSE ? 'http' : 'https';
$host     = $_SERVER['HTTP_HOST'];
$script   = $_SERVER['SCRIPT_NAME'];
$params   = $_SERVER['QUERY_STRING'];
 
$currentUrl = $protocol . '://' . $host . $script . '?' . $params;
 
return $currentUrl;
}

function get_page_url()
{
$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') 
                === FALSE ? 'http' : 'https';
$host     = $_SERVER['HTTP_HOST'];
$script   = $_SERVER['SCRIPT_NAME'];
 
$currentUrl = $protocol . '://' . $host . $script;
 
return $currentUrl;
}

function get_base_url()
{
$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') 
                === FALSE ? 'http' : 'https';
$host     = $_SERVER['HTTP_HOST'];
$script   = $_SERVER['SCRIPT_NAME'];
 
$currentUrl = $protocol . '://' . $host;
 
return $currentUrl;
}

function referer_url()
{
	if(isset($_SERVER['HTTP_REFERER']))
	{
		$currentUrl = $_SERVER['HTTP_REFERER'];
	}
	else
	{
		$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') 
		                === FALSE ? 'http' : 'https';
		$host     = $_SERVER['HTTP_HOST'];
		$script   = $_SERVER['SCRIPT_NAME'];
		 
		$currentUrl = $protocol . '://' . $host . $script;
	}
	
return $currentUrl;
}

function alert($class,$html)
{
	return "<div class='alert alert-block alert-$class fade in'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
        $html
	</div>";				
	
}

function text_input($name,$value,$type,$size,$extra="")
{
/*     generates a html input field formated for a hortizontal bootstrap form      
 *			args: 
 *              $name = display name of input field and name of input field forPOST variable, also placeholder
 *              $value = default value
 *              $type = type of html5 input  ie date, text, time, number, email, etc...
 *              $size= = currently not used, need to re-write since broke when updating to bootstrap v3
 *              $extra = extra attributes for the input field to have such as the "required" attribute
 */

	if ($_GET['action'] === "view")
	{ 	$extra = $extra . " disabled";
	}
	if ($type == "checkbox")
	{ $class = "form-checkbox"; }
	else{ $class = "form-control"; }
	if (strstr($name, '['))
	{	$label = ucwords(str_replace("_", " ", getBetween($name,'[',']'))); }
	else
	{	$label = ucwords(str_replace("_", " ", $name)); }
    print "<div class=\"form-group\">\n";
    print "\t\t<label class=\"col-md-2 control-label\" for=\"input$name\">$label</label>\n";
    print "\t\t<div class=\"col-md-4\">\n";
    print "\t\t\t\t<input type=\"$type\" class=\"$class\" id=\"input$name\" name=\"$name\" placeholder=\"$label\" $extra value=\"$value\">\n";
    print "\t\t</div>\n\t</div>\n";
}

function select_input($name,$options,$default="",$extra="",$add_all,$rows=false)
{
	/*      args: 
 *              $name = display name of input field and name of input field forPOST variable, also placeholder
 *				$options can be passed as nested  array or SQL query; generates options list
 *				if passed as array (
 *                                  	array (value1, display)
 *                                  )
 *                              value1 = value for POST data, 
 *                              display= display name
 *      		for SQL query first returned column with be used as value and second column for display
 *              
 *				$default = default value
 *              $extra = extra attributes for the input field to have such as the "required" attribute
 *              
 *              
 */

	if ($_GET['action'] === "view")
	{ 	$extra = $extra . " disabled";
	}
	if (strstr($name, '['))
	{	$label = ucwords(str_replace("_", " ", getBetween($name,'[',']'))); }
	else
	{	$label = ucwords(str_replace("_", " ", $name)); }
	   
	if ($rows)
	{ ?>
		
		<div class="form-group">
		<label for="category" class="col-md-6 control-label"><?php print $label; ?></label>
		<div class="col-md-6">
		<?php
	}
	else
	{ ?>
		<div class="form-group">
		<label for="category" class="col-md-2 control-label"><?php print $label; ?></label>
		<div class="col-md-3">
		<?php
	} ?>
		<select name="<?php print $name; ?>" id="category" class="form-control" <?php print $extra; ?>>
	<?php	
			if (!is_array($options)) //if not array build array of options from SQL query
			{	
				$link = db_connect();
				$query = $options;
				$result = mysqli_query($link,$query);
				if (!$result) //prints SQL query and errors and exits
				{	
					print 'Could not run query: ' . $q . mysqli_error();
				    exit;
				}
				$options = array ();
				if($add_all) //Add All option
				{ array_push($options,array('all','All'));
				}
				while($row = mysqli_fetch_assoc($result))
				{
					array_push($options,$row);
				}
			}
			foreach ($options as $k => $v) 
			{	$v = array_values($v);
				
				print "\t\t<option value=\"" . $v[0] . "\"";
				//print selected if matches default value
				if ($default == $v[0])
				{	print " selected"; }
				print ">" . $v[1] . "</option>\n";
			}
			?>
		</select>
	</div>
	</div>
	<?php
}
	
function process_query($title,$query,$trlink=false)
{
	
 /* Processes MySQL query and outputs results into a dynamically generated html table. 
  * Column headers are generated from the returned column name with "_" changed to " " and ucwords excuted to 
  * upper case first letter of each column name.  Expects a column named "id" to be returned with the identity id of the MySQL row
  * this column is not displayed in the html table.
  
  * args:
  * $title = title for outputed table
  * $query = SQL query to be executed resulting in output to be parsed
  * $trlink = page to send user to if clicking on row, expecting just the name of the page in the include directory ie "users" 
  * will also send &id=$mysql_row_id
  * 
  */
	
	$link = db_connect();  //connect to database
	
	//insert new audit trail capturing actual SQL query
	
			
		$result = mysqli_query($link,$query);
			if (!$result) //prints SQL query and errors and exits
			{	
				print 'Could not run query: ' . $query;
			    exit;
			}
				
	//	do { //this do loop cycles through result sets
				if (mysqli_num_rows($result) == 0)
				{	print "No records found";
				}		
				else
				{
					$thead = 1; //setup value for creating table header	
					while($row = mysqli_fetch_assoc($result))
					{	
						
						if ($thead == 1) //make table and title
						{	//count number of rows to determine number of columns required
							$row_count = count($row);
							foreach ($row as $k => $v) //don't count
							{	if($k === "id")
								{ $row_count = $row_count - 1; }
							}
							
							print "<table class=\"table-hover table table-striped table-bordered\" id=\"example\">\n";
							print "\t<thead>\n";
							if ($title)
							{
								print "\t\t<th colspan=\"$row_count\"><center>$title</center></th>\n";
							}
							print "\t\t<tr>\n"; //start new row
								
							//label columns from names returned with query
				            //to change names use SELECT column AS New_Name
				            // "_" will be treated a as space
							foreach ($row as $k => $v) 
							{ 	
								if($k !== 'id')
								{
									$k = ucwords(str_replace("_", " ", $k)); //convert _ to spaces so it looks better on output
									print "\t\t\t<td>$k</td>\n";
								}
							}
							print "\t\t</tr>\n" .
								"\t</thead>\n" . //done with table header
								"\t<tbody>\n"; //start table body
							$thead = 2; //to mark that it is okay to output results
									
						}
						
							if($trlink)
							{
								$trextra = "style=\"cursor: pointer;\" onclick=\"document.location = '" . get_page_url() . "?page=$trlink&action=view&id=" . $row['id'] . "';\"";
							}
							print "\t\t<tr $trextra>\n"; //start new row
							
							foreach ($row as $k => $v) 
							{ 
								
															 	
								if($k !== 'id')
								{
									print "\t\t\t<td>$v</td>\n"; //insert values into table
								}
								
							}
							print "\t\t</tr>\n"; //end row
							
					}
				
					if ($thead == 2) //print end of table if table above was output
					{
						print "\t</tbody>\n</table>\n";
					}
				}
		//}  while($t<10);  //while(sqlsrv_next_result($stmt)); //return next result set, if none exit loop
		mysqli_close($link);
}

function get_data($table,$id=false)
{
	/* returns a row in $table which row id matches $id
	*
	*
	*/
	if (!$table)  //check and make sure $table was passed if not kill function
	{
		return;
	}
	if (!$id)
	{ $id = $_GET['id'];
	}
	if ($_GET['action'] === "edit"||$_GET['action'] === "view"||$id)
	{	//connect to db
		$link = db_connect();
		//retrive info from existing entry
		$q= "SELECT * FROM $table WHERE id=" . mysqli_real_escape_string($link, $id);
		
		$res = mysqli_query($link,$q);
			if (!$res) 
			{
				print 'Could not run query: ' . $q;
			    exit;
			}
		$row = mysqli_fetch_assoc($res);		
	}
	
	mysqli_close($link);
	return $row;
}	

function process_post_data($table=false,$data_array=false)
{	/*
		Processes through a posted array and inserts/updates/deletes data in a database
		$table = SQL table that data is stored in
		$data_array = posted data array
		
	*/
	
	
	if (!$table || !$data_array)  //check and make sure $table was passed if not kill function
	{
		return;
	}
	
	$link = db_connect();
	
	if (isset($_POST['edit']))
	{	
		foreach ( $data_array as $k => $v) 
		{	
			if($first_done)
			{
				$values = $values . ",";
			}
			
			
			$escaped_value = mysqli_real_escape_string($link,$v);
			$values = $values . "$k='$escaped_value'";
			
			$first_done = true;
		}
		
		$action = 'edited';
		
		$q= "UPDATE $table SET $values WHERE id=" . mysqli_real_escape_string($link,$_POST['id']);	
		//print $q;
	}
	
	if (isset($_POST['new']))
	{		
		foreach ( $data_array as $k => $v) 
		{	
			if($first_done)
			{
				$columns = $columns . ",";
				$values = $values . ",";
			}
			
			$columns = $columns . $k;
			$escaped_value = mysqli_real_escape_string($link,$v);
			$values = $values . "'$escaped_value'";
			
			$first_done = true;
		}
		
		$action = 'added';
		
		$q= "INSERT INTO $table ($columns) VALUES ($values)";	
		//print $q;
	}
	
	$res = mysqli_query($link, $q);
	if (!$res) 
	{
		print 'Could not run query: ' . $q;
		exit;
	}
	
	if (mysqli_insert_id ($link) == 0)
	{ $id = mysqli_real_escape_string($link,$_POST['id']); }
	else { $id = mysqli_insert_id ($link); }
	
	mysqli_close($link);
	
	$msg = "<h4>Record $action</h4>";
	$array = array(
					'msg' => $msg,
					'id' => $id 
				  );
	return $array; 
}

function process_staff_type_data($table,$data_array,$linked_id,$linked_id_column)
{	/*
		Processes through a posted array and inserts/updates staff types
		$table = SQL table that data is stored in
		$data_array = posted data array
	*/
	
	$link = db_connect();
if(isset($data_array))
	{
		
		//drop everything and then recreate
		$q= "DELETE FROM $table WHERE $linked_id_column = $linked_id";			
		$res = mysqli_query($link, $q);
		if (!$res) 
		{
			print 'Could not run query: ' . $q;
			exit;
		}
		foreach ( $_POST['type'] as $k => $v) 
		{	
			
			$q= "INSERT INTO $table (staff_type_id,$linked_id_column) VALUES (" . mysqli_real_escape_string($link,$v) . ",$linked_id)";			
			$res = mysqli_query($link, $q);
			if (!$res) 
			{
				print 'Could not run query: ' . $q;
				exit;
			}
		}
	}
	mysqli_close($link);
}
function process_response_data($table,$data_array,$linked_id,$linked_id_column)
{	/*
		Processes through a posted array and inserts/updates responses
		$table = SQL table that data is stored in
		$data_array = posted data array
	*/
	
	$link = db_connect();
if(isset($data_array))
	{
		foreach ( $data_array as $k => $v) 
		{	$x++;
			$correct = "N";
			if ($_POST['correct'] == $x)
			{	$correct = "Y";
			}
			if (isset($_POST['edit']))
			{	$q= "UPDATE $table SET text='" . mysqli_real_escape_string($link,$v) . "', correct='$correct' WHERE id=" . mysqli_real_escape_string($link,$k);}
			else			
			{	$q= "INSERT INTO $table (text,$linked_id_column,correct) VALUES ('" . mysqli_real_escape_string($link,$v) . "',$linked_id,'$correct')";}
			$res = mysqli_query($link, $q);
			//print $q;
			if (!$res) 
			{
				print 'Could not run query: ' . $q;
				exit;
			}
		}
	}
	mysqli_close($link);
}

?>
