<?php 

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

session_start();

$id = $_SESSION['id'];
$action = $_POST['action'];

if($action == 'show_all'){  // display customers registered with the store that is currently logged in 

	$sql = $mysqli->query("SELECT customer_id, customer_name, customer_email, customer_phone, customer_points, customer_address, customer_city, customer_state, customer_pcode, customer_country  from tblcustomer WHERE store_id='{$id}' ");
	$result = array();
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		$result[$row['customer_id']] = $row;
	}
	echo json_encode($result);
}

else if($action == 'search'){ // display customers registered with the store that match the search query
	$query = $_POST['search_query'];
	$sql = $mysqli->query("SELECT customer_id, customer_name, customer_email, customer_phone, customer_points, customer_address, customer_city, customer_state, customer_pcode, customer_country  from tblcustomer WHERE customer_name LIKE '%$query%' ");
	if($sql->rowCount() == 0){
		echo "Fail";
		exit();
	}
	$result = array();
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		$result[$row['customer_id']] = $row;
	}
	echo json_encode($result);
}
else if($action == 'delete'){    // delete customer with particular id
	$customer_id = $_POST['customer_id'];
	$sql = $mysqli->query("DELETE FROM tblcustomer WHERE store_id='{$id}' AND customer_id='{$customer_id}' LIMIT 1 ");
	if($sql->rowCount() == 0)
		echo "Fail";
	else echo "success";
	exit();
}

else if($action == 'add'){   // add new customer to the store

	$name = $_POST['name'];
	$email = $_POST['email'];
	$pass = $_POST['pass'];
	$con_pass = $_POST['con_pass'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$country = $_POST['country'];
	$phone = $_POST['phone'];
	$pcode = $_POST['pcode'];
	$email = preg_replace('#[^A-Za-z0-9@.]#i', '', $email);

	$message = "";

	if($name=='' || $email=='' || $pass=='' || $con_pass=='' || $address=='' || $city=='' || $state=='' || $country=='' || $phone=='' || $pcode==''){
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
	$sql = $mysqli->query("INSERT INTO tblcustomer VALUES ('', '{$id}', '{$name}', '{$email}', '{$phone}', '{$pass}', '0', '{$address}', '{$city}', '{$state}', '{$pcode}', '{$country}' ) ");
	if($sql->rowCount() == 0){
		echo "Failed to add user.";
		exit();
	}
	echo "success";
	exit();
}

?>