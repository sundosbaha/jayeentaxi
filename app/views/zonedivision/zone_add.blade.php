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
            padding: 12px 20px;
            text-decoration: none;
            display: block;
        }

        .dropdown a:hover {
            background-color: #f1f1f1
        }

        .show {
            display: block;
        }

        .sk-cube-grid {
            width: 40px;
            height: 40px;
            padding: 22% 47%;
            background: white;
            top:0px;
            height: 500px;

        }

        .sk-cube-grid .sk-cube {
            width: 33%;
            height: 33%;
            background-color: #333;
            float: left;
            -webkit-animation: sk-cubeGridScaleDelay 1.3s infinite ease-in-out;
            animation: sk-cubeGridScaleDelay 1.3s infinite ease-in-out;
        }
        .sk-cube-grid .sk-cube1 {
            -webkit-animation-delay: 0.2s;
            animation-delay: 0.2s; }
        .sk-cube-grid .sk-cube2 {
            -webkit-animation-delay: 0.3s;
            animation-delay: 0.3s; }
        .sk-cube-grid .sk-cube3 {
            -webkit-animation-delay: 0.4s;
            animation-delay: 0.4s; }
        .sk-cube-grid .sk-cube4 {
            -webkit-animation-delay: 0.1s;
            animation-delay: 0.1s; }
        .sk-cube-grid .sk-cube5 {
            -webkit-animation-delay: 0.2s;
            animation-delay: 0.2s; }
        .sk-cube-grid .sk-cube6 {
            -webkit-animation-delay: 0.3s;
            animation-delay: 0.3s; }
        .sk-cube-grid .sk-cube7 {
            -webkit-animation-delay: 0s;
            animation-delay: 0s; }
        .sk-cube-grid .sk-cube8 {
            -webkit-animation-delay: 0.1s;
            animation-delay: 0.1s; }
        .sk-cube-grid .sk-cube9 {
            -webkit-animation-delay: 0.2s;
            animation-delay: 0.2s; }

        @-webkit-keyframes sk-cubeGridScaleDelay {
            0%, 70%, 100% {
                -webkit-transform: scale3D(1, 1, 1);
                transform: scale3D(1, 1, 1);
            } 35% {
                  -webkit-transform: scale3D(0, 0, 1);
                  transform: scale3D(0, 0, 1);
              }
        }

        @keyframes sk-cubeGridScaleDelay {
            0%, 70%, 100% {
                -webkit-transform: scale3D(1, 1, 1);
                transform: scale3D(1, 1, 1);
            } 35% {
                  -webkit-transform: scale3D(0, 0, 1);
                  transform: scale3D(0, 0, 1);
              }
        }
    </style>


    <div class="row" >
        <form method="post" id="main-form" action="{{ URL::Route('AddZoneDivision') }}" enctype="multipart/form-data">
            <div class="col-md-12 col-sm-12">
                <div class="box box-primary" style="height: 650px;">
                    <div class="box-header">
                        <h3 class="box-title">{{$title}} -{{ trans('language_changer.add') }}</h3>
                    </div> <!-- /.box-header -->


                    <div class="col-md-11 col-sm-10" dir="{{ trans('language_changer.text_format') }}">

                        <div class="form-group">
                            <label> {{ trans('language_changer.zone_name') }}</label>
                            <input class="form-control" type="text" name="zone_name" value="" placeholder="{{ trans('language_changer.zone_name') }}"
                                   required>
                        </div>
                    </div>


                            <input style="top: 1%; left: 272px; width: 45%;display: none;" class="form-control" type="text" name="auto_input" dir="{{ trans('language_changer.text_format') }}" id="auto_input" value="" placeholder="{{ trans('language_changer.search'),' ',trans('language_changer.place') }}">


                    <div class="col-md-11 col-sm-10">
                        <div class="sk-cube-grid" id="loader" style="z-index: 1;position: absolute;width: 100%;">
                            <div class="sk-cube sk-cube1" style="width: 20px;height: 20px;"></div>
                            <div class="sk-cube sk-cube2" style="width: 20px;height: 20px;"></div>
                            <div class="sk-cube sk-cube3" style="width: 20px;height: 20px;"></div>
                            <div class="clearfix"></div>
                            <div class="sk-cube sk-cube4" style="width: 20px;height: 20px;"></div>
                            <div class="sk-cube sk-cube5" style="width: 20px;height: 20px;"></div>
                            <div class="sk-cube sk-cube6" style="width: 20px;height: 20px;"></div>
                            <div class="clearfix"></div>
                            <div class="sk-cube sk-cube7" style="width: 20px;height: 20px;"></div>
                            <div class="sk-cube sk-cube8"style="width: 20px;height: 20px;"></div>
                            <div class="sk-cube sk-cube9" style="width: 20px;height: 20px;"></div>
                            <p style="text-align: center;font-weight: bold;width: 81px;margin-left: -10px;">Getting Map Data</p>
                        </div>
                        <div id="map" style="width: 100%;height: 500px;">

                        </div>
                    </div>
                    <!-- dropdown menu-->
                    <ul id="side-menu" style="display: none;position: absolute;z-index: 1;width: 100%;">
                        <li class="dropdown-content" style="display: block;"><a style="padding: 6px 19px;" href="#" class="tool1" data-id="reset">
                                <i class="fa fa-refresh" aria-hidden="true"></i> Reset
                            </a>
                        </li>
                        </ul>

                    <ul id="drop"  style="left: 91%; margin-top: 1%;display: none;">
                     <li class="dropdown-content" style="position:relative;display: block;right: 20%;"><a href="#" class="tool1" data-id="pen">
                             <i class="fa fa-pencil" aria-hidden="true"></i>
                         </a>
                     </li>
                       <li class="dropdown-content" style="position: relative;display: block;right: 20%;">
                           <a href="#" class="tool1 tmp-tool" style="display: none;" data-id="delete"><i
                                       class="fa fa-trash-o" aria-hidden="true"></i>
                               </i>
                           </a>
                       </li>

                        <li class="dropdown-content" style="position: relative;display: block;right: 20%;">
                            <a href="#" class="tool1" id="undo" style="display: none;" data-id="undo"><i
                                        class="fa fa-undo" aria-hidden="true"></i>
                                </i>
                            </a>
                        </li>

                    </ul>

                    </div>

                   {{-- <div class="dropdown" id="drop">
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

                </div>--}}


                <!-- second area-->
                <div class="box box-primary" dir="{{ trans('language_changer.text_format') }}">


                    <div class="form-group">
                        <label>{{ trans('language_changer.service'),' ',trans('language_changer.type') }}</label>
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th>{{ trans('language_changer.type') }}</th>
                                <th>{{ trans('language_changer.base_price') }}</th>
                                <th>{{ trans('language_changer.price_per_unit_distance') }}</th>
                                <th>{{ trans('language_changer.price_per_unit_time') }}</th>
                                <th>{{ trans('language_changer.max_size') }}</th>
                                <th>{{ trans('language_changer.base_distance') }}</th>
                                <th>{{ trans('language_changer.visible') }}</th>
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
                    <button type="submit" class="btn btn-primary btn-flat btn-block">{{ trans('language_changer.save') }}</button>
                </div>

            </div>
        </form>
    </div>

    </div>

    <script src="<?php echo asset_url(); ?>/admins/js/AdminLTE/zone-app.js" type="application/javascript"></script>
    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key=<?php echo Config::get('app.gcm_browser_key') ?>&libraries=drawing,places">
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