<?php

//starting the session.
session_start();

//Destroying all session variables.
session_destroy();

//Redirecting to login page again.
header("Location: ../view/store_login.php");

?>