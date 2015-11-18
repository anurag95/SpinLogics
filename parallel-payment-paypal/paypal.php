<?php

require_once("config_sql.php"); // include the file connecting to the DB

require_once("paypal_class.php");
$p = new Paypal();

$action = $_REQUEST["action"];

switch($action){

	case "success": // success case to show the user payment got success
	
		echo "<h1>Payment Transaction Done Successfully</h1>";
	break;
	
	case "cancel": // case cancel to show user the transaction was cancelled
		echo "<h1>Transaction Cancelled";
	break;

	case "process": // it performs the parallel payment
		$p->splitPay();
 	break;
}

?>