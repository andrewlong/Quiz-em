
    <link href="css/styles.css" rel="stylesheet">
      <!-- Main component for a primary marketing message or call to action -->
      <div class="well">    
<?php

$table = 'users';

if (!isset($_POST['search']))
{
	if (isset($_POST['input']))
	{
		$return = process_post_data($table, $_POST['input']);
		process_staff_type_data('user_staff_type',$_POST['type'],$return['id'],'user_id');
		print alert('sucess',$return['msg']);
	}
print "<form class='form-horizontal' method='post' action='" . get_page_url() . "?page={$_GET['page']}'>\n";

$data= get_data($table);

text_input('input[user_name]',$data['user_name'],'text',4);
text_input('input[display_name]',$data['display_name'],'text',4);
text_input('input[password]',$data['password'],'text',4);
$yes_no = array(
				array('N','No'),
				array('Y','Yes')
				);
select_input('input[admin]',$yes_no,$data['admin']);
select_input('input[creator]',$yes_no,$data['creator']);
$yes_no = array(
				array('Y','Yes'),
				array('N','No')
				);
select_input('input[active]',$yes_no,$data['active']);
?>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<h4>Staff Type</h4>
		</div>
	</div>
<?php

$link = db_connect();

$q = "SELECT * FROM staff_type WHERE active ='Y'";

$res = mysqli_query($link, $q);
if (isset($_GET['id']))
{
	$q = "SELECT * FROM user_staff_type WHERE user_id = '" . mysqli_real_escape_string($link,$_GET['id']) . "'";
	$type_res = mysqli_query($link, $q);
}
while($row = mysqli_fetch_assoc($res))
{
	$checked = '';
	if (isset($_GET['id']))
	{	while($type_data = mysqli_fetch_assoc($type_res))
		{	if ($type_data['staff_type_id'] == $row['id'])
			{ $checked = "checked"; }
		}
		mysqli_data_seek($type_res,0);
	}
	text_input("type[{$row['name']}]","{$row['id']}",'checkbox',4,$checked);
}

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
			if ($_POST['input']["user_name"])
			{	$partq = "u.user_name LIKE '%" . mysqli_real_escape_string($link,$_POST['input']['user_name']) . "%'";
				
				array_push($buildq, $partq);
					
			 }
			 if ($_POST['input']["active"])
			{	$partq = "u.active = '" . mysqli_real_escape_string($link,$_POST['input']['active']) . "'";
				
				array_push($buildq, $partq);
					
			 }
			if ($_GET["id"])
			{	$partq = "u.id = " . mysqli_real_escape_string($link,$_GET['id']);
				
				array_push($buildq, $partq);
					
			 }
			if ($_POST['type'])
			{	$in = array();
				foreach ( $_POST['type'] as $k => $v) 
				{
					array_push($in, mysqli_real_escape_string($link,$v));					
				}
				$partq = "ut.staff_type_id IN (" . implode(',',$in) . ")";
					
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

		$q= "SELECT u.id as id, u.user_name, u.display_name,  
				(SELECT GROUP_CONCAT(st.name) from user_staff_type ut 
				INNER JOIN staff_type st ON st.id = ut.staff_type_id
				WHERE ut.user_id = u.id) as staff_type,
				u.creator, u.active FROM users u
				LEFT OUTER JOIN user_staff_type ut ON ut.user_id = u.id
				$addq 
				group by u.id";
		process_query("Users",$q,"user");
		mysqli_close($link);	
}
?>
	</div>
</div> <!-- /container -->
   