<?php

// edit general store details like name, address, phone number, website etc.

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

session_start();

// extracting all fields to be edited

$message = false;
$id = @$_SESSION['id'];
$name = @$_REQUEST['name'];
$currency_name = @$_REQUEST['currency'];
$cur_to_points = @$_REQUEST['cur_to_points'];
$points_to_cur = @$_REQUEST['points_to_cur'];
$adddetails = @$_REQUEST['adddetails'];
$website = @$_REQUEST['website'];
$phone = @$_REQUEST['phone'];
$address = @$_REQUEST['address'];
$city = @$_REQUEST['city'];
$state = @$_REQUEST['state'];
$pincode = @$_REQUEST['pincode'];
$country = @$_REQUEST['country'];
  
$points_to_cur = preg_replace('/[^0-9.]/', '', $points_to_cur);
$cur_to_points = preg_replace('/[^0-9.]/', '', $cur_to_points);
$phone = preg_replace('/[^0-9+\- ]/', '', $phone);
$pincode = preg_replace('/[^0-9]/', '', $pincode);

if($cur_to_points=='') $cur_to_points=0;
if($points_to_cur=='') $points_to_cur=0;

// neccessary fields
if($name=='' || $currency_name==''){
	$message = "These fields are neccessary";
}

else
{

	// extracting currency id from name which user has chosen
	$sql = $mysqli->query("SELECT currency_id FROM tblcurrency WHERE currency_name='{$currency_name}' LIMIT 1");
	$result = $sql->fetch(PDO::FETCH_ASSOC);
	$cur_id = $result['currency_id'];
	
	// updating all information except the email
	$sql = $mysqli->query("UPDATE tblstore SET currency_id='{$cur_id}', store_name='{$name}', cur_to_points='{$cur_to_points}', points_to_cur='{$points_to_cur}', store_adddetails='{$adddetails}', store_website='{$website}', store_phone='{$phone}', store_address='{$address}', store_city='{$city}', store_state='{$state}', store_pcode='{$pincode}', store_country='{$country}' WHERE store_id='{$id}' LIMIT 1");
	
	if($sql->rowCount() == 0){
		$message = "Failed to update data";
	} 
}

// failed
if ($message != false) {
	$url = "../view/edit_details?status=fail&message=" . $message;
	header("location: " . $url);
}

else {  // successful
	echo "<script> alert('Details successfully updated'); location.href = '../view/dashboard.php' </script>";
}
	
?>
