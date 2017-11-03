@extends('layout')

@section('content')

<script src="https://bitbucket.org/pellepim/jstimezonedetect/downloads/jstz-1.0.4.min.js"></script>
<script src="http://momentjs.com/downloads/moment.min.js"></script>
<script src="http://momentjs.com/downloads/moment-timezone-with-data.min.js"></script> 

<div class="box box-danger" >
    <form method="get" action="{{ URL::Route('/admin/searchrev') }}" >
            <div class="box-header">
                <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.filter') }}</h3>
            </div>
            <div class="box-body row" dir="{{ trans('language_changer.text_format') }}">
                <div class="col-md-6 col-sm-12" style="float:<?php echo $align_format; ?>">
                    <select id="searchdrop" class="form-control" name="type">
                        <option value="owner" id="owner">{{ trans('language_changer.User'),' ',trans('language_changer.name') }} </option>
                        <option value="walker" id="walker">{{ trans('language_changer.Provider');}}</option>
                    </select>
                    <br>
                </div>
                <div class="col-md-6 col-sm-12" dir="{{ trans('language_changer.text_format') }}">
                    <input class="form-control" type="text" name="valu" value="<?php if(Session::has('valu')){echo Session::get('valu');} ?>" id="insearch" placeholder="{{ trans('language_changer.keyword') }}"/>
                    <br>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" id="btnsearch" class="btn btn-flat btn-block btn-success serach_blad">{{ trans('language_changer.search') }}</button>
            </div>
    </form>
</div>

<div class="box box-info tbl-box" >
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">{{ trans('language_changer.Provider'),' ',trans('language_changer.reviews')}} </a></li>
            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">{{ trans('language_changer.User'),' ',trans('language_changer.reviews')}}</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1" >

                <div align="<?php echo $align_format ?>" id="paglink"><?php echo $provider_reviews->appends(array('type'=>Session::get('type'), 'valu'=>Session::get('valu')))->links(); ?></div>
                <table class="table table-bordered" dir="{{ trans('language_changer.text_format') }}">
                    <tbody>
                        <tr>
                            <th>{{ trans('language_changer.User'),' ',trans('language_changer.name')}}</th>
                            <th>{{ trans('language_changer.Provider')}}</th>
                            <th>{{ trans('language_changer.rating') }}</th>
                            <th>{{ trans('language_changer.date').  trans('language_changer.and')  . trans('language_changer.time')}}</th>
                            <th>{{ trans('language_changer.comment') }}</th>
                            <th>{{trans('language_changer.action')}}</th>
                        </tr>
                        <?php $i =0; ?>
                        <?php foreach ($provider_reviews as $reviewp) { ?>
                            <tr>
                                <td><?php echo $reviewp->owner_first_name." ".$reviewp->owner_last_name; ?> </td>
                                <td><?php echo $reviewp->walker_first_name." ".$reviewp->walker_last_name; ?> </td>
                                <td><?= $reviewp->rating ?></td>
                                <!--<td id= 'Datetime<?php echo $i; ?>' >
                                <script>
                                var timezone = jstz.determine();
                                 // console.log(timezone.name());
                                var timevar = moment.utc("<?php echo $reviewp->created_at; ?>");
                                timevar.toDate();
                                timevar.tz(timezone.name());
                                // console.log(timevar);
                                document.getElementById("Datetime<?php echo $i; ?>").innerHTML = timevar;
                                <?php  $i++; ?>
                                </script>-->
                                
                                
                                <td><?php echo date('Y-m-d H:i:s', strtotime($reviewp->created_at)); ?></td>
                                <td><?= $reviewp->comment ?></td>
                                <td><a href="{{ URL::Route('AdminReviewsDelete', $reviewp->review_id) }}"><input type="button" class="btn btn-success" value=" {{trans('language_changer.delete')}}"></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div align="<?php echo $align_format ?>" id="paglink"><?php echo $provider_reviews->appends(array('type'=>Session::get('type'), 'valu'=>Session::get('valu')))->links(); ?></div>
            </div><!-- /.tab-pane -->
             <div class="tab-pane" id="tab_2">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>{{ trans('language_changer.User'),' ', trans('language_changer.name')}}</th>
                            <th>{{ trans('language_changer.Provider');}}</th>
                            <th>{{ trans('language_changer.rating') }}</th>
                            <th>{{ trans('language_changer.date'). ' and ' . trans('language_changer.time')}}</th>
                            <th>{{ trans('language_changer.comment') }}</th>
                            <th>{{trans('language_changer.action')}}</th>
                        </tr>
                        <?php $i =0; ?>
                        <?php foreach ($user_reviews as $reviewu) { ?>
                            <tr>
                                <td><?php echo $reviewu->walker_first_name." ".$reviewu->walker_last_name; ?> </td>
                                <td><?php echo $reviewu->owner_first_name." ".$reviewu->owner_last_name; ?> </td>
                                <td><?= $reviewu->rating ?></td>
                               <!-- <td id= 'time<?php /*echo $i; */?>' >
                                <script>
                                var timezone = jstz.determine();
                                 // console.log(timezone.name());
                                var timevar = moment.utc("<?php /*echo $reviewu->created_at; */?>");
                                timevar.toDate();
                                timevar.tz(timezone.name());
                                // console.log(timevar);
                                document.getElementById("time<?php /*echo $i; */?>").innerHTML = timevar;
                                <?php /* $i++; */?>
                                </script>-->
                              <td><?php echo date('Y-m-d H:i:s', strtotime($reviewp->created_at)); ?></td>
                                <td><?= $reviewu->comment ?></td>
                                <td><a href="{{ URL::Route('AdminReviewsDeleteDog', $reviewu->review_id) }}"><input type="button" class="btn btn-success" value="{{trans('language_changer.delete')}}"></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                 <div align="<?php echo $align_format ?>" id="paglink"><?php echo $provider_reviews->appends(array('type'=>Session::get('type'), 'valu'=>Session::get('valu')))->links(); ?></div>
           </div>
       </div>
   </div>
</div>

@stop