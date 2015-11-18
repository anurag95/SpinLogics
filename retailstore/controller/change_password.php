<?php

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

$message = false;
$action = $_REQUEST['action'];  // takes two values - change and reset
if($action == 'change'){    // for changing the password
	session_start();
	$id = $_SESSION['id'];   // id of the store whih wants to change the password
	$email = $_SESSION['email'];
	$flag = 0;

	$cur_pass = @$_REQUEST['cur_password'];
	$new_pass = @$_REQUEST['new_password'];
	$confirm = @$_REQUEST['confirm_password'];

	if($new_pass == "" || $confirm != $new_pass)    //empty passwords or passwords don't match
		$message = "Passwords do not match";
	else {
		$new_pass = md5($new_pass);
		$password = md5($cur_pass);
		$sql = $mysqli->query("SELECT * FROM tblstore WHERE store_id='{$id}' AND store_email='{$email}' AND store_password='{$password}' LIMIT 1");
		if($sql->rowCount() == 0) // checking if old pass is entered right
		{
			$flag = 1;  // one of the employees may have logged in
			$sql = $mysqli->query("SELECT * FROM tblusers WHERE user_email='{$email}' AND user_password='{$password}' LIMIT 1");
			if($sql->rowCount() == 0) // checking if old pass is entered right
			{
				$flag = 2;  // one of the employees may have logged in
				$message = "Invalid password";
			}
		}
		if($flag == 0){
			$sql = $mysqli->query("UPDATE tblstore SET store_password='{$new_pass}' WHERE store_id='{$id}' LIMIT 1");
		}
		else if($flag == 1){
			$sql = $mysqli->query("UPDATE tblusers SET user_password='{$new_pass}' WHERE user_email='{$email}' LIMIT 1");
		}		
		if($sql->rowCount() == 0)
			$message = "Failed to change password";
	}
	// error
	if ($message != false) {
		$url = "../view/change_password.php?status=fail&message=" . $message;
		header("location: " . $url);

	}
	// success
	else {
		echo "<script>alert('Password changed successfully'); location.href= '../view/dashboard.php';</script>";
	}
}

else if($action == 'reset'){   // reset password in case of forgotten password

	// extracting all fields neccessary
	$new_pass = @$_REQUEST['new_password']; 
 	$confirm = @$_REQUEST['confirm_password'];
	$email = @$_REQUEST['email'];  // md5 of email so that we confirm activation link
	$time = @$_REQUEST['time'];  // time at which it was sent 
	$time = (int)$time;
	$cur_time = time();

	$id = 0;
	$e = "";
	$right_email = ""; // the email to which activation link was sent in plaintext

	if($new_pass == "" || $confirm != $new_pass)  // password validation
		$message = "Passwords do not match";
	else {
		$new_pass = md5($new_pass);
		$flag = 0;

		// checking in tblstore
		$sql = $mysqli->prepare("SELECT * FROM tblstore");
		$sql->execute();
		while($row = $sql->fetch(PDO::FETCH_ASSOC) ){

			$id = $row['store_id'];
			$e = $row['store_email'];
			if(md5($e) == $email && $row['time_status'] == 1){
				$flag = 1;
				$right_email = $e;  // assigning right email
				break;
			}
		}
		if($flag == 0){

			// checking in tblusers
			$sql = $mysqli->prepare("SELECT * FROM tblusers");
			$sql->execute();
			while($row = $sql->fetch(PDO::FETCH_ASSOC) ){
				$id = $row['store_id'];
				$e = $row['user_email'];
				if(md5($e) == $email && $row['time_status'] == 1){
					$flag = 2;
					$right_email = $e;  // assigning right email
					break;
				}
			}
		}
		if($flag == 0){  // the link expired
			$message = "Failed to change password.";
		}
		else {
			if($cur_time - $time > 86400) {      // 86400 is seconds in a day, change this number in seconds to set expiry time of link.
				$message = "Link is outdated";
				if($flag == 1)
					$result = $mysqli->query("UPDATE tblstore SET time_status='0' WHERE store_id='{$id}' LIMIT 1");
				else if($flag == 2)
					$result = $mysqli->query("UPDATE tblusers SET time_status='0' WHERE user_email='{$right_email}' LIMIT 1");
			}
			else if((int)$row['time'] != $time){
				$message = "Link is outdated";
			}
			else {
				if($flag == 1)
					$result = $mysqli->query("UPDATE tblstore SET store_password='{$new_pass}' WHERE store_id='{$id}' LIMIT 1");
				else if($flag == 2)
					$result = $mysqli->query("UPDATE tblusers SET user_password='{$new_pass}' WHERE user_email='{$right_email}' LIMIT 1");
				if($result->rowCount() == 0) {
					$message = "Failed to change password";
				}
				else {
					if($flag == 1)
						$result = $mysqli->query("UPDATE tblstore SET time_status='0' WHERE store_id='{$id}' LIMIT 1"); 
					else if($flag == 2)
						$result = $mysqli->query("UPDATE tblusers SET time_status='0' WHERE user_email='{$right_email}' LIMIT 1"); 
				}
			}
		}
	}

	if ($message != false) {
		echo "<script>alert('Password change failed.'); location.href= '../view/store_login.php';</script>";
	}

	else {
		echo "<script>alert('Password changed successfully'); location.href= '../view/store_login.php';</script>";
	}
}

?>
