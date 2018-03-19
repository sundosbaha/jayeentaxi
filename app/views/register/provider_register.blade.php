@extends('layout')

@section('content')

    <!-- will be used to show any messages -->
    @if(Session::has('msg'))
        <div class="alert alert-success"><b><?php
                echo Session::get('msg');
                Session::put('msg', NULL);
                ?></b></div>
    @endif

    <div class="box box-primary" dir="{{ trans('language_changer.text_format') }}">

        <!-- form start -->
        <form method="post" id="main-form" action="{{ URL::Route('providerRegisterSave') }}" name="main-form"
             enctype="multipart/form-data">

            <div class="box-body">
                <div class="form-group">
                    <label>{{ trans('language_changer.first'),' ',trans('language_changer.name') }}</label>
                    <input type="text" class="form-control" id="first_name" name="first_name"
                           value="<?php echo Input::old('first_name');?>" placeholder="{{ trans('language_changer.first'),' ',trans('language_changer.name') }}">
                    @if ($errors->has('first_name'))<p
                            style="color:red;"><?php echo $errors->first('first_name'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>{{ trans('language_changer.last'),' ',trans('language_changer.name') }}</label>
                    <input type="text" class="form-control" id="last_name" name="last_name"
                           value="<?php echo Input::old('last_name');?>" placeholder="{{ trans('language_changer.last'),' ',trans('language_changer.name') }}">
                    @if ($errors->has('last_name'))<p
                            style="color:red;"><?php echo $errors->first('last_name'); ?></p>@endif
                </div>

    <!--            {{--<div class="form-group">
                    <label>Gender</label>
                    <?php $gender = Input::old('gender'); ?>
                    <select class="form-control" id="gender" name="gender">
                        <option value="">Gender</option>

                        <option value="m"
                        <?php
                               if($gender =='m'){
                                   echo "Selected=Selected";
                               }
                                ?>
                        >Male</option>

                        <option value="f"

                                <?php
                                if($gender =='f'){
                                echo "Selected=Selected";
                        }
?>

                        >Female</option>
                    </select>
                    @if ($errors->has('gender'))<p style="color:red;"><?php echo $errors->first('gender'); ?></p>@endif
                </div>
--}}

-->
                <div class="form-group">
                    <label>{{ trans('language_changer.email')}}</label>
                    <input type="text" class="form-control" id="email" name="email"
                           value="<?php echo Input::old('email');?>" placeholder="{{ trans('language_changer.email')}}">
                    @if ($errors->has('email'))<p style="color:red;"><?php echo $errors->first('email'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>{{ trans('language_changer.phone'),' ',trans('language_changer.number') }}</label>
                    <?php $country_code = Input::old('country_code'); ?>
                    <select class="form-control" id="country_code" name="country_code">
                        <option value=""> {{ trans('language_changer.country_code') }}</option>
                        <?php foreach ($countryCodes as $key=>$countryName): ?>
                        <option value="<?php echo $countryName->phonecode; ?>" <?php
                                if (isset($country_code) && Input::old('country_code') == $countryName->phonecode) {
                                    echo 'selected="selected"';
                                }
                                ?>><?php echo '+'.$countryName->phonecode.' / '.$countryName->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    @if ($errors->has('country_code'))<p
                            style="color:red;"><?php echo $errors->first('country_code'); ?></p>@endif
                    <input type="text" class="form-control" id="phone" name="phone"
                           value="<?php echo Input::old('phone');?>" placeholder="{{ trans('language_changer.phone'),' ',trans('language_changer.number') }}">
                    @if ($errors->has('phone'))<p style="color:red;"><?php echo $errors->first('phone'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>{{ trans('language_changer.address')}}</label>
                    <input type="textarea" class="form-control" id="address" name="address"
                           value="<?php echo Input::old('address');?>" placeholder="{{ trans('language_changer.address') }}">
                    @if ($errors->has('address'))<p
                            style="color:red;"><?php echo $errors->first('address'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>{{ trans('language_changer.bio')}}</label>
                    <input type="textarea" class="form-control" id="bio" name="bio"
                           value="<?php echo Input::old('bio');?>" placeholder="{{ trans('language_changer.bio') }}">
                    @if ($errors->has('bio'))<p style="color:red;"><?php echo $errors->first('bio'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>{{ trans('language_changer.zip_Code')}}</label>
                    <input type="text" class="form-control" id="zipcode" name="zipcode"
                           value="<?php echo Input::old('zipcode');?>" placeholder="{{ trans('language_changer.zip_Code') }}">
                    @if ($errors->has('zipcode'))<p
                            style="color:red;"><?php echo $errors->first('zipcode'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>{{ trans('language_changer.car'),' ',trans('language_changer.number') }}</label>
                    <input class="form-control" type="text" id="car_number" name="car_number"
                           value="<?php echo Input::old('car_number');?>" placeholder="{{ trans('language_changer.car'),' ',trans('language_changer.number') }}">
                    @if ($errors->has('car_number'))<p
                            style="color:red;"><?php echo $errors->first('car_number'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>{{ trans('language_changer.car'),' ',trans('language_changer.model') }}</label>
                    <input class="form-control" type="text" id="car_model" name="car_model"
                           value="<?php echo Input::old('car_model');?>" placeholder="{{ trans('language_changer.car'),' ',trans('language_changer.model') }}">
                    @if ($errors->has('car_model'))<p
                            style="color:red;"><?php echo $errors->first('car_model'); ?></p>@endif
                </div>


                <div class="form-group">
                    <label>{{ trans('language_changer.Types') }}</label>
                    <?php $type = Input::old('type'); ?>
                    <select class="form-control" id="type" name="type">
                        <option value="">{{ trans('language_changer.Types')}}</option>
                        <?php foreach ($walkerTypes as $key=>$walkerType): ?>
                        <option value="<?php echo $walkerType->id; ?>" <?php
                                if (isset($type) && Input::old('type') == $walkerType->id) {
                                    echo 'selected="selected"';
                                }
                                ?>><?php echo $walkerType->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    @if ($errors->has('type'))<p style="color:red;"><?php echo $errors->first('type'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>{{ trans('language_changer.password') }}</label>
                    <input type="password" class="form-control" id="password" name="password"
                           value="" placeholder="{{ trans('language_changer.password') }}">
                    @if ($errors->has('password'))<p
                            style="color:red;"><?php echo $errors->first('password'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>{{ trans('language_changer.confirm'),' ',trans('language_changer.password') }}</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                           value="" placeholder="{{ trans('language_changer.confirm'),' ',trans('language_changer.password') }}">
                    @if ($errors->has('confirm_password'))<p
                            style="color:red;"><?php echo $errors->first('confirm_password'); ?></p>@endif
                </div>


                <div class="form-group">
                    <label>{{ trans('language_changer.profile'),' ',trans('language_changer.image') }}</label>
                    <input class="form-control" type="file" name="image" id="image" >
                    @if ($errors->has('image'))<p
                            style="color:red;"><?php echo $errors->first('image'); ?></p>@endif
                </div>


            </div><!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" id="edit" class="btn btn-primary btn-flat btn-block">{{ trans('language_changer.save') }}</button>
            </div>
        </form>
    </div>


    <script type="text/javascript">
        $("#main-form").validate({
            rules: {
                first_name: "required",
                last_name: "required",
               // gender: "required",

                country_code: "required",
                email: {
                    required: true,
                    email: true
                },
                state: "required",
                address: "required",
                bio: "required",
                type:"required",
                car_number:"required",
                car_model:"required",
                password:"required",
                image:"required",
                confirm_password:"required",
                zipcode: {
                    required: true,
                    number: true,
                },
                phone: {
                    required: true,
                    digits: true,
                }


            }
        });

    </script>


    <script>


        $('#edit').click(function(){

            var password=$("#password").val();
            var confirm_password=$("#confirm_password").val();
            if(password !='' && confirm_password !=''){
                if(password != confirm_password){
                    $("#password").val('');
                    $("#confirm_password").val('');

                    alert("Password and Confirm password mismatch");
                    return false;
                }
            }else{
                alert("Don\'t Leave any field empty");
                return false;
            }


 /*           var gender=$("#gender").val();
            var type=$("#type").val();

if(gender !='' && type !='' ){
    if((type == 3) && (gender != 'f')){
        alert("Cannot Create Male driver for this Type");
        return false;
    }
}else{
    alert("Don\'t Leave any field empty");
    return false;
}*/



        });
    </script>

@stop

