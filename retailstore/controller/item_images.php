<?php

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

session_start();

$id = @$_SESSION['id'];   // id of the store logged in

$action = $_POST['action']; // show images of an item, delete or add new image

if($action == 'show'){     // show tax informmation for a department

	$item_id = $_POST['item_id'];

	$sql = $mysqli->query("SELECT * FROM tblimages WHERE item_id='{$item_id}' ");
	$images = array();
	while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
		$images[$row['image_id']] = $row;
	}
	echo json_encode($images);
}

else if($action == 'add'){

	$item_id = $_POST['upload_item_id'];
	$allowedExts = array("gif", "jpeg", "jpg", "png");
	$temp = explode(".", $_FILES["item_image"]["name"]);  // extracting the extension of uploaded file

	$extension = end($temp);

	if ((($_FILES["item_image"]["type"] == "image/gif")
	|| ($_FILES["item_image"]["type"] == "image/jpeg")
	|| ($_FILES["item_image"]["type"] == "image/jpg")
	|| ($_FILES["item_image"]["type"] == "image/pjpeg")
	|| ($_FILES["item_image"]["type"] == "image/x-png")
	|| ($_FILES["item_image"]["type"] == "image/png"))
	&& in_array($extension, $allowedExts)) {

		if ($_FILES["item_image"]["error"] > 0) {    // error in file
	    	echo "Failed to add image";
	 		exit;
		} 
		else {   // proceed to add file in database
			$type = substr($_FILES["item_image"]["type"],6,strlen($_FILES["item_image"]["type"]));
			$sql = $mysqli->query("SELECT MAX(image_id) from tblimages");
			$row = $sql->fetch(PDO::FETCH_ASSOC);
			$id = (int)$row['MAX(image_id)']+1;
			$_FILES["item_image"]["name"] = $id . "." . $type;
			$filepath = "../asset/img/item_images/" . $_FILES["item_image"]["name"]; // adding item_image to folder item_images as image_id.extension
			move_uploaded_file($_FILES["item_image"]["tmp_name"], $filepath);
			$mysqli->query("INSERT INTO tblimages VALUES ('', '{$item_id}', '{$filepath}')");
			echo "success";
		}
	} 
	else {
		echo "*Invalid picture format";
		exit;
	}
}

else if($action == 'delete'){

	$image_id = $_POST['image_id'];
	$sql = $mysqli->query("SELECT image_url FROM tblimages WHERE image_id='{$image_id}' LIMIT 1");
	$row = $sql->fetch(PDO::FETCH_ASSOC);
	unlink($row['image_url']);
	$sql = $mysqli->query("DELETE FROM tblimages WHERE image_id='{$image_id}' LIMIT 1");
	echo "success";
	exit();
}

?>