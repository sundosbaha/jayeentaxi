<html>
    <!-- START Head -->
    <head>
        <?php $theme = Theme::all(); ?>
        <!-- START META SECTION -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= $title ?> | <?= Config::get('app.website_title') ?> {{ trans('language_changer.web_dash_board') }}</title>
        <meta name="author" content="pampersdry.info">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
        <?php
        $active = '#000066';
        $favicon = '/image/favicon.ico'; 
        foreach ($theme as $themes) {
            $active = $themes->active_color;
            $favicon = '/uploads/' . $themes->favicon;
            $logo = '/uploads/' . $themes->logo;
        }
        if ($favicon == '/uploads/') {
            $favicon = '/image/favicon.ico';
        }
        if ($logo == '/uploads/') {
            $logo = '/image/logo.png';
        }
        ?>
        <style type="text/css">
            .login_back {

                background-color: green;
            }
        </style>

        <link rel="icon" type="image/ico" href="<?php echo asset_url(); ?><?php echo $favicon; ?>">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo asset_url(); ?>/image/touch/apple-touch-icon-114x114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo asset_url(); ?>/image/touch/apple-touch-icon-72x72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="<?php echo asset_url(); ?>/image/touch/apple-touch-icon-57x57-precomposed.png">
        <link rel="shortcut icon" href="<?php echo asset_url(); ?>/image/touch/apple-touch-icon.png">
        <!--/ END META SECTION -->

        <!-- START STYLESHEETS -->
        <!-- Plugins stylesheet : optional -->

        <!--/ Plugins stylesheet -->

        <!-- Application stylesheet : mandatory -->
        <!--<link rel="stylesheet" href="<?php //echo asset_url(); ?>library/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php //echo asset_url(); ?>stylesheet/layout.min.css">
        <link rel="stylesheet" href="<?php //echo asset_url(); ?>stylesheet/uielement.min.css">

        <link rel="stylesheet"href="<?php //echo asset_url(); ?>plugins/datatables/css/jquery.datatables.min.css">
        -->
        <link href="<?php echo asset_url(); ?>/adminlogins/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo asset_url(); ?>/adminlogins/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo asset_url(); ?>/adminlogins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo asset_url(); ?>/adminlogins/css/style-metro.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo asset_url(); ?>/adminlogins/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo asset_url(); ?>/adminlogins/css/style-responsive.css" rel="stylesheet" type="text/css"/>

        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="<?php echo asset_url(); ?>/adminlogins/css/login-soft.css" rel="stylesheet" type="text/css"/>
        <script src="<?php echo asset_url(); ?>/web/js/validation.js"></script>

        <!--/ END JAVASCRIPT SECTION -->
    </head>
    <!--/ END Head -->


    <body class="login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <h2 dir="{{ trans('language_changer.text_format') }}">{{ trans('language_changer.welcome_to') }} <?= Config::get('app.website_title') ?></h2>
            <img src="<?php echo asset_url(); ?><?php echo $logo; ?>" style="height:50px;">
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            <?php
            $default_user_field_value = "admin@jayeentaxi.com";
            $default_pass_field_value = "1234";
            if ($button == 'Create') {
               /* $default_user_field_value = "admin@alobasha.com";
                $default_pass_field_value = "1234";*/
            }
            ?>

            <form class="form-vertical login-form" action="{{ URL::Route('AdminVerify') }}" method="post">
                <h3 class="form-title" dir="{{ trans('language_changer.text_format') }}" >{{ trans('language_changer.login_to_account') }}</h3>

                <div class="control-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">{{ trans('language_changer.User'),' ',trans('language_changer.name')  }}</label>
                    <div class="controls">
                        <div class="input-icon left">
                            <i class="icon-user"></i>
                            <input name="username"  dir="{{ trans('language_changer.text_format')}}" type="text" class="m-wrap placeholder-no-fix" autocomplete="off" placeholder="{{ trans('language_changer.User'),' ',trans('language_changer.name')  }}" data-parsley-errors-container="#error-container" data-parsley-error-message="Please fill in your username / email" data-parsley-required value="<?= $default_user_field_value ?>">

                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label visible-ie8 visible-ie9">{{ trans('language_changer.password')  }}</label>
                    <div class="controls">
                        <div class="input-icon left">
                            <i class="icon-lock"></i>
                            <input name="password" dir="{{ trans('language_changer.text_format')}}" class="m-wrap placeholder-no-fix" autocomplete="off" type="password" placeholder="{{ trans('language_changer.password')  }}" data-parsley-errors-container="#error-container" data-parsley-error-message="Please fill in your password" data-parsley-required value="<?= $default_pass_field_value ?>">

                        </div>
                    </div>
                </div>
                <div class="form-actions">


                    <button type="submit" class="btn blue pull-right"  dir="{{ trans('language_changer.text_format')}}" >
                        <?//= $button ?> {{ trans('language_changer.go') }}&nbsp;<!--<i class="m-icon-swapright m-icon-white"></i>-->
                    </button>            
                </div>

            </form>
            <!-- END LOGIN FORM -->        

        </div>
        <!-- END LOGIN -->


        <!-- START JAVASCRIPT SECTION (Load javascripts at bottom to reduce load time) -->
        <!-- Library script : mandatory -->

        <!--/ Library script -->


        <!--/ App and page level scrip -->
        <!--/ END JAVASCRIPT SECTION -->
        <?php if ($error) { ?>
            <script type="text/javascript">
    alert('{{ trans('language_changer.invalid_username_or_password') }}');
            </script>
        <?php } ?>
    </body>
    <!--/ END Body -->
</html>
