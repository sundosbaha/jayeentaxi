@extends('layout')
@section('content')


    <?php


    $license_img="";
    $license_expiry_date="";
    $certificate_img="";
    $certificate_expiry_date="";
    $insurance_img="";
    $insurance_expiry_date="";
    $inspection_img="";
    $license_expiry_date="";


    foreach($walk_document as $wd){
        if($wd->comment_key==1){
            $license_img=$wd->image;
            $license_expiry_date=$wd->expiry_date;
        }if($wd->comment_key==2){
            $certificate_img=$wd->image;
            $certificate_expiry_date=$wd->expiry_date;
        }if($wd->comment_key==3){
            $insurance_img=$wd->image;
            $insurance_expiry_date=$wd->expiry_date;
        }if($wd->comment_key==4){
            $inspection_img=$wd->image;
            $license_expiry_date=$wd->expiry_date;
        }

    }

    ?>



    <?php $counter = 1; ?>
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">
                <?= $title ?>
            </h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form method="post" id="main-form" action="{{ URL::Route('AdminProviderUpdate')}}"  enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $ids ?>">
            <div class="box-body">
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <label>
                                <h3>Personal Details
                                </h3>
                            </label>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>First Name
                                </label>

                                <input type="text" class="form-control" name="first_name" value="<?= $walker->first_name ?>" placeholder="First Name" >
                            </div>
                            <div class="form-group">
                                <label>Last Name
                                </label>
                                <input class="form-control" type="text" name="last_name" value="<?= $walker->last_name ?>" placeholder="Last Name" >
                            </div>
                            <div class="form-group">
                                <label>Email
                                </label>
                                <input class="form-control" type="email" name="email" value="<?=
                                $walker->email ?>" placeholder="Email" readonly >
                            </div>
                            <div class="form-group">
                                <label>Bio
                                </label>
                                <input class="form-control" type="text" name="bio" value="<?= $walker->bio ?>" placeholder="Bio">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <label>
                                <h3>Identity
                                </h3>
                            </label>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Profile Image
                                </label>
                                <input  type="file" name="pic" >
                                <br>
                                <img src="<?= $walker->picture; ?>" height="50" width="50">
                                <br>
                                <p class="help-block">Please Upload image in jpg, png format.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <label>
                                <h3>Address Details
                                </h3>
                            </label>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Address
                                </label>
                                <input class="form-control" type="text" name="address" value="<?= $walker->address ?>" placeholder="Address" >
                            </div>
                            <div class="form-group">
                                <label>State/Province/Region
                                </label>
                                <input class="form-control" type="text" name="state" value="<?= $walker->state ?>" placeholder="State" >
                            </div>
                            <div class="form-group">
                                <label>City
                                </label>
                                <input class="form-control" type="text" name="city" value="<?= $walker->city ?>" placeholder="city" >
                            </div>
                            <div class="form-group">
                                <?php
                                $countryCodes=DB::table('country_code')
                                        ->get();
                                $walkerTypes=DB::table('walker_type')
                                        ->get();
                                ?>
                                <label>Country</label>
                                <?php $country =  $walker->country?>
                                <select class="form-control" id="country" name="country">
                                    <option value="">Country</option>
                                    <?php foreach ($countryCodes as $key=>$countryName): ?>
                                    <option value="<?php echo $countryName->name; ?>" <?php
                                            if (isset($country) && $walker->country == $countryName->name) {
                                                echo 'selected="selected"';
                                            }
                                            ?>><?php echo $countryName->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <!--<input class="form-control" type="text" name="country" value="<?//= $walker->country ?>" placeholder="Country" >-->
                            </div>
                            <div class="form-group">
                                <label>Zip Code
                                </label>
                                <input class="form-control" type="text" name="zipcode" value="<?= $walker->zipcode ?>" placeholder="Zip Code" >
                            </div>
                            <div class="form-group">
                                <label>Phone
                                </label>
                                <input class="form-control" type="text" name="phone" value="<?= $walker->phone ?>" placeholder="Phone" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <label>
                                <h3>Vehicle Documents
                                </h3>
                            </label>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Car Number
                                </label>
                                <input class="form-control" type="text" name="car_number" value="<?= $walker->car_number ?>" placeholder="Zip Code" >
                            </div>
                            <div class="form-group">
                                <label>Car Model
                                </label>
                                <input class="form-control" type="text" name="car_model" value="<?= $walker->car_model ?>" placeholder="Zip Code" >
                            </div>
                            <div class="form-group">
                                <label>Service Type
                                </label>
                                <?php $type = $walker->type; ?>
                                <select class="form-control" id="type" name="type">
                                    <option value="">Type</option>
                                    <?php foreach ($walkerTypes as $key=>$walkerType): ?>
                                    <option value="<?php echo $walkerType->name; ?>" <?php
                                            if (isset($type) && $walker->type == $walkerType->name) {
                                            echo 'selected="selected"';
                                            }
                                            ?>><?php echo $walkerType->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <label>
                                <h3>Status
                                </h3>
                            </label>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Is Currently Providing :
                                </label>
                                <?php
                                $walk = DB::table('walk')
                                ->select('id')
                                ->where('walk.is_started', 1)
                                ->where('walk.is_completed', 0)
                                ->where('walker_id', $walker->id);
                                $count = $walk->count();
                                if ($count > 0) {
                                echo "Yes";
                                } else {
                                echo "No";
                                }
                                ?>
                            </div>
                            <div class="form-group">
                                <label>Is Provider Active :
                                </label>
                                <?php
                                $walk = DB::table('walker')
                                ->select('id')
                                ->where('walker.is_active', 1)
                                ->where('walker.id', $walker->id);
                                $count = $walk->count();
                                if ($count > 0) {
                                echo "Yes";
                                } else {
                                echo "No";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <label>
                                <h3>Identity
                                </h3>
                            </label>
                        </div>
                        <div class="panel-body">
                            <?php foreach($walk_document as $wd) { ?>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>
                                            <?php echo ucfirst($wd->comment)?>
                                        </label>
                                        <?php if($wd->comment_key==1 && $wd->walker_id==$ids) { ?>
                                        <input type="file"  class="form-control" style="overflow:hidden;"  value="<?php ?>" id="license_img" name="license_img"  >
                                    </div>
                                    <div class="col-md-6">
                                        <label>Expiry Date
                                        </label>
                                        <input type="text"  class="form-control" style="overflow:hidden;"   id="license_expiry_date" name="license_expiry_date" value="<?= date("m-d-Y", strtotime($wd->expiry_date));?>" placeholder="License Expiry Date">
                                    </div>
                                </div>
                                <br>
                                <img src="<?= $wd->image; ?>" height="50" width="50">
                                <p class="help-block">Please Upload image in jpg, png format.
                                </p>
                            </div>
                            <!--</div>-->
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                    <?php }   elseif($wd->comment_key==2 && $wd->walker_id==$ids) { ?>
                                    <!-- -->
                                        <input type="file"  class="form-control" style="overflow:hidden;"   id="certificate_img" name="certificate_img">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Expiry Date
                                        </label>
                                        <input type="text"  class="form-control" style="overflow:hidden;"   id="certificate_expiry_date" name="certificate_expiry_date" value="<?= date("m-d-Y", strtotime($wd->expiry_date));?>" placeholder="Certificate Expiry Date">
                                    </div>
                                </div>
                                <br>
                                <img src="<?= $wd->image; ?>" height="50" width="50">
                                <p class="help-block">Please Upload image in jpg, png format.
                                </p>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php } elseif($wd->comment_key==3 && $wd->walker_id==$ids)  { ?>
                                        <input type="file"  class="form-control" style="overflow:hidden;"   id="insurance_img" name="insurance_img"  >
                                    </div>
                                    <div class="col-md-6">
                                        <label>Expiry Date
                                        </label>
                                        <input type="text"  class="form-control" style="overflow:hidden;"   id="insurance_expiry_date" name="insurance_expiry_date" value="<?= date("m-d-Y", strtotime($wd->expiry_date));?>" placeholder="insurance Expiry Date">
                                    </div>
                                </div>
                                <br>
                                <img src="<?= $wd->image; ?>" height="50" width="50">
                                <br>
                                <p class="help-block">Please Upload image in jpg, png format.
                                </p>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php } elseif($wd->comment_key==4 && $wd->walker_id==$ids) { ?>
                                        <input type="file"  class="form-control" style="overflow:hidden;"   id="inspection_img" name="inspection_img"  >
                                        <br>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Expiry Date
                                        </label>
                                        <input type="text"  class="form-control" style="overflow:hidden;"   id="inspection_expiry_date" name="inspection_expiry_date" value="<?= date("m-d-Y", strtotime($wd->expiry_date));?>" placeholder="inspection Expiry Date">
                                    </div>
                                </div>
                                <img src="<?= $wd->image; ?>" height="50" width="50">
                                <p class="help-block">Please Upload image in jpg, png format.
                                </p>
                                <?php } ?>
                            </div>
                            <!--  <div class="form-group">-->

                        </div>
                        <?php }  ?>
                    </div>
                </div>
                <!--</div>-->

                <div class="box-footer">
                    <button type="submit"  class="btn btn-primary btn-flat btn-block">Update Changes
                    </button>
                </div>
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
    <script type="text/javascript">
        $(function () {
                    $("#license_expiry_date").datepicker({
                                defaultDate: "",
                                changeMonth: true,
                                numberOfMonths: 1,
                            }
                    );
                    $("#certificate_expiry_date").datepicker({
                                defaultDate: "",
                                changeMonth: true,
                                numberOfMonths: 1,
                            }
                    );
                    $("#insurance_expiry_date").datepicker({
                                defaultDate: "",
                                changeMonth: true,
                                numberOfMonths: 1,
                            }
                    );
                    $("#inspection_expiry_date").datepicker({
                                defaultDate: "",
                                changeMonth: true,
                                numberOfMonths: 1,
                            }
                    );
                }
        );
        $("#main-form").validate({
                    rules: {
                        first_name: "required",
                        last_name: "required",
                        country: "required",
                        email: {
                            required: true,
                            email: true
                        }
                        ,
                        state: "required",
                        address: "required",
                        bio: "required",
                        zipcode: {
                            required: true,
                            digits: true,
                        }
                        ,
                        phone: {
                            required: true,
                            digits: true,
                        }
                    }
                }
        );
    </script>
    <style>
        .panel-default > .panel-heading {
            color: #333;
            background-color: #ec7171 !important;
            /*#f5f5f5*/
            border-color: #ddd;
        }
    </style>
@stop
