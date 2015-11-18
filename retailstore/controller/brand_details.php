<?php

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

session_start();

$store_id = $_SESSION['id'];  // id of the store logged in
$action = $_POST['action'];  // the attribute of department to be edited

if($action == 'brand_list'){ //  show department list on product page to choose from
	$query = $mysqli->query("SELECT * FROM tblbrand WHERE 1");
	$brands = array();
	while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
		$brands[$row['brand_id']] = $row;
	}
	echo json_encode($brands);
	exit();
}

?>