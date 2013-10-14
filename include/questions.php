<?php
$table = 'users';


	if (isset($_POST['id']))
	{
		$return = process_post_data($table, $_POST['input']);
		header('Location:' . get_page_url() . "?page=quiz_questions");
		
	}
?>
      <!-- Main component for a primary marketing message or call to action -->
      <div class="well">    
<?php

print "<form class='form-horizontal' method='post' action='" . get_page_url() . "?page={$_GET['page']}'>\n";

$data= get_data($table);

text_input('input[user_name]',$data['user_name'],'text',4);
text_input('input[password]',$data['password'],'text',4);
$yes_no = array(
				array('Y','Yes'),
				array('N','No')
				);
select_input('input[active]',$yes_no,$data[active]);
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
   else
  {	?>
	<button type="submit" class="btn btn-default btn-primary" name="save" value="1">Add</button>
	<input type="hidden" name="new" value="1"> 
  <?php }

?>
    </div>
  </div>
</form>	

	</div>


   