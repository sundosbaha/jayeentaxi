<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo Lang::get('dispatcher.dispatcher'); ?></title>
    <link rel="stylesheet" type="text/css"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo asset_url(); ?>/dispatcher/css/custom.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo asset_url(); ?>/dispatcher/css/datepicker.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo asset_url(); ?>/dispatcher/css/bootstrap-timepicker.min.css"/>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo asset_url(); ?>/dispatcher/css/bootstrap-fileupload.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo asset_url(); ?>/dispatcher/css/datatable.css"/>
    <!--<link rel="stylesheet" type="text/css" href="css/bootstrap-timepicker.min.css"/>-->
</head>

<body class="loaded">


<nav class="navbar navbar-inverse" style="background:#000906;border-color:white;">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" style="color:white;" href="#"><?php echo Lang::get('dispatcher.dispatcher'); ?></a>
        </div>
        <style>
            .navbar-inverse .navbar-nav > .active > a {
                background: #23374a;
            }
        </style>
        <ul class="nav navbar-nav">
            <li <?php if ($page == 'tracking') {
                echo 'class="active"';
            } ?>><a href="<?php echo asset_url() . '/dispatch/tracking'; ?>" style="color:white;"><span
                            class="glyphicon glyphicon-map-marker"></span><?php echo Lang::get('dispatcher.tracking'); ?>
                </a></li>
            <li <?php if ($page == 'booking') {
                echo 'class="active"';
            } ?> ><a href="<?php echo asset_url() . '/dispatch/booking'; ?>" style="color:white;"><span
                            class="glyphicon glyphicon-book"></span> <?php echo Lang::get('dispatcher.booking'); ?></a>
            </li>
            <li <?php if ($page == 'schedule') {
                echo 'class="active"';
            } ?>><a href="<?php echo asset_url() . '/dispatch/schedule_view'; ?>" style="color:white;"><span
                            class="glyphicon glyphicon-book"></span> <?php echo Lang::get('dispatcher.schdule'); ?></a>
            </li>

            <?php if(Session::get('user_type') == 'admin') { ?>
            <li <?php if ($page == 'user') {
                echo 'class="active"';
            } ?>><a style="color:white;" href="<?php echo asset_url() . '/dispatch/user'; ?>"><span
                            class="glyphicon glyphicon-education"></span> Users</a></li> <?php }?>
        <!-- <?php if(Session::get('user_type') == 'admin') { ?> <li <?php if ($page == 'settings') {
            echo 'class="active"';
        } ?>><a href="<?php echo asset_url() . '/dispatch/setting'; ?>"><span class="glyphicon glyphicon-education"></span> Settings</a></li> <?php }?> -->
        </ul>
        <ul class="nav navbar-nav navbar-right">

            <li><a href="#" style="color:white;"><span
                            class="glyphicon glyphicon-user"></span><?php Session::get('user_name'); ?></a></li>
            <li><a style="color:white;" href="<?php echo asset_url() . '/dispatch/logout'; ?>"><span
                            class="glyphicon glyphicon-log-in"></span> <?php echo Lang::get('dispatcher.sign-out'); ?>
                </a></li>


        </ul>
    </div>
</nav>

@yield('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<!--<script src="js/bootstrap-datepicker.js"></script>-->
<!--<script src="js/bootstrap-timepicker.js"></script>-->
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>

@yield('javascript')

</body>
</html>