@extends('dispatch.layout')


@section('content')




    <!--

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
-->

    <!--<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />-->
    <link href="<?php echo asset_url(); ?>/admins/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!--<link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />-->
    <link href="<?php echo asset_url(); ?>/admins/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <!--<link href="//code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" rel="stylesheet" type="text/css" />-->
    <link href="<?php echo asset_url(); ?>/admins/ionicons.min.css" rel="stylesheet" type="text/css" />

    <!-- Theme style -->
    <link href="<?php echo asset_url(); ?>/admins/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset_url(); ?>/admins/css/custom-admin.css" rel="stylesheet" type="text/css" />



    <div class="col-md-12 col-sm-12" style="display : none;" dir="{{ trans('dispatcher.text_format') }}">
        <div class="box box-danger">
            <form method="get" action="{{ URL::Route('DispatchBooking') }}">
                <div class="box-header">
                    <h3 class="box-title" dir="{{ trans('dispatcher.text_format') }}" style="float:<?php echo $align_format; ?>">{{ trans('dispatcher.search') }}</h3>
                </div>
                <div class="box-body row">
                    <div class="col-md-6 col-sm-12">
                        <input id="start_date" class="form-control" style="overflow:hidden;"  name="start_date" value="{{ Input::get('start_date') }}" placeholder="{{ trans('dispatcher.start').' '.trans('dispatcher.date') }}">
                        <br>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="input-group bootstrap-timepicker">

                            <input id="end_date" type="text" name="end_date" class="form-control" value="{{ Input::get('end_date') }}" placeholder="{{ trans('dispatcher.end').' '.trans('dispatcher.date') }}">
                        </div>
                    <!-- <input class="form-control" style="overflow:hidden;" id="end-date" name="end_date" placeholder="End Date"  value="{{--{{ Input::get('end_date') }}--}}">-->
                        <br>
                    </div>
                </div>
                <div class="box-body row ">
                    <div class="col-md-6 col-sm-6">
                        <button type="submit" name="submit" id="btnsearchdispa" class="btn btn-flat btn-block btn-success"  value="search">{{ trans('dispatcher.search') }}</button>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <button type="submit" name="submit" id="btndownloaddispa" class="btn btn-flat btn-block btn-success" value="Download_Report">{{ trans('dispatcher.download').' '.trans('dispatcher.report')  }}</button>
                    </div>
                </div>
                <div class="box-body row ">
                    <div class="col-md-12 col-sm-12">
                        <button type="submit" name="submit" id="btnviewdispa" class="btn btn-flat btn-block btn-success" value="view">{{ trans('dispatcher.reset_all') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>






    <div class="col-md-12 col-sm-12" >

        <br><br>
    </div>
    <div class="box box-info tbl-box">
        <div align="left" id="paglink"><?php  echo urldecode($request->appends(array('type' => Session::get('type')))->links()); ?></div>
        <table class="table table-bordered" dir="{{ trans('dispatcher.text_format') }}">
            <tbody>
            <tr>
                <th ><?php echo Lang::get('dispatcher.request_id'); ?></th>
                <th><?php echo Lang::get('dispatcher.customer_name'); ?></th>
                <th><?php echo Lang::get('dispatcher.driver_id'); ?></th>
                <th><?php echo Lang::get('dispatcher.driver_name'); ?></th>
                <th><?php echo Lang::get('dispatcher.status'); ?></th>
                <th><?php echo Lang::get('dispatcher.amount'); ?></th>
                <th><?php echo Lang::get('dispatcher.payment_status'); ?></th>
            </tr>


            <?php foreach($request as $req){  ?>
            <tr>
                <td><?php echo $req->request_id ?></td>
                <td><?php echo $req->ufname.' '.$req->ulname; ?></td>
                <td><?php echo $req->driver_id; ?></td>
                <td><?php echo $req->dfname.' '.$req->dlname; ?></td>
                <td>
                    <?php
                    if($req->is_completed == 1)
                    {
                        echo "<span class='badge bg-green'>".Lang::get('dispatcher.completed')."</span>";
                    }
                    else if($req->is_cancelled == 1)
                    {
                        echo "<span class='badge bg-yellow'>".Lang::get('dispatcher.cancelled')."</span>";
                    }
                    else if($req->confirmed_walker 	== 1)
                    {
                        echo "<span class='badge bg-red'>".Lang::get('dispatcher.on_trip')."</span>";
                    }
                    elseif($req->is_started == 0)
                    {
                        echo "<span class='badge bg-olive'>".Lang::get('dispatcher.not')." ".Lang::get('dispatcher.started')."</span>";
                    }
                    else{
                        echo "<span class='badge bg-olive'>".Lang::get('dispatcher.request_in_progress')."</span>";
                    }
                    ?>
                </td>
                <td><?php echo $req->total;  ?></td>
                <td>
                    <?php
                    if($req->is_paid == 1)
                    {
                        echo "<span class='badge bg-yellow'>".Lang::get('dispatcher.payment').' '.Lang::get('dispatcher.completed')."</span>";
                    }


                    else{
                        echo "<span class='badge bg-yellow'>".Lang::get('dispatcher.trip')." ".Lang::get('dispatcher.not')." ".Lang::get('dispatcher.completed')."</span>";
                    }

                    ?>
                </td>



            </tr>
            <?php } ?>



            </tbody></table>
        <div align="left" id="paglink"><?php echo urldecode($request->appends(array('type' => Session::get('type')))->links()); ?></div>
    </div>




@stop


@section('javascript')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    <script src="<?php echo asset_url(); ?>/admins/js/validator/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.js"></script>



    <script>
        $(document).ready(function() {
            $('#start_date').datepicker({
                //startDate: new Date(),
                defaultDate: "+1w",
                format: 'yyyy-mm-dd',
                autoclose: true,
                onClose: function () {
                    alert('Datepicker Closed');
                }
            });
            $('#end_date').datepicker({
                //startDate: new Date(),
                defaultDate: "+1w",
                format: 'yyyy-mm-dd',
                autoclose: true,
            });


            $('#btnviewdispa').click(function(){
                $("#start_date").val('');
                $("#end_date").val('');
                $("#submit").val('');
                window.location.href = "http://sapphiretechserve.com/public/dispatch/booking";
            });

            $('#btnsearchdispa').click(function(){



                if($('#end_date').val() && $('#start_date').val() !=null){

                    var st_date=new Date($('#start_date').val());
                    var ed_date=new Date($('#end_date').val());

                    if(st_date > ed_date ){
                        alert('Invalid End Date');
                        $('#end_date').val('');
                        return false;
                    }

                }
                else{
                    alert('Don\'t leave Empty');
                    return false;
                }
            });

            $('#btndownloaddispa').click(function(){

                if($('#start_date').val()){
                    if($('#end_date').val() == 0){
                        alert('End Date Required');
                        return false;
                    }
                }


                if($('#end_date').val()){
                    if($('#start_date').val() == 0){
                        alert('Start Date Required');
                        return false;
                    }
                }


                if($('#end_date').val() && $('#start_date').val() !=null){

                    var st_date=new Date($('#start_date').val());
                    var ed_date=new Date($('#end_date').val());

                    if(st_date > ed_date ){
                        alert('Invalid End Date');
                        $('#end_date').val('');
                        return false;
                    }
                }

            });

        });

    </script>

    <script>
        /*$(function () {
         $('#start_date').datepicker({
         defaultDate: "+1w",
         changeMonth: true,
         numberOfMonths: 1,
         onClose: function (selectedDate) {
         $('#end_date').datepicker("option", "minDate", selectedDate);
         }
         });
         $('#end_date').datepicker({
         defaultDate: "+1w",
         changeMonth: true,
         numberOfMonths: 1,
         onClose: function (selectedDate) {
         $('#start_date').datepicker("option", "maxDate", selectedDate);
         }
         });
         });*/



@stop