<html>
    <!-- START Head -->
    <head>





        <?php $theme = Theme::all();

     //   Session::flush();


        ?>
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



            .forget_password{
                text-align: center;
                margin-left: 60px;
                margin-bottom: 10px;
                color: red;
                text-decoration-line: underline;
            }


            span:hover{
                cursor:pointer;
            }

            .modal_pop {
                display: none; /* Hidden by default */
                position: fixed; /* Stay in place */
                z-index: 1; /* Sit on top */
                padding-top: 100px; /* Location of the box */
                left: 0;
                top: 0;
                width: 100%; /* Full width */
                height: 100%; /* Full height */
                overflow: auto; /* Enable scroll if needed */
                background-color: rgb(0,0,0); /* Fallback color */
                background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            }

            /* Modal Content */
            .modal-content {
                background-color: #fefefe;
                margin: auto;
                padding: 20px;
                border: 1px solid #888;
                width: 30%;
            }

            /* The Close Button */
            .close {
                color: #aaaaaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: #000;
                text-decoration: none;
                cursor: pointer;
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
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


        <!--/ END JAVASCRIPT SECTION -->
    </head>
    <!--/ END Head -->

    <script>

        $(document).ready(function () {



            $("#forget_pass").click(function(){

                $('#myModal').dialog('open');
                return false;

            });
        });

    </script>

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
                <?php if(Session::has('invalid_user') && !empty(Session::get('invalid_user')) ){



                    ?>
                <div class="alert {{ Session::get('alert_type') }}">{{ Session::get('invalid_user') }}</div>
                <?php
                Session::put('invalid_user','');
                Session::put('alert_type','');

                } ?>
                    </div>
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

                    <span class="forget_password" id="myBtn" dir="{{ trans('language_changer.text_format')}}">{{ trans('language_changer.forget'),' ',trans('language_changer.password') }} </span>

                    <button type="submit" class="btn blue pull-right"  dir="{{ trans('language_changer.text_format')}}" >
                        <?//= $button ?> {{ trans('language_changer.go') }}&nbsp;<!--<i class="m-icon-swapright m-icon-white"></i>-->
                    </button>

                </div>

            </form>





            <!-- END LOGIN FORM -->        

        </div>


        <div id="myModal" class="modal_pop">

            <!-- Modal content -->
            <div class="modal-content">
                <span class="close">&times;</span>
                <form method="post"  action="{{ URL::Route('AdminForgetPassword') }}">
                    <label>{{ trans('language_changer.please_enter_your_email_id') }}</label>
                  <div><input type="text" id="email_id" name="email_id" required ></div>
                   <div><button class="btn btn-primary">{{ trans('language_changer.submit') }}</button></div>
                </form>
            </div>

        </div>

        <script>
            // Get the modal
            var modal = document.getElementById('myModal');

            // Get the button that opens the modal
            var btn = document.getElementById("myBtn");

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks the button, open the modal
            btn.onclick = function() {
                modal.style.display = "block";
            }

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>

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
