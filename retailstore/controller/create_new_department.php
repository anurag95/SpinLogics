<?php 

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

session_start();

$store_id = $_SESSION['id'];  // id of store currently logged in
$action = $_POST['action_new'];  

if($action == 'create'){   // create new department


	// exteacting all fields to be filled while creating new department
	$name = $_POST['create_dep_name'];
	$desc = $_POST['create_dep_desc'];
	$priority = $_POST['create_dep_priority'];
	$parent = $_POST['create_dep_parent'];

	// checking if mandatory fields are filled or not
	if($store_id == ''){
		echo "Failed to create department";
		exit();
	}
	if($name == ''){
		echo "Field Name is neccessary";
		exit();
	}

	$sql = $mysqli->query("INSERT INTO tbldep VALUES ('', '{$store_id}', '{$name}', '{$desc}', '{$priority}', '{$parent}')");
	if($sql->rowCount() > 0){  // successfully added
		echo "success";
		exit();
	}
	else {  // failed
		echo "Failed to create department";
		exit();
	}
}

?>

