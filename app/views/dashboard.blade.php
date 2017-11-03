@extends('layout')

@section('content')

    <style>

        #box_inline_shadow{
            color: white;
            background: linear-gradient(141deg, #949359 0%, #d6d459 52%, #ddda1d 75%);
            border: none;
        }

        #box_panel_footer{

            box-shadow: 10px 10px 10px 0px;
            color: #5d5a5a;
            font-weight: 600;
        }

    </style>

<?php
if (!isset($_COOKIE['skipInstallation'])) {
    if (Session::has('notify')) {
        $message = '';
        $message1 = $message2 = $message3 = '';
        if ($install['mail_driver'] == '' && $install['email_address'] == '' && $install['email_name'] == '') {
            $message1 = 'Mail Configuration Missing During Installation';
        }
        if ($install['twillo_account_sid'] == '' && $install['twillo_auth_token'] == '' && $install['twillo_number'] == '') {
            $message2 = 'SMS Configuration Missing During Installation';
        }
        if (($install['default_payment'] == '' && $install['braintree_environment'] == '' && $install['braintree_merchant_id'] == '' && $install['braintree_public_key'] == '' && $install['braintree_private_key'] == '' && $install['braintree_cse'] == '') && ( $install['stripe_publishable_key'] == '')) {
            $message3 = 'Payment Configuration Missing During Installation';
        }
        if ($message1 != '' && $message2 != '' && $message3 != '') {
            $message = "SMS, Mail, Payment Configuration Missing";
        } else if ($message1 != '' && $message2 != '') {
            $message = "SMS, Mail Configuration Missing";
        } else if ($message1 != '' && $message3 != '') {
            $message = "Mail, Payment Configuration Missing";
        } else if ($message3 != '' && $message2 != '') {
            $message = "SMS, Payment Configuration Missing";
        } else if ($message1 != '' && $message3 == '' && $message2 == '') {
            $message = $message1;
        } else if ($message2 != '' && $message1 == '' && $message3 == '') {
            $message = $message2;
        } else if ($message3 != '' && $message1 == '' && $message2 == '') {
            $message = $message3;
        }

        if ($message != '') {
            ?>
            <div id="myModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">{{ trans('language_changer.installation'),' ',trans('language_changer.notification') }}</h4>
                        </div>
                        <div class="modal-body">
                            <p><?php echo $message; ?></p>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ URL::Route('AdminSettingDontShow') }}"><button type="button" class="btn btn-default" >{{ trans('language_changer.dont_show_again'); }}</button></a>
                            <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('language_changer.close') }}</button>
                            <a href="{{ URL::Route('AdminSettingInstallation') }}"><button type="button" class="btn btn-primary">{{ trans('language_changer.change'),' ',trans('language_changer.now') }}</button></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
?>


<!--   summary start -->

<!--New dashboard code starts here-->
    <div class="row">
        <div class="col-md-3 col-sm-12 col-xs-12" >
            <div class="panel panel-primary text-center no-boder bg-color-green" id="box_inline_shadow">
                <div class="panel-body" >
                    <i class="fa fa-5x"><?php
                        /* $show = Icons::find($icon->alias); */
                        $show = Icons::find(Config::get('app.generic_keywords.total_trip'));
                        echo $show->icon_code;
                        ?></i>
                    <h3><?= $completed_rides + $cancelled_rides ?></h3>
                </div>
                <div class="panel-footer back-footer-green" id="box_panel_footer" >
                    <span>{{trans('language_changer.total'),' ',trans('language_changer.trip'),' ',trans('language_changer.s') }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-12 col-xs-12" >
            <div class="panel panel-primary text-center no-boder bg-color-green" id="box_inline_shadow">
                <div class="panel-body" >
                    <i class="fa fa-5x"><?php
                        /* $show = Icons::find($icon->alias); */
                        $show = Icons::find(Config::get('app.generic_keywords.completed_trip'));
                        echo $show->icon_code;
                        ?></i>
                    <h3><?= $completed_rides ?></h3>
                </div>
                <div class="panel-footer back-footer-green" id="box_panel_footer" >
                    <span>  {{trans('language_changer.completed'),' ',trans('language_changer.service')}}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-12 col-xs-12" >
            <div class="panel panel-primary text-center no-boder bg-color-green" id="box_inline_shadow">
                <div class="panel-body">
                    <i class="fa fa-5x"><?php
                        /* $show = Icons::find($icon->alias); */
                        $show = Icons::find(Config::get('app.generic_keywords.cancelled_trip'));
                        echo $show->icon_code;
                        ?></i>
                    <h3> <?= $cancelled_rides ?></h3>
                </div>
                <div class="panel-footer back-footer-green" id="box_panel_footer" >
                    <span> {{ trans('language_changer.cancelled'),' ',trans('language_changer.service'),' ',trans('language_changer.s') }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-12 col-xs-12" >
            <div class="panel panel-primary text-center no-boder bg-color-green" id="box_inline_shadow">
                <div class="panel-body">
                    <i class="fa fa-5x"><?php
                        /* $show = Icons::find($icon->alias); */
                        $show = Icons::find(Config::get('app.generic_keywords.total_payment'));
                        echo $show->icon_code;
                        ?></i>
                    <h3> <?= $currency_sel ?> <?= sprintf2(($credit_payment + $card_payment + $cash_payment), 2) ?> </h3>
                </div>
                <div class="panel-footer back-footer-green" id="box_panel_footer" >
                    <span> {{trans('language_changer.total'),' ',trans('language_changer.payment')}} </span>
                </div>
            </div>
        </div>


        <div class="col-md-3 col-sm-12 col-xs-12" >
            <div class="panel panel-primary text-center no-boder bg-color-green" id="box_inline_shadow">
                <div class="panel-body">
                    <i class="fa fa-5x"><?php
                        /* $show = Icons::find($icon->alias); */
                        $show = Icons::find(Config::get('app.generic_keywords.card_payment'));
                        echo $show->icon_code;
                        ?></i>
                    <h3> <?= $currency_sel ?> <?= sprintf2($card_payment, 2) ?> </h3>
                </div>
                <div class="panel-footer back-footer-green" id="box_panel_footer">
                    <span> {{trans('language_changer.card'),' ',trans('language_changer.payment')}}</span>
                </div>
            </div>
        </div>

        {{--now--}}
        <div class="col-md-3 col-sm-12 col-xs-12" style="color: white;border: none;">
            <div class="panel panel-primary text-center no-boder bg-color-green" id="box_inline_shadow">
                <div class="panel-body">
                    <i class="fa fa-5x"><?php
                        /* $show = Icons::find($icon->alias); */
                        $show = Icons::find(Config::get('app.generic_keywords.total_payment'));
                        echo $show->icon_code;
                        ?></i>
                    <h3> <?= $currency_sel ?> <?= sprintf2(($cash_payment), 2) ?> </h3>
                </div>
                <div class="panel-footer back-footer-green" id="box_panel_footer">
                    <span> {{trans('language_changer.cash'),' ',trans('language_changer.payment')}} </span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-12 col-xs-12" style="color: white;border: none;">
            <div class="panel panel-primary text-center no-boder bg-color-green" id="box_inline_shadow">
                <div class="panel-body">
                    <i class="fa fa-5x"><?php
                        /* $show = Icons::find($icon->alias); */
                        $show = Icons::find(Config::get('app.generic_keywords.credit_payment'));
                        echo $show->icon_code;
                        ?></i>
                    <h3>  <?= $currency_sel ?> <?= sprintf2($credit_payment, 2) ?> </h3>
                </div>
                <div class="panel-footer back-footer-green" id="box_panel_footer">
                    <span>{{trans('language_changer.credit'),' ',trans('language_changer.payment')}}</span>
                </div>
            </div>
        </div>

    </div>


<!--New dashboard code ends here-->


<!-- filter start -->

<div class="box box-danger" >
    <div class="box-header">
        <h3  class="box-title" style="float:<?php echo $align_format; ?>" >{{trans('language_changer.filter');}}</h3>
    </div>
    <div class="box-body" >
        <div class="row" dir="{{ trans('language_changer.text_format') }}">
            <form role="form" method="get" action="{{ URL::Route('AdminReport') }}">

                <div class="col-md-6 col-sm-6 col-lg-6" style="float:<?php echo $align_format; ?>">
                    <input type="text"  class="form-control" style="overflow:hidden;" id="start-date" name="start_date" value="{{ Input::get('start_date') }}" placeholder="{{trans('language_changer.start'),' ',trans('language_changer.date')}}">
                    <br>
                </div>

                <div class="col-md-6 col-sm-6 col-lg-6">
                    <input type="text" class="form-control" style="overflow:hidden;" id="end-date" name="end_date" placeholder="{{trans('language_changer.end'),' ',trans('language_changer.date')}}"  value="{{ Input::get('end_date') }}">
                    <br>
                </div>

                <div class="col-md-4 col-sm-4 col-lg-4">

                    <select name="status"  class="form-control">
                        <option value="0">{{trans('language_changer.status') }}</option>
                        <option value="1" <?php echo Input::get('status') == 1 ? "selected" : "" ?> >{{trans('language_changer.completed')}}</option>
                        <option value="2" <?php echo Input::get('status') == 2 ? "selected" : "" ?>>{{trans('language_changer.cancelled')}}</option>
                    </select>
                    <br>
                </div>

                <div class="col-md-4 col-sm-4 col-lg-4">

                    <select name="walker_id" style="overflow:hidden;" class="form-control">
                        <option value="0">{{trans('language_changer.providers')}}</option>
                        <?php foreach ($walkers as $walker) { ?>
                            <option value="<?= $walker->id ?>" <?php echo Input::get('walker_id') == $walker->id ? "selected" : "" ?>><?= $walker->first_name; ?> <?= $walker->last_name ?></option>
                        <?php } ?>
                    </select>
                    <br>
                </div>

                <div class="col-md-4 col-sm-4 col-lg-4">

                    <select name="owner_id" style="overflow:hidden;" class="form-control">
                        <option value="0">{{trans('language_changer.User')}}</option>
                        <?php foreach ($owners as $owner) { ?>
                            <option value="<?= $owner->id ?>" <?php echo Input::get('owner_id') == $owner->id ? "selected" : "" ?>><?= $owner->first_name; ?> <?= $owner->last_name ?></option>
                        <?php } ?>
                    </select>
                    <br>
                </div>


            <div class="box-footer">
                    <button type="submit" name="submit" class="btn btn-flat btn-block btn-success board_btn" value="Filter_Data">{{trans('language_changer.filter'),' ',trans('language_changer.data')}}</button>
                    <button type="submit" name="submit" class="btn btn-flat btn-block btn-success board_btn" value="Download_Report">{{trans('language_changer.download').' ',trans('language_changer.report')}}</button>
            </div>
                </form>
        </div>
        </div>
</div>

<!-- filter end-->




<div class="box box-info tbl-box" dir="{{ trans('language_changer.text_format') }}">
    <div align="{{ trans('language_changer.text_format') }}" id="paglink" ><?php
        $t1=urldecode($walks->appends(array('type'=>Session::get('type')))->links());

        echo $t1;

        ?></div>
    <table class="table table-bordered">
        <tbody><tr>
                <th>{{ trans('language_changer.Request'),' ',trans('language_changer.id') }}</th>
                <th>{{ trans('language_changer.User'),' ',trans('language_changer.name')}} </th>
                <th>{{ trans('language_changer.Provider');}}</th>
                <th>{{ trans('language_changer.date') }}</th>
                <th>{{ trans('language_changer.time') }}</th>
                <th>{{ trans ('language_changer.status') }}</th>
                <th>{{ trans('language_changer.amount') }}</th>
                <th>{{ trans('language_changer.payment'),' ',trans('language_changer.status')}}</th>
                <th>{{ trans('language_changer.referral'),' ',trans('language_changer.bonus') }}</th>
                <th>{{ trans('language_changer.promotional'),' ',trans('language_changer.bonus') }}</th>
                <th>{{ trans('language_changer.card'),' ',trans('language_changer.payment') }}</th>
                <th>{{ trans('language_changer.cash'),' ',trans('language_changer.payment') }}</th>
            </tr>


            <?php foreach ($walks as $walk) { ?>

                <tr>
                    <td><?= $walk->id ?></td>

                    <td><?php echo $walk->owner_first_name . " " . $walk->owner_last_name; ?> </td>
                    <td>
                        <?php
                        if ($walk->confirmed_walker) {
                            echo $walk->walker_first_name . " " . $walk->walker_last_name;
                        } else {
                            echo "Un Assigned";
                        }
                        ?>
                    </td>
                    <td><?php echo date("d M Y", strtotime($walk->date)); ?></td>
                    <td><?php echo date("g:iA", strtotime($walk->date)); ?></td>

                    <td>
                        <?php
                        if ($walk->is_cancelled == 1) {

                            echo "<span class='badge bg-red'>".trans('language_changer.cancelled')."</span>";
                        } elseif ($walk->is_completed == 1) {
                            echo "<span class='badge bg-green'>".trans('language_changer.completed')."</span>";
                        } elseif ($walk->is_started == 1) {
                            echo "<span class='badge bg-yellow'>".trans('language_changer.started')."</span>";
                        } elseif ($walk->is_walker_arrived == 1) {
                            echo "<span class='badge bg-yellow'>".trans('language_changer.walker').' '.trans('language_changer.arrived')."</span>";
                        } elseif ($walk->is_walker_started == 1) {
                            echo "<span class='badge bg-yellow'>".trans('language_changer.walker').' '.trans('language_changer.started')."</span>";
                        } else {
                            
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo sprintf2($walk->total, 2); ?>
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
                        <?= sprintf2($walk->ledger_payment, 2); ?>
                    </td>
                    <td>
                        <?= sprintf2($walk->promo_payment, 2); ?>
                    </td>
                    <?php if ($walk->payment_mode == 1) { ?>
                        <td>
                            <?= sprintf2(0, 2); ?>
                        </td>
                    <?php } else { ?>
                        <td>
                            <?= sprintf2($walk->card_payment, 2); ?>
                        </td>
                        <?php
                    }
                    if ($walk->payment_mode == 1) {
                        ?>
                        <td>
                            <?= sprintf2($walk->card_payment, 2); ?>
                        </td>
                    <?php } else { ?>
                        <td>
                            <?= sprintf2(0, 2); ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>

        </tbody>
    </table>
    <div align="{{ trans('language_changer.text_format') }}" id="paglink"><?php
        $t1=urldecode($walks->appends(array('type'=>Session::get('type')))->links());
        echo $t1;
        ?></div>
</div>
<!--</form>-->


<script>
    $(function () {
        $("#start-date").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function (selectedDate) {
                $("#end-date").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#end-date").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function (selectedDate) {
                $("#start-date").datepicker("option", "maxDate", selectedDate);
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#myModal").modal('show');
    });




</script>

@stop