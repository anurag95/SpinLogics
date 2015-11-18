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
<title>Edit Details</title>

<link href="../asset/css/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

</head>

<body id="dashboard">
	<div class = 'col-xs-3'></div>
	<div class = 'col-xs-5' style='margin-top:50px;'>
		<h1>Edit Your Details</h1>

		<form id='update_logo' enctype='multipart/form-data' role='form'>
			<img style='height:100px; width:100px' src="<?php echo $row['store_logo'] ?>" />
			<input name="logo" type="file" class="inputFile" id="userImage">
			<button id='change_photo' type='submit' class="btn col-xs-3 col-md-3"> Submit </button>
		</form>
		
		<br /><br />
		<form role="form" action="../controller/edit_details.php" method='post'>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-key"></i></span>
				<input id="password" type="text" class="form-control" placeholder="Name" name="name" value="<?php echo $row['store_name'] ?>" required>
			</div>
			<div class="input-group">
				<label>Currency</label>
				<select class="form-control" id = 'currency_name' name="currency" required>
					<?php
						$sql = $mysqli->query("SELECT * FROM tblcurrency");				
						while($cur = $sql->fetch(PDO::FETCH_ASSOC))
							if($cur['currency_id'] == $result['currency_id'])
								echo "<option id = '" .$cur['currency_id']. "' selected>".$cur['currency_name']."</option>";
							else
								echo "<option id = '" .$cur['currency_id']. "'>".$cur['currency_name']."</option>";
					?>
				</select>
				<br/>
			</div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-key"></i></span>
				<input id="cur_to_points" type="text" class="form-control" placeholder="Currency to points" name="cur_to_points" value="<?php echo $row['cur_to_points'] ?>" required>
			</div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-key"></i></span>
				<input id="points_to_cur" type="text" class="form-control" placeholder="Points to Currency" name="points_to_cur" value="<?php echo $row['points_to_cur'] ?>" required>
			</div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-key"></i></span>
				<textarea id="adddetails" type="text" class="form-control" placeholder="Additional Details" name="adddetails" required><?php echo $row['store_adddetails'] ?></textarea>
			</div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-key"></i></span>
				<input id="website" type="text" class="form-control" placeholder="Website" name="website" value="<?php echo $row['store_website'] ?>" required>
			</div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-key"></i></span>
				<input id="phone" type="text" class="form-control" placeholder="Phone" name="phone" value="<?php echo $row['store_phone'] ?>" required>
			</div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-key"></i></span>
				<input id="address" type="text" class="form-control" placeholder="Address" name="address" value="<?php echo $row['store_address'] ?>" required>
			</div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-key"></i></span>
				<input id="city" type="text" class="form-control" placeholder="City" name="city" value="<?php echo $row['store_city'] ?>" required>
			</div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-key"></i></span>
				<input id="state" type="text" class="form-control" placeholder="State" name="state" value="<?php echo $row['store_state'] ?>" required>
			</div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-key"></i></span>
				<input id="pincode" type="text" class="form-control" placeholder="Pincode" name="pincode" value="<?php echo $row['store_pcode'] ?>" required>
			</div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-key"></i></span>
				<input id="country" type="text" class="form-control" placeholder="Country" name="country" value="<?php echo $row['store_country'] ?>" required>
			</div>
			<input type='hidden' id='id' value='<?php echo $row['store_id'] ?>' >
			<div class="row">
				<div class="col-xs-12">
					<div class="col-md-3 col-xs-3">	</div>
					<button id='submit_button' type="submit" class="btn btn-success col-xs-6 col-md-6">Submit</button>
					<div class="col-md-3 col-xs-3"></div>
				</div>
			</div>
		</form>
		<a href="change_password.php">Change Password</a>
		
	</div>
	<div class = 'col-xs-4'>
		<button class="btn col-xs-3 col-md-3"><a class="btn" href="../controller/logout.php">Log Out</a></button>
	</div>

<script type="text/javascript">
	var status = "<?php echo @$_GET['status'] ?>";
	var msg = "<?php echo @$_GET['message'] ?>";
	if(status.toLowerCase() == "fail"){
		document.getElementById("statusdiv").innerHTML = msg;
		document.getElementById("statusdiv").style.display = "";
	}
	else {
		document.getElementById("statusdiv").style.display = "none";
	}

</script>


<script src="../asset/js/jquery.js"></script>
<script src="../asset/js/bootstrap.js"></script>

<script type="text/javascript">
	$(document).ready(function (event){
		$("#update_logo").on('submit', (function(e) {
		    e.preventDefault();
		    $.ajax({
			    url: "../controller/update_logo.php",
			    type: 'POST',
			    data: new FormData(this),
			    contentType: false,
			    cache: false,
			    processData:false,

			    success: function(msg){
			        if(msg == "success")
			        {
		                alert("done");
       					location.reload();
			        }
			        else {
			     		alert(msg);
			  		}
		    	},
			    error: function() {
			    	alert("error");
			    }
			}); 
		}));
	});

</script>
</body>
</html>