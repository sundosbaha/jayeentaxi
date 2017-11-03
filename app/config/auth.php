<?php
return array(

    'multi' => array(
        'admin_panel' => array(
            'driver' => 'eloquent',
            'model' => 'Admin'
        ),
        'dispatcher' => array(
            'driver' => 'database',
            'table' => 'userdetail'
        )
    ),

    'reminder' => array(

        'email' => 'emails.auth.reminder',

        'table' => 'password_reminders',

        'expire' => 60,

    ),

);
