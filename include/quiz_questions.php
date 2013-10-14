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
?>
<script>
$('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
   $( ".saving-order" ).html( "Updating..." );
   $('.btn').bind('click', false); //disable buttons while updating
    setTimeout(function (){
         //delay showing refreshed page
		$.get("include/get_quiz.php?id=<?php print $_GET['id']; ?>",function(data,status){
			$( "#result" ).html( data );
		});
    }, 400); //delay XXX milliseconds 
    ('.btn').unbind('click', false); //reenable buttons
});
//loads remote results on inital page load
$(document).ready(function(){
    $.get("include/get_quiz.php?id=<?php print $_GET['id']; ?>",function(data,status){
      $( "#result" ).html( data );
    });
});
</script>
<div class="panel panel-default">
 <div class="panel-heading"><strong>Quiz Information</strong>
 <a href='?page=quiz&action=edit&id=<?php print $_GET['id']; ?>' class='btn btn-default btn-xs btn-success pull-right'>Edit Quiz Info</a>
 </div>
   <div class="panel-body">
	<?php
	
			$link = db_connect();
			
			$quiz_id=mysqli_real_escape_string($link, $_GET['id']);
			
			$q= "SELECT * FROM quiz where id = $quiz_id";
			$res = mysqli_query($link,$q);
			if (!$res) 
			{
				print 'Could not run query: ' . $q;
			    exit;
			}
			$row = mysqli_fetch_assoc($res);
			
			//output quiz info
			
			print "<h4>{$row['name']}</h4><p>{$row['message']}</p>Files:<br>";
			
			$q= "SELECT id, file_name FROM files where quiz_id = $quiz_id";
			$res = mysqli_query($link,$q);
			if (!$res) 
			{
				print 'Could not run query: ' . $q;
			    exit;
			}
			$total_files = mysqli_num_rows($res);
			if ($total_files == 0)
			{	print "No files added, to add click edit quiz info and use the file management at the bottom";
			}
			while ($row = mysqli_fetch_assoc($res))
			{	
				print "<a href='img/view.php?cat=files&file={$row['id']}' title='{$row['file_name']}' target='_blank'>{$row['file_name']}</a><br>";
			}
	?>
	</div>
</div>



      <!-- Main component for a primary marketing message or call to action -->
      <div id="result">    

	
	</div>
   