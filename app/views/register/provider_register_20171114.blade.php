@extends('layout')

@section('content')

    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-success">{{ Session::get('message') }}</div>
    @endif

    <div class="box box-primary">

        <!-- form start -->
        <form method="post" id="main-form" action="{{ URL::Route('providerRegisterSave') }}"
              enctype="multipart/form-data">

            <div class="box-body">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name"
                           value="<?php echo Input::old('first_name');?>" placeholder="First Name">
                    @if ($errors->has('first_name'))<p
                            style="color:red;"><?php echo $errors->first('first_name'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name"
                           value="<?php echo Input::old('last_name');?>" placeholder="Last Name">
                    @if ($errors->has('last_name'))<p
                            style="color:red;"><?php echo $errors->first('last_name'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>Eamil</label>
                    <input type="text" class="form-control" id="email" name="email"
                           value="<?php echo Input::old('email');?>" placeholder="Email">
                    @if ($errors->has('email'))<p style="color:red;"><?php echo $errors->first('email'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <?php $country_code = Input::old('country_code'); ?>
                    <select class="form-control" id="country_code" name="country_code">
                        <option value=""> Country Code</option>
                        <?php foreach ($countryCodes as $key=>$countryCode): ?>
                        <option value="<?php echo $countryCode->phonecode; ?>" <?php
                                if (isset($country_code) && Input::old('country_code') == $countryCode->phonecode) {
                                    echo 'selected="selected"';
                                }
                                ?>><?php echo $countryCode->phonecode; ?></option>
                        <?php endforeach; ?>
                    </select>
                    @if ($errors->has('country_code'))<p
                            style="color:red;"><?php echo $errors->first('country_code'); ?></p>@endif
                    <input type="text" class="form-control" id="phone" name="phone"
                           value="<?php echo Input::old('phone');?>" placeholder="Phone Number">
                    @if ($errors->has('phone'))<p style="color:red;"><?php echo $errors->first('phone'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>Profile Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <p class="help-block">Please Upload image in jpg, png format.</p>
                    @if ($errors->has('image'))<p style="color:red;"><?php echo $errors->first('image'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input type="textarea" class="form-control" id="address" name="address"
                           value="<?php echo Input::old('address');?>" placeholder="Address">
                    @if ($errors->has('address'))<p
                            style="color:red;"><?php echo $errors->first('address'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>Bio</label>
                    <input type="textarea" class="form-control" id="bio" name="bio"
                           value="<?php echo Input::old('bio');?>" placeholder="Bio">
                    @if ($errors->has('bio'))<p style="color:red;"><?php echo $errors->first('bio'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>Zip Code</label>
                    <input type="text" class="form-control" id="zipcode" name="zipcode"
                           value="<?php echo Input::old('zipcode');?>" placeholder="Zip Code">
                    @if ($errors->has('zipcode'))<p
                            style="color:red;"><?php echo $errors->first('zipcode'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>Car Number</label>
                    <input class="form-control" type="text" id="car_number" name="car_number"
                           value="<?php echo Input::old('car_number');?>" placeholder="Car Number">
                    @if ($errors->has('car_number'))<p
                            style="color:red;"><?php echo $errors->first('car_number'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>Car Model</label>
                    <input class="form-control" type="text" id="car_model" name="car_model"
                           value="<?php echo Input::old('car_model');?>" placeholder="Car Model">
                    @if ($errors->has('car_model'))<p
                            style="color:red;"><?php echo $errors->first('car_model'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>Types</label>
                    <?php $type = Input::old('type'); ?>
                    <select class="form-control" id="type" name="type">
                        <option value="">Type</option>
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
                    <label>Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                           value="<?php echo Input::old('password');?>" placeholder="Password">
                    @if ($errors->has('password'))<p
                            style="color:red;"><?php echo $errors->first('password'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                           value="<?php echo Input::old('confirm_password');?>" placeholder="Confirm Password">
                    @if ($errors->has('confirm_password'))<p
                            style="color:red;"><?php echo $errors->first('confirm_password'); ?></p>@endif
                </div>


            </div><!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" id="edit" class="btn btn-primary btn-flat btn-block">Save</button>
            </div>
        </form>
    </div>

@stop