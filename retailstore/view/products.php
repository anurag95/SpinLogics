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
<title>Products</title>

<link href="../asset/css/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

</head>

<body id="">
	<?php include_once 'header.php' ?>

	<div style='margin-top:80px;' class="container">
	<div class="row">
    	
        <div class="col-md-4">
          <h2>Products</h2>
          <div class="input-group">
			<input id="search_products" class="form-control" type="text" placeholder="Search" name="search_products" value = '' required>
		  </div>
		  <br />
	      <span onclick='add_new_product()'>Add new product</span><br /><br />
		  <div id='product_all'>
	          <?php
	          	$information = array();
	          	$query = $mysqli->query("SELECT * FROM tblitems WHERE store_id='{$id}'");
	          	echo "<ul id='product_list'>";
	          	while ($row = $query->fetch(PDO::FETCH_ASSOC)) { 
	          		echo '<li id="'. $row['item_id'] .'" onclick=\'display_info('. $row['item_id'] .')\'>'.$row['item_name'].'</li>';
	          	}
		        echo "</ul>";
	          ?>
          </div>
          <div id='product_search'>
          	
          </div>
        </div>
        <div class="col-md-4">
        	<div id='dep_properties' style=''>
        		<h2>Product Details</h2>
        		<span id='' onclick='show("name")'>Name</span><br />
        		<span id='' onclick='show("desc")'>Description</span><br />
        		<span id='' onclick='show("code")'>Item Code</span><br />
        		<span id='' onclick='show("department")'>Department</span><br />
        		<span id='' onclick='show("brand")'>Brand</span><br />
        		<span id='' onclick='show("price")'>Price</span><br />
        		<span id='' onclick='show("disc_price")'>Discounted Price</span><br />
        		<span id='' onclick='show("status")'>Product Status</span><br />
        		<span id='' onclick='show("images")'>Item Images</span><br />
        		<span id='' onclick='show("coloursize")'>Colour and Size</span><br />
        	</div> 
        
       	</div>
        <div class="col-md-4">
          	<div id='statusdiv' style='color:red;'></div>

          	<!-- for entering new product details -->

          	<form id='add_product_details' role="form" action="" method='post' style="display:none">
    			
    			<input type='hidden' id='action_new' name='action_new' value='create' required>
    			<div id="create_product_name" style='visibility:hidden; position:absolute' class="input-group form-create">
					<h2>Name:</h2>
					<input class="form-control" type="text" placeholder="Product Name" name="create_product_name" value = ''>
				</div>
				<div id="create_product_desc" style='visibility:hidden; position:absolute' class="input-group form-create">
					<h2>Description:</h2>
					<textarea type="text" class="form-control" placeholder="Product Description" name="create_product_desc"></textarea>
				</div>
				<div id="create_product_code" style='visibility:hidden; position:absolute' class="input-group form-create">
					<h2>Item Code:</h2>
					<input class="form-control" type="text" placeholder="Product Name" name="create_product_code" value = ''>
				</div>
				<div id="create_dep_id" style='visibility:hidden; position:absolute' class="input-group form-create">
					<h2>Department</h2>
					<select class="form-control" name="create_dep_id">

					</select>
				</div>
				<div id="create_brand_id" style='visibility:hidden; position:absolute' class="input-group form-create">
					<h2>Brand</h2>
					<select class="form-control" name="create_brand_id">

					</select>
				</div>
				<div id="create_product_price" style='visibility:hidden; position:absolute' class="input-group form-create">
					<h2>Price:</h2>
					<input class="form-control" type="text" placeholder="Price" name="create_product_price" value = ''>
				</div>
				<div id="create_disc_price" style='visibility:hidden; position:absolute' class="input-group form-create">
					<h2>Discounted Price:</h2>
					<input class="form-control" type="text" placeholder="Discounted Price" name="create_disc_price" value = ''>
				</div>
				<div id="create_product_status" style='visibility:hidden; position:absolute' class="form-create">
					<h2>Product Status:</h2><br />
					Is the product available
					<input class="" type="checkbox" name='create_product_status'>
				</div>
				<div id="create_product_image" style='visibility:hidden; position:absolute' class="input-group form-create">
					<h2>Add Image:</h2>
					<input type="file" name="create_product_image">
				</div>
				<button style='margin-top:130px;' id='' type="submit" class="btn btn-success col-xs-3 col-md-3">Submit</button>
    		</form>
          	
          	<!-- for editing existing product details -->

          	<form id='change_product_details' role="form" action="" method='post' style="display:none">
    			<input type='hidden' id='change_product_id' name='change_product_id' value='' required>
    			<input type='hidden' id='action' name='action' value='' required>
				<div id="change_product_name" style='display:none' class="input-group form-edit">
					<h2>Name:</h2>
					<input class="form-control" type="text" placeholder="Product Name" name="change_product_name" value = ''>
				</div>
				<div id="change_product_desc" style='display:none' class="input-group form-edit">
					<h2>Description:</h2>
					<textarea type="text" class="form-control" placeholder="Product Description" name="change_product_desc"></textarea>
				</div>
				<div id="change_product_code" style='display:none' class="input-group form-edit">
					<h2>Item Code:</h2>
					<input class="form-control" type="text" placeholder="Product Name" name="change_product_code" value = ''>
				</div>
				<div id="change_dep_id" style='display:none' class="input-group form-edit">
					<h2>Department</h2>
					<select class="form-control" name="change_dep_id">

					</select>
				</div>
				<div id="change_brand_id" style='display:none' class="input-group form-edit">
					<h2>Brand</h2>
					<select class="form-control" name="change_brand_id">

					</select>
				</div>
				<div id="change_product_price" style='display:none' class="input-group form-edit">
					<h2>Price:</h2>
					<input class="form-control" type="text" placeholder="Price" name="change_product_price" value = ''>
				</div>
				<div id="change_disc_price" style='display:none' class="input-group form-edit">
					<h2>Discounted Price:</h2>
					<input class="form-control" type="text" placeholder="Discounted Price" name="change_disc_price" value = ''>
				</div>
				<div id="change_product_status" style='display:none' class="form-edit">
					<h2>Product Status:</h2><br />
					Is the product available
					<input class="" type="checkbox" name='change_product_status'>
				</div>
				<div id="change_product_colour" style='display:none' class="input-group form-edit">
					<h2>Stock</h2>
					Colour
					<select class="form-control" name="change_product_colour">
					</select>
				</div>
				<div id="change_product_size" style='display:none' class="input-group form-edit">
					Size
					<select class="form-control" name="change_product_size">
						
					</select>
				</div>
				<div id="change_product_stock" style='display:none' class="input-group form-edit">
					Stock
					<input class="form-control" type="number" placeholder="Stock" name="change_product_stock" value = '0'>
				</div>
				<br />
				<button id='submit_button' type="submit" class="btn btn-success col-xs-6 col-md-6">Submit</button>
    		</form>
    		<div id='image_display'>
    			<div id='current_images'>
    			</div>
    			 <form id='add_image' enctype='multipart/form-data' role='form'>
    				<input type='hidden' id='' name='action' value='add' required>
    				<input type='hidden' id='' name='upload_item_id' value='' required>
					<input name="item_image" type="file" class="">
					<br />
					<button id='' type='submit' class="btn btn-success col-md-3"> Add </button>
				</form>		 			
    		</div>
        </div>
      </div>
      </div>

	<?php include_once 'navbar.php' ?>

<script src="../asset/js/jquery.js"></script>
<script src="../asset/js/bootstrap.js"></script>

<script type="text/javascript">

	document.getElementById('products').className +=  "active";
	var information = null;
	var selected_item = null;
	function display_info(item_id, field){
		if(event){
			event.stopImmediatePropagation();
		}
		if(field == null)
			field = 'name';
		if(item_id == 0){
			item_id = parseInt($("ul#product_list li:first").attr("id"));
		}		
		selected_item = item_id;
		$.ajax({
           type: "POST",
           async: false,
           url: '../controller/product_details.php',
           data: {'change_product_id':selected_item, 'action':'show'}, 
           dataType : 'json',
           success: function(msg)
           {
           		information = msg;
           }	
        });
		show(field);  
	}

	function show(field){
        $('#statusdiv').hide();
        if(selected_item == null){
        	show_new(field);
        	return ;
        }
        document.getElementById("change_product_details").style.display = '';
        document.getElementById("image_display").style.display = 'none';
		document.getElementById('change_product_id').value = selected_item;
		document.getElementById('change_product_details').style.display = '';
		var elements = document.getElementsByClassName('form-edit');
		for(var i = 0; i < elements.length; i++){
			elements[i].style.display = 'none';
		}
		document.getElementById('submit_button').style.display = '';
		document.getElementById('add_product_details').style.display = 'none';
		document.getElementById('action').value = field;
		if(field == 'name'){
			document.getElementById('change_product_name').style.display = '';	
			document.getElementsByName('change_product_name')[0].value = information['item_name'];
		}
		else if(field == 'desc'){
			document.getElementById('change_product_desc').style.display = '';
			document.getElementsByName('change_product_desc')[0].value = information['item_desc'];	
		}
		else if(field == 'code'){
			document.getElementById('change_product_code').style.display = '';			
			document.getElementsByName('change_product_code')[0].value = information['item_code'];		
		}
		else if(field == 'department'){
			document.getElementById('change_dep_id').style.display = '';

			$.ajax({
	           type: "POST",
	           url: '../controller/dep_details.php',
	           async: false,
	           data: {'action':'dep_list'},
	           success: function(msg)
	           {
	           		departments = JSON.parse(msg);
	           		document.getElementsByName('change_dep_id')[0].innerHTML = '';
	           		for(var key in departments){
						if(information['dep_id'] == key)
							document.getElementsByName('change_dep_id')[0].innerHTML += '<option value="'+key+'" selected>'+ departments[key]['dep_name'] +'</option>';
						else 
							document.getElementsByName('change_dep_id')[0].innerHTML += '<option value="'+key+'" >'+ departments[key]['dep_name'] +'</option>';
					} 
	           }
	        });		
		}
		else if(field == 'brand'){
			document.getElementById('change_brand_id').style.display = '';

			$.ajax({
	           type: "POST",
	           url: '../controller/brand_details.php',
	           async: false,
	           data: {'action':'brand_list'},
	           success: function(msg)
	           {
	           		brand_list = JSON.parse(msg);
	           		document.getElementsByName('change_brand_id')[0].innerHTML = '<option value="0"></option>';
	           		for(var key in brand_list){
						if(information['brand_id'] == key)
							document.getElementsByName('change_brand_id')[0].innerHTML += '<option value="'+key+'" selected>'+ brand_list[key]['brand_name'] +'</option>';
						else 
							document.getElementsByName('change_brand_id')[0].innerHTML += '<option value="'+key+'" >'+ brand_list[key]['brand_name'] +'</option>';
					} 
	           }
	        });		
		}
		else if(field == 'price'){
			document.getElementById('change_product_price').style.display = '';	
			document.getElementsByName('change_product_price')[0].value = information['item_price'];
		}
		else if(field == 'disc_price'){
			document.getElementById('change_disc_price').style.display = '';	
			document.getElementsByName('change_disc_price')[0].value = information['item_disc_price'];
		}
		else if(field == 'status'){
			document.getElementById('change_product_status').style.display = '';
			if(information['dish_status'] == '1')	
				document.getElementsByName('change_product_status')[0].checked = true;
			else
				document.getElementsByName('change_product_status')[0].checked = false;
		}
		else if(field == 'images'){
        	document.getElementById("change_product_details").style.display = 'none';
        	document.getElementById("image_display").style.display = '';
        	document.getElementsByName("upload_item_id")[0].value = selected_item;
        	show_images();
		}
		else if(field == 'coloursize'){
			document.getElementById('change_product_size').style.display = '';	
			document.getElementById('change_product_colour').style.display = '';	
			document.getElementById('change_product_stock').style.display = '';	

			$.ajax({
	           type: "POST",
	           url: '../controller/product_details.php',
	           async: false,
	           data: {'action':'show_colour'},
	           success: function(msg)
	           {
	           		colour_list = JSON.parse(msg);
	           		for(var key in colour_list){
						document.getElementsByName('change_product_colour')[0].innerHTML += '<option value="'+key+':'+colour_list[key]['colour_name']+'" >'+ colour_list[key]['colour_name'] +'</option>';
					} 
	           }
	        });

			$.ajax({
	           type: "POST",
	           url: '../controller/product_details.php',
	           async: false,
	           data: {'action':'show_size'},
	           success: function(msg)
	           {
	           		size_list = JSON.parse(msg);
	           		for(var key in size_list){
						document.getElementsByName('change_product_size')[0].innerHTML += '<option value="'+key+':'+size_list[key]['size_name']+'" >'+ size_list[key]['size_name'] +'</option>';
					} 
	           }
	        });				
		}
	}

	function show_images(){
		$.ajax({
           type: "POST",
           url: '../controller/item_images.php',
           async: false,
           data: {'action':'show', 'item_id': selected_item},
           success: function(msg)
           {
           		document.getElementById('current_images').innerHTML = "<h2>Images</h2><br />";
           		image_list = JSON.parse(msg);
           		for(var i in image_list){
					document.getElementById('current_images').innerHTML += "<img height='60px' width='60px' src=\'"+image_list[i]['image_url']+"\'>&nbsp;&nbsp;&nbsp;&nbsp;";
					document.getElementById('current_images').innerHTML += "<button class='btn btn-danger' onclick=\'remove_image("+image_list[i]['image_id']+")\'>Delete</button><br /><br />";
				} 
           }
        });		
	}

	function remove_image(image_id){
		$.ajax({
           type: "POST",
           url: '../controller/item_images.php',
           async: false,
           data: {'action':'delete', 'image_id': image_id},
           success: function(msg)
           {
           		if(msg == 'success') { // show response from the php script.
           			$('#statusdiv').hide();
           			alert("Image removed successfully");
           			show_images();
           		}
           		else {
           			$('#statusdiv').html(msg);
           			$('#statusdiv').show();
           		} 
           },
        });		
	}
	function show_new(field){
		
		if(field!='code' && field!='name' && field!='desc' && field!='price' && field!='disc_price' && field!='status' && field!='images' && field!='department' && field!='brand')
			return ;
		var elements = document.getElementsByClassName('form-create');
		for(var i = 0; i < elements.length; i++){
			elements[i].style.visibility = 'hidden';
		}

		if(field == 'name'){
			document.getElementById('create_product_name').style.visibility = 'visible';
		}
		else if(field == 'desc'){
			document.getElementById('create_product_desc').style.visibility = 'visible';
		}
		else if(field == 'code'){
			document.getElementById('create_product_code').style.visibility = 'visible';
		}
		else if(field == 'price'){
			document.getElementById('create_product_price').style.visibility = 'visible';
		}
		else if(field == 'disc_price'){
			document.getElementById('create_disc_price').style.visibility = 'visible';
		}
		else if(field == 'status'){
			document.getElementById('create_product_status').style.visibility = 'visible';
		}
		else if(field == 'images'){
			document.getElementById('create_product_image').style.visibility = 'visible';
		}
		else if(field == 'department'){
			document.getElementById('create_dep_id').style.visibility = 'visible';
			dep_already_selected = document.getElementsByName('create_dep_id')[0].value
			$.ajax({
	           type: "POST",
	           url: '../controller/dep_details.php',
	           async: false,
	           data: {'action':'dep_list'},
	           success: function(msg)
	           {
	           		departments = JSON.parse(msg);
					document.getElementsByName('create_dep_id')[0].innerHTML = '<option value="0"></option>';
	           		for(var key in departments){
	           			if(dep_already_selected == key)
							document.getElementsByName('create_dep_id')[0].innerHTML += '<option selected value="'+key+'" >'+ departments[key]['dep_name'] +'</option>';
						else
							document.getElementsByName('create_dep_id')[0].innerHTML += '<option value="'+key+'" >'+ departments[key]['dep_name'] +'</option>';
					} 
	           }
	        });		
		}
		else if(field == 'brand'){
			document.getElementById('create_brand_id').style.visibility = 'visible';
			brand_already_selected = document.getElementsByName('create_brand_id')[0].value
			$.ajax({
	           type: "POST",
	           url: '../controller/brand_details.php',
	           async: false,
	           data: {'action':'brand_list'},
	           success: function(msg)
	           {
	           		brands = JSON.parse(msg);
					document.getElementsByName('create_brand_id')[0].innerHTML = '<option value="0"></option>';
	           		for(var key in departments){
	           			if(brand_already_selected == key)
							document.getElementsByName('create_brand_id')[0].innerHTML += '<option selected value="'+key+'" >'+ brands[key]['brand_name'] +'</option>';
						else
							document.getElementsByName('create_brand_id')[0].innerHTML += '<option value="'+key+'" >'+ brands[key]['brand_name'] +'</option>';
					} 
	           }
	        });		
		}
	}
	function add_new_product(){
		selected_item = null;
		information = null;
		document.getElementById('change_product_details').style.display = 'none';
		document.getElementById('image_display').style.display = 'none';
		document.getElementById('add_product_details').style.display = '';
		document.getElementsByName('create_product_status')[0].checked = true
		show('name');
	}
	
	$("#change_product_details").submit(function() {

    	$.ajax({
           type: "POST",
           url: "../controller/product_details.php",
           data: $("#change_product_details").serialize(), // serializes the form's elements.
           success: function(msg)
           {
                if(msg == 'success') { // show response from the php script.
           			$('#statusdiv').hide();
           			alert("Details Sucessfully Changed");
           			display_info(selected_item, document.getElementById('action').value);
           		}
           		else {
           			$('#statusdiv').html(msg);
           			$('#statusdiv').show();
           		} 
           }	
        });

    	return false; // avoid to execute the actual submit of the form.
	});

	$("#add_product_details").on('submit', (function(e) {
	   
	    e.preventDefault();
	    $.ajax({
		    url: "../controller/add_new_product.php",
		    type: 'POST',
		    data: new FormData(this),
		    contentType: false,
		    cache: false,
		    processData:false,

		    success: function(msg)
            {
                if(msg == 'success') { // show response from the php script.
           			$('#statusdiv').hide();
           			alert("Added new product");
           			location.reload();
           		}
           		else {
           			$('#statusdiv').html(msg);
           			$('#statusdiv').show();
           		} 
            }	
		}); 
	}));

	$("#add_image").on('submit', (function(e) {
	    
	    e.preventDefault();
	    $.ajax({
		    url: "../controller/item_images.php",
		    type: 'POST',
		    data: new FormData(this),
		    contentType: false,
		    cache: false,
		    processData:false,

		    success: function(msg)
           	{
                if(msg == 'success') { // show response from the php script.
           			$('#statusdiv').hide();
           			alert("Added new inage");
           			show_images();
           		}
           		else {
           			$('#statusdiv').html(msg);
           			$('#statusdiv').show();
           		} 
           }	
		}); 
	}));

	display_info(0, 'name');

	$("#search_products").keyup(function(){

		if($("#search_products").val() == ''){
			document.getElementById("product_search").style.display = 'none';
			document.getElementById("product_all").style.display = '';
			document.getElementById('change_product_details').style.display = 'none';
			display_info(0);
		}
		else {
			document.getElementById("product_search").style.display = '';
			document.getElementById("product_all").style.display = 'none';
	    	$.ajax({
	           type: "POST",
	           url: "../controller/search_products.php",
	           data: {'search_query': $("#search_products").val()}, 
	           success: function(msg)
	           {
	           		document.getElementById('change_product_details').style.display = 'none';
					selected_item = null;
					information = null;
	                if(msg == 'Fail') { // show response from the php script.
	           			$("#product_search").html("Query doesn't match any product");
						document.getElementById('change_product_details').style.display = 'none';
						selected_item = null;
						information = null;
	           		}
	           		else {
	           			$("#product_search").html(msg);
	           		} 
	           }	
	        });
	    }
	});

</script>

</body>
</html>