<?php 

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

session_start();

$store_id = $_SESSION['id'];   // store id of the currently logged in store
$action = $_POST['action'];    // action field

if($action == 'add'){
	$coupon_name = $_POST['coupon_name'];
	if($coupon_name == ''){
		echo "Enter coupon name";
		exit();
	}

	// handling status
	if($_POST['coupon_status'] == 'active'){
		$coupon_status = 1;
	}
	else $coupon_status = 0;

	// handling type of discount
	if($_POST['type_of_discount'] == 'percent'){
		$type_of_discount = 1;
		$percent_value = $_POST['percent_off'];
		$max_value = $_POST['max_off'];
		$absolute_value = NULL;
	}
	else {
		$type_of_discount = 0;
		$percent_value = NULL;
		$max_value = NULL;
		$absolute_value = $_POST['absolute_off'];
	}

	// handling dates
	if(isset($_POST['date'])){
		$date_status = 1;
		$start_date = new DateTime($_POST['start_date']);
		$end_date = new DateTime($_POST['end_date']);
		if($start_date > $end_date){
			echo "Dates entered are invalid.";
			exit();
		}
		else {
			$start_date = $start_date->format('Y-m-d');
			$end_date = $end_date->format('Y-m-d');
		} 
	}
	else { 
		$date_status = 0;
		$start_date = NULL;
		$end_date = NULL;
	} 

	// handling price limit
	if(isset($_POST['price'])){
		$price_status = 1;
		$min_price = $_POST['start_price'];
		$max_price = $_POST['end_price'];
		if($min_price == '' && $max_price == ''){
			echo "Please enter price limit.";
			exit();
		}
		if($min_price == '') $min_price = NULL;
		if($max_price == '') $max_price = NULL;

		if(($min_price > $max_price) && ($max_price != NULL)){
			echo "Prices entered are invalid.";
			exit();
		}
	}
	else { 
		$price_status = 0;
		$min_price = NULL;
		$max_price = NULL;
	}	

	// handling validity of number of times 
	if(isset($_POST['number'])){
		$number_status = 1;
		$number_of_times = $_POST['valid_number'];
	}
	else {
		$number_status = 0;
		$number_of_times = NULL;
	}

	$coupon_id = $_POST['coupon_id'];
	if($coupon_id == '')  // adding new coupon
		$sql = $mysqli->query("INSERT INTO tblcoupons VALUES ('', '{$store_id}', '{$coupon_name}', '{$type_of_discount}', '{$percent_value}', '{$max_value}', '{$absolute_value}', '{$date_status}', '{$start_date}', '{$end_date}', '{$number_status}', '{$number_of_times}', '{$price_status}', '{$min_price}', '{$max_price}', '{$coupon_status}') ");
	else
		$sql = $mysqli->query("UPDATE tblcoupons SET coupon_name='{$coupon_name}', percent_discount='{$type_of_discount}', percent_value='{$percent_value}', max_value='{$max_value}', absolute_value='{$absolute_value}', date_status='{$date_status}', start_date='{$start_date}', end_date='{$end_date}', number_status='{$number_status}', valid_no_of_times='{$number_of_times}', price_status='{$price_status}',  start_price='{$min_price}', end_price='{$max_price}', coupon_status='{$coupon_status}' WHERE store_id='{$store_id}' AND coupon_id='{$coupon_id}' ");

	if($sql->rowCount() > 0){
		echo "success";
	}	
	else
	    echo "Failed to add coupon.";
	exit();
}
else if($action == 'show_all'){ // show all coupons for a store
	
	$sql = $mysqli->query("SELECT * FROM tblcoupons WHERE store_id='{$store_id}' ");
	$results = array();
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		$results[$row['coupon_id']] = $row;
	}
	echo json_encode($results);
	exit();
}	
else if($action == 'delete'){    // delete coupon with particular id
	$coupon_id = $_POST['coupon_id'];
	$sql = $mysqli->query("DELETE FROM tblcoupons WHERE store_id='{$store_id}' AND coupon_id='{$coupon_id}' LIMIT 1 ");
	if($sql->rowCount() == 0)
		echo "Fail";
	else echo "success";
	exit();
}

?>