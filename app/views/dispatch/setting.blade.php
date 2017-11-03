@extends('dispatch.layout')


@section('content')


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
.doc_file {
	top: 4px !important;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 16px !important;
    text-align: right !important;
    filter: alpha(opacity=0);
    opacity: 2 !important;
    transform: unset !important;
}
</style>


<div class="container-fluid">
<div class="col-md-6 col-md-offset-3">
      
    
      <form action="" method="post"  role="form" style="padding: 4px;border: 1px solid #C3C0C0;" enctype="multipart/form-data">
<div class="form-group">
                    <div class="input-group">
                     <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                        <input type="text" class="form-control" id="InputEmailFirst" name="code" placeholder="Country Code" value="<?php echo $setting->configValue; ?>" required>
                    </div>
                </div>
              
                   <button type="submit" id="btn" class="btn btn-primary">Save</button>
             

 
        </form>
   </div>
</div>


                    
                    
                    @stop
                    
                    
                    
                    
                     @section('javascript')
                     
          <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="<?php echo asset_url(); ?>/dispatcher/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo asset_url(); ?>/dispatcher/js/bootstrap-fileupload.js"></script>
<script src="<?php echo asset_url(); ?>/dispatcher/js/jquery.dataTables.js"></script>
<script src="<?php echo asset_url(); ?>/dispatcher/js/DT_bootstrap.js"></script>
<script src="<?php echo asset_url(); ?>/dispatcher/js/dynamic_table_init.js"></script>           
                     
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




<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>

                     
                     
                     
                     
                     
                     @stop