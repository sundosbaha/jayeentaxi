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
<div id="overlay" style="display:none;">
<div class="loader-new"></div>
<!--<img src="<?php //echo $base ?>assets/images/taxi_logo.jpg" class="taxi_img">-->
</div>
<?php include("include/header.php"); ?>
<div class="container-fluid">
<div class="col-md-4">
      <form role="form" style="
    padding: 4px;
    border: 1px solid #C3C0C0;
" id="routeForm" onSubmit=" return addbookingAjax(0)" method="post">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-earphone"></span></span>
                        <input type="text" class="form-control" name="customerMobile" id="customerMobile" placeholder="Phone Number" required="" onChange="fetchcustomerDetails()">
                        
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                     <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                        <input type="text" class="form-control" id="customerfirstName" name="customerfirstName" placeholder="First Name" required="">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                     <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                        <input type="text" class="form-control" id="customerLastName" name="customerLastName" placeholder="Last Name" required=""> 
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                       <span class="input-group-addon"><span class="glyphicon glyphicon-comment"></span></span>
                        <input type="text" class="form-control" id="customerAddress" name="customerAddress" placeholder="Address" >
                     
                    </div>
                </div>
                
                <div class="form-group col-md-6" style="padding:0;">
                    <div class="input-group">
                       <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></span>
                        <input type="text" class="form-control"  name="pickupLocation" placeholder="Pickup" id="pickupLocation" required="">
                      <input type="hidden" value="" id="pickupLatitude" name="pickupLatitude"  />
                      <input type="hidden" value="" id="pickupLongitude" name="pickupLongitude"  />
                     </div> 
                </div>
                 <div class="form-group col-md-6">
                    <div class="input-group">
                      
                        <input type="text" class="form-control" id="pickupDetails" name="pickupDetails" placeholder="Pickup details" required="">
                     
                    </div>
                </div>
                <div class="form-group col-md-6" style="padding:0;">
                    <div class="input-group">
                       <span class="input-group-addon"><span class="glyphicon glyphicon-flag"></span></span>
                        <input type="text" class="form-control" id="dropoffLocation" name="dropoffLocation" placeholder="Dropoff" required="">
                      <input type="hidden" value="" id="dropoffLatitude" name="dropoffLatitude"  />
                      <input type="hidden" value="" id="dropoffLongitude" name="dropoffLongitude"  />
                    </div>
                </div> 
                 <div class="form-group col-md-6">
                    <div class="input-group">
                      
                        <input type="text" class="form-control" id="dropoffDetails" name="dropoffDetails" placeholder="Dropoff details" required="">
                     
                    </div>
                </div>
               
          
                <div class="form-group col-md-6" style="padding:0;">
                    <div class="input-group">
                       <span class="input-group-addon"><span class="glyphicon glyphicon-comment"></span></span>
                        <input type="text" class="form-control" id="numberOfadults" name="numberOfadults" placeholder="No of Adults" required="">
                     
                    </div>
                </div>
                <div class="form-group col-md-6" style="padding:0;">
                    <div class="input-group">
                       <span class="input-group-addon"><span class="glyphicon glyphicon-comment"></span></span>
                        <input type="text" class="form-control" id="noOfchildren" name="noOfchildren" placeholder="No of Children" required="">
                    </div>
                </div>
             <!--   <div class="form-group">
                <label class="col-sm-5 control-label col-lg-5" for="inputSuccess">Vehicle Type</label>
                   <div class="col-lg-6">
                       <label class="radio-inline"><input type="radio" name="optradio">AC</label>
                       <label class="radio-inline"><input type="radio" name="optradio">Non AC</label>
                    </div>
                </div>-->
                    <div class="form-group">
                    <div class="input-group">
                       <span class="input-group-addon"><span class="glyphicon glyphicon-comment"></span></span>
                        <input type="text" class="form-control" id="luggageCount" name="luggageCount" placeholder="Luggages" required="">
                     
                    </div>
                </div>
                    
                 <div class="form-group">
                    <div class="input-group">
                        <select class="form-control" id="typeofCar" name="typeofCar" onChange="locateNearbyDrivers(this.value)" required>
                            <option value="">Type of Car</option>
                           
                         </select>
                    </div>
                </div>
                 <div class="form-group">
                    <div class="input-group">
                       <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                        <textarea name="rideComment" placeholder="Comment" id="rideComment" class="form-control" rows="1" required></textarea>
                        <input type="hidden" name="user_timezone" value="" id="user_timezone" />
                         <input type="hidden" name="executive_user_id" value="<?php echo $this->session->userdata('userid') ?>" id="executive_user_id" />
                    </div>
                </div>
                   <button type="submit" class="btn btn-primary" id="rideNowbutton" >Ride Now</button>
             <button type="button"  class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="background: #1fb5ad;border: #1fb5ad;
" onClick="marklocationinmap()">Ride Later

</button>

 
        </form>
   </div>
<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Ride Later</h4>
        </div>
        <div class="modal-body">
        
          <div class="form-group">
                    <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input type="text" class="form-control" id="booking_date_picker" placeholder="Booking Date" required="" name="booking_time_picker">        
                    </div>
                </div>
                
                 <div class="form-group">
                  <div class="input-group bootstrap-timepicker">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                    <input id="booking_time_picker" type="text" name="booking_time_picker" class="form-control">
                </div>
                </div>
             
        </div>
        <div class="modal-footer">
          <button type="submit" data-dismiss="modal" class="btn btn-default" onClick="addbookingAjax(1)">Submit</button>
        </div>
        
      </div>
      
    </div>
  </div>
   <div class="col-md-8">
    
     <div id="map" style="height:500px;width:900px;"></div>


   </div>
</div>
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
                                <th>Request ID</th>
                                <th>Customer Name</th>
                                <th>Driver Name</th>
                                <th>Driver Mobile</th>
                                <th>Pick Up</th>
                                <th>Drop off</th>
                                <th>Adults</th>
                                <th>Children</th>
                                <th>Luggages</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody id="assignedDrivers">
                            
                             
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

    <!--<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyBQw_IdF2l5XWDJQUlwl9lWHQPaKJ1XmEE&sensor=false&callback=initMap&libraries=places"></script>-->

  <script  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQw_IdF2l5XWDJQUlwl9lWHQPaKJ1XmEE&libraries=places,drawing,geometry,visualization">
    </script> 
 <script>
		var map, directionsService, directionsDisplay, geocoder, startLatlng, endLatlng, routeStart, routeEnd, markers = [],pickupMarker,dropOffMarker;

function initialize(latitude,longitude,hasmarker=0) {
	
    var latlng = new google.maps.LatLng(latitude,longitude);
    routeStart = document.getElementById('pickupLocation');
    routeEnd = document.getElementById('dropoffLocation');
    geocoder = new google.maps.Geocoder();
    directionsService = new google.maps.DirectionsService();
    directionsDisplay = new google.maps.DirectionsRenderer({
        animation: google.maps.Animation.DROP,
        draggable: true
    });
    var myOptions = {
        zoom: 12,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false
    };
    map = new google.maps.Map(document.getElementById("map"), myOptions);
    directionsDisplay.setMap(map);
   // directionsDisplay.setPanel(document.getElementById("directionsPanel"));

if(hasmarker == 1)
{
	var myLatLng = {lat: latitude, lng: longitude};
	pickupMarker = new google.maps.Marker({
    position: myLatLng,
	draggable:true,
   	animation: google.maps.Animation.DROP,
    map: map,
    title: 'Customer'
  });
     // Extend markerBounds with each random point.
   google.maps.event.addListener(pickupMarker, 'dragend', function() 
{
    geocodePosition(pickupMarker.getPosition());
	
});
 
}// end if marker check

function geocodePosition(pos) 
{
   geocoder = new google.maps.Geocoder();
   geocoder.geocode
    ({
        latLng: pos
    }, 
        function(results, status) 
        {
            if (status == google.maps.GeocoderStatus.OK) 
            {
                $("#pickupLocation").val(results[0].formatted_address);
                console.log(results);
			document.getElementById("pickupLatitude").value= pos.lat();
			document.getElementById("pickupLongitude").value=pos.lng();
            } 
            else 
            {
               
            }
        }
    );
}

    google.maps.event.addListener(directionsDisplay, 'directions_changed', function() {
        var directions = this.getDirections();
        var overview_path = directions.routes[0].overview_path;
        var startingPoint = overview_path[0];
        var destination = overview_path[overview_path.length - 1];
        if (typeof startLatlng === 'undefined' || !startingPoint.equals(startLatlng)) {
            startLatlng = startingPoint;
            getLocationName(startingPoint, function(name) {
            //    routeStart.value = name;
            });
        }
        if (typeof endLatlng === 'undefined' || !destination.equals(endLatlng)) {
            endLatlng = destination;
            getLocationName(destination, function(name) {
               // routeEnd.value = name;
            });
        }
    });
}


function marklocationinmap()
{
	var start = document.getElementById("pickupLocation").value;
        var end = document.getElementById("dropoffLocation").value;
		if (start.length && end.length) {
            calcRoute(start, end);
        }
}
 
function getLocationName(latlng, callback) {
    geocoder.geocode({
        location: latlng
    }, function(result, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            var i = -1;
            console.log(result);
            // find the array index of the last object with the locality type
            for (var c = 0; c < result.length; c++) {
                for (var t = 0; t < result[c].types.length; t++) {
                    if (result[c].types[t].search('locality') > -1) {
                        i = c;
                    }
                }
            }
            var locationName = result[i].address_components[0].long_name;
            callback(locationName);
        }
    });
}

function calcRoute(start, end) {
    
	pickupMarker.setMap(null);
	var request = {
        origin: start,
        destination: end,
        travelMode: google.maps.DirectionsTravelMode.DRIVING
    };
    directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
        }
    });
}


window.onLoad = initialize(51.764696,5.526042);


function locateNearbyDrivers(type)
{
	
	$.ajax({
   			url	 	: 	'<?php echo API_ENDPOINT_URL ?>/find',
   			data 	:	 {
      						'latitude' 		: 	$('#pickupLatitude').val(),
	  						'longitude' 	: 	$('#pickupLongitude').val(),
	  						'type' 			: 	type
  				 		 },
   			error	: function(a,b,c) {
       						console.log(a);
	    					console.log(b);
		 					console.log(c);
   						},
   			success	: function(data) {
	  				console.log("Locate neaby Drivers below");
	 				console.log(data);	 
									 
   },
   type: 'GET'
});	
}
		</script>
    <script type="text/javascript">
        google.maps.event.addDomListener(window, 'load', function () {
            var places = new google.maps.places.Autocomplete(document.getElementById('pickupLocation'));
            google.maps.event.addListener(places, 'place_changed', function () {
                var place = places.getPlace();
                var address = place.formatted_address;
                var latitude = place.geometry.location.lat();
                var longitude = place.geometry.location.lng();
                var mesg = "Address: " + address;
                mesg += "\nLatitude: " + latitude;
                mesg += "\nLongitude: " + longitude;
				$('#pickupLatitude').val(latitude);
				$('#pickupLongitude').val(longitude);
                console.log("The pickup latitude is"+mesg);
				
				$.ajax({
					
   				url		: '<?php echo API_ENDPOINT_URL ?>/application/types',
   				data 	: {
      						'usr_lat' 	: latitude,
	  						'user_long' : longitude
   					   	   },
   				error	: function(a,b,c)
						 {
      					  	console.log(a);
	    				  	console.log(b);
		 				  	console.log(c);
   						  },
   				success	: function(data) {
	 						 console.log(data);
	  						 var jsonObject=data;
	  						if(jsonObject.success)
	  						{
		 						var walkerArray=jsonObject.types;
		 					for(walker=0;walker<walkerArray.length;walker++)
		 					{
								var walkerObject=walkerArray[walker];
								$('#typeofCar').append('<option value="'+walkerObject.id+'" >'+                                walkerObject.name+'</option>');
		 					}
	  						}
	  						else
	  						{
		  
	  						}
   							},
  							type: 'GET'
							});


// for marking the place in google map
							initialize(latitude,longitude,1);
// end for marking the place in google map		
            });
					
        });
    </script>
    
    <script type="text/javascript">
        google.maps.event.addDomListener(window, 'load', function () {
            var places = new google.maps.places.Autocomplete(document.getElementById('dropoffLocation'));
            google.maps.event.addListener(places, 'place_changed', function () {
                var place = places.getPlace();
                var address = place.formatted_address;
                var latitude = place.geometry.location.lat();
                var longitude = place.geometry.location.lng();
                var mesg = "Address: " + address;
                mesg += "\nLatitude: " + latitude;
                mesg += "\nLongitude: " + longitude;
				 
				$('#dropoffLatitude').val(latitude);
				$('#dropoffLongitude').val(longitude);
                console.log("The drop off longitude"+mesg);
            });
        });
    </script>
<!--<script src="js/bootstrap-datepicker.js"></script>-->
<!--<script src="js/bootstrap-timepicker.js"></script>-->
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.js"></script>
<script src="<?php echo $base; ?>assets/js/bootstrap-timepicker.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.2/jquery.timepicker.min.js"></script>-->

<!--<script src="css/bootstrap-timepicker.min.css"></script>-->
<script type="text/javascript">
  $('#booking_date_picker').datepicker({
				format: 'yyyy-mm-dd'
			});
</script>
    <script>
	//Timepicker
        $("#booking_time_picker").timepicker({
          showInputs: false
        });
	</script>
<!--<script type="text/javascript">
     $('#timepicker1').timepicker( {
	    minuteStep: 5
	 });
</script>-->
<script>
function fetchcustomerDetails()
{
	if($('#customerMobile').val() != "")
	{	
	$.ajax({
   url: '<?php echo $base ?>Booking/getuserDetails',
   data: {
      mobileval: $('#customerMobile').val()
   },
   error: function(a,b,c) {
      console.log(a);
	  console.log(b);
	  console.log(c);
   },
   success: function(data) {
    // alert(data);
	 var obj = jQuery.parseJSON(data);
	 if(obj.cExists == 1)
	 {
		  $('#customerfirstName').val(obj.cName);
		  $('#customerLastName').val(obj.cLastname);
	 }
   },
   type: 'POST'
});
	}
}
</script>

<script>
var cancelInterval;
var counterValue=0;
    function checktripStatus(requestId){
				var current_state = "0";
            	var send_url = "<?php echo API_ENDPOINT_URL ?>user/dispatchertrip/status/"+requestId;
                    $.ajax({
                        url: send_url,
                        type: "GET",
                        success: function (data) {
                            if (data != '') {
								console.log(data);
                                //var jsonObj= JSON.parse(data);
								try
								{
                                    current_state = data.status;
								}
								catch(err) {
									
								}
                                    if (current_state == 1) {  
							    var assignDetailsHTML='<tr><td>'+requestId+'</td><td>'+$('#customerfirstName').val()+' '+$('#customerLastName').val()+'</td><td>'+data.walkerName+'</td><td>'+data.walkerPhone+'</td><td>'+$('#pickupLocation').val()+'</td><td>'+$('#dropoffLocation').val()+'</td><td>'+$('#numberOfadults').val()+'</td><td>'+$('#noOfchildren').val()+'</td><td>'+$('#luggageCount').val()+'</td><td><span class="label label-danger label-mini">Assigned</span></td></tr>';
							$('#assignedDrivers').html(assignDetailsHTML);
							$('#overlay').toggle('display');
							clearInterval(cancelInterval);
							$('#routeForm').reset();
                                    }
									if(counterValue==60 &&  current_state == "0" )
									{
										clearInterval(cancelInterval);
										alert("No Drivers Found");
										counterValue=0;
										//$('#routeForm').reset();
									}
                                   
                            }
                        },
                        cache: false
                    });
			
			counterValue++;
			
			}


function addbookingAjax(schedule=0)
{
	console.log("Inside Ajax call");
	var ajaxUrl='<?php echo $base.'Booking/addBooking' ?>';
	
	var scheduleDateTime= 0;
	if(schedule == 1)
	{
	var time = $("#booking_time_picker").val();
  	var hours = Number(time.match(/^(\d+)/)[1]);
 	var minutes = Number(time.match(/:(\d+)/)[1]);
	var AMPM = time.match(/\s(.*)$/)[1];
	if(AMPM == "PM" && hours<12) hours = hours+12;
	if(AMPM == "AM" && hours==12) hours = hours-12;
	var sHours = hours.toString();
	var sMinutes = minutes.toString();
	if(hours<10) sHours = "0" + sHours;
	if(minutes<10) sMinutes = "0" + sMinutes;
	console.log(sHours + ":" + sMinutes);	
	scheduleDateTime=$('#booking_date_picker').val()+' '+sHours + ":" + sMinutes + ':00';
	ajaxUrl='<?php echo $base.'Booking/addSchedule' ?>';
	console.log("The usertimezone is "+$('#user_timezone').val());
	
	}
	
	$.ajax({
   url: ajaxUrl,
   data: {    
first_name 		: 	$('#customerfirstName').val(),
last_name 		:	$('#customerLastName').val(),
email			:	makeid()+'@'+makeid()+'.com',
phone			:	'+91'+$('#customerMobile').val(),
password 		: 	makeid(),
device_token 	: 	Math.floor((Math.random() * 100000000) + 1), 
device_type 	: 	'android',
otptype 		: 	true,
mobileval		: 	$('#customerMobile').val(),
pickupLatitude 	: 	$('#pickupLatitude').val(),
pickupLongitude : 	$('#pickupLongitude').val(),
dropoffLatitude :	$('#dropoffLatitude').val(),
dropoffLongitude : 	$('#dropoffLongitude').val(),
pickupDetails 	: 	$('#pickupDetails').val(),
dropoffDetails 	: 	$('#dropoffDetails').val(),
numberOfadults 	: 	$('#numberOfadults').val(),
noOfchildren 	: 	$('#noOfchildren').val(),
luggageCount 	: 	$('#luggageCount').val(),
rideComment 	: 	$('#rideComment').val(),
typeofCar		: 	$('#typeofCar').val(),
schedule		:	scheduleDateTime,
user_timezone   :	$('#user_timezone').val(),
pickupLocation  :	$('#pickupLocation').val(),
customerAddress :   $('#customerAddress').val(),
   },
   error: function(a,b,c) {
      console.log(a);
	  console.log(b);
	  console.log(c);
   },
   
   beforeSend: function() {
        // setting a timeout
       if(schedule!=1)
	{ 
		$('#overlay').toggle('display');
	}
    },
   	success: function(data) {
	console.log("Book a drive now below"); 
	console.log(data);
	 if(data!='')
	 { // data check if begins
	if(schedule!=1)
	{	
	var jsonObj=JSON.parse(data);
	console.log(jsonObj); 
	if(jsonObj.success == false || typeof jsonObj.request_id == 'undefined')
	{
		alert("No Driver Avalable at this time.");
		$('#overlay').toggle('display');
		$('#routeForm').reset();
		
	}
	else
	{
	populateDriverDetails($('#pickupLatitude').val(),$('#pickupLongitude').val());
	console.log("The request Id is "+jsonObj.request_id);
	cancelInterval=setInterval(function(){ checktripStatus(jsonObj.request_id) }, 1000);
		
	}
	

	}
	else
	{
		alert("Scheduled Successfully");
	}
	 } // data check if ends
	 else
	 {
		$('#overlay').toggle('display');
		alert("No Driver Avalable at this time."); 
	 }
	 
	
   },
   type: 'POST'
});
marklocationinmap();
	return false;
}


function populateDriverDetails(lat,long)
{
	
	$.ajax({
   url: '<?php echo API_ENDPOINT_URL.'user/allprovider_list' ?>',
   data: {    
usr_lat	 :  lat,
user_long: long,
   },
   error: function(a,b,c) {
      console.log(a);
	  console.log(b);
	  console.log(c);
   },
   success: function(data) {
    console.log("Populate drives below");
	console.log(data);
   },
   type: 'POST'
});


}

function makeid()
{
    var text = "";
    var possible = "abcdefghijklmnopqrstuvwxyz";

    for( var i=0; i < 10; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

// check trip status

 
      
// end check trip status

</script>
<?php 
if(isset($accessmsg))
{
?>
<script>
$(document).ready(function(e) {
    alert('No access to this page');
});
</script>
<?php
}
?>

<script>

$( document ).ready(function() {
     //showlocation()
	 $('#user_timezone').val(getTimeZone());
});


/* function showlocation() {
               // One-shot position request.
                navigator.geolocation.getCurrentPosition(
				
				function(position) {
         
        var lat = position.coords.latitude;
        var lon = position.coords.longitude;
		$.ajax({
   url:"https://maps.googleapis.com/maps/api/timezone/json?location="+lat+","+lon+"&timestamp="+(Math.round((new Date().getTime())/1000)).toString()+"&sensor=false",
}).done(function(response){
   if(response.timeZoneId != null){
     //do something
	 $('#user_timezone').val(response.timeZoneId);
	 console.log($('#user_timezone').val());
   }
});
			
				
				
				);
            }
 }*/
			
		function callback(position) {}
			

function getTimeZone() {
  var offset = new Date().getTimezoneOffset(), o = Math.abs(offset);
  return (offset < 0 ? "+" : "-") + ("00" + Math.floor(o / 60)).slice(-2) + ":" + ("00" + (o % 60)).slice(-2);
}
</script>
 </body>
</html>