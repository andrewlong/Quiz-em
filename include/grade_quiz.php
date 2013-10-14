<div class="panel panel-default">
  <div class="panel-heading"><h3 class="panel-title">Results</h3>
	  </div>
   <div class="panel-body">
<?php

$link = db_connect();
$quiz_id = mysqli_real_escape_string($link, $_POST['quiz_id']);
	

$user_id = $_SESSION['user_id'];


$correct_question = 0;
foreach ($_POST['question'] as $k => $v)
{
	$total_questions ++;
	$question = mysqli_real_escape_string($link, $k);
	$answer = mysqli_real_escape_string($link, $_POST['answer'][$k]);
	$q= "SELECT r.correct, q.question, r.text, quiz.passing_score FROM response r
	INNER JOIN question q ON q.id = r.question_id 
	INNER JOIN quiz ON quiz.id = q.quiz_id
	WHERE r.id = $answer";
	$res = mysqli_query($link,$q);
	if (!$res) 
	{
		print 'Could not run query: ' . $q;
	    exit;
	}
	$row = mysqli_fetch_assoc($res);
	$temp_text = "<p>Question:{$row['question']}<br>Answer:";
	if($row['correct'] == "Y")
	{
		$temp_text .= "<span class='text-success'>(Correct)";
		$correct_question ++;
	}
	else
	{
		$temp_text .= "<span class='text-danger'>(Incorrect)";
	}
	$q= "INSERT INTO question_result (quiz_id, question_id, response_id, user_id, correct) 
		VALUES ($quiz_id, $question, '$answer', $user_id, '{$row['correct']}')";
	
	$res = mysqli_query($link,$q);
	if (!$res) 
	{
		print 'Could not run query: ' . $q;
	    exit;
	}
	
	$temp_text .= "&nbsp;{$row['text']}</span></p>";
	$result_text .= $temp_text;
	
}
	$grade = $correct_question / $total_questions * 100;
	$grade = number_format($grade, 0, '.', '');
	
	
	if ($grade >= $row['passing_score'])
	{	$pass = 'Y'; $result_text .= "PASS "; }
	else {	$pass = 'N';$result_text .= "FAIL "; }
	
	$result_text .= "$correct_question/$total_questions $grade%";
	
	$result_text = mysqli_real_escape_string($link, $result_text);
	
	$q= "INSERT INTO quiz_result (quiz_id, user_id, result_text, score, pass) 
		VALUES ($quiz_id, $user_id, '$result_text', $grade, '$pass')";
	
	$res = mysqli_query($link,$q);
	if (!$res) 
	{
		print 'Could not run query: ' . $q;
	    exit;
	}
	
	
	print stripslashes($result_text);
	
		
?>
</div>