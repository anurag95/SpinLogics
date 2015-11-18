<?php include_once '../controller/ensure_login.php';
	ensureLoggedIn();
	require_once '../model/config_sql.php';
	$id = $_SESSION['id'];
	$sql = $mysqli->query("SELECT * FROM tblstore WHERE store_id='{$id}' LIMIT 1");
	$row = $sql->fetch(PDO::FETCH_ASSOC);

	$currency_id = $row['currency_id'];
	$sql = $mysqli->query("SELECT * FROM tblcurrency WHERE currency_id='{$currency_id}' LIMIT 1");
	$result = $sql->fetch(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>

<html>
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Change Password</title>

<link href="../asset/css/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

</head>

<body id="dashboard">
	<div class = 'col-xs-4'></div>
	<div class = 'col-xs-4' style='margin-top:150px;'>
		<h1>Welcome <?php echo $row['store_name']; ?> </h1>

		<form role="form" action="../controller/change_password.php" method='post'>
		<br /><br />
		<div class = 'col-md-12 col-xs-12' style="text-align:center;"><h1>Change Password</h1></div> 
		<br />
		<div class="modal-body">
			
			<div id = "statusdiv" class="row">
				<div class="col-xs-12">
					<p id="status" class="alert fade in" style="padding:3px;"></p>
				</div>
			</div>
				
			<div class="form-group">
				<input class="form-control" type="password" placeholder="Current Password" name="cur_password" required>
			</div>


			<div class="form-group">
				<input class="form-control" type="password" placeholder="New Password" name="new_password" required>
			</div>

			<div class="form-group">
				<input class="form-control" type="password" placeholder="Confirm Password" name="confirm_password" required>
			</div>

            <div class="form-group">
				<input class="form-control" type="hidden" value="change" name="action" required hidden>
            </div>

		</div>

		<div class="modal-footer">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>

	</form>	

	</div>
	<div class = 'col-xs-4'></div>

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

<script src="../asset/js/jquery.js"></script>
<script src="../asset/js/bootstrap.js"></script>
</body>
</html>