<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Dispacho</title>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="css/custom.css"/>
<link rel="stylesheet" type="text/css" href="css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="css/bootstrap-timepicker.min.css" rel="stylesheet"/>
<!--<link rel="stylesheet" type="text/css" href="css/bootstrap-timepicker.min.css"/>-->
</head>

<body>

<?php include("include/header.php"); ?>

<div class="container-fluid">
   <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                      
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
                                <th>ID de solicitud
</th>
                                <th>Nombre del Cliente
</th>
                                <th>Nombre del Conductor
</th>
                                <th>Estado
</th>
                                <th>Cantidad
</th>
                                <th>Estado del pago</th>
                                
                      
                                <th>Ejecutivo</th>
                                <th>Origen
</th>
                                <th>Destino
</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                            <?php
							foreach($all_bookings as $bookings)
							{
							?>
                            <tr>
                                <td><?php  echo $bookings->id ?></td>
                                <td><?php echo $bookings->owner_first_name.' '.$bookings->owner_last_name ?></td>
                                <td><?php echo $bookings->walker_first_name.' '.$bookings->walker_last_name ?></td>
                                <td>
                          
                              <?php
                        if ($bookings->is_cancelled == 1) {
                            echo "<span class='badge bg-red'>Cancelled</span>";
                        } elseif ($bookings->is_completed == 1) {
                            echo "<span class='badge bg-green'>Completed</span>";
                        } elseif ($bookings->is_started == 1) {
                            echo "<span class='badge bg-yellow'>Started</span>";
                        } elseif ($bookings->is_walker_arrived == 1) {
                            echo "<span class='badge bg-yellow'>Walker Arrived</span>";
                        } elseif ($bookings->is_walker_started == 1) {
                            echo "<span class='badge bg-yellow'>Walker Started</span>";
                        } else {
                            echo "<span class='badge bg-light-blue'>Yet To Start</span>";
                        }
                        ?>
                                </td>
                                <td><?php echo $bookings->total ?></td>
                                <td>
								
                                   <?php
                        if ($bookings->is_paid == 1) {
                            echo "<span class='badge bg-green'>Completed</span>";
                        } elseif ($bookings->is_paid == 0 && $bookings->is_completed == 1) {
                            echo "<span class='badge bg-red'>Pending</span>";
                        } else {
                            echo "<span class='badge bg-yellow'>Request Not Completed</span>";
                        }
                        ?>
                                </td>
                                <td>
                                <?php
                               echo $bookings->execName;
							   ?>
                                </td>
                                <td><?php 
								$pickupdetail=$this->Booking_Model->getPickup($bookings->id);
								
								 $this->Booking_Model->get_location($pickupdetail->latitude,$pickupdetail->longitude);
								// $this->Booking_Model->get_address_trip($pickupdetail->latitude, $pickupdetail->longitude);
								  ?></td>
                                <td>
                                <?php
                                $dropOffdetail=$this->Booking_Model->getDrop($bookings->id);
								 $this->Booking_Model->get_location($dropOffdetail->latitude,$dropOffdetail->longitude);
                               // echo $this->Booking_Model->get_address_trip($dropOffdetail->latitude, $dropOffdetail->longitude);
								?>
                                </td>
                                <!--<td data-toggle="modal" data-target="#assign"><span class="label label-danger label-mini"><?php //echo $bookings-> ?></span></td>-->
                            </tr>
                            <?php
							}
							 ?>
                            
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