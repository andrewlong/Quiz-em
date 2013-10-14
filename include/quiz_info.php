<div class="panel panel-default">
 <div class="panel-heading"><strong>Quiz Information</strong>
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
			
			print "<h4>{$row['name']}</h4><p>{$row['message']}</p>";
			
			$q= "SELECT id, file_name FROM files where quiz_id = $quiz_id";
			$res = mysqli_query($link,$q);
			if (!$res) 
			{
				print 'Could not run query: ' . $q;
			    exit;
			}
			$total_files = mysqli_num_rows($res);
			if (!$total_files == 0)
			{	print "Files:<br>";
			}
			while ($row = mysqli_fetch_assoc($res))
			{	
				print "<a href='img/view.php?cat=files&file={$row['id']}' title='{$row['file_name']}' target='_blank'>{$row['file_name']}</a><br>";
			}
	?>
	</div>
</div>