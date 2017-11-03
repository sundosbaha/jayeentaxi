<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Dispatcher</title>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $base; ?>assets/css/custom.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $base; ?>assets/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $base; ?>assets/css/bootstrap-timepicker.min.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="<?php echo $base; ?>assets/css/bootstrap-fileupload.css"/>
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
                     <span class="input-group-addon" style="background:#23374a;"><span class="glyphicon glyphicon-user" style="color:white;"></span></span>
                    
                        <input type="text" class="form-control" id="InputEmailFirst" name="uname" placeholder="Name" value="<?php echo $user->name; ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                    <span class="input-group-addon" style="background:#23374a;"><span class="glyphicon glyphicon-calendar" style="color:white;"></span></span>
                    
					
                       <input type="text" class="form-control" id="date_pck" name="ubdate" value="<?php echo $user->dateofbirth; ?>" placeholder="Date Of Birth" required>      
                    </div>
                </div>
                 
                <div class="form-group">
                    <div class="input-group">
                    <span class="input-group-addon" style="background:#23374a;"><span class="glyphicon glyphicon-calendar" style="color:white;"></span></span>
                   
                        <input type="text" class="form-control" id="join_date" name="ujdate" value="<?php echo $user->dateofjoin; ?>" placeholder="Date Of Joining" >        
                    </div>
                </div>
                    <div class="form-group">
                    <div class="input-group">
                       <span class="input-group-addon" style="background:#23374a;"><span class="glyphicon glyphicon-comment" style="color:white;"></span></span>
                       
                       <input type="text" class="form-control" id="age" name="uage" placeholder="age" value="<?php echo $user->age; ?>" readonly required>
                     
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
                      	  <img src="<?php echo $base.$user->img; ?>" alt="" />
                       </div>
                      <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                      <div> <span class="btn btn-white btn-file"> <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span> <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                        <input type="file" class="default" accept="image/*" name="uimg" id="c_sImg"   />
                        </span> <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a> </div>
                    </div>
                </div>
                   <div class="form-group">
                    <div class="input-group">
                       <span class="input-group-addon" style="background:#23374a; color:#FFFFFF;"><!--<span class="glyphicon glyphicon-bookmark"></span>--> Documents </span>
                     <span class="btn btn-default btn-file" style="height:31px;">
    	 <input type="file" name="udoc" class="doc_file"  accept="application/*">
</span>
                    </div>
                    <?php if($user->doc != '') {?><a target="_blank" class="btn btn-primary" href="http://view.officeapps.live.com/op/view.aspx?src=<?php echo $user->doc; ?>">View Doc</a><?php } ?>
                </div>
                
                <div class="form-group">
                    <div class="input-group">
                     <span class="input-group-addon" style="background:#23374a;"><span class="glyphicon glyphicon-user" style="color:white;"></span></span>
                     
                        <input type="email" class="form-control" id="InputEmailFirst" value="<?php echo $user->email; ?>" name="uemail" placeholder="Email" required>
                    </div>
                </div>
                
               <div class="form-group">
                    <div class="input-group">
                     <span class="input-group-addon" style="background:#23374a;"><span class="glyphicon glyphicon-user"style="color:white;" ></span></span>
                      
                        <input type="text" class="form-control" id="uuname" onBlur="press()" name="uuname" value="<?php echo $user->username; ?>" placeholder="Username" minlength="6" required>
                        
                    </div>
                    <p id="error" style="color:red;"></p>
                </div>
                <div class="form-group">
                    <div class="input-group">
                     <span class="input-group-addon" style="background:#23374a;"><span class="glyphicon glyphicon-user" style="color:white;"></span></span>
                    
                        <input type="text" class="form-control" id="InputEmailFirst" name="upass" placeholder="Password" value="<?php echo $this->encrypt->decode($user->password); ?>" minlength="6" required>
                    </div>
                </div>
                
                  <div class="form-group">
                    <div class="input-group">
                     <span class="input-group-addon" style="background:#23374a;"><span class="glyphicon glyphicon-user" style="color:white;"></span></span>
                    
                        <select class="form-control" id="InputEmailFirst" name="utype"  required>
                        <option value="staff" <?php if($user->type == 'staff'){ echo "selected";} ?>>Staff</option>
                        <option value="admin" <?php if($user->type == 'admin'){ echo "selected";} ?>>Admin</option>
                        </select>
                    </div>
                </div>
                   <button type="submit" id="btn"  style="background:#23374a;" class="btn btn-primary">Save</button>
             

 
        </form>
   </div>
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
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>


 </body>
</html>