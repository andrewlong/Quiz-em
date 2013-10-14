<!DOCTYPE html>
<html lang="en">
<!--
Quiz-em

https://github.com/andrewlong/Quiz-em

Copyright (c) 2013 Andrew Long

Quiz'em is licensed under the MIT License (MIT)

The MIT License (MIT)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
-->
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Quiz-em</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link href="css/styles.css" rel="stylesheet">
	
	<!-- Bootstrap core JavaScript -->
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    
    <!-- Custom styles for this template -->
   	<style>
   	body {
	padding: 30px;
	}
	
	.navbar {
	margin-bottom: 30px;
	}
   	</style>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <!-- Static navbar -->
      <div class="navbar navbar-default">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href=".">Quiz'em</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
          <?php if ($_SESSION['creator'] == "Y")
          { ?>
            <li><a href="index.php?page=my_quizes">My Quizes</a></li>
            <li><a href="index.php?page=quiz">Create Quiz</a></li>
         <?php } ?>
          <?php if ($_SESSION['admin'] == "Y")
          { ?>
            <li><a href="https://github.com/andrewlong/Quiz-em/blob/master/README.md">Docs</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Management <b class="caret"></b></a>
              <ul class="dropdown-menu">
					<li><a href="index.php?page=user">Create User</a></li>
					<li><a href="index.php?page=user&action=search">User Search</a></li>
					<li><a href="index.php?page=staff_type">Create Staff Types</a></li>
					<li><a href="index.php?page=staff_type&action=search">Staff Type Search</a></li>
					<li><a href="index.php?page=quiz">Create Quiz</a></li>
					<li><a href="index.php?page=quiz&action=search">Quiz Search</a></li>
					<li><a href="index.php?page=user_quiz_completion">Quiz Completion</a></li>
                </ul>
         <?php } ?>
            </li>
           	</ul>
           <ul class="nav navbar-nav navbar-right">
            <li><p class="navbar-text">Signed in as <?php print $_SESSION['display_name']; ?></p></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
         </div><!--/.nav-collapse -->
      </div>