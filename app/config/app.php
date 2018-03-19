<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => TRUE,

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => 'localhost',

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Asia/Kolkata',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

        'locale' => 'en',
                           
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                           
    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => 'EylYh3f9D4p3YKxxzZZtghNNph4WMqnh',

    'cipher' => MCRYPT_RIJNDAEL_128,

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => array(

        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
       // 'Illuminate\Auth\AuthServiceProvider',
        'Illuminate\Cache\CacheServiceProvider',
        'Illuminate\Session\CommandsServiceProvider',
        'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
        'Illuminate\Routing\ControllerServiceProvider',
        'Illuminate\Cookie\CookieServiceProvider',
        'Illuminate\Database\DatabaseServiceProvider',
        'Illuminate\Encryption\EncryptionServiceProvider',
        'Illuminate\Filesystem\FilesystemServiceProvider',
        'Illuminate\Hashing\HashServiceProvider',
        'Illuminate\Html\HtmlServiceProvider',
        'Illuminate\Log\LogServiceProvider',
        'Illuminate\Mail\MailServiceProvider',
        'Illuminate\Database\MigrationServiceProvider',
        'Illuminate\Pagination\PaginationServiceProvider',
        'Illuminate\Queue\QueueServiceProvider',
        'Illuminate\Redis\RedisServiceProvider',
        'Illuminate\Remote\RemoteServiceProvider',
        'Illuminate\Auth\Reminders\ReminderServiceProvider',
        'Illuminate\Database\SeedServiceProvider',
        'Illuminate\Session\SessionServiceProvider',
        'Illuminate\Translation\TranslationServiceProvider',
        'Illuminate\Validation\ValidationServiceProvider',
        'Illuminate\View\ViewServiceProvider',
        'Illuminate\Workbench\WorkbenchServiceProvider',
        'Aws\Laravel\AwsServiceProvider',
        'Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider',
        'Way\Generators\GeneratorsServiceProvider',
        'Raahul\LarryFour\LarryFourServiceProvider',
        'Davibennun\LaravelPushNotification\LaravelPushNotificationServiceProvider',
        'Intervention\Image\ImageServiceProvider',
        'Ollieread\Multiauth\MultiauthServiceProvider'

    ),

    /*
    |--------------------------------------------------------------------------
    | Service Provider Manifest
    |--------------------------------------------------------------------------
    |
    | The service provider manifest is used by Laravel to lazy load service
    | providers which are not needed for each request, as well to keep a
    | list of all of the services. Here, you may set its storage spot.
    |
    */

    'manifest' => storage_path().'/meta',

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are lazy loaded so they don't hinder performance.
    |
    */

    'aliases' => array(

        'App'               => 'Illuminate\Support\Facades\App',
        'Artisan'           => 'Illuminate\Support\Facades\Artisan',
        'Auth'              => 'Illuminate\Support\Facades\Auth',
        'Blade'             => 'Illuminate\Support\Facades\Blade',
        'Cache'             => 'Illuminate\Support\Facades\Cache',
        'ClassLoader'       => 'Illuminate\Support\ClassLoader',
        'Config'            => 'Illuminate\Support\Facades\Config',
        'Controller'        => 'Illuminate\Routing\Controller',
        'Cookie'            => 'Illuminate\Support\Facades\Cookie',
        'Crypt'             => 'Illuminate\Support\Facades\Crypt',
        'DB'                => 'Illuminate\Support\Facades\DB',
        'Eloquent'          => 'Illuminate\Database\Eloquent\Model',
        'Event'             => 'Illuminate\Support\Facades\Event',
        'File'              => 'Illuminate\Support\Facades\File',
        'Form'              => 'Illuminate\Support\Facades\Form',
        'Hash'              => 'Illuminate\Support\Facades\Hash',
        'HTML'              => 'Illuminate\Support\Facades\HTML',
        'Input'             => 'Illuminate\Support\Facades\Input',
        'Lang'              => 'Illuminate\Support\Facades\Lang',
        'Log'               => 'Illuminate\Support\Facades\Log',
        'Mail'              => 'Illuminate\Support\Facades\Mail',
        'Paginator'         => 'Illuminate\Support\Facades\Paginator',
        'Password'          => 'Illuminate\Support\Facades\Password',
        'Queue'             => 'Illuminate\Support\Facades\Queue',
        'Redirect'          => 'Illuminate\Support\Facades\Redirect',
        'Redis'             => 'Illuminate\Support\Facades\Redis',
        'Request'           => 'Illuminate\Support\Facades\Request',
        'Response'          => 'Illuminate\Support\Facades\Response',
        'Route'             => 'Illuminate\Support\Facades\Route',
        'Schema'            => 'Illuminate\Support\Facades\Schema',
        'Seeder'            => 'Illuminate\Database\Seeder',
        'Session'           => 'Illuminate\Support\Facades\Session',
        'SoftDeletingTrait' => 'Illuminate\Database\Eloquent\SoftDeletingTrait',
        'SSH'               => 'Illuminate\Support\Facades\SSH',
        'Str'               => 'Illuminate\Support\Str',
        'URL'               => 'Illuminate\Support\Facades\URL',
        'Validator'         => 'Illuminate\Support\Facades\Validator',
        'View'              => 'Illuminate\Support\Facades\View',
        'AWS' => 'Aws\Laravel\AwsFacade',
        'PushNotification' => 'Davibennun\LaravelPushNotification\Facades\PushNotification',
        'Image' => 'Intervention\Image\Facades\Image',
    ),
    'menu_titles' => array(
        'admin_control' => 'Admin Control',
        'income_history' => 'Income History',
        'log_out' => 'Log Out',
        'dashboard' => 'Dashboard',
        'map_view' => 'Map View',
        'providers' => 'Providers',
        'requests' => 'Requests',
        'customers' => 'Customers',
        'reviews' => 'Reviews',
        'information' => 'Information',
        'types' => 'Types',
        'documents' => 'Documents',
        'settings' => 'Settings',
        'balance' => 'Balance',
        'create_request' => 'Create Request',
        'promotional_codes' => 'Promotional Codes',
    ),
    'generic_keywords'=> array(
        'Provider' => 'Driver',
        'User' => 'User',
        'Services' => 'Taxi',
        'Trip' => 'Trip',
        'Currency' => 'JOD',
        'total_trip' => '1',
        'cancelled_trip' => '4',
        'total_payment' => '5',
        'completed_trip' => '4',
        'card_payment' => '6',
        'credit_payment' => '32',
    ),
    /* DEVICE PUSH NOTIFICATION DETAILS */
    'customer_certy_url' => 'http://192.168.0.26/uberforx_new/api/public/apps/ios_push/iph_cert/Client_certy.pem',
    'customer_certy_pass' => '123456',
    'customer_certy_type' => '0',
    'provider_certy_url' => 'http://192.168.0.26/uberforx_new/api/public/apps/ios_push/walker/iph_cert/Walker_certy.pem',
    'provider_certy_pass' => '123456',
    'provider_certy_type' => '0',
    'gcm_browser_key' => 'AIzaSyAXatDcDqW3hERFA-zpeX86juvvWQr8ycM',
    /* DEVICE PUSH NOTIFICATION DETAILS END */
    'currency_symb' => 'JOD', 
    
    /* Developer Company Details */
    'developer_company_name' => '',
    'developer_company_web_link' => '', 
    'developer_company_email' => 'admin@jayeentaxi.com', 
    'developer_company_fb_link' => '#', 
    'developer_company_twitter_link' => '#',
    /* Developer Company Details END */
    
    /* APP LINK DATA */
    'android_client_app_url'=>'http://www.google.com',
    'android_provider_app_url'=>'http://www.apple.com',
    'ios_client_app_url'=>'http://www.apple.com',
    'ios_provider_app_url'=>'http://www.google.com',
    /* APP LINK DATA END */
    
    'no_data_available' => 'History not availalbe.', 
    'data_not_available' => 'Data not availalbe.', 
    'blank_fiend_val' => 'N/A',

    'website_title' => 'Jayeen Taxi',
    'referral_prefix' => 'TNN',
    'referral_zero_len' => 10,
    'website_meta_description' => '',
    'website_meta_keywords' => '',
	'email_color' => '#039be6',

    's3_bucket' => '',

    'twillo_account_sid' => 'AC7f844250d0947d120750ec87df0c5b2f',
    'twillo_auth_token' => '69c494cfc69bbde15030be6700862dc7',
    'twillo_number' => '+14847256990',

    'production' => false,

    'default_payment' => 'braintree',

    'stripe_secret_key' => 'sk_test_kvvz65I6KE8WD52mlVcPZyKd',
    'stripe_publishable_key' => 'pk_test_32zokmkeyujygxDUl2sQ44Ds',
    'braintree_environment' => 'sandbox',
    'braintree_merchant_id' => 'qmbcjt39m9wd5b6c',
    'braintree_public_key' => '3j57tp68hd46c5st',
    'braintree_private_key' => '486730adad07420fe4c2d8ced57ef3da',
    'braintree_cse' => 'MIIBCgKCAQEAvAohp4NRB/JrYHBfzlXAs0F7b34AHp1Y6k5vdUoVPkq84oFwbpzM6o5oUPczdB0gTcB45fAfxzD65HBcJS+vUEG8hfFB6WMryeMlLDHYe2cigPZnQNJhLMRRAocLpdLBMjzEo4COhi2XW7HwmL3gs+FUawuJhQSzltX6nJaYW7mtRF/vUZgdMDQvtU/7hPaeYdDrvmjAe3QH+wG8ZKzhKx8rjowP5WQ4ASUjiDHnnnvNpVbJNkdsXBNr6WjdwCdnB1vNBp2/yWbnA0a/zWPNd9IwM09QR06mIyyVKFHUcgpJFSwjKfCH0HQ1tzZ0m6a3Ld6l2tywLbJxd4CHTF0QlQIDAQAB',
        
    'coinbaseAPIKey' => 'g0xKQqKRwNj84IW9',
    'coinbaseAPISecret' => 'iEHiMMeUXWEGHV02M3lcxt8evBPaOzlC',

    'paypal_sdk_mode' => 'sandbox',
    'paypal_sdk_UserName' => 'npavankumar34-buyer_api1.gmail.com',
    'paypal_sdk_Password' => 'WUUPVM3ETSJ6CARS',
    'paypal_sdk_Signature' => 'AnIGq3pWk8Gb1yRu1ZjCY0N3ccikAdq-3A6AHjDQPytHJVE2N4d6jeWH',
    'paypal_sdk_AppId' => 'APP-80W284485P519543T',

);
