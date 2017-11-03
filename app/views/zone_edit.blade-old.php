@extends('layout')
@section('content')

<div class="row">
    <form method="post" id="main-form-edit" action="{{ URL::Route('EditZoneDivision') }}" enctype="multipart/form-data">
        <div class="col-md-12 col-sm-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">{{$title}} - Edit</h3>
                </div> <!-- /.box-header -->

                <div class="form-group">
                    <label> Zone Name </label>
                    <input class="form-control" type="hidden" name="zone_id" value="{{$zone->zone_id }}"
                           placeholder="Zone Name"
                           required>
                    <input class="form-control" type="text" name="zone_name" value="{{$zone->zone_name }}"
                           placeholder="Zone Name"
                           required>
                    <div id="map">
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
                        <?php $count = 1;
                        $zone_distance = explode(',', $zone->price_per_unit_distance);
                        $zone_time = explode(',', $zone->price_per_unit_time);
                        $zone_base = explode(',', $zone->base_price);
                        $max_size = explode(',', $zone->max_size);
                        $is_visible = explode(',', $zone->is_visible);
                        $type_name = explode(',', $zone->type_name);
                        $type_id = explode(',', $zone->type_id);
                        for ($i = 0;
                        $i < count($type_id);
                        $i++)
                        {
                        ?>

                        <tr>
                            <td>
                                <input class="form-control" name="typeid[]" data-type="{{$type_name[$i]}}"
                                       title="Type Id" type="hidden" placeholder="Type" value="{{$type_id[$i]}}"
                                       readonly>
                                <input class="form-control" name="type[]" id="type_{{$type_id[$i]}}"
                                       data-type="{{$type_name[$i]}}" title="Type" type="text" placeholder="Type"
                                       value="{{$type_name[$i]}}" readonly><br>
                            </td>
                            <td>
                                <input class="form-control" name="service_base_price[]"
                                       data-type="{{$type_name[$i]}}" data-id="{{$type_id[$i]}}" title="Base Price"
                                       type="text" value="{{$zone_base[$i]}}" placeholder="Base Price"><br>
                            </td>
                            <td>
                                <input class="form-control" name="service_price_distance[]"
                                       data-type="{{$type_name[$i]}}" data-id="{{$type_id[$i]}}"
                                       title="Price per unit distance" type="text" value="{{$zone_distance[$i]}}"
                                       placeholder="Price per unit distance"><br>

                            </td>
                            <td>
                                <input class="form-control" name="service_price_time[]"
                                       data-type="{{$type_name[$i]}}" data-id="{{$type_id[$i]}}"
                                       title="Price per unit time" type="text" value="{{$zone_time[$i]}}"
                                       placeholder="Price per unit time"><br>
                            </td>
                            <td>
                                <input class="form-control" name="service_max_size[]"
                                       data-type="{{$type_name[$i]}}" data-id="{{$type_id[$i]}}" title="Max Size"
                                       type="text" value="{{$max_size[$i]}}" placeholder="Max Size"><br>
                            </td>
                            <td>
                                <input class="form-control" name="service_base_distance[]"
                                       data-type="{{$type_name[$i]}}" data-id="{{$type_id[$i]}}"
                                       title="Base Distance"
                                       type="text" value="{{$max_size[$i]}}" placeholder="Base Distance"><br>
                            </td>
                            <td>
                                <input class="form-control" name="visible_{{$type_id[$i]}}"
                                       id="visible_{{$type_id[$i]}}" data-id="{{$type_id[$i]}}"
                                       data-type="{{$type_name[$i]}}" title="Visible" type="checkbox" value="yes"
                                    <?php if ($is_visible[0]) {
                                        echo "checked";
                                    } ?>><br>
                            </td>
                            <?php $count++;
                            } ?>

                        </tbody>
                    </table>
                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-flat btn-block">Update Changes</button>
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