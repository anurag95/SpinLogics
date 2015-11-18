<?php

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

session_start();

$id = $_SESSION['id'];

$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["logo"]["name"]);  // extracting the extension of uploaded file

$extension = end($temp);

if ((($_FILES["logo"]["type"] == "image/gif")
|| ($_FILES["logo"]["type"] == "image/jpeg")
|| ($_FILES["logo"]["type"] == "image/jpg")
|| ($_FILES["logo"]["type"] == "image/pjpeg")
|| ($_FILES["logo"]["type"] == "image/x-png")
|| ($_FILES["logo"]["type"] == "image/png"))
&& in_array($extension, $allowedExts)) {

	if ($_FILES["logo"]["error"] > 0) {    // error in file
    	echo "Failed to update logo";
 		exit;
	} 
	else {   // proceed to add file in database
		$type = substr($_FILES["logo"]["type"],6,strlen($_FILES["logo"]["type"]));
		$_FILES["logo"]["name"] = $id . "." . $type;
		$filepath = "../asset/img/logos/" . $_FILES["logo"]["name"]; // adding logo to folder logos by name store_id.extension
		move_uploaded_file($_FILES["logo"]["tmp_name"], $filepath);
		$mysqli->query("UPDATE tblstore SET store_logo='{$filepath}' WHERE store_id='{$id}' LIMIT 1");
		echo "success";
	}
} 
else {
	echo "*Invalid picture format";
	exit;
}



?>
