<?php
include( "functions.php" ); 
include( "auth.php" ); 

		$table = 'question';
		
		if (isset($_POST['question']['quiz_id']))
		{	if ($_SESSION['creator'] != "Y") //check for access
			{	exit("No access defined");
			}
			
				$link = db_connect();
				$id = mysqli_real_escape_string($link, $_POST['question']['quiz_id']);
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
		
		
		
		if (isset($_POST['question'])) //just process the data and exit.
			{
				$return = process_post_data($table, $_POST['question']);
				
				process_response_data('response',$_POST['response'],$return['id'],'question_id');
				
				exit;
			}
		if (isset($_POST['deact'])) //just process the data and exit.
			{	
				$link = db_connect();
				
				$q = "UPDATE question SET active='N' WHERE id = '" . mysqli_real_escape_string($link,$_POST['id']) . "'";
				$res = mysqli_query($link, $q);
				
				if (!$res) 
				{
					print 'Could not run query: ' . $q;
					exit;
				}

				exit;
			}
		
$data= get_data($table,$_GET['question_id']);
?>
<script>
// variable to hold request
var request;
// bind to the submit event of our form
$("#edit-question").submit(function(event){
    // abort any pending request
    if (request) {
        request.abort();
    }
    // setup some local variables
    var $form = $(this);
    // let's select and cache all the fields
    var $inputs = $form.find("input, select, button, textarea");
    // serialize the data in the form
    var serializedData = $form.serialize();

    // let's disable the inputs for the duration of the ajax request
    $inputs.prop("disabled", true);

    // fire off the request to /form.php
    request = $.ajax({
        url: "include/modal_question.php",
        type: "post",
        data: serializedData
    });

    // callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
        // log a message to the console
        console.log("Hooray, it worked!");
		$('.modal').modal('hide');
    });

    // callback handler that will be called on failure
    request.fail(function (jqXHR, textStatus, errorThrown){
        // log the error to the console
        console.error(
            "The following error occured: "+
            textStatus, errorThrown
        );
    });

    // callback handler that will be called regardless
    // if the request failed or succeeded
    request.always(function () {
        // reenable the inputs
        $inputs.prop("disabled", false);
    });

    // prevent default posting of form
    event.preventDefault();
});

</script>
<?php
if (isset($_GET['delete'])) //just process the data and exit.
{	
?>	
	    <div class="modal-dialog">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >&times;</button>
		          <h4 class="modal-title">Confim Deletion</h4>
		        </div>
		        <div class="modal-body">	
				<form class='form-horizontal' id='edit-question'>
				Are you sure?
				</div>
		        <div class="modal-footer">
		        
				<input type="hidden" name="id" value="<?php print $_GET['question_id']; ?>"> 
				<input type="hidden" name="deact" value="1"> 
		          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-default btn-danger" name="drop" value="1" id="submit">Yes, Delete It!</button>
		        </div>
		      
					</form>	
				</div><!-- /.modal-content -->
		    </div><!-- /.modal-dialog -->
	<?php
	
	
}
else
{
	if (isset($_GET['question_id']))
	{ $text = "Edit"; }
	else { $text = "New"; }
				?>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >&times;</button>
          <h4 class="modal-title"><?php print $text; ?> Question</h4>
        </div>
        <div class="modal-body">	
		<form class='form-horizontal' id='edit-question'>
		<table class="table table-condensed">
				<thead>
					<tr>
						<th colspan="2">
							Question:<br>
							<input type="text" class="form-control" name="question[question]" placeholder="Enter question" value="<?php print $data['question']; ?>">
						</th>
					</tr>
				</thead>
<?php


if (isset($_GET['question_id']))
{	print "<input type='hidden' name='edit' value='1'>";
	$link = db_connect();

	$q = "SELECT * FROM response WHERE question_id = '" . mysqli_real_escape_string($link,$_GET['question_id']) . "' ORDER BY correct DESC";
	$res = mysqli_query($link, $q);
	
	while($row = mysqli_fetch_assoc($res))
	{	$x++;
		$checked = '';
		if ($row['correct'] == "Y")
				{ $checked = "checked"; }
		?>
		<tr>
						<td>
							<input type="radio" name="correct" value="<?php print $x; ?>" <?php print $checked; ?> class="form-radio" required>
						</td>
						<td>
							<input type="text" class="form-control" name="response[<?php print $row['id']; ?>]" value="<?php print $row['text']; ?>">
						</td>
		</tr>
<?php }
}
else
{	$x=0;
	while($x<4)
	{	$x++;
			?>
		<tr>
						<td>
							<input type="radio" name="correct" value="<?php print $x; ?>" class="form-radio" required>
						</td>
						<td>
							<input type="text" class="form-control" name="response[]" value="<?php print $data['sssss']; ?>">
						</td>
		</tr>
<?php }
}	

	if(isset($_GET['question_id']))
   {	print "<input type='hidden' name='edit' value='1'> ";
   ?>
   
	<input type="hidden" name="id" value="<?php print $_GET['question_id']; ?>"> 	 
<?php   }
  else
  {	?>
	<input type="hidden" name="new" value="1">
	<input type="hidden" name="question[sort_order]" value="99999">
  <?php } //set inital sort_order to 99999 so that it shows up at end
?>		
		
	<input type="hidden" name="question[quiz_id]" value="<?php print $_GET['quiz_id']; ?>"> 
		</table>
        </div>
        <div class="modal-footer"> <div class="pull-left">Response order will be randomized on Quiz generation for user</div>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<button type="submit" class="btn btn-default btn-primary" name="save" value="1" id="submit">Save</button>
        </div>
      
			</form>	
		</div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

<?php  } ?>
