<?php

// display all departments of a store which match the search query by name

require( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "config_sql.php" );

session_start();

$id = $_SESSION['id'];
$search = $_POST['search_query'];

$sql = $mysqli->query("SELECT * FROM tbldep WHERE  store_id='{$id}' AND dep_name LIKE '%$search%' ");
if($sql->rowCount() == 0)
	echo "Fail";
else {  // return list of all departments matching criteria
	echo "<ul>";
	while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
	    echo '<li onclick=\'display_info('.$row['dep_id'].')\'>'.$row['dep_name'].'</li>';
	}
	echo "</ul>";
}

?>