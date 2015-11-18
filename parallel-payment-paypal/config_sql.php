<?php
	mysql_connect("localhost", "root", "anurag123") or die ("Oops! Server not connected"); // connect to the host
	mysql_select_db("purchases") or die ("Oops! DB not connected"); // select the database
?>