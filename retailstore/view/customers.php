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
<title>Customers</title>

<link href="../asset/css/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

</head>

<body id="dashboard">
	<?php include_once 'header.php' ?>
	<div style='margin-top:80px;' class="container">

		<div class="row">
			<h1 style="text-align:center">Customers</h1><br />
			<div class='row'>
				<div class="col-md-4">
					<input id="search_customers" class="form-control" type="text" placeholder="Search Customers" name="search_customers" value = ''>
					<br />
				</div>
				<div class="col-md-8">
					<button class="btn btn-primary" id='add'>Add Customer</button>
				</div>
			</div>
			<div class="container">
				<div class="row" id='list_container'></div>
				<div class="row" id='search_container'></div>			
			</div>
			<div class="container">
			<form id='add_customer' role='form' method='post' action='' style="display:none">
				<h2>Add New Customer</h2>
          		<div id='statusdiv' style='color:red;'></div>
    			<input type='hidden' name='action' value='add' required>
				<div class="input-group">
					<input type="text" placeholder="Customer Name" name="name" required>
				</div>
				<div class="input-group">
					<input type="email" placeholder="Email" name="email" required>
				</div>
				<div class="input-group">
					<input type="password" placeholder="Password" name="pass" required>
					<input type="password" placeholder="Confirm Password" name="con_pass" required>
				</div>
				<div class="input-group">
					<input type="text" placeholder="Address" name="address" required>
				</div>
				<div class="input-group">
					<input type="text" placeholder="City" name="city" required>
					<input type="text" placeholder="State" name="state" required>
				</div>
				<div class="input-group">
					<input type="text" placeholder="Country" name="country" required>
					<input type="text" placeholder="Pin Code" name="pcode" required>
				</div>
				<div class="input-group">
					<input type="text" placeholder="Phone" name="phone" required>
				</div>
				<button type='submit' class="btn btn-success">Submit</button>
				<button class="btn btn-danger" id='cancel'>Cancel</button>
    		</form>
    		</div>
		</div>
	</div>
	<?php include_once 'navbar.php' ?>

<script src="../asset/js/jquery.js"></script>
<script src="../asset/js/bootstrap.js"></script>
<script type="text/javascript">
	document.getElementById('customers').className +=  "active";
	$(document).ready(function() {
    	$.ajax({
	       type: "POST",
	       url: '../controller/customers.php',
	       async: false,
	       data: {'action':'show_all'},
	       success: function(msg)
	       {
	       		customers = JSON.parse(msg);
	       		for(key in customers){
	       			row = "<h3>"+customers[key]['customer_name'];
	       			row += "<button style='margin-left:30px;' class='btn btn-danger' onclick=\'call_delete("+key+")\' >Delete</button> </h3>";
	       			row += customers[key]['customer_address'] + "<br />";
	       			row += customers[key]['customer_city'] + ", " + customers[key]['customer_state'] + " - " + customers[key]['customer_pcode'] +"<br />";
	       			row += customers[key]['customer_country'] + "<br /><br />";
	       			row += "Phone: " + customers[key]['customer_phone'] + "<br />";
	       			row += "Email: " + customers[key]['customer_email'] + "<br /><br />";
	       			row += "Points: " + customers[key]['customer_points'] + "<br /><br />";
	       			document.getElementById('list_container').innerHTML += row;
	       		} 
	       }
	    });		
	});

	$("#search_customers").keyup(function(){

		if($("#search_customers").val() == ''){
			document.getElementById("search_container").style.display = 'none';
			document.getElementById("list_container").style.display = '';
	    	document.getElementById('add_customer').style.display = 'none';
		}
		else {
	    	document.getElementById('add_customer').style.display = 'none';
			document.getElementById("search_container").style.display = '';
			document.getElementById("list_container").style.display = 'none';
	    	$.ajax({
	           type: "POST",
		       url : "../controller/customers.php", // the script where you handle the form input.
	           data: {'action':'search', 'search_query': $("#search_customers").val() }, 
	           success: function(msg)
	           {
	                if(msg == 'Fail') { // show response from the php script.
	           			$("#search_container").html("No results found");
	           		}
	           		else {
	           			var s_customers = JSON.parse(msg);
			       		document.getElementById('search_container').innerHTML = '';

			       		for(key in s_customers){
	       					row = "<h3>"+s_customers[key]['customer_name'];
	       					row += "<button style='margin-left:30px;' class='btn btn-danger' onclick=\'call_delete("+key+")\' >Delete</button> </h3>";
			       			row += s_customers[key]['customer_address'] + "<br />";
			       			row += s_customers[key]['customer_city'] + ", " + s_customers[key]['customer_state'] + " - " + s_customers[key]['customer_pcode'] +"<br />";
			       			row += s_customers[key]['customer_country'] + "<br /><br />";
			       			row += "Phone: " + s_customers[key]['customer_phone'] + "<br />";
			       			row += "Email: " + s_customers[key]['customer_email'] + "<br /><br />";
			       			row += "Points: " + s_customers[key]['customer_points'] + "<br /><br />";
			       			document.getElementById('search_container').innerHTML += row;
			       		} 
			        } 
	           }	
	        });
	    } 
	}); 

	$("#add").click(function() {
	    document.getElementById('search_container').style.display = 'none';
	    document.getElementById('list_container').style.display = 'none';
	    document.getElementById('add_customer').style.display = '';
	});

	$("#cancel").click(function() {
	    document.getElementById('search_container').style.display = 'none';
	    document.getElementById('list_container').style.display = '';
	    document.getElementById('add_customer').style.display = 'none';
	});

	function call_delete(cust_id){
		$.ajax({
           type: "POST",
	       url : "../controller/customers.php", // the script where you handle the form input.
           data: {'action':'delete', 'customer_id': cust_id }, 
           success: function(msg)
           {
                if(msg == 'Fail') { // show response from the php script.
           			alert("Failed to delete.");
           		}
           		else {
           			alert("Successfully deleted customer.");
           			location.reload(); 
		        } 
           }
        });	
	}

	$("#add_customer").submit(function(e) {

		$.ajax({
	       type: "POST",
	       url : "../controller/customers.php", // the script where you handle the form input.
           data: $("#add_customer").serialize() , // serializes the form's elements.
	       success: function(msg)
	       {
	            if(msg == 'success') { // show response from the php script.
	       			alert("Successfully added new customer");
	       			location.reload();
	       		}
	       		else {
	           		$('#statusdiv').show();
	       			$("#statusdiv").html(msg);
		        } 
	       },	
	    });

	    return false;
	}); 

	
</script>
</body>
</html>