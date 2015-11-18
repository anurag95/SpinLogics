<?php

// display all products that a store sells which match the search query by name

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

session_start();

$id = $_SESSION['id'];
$search = $_POST['search_query'];

$sql = $mysqli->query("SELECT * FROM tblitems WHERE  store_id='{$id}' AND item_name LIKE '%$search%' ");
if($sql->rowCount() == 0)
	echo "Fail";
else {  // return list of all products matching crietria
	echo "<ul>";
	while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
		echo '<li id=\''.$row['item_id'].'\' onclick=\'display_info('. $row['item_id'] .')\'>'.$row['item_name'].'</li>';
	}
	echo "</ul>";
}

?>