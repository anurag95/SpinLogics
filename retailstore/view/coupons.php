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
<title>Coupons</title>

<link href="../asset/css/bootstrap/bootstrap.min.css" rel="stylesheet"/>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

<!-- Page specific Styles -->
<link rel="stylesheet" href="../asset/css/libs/datepicker.css" type="text/css"/>
<link rel="stylesheet" href="../asset/css/libs/fullcalendar.css" type="text/css"/>
<link rel="stylesheet" href="../asset/css/libs/fullcalendar.print.css" type="text/css" media="print"/>
<link rel="stylesheet" href="../asset/css/compiled/calendar.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="../asset/css/libs/daterangepicker.css" type="text/css"/>
<link rel="stylesheet" href="../asset/css/libs/jquery.datetimepicker.css" type="text/css"/>

</head>

<body id="dashboard">
	<?php include_once 'header.php' ?>
	<div style='margin-top:80px;' class="container">

		<div class="row">
		  <h1 style="text-align:center">Coupons</h1>
      <div class="col-md-8">
        <div class="row" id='show_coupons'>
          
        </div>
      </div>
      <div class="col-md-4">
          <br />
          <h3 id='add-heading' style="text-align:centr"> Add New Coupon<br /></h3>
          <h3 id='edit-heading' style="text-align:centr; display:none;"> Edit Coupon<br /></h3>
            <form role='form' action='' id='add_coupon' class='form-inline' novalidate>
                <div id='statusdiv' style='color:red;'><br /></div>
                <input type="hidden" name='action' value='add'>
                <input type="hidden" name='coupon_id' value=''>
                <label for="coupon_name">Name</label>
                <input type="text" name='coupon_name' placeholder='Coupon Name' class="form-control" required>
                <br /><br />
                <b>Type of Discount</b> <br />
                <input id='percent' type="radio" name='type_of_discount' value='percent' checked> Percent
                <input id='absolute' type="radio" name='type_of_discount' value='absolute'> Absolute
                <br />
                <input type="text" name='percent_off' placeholder='Discount' class="form-control" required>
                <input type="text" name='max_off' placeholder='Maximum Discount' class="form-control" required>
                <input type="text" name='absolute_off' placeholder='Discount' class="form-control" style="display:none;" required>
                <br /><br />
                <input type="checkbox" name='date' checked> Valid for certain dates 
                <br />
                  <input type="text" class="form-control" id="datetimepicker1" name="start_date" placeholder='Start Date' required>
                <input type="text" class="form-control" id="datetimepicker2" name="end_date" placeholder='Start Date' required>
                <br /><br />
                <input type="checkbox" name='price' checked> Valid between certain prices 
                <br />
                <input type="text" name='start_price' placeholder='Minimum Price' class="form-control">
                <input type="text" name='end_price' placeholder='Maximum Price' class="form-control">
                <br /><br />
                <input type="checkbox" name='number' checked> Valid for limited number of times 
                <br />
                <input type="number" name='valid_number' placeholder='Number of times' class="form-control" required> 
                <br /><br />
                <b>Coupon Status</b> <br />
                <input id='active' type="radio" name='coupon_status' value='active' checked> Active
                <input id='inactive' type="radio" name='coupon_status' value='inactive'> Inactive
                
                <br /><br />
                <button type='submit' class='btn btn-success'>Submit</button>
                <input type='button' onclick='resetform()' class='btn btn-primary' value='Cancel'>
            </form>        
        
      </div>
	  </div>
	
	<?php include_once 'navbar.php' ?>

<script src="../asset/js/jquery.js"></script>
<script src="../asset/js/bootstrap.js"></script>
<script src="../asset/js/jquery.nanoscroller.min.js"></script> 

<script src="../asset/js/bootstrap-datepicker.js"></script>
<script src="../asset/js/daterangepicker.js"></script>
<script src="../asset/js/bootstrap-timepicker.min.js"></script>
<script src="../asset/js/jquery.datetimepicker.js"></script>

<script type="text/javascript">
  document.getElementById('coupons').className +=  "active";

  function resetform(){
    document.getElementById('add_coupon').reset();
    document.getElementsByName('coupon_id')[0].value = '';
    document.getElementsByName('start_price')[0].style.display = '';
    document.getElementsByName('end_price')[0].style.display = '';
    document.getElementsByName('valid_number')[0].style.display = '';
    document.getElementById('datetimepicker1').style.display = '';
    document.getElementById('datetimepicker2').style.display = '';
    document.getElementsByName('max_off')[0].style.display = '';
    document.getElementsByName('percent_off')[0].style.display = '';
    document.getElementsByName('absolute_off')[0].style.display = 'none';
    document.getElementById('edit-heading').style.display = 'none';
    document.getElementById('add-heading').style.display = '';
  }
  $(document).ready(function(){
     $.ajax({
           type: "POST",
           url: "../controller/coupons.php",
           async: false,
           data: {'action':'show_all'}, 
           success: function(msg)
           {
                div = document.getElementById("show_coupons");
                coupons = JSON.parse(msg) ;
                for(var key in coupons){
                  var str = '';
                  str += "<div class='col-md-6'><br /><h3>"+coupons[key]['coupon_name'];
                  str += "<button style='margin-left:30px;' class='btn btn-danger' onclick=\'call_delete("+key+")\' >Delete</button>";
                  str += "<button style='margin-left:5px;' class='btn btn-primary' onclick=\'call_edit("+key+")\' >Edit</button> </h3>";
                  if(coupons[key]['percent_discount'] == 1){ // type od discount
                      str += coupons[key]['percent_value']+"% discount with maximum discount upto "+coupons[key]['max_value'];
                  }
                  else {
                    str += "Dscount of flat "+coupons[key]['absolute_value'];
                  }
                  str += "<br />";
                  if(coupons[key]['price_status'] == 1){  // price range for coupon
                   //  str += "<br />";
                    if(coupons[key]['start_price'] == 0){
                      str += "Valid on purchase upto "+coupons[key]['end_price'];
                    }
                    else if(coupons[key]['end_price'] == 0){
                      str += "Valid on minimum purchase of "+coupons[key]['start_price'];
                    }
                    else str += "Valid on purchase from "+coupons[key]['start_price']+" to "+coupons[key]['end_price'];
                    str += "<br />";
                  }
                  if(coupons[key]['number_status'] == 1){ // validity number of times
                   // str += '<br />';
                    if(coupons[key]['valid_no_of_times'] == 1){
                      str += "Valid only once per user";
                    }
                    else {
                      str += "Valid for "+coupons[key]['valid_no_of_times']+" times per user";
                    }
                    str += "<br />";
                  }
                  if(coupons[key]['date_status'] == 1){  // dates of coupon
                    str += "<b>Starting Date: </b>"+coupons[key]['start_date']+"<br />";
                    str += "<b>Ending Date: </b>"+coupons[key]['end_date']+"<br />";
                  }
                  if(coupons[key]['coupon_status'] == 1){
                   str += "<b>Coupon Status:</b> Active";
                  }
                  else str += "<b>Coupon Status: </b> Inactive";
                  str += "</div>";
                  div.innerHTML += str;
                } 
           }  
        });   
  });

  $("[name='price']").click(function(){
    if(document.getElementsByName('price')[0].checked == true){
      document.getElementsByName('start_price')[0].style.display = '';
      document.getElementsByName('end_price')[0].style.display = '';
    }
    else {
      document.getElementsByName('start_price')[0].style.display = 'none';
      document.getElementsByName('end_price')[0].style.display = 'none'; 
    }
  });
   $("[name='number']").click(function(){
      if(document.getElementsByName('number')[0].checked == true){
        document.getElementsByName('valid_number')[0].style.display = '';
      }
      else {
        document.getElementsByName('valid_number')[0].style.display = 'none';
      }
  });

  $("[name='date']").click(function(){
    if(document.getElementsByName('date')[0].checked == true){
      document.getElementById('datetimepicker1').style.display = '';
      document.getElementById('datetimepicker2').style.display = '';
    }
    else {
      document.getElementById('datetimepicker1').style.display = 'none';
      document.getElementById('datetimepicker2').style.display = 'none'; 
    }
  });
  $("[name='type_of_discount']").click(function(){
    if($("input[name='type_of_discount']:checked").val() == 'percent'){
      document.getElementsByName('max_off')[0].style.display = '';
      document.getElementsByName('percent_off')[0].style.display = '';
      document.getElementsByName('absolute_off')[0].style.display = 'none';
    }
    else {
      document.getElementsByName('max_off')[0].style.display = 'none';
      document.getElementsByName('percent_off')[0].style.display = 'none';
      document.getElementsByName('absolute_off')[0].style.display = '';
    }
  });

  function call_delete(coupon_id){
    $.ajax({
          type: "POST",
          url : "../controller/coupons.php", // the script where you handle the form input.
          data: {'action':'delete', 'coupon_id': coupon_id }, 
          success: function(msg)
          {
              //console.log(msg);
              if(msg == 'Fail') { // show response from the php script.
                alert("Failed to delete.");
              }
              else {
                alert("Successfully deleted coupon.");
                location.reload(); 
              } 
          }  
    }); 
  }

  function call_edit(coupon_id){
    document.getElementById('edit-heading').style.display = '';
    document.getElementById('add-heading').style.display = 'none';

    document.getElementsByName('coupon_id')[0].value = coupon_id;
    document.getElementsByName('coupon_name')[0].value = coupons[coupon_id]['coupon_name'];
    
    if(coupons[coupon_id]['percent_discount'] == 1){  // type of discount
      document.getElementById('percent').checked = true;
      document.getElementsByName('percent_off')[0].value = coupons[coupon_id]['percent_value'];
      document.getElementsByName('max_off')[0].value = coupons[coupon_id]['max_value'];
      document.getElementsByName('absolute_off')[0].value = '';
      document.getElementsByName('max_off')[0].style.display = '';
      document.getElementsByName('percent_off')[0].style.display = '';
      document.getElementsByName('absolute_off')[0].style.display = 'none';
    }
    else {
      document.getElementById('absolute').checked = true;
      document.getElementsByName('absolute_off')[0].value = coupons[coupon_id]['absolute_value'];
      document.getElementsByName('percent_off')[0].value = '';
      document.getElementsByName('max_off')[0].value = '';
      document.getElementsByName('max_off')[0].style.display = 'none';
      document.getElementsByName('percent_off')[0].style.display = 'none';
      document.getElementsByName('absolute_off')[0].style.display = '';      
    }

    if(coupons[coupon_id]['price_status'] == 1){ // price limits
      document.getElementsByName('price')[0].checked = true;
      document.getElementsByName('start_price')[0].style.display = '';
      document.getElementsByName('end_price')[0].style.display = '';      
      document.getElementsByName('end_price')[0].value = coupons[coupon_id]['end_price'];      
      document.getElementsByName('start_price')[0].value = coupons[coupon_id]['start_price'];      
    }
    else {
      document.getElementsByName('price')[0].checked = false;
      document.getElementsByName('start_price')[0].style.display = 'none';
      document.getElementsByName('end_price')[0].style.display = 'none';
      document.getElementsByName('end_price')[0].value = '';      
      document.getElementsByName('start_price')[0].value = '';      
   }

    if(coupons[coupon_id]['number_status'] == 1){ // validity number of times
      document.getElementsByName('number')[0].checked = true;
      document.getElementsByName('valid_number')[0].style.display = '';
      document.getElementsByName('valid_number')[0].value = coupons[coupon_id]['valid_no_of_times'];      
    }
    else {
      document.getElementsByName('number')[0].checked = false;
      document.getElementsByName('valid_number')[0].value = 0;      
      document.getElementsByName('valid_number')[0].style.display = 'none';
    }

    if(coupons[coupon_id]['date_status'] == 1){  // validity of dates
      document.getElementsByName('date')[0].checked = true;
      document.getElementById('datetimepicker1').style.display = '';
      document.getElementById('datetimepicker2').style.display = '';      
      document.getElementById('datetimepicker1').value = coupons[coupon_id]['start_date'];      
      document.getElementById('datetimepicker2').value = coupons[coupon_id]['end_date'];      
    }
    else {
      document.getElementsByName('date')[0].checked = false;
      document.getElementById('datetimepicker1').style.display = 'none';
      document.getElementById('datetimepicker2').style.display = 'none';      
      document.getElementById('datetimepicker1').value = '';      
      document.getElementById('datetimepicker2').value = '';      
    }

    if(coupons[coupon_id]['coupon_status'] == 1){
      document.getElementById('active').checked = true;
    }
    else 
      document.getElementById('inactive').checked = true;
  }

    $('#datetimepicker1').datepicker({
      format: 'yyyy-mm-dd'
    });
    $('#datetimepicker2').datepicker({
      format: 'yyyy-mm-dd'
    });
    $("#datetimepicker1").on("dp.change", function (e) {
        $('#datetimepicker2').data("DateTimePicker").minDate(e.date);
    });
    $("#datetimepicker2").on("dp.change", function (e) {
        $('#datetimepicker1').data("DateTimePicker").maxDate(e.date);
    });
  
  $("#add_coupon").submit(function() {

      $.ajax({
           type: "POST",
           url: "../controller/coupons.php",
           data: $("#add_coupon").serialize(), // serializes the form's elements.
           success: function(msg)
           {
                if(msg == 'success') { // show response from the php script.
                  alert("Coupon Sucessfully Added");
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

</script>
</body>
</html>