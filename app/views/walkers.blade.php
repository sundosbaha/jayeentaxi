@extends('layout')

@section('content')

<!--<div class="row">
    <div class="col-md-12 col-sm-12">
        <a id="addpro" href="{{ URL::Route('AdminProviderAdd') }}"><button class="btn btn-flat btn-block btn-info" type="button">Add Provider</button></a>
        <br/>
    </div>
</div>-->
@if (Session::has('message'))
<div class="alert alert-danger">{{ Session::get('message') }}</div>
@endif

<div class="col-md-6 col-sm-12">
    <div class="box box-danger">
        <form method="get" action="{{ URL::Route('/admin/sortpv') }}">
            <div class="box-header">
                <h3 class="box-title" style="float:<?php echo $align_format; ?>" >{{ trans('language_changer.sort'); }}</h3>
            </div>
            <div class="box-body row" dir="{{ trans('language_changer.text_format') }}" >
                <div class="col-md-6 col-sm-12" style="float:<?php echo $align_format; ?>">
                    <select class="form-control" id="sortdrop" name="type">
                        <option value="provid" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'provid') {
                            echo 'selected="selected"';
                        }
                        ?> id="provid">{{ trans('language_changer.Provider'),' ',trans('language_changer.id')}}</option>
                        <option value="pvname" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'pvname') {
                            echo 'selected="selected"';
                        }
                        ?> id="pvname">{{ trans('language_changer.Provider'),' ',trans('language_changer.name') }} </option>
                        <option value="pvemail" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'pvemail') {
                            echo 'selected="selected"';
                        }
                        ?> id="pvemail">{{ trans('language_changer.Provider'),' ',trans('language_changer.email') }}</option>
                        <option value="pvaddress" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'pvaddress') {
                            echo 'selected="selected"';
                        }
                        ?>  id="pvaddress">{{ trans('language_changer.Provider'),' ',trans('language_changer.address') }} </option>
                    </select>
                    <br>
                </div>
                <div class="col-md-6 col-sm-12">
                    <select class="form-control" id="sortdroporder" name="valu">
                        <option value="asc" <?php
                        if (isset($_GET['valu']) && $_GET['valu'] == 'asc') {
                            echo 'selected="selected"';
                        }
                        ?> selected id="asc">{{ trans('language_changer.ascending') }}</option>
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
                <button type="submit" id="btnsort" class="btn btn-flat btn-block btn-success" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.sort'); }}</button>
                <button type="submit" id="btnsort" name="submit" class="btn btn-flat btn-block btn-success" value="Download_Report">{{ trans('language_changer.download'),' ',trans('language_changer.report') }}</button>
            </div>
        </form>
    </div>
</div>

<div class="col-md-6 col-sm-12">

    <div class="box box-danger">

        <form method="get" action="{{ URL::Route('/admin/searchpv') }}">
            <div class="box-header" >
                <h3 class="box-title" style="float:<?php echo $align_format; ?>" >{{ trans('language_changer.filter') }}</h3>
            </div>
            <div class="box-body row" dir="{{ trans('language_changer.text_format') }}">

                <div class="col-md-6 col-sm-12" style="float:<?php echo $align_format; ?>">
                    <select class="form-control" id="sortdrop" name="filter_type" >
                        <option value="provid" <?php
                        if (isset($_GET['filter_type']) && $_GET['filter_type'] == 'provid') {
                            echo 'selected="selected"';
                        }
                        ?> id="provid"> {{ trans('language_changer.Provider'),' ',trans('language_changer.id')}}</option>
                        <option value="pvname" <?php
                        if (isset($_GET['filter_type']) && $_GET['filter_type'] == 'pvname') {
                            echo 'selected="selected"';
                        }
                        ?> id="pvname">{{ trans('language_changer.Provider'),' ',trans('language_changer.name') }}</option>
                        <option value="pvemail" <?php
                        if (isset($_GET['filter_type']) && $_GET['filter_type'] == 'pvemail') {
                            echo 'selected="selected"';
                        }
                        ?> id="pvemail">{{ trans('language_changer.Provider'),' ',trans('language_changer.email') }}</option>
                        <option value="pvaddress" <?php
                        if (isset($_GET['filter_type']) && $_GET['filter_type'] == 'pvaddress') {
                            echo 'selected="selected"';
                        }
                        ?>  id="pvaddress"> {{ trans('language_changer.Provider'),' ',trans('language_changer.address') }}</option>
                    </select>
                    <br>
                </div>
                <div class="col-md-6 col-sm-12" >
                    <input class="form-control" type="text" name="filter_valu" value="<?php echo (!empty($_GET['filter_valu']) ? $_GET['filter_valu'] : '');
                    ?>" id="insearch" placeholder="{{ trans('language_changer.keyword') }}"/>
                    <br>
                </div>

            </div>

            <div class="box-footer" >

                <button  type="submit" id="btnsearch" style="float:<?php echo $align_format; ?>" class="btn btn-flat btn-block btn-success">{{ trans('language_changer.search') }}</button>
                <button type="submit" id="btnsearch" name="submit" class="btn btn-flat btn-block btn-success" value="Download_Report">{{ trans('language_changer.download'),' ',trans('language_changer.report') }}</button>
            </div>
        </form>

    </div>
</div>
<div class="col-md-12 col-sm-12">
    <?php if (Session::get('che')) { ?>
        <a id="providers" href="{{ URL::Route('AdminProviders') }}"><button class="col-md-12 col-sm-12 btn btn-warning" type="button"> {{trans('language_changer.Provider').' '. trans('language_changer.Provider').' '.trans('language_changer.s');}}</button></a><br/>
    <?php } else { ?>
        <a id="currently" href="{{ URL::Route('AdminProviderCurrent') }}"><button class="col-md-12 col-sm-12 btn btn-flat btn-block  btn-warning"  type="button">{{ trans('language_changer.currently_providing') }}</button></a><br/>
    <?php } ?>
    <br><br>
</div>
<div class="box box-info tbl-box">
    <div align="left" id="paglink"><?php echo $walkers->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>
    <table class="table table-bordered" dir="{{ trans('language_changer.text_format') }}">
        <tbody><tr>
                <th>{{ strtoupper(trans('language_changer.id')); }}</th>
                <th>{{ trans('language_changer.name') }}</th>
                <th>{{ trans('language_changer.email') }}</th>
                <th>{{ trans('language_changer.phone') }}</th>
                <th>{{ trans('language_changer.photo') }}</th>
                <th>{{ trans('language_changer.bio') }}</th>
                <th>{{ trans('language_changer.total'),' ',trans('language_changer.Request') }}</th>
                <th>{{ trans('language_changer.acceptance'),' ',trans('language_changer.rate') }}</th>
                <th>{{ trans('language_changer.status') }}</th>
                <th>{{ trans('language_changer.actions') }}</th>
            </tr>

            <?php foreach ($walkers as $walker) { ?>
                <tr>
                    <td><?= $walker->id ?></td>
                    <td><?php echo $walker->first_name . " " . $walker->last_name; ?> </td>
                    <td><?php echo $walker->email; ?></td>
                    <td><?php echo $walker->phone; ?>
                    <!--{{--</td><td><?php $half=explode('@',$walker->email); echo "xxxx@".end($half); ?></td>
                    <td><?php $first=substr($walker->phone,0,4); echo $first."xxxxx"; ?></td>--}}-->
                    <td><a href="<?php echo $walker->picture; ?>" target="_blank" onclick="window.open('<?php echo $walker->picture; ?>', 'popup', 'height=500px, width=400px'); return false;">{{ trans('language_changer.view_photo') }}</a></td>
                    <td>
                        <?php
                        if ($walker->bio) {
                            echo $walker->bio;
                        } else {
                            echo "<span class='badge bg-red'>" . Config::get('app.blank_fiend_val') . "</span>";
                        }
                        ?>
                    </td>
                    <td><?= $walker->total_requests ?></td>
                    <td><?php
                        if ($walker->total_requests != 0) {
                            echo round(($walker->accepted_requests / $walker->total_requests) * 100, 2);
                        } else {
                            echo 0;
                        }
                        ?> %</td>
                    <td><?php
                        if ($walker->is_approved == 1) {
                            echo "<span class='badge bg-green'>" .  trans('language_changer.approve').'d' ."</span>";
                        } else {
                            echo "<span class='badge bg-red'>" . trans('language_changer.pending') . "</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-flat btn-info dropdown-toggle" type="button" id="dropdownMenu1" name="action" data-toggle="dropdown">
                                {{ trans('language_changer.actions') }}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" <?php echo trans('language_changer.text_format')=="rtl"?'style="left:0;right:auto;"':""; ?> role="menu" aria-labelledby="dropdownMenu1">
                                <li role="presentation"><a role="menuitem" id="editDetail" tabindex="-1" href="{{ URL::Route('AdminProviderEdit', $walker->id) }}">{{ trans('language_changer.edit'),' ',trans('language_changer.details') }}</a></li>
                                <?php if ($walker->merchant_id == NULL && (Config::get('app.generic_keywords.Currency') == '$' || Config::get('app.default_payment') != 'stripe')) {
                                    ?>
                                   <!-- <li role="presentation"><a id="addbank" role="menuitem" tabindex="-1" href="{{ URL::Route('AdminProviderBanking', $walker->id) }}">Add Banking Details</a></li>-->
                                <?php } ?>
                                <li role="presentation"><a role="menuitem" id="history" tabindex="-1" href="{{ URL::Route('AdminProviderHistory', $walker->id) }}">{{ trans('language_changer.view'), ' ', trans('language_changer.history') }}</a></li>

                                <?php if ($walker->is_approved == 0) { ?>
                                    <li role="presentation"><a role="menuitem" id="approve" tabindex="-1" href="{{ URL::Route('AdminProviderApprove', $walker->id) }}">{{ trans('language_changer.approve') }}</a></li>
                                <?php } else { ?>
                                    <li role="presentation"><a role="menuitem" id="decline" tabindex="-1" href="{{ URL::Route('AdminProviderDecline', $walker->id) }}">{{ trans('language_changer.decline') }}</a></li>
                                    <li role="presentation"><a role="menuitem" id="decline" tabindex="-1" href="{{ URL::Route('AdminProviderDelete', $walker->id) }}">{{ trans('language_changer.delete') }}</a></li>

                                <?php } ?>
                                <?php
                                /* $settng = Settings::where('key', 'allow_calendar')->first();
                                  if ($settng->value == 1) { */
                                ?>
                                <!--<li role="presentation"><a role="menuitem" id="avail" tabindex="-1" href="{{ URL::Route('AdminProviderAvailability', $walker->id) }}">View Calendar</a></li>-->
                                <?php /* } */ ?>
                                <?php
                                $walker_doc = WalkerDocument::where('walker_id', $walker->id)->first();
                                if ($walker_doc != NULL) {
                                    ?>
                                    <li role="presentation"><a id="view_walker_doc" role="menuitem" tabindex="-1" href="{{ URL::Route('AdminViewProviderDoc', $walker->id) }}">{{ trans('view'),' ',trans('documents') }}</a></li>
                                <?php } else { ?>
                                    <li role="presentation"><a id="view_walker_doc" role="menuitem" tabindex="-1" href="#"><span class='badge bg-red'>{{ trans('language_changer.no'),' ',trans('language_changer.documents') }}</span></a></li>
                                <?php } ?>
                                <!--<li role="presentation"><a role="menuitem" id="history" tabindex="-1" href="{{ web_url().'/admin/provider/documents/'.$walker->id }}">View Documents</a></li>-->
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody></table>
    <div align="left" id="paglink"><?php echo $walkers->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>
</div>



@stop

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