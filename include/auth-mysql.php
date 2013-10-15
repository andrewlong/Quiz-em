<?php

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
?>
