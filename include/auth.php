<?php
/*
	include/auth-mysql.php is an example authenication system that uses MySQL, you can use whatever system you want just so long as you 
	set $_SESSION['user_name'], because it is an example we don't do any fancy md5 encyption of passwords and the such.
	
	
	#LDAP authenication
	Simply change to include/auth-mysql.php to include/auth-ldap.php
	and then configure include/auth-ldap.php; Set $ldap_host and $ldap_dn at top

*/
session_start(); // This starts the session which is like a cookie, but it isn't saved on your hdd and is much more secure.

include ("include/auth-mysql.php");

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
	if (mysqli_num_rows($res) == 0) //check and make user is setup - Mainly for LDAP and other authenication not using the Quiz user database for authenication.
	{
		$auth=  "Error user name: {$_SESSION['user_name']} not setup.";
		
	}
	elseif (mysqli_num_rows($res) > 1) //check and make sure only one result is returned
	{
		$auth= "Error duplicate user name";
		
	}
	
	if (isset($auth))
	{	//kill session 
		session_start();
		session_unset();
		session_destroy();
		
		include 'include/login.php';	//send to login form
		exit;
	}

		$row = mysqli_fetch_assoc($res);
		//Set user's rights
		$_SESSION['user_id'] = $row['id'];
		$_SESSION['creator'] = $row['creator'];
		$_SESSION['admin'] = $row['admin'];
		$_SESSION['display_name'] = $row['display_name'];
	
}
	if($link) //close connection if it exists
	{
		mysqli_close($link);
	}	

?>
