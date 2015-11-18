<?php 

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

session_start();

$store_id = $_SESSION['id'];   // store id of the currently logged in store
$action = $_POST['action'];    // action field

if($action == 'show'){
	$sql = $mysqli->query("SELECT * FROM tbl_payment WHERE store_id='{$store_id}' LIMIT 1");
	$row = $sql->fetch(PDO::FETCH_ASSOC);
	echo json_encode($row);
}
else if($action == 'change'){
	$field = $_POST['field'];
	if($field == 'cash')
		$sql = $mysqli->query("UPDATE tbl_payment SET payment_cash = 1-payment_cash WHERE store_id='{$store_id}' LIMIT 1 ");
	else if($field == 'debit')
		$sql = $mysqli->query("UPDATE tbl_payment SET payment_debit = 1-payment_debit WHERE store_id='{$store_id}' LIMIT 1 ");
	else if($field == 'credit')
		$sql = $mysqli->query("UPDATE tbl_payment SET payment_credit = 1-payment_credit WHERE store_id='{$store_id}' LIMIT 1 ");
	else if($field == 'gift')
		$sql = $mysqli->query("UPDATE tbl_payment SET payment_giftcoupons = 1-payment_giftcoupons WHERE store_id='{$store_id}' LIMIT 1 ");
	else if($field == 'misc')
		$sql = $mysqli->query("UPDATE tbl_payment SET payment_misc_enable = 1-payment_misc_enable WHERE store_id='{$store_id}' LIMIT 1 ");

	if($sql->rowCount() == 0)
		echo "Failed to update setting";
	else 
		echo "Updated setting";
}
else if($action == 'change_misc'){
	$misc_data = $_POST['misc_data'];
	$sql = $mysqli->query("UPDATE tbl_payment SET payment_misc = '{$misc_data}' WHERE store_id='{$store_id}' LIMIT 1 ");
	if($sql->rowCount() == 0)
		echo "Failed to update setting";
	else 
		echo "Updated setting";	
}

?>