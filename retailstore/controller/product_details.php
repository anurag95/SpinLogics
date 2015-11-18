<?php

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

session_start();

$store_id = $_SESSION['id'];   // id of store logged in 
$action = $_POST['action'];   // action stands for field which has to be updated
$item_id = @$_POST['change_product_id'];  // id of the product whose field is to be updated

if($action == 'show'){   // show all information of a product
	$information = array();
	$query = $mysqli->query("SELECT * FROM tblitems WHERE store_id='{$store_id}' AND item_id='{$item_id}' LIMIT 1 ");
	if($row = $query->fetch(PDO::FETCH_ASSOC)){
		$information[$row['item_id']] = array($row['item_name'], $row['item_desc'], $row['item_code']);
		echo json_encode($row);
		exit();
	}
}
else if($action == 'show_colour'){
	$query = $mysqli->query("SELECT * FROM tblcolour WHERE 1");
	$result = array();
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$result[$row['colour_id']] = $row;
	}
	echo json_encode($result);
	exit();
}
else if($action == 'show_size'){
	$query = $mysqli->query("SELECT * FROM tblsize WHERE 1");
	$result = array();
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$result[$row['size_id']] = $row;
	}
	echo json_encode($result);
	exit();
}
else if($action == 'name'){  // edit name 
	
	$product_name = $_POST['change_product_name'];
	if($item_id=='' || $product_name=='' || $store_id==''){
		echo 'All fields are necessary';
		exit(1);
	}
	$query = $mysqli->query("UPDATE tblitems SET item_name='{$product_name}' WHERE store_id='{$store_id}' AND item_id='{$item_id}' LIMIT 1 ");
}
else if($action == 'desc'){  // edit description 
	
	$product_desc = $_POST['change_product_desc'];
	if($item_id=='' || $product_desc=='' || $store_id==''){
		echo 'All fields are necessary';
		exit(1);
	}
	$query = $mysqli->query("UPDATE tblitems SET item_desc='{$product_desc}' WHERE store_id='{$store_id}' AND item_id='{$item_id}' LIMIT 1 ");
}
else if($action == 'code'){  // edit item code 
	
	$product_code = $_POST['change_product_code'];
	if($item_id=='' || $product_code=='' || $store_id==''){
		echo 'All fields are necessary';
		exit(1);
	}
	$query = $mysqli->query("UPDATE tblitems SET item_code='{$product_code}' WHERE store_id='{$store_id}' AND item_id='{$item_id}' LIMIT 1 ");
}
else if($action == 'price'){  // edit price of item 
	
	$product_price = $_POST['change_product_price'];
	if($item_id=='' || $product_price=='' || $store_id==''){
		echo 'All fields are necessary';
		exit(1);
	}
	$query = $mysqli->query("UPDATE tblitems SET item_price='{$product_price}' WHERE store_id='{$store_id}' AND item_id='{$item_id}' LIMIT 1 ");
}
else if($action == 'disc_price'){  // edit discounted price of item
	
	$disc_price = $_POST['change_disc_price'];
	if($item_id=='' || $disc_price=='' || $store_id==''){
		echo 'All fields are necessary';
		exit(1);
	}
	$query = $mysqli->query("UPDATE tblitems SET item_disc_price='{$disc_price}' WHERE store_id='{$store_id}' AND item_id='{$item_id}' LIMIT 1 ");
}
else if($action == 'department'){   // edit department to which it belongs 
	
	$dep_id = $_POST['change_dep_id'];
	if($item_id=='' || $dep_id=='' || $store_id==''){
		echo 'All fields are necessary';
		exit(1);
	}
	$query = $mysqli->query("UPDATE tblitems SET dep_id='{$dep_id}' WHERE store_id='{$store_id}' AND item_id='{$item_id}' LIMIT 1 ");
}
else if($action == 'brand'){   // edit department to which it belongs 
	
	$brand_id = $_POST['change_brand_id'];
	if($item_id=='' || $brand_id=='' || $store_id==''){
		echo 'All fields are necessary';
		exit(1);
	}
	$query = $mysqli->query("UPDATE tblitems SET brand_id='{$brand_id}' WHERE store_id='{$store_id}' AND item_id='{$item_id}' LIMIT 1 ");
}
else if($action == 'status'){  // edit status or product availability
	if($item_id=='' || $store_id==''){
		echo 'All fields are necessary';
		exit(1);
	}
	if(isset($_POST['change_product_status'])){
		$query = $mysqli->query("UPDATE tblitems SET dish_status='1' WHERE store_id='{$store_id}' AND item_id='{$item_id}' LIMIT 1 ");
	}
	else {
		$query = $mysqli->query("UPDATE tblitems SET dish_status='0' WHERE store_id='{$store_id}' AND item_id='{$item_id}' LIMIT 1 ");
	}
}
else if($action == 'coloursize'){
	$colour = $_POST['change_product_colour'];
	$size = $_POST['change_product_size'];
	$stock = $_POST['change_product_stock'];
	$arraycolour = explode(":", $colour);
	$arraysize = explode(":", $size);
	
	$query = $mysqli->query("SELECT * FROM tblitemdesc WHERE item_id='{$item_id}' AND size_id='{$arraysize[0]}' AND colour_id='{$arraycolour[0]}' LIMIT 1");
	if($query->rowCount() == 0){
		$query = $mysqli->query("INSERT INTO tblitemdesc VALUES ('{$item_id}', '{$arraysize[0]}', '{$arraysize[1]}', '{$arraycolour[0]}', '{$arraycolour[1]}', '{$stock}') ");
	}
	else {
		$query = $mysqli->query("UPDATE tblitemdesc SET stock='{$stock}' WHERE item_id='{$item_id}' AND size_id='{$arraysize[0]}' AND colour_id='{$arraycolour[0]}' LIMIT 1");
	}
}
else {
	$query = NULL;
}

if($query && $query->rowCount() == 0){
	echo "Failed to update data";
	exit(1);
}
echo 'success';

?>