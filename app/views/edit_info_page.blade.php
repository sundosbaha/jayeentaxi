
@extends('layout')

@section('content')

 <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title" style="float:<?php echo $align_format; ?>" ><?= $title ?></h3>
                                </div><!-- /.box-header -->
                                <!-- form start -->
                                 <form method="post" id="basic-form" action="{{ URL::Route('AdminInformationUpdate') }}"  enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $id ?>">

                                    <div class="box-body" dir="{{ trans('language_changer.text_format') }}">
                                        <div class="form-group">
                                            <label>{{ trans('language_changer.title') }}</label>
                                            <input type="text" name="title" class="form-control" placeholder="{{ trans('language_changer.title') }}" value="<?= $info_title ?>">
                                                                                   
                                        </div>

                                                                           

                                        <div class="form-group">
                                            <label>{{ trans('language_changer.icon'), ' ' ,trans('language_changer.file') }}</label>

                                           
                                             <input type="file" name="icon" class="form-control" >
                                             <br>
                                              <?php if($icon != "") {?>
                                            <img src="<?= $icon; ?>" height="50" width="50">
                                            <?php } ?><br>
                                            
                                            <p class="help-block">{{ trans('language_changer.upload_image_format') }}</p>
                                        </div>

                                        <div class="form-group">
                                        <label>{{ trans('language_changer.description') }}</label>
                                        <textarea id="editor1" name="description" rows="10" cols="124">
                                            <?= $description ?>  
                                        </textarea>
                                        </div>
                                  

                                   
                                    </div><!-- /.box-body -->

                                    <div class="box-footer">

                                        <button id="add_info" type="submit" class="btn btn-primary btn-flat btn-block">{{ trans('language_changer.save'), ' ' , trans('language_changer.page') }}</button>
                                    </div>
                                </form>
                            </div>




<?php
if($success == 1) { ?>
<script type="text/javascript">

    var msg= "<?php echo trans('language_changer.successfully').' '.trans('language_changer.update') ?>";

    alert(msg);
</script>
<?php } ?>
<?php
if($success == 2) { ?>
<script type="text/javascript">
    var msg= "<?php echo trans('language_changer.wrong')  ?>";
    alert(msg);
</script>
<?php } ?>

<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>

<script type="text/javascript">
$("#basic-form").validate({
  rules: {
    title: "required",
    description: "required",
  
  }
});

</script>

@stop