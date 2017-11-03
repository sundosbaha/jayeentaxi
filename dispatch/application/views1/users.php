<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Dispacho</title>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $base; ?>assets/css/custom.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $base; ?>assets/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $base; ?>assets/css/bootstrap-timepicker.min.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="<?php echo $base; ?>assets/css/bootstrap-fileupload.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $base; ?>assets/css/datatable.css"/>
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
</head>

<body>

<?php include("include/header.php"); ?>


<div class="container-fluid">
<div class="col-md-6 col-md-offset-3">
      
    
      <form action="" method="post"  role="form" style="padding: 4px;border: 1px solid #C3C0C0;" enctype="multipart/form-data">
<div class="form-group">
                    <div class="input-group">
                     <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                    
                        <input type="text" class="form-control" id="InputEmailFirst" name="uname" placeholder="Nombre
" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    
					
                       <input type="text" class="form-control" id="date_pck" name="ubdate" placeholder="Date Of Birth" required>      
                    </div>
                </div>
                 
                <div class="form-group">
                    <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                   
                        <input type="text" class="form-control" id="join_date" name="ujdate" placeholder="Date Of Joining" >        
                    </div>
                </div>
                    <div class="form-group">
                    <div class="input-group">
                       <span class="input-group-addon"><span class="glyphicon glyphicon-comment"></span></span>
                       
                       <input type="text" class="form-control" id="age" name="uage" placeholder="age" readonly required>
                     
                    </div>
                </div>
                  
                     <div class="form-group">
                 <!--   <div class="input-group">
                       <span class="input-group-addon"><span class="glyphicon glyphicon-picture"></span></span>
                     <span class="btn btn-default btn-file">
                   
                     
    Photo <input type="file" name="uimg" id="img" accept="image/*" required>
</span>
                    </div>-->
                     <div class="fileupload fileupload-new" data-provides="fileupload">
                      <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"> 
                      	  <img src="<?php echo $base; ?>assets/images/no-img.png" alt="" />
                       </div>
                      <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                      <div> <span class="btn btn-white btn-file"> <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span> <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                        <input type="file" class="default" accept="image/*" name="uimg" id="c_sImg" accept="image/*"  />
                        </span> <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a> </div>
                    </div>
                </div>
                   <div class="form-group">
                    <div class="input-group">
                       <span class="input-group-addon">Documents<!--<span class="glyphicon glyphicon-bookmark"></span>--></span>
                     <span class="btn btn-default btn-file">
    Documents <input type="file" name="udoc" class="doc_file" accept="application/*">
</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-group">
                     <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                     
                        <input type="email" class="form-control" id="InputEmailFirst" name="uemail" placeholder="Email" required>
                    </div>
                </div>
                
               <div class="form-group">
                    <div class="input-group">
                     <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                      
                        <input type="text" class="form-control" id="uuname" onBlur="press()" name="uuname" placeholder="Username" minlength="6" required>
                        
                    </div>
                    <p id="error" style="color:red;"></p>
                </div>
                <div class="form-group">
                    <div class="input-group">
                     <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                    
                        <input type="password" class="form-control" id="InputEmailFirst" name="upass" placeholder="Password" minlength="6" required>
                    </div>
                </div>
                
                  <div class="form-group">
                    <div class="input-group">
                     <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                    
                        <select class="form-control" id="InputEmailFirst" name="utype"  required>
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                   <button type="submit" id="btn" class="btn btn-primary">Save</button>
             

 
        </form>
   </div>
   <!--Table code starts here-->
   <div class="col-md-12">
   <section class="panel">
                    <header class="panel-heading">
                        User Detail
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-cog"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
                    <div class="panel-body">
                    <div class="adv-table">
                    <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline" role="grid"><table class="display table table-bordered table-striped dataTable" id="dynamic-table" aria-describedby="dynamic-table_info">
                    <thead>
                    <tr role="row"><th class="sorting" role="columnheader" tabindex="0" aria-controls="dynamic-table" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending" style="width: 232px;">Id</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="dynamic-table" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 313px;">Name</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="dynamic-table" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 285px;">Age</th><th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="dynamic-table" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 199px;">type</th><th class="hidden-phone sorting_desc" role="columnheader" tabindex="0" aria-controls="dynamic-table" rowspan="1" colspan="1" aria-sort="descending" aria-label="CSS grade: activate to sort column ascending" style="width: 143px;">email</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="dynamic-table" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending" style="width: 232px;">Status</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="dynamic-table" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending" style="width: 232px;">Edit</th></tr>
                    </thead>
                    
                   
                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                    <?php
					$i=1;
					foreach($user as $us)
					{
					?>
                    <tr class="gradeX odd">
                    <td class=" "><?php echo $i; ?></td>
                        <td class=" "><?php echo $us->name; ?></td>
                        <td class=" "><?php echo $us->age; ?></td>
                        <td class=" "><?php echo $us->type; ?></td>
                        <td class="center hidden-phone "><?php echo $us->email; ?></td>
                        <td class="center hidden-phone  sorting_1"> <select onChange="chan(<?php echo $us->id; ?>)" class="form-control" id="InputEmailFirst" name="utype"  required>
                        <option value="1"    <?php if($us->is_active == 1){ echo "selected";}?> >Active</option>
                        <option value="0" <?php if($us->is_active == 0){ echo "selected";}?>>Inactive</option>
                        </select></td>
                        <td class="center hidden-phone  sorting_1"><a href="<?php echo $base; ?>Users/Update/<?php echo $us->id; ?>"  class="btn btn-primary">Edit</a></td>
                    </tr>
                    <?php
					$i++;
					}
					?>
                   </tbody></table></div>
                    </div>
                    </div>
                </section>
             </div>   
   <!--Table code ends here-->
</div>







<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="<?php echo $base; ?>assets/js/jquery-1.8.3.min.js"></script>
<!--<script src="js/bootstrap-datepicker.js"></script>-->
<!--<script src="js/bootstrap-timepicker.js"></script>-->
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $base; ?>assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo $base; ?>assets/js/bootstrap-fileupload.js"></script>
<script src="<?php echo $base; ?>assets/js/jquery.dataTables.js"></script>
<script src="<?php echo $base; ?>assets/js/DT_bootstrap.js"></script>
<script src="<?php echo $base; ?>assets/js/dynamic_table_init.js"></script>
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

<script>
function press()
{
	var username=$('#uuname').val();
	$.ajax({
		type:'POST',
		url:"<?php echo $base; ?>Users/chckname",
		data:"username="+username,
		success: function(data)
		{
			if(data == 'false')
			{
				$('#uuname').css('border-color' ,'red');
				$('#btn').prop('disabled', true);
				$('#error').text('Username Already Exists');
			}
			else
			{
				$('#uuname').css('border-color' ,'#008313');
				$('#btn').prop('disabled', false);
				$('#error').empty();
			}
		},
		error: function(a, b, c)
		{
			console.log(a);
			console.log(b);
			console.log(c);
		}
	});
}
</script>

<script>
function chan(id)
{
	$.ajax({
		type:'POST',
		url:"<?php echo $base; ?>Users/changestatus",
		data:"id="+id,
		success: function(data)
		{
			console.log(data);
		},
		error: function(a, b, c)
		{
			console.log(a);
			console.log(b);
			console.log(c);
		}
	});
}
</script>

<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>


 </body>
</html>