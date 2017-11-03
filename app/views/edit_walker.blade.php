@extends('layout')

@section('content')

    <?php $counter = 1; ?>
    <div class="box box-primary" dir="{{ trans('language_changer.text_format') }}">
        <div class="box-header"></div><!-- /.box-header -->
        <!-- form start -->
        <form method="post" id="main-form" action="{{ URL::Route('AdminProviderUpdate') }}"  enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $walker->id ?>">

            <div class="box-body">
                <div class="form-group">
                    <label>{{ trans('language_changer.first'),' ',trans('language_changer.name') }}
                    </label>

                    <input type="text"  class="form-control" name="first_name" value="<?= $walker->first_name ?>" placeholder="{{ trans('language_changer.first'),' ',trans('language_changer.name') }}" _disabled>
                </div>

                <div class="form-group">
                    <label>{{ trans('language_changer.last'),' ',trans('language_changer.name') }}</label>
                    <input class="form-control" type="text" name="last_name" value="<?= $walker->last_name ?>" placeholder="{{ trans('language_changer.last'),' ',trans('language_changer.name') }}" _disabled>
                </div>

                <div class="form-group">
                    <label>{{ trans('language_changer.email')}}</label>
                    <input class="form-control" type="email" name="email" value="<?php echo $walker->email ?>" placeholder="{{ trans('language_changer.email')}}" readonly >
                </div>

                <div class="form-group">
                    <label>{{ trans('language_changer.phone') }}</label>
                    <input class="form-control" type="text" name="phone" value="<?php echo $walker->phone ?>" placeholder="{{ trans('language_changer.phone') }}" readonly>
                </div>

                <div class="form-group">
                    <label>{{ trans('language_changer.bio')}}</label>
                    <input class="form-control" type="text" name="bio" value="<?= $walker->bio ?>" placeholder="{{ trans('language_changer.bio')}}" _disabled>
                </div>


                <div class="form-group">
                    <label>{{ trans('language_changer.address')}}</label>
                    <input class="form-control" type="text" name="address" value="<?= $walker->address ?>" placeholder="{{ trans('language_changer.address')}}" _disabled>
                </div>


                <div class="form-group">
                    <label>{{ trans('language_changer.state') }}</label>
                    <input class="form-control" type="text" name="state" value="<?= $walker->state ?>" placeholder="{{ trans('language_changer.state') }}" _disabled>
                </div>


                <div class="form-group">
                    <label>{{ trans('language_changer.country') }}</label>
                    <input class="form-control" type="text" name="country" value="<?= $walker->country ?>" placeholder="{{ trans('language_changer.country') }}" _disabled>
                </div>

                <div class="form-group">
                    <label>{{ trans('language_changer.zip_Code') }}</label>
                    <input class="form-control" type="text" name="zipcode" value="<?= $walker->zipcode ?>" placeholder="{{ trans('language_changer.zip_Code') }}" _disabled>
                </div>

                <div class="form-group">
                    <label>{{ trans('language_changer.car'), ' ' ,trans('language_changer.number')  }}</label>
                    <input class="form-control" type="text" name="car_number" value="<?= $walker->car_number ?>" placeholder="{{ trans('language_changer.car'), ' ' ,trans('language_changer.number')  }}" _disabled>
                </div>

                <div class="form-group">
                    <label>{{ trans('language_changer.car'), ' ' ,trans('language_changer.model')  }}</label>
                    <input class="form-control" type="text" name="car_model" value="<?= $walker->car_model ?>" placeholder="{{ trans('language_changer.car'), ' ' ,trans('language_changer.model')  }}" _disabled>
                </div>


                <div class="form-group">
                    <label>{{ trans('language_changer.profile'),' ',trans('language_changer.image') }}</label>
                    <input class="form-control" type="file" name="pic" _disabled>
                    <br>
                    <img src="<?= $walker->picture; ?>" height="50" width="50"><br>
                    <p class="help-block">{{ trans('language_changer.image_format') }}</p>
                </div>
                <div class="form-group">
                    <label>{{ trans('language_changer.is_currently_providing') }} </label>
                    <?php
                    $walk = DB::table('walk')
                            ->select('id')
                            ->where('walk.is_started', 1)
                            ->where('walk.is_completed', 0)
                            ->where('walker_id', $walker->id);
                    $count = $walk->count();
                    if ($count > 0) {
                        echo trans('language_changer.yes');
                    } else {
                        echo trans('language_changer.no');
                    }
                    ?>
                </div>
                <div class="form-group">
                    <label>{{ trans('language_changer.is_provider_active') }}  : </label>
                    <?php
                    $walk = DB::table('walker')
                            ->select('id')
                            ->where('walker.is_active', 1)
                            ->where('walker.id', $walker->id);
                    $count = $walk->count();
                    if ($count > 0) {
                        echo trans('language_changer.yes');
                    } else {
                        echo trans('language_changer.no');
                    }
                    ?>
                </div>
                <div class="form-group">
                    <label>{{ trans('language_changer.service'), ' ', trans('language_changer.type') }}</label>
                    <table class="table table-bordered">
                        <tbody><tr>
                            <th>{{ trans('language_changer.service'), ' ', trans('language_changer.type') }}</th>
                            <th>{{ trans('language_changer.base_price') }}</th>
                            <th>{{ trans('language_changer.price_per_unit_distance') }}</th>
                            <th>{{ trans('language_changer.price_per_unit_time') }}</th>
                        </tr>
                        @foreach($type as $types)

                            <tr>
                                <td id="col2">
                                    <?php
                                    $ar = array();
                                    foreach ($ps as $pss) {
                                        $ser = ProviderType::where('id', $pss->type)->first();
                                        if ($ser)
                                            $ar[] = $ser->name;
                                    }
                                    $servname = $types->name;
                                    ?>
                                    <input class="form-control" name="service[]" type="radio" value="{{$types->id}}" <?php
                                            if (!empty($ar)) {
                                                if (in_array($servname, $ar))
                                                    echo "checked='checked'";
                                            }
                                            ?>>{{$types->name}}<br>
                                </td>
                                <td>
                                    <?php $counter++; ?>
                                    <span id="no_amount_error<?php echo $counter; ?>" style="display: none"></span>
                                    <input class="form-control" name="service_base_price[{{$types->id}}]" type="text" onkeypress="return Isamount(event,<?php echo $counter; ?>);" value="<?php
                                    $proviserv = ProviderServices::where('provider_id', $walker->id)->where('type', $types->id)->first();
                                    if (empty($proviserv)) {
                                        echo "";
                                    } else {
                                        echo sprintf2($proviserv->base_price, 2);
                                    }
                                    ?>" placeholder="{{ trans('language_changer.base_price') }}" ><br>
                                </td>
                                <td>
                                    <?php $counter++; ?>
                                    <span id="no_amount_error<?php echo $counter; ?>" style="display: none"></span>
                                    <input class="form-control" name="service_price_distance[{{$types->id}}]" type="text" onkeypress="return Isamount(event,<?php echo $counter; ?>);" value="<?php
                                    $proviserv = ProviderServices::where('provider_id', $walker->id)->where('type', $types->id)->first();
                                    if (empty($proviserv)) {
                                        echo "";
                                    } else {
                                        echo sprintf2($proviserv->price_per_unit_distance, 2);
                                    }
                                    ?>" placeholder="{{ trans('language_changer.price_per_unit_distance') }}" ><br>
                                </td>
                                <td>
                                    <?php $counter++; ?>
                                    <span id="no_amount_error<?php echo $counter; ?>" style="display: none"></span>
                                    <input class="form-control" name="service_price_time[{{$types->id}}]" type="text" onkeypress="return Isamount(event,<?php echo $counter; ?>);" value="<?php
                                    $proviserv = ProviderServices::where('provider_id', $walker->id)->where('type', $types->id)->first();
                                    if (empty($proviserv)) {
                                        echo "";
                                    } else {
                                        echo sprintf2($proviserv->price_per_unit_time, 2);
                                    }
                                    ?>" placeholder="{{ trans('language_changer.price_per_unit_time') }}" ><br>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>


            </div><!-- /.box-body -->

            <div class="box-footer">

                <button type="submit" class="btn btn-primary btn-flat btn-block">{{ trans('language_changer.update'),' ', trans('language_changer.change')  }}</button>
            </div>
        </form>
    </div>



    <?php if ($success == 1) { ?>
    <script type="text/javascript">
        var msg={{ trans('language_changer.walker'),' ',trans('language_changer.profile'),' ',trans('language_changer.update'),' ',trans('language_changer.successfully')  }}
        alert(msg);
    </script>
    <?php } ?>
    <?php if ($success == 2) { ?>
    <script type="text/javascript">
        alert('Sorry Something went Wrong');
    </script>
    <?php } ?>

    <script type="text/javascript">
        $("#main-form").validate({
            rules: {
                first_name: "required",
                last_name: "required",
                country: "required",
                email: {
                    required: true,
                    email: true
                },
                state: "required",
                address: "required",
                bio: "required",
                zipcode: {
                    required: true,
                    digits: true,
                },
                phone: {
                    required: true,
                    digits: true,
                }


            }
        });
    </script>




@stop