<?php

require_once("config_sql.php"); // include the file connecting to the DB

define('EMAIL_ADD', 'gupta.anu1995-buyer@gmail.com'); // define any notification email
define('PAYPAL_EMAIL_ADD', 'gupta.anu1995-facilitator@gmail.com'); // 1st facilitator email which will receive payments 
define('PAYPAL_EMAIL_ADD2', 'gupta@students.iiit.ac.in'); // 2nd facilitator email which will receive payments change this email to a live paypal account id when the site goes live
require_once("paypal_class.php");
$p = new Paypal();

$action = $_REQUEST["action"];

switch($action){

	case "success": // success case to show the user payment got success
	echo '<title>Payment Done Successfully</title>';
	echo '<style>.as_wrapper{
	font-family:Arial;
	color:#333;
	font-size:14px;
	padding:20px;
	border:2px dashed #17A3F7;
	width:600px;
	margin:0 auto;
	}</style>
	';
		echo '<div class="as_wrapper">';
		echo "<h1>Payment Transaction Done Successfully</h1>";
		echo '<h4>Use this below URL in paypal sandbox IPN Handler URL to complete the transaction</h4>';
		echo '<h3>http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?action=ipn</h3>';
		echo '</div>';
	break;
	
	case "cancel": // case cancel to show user the transaction was cancelled
		echo "<h1>Transaction Cancelled";
	break;

	case "process": // it performs the parallel payment
		$p->splitPay();
 	break;
}

?>