@extends('dispatch.layout')


@section('content')

    <div id="loader-wrapper">
        <div id="loader"></div>

        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>

    </div>

    <style>
        .js div#preloader {
            position: fixed;
            left: 0;
            top: 0;
            z-index: 999;
            width: 100%;
            height: 100%;
            overflow: visible;
            background: #333 url('http://files.mimoymima.com/images/loading.gif') no-repeat center center;
        }
    </style>
    <div id="preloader" style="display:none;"></div>
    <div class="col-md-4">
        <form role="form" style="
    padding: 4px;
    border: 1px solid #C3C0C0;
" id="routeForm" method="post">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon" style="background:#23374a;"><span style="color:white;"
                                                                                      class="glyphicon glyphicon-earphone"></span></span>
                    <input type="text" class="form-control" name="customerMobile" id="customerMobile"
                           placeholder="<?php echo Lang::get('dispatcher.phonenumber'); ?>*" required="">
                    <input type="hidden" class="form-control" name="customerId" id="customerId"
                           placeholder="Phone Number">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon" style="background:#23374a;"><span style="color:white;"
                                                                                      class="glyphicon glyphicon-user"></span></span>
                    <input type="text" class="form-control" id="customerfirstName" name="customerfirstName"
                           placeholder="<?php echo Lang::get('dispatcher.fname'); ?>*" required="">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon" style="background:#23374a;"><span style="color:white;"
                                                                                      class="glyphicon glyphicon-user"></span></span>
                    <input type="text" class="form-control" id="customerLastName" name="customerLastName"
                           placeholder="<?php echo Lang::get('dispatcher.lname'); ?>*" required="">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon" style="background:#23374a;"><span style="color:white;"
                                                                                      class="glyphicon glyphicon-comment"></span></span>
                    <input type="text" class="form-control" id="customerAddress" name="customerAddress"
                           placeholder="<?php echo Lang::get('dispatcher.address'); ?>">

                </div>
            </div>

            <div class="form-group col-md-8" style="padding:0;">
                <div class="input-group">
                    <span class="input-group-addon" style="background:#23374a;"><span style="color:white;"
                                                                                      id="pickup_in"
                                                                                      class="glyphicon glyphicon-map-marker"></span></span>
                    <input type="text" class="form-control" name="pickupLocation"
                           placeholder="<?php echo Lang::get('dispatcher.Pickup'); ?>*" id="pickupLocation" required="">
                    <input type="hidden" value="" id="pickupLatitude" name="pickupLatitude"/>
                    <input type="hidden" value="" id="pickupLongitude" name="pickupLongitude"/>
                </div>
            </div>
            <div class="form-group col-md-4">
                <div class="input-group">

                    <input type="text" class="form-control" id="pickupDetails" name="pickupDetails"
                           placeholder="<?php echo Lang::get('dispatcher.Pickdetails'); ?>" required="">

                </div>
            </div>
            <div class="form-group col-md-8" style="padding:0;">
                <div class="input-group">
                    <span class="input-group-addon" style="background:#23374a;"><span style="color:white;" id="drop_in"
                                                                                      class="glyphicon glyphicon-flag"></span></span>
                    <input type="text" class="form-control" id="dropoffLocation" name="dropoffLocation"
                           placeholder="<?php echo Lang::get('dispatcher.drop'); ?>*" required="">
                    <input type="hidden" value="" id="dropoffLatitude" name="dropoffLatitude"/>
                    <input type="hidden" value="" id="dropoffLongitude" name="dropoffLongitude"/>
                </div>
            </div>
            <div class="form-group col-md-4">
                <div class="input-group">

                    <input type="text" class="form-control" id="dropoffDetails" name="dropoffDetails"
                           placeholder="<?php echo Lang::get('dispatcher.dropdetail'); ?>" required="">

                </div>
            </div>


            <div class="form-group col-md-6" style="padding:0;">
                <div class="input-group">
                    <span class="input-group-addon" style="background:#23374a;"><span style="color:white;"
                                                                                      class="glyphicon glyphicon-comment"></span></span>
                    <input type="text" class="form-control" id="numberOfadults" name="numberOfadults" maxlength="1"
                           placeholder="<?php echo Lang::get('dispatcher.no_of_adult'); ?>" required="">

                </div>
            </div>
            <div class="form-group col-md-6" style="padding:0;">
                <div class="input-group">
                    <span class="input-group-addon" style="background:#23374a;"><span style="color:white;"
                                                                                      class="glyphicon glyphicon-comment"></span></span>
                    <input type="text" class="form-control" id="noOfchildren" name="noOfchildren" maxlength="1"
                           placeholder="<?php echo Lang::get('dispatcher.no_of_child'); ?>" required="">
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
                    <span class="input-group-addon" style="background:#23374a;"><span style="color:white;"
                                                                                      class="glyphicon glyphicon-comment"></span></span>
                    <input type="text" class="form-control" id="luggageCount" name="luggageCount"
                           placeholder="<?php echo Lang::get('dispatcher.luggages'); ?>" required="">

                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <select class="form-control" id="typeofCar" name="typeofCar" required>
                        <option value=""><?php echo Lang::get('dispatcher.type_of_car'); ?></option>

                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-6 form-group" id="price_d">


                </div>
                <div class="col-md-6 form-group" id="price_r">


                </div>
            </div>


            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon" style="background:#23374a;"><span style="color:white;"
                                                                                      class="glyphicon glyphicon-asterisk"></span></span>
                    <textarea name="rideComment" placeholder="<?php echo Lang::get('dispatcher.comment'); ?>"
                              id="rideComment" class="form-control" rows="1" required></textarea>
                    <input type="hidden" name="user_timezone" value="" id="user_timezone"/>
                    <input type="hidden" name="executive_user_id" value="3" id="executive_user_id"/>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="background:#000906;"
                    id="rideNowbutton"><?php echo Lang::get('dispatcher.ride_now'); ?></button>
            <button type="button" class="btn btn-primary" style="background: #23374a;
" onClick="valid()"><?php echo Lang::get('dispatcher.ride_late'); ?>

            </button>
            <button type="button" class="btn btn-primary" id="estimate" style="background: #000906;
"><?php echo Lang::get('dispatcher.estimate_price'); ?>

            </button>

        </form>
    </div>






    <style>
        .bounce {
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            -ms-border-radius: 50%;
            border-radius: 50%;
            animation: bounce 2s infinite;
            -webkit-animation: bounce 2s infinite;
            -moz-animation: bounce 2s infinite;
            -o-animation: bounce 2s infinite;
        }

        @-webkit-keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                -webkit-transform: translateY(0);
            }
            40% {
                -webkit-transform: translateY(-30px);
            }
            60% {
                -webkit-transform: translateY(-15px);
            }
        }

        @-moz-keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                -moz-transform: translateY(0);
            }
            40% {
                -moz-transform: translateY(-30px);
            }
            60% {
                -moz-transform: translateY(-15px);
            }
        }

        @-o-keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                -o-transform: translateY(0);
            }
            40% {
                -o-transform: translateY(-30px);
            }
            60% {
                -o-transform: translateY(-15px);
            }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-30px);
            }
            60% {
                transform: translateY(-15px);
            }
        }

        #loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
        }

        #loader {
            display: block;
            position: relative;
            left: 50%;
            top: 50%;
            width: 150px;
            height: 150px;
            margin: -75px 0 0 -75px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #3498db;

            -webkit-animation: spin 2s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
            animation: spin 2s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */

            z-index: 1001;
        }

        #loader:before {
            content: "";
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #e74c3c;

            -webkit-animation: spin 3s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
            animation: spin 3s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
        }

        #loader:after {
            content: "";
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #f9c922;

            -webkit-animation: spin 1.5s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
            animation: spin 1.5s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg); /* Chrome, Opera 15+, Safari 3.1+ */
                -ms-transform: rotate(0deg); /* IE 9 */
                transform: rotate(0deg); /* Firefox 16+, IE 10+, Opera */
            }
            100% {
                -webkit-transform: rotate(360deg); /* Chrome, Opera 15+, Safari 3.1+ */
                -ms-transform: rotate(360deg); /* IE 9 */
                transform: rotate(360deg); /* Firefox 16+, IE 10+, Opera */
            }
        }

        @keyframes spin {
            0% {
                -webkit-transform: rotate(0deg); /* Chrome, Opera 15+, Safari 3.1+ */
                -ms-transform: rotate(0deg); /* IE 9 */
                transform: rotate(0deg); /* Firefox 16+, IE 10+, Opera */
            }
            100% {
                -webkit-transform: rotate(360deg); /* Chrome, Opera 15+, Safari 3.1+ */
                -ms-transform: rotate(360deg); /* IE 9 */
                transform: rotate(360deg); /* Firefox 16+, IE 10+, Opera */
            }
        }

        #loader-wrapper .loader-section {
            position: fixed;
            top: 0;
            width: 51%;
            height: 100%;
            background: #222222;
            z-index: 1000;
            -webkit-transform: translateX(0); /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: translateX(0); /* IE 9 */
            transform: translateX(0); /* Firefox 16+, IE 10+, Opera */
        }

        #loader-wrapper .loader-section.section-left {
            left: 0;
        }

        #loader-wrapper .loader-section.section-right {
            right: 0;
        }

        /* Loaded */
        .loaded #loader-wrapper .loader-section.section-left {
            -webkit-transform: translateX(-100%); /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: translateX(-100%); /* IE 9 */
            transform: translateX(-100%); /* Firefox 16+, IE 10+, Opera */

            -webkit-transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1.000);
            transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1.000);
        }

        .loaded #loader-wrapper .loader-section.section-right {
            -webkit-transform: translateX(100%); /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: translateX(100%); /* IE 9 */
            transform: translateX(100%); /* Firefox 16+, IE 10+, Opera */

            -webkit-transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1.000);
            transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1.000);
        }

        .loaded #loader {
            opacity: 0;
            -webkit-transition: all 0.3s ease-out;
            transition: all 0.3s ease-out;
        }

        .loaded #loader-wrapper {
            visibility: hidden;

            -webkit-transform: translateY(-100%); /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: translateY(-100%); /* IE 9 */
            transform: translateY(-100%); /* Firefox 16+, IE 10+, Opera */

            -webkit-transition: all 0.3s 1s ease-out;
            transition: all 0.3s 1s ease-out;
        }

        /* JavaScript Turned Off */
        .no-js #loader-wrapper {
            display: none;
        }

        .no-js h1 {
            color: #222222;
        }

        #legend {
            font-family: Arial, sans-serif;
            background: #fff;
            padding: 10px;
            margin: 10px;
            border: 1px solid #000;
        }

        #legend h3 {
            margin-top: 0;
        }


    </style>



    <div class="modal fade" id="myModal_sch" role="dialog">
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
                            <input type="text" class="form-control" id="booking_date_picker"
                                   placeholder="<?php echo Lang::get('dispatcher.booking_date'); ?>" required=""
                                   name="booking_time_picker">
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
                    <button type="submit" data-dismiss="modal" class="btn btn-default" onClick="addbookingAjax(1)">
                        Submit
                    </button>
                </div>

            </div>

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button>-->
                    <h4 class="modal-title" id="tit">Ride Now</h4>
                </div>
                <div class="modal-body" id="dis">

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            <input type="text" class="form-control" id="booking_date_picker" placeholder="Booking Date"
                                   required="" name="booking_time_picker">
                        </div>
                    </div>


                </div>
                <!--   <div class="modal-footer">
                     <button type="submit" data-dismiss="modal" class="btn btn-default" onClick="addbookingAjax(1)">Submit</button>
                   </div>-->

            </div>

        </div>
    </div>
    <div class="col-md-8">

        <div id="map" style="height:500px;width:900px;"></div>


        <div id="legend" style="display:none;"><h3><?php echo Lang::get('dispatcher.legend'); ?></h3></div>


    </div>











    </div>
    <!--<div class="container-fluid">
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
    </div>-->

@stop


@section('javascript')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQw_IdF2l5XWDJQUlwl9lWHQPaKJ1XmEE&libraries=places,drawing,geometry,visualization">
    </script>

    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.js"></script>
    <script src="http://taxiappz.com/demo/public/dispatcher/js/bootstrap-timepicker.min.js"></script>

    <script>
        //Timepicker
        $("#booking_time_picker").timepicker({
            showInputs: false
        });
    </script>
    <script type="text/javascript">
        $('#booking_date_picker').datepicker({

            todayHighlight: true,
            minDate: 0,
            format: 'yyyy-mm-dd',
            startDate: new Date,
            autoclose: true

        });
    </script>

    <script>
        $('#pickupLocation').focusin(function () {
            $('#pickup_in').addClass('bounce');
        });
        $('#pickupLocation').focusout(function () {
            $('#pickup_in').removeClass('bounce');
        });
        var map;
        var pick_window;
        var drop_window;
        var driver_update;
        var country_code = "+52";
        var marker1 = '<?php echo asset_url() . "/image/pin_client_pickup.png"; ?>';
        var marker2 = '<?php echo asset_url() . "/image/pin_client_dropoff.png"; ?>';
        var newmarkersArray = [];
        var markersArray = [];
        var icon = "<?php echo asset_url(); ?>/image/driver_available.png";
        var icon1 = "<?php echo asset_url(); ?>/image/driver_on_trip.png";
        var shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
        var infowindow_driver = new google.maps.InfoWindow();
        var html = '';

        function init(lati, longi) {
            var directionsDisplay = new google.maps.DirectionsRenderer;
            var directionsService = new google.maps.DirectionsService;
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: lati, lng: longi},
                draggable: true,
                zoom: 2
            });
            directionsDisplay = new google.maps.DirectionsRenderer({
                draggable: true,
                map: map
            });

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    //infoWindow.setPosition(pos);
                    //infoWindow.setContent('Location found.');
                    map.setCenter(pos);
                    map.setZoom(10);
                }, function () {
                    handleLocationError(true, infoWindow, map.getCenter());
                });
            } else {
                // Browser doesn't support Geolocation
                //handleLocationError(false, infoWindow, map.getCenter());
            }


            var picklocation = document.getElementById('pickupLocation');
            var droplocation = document.getElementById('dropoffLocation');

            //autocomplete-pick
            var autocomplete_pick = new google.maps.places.Autocomplete(picklocation);

            //autocomplete-pick
            var autocomplete_drop = new google.maps.places.Autocomplete(droplocation);


            //marker-pick init
            var marker_pick = new google.maps.Marker({
                map: map,
                icon: marker1,
                draggable: true,
                animation: google.maps.Animation.DROP,
                anchorPoint: new google.maps.Point(0, -29)
            });

            //marker-drop init
            var marker_drop = new google.maps.Marker({
                map: map,
                icon: marker2,
                draggable: true,
                animation: google.maps.Animation.DROP,
                anchorPoint: new google.maps.Point(0, -56)
            });

            //info window
            var infowindow = new google.maps.InfoWindow();
            var infowindow_drop = new google.maps.InfoWindow();

            //autocomplete listener	 -drop
            autocomplete_drop.addListener('place_changed', function () {

                infowindow_drop.close();
                marker_drop.setVisible(false);
                var place_drop = autocomplete_drop.getPlace();
                if (!place_drop.geometry) {
                    window.alert("<?php echo Lang::get('dispatcher.enter_the_place_not_find_in_map') ?>");
                    return;
                }

                // If the place has a geometry, then present it on a map
                if (place_drop.geometry.viewport) {
                    map.fitBounds(place_drop.geometry.viewport);
                } else {
                    map.setCenter(place_drop.geometry.location);
                    map.setZoom(10);
                }

                /* marker_pick.setIcon({
                 url: place.icon,
                 size: new google.maps.Size(71, 71),
                 origin: new google.maps.Point(0, 0),
                 anchor: new google.maps.Point(17, 34),
                 scaledSize: new google.maps.Size(35, 35)
                 });*/
                marker_drop.setPosition(place_drop.geometry.location);
                marker_drop.setVisible(true);
                document.getElementById("dropoffLatitude").value = place_drop.geometry.location.lat();
                document.getElementById("dropoffLongitude").value = place_drop.geometry.location.lng();
                //address in info window
                var address = '';
                infowindow_drop.setContent(place_drop.formatted_address);
                infowindow_drop.open(map, marker_drop);
                //draw navigation
                calculateAndDisplayRoute(directionsService, directionsDisplay, marker_pick, marker_drop, map);
                drop_window = true;
            });


            //autocomplete listener	 -pickup
            autocomplete_pick.addListener('place_changed', function () {

                infowindow.close();
                marker_pick.setVisible(false);
                var place = autocomplete_pick.getPlace();
                if (!place.geometry) {
                    window.alert("<?php echo Lang::get('dispatcher.enter_the_place_not_find_in_map') ?>");
                    return;
                }

                // If the place has a geometry, then present it on a map
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(10);
                }

                /* marker_pick.setIcon({
                 url: place.icon,
                 size: new google.maps.Size(71, 71),
                 origin: new google.maps.Point(0, 0),
                 anchor: new google.maps.Point(17, 34),
                 scaledSize: new google.maps.Size(35, 35)
                 });*/
                marker_pick.setPosition(place.geometry.location);
                marker_pick.setVisible(true);
                document.getElementById("pickupLatitude").value = place.geometry.location.lat();
                document.getElementById("pickupLongitude").value = place.geometry.location.lng();
                //address in info window
                var address = '';
                infowindow.setContent(place.formatted_address);
                infowindow.open(map, marker_pick);
                pick_window = true;
            });


            //pick  marker click
            marker_pick.addListener('click', function () {
                if (pick_window) {
                    infowindow.close();
                    pick_window = false;
                }
                else {
                    infowindow.open(map, marker_pick);
                    pick_window = true;
                }
            });

            //drop  marker click
            marker_drop.addListener('click', function () {
                if (drop_window) {
                    infowindow_drop.close();
                    drop_window = false;
                }
                else {
                    infowindow_drop.open(map, marker_drop);
                    drop_window = true;
                }
            });


            $('#dropoffLocation').focusin(function () {
                var text = $('#pickupLatitude').val();
                if (text == '') {
                    $('#pickupLocation').focus();
                }
                else {
                    //$('#drop_in').draggable({ revert: "invalid" });
                }
            });


            $('#pickup_in').draggable({revert: "invalid"});

            $("#map").droppable({
                drop: function (event, ui) {

                    //pick up
                    var myCenter = new google.maps.LatLng(lati, longi);
                    map.setCenter(myCenter);
                    map.setZoom(15);
                    marker_pick.setPosition(myCenter);
                    marker_pick.setVisible(true);
                    geocoder = new google.maps.Geocoder();
                    geocoder.geocode
                    ({
                                latLng: myCenter
                            },
                            function (results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    $("#pickupLocation").val(results[0].formatted_address);
                                    infowindow.setContent(results[0].formatted_address);
                                    infowindow.open(map, marker_pick);
                                }
                                else {
                                    alert("<?php echo Lang::get('dispatcher.cant_get_address_for_this_place'); ?>");
                                }
                            }
                    );
                    document.getElementById("pickupLatitude").value = lati;
                    document.getElementById("pickupLongitude").value = longi;


                    //drop
                    myCenter = new google.maps.LatLng(lati + 0.100, longi + 0.100);
                    marker_drop.setPosition(myCenter);
                    marker_drop.setVisible(true);
                    geocoder = new google.maps.Geocoder();
                    geocoder.geocode
                    ({
                                latLng: myCenter
                            },
                            function (results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    $("#dropoffLocation").val(results[0].formatted_address);
                                    infowindow_drop.setContent(results[0].formatted_address);
                                    infowindow_drop.open(map, marker_pick);
                                }
                                else {
                                    alert("<?php echo Lang::get('dispatcher.cant_get_address_for_this_place'); ?>");
                                }
                            }
                    );
                    document.getElementById("dropoffLatitude").value = lati;
                    document.getElementById("dropoffLongitude").value = longi;
                    calculateAndDisplayRoute(directionsService, directionsDisplay, marker_pick, marker_drop, map);
                }

            });


            update_driver(map);
            /*	$.ajax({
             type:"post",
             url:"{{ URL::Route('DispatchallProviderXml') }}",
             success: function(data)
             {
             console.log(data);
             var markers=JSON.parse(data);
             if(markers.success)
             {
             for (var i = 0; i < markers.walkers.length; i++) {

             if(markers.walkers[i].type == 'free')
             {
             var point = new google.maps.LatLng(
             parseFloat(markers.walkers[i].lati),
             parseFloat(markers.walkers[i].longi));
             marker = new google.maps.Marker({
             map: map,
             position: point,
             icon: icon,
             shadow: shadow});
             newmarkersArray.push(marker);
             html='<?php echo Lang::get('dispatcher.name'); ?> : '+markers.walkers[i].fname+' '+markers.walkers[i].lname+'<br><?php echo Lang::get('dispatcher.car_type'); ?>:'+markers.walkers[i].type_name +'<br><b><?php echo Lang::get('dispatcher.available'); ?></b>';
             }
             else
             {
             var point = new google.maps.LatLng(
             parseFloat(markers.walkers[i].lati),
             parseFloat(markers.walkers[i].longi));
             marker = new google.maps.Marker({
             map: map,
             position: point,
             icon: icon1,
             shadow: shadow});
             newmarkersArray.push(marker);
             html='<?php echo Lang::get('dispatcher.name'); ?> : '+markers.walkers[i].fname+' '+markers.walkers[i].lname+'<br><?php echo Lang::get('dispatcher.car_type'); ?>:'+markers.walkers[i].type_name+'<br><b><?php echo Lang::get('dispatcher.on_trip'); ?></b>';
             }
             bindInfoWindow(marker, map, infowindow_driver, html, markers.walkers[i].type);

             }

             clearOverlays(markersArray);
             markersArray = newmarkersArray;
             newmarkersArray = [];
             }



             },
             error:function(a, b, c)
             {
             console.log(a);
             console.log(b);
             console.log(c);
             }
             });*/


            $('#estimate').click(function (e) {


                if ($('#typeofCar').val() && $('#pickupLatitude').val() != '' && $('#dropoffLatitude').val() != '') {

                    var type = $('#typeofCar').val();
                    var latitude = $('#pickupLatitude').val();
                    var longitude = $('#pickupLongitude').val();
                    var drop_latitude = $('#dropoffLatitude').val();
                    var drop_longitude = $('#dropoffLongitude').val();
                    var pick_lat = new google.maps.LatLng(latitude, longitude);
                    var drop_lat = new google.maps.LatLng(drop_latitude, drop_longitude);

                    directionsService.route({
                        origin: pick_lat,  // Haight.
                        destination: drop_lat,  // Ocean Beach.
                        // Note that Javascript allows us to access the constant
                        // using square brackets and a string value as its
                        // "property."
                        travelMode: 'DRIVING'
                    }, function (response, status) {
                        //console.log(response);
                        if (status == 'OK') {


                            var dis = response.routes[0].legs[0].distance['text'].split(' ');
                            var dur = response.routes[0].legs[0].duration['text'].split(' ');
                            $.ajax({
                                type: "post",
                                url: "{{ URL::Route('Dispatcheta') }}",
                                data: "type=" + type + "&distance=" + dis[0] + "&duration=" + dur[0],
                                success: function (data) {
                                    console.log(data);
                                    $('#price_r').show().html('<div class="input-group" style="border:1px solid black;"><h3 style="text-align:center;">ETA</h3><div style="text-align:center;"><?php echo Lang::get('dispatcher.base_price') ?> - <?php echo Config::get('app.generic_keywords.Currency'); ?>' + data.base_price + '</div><div style="text-align:center;"><?php echo Lang::get('dispatcher.cal_distance_price') ?> - <?php echo Config::get('app.generic_keywords.Currency'); ?>' + data.cal_distance_price.toFixed(2) + ' </div><div style="text-align:center;"><?php echo Lang::get('dispatcher.cal_time_price') ?> - <?php echo Config::get('app.generic_keywords.Currency'); ?>' + data.cal_time_price.toFixed(2) + '</div><div style="text-align:center;"><?php echo Lang::get('dispatcher.total') ?> - <?php echo Config::get('app.generic_keywords.Currency'); ?>' + data.cal_total.toFixed(2) + '</div></div>')
                                },
                                error: function (a, b, c) {
                                    console.log(a);
                                    console.log(b);
                                    console.log(c);
                                }
                            });


                        } else {
                            window.alert('Directions request failed due to ' + status);
                        }

                    });


                }
                else {
                    if ($('#typeofCar').val() == '') {
                        alert("Please Select Any type of car");
                    }
                    else if ($('#pickupLatitude').val() == '') {
                        alert("Please Select Pick up Point");
                    }
                    else {
                        alert("Please Select drop Point");
                    }
                }


            });


            $('#typeofCar').change(function (e) {
                var icon = "<?php echo asset_url(); ?>/image/driver_available.png";
                var shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
                var type = $(this).val();
                if (type != '') {
                    $.ajax({
                        type: "post",
                        url: "{{ URL::Route('DispatchProviderXml') }}",
                        data: "type=" + type,
                        success: function (data) {
                            var markers = JSON.parse(data);
                            if (markers.success) {
                                for (var i = 0; i < markers.walkers.length; i++) {

                                    var point = new google.maps.LatLng(
                                            parseFloat(markers.walkers[i].lati),
                                            parseFloat(markers.walkers[i].longi));
                                    marker = new google.maps.Marker({
                                        map: map,
                                        position: point,
                                        icon: icon,
                                        shadow: shadow
                                    });
                                    newmarkersArray.push(marker);
                                    html = '<?php echo Lang::get('dispatcher.name'); ?> : ' + markers.walkers[i].fname + ' ' + markers.walkers[i].lname + '<br><?php echo Lang::get('dispatcher.car_type'); ?>:' + markers.walkers[i].type_name + '<br><b><?php echo Lang::get('dispatcher.available'); ?></b>';
                                    bindInfoWindow(marker, map, infowindow_driver, html, markers.walkers[i].type);
                                }
                            }
                            clearOverlays(markersArray);
                            markersArray = newmarkersArray;
                            newmarkersArray = [];
                            $('#price_r').empty();
                            clearTimeout(driver_update);
                        },
                        error: function (a, b, c) {
                            console.log(a);
                            console.log(b);
                            console.log(c);
                        }
                    });


                    $.ajax({
                        type: "post",
                        url: "{{ URL::Route('Dispatcheta') }}",
                        data: "type=" + type,
                        success: function (data) {
                            //console.log(data);
                            $('#price_d').show().html('<div class="input-group" style="border:1px solid black;"><h3 style="text-align:center;">' + data.name + '</h3><div style="text-align:center;"><?php echo Lang::get('dispatcher.base_price') ?> - <?php echo Config::get('app.generic_keywords.Currency'); ?>' + data.base_price + '</div><div style="text-align:center;"><?php echo Lang::get('dispatcher.base_distance') ?> - ' + data.base_distance + ' Km</div><div style="text-align:center;"><?php echo Lang::get('dispatcher.distance_price') ?> - <?php echo Config::get('app.generic_keywords.Currency'); ?>' + data.distance_price + '</div><div style="text-align:center;"><?php echo Lang::get('dispatcher.time_price') ?> - <?php echo Config::get('app.generic_keywords.Currency'); ?>' + data.time_price + '</div></div>')
                        },
                        error: function (a, b, c) {
                            console.log(a);
                            console.log(b);
                            console.log(c);
                        }
                    });

                }

            });


            // pick up  marker line
            marker_pick.addListener('dragend', function () {

                geocoder = new google.maps.Geocoder();
                geocoder.geocode
                ({
                            latLng: marker_pick.getPosition()
                        },
                        function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                $("#pickupLocation").val(results[0].formatted_address);
                                infowindow.setContent(results[0].formatted_address);
                                infowindow.open(map, marker_pick);
                                document.getElementById("pickupLatitude").value = marker_pick.getPosition().lat();
                                document.getElementById("pickupLongitude").value = marker_pick.getPosition().lng();
                                var drop_point_ref = document.getElementById("dropoffLatitude").value;

                                if (drop_point_ref != '') {
                                    calculateAndDisplayRoute(directionsService, directionsDisplay, marker_pick, marker_drop, map);
                                }

                            }
                            else {
                                alert("<?php echo Lang::get('dispatcher.cant_get_address_for_this_place') ?>");
                            }
                        }
                );


            });


            // drop  marker line
            marker_drop.addListener('dragend', function () {

                geocoder = new google.maps.Geocoder();
                geocoder.geocode
                ({
                            latLng: marker_drop.getPosition()
                        },
                        function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                $("#dropoffLocation").val(results[0].formatted_address);
                                infowindow_drop.setContent(results[0].formatted_address);
                                infowindow_drop.open(map, marker_drop);
                                document.getElementById("dropoffLatitude").value = marker_drop.getPosition().lat();
                                document.getElementById("dropoffLongitude").value = marker_drop.getPosition().lng();
                                calculateAndDisplayRoute(directionsService, directionsDisplay, marker_pick, marker_drop, map);
                            }
                            else {
                                alert("<?php echo Lang::get('dispatcher.cant_get_address_for_this_place') ?>");
                            }
                        }
                );


            });

            /*directionsDisplay.addListener('directions_changed', function() {
             //computeTotalDistance(directionsDisplay.getDirections());
             var respo_val = directionsDisplay.getDirections();
             var last=respo_val.routes[0].overview_path.length;
             document.getElementById("pickupLatitude").value=   respo_val.routes[0].overview_path[0].lat();
             document.getElementById("pickupLongitude").value=  respo_val.routes[0].overview_path[0].lng();
             document.getElementById("dropoffLatitude").value=  respo_val.routes[0].overview_path[last-1].lat();
             document.getElementById("dropoffLongitude").value= respo_val.routes[0].overview_path[last-1].lat();
             });*/


        }
        var line_count = 0;
        var flightPath = [];

        function calculateAndDisplayRoute(directionsService, directionsDisplay, marker_pick, marker_drop, map) {
            directionsService.route({
                origin: marker_pick.getPosition(),  // Haight.
                destination: marker_drop.getPosition(),  // Ocean Beach.
                // Note that Javascript allows us to access the constant
                // using square brackets and a string value as its
                // "property."
                travelMode: 'DRIVING'
            }, function (response, status) {
                //console.log(response);
                if (status == 'OK') {
                    var last = response.routes[0].overview_path.length;
                    var path = [];
                    for (var i = 0; i <= last - 1; i++) {
                        path.push({
                            "lat": response.routes[0].overview_path[i].lat(),
                            "lng": response.routes[0].overview_path[i].lng()
                        });
                    }

                    flightPath[line_count] = new google.maps.Polyline({
                        path: path,
                        strokeColor: '#6ea2f8',
                        strokeOpacity: 1.0,
                        strokeWeight: 5
                    });


                    flightPath[line_count].setMap(map);
                    if (line_count != 0) {
                        flightPath[line_count - 1].setMap(null);
                    }

                    //legend
                    var legend = document.getElementById('legend');
                    $('#legend').show().empty();
                    var div = document.createElement('div');


                    div.innerHTML = '<h6><?php echo Lang::get('dispatcher.legend') ?></h6><?php echo Lang::get('dispatcher.distance') ?> - ' + response.routes[0].legs[0].distance['text'] + '<br><?php echo Lang::get('dispatcher.duration') ?> - ' + response.routes[0].legs[0].duration['text']
                            + "<br><img src='" + marker1 + "'> <?php echo Lang::get('dispatcher.pickup_marker') ?> <br><img src='" + marker2 + "'> <?php echo Lang::get('dispatcher.drop_marker') ?> ";
                    legend.appendChild(div);
                    //
                    map.controls[google.maps.ControlPosition.RIGHT_TOP].push(legend);
                    map.controls[google.maps.ControlPosition.RIGHT_TOP] = [];


                    line_count++;

                    document.getElementById("pickupLatitude").value = response.routes[0].overview_path[0].lat();
                    document.getElementById("pickupLongitude").value = response.routes[0].overview_path[0].lng();
                    document.getElementById("dropoffLatitude").value = response.routes[0].overview_path[last - 1].lat();
                    document.getElementById("dropoffLongitude").value = response.routes[0].overview_path[last - 1].lng();

                    //directionsDisplay.setDirections(response);
                } else {
                    window.alert('Directions request failed due to ' + status);
                }
            });


        }


        $('#customerMobile').focusout(function () {

            var mobile_num = $('#customerMobile').val();
            if (mobile_num == '') {
                $('#customerMobile').focus();
            }
            else {
                $.ajax({
                    type: "post",
                    url: '<?php echo asset_url() . "/dispatch/userdetail"; ?>',
                    data: "num=" + mobile_num,
                    success: function (data) {
                        var response = JSON.parse(data);
                        if (response.success) {
                            $('#customerfirstName').val(response.fname);
                            $('#customerLastName').val(response.lname);
                            $('#customerId').val(response.id);
                        }
                        else {
                            $('#customerfirstName').val('');
                            $('#customerLastName').val('');
                            $('#customerId').val('');
                        }

                    },
                    error: function (a, b, c) {
                        console.log(a);
                        console.log(b);
                        console.log(c);
                    }
                });

            }
        });

        $(document).ready(function (e) {
            $.ajax({
                type: "get",
                url: '<?php echo asset_url() . "/application/types"; ?>',
                success: function (data) {
                    var type_len = data.types.length;
                    for (i = 0; i <= type_len - 1; i++) {
                        $('#typeofCar').append('<option value="' + data.types[i].id + '">' + data.types[i].name + '</option>');
                    }
                },
                error: function (a, b, c) {
                    console.log(a);
                    console.log(b);
                    console.log(c);
                }
            });
        });

        function clearOverlays(arr) {
            for (var i = 0; i < arr.length; i++) {
                arr[i].setMap(null);
            }
        }
        init(23.5687272, 31.6807276);


        function update_driver(map) {

            $.ajax({
                type: "post",
                url: "{{ URL::Route('DispatchallProviderXml') }}",
                success: function (data) {
                    console.log(data);
                    var markers = JSON.parse(data);
                    if (markers.success) {
                        for (var i = 0; i < markers.walkers.length; i++) {

                            if (markers.walkers[i].type == 'free') {
                                var point = new google.maps.LatLng(
                                        parseFloat(markers.walkers[i].lati),
                                        parseFloat(markers.walkers[i].longi));
                                marker = new google.maps.Marker({
                                    map: map,
                                    position: point,
                                    icon: icon,
                                    shadow: shadow
                                });
                                newmarkersArray.push(marker);
                                html = '<?php echo Lang::get('dispatcher.name'); ?> : ' + markers.walkers[i].fname + ' ' + markers.walkers[i].lname + '<br><?php echo Lang::get('dispatcher.car_type'); ?>:' + markers.walkers[i].type_name + '<br><b><?php echo Lang::get('dispatcher.available'); ?></b>';
                            }
                            else {
                                var point = new google.maps.LatLng(
                                        parseFloat(markers.walkers[i].lati),
                                        parseFloat(markers.walkers[i].longi));
                                marker = new google.maps.Marker({
                                    map: map,
                                    position: point,
                                    icon: icon1,
                                    shadow: shadow
                                });
                                newmarkersArray.push(marker);
                                html = '<?php echo Lang::get('dispatcher.name'); ?> : ' + markers.walkers[i].fname + ' ' + markers.walkers[i].lname + '<br><?php echo Lang::get('dispatcher.car_type'); ?>:' + markers.walkers[i].type_name + '<br><b><?php echo Lang::get('dispatcher.on_trip'); ?></b>';
                            }
                            bindInfoWindow(marker, map, infowindow_driver, html, markers.walkers[i].type);

                        }

                        clearOverlays(markersArray);
                        markersArray = newmarkersArray;
                        newmarkersArray = [];
                    }
                    driver_update = setTimeout(function () {

                        update_driver(map);
                    }, 5000);
                },
                error: function (a, b, c) {
                    console.log(a);
                    console.log(b);
                    console.log(c);
                }
            });


        }


        var checker;


        $('#rideNowbutton').click(function (e) {
            e.preventDefault();
            var phone_num = $('#customerMobile').val();
            var user_id = $('#customerId').val();
            var fname = $('#customerfirstName').val();
            var lname = $('#customerLastName').val();
            var address = $('#customerAddress').val();
            var pick_location = $('#pickupLocation').val();
            var pick_lati = $('#pickupLatitude').val();
            var pick_longi = $('#pickupLongitude').val();
            var pick_detail = $('#pickupDetails').val();
            var drop_location = $('#dropoffLocation').val();
            var drop_lati = $('#dropoffLatitude').val();
            var drop_longi = $('#dropoffLongitude').val();
            var drop_detail = $('#dropoffDetails').val();
            var adult = $('#numberOfadults').val();
            var child = $('#noOfchildren').val();
            var type = $('#typeofCar').val();
            var luggage = $('#luggageCount').val();
            var ridecomment = $('#rideComment').val();

            if (phone_num != '' && fname != '' && lname != '' && pick_location != '' && drop_location != '' && type != '') {

                create_trip();

            }
            else {
                alert("Don't leave empty field");
                return false;
            }

        });

        //$('#rideNowbutton').click(function(e) {
        function create_trip() {

            var phone_num = $('#customerMobile').val();
            var user_id = $('#customerId').val();
            var fname = $('#customerfirstName').val();
            var lname = $('#customerLastName').val();
            var address = $('#customerAddress').val();
            var pick_location = $('#pickupLocation').val();
            var pick_lati = $('#pickupLatitude').val();
            var pick_longi = $('#pickupLongitude').val();
            var pick_detail = $('#pickupDetails').val();
            var drop_location = $('#dropoffLocation').val();
            var drop_lati = $('#dropoffLatitude').val();
            var drop_longi = $('#dropoffLongitude').val();
            var drop_detail = $('#dropoffDetails').val();
            var adult = $('#numberOfadults').val();
            var child = $('#noOfchildren').val();
            var type = $('#typeofCar').val();
            var luggage = $('#luggageCount').val();
            var ridecomment = $('#rideComment').val();


            $.ajax({
                type: "post",
                url: '<?php echo asset_url() . '/dispatch/createtrip'; ?>',
                data: {
                    "phone": phone_num,
                    "user_id": user_id,
                    "fname": fname,
                    "lname": lname,
                    "address": address,
                    "pick_location": pick_location,
                    "pick_lati": pick_lati,
                    "pick_longi": pick_longi,
                    "pick_detail": pick_detail,
                    "drop_location": drop_location,
                    "drop_lati": drop_lati,
                    "drop_longi": drop_longi,
                    "drop_detail": drop_detail,
                    "type": type,
                    "adult": adult,
                    "child": child,
                    "ridecomment": ridecomment,
                    "luggage": luggage
                },
                async: true,
                beforeSend: function (dat) {
                    $('body').removeClass('loaded');
                },
                success: function (response_user) {
                    $('body').addClass('loaded');
                    console.log(response_user);
                    if (response_user.success) {
                        $('#customerId').val(response_user.userid);
                        if (response_user.method == 'manual') {
                            $('#myModal').modal('toggle');
                            $('#tit').html('<?php echo Lang::get('dispatcher.select_driver') ?>');
                            var select_design = '<div class="form-group"><div class="input-group"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span><select class="form-control" id="driver" placeholder="Booking Date" required="" ><option value=""><?php echo Lang::get('dispatcher.select_driver') ?></option></select></div></div>';
                            $('#dis').html(select_design);
                            for (var i = 0; i < response_user.walker.length; i++) {

                                $('#driver').append('<option value="' + response_user.walker[i].id + '&' + i + '">' + response_user.walker[i].first_name + ' ' + response_user.walker[i].last_name + '</option>');
                            }
                            $('#driver').change(function (e) {
                                if ($(this).val() != '') {
                                    var sel = $(this).val().split('&');
                                    $.ajax({
                                        type: "post",
                                        url: '<?php echo asset_url() . '/dispatch/setdriver'; ?>',
                                        data: {
                                            "request_id": response_user.request_id,
                                            "driver_id": sel[0],
                                            "user_id": user_id
                                        },
                                        async: true,
                                        beforeSend: function (dat) {
                                            $('body').removeClass('loaded');
                                        },
                                        success: function (data) {
                                            $('body').addClass('loaded');
                                            if (data.success) {
                                                var waiting_design = '<div class="form-group"><div class="input-group"><div class="col-md-6"><img width="100" height="100" src="' + response_user.walker[sel[1]].picture + '"></div><div class="col-md-6"><div class="col-md-12"><?php echo Lang::get('dispatcher.name') ?>:' + response_user.walker[sel[1]].first_name + ' ' + response_user.walker[sel[1]].last_name + '</div><div class="col-md-12"><?php echo Lang::get('dispatcher.phone') ?>:' + response_user.walker[sel[1]].phone + '</div><div class="col-md-12"><button class="btn btn-btn-danger" style="display:none;background: rgb(70, 204, 190) none repeat scroll 0% 0%;color: white;" id="cancel">Cancel Request</button></div>';
                                                $('#dis').html(waiting_design);
                                                localStorage.setItem("driverid", sel[0]);
                                                check_request_status(response_user.request_id, sel[0]);

                                                $('#cancel').click(function (e) {
                                                    clearInterval(checker);
                                                    $.ajax({
                                                        type: "post",
                                                        url: '<?php echo asset_url() . '/dispatch/canceltrip'; ?>',
                                                        data: {"request_id": response_user.request_id},
                                                        async: true,
                                                        beforeSend: function (dat) {
                                                            $('body').removeClass('loaded');
                                                        },
                                                        success: function (canc) {
                                                            $('body').addClass('loaded');
                                                            if (canc) {


                                                                //$('#routeForm').reset();
                                                                window.location = window.location;

                                                            }
                                                        },
                                                        error: function (a, b, c) {
                                                            console.log(a);
                                                            console.log(b);
                                                            console.log(c);
                                                        }
                                                    });
                                                });

                                            }
                                        },
                                        error: function (a, b, c) {
                                            console.log(a);
                                            console.log(b);
                                            console.log(c);
                                        }
                                    });
                                }

                            });

                        }
                        else {
                            $('#myModal').modal('toggle');
                            var waiting_design = '<div class="form-group"><div class="input-group"><div class="col-md-6"><img width="100" height="100" src="' + response_user.walker.picture + '"></div><div class="col-md-6"><div class="col-md-12"><?php echo Lang::get('dispatcher.name') ?>:' + response_user.walker.first_name + ' ' + response_user.walker.last_name + '</div><div class="col-md-12"><?php echo Lang::get('dispatcher.phone') ?>:' + response_user.walker.phone + '</div><div class="col-md-12"><button class="btn btn-btn-danger" style="display:none;background: rgb(70, 204, 190) none repeat scroll 0% 0%;color:white;" id="cancel1">Cancel Request</button></div>';
                            $('#dis').html(waiting_design);
                            localStorage.setItem("driverid", response_user.walker.id);
                            check_request_status(response_user.request_id, response_user.walker.id);
                            $('#cancel1').click(function (e) {
                                clearInterval(checker);
                                $.ajax({
                                    type: "post",
                                    url: '<?php echo asset_url() . '/dispatch/canceltrip'; ?>',
                                    data: {"request_id": response_user.request_id},
                                    async: true,
                                    beforeSend: function (dat) {
                                        $('body').removeClass('loaded');
                                    },
                                    success: function (canc) {
                                        $('body').addClass('loaded');
                                        if (canc) {


                                            //$('#routeForm').reset();
                                            window.location = window.location;

                                        }
                                    },
                                    error: function (a, b, c) {
                                        console.log(a);
                                        console.log(b);
                                        console.log(c);
                                    }
                                });
                            });
                        }
                    }
                    else {
                        $('#customerId').val(response_user.userid);
                        //console.log(response_user);
                        if (response_user.error != '') {
                            alert(response_user.error);
                        }

                        var error = "";


                        if (Array.isArray(response_user.error_messages)) {
                            for (var i = 0; i < response_user.error_messages.length; i++) {
                                error += response_user.error_messages[i] + ',';

                            }
                        }
                        else {
                            error = response_user.error_messages;
                        }

                        alert(error);
                        return false;
                    }
                },
                error: function (a, b, c) {
                    console.log(a);
                    console.log(b);
                    console.log(c);
                }
            });


        }


        function check_request_status(request_id, driver) {

            $.ajax({
                type: "post",
                url: '<?php echo asset_url() . '/dispatch/statustrip'; ?>',
                data: {"request_id": request_id, "driver_id": driver},
                async: true,
                success: function (canc) {
                    //console.log(canc);
                    if (canc.success) {
                        localStorage.removeItem("driverid");

                        clearInterval(checker);
                        $('#cancel').show();
                        $('#cancel1').show();
                        if (canc.pty == 1) {

                            $('#dis').append('<div class="form-group"><div class="input-group"><div class="col-md-12"><?php echo Lang::get('dispatcher.all_driver_are_busy') ?></div><div class="col-md-12" ><button id="close" style="background: rgb(254, 79, 79) none repeat scroll 0% 0%;color:white;"  class="btn btn-btn-danger" onClick="window.location=window.location"><?php echo Lang::get('dispatcher.close') ?></button></div></div></div>');
                        }
                        else if (canc.pty == 2) {
                            //$('.modal-body').append('<div class="col-md-12">All Drivers Are Busy</div><div class="col-md-12" id="close">close</div>');
                            alert('<?php echo Lang::get('dispatcher.Last_request_cancelled') ?>');
                            //$('#myModal').modal('toggle');
                            window.location = window.location;
                        }
                        else if (canc.pty == 3) {
                            $('#dis').append('<div class="form-group"><div class="input-group"><div class="col-md-12"><?php echo Lang::get('dispatcher.driver_accepted') ?></div><div class="col-md-12" ><button id="close" style="background: rgb(254, 79, 79) none repeat scroll 0% 0%;color:white;"  class="btn btn-btn-danger" onClick="window.location=window.location"><?php echo Lang::get('dispatcher.close') ?></button></div></div></div>');
                        }

                        $('#close').click(function (e) {
                            //$('#routeForm').reset();
                            window.location = window.location;
                        });
                    }
                    else {
                        if (localStorage.getItem("driverid") != canc.id) {
                            localStorage.setItem("driverid") = canc.id;
                            var waiting_design = '<div class="form-group"><div class="input-group"><div class="col-md-6"><img width="100" height="100" src="' + canc.picture + '"></div><div class="col-md-6"><div class="col-md-12"><?php echo Lang::get('dispatcher.name') ?>:' + canc.fname + ' ' + canc.lname + '</div><div class="col-md-12"><?php echo Lang::get('dispatcher.phone') ?>:' + canc.phone + '</div><div class="col-md-12"><button class="btn btn-btn-danger" style="display:none;background: rgb(70, 204, 190) none repeat scroll 0% 0%;color:white;" id="cancel1">Cancel Request</button></div>';
                            $('#dis').html(waiting_design);


                        }
                        chcker = setInterval(check_request_status(request_id, canc.id), 3000);
                    }
                },
                error: function (a, b, c) {
                    console.log(a);
                    console.log(b);
                    console.log(c);
                }
            });
        }


        function valid() {
            var phone_num = $('#customerMobile').val();
            var user_id = $('#customerId').val();
            var fname = $('#customerfirstName').val();
            var lname = $('#customerLastName').val();
            var address = $('#customerAddress').val();
            var pick_location = $('#pickupLocation').val();
            var pick_lati = $('#pickupLatitude').val();
            var pick_longi = $('#pickupLongitude').val();
            var pick_detail = $('#pickupDetails').val();
            var drop_location = $('#dropoffLocation').val();
            var drop_lati = $('#dropoffLatitude').val();
            var drop_longi = $('#dropoffLongitude').val();
            var drop_detail = $('#dropoffDetails').val();
            var adult = $('#numberOfadults').val();
            var child = $('#noOfchildren').val();
            var type = $('#typeofCar').val();
            var luggage = $('#luggageCount').val();
            var ridecomment = $('#rideComment').val();

            if (phone_num != '' && fname != '' && lname != '' && pick_location != '' && drop_location != '' && type != '') {

                $('#myModal_sch').modal('toggle');
                return true;
            }
            else {
                alert("Don't leave empty field");
                return false;
            }

        }


        function addbookingAjax(schedule=0) {
            console.log("Inside Ajax call");


            var phone_num = $('#customerMobile').val();
            var user_id = $('#customerId').val();
            var fname = $('#customerfirstName').val();
            var lname = $('#customerLastName').val();
            var address = $('#customerAddress').val();
            var pick_location = $('#pickupLocation').val();
            var pick_lati = $('#pickupLatitude').val();
            var pick_longi = $('#pickupLongitude').val();
            var pick_detail = $('#pickupDetails').val();
            var drop_location = $('#dropoffLocation').val();
            var drop_lati = $('#dropoffLatitude').val();
            var drop_longi = $('#dropoffLongitude').val();
            var drop_detail = $('#dropoffDetails').val();
            var adult = $('#numberOfadults').val();
            var child = $('#noOfchildren').val();
            var type = $('#typeofCar').val();
            var luggage = $('#luggageCount').val();
            var ridecomment = $('#rideComment').val();


            var scheduleDateTime = 0;
            if (schedule == 1) {
                var time = $("#booking_time_picker").val();
                var hours = Number(time.match(/^(\d+)/)[1]);
                var minutes = Number(time.match(/:(\d+)/)[1]);
                var AMPM = time.match(/\s(.*)$/)[1];
                if (AMPM == "PM" && hours < 12) hours = hours + 12;
                if (AMPM == "AM" && hours == 12) hours = hours - 12;
                var sHours = hours.toString();
                var sMinutes = minutes.toString();
                if (hours < 10) sHours = "0" + sHours;
                if (minutes < 10) sMinutes = "0" + sMinutes;
                console.log(sHours + ":" + sMinutes);
                scheduleDateTime = $('#booking_date_picker').val() + ' ' + sHours + ":" + sMinutes + ':00';


            }

            $.ajax({
                url: '<?php echo asset_url() . '/dispatch/schedule'; ?>',
                data: {
                    "user_timezone": getTimeZone(),
                    "schedule": scheduleDateTime,
                    "phone": phone_num,
                    "user_id": user_id,
                    "fname": fname,
                    "lname": lname,
                    "address": address,
                    "pick_location": pick_location,
                    "pick_lati": pick_lati,
                    "pick_longi": pick_longi,
                    "pick_detail": pick_detail,
                    "drop_location": drop_location,
                    "drop_lati": drop_lati,
                    "drop_longi": drop_longi,
                    "drop_detail": drop_detail,
                    "type": type,
                    "adult": adult,
                    "child": child,
                    "ridecomment": ridecomment,
                    "luggage": luggage
                },
                error: function (a, b, c) {
                    console.log(a);
                    console.log(b);
                    console.log(c);
                },

                beforeSend: function (dat) {
                    $('body').removeClass('loaded');
                },
                success: function (data) {
                    $('body').addClass('loaded');
                    //console.log("Book a drive now below");
                    //console.log(data);
                    if (data.success) {
                        alert('<?php echo Lang::get('dispatcher.your_trip_schdule_successfully') ?>');
                        window.location = window.location;
                    }
                    else {
                        var error = "";
                        if (Array.isArray(data.error_messages)) {
                            for (var i = 0; i < data.error_messages.length; i++) {
                                error += data.error_messages[i] + ',';

                            }
                        }
                        else {
                            error = data.error_messages;
                        }

                        alert(error);
                        return false;
                    }

                },
                type: 'POST'
            });
            marklocationinmap();
            return false;
        }


        function getTimeZone() {
            var offset = new Date().getTimezoneOffset(), o = Math.abs(offset);
            return (offset < 0 ? "+" : "-") + ("00" + Math.floor(o / 60)).slice(-2) + ":" + ("00" + (o % 60)).slice(-2);
        }


        function bindInfoWindow(marker, map, infowindow_driver, html, type) {
            infowindow_driver.setContent(html);
            infowindow_driver.close(map, marker);
            google.maps.event.addListener(marker, 'click', function () {


                if (type == 'free') {

                    infowindow_driver.setContent(html);

                    infowindow_driver.open(map, marker);

                } else {

                    infowindow_driver.setContent(html);

                    infowindow_driver.open(map, marker);

                }

            });
        }

    </script>

@stop