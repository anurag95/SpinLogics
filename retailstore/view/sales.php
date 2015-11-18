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
<title>Sales</title>

<link href="../asset/css/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href="../asset/css/jquery.dataTables.css" rel="stylesheet"/>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

</head>

<body id="">
	<?php include_once 'header.php' ?>

	<div style='margin-top:80px;' class="container">
	<div class="row">
		<table id="order_list" class="display">
		    <thead>
		        <tr>
		            <th>Order Id</th>
		            <th>Date</th>
		            <th>Customer Name</th>
		            <th>Total</th>
		            <th>Order Status</th>
		            <th></th>
		        </tr>
		    </thead>
		 
		    <tfoot>
		        <tr>
		            <th>Order Id</th>
		            <th>Date</th>
		            <th>Customer Name</th>
		            <th>Total</th>
		            <th>Order Status</th>
		            <th></th>
		        </tr>
		    </tfoot>
		    <tbody>
		    </tbody>
		</table>
    </div>
    </div>

    <div class="modal fade" id="changeStatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Update Order Status</h4>
	      </div>
	      	<form id='change_order_status' role='form' action='' method='post'>

		      <div class="modal-body">
		      	<input type='hidden' name='action' value='change_status'>
		      	<input type='hidden' name='order_id' value=''>
		        <input type="radio" name="order_status" value="1" required> Placed<br>
				<input type="radio" name="order_status" value="2"> Pending<br>
				<input type="radio" name="order_status" value="3"> Packed<br>
				<input type="radio" name="order_status" value="4"> Dispatched<br>
				<input type="radio" name="order_status" value="5"> Delivered<br>
				<input type="radio" name="order_status" value="6"> Cancelled<br>
		      </div>
		      <div class="modal-footer">
		        <button class="btn btn-default" data-dismiss="modal">Close</button>
		        <button id='submit' type="submit" class="btn btn-primary">Save changes</button>
		      </div>

	      </form>
	    </div>
	  </div>
	</div>
	<?php include_once 'navbar.php' ?>

<script src="../asset/js/jquery.js"></script>
<script src="../asset/js/jquery.dataTables.js"></script>
<script src="../asset/js/bootstrap.js"></script>
<script type="text/javascript">
	document.getElementById('sales').className +=  "active";
	$(document).ready(function() {
    	$('#order_list').DataTable();
	} );

	//  showing the orders in a table
	$.ajax({
       type: "POST",
       url: '../controller/orders.php',
       async: false,
       data: {'action':'show_all'},
       success: function(msg)
       {
       		orders = JSON.parse(msg);
       		for(key in orders){
       			row = "<tr>";
       			for(item in orders[key]){
       				if(item == 'order_status')
       					row += "<td id=\'"+orders[key]['order_id']+"-"+orders[key][item].split("-")[0]+"\' class='status_update' style='color:blue; cursor: pointer; cursor: hand;'>"+orders[key][item].split("-")[1]+"</td>";
       				else
       					row += "<td>"+orders[key][item]+"</td>";
       			}
       			row += "<td><a href=\'view_order.php?order_id="+key+"\'><button class='btn btn-primary'>View</button></td>"
       			row += "</tr>";
       			$('#order_list > tbody:last-child').append(row);
       		} 
       }
    });		

	// showing modal window to change status
    $(".status_update").click(function(){
    	$('#changeStatus').modal();
    	document.getElementsByName("order_id")[0].value = this.id.split("-")[0];
    	document.getElementsByName("order_status")[this.id.split("-")[1] - 1].checked = true;
    });

    // change status of order 
    $("#change_order_status").submit(function(){
       $.ajax({
	       type: "POST",
	       url: '../controller/orders.php',
	       async: false,
	       data: $("#change_order_status").serialize(),
	       success: function(msg)
	       {
	       		alert(msg); 
	       		location.reload();
	       }
	    });	
	    return false;	
    });
</script>

</body>
</html>