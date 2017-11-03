@extends('layout')

@section('content')

<script src="https://bitbucket.org/pellepim/jstimezonedetect/downloads/jstz-1.0.4.min.js"></script>
<script src="http://momentjs.com/downloads/moment.min.js"></script>
<script src="http://momentjs.com/downloads/moment-timezone-with-data.min.js"></script>

 <div class="col-md-6 col-sm-12" dir="{{ trans('language_changer.text_format') }}" >

    <div class="box box-danger">

        <form method="get" action="{{ URL::Route('/admin/sortreq') }}">
            <div class="box-header">
                <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.sort') }}</h3>
            </div>
            <div class="box-body row">

                <div class="col-md-6 col-sm-12" style="float:<?php echo $align_format; ?>">

                    <select class="form-control" id="sortdrop" name="type">
                        <option value="reqid" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'reqid') {
                            echo 'selected="selected"';
                        }
                        ?>  id="reqid">{{ trans('language_changer.Request'),' ', strtoupper(trans('language_changer.id')) }} </option>
                        <option value="owner" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'owner') {
                            echo 'selected="selected"';
                        }
                        ?>  id="owner">{{ trans('language_changer.User'),' ',trans('language_changer.name');}}</option>
                        <option value="walker" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'walker') {
                            echo 'selected="selected"';
                        }
                        ?>  id="walker">{{ trans('language_changer.Provider');}}</option>
                        <option value="payment" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'payment') {
                            echo 'selected="selected"';
                        }
                        ?>  id="payment">{{trans('language_changer.payment'),' ',trans('language_changer.mode')}}</option>
                    </select>

                    <br>
                </div>
                <div class="col-md-6 col-sm-12">
                    <select class="form-control" id="sortdroporder" name="valu">
                        <option value="asc" <?php
                        if (isset($_GET['type']) && $_GET['valu'] == 'asc') {
                            echo 'selected="selected"';
                        }
                        ?>  id="asc">{{trans('language_changer.ascending')}}</option>
                        <option value="desc" <?php
                        if (isset($_GET['type']) && $_GET['valu'] == 'desc') {
                            echo 'selected="selected"';
                        }
                        ?>  id="desc">{{trans('language_changer.descending')}}</option>
                    </select>

                    <br>
                </div>

            </div>

            <div class="box-footer">

                <button type="submit" id="btnsort" class="btn btn-flat btn-block btn-success">{{ trans('language_changer.sort') }}</button>
                <button type="submit" id="btnsort" name="submit" class="btn btn-flat btn-block btn-success" value="Download_Report">{{ trans('language_changer.download'),' ',trans('language_changer.report')  }}</button>
            </div>
        </form>

    </div>
</div>


<div class="col-md-6 col-sm-12" dir="{{ trans('language_changer.text_format') }}">

    <div class="box box-danger">

        <form method="get" action="{{ URL::Route('/admin/searchreq') }}">
            <div class="box-header">
                <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.filter') }}</h3>
            </div>
            <div class="box-body row"  >

                <div class="col-md-6 col-sm-12" style="float:<?php echo $align_format; ?>" >

                    <select class="form-control" id="searchdrop" name="filter_type">
                        <option value="reqid" id="reqid" <?php
                                if (isset($_GET['filter_type']) && $_GET['filter_type'] == 'reqid') {
                                    echo 'selected="selected"';
                                }
                                ?>>{{ trans('language_changer.Request'),' ',strtoupper(trans('language_changer.id'))  }}</option>

                        <option value="owner" id="owner" <?php
                                if (isset($_GET['filter_type']) && $_GET['filter_type'] == 'owner') {
                                    echo 'selected="selected"';
                                }
                                ?> >{{ trans('language_changer.User'), ' ' ,trans('language_changer.name')}} </option>
                        <option value="walker" id="walker" <?php
                                if (isset($_GET['filter_type']) && $_GET['filter_type'] == 'walker') {
                                    echo 'selected="selected"';
                                }
                                ?>>{{ trans('language_changer.Provider');}}</option>
                        <option value="payment" id="payment" <?php
                                if (isset($_GET['filter_type']) && $_GET['filter_type'] == 'payment') {
                                    echo 'selected="selected"';
                                }
                                ?>>{{trans('language_changer.payment'),' ',trans('language_changer.mode')}}</option>
                    </select>

                    <br>
                </div>
                <div class="col-md-6 col-sm-12">

                    <input class="form-control" type="text" name="filter_valu" value="<?php echo (!empty($_GET['filter_valu']) ? $_GET['filter_valu'] : '');
                    ?>" id="insearch" placeholder="{{ trans('language_changer.keyword'); }}"/>
                    <br>
                </div>

            </div>

            <div class="box-footer">
                <button type="submit" id="btnsearch" class="btn btn-flat btn-block btn-success">{{ trans('language_changer.search')}}</button>
                <button type="submit" id="btnsearch" name="submit" class="btn btn-flat btn-block btn-success" value="Download_Report">{{ trans('language_changer.download'),' ', trans('language_changer.report') }}</button>
            </div>
        </form>

    </div>
</div>



<div class="box box-info tbl-box" >
    <div align="left" id="paglink" style="float:<?php echo $align_format; ?>"><?php echo $walks->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>
    <table class="table table-bordered" dir="{{ trans('language_changer.text_format') }}">
        <tbody>
            <tr>
                <th>{{ trans('language_changer.Request'),' ', strtoupper(trans('language_changer.id'))}}</th>
                <th>{{ trans('language_changer.booking'),' ', strtoupper(trans('language_changer.id'))}}</th>
                <th>{{ trans('language_changer.User'),' ', trans('language_changer.name');}}</th>
                <th>{{ trans('language_changer.Provider');}}</th>
                <th>{{ trans('language_changer.date'),'/', trans('language_changer.time')}}</th>
                <th>{{ trans('language_changer.status')}}</th>
                <th>{{ trans('language_changer.amount')}}</th>
                <th>{{ trans('language_changer.payment'),' ', trans('language_changer.mode')}}</th>
                <th>{{ trans('language_changer.payment'),' ', trans('language_changer.status')}}</th>
                <th>{{ trans('language_changer.action')}}</th>
            </tr>
            <?php $i = 0; ?>

            <?php  if(count($walks) > 0) :
            foreach ($walks as $walk) { ?>
                <tr>
                    <td ><?= $walk->id ?></td>
                    <td><?= (!empty($walk->booking_id) ? $walk->booking_id : '--') ?></td>
                    <td><?php echo $walk->owner_first_name . " " . $walk->owner_last_name; ?> </td>
                    <td>
                        <?php
                        if ($walk->confirmed_walker) {
                            echo $walk->walker_first_name . " " . $walk->walker_last_name;
                        } else {
                            echo trans('language_changer.un_assigned');
                        }
                        ?>
                    </td>
                   <!-- <td id= 'time<?php //echo $i; ?>' >
                        <script>
    var timezone = jstz.determine();
    // console.log(timezone.name());
    var timevar = moment.utc("<?php //echo $walk->date; ?>");
    timevar.toDate();
    timevar.tz(timezone.name());
    // console.log(timevar);
    document.getElementById("time<?php //echo $i; ?>").innerHTML = timevar;
    <?php //$i++; ?>
                        </script>
                    </td>-->
                    <?php
                    $default_timezone = Config::get('app.timezone');
                    $user_timezone = Config::get('app.usertimezone');
                    $date_time = get_user_time($default_timezone, $user_timezone, $walk->date);
                    ?>
                    <td> <?php echo date('Y-m-d/H:i:s', strtotime($date_time)); ?> </td>

                    <td>
                        <?php
                        if ($walk->is_cancelled == 1) {
                            echo "<span class='badge bg-red'>". trans('language_changer.cancelled')."</span>";
                        } elseif ($walk->is_completed == 1) {
                            echo "<span class='badge bg-green'>". trans('language_changer.completed')."</span>";
                        } elseif ($walk->is_started == 1) {
                            echo "<span class='badge bg-yellow'>". trans('language_changer.start').trans('language_changer.ed')."</span>";
                        } elseif ($walk->is_walker_arrived == 1) {
                            echo "<span class='badge bg-yellow'>".trans('language_changer.walker').' '.trans('language_changer.arrived')."</span>";
                        } elseif ($walk->is_walker_started == 1) {
                            echo "<span class='badge bg-yellow'>".trans('language_changer.walker').' '.trans('language_changer.start')."ed"."</span>";
                        } else {
                            echo "<span class='badge bg-light-blue'>".trans('language_changer.yet_to_start')."</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo sprintf2($walk->total, 2); ?>
                    </td>
                    <td>
                        <?php
                        if ($walk->payment_mode == 0) {
                            echo "<span class='badge bg-orange'>".trans('language_changer.stored').' '.trans('language_changer.card')."s"."</span>";
                        } elseif ($walk->payment_mode == 1) {
                            echo "<span class='badge bg-blue'>".trans('language_changer.pay_by_cash')."</span>";
                        } elseif ($walk->payment_mode == 2) {
                            echo "<span class='badge bg-purple'>".trans('language_changer.pay_pal')."</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($walk->is_paid == 1) {
                            echo "<span class='badge bg-green'>".trans('language_changer.completed')."</span>";
                        } elseif ($walk->is_paid == 0 && $walk->is_completed == 1) {
                            echo "<span class='badge bg-red'>".trans('language_changer.pending')."</span>";
                        } else {
                            echo "<span class='badge bg-yellow'>".trans('language_changer.request_not_completed')."</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-flat btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                                {{ trans('language_changer.action')}}
                                <span class="caret"></span>
                            </button>
                            <?php /* echo Config::get('app.generic_keywords.Currency'); */ ?>
                            <ul class="dropdown-menu" <?php echo trans('language_changer.text_format')=="rtl"?'style="left:0;right:auto;"':""; ?> role="menu" aria-labelledby="dropdownMenu1">
                                <li role="presentation"><a role="menuitem" id="map" tabindex="-1" href="{{ URL::Route('AdminRequestsMap', $walk->id) }}">{{ trans('language_changer.view_map') }}</a></li>
                                @if($setting->value==1 && $walk->is_completed==1 && (Config::get('app.generic_keywords.Currency')=='$' || Config::get('app.default_payment') != 'stripe'))
                                <li role="presentation"><a role="menuitem" id="map" tabindex="-1" href="{{ URL::Route('AdminPayProvider', $walk->id) }}">{{ trans('language_changer.transfer'),' ',trans('language_changer.amount') }}</a></li>
                                @endif
                                @if($walk->is_paid==0 && $walk->is_completed==1 && $walk->payment_mode!=1 && $walk->total!=0)
                                <li role="presentation"><a role="menuitem" id="map" tabindex="-1" href="{{ URL::Route('AdminChargeUser', $walk->id) }}"> {{trans('language_changer.change'),' ', trans('language_changer.User');}}</a></li>
                                @endif
                                <!--
                                <li role="presentation" class="divider"></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo web_url(); ?>/admin/walk/delete/<?= $walk->id; ?>">Delete Walk</a></li>
                                -->
                            </ul>
                        </div>  

                    </td>
                </tr>
            <?php } else :?>
            <tr>
                <td colspan="10"><?php echo trans('language_changer.no_record_found'); ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div style="float:<?php echo $align_format; ?>" align="left" id="paglink"><?php echo $walks->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>




</div>

<!--
  <script>
  $(function() {
    $( "#start-date" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function( selectedDate ) {
        $( "#end-date" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#end-date" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function( selectedDate ) {
        $( "#start-date" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
  });
  </script>
-->

<script type="text/javascript">


    $(document).ready(function () {
        $("#btnsearch").click(function(){

            if($("#insearch").val() == '' ){
             return false;
            }

        });
    });


</script>

<style type="text/css">

    #btnsort {
        width: 49% !important;
        left: 0% !important;
    }
    #btnsearch, .btn-warning {
        width: 49% !important;
        left: 0% !important;
    }


</style>
@stop