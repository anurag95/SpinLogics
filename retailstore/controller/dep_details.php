<?php

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

session_start();

$store_id = $_SESSION['id'];  // id of the store logged in
$action = $_POST['action'];  // the attribute of department to be edited
$dep_id = @$_POST['change_dep_id'];  // the id of the department in cosideration

if($action == 'dep_list'){ //  show department list on product page to choose from
	$query = $mysqli->query("SELECT dep_id, dep_name FROM tbldep WHERE 1");
	$departments = array();
	while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
		$departments[$row['dep_id']] = $row;
	}
	echo json_encode($departments);
	exit();
}
else if($action == 'name'){  // edit name
	
	$dep_name = $_POST['change_dep_name'];
	if($dep_id=='' || $dep_name=='' || $store_id==''){
		echo 'All fields are necessary';
		exit(1);
	}
	$query = $mysqli->query("UPDATE tbldep SET dep_name='{$dep_name}' WHERE store_id='{$store_id}' AND dep_id='{$dep_id}' LIMIT 1 ");
}
else if($action == 'desc'){  // edit description of department
	
	$dep_desc = $_POST['change_dep_desc'];
	if($dep_id=='' || $dep_desc=='' || $store_id==''){
		echo 'All fields are necessary';
		exit(1);
	}
	$query = $mysqli->query("UPDATE tbldep SET dep_desc='{$dep_desc}' WHERE store_id='{$store_id}' AND dep_id='{$dep_id}' LIMIT 1 ");
}
else if($action == 'priority'){  // edit priority of department in product listing
	
	$dep_priority = $_POST['change_dep_priority'];
	if($dep_id=='' || $dep_priority=='' || $store_id==''){
		echo 'All fields are necessary';
		exit(1);
	}
	$query = $mysqli->query("UPDATE tbldep SET dep_priority='{$dep_priority}' WHERE store_id='{$store_id}' AND dep_id='{$dep_id}' LIMIT 1 ");
}
else if($action == 'parent'){  // edit the parent under which this department comes

	$dep_parent = $_POST['change_dep_parent'];
	if($dep_id=='' || $dep_parent=='' || $store_id==''){
		echo 'All fields are necessary';
		exit(1);
	}
	$query = $mysqli->query("UPDATE tbldep SET parent_id='{$dep_parent}' WHERE store_id='{$store_id}' AND dep_id='{$dep_id}' LIMIT 1 ");
}
else {   // invalid action
	$query = NULL;
}

if($query->rowCount() == 0){   // failed
	echo "Failed to update data";
	exit(1);
}
echo 'success';

?>