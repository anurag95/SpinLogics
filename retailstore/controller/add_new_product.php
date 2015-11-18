<?php 

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

session_start();

$store_id = $_SESSION['id'];   // store id of the currently logged in store
$action = $_POST['action_new'];    // action field
$empty_fields = array();
if($action == 'create'){   // create action for adding new products

	// extracting the parameters

	$name = @$_POST['create_product_name'];
	$desc = @$_POST['create_product_desc'];
	$code = @$_POST['create_product_code'];
	$price = @$_POST['create_product_price'];
	$disc_price = @$_POST['create_disc_price'];
	$dep_id = @$_POST['create_dep_id'];
	$brand_id = @$_POST['create_brand_id'];

	// checking if the parameters are empty

	if($store_id == ''){
		echo "Failed to create department";
		exit();
	}
	if($name == ''){
		array_push($empty_fields, 'Name');
	}
	if($desc == ''){
		array_push($empty_fields, 'Description');
	}
	if($dep_id == 0){
		array_push($empty_fields, 'Department');
	}
	if($price == ''){
		array_push($empty_fields, 'Price');
	}
	if(empty($_FILES)){
		array_push($empty_fields, 'Image');
	}
	// sending eeror in case any mandatory field is blank.
	if(count($empty_fields) > 0){   
		echo "The following fields are neccessary - ";
		echo $empty_fields[0];
		for($i = 1; $i < count($empty_fields); $i++){
			echo ", ".$empty_fields[$i];
		}
		exit();
	}

	$allowedExts = array("gif", "jpeg", "jpg", "png");
	$temp = explode(".", $_FILES["create_product_image"]["name"]);  // extracting the extension of uploaded file

	$extension = end($temp);

	if ((($_FILES["create_product_image"]["type"] == "image/gif")
	|| ($_FILES["create_product_image"]["type"] == "image/jpeg")
	|| ($_FILES["create_product_image"]["type"] == "image/jpg")
	|| ($_FILES["create_product_image"]["type"] == "image/pjpeg")
	|| ($_FILES["create_product_image"]["type"] == "image/x-png")
	|| ($_FILES["create_product_image"]["type"] == "image/png"))
	&& in_array($extension, $allowedExts)) {

		if ($_FILES["create_product_image"]["error"] > 0) {    // error in file
	    	echo "Error in uploaded image";
	 		exit;
		} 
	}
	else {
		echo "*Invalid picture format";
		exit;
	}

	if(isset($_POST['create_product_status'])){   // if checkbox for product availability if checked
		$sql = $mysqli->query("INSERT INTO tblitems VALUES ('', '{$store_id}', '{$dep_id}', '{$brand_id}', '{$code}', '{$name}', '{$desc}', '{$price}', '1', '{$disc_price}')");
	}
	else {   // if checkbox for product availability if not checked
		$sql = $mysqli->query("INSERT INTO tblitems VALUES ('', '{$store_id}', '{$dep_id}', '{$brand_id}', '{$code}', '{$name}', '{$desc}', '{$price}', '0', '{$disc_price}')");
	}
	if($sql->rowCount() > 0){

		$sql = $mysqli->query("SELECT MAX(item_id) from tblitems");
		$row = $sql->fetch(PDO::FETCH_ASSOC);
		$item_id = (int)$row['MAX(item_id)'];
		
		// proceed to add file in database
		$type = substr($_FILES["create_product_image"]["type"],6,strlen($_FILES["create_product_image"]["type"]));
		$sql = $mysqli->query("SELECT MAX(image_id) from tblimages");
		$row = $sql->fetch(PDO::FETCH_ASSOC);
		$id = (int)$row['MAX(image_id)']+1;
		$_FILES["create_product_image"]["name"] = $id . "." . $type;
		$filepath = "../asset/img/item_images/" . $_FILES["create_product_image"]["name"]; // adding create_product_image to folder item_images as image_id.extension
		move_uploaded_file($_FILES["create_product_image"]["tmp_name"], $filepath);
		$mysqli->query("INSERT INTO tblimages VALUES ('', '{$item_id}', '{$filepath}')");
		
		echo "success"; 
		exit;
	
	}
	else {
		echo "Failed to add product";
		exit();
	}
}

?>

