@extends('layout')

@section('content')

    <div class="box box-primary" dir="{{ trans('language_changer.text_format') }}">



        <!-- form start -->
        <form method="post" id="main-form" action="{{ URL::Route('userRegisterSave') }}" enctype="multipart/form-data">

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

                <div class="form-group">
                    <label>{{ trans('language_changer.email')}}</label>
                    <input type="text" class="form-control" id="email" name="email"
                           value="<?php echo Input::old('email');?>" placeholder="{{ trans('language_changer.email') }}">
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
                           value="<?php echo Input::old('phone');?>" maxlength="10" placeholder="{{ trans('language_changer.phone'),' ',trans('language_changer.number') }}">
                    @if ($errors->has('phone'))<p style="color:red;"><?php echo $errors->first('phone'); ?></p>@endif
                </div>


                <div class="form-group">
                    <label>{{ trans('language_changer.profile'),' ',trans('language_changer.image') }}</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <p class="help-block">{{ trans('language_changer.upload_image_format') }}</p>
                    @if ($errors->has('image'))<p style="color:red;"><?php echo $errors->first('image'); ?></p>@endif
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
    email:"required",
        image:"required",
    country_code: "required",
    phone: {
    required: true,
    number: true,
    },
        password:"required",
        confirm_password:"required",


    }
    });
    </script>

    <?php
           if(isset($success)){


    if($success == 1) { ?>
    <script type="text/javascript">
        var msg="{{ trans('language_changer.owner'),' ',trans('language_changer.profile'),' ',trans('language_changer.update'),' ',trans('language_changer.successfully') }}";
        alert(msg);
    </script>
    <?php } ?>
    <?php
    if($success == 2) { ?>
    <script type="text/javascript">
        var msg="{{ trans('language_changer.wrong') }}"
        alert(msg);
    </script>
    <?php } } ?>
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


        });



    </script>

@stop