@extends('layout')

@section('content')
@if(Session::has('msg'))
<div class="alert alert-success"><b><?php
        echo Session::get('msg');
        Session::put('msg', NULL);
        ?></b></div>
@endif
<div class="col-md-6 col-sm-12">

    <div class="box box-danger">

        <form method="get" action="{{ URL::Route('/admin/sortur') }}">
            <div class="box-header">
                <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.sort') }}</h3>
            </div>
            <div class="box-body row" dir="{{ trans('language_changer.text_format') }}">

                <div class="col-md-6 col-sm-12" style="float:<?php echo $align_format; ?>">


                    <select id="sortdrop" class="form-control" name="type">
                        <option value="userid" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'userid') {
                            echo 'selected="selected"';
                        }
                        ?> id="provid">{{ trans('language_changer.User'),' ',strtoupper(trans('language_changer.id'))}} </option>
                        <option value="username" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'username') {
                            echo 'selected="selected"';
                        }
                        ?> id="pvname">{{ trans('language_changer.User'),' ',trans('language_changer.name')}}</option>
                        <option value="useremail" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'useremail') {
                            echo 'selected="selected"';
                        }
                        ?> id="pvemail">{{ trans('language_changer.User'),' ',trans('language_changer.email')}}</option>
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


                <button type="submit" id="btnsort" class="btn btn-flat btn-block btn-success" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.sort') }}</button>
                <button type="submit" id="btnsort" name="submit" class="btn btn-flat btn-block btn-success" value="Download_Report">{{ trans('language_changer.download'),' ',trans('language_changer.report') }}</button>
            </div>
        </form>

    </div>
</div>


<div class="col-md-6 col-sm-12">

    <div class="box box-danger">

        <form method="get" action="{{ URL::Route('/admin/searchur') }}">
            <div class="box-header"  >
                <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.filter') }}</h3>
            </div>
            <div class="box-body row" dir="{{ trans('language_changer.text_format') }}">

                <div class="col-md-6 col-sm-12" style="float:<?php echo $align_format; ?>">

                    <select class="form-control" id="searchdrop" name="filter_type">
                        <option value="userid" id="userid" <?php
                                if (isset($_GET['filter_type']) && $_GET['filter_type'] == 'userid') {
                                    echo 'selected="selected"';
                                }
                                ?>>{{ trans('language_changer.User'),' ',strtoupper(trans('language_changer.id'))}}</option>
                        <option value="username" id="username" <?php
                                if (isset($_GET['filter_type']) && $_GET['filter_type'] == 'username') {
                                    echo 'selected="selected"';
                                }
                                ?>>{{ trans('language_changer.User'),' ',trans('language_changer.name')}}</option>
                        <option value="useremail" id="useremail" <?php
                                if (isset($_GET['filter_type']) && $_GET['filter_type'] == 'useremail') {
                                    echo 'selected="selected"';
                                }
                                ?>>{{ trans('language_changer.User'),' ',trans('language_changer.email')}}</option>
                        <option value="userphone" id="userphone" <?php
                                if (isset($_GET['filter_type']) && $_GET['filter_type'] == 'userphone') {
                                    echo 'selected="selected"';
                                }
                                ?>>{{ trans('language_changer.User'),' ',trans('language_changer.phone')}}</option>

                        <option value="useraddress" id="useraddress" <?php
                                if (isset($_GET['filter_type']) && $_GET['filter_type'] == 'useraddress') {
                                    echo 'selected="selected"';
                                }
                                ?>>{{ trans('language_changer.User'),' ',trans('language_changer.address')}}</option>
                    </select>


                    <br>
                </div>
                <div class="col-md-6 col-sm-12">
                    <input class="form-control" type="text" name="filter_valu" value="<?php echo (!empty($_GET['filter_valu']) ? $_GET['filter_valu'] : '');?>" id="insearch" placeholder="{{ trans('language_changer.keyword')}}"/>
                    <br>
                </div>

            </div>

            <div class="box-footer" >

                <button type="submit" id="btnsearch" class="btn btn-flat btn-block btn-success" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.search') }}</button>
                <button type="submit" id="btnsearch" name="submit" class="btn btn-flat btn-block btn-success" value="Download_Report">{{ trans('language_changer.download'),' ',trans('language_changer.report') }}</button>
           </div>
        </form>

    </div>
</div>



<div class="box box-info tbl-box" >
    <div align="<?php echo $align_format ?>" id="paglink"><?php echo $owners->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>
    <table class="table table-bordered" dir="{{ trans('language_changer.text_format') }}">
        <tbody>
            <tr>
                <th>{{ strtoupper(trans('language_changer.id')); }}</th>
                <th>{{ trans('language_changer.name') }}</th>
                <th>{{ trans('language_changer.email') }}</th>
                <th>{{ trans('language_changer.phone') }}</th>
                <th>{{ trans('language_changer.address') }}</th>
                <th>{{ trans('language_changer.state') }}</th>
                <th>{{ trans('language_changer.zip_Code') }}</th>
                <th>{{ trans('language_changer.debt') }}</th>
                <th>{{ trans('language_changer.reffered_by') }}</th>
                <th>{{ trans('language_changer.actions') }}</th>



            </tr>

            <?php foreach ($owners as $owner) { ?>
                <tr>
                    <td><?= $owner->id ?></td>
                    <td><?php echo $owner->first_name . " " . $owner->last_name; ?> </td>
                    <td><?php echo $owner->email; ?></td>
                    <td><?php  echo $owner->phone; ?></td>
                  <!-- {{-- <td><?php $half=explode('@',$owner->email); echo "xxxx@".end($half);?></td>
                    <td><?php  $first=substr($owner->phone,0,4); echo $first."xxxxx"; ?></td>
                   --}} --><td>
                        <?php
                        if ($owner->address) {
                            echo $owner->address;
                        } else {
                            echo "<span class='badge bg-red'>" . Config::get('app.blank_fiend_val') . "</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($owner->state) {
                            echo $owner->state;
                        } else {
                            echo "<span class='badge bg-red'>" . Config::get('app.blank_fiend_val') . "</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($owner->zipcode) {
                            echo $owner->zipcode;
                        } else {
                            echo "<span class='badge bg-red'>" . Config::get('app.blank_fiend_val') . "</span>";
                        }
                        ?>
                    </td>
                    <td><?= sprintf2($owner->debt, 2) ?></td>
                    <?php
                    $refer = Owner::where('id', $owner->referred_by)->first();
                    if ($refer) {
                        $referred = $refer->first_name . " " . $refer->last_name;
                    } else {
                        $referred = "None";
                    }
                    ?>
                    <td><?php echo $referred; ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-flat btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                                {{ trans('language_changer.actions') }}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" <?php echo trans('language_changer.text_format')=="rtl"?'style="left:0;right:auto;"':""; ?> role="menu" aria-labelledby="dropdownMenu1">
                                <li role="presentation"><a role="menuitem" tabindex="-1" id="edit" href="{{ URL::Route('AdminUserEdit', $owner->id) }}">{{trans('language_changer.edit'),' ',trans('language_changer.User') }}</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" id="history" href="{{ URL::Route('AdminUserHistory',$owner->id) }}">{{trans('language_changer.view'),' ',trans('language_changer.history')}}</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" id="coupon" href="{{ URL::Route('AdminUserReferral', $owner->id) }}">{{trans('language_changer.coupon'),' ',trans('language_changer.details')}}</a></li>
            <!--  {{--                  <?php
                                $check = Requests::where('owner_id', '=', $owner->id)->where('is_cancelled', '<>', '1')->get()->count(); //print_r($check);
                                if ($check == 0) {
                                    ?>
                                    <li role="presentation"><a role="menuitem" tabindex="-1" id="add_req" href="{{ URL::Route('AdminAddRequest', $owner->id) }}">{{ trans('language_changer.add_request') }}</a></li>
    <?php } ?>--}}-->
                                <li role="presentation"><a role="menuitem" tabindex="-1" id="add_req" href="{{ URL::Route('AdminDeleteUser', $owner->id) }}">{{ trans('language_changer.delete') }}</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
<?php } ?>
        </tbody>
    </table>

    <div align="<?php echo $align_format ?>" id="paglink"><?php echo $owners->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>




</div>

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