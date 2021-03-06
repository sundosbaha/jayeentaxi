@extends('layout')

@section('content')

    <style>

    </style>

    <a id="addinfo" href="{{ URL::Route('AdminInformationEdit', 0) }}" >
    <button type="submit"  id="newpage" class="btn btn-flat btn-block btn-success" value="Add New Page"  style="width:100%;"  >{{ trans('language_changer.add'), ' ',trans('language_changer.new'), ' ',trans('language_changer.page')    }}</button> </a>

<br>
                    <div class="box box-danger">

                       <form method="get" action="{{ URL::Route('/admin/searchinfo', 0) }}">
                                <div class="box-header">
                                    <h3 class="box-title" style="float:<?php echo $align_format; ?>" >{{ trans('language_changer.filter') }}</h3>
                                </div>
                                <div class="box-body row">

                                <div class="col-md-6 col-sm-12" style="float:<?php echo $align_format; ?>" dir="{{ trans('language_changer.text_format') }}">

                                <select id="searchdrop" class="form-control" name="type">
                                    <option value="infoid" id="infoid">{{ strtoupper(trans('language_changer.id')) }}</option>
                                    <option value="infotitle" id="infotitle">{{ trans('language_changer.title') }}</option>
                                </select>

                                               
                                    <br>
                                </div>
                                <div class="col-md-6 col-sm-12" dir="{{ trans('language_changer.text_format') }}">

                                    <input class="form-control" type="text" name="valu" id="insearch" placeholder="{{ trans('language_changer.keyword') }}"/>
                                    <br>
                                </div>

                                </div>

                                <div class="box-footer">

                                  
                                        <button type="submit" id="btnsearch" class="btn btn-flat btn-block btn-success serach_blad">{{ trans('language_changer.search') }}</button>

                                        
                                </div>
                        </form>

                    </div>



                <div class="box box-info tbl-box">
                    <div align="left" id="paglink"><?php echo $informations->appends(array('type'=>Session::get('type'), 'valu'=>Session::get('valu')))->links(); ?></div>
                <table class="table table-bordered" dir="{{ trans('language_changer.text_format') }}">
                                <tbody>
                                        <tr>
                                            <th>{{ trans('language_changer.id') }}</th>
                                            <th>{{ trans('language_changer.title') }}</th>
                                            <th>{{ trans('language_changer.actions') }}</th>

                                        </tr>
                                    <?php foreach ($informations as $information) { ?>
                                    <tr>
                                        <td><?= $information->id ?></td>
                                        <td><?= $information->title ?></td>
                                        <td><a id="edit" href="{{ URL::Route('AdminInformationEdit', $information->id) }}"><input type="button" class="btn btn-success" value="{{ trans('language_changer.edit') }}"></a>
                                        <a id="delete" href="{{ URL::Route('AdminInformationDelete', $information->id) }}"><input type="button" class="btn btn-danger" value="{{ trans('language_changer.delete') }}"></a></td>
                                    </tr>
                                    <?php } ?>
                    </tbody>
                </table>

                <div align="left" id="paglink"><?php echo $informations->appends(array('type'=>Session::get('type'), 'valu'=>Session::get('valu')))->links(); ?></div>

                </div>





@stop