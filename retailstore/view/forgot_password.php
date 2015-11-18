<?php include_once '../controller/ensure_login.php';
	ensureLoggedOut();
?>

<!DOCTYPE html>

<html>
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password</title>

<link href="../asset/css/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

</head>

<body id="dashboard">
	<div class = 'col-xs-4'></div>
	<div class = 'col-xs-3' style='margin-top:150px;'>
	<span>Enter Email</span> <br />
	<form role="form" action="../controller/forgot_password.php" method='post'>
		<div class="form-group">
			<input class="form-control" type="email" placeholder="Email" name="email" required>
		</div>

		<button type='submit' class="btn col-xs-5 col-md-5">Send Email</button>
	</form>
	</div>
	<div class = 'col-xs-5'></div>


<script src="../asset/js/jquery.js"></script>
<script src="../asset/js/bootstrap.js"></script>
</body>
</html>