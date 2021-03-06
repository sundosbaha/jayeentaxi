
@extends('layout')

@section('content')

<div class="box box-primary">
              
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

                                      <!--   <div class="form-group">
                                            <label>Email</label>
                                            <input class="form-control" type="email" name="email" value="<?php //$owner->email 
											?>" placeholder="Email">

                                
                                        </div>

                                         <div class="form-group">
                                            <label>Phone</label>
                                            <input class="form-control" type="text" name="phone" value="<?php //$owner->phone 
											?>" placeholder="Phone">

                                
                                        </div>-->


                                         <div class="form-group">
                                            <label>{{ trans('language_changer.address')}}</label>
                                            <input class="form-control" type="text" name="address" value="<?= $owner->address ?>" placeholder="{{ trans('language_changer.address')}}">


                                        </div>


                                         <div class="form-group">
                                            <label>{{ trans('language_changer.state')}}</label>
                                            <input class="form-control" type="text" name="state" value="<?= $owner->state ?>" placeholder="{{ trans('language_changer.state')}}">

                                        </div>



                                        <div class="form-group">
                                            <label>{{ trans('language_changer.zip_Code')}}</label>
                                            <input class="form-control" type="text" name="zipcode" value="<?= $owner->zipcode ?>" placeholder="{{ trans('language_changer.zip_Code')}}">

                                        </div>


                                   
                                    </div><!-- /.box-body -->

                                    <div class="box-footer">

                                      <button type="submit" id="edit" class="btn btn-primary btn-flat btn-block">{{ trans('language_changer.update'),' ',trans('language_changer.change'),'s' }}</button>
                                    </div>
                                </form>
                            </div>



<?php
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

<script type="text/javascript">
$("#main-form").validate({
  rules: {
    first_name: "required",
    last_name: "required",

    email: {
      required: true,
      email: true
    },

   phone: {
    required: true,
    digits: true,
  }


  }
});
</script>

@stop