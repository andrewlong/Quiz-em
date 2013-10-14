<?php
if (isset($_POST['year']))//check to see if year is posted
	{	header("location:". get_page_url() . "?page={$_GET['page']}&id={$_GET['id']}&year={$_POST['year']}");
	}

if (isset($_GET['id']))//check to see if id is set
{
	?>
    <link href="css/styles.css" rel="stylesheet">
      <!-- Main component for a primary marketing message or call to action -->
      <div class="well">    

<form id="getquiz" action="<?php print get_url(); ?>" method="post">Select Year
  <select id="year" name="year" class="form-control-xs input-xs col-lg-3">
  <?php 
  	$year = mktime(0, 0, 0, 1, 1, date("Y"));

	while ($year > mktime(0, 0, 0, 1, 1, 2011) ) //first year before start
	{
		print "<option value=\"" . date("Y",$year) . "\">" . date("Y",$year) . "</option>";
		$year = strtotime ( "-1 year" , $year ) ;	
	}
	?>
	</select>&nbsp;<button class="btn btn-primary btn-sm" type="submit">Go</button>
	</form>
	
<?php
	
	$link = db_connect();
	
	if (isset($_GET['year']))//check to see if year is posted
	{	$year = mysqli_real_escape_string($link, $_GET['year']);
	}
	else // if not set to current year
	{	$year = date("Y");
	}
	
	$q= "SELECT quiz.id, quiz.name, users.display_name, qr.pass, qr.datetime, quiz.annual, users.id  as user_id, quiz.due_date FROM quiz 
	INNER JOIN quiz_staff_type qst ON qst.quiz_id = quiz.id
	INNER JOIN user_staff_type ust ON ust.staff_type_id = qst.staff_type_id
	INNER JOIN users ON users.id = ust.user_id
	LEFT OUTER JOIN quiz_result qr ON users.id = qr.user_id AND qr.quiz_id = quiz.id
	AND qr.pass = 'Y'
	WHERE quiz.id = " . mysqli_real_escape_string($link, $_GET['id']) .
	" $addq AND (quiz.due_date between '$year-01-01' AND '$year-12-31' OR quiz.annual = 'Y') group by users.id, quiz.id
	order by users.id, quiz.id";
	
	

$res = mysqli_query($link,$q);
if (!$res) 
{
	print 'Could not run query: ' . $q;
    exit;
}
if (mysqli_num_rows($res) == 0)
{	print "<br><p>No results found</p>";
}
else
{
	?>	
	<table class='table-hover table table-striped table-bordered'>
		<thead>
			<th colspan="3"><center>Quiz Completion Results for <?php print $year; ?></center></th>
			<tr>
				<td>Quiz</td>
				<td>Name</td>
				<td>Status</td>
			</tr>
		</thead>
	<?php
	while ($row = mysqli_fetch_assoc($res))
	{
		$pass = false;
		if ($row['annual'] == "Y")
		{	$q = "SELECT pass, datetime FROM quiz_result WHERE user_id={$row['user_id']} AND quiz_id={$row['id']} AND datetime between '$year-01-01' AND '$year-12-31' AND pass='Y' ORDER BY datetime";
		}
		else
		{	$q = "SELECT pass, datetime FROM quiz_result WHERE user_id={$row['user_id']} AND quiz_id={$row['id']} AND pass='Y' ORDER BY datetime";
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
		
		
		print "<tr><td>{$row['name']}</td>
				<td>{$row['display_name']}</td>";
		if ($pass)
		{ print "<td>Completed {$row_pass['datetime']}</td>";}
		else{ print "<td class='danger'>Incomplete</td>";}
		print "</tr>";
	}	
		mysqli_close($link);	
	
	?>
	</table>
		</div>
	</div> <!-- /container -->
	<?php
	}//end if nysqki_num_rows
}
else //if get id is not set then display all quizes
{
	if ($_SESSION['admin'] != "Y")
	{	exit("You do not have access");
	}
	
	
	?>
	<div class="panel panel-default">
		<div class="panel-heading">My Quizes
		  </div>
		   <div class="panel-body">
	<?php
	$link = db_connect();
	
		$q = "SELECT * FROM quiz ORDER BY  active DESC, annual DESC, due_date DESC";
	
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
	{	print "All Quizes</div><table class='table'>";
		while ($row = mysqli_fetch_assoc($res))
		{
			
			print "<tr><td>{$row['name']}</td><td>Active: {$row['active']}</td>
			<td>Due Date: {$row['due_date']}</td>
			<td>Annual: {$row['annual']}</td>
			<td><a href='index.php?page=user_quiz_completion&id={$row['id']}' class='btn btn-primary btn-xs'>Check Completion</a></td>";
			print "</tr>";
		}
		?>
		  </div> 
	</div>
	<?php		
	}



	
}