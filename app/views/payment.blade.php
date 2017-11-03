@extends('layout')

@section('content')
<!--   summary start -->

<style>

    .inner{
        <?php if($align_format == 'right'){ ?>
                    text-align: right;
    <?php } ?>

}

    .icon{
        <?php if($align_format == 'right'){ ?>
                 left: 0;
        bottom: 24px !important;
        padding-left: 24px;"

    <?php  } ?>


}


</style>



<div class="row">
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    <?= $currency_sel ?> <?= sprintf2(($credit_payment + $card_payment + $cash_payment), 2) ?>
                </h3>
                <p>
                    {{ trans('language_changer.total'),' ',trans('language_changer.payment') }}

                </p>
            </div>
            <div class="icon">
                <?php /* $icon = Keywords::where('keyword', 'total_payment')->first(); */ ?>
                <i class="fa"><?php
                    /* $show = Icons::find($icon->alias); */
                    $show = Icons::find(Config::get('app.generic_keywords.total_payment'));
                    echo $show->icon_code;
                    ?></i>
            </div>

        </div>
    </div><!-- ./col -->
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>
                    <?= $currency_sel ?> <?= sprintf2($card_payment, 2) ?>
                </h3>
                <p>
                    <?= $payment_default ?>   {{ trans('language_changer.payment') }}
                </p>
            </div>
            <div class="icon">
                <?php /* $icon = Keywords::where('keyword', 'card_payment')->first(); */ ?>
                <i class="fa"><?php
                    /* $show = Icons::find($icon->alias); */
                    $show = Icons::find(Config::get('app.generic_keywords.card_payment'));
                    echo $show->icon_code;
                    ?></i>
            </div>

        </div>
    </div><!-- ./col -->
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-blue">
            <div class="inner">
                <h3>
                    <?= $currency_sel ?> <?= sprintf2($credit_payment, 2) ?>
                </h3>
                <p>
                    {{ trans('language_changer.credit'),' ',trans('language_changer.payment') }}
                </p>
            </div>
            <div class="icon">
                <?php /* $icon = Keywords::where('keyword', 'credit_payment')->first(); */ ?>
                <i class="fa"><?php
                    /* $show = Icons::find($icon->alias); */
                    $show = Icons::find(Config::get('app.generic_keywords.credit_payment'));
                    echo $show->icon_code;
                    ?></i>
            </div>

        </div>
    </div><!-- ./col -->
</div>
<!--  Summary end -->
<!-- filter start -->
<div class="box box-danger">
    <div class="box-header">
        <h3 class="box-title">{{ trans('language_changer.filter') }}</h3>
    </div>
    <div class="box-body" dir="{{ trans('language_changer.text_format') }}">
        <div class="row">

            <form role="form" method="get" action="{{ URL::Route('AdminPayment') }}">

                <div class="col-md-6 col-sm-6 col-lg-6">
                    <input type="text" class="form-control" style="overflow:hidden;" id="start-date" name="start_date" value="{{ Input::get('start_date') }}" placeholder="{{ trans('language_changer.start'),' ',trans('language_changer.date') }}">
                    <br>
                </div>

                <div class="col-md-6 col-sm-6 col-lg-6">
                    <input type="text" class="form-control" style="overflow:hidden;" id="end-date" name="end_date" placeholder="{{ trans('language_changer.end'),' ',trans('language_changer.date') }}"  value="{{ Input::get('end_date') }}">
                    <br>
                </div>

                <div class="col-md-4 col-sm-4 col-lg-4">

                    <select name="status"  class="form-control">
                        <option value="0">{{ trans('language_changer.status') }}</option>
                        <option value="1" <?php echo Input::get('status') == 1 ? "selected" : "" ?> >{{ trans('language_changer.completed') }}</option>
                        <option value="2" <?php echo Input::get('status') == 2 ? "selected" : "" ?>>{{ trans('language_changer.cancelled') }}</option>
                    </select>
                    <br>
                </div>

                <div class="col-md-4 col-sm-4 col-lg-4">

                    <select name="walker_id" style="overflow:hidden;" class="form-control">
                        <option value="0">{{trans('language_changer.providers') }}</option>
                        <?php foreach ($walkers as $walker) { ?>
                            <option value="<?= $walker->id ?>" <?php echo Input::get('walker_id') == $walker->id ? "selected" : "" ?>><?= $walker->first_name; ?> <?= $walker->last_name ?></option>
                        <?php } ?>
                    </select>
                    <br>
                </div>

                <div class="col-md-4 col-sm-4 col-lg-4">

                    <select name="owner_id" style="overflow:hidden;" class="form-control">
                        <option value="0">{{ trans('language_changer.User') }}</option>
                        <?php foreach ($owners as $owner) { ?>
                            <option value="<?= $owner->id ?>" <?php echo Input::get('owner_id') == $owner->id ? "selected" : "" ?>><?= $owner->first_name; ?> <?= $owner->last_name ?></option>
                        <?php } ?>
                    </select>
                    <br>
                </div>


        </div>
    </div><!-- /.box-body -->
    <div class="box-footer" style="float:<?php echo $align_format; ?>" >
        <button type="submit" name="submit" class="btn btn-primary" value="Filter_Data" >{{ trans('language_changer.filter'),' ',trans('language_changer.data') }}</button>
        <button type="submit" name="submit" class="btn btn-primary" value="Download_Report" >{{ trans('language_changer.download'),' ',trans('language_changer.report') }}</button>
    </div>

</form>

</div>

<!-- filter end-->




<div class="box box-info tbl-box" >
    <div align="left" id="paglink" style="float:<?php echo $align_format; ?>"><?php
        $t1=urldecode($walks->appends(array('type'=>Session::get('type')))->links());
        echo $t1;
        ?></div>
    <table class="table table-bordered" dir="{{ trans('language_changer.text_format') }}">
        <tbody><tr>
                <th>{{ trans('language_changer.Request'),' ',trans('language_changer.id') }}</th>
                <th>{{ trans('language_changer.owner'),' ',trans('language_changer.name') }}</th>
                <th>{{ trans ('language_changer.providers') }}</th>
                <th>{{ trans ('language_changer.status') }}</th>
                <th>{{ trans('language_changer.amount') }}</th>
                <th>{{ trans('language_changer.payment'),' ',trans('language_changer.status')}}</th>
                <th>{{ trans('language_changer.payment'),' ',trans('language_changer.mode')}}</th>
                 <th>{{ trans('language_changer.ledger'), ' ',trans('language_changer.payment')}}</th>
                <th><?= $payment_default ?>{{" ",trans('language_changer.payment') }}</th>
                <th>{{ trans('language_changer.promo_discount') }}</th>
                <th>{{  trans('language_changer.action')}}</th>

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

                    <td>
                        <?php
                        if ($walk->is_cancelled == 1) {

                            echo "<span class='badge bg-red'>". trans('language_changer.cancelled')."</span>";
                        } elseif ($walk->is_completed == 1) {
                            echo "<span class='badge bg-green'>". trans('language_changer.completed')."</span>";
                        } elseif ($walk->is_started == 1) {
                            echo "<span class='badge bg-yellow'>". trans('language_changer.start')."ed"."</span>";
                        } elseif ($walk->is_walker_arrived == 1) {
                            echo "<span class='badge bg-yellow'>".trans('language_changer.walker').' '.trans('language_changer.arrived')."</span>";
                        } elseif ($walk->is_walker_started == 1) {
                            echo "<span class='badge bg-yellow'>".trans('language_changer.walker').' '.trans('language_changer.start')."ed"."</span>";
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
                        <?php
                        if ($walk->payment_mode == 0) {
                            echo $payment_default;
                        } elseif ($walk->payment_mode == 1) {
                            echo "Cash";
                        } elseif ($walk->payment_mode == 2) {
                            echo "Paypal>";
                        }
                        ?>
                    </td>
                    <td>
                        <?= sprintf2($walk->ledger_payment, 2); ?>
                    </td>
                    <td>
                        <?= sprintf2($walk->card_payment, 2); ?>
                    </td>
                    <td>
                        <?php
                        if ($walk->promo_id !== NULL) {
                            $promo = PromoCodes::where('id', $walk->promo_id)->first();
                            if ($promo) {
                                if ($promo->type == 2) {
                                    echo sprintf2($promo->value, 2);
                                } elseif ($promo->type == 1) {
                                    echo sprintf2(($promo->value * $walk->total / 100), 2);
                                } else {
                                    echo "<span class='badge bg-red'>" . Config::get('app.blank_fiend_val') . "</span>";
                                }
                            } else {
                                echo "<span class='badge bg-red'>" . Config::get('app.blank_fiend_val') . "</span>";
                            }
                        } else {
                            echo "<span class='badge bg-red'>" . Config::get('app.blank_fiend_val') . "</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-flat btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                             {{  trans('language_changer.action')}}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" <?php echo trans('language_changer.text_format')=="rtl"?'style="left:0;right:auto;"':""; ?> role="menu" aria-labelledby="dropdownMenu1">

                                <li role="presentation"><a role="menuitem" id="map" tabindex="-1" href="{{ URL::Route('AdminRequestsMap', $walk->id) }}">{{ trans('language_changer.view_map') }}</a></li>
                                @if($walk->is_paid==0 && $walk->is_completed==1 && $walk->payment_mode!=1 && $walk->total!=0)
                                <li role="presentation"><a role="menuitem" id="map" tabindex="-1" href="{{ URL::Route('AdminChargeUser', $walk->id) }}">{{ trans('language_changer.charge'),' ',trans('language_changer.User') }}</a></li>
                                @endif

                                <!--
                                <li role="presentation" class="divider"></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo web_url(); ?>/admin/walk/delete/<?= $walk->id; ?>">Delete Walk</a></li>
                                -->
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
    <div align="left" id="paglink" style="float:<?php echo $align_format; ?>"><?php
        $t1=urldecode($walks->appends(array('type'=>Session::get('type')))->links());
        echo $t1;


        ?></div>
</div>
<!--</form>-->
</div>
</div>
</div>

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