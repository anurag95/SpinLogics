<?php

// mail settings

require_once 'vendor/autoload.php';

$mail = new PHPMailer; // create a new object

$mail->isSMTP(); // enable SMTP
$mail->SMTPAuth = true; // authentication enabled

$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
$mail->Host = "smtp.gmail.com";
$mail->Port = 587; // or 465
$mail->Username = "";   // gmail username
$mail->Password = ""; // gmail password

$mail->From = ""; // mail from 
$mail->FromName = "";  // name of person mailing
?>
