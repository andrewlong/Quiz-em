
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz-em Signin</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

  </head>

  <body>

    <div class="container">

      <form class="form-signin" method="post">
      <? if (isset($_GET['auth'])) {print $_GET['auth']; }?>
        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="text" name="username" class="form-control" placeholder="Username" autofocus>
        <input type="password" name="password" class="form-control" placeholder="Password">
        <button name="submit" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>
    </div>
  </body>
</html>
