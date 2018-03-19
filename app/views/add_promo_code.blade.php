@extends('layout')

@section('content')

@if (Session::has('msg'))
<h4 class="alert alert-info">
    {{{ Session::get('msg') }}}
    {{{Session::put('msg',NULL)}}}
</h4>
@endif
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.add'),' ', trans('language_changer.promo_code') }}</h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <form role="form" id="form" method="post" action="{{ URL::Route('AdminPromoUpdate') }}"  enctype="multipart/form-data">
        <input type="hidden" name="id" value="0>">
        <div class="box-body" style="padding: 0px" dir="{{ trans('language_changer.text_format') }}">
            <div class="form-group col-md-6 col-sm-6" >
                <label>{{ trans('language_changer.promo_code'),' ',trans('language_changer.name') }}</label>
                <input type="text" class="form-control" name="code_name" value="" placeholder="{{ trans('language_changer.promo_code'),' ',trans('language_changer.name') }}" >
            </div>
            <div class="form-group col-md-6 col-sm-6">
                <label>{{ trans('language_changer.promo_code'),' ',trans('language_changer.value') }}</label>
                <span id="no_amount_error1" style="display: none"></span>
                <input class="form-control" type="text"  id="code_value" name="code_value" value="" placeholder="{{ trans('language_changer.promo_code'),' ',trans('language_changer.value') }}" onkeypress="return Isamount(event, 1);">
            </div>
            <div class="form-group col-md-6 col-sm-6">
                <label>{{ trans('language_changer.promo_code'),' ',trans('language_changer.type') }}</label>
                <select name="code_type" id="code_type" class="form-control">
                    <option value="1">{{ trans('language_changer.percent')}}</option>
                    <option value="2">{{ trans('language_changer.absolute')}}</option>
                </select>
            </div>
            <div class="form-group col-md-6 col-sm-6">
                <label>{{ trans('language_changer.uses_allowed')}}</label>
                <span id="no_number_error1" style="display: none"> </span>
                <input class="form-control" type="text" name="code_uses" value="" placeholder="Number of Users" onkeypress="return IsNumeric(event, 1);">
            </div>
            <div class="form-group col-md-6 col-sm-6">
                <label>{{ trans('language_changer.start').trans('language_changer.date') }}</label>
                <br>
                <input type="text" class="form-control" style="overflow:hidden;" id="start-date" name="start_date" value="{{date("m/d/Y")}}" placeholder="{{ trans('language_changer.start').trans('language_changer.date') }}">
                <!--<div class='input-group date' id='startDate'>
                <input type='text' class="form-control" name="code_expiry" value="{{date("m/d/Y h:i A")}}">
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                </span>
                </div>-->
            </div>
            <div class="form-group col-md-6 col-sm-6">
                <label>{{ trans('language_changer.expiry').trans('language_changer.date') }}</label>
                <br>
                <input type="text" class="form-control" style="overflow:hidden;" id="end-date" name="code_expiry" placeholder="{{ trans('language_changer.expiry').trans('language_changer.date') }}"  value="{{date("m/d/Y")}}">
                <!--<div class='input-group date' id='startDate'>
                <input type='text' class="form-control" name="code_expiry" value="{{date("m/d/Y h:i A")}}">
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                </span>
                </div>-->
            </div>
        </div><!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary btn-flat btn-block">{{ trans('language_changer.update'),' ',trans('language_changer.change') }}</button>
        </div>
    </form>
</div>



<script type="text/javascript">

    $('#form').submit(function(e){

        if($('#code_type').val() == '1')

        //alert($('#code_type').val());
        {
            if($('#code_value').val() > 100)
            {
                $('#code_value').val('');
                $('#code_value').focus();
                alert('Percentage is only upto 100');
                return false;
            }
        }
        return true;
    })



</script>


<script type="text/javascript">
    $("#form").validate({
        rules: {
            code_name: "required",
            code_value: "required",
            code_uses: "required",
            code_expiry: "required",
        }
    });

</script>
<script>



</script>


<script type="text/javascript">

    /*jQuery(function () {
     jQuery('#startDate').datetimepicker();
     jQuery("#startDate").on("dp.change", function (e) {
     jQuery('#endDate').data("DateTimePicker").setMinDate(e.date);
     });
     jQuery("#endDate").on("dp.change", function (e) {
     jQuery('#startDate').data("DateTimePicker").setMaxDate(e.date);
     });
     });
     /*jQuery(function () {
     jQuery('#endDate').datetimepicker();
     jQuery("#endDate").on("dp.change", function (e) {
     jQuery('#startDate').data("DateTimePicker").setMinDate(e.date);
     });
     jQuery("#startDate").on("dp.change", function (e) {
     jQuery('#endDate').data("DateTimePicker").setMaxDate(e.date);
     });
     });*/

</script>

<!--<script type="text/javascript" src="{{asset('javascript/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('javascript/bootstrap-datetimepicker.js')}}"></script>-->
@stop