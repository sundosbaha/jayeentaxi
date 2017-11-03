@extends('layout')
@section('content')



<div class="box box-info tbl-box">
    <div align="left" id="paglink"><?php echo $admin->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>{{ strtoupper(trans('customize.id')) }}</th>
                <th>{{ trans('customize.User'),' ',trans('customize.name') }}</th>
                
                <th style="width: 105px;">{{ trans('customize.actions') }}</th>
            </tr>
            
            <?php $i=1; foreach ($admin as $ad) { ?>
                <tr>
                <td><?php echo $i; ?></td>
                   <td><?php echo $ad->username; ?></td>
                    <td><?php if($ad->type != 'super') {?> <a href="<?php echo url('admin/privilege', [$ad->id]) ?>" class="btn btn-primary">{{ trans('customize.set_privilege') }}</a><?php } ?>
                    
 
                </tr>
            <?php $i++;  } ?>
        </tbody>
    </table>
    <div align="left" id="paglink"><?php echo $admin->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>
</div>

@stop