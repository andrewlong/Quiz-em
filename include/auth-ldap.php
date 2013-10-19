<?php
		//set these to yours
		 // Active Directory server
	    $ldap_host = "ldapserver";
	 
	    // Active Directory DN
	    $ldap_dn = "DC=XXX";

$use_ldap = true; //setup to disable password field when entering new users
if($_SESSION['loggedin']) 
{   //logged in
}
else
{   //not logged in	


	if(isset($_POST['submit'])) //submitted login
	{
	   	
		if (strlen($_POST['password'])==0)
		{
			$auth="Invalid User/Password";
		    include 'include/login.php';
     		exit;
     		die();
		}
		
		
		//first removed any @ and domains
		
		if (strpos($_POST['username'], "@"))
		{
			$_POST['username'] = substr($_POST['username'], 0, strpos($_POST['username'], "@"));
		}
		
		
		$user = $_POST['username'];
	   $password = $_POST['password'];


	 
	 
	    // connect to active directory
	   $ldap = ldap_connect($ldap_host) or die("Could not connect to $ldap_host");
	   //Set protocol version
		ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3)
		or die ("Could not set ldap protocol");
		
		// Set this option for AD on Windows Server 2003 per PHP manual
		ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0)
		or die ("Could not set option referrals");

		$bind = ldap_bind($ldap, $user. "@crm.crmcinc.org", $password);
	   	// verify user and password if bind == true
	    if($bind) 
	    {
	    	$_SESSION['loggedin'] = TRUE;
			$_SESSION['user_name'] = strtolower($user);	
				
			    
			header("location:". get_url());
			
		
	   } else 
	   {
	        // invalid name or password
	       $auth="Invalid User/Password";
		   include 'include/login.php';	//send to login form
     		die();
	   }
	   
	} 
	else
	{
		include 'include/login.php';	//send to login form
		exit(); 
		die(); //kill the page with double tap
	}
}
//if made it to here, previously authenicated 
?>
