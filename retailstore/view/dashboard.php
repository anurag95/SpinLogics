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
<title>Dashboard</title>

<link href="../asset/css/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

</head>

<body id="dashboard">
	<?php include_once 'header.php' ?>
	<div class="row" style="margin-top:100px;">
		<div class = 'col-xs-4'>
			<div class="col-xs-3"></div>
			<div class="col-xs-9">
				<img border='1' src="../asset/img/logos/2.jpg" /><br />
				<div style='margin-left:70px;'><a href="categories.php">Categories</a></div>
			</div>
		</div>
		<div class = 'col-xs-4'>
			<div class="col-xs-3"></div>
			<div class="col-xs-9">
				<img border='1' src="../asset/img/logos/2.jpg" /><br />
				<div style='margin-left:70px;'><a href="products.php">Products</a></div>
			</div>
		</div>
		<div class = 'col-xs-4'>
			<div class="col-xs-3"></div>
			<div class="col-xs-9">
				<img border='1' src="../asset/img/logos/2.jpg" /><br />
				<div style='margin-left:70px;'><a href="sales.php">Sales</a></div>
			</div>
		</div>
	</div>
	<br /><br />
	<div class="row">
		<div class = 'col-xs-4'>
			<div class="col-xs-3"></div>
			<div class="col-xs-9">
				<img border='1' src="../asset/img/logos/2.jpg" /><br />
				<div style='margin-left:70px;'><a href="customers.php">Customers</a></div>
			</div>
		</div>
		<div class = 'col-xs-4'>
			<div class="col-xs-3"></div>
			<div class="col-xs-9">
				<img border='1' src="../asset/img/logos/2.jpg" /><br />
				<div style='margin-left:70px;'><a href="coupons.php">Gift Coupons</a></div>
			</div>
		</div>
		<div class = 'col-xs-4'>
			<div class="col-xs-3"></div>
			<div class="col-xs-9">
				<img border='1' src="../asset/img/logos/2.jpg" /><br />
				<div style='margin-left:70px;'><a href="settings.php">Settings</a></div>
			</div>
		</div>
	</div>

<script src="../asset/js/jquery.js"></script>
<script src="../asset/js/bootstrap.js"></script>
</body>
</html>