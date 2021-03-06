@extends('layout')

@section('content')
    <style>

        <?php  if($align_format == 'right'){  ?>

    .inner{
            position: relative;
            text-align: right;
        }

        .icon{
            left: 41px;
        }
        <?php  } ?>



    </style>


                        <div class="row" >
                        <div class="col-lg-6 col-xs-6" >
                            <!-- small box -->
                            <div class="small-box bg-aqua"  >
                                <div class="inner" >
                                    <h3>
                                       {{ $ledger?$ledger->total_referrals:0 }}
                                    </h3>
                                    <p>
                                        {{ trans('language_changer.total'),' ',trans('language_changer.referral'),trans('language_changer.s') }}

                                    </p>
                                </div>
                                <div class="icon" >
                                    {{$ledger?$ledger->referral_code:0}}
                                </div>
                              
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-6 col-xs-6" >
                            <!-- small box -->
                            <div class="small-box bg-yellow">
                                <div class="inner" >
                                    <h3>
                                       {{ $ledger?round($ledger->amount_earned):0 }}
                                    </h3>
                                    <p>
                                        {{ trans('language_changer.credit'),'s',' ',trans('language_changer.earned') }}
                                    </p>
                                </div>
                                <div class="icon" >
                                    <i class="ion ion-cash"></i>
                                </div>
                                
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-6 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>
                                        {{ $ledger?round($ledger->amount_spent):0 }}
                                    </h3>
                                    <p>
                                        {{ trans('language_changer.credit'),trans('language_changer.s'),' ',trans('language_changer.spent') }}

                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-battery-low"></i>
                                </div>
                                
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-6 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-purple">
                                <div class="inner">
                                    <h3>
                                        {{ $ledger?round($ledger->amount_earned - $ledger->amount_spent):0 }}
                                    </h3>
                                    <p>
                                        {{ trans('language_changer.balance'),' ',trans('language_changer.credit'),trans('language_changer.s') }}

                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                
                            </div>
                        </div><!-- ./col -->
      
                    </div>

                    <div class="col-md-6 col-sm-12" >

                    <div class="box box-danger" >

                        <form method="get" action="{{ URL::Route('/admin/sortur') }}">
                                <div class="box-header">
                                    <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.sort') }}</h3>
                                </div>
                                <div class="box-body row">

                                <div class="col-md-6 col-sm-12" dir="{{ trans('language_changer.text_format') }}">
                                    <select class="form-control" id="searchdrop" name="type">

                                    <option value="userid" <?php if(isset($_GET['type']) && $_GET['type']=='userid') {echo 'selected="selected"';}?> id="provid">{{ trans('language_changer.User'),' ',trans('language_changer.id')}}</option>
                                    <option value="username" <?php if(isset($_GET['type']) && $_GET['type']=='username') {echo 'selected="selected"';}?> id="pvname">{{ trans('language_changer.User'),' ',trans('language_changer.name')}}</option>
                                    <option value="useremail" <?php if(isset($_GET['type']) && $_GET['type']=='useremail') {echo 'selected="selected"';}?> id="pvemail">{{ trans('language_changer.User'),' ',trans('language_changer.email')}}</option>
                                </select>
                                    <br>
                                </div>
                                <div class="col-md-6 col-sm-12" dir="{{ trans('language_changer.text_format') }}">
                                    <select class="form-control" id="searchdroporder" name="valu">
                                    <option value="asc" <?php if(isset($_GET['valu']) && $_GET['valu']=='asc') {echo 'selected="selected"';}?> id="asc">{{ trans('language_changer.ascending') }}</option>
                                    <option value="desc" <?php if(isset($_GET['valu']) && $_GET['valu']=='desc') {echo 'selected="selected"';}?> id="desc">{{ trans('language_changer.descending') }}</option>
                                </select>
                                    <br>
                                </div>

                                </div>

                                <div class="box-footer">

                                        
                                        <button type="submit" id="btnsort" class="btn btn-flat btn-block btn-success">{{ trans('language_changer.sort') }}</button>

                                        
                                </div>
                        </form>

                    </div>
                </div>

                <div class="col-md-6 col-sm-12">

                    <div class="box box-danger" >

                       <form method="get" action="{{ URL::Route('/admin/searchur') }}">
                                <div class="box-header" dir="{{ trans('language_changer.text_format') }}">
                                    <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.filter') }}</h3>
                                </div>
                                <div class="box-body row">

                                <div class="col-md-6 col-sm-12" dir="{{ trans('language_changer.text_format') }}">

                                <select class="form-control" id="searchdrop" name="type">
                                  <option value="userid" id="userid">{{ trans('language_changer.User'),' ',trans('language_changer.id')}}</option>
                                  <option value="username" id="username">{{ trans('language_changer.User'),' ',trans('language_changer.name')}}</option>
                                  <option value="useremail" id="useremail">{{ trans('language_changer.User'),' ',trans('language_changer.email') }}</option>
                                  <option value="useraddress" id="useraddress">{{ trans('language_changer.User'),trans('language_changer.address')}} </option>
                              </select>
                                    
                                    <br>
                                </div>
                                <div class="col-md-6 col-sm-12" dir="{{ trans('language_changer.text_format') }}">
                                    <input class="form-control" type="text" name="valu" id="insearch" placeholder="{{ trans('language_changer.keyword') }}"/>
                                    <br>
                                </div>

                                </div>

                                <div class="box-footer">

                                        <button type="submit" id="btnsearch" class="btn btn-flat btn-block btn-success">{{ trans('language_changer.search') }}</button>

                                        
                                </div>
                        </form>

                    </div>
                </div>



                <div class="box box-info tbl-box" dir="{{ trans('language_changer.text_format') }}">
                <div align="left" id="paglink"><?php echo $owners->appends(array('type'=>Session::get('type'), 'valu'=>Session::get('valu')))->links(); ?></div>
                <table class="table table-bordered">
                                <tbody>
                                        <tr>
                                            <th>{{ trans('language_changer.id') }}</th>
                                            <th>{{ trans('language_changer.name') }}</th>
                                            <th>{{ trans('language_changer.email') }}</th>
                                            <th>{{ trans('language_changer.phone') }}</th>
                                            <th>{{ trans('language_changer.address') }}</th>
                                            <th>{{ trans('language_changer.state') }}</th>
                                            <th>{{ trans('language_changer.zip_Code') }}</th>

                                        </tr>
                                     <?php foreach ($owners as $owner) { ?>
                                    <tr>
                                        <td><?= $owner->id ?></td>
                                        <td><?php echo $owner->first_name." ".$owner->last_name; ?> </td>
                                        <td><?= $owner->email ?></td>
                                        <td><?= $owner->phone ?></td>
                                        <td><?= $owner->address ?></td>
                                        <td><?= $owner->state ?></td>
                                        <td><?= $owner->zipcode ?></td>
                                        </tr>
                                    <?php } ?>
                    </tbody>
                </table>

                <div align="left" id="paglink"><?php echo $owners->appends(array('type'=>Session::get('type'), 'valu'=>Session::get('valu')))->links(); ?></div>
                </div>


<script>

    $("#btnsearch").click(function(){
        if($("#insearch").val() == '' ){
            return false;
        }

    });


</script>

@stop