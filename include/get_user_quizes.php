<?php
include( "functions.php" ); 
include( "auth.php" ); 
?>
<table class="table">
<?php
$link = db_connect();
$year = mysqli_real_escape_string($link,$_POST['year']);

if ($year == date("Y")) //check if current year
{
	$q = "SELECT quiz.id, quiz.name, quiz.due_date, quiz.annual FROM quiz 
			INNER JOIN quiz_staff_type qst ON qst.quiz_id = quiz.id
			INNER JOIN user_staff_type ust ON ust.staff_type_id = qst.staff_type_id
			INNER JOIN users ON users.id = ust.user_id
			AND users.id = {$_SESSION['user_id']}
			AND (quiz.due_date between '$year-01-01' AND '$year-12-31' OR quiz.annual = 'Y')
			AND quiz.active='Y' 
			GROUP BY quiz.id";
}
else
{
	$q = "SELECT quiz.id, quiz.name, quiz.due_date FROM quiz 
			INNER JOIN quiz_result qr ON qr.quiz_id = quiz.id
			AND qr.user_id = {$_SESSION['user_id']}
			AND qr.pass = 'Y'
			AND qr.datetime between '$year-01-01' AND '$year-12-31'
			GROUP BY quiz.id";
}
$res = mysqli_query($link,$q);
if (!$res) 
{
	print 'Could not run query: ' . $q;
    exit;
}

if (mysqli_num_rows($res) == 0)
{	print "<tr><td>No quizes for year $year.</td></tr>";
}

while ($row = mysqli_fetch_assoc($res))
{
	$pass = false;
	if ($row['annual'] == "Y")
	{	$q = "SELECT pass, datetime FROM quiz_result WHERE user_id={$_SESSION['user_id']} AND quiz_id={$row['id']} AND datetime between '$year-01-01' AND '$year-12-31' AND pass='Y' ORDER BY datetime";
	}
	else
	{	$q = "SELECT pass, datetime FROM quiz_result WHERE user_id={$_SESSION['user_id']} AND quiz_id={$row['id']} AND pass='Y' ORDER BY datetime";
	}		
	$res_pass = mysqli_query($link,$q);
	if (!$res_pass) 
	{
		print 'Could not run query: ' . $q;
	    exit;
	}
	$row_pass = mysqli_fetch_assoc($res_pass);
	if ($row_pass['pass'] == "Y")
	{ $pass = true; }
	else { $pass = false; }
	
	$due_date = date("F j",strtotime($row['due_date']));
	
	print "<tr><td>{$row['name']}</td>
			<td><a href='index.php?page=quiz_info&id={$row['id']}' class='btn btn-primary btn-xs'>Quiz Infomation</a></td>
			<td>Due: $due_date</td>";
	if ($pass)
	{ print "<td>Completed {$row_pass['datetime']}</td>";}
	else{ print "<td><a href='index.php?page=take_quiz&id={$row['id']}' class='btn btn-primary btn-xs'>Take Quiz</a></td>";}
	print "</tr>";
}
		
		



?>
</table>

