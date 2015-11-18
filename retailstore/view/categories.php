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
<title>Categories</title>

<link href="../asset/css/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href="../asset/css/bootstrap/bootstrap-switch.css" rel="stylesheet">
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

</head>

<body id="">
	<?php include_once 'header.php' ?>

	<div style='margin-top:80px;' class="container">
	<div class="row">    	
        <div class="col-md-4">
          <h2>Departments</h2>
          <div class="input-group">
			<input id="search_dep" class="form-control" type="text" placeholder="Search" name="search_dep" value = '' required>
		  </div>
		  <br />
	      <span onclick='create_new_dep()'>Create New Department</span><br /><br />
		  <div id='dep_all'>
	          <?php
	          	$tree = array();
	          	$query = $mysqli->query("SELECT * FROM tbldep WHERE store_id='{$id}'");
	          	while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	          		$tree[$row['dep_id']] = array($row['dep_id'], $row['dep_name'], $row['dep_desc'], $row['dep_priority'], $row['parent_id']);          		
	          	}
	          	$information = $tree;
		        function parseTree($root = 0) {
		        	global $tree;
				    $return = array();
				    # Traverse the tree and search for direct children of the root
				    foreach($tree as $dep_id => $dep_info) {
				        # A direct child is found
				        if($dep_info[4] == $root) {
				            # Remove item from tree (we don't need to traverse this again)
				            unset($tree[$dep_id]);
				            # Append the child into result array and parse its children
				            $return[] = array(
				                'name' => $dep_info,
				                'children' => parseTree($dep_id)
				            );
				        }
				    }
				    return empty($return) ? null : $return;    
				}
				function printTree($tree) {
				    if(!is_null($tree) && count($tree) > 0) {
				        echo '<ul>';
				        foreach($tree as $node) {
				        	$dep_id = $node['name'][0];
				            echo '<li onclick=\'display_info('.$dep_id.')\'>'.$node['name'][1];
				            printTree($node['children']);
				            echo '</li>';
				        }
				        echo '</ul>';
				    }
				}
				while(count($tree)>0){
					$result = parseTree();
					printTree($result);
				}
	          ?>
          </div>
          <div id='dep_search'>
          </div>
        </div>
        <div class="col-md-4">
        	<div id='dep_properties' style=''>
        		<h2>Department Details</h2>
        		<span id='' onclick='show("name")'>Name</span><br />
        		<span id='' onclick='show("desc")'>Description</span><br />
        		<span id='' onclick='show("priority")'>Priority</span><br />
        		<span id='' onclick='show("tax_rate")'>Tax Rate</span><br />
        		<span id='' onclick='show("parent")'>Nesting</span><br />
        	</div> 
       	</div>
        <div class="col-md-4">
          	<div id='statusdiv' style='color:red;'></div>

          	<!-- for entering new department details -->

          	<form id='create_dep_details' role="form" action="" method='post' style="display:none">
    			<input type='hidden' id='action_new' name='action_new' value='create' required>
				<div id="create_dep_name" style='visibility:hidden; position:absolute' class="input-group form-create">
					<h2>Name:</h2>
					<input class="form-control" type="text" placeholder="Department Name" name="create_dep_name" value = ''>
				</div>
				<div id="create_dep_desc" style='visibility:visible; position:absolute' class="input-group form-create">
					<h2>Description:</h2>
					<textarea type="text" class="form-control" placeholder="Department Description" name="create_dep_desc"></textarea>
				</div>
				<div id="create_dep_priority" style='visibility:hidden; position:absolute' class="input-group form-create">
					<h2>Priority</h2>
					<input class="form-control" type="text" placeholder="Department Priority" name="create_dep_priority" value = ''>
				</div>
				<div id="create_dep_parent" style='visibility:hidden; position:absolute' class="input-group form-create">
					<h2>Nest Label Under:</h2>
					<select class="form-control" name="create_dep_parent">

					</select>
				</div>
				<button style='margin-top:130px;' id='submit_button' type="submit" class="btn btn-success col-xs-3 col-md-3">Submit</button>
    		</form>
          	
          	<!-- for editing existing department details -->

          	<form id='change_dep_details' role="form" action="" method='post' style="display:none">
    			<input type='hidden' id='change_dep_id' name='change_dep_id' value='' required>
    			<input type='hidden' id='action' name='action' value='' required>
				<div id="change_dep_name" style='display:none' class="input-group form-edit">
					<h2>Name:</h2>
					<input class="form-control" type="text" placeholder="Department Name" name="change_dep_name" value = ''>
				</div>
				<div id="change_dep_desc" style='display:none' class="input-group form-edit">
					<h2>Description:</h2>
					<textarea type="text" class="form-control" placeholder="Department Description" name="change_dep_desc"></textarea>
				</div>
				<div id="change_dep_priority" style='display:none' class="input-group form-edit">
					<h2>Priority</h2>
					<input class="form-control" type="text" placeholder="Department Priority" name="change_dep_priority" value = ''>
				</div>
				<div id="change_dep_parent" style='display:none' class="input-group form-edit">
					<h2>Nest Label Under:</h2>
					<select class="form-control" name="change_dep_parent">

					</select>
				</div>
				<br />
				<button id='submit_button' type="submit" class="btn btn-success col-xs-6 col-md-6">Submit</button>
    		</form>

    		<div id='tax_information'>   		 			
    		</div>
    		
    		<form id='add_new_tax' role="form" action="" method='post' style="display:none">
    			<h2>Add New Tax</h2>
    			<input type='hidden' id='dep_for_tax' name='dep_for_tax' value='' required>
    			<input type='hidden' id='action' name='action' value='add' required>
				<div class="input-group">
					Tax Name:
					<input class="form-control" type="text" placeholder="Tax Name" name="tax_name">
				</div>
				<div class="input-group">
					Value:
					<input class="form-control" type="text" placeholder="Value" name="tax_value">
				</div>
				<input type="radio" name="tax_type" value="1" checked>Percent
				<br />
				<input type="radio" name="tax_type" value="0">Absolute
				<br />
				<button id='submit_button' type="submit" class="btn btn-success col-xs-6 col-md-6">Submit</button>
    		</form>
        </div>
      </div>
      </div>

	<?php include_once 'navbar.php' ?>

<script src="../asset/js/jquery.js"></script>
<script src="../asset/js/bootstrap.js"></script>
<script src="../asset/js/bootstrap-switch.js"></script>

<script type="text/javascript">

	document.getElementById('categories').className +=  "active";

	var information = <?php echo json_encode($information); ?>;
	var selected = null;

	function display_info(dep_id){
		if(event){
			event.stopImmediatePropagation();
		}
		if(dep_id == 0){
			for(var key in information) {
				if(information[key][4] == 0){
					dep_id = key;
					break;
				}
			}
		}		
		selected = dep_id;
		show('name');
	}

	function show(field){

        $('#statusdiv').hide();
        if(selected == null){
        	show_new(field);
        	return ;
        }

		document.getElementById('action').value = field;
		document.getElementById('change_dep_id').value = selected;
		document.getElementById('change_dep_details').style.display = '';
		var elements = document.getElementsByClassName('form-edit');
		for(var i = 0; i < elements.length; i++){
			elements[i].style.display = 'none';
		}

		document.getElementById('tax_information').style.display = 'none';
		document.getElementById('add_new_tax').style.display = 'none';
		document.getElementById('create_dep_details').style.display = 'none';

		if(field == 'name'){
			document.getElementById('change_dep_name').style.display = '';	
			document.getElementsByName('change_dep_name')[0].value = information[selected][1];		
		}
		else if(field == 'desc'){
			document.getElementById('change_dep_desc').style.display = '';
			document.getElementsByName('change_dep_desc')[0].value = information[selected][2];		
		}
		else if(field == 'priority'){
			document.getElementById('change_dep_priority').style.display = '';			
			document.getElementsByName('change_dep_priority')[0].value = information[selected][3];		
		}
		else if(field == 'parent'){
			document.getElementById('change_dep_parent').style.display = '';
			document.getElementsByName('change_dep_parent')[0].innerHTML = '<option value="0"></option>';
			for(var key in information){
				tempkey = key;
				while(information[tempkey][4] != 0 && information[tempkey][4]!= selected){
					tempkey = information[tempkey][4];
				}
				if(information[tempkey][4] == selected)
					continue;
				if(information[selected][4] == information[key][0])
					document.getElementsByName('change_dep_parent')[0].innerHTML += '<option value="'+key+'" selected>'+ information[key][1] +'</option>';
				else if(information[key][0] != document.getElementById('change_dep_id').value)
					document.getElementsByName('change_dep_parent')[0].innerHTML += '<option value="'+key+'">'+ information[key][1] +'</option>';		
			}
		}
		else if(field == 'tax_rate'){
			document.getElementById('tax_information').style.display = '';
			document.getElementById('change_dep_details').style.display = 'none';
	
	    	$.ajax({
	           type: "POST",
	           url: "../controller/tax_information.php",
	           data: {'dep_id': selected, 'action':'show' }, // serializes the form's elements.
	           success: function(msg)
	           {
	           		tax = JSON.parse(msg);
	                var div = document.getElementById('tax_information');
	                div.innerHTML = "<h2>Tax Information</h2><br />";
	                div.innerHTML += "Tax : <input type='checkbox' name='dep_tax' checked><br />";
	                $("[name='dep_tax']").bootstrapSwitch('state', true);
	                $('input[name="dep_tax"]').on('switchChange.bootstrapSwitch', function(event, state) {
					  console.log(this.name); // DOM element
					  console.log(event); // jQuery event
					  console.log(state); // true | false
					});	
					for( key in tax){
						div.innerHTML += "<br />";
						if(tax[key]['status'] == 1)
							div.innerHTML +=  tax[key]['tax_name'] + " : <input onclick='change_tax_details("+tax[key]["dep_id"]+","+tax[key]["tax_id"]+")' type='checkbox' id='tax-"+tax[key]["dep_id"]+"-"+tax[key]["tax_id"]+"' checked>";
						else 
							div.innerHTML +=  tax[key]['tax_name'] + " : <input onclick='change_tax_details("+tax[key]["dep_id"]+","+tax[key]["tax_id"]+")' type='checkbox' id='tax-"+tax[key]["dep_id"]+"-"+tax[key]["tax_id"]+"' >";
					}
					div.innerHTML += '<br /><br />';
					div.innerHTML += "<button class='btn btn-primary' onclick='add_tax("+selected+")'>Add New Tax</button>";
	           }
	        });
		}
	}

	function change_tax_details(department, tax_id)
	{
		$.ajax({
           type: "POST",
           url: "../controller/tax_information.php",
           data: {'dep_id': department, 'tax_id':tax_id, 'action':'change' }, // serializes the form's elements.
           success: function(msg){
           		alert(msg);
           }
        });
	}

	function add_tax(department){
		document.getElementById('tax_information').style.display = 'none';
		document.getElementById('add_new_tax').style.display = '';
		document.getElementById('dep_for_tax').value = department;
	}

	$("#add_new_tax").submit(function() {

    	$.ajax({
           type: "POST",
           url: "../controller/tax_information.php",
           data: $("#add_new_tax").serialize(), // serializes the form's elements.
           success: function(msg)
           {
                if(msg == 'success') { // show response from the php script.
                	alert('Added new Tax');	
					document.getElementById('dep_for_tax').value = null;
					show("tax_rate");
           		}
           		else {
           			alert(msg);
           		} 
           }	
        });
    	return false; // avoid to execute the actual submit of the form.
	});

	function show_new(field){
		
		if(field!='parent' && field!='name' && field!='desc' && field!='priority')
			return ;	
		var elements = document.getElementsByClassName('form-create');
		for(var i = 0; i < elements.length; i++){
			elements[i].style.visibility = 'hidden';
		}
		document.getElementById('tax_information').style.display = 'none';

		if(field == 'name'){
			document.getElementById('create_dep_name').style.visibility = 'visible';			
		}
		else if(field == 'desc'){
			document.getElementById('create_dep_desc').style.visibility = 'visible';
		}
		else if(field == 'priority'){
			document.getElementById('create_dep_priority').style.visibility = 'visible';			
		}
		else if(field == 'parent'){			
			document.getElementById('create_dep_parent').style.visibility = 'visible';
			document.getElementsByName('create_dep_parent')[0].innerHTML = '<option value="0" checked></option>';
			for(var key in information){				
				document.getElementsByName('create_dep_parent')[0].innerHTML += '<option value="'+key+'">'+ information[key][1] +'</option>';		
			}
		}
	}
	function create_new_dep(){
		selected = null;
		document.getElementById('change_dep_details').style.display = 'none';
		document.getElementById('create_dep_details').style.display = '';
		document.getElementsByName('create_dep_priority')[0].value = 0;
		show('name');
	}

	$("#change_dep_details").submit(function() {

    	$.ajax({
           type: "POST",
           url: "../controller/dep_details.php",
           data: $("#change_dep_details").serialize(), // serializes the form's elements.
           success: function(msg)
           {
                if(msg == 'success') { // show response from the php script.
           			$('#statusdiv').hide();
           			alert("Details Sucessfully Changed");
           			location.reload();
           		}
           		else {
           			$('#statusdiv').html(msg);
           			$('#statusdiv').show();
           		} 
           }	
        });
    	return false; // avoid to execute the actual submit of the form.
	});

	$("#create_dep_details").submit(function() {

    	$.ajax({
           type: "POST",
           url: "../controller/create_new_department.php",
           data: $("#create_dep_details").serialize(), // serializes the form's elements.
           success: function(msg)
           {
                if(msg == 'success') { // show response from the php script.
           			$('#statusdiv').hide();
           			alert("Created New Department");
           			location.reload();
           		}
           		else {
           			$('#statusdiv').html(msg);
           			$('#statusdiv').show();
           		} 
           }	
        });
    	return false; // avoid to execute the actual submit of the form.
	});
	$(document).ready(function(){
		display_info(0);
	});

	$("#search_dep").keyup(function(){

		if($("#search_dep").val() == ''){
			document.getElementById("dep_search").style.display = 'none';
			document.getElementById("dep_all").style.display = '';
			document.getElementById('change_dep_details').style.display = 'none';
			display_info(0);
		}
		else {
			document.getElementById("dep_search").style.display = '';
			document.getElementById("dep_all").style.display = 'none';
	    	$.ajax({
	           type: "POST",
	           url: "../controller/search_dep.php",
	           data: {'search_query': $("#search_dep").val()}, // serializes the form's elements.
	           success: function(msg)
	           {
	           		document.getElementById('change_dep_details').style.display = 'none';
					selected = null;
	                if(msg == 'Fail') { // show response from the php script.
	           			$("#dep_search").html("Query doesn't match any department");
						document.getElementById('change_dep_details').style.display = 'none';
						selected = null;
	           		}
	           		else {
	           			$("#dep_search").html(msg);
	           		} 
	           }	
	        });
	    }
	});

</script>

</body>
</html>