@extends('layout')

@section('content')
<?php
$counter = 1;
$message1 = $message2 = $message3 = '';
if ($install['mail_driver'] == '' && $install['email_address'] == '' && $install['email_name'] == '') {
    $message1 = 'Mail Configuration Missing During Installation';
}
if ($install['twillo_account_sid'] == '' && $install['twillo_auth_token'] == '' && $install['twillo_number'] == '') {
    $message2 = 'SMS Configuration Missing During Installation';
}
if (($install['default_payment'] == '' && $install['braintree_environment'] == '' && $install['braintree_merchant_id'] == '' && $install['braintree_public_key'] == '' && $install['braintree_private_key'] == '' && $install['braintree_cse'] == '') && ( $install['stripe_publishable_key'] == '')) {
    $message3 = 'Payment Configuration Missing During Installation';
}
$ispromo_active = $isreferal_active = 0;
$ref = $refcash = $refcard = 0;
$promo = $promocash = $promocard = 0;
?>
<!--<a href="{{ URL::Route('AdminSettingInstallation') }}"><button class="btn btn-flat btn-block btn-success" id="newinstall">Installation Settings</button></a>-->

<div class="row" dir="{{ trans('language_changer.text_format') }}">

    <div class="col-md-6 col-sm-12" style="float:<?php echo $align_format; ?>">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"  style="float:<?php echo $align_format; ?>">{{ trans('language_changer.basic_app_settings') }}</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form method="post" action="{{ URL::Route('AdminSettingsSave') }}" id="basic"  enctype="multipart/form-data">


                <div class="box-body">

                    <?php
                    foreach ($settings as $setting) {
                        if ($setting->page == 1) {
							
							
                            if ($setting->key == 'referral_code_activation') {
                                $ref = $setting->id;
                                if ($setting->value == 1) {
                                    $isreferal_active = 1;
                                }
                            }
                            if ($setting->key == 'promotional_code_activation') {
                                $promo = $setting->id;
                                if ($setting->value == 1) {
                                    $ispromo_active = 1;
                                }
                            }
                            if ($setting->key == 'get_referral_profit_on_card_payment') {
                                $refcard = $setting->id;
                            }
                            if ($setting->key == 'get_referral_profit_on_cash_payment') {
                                $refcash = $setting->id;
                            }
                            if ($setting->key == 'get_promotional_profit_on_card_payment') {

                                $promocard = $setting->id;
                            }
                            if ($setting->key == 'get_promotional_profit_on_cash_payment') {

                                $promocash = $setting->id;
                            }
                            /* if ($setting->key != 'admin_email_address') { */
                    if ($setting->key != 'default_distance_unit' && $setting->key != 'sms_notification' && $setting->key != 'zone_division' && $setting->key != 'email_notification' && $setting->key != 'push_notification' && $setting->key != 'default_charging_method_for_users' && $setting->key != 'referral_code_activation' && $setting->key != 'promotional_code_activation' && $setting->key != 'get_referral_profit_on_card_payment' && $setting->key != 'get_referral_profit_on_cash_payment' && $setting->key != 'get_promotional_profit_on_card_payment' && $setting->key != 'get_promotional_profit_on_cash_payment' && $setting->key != 'otp_verification' && $setting->key != 'Language') {
                                if ($setting->key == 'base_price' || $setting->key == 'price_per_unit_distance' || $setting->key == 'price_per_unit_time') {
                                    
                                } else {
                                    ?>
                                    <div class="form-group">
                                        <label><?php

                                            if(Config::get('app.locale') == 'arb'){
                                                $label=trans('language_changer.'.$setting->key);

                                            }elseif(Config::get('app.locale') == 'en'){
                                                $label=ucwords(str_replace("_", " ", $setting->key));
                                            }

                                            echo $label;

                                            ?>&nbsp;<a href="#" data-toggle="tooltip" title="<?= $setting->tool_tip; ?>"><img src="<?php echo asset_url(); ?>/image/icon-tooltip.jpg"></a></label>

                                        <?php if ((strstr($setting->key, 'email_') || strstr($setting->key, 'sms_')) & $setting->key != 'admin_email_address') { ?>
                                            <textarea style="resize: none;" class="form-control" rows="2" cols="50" name="<?php echo $setting->id; ?>" ><?php echo $setting->value; ?></textarea>
                                        <?php } else { ?>
                                            <?php if ($setting->key == 'base_price' || $setting->key == 'price_per_unit_distance' || $setting->key == 'price_per_unit_time' || $setting->key == 'default_referral_bonus' || $setting->key == 'default_referral_bonus_to_refered_user' || $setting->key == 'default_referral_bonus_to_refereel') { ?>
                                                <span id="no_amount_error<?php echo $counter; ?>" style="display: none"></span>
                                                <input class="form-control" type="text" name="<?php echo $setting->id; ?>" value="<?php echo $setting->value; ?>" onkeypress="return Isamount(event,<?php echo $counter; ?>);" >
                                                <?php
                                            } else if ($setting->key == 'provider_timeout') {
                                                ?>
                                                <span id="no_number_error<?php echo $counter; ?>" style="display: none"> </span>
                                                <input class="form-control" type="text" name="<?php echo $setting->id; ?>" value="<?php echo $setting->value; ?>" onkeypress="return IsNumeric(event,<?php echo $counter; ?>);" >
                                                <?php
                                            } else if ($setting->key == 'default_search_radius' || $setting->key == 'map_center_latitude' || $setting->key == 'map_center_longitude') {
                                                ?>
                                                <span id="no_numamount_error<?php echo $counter; ?>" style="display: none"></span>
                                                <input class="form-control" type="text" name="<?php echo $setting->id; ?>" value="<?php echo $setting->value; ?>" onkeypress="return Isnumamount(event,<?php echo $counter; ?>);" >
                                            <?php } else if ($setting->key == 'admin_phone_number') { ?>
                                                <span id="no_mobile_error<?php echo $counter; ?>" style="display: none"> </span>
                                                <input class="form-control" type="text" name="<?php echo $setting->id; ?>" value="<?php echo $setting->value; ?>" onkeypress="return Ismobile(event,<?php echo $counter; ?>);" >
                                            <?php } else if ($setting->key == 'admin_email_address') {
                                                ?>
                                                <span id="no_email_error<?php echo $counter; ?>" style="display: none"> </span>   
                                                <input class="form-control" type="text" name="<?php echo $setting->id; ?>" value="<?php echo $setting->value; ?>" onblur="ValidateEmail(<?php echo $counter; ?>)" id="email_check<?php echo $counter; ?>" required="" >
                                            <?php }


                                        else {
                                                ?>
                                                <input class="form-control" type="text" name="<?php echo $setting->id; ?>" value="<?php echo $setting->value; ?>" >
                                                <?php
                                            }



                                                ?>

                                        <?php


                                        }
                                        ?>

                                    </div>
                                    <?php
                                }
                            } else {

                        if($setting->key == 'Language') {

                            ?>

                        <div class="form-group">
                            <label>
                                <?php

                                if(Config::get('app.locale') == 'arb'){
                                    $label=trans('language_changer.'.$setting->key);

                                }elseif(Config::get('app.locale') == 'en'){
                                    $label=ucwords(str_replace("_", " ", $setting->key));
                                }

                                echo $label;
                                ?>&nbsp;<a href="#" data-toggle="tooltip" title="<?= $setting->tool_tip; ?>"><img src="<?php echo asset_url(); ?>/image/icon-tooltip.jpg"></a></label>
                            <select  class="form-control" name="<?php echo $setting->id; ?>">
                                <option value="1" <?php
                                        if ($setting->value == 1) {
                                            echo "selected";
                                        }
                                        ?> >
                                    Arabic
                                </option>
                                <option value="2" <?php
                                        if ($setting->value == 2) {
                                            echo "selected";
                                        }
                                        ?> >
                                    English
                                </option>
                            </select>
                        </div>

                        <?php


                            }

                                if ($setting->key != 'default_charging_method_for_users' && $setting->key != 'Language') {
                                    ?>
                                    <div class="form-group">
                                        <label><?php

                                            if(Config::get('app.locale') == 'arb'){
                                                $label=trans('language_changer.'.$setting->key);

                                            }elseif(Config::get('app.locale') == 'en'){
                                                $label=ucwords(str_replace("_", " ", $setting->key));
                                            }

                                            echo $label;
                                            ?>&nbsp;<a href="#" data-toggle="tooltip" title="<?= $setting->tool_tip; ?>"><img src="<?php echo asset_url(); ?>/image/icon-tooltip.jpg"></a></label>

                                        <select <?php if($setting->key == 'otp_verification'){ ?> id="otp" onChange="otpchange('<?php if ($install['twillo_account_sid'] == '' && $install['twillo_auth_token'] == '' && $install['twillo_number'] == '') { echo 'false';  } else { echo 'true';  }?>')" <?php  }  ?> class="form-control" name="<?php echo $setting->id; ?>">
                                            <option value="1" <?php
                                            if ($setting->value == 1) {
                                                echo "selected";
                                            }
                                            ?> >
                                                        <?php if ($setting->key == 'default_charging_method_for_users') { ?>
                                                    {{ trans('language_changer.time_and_distance_based') }}
                                                <?php } elseif ($setting->key == 'default_distance_unit') { ?>
                                                    {{ trans('language_changer.miles') }}
                                                <?php } else { ?>
                                                    {{ trans('language_changer.yes') }}
                                                <?php } ?>
                                            </option>
                                            <option value="0" <?php
                                            if ($setting->value == 0) {
                                                echo "selected";
                                            }
                                            ?> >
                                                        <?php if ($setting->key == 'default_charging_method_for_users') { ?>
                                                    {{ trans('language_changer.fixed_price') }}
                                                <?php } elseif ($setting->key == 'default_distance_unit') { ?>
                                                    {{ trans('language_changer.km') }}
                                                <?php } else { ?>
                                                    {{ trans('language_changer.no') }}
                                                <?php } ?>
                                            </option>
                                        </select>

                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <?php
                            /* } */
                        }
                        ?>


                        <?php
                        $counter++;
                    }
                    ?>



                </div><!-- /.box-body -->

                <div class="box-footer">

                    <button type="submit" class="btn btn-primary btn-flat btn-block">{{ trans('language_changer.update'),' ',trans('language_changer.change'),'s' }}</button>
                </div>

        </div>
    </div>



    <div class="col-md-6 col-sm-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.sms_templates') }}</h3>
            </div><!-- /.box-header -->


            <div class="box-body">
                <?php
                foreach ($settings as $setting) {
                    if ($setting->page == 2) {
                        if ($setting->key != 'sms_notification' && $setting->key != 'email_notification' && $setting->key != 'push_notification' && $setting->key != 'default_charging_method_for_users') {
                            ?>
                            <div class="form-group">
                                <label><?php

                                    if(Config::get('app.locale') == 'arb'){
                                        $label=trans('language_changer.'.$setting->key);

                                    }elseif(Config::get('app.locale') == 'en'){
                                        $label=ucwords(str_replace("_", " ", $setting->key));
                                    }

                                    echo $label;


                                    ?>&nbsp;<a href="#" data-toggle="tooltip" title="<?= $setting->tool_tip; ?>"><img src="<?php echo asset_url(); ?>/image/icon-tooltip.jpg"></a></label>

                                <?php if ((strstr($setting->key, 'email_') || strstr($setting->key, 'sms_')) & $setting->key != 'admin_email_address') { ?>
                                    <textarea style="resize: none;" class="form-control" rows="1" cols="50" name="<?php echo $setting->id; ?>" ><?php echo $setting->value; ?></textarea>
                                <?php } else { ?>
                                    <input class="form-control" type="text" name="<?php echo $setting->id; ?>" value="<?php echo $setting->value; ?>">
                                <?php } ?>

                            </div>
                        <?php } else { ?>
                            <div class="form-group">
                                <label><?php

                                    if(Config::get('app.locale') == 'arb'){
                                        $label=trans('language_changers.'.$setting->key);

                                    }elseif(Config::get('app.locale') == 'en'){
                                        $label=ucwords(str_replace("_", " ", $setting->key));
                                    }

                                    echo $label;

                                    ?>&nbsp;<a href="#" data-toggle="tooltip" title="<?= $setting->tool_tip; ?>"><img src="<?php echo asset_url(); ?>/image/icon-tooltip.jpg"></a></label>

                                <select class="form-control" name="<?php echo $setting->id; ?>">
                                    <option value="1" <?php
                                    if ($setting->value == 1) {
                                        echo "selected";
                                    }
                                    ?> >
                                                <?php if ($setting->key == 'default_charging_method_for_users') { ?>
                                                    {{ trans('language_changer.time_and_distance_based') }}
                                        <?php } else { ?>
                                            {{ trans('language_changer.yes') }}
                                        <?php } ?>
                                    </option>
                                    <option value="0" <?php
                                    if ($setting->value == 0) {
                                        echo "selected";
                                    }
                                    ?> >
                                                <?php if ($setting->key == 'default_charging_method_for_users') { ?>
                                                    {{ trans('language_changer.fixed_price') }}
                                        <?php } else { ?>
                                                    {{ trans('language_changer.no') }}
                                        <?php } ?>
                                    </option>
                                </select>

                            </div>
                        <?php } ?>
                    <?php } ?>


                <?php } ?>

            </div><!-- /.box-body -->

            <div class="box-footer">

                <button type="submit" class="btn btn-primary btn-flat btn-block">{{ trans('language_changer.update'),' ',trans('language_changer.change'),'s' }}</button>
            </div>

        </div>
    </div>
    <!--
    
    
    
    <div class="col-md-6 col-sm-12">
    <div class="box box-primary">
    <div class="box-header">
    <h3 class="box-title">Email Templates</h3>
    </div>
    
    <div class="box-body">
    
    <?php
    foreach ($settings as $setting) {
        if ($setting->page == 3) {
            if ($setting->key != 'sms_notification' && $setting->key != 'get_destination' && $setting->key != 'email_notification' && $setting->key != 'push_notification' && $setting->key != 'default_charging_method_for_users') {
                ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div class="form-group">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <label><?php echo ucwords(str_replace("_", " ", $setting->key)); ?>&nbsp;<a href="#" data-toggle="tooltip" title="<?= $setting->tool_tip; ?>"><img src="<?php echo asset_url(); ?>/image/icon-tooltip.jpg"></a></label>
                                                                                                                                                                                                                    
                <?php if ((strstr($setting->key, 'email_') || strstr($setting->key, 'sms_')) & $setting->key != 'admin_email_address') { ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <textarea class="form-control" rows="5" cols="50" name="<?php echo $setting->id; ?>" ><?php echo $setting->value; ?></textarea>
                <?php } else { ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <input class="form-control" type="text" name="<?php echo $setting->id; ?>" value="<?php echo $setting->value; ?>">
                <?php } ?>
                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                <?php
            } else {
                if ($setting->key != 'get_destination') {
                    ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <label><?php echo ucwords(str_replace("_", " ", $setting->key)); ?>&nbsp;<a href="#" data-toggle="tooltip" title="<?= $setting->tool_tip; ?>"><img src="<?php echo asset_url(); ?>/image/icon-tooltip.jpg"></a></label>
                                                                                                                                                                                                                        
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <select class="form-control" name="<?php echo $setting->id; ?>">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <option value="1" <?php
                    if ($setting->value == 1) {
                        echo "selected";
                    }
                    ?> >
                    <?php if ($setting->key == 'default_charging_method_for_users') { ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                Time and Distance Based
                    <?php } else { ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                Yes
                    <?php } ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </option>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <option value="0" <?php
                    if ($setting->value == 0) {
                        echo "selected";
                    }
                    ?> >
                    <?php if ($setting->key == 'default_charging_method_for_users') { ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                Fixed Price
                    <?php } else { ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                No
                    <?php } ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </option>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </select>
                                                                                                                                                                                                                        
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                    <?php
                }
            }
        }
    }
    ?>                                       
    
    
    </div>
    
    <div class="box-footer">
    
    
    <button type="submit" class="btn btn-primary btn-flat btn-block">Save</button>
    </div>
    
    </div>
    </div>
    -->


    <div class="col-md-6 col-sm-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.advance_settings') }}</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php
                foreach ($settings as $setting) {
                    if ($setting->page == 4) {
                        if ($setting->key == 'provider_selection') {
                            ?>
                            <div class="form-group">
                                <label><?php


                                    if(Config::get('app.locale') == 'arb'){
                                        $label=trans('language_changer.'.$setting->key);

                                    }elseif(Config::get('app.locale') == 'en'){
                                        $label=ucwords(str_replace("_", " ", $setting->key));
                                    }

                                    echo $label;



                                    ?>&nbsp;<a href="#" data-toggle="tooltip" title="<?= $setting->tool_tip; ?>"><img src="<?php echo asset_url(); ?>/image/icon-tooltip.jpg"></a></label>

                                <select class="form-control" name="<?php echo $setting->id; ?>">
                                    <option value="1" <?php
                                    if ($setting->value == 1) {
                                        echo "selected";
                                    }
                                    ?> >
                                        {{ trans('language_changer.automatic') }}
                                    </option>
                                    <option value="2" <?php
                                    if ($setting->value == 2) {
                                        echo "selected";
                                    }
                                    ?> >
                                        {{ trans('language_changer.manually') }}
                                    </option>
                                </select>

                            </div>
                            <?php
                        } else if ($setting->key == 'service_fee') {
                            if (Config::get('app.generic_keywords.Currency') == '$') {
                                ?>
                                <div class="form-group">
                                    <label><?php

                                        if(Config::get('app.locale') == 'arb'){
                                            $label=trans('language_changer.'.$setting->key);

                                        }elseif(Config::get('app.locale') == 'en'){
                                            $label=ucwords(str_replace("_", " ", $setting->key));
                                        }

                                        echo $label;


                                        ?>&nbsp;<a href="#" data-toggle="tooltip" title="<?= $setting->tool_tip; ?>"><img src="<?php echo asset_url(); ?>/image/icon-tooltip.jpg"></a></label>
                                    <span id="no_amount_error<?php echo $counter; ?>" style="display: none"></span>
                                    <input class="form-control" type="text" name="<?php echo $setting->id; ?>" value="<?php echo $setting->value; ?>" onkeypress="return Isamount(event,<?php echo $counter; ?>);" >
                                </div>                        
                                <?php
                            }
                        } else {
                            ?>
                            <div class="form-group">
                                <label><?php

                                    if(Config::get('app.locale') == 'arb'){
                                        $label=trans('language_changer.'.$setting->key);

                                    }elseif(Config::get('app.locale') == 'en'){
                                        $label=ucwords(str_replace("_", " ", $setting->key));
                                    }

                                    echo $label;


                                    ?>&nbsp;<a href="#" data-toggle="tooltip" title="<?= $setting->tool_tip; ?>"><img src="<?php echo asset_url(); ?>/image/icon-tooltip.jpg"></a></label>
                                <span id="no_amount_error<?php echo $counter; ?>" style="display: none"></span>
                                <input class="form-control" type="text" name="<?php echo $setting->id; ?>" value="<?php echo $setting->value; ?>" onkeypress="return Isamount(event,<?php echo $counter; ?>);" >
                            </div>
                            <?php
                        }
                    }
                    ?>
                    <?php if ($setting->page == 5) { ?>
                        <div class="form-group">
                            <label><?php


                                if(Config::get('app.locale') == 'arb'){
                                    $label=trans('language_changer.'.$setting->key);

                                }elseif(Config::get('app.locale') == 'en'){
                                    $label=ucwords(str_replace("_", " ", $setting->key));
                                }

                                echo $label;



                                ?>&nbsp;<a href="#" data-toggle="tooltip" title="<?= $setting->tool_tip; ?>"><img src="<?php echo asset_url(); ?>/image/icon-tooltip.jpg"></a></label>

                            <select class="form-control" name="<?php echo $setting->id; ?>">
                                <option value="1" <?php
                                if ($setting->value == 1) {
                                    echo "selected";
                                }
                                ?> >
                                    {{ trans('language_changer.pre_payment') }}
                                </option>
                                <option value="0" <?php
                                if ($setting->value == 0) {
                                    echo "selected";
                                }
                                ?> >
                                   {{ trans('language_changer.post_payment') }}
                                </option>
                            </select>

                        </div>
                    <?php } ?>
                    <?php if ($setting->key == 'get_destination') { ?>
                        <div class="form-group">
                            <label><?php

                                if(Config::get('app.locale') == 'arb'){
                                    $label=trans('language_changer.'.$setting->key);

                                }elseif(Config::get('app.locale') == 'en'){
                                    $label=ucwords(str_replace("_", " ", $setting->key));
                                }

                                echo $label;



                                ?>&nbsp;<a href="#" data-toggle="tooltip" title="<?= $setting->tool_tip; ?>"><img src="<?php echo asset_url(); ?>/image/icon-tooltip.jpg"></a></label>

                            <select class="form-control" name="<?php echo $setting->id; ?>">
                                <option value="1" <?php
                                if ($setting->value == 1) {
                                    echo "selected";
                                }
                                ?> >
                                    {{ trans('language_changer.yes') }}
                                </option>
                                <option value="0" <?php
                                if ($setting->value == 0) {
                                    echo "selected";
                                }
                                ?> >
                                    {{ trans('language_changer.no') }}
                                </option>
                            </select>

                            <p class="help-block">{{ trans('language_changer.Reviews').' : '.trans('language_changer.fare_calc_not_work') }}  </p>

                        </div>
                    <?php } ?>

                    <?php if ($setting->page == 6) { ?>
                        <div class="form-group">
                            <label><?php

                                if(Config::get('app.locale') == 'arb'){
                                    $label=trans('language_changer.'.$setting->key);

                                }elseif(Config::get('app.locale') == 'en'){
                                    $label=ucwords(str_replace("_", " ", $setting->key));
                                }

                                echo $label;



                                ?>&nbsp;<a href="#" data-toggle="tooltip" title="<?= $setting->tool_tip; ?>"><img src="<?php echo asset_url(); ?>/image/icon-tooltip.jpg"></a></label>
                            <select class="form-control" name="<?php echo $setting->id; ?>">
                                <option value="1" <?php
                                if ($setting->value == 1) {
                                    echo "selected";
                                }
                                ?> >
                                    {{ trans('language_changer.admins') }}
                                </option>
                                <option value="2" <?php
                                if ($setting->value == 2) {
                                    echo "selected";
                                }
                                ?> >
                                    {{ trans('language_changer.providers') }}
                                </option>
                            </select>
                        </div>
                    <?php } ?>
                    <?php if ($setting->page == 7) { ?>
                        <div class="form-group">
                            <?php
                            if ($setting->key == "transfer") {
                                if (Config::get('app.generic_keywords.Currency') == '$') {
                                    ?>
                                    <label><?php


                                        if(Config::get('app.locale') == 'arb'){
                                            $label=trans('language_changer.'.$setting->key);

                                        }elseif(Config::get('app.locale') == 'en'){
                                            $label=ucwords(str_replace("_", " ", $setting->key));
                                        }

                                        echo $label;



                                        ?>&nbsp;<a href="#" data-toggle="tooltip" title="<?= $setting->tool_tip; ?>"><img src="<?php echo asset_url(); ?>/image/icon-tooltip.jpg"></a></label>
                                    <select class="form-control" name="<?php echo $setting->id; ?>">
                                        <option value="1" <?php
                                        if ($setting->value == 1) {
                                            echo "selected";
                                        }
                                        ?> >
                                            {{ trans('language_changer.yes') }}
                                        </option>
                                        <option value="2" <?php
                                        if ($setting->value == 2) {
                                            echo "selected";
                                        }
                                        ?> >
                                            {{ trans('language_changer.no') }}
                                        </option>
                                    </select>
                                <?php } ?>

                            <?php } else { ?>  
                                <label><?php

                                    if(Config::get('app.locale') == 'arb'){
                                        $label=trans('language_changer.'.$setting->key);

                                    }elseif(Config::get('app.locale') == 'en'){
                                        $label=ucwords(str_replace("_", " ", $setting->key));
                                    }

                                    echo $label;



                                    ?>&nbsp;<a href="#" data-toggle="tooltip" title="<?= $setting->tool_tip; ?>"><img src="<?php echo asset_url(); ?>/image/icon-tooltip.jpg"></a></label>
                                <select class="form-control" name="<?php echo $setting->id; ?>">
                                    <option value="1" <?php
                                    if ($setting->value == 1) {
                                        echo "selected";
                                    }
                                    ?> >
                                        {{ trans('language_changer.yes') }}
                                    </option>
                                    <option value="2" <?php
                                    if ($setting->value == 2) {
                                        echo "selected";
                                    }
                                    ?> >
                                        {{ trans('language_changer.no') }}
                                    </option>
                                </select>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php
                    if ($setting->page == 8) {
                        if ($setting->key != 'paypal' && $setting->key != 'promo_code') {
                            ?>
                            <div class="form-group">
                                <?php if ($setting->key == 'cod') { ?>
                                    <label><?php


                                        if(Config::get('app.locale') == 'arb'){
                                            $label=trans('language_changer.cash_payment_option');

                                        }elseif(Config::get('app.locale') == 'en'){
                                            $label=trans('language_changer.cash_payment_option');
                                        }

                                        echo $label;


                                        //echo ucwords("Cash payment option"); /* ucwords(str_replace("_", " ", $setting->key)); */ ?>&nbsp;<a href="#" data-toggle="tooltip" title="<?= $setting->tool_tip; ?>"><img src="<?php echo asset_url(); ?>/image/icon-tooltip.jpg"></a></label>
                                <?php } else { ?>
                                    <label><?php

                                        if(Config::get('app.locale') == 'arb'){
                                            $label=trans('language_changer.'.$setting->key);

                                        }elseif(Config::get('app.locale') == 'en'){
                                            $label=ucwords(str_replace("_", " ", $setting->key));
                                        }

                                        echo $label;


                                        ?>&nbsp;<a href="#" data-toggle="tooltip" title="<?= $setting->tool_tip; ?>"><img src="<?php echo asset_url(); ?>/image/icon-tooltip.jpg"></a></label>
                                <?php } ?>
                                <select class="form-control" name="<?php echo $setting->id; ?>">
                                    <option value="1" <?php
                                    if ($setting->value == 1) {
                                        echo "selected";
                                    }
                                    ?> >
                                        {{ trans('language_changer.yes') }}
                                    </option>
                                    <option value="0" <?php
                                    if ($setting->value == 0) {
                                        echo "selected";
                                    }
                                    ?> >
                                        {{ trans('language_changer.no') }}
                                    </option>
                                </select>
                            </div>
                            <?php
                        }
                    }
                    $counter++;
                }
                ?>
            </div><!-- /.box-body -->

            <div class="box-footer">

                <button type="submit" class="btn btn-primary btn-flat btn-block">{{ trans('language_changer.update'),' ',trans('language_changer.change'),'s' }}</button>
            </div>
            </form>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title" style="float:<?php echo $align_format; ?>">{{ trans('language_changer.theme_settings') }}</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form enctype="multipart/form-data" onsubmit="Checkfiles(this)" method="post" action="{{ URL::Route('AdminTheme') }}">
                <div class="box-body">
                    <div class="form-group">
                        <label>{{ trans('language_changer.logo') }}</label>
                        <input type="file" class="form-control" name="logo" id="logo"></input>
                        <p class="help-block">{{ trans('language_changer.upload_image_format') }}</p>
                        <?php if (isset($theme->id)) { ?>
                            <img src="<?php echo asset_url(); ?>/uploads/<?= $theme->logo; ?>" height="40" width="40">
                            <br>
                        <?php } ?>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label>{{ trans('language_changer.title'),' ',trans('language_changer.icon') }}</label>
                        <input type="file" class="form-control" name="icon" id="icon"></input>
                        <p class="help-block">{{ trans('language_changer.upload_image_format') }}</p>
                        <?php if (isset($theme->id)) { ?>
                            <img src="<?php echo asset_url(); ?>/uploads/<?= $theme->favicon; ?>" height="40" width="40">
                            <br>
                        <?php } ?>
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" id="theme" class="btn btn-primary btn-flat btn-block">{{ trans('language_changer.update'),' ',trans('language_changer.change'),'s' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">

    $('#picker').colpick({
        layout: 'hex',
        submit: 0,
        colorScheme: 'dark',
        onChange: function (hsb, hex, rgb, el, bySetColor) {
            $(el).css('border-color', '#' + hex);
            // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
            if (!bySetColor)
                $(el).val(hex);
        }
    }).keyup(function () {
        $(this).colpickSetColor(this.value);
    });

    $('#picker1').colpick({
        layout: 'hex',
        submit: 0,
        colorScheme: 'dark',
        onChange: function (hsb, hex, rgb, el, bySetColor) {
            $(el).css('border-color', '#' + hex);
            // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
            if (!bySetColor)
                $(el).val(hex);
        }
    }).keyup(function () {
        $(this).colpickSetColor(this.value);
    });


    $('#picker2').colpick({
        layout: 'hex',
        submit: 0,
        colorScheme: 'light',
        onChange: function (hsb, hex, rgb, el, bySetColor) {
            $(el).css('border-color', '#' + hex);
            // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
            if (!bySetColor)
                $(el).val(hex);
        }
    }).keyup(function () {
        $(this).colpickSetColor(this.value);
    });

    $('#picker3').colpick({
        layout: 'hex',
        submit: 0,
        colorScheme: 'dark',
        onChange: function (hsb, hex, rgb, el, bySetColor) {
            $(el).css('border-color', '#' + hex);
            // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
            if (!bySetColor)
                $(el).val(hex);
        }
    }).keyup(function () {
        $(this).colpickSetColor(this.value);
    });
    $('#picker4').colpick({
        layout: 'hex',
        submit: 0,
        colorScheme: 'dark',
        onChange: function (hsb, hex, rgb, el, bySetColor) {
            $(el).css('border-color', '#' + hex);
            // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
            if (!bySetColor)
                $(el).val(hex);
        }
    }).keyup(function () {
        $(this).colpickSetColor(this.value);
    });

</script>
<script type="text/javascript">
    function Checkfiles()
    {
        var fup = document.getElementById('logo');
        var fileName = fup.value;
        if (fileName != '')
        {
            var ext = fileName.substring(fileName.lastIndexOf('.') + 1);

            if (ext == "PNG" || ext == "png")
            {
                return true;
            }
            else
            {
                alert("{{ trans('language_changer.upload_icon_favicon') }}");
                return false;
            }
        }
        var fup = document.getElementById('icon');
        var fileName1 = fup.value;
        if (fileName1 != '')
        {
            var ext = fileName1.substring(fileName1.lastIndexOf('.') + 1);

            if (ext == "ICO" || ext == "ico")
            {
                return true;
            }
            else
            {
                alert("{{ trans('language_changer.upload_icon_favicon') }}");
                return false;
            }
        }
    }
</script>
<?php if ($success == 1) { ?>
    <script type="text/javascript">
        alert('{{ trans('language_changer.Settings').' '.trans('language_changer.update').' '.trans('language_changer.successfully') }}');
    </script>
<?php } ?>
<?php if ($success == 2) { ?>
    <script type="text/javascript">
        alert('{{ trans('language_changer.wrong') }}');
    </script>
<?php } ?>

<script>
    $(function () {
        $("[data-toggle='tooltip']").tooltip();
    });
</script>

<script type="text/javascript">
    $("#basic").validate({
        rules: {
            1: "required",
            2: "required",
            3: "required",
            4: "required",
            5: "required",
            6: "required",
            7: "required",
            8: "required",
            9: "required",
            10: "required",
            29: "required",
            30: "required",
            31: "required",
            20: {
                required: true,
                email: true
            },
            11: {
                required: true,
            }
        }
    });

</script>

<script type="text/javascript">
   function otpchange(test)
   {
	  if(test == 'false')
	  {
		  alert('{{ trans('language_changer.config_sms_gateway') }}');
		  $('#otp').val(0);
	  }
   }

</script>

@stop