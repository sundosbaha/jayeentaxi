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
        <form method="post" id="main-form" action="{{ URL::Route('AddZoneDivision') }}" enctype="multipart/form-data">
            <div class="col-md-12 col-sm-12">
                <div class="box box-primary" style="height: 650px;">
                    <div class="box-header">
                        <h3 class="box-title">{{$title}} - Add</h3>
                    </div> <!-- /.box-header -->


                    <div class="col-md-11 col-sm-10">

                        <div class="form-group">
                            <label> Zone Name </label>
                            <input class="form-control" type="text" name="zone_name" value="" placeholder="Zone Name"
                                   required>
                        </div>
                    </div>


                    <div class="col-md-11 col-sm-10">
                        <div id="map" style="width: 100%;height: 500px;">

                        </div>
                    </div>
                    <!-- dropdown menu-->
                    <div class="dropdown">
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
                    </div>

                </div>


                <!-- second area-->
                <div class="box box-primary">


                    <div class="form-group">
                        <label>Service Type</label>
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th>{{ trans('customize.type') }}</th>
                                <th>{{ trans('customize.base_price') }}</th>
                                <th>{{ trans('customize.price_per_unit_distance') }}</th>
                                <th>{{ trans('customize.price_per_unit_time') }}</th>
                                <th>{{ trans('customize.max_size') }}</th>
                                <th>{{ trans('customize.base_distance') }}</th>
                                <th>{{ trans('customize.visible') }}</th>
                            </tr>
                            <?php $count = 1;  ?>
                            @foreach($walkerTypes as $key => $type)
                                <tr>
                                    <td>
                                        <input class="form-control" name="typeid[]" data-type="{{$type->name}}"
                                               title="Type Id" type="hidden" placeholder="Type" value="{{$type->id}}"
                                               readonly>
                                        <input class="form-control" name="type[]" id="type_{{$type->id}}"
                                               data-type="{{$type->name}}" title="Type" type="text" placeholder="Type"
                                               value="{{$type->name}}" disabled><br>
                                    </td>
                                    <td>
                                        <input class="form-control" name="service_base_price[]"
                                               data-type="{{$type->name}}" data-id="{{$type->id}}" title="Base Price"
                                               type="text" value="" placeholder="Base Price"><br>
                                    </td>
                                    <td>
                                        <input class="form-control" name="service_price_distance[]"
                                               data-type="{{$type->name}}" data-id="{{$type->id}}"
                                               title="Price per unit distance" type="text" value=""
                                               placeholder="Price per unit distance"><br>

                                    </td>
                                    <td>
                                        <input class="form-control" name="service_price_time[]"
                                               data-type="{{$type->name}}" data-id="{{$type->id}}"
                                               title="Price per unit time" type="text" value=""
                                               placeholder="Price per unit time"><br>
                                    </td>
                                    <td>
                                        <input class="form-control" name="service_max_size[]"
                                               data-type="{{$type->name}}" data-id="{{$type->id}}" title="Max Size"
                                               type="text" value="" placeholder="Max Size"><br>
                                    </td>
                                    <td>
                                        <input class="form-control" name="service_base_distance[]"
                                               data-type="{{$type->name}}" data-id="{{$type->id}}" title="Base Distance"
                                               type="text" value="" placeholder="Base Distance"><br>
                                    </td>
                                    <td>
                                        <input class="form-control" name="visible_{{$type->id}}"
                                               id="visible_{{$type->id}}" data-id="{{$type->id}}"
                                               data-type="{{$type->name}}" title="Visible" type="checkbox" value="yes"
                                               checked><br>
                                    </td>
                                <?php $count++; ?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-flat btn-block">Save</button>
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