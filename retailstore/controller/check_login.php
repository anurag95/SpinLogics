<?php

// check whether login credentails are correct or not

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

$message = false;
$email = @$_POST['email'];
$password = @$_POST['password'];

// password is stored in md5 to protect user
echo md5($password);
$email = preg_replace('#[^A-Za-z0-9@.]#i', '', $email); 
$password = preg_replace('#[^A-Za-z0-9]#i', '', $password);


// storing ip of the computer from which user has logged in
if (!empty($_SERVER['HTTP_CLIENT_IP'])) { 
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}


// storing current date and time of login
$date = date('m/d/Y h:i:s a', time()); 
echo $date;
if($email == "" || $password == "") {
	$message = "Invalid Email ID or Password";
}

else
{
	$password = md5($password);
	$flag = 0;

	// checking tblstore 
	$sql = $mysqli->query("SELECT * FROM tblstore WHERE store_email='{$email}' AND store_password='{$password}' LIMIT 1");
	if($sql->rowCount() == 0){
		$flag = 1;

		// checking tblusers
		$sql = $mysqli->query("SELECT * FROM tblusers WHERE user_email='{$email}' AND user_password='{$password}' LIMIT 1");
	}
	if($sql->rowCount() == 0)
	{
		$message = "Invalid Email ID or Password";
	}
	else
	{
		$row = $sql->fetch(PDO::FETCH_ASSOC);
		session_start();
		$tempid = $row['store_id'];
		$_SESSION['id'] = $row['store_id'];
		if($flag == 0) 
			$_SESSION['email'] = $row['store_email'];
		else 
			$_SESSION['email'] = $row['user_email'];
		$_SESSION['view'] = 1;
		if($flag == 0)  // login was made from id of store
			$mysqli->query("UPDATE tblstore SET ip_address='{$ip}', last_login_time='{$date}' WHERE store_id='{$tempid}' LIMIT 1");
		else // login was made from id of employee belonging to store
			$mysqli->query("UPDATE tblusers SET ip_address='{$ip}', last_login_time='{$date}' WHERE store_id='{$tempid}' AND user_email='{$email}' LIMIT 1");
	}
}

if ($message != false) {
	$url = "../view/store_login.php?status=fail&message=" . $message;
}

else {
	$url = "../view/welcome.php";
}

header("location: " . $url);

?>
