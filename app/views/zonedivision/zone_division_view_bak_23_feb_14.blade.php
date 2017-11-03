@extends('layout')
@section('content')
    <style>
        .dropbtn {
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            font-size: 17px;
            border: none;
            cursor: pointer;
            border-radius: 50%;
        }

        .dropbtn:hover, .dropbtn:focus {
            background-color: #3e8e41;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            overflow: auto;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown a:hover {
            background-color: #f1f1f1
        }

        .show {
            display: block;
        }
    </style>


    <div class="row">
        <form method="post" id="zone-view" action="{{ URL::Route('MapViewZone') }}" enctype="multipart/form-data">
            <div class="col-md-12 col-sm-12">
                <div class="box box-primary" style="height: 650px;">
                    <div class="box-header">
                        <h3 class="box-title">{{$title}} - View</h3>
                    </div> <!-- /.box-header -->


                    <div class="col-md-11 col-sm-10">

                        <div class="form-group">
                            <input class="form-control" type="hidden" name="zone_id" id="zone_id"
                                   value="<?php echo(!empty($zone_id) ? $zone_id : ''); ?>" placeholder="Zone ID">
                        </div>
                    </div>


                    <div class="col-md-11 col-sm-10">
                        <div id="map" style="width: 100%;height: 500px;">

                        </div>
                    </div>
                    <!-- dropdown menu-->
                    {{--<div class="dropdown">
                        <button type="button" onclick="myFunction()" data-toggle="tooltip" data-placement="top"
                                title="Click To Select Tool" class="dropbtn"><i class="fa fa-bars fa-6"
                                                                                aria-hidden="true"></i>
                        </button>
                        <div id="myDropdown" class="dropdown-content">
                            <a href="#" class="tool1" data-id="pen"><i class="fa fa-pencil" aria-hidden="true"></i>
                            </a>
                            <a href="#" class="tool1 tmp-tool" style="display: none;" data-id="delete"><i
                                        class="fa fa-trash-o" aria-hidden="true"></i>
                                </i>
                            </a>
                        </div>
                    </div>--}}

                </div>


            </div>
        </form>
    </div>

    </div>

    <script src="<?php echo asset_url(); ?>/admins/js/AdminLTE/zone-app.js" type="application/javascript"></script>

    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key=<?php echo Config::get('app.gcm_browser_key') ?>&libraries=drawing">
    </script>
    <script>

        var site_url = "<?php echo asset_url(); ?>";
        /* When the user clicks on the button,
         toggle between hiding and showing the dropdown content */
        function myFunction() {
            document.getElementById("myDropdown").classList.toggle("show");
        }

        // Close the dropdown if the user clicks outside of it
        window.onclick = function (event) {
            if (!event.target.matches('.dropbtn')) {

                var dropdowns = document.getElementsByClassName("dropdown-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>






@stop
