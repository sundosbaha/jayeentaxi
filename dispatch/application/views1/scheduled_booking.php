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
</head>

<body>

<?php include("include/header.php"); ?>

<div class="container-fluid">
   <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                       Vehicles
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-cog"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
                    <div class="panel-body">
                        <table class="table  table-hover general-table">
                            <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Driver Name</th>
                                <th>Driver ID</th>
                                <th>Vehicle Type</th>
                                <th>Vehicle No</th>
                                <th>Passenger Capacity</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>Test</td>
                                <td>emp0012</td>
                                <td>Fast</td>
                                <td>TN001</td>
                                <td>Speed</td>
                                <td data-toggle="modal" data-target="#assign"><span class="label label-danger label-mini">Assign</span></td>
                            </tr>
                           </tbody>
                           </table> 
                </section>
                
            </div>
        </div>
</div>
<div class="modal fade" id="assign" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Ride Later</h4>
        </div>
        <div class="modal-body">
           <table class="table  table-hover general-table">
                            <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Driver Name</th>
                                <th>Driver ID</th>
                                <th>Vehicle Type</th>
                                <th>Vehicle No</th>
                                <th>Passenger Capacity</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>Test</td>
                                <td>emp0012</td>
                                <td>Fast</td>
                                <td>TN001</td>
                                <td>Speed</td>
                                <td><span class="label label-danger label-mini">Assign</span></td>
                            </tr>
                           </tbody>
                           </table> 
      </div>
      
    </div>
  </div>
                            
                    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<!--<script src="js/bootstrap-datepicker.js"></script>-->
<!--<script src="js/bootstrap-timepicker.js"></script>-->
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>

 </body>
</html>