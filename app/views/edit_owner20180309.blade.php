
@extends('layout')

@section('content')

    <?php
/*echo "<pre>";
    print_r($owner);
            die("x");*/

    ?>


<div class="box box-primary" dir="{{ trans('language_changer.text_format') }}">
              
                                <!-- form start -->
                               <form method="post" id="main-form" action="{{ URL::Route('AdminUserUpdate') }}"  enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $owner->id ?>">

                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>{{ trans('language_changer.first'),' ',(trans('language_changer.name'))}}</label>
                                            <input type="text" class="form-control" name="first_name" value="<?= $owner->first_name ?>" placeholder="{{ trans('language_changer.first'),' ',(trans('language_changer.name'))}}" >

                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('language_changer.last'),' ',(trans('language_changer.name'))}}</label>
                                            <input class="form-control" type="text" name="last_name" value="<?= $owner->last_name ?>" placeholder="{{ trans('language_changer.last'),' ',(trans('language_changer.name'))}}">


                                
                                        </div>

                                      <div class="form-group">
                                            <label>{{ trans('language_changer.email')}}</label>
                                            <input class="form-control" type="email" name="email" value="<?= $owner->email ?>" placeholder="{{ trans('language_changer.email')}}" disabled >

                                
                                        </div>

                                         <div class="form-group">
                                            <label>{{ trans('language_changer.phone')}}</label>
                                            <input class="form-control" type="text" name="phone" value="<?php $pno= preg_replace('/[^A-Za-z0-9\-]/', '', $owner->phone);echo $pno; ?>" placeholder="{{ trans('language_changer.phone')}}" disabled>

                                
                                        </div>


                                         <div class="form-group">
                                            <label>{{ trans('language_changer.address')}}</label>
                                            <input class="form-control" type="text" name="address" id="address" value="<?= $owner->address ?>" placeholder="{{ trans('language_changer.address')}}" >


                                        </div>


                                         <div class="form-group">
                                            <label>{{ trans('language_changer.state')}}</label>
                                            <input class="form-control" type="text" name="state" id="state" value="<?= $owner->state ?>" placeholder="{{ trans('language_changer.state')}}">

                                        </div>



                                        <div class="form-group">
                                            <label>{{ trans('language_changer.zip_Code')}}</label>
                                            <input class="form-control" type="text" name="zipcode" id="zipcode" value="<?= $owner->zipcode ?>" placeholder="{{ trans('language_changer.zip_Code')}}">

                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('language_changer.profile'),' ',trans('language_changer.image') }}</label>
                                            <input class="form-control" type="file" name="image" >
                                            <br>
                                            <img src="<?= $owner->picture; ?>" height="50" width="50"><br>
                                            <p class="help-block">{{ trans('language_changer.image_format') }}</p>
                                        </div>
                                   
                                    </div><!-- /.box-body -->

                                    <div class="box-footer">

                                      <button type="submit" id="edit" class="btn btn-primary btn-flat btn-block">{{ trans('language_changer.update'),' ',trans('language_changer.change'),'s' }}</button>
                                    </div>
                                </form>
                            </div>



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
<?php } ?>
<?php
if($success == 3) { ?>
    <script type="text/javascript">
        var msg="Please Upload image as Jpeg or png"
        alert(msg);
    </script>
    <?php }

    }?>

<script type="text/javascript">
$("#main-form").validate({
  rules: {
    first_name: "required",
    last_name: "required",
    address: "required",
    state: "required",

    zipcode: {
        required: true,
       number: true,
    },
      /*phone: {
        required: true,
       number: true,
    },
      email: {
          required: true,
          email: true,
    },*/


  }
});
</script>

@stop