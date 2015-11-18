<?php

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

session_start();

$id = @$_SESSION['id'];   // id of the store logged in

$action = $_POST['action']; // change tax information or add a new tax or show taxes

if($action == 'show'){     // show tax informmation for a department

	$dep_id = $_POST['dep_id'];

	$sql = $mysqli->query("SELECT * FROM tbl_dep_map, tbltaxrate WHERE dep_id='{$dep_id}' AND tbl_dep_map.tax_id = tbltaxrate.tax_id ");
	$tax = array();
	while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
		$tax[$row['map_id']] = $row;
	}
	echo json_encode($tax);
}

else if($action == 'change'){    // enable or diable a particular tax in a department
	$dep_id = $_POST['dep_id'];
	$tax_id = $_POST['tax_id'];

	$sql = $mysqli->query("UPDATE tbl_dep_map SET status = 1-status WHERE dep_id={$dep_id} AND tax_id = '{$tax_id}' ");
	if($sql->rowCount() == 0)
		echo "Failed to change tax information";
	else 
		echo "Updated setting";
}

else if($action == 'add'){   // add new tax to a department
	$dep_id = $_POST['dep_for_tax'];
	$tax_name = $_POST['tax_name'];
	$tax_value = $_POST['tax_value'];
	$tax_type = $_POST['tax_type'];

	$sql = $mysqli->query("INSERT INTO tbltaxrate VALUES ('', '{$tax_name}', '{$tax_type}', '{$tax_value}') ");
	if($sql->rowCount() == 0){
		echo "Failed to add tax.";
		exit();
	}
	$sql = $mysqli->query("SELECT MAX(tax_id) from tbltaxrate");
	$row = $sql->fetch(PDO::FETCH_ASSOC);
	$max = $row['MAX(tax_id)'];
	$mysqli->query("INSERT INTO tbl_dep_map VALUES ('', '{$dep_id}', '{$max}', '0') ");
	echo "success";
}

?>