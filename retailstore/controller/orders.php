<?php

// display orders served by a store 

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

session_start();

$id = $_SESSION['id'];  // id of the store currently logged in
$action = $_POST['action'];  // action to be performed by the script

if($action == 'show_all'){  // show list of all orders placed by the customers on the store

	$sql = $mysqli->query("SELECT order_id, order_date, customer_name, total_amount, order_status FROM tblorders WHERE store_id='{$id}' ");
	$result = array();
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		switch ($row['order_status']) {  // replacing order status number by "number-statusname"
			case '1':
				$row['order_status'] = '1-Placed';   
				break;
			case '2':
				$row['order_status'] = '2-Pending';
				break;
			case '3':
				$row['order_status'] = '3-Packed';
				break;
			case '4':
				$row['order_status'] = '4-Dispatched';
				break;
			case '5':
				$row['order_status'] = '5-Delivered';
				break;
			case '6':
				$row['order_status'] = '6-Cancelled';
				break;
		}
		$result[$row['order_id']] = $row;
	}
	echo json_encode($result);
}

else if($action == 'show'){  // show order with given id in detail, i.e. the invoice

	$order_id = $_POST['order_id'];
	$sql = $mysqli->query("SELECT * FROM tblorders WHERE store_id='{$id}' AND order_id='{$order_id}' LIMIT 1");
	if($sql->rowCount() == 0)
	{
		echo "Fail";
		exit();
	}
	$rows = $sql->fetch(PDO::FETCH_ASSOC);
	$row = array();
	$row = $rows;
	
	switch ($row['payment_type']) {   // converting payment type number to name
		case '1':
			$row['payment_type'] = 'Cash';
			break;
		case '2':
			$row['payment_type'] = 'Debit Card';
			break;
		case '3':
			$row['payment_type'] = 'Credit Card';
			break;
		case '4':
			$row['payment_type'] = 'Gift Coupon';
			break;
		case '5':
			$row['payment_type'] = 'Miscellaneous';
			break;
	}
	switch ($row['order_status']) {   // converting order status number to name
		case '1':
			$row['order_status'] = 'Placed';
			break;
		case '2':
			$row['order_status'] = 'Pending';
			break;
		case '3':
			$row['order_status'] = 'Packed';
			break;
		case '4':
			$row['order_status'] = 'Dispatched';
			break;
		case '5':
			$row['order_status'] = 'Delivered';
			break;
		case '6':
			$row['order_status'] = 'Cancelled';
			break;
	}
	$tracker_id = $row['tracker_id'];

	// extracting tracker name from tracker_id
	$query = $mysqli->query("SELECT tracker_name FROM tbltracker WHERE tracker_id='{$tracker_id}' LIMIT 1");
	$result = $query->fetch(PDO::FETCH_ASSOC);
	$row['tracker_id'] = $result['tracker_name'];

	// appending the actual list of items bought
	$query = $mysqli->query("SELECT * FROM tblinvoice WHERE order_id='{$order_id}' ");
	while($result = $query->fetch(PDO::FETCH_ASSOC)){
		$row[$result['invoice_id']] = $result;
	}

	echo json_encode($row);
}
else if($action == 'change_status'){   // change status of an order
	$order_id = $_POST['order_id'];
	$order_status = $_POST['order_status'];

	$sql = $mysqli->query("UPDATE tblorders SET order_status='{$order_status}' WHERE order_id='{$order_id}' AND store_id='{$id}' LIMIT 1 ");
	if($sql->rowCount() == 0){
		echo "Failed To update status";
	}
	else echo "Status updated successfully";
	exit();
}

?>
