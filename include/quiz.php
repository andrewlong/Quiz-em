<?php
if ($_SESSION['creator'] != "Y") //check for access
{	exit("No access defined");
}

if (isset($_GET['id']))
{
	$link = db_connect();
	$id = mysqli_real_escape_string($link, $_GET['id']);
	$q = "SELECT owner FROM quiz WHERE id=$id";
			
	$res = mysqli_query($link,$q);
	if (!$res) 
	{
		print 'Could not run query: ' . $q;
	    exit;
	}
	$row = mysqli_fetch_assoc($res);
	if ($row['owner'] != $_SESSION['user_id']&& $_SESSION['admin'] != "Y")
	{
		exit("You do not have access to this quiz");	
	}
}

$table = 'quiz';

if (!isset($_POST['search']))
{
	if (isset($_POST['input']))
		{
			$return = process_post_data($table, $_POST['input']);
			process_staff_type_data('quiz_staff_type',$_POST['type'],$return['id'],'quiz_id');
			header("Location: " . get_base_url() . "/quiz/?page=quiz_questions&id={$return['id']}");
		}
	$url = '';
	if (isset($_GET['id']))
	{	$url = "&action=view&id=" . $_GET['id'];
	}
?>

      <!-- Main component for a primary marketing message or call to action -->
      <div class="well">   
      <?php
print "<form class='form-horizontal' method='post' action='" . get_page_url() . "?page={$_GET['page']}$url'>\n";
?>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<h4>Quiz</h4>
		</div>
	</div>
<?


$data= get_data($table);

text_input('input[name]',$data['name'],'text',4);
if($_GET['action'] == "search")
{}
else{
text_input('input[message]',$data['message'],'text',4);
text_input('input[start_date]',$data['start_date'],'date',4);
text_input('input[due_date]',$data['due_date'],'date',4);
$required="required";  //set required fields for edit and new
text_input('input[passing_score]',$data['passing_score'],'number',4,$required);
}

if(isset($_GET['action']))
{	
	$q = "SELECT id, user_name FROM users WHERE active = 'Y' and creator = 'Y'";
	select_input('input[owner]',$q,$data[owner]);
}
else
{	print "<input type='hidden' name='input[owner]' value='{$_SESSION['user_id']}'>";
}
$no_yes = array(
				array('N','No'),
				array('Y','Yes')
				);

select_input('input[annual]',$no_yes,$data[annual]);
$yes_no = array(
				array('Y','Yes'),
				array('N','No')
				);
select_input('input[active]',$yes_no,$data[active]);
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
	$q = "SELECT * FROM quiz_staff_type WHERE quiz_id = '" . mysqli_real_escape_string($link,$_GET['id']) . "'";
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
  	print "<a href='" . get_page_url() . "?page={$_GET['page']}&action=edit&id={$_GET['id']}' class='btn btn-default btn-success'>Edit Quiz Info</a>";
  	print "&nbsp;<a href='" . get_page_url() . "?page=question&action=edit&quiz_id={$_GET['id']}' class='btn btn-default btn-success'>Edit Quiz Questions</a>";
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
&nbsp;<a href="<?php print referer_url();?>" class="btn btn-default">Cancel</a>
    </div>
  </div>
</form>	
<?php
	if($_GET['action'] == "edit" || $_GET['action'] == "view")
	{
		$_SESSION["table"] = "lease_images";
	    $_SESSION["id"] = $_GET['id'];
	    $_SESSION["constraint"] = "lease_id";
		include 'include/upload.php';
	}


}
else
{
			
			$link = db_connect();
			$buildq = array(); //define array
			if ($_POST['input']["user_name"])
			{	$partq = "q.name LIKE '%" . mysqli_real_escape_string($link,$_POST['input']['name']) . "%'";
				
				array_push($buildq, $partq);
					
			 }
			 if ($_POST['input']["active"])
			{	$partq = "q.active = '" . mysqli_real_escape_string($link,$_POST['input']['active']) . "'";
				
				array_push($buildq, $partq);
					
			 }
			if ($_GET["id"])
			{	$partq = "q.id = " . mysqli_real_escape_string($link,$_GET['id']);
				
				array_push($buildq, $partq);
					
			 }
			if ($_POST['type'])
			{	$in = array();
				foreach ( $_POST['type'] as $k => $v) 
				{
					array_push($in, mysqli_real_escape_string($link,$v));					
				}
				$partq = "qst.staff_type_id IN (" . implode(',',$in) . ")";
					
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

		$q= "SELECT q.id as id, q.name, 
				(SELECT GROUP_CONCAT(st.name) from quiz_staff_type qst 
				INNER JOIN staff_type st ON st.id = qst.staff_type_id
				WHERE qst.quiz_id = q.id) as staff_type,
				q.active FROM quiz q
				LEFT OUTER JOIN quiz_staff_type qst ON qst.quiz_id = q.id
				$addq 
				group by q.id";
		process_query("Quiz",$q,"quiz_questions");
		mysqli_close($link);	
}
?>
	</div>
</div> <!-- /container -->
   