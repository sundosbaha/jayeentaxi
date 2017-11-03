
@extends('layout')

@section('content')


<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title" style="float:<?php echo $align_format; ?>"><?= $title ?></h3>
    </div><!-- /.box-header -->
    <!--<div class="box-body"></div><!-- /.box-body -->
    <!-- form start -->
    <form method="post" id="basic" action="{{ URL::Route('AdminProviderTypeUpdate') }}"  enctype="multipart/form-data" dir="{{ trans('language_changer.text_format') }}">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="form-group col-md-12 col-sm-12">
            <label>{{ trans('language_changer.type'),' ',trans('language_changer.name') }}</label>
            <input type="text" class="form-control" name="name" placeholder="Type Name" name="name" value="<?= $name ?>">
            {{--@if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif--}}
        </div>
        <div class="form-group col-md-6 col-sm-6" >
            <label>{{ trans('language_changer.base_price'), ' ' , trans('language_changer.distance') }}</label>
            <select name="base_distance" class="form-control">
                <?php
                for ($i = 1; $i <= 25; $i++) {
                    if ($base_distance == $i) {
                        ?>
                        <option value="<?= $i ?>" selected=""><?= $i . " " . $unit_set ?></option>
                    <?php } else { ?>
                        <option value="<?= $i ?>" ><?= $i . " " . $unit_set ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group col-md-6 col-sm-6">
            <label>{{ trans('language_changer.base_price'),' (',trans('language_changer.in_us_dollar'),')  ' }}</label> <span id="no_amount_error1" style="display: none"> </span>
            <input type="text" class="form-control" onkeypress="return Isamount(event, 1);" placeholder="{{ trans('language_changer.base_price') }}" name="base_price" value="<?= $base_price ?>">
        </div>
        <div class="form-group col-md-6 col-sm-6">
            <label>{{ trans('language_changer.price_per_unit_distance'),' (',trans('language_changer.in_us_dollar'),')  ' }} </label> <span id="no_amount_error2" style="display: none"> </span>
            <input type="text" class="form-control" onkeypress="return Isamount(event, 2);" placeholder="{{ trans('language_changer.price_per_unit_distance') }}" name="distance_price" value="<?= $price_per_unit_distance ?>">
        </div>
        <div class="form-group col-md-6 col-sm-6">
            <label>{{ trans('language_changer.price_per_unit_time'),' (',trans('language_changer.in_us_dollar'),')  ' }} </label> <span id="no_amount_error3" style="display: none"> </span>
            <input type="text" class="form-control" onkeypress="return Isamount(event, 3);" placeholder="{{ trans('language_changer.price_per_unit_time') }}" name="time_price" value="<?= $price_per_unit_time ?>">
        </div>
        <div class="form-group col-md-6 col-sm-6">
            <label>{{ trans('language_changer.maximum'),' ', trans('language_changer.size') }}</label> <span id="no_number_error1" style="display: none"> </span>
            <input type="text" class="form-control" onkeypress="return IsNumeric(event, 1);" placeholder="{{ trans('language_changer.maximum'),' ', trans('language_changer.size') }}" name="max_size" value="<?= $max_size ?>">
        </div>
        <div class="form-group col-md-6 col-sm-6">
            <label>{{ trans('language_changer.waiting_price') }}</label> <span id="no_number_error1" style="display: none"> </span>
            <input type="text" class="form-control" onkeypress="return IsNumeric(event, 1);" placeholder="{{ trans('language_changer.waiting_price')}}" name="waiting_price" value="<?= $waiting_price ?>">
        </div>
        <?php if (!$is_default == 1) { ?>
            <div class="form-group col-md-6 col-sm-6">
                <label>{{ trans('language_changer.visibility') }}</label>
                <select name="is_visible" class="form-control">
                    <?php if ($is_visible == 1) { ?>
                        <option value="0" >{{ trans('language_changer.invisible') }}</option>
                        <option value="1" selected="">{{ trans('language_changer.visible') }}</option>
                    <?php } else { ?>
                        <option value="0" selected="">{{ trans('language_changer.invisible') }}</option>
                        <option value="1" >{{ trans('language_changer.visible') }}</option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-md-6 col-sm-6">
                <br>
                <br>
                <input class="form-control" type="checkbox" name="is_default" value="1">
                <label>{{ trans('language_changer.set_as_default') }}</label>
            </div>
        <?php } else { ?>
            <input type="hidden" name="is_default" value="1">
            <input type="hidden" name="is_visible" value="1">
        <?php } ?>
        <div class="form-group col-md-6 col-sm-6">
            <label>{{ trans('language_changer.icon'),' ',trans('language_changer.file') }}</label>
            <input type="file" name="icon" class="form-control" >
            <br>
            <?php

            if ($icon != "") { ?>
                <img src="<?= $icon; ?>" height="50" width="50">
            <?php } ?><br>
            <!--<p class="help-block">Please Upload image in jpg, png format.</p>-->
            @if ($errors->has('icon'))
                <span class="help-block">
                    <strong>{{ $errors->first('icon') }}</strong>
                </span>
            @endif
        </div>
        <div class="box-footer">
            <button type="submit" id="add" class="btn btn-primary btn-flat btn-block">{{ trans('language_changer.save') }}</button>
        </div>
    </form>
</div>



<?php if ($success == 1) { ?>
    <script type="text/javascript">
var msg="<?php echo trans('language_changer.provider') .' '. trans('language_changer.update') .' '.trans('language_changer.successfully') ?>";

        alert(msg);
        document.location.href = "{{ URL::Route('AdminProviderTypes') }}";
    </script>
<?php } ?>
<?php if ($success == 2) { ?>
    <script type="text/javascript">
        var msg= "<?php echo trans('language_changer.wrong')  ?>";
        alert(msg);

    </script>
<?php } ?>


<script type="text/javascript">
     $("#basic").validate({
        rules: {
            name: "required",
        }
    });

</script>


@stop