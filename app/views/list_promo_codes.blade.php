@extends('layout')
@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        <a id="addpromo" href="{{ URL::Route('AdminPromoAdd') }}">
            <button class="btn btn-flat btn-block btn-success" id="newpromo" type="button" style="box-shadow: unset !important; width : 100%;">{{ trans('language_changer.add_promo_code'); }}</button></a>
        <br/>
    </div>
</div>
<div class="col-md-6 col-sm-12">
    <div class="box box-danger">
        <form method="get" action="{{ URL::Route('/admin/sortpromo') }}">
            <div class="box-header">
                <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.sort') }}</h3>
            </div>
             <div class="box-body row" dir="{{ trans('language_changer.text_format') }}">
                <div class="col-md-6 col-sm-12" style="float:<?php echo $align_format; ?>">
                    <select id="sortdrop" class="form-control" name="type">
                        <option value="promoid" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'promoid') {
                            echo 'selected="selected"';
                        }
                        ?> id="promoid">{{ trans('language_changer.promo_code'),' ',strtoupper(trans('language_changer.id')) }}</option>
                        <option value="promo" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'promo') {
                            echo 'selected="selected"';
                        }
                        ?> id="promo">{{ trans('language_changer.promo_code') }}</option>
                        <option value="uses" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'uses') {
                            echo 'selected="selected"';
                        }
                        ?> id="promovalue">{{ trans('language_changer.uses_remaining') }}</option>
                    </select>
                    <br>
                </div>
                <div class="col-md-6 col-sm-12">
                    <select id="sortdroporder" class="form-control" name="valu">
                        <option value="asc" <?php
                        if (isset($_GET['valu']) && $_GET['valu'] == 'asc') {
                            echo 'selected="selected"';
                        }
                        ?> id="asc">{{trans('language_changer.ascending')}}</option>
                        <option value="desc" <?php
                        if (isset($_GET['valu']) && $_GET['valu'] == 'desc') {
                            echo 'selected="selected"';
                        }
                        ?> id="desc">{{trans('language_changer.descending')}}</option>
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
    <div class="box box-danger">
        <form method="get" action="{{ URL::Route('/admin/searchpromo') }}">
            <div class="box-header">
                <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.filter') }}</h3>
            </div>
            <div class="box-body row" dir="{{ trans('language_changer.text_format') }}">

                <div class="col-md-6 col-sm-12" style="float:<?php echo $align_format; ?>">

                    <select class="form-control" id="searchdrop" name="type">
                        <option value="promo_id" id="promo_id">{{ trans('language_changer.promo_code'),' ',strtoupper(trans('language_changer.id')) }}</option>
                        <option value="promo_name" id="promo_name">{{ trans('language_changer.promo_code'),' ',(trans('language_changer.name')) }}</option>
                        <option value="promo_type" id="promo_type">{{ trans('language_changer.promo_code'),' ',(trans('language_changer.type')) }}</option>
                        <option value="promo_state" id="promo_state">{{ trans('language_changer.promo_code_state') }}</option>
                    </select>
                    <br>
                </div>
                <div class="col-md-6 col-sm-12">
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
    <div align="left" id="paglink"><?php echo $promo_codes->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>{{ trans('language_changer.id') }}</th>
                <th>{{ trans('language_changer.promo_code') }}</th>
                <th>{{ trans('language_changer.value') }}</th>
                <th>{{ trans('language_changer.uses_remaining') }}</th>
                <th>{{ trans('language_changer.states') }}</th>
                <th>{{ trans('language_changer.is_expired') }}</th>
                <th>{{ trans('language_changer.start'),' ', trans('language_changer.date') }}</th>
                <th>{{ trans('language_changer.expiry'), ' ', trans('language_changer.date')   }}</th>
                <th style="width: 105px;">{{ trans('language_changer.actions') }}</th>
            </tr>
            <?php foreach ($promo_codes as $promo) { ?>
                <tr>
                    <td><?= $promo->id ?></td>
                    <td><?= $promo->coupon_code ?></td>
                    <td><?php
                        if ($promo->type == 1) {
                            echo $promo->value . " %";
                        } elseif ($promo->type == 2) {
                            echo "$ " . $promo->value;
                        }
                        ?></td>
                    <td><?= $promo->uses ?></td>
                    <td><?php
                        if ($promo->state == 1) {
                            echo trans('language_changer.active');
                        } elseif ($promo->state == 0) {
                            echo trans('language_changer.expiry');
                        } elseif ($promo->state == 2) {
                            echo trans('language_changer.deactivate');
                        } elseif ($promo->state == 3) {
                            echo trans('language_changer.max_limit_reached');
                        }
                        ?></td>
                    <td>
                        <?php
                        if (date("Y-m-d H:i:s") < date("Y-m-d H:i:s", strtotime(trim($promo->start_date)))) {
                            echo "<span class='badge bg-blue'>". trans('language_changer.inactive') ."</span>";
                        } else if (date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime(trim($promo->expiry)))) {
                            echo "<span class='badge bg-red'>". trans('language_changer.expiry')."</span>";
                        } else {
                            echo "<span class='badge bg-green'>". trans('language_changer.active') ."</span>";
                        }
                        ?>
                    </td>
                    <td><?= date("d M Y g:i:s A", strtotime(trim($promo->start_date))) ?></td>
                    <td><?= date("d M Y g:i:s A", strtotime(trim($promo->expiry))) ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-flat btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                                {{ trans('language_changer.actions') }}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" <?php echo trans('language_changer.text_format')=="rtl"?'style="left:0;right:auto;"':""; ?> role="menu" aria-labelledby="dropdownMenu1">
                                <li role="presentation"><a role="menuitem" tabindex="-1" id="edit" href="{{ URL::Route('AdminPromoCodeEdit',$promo->id) }}">{{ trans('language_changer.edit'),' ', trans('language_changer.promo_code') }}</a></li>
                                <?php if ($promo->state == 1) { ?>
                                    <li role="presentation"><a role="menuitem" tabindex="-1" id="edit" href="{{ URL::Route('AdminPromoCodeDeactivate',$promo->id) }}">{{ trans('language_changer.deactivate') }}</a></li>
                                <?php } elseif ($promo->state == 2) { ?>
                                    <li role="presentation"><a role="menuitem" tabindex="-1" id="edit" href="{{ URL::Route('AdminPromoCodeActivate',$promo->id) }}">{{ trans('language_changer.activate') }}</a></li>
                                <?php } ?>
                                <!--li role="presentation"><a role="menuitem" tabindex="-1" id="history" href="">View History</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" id="coupon" href="">Delete</a></li-->
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <div align="left" id="paglink"><?php echo $promo_codes->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>
</div>
<?php
if ($success == 1) {
    ?>
    <script type="text/javascript">
        var msg="<?php trans('language_changer.duplicate_promocode_warning'); ?>";
        alert(msg);


    </script>
<?php } ?>
<?php if ($success == 2) { ?>
    <script type="text/javascript">

        var msg="<?php trans('language_changer.wrong') ?>";

        alert(msg);
    </script>
<?php }
?>
@stop
