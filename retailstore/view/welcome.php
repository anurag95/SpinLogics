<?php include_once '../controller/ensure_login.php';
	ensureLoggedIn();
	require_once '../model/config_sql.php';
	$id = $_SESSION['id'];
	$sql = $mysqli->query("SELECT * FROM tblstore WHERE store_id='{$id}' LIMIT 1");
	$row = $sql->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>

<html>
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Welcome</title>

<link href="../asset/css/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

</head>

<body id="welcome">
	<div class = 'col-xs-4'></div>
	<div class = 'col-xs-4' style='margin-top:150px;'>
		<h1>Welcome <?php echo $row['store_name']; ?> </h1>
		<?php
			echo "<img style='margin-left:100px; height:75px; width:75px' src='". $row['store_logo'] . "' /><br />";
		?>
		<br />
		<span style='margin-left:50px;'>You'll be redirected in 5s...</span>
	</div>

<script type="text/javascript">
	setTimeout(function(){ 
		location.href = 'dashboard.php'; 
	}, 5000);	
</script>

<script src="../asset/js/jquery.js"></script>
<script src="../asset/js/bootstrap.js"></script>
</body>
</html>