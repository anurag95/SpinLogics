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
<title>Settings</title>

<link href="../asset/css/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

</head>

<body id="dashboard">
	<?php include_once 'header.php' ?>
	<div style='margin-top:80px;' class="container">

		<div class="row">
			<h1 style="text-align:center">Settings</h1>
		</div>
		<div class="row">
			<div class="col-md-6">
				<h2>Payment Settings</h2>
				<h3><input name='cash' onclick='change_payment_setting("cash")' type="checkbox">&nbsp;&nbsp;Cash</h3>
				<h3><input name='credit' onclick='change_payment_setting("credit")' type="checkbox">&nbsp;&nbsp;Credit</h3>
				<h3><input name='debit' onclick='change_payment_setting("debit")' type="checkbox">&nbsp;&nbsp;Debit</h3>
				<h3><input name='gift' onclick='change_payment_setting("gift")' type="checkbox">&nbsp;&nbsp;Gift Coupons</h3>
				<h3><input name='misc' onclick='change_payment_setting("misc")' type="checkbox">&nbsp;&nbsp;Miscellaneous</h3>
				<br />
				<form role='form' action='' id='change_misc' class='form-inline'>
					<input type="hidden" name='action' value='change_misc'>
					<label for="misc_data">Miscellaneous Payment</label><br />
					<input type="text" name='misc_data' placeholder='Miscellaneous Payment' class="form-control">
					<button type='submit' class='btn btn-primary'>Change</button>
				</form>
			</div>
			<div class="col-md-6">
				<h2>Add User</h2>
				<form role='form' action='' id='add_user' class='form-inline'>
          			<div id='statusdiv' style='color:red;'></div>
					<input type="hidden" value='add' name="action">
					<label for="email">User Email</label><br />
					<input class="form-control" type="email" placeholder="Email" name="email" required><br />
					<label for="pass">Password</label><br />
					<input class="form-control" type="password" placeholder="Password" name="pass" required><br />
					<label for="con_pass">Confirm Password</label><br />
					<input class="form-control" type="password" placeholder="Confirm Password" name="con_pass" required><br />
					<br />
					<button type='submit' class='btn btn-success'>Submit</button>
				</form>
			</div>
		</div>
	</div>
	<?php include_once 'navbar.php' ?>

<script src="../asset/js/jquery.js"></script>
<script src="../asset/js/bootstrap.js"></script>
<script type="text/javascript">
	document.getElementById('settings').className +=  "active";
	$(document).ready(function(){
		$.ajax({
	       type: "POST",
		   url: "../controller/payment_settings.php", // the script where you handle the form input.
	       data: {'action':'show' },
	       success: function(msg)
	       {
	       		payment_info = JSON.parse(msg);
				if(payment_info['payment_cash'] == 1)   document.getElementsByName("cash")[0].checked = true;
				if(payment_info['payment_credit'] == 1)   document.getElementsByName("credit")[0].checked = true;
				if(payment_info['payment_debit'] == 1)   document.getElementsByName("debit")[0].checked = true;
				if(payment_info['payment_giftcoupons'] == 1)   document.getElementsByName("gift")[0].checked = true;
				if(payment_info['payment_misc_enable'] == 1)   document.getElementsByName("misc")[0].checked = true;
				document.getElementsByName("misc_data")[0].value = payment_info['payment_misc'];
	       }
	    });
 	}); 

 	function change_payment_setting(type){
		$.ajax({
           type: "POST",
           url: "../controller/payment_settings.php",
           data: {'field':type, 'action':'change' }, // serializes the form's elements.
           success: function(msg)
           {
           		alert(msg);
           }
        });
 	}

 	$("#change_misc").submit(function() {
    	$.ajax({
           type: "POST",
           url: "../controller/payment_settings.php",
           data: $("#change_misc").serialize(), // serializes the form's elements.
           success: function(msg)
           {
                alert(msg);
           }	
        });

    	return false; // avoid to execute the actual submit of the form.
	});

	$("#add_user").submit(function() {
    	$.ajax({
           type: "POST",
           url: "../controller/add_user.php",
           data: $("#add_user").serialize(), // serializes the form's elements.
           success: function(msg)
           {
           		if(msg == 'success'){
           			alert("User added successfully");
           			location.reload();
           		}
           		else {
                	$('#statusdiv').show();
	       			$("#statusdiv").html(msg);
            	}
           }	
        });
    	return false; // avoid to execute the actual submit of the form.
	});	

</script>
</body>
</html>