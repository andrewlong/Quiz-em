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
			
			
	
	?>
	<div class="panel panel-default">
  <div class="panel-heading"><h3 class="panel-title"><?php print $row['name'] ?></h3>
	  </div>
   <div class="panel-body">
<form action="index.php?page=grade_quiz" method='post'>		  

<?php
		print "<input type='hidden' name='quiz_id' value='$quiz_id'>";			
		$q= "SELECT id, question FROM question where quiz_id = $quiz_id and active !='N' ORDER BY sort_order";
		
		$res = mysqli_query($link,$q);
		if (!$res) 
		{
			print 'Could not run query: ' . $q;
		    exit;
		}
		while ($row = mysqli_fetch_assoc($res))
		{	$x++;
			//output questions
		?>

	
	   <div class="col-md-10">
		<div class="panel panel-default">
		  <div class="panel-heading">Question <?php print $x; ?></div>
		  <div class="panel-body">
		    <p class="lead">
		    <?php print $row['question']; ?>
		    </p>
		    
		    <?php
			
			print "<input type='hidden' name='question[{$row['id']}]' value='1'>";
			$q= "SELECT * FROM response WHERE question_id = {$row['id']} ORDER BY RAND()";
		
			$res_resp = mysqli_query($link,$q);
			if (!$res_resp) 
			{
				print 'Could not run query: ' . $q;
			    exit;
			}
			print "<table>";
			while ($resp = mysqli_fetch_assoc($res_resp))
			{	
				
				if (!$resp['text'] == "")
				{
					print "<tr><td><input type='radio' name='answer[{$row['id']}]' value='{$resp['id']}' class='form-radio' required>";
					print "</td><td>&nbsp;<strong>{$resp['text']}</strong></td></tr>";
				}
			}
			?>
			</table>
		  </div>
		</div>
	</div>
		<?php
		}
		mysqli_close($link);	
?>

  </div>
  
<div class="panel-footer">
	<button type="submit" class="btn btn-default btn-primary" name="submit" value="1">Submit</button>
</form>
</div>
</div>