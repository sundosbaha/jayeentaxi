@extends('layout')

@section('content')

    <?php $counter = 1; ?>
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title"><?php echo $title; ?></h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form method="post" id="main-form" action="{{ URL::Route('AddZoneDivision') }}" enctype="multipart/form-data">

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
                        <th>{{ trans('customize.visible') }}</th>
                    </tr>
                    <?php $count = 1; ?>
                    @foreach($walkerTypes as $key => $type)

                        <tr>
                            <td>
                                <input class="form-control" name="type[{{$type->id}}]" id="type[{{$type->id}}]"
                                       type="text" placeholder="Type" value="{{$type->name}}" disabled><br>
                            </td>
                            <td>
                                <?php $counter++; ?>
                                <span id="no_amount_error<?php echo $counter; ?>" style="display: none"></span>
                                <input class="form-control" name="service_base_price[{{$type->id}}]"
                                       id="service_base_price[{{$type->id}}]" type="text"
                                       onkeypress="return Isamount(event,<?php echo $counter; ?>);" value=""
                                       placeholder="Base Price"><br>
                                @if ($errors->has('service_base_price'.$count))
                                    <span class="help-block">
                                        <strong>{{ str_replace('service_base_price'.$count, 'service_base_price', $errors->first('service_base_price'.$count, '<p class="text-danger">:message</p>')) }}</strong>
                                    </span>
                                @endif
                            </td>
                            <td>
                                <?php $counter++; ?>
                                <span id="no_amount_error<?php echo $counter; ?>" style="display: none"></span>
                                <input class="form-control" name="service_price_distance[{{$type->id}}]"
                                       id="service_price_distance[{{$type->id}}]" type="text"
                                       onkeypress="return Isamount(event,<?php echo $counter; ?>);" value=""
                                       placeholder="Price per unit distance"><br>
                                @if ($errors->has('service_price_distance'.$count))
                                    <span class="help-block">
                                        <strong>{{ str_replace('service_price_distance'.$count, 'service_price_distance', $errors->first('service_price_distance'.$count, '<p class="text-danger">:message</p>')) }}</strong>
                                    </span>
                                @endif
                            </td>
                            <td>
                                <?php $counter++; ?>
                                <span id="no_amount_error<?php echo $counter; ?>" style="display: none"></span>
                                <input class="form-control" name="service_price_time[{{$type->id}}]"
                                       id="service_price_time[{{$type->id}}]" type="text"
                                       onkeypress="return Isamount(event,<?php echo $counter; ?>);" value=""
                                       placeholder="Price per unit time"><br>
                                @if ($errors->has('service_price_time'.$count))
                                    <span class="help-block">
                                        <strong>{{ str_replace('service_price_time'.$count, 'service_price_time', $errors->first('service_price_time'.$count, '<p class="text-danger">:message</p>')) }}</strong>
                                    </span>
                                @endif
                            </td>
                            <td>
                                <?php $counter++; ?>
                                <span id="no_amount_error<?php echo $counter; ?>" style="display: none"></span>
                                <input class="form-control" name="service_max_size[{{$type->id}}]"
                                       id="service_max_size[{{$type->id}}]" type="text"
                                       onkeypress="return Isamount(event,<?php echo $counter; ?>);" value=""
                                       placeholder="Max Size"><br>
                                @if ($errors->has('service_max_size'.$count))
                                    <span class="help-block">
                                            <strong>{{ str_replace('service_max_size'.$count, 'service_max_size', $errors->first('service_max_size'.$count, '<p class="text-danger">:message</p>')) }}</strong>
                                        </span>
                                @endif
                            </td>
                            <td>
                                <?php $counter++; ?>
                                <span id="no_amount_error<?php echo $counter; ?>" style="display: none"></span>
                                <input class="form-control" name="service_base_distance[{{$type->id}}]"
                                       id="service_base_distance[{{$type->id}}]" type="text"
                                       onkeypress="return Isamount(event,<?php echo $counter; ?>);" value=""
                                       placeholder="Base Distance"><br>
                                @if ($errors->has('service_base_distance'.$count))
                                    <span class="help-block">
                                            <strong>{{ str_replace('service_base_distance'.$count, 'service_base_distance', $errors->first('service_base_distance'.$count, '<p class="text-danger">:message</p>')) }}</strong>
                                        </span>
                                @endif
                            </td>
                            <td>
                                <?php $counter++; ?>
                                <span id="no_amount_error<?php echo $counter; ?>" style="display: none"></span>
                                <input class="form-control" name="service_visible[{{$type->id}}]"
                                       id="service_visible[{{$type->id}}]" type="checkbox"
                                       onkeypress="return Isamount(event,<?php echo $counter; ?>);" value="yes" checked><br>
                            </td>
                        </tr>
                        <?php $count++; ?>
                    @endforeach
                    </tbody>
                </table>
            </div>
    </div><!-- /.box-body -->

    <div class="box-footer">
        <button type="submit" class="btn btn-primary btn-flat btn-block">Update Changes</button>
    </div>
    </form>
    </div>



    <?php if ($success == 1) { ?>
    <script type="text/javascript">
        alert('Walker Profile Updated Successfully');
    </script>
    <?php } ?>
    <?php if ($success == 2) { ?>
    <script type="text/javascript">
        alert('Sorry Something went Wrong');
    </script>
    <?php } ?>




@stop