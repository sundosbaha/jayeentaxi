<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Dispatcher</title>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $base; ?>assets/css/custom.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $base; ?>assets/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $base; ?>assets/css/bootstrap-timepicker.min.css" rel="stylesheet"/>
<!--<link rel="stylesheet" type="text/css" href="css/bootstrap-timepicker.min.css"/>-->
<style>
   .btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}
.btn-default {
	width: 100%;
}
.datepicker th.next, .datepicker th.prev {
	cursor:pointer;
}
</style>
</head>

<body>

<?php include("include/header.php"); ?>


<div class="container-fluid">
<div class="col-md-6 col-md-offset-3">
      
    
      <form action="<?php echo base_url() ?>Settings/updateCountrycode" method="post"  role="form" style="
    padding: 4px;
    border: 1px solid #C3C0C0;
">
<div class="form-group">
                    <div class="input-group">
                     <span class="input-group-addon">Country Code</span>
                    
                        <input type="text" class="form-control" id="country_code" name="country_code" placeholder="Country Code" value="<?php echo $country_code; ?>" required>
                    </div>
                </div>
    
                   
                
                
                
             
              
                   <button type="submit" class="btn btn-primary">Save</button>
             

 
        </form>
   </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<!--<script src="js/bootstrap-datepicker.js"></script>-->
<!--<script src="js/bootstrap-timepicker.js"></script>-->
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.js"></script>
<script src="<?php echo $base; ?>assets/js/bootstrap-timepicker.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.2/jquery.timepicker.min.js"></script>-->

<!--<script src="css/bootstrap-timepicker.min.css"></script>-->

<script type="text/javascript">

$('#date_pck').focus(function(){
var dob=$(this).val();
dob = new Date(dob);
var today = new Date();
var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
$('#age').val(age);
	});
	
  $('#date_pck').datepicker({
				format: 'yyyy-mm-dd'
			});
  $('#join_date').datepicker({
				format: 'yyyy-mm-dd'
   });
</script>


 </body>
</html>