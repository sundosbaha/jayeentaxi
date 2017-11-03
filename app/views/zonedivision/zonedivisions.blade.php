@extends('layout')

@section('content')

    {{-- <script src="https://bitbucket.org/pellepim/jstimezonedetect/downloads/jstz-1.0.4.min.js"></script>
     <script src="http://momentjs.com/downloads/moment.min.js"></script>
     <script src="http://momentjs.com/downloads/moment-timezone-with-data.min.js"></script>--}}

    <!-- <div class="box box-danger">
        <form method="get" action="{{ URL::Route('/admin/searchrev') }}">
            <div class="box-header">
                <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body row">
                <div class="col-md-6 col-sm-12">
                    <select id="searchdrop" class="form-control" name="type">
                        <option value="owner" id="owner">{{ trans('language_changer.User');}} Name</option>
                        <option value="walker" id="walker">{{ trans('language_changer.Provider');}}</option>
                    </select>
                    <br>
                </div>
                <div class="col-md-6 col-sm-12">
                    <input class="form-control" type="text" name="valu" value="<?php if (Session::has('valu')) {
        echo Session::get('valu');
    } ?>" id="insearch" placeholder="keyword"/>
                    <br>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" id="btnsearch" class="btn btn-flat btn-block btn-success serach_blad">Search
                </button>
            </div>
        </form>
    </div> -->

    <div class="box box-info tbl-box">
        <div class="col-md-12 col-sm-12" style="width: 100% !important">

            <div class="col-md-12 col-sm-12">
                <a id="currently" href="{{ URL::Route('ZoneDivision') }}">
                    <button class="col-md-12 col-sm-12 btn btn-flat btn-block  btn-warning" type="button">{{ trans('language_changer.add'),' ',trans('language_changer.new'),' ',trans('language_changer.zonedivision') }}
                    </button>
                </a><br/>
                <br><br>
            </div>
        </div>
        <table class="table table-bordered" dir="{{ trans('language_changer.text_format') }}">
            <tbody>
            <tr>
                <th>{{ trans('language_changer.s_no') }}</th>
                <th>{{ trans('language_changer.zone_name') }}</th>
                <th>{{ trans('language_changer.zone_view') }}</th>
                <th><?php echo Lang::get('language_changer.action'); ?></th>
            </tr>
            <?php $i = 1; ?>
            <?php
            if(count($zoneTypeRecords) > 0) :
            $zoneName = array();
            foreach ($zoneTypeRecords as $key => $zonetype) : ?>
            <tr>
                <?php if(!in_array($zonetype->zone_name, $zoneName)) : ?>
                <td><?php echo $i; ?> </td>
                <td><?php echo $zonetype->zone_name; ?> </td>
                <td><a role="menuitem" tabindex="-1" id="view_zone"
                       href="{{ URL::Route('ViewZoneDivision', $zonetype->id) }}">{{ trans('language_changer.view_map') }}</a></td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-flat btn-info dropdown-toggle" type="button" id="dropdownMenu1"
                                data-toggle="dropdown">
                            {{ trans('language_changer.actions') }}
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                            <li role="presentation"><a role="menuitem" tabindex="-1" id="edit"
                                                       href="{{ URL::Route('EditViewZoneDivision', $zonetype->id) }}">{{ trans('language_changer.edit'),' ',trans('language_changer.zonedivision') }}</a>
                            </li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" id="del_zone" class="confirmation"
                                                       href="{{ URL::Route('DeleteZoneDivision', $zonetype->id) }}">{{ trans('language_changer.delete') }}</a>
                            </li>
                        </ul>
                    </div>
                </td>
                <?php array_push($zoneName, $zonetype->zone_name);
                $i++; ?>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
            <?php else : ?>
            <tr>
                <td colspan="4">No Records Found</td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <div align="left" id="paglink"></div>
    </div><!-- /.tab-pane -->


    <script type="text/javascript">
        $('.confirmation').on('click', function () {
            return confirm('Are you sure you want to delete?');
        });
    </script>

    <style>
        .dropdown-menu {
            margin: 2px 21px 21px 0px !important;

        }
    </style>
@stop