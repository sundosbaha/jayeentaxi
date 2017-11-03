
@extends('layout')

@section('content')

<div class="box box-success">
<br/>
<br/>
                    @if (Session::has('msg'))
                    <h4 class="alert alert-info">
                    {{{ Session::get('msg') }}}
                    {{{Session::put('msg',NULL)}}}
                    </h4>
                   @endif
                <br/>
<a href="<?php echo url('admin/listuser'); ?>" class="btn btn-primary">Go Back</a>
                    <div class="box-body ">
            <form method="post" action="#">
          <!--  <div class="form-group" id="Dashboard">
            <input class="for2" type="checkbox" <?php //if(inarray('AdminReport', $urll)) { echo "checked";} ?> onclick="chan('AdminReport','{{$userid;}}');"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>{{ trans('customize.Dashboard'); }}</label>
            </div>-->
             <div class="form-group" id="map_view">
            <input class="for2" type="checkbox" <?php if(inarray('AdminMapview', $urll))  { echo "checked";} ?> onChange="chan('AdminMapview','{{$userid;}}')"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>{{ trans('customize.map_view'); }}</label>
            </div>
             <div class="form-group" id="providers">
            <input class="for2" type="checkbox" <?php if(inarray('AdminProviders', $urll))  { echo "checked";} ?> onChange="chan('AdminProviders','{{$userid;}}');"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>{{ trans('customize.Provider'); }}</label>
            </div>
            <div class="form-group" id="Request">
            <input class="for2" type="checkbox" <?php if(inarray('AdminRequests', $urll))  { echo "checked";} ?> onChange="chan('AdminRequests','{{$userid;}}');"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>{{ trans('customize.Request'); }}</label>
            </div>
             <div class="form-group" id="users">
            <input class="for2" type="checkbox" <?php if(inarray('AdminUsers', $urll))  { echo "checked";} ?> onChange="chan('AdminUsers','{{$userid;}}')"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>{{ trans('customize.User'); }}</label>
            </div>
             <div class="form-group" id="reviews">
            <input class="for2" type="checkbox" <?php if(inarray('AdminReviews', $urll))   { echo "checked";} ?> onChange="chan('AdminReviews','{{$userid;}}')"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>{{ trans('customize.Reviews'); }}</label>
            </div>
             <div class="form-group" id="informations">
            <input class="for2" type="checkbox" <?php if(inarray('AdminInformations', $urll))   { echo "checked";} ?> onChange="chan('AdminInformations','{{$userid;}}')"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>{{ trans('customize.Information'); }}</label>
            </div>
             <div class="form-group" id="provider-types">
            <input class="for2" type="checkbox" <?php if(inarray('AdminProviderTypes', $urll))   { echo "checked";} ?> onChange="chan('AdminProviderTypes','{{$userid;}}')"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>{{ trans('customize.Types'); }}</label>
            </div>
             <div class="form-group" id="document-types">
            <input class="for2" type="checkbox" <?php if(inarray('AdminDocumentTypes', $urll))   { echo "checked";} ?> onChange="chan('AdminDocumentTypes','{{$userid;}}')"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>{{ trans('customize.Documents'); }}</label>
            </div>
             <div class="form-group" id="promo_code">
            <input class="for2" type="checkbox" <?php if(inarray('AdminPromoCodes', $urll))   { echo "checked";} ?>  onChange="chan('AdminPromoCodes','{{$userid;}}')"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>{{ trans('customize.promo_codes'); }}</label>
            </div>
             <div class="form-group" id="edit_keywords">
            <input class="for2" type="checkbox" <?php if(inarray('AdminKeywords', $urll))   { echo "checked";} ?>  onChange="chan('AdminKeywords','{{$userid;}}')"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>{{ trans('customize.Customize'); }}</label>
            </div>
             <div class="form-group" id="details_payment">
            <input class="for2" type="checkbox" <?php if(inarray('AdminPayment', $urll))   { echo "checked";} ?> onChange="chan('AdminPayment','{{$userid;}}')"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>{{ trans('customize.payment_details'); }}</label>
            </div>
             <div class="form-group" id="settings">
            <input class="for2" type="checkbox" <?php if(inarray('AdminSettings', $urll))   { echo "checked";} ?>   onChange="chan('AdminSettings','{{$userid;}}')"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>{{ trans('customize.Settings'); }}</label>
            </div>
              <div class="form-group" id="zone_division">
                  <input class="for2" type="checkbox"
                         <?php if(inarray('ZoneDivision', $urll))   { echo "checked";} ?>   onChange="chan('ZoneDivision','{{$userid;}}')">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>{{ trans('customize.zonedivision'); }}</label>
              </div>
            </div>
                      <!--  <div class="box-footer">
                                  
                                        <button type="submit" id="btnsearch" class="btn btn-flat btn-block btn-success">Add Admin</button>

                                        
                                </div>-->
                                </form>
                                </div>
                                </div>
                                
                               
                    

@stop