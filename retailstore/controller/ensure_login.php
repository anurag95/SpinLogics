<?php

function ensureLoggedOut()   // first thing called on pages which expect user to be logged out, like login, reset password
{
	session_start();
	if(isset($_SESSION['view']))
		header("location: ../view/dashboard.php");
}

function ensureLoggedIn()  // first thing called on pages which expect user to be logged in, like products or department page 
{
	session_start();
	if(!isset($_SESSION['view']))
		header("location: ../view/store_login.php");
}

?>