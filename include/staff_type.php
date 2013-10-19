      <!-- Main component for a primary marketing message or call to action -->
      <div class="well">    
<?php

$table = 'staff_type';


if (!isset($_POST['search']))
{
	if (isset($_POST['input']))
	{
		$return = process_post_data($table, $_POST['input']);
		print alert('success',$return['msg']);
	}
$input= get_data($table);
print "<form class='form-horizontal' method='post' action='" . get_page_url() . "?page={$_GET['page']}'>\n";
?>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<h4>Staff Type</h4>
		</div>
	</div>
<?php

text_input('input[name]',$input['name'],'text',4);
$yes_no = array(
				array('Y','Yes'),
				array('N','No')
				);
select_input('input[active]',$yes_no,$input[active]);
?>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      
<?php if ($_GET["action"] =="view")
	{	
  	print "<a href='" . get_page_url() . "?page={$_GET['page']}&action=edit&id={$_GET['id']}' class='btn btn-default btn-success'>Edit</a>";
   }
	else if($_GET['action'] == "edit")
   {
   ?>
	<button type="submit" class="btn btn-default btn-primary" name="save" value="1">Update</button>
	<input type="hidden" name="edit" value="1"> 
    <input type="hidden" name="id" value="<?php print $_GET['id']; ?>"> 	 
 <?php }
  	else if($_GET['action'] == "search")
   {
   ?>
	<button type="submit" class="btn btn-default btn-primary" name="save" value="1">Search</button>
	<input type="hidden" name="search" value="1"> 	 
 <?php }
   else
  {	?>
	<button type="submit" class="btn btn-default btn-primary" name="save" value="1">Add</button>
	<input type="hidden" name="new" value="1"> 
  <?php }

?>
<a href="<?php print referer_url();?>" class="btn btn-default">Cancel</a>
    </div>
  </div>
</form>	
<?php
}
else
{
			
			$link = db_connect();
			$buildq = array(); //define array
			if ($_POST["name"])
			{	$partq = "name LIKE '%" . mysqli_real_escape_string($link,$_POST['user_name']) . "%'";
				
				array_push($buildq, $partq);
					
			 }
			if ($_GET["id"])
			{	$partq = "id = " . mysqli_real_escape_string($link,$_GET['id']);
				
				array_push($buildq, $partq);
					
			 }
			$addq = "";
			foreach ($buildq as $k => $v) 
			{
				$x = $x + 1; 
				if ($x == 1)
				{
					$addq = " WHERE " . $v;	//adds WHERE to first search term
				}
				else 
				{
					$addq = $addq . " AND " . $v;  //adds AND to 2nd+ search terms
				}
			
			}

		$q= "SELECT id, name, active FROM staff_type $addq";
		
		process_query("Staff Types",$q,"staff_type");
		mysqli_close($link);	
}
?>
	</div>
</div> <!-- /container -->
   