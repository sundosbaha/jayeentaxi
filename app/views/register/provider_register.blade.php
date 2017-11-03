@extends('layout')

@section('content')

    <div class="box box-primary">

        <?php
        //print_r($ranga);
       // print_r($errors->all());
        ?>
<!--
        <p style="color:red;">
           {{-- @foreach ($errors->all() as $error)
                <?php echo $error; ?>
                <br/>
            @endforeach--}}
        </p>
        -->

        <!-- form start -->
        <form method="post" id="main_form" action="{{ URL::Route('providerRegisterSave') }}"
              enctype="multipart/form-data">

            <div class="box-body">
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading"><label><h3>{{ trans('customize.personal'),' ',trans('customize.details') }}</h3></label></div>
                        <div class="panel-body">


                <div class="form-group">
                    <label>{{ trans('customize.first'),' ',trans('customize.name') }}</label>
                    <input type="text" class="form-control" id="first_name" name="first_name"
                           value="<?php echo Input::old('first_name');?>" placeholder="{{ trans('customize.first'),' ',trans('customize.name') }}">
                    @if ($errors->has('first_name'))<p
                            style="color:red;"><?php echo $errors->first('first_name'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>{{ trans('customize.last'),' ',trans('customize.name') }}</label>
                    <input type="text" class="form-control" id="last_name" name="last_name"
                           value="<?php echo Input::old('last_name');?>" placeholder="{{ trans('customize.last'),' ',trans('customize.name') }}">
                    @if ($errors->has('last_name'))<p
                            style="color:red;"><?php echo $errors->first('last_name'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>{{ trans('customize.email')}}</label>
                    <input type="text" class="form-control" id="email" name="email"
                           value="<?php echo Input::old('email');?>" placeholder="{{ trans('customize.email')}}">
                    @if ($errors->has('email'))<p style="color:red;"><?php echo $errors->first('email'); ?></p>@endif
                </div>


                            <div class="form-group">
                                <label>{{ trans('customize.bio')}}</label>
                                <input type="textarea" class="form-control" id="bio" name="bio"
                                       value="<?php echo Input::old('bio');?>" placeholder="Bio">
                                @if ($errors->has('bio'))<p style="color:red;"><?php echo $errors->first('bio'); ?></p>@endif
                            </div>

                        </div>
                        {{--<label><h1>Personal Details</h1></label>--}}
                    </div>
                </div>

                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading"><label><h3>{{ trans('customize.identity')}}</h3></label></div>
                        <div class="panel-body">
                <div class="form-group">
                    <label>{{ trans('customize.profile'),' ',trans('customize.image') }}</label>
                    <input type="file"  id="image" name="image">
                    <!--class="form-control"-->
                    <p class="help-block">{{ trans('customize.upload_image_format') }}</p>
                    @if ($errors->has('image'))<p style="color:red;"><?php echo $errors->first('image'); ?></p>@endif
                </div>
                            </div>
                        </div>
                    </div>


                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading"><label><h3>{{ trans('customize.address'),' ',trans('customize.details') }}</h3></label></div>
                        <div class="panel-body">

                <div class="form-group">
                    <label>{{ trans('customize.address')}}</label>
                    <input type="textarea" class="form-control" id="address" name="address"
                           value="<?php echo Input::old('address');?>" placeholder="{{ trans('customize.address')}}">
                    @if ($errors->has('address'))<p
                            style="color:red;"><?php echo $errors->first('address'); ?></p>@endif
                </div>

                            <!--  country -->
                 <div class="form-group">
                                <label>{{ trans('customize.country') }}</label>
                                <?php $country = Input::old('country'); ?>
                                <select class="form-control" id="country" name="country">
                                    <option value="">{{ trans('customize.country') }}</option>
                                    <?php foreach ($countryCodes as $key=>$countryName): ?>
                                    <option value="<?php echo $countryName->name; ?>" <?php
                                            if (isset($country) && Input::old('country') == $countryName->name) {
                                                echo 'selected="selected"';
                                            }
                                            ?>><?php echo $countryName->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                @if ($errors->has('country'))<p style="color:red;"><?php echo $errors->first('country'); ?></p>@endif
                            </div>

                            <!--  state -->

                            <div class="form-group">
                                <!--<label>State/Province/Region</label>-->
                                <label>{{ trans('customize.state') }}</label>
                                <input type="text" class="form-control" id="state" name="state"
                                       value="<?php echo Input::old('state');?>" placeholder="{{ trans('customize.state') }}">
                                @if ($errors->has('state'))<p
                                        style="color:red;"><?php echo $errors->first('state'); ?></p>@endif
                            </div>


                            <div class="form-group">
                                <label>{{ trans('customize.city') }}</label>
                                <input type="text" class="form-control" id="city" name="city"
                                       value="<?php echo Input::old('city');?>" placeholder="{{ trans('customize.city') }}">
                                @if ($errors->has('city'))<p
                                        style="color:red;"><?php echo $errors->first('city'); ?></p>@endif
                            </div>


                <div class="form-group">
                    <label>{{ trans('customize.zip_Code') }}</label>
                    <input type="text" class="form-control" id="zipcode" name="zipcode"
                           value="<?php echo Input::old('zipcode');?>" placeholder="{{ trans('customize.zip_Code') }}">
                    @if ($errors->has('zipcode'))<p
                            style="color:red;"><?php echo $errors->first('zipcode'); ?></p>@endif
                </div>
                            <div class="form-group">
                                <label>{{trans('customize.phone'),' ',trans('customize.number') }}</label>
                                <?php $country_code = Input::old('country_code'); ?>
                                <select class="form-control" id="country_code" name="country_code">
                                    <option value=""> {{ trans('customize.country_code') }}</option>
                                    <?php foreach ($countryCodes as $key=>$countryCode): ?>
                                    <option value="<?php echo $countryCode->phonecode;?>" <?php
                                            if (isset($country_code) && Input::old('country_code') == $countryCode->phonecode) {
                                                echo 'selected="selected"';
                                            }
                                            ?>><?php echo '+'.$countryCode->phonecode."-".$countryCode->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                @if ($errors->has('country_code'))<p
                                        style="color:red;"><?php echo $errors->first('country_code'); ?></p>@endif
                                <input type="text" class="form-control" id="phone" name="phone"
                                       value="<?php echo Input::old('phone');?>" placeholder="{{trans('customize.phone'),' ',trans('customize.number') }}">
                                @if ($errors->has('phone'))<p style="color:red;"><?php echo $errors->first('phone'); ?></p>@endif
                            </div>

                </div>
                        </div>
                </div>


                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading"><label><h3>{{ trans('customize.vehicle'), ' ' ,trans('customize.documents') }}</h3></label></div>
                        <div class="panel-body">


                        <div class="form-group">
                    <label>{{ trans('customize.car'), ' ' ,trans('customize.number')  }}</label>
                    <input class="form-control" type="text" id="car_number" name="car_number"
                           value="<?php echo Input::old('car_number');?>" placeholder="{{ trans('customize.car'), ' ' ,trans('customize.number')  }}">
                    @if ($errors->has('car_number'))<p
                            style="color:red;"><?php echo $errors->first('car_number'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>{{ trans('customize.car'), ' ' ,trans('customize.model')  }}</label>
                    <input class="form-control" type="text" id="car_model" name="car_model"
                           value="<?php echo Input::old('car_model');?>" placeholder="{{ trans('customize.car'), ' ' ,trans('customize.model')  }}">
                    @if ($errors->has('car_model'))<p
                            style="color:red;"><?php echo $errors->first('car_model'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>{{ trans('customize.type') }}</label>
                    <?php $type = Input::old('type');



                    ?>

                    <select class="form-control" id="type" name="type">
                        <?php foreach ($walkerTypes as $key=>$Walker_type): ?>
                        <option value="<?php echo $Walker_type->id; ?>" <?php
                                if (isset($type) && $walker->type == $Walker_type->id) {
                                    echo 'selected="selected"';
                                }
                                ?>><?php echo $Walker_type->name ?></option>
                        <?php endforeach; ?>
                    </select>
                    @if ($errors->has('type'))<p style="color:red;"><?php echo $errors->first('type'); ?></p>@endif
                </div>
                            </div>
                        </div>
                    </div>



                <div class="panel-group">
                    <div class="panel panel-default">
<!--                        <div class="panel-heading"><label><h3>Vehicle Information</h3></label></div>-->
                        <div class="panel-body">

                <div class="form-group">
                    <label>{{ trans('customize.password') }}</label>
                    <input type="password" class="form-control" id="password" name="password"
                           value="<?php echo Input::old('password');?>" placeholder="{{ trans('customize.password') }}">
                    @if ($errors->has('password'))<p
                            style="color:red;"><?php echo $errors->first('password'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>{{ trans('customize.confirm'),' ',trans('customize.password') }}</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                           value="<?php echo Input::old('confirm_password');?>" placeholder="{{ trans('customize.confirm'),' ',trans('customize.password') }}">
                    @if ($errors->has('confirm_password'))<p
                            style="color:red;"><?php echo $errors->first('confirm_password'); ?></p>@endif
                </div>


                            </div>
                        </div>


            </div><!-- /.box-body -->


                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading"><label><h3>{{ trans('customize.Provider'),' ',trans('customize.documents') }}</h3></label></div>
                        <div class="panel-body">

                            <div class="form-group">

<!--
                                <div class="form-group">
                                    <label>Driver License</label>
                                    <input type='file'  name="license_img" id="license_img" />
                                    <p class="help-block">Please License in jpg, png format.</p>
   {{--                                 @if ($errors->has('license_img'))<p style="color:red;"><?php echo $errors->first('license_img'); ?></p>@endif--}}
                                </div>
-->
                                <div class="form-group">
                                    <label> {{ trans('customize.Provider'),' ',trans('customize.license') }}</label>
                                    <input type='file'  name="license_img" id="license_img"  />
                                    <p class="help-block">{{ trans('customize.upload_image_format') }}</p>
                                    @if ($errors->has('license_img'))<p style="color:red;"><?php echo $errors->first('license_img'); ?></p>@endif
                                </div>




                            <div class="form-group">
                                <label>{{ trans('customize.license'),' ',trans('customize.expiry'),' ',trans('customize.date') }}</label>

                                <input type="text" class="form-control" style="overflow:hidden;" id="license_expiry_date" name="license_expiry_date"  value="<?php echo Input::old('license_expiry_date');?>" placeholder="{{ trans('customize.license'),' ',trans('customize.expiry'),' ',trans('customize.date') }}">

                                @if ($errors->has('license_expiry_date'))<p
                                        style="color:red;"><?php echo $errors->first('expiry_date)'); ?></p>@endif
                            </div>



                            </div>
                        </div>
                    </div>
                    </div>



                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading"><label><h3>{{ trans('customize.vehicle'), ' ' ,trans('customize.documents') }}</h3></label></div>
                        <div class="panel-body">

                            <div class="form-group">
                                <label>{{ trans('customize.certification_of_registration') }}</label>
                                <input type='file'  name="certificate_img" id="certificate_img" />
                                <p class="help-block">{{ trans('customize.upload_image_format') }}</p>
                                @if ($errors->has('certificate_reg_img'))<p style="color:red;"><?php echo $errors->first('certificate_reg_img'); ?></p>@endif
                            </div>


                            <div class="form-group">
                                <label>{{ trans('customize.certificate'),' ',trans('customize.expiry'),' ',trans('customize.date') }}</label>

                                <input type="text" class="form-control" style="overflow:hidden;" id="certificate_expiry_date" name="certificate_expiry_date" value="<?php echo Input::old('certificate_expiry_date');?>" placeholder="certificate Expiry Date" >

                                @if ($errors->has('certificate_expiry_date'))<p
                                        style="color:red;"><?php echo $errors->first('certificate_expiry_date)'); ?></p>@endif
                            </div>

                            <div class="form-group">
                                <label>{{ trans('customize.insurance') }}</label>
                                <input type='file'  name="insurance_img" id="insurance_img" />
                                <p class="help-block">{{ trans('customize.upload_image_format') }}</p>
                                @if ($errors->has('insurance_img'))<p style="color:red;"><?php echo $errors->first('insurance_img'); ?></p>@endif
                            </div>


                            <div class="form-group">
                                <label>{{ trans('customize.insurance'),' ',trans('customize.expiry'),' ',trans('customize.date') }}</label>

                                <input type="text" class="form-control" style="overflow:hidden;" id="insurance_expiry_date" name="insurance_expiry_date" value="<?php echo Input::old('insurance_expiry_date');?>" placeholder="{{ trans('customize.insurance'),' ',trans('customize.expiry'),' ',trans('customize.date') }}">

                                @if ($errors->has('insurance_expiry_date'))<p
                                        style="color:red;"><?php echo $errors->first('insurance_expiry_date)'); ?></p>@endif
                            </div>
                            <div class="form-group">
                                <label>{{ trans('inspection') }}</label>
                                <input type='file'  name="inspection_img" id="inspection_img" />
                                <p class="help-block">{{ trans('customize.upload_image_format') }}</p>
                                @if ($errors->has('inspection_img'))<p style="color:red;"><?php echo $errors->first('inspection_img'); ?></p>@endif
                            </div>


                            <div class="form-group">
                                <label>{{ trans('customize.inspection'),' ',trans('customize.expiry'),' ',trans('customize.date') }}</label>

                                <input type="text" class="form-control" style="overflow:hidden;" id="inspection_expiry_date" name="inspection_expiry_date" value="<?php echo Input::old('inspection_expiry_date');?>" placeholder="{{ trans('customize.inspection'),' ',trans('customize.expiry'),' ',trans('customize.date') }}">

                                @if ($errors->has('inspection_expiry_date'))<p
                                        style="color:red;"><?php echo $errors->first('inspection_expiry_date)'); ?></p>@endif
                            </div>

                        </div>
                    </div>
                </div>






                <div class="box-footer">
                <button type="submit" id="edit" class="btn btn-primary btn-flat btn-block">{{ trans('customize.save') }}</button>
            </div>
        </form>
    </div>


    <script>
        var cer_req="";

        $(function () {
            $("#license_expiry_date").datepicker({
                defaultDate: "",
                changeMonth: true,
                numberOfMonths: 1,
            });
            $("#certificate_expiry_date").datepicker({
                defaultDate: "",
                changeMonth: true,
                numberOfMonths: 1,
            });
            $("#insurance_expiry_date").datepicker({
                defaultDate: "",
                changeMonth: true,
                numberOfMonths: 1,
            });$("#inspection_expiry_date").datepicker({
                defaultDate: "",
                changeMonth: true,
                numberOfMonths: 1,
            });


        });
        $(document).ready(function () {

            $("#certificate_img").change(function(){
                var cer_img=$("#certificate_img").val();
                cer_req=((cer_img.length)>0) ? true : false;
                if(cer_req == true){
                $("#certificate_expiry_date").prop('required',true)
            }
            else{
                $('#certificate_expiry_date').removeAttr('required');
            }

            });

            $("#insurance_img").change(function(){
                var insur_img=$("#insurance_img").val();
                var insur_req=((insur_img.length)>0) ? true : false;
                if(insur_req == true){
                    $("#insurance_expiry_date").prop('required',true)
                }
                else{
                    $('#insurance_expiry_date').removeAttr('required');
                }

            });
            $("#inspection_img").change(function(){
                var insp_img=$("#inspection_img").val();
                var insp_req=((insp_img.length)>0) ? true : false;
                if(insp_req == true){
                    $("#inspection_expiry_date").prop('required',true)
                }
                else{
                    $('#inspection_expiry_date').removeAttr('required');
                }

            });
            $('#main_form').validate({ // initialize the plugin
                rules: {
                    first_name: "required",
                    last_name:"required",
                    email:"required",
                    bio:"required",
                    address:"required",
                    country:"required",
                    state:"required",
                    city:"required",
                    zipcode:"required",
                    //country_code:"required",
                    phone:"required",
                    car_number:"required",
                    car_model:"required",
                    type:"required",
                    password:"required",
                    confirm_password: {
                        equalTo: "#password"
                    },
                    license_img:"required",
                    license_expiry_date:"required",

            }





            });

        });

    </script>

<style>


    .panel-default > .panel-heading {
        color: #333;
        background-color: #d5d5d5 !important;
        /*#f5f5f5*/
        border-color: #ddd;

    }



    input[type="file"]
    {
        display: block;
        font-weight: bold;
        color: #544B4B ;
    }

</style>
@stop

