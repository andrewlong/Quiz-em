<div class="panel panel-default">
	<div class="panel-heading">My Quizes
	  </div>
	   <div class="panel-body">
<?php
$link = db_connect();

	$q = "SELECT * FROM quiz WHERE owner = {$_SESSION['user_id']} order by active desc";

$res = mysqli_query($link,$q);
if (!$res) 
{
	print 'Could not run query: ' . $q;
    exit;
}
if (mysqli_num_rows($res) == 0)
{
	print "No quizes found";
	?>
	   </div>
   <div id="quizes"></div>
 </div> 
</div>
<?php
}
else
{	print "Your Quizes</div><table class='table'>";
	while ($row = mysqli_fetch_assoc($res))
	{
		print "<tr><td>{$row['name']}</td><td>Active:{$row['active']}</td><td><a href='index.php?page=quiz_questions&id={$row['id']}' class='btn btn-primary btn-xs'>Edit Quiz</a></td>
		<td><a href='index.php?page=user_quiz_completion&id={$row['id']}' class='btn btn-primary btn-xs'>Check Completion</a></td>";
		print "</tr>";
	}
	?>
	  </div> 
</div>
<?php		
}
?>

