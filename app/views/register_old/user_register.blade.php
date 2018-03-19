@extends('layout')

@section('content')

    <div class="box box-primary">

        <?php
        print_r($errors->all());
        ?>

        <p style="color:red;">
            @foreach ($errors->all() as $error)
                <?php echo $error; ?>
                <br/>
            @endforeach
        </p>


        <!-- form start -->
        <form method="post" id="main-form" action="{{ URL::Route('userRegisterSave') }}" enctype="multipart/form-data">

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
                           value="<?php echo Input::old('phone');?>" maxlength="10" placeholder="Phone Number">
                    @if ($errors->has('phone'))<p style="color:red;"><?php echo $errors->first('phone'); ?></p>@endif
                </div>

                <div class="form-group">
                    <label>Profile Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <p class="help-block">Please Upload image in jpg, png format.</p>
                    @if ($errors->has('image'))<p style="color:red;"><?php echo $errors->first('image'); ?></p>@endif
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