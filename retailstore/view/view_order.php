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
<title>Order</title>

<link href="../asset/css/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

</head>

<body id="dashboard">
	<?php include_once 'header.php' ?>
	<div style='margin-top:80px;' class="container">

		<div class="row">
			<h1 style='text-align:center'>Order Details</h1>
			<div class="col-md-2"></div>
			<div class='col-md-3' id='shipping_address'></div>
			<div class='col-md-3' id='payment_type'></div>
			<div class='col-md-4' id='amount'></div>
		</div>
		<br />
		<div class="row">
		<div class="col-md-3"></div>
			<div class="col-md-6">
				<table class="table">
					<thead>
						<tr>
							<th>Item</th>
							<th>Colour</th>
							<th>Size</th>
							<th>Quantity</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
					</tfoot>
				</table>
			</div>
			<div class="col-md-3"></div>

		</div>
	</div>
	
	<?php include_once 'navbar.php' ?>

<script src="../asset/js/jquery.js"></script>
<script src="../asset/js/bootstrap.js"></script>
<script type="text/javascript">

	$(document).ready(function(){
		var order_id = <?php if(isset($_GET['order_id'])) echo $_GET['order_id']; else echo '0' ?>;
		
		// display order details with the particular id
		$.ajax({
           type: "POST",
           url: '../controller/orders.php',
           async: false,
           data: {'action':'show', 'order_id': order_id},
           success: function(msg)
           {
           		if(msg == 'Fail'){
           			alert("Order not available");
           			location.href = '../view/dashboard.php';
           		}
           		
         		invoice = JSON.parse(msg);
         		div = document.getElementById('shipping_address');
         		div.innerHTML = '<br /><b>Shipping Address</b><br /><br />';
         		div.innerHTML += invoice['customer_name']+'<br />';
         		div.innerHTML += invoice['customer_address']+'<br />';
         		div.innerHTML += invoice['customer_city']+', '+invoice['customer_state']+' - '+invoice['customer_pcode']+'<br />';
         		div.innerHTML += invoice['customer_country']+'<br /><br />';
         		div.innerHTML += 'Order Placed on '+invoice['order_date'].split(" ")[0]+'<br /><br />';

         		div = document.getElementById('payment_type');
         		div.innerHTML = '<br /><b>Payment Method</b><br /><br />';
         		div.innerHTML += invoice['payment_type'];
         		if(invoice['coupon_status'] == 1){
					div.innerHTML += 'Coupon Applied: '+invoice['coupon_code'];
         		}
         		div.innerHTML += "<br /><br />Courier Company: "+invoice['tracker_id'];
         		div.innerHTML += "<br />Tracking Id: "+invoice['tracking_id'];
        		
         		div = document.getElementById('amount');
         		div.innerHTML = '<br /><b>Order Summary</b><br /><br />';
         		div.innerHTML += 'Total Amount: '+invoice['total_amount']+'<br />';
         		div.innerHTML += 'Points Redeemed: '+invoice['points_redeemed']+'<br /><br />';
         		div.innerHTML += 'Order Status: '+invoice['order_status']+'<br />';         		

         		div = document.getElementsByTagName("tbody")[0];
         		div.innerHTML = '';
         		var str;
         		for(key in invoice){
         			if(isNaN(key))
         				continue;
         			str = "<tr>";
         			str += "<td>"+invoice[key]['item_name']+"</td>";
         			str += "<td>"+invoice[key]['colour_name']+"</td>";
         			str += "<td>"+invoice[key]['size_name']+"</td>";
         			str += "<td>"+invoice[key]['quantity']+"</td>";
         			str += "<td>"+invoice[key]['amount']+"</td>";
         			str += "</tr>";

         			div.innerHTML += str;
         		}

         		div = document.getElementsByTagName("tfoot")[0];
         		div.innerHTML = "<tr><td></td><td></td><td></td><td></td><td><b>"+invoice['total_amount']+"</b></td></tr>";
           	}
        });
		
	});
</script>
</body>
</html>