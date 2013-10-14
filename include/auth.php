<?
/*
	This part below is an example authenication system that uses MySQL, you can use whatever system you want just so long as you 
	pass $_SESSION['user_name'], because it is an example we don't do any fancy md5 encyption of passwords and the such.
	

	I also have a ldap authenication system in the works too which I'll be putting out later.

*/
session_start(); // This starts the session which is like a cookie, but it isn't saved on your hdd and is much more secure.


if(!isset($_SESSION['loggedin']))  //not logged in
{
if(isset($_POST['submit'])) //submitted login
{
	
   $link = db_connect();
   $name = mysqli_real_escape_string($link,$_POST['username']); // The function mysql_real_escape_string() stops hackers!
   $pass = mysqli_real_escape_string($link,$_POST['password']);
   $sql = "SELECT user_name FROM users WHERE user_name = '{$name}' AND password = '{$pass}' AND active = 'Y'";
   //print $sql; exit();
   $mysql = mysqli_query($link,$sql); // This code uses MySQL to get all of the users in the database with that username and password.
   
   if(mysqli_num_rows($mysql) < 1)
   {
   	header("location:?auth=Invalid+User/Password");	//send to login form
   	die();
   } // That snippet checked to see if the number of rows the MySQL query was less than 1, so if it couldn't find a row, the password is incorrect or the user doesn't exist!
   $_SESSION['loggedin'] = "YES"; // Set it so the user is logged in!
   $_SESSION['user_name'] = $name; // Make it so the username can be called by $_SESSION['name']
   	//continue on, authenicated
	header("location:". get_url());//send to requested page
} 
else
{
	include 'include/login.php';	//send to login form
	exit(); 
	die(); //kill the page with double tap...(doesn't really do any thing I think I just used to play to many FPS...)
}
}
//if made it to here, previously authenicated 

/*

	End example, include the rest of this page with whatever auth system you use
	I could combine this with the above but chose not to at this time different authenication systems could be used


*/

//get user id and user rights
if (!isset($_SESSION['user_id']))
{

	if(!$link) //connect to MySQL if not already
	{
		$link = db_connect();
	}
	$q= "SELECT id, creator, admin, display_name FROM users where user_name = '{$_SESSION['user_name']}'";			
	$res = mysqli_query($link, $q);
	if (!$res) 
	{
		print 'Could not run query: ' . $q;
		exit;
	}
	if (mysqli_num_rows($res) > 1) //check and make sure only one result is returned
	{
		print "Error duplicate user name";
		exit;
	}
	else
	{
		$row = mysqli_fetch_assoc($res);
		//Set user's rights
		$_SESSION['user_id'] = $row['id'];
		$_SESSION['creator'] = $row['creator'];
		$_SESSION['admin'] = $row['admin'];
		$_SESSION['display_name'] = $row['display_name'];
	}
}
	if($link) //close connection if it exists
	{
		mysqli_close($link);
	}	

?>
