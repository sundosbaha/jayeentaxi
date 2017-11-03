@extends('layout')

@section('content')

<p align="right"><a href="{{ URL::Route('AdminSettings') }}"><button class="btn btn-info btn-flat">{{ trans('language_changer.back_to_settings') }} </button></a></p>




<div class="box box-primary" dir="{{ trans('language_changer.text_format') }}">

    <div class="box-header">
        <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.sms_configuration') }}</h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <form role="form" method="POST" action="{{ URL::Route('AdminInstallFinish') }}">

        <div class="box-body">
            <div class="form-group">
                <label>{{ trans('language_changer.twilio_account_sid') }}</label>

                <input type="text"  name="twillo_account_sid" class="form-control" placeholder="{{ trans('language_changer.twilio_account_sid') }}" value="{{$install['twillo_account_sid']?$install['twillo_account_sid']:''}}">

            </div>
            <div class="form-group">
                <label>{{ trans('language_changer.twilio_auth_token') }}</label>
                <input type="text" name="twillo_auth_token" class="form-control" placeholder="{{ trans('language_changer.twilio_auth_token') }}" value="{{$install['twillo_auth_token']?$install['twillo_auth_token']:''}}">

            </div>
            <div class="form-group">
                <label>{{ trans('language_changer.twilio_number') }}</label>

                <input type="text" name="twillo_number" class="form-control" placeholder="{{ trans('language_changer.twilio_number') }}" value="{{$install['twillo_number']?$install['twillo_number']:''}}">

            </div>




        </div><!-- /.box-body -->

        <div class="box-footer">


            <button type="submit" name="sms" class="btn btn-primary btn-flat btn-block">{{ trans('language_changer.update'),' ',trans('language_changer.change'),trans('language_changer.s')  }}</button>
        </div>
    </form>
</div>






<div class="box box-primary" dir="{{ trans('language_changer.text_format') }}">
    <div class="box-header">
        <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.email'),' ',trans('language_changer.configuration') }}</h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <form role="form" method="POST" action="{{ URL::Route('AdminInstallFinish') }}">

        <div class="box-body">
            <div class="form-group">
                <label>{{ trans('language_changer.mail_driver') }}</label>
                <select name="mail_driver" id="mail_driver" class="form-control">
                    <option value=''>---{{ trans('language_changer.select'),' ',trans('mail_driver') }}---</option>
                    <option value="mail" <?php
                    if ($install['mail_driver'] == 'mail') {
                        echo 'selected';
                    } else {
                        echo'';
                    }
                    ?>>{{ trans('language_changer.mail') }}</option>
                    <option value="mandrill"  <?php
                    if ($install['mail_driver'] == 'mandrill') {
                        echo 'selected';
                    } else {
                        echo'';
                    }
                    ?>>{{ trans('language_changer.mandrill') }}</option>
                </select>



            </div>
            <div class="form-group">
                <label>{{ trans('language_changer.email'),' ',trans('address') }}</label> <span id="no_email_error1" style="display: none"> </span>
                <input type="text" class="form-control"  name="email_address" placeholder="{{ trans('language_changer.email'),' ',trans('address') }}" value="{{$install['email_address']?$install['email_address']:''}}"  onblur="ValidateEmail(1)" id="email_check1" required="" >


            </div>
            <div class="form-group">
                <label>{{ trans('language_changer.display'),' ',trans('language_changer.name')}}</label>
                <input type="text" class="form-control"  name="email_name" placeholder="{{ trans('language_changer.display'),' ',trans('language_changer.name')}}" value="{{$install['email_name']?$install['email_name']:''}}">


            </div>
            <div class="form-group" id="mandrill1" style="display:<?php
            if ($install['mail_driver'] == 'mandrill')
                echo 'block';
            else
                echo 'none';
            ?>">
                <label>{{ trans('mandrill'),' ',trans('secret') }}</label>
                <input type="text" class="form-control"  name="mandrill_secret" placeholder="{{ trans('mandrill'),' ',trans('secret') }}" value="{{$install['mandrill_secret']?$install['mandrill_secret']:''}}">

            </div>
            <div class="form-group" id="mandrill2" style="display:<?php
            if ($install['mail_driver'] == 'mandrill')
                echo 'block';
            else
                echo 'none';
            ?>">
                <label>{{ trans('mandrill'),' ',trans('host_name') }}</label>
                <input type="text" class="form-control"  name="host_name" placeholder="{{ trans('mandrill'),' ',trans('host_name') }}" value="{{$install['host']?$install['host']:''}}">

            </div>
            <div class="form-group" id="mandrill3" style="display:<?php
            if ($install['mail_driver'] == 'mandrill')
                echo 'block';
            else
                echo 'none';
            ?>">
                <label>{{ trans('mandrill'),' ',trans('language_changer.User'),' ',trans('name') }}</label>
                <input type="text" class="form-control"  name="user_name" placeholder="{{ trans('mandrill'),' ',trans('language_changer.User'),' ',trans('name') }}" value="{{$install['mandrill_username']?$install['mandrill_username']:''}}">

            </div>



        </div><!-- /.box-body -->

        <div class="box-footer">

            <button type="submit" name="mail" class="btn btn-primary btn-flat btn-block">{{ trans('language_changer.update'),' ',trans('language_changer.change'),trans('language_changer.s')  }}</button>
        </div>
    </form>
</div>



<div class="box box-primary" dir="{{ trans('language_changer.text_format') }}">
    <div class="box-header">
        <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.payment'),' ',trans('language_changer.configuration')  }}</h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <form role="form" method="POST" action="{{ URL::Route('AdminInstallFinish') }}">

        <div class="box-body">

            <div class="form-group">
                <label>{{ trans('language_changer.default'),' ',trans('language_changer.payment'),' ',trans('language_changer.gateway') }}</label>
                <select name="default_payment" id="default_payment" class="form-control">
                    <?php if (Config::get('app.default_payment') == 'stripe') { ?>
                        <option value="stripe" selected="">{{ trans('language_changer.stripe') }}</option>
                        <option value="braintree">{{ trans('language_changer.brain_tree') }}</option>
                    <?php } else { ?>
                        <option value="stripe">{{ trans('language_changer.stripe') }}</option>
                        <option value="braintree" selected="">{{ trans('language_changer.brain_tree') }}</option>
                    <?php } ?>
                </select>

            </div>

            <div class="form-group stripe">
                <label>{{ trans('language_changer.stripe'),' ',trans('language_changer.secret'),' ',trans('language_changer.key')   }}</label>
                <input type="text" class="form-control"  name="stripe_secret_key" placeholder="{{ trans('language_changer.stripe'),' ',trans('language_changer.secret'),' ',trans('language_changer.key') }}" value="{{$install['stripe_secret_key']?$install['stripe_secret_key']:''}}">

            </div>

            <div class="form-group stripe">
                <label>{{ trans('language_changer.stripe'),' ',trans('language_changer.publishable'),' ',trans('language_changer.key') }} </label>
                <input type="text" class="form-control"  name="stripe_publishable_key" placeholder="{{ trans('language_changer.stripe'),' ',trans('language_changer.publishable'),' ',trans('language_changer.key') }}" value="{{$install['stripe_publishable_key']?$install['stripe_publishable_key']:''}}">

            </div>


            <div class="form-group braintree" style="display:none" >
                <label>{{ trans('language_changer.brain_tree'),' ',trans('language_changer.environment')  }}</label>
                <input type="text" class="form-control"  name="braintree_environment" placeholder="{{ trans('language_changer.brain_tree'),' ',trans('environment')  }}" value="{{$install['braintree_environment']?$install['braintree_environment']:''}}">

            </div>

            <div class="form-group braintree" style="display:none" >
                <label>{{ trans('language_changer.brain_tree'),' ',trans('language_changer.merchant_id')  }}</label>
                <input type="text" class="form-control"  name="braintree_merchant_id" placeholder="{{ trans('language_changer.brain_tree'),' ',trans('merchant_id')  }}" value="{{$install['braintree_merchant_id']?$install['braintree_merchant_id']:''}}">

            </div>

            <div class="form-group braintree" style="display:none" >
                <label>{{ trans('language_changer.brain_tree'),' ',trans('language_changer.public_key') }}</label>
                <input type="text" class="form-control"  name="braintree_public_key" placeholder="{{ trans('brain_tree'),' ',trans('language_changer.public_key') }}" value="{{$install['braintree_public_key']?$install['braintree_public_key']:''}}">

            </div>

            <div class="form-group braintree" style="display:none" >
                <label>{{ trans('language_changer.brain_tree'),' ',trans('language_changer.private_key') }}</label>
                <input type="text" class="form-control"  name="braintree_private_key" placeholder="{{ trans('brain_tree'),' ',trans('language_changer.private_key') }}" value="{{$install['braintree_private_key']?$install['braintree_private_key']:''}}">

            </div>

            <div class="form-group braintree" style="display:none" >
                <label>{{ trans('language_changer.brain_tree'),' ',trans('language_changer.client_side_encryption_key') }}</label>
                <input type="text" class="form-control"  name="braintree_cse" placeholder="{{ trans('brain_tree'),' ',trans('language_changer.client_side_encryption_key') }}" value="{{$install['braintree_cse']?$install['braintree_cse']:''}}">

            </div>



        </div><!-- /.box-body -->

        <div class="box-footer">
            <button type="submit" name="payment" class="btn btn-primary btn-flat btn-block">{{ trans('language_changer.update'),' ',trans('language_changer.change'),trans('language_changer.s')  }}</button>
        </div>
    </form>
</div>

<div class="box box-primary" dir="{{ trans('language_changer.text_format') }}">

    <div class="box-header">
        <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.certificate'),trans('language_changer.s') }}</h3>
    </div>
    <form role="form" method="POST" action="{{ URL::Route('AdminAddCerti') }}" enctype="multipart/form-data">
        <div class="box-body">
            <h3>{{ trans('language_changer.ios') }}</h3>
            <div class="form-group">
                <label>{{ trans('language_changer.certificate'),' ',trans('language_changer.type') }}</label>
                <select class="form-control" name="cert_type_a">
                    <?php
                    if ($install['customer_certy_type']) {
                        ?>
                        <option value="0">{{ trans('language_changer.sandbox') }}</option>
                        <option value="1" selected="">{{ trans('language_changer.production') }}</option>
                    <?php } else { ?>
                        <option value="0" selected="">{{ trans('language_changer.sandbox') }}</option>
                        <option value="1">{{ trans('language_changer.production') }}</option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-sm-4">
                <label> {{ trans('language_changer.User')  }}</label>
                <input type="file" class="form-control" name="user_certi_a" value="<?= $install['customer_certy_url'] ?>">
            </div>
            <div class="form-group col-sm-4">
                <label>{{ trans('language_changer.User'),' ',trans('language_changer.passphrase')  }} </label>
                <input type="text" class="form-control" name="user_pass_a" value="<?= $install['customer_certy_pass'] ?>">
            </div>
            <div class="form-group col-sm-4">
                <label>&nbsp;</label>
                <a href="<?= $install['customer_certy_url'] ?>" target="_blank"><span class="btn btn-default form-control">{{ trans('language_changer.view_download_user_certificate') }}</span></a>
            </div>
            <div class="form-group col-sm-4">
                <label>{{ trans('language_changer.providers') }}</label>
                <input class="form-control" type="file" name="prov_certi_a" value="<?= $install['provider_certy_url'] ?>">
            </div>

            <div class="form-group col-sm-4">
                <label>{{ trans('language_changer.providers'),' ',trans('language_changer.passphrase') }} </label>
                <input class="form-control" type="text" name="prov_pass_a" value="<?= $install['provider_certy_pass'] ?>">
            </div>
            <div class="form-group col-sm-4">
                <label>&nbsp;</label>
                <a href="<?= $install['provider_certy_url'] ?>" target="_blank"><span class="btn btn-default form-control">{{ trans('language_changer.view_download_user_certificate') }}</span></a>
            </div>
            <div class="form-group col-sm-6">
                <label>{{ trans('language_changer.ios'),' ' }}<?php  if(Config::get('app.generic_keywords.User') == 'User'){
                        echo Lang::get('language_changer.User');
                    } ?> {{ trans('language_changer.app_link') }}</label>
                <input class="form-control" type="text" name="ios_client_app_url" value="<?= Config::get('app.ios_client_app_url') ?>">
            </div>
            <div class="form-group col-sm-6">
                <label>{{ trans('language_changer.ios') }} <?php  if(Config::get('app.generic_keywords.Provider') == 'Driver'){
                        echo Lang::get('language_changer.provider');
                    } ?> {{ trans('language_changer.app_link') }}</label>
                <input class="form-control" type="text" name="ios_provider_app_url" value="<?= Config::get('app.ios_provider_app_url') ?>">
            </div>
            <!--<div class="form-group">
                <label>Choose Default</label>
                <select class="form-control" name="cert_default">
                    <option value="0" <?php
            if ($cert_def != 1) {
                echo "selected";
            }
            ?>>Sandbox</option>
                    <option value="1" <?php
            if ($cert_def == 1) {
                echo "selected";
            }
            ?>>Production</option>
                </select>
            </div>-->
        </div>
        <hr>
        <div class="box-body">
            <h3>{{ trans('language_changer.gcm') }}</h3>
            <div class="form-group">
                <label>{{ trans('language_changer.browser_key') }}</label>
                <input type="text" class="form-control" name="gcm_key" placeholder="Enter your Browser GCM Key here." value="<?= $install['gcm_browser_key'] ?>">
            </div>
            <div class="form-group col-sm-6">
                <label>{{ trans('language_changer.android') }} <?php  if(Config::get('app.generic_keywords.User') == 'User'){
                       echo Lang::get('language_changer.User');
                    } ?> {{ trans('language_changer.app_link') }}</label>
                <input class="form-control" type="text" name="android_client_app_url" value="<?= Config::get('app.android_client_app_url') ?>">
            </div>
            <div class="form-group col-sm-6">
                <label>{{ trans('language_changer.android') }} <?php  if(Config::get('app.generic_keywords.User') == 'User'){
                        echo Lang::get('language_changer.provider');
                    } ?> {{ trans('language_changer.app_link') }}</label>
                <input class="form-control" type="text" name="android_provider_app_url" value="<?= Config::get('app.android_provider_app_url') ?>">
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-flat btn-block">{{ trans('language_changer.update'),' ',trans('language_changer.change'),trans('language_changer.s')  }}</button>
            </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var payment = '<?php echo Config::get('app.default_payment'); ?>';
        if (payment == 'stripe') {
            $(".braintree").hide();
            $(".stripe").show();
        }
        else {
            $(".stripe").hide();
            $(".braintree").show();
        }
    });
    $(function () {
        $("#default_storage").change(function () {
            val = $("#default_storage").val();
            if (val == 2) {
                $("#s3").show();
            }
            else {
                $("#s3").hide();
            }
        });
    });
</script>
<script type="text/javascript">
    $(function () {
        $("#mail_driver").change(function () {
            val = $("#mail_driver").val();
            if (val == 'mandrill') {
                $("#mandrill1").fadeIn(300);
                $("#mandrill2").fadeIn(300);
                $("#mandrill3").fadeIn(300);
            }
            else {
                $("#mandrill1").fadeOut(300);
                $("#mandrill2").fadeOut(300);
                $("#mandrill3").fadeOut(300);
            }
        });
    });
</script>
<script type="text/javascript">
    $(function () {
        $("#default_payment").change(function () {
            val = $("#default_payment").val();
            if (val == 'stripe') {
                $(".braintree").hide();
                $(".stripe").show();
            }
            else {
                $(".stripe").hide();
                $(".braintree").show();
            }
        });
    });
</script>
<?php if ($success == 1) { ?>
    <script type="text/javascript">
        alert('{{ trans('language_changer.settings_updated_successfully') }}');
    </script>
<?php } ?>
<?php if ($success == 2) { ?>
    <script type="text/javascript">
        alert('{{ trans('language_changer.wrong') }}');
    </script>
<?php } ?>
<?php if ($success == 3) { ?>
    <script type="text/javascript">
        alert('{{ trans('language_changer.pem_format') }}');
    </script>
<?php } ?>
<?php if ($success == 4) { ?>
    <script type="text/javascript">
        alert('{{ trans('language_changer.browserKey_not_blank') }}');
    </script>
<?php } ?>
<?php if ($success == 5) { ?>
    <script type="text/javascript">
        alert('{{ trans('language_changer.mobile_notify_not_change') }}');
    </script>
<?php } ?>

<script>
    $(function () {
        $("[data-toggle='tooltip']").tooltip();
    });
</script>
@stop