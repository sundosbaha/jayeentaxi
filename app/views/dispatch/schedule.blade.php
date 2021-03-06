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




    <div class="col-md-12 col-sm-12" >
        <div class="box box-danger" style="display : none;" dir="{{ trans('dispatcher.text_format') }}">
            <form method="get" action="{{ URL::Route('DispatchScheduleview') }}">
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
                <div class="box-footer">
                    <!--<button type="submit" name="submit" class="btn btn-flat btn-block btn-success board_btn" value="Filter_Data">Filter Data</button>-->

                    <div class="box-body row ">
                        <div class="col-md-6 col-sm-6">
                            <button type="submit" name="submit" id="btnsearchdispa" class="btn btn-flat btn-block btn-success"  value="search">{{ trans('dispatcher.search') }}</button>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <button type="submit" name="submit" id="btndownloaddispa" class="btn btn-flat btn-block btn-success" value="Download_Report">{{ trans('dispatcher.download').' '.trans('dispatcher.report')  }}</button>
                        </div>

                    </div><div class="box-body row ">
                        <div class="col-md-12 col-sm-12">
                            <button type="submit" name="submit" id="btnviewdispa" class="btn btn-flat btn-block btn-success" value="view">{{ trans('dispatcher.reset_all') }}</button>
                        </div>
                    </div>
                </div>      </form>
        </div>

        <div class="col-md-12 col-sm-12">

            <br><br>
        </div>
        <div class="box box-info tbl-box">
            <div align="left" id="paglink"><?php  echo urldecode($request->appends(array('type' => Session::get('type')))->links()); ?></div>
            <table class="table table-bordered" dir="{{ trans('dispatcher.text_format') }}">
                <tbody>
                <tr>
                    <th><?php echo Lang::get('dispatcher.sno'); ?></th>
                    <th><?php echo Lang::get('dispatcher.customer_name'); ?></th>
                    <th><?php echo Lang::get('dispatcher.date'); ?></th>
                    <th><?php echo Lang::get('dispatcher.time'); ?></th>
                    <th><?php echo Lang::get('dispatcher.Pickup'); ?></th>

                </tr>

                <?php
                $cntrr=1;
                foreach($request as $req)
                {
                ?>
                <tr>
                    <td><?php  echo $cntrr; ?></td>
                    <td><?php echo $req->first_name.' '.$req->last_name ?></td>


                    <td><?php echo date('d-m-Y', strtotime($req->schedule_datetime)); ?></td>
                    <td>
                        <?php  echo $req->newtime;
                        ?>
                    </td>
                    <td ><?php echo $req->pickupAddress; ?></td>
                </tr>
                <?php
                $cntrr++;
                }
                ?>

                </tbody></table>
            <div align="left" id="paglink"><?php echo urldecode($request->appends(array('type' => Session::get('type')))->links()); ?></div>
        </div>


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
                onSelect: function (date) {
                    // Your CSS changes, just in case you still need them
                    alert('ok');
                }
            });


            $('#btnviewdispa').click(function(){
                $("#start_date").val('');
                $("#end_date").val('');
                $("#submit").val('');
                window.location.href = "http://sapphiretechserve.com/public/dispatch/schedule_view";
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
                    alert('Don\'t leave Empty Field');
                    return false;
                }
            });

            $('#end_date').datepicker({
                //startDate: new Date(),
                defaultDate: "+1w",
                format: 'yyyy-mm-dd',
                autoclose: true,
            });
        });

    </script>




    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>


    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        } );
    </script>






@stop