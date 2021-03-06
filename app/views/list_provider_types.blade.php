@extends('layout')

@section('content')

    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif


<a id="addtype" href="{{ URL::Route('AdminProviderTypeEdit', 0) }}" >
    <button type="submit" class="btn btn-flat btn-block btn-success" style="width: 100%;" id="drivetype" value=""> {{trans('language_changer.add').' '.trans('language_changer.new').' '.trans('language_changer.Provider'). ' '.trans('language_changer.type')}}</button></a>


<br>


<div class="col-md-6 col-sm-12">

    <div class="box box-danger">

        <form method="get" action="{{ URL::Route('/admin/sortpvtype') }}">
            <div class="box-header">
                <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.sort') }}</h3>
            </div>
            <div class="box-body row" dir="{{ trans('language_changer.text_format') }}">

                <div class="col-md-6 col-sm-12" style="float:<?php echo $align_format; ?>">
                    <select class="form-control" id="sortdrop" name="type">
                        <option value="provid" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'provid') {
                            echo 'selected="selected"';
                        }
                        ?> id="provid">{{ trans('language_changer.Provider').' '.trans('language_changer.type').' '.trans('language_changer.id')}}</option>
                        <option value="pvname" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'pvname') {
                            echo 'selected="selected"';
                        }
                        ?> id="pvname">{{ trans('language_changer.Provider'),' ',trans('language_changer.id');}}</option>
                    </select>
                    <br>
                </div>
                <div class="col-md-6 col-sm-12">
                    <select class="form-control" id="sortdroporder" name="valu">
                        <option value="asc" <?php
                        if (isset($_GET['valu']) && $_GET['valu'] == 'asc') {
                            echo 'selected="selected"';
                        }
                        ?> id="asc">{{ trans('language_changer.ascending') }}</option>
                        <option value="desc" <?php
                        if (isset($_GET['valu']) && $_GET['valu'] == 'desc') {
                            echo 'selected="selected"';
                        }
                        ?> id="desc">{{ trans('language_changer.descending') }}</option>
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

        <form method="get" action="{{ URL::Route('/admin/searchpvtype') }}">
            <div class="box-header">
                <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.filter') }}</h3>
            </div>
            <div class="box-body row" dir="{{ trans('language_changer.text_format') }}">

                <div class="col-md-6 col-sm-12" style="float:<?php echo $align_format; ?>">

                    <select id="searchdrop" class="form-control" name="type">
                        <option value="provid" id="provid">{{ trans('language_changer.Provider').' '.trans('language_changer.type').' '.trans('language_changer.id')}}</option>
                        <option value="provname" id="provname">{{ trans('language_changer.Provider').' '.trans('language_changer.name')}}</option>
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
    <div align="left" id="paglink"><?php echo $types->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>{{ strtoupper(trans('language_changer.id')) }}</th>
                <th>{{ trans('language_changer.name') }}</th>
                <th>{{ trans('language_changer.base_price'), ' ' , trans('language_changer.distance') }}</th>
                <th>{{ trans('language_changer.base_price') }}</th>
                <th>{{ trans('language_changer.price_per_unit_distance') }}</th>
                <th>{{ trans('language_changer.price_per_unit_time'),'('.trans('language_changer.minutes').')' }}</th>
                <th>{{ trans('language_changer.maximum'),' ', trans('language_changer.space') }}</th>
                <th>{{ trans('language_changer.visibility') }}</th>
                <th>{{ trans('language_changer.actions') }}</th>
            </tr>
            <?php foreach ($types as $type) {
                ?>
                <tr>
                    <td><?= $type->id ?></td>
                    <td><?= $type->name ?>
                        <?php if ($type->is_default) { ?>
                            <font style="color:rgb(15, 131, 15);"><?php echo trans('language_changer.default'); ?></font>
                        <?php } ?>
                    </td>
                    <td><?= $type->base_distance . " " . $unit_set ?></td>
                    <td><?= Config::get('app.generic_keywords.Currency') . " " . sprintf2($type->base_price, 2) ?></td>
                    <td><?= Config::get('app.generic_keywords.Currency') . " " . sprintf2($type->price_per_unit_distance, 2) ?></td>
                    <td><?=  sprintf2($type->price_per_unit_time, 2) ?></td>
                    <td><?= $type->max_size ?></td>
                    <td>
                        <?php
                        if ($type->is_visible == 1) {
                            echo "<span class='badge bg-green'>". trans('language_changer.visible') ."</span>";
                        } else {
                            echo "<span class='badge bg-red'>". trans('language_changer.invisible') ."</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <a href="{{ URL::Route('AdminProviderTypeEdit', $type->id) }}"><input type="button" class="btn btn-success" value="{{ trans('language_changer.edit') }}"></a>
                        <?php /* if (!$type->is_default) { ?>
                          <a href="{{ URL::Route('AdminProviderTypeDelete', $type->id) }}"><input type="button" class="btn btn-danger" value="Delete"></a>
                          <?php } */ ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div align="left" id="paglink"><?php echo $types->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>

</div>





@stop