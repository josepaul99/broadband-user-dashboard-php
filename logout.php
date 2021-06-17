<?php
session_start();
if(isset($_SESSION['usr']) & isset($_SESSION['pwd']) & isset($_SESSION['isp']) & isset($_SESSION['name']))
{
   
// remove all session variables
session_unset(); 

// destroy the session 
session_destroy(); 
    //logged out successfully message
	header('Location: /login.php?err=los');
	exit;
}
	
else
{
	//login to view dashboard message
	header('Location: /login.php?err=ltc');
	exit;
}
?>
