<?php

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

$email = $_REQUEST['email'];	
$email = preg_replace('#[^A-Za-z0-9@.]#i', '', $email); 
$md5 = md5($email); // md5 of email so that link can be identified on clicking
$status = 1;

require_once '../vendor/config_mail.php';   // email settings have been configured


$mail->Subject = "Reset Password";
$time = time();
$time = (string)$time;
$mail->Body = "Please click on this link to reset your password. http://localhost/retailstore/view/reset_password.php?id=".$md5."&t=".$time;
$mail->addAddress($email);

// update time of sending mail and the acctivation link status
$sql = $mysqli->query("UPDATE tblstore SET time='{$time}', time_status='1' WHERE store_email='{$email}' LIMIT 1 ");

if($sql->rowCount() == 0)  // the user must have logged in from employee id instead od store id
	$sql = $mysqli->query("UPDATE tblusers SET time='{$time}', time_status='1' WHERE user_email='{$email}' LIMIT 1 ");


if($sql->rowCount() == 0) {  // invalid id
	echo "<script>alert('Invalid email'); location.href= '../view/forgot_password.php';</script>";
}

else {
	if(!$mail->send()) {
		$sql = $mysqli->query("UPDATE tblstore SET time_status='0' WHERE store_email='{$email}' LIMIT 1 ");
		if($sql->rowCount() == 0)  // the user must have looged in from employee id instead od store id
			$sql = $mysqli->query("UPDATE tblusers SET time_status='0' WHERE user_email='{$email}' LIMIT 1 ");

		echo "<script>alert('Failed to send mail. Please submit form again'); location.href= '../view/forgot_password.php';</script>";
	}
	else {
		echo "<script>alert('Email has been sent. Please check.'); location.href= '../view/dashboard.php';</script>";
	}

}

?>
