<?php

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

session_start();

$store_id = $_SESSION['id'];   // id of store logged in 
$action = $_POST['action'];   // action to be performed by the script

if($action == 'add'){   // add the bew user
	$email = $_POST['email'];
	$pass = $_POST['pass'];
	$con_pass = $_POST['con_pass'];
	$email = preg_replace('#[^A-Za-z0-9@.]#i', '', $email);

	$message = "";

	if($email=='' || $pass=='' || $con_pass==''){
		$message = "All Fields are neccessary.";
	}
	else if(!filter_var($email,FILTER_VALIDATE_EMAIL))
	{    
		$message = "Invalid email id";
	}
	else if($pass != $con_pass){
		$message = "Passwords do not match.";
	}
	$pass = md5($pass);

	if($message != ''){
		echo $message;
		exit();
	}
	$sql = $mysqli->query("INSERT INTO tblusers VALUES ('{$store_id}', '{$email}', '{$pass}', '', '', '', '') ");
	if($sql->rowCount() == 0){
		echo "Failed to add user.";
		exit();
	}
	echo "success";
	exit();
}