<?php include_once '../controller/ensure_login.php';
	ensureLoggedOut();
?>

<!DOCTYPE html>

<html>
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>

<link href="../asset/css/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

<style type="text/css">
table {
    border-collapse: collapse;
}

td {
    padding-top: .5em;
    padding-bottom: .5em;
}

</style>
</head>

<body id="login-page">
	<div class = 'col-xs-4'></div>
	<div class = 'col-xs-4' style='margin-top:150px;'>
		<div id = 'statusdiv'></div>
		<form role="form" action="../controller/check_login.php" method='post'>
			<table class=""><tbody>
			<tr>
				<td class="title"><label>Email</label></td>
				<td>&nbsp;&nbsp;</td>
			    <td class="input"><input size='35' id="email" class="form-control" type="text" placeholder="Email address" name="email"></td>
			</tr>
			<br />
			<tr>
				<td class="title"><label>Password</label></td>
				<td>&nbsp;&nbsp;</td>
				<td class="input"><input size='35' id="password" type="password" class="form-control" placeholder="Password" name="password"></td>
			</tr>
			</tbody></table>

			<div class="row">
				<div class="col-xs-2"></div>
				<button id='submit_button' type="submit" class="btn btn-success col-xs-2" style="margin-left:17px;">Login</button>
				<div class="col-xs-3"></div>
				<div class="col-xs-2">
					<button id='register' class="btn btn-primary" style="margin-left:0px;">Signup</button>
				</div>
			</div>
			<div class="row" style="margin-left:233px"><a href="forgot_password.php">Forgot Password</a></div>

		</form>
	</div>
	<div class = 'col-xs-4'>
	</div>


<script src="../asset/js/jquery.js"></script>
<script src="../asset/js/bootstrap.js"></script>

<script type="text/javascript">
	var status = "<?php echo $_GET['status'] ?>";
	var msg = "<?php echo $_GET['message'] ?>";
	if(status.toLowerCase() == "fail"){
		document.getElementById("statusdiv").innerHTML = msg;
		document.getElementById("statusdiv").style.display = "";
	}
	else{
		document.getElementById("statusdiv").style.display = "none";
	}

</script>
</body>
</html>