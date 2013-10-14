<div class="panel panel-default">
  <div class="panel-heading"><strong>Questions</strong>&nbsp;&nbsp;&nbsp;Click and drag to reorder questions.
	  <a data-toggle="modal" class="btn btn-primary btn-xs pull-right" href="include/modal_question.php?quiz_id=<?php print $_GET['id']; ?>" data-target="#remoteModal">Add Question</a>
	  <div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true"></div>
  </div>
   <div class="panel-body">
		  
<?php
include( "functions.php" ); 
include( "auth.php" ); 
?>
<div class="row" id="sortable">

<?php
		$link = db_connect();
		
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
		
		
		
			
		$quiz_id=mysqli_real_escape_string($link, $_GET['id']);
			
		$q= "SELECT id, question FROM question where quiz_id = $quiz_id and active !='N' ORDER BY sort_order";
		
		$res = mysqli_query($link,$q);
		if (!$res) 
		{
			print 'Could not run query: ' . $q;
		    exit;
		}
		$total_questions = mysqli_num_rows($res);
		if ($total_questions == 0)
		{	print "<p>&nbsp;If you want to make the quiz harder add some questions</p>";
		}
		while ($row = mysqli_fetch_assoc($res))
		{	$x++;
			//output questions
		?>
	
	   <div class="col-md-10">
		<div class="panel panel-default">
		  <div class="panel-heading"><span class="number"></span>
		  <?php
		  print "<input type='hidden' class='question-order' name='order[{$row['id']}]' value=1>";
		  print "&nbsp;&nbsp;&nbsp;<a data-toggle='modal' class='btn btn-primary btn-xs' href='include/modal_question.php?question_id={$row['id']}&quiz_id={$_GET['id']}' data-target='#remoteModal{$row['id']}'>Edit</a>&nbsp;&nbsp;";
		  print "<span class='saving-order'></span>"; //span to show saving order message to user
		  print "<a data-toggle='modal' class='btn btn-danger btn-xs pull-right' href='include/modal_question.php?quiz_id={$row['id']}question_id={$row['id']}modal_question.php?quiz_id={$row['id']}&question_id={$row['id']}&delete=1' data-target='#deleteModal{$row['id']}'>Delete</a>";
		  print "<div class='modal fade' id='remoteModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='remoteModalLabel{$row['id']}' aria-hidden='true'></div>";
		print "<div class='modal fade' id='deleteModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='remoteModalLabel{$row['id']}' aria-hidden='true'></div>";

		  ?></div>
		  <div class="panel-body">
		    <p class="lead">
		    <?php print $row['question']; ?>
		    </p>
		    
		    <?php

			$q= "SELECT * FROM response WHERE question_id = {$row['id']} ORDER BY correct DESC";
		
			$res_resp = mysqli_query($link,$q);
			if (!$res_resp) 
			{
				print 'Could not run query: ' . $q;
			    exit;
			}
			while ($resp = mysqli_fetch_assoc($res_resp))
			{	$q= "SELECT COUNT(id) as chosen, (SELECT COUNT(id) FROM question_result WHERE question_id = {$row['id']}) as total 
						FROM question_result qr WHERE response_id = {$resp['id']}";
				
				$stat_resp = mysqli_query($link,$q);
				if (!$stat_resp) 
				{
					print 'Could not run query: ' . $q;
				    exit;
				}
				$stat = mysqli_fetch_assoc($stat_resp);
				$stats_per_response = '';
				if ($stat['total'] != 0)
				{	
					$number = $stat['chosen'] / $stat['total'] * 100;
					$number = number_format($number, 0, '.', '');
					$stats_per_response = " [$number%]";
				}
				if (!$resp['text'] == "")
				{	$correct = '';
					if($resp['correct'] == "Y")
					{ $class="text-success"; $correct = "(CORRECT)";}
					else {$class="text-danger";}
					print "<p class='$class'><strong>$correct {$resp['text']} $stats_per_response</strong></p>";
				}
			}
			?>

		  </div>
		</div>
	</div>

		<?php
		}
		mysqli_close($link);	
print "</div></div>";
if ($total_questions > 2)
{
?>
<div class="panel-footer">
<a data-toggle="modal" class="btn btn-primary" href="include/modal_question.php?quiz_id=<?php print $_GET['id']; ?>" data-target="#remoteModal">Add Question</a>
</div>
<?php
}
?>
</div>
<script>
    $(document).ready(function() { 
        $("#sortable").sortable({  
            update : function () { 
            
				    // serialize the data from the question-order class attached to the hidden input fields
				    var serializedData = $(".question-order").serialize();
				    
				    //update question numbering	    
					  $(".number").each(function(i, n) {
					    $(this).html("<span>Question #" + (i+1) + "</span> ");
					  });
					
					//post the data
                    request = $.ajax({
				        url: "include/reorder.php",
				        type: "post",
				        data: serializedData });
				    //show a message to user
				    $( ".saving-order" ).html( "Saving Order..." );
				    setTimeout(function (){
				         //delay showing refreshed page
						$( ".saving-order" ).html("");
				    }, 600);
				    
					  
					
            } 
        });
        
		//Start question numbering
        $(".number").each(function(i, n) {
			$(this).html("<span>Question #" + (i+1) + "</span> ");
		});
    }); </script>
