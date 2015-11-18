<?php


$sql_hostname = "localhost"; //Enter hostname
$sql_user = "root"; // Enter Username
$sql_password = "anurag123"; // Enter password
$sql_db = "sudeep_apparel_clothing"; // Enter db_name

try {
	$mysqli = new PDO("mysql:host=$sql_hostname;dbname=$sql_db", $sql_user, $sql_password);
    /*** echo a message saying we have connected ***/
  //  echo 'Connected to database';
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>
