<?php

use Stripe\Stripe as Stripe;
use Stripe\Account as Account;
use Stripe\Transfer as Stripe_Transfer;
class AdminController extends BaseController {

    public function __construct() {
        TableController::check();

        if(!Schema::hasTable('wallet_add_history'))
        {
            Self::wallet_add_history();
        }
        if(!Schema::hasTable('wallet_spend_history'))
        {
            Self::wallet_spend_history();
        }
        if(!Schema::hasTable('wallet_user_state'))
        {
            Self::wallet_user_state();
        }

        $this->beforeFilter(function () {

            if (!Auth::admin_panel()->check()) {
                $url = URL::current();
                $routeName = Route::currentRouteName();
				
                Log::info('current route =' . print_r(Route::currentRouteName(), true));

                if ($routeName != "AdminLogin" && $routeName != 'admin') {
                    Session::put('pre_admin_login_url', $url);
                }
                return Redirect::to('/admin/login');
            }

        }, array('except' => array('login', 'verify', 'add', 'walker_xml')));
		
		 
		 $urll=$url=DB::table('privilege')
                     ->select(DB::raw('url'))
                     ->where('userid', '=', Session::get('admin_id'))
                     ->get();


		 $url=Route::currentRouteName();

		
		if(inarray($url,$urll))
		 {

			 echo "<script>alert('No Access To this Page');window.location.href='".URL::previous()."'</script>";
			 
			 return URL::previous();
		 }
		 
               
		
    }

    private function _braintreeConfigure() {
        Braintree_Configuration::environment(Config::get('app.braintree_environment'));
        Braintree_Configuration::merchantId(Config::get('app.braintree_merchant_id'));
        Braintree_Configuration::publicKey(Config::get('app.braintree_public_key'));
        Braintree_Configuration::privateKey(Config::get('app.braintree_private_key'));
    }

    public function add() {
        $user = new Admin;
        $user->username = Input::get('username');
        $user->password = $user->password = Hash::make(Input::get('password'));
        $user->save();
    }



    public function email_views(){


        $driver=Walker::find('13');


        $req=DB::table('request')->where('id','=','71')->first();

        $mail_body['name']='bala';
        $mail_body['req_id']='18';
        $mail_body['walker_name']='test';
        $mail_body['driver_name']='test';
        $mail_body['driver_contact']='12345888';
        $mail_body['driver_car_model']='CT100';
        $mail_body['driver_licence']='lic';
        $mail_body['admin_eamil']="bala@gmail.com";
        $mail_body['trip_id']="12";
        $mail_body['amount']="121212";
        $mail_body['login_url']="exx";
        $mail_body['request']=$req;
        $mail_body['client_name']="ioyiu";
        $mail_body['walker']=$driver;
        $mail_body['map_url']='v';
        $mail_body['start_address']='0';
        $mail_body['end_address']=0;
        $mail_body['start']=(object) array('created_at' => '0');
        $mail_body['end']=(object) array('created_at' => '0');
        $mail_body['type_name']="c";
        $mail_body['base_price']="0";
        $mail_body['dist_cost']=0;
        $mail_body['time_cost']=0;
        $mail_body['ref_bonus']=0;
        $mail_body['promo_bonus']=0;

        $mail_body['new_password']="571moj";
        $mail_body['follow_url']="57181785";
        $mail_body['web_url']="57181785";
        $mail_body['email_data']="dat";
        $email_data['name']='cc';
        $email_data['password']='CCC';
        $email_data['url']='ff';

        $walker_name="xxx";
        /*return View::make('emails.invoice')*/
        return View::make('emails.invoice')
            ->with('driver',$driver)
            ->with('trip_id','12')
            ->with('email_data',$email_data)
            ->with('walker_name',$walker_name)
            ->with('mail_body',$mail_body);

    }

    public function report() {


        $braintree_environment = Config::get('app.braintree_environment');
        $braintree_merchant_id = Config::get('app.braintree_merchant_id');
        $braintree_public_key = Config::get('app.braintree_public_key');
        $braintree_private_key = Config::get('app.braintree_private_key');
        $braintree_cse = Config::get('app.braintree_cse');
        $twillo_account_sid = Config::get('app.twillo_account_sid');
        $twillo_auth_token = Config::get('app.twillo_auth_token');
        $twillo_number = Config::get('app.twillo_number');
        $stripe_publishable_key = Config::get('app.stripe_publishable_key');
        $default_payment = Config::get('app.default_payment');
        $stripe_secret_key = Config::get('app.stripe_secret_key');
        $mail_driver = Config::get('mail.mail_driver');
        $email_name = Config::get('mail.from.name');
        $email_address = Config::get('mail.from.address');
        $mandrill_secret = Config::get('services.mandrill_secret');
        /* DEVICE PUSH NOTIFICATION DETAILS */
        $customer_certy_url = Config::get('app.customer_certy_url');
        $customer_certy_pass = Config::get('app.customer_certy_pass');
        $customer_certy_type = Config::get('app.customer_certy_type');
        $provider_certy_url = Config::get('app.provider_certy_url');
        $provider_certy_pass = Config::get('app.provider_certy_pass');
        $provider_certy_type = Config::get('app.provider_certy_type');
        $gcm_browser_key = Config::get('app.gcm_browser_key');
        /* DEVICE PUSH NOTIFICATION DETAILS END */
        $install = array(
            'braintree_environment' => $braintree_environment,
            'braintree_merchant_id' => $braintree_merchant_id,
            'braintree_public_key' => $braintree_public_key,
            'braintree_private_key' => $braintree_private_key,
            'braintree_cse' => $braintree_cse,
            'twillo_account_sid' => $twillo_account_sid,
            'twillo_auth_token' => $twillo_auth_token,
            'twillo_number' => $twillo_number,
            'stripe_publishable_key' => $stripe_publishable_key,
            'stripe_secret_key' => $stripe_secret_key,
            'mail_driver' => $mail_driver,
            'email_address' => $email_address,
            'email_name' => $email_name,
            'mandrill_secret' => $mandrill_secret,
            'default_payment' => $default_payment,
            /* DEVICE PUSH NOTIFICATION DETAILS */
            'customer_certy_url' => $customer_certy_url,
            'customer_certy_pass' => $customer_certy_pass,
            'customer_certy_type' => $customer_certy_type,
            'provider_certy_url' => $provider_certy_url,
            'provider_certy_pass' => $provider_certy_pass,
            'provider_certy_type' => $provider_certy_type,
            'gcm_browser_key' => $gcm_browser_key,
                /* DEVICE PUSH NOTIFICATION DETAILS END */
        );
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        $submit = Input::get('submit');
        $walker_id = Input::get('walker_id');
        $owner_id = Input::get('owner_id');
        $status = Input::get('status');

        $type1='0'.'&';
        if(Input::has('start_date')){
            $type1.= 'start_date'.'='.$start_date.'&';
        }
        else{
            $type1.= 'start_date'.'='.''.'&';
        }

        if(Input::has('end_date')){
            $type1.= 'end_date'.'='.$end_date.'&';
        }
        else{
            $type1.= 'end_date'.'='.''.'&';
        }

        if(Input::has('status')){
            $type1.= 'status'.'='.$status.'&';
        }
        else{
            $type1.= 'status'.'='.''.'&';

        }
        if(Input::has('walker_id')){
            $type1.= 'walker_id'.'='.$walker_id.'&';
        }
        else{
            $type1.= 'walker_id'.'='.''.'&';

        }
        if(Input::has('owner_id')){
            $type1.= 'owner_id'.'='.$owner_id.'&';
        }
        else{
            $type1.= 'owner_id'.'='.''.'&';

        }
        if(Input::has('submit')){
           // $type1.= 'submit'.'='.$submit;
        }
        else{
           // $type1.= 'submit'.'=';

        }

        Session::put('type',$type1);

        $start_time = date("Y-m-d H:i:s", strtotime($start_date));
        $end_time = date("Y-m-d H:i:s", strtotime($end_date));
        $start_date = date("Y-m-d", strtotime($start_date));
        $end_date = date("Y-m-d", strtotime($end_date));


        $query = DB::table('request')
                ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->leftJoin('walker_type', 'walker.type', '=', 'walker_type.id');

        if (Input::get('start_date') && Input::get('end_date')) {
            $query = $query->where('request_start_time', '>=', $start_time)
                    ->where('request_start_time', '<=', $end_time);
        }

        if (Input::get('walker_id') && Input::get('walker_id') != 0) {
            $query = $query->where('request.confirmed_walker', '=', $walker_id);
        }

        if (Input::get('owner_id') && Input::get('owner_id') != 0) {
            $query = $query->where('request.owner_id', '=', $owner_id);
        }

        if (Input::get('status') && Input::get('status') != 0) {
            if ($status == 1) {
                $query = $query->where('request.is_completed', '=', 1);
            } else {
                $query = $query->where('request.is_cancelled', '=', 1);
            }
        } else {

            $query = $query->where(function ($que) {
                $que->where('request.is_completed', '=', 1)
                        ->orWhere('request.is_cancelled', '=', 1);
            });
        }

       if(Input::get('submit') && Input::get('submit') == 'Download_Report'){

           $walks = $query->select('request.request_start_time', 'walker_type.name as type', 'request.ledger_payment', 'request.card_payment', 'owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'request.id as id', 'request.created_at as date', 'request.is_started', 'request.is_walker_arrived', 'request.payment_mode', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker'
               , 'request.status', 'request.time', 'request.distance', 'request.total', 'request.is_cancelled', 'request.promo_payment');
           $walks = $walks->orderBy('id', 'DESC')->get();

       }
       else{

           $walks = $query->select('request.request_start_time', 'walker_type.name as type', 'request.ledger_payment', 'request.card_payment', 'owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'request.id as id', 'request.created_at as date', 'request.is_started', 'request.is_walker_arrived', 'request.payment_mode', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker'
               , 'request.status', 'request.time', 'request.distance', 'request.total', 'request.is_cancelled', 'request.promo_payment');

           $walks = $walks->orderBy('id', 'DESC')->paginate(10);

       }



        $query = DB::table('request')
                ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->leftJoin('walker_type', 'walker.type', '=', 'walker_type.id');

        if (Input::get('start_date') && Input::get('end_date')) {
            $query = $query->where('request_start_time', '>=', $start_time)
                    ->where('request_start_time', '<=', $end_time);
        }

        if (Input::get('walker_id') && Input::get('walker_id') != 0) {
            $query = $query->where('request.confirmed_walker', '=', $walker_id);
        }

        if (Input::get('owner_id') && Input::get('owner_id') != 0) {
            $query = $query->where('request.owner_id', '=', $owner_id);
        }

        $completed_rides = $query->where('request.is_completed', 1)->count();


        $query = DB::table('request')
                ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->leftJoin('walker_type', 'walker.type', '=', 'walker_type.id');

        if (Input::get('start_date') && Input::get('end_date')) {
            $query = $query->where('request_start_time', '>=', $start_time)
                    ->where('request_start_time', '<=', $end_time);
        }

        if (Input::get('walker_id') && Input::get('walker_id') != 0) {
            $query = $query->where('request.confirmed_walker', '=', $walker_id);
        }

        if (Input::get('owner_id') && Input::get('owner_id') != 0) {
            $query = $query->where('request.owner_id', '=', $owner_id);
        }
        $cancelled_rides = $query->where('request.is_cancelled', 1)->count();


        $query = DB::table('request')
                ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->leftJoin('walker_type', 'walker.type', '=', 'walker_type.id');

        if (Input::get('start_date') && Input::get('end_date')) {
            $query = $query->where('request_start_time', '>=', $start_time)
                    ->where('request_start_time', '<=', $end_time);
        }

        if (Input::get('walker_id') && Input::get('walker_id') != 0) {
            $query = $query->where('request.confirmed_walker', '=', $walker_id);
        }

        if (Input::get('owner_id') && Input::get('owner_id') != 0) {
            $query = $query->where('request.owner_id', '=', $owner_id);
        }

        $card_payment = $query->where('request.is_completed', 1)->sum('request.card_payment');


        $query = DB::table('request')
                ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->leftJoin('walker_type', 'walker.type', '=', 'walker_type.id');

        if (Input::get('start_date') && Input::get('end_date')) {
            $query = $query->where('request_start_time', '>=', $start_time)
                    ->where('request_start_time', '<=', $end_time);
        }

        if (Input::get('walker_id') && Input::get('walker_id') != 0) {
            $query = $query->where('request.confirmed_walker', '=', $walker_id);
        }

        if (Input::get('owner_id') && Input::get('owner_id') != 0) {
            $query = $query->where('request.owner_id', '=', $owner_id);
        }
        $credit_payment = $query->where('request.is_completed', 1)->sum('request.ledger_payment');

       // $cash_payment=0;
         $cash_payment = $query->where('request.payment_mode', 1)->sum('request.total');
        $cash_payment=$cash_payment;


        if (Input::get('submit') && Input::get('submit') == 'Download_Report') {


            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=data.csv');
            $handle = fopen('php://output', 'w');
            $settings = Settings::where('key', 'default_distance_unit')->first();
            $unit = $settings->value;
            if ($unit == 0) {
                $unit_set = 'kms';
            } elseif ($unit == 1) {
                $unit_set = 'miles';
            }


            fputcsv($handle, array('ID', 'Date', 'Type of Service', 'Provider', 'Owner', 'Distance (' . $unit_set . ')', 'Time (Minutes)', 'Payment Mode', 'Earning', 'Referral Bonus', 'Promotional Bonus', 'Card Payment'));



            foreach ($walks as $request) {
                $pay_mode = "Card Payment";
                if ($request->payment_mode == 1) {
                    $pay_mode = "Cash Payment";
                }
                fputcsv($handle, array(
                    $request->id,
                    date('l, F d Y h:i A', strtotime($request->request_start_time)),
                    $request->type,
                    $request->walker_first_name . " " . $request->walker_last_name,
                    $request->owner_first_name . " " . $request->owner_last_name,
                    sprintf2($request->distance, 2),
                    sprintf2($request->time, 2),
                    $pay_mode,
                    sprintf2($request->total, 2),
                    sprintf2($request->ledger_payment, 2),
                    sprintf2($request->promo_payment, 2),
                    sprintf2($request->card_payment, 2),
                ));
            }
            /*echo 'hello';
            die();*/

            fputcsv($handle, array());
            fputcsv($handle, array());
            fputcsv($handle, array('Total Trips', $completed_rides + $cancelled_rides));
            fputcsv($handle, array('Completed Trips', $completed_rides));
            fputcsv($handle, array('Cancelled Trips', $cancelled_rides));
            fputcsv($handle, array('Total Payments', sprintf2(($credit_payment + $card_payment), 2)));
            fputcsv($handle, array('Card Payment', sprintf2($card_payment, 2)));
            fputcsv($handle, array('Credit Payment', $credit_payment));

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
        } else {
            /* $currency_selected = Keywords::where('alias', 'Currency')->first();
              $currency_sel = $currency_selected->keyword; */
            $currency_sel = Config::get('app.generic_keywords.Currency');
            $walkers = Walker::get();
            $owners = Owner::get();
            $title = ucwords(trans('language_changer.Dashboard'));

            if(Config::get('app.locale') == 'arb'){
                $align_format="right";
            }elseif(Config::get('app.locale') == 'en'){
                $align_format="left";
            }



            return View::make('dashboard')
                            ->with('title', $title)
                            ->with('page', 'dashboard')
                            ->with('walks', $walks)
                            ->with('owners', $owners)
                            ->with('walkers', $walkers)
                            ->with('completed_rides', $completed_rides)
                            ->with('cancelled_rides', $cancelled_rides)
                            ->with('card_payment', $card_payment)
                            ->with('install', $install)
                            ->with('currency_sel', $currency_sel)
                            ->with('cash_payment', $cash_payment)
                            ->with('credit_payment', $credit_payment)
                            ->with('align_format',$align_format);
        }
    }

    //admin control

    public function admins() {
        Session::forget('type');
        Session::forget('valu');
        $admins = Admin::paginate(10);
        $title = ucwords(trans('language_changer.admin_control'));
        return View::make('admins')
                        ->with('title', $title)
                        ->with('page', 'settings')
                        ->with('admin', $admins);
    }

    public function add_admin() {
        $admin = Admin::all();
        return View::make('add_admin')
                        ->with('title', 'Add Admin')
                        ->with('page', 'add_admin')
                        ->with('admin', $admin);
    }

    public function add_admin_do() {
        $username = Input::get('username');
        $password = Input::get('password');

        $validator = Validator::make(
                        array(
                    'username' => $username,
                    'password' => $password,
                        ), array(
                    'username' => 'required',
                    'password' => 'required|min:6'
                        )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->first();
            Session::put('msg', $error_messages);
            $admin = Admin::all();
            return View::make('add_admin')
                            ->with('title', 'Add Admin')
                            ->with('page', 'add_admin')
                            ->with('admin', $admin);
        } else {

            $admin = new Admin;
            $password = Hash::make(Input::get('password'));
            $admin->username = $username;
            $admin->password = $admin->password = $password;
            $admin->save();
            return Redirect::to("/admin/admins?success=1");
        }
    }

    public function edit_admins() {
        $id = Request::segment(4);
        $success = Input::get('success');
        $admin = Admin::find($id);
        Log::info("admin = " . print_r($admin, true));
        $title = ucwords( trans('language_changer.edit').' '.trans('language_changer.admins')  . " : " . $admin->username);
        if ($admin) {
            return View::make('edit_admin')
                            ->with('title', $title)
                            ->with('page', 'settings')
                            ->with('success', $success)
                            ->with('admin', $admin);
        } else {
            return View::make('notfound')->with('title', 'Error Page Not Found')->with('page', 'Error Page Not Found');
        }
    }

    public function update_admin() {

        $admin = Admin::find(Input::get('id'));
        $username = Input::get('username');
        $old_pass = Input::get('old_password');
        $new_pass = Input::get('new_password');
        $address = Input::get('my_address');
        $latitude = Input::get('latitude');
        $longitude = Input::get('longitude');

        $validator = Validator::make(
                        array(
                    'username' => $username,
                    'old_pass' => $old_pass,
                    'new_pass' => $new_pass,
                        ), array(
                    'username' => 'required',
                    'old_pass' => 'required',
                    'new_pass' => 'required|min:6'
                        )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->first();
            Session::put('msg', $error_messages);
            if ($admin) {
                $title = ucwords(trans('language_changer.edit').' '.trans('language_changer.admins')   . " : " . $admin->username);
                return View::make('edit_admin')
                                ->with('title', $title)
                                ->with('page', 'admins')
                                ->with('success', '')
                                ->with('admin', $admin);
            } else {

                return View::make('notfound')->with('title', trans('language_changer.page_not_found'))->with('page', trans('language_changer.page_not_found'));
            }
        } else {

            $admin->username = $username;
            $admin->latitude = $latitude;
            $admin->longitude = $longitude;
            $admin->address = $address;

            if ($new_pass != NULL) {
                $check_pass = Hash::check($old_pass, $admin->password);
                if ($check_pass) {
                    $admin->password = $admin->password = Hash::make($new_pass);
                    Log::info('admin password changed');
                }
            }
            $admin->save();
            return Redirect::to("/admin/admins");
        }
    }

    public function delete_admin() {
        $id = Request::segment(4);
        $success = Input::get('success');
        $admin = Admin::find($id);
        if ($admin) {
            Admin::where('id', $id)->delete();
            return Redirect::to("/admin/admins?success=1");
        } else {
            return View::make('notfound')->with('title', trans('language_changer.page_not_found'))->with('page', trans('language_changer.page_not_found'));
        }
    }

    public function banking_provider() {
        $id = Request::segment(4);
        $success = Input::get('success');
        $provider = Walker::find($id);
        if ($provider) {
            $title = ucwords(trans('language_changer.banking').' ' .trans('language_changer.details').' '. " : " . $provider->first_name . " " . $provider->last_name);
            if (Config::get('app.default_payment') == 'stripe') {
                return View::make('banking_provider_stripe')
                                ->with('title', $title)
                                ->with('page', 'walkers')
                                ->with('success', $success)
                                ->with('provider', $provider);
            } else {
                return View::make('banking_provider_braintree')
                                ->with('title', $title)
                                ->with('page', 'walkers')
                                ->with('success', $success)
                                ->with('provider', $provider);
            }
        } else {
            return View::make('notfound')->with('title', trans('language_changer.page_not_found'))->with('page', trans('language_changer.page_not_found'));
        }
    }

    public function providerB_bankingSubmit1() {
        $this->_braintreeConfigure();
        $result = new stdClass();
        $result = Braintree_MerchantAccount::create(
                        array(
                            'individual' => array(
                                'firstName' => Input::get('first_name'),
                                'lastName' => Input::get('last_name'),
                                'email' => Input::get('email'),
                                'phone' => Input::get('phone'),
                                'dateOfBirth' => date('Y-m-d', strtotime(Input::get('dob'))),
                                'ssn' => Input::get('ssn'),
                                'address' => array(
                                    'streetAddress' => Input::get('streetAddress'),
                                    'locality' => Input::get('locality'),
                                    'region' => Input::get('region'),
                                    'postalCode' => Input::get('postalCode')
                                )
                            ),
                            'funding' => array(
                                'descriptor' => 'UberForX',
                                'destination' => Braintree_MerchantAccount::FUNDING_DESTINATION_BANK,
                                'email' => Input::get('bankemail'),
                                'mobilePhone' => Input::get('bankphone'),
                                'accountNumber' => Input::get('accountNumber'),
                                'routingNumber' => Input::get('routingNumber')
                            ),
                            'tosAccepted' => true,
                            'masterMerchantAccountId' => Config::get('app.masterMerchantAccountId'),
                            'id' => "taxinow" . Input::get('id')
                        )
        );

        Log::info('res = ' . print_r($result, true));
        if ($result->success) {
            $pro = Walker::where('id', Input::get('id'))->first();
            $pro->merchant_id = $result->merchantAccount->id;
            $pro->save();
            Log::info(print_r($pro, true));
            Log::info('Adding banking details to provider from Admin = ' . print_r($result, true));
            return Redirect::to("/admin/providers");
        } else {
            Log::info('Error in adding banking details: ' . $result->message);
            return Redirect::to("/admin/providers");
        }
    }


    public function providerB_bankingSubmit() {


        /* $isDefault = WalkerBankingDetails::where('walker_id', '=', Input::get('id'))->where('is_default', '=', 1)->get();
        $dob = Input::get('dob');
        $dob = date("Y-m-d H:i:s", strtotime($dob));
        $ssn = Input::get('ssn');
        $account_number = Input::get('accountNumber');
        $routing_number = Input::get('routingNumber');
        $is_default = Input::get('is_default');

        $bankingDetails = new WalkerBankingDetails();
        $bankingDetails->walker_id = Input::get('id');
        $bankingDetails->dob = $dob;
        $bankingDetails->ssn = (!empty($ssn) ? $ssn : '');
        $bankingDetails->account_number = (!empty($account_number) ? $account_number : '');
        $bankingDetails->routing_number = (!empty($routing_number) ? $routing_number : '');
        if(count($isDefault) > 0 && $is_default == "yes") {
            WalkerBankingDetails::where('walker_id', Input::get('id'))->update(array('is_default' => 0,'merchant_id','=',''));
            $bankingDetails->merchant_id = (!empty($result->merchantAccount->id) ? $result->merchantAccount->id : '');
        }
        $bankingDetails->is_default = (!empty($is_default == "yes") ? 1 : 0);
        $bankingDetails->save();
        echo "success";
        exit; */


        $this->_braintreeConfigure();
        /* $merchantAccountIterator = Braintree_MerchantAccount::find(Config::get('app.masterMerchantAccountId'));

         echo "<pre>";
         print_r($merchantAccountIterator);

         die(); */

        $result = new stdClass();
        $result = Braintree_MerchantAccount::create(
            array(
                'individual' => array(
                    'firstName' => Input::get('first_name'),
                    'lastName' => Input::get('last_name'),
                    'email' => Input::get('email'),
                    'phone' => Input::get('phone'),
                    'dateOfBirth' => date('Y-m-d', strtotime(Input::get('dob'))),
                    'ssn' => Input::get('ssn'),
                    'address' => array(
                        'streetAddress' => Input::get('streetAddress'),
                        'locality' => Input::get('locality'),
                        'region' => Input::get('region'),
                        'postalCode' => Input::get('postalCode')
                    )
                ),
                'funding' => array(
                    'descriptor' => 'UberForX',
                    'destination' => Braintree_MerchantAccount::FUNDING_DESTINATION_BANK,
                    'email' => Input::get('bankemail'),
                    'mobilePhone' => Input::get('bankphone'),
                    'accountNumber' => Input::get('accountNumber'),
                    'routingNumber' => Input::get('routingNumber')
                ),
                'tosAccepted' => true,
                'masterMerchantAccountId' => Config::get('app.masterMerchantAccountId'),
                'id' => "taxi-"."".RandomString(8)."-".Input::get('id')
            )
        );

        Log::info('res = ' . print_r($result, true));

       /* echo "<pre>";
        print_r($result); exit;*/

        if ($result->success) {
            $resultMerchantId = '';
            $pro = Walker::where('id', Input::get('id'))->first();
            $pro->merchant_id = $resultMerchantId = $result->merchantAccount->id;
            $pro->save();

            $isDefault = WalkerBankingDetails::where('walker_id', '=', Input::get('id'))->where('is_default', '=', 1)->get();
            $dob = Input::get('dob');
            $dob = date("Y-m-d", strtotime($dob));
            $ssn = Input::get('ssn');
            $account_number = Input::get('accountNumber');
            $routing_number = Input::get('routingNumber');
            $is_default = Input::get('is_default');

            $bankingDetails = new WalkerBankingDetails();
            $bankingDetails->walker_id = Input::get('id');
            $bankingDetails->dob = $dob;
            $bankingDetails->ssn = (!empty($ssn) ? $ssn : '');
            $bankingDetails->account_number = (!empty($account_number) ? $account_number : '');
            $bankingDetails->routing_number = (!empty($routing_number) ? $routing_number : '');
            if(count($isDefault) > 0 && $is_default == "yes") {
                WalkerBankingDetails::where('walker_id', Input::get('id'))->update(array('is_default' => 0,'merchant_id' => ''));
            }
            $bankingDetails->merchant_id = (!empty($resultMerchantId) ? $resultMerchantId : '');
            $bankingDetails->is_default = (!empty($is_default == "yes") ? 1 : 0);
            $bankingDetails->save();

            Log::info(print_r($pro, true));
            Log::info('Adding banking details to provider from Admin = ' . print_r($result, true));
            return Redirect::to("/admin/providers");
        } else {
            Log::info('Error in adding banking details: ' . $result->message);
            return Redirect::to("/admin/providers");
        }
    }


    public function providerS_bankingSubmit() {
        $id = Input::get('id');
        Stripe::setApiKey(Config::get('app.stripe_secret_key'));
        $token_id = Input::get('stripeToken');
        // Create a Recipient
        try {
			
			 $recipient = Account::create(array(
                        "email" => Input::get('email'),
						"managed" => TRUE
                            )
            );
			
          /*  $recipient = Stripe_Recipient::create(array(
                        "name" => Input::get('first_name') . " " . Input::get('last_name'),
                        "type" => Input::get('type'),
                        "bank_account" => $token_id,
                        "email" => Input::get('email')
                            )
            );*/
			
			
        Log::info('recipient = ' . print_r($recipient, true));

            $pro = Walker::where('id', Input::get('id'))->first();
            $pro->merchant_id = $recipient->id;
            //$pro->account_id = $recipient->active_account->id;
           // $pro->last_4 = $recipient->active_account->last4;
            $pro->save();

            Log::info('recipient added = ' . print_r($recipient, true));
        } catch (Exception $e) {
            Log::info('Error in Stripe = ' . print_r($e, true));
			print_r($e);
			die();
        }
        return Redirect::to("/admin/providers");
    }

    public function index() {
        return Redirect::to('/admin/login');
    }

    public function get_document_types() {
        Session::forget('type');
        Session::forget('valu');
        
        $walkers = DB::table('walker')
            ->select('walker.id','walker.first_name','walker.last_name','walker.proof_commercial_insurance','walker.license_front','walker.license_back','walker.created_at','walker.deleted_at')->where('walker.deleted_at', NULL)
            ->orderBy('walker.created_at', 'DESC')
            ->paginate(10);
       
        $title = ucwords(trans('language_changer.Documents')); /* 'Document Types' */
        return View::make('list_document_types')
                        ->with('title', $title)
                        ->with('page', 'document-type')
                        ->with('walkers', $walkers);
    }

    public function get_promo_codes() {
        Session::forget('type');
        Session::forget('valu');
        $success = Input::get('success');
        $promo_codes = PromoCodes::paginate(10);
        $title = ucwords(trans('language_changer.promo_codes')); /* 'Promo Codes' */

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }

        return View::make('list_promo_codes')
                        ->with('title', $title)
                        ->with('page', 'promo_code')
                        ->with('success', $success)
            ->with('align_format',$align_format)
                        ->with('promo_codes', $promo_codes);
    }

    public function searchdoc() {
        $valu = $_GET['valu'];
        $type = $_GET['type'];
        Session::put('valu', $valu);
        Session::put('type', $type);
        if ($type == 'docid') {
            $types = Document::where('id', $valu)->paginate(10);
        } elseif ($type == 'docname') {
            $types = Document::where('name', 'like', '%' . $valu . '%')->paginate(10);
        }
        $title = ucwords(trans('language_changer.Documents')); /* 'Document Types' */

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }

        return View::make('list_document_types')
                        ->with('title', $title)
                        ->with('page', 'document-type')
            ->with('align_format',$align_format)
            ->with('types', $types);
    }

    public function delete_document_type() {
        $id = Request::segment(4);
        Document::where('id', $id)->delete();
        DocumentImages::where('document_id', $id)->delete();

        Session::flash('message', 'Document Successfully Deleted!');
        return Redirect::to("/admin/document-types");
    }

    public function edit_document_type() {
        $id = Request::segment(4);
        $success = Input::get('success');
        $document_type = Document::find($id);

        $walkers = DB::table('walker')
            ->select('id',DB::raw('CONCAT(last_name, " ", first_name) AS walkerName'))
             ->get();

        if ($document_type) {
            $id = $document_type->id;
            $name = $document_type->name;
            $walkerId = $document_type->walker_id;
            $title = ucwords(trans('language_changer.edit')." ".trans('language_changer.documents')." ".trans('language_changer.type') . " : " . $name);
        } else {
            $id = 0;
            $name = "";
            $walkerId = "";
            $title = trans('langeage_changer.add')." ".trans('langeage_changer.documents')." ".trans('langeage_changer.type');
        }

        return View::make('edit_document_type')
                        ->with('title', $title)
                        ->with('page', 'document-type')
                        ->with('success', $success)
                        ->with('id', $id)
                        ->with('walkerId', $walkerId)
                        ->with('walkers', $walkers)
                        ->with('name', $name);
    }

    public function update_document_type() {
        $id = Input::get('id');
        $name = Input::get('name');

        $walkerId = Input::get('walkerId');

        $walkerRec = Walker::find($walkerId);

        /*echo "<pre>";
        print_r($walkerRec->id);
        exit;*/

        if ($id == 0) {
            $document_type = new Document;
        } else {
            $document_type = Document::find($id);
        }

        $rules = array(
            'name' => 'required',
            'walkerId' => 'required'
        );

        $images = count(Input::file('image'));
        for($i = 0; $i < $images; $i++) {
            $rules['image.' . $i] = 'required | mimes:jpeg,jpg,png,pdf';
        }

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

           /* echo "<pre>";
            print_r($validator->errors());
            exit;*/

            return Redirect::to('/admin/document-type/edit/'.$id)->withInput()->withErrors($validator);

        } else {

            $document_type->name = $name;
            $document_type->walker_id = $walkerId;
            $document_type->save();
            $lastInsertId = $document_type->id;

            if (Input::hasFile('image')) {

                $files = Input::file('image');
                foreach($files as $file) {
                    // Upload File
                    $file_name = time();
                    $file_name .= rand();
                    $ext = $file->getClientOriginalExtension();
                    $file->move(public_path() . "/uploads", $file_name . "." . $ext);
                    $local_url = $file_name . "." . $ext;

                    // Upload to S3
                    if (Config::get('app.s3_bucket') != "") {
                        $s3 = App::make('aws')->get('s3');
                        $pic = $s3->putObject(array(
                            'Bucket' => Config::get('app.s3_bucket'),
                            'Key' => $file_name,
                            'SourceFile' => public_path() . "/uploads/" . $local_url,
                        ));

                        $s3->putObjectAcl(array(
                            'Bucket' => Config::get('app.s3_bucket'),
                            'Key' => $file_name,
                            'ACL' => 'public-read'
                        ));

                        $s3_url = $s3->getObjectUrl(Config::get('app.s3_bucket'), $file_name);
                    } else {
                        $s3_url = asset_url() . '/uploads/' . $local_url;
                    }

                    $DocumentImage = new DocumentImages;
                    $DocumentImage->document_id = $lastInsertId;
                    $DocumentImage->image = (!empty($s3_url) ? $s3_url : '');
                    $DocumentImage->save();
                }
            }

            if($id == 0) {

                Session::flash('message', 'Document Successfully created!');
            } else {
                Session::flash('message', 'Document Successfully updated!');
            }

            //return Redirect::to("/admin/document-type/edit/$document_type->id?success=1");
            return Redirect::to("/admin/document-types");

        }



    }

    public function get_provider_types() {

        $settings = Settings::where('key', 'default_distance_unit')->first();
        $unit = $settings->value;
        if ($unit == 0) {
            $unit_set = 'kms';
        } elseif ($unit == 1) {
            $unit_set = 'miles';
        }
        $types = ProviderType::paginate(10);
        $title = ucwords(trans('language_changer.Provider') . " " . trans('language_changer.Types')); /* 'Provider Types' */


        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }

        return View::make('list_provider_types')
                        ->with('title', $title)
                        ->with('page', 'provider-type')
            ->with('align_format',$align_format)
                        ->with('unit_set', $unit_set)
                        ->with('types', $types);
    }

    public function searchpvtype() {
        $valu = $_GET['valu'];
        $type = $_GET['type'];
        Session::put('valu', $valu);
        Session::put('type', $type);
        if ($type == 'provid') {
            $types = ProviderType::where('id', $valu)->paginate(10);
        } elseif ($type == 'provname') {
            $types = ProviderType::where('name', 'like', '%' . $valu . '%')->paginate(10);
        }
        $settings = Settings::where('key', 'default_distance_unit')->first();
        $unit = $settings->value;
        if ($unit == 0) {
            $unit_set = 'kms';
        } elseif ($unit == 1) {
            $unit_set = 'miles';
        }
        $title = ucwords(trans('language_changer.Provider') . " " . trans('language_changer.Types')); /* 'Provider Types' */


        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }


        return View::make('list_provider_types')
                        ->with('title', $title)
                        ->with('page', 'provider-type')
                        ->with('unit_set', $unit_set)
                        ->with('align_format',$align_format)
                        ->with('types', $types);
    }

    public function delete_provider_type() {
        $id = Request::segment(4);
        ProviderType::where('id', $id)->where('is_default', 0)->delete();
        return Redirect::to("/admin/provider-types");
    }

    public function edit_provider_type() {
        $id = Request::segment(4);
        $success = Input::get('success');
        $providers_type = ProviderType::find($id);

       /* echo "<pre>";
        print_r($providers_type);
        exit;*/
        $providerIds = array();
        $providerAllTypes = ProviderType::all();
        if(!empty($providers_type['admin_select_vechicle_type'])) {

            //$providerTypes = explode(",", $providers_type['admin_select_vechicle_type']);

            $providerTypes = array_map('trim',array_filter(explode(',',$providers_type['admin_select_vechicle_type'])));

            /*echo "<pre>";
            print_r($providerTypes);
            exit;*/

            if (!empty($providerTypes)) {

                foreach ($providerTypes as $key => $providerTypeId) {
                    //if($providerType->id == 2 || $providerType->id == 5 || $providerType->id == 1) {
                    // if($providerType->admin_select_vechicle_type == "1") {
                    array_push($providerIds, $providerTypeId);
                    //}
                }
            }
        }

        /*echo "<pre>";
        print_r($providerIds);
        exit;*/

        $settings = Settings::where('key', 'default_distance_unit')->first();
        $unit = $settings->value;
        if ($unit == 0) {
            $unit_set = 'kms';
        } elseif ($unit == 1) {
            $unit_set = 'miles';
        }

        if ($providers_type) {
            $id = $providers_type->id;
            $name = $providers_type->name;
            $is_default = $providers_type->is_default;
            $base_price = $providers_type->base_price;
            $base_distance = $providers_type->base_distance;
            $price_per_unit_distance = $providers_type->price_per_unit_distance;
            $price_per_unit_time = $providers_type->price_per_unit_time;
            $icon = $providers_type->icon;
            $base_price = $providers_type->base_price;
            $max_size = $providers_type->max_size;
            $is_visible = $providers_type->is_visible;
            $waiting_price = $providers_type->waiting_price;
            $title = ucwords(  trans('language_changer.edit').' '.trans('language_changer.provider').' '.trans('language_changer.type').' ' . " : " . $name);

        } else {
            $id = 0;
            $name = "";
            $is_default = "";
            $base_distance = 1;
            $base_price = "";
            $price_per_unit_time = "";
            $price_per_unit_distance = "";
            $icon = "";
            $base_price = '';
            $max_size = '';
            $is_visible = "";
            $waiting_price = "";
            $title = ucwords(  trans('language_changer.add').' '.trans('language_changer.new').' '.trans('language_changer.provider').' '.trans('language_changer.type'));
            //$title = "Add New Provider Type";
        }
        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }
        $currency=Config::get('app.generic_keywords.Currency');


        return View::make('edit_provider_type')
                        ->with('title', $title)
                        ->with('page', 'provider-type')
                        ->with('success', $success)
                        ->with('id', $id)
                        ->with('base_price', $base_price)
                        ->with('base_distance', $base_distance)
                        ->with('max_size', $max_size)
                        ->with('name', $name)
                        ->with('is_default', $is_default)
                        ->with('base_price', $base_price)
                        ->with('waiting_price', $waiting_price)
                        ->with('icon', $icon)
                        ->with('is_visible', $is_visible)
                        ->with('price_per_unit_time', $price_per_unit_time)
                        ->with('align_format',$align_format)
                        ->with('price_per_unit_distance', $price_per_unit_distance)
                        ->with('providerTypes', $providerAllTypes)
                        ->with('providerIds', $providerIds)
                        ->with('currency', $currency)
                        ->with('unit_set', $unit_set);
    }

    /*public function update_provider_type() {
        $id = Input::get('id');
        $name = ucwords(trim(Input::get('name')));
        $base_distance = trim(Input::get('base_distance'));
        if ($base_distance == "" || $base_distance == 0) {
            $base_distance = 1;
        }
        $base_price = trim(Input::get('base_price'));
        if ($base_price == "" || $base_price == 0) {
            $base_price = 0;
        }
        $distance_price = trim(Input::get('distance_price'));
        if ($distance_price == "" || $distance_price == 0) {
            $distance_price = 0;
        }
        $time_price = trim(Input::get('time_price'));
        if ($time_price == "" || $time_price == 0) {
            $time_price = 0;
        }
        $max_size = trim(Input::get('max_size'));
        if ($max_size == "" || $max_size == 0) {
            $max_size = 0;
        }
        $is_default = Input::get('is_default');
        $is_visible = trim(Input::get('is_visible'));

        if ($is_default) {
            if ($is_default == 1) {
                ProviderType::where('is_default', 1)->update(array('is_default' => 0));
            }
        } else {
            $is_default = 0;
        }


        if ($id == 0) {
            $providers_type = new ProviderType;
        } else {
            $providers_type = ProviderType::find($id);
        }
        if (Input::hasFile('icon')) {
            // Upload File
            $file_name = time();
            $file_name .= rand();
            $ext = Input::file('icon')->getClientOriginalExtension();
            Input::file('icon')->move(public_path() . "/uploads", $file_name . "." . $ext);
            $local_url = $file_name . "." . $ext;

            // Upload to S3
            if (Config::get('app.s3_bucket') != "") {
                $s3 = App::make('aws')->get('s3');
                $pic = $s3->putObject(array(
                    'Bucket' => Config::get('app.s3_bucket'),
                    'Key' => $file_name,
                    'SourceFile' => public_path() . "/uploads/" . $local_url,
                ));

                $s3->putObjectAcl(array(
                    'Bucket' => Config::get('app.s3_bucket'),
                    'Key' => $file_name,
                    'ACL' => 'public-read'
                ));

                $s3_url = $s3->getObjectUrl(Config::get('app.s3_bucket'), $file_name);
            } else {
                $s3_url = asset_url() . '/uploads/' . $local_url;
            }

            if (isset($providers_type->icon)) {
                if ($providers_type->icon != "") {
                    $icon = $providers_type->icon;
                    unlink_image($icon);
                }
            }
            $providers_type->icon = $s3_url;
        }

        $providers_type->name = $name;
        $providers_type->base_distance = $base_distance;
        $providers_type->base_price = $base_price;
        $providers_type->price_per_unit_distance = $distance_price;
        $providers_type->price_per_unit_time = $time_price;
        $providers_type->max_size = $max_size;
        $providers_type->is_default = $is_default;
        $providers_type->is_visible = $is_visible;
        $providers_type->save();

        return Redirect::to("/admin/provider-type/edit/$providers_type->id?success=1");
    }*/

    public function update_provider_type() {

        /*echo"<pre>";
        print_r(Input::all());
        die();*/


        $id = Input::get('id');

        /*$multi_select_vechicle_types = Input::get('multi_select_vechicle_types');*/

        $name = ucwords(trim(Input::get('name')));

        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'name'       => 'required',
            'icon'       => $id==0?'required':"".' | mimes:jpeg,jpg,png| max:300'
        );

        $validator = Validator::make(Input::all(), $rules);


        // process the login
        if ($validator->fails()) {

            return Redirect::to("/admin/provider-type/edit/$id")->withInput()->withErrors($validator);

        } else {

            $base_distance = trim(Input::get('base_distance'));
            if ($base_distance == "" || $base_distance == 0) {
                $base_distance = 1;
            }
            $base_price = trim(Input::get('base_price'));
            if ($base_price == "" || $base_price == 0) {
                $base_price = 0;
            }
            $distance_price = trim(Input::get('distance_price'));
            if ($distance_price == "" || $distance_price == 0) {
                $distance_price = 0;
            }
            $time_price = trim(Input::get('time_price'));
            if ($time_price == "" || $time_price == 0) {
                $time_price = 0;
            }
            $max_size = trim(Input::get('max_size'));
            if ($max_size == "" || $max_size == 0) {
                $max_size = 0;
            }
            $is_default = Input::get('is_default');
            $is_visible = trim(Input::get('is_visible'));

            if ($is_default) {
                if ($is_default == 1) {
                    ProviderType::where('is_default', 1)->update(array('is_default' => 0));
                }
            } else {
                $is_default = 0;
            }
            $waiting_price = trim(Input::get('waiting_price'));
            if ($waiting_price == "" || $waiting_price == 0) {
                $waiting_price = 0;
            }

            if ($id == 0) {
                $providers_type = new ProviderType;
            } else {
                $providers_type = ProviderType::find($id);
            }
            if (Input::hasFile('icon')) {
                // Upload File
                $file_name = time();
                $file_name .= rand();
                $ext = Input::file('icon')->getClientOriginalExtension();
                Input::file('icon')->move(public_path() . "/uploads", $file_name . "." . $ext);
                $local_url = $file_name . "." . $ext;

                // Upload to S3
                if (Config::get('app.s3_bucket') != "") {
                    $s3 = App::make('aws')->get('s3');
                    $pic = $s3->putObject(array(
                        'Bucket' => Config::get('app.s3_bucket'),
                        'Key' => $file_name,
                        'SourceFile' => public_path() . "/uploads/" . $local_url,
                    ));

                    $s3->putObjectAcl(array(
                        'Bucket' => Config::get('app.s3_bucket'),
                        'Key' => $file_name,
                        'ACL' => 'public-read'
                    ));

                    $s3_url = $s3->getObjectUrl(Config::get('app.s3_bucket'), $file_name);
                } else {
                    $s3_url = asset_url() . '/uploads/' . $local_url;
                }

                if (isset($providers_type->icon)) {
                    if ($providers_type->icon != "") {
                        $icon = $providers_type->icon;
                        unlink_image($icon);
                    }
                }
                $providers_type->icon = $s3_url;
            }

            $providers_type->name = $name;
            $providers_type->base_distance = $base_distance;
            $providers_type->base_price = $base_price;
            $providers_type->price_per_unit_distance = $distance_price;
            $providers_type->price_per_unit_time = $time_price;
            $providers_type->max_size = $max_size;
            $providers_type->is_default = $is_default;
            $providers_type->is_visible = $is_visible;
            $providers_type->waiting_price = $waiting_price;
            /*$providers_type->admin_select_vechicle_type = implode(",",$multi_select_vechicle_types);*/
            $providers_type->save();

            // redirect
            return Redirect::to("/admin/provider-type/edit/$providers_type->id?success=1");
        }
    }

    public function get_info_pages() {

        $informations = Information::paginate(10);
        $title = ucwords(trans('language_changer.Information') .' ' .trans('language_changer.page')); /* 'Information Pages' */

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }

        return View::make('list_info_pages')
                        ->with('title', $title)
                        ->with('page', 'information')
                         ->with('align_format',$align_format)
                        ->with('informations', $informations);
    }

    public function searchinfo() {
        $valu = $_GET['valu'];
        $type = $_GET['type'];
        Session::put('valu', $valu);
        Session::put('type', $type);
        if ($type == 'infoid') {
            $informations = Information::where('id', $valu)->paginate(10);
        } elseif ($type == 'infotitle') {
            $informations = Information::where('title', 'like', '%' . $valu . '%')->paginate(10);
        }
        $title = ucwords(trans('language_changer.Information') . " | ".trans('language_changer.search').' '.trans('language_changer.result')   ); /* 'Information Pages | Search Result' */

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }

        return View::make('list_info_pages')
                        ->with('title', $title)
                        ->with('page', 'information')
                        ->with('align_format',$align_format)
                        ->with('informations', $informations);
    }

    public function delete_info_page() {
        $id = Request::segment(4);
        Information::where('id', $id)->delete();
        return Redirect::to("/admin/informations");
    }

    public function skipSetting() {
        setcookie("skipInstallation", "admincookie", time() + (86400 * 30));
        return Redirect::to("/admin/report/");
    }

    public function edit_info_page() {
        $id = Request::segment(4);
        $success = Input::get('success');
        $information = Information::find($id);
        if ($information) {
            $id = $information->id;
            $title = $information->title;
            $description = $information->content;
            $icon = $information->icon;

            $title_new = str_replace(' ', '_', $title);

            $file = base_path() . "/app/views/website/" . $title . ".blade.php";

            if (file_exists($file)) {
                $fp = fopen($file, "w");
                $body = generate_generic_page_layout($description);
                fwrite($fp, $body);
                fclose($fp);
            } else {
                $success = 2;
            }
            $page_title = ucwords(trans('language_changer.edit').' '.trans('language_changer.Information').' '.trans('language_changer.page') . " : " . $title);
        } else {
            $id = 0;
            $title = "";
            $description = "";
            $icon = "";
            $page_title = ucwords(trans('language_changer.add').' '.trans('language_changer.Information').' '.trans('language_changer.page'));
        }

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }

        return View::make('edit_info_page')
                        ->with('title', $page_title)
                        ->with('page', 'information')
                        ->with('success', $success)
                        ->with('align_format',$align_format)
                        ->with('id', $id)
                        ->with('info_title', $title)
                        ->with('icon', $icon)
                        ->with('description', $description);
    }

    public function update_info_page() {
        $id = Input::get('id');
        $title = Input::get('title');
        $description = Input::get('description');
        if ($id == 0) {
            $information = new Information;
        } else {
            $information = Information::find($id);
        }

        if (Input::hasFile('icon')) {
            // Upload File
            $file_name = time();
            $file_name .= rand();
            $ext = Input::file('icon')->getClientOriginalExtension();
            Input::file('icon')->move(public_path() . "/uploads", $file_name . "." . $ext);
            $local_url = $file_name . "." . $ext;

            // Upload to S3
            if (Config::get('app.s3_bucket') != "") {
                $s3 = App::make('aws')->get('s3');
                $pic = $s3->putObject(array(
                    'Bucket' => Config::get('app.s3_bucket'),
                    'Key' => $file_name,
                    'SourceFile' => public_path() . "/uploads/" . $local_url,
                ));

                $s3->putObjectAcl(array(
                    'Bucket' => Config::get('app.s3_bucket'),
                    'Key' => $file_name,
                    'ACL' => 'public-read'
                ));

                $s3_url = $s3->getObjectUrl(Config::get('app.s3_bucket'), $file_name);
            } else {
                $s3_url = asset_url() . '/uploads/' . $local_url;
            }

            if (isset($information->icon)) {
                if ($information->icon != "") {
                    $icon = $information->icon;
                    unlink_image($icon);
                }
            }
            $information->icon = $s3_url;
        }

        $information->title = $title;
        $information->content = $description;
        $information->save();

        $title_new = str_replace(' ', '_', $title);

        $file = base_path() . "/app/views/website/" . $title . ".blade.php";

        if (!file_exists($file)) {
            $fp = fopen($file, "w");
            $body = generate_generic_page_layout($description);
            fwrite($fp, $body);
            fclose($fp);
        }

        return Redirect::to("/admin/information/edit/$information->id?success=1");
    }

    public function map_view() {
        $settings = Settings::where('key', 'map_center_latitude')->first();
        $center_latitude = $settings->value;
        $settings = Settings::where('key', 'map_center_longitude')->first();
        $center_longitude = $settings->value;
        $title = ucwords(trans('language_changer.map_view')); /* 'Map View' */
        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }
        return View::make('map_view')
                        ->with('title', $title)
                        ->with('page', 'map-view')
                        ->with('center_longitude', $center_longitude)
                        ->with('center_latitude', $center_latitude)
                        ->with('align_format',$align_format);
    }

    public function walkers() {
        Session::forget('type');
        Session::forget('valu');
        Session::forget('che');
        //$query = "SELECT *,(select count(*) from request_meta where walker_id = walker.id  and status != 0 ) as total_requests,(select count(*) from request_meta where walker_id = walker.id and status=1) as accepted_requests FROM `walker`";
        //$walkers = DB::select(DB::raw($query));
        /* $walkers1 = DB::table('walker')
          ->leftJoin('request_meta', 'walker.id', '=', 'request_meta.walker_id')
          ->where('request_meta.status', '!=', 0)
          ->count();
          $walkers2 = DB::table('walker')
          ->leftJoin('request_meta', 'walker.id', '=', 'request_meta.walker_id')
          ->where('request_meta.status', '=', 1)
          ->count();

          $walkers = Walker::paginate(10); */
        $subQuery = DB::table('request_meta')
                ->select(DB::raw('count(*)'))
                ->whereRaw('walker_id = walker.id and status != 0');
        $subQuery1 = DB::table('request_meta')
                ->select(DB::raw('count(*)'))
                ->whereRaw('walker_id = walker.id and status=1');

        $walkers = DB::table('walker')
                ->select('walker.*', DB::raw("(" . $subQuery->toSql() . ") as 'total_requests'"), DB::raw("(" . $subQuery1->toSql() . ") as 'accepted_requests'"))->where('deleted_at', NULL)
                /* ->where('walker.is_deleted', 0) */
                ->orderBy('walker.created_at', 'DESC')
                ->paginate(10);
        $title = ucwords(trans('language_changer.Provider') . trans('language_changer.s')); /* 'Providers' */


        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }




        return View::make('walkers')
                        ->with('title', $title)
                        ->with('page', 'walkers')
                        ->with('walkers', $walkers)
                        ->with('align_format',$align_format);
            /*
          ->with('total_requests', $walkers1)
          ->with('accepted_requests', $walkers2) */;
    }

    //Referral Statistics
    public function referral_details() {
        $owner_id = Request::segment(4);
        $ledger = Ledger::where('owner_id', $owner_id)->first();
        $owners = Owner::where('referred_by', $owner_id)->paginate(10);
        $title = ucwords(trans('language_changer.User') . trans('language_changer.s') . " | ".trans('language_changer.coupon').' '.trans('language_changer.statistics') ); /* 'Owner | Coupon Statistics' */

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }
        return View::make('referred')
                        ->with('page', 'owners')
                        ->with('title', $title)
                        ->with('owners', $owners)
            ->with('align_format',$align_format)
                        ->with('ledger', $ledger);
    }

    // Search Walkers from Admin Panel
    public function searchpv() {

        if(!empty($_GET['valu'])) {
            $valu = $_GET['valu'];
            $type = $_GET['type'];

        } else {
            $valu = $_GET['filter_valu'];
            $type = $_GET['filter_type'];
        }


        Session::forget('message');
        Session::put('valu', $valu);
        Session::put('type', $type);

        
        if ($type == 'provid') {
            /* $walkers = Walker::where('id', $valu)->paginate(10); */
            $subQuery = DB::table('request_meta')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('walker_id = walker.id and status != 0');
            $subQuery1 = DB::table('request_meta')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('walker_id = walker.id and status=1');

            $query = DB::table('walker')
                    ->select('walker.*', DB::raw("(" . $subQuery->toSql() . ") as 'total_requests'"), DB::raw("(" . $subQuery1->toSql() . ") as 'accepted_requests'"))->where('deleted_at', NULL)
                    /* ->where('walker.is_deleted', 0) */
                    ->where('walker.id', $valu);
                    if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                        $walkers = $query->orderBy('walker.created_at', 'DESC')->get();
                    }else {
                        $walkers = $query->orderBy('walker.created_at', 'DESC')->paginate(10);
                    }

        } elseif ($type == 'pvname') {
            /* $walkers = Walker::where('first_name', 'like', '%' . $valu . '%')->orWhere('last_name', 'like', '%' . $valu . '%')->paginate(10); */
            $subQuery = DB::table('request_meta')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('walker_id = walker.id and status != 0');
            $subQuery1 = DB::table('request_meta')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('walker_id = walker.id and status=1');

            $query = DB::table('walker')
                    ->select('walker.*', DB::raw("(" . $subQuery->toSql() . ") as 'total_requests'"), DB::raw("(" . $subQuery1->toSql() . ") as 'accepted_requests'"))->where('deleted_at', NULL)
                    /* ->where('walker.is_deleted', 0) */
                    ->where('walker.first_name', 'like', '%' . $valu . '%')->orWhere('walker.last_name', 'like', '%' . $valu . '%');
                    if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                        $walkers = $query->orderBy('walker.created_at', 'DESC')->get();
                    }else {
                        $walkers = $query->orderBy('walker.created_at', 'DESC')->paginate(10);
                    }
        } elseif ($type == 'pvemail') {
            /* $walkers = Walker::where('email', 'like', '%' . $valu . '%')->paginate(10); */
            $subQuery = DB::table('request_meta')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('walker_id = walker.id and status != 0');
            $subQuery1 = DB::table('request_meta')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('walker_id = walker.id and status=1');

            $query = DB::table('walker')
                    ->select('walker.*', DB::raw("(" . $subQuery->toSql() . ") as 'total_requests'"), DB::raw("(" . $subQuery1->toSql() . ") as 'accepted_requests'"))->where('deleted_at', NULL)
                    /* ->where('walker.is_deleted', 0) */
                    ->where('walker.email', 'like', '%' . $valu . '%');
                    if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                        $walkers = $query->orderBy('walker.created_at', 'DESC')->get();
                    }else {
                        $walkers = $query->orderBy('walker.created_at', 'DESC')->paginate(10);
                    }
        }elseif ($type == 'pvphone') {
            /* $walkers = Walker::where('email', 'like', '%' . $valu . '%')->paginate(10); */
            $subQuery = DB::table('request_meta')
                ->select(DB::raw('count(*)'))
                ->whereRaw('walker_id = walker.id and status != 0');
            $subQuery1 = DB::table('request_meta')
                ->select(DB::raw('count(*)'))
                ->whereRaw('walker_id = walker.id and status=1');

            $query = DB::table('walker')
                ->select('walker.*', DB::raw("(" . $subQuery->toSql() . ") as 'total_requests'"), DB::raw("(" . $subQuery1->toSql() . ") as 'accepted_requests'"))->where('deleted_at', NULL)
                /* ->where('walker.is_deleted', 0) */
                ->where('walker.phone', 'like', '%' . $valu . '%');
            if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                $walkers = $query->orderBy('walker.created_at', 'DESC')->get();
            }else {
                $walkers = $query->orderBy('walker.created_at', 'DESC')->paginate(10);
            }
        }elseif ($type == 'pvtype') {
            /* $walkers = Walker::where('email', 'like', '%' . $valu . '%')->paginate(10); */
            $subQuery = DB::table('request_meta')
                ->select(DB::raw('count(*)'))
                ->whereRaw('walker_id = walker.id and status != 0');
            $subQuery1 = DB::table('request_meta')
                ->select(DB::raw('count(*)'))
                ->whereRaw('walker_id = walker.id and status=1');

            $query = DB::table('walker')
                ->select('walker.*', DB::raw("(" . $subQuery->toSql() . ") as 'total_requests'"), DB::raw("(" . $subQuery1->toSql() . ") as 'accepted_requests'"))->where('deleted_at', NULL)
                /* ->where('walker.is_deleted', 0) */
                ->where('walker.type',$valu);
            if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                $walkers = $query->orderBy('walker.created_at', 'DESC')->get();
            }else {
                $walkers = $query->orderBy('walker.created_at', 'DESC')->paginate(10);
            }
        }elseif ($type == 'pvaddress') {
            /* $walkers = Walker::where('bio', 'like', '%' . $valu . '%')->paginate(10); */
            $subQuery = DB::table('request_meta')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('walker_id = walker.id and status != 0');
            $subQuery1 = DB::table('request_meta')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('walker_id = walker.id and status=1');

            $query = DB::table('walker')
                    ->select('walker.*', DB::raw("(" . $subQuery->toSql() . ") as 'total_requests'"), DB::raw("(" . $subQuery1->toSql() . ") as 'accepted_requests'"))->where('deleted_at', NULL)
                    /* ->where('walker.is_deleted', 0) */
                    ->where('walker.address', 'like', '%' . $valu . '%');
                    if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                        $walkers = $query->orderBy('walker.created_at', 'DESC')->get();
                    }else {
                        $walkers = $query->orderBy('walker.created_at', 'DESC')->paginate(10);
                    }
        }
        $title = ucwords(trans('language_changer.Provider') . trans('language_changer.s') . " |"." ". trans('language_changer.search').' '.trans('language_changer.result')); /* 'Providers | Search Result' */

        if (Input::get('submit') && Input::get('submit') == 'Download_Report') {

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=Report_drivers.csv');
            $handle = fopen('php://output', 'w');

            fputcsv($handle, array(Lang::get('constants.id'), Lang::get('constants.name'), Lang::get('constants.email'), Lang::get('constants.phone'), Lang::get('constants.bio'), Lang::get('constants.total_requests'), Lang::get('constants.acceptance_rate'), Lang::get('constants.status')));

            foreach ($walkers as $provider) {

                //Phone
                $walkerPhone = $provider->phone;
                //Email
                $walkerEmail = $provider->email;
                //Bio
                $walkerBio = ( !empty( $provider->bio ) ? $provider->bio : Config::get('app.blank_fiend_val') );
                //Accept Request
                $walkerAcptRequest = (( $provider->total_requests != 0 ) ? round(( $provider->accepted_requests / $provider->total_requests) * 100, 2) : 0);
                //Is Approved
                $walkerIsApproved = ( !empty( $provider->is_approved )  ? Lang::get('constants.approved') : Lang::get('constants.pending') );

                fputcsv($handle, array(
                    $provider->id,
                    $provider->first_name . " " . $provider->last_name,
                    $walkerEmail,
                    $walkerPhone,
                    $walkerBio,
                    $provider->total_requests,
                    $walkerAcptRequest."%",
                    $walkerIsApproved,
                ));
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

        } else {

            if(Config::get('app.locale') == 'arb'){
                $align_format="right";
            }elseif(Config::get('app.locale') == 'en'){
                $align_format="left";
            }


            return View::make('walkers')
                ->with('title', $title)
                ->with('page', 'walkers')
                ->with('align_format',$align_format)
                ->with('walkers', $walkers);
        }
    }

    public function walkers_xml() {

        $walkers = Walker::where('');
        $response = "";
        $response .= '<markers>';

        $walkers = DB::table('walker')
                ->select('walker.*')
                ->where("latitude","!=",0)
                ->where("longitude","!=",0)
                ->whereNull('deleted_at')
                ->get();
        $walker_ids = array();
        foreach ($walkers as $walker) {
            if ($walker->is_active == 1 && $walker->is_available == 1 && $walker->is_approved == 1/* && $walker->is_deleted == 0 */) {
                $response .= '<marker ';
                $response .= 'name="' . $walker->first_name . " " . $walker->last_name . '" ';
                $response .= 'client_name="' . $walker->first_name . " " . $walker->last_name . '" ';
                $response .= 'contact="' . $walker->phone . '" ';
                $response .= 'amount="' . 0 . '" ';
                $response .= 'angl="' . $walker->bearing . '" ';
                $response .= 'lat="' . $walker->latitude . '" ';
                $response .= 'lng="' . $walker->longitude . '" ';
                $response .= 'id="' . $walker->id . '" ';
                $response .= 'type="driver_free" ';
                $response .= '/>';
                array_push($walker_ids, $walker->id);
            } else if ($walker->is_active == 1 && $walker->is_available == 0 && $walker->is_approved == 1/* && $walker->is_deleted == 0 */) {
                $response .= '<marker ';
                $response .= 'name="' . $walker->first_name . " " . $walker->last_name . '" ';
                $response .= 'client_name="' . $walker->first_name . " " . $walker->last_name . '" ';
                $response .= 'contact="' . $walker->phone . '" ';
                $response .= 'amount="' . 0 . '" ';
                $response .= 'angl="' . $walker->bearing . '" ';
                $response .= 'lat="' . $walker->latitude . '" ';
                $response .= 'lng="' . $walker->longitude . '" ';
                $response .= 'id="' . $walker->id . '" ';
                $response .= 'type="driver_on_trip" ';
                $response .= '/>';
                array_push($walker_ids, $walker->id);
            } else if (($walker->is_active == 0 || $walker->is_active == 1) && ($walker->is_available == 0 || $walker->is_available == 1) && $walker->is_approved == 0 /* && $walker->is_deleted == 0 */) {
                $response .= '<marker ';
                $response .= 'name="' . $walker->first_name . " " . $walker->last_name . '" ';
                $response .= 'client_name="' . $walker->first_name . " " . $walker->last_name . '" ';
                $response .= 'contact="' . $walker->phone . '" ';
                $response .= 'amount="' . 0 . '" ';
                $response .= 'angl="' . $walker->bearing . '" ';
                $response .= 'lat="' . $walker->latitude . '" ';
                $response .= 'lng="' . $walker->longitude . '" ';
                $response .= 'id="' . $walker->id . '" ';
                $response .= 'type="driver_not_approved" ';
                $response .= '/>';
                array_push($walker_ids, $walker->id);
            } /* else if (($walker->is_active == 0 || $walker->is_active == 1) && ($walker->is_available == 0 || $walker->is_available == 1) && ($walker->is_approved == 0 || $walker->is_approved == 1) && $walker->is_deleted == 1) {
              $response .= '<marker ';
              $response .= 'name="' . $walker->first_name . " " . $walker->last_name . '" ';
              $response .= 'client_name="' . $walker->first_name . " " . $walker->last_name . '" ';
              $response .= 'contact="' . $walker->phone . '" ';
              $response .= 'amount="' . $walker->topup_bal . '" ';
              $response .= 'licence_plate="' . $walker->licence_plate . '" ';
              $response .= 'lat="' . $walker->latitude . '" ';
              $response .= 'lng="' . $walker->longitude . '" ';
              $response .= 'id="' . $walker->id . '" ';
              $response .= 'company_name="' . $walker->company_name . '" ';
              $response .= 'type="driver_deleted" ';
              $response .= '/>';
              array_push($walker_ids, $walker->id);
              } */
        }

        /* // busy walkers
          $walkers = DB::table('walker')
          ->where('walker.is_active', 1)
          ->where('walker.is_available', 0)
          ->where('walker.is_approved', 1)
          ->select('walker.id', 'walker.phone', 'walker.first_name', 'walker.last_name', 'walker.latitude', 'walker.longitude')
          ->get();

          $walker_ids = array();


          foreach ($walkers as $walker) {
          $response .= '<marker ';
          $response .= 'name="' . $walker->first_name . " " . $walker->last_name . '" ';
          $response .= 'client_name="' . $walker->first_name . " " . $walker->last_name . '" ';
          $response .= 'contact="' . $walker->phone . '" ';
          $response .= 'amount="' . 0 . '" ';
          $response .= 'lat="' . $walker->latitude . '" ';
          $response .= 'lng="' . $walker->longitude . '" ';
          $response .= 'id="' . $walker->id . '" ';
          $response .= 'type="client_pay_done" ';
          $response .= '/>';
          array_push($walker_ids, $walker->id);
          }

          $walker_ids = array_unique($walker_ids);
          $walker_ids_temp = implode(",", $walker_ids);

          $walkers = DB::table('walker')
          ->where('walker.is_active', 0)
          ->where('walker.is_approved', 1)
          ->select('walker.id', 'walker.phone', 'walker.first_name', 'walker.last_name', 'walker.latitude', 'walker.longitude')
          ->get();
          foreach ($walkers as $walker) {
          $response .= '<marker ';
          $response .= 'name="' . $walker->first_name . " " . $walker->last_name . '" ';
          $response .= 'client_name="' . $walker->first_name . " " . $walker->last_name . '" ';
          $response .= 'contact="' . $walker->phone . '" ';
          $response .= 'amount="' . 0 . '" ';
          $response .= 'lat="' . $walker->latitude . '" ';
          $response .= 'lng="' . $walker->longitude . '" ';
          $response .= 'id="' . $walker->id . '" ';
          $response .= 'type="client_no_pay" ';
          $response .= '/>';
          array_push($walker_ids, $walker->id);
          }
          $walker_ids = array_unique($walker_ids);
          $walker_ids = implode(",", $walker_ids);
          if ($walker_ids) {
          $query = "select * from walker where is_approved = 1 and id NOT IN ($walker_ids)";
          } else {
          $query = "select * from walker where is_approved = 1";
          }
          // free walkers
          $walkers = DB::select(DB::raw($query));
          foreach ($walkers as $walker) {
          $response .= '<marker ';
          $response .= 'name="' . $walker->first_name . " " . $walker->last_name . '" ';
          $response .= 'client_name="' . $walker->first_name . " " . $walker->last_name . '" ';
          $response .= 'contact="' . $walker->phone . '" ';
          $response .= 'amount="' . 0 . '" ';
          $response .= 'lat="' . $walker->latitude . '" ';
          $response .= 'lng="' . $walker->longitude . '" ';
          $response .= 'id="' . $walker->id . '" ';
          $response .= 'type="client" ';
          $response .= '/>';
          } */
        $response .= '</markers>';
        $content = View::make('walkers_xml')->with('response', $response);
        return Response::make($content, '200')->header('Content-Type', 'text/xml');
    }

    public function owners() {
        Session::forget('type');
        Session::forget('valu');

        $owners = Owner::where('first_name', 'not like', '%stranger%')->orderBy('id', 'DESC')->paginate(10);
        $title = ucwords(trans('language_changer.User') . trans('language_changer.s')); /* 'Owners' */
        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }

        return View::make('owners')
                        ->with('title', $title)
                        ->with('page', 'owners')
                        ->with('align_format',$align_format)
                        ->with('owners', $owners);
    }

    public function searchur() {

        if(!empty($_GET['valu'])) {
            $valu = $_GET['valu'];
            $type = $_GET['type'];
        } else {
            $valu = $_GET['filter_valu'];
            $type = $_GET['filter_type'];
        }

        Session::put('valu', $valu);
        Session::put('type', $type);
        if ($type == 'userid') {
            $query = Owner::where('id', $valu);
            if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                $owners = $query->where('first_name', 'not like', '%stranger%')->orderBy('id', 'DESC')->get();
            }else {
                $owners = $query->where('first_name', 'not like', '%stranger%')->orderBy('id', 'DESC')->paginate(10);
            }
        } elseif ($type == 'username') {
            $query = Owner::where('first_name', 'like', '%' . $valu . '%')->orWhere('last_name', 'like', '%' . $valu . '%');
            if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                $owners = $query->where('first_name', 'not like', '%stranger%')->orderBy('id', 'DESC')->get();
            }else {
                $owners = $query->where('first_name', 'not like', '%stranger%')->orderBy('id', 'DESC')->paginate(10);
            }
        } elseif ($type == 'useremail') {
            $query = Owner::where('email', 'like', '%' . $valu . '%');
            if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                $owners = $query->where('first_name', 'not like', '%stranger%')->orderBy('id', 'DESC')->get();
            }else {
                $owners = $query->where('first_name', 'not like', '%stranger%')->orderBy('id', 'DESC')->paginate(10);
            }
        } elseif ($type == 'userphone') {
            $query = Owner::where('phone', 'like', '%' . $valu . '%');
            if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                $owners = $query->where('first_name', 'not like', '%stranger%')->orderBy('id', 'DESC')->get();
            }else {
                $owners = $query->where('first_name', 'not like', '%stranger%')->orderBy('id', 'DESC')->paginate(10);
            }
        } elseif ($type == 'useraddress') {
            $query = Owner::where('address', 'like', '%' . $valu . '%')->orWhere('state', 'like', '%' . $valu . '%')->orWhere('country', 'like', '%' . $valu . '%');
            if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                $owners = $query->where('first_name', 'not like', '%stranger%')->orderBy('id', 'DESC')->get();
            }else {
                $owners = $query->where('first_name', 'not like', '%stranger%')->orderBy('id', 'DESC')->paginate(10);
            }
        }
        $title = ucwords(trans('language_changer.User') . trans('language_changer.s') . " | ".trans('language_changer.search').' '.trans('language_changer.result')); /* 'Owners | Search Result' */

        if (Input::get('submit') && Input::get('submit') == 'Download_Report') {

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=Report_users.csv');
            $handle = fopen('php://output', 'w');

            fputcsv($handle, array(Lang::get('constants.id'), Lang::get('constants.name'), Lang::get('constants.email'), Lang::get('constants.phone'), Lang::get('constants.address'), Lang::get('constants.state'), Lang::get('constants.zip_code'), Lang::get('constants.debt'), Lang::get('constants.referred_by')));

            foreach ($owners as $owner) {

                //Owner Phone
                $ownerPhone = $owner->phone;

                //Email
                $ownerEmail = $owner->email;

                //Owner Address
                $ownerAddress = ( !empty( $owner->address ) ? $owner->address : Config::get('app.blank_fiend_val') );

                //Owner State
                $ownerState = ( !empty( $owner->state ) ? $owner->state : Config::get('app.blank_fiend_val') );

                //Owner Zip code
                $ownerZipCode = ( !empty( $owner->zipcode ) ? $owner->zipcode : Config::get('app.blank_fiend_val') );

                //Owner Referred By
                $refer = Owner::where('id', $owner->referred_by)->first();
                $ownerRefBy = ( !empty( $refer ) ? $refer->first_name . " " . $refer->last_name : Lang::get('constants.none') );

                fputcsv($handle, array(
                    $owner->id,
                    $owner->first_name . " " . $owner->last_name,
                    $ownerEmail,
                    $ownerPhone,
                    $ownerAddress,
                    $ownerState,
                    $ownerZipCode,
                    sprintf2($owner->debt, 2),
                    $ownerRefBy,
                ));
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

        } else {

            if(Config::get('app.locale') == 'arb'){
                $align_format="right";
            }elseif(Config::get('app.locale') == 'en'){
                $align_format="left";
            }

            return View::make('owners')
                ->with('title', $title)
                ->with('page', 'owners')
                ->with('align_format',$align_format)
                ->with('owners', $owners);
        }
    }

    public function walks() {
        Session::forget('type');
        Session::forget('valu');
        $walks = DB::table('request')
                ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                ->select('owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'walker.merchant_id as walker_merchant', 'request.id as id', 'request.created_at as date', 'request.payment_mode', 'request.is_started', 'request.is_walker_arrived', 'request.payment_mode', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker'
                        , 'request.status', 'request.time', 'request.distance', 'request.total', 'request.is_cancelled', 'request.transfer_amount')
                ->orderBy('request.created_at', 'DESC')
                ->paginate(10);
        $setting = Settings::where('key', 'paypal')->first();
        $title = ucwords(trans('language_changer.Request') . trans('language_changer.s'));

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }
        return View::make('walks')
                        ->with('title', $title)
                        ->with('page', 'walks')
                        ->with('walks', $walks)
                        ->with('align_format',$align_format)
                        ->with('setting', $setting);
    }

    // Search Walkers from Admin Panel
    public function searchreq() {

        /*print_r(Input::all());
        die()*/;

        if(!empty($_GET['valu'])) {
            $valu = $_GET['valu'];
            $type = $_GET['type'];

        } else {
            $valu = $_GET['filter_valu'];
            $type = $_GET['filter_type'];

        }

        Session::put('valu', $valu);
        Session::put('type', $type);
        if ($type == 'reqid') {
            $query = DB::table('request')
                    ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                    ->leftJoin('walker', 'request.current_walker', '=', 'walker.id')
                    ->groupBy('request.id')
                    ->select('owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'request.id as id', 'request.created_at as date', 'request.*', 'request.is_walker_arrived', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker'
                            , 'request.status', 'request.time', 'request.distance', 'request.total', 'request.is_cancelled', 'request.payment_mode')
                    ->where('request.id', $valu)
                    ->orderBy('request.created_at');
                    if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                        $walks = $query->get();
                    }else {
                        $walks = $query->paginate(10);
                    }
        } elseif ($type == 'owner') {
            $query = DB::table('request')
                    ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                    ->leftJoin('walker', 'request.current_walker', '=', 'walker.id')
                    ->groupBy('request.id')
                    ->select('owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'request.id as id', 'request.created_at as date', 'request.*', 'request.is_walker_arrived', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker'
                            , 'request.status', 'request.time', 'request.distance', 'request.total', 'request.is_cancelled', 'request.payment_mode')
                    ->where('owner.first_name', 'like', '%' . $valu . '%')
                    ->orWhere('owner.last_name', 'like', '%' . $valu . '%')
                    ->orderBy('request.created_at');
                    if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                        $walks = $query->get();
                    }else {
                        $walks = $query->paginate(10);
                    }
        } elseif ($type == 'walker') {
            $query = DB::table('request')
                    ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                    ->leftJoin('walker', 'request.current_walker', '=', 'walker.id')
                    ->groupBy('request.id')
                    ->select('owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'request.id as id', 'request.created_at as date', 'request.*', 'request.is_walker_arrived', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker'
                            , 'request.status', 'request.time', 'request.distance', 'request.total', 'request.is_cancelled', 'request.payment_mode')
                    ->where('walker.first_name', 'like', '%' . $valu . '%')
                    ->orWhere('walker.last_name', 'like', '%' . $valu . '%')
                    ->orderBy('request.created_at');
                    if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                        $walks = $query->get();
                    }else {
                        $walks = $query->paginate(10);
                    }

            /* $queries = DB::getQueryLog();
            $last_query = end($queries);

            echo "<pre>";
            print_r($last_query);
            exit; */

        } elseif ($type == 'payment') {
            if ($valu == "Stored Cards" || $valu == "cards" || $valu == "Cards" || $valu == "Card") {
                $value = 0;
            } elseif ($valu == "Pay by Cash" || $valu == "cash" || $valu == "Cash") {
                $value = 1;
            } elseif ($valu == "Paypal" || $valu == "paypal") {
                $value = 2;
            }

            $query = DB::table('request')
                    ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                    ->leftJoin('walker', 'request.current_walker', '=', 'walker.id')
                    ->groupBy('request.id')
                    ->select('owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'request.id as id', 'request.created_at as date', 'request.is_started', 'request.is_walker_arrived', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker'
                            , 'request.status', 'request.time', 'request.distance', 'request.total', 'request.is_cancelled', 'request.payment_mode')
                    ->Where('request.payment_mode', $valu)
                    ->orderBy('request.created_at');
                    if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                        $walks = $query->get();
                    }else {
                        $walks = $query->paginate(10);
                    }
        }

        $setting = Settings::where('key', 'paypal')->first();
        $title = ucwords(trans('language_changer.Request') . trans('language_changer.s') . " | ". trans('language_changer.search').' '.trans('language_changer.result')); /* 'Requests | Search Result' */

        if (Input::get('submit') && Input::get('submit') == 'Download_Report') {

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=Report_requests.csv');
            $handle = fopen('php://output', 'w');

            fputcsv($handle, array(Lang::get('constants.request_id'), Lang::get('constants.user_name'), Lang::get('constants.driver'), Lang::get('constants.date_time'), Lang::get('constants.status'), Lang::get('constants.amount'),Lang::get('constants.payment_mode'), Lang::get('constants.payment_status')));

            foreach ($walks as $walk) {

                //Walkers First name and last Name
                $walkerName = ( !empty( $walk->confirmed_walker ) ? $walk->walker_first_name . " " . $walk->walker_last_name : Lang::get('constants.un_assigned') );

                //WalkersDate
                $walkersDate = ( !empty( $walk->date )  ? date('Y-m-d/H:m:s',strtotime($walk->date)) : '' );

                $walkerIsCanceled = '';
                //Walker cancel status
                if ($walk->is_cancelled == 1) {
                    $walkerIsCanceled = Lang::get('constants.cancelled');
                } elseif ($walk->is_completed == 1) {
                    $walkerIsCanceled = Lang::get('constants.completed');
                } elseif ($walk->is_started == 1) {
                    $walkerIsCanceled = Lang::get('constants.started');
                } elseif ($walk->is_walker_arrived == 1) {
                    $walkerIsCanceled = Lang::get('constants.walker_arrived');
                } elseif ($walk->is_walker_started == 1) {
                    $walkerIsCanceled = Lang::get('constants.walker_started');
                } else {
                    $walkerIsCanceled = Lang::get('constants.yet_to_start');
                }

                $walkerPaymentMode = '';
                //Walker Payment Mode
                if ($walk->payment_mode == 0) {
                    $walkerPaymentMode = Lang::get('constants.stored_cards');
                } elseif ($walk->payment_mode == 1) {
                    $walkerPaymentMode = Lang::get('constants.pay_by_cash');
                } elseif ($walk->payment_mode == 2) {
                    $walkerPaymentMode = Lang::get('constants.paypal');
                }

                $walkerIsPaid = '';
                if ($walk->is_paid == 1) {
                    $walkerIsPaid = Lang::get('constants.completed');
                } elseif ($walk->is_paid == 0 && $walk->is_completed == 1) {
                    $walkerIsPaid = Lang::get('constants.pending');
                } else {
                    $walkerIsPaid = Lang::get('constants.request_not_completed');
                }

                fputcsv($handle, array(
                    $walk->id,
                    $walk->owner_first_name . " " . $walk->owner_last_name,
                    $walkerName,
                    $walkersDate,
                    $walkerIsCanceled,
                    sprintf2($walk->total, 2),
                    $walkerPaymentMode,
                    $walkerIsPaid,
                ));
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

        } else {
            if(Config::get('app.locale') == 'arb'){
                $align_format="right";
            }elseif(Config::get('app.locale') == 'en'){
                $align_format="left";
            }
            return View::make('walks')
                ->with('title', $title)
                ->with('page', 'walks')
                ->with('setting', $setting)
                ->with('align_format',$align_format)
                ->with('valu', $valu)
                ->with('walks', $walks);
        }
    }

    public function reviews() {
        Session::forget('type');
        Session::forget('valu');
        $provider_reviews = DB::table('review_walker')
                ->leftJoin('walker', 'review_walker.walker_id', '=', 'walker.id')
                ->leftJoin('owner', 'review_walker.owner_id', '=', 'owner.id')
                ->select('review_walker.id as review_id', 'review_walker.rating', 'review_walker.comment', 'owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'review_walker.created_at')
                ->orderBy('review_walker.id', 'DESC')
                ->paginate(10);

        $user_reviews = DB::table('review_dog')
                ->leftJoin('walker', 'review_dog.walker_id', '=', 'walker.id')
                ->leftJoin('owner', 'review_dog.owner_id', '=', 'owner.id')
                ->select('review_dog.id as review_id', 'review_dog.rating', 'review_dog.comment', 'owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'review_dog.created_at')
                ->orderBy('review_dog.id', 'DESC')
                ->paginate(10);
        $title = ucwords(trans('language_changer.Reviews')); /* 'Reviews' */

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }


        return View::make('reviews')
                        ->with('title', $title)
                        ->with('page', 'reviews')
                        ->with('provider_reviews', $provider_reviews)
                         ->with('align_format',$align_format)
                        ->with('user_reviews', $user_reviews);
    }

    public function searchrev() {

        $valu = $_GET['valu'];
        $type = $_GET['type'];
        Session::put('valu', $valu);
        Session::put('type', $type);
        if ($type == 'owner') {
            $provider_reviews = DB::table('review_walker')
                    ->leftJoin('walker', 'review_walker.walker_id', '=', 'walker.id')
                    ->leftJoin('owner', 'review_walker.owner_id', '=', 'owner.id')
                    ->select('review_walker.id as review_id', 'review_walker.rating', 'review_walker.comment', 'owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'review_walker.created_at')
                    ->where('owner.first_name', 'like', '%' . $valu . '%')->orWhere('owner.last_name', 'like', '%' . $valu . '%')
                    ->paginate(10);

            $reviews = DB::table('review_dog')
                    ->leftJoin('walker', 'review_dog.walker_id', '=', 'walker.id')
                    ->leftJoin('owner', 'review_dog.owner_id', '=', 'owner.id')
                    ->select('review_dog.id as review_id', 'review_dog.rating', 'review_dog.comment', 'owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'review_dog.created_at')
                    ->where('owner.first_name', 'like', '%' . $valu . '%')->orWhere('owner.last_name', 'like', '%' . $valu . '%')
                    ->paginate(10);
        } elseif ($type == 'walker') {
            $provider_reviews = DB::table('review_walker')
                    ->leftJoin('walker', 'review_walker.walker_id', '=', 'walker.id')
                    ->leftJoin('owner', 'review_walker.owner_id', '=', 'owner.id')
                    ->select('review_walker.id as review_id', 'review_walker.rating', 'review_walker.comment', 'owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'review_walker.created_at')
                    ->where('walker.first_name', 'like', '%' . $valu . '%')->orWhere('walker.last_name', 'like', '%' . $valu . '%')
                    ->paginate(10);

            $reviews = DB::table('review_dog')
                    ->leftJoin('walker', 'review_dog.walker_id', '=', 'walker.id')
                    ->leftJoin('owner', 'review_dog.owner_id', '=', 'owner.id')
                    ->select('review_dog.id as review_id', 'review_dog.rating', 'review_dog.comment', 'owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'review_dog.created_at')
                    ->where('walker.first_name', 'like', '%' . $valu . '%')->orWhere('walker.last_name', 'like', '%' . $valu . '%')
                    ->paginate(10);
        }
        $title = ucwords(trans('language_changer.Reviews') . " | " .trans('language_changer.search').' '.trans('language_changer.result') ); /* 'Reviews | Search Result' */

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }




        return View::make('reviews')
                        ->with('title', $title)
                        ->with('page', 'reviews')
                        ->with('provider_reviews', $provider_reviews)
            ->with('align_format',$align_format)
                        ->with('user_reviews', $reviews)
        /* ->with('reviews', $reviews) */;
    }

    public function search() {
        Session::forget('type');
        Session::forget('valu');
        $type = Input::get('type');
        $q = Input::get('q');
        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }



        if ($type == 'user') {
            $owners = Owner::where('first_name', 'like', '%' . $q . '%')
                    ->where('deleted_at', NULL)
                    ->orWhere('last_name', 'like', '%' . $q . '%')
                    ->paginate(10);
            $title = ucwords(trans('language_changer.User') . trans('language_changer.s')); /* 'Users' */
            return View::make('owners')
                            ->with('title', $title)
                            ->with('page', 'owners')
                ->with('align_format',$align_format)
                            ->with('owners', $owners);
        } else {

            $walkers = Walker::where('deleted_at', NULL)
                    ->where('deleted_at', NULL)
                    ->where('first_name', 'like', '%' . $q . '%')
                    ->orWhere('last_name', 'like', '%' . $q . '%')
                    ->paginate(10);
            $title = ucwords(trans('language_changer.Provider') . trans('language_changer.s')); /* 'Providers' */
            return View::make('walkers')
                            ->with('title', $title)
                ->with('align_format',$align_format)
                            ->with('page', 'walkers')
                            ->with('walkers', $walkers);
        }
    }

    public function logout() {
        Auth::logout();
        return Redirect::to('/admin/login');
    }

    public function verify() {
        $username = Input::get('username');
        $password = Input::get('password');
        if (!Admin::count()) {
            $user = new Admin;
            $user->username = Input::get('username');
            $user->password = $user->password = Hash::make(Input::get('password'));
            $user->save();
            return Redirect::to('/admin/login');
        } else {

            if (Auth::admin_panel()->attempt(array('username' => $username, 'password' => $password))) {

                if (Session::has('pre_admin_login_url')) {
                    $url = Session::get('pre_admin_login_url');
                    Session::forget('pre_admin_login_url');
                    return Redirect::to($url);
                } else {
                    $admin = Admin::where('username', 'like', '%' . $username . '%')->first();
                    Session::put('admin_id', $admin->id);
					Session::put('admin_type', $admin->type);
                    return Redirect::to('/admin/report')->with('notify', 'installation Notification');
                }
            } else {
                return Redirect::to('/admin/login?error=1');
            }
        }
    }

    public function login() {
        $error = Input::get('error');
        if (Admin::count()) {

            return View::make('login')->with('title', 'Login')->with('button', 'Login')->with('error', $error);
        } else {
            return View::make('login')->with('title', 'Create Admin')->with('button', 'Create')->with('error', $error);
        }
    }

    public function edit_walker() {
        $id = Request::segment(4);
        $type = ProviderType::where('is_visible', '=', 1)->get();
        $provserv = ProviderServices::where('provider_id', $id)->get();
        $success = Input::get('success');
        $walker = Walker::find($id);
        if ($walker) {
            $title = ucwords(trans('language_changer.edit')." " . trans('language_changer.Provider') . " : " . $walker->first_name . " " . $walker->last_name); /* 'Edit Provider' */
            if(Config::get('app.locale') == 'arb'){
                $align_format="right";
            }elseif(Config::get('app.locale') == 'en'){
                $align_format="left";
            }


            return View::make('edit_walker')
                ->with('title', $title)
                ->with('page', 'walkers')
                ->with('success', $success)
                ->with('walkerTypes', $type)
                ->with('ps', $provserv)
                ->with('align_format',$align_format)
                ->with('walker', $walker);
        } else {
            return View::make('notfound')->with('title', 'Error Page Not Found')->with('page', 'Error Page Not Found');
        }

    }

    public function provider_availabilty() {
        $id = Request::segment(5);
        $type = ProviderType::where('is_visible', '=', 1)->get();
        $provserv = ProviderServices::where('provider_id', $id)->get();
        $success = Input::get('success');
        $walker = Walker::find($id);
        $title = ucwords(trans('language_changer.edit')." " . trans('language_changer.Provider') . " : ".trans('language_changer.available')); /* 'Edit Provider Availability' */
        return View::make('edit_walker_availability')
                        ->with('title', $title)
                        ->with('page', 'walkers')
                        ->with('success', $success)
                        ->with('type', $type)
                        ->with('ps', $provserv)
                        ->with('walker', $walker);
    }

    public function add_walker() {
        $title = ucwords(trans('language_changer.add').' ' . trans('language_changer.Provider')); /* 'Add Provider' */
        return View::make('add_walker')
                        ->with('title', $title)
                        ->with('page', 'walkers');
    }

    public function add_promo_code() {
        $title = ucwords(trans('language_changer.add') .' ' .trans('language_changer.promo_codes')); /* 'Add Promo Code' */

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }

        return View::make('add_promo_code')
                        ->with('title', $title)
            ->with('align_format',$align_format)
                        ->with('page', 'promo_code');
    }

    public function edit_promo_code() {
        $id = Request::segment(4);
        $promo_code = PromoCodes::where('id', $id)->first();
        $title = ucwords(trans('language_changer.edit')." " . trans('language_changer.promo_codes')); /* 'Edit Promo Code' */

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }
        return View::make('edit_promo_code')
                        ->with('title', $title)
                        ->with('page', 'promo_code')
            ->with('align_format',$align_format)
                        ->with('promo_code', $promo_code);
    }

    public function deactivate_promo_code() {
        $id = Request::segment(4);
        $promo_code = PromoCodes::where('id', $id)->first();
        $promo_code->state = 2;
        $promo_code->save();
        return Redirect::route('AdminPromoCodes');
    }

    public function activate_promo_code() {
        $id = Request::segment(4);
        $promo_code = PromoCodes::where('id', $id)->first();
        $promo_code->state = 1;
        $promo_code->save();
        return Redirect::route('AdminPromoCodes');
    }

    public function update_promo_code() {
        $check = PromoCodes::where('coupon_code', '=', Input::get('code_name'))->where('id', '!=', Input::get('id'))->count();
        if ($check > 0) {

            return Redirect::to("admin/promo_code?success=1");
        }
        if (Input::get('id') != 0) {
            $promo = PromoCodes::find(Input::get('id'));
        } else {
            $promo = new PromoCodes;
        }

        $code_name = Input::get('code_name');
        $code_value = Input::get('code_value');
        $code_type = Input::get('code_type');
        $code_uses = Input::get('code_uses');
        $start_date = date("Y-m-d H:i:s", strtotime(trim(Input::get('start_date'))));
        $code_expiry = date("Y-m-d H:i:s", strtotime(trim(Input::get('code_expiry'))) + ((((23 * 60) + 59) * 60) + 59));

        $validator = Validator::make(
                        array(
                    'code_name' => $code_name,
                    'code_value' => $code_value,
                    'code_type' => $code_type,
                    'code_uses' => $code_uses,
                    'code_expiry' => $code_expiry,
                    'start_date' => $start_date,
                        ), array(
                    'code_name' => 'required',
                    'code_value' => 'required|integer',
                    'code_type' => 'required|integer',
                    'code_uses' => 'required|integer',
                    'code_expiry' => 'required',
                    'start_date' => 'required',
                        )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->first();
            Session::put('msg', $error_messages);
            $title = ucwords("Add " . trans('customize.promo_codes')); /* 'Add Promo Code' */
            return View::make('add_promo_code')
                            ->with('title', $title)
                            ->with('page', 'promo_codes');
        } else {
            $expirydate = date("Y-m-d H:i:s", strtotime($code_expiry));

            $promo->coupon_code = $code_name;
            $promo->value = $code_value;
            $promo->type = $code_type;
            $promo->uses = $code_uses;
            $promo->start_date = $start_date;
            $promo->expiry = $expirydate;
            $promo->state = 1;
            $promo->save();
        }
        return Redirect::route('AdminPromoCodes');
    }

    public function update_walker() {

        if (Input::get('id') != 0) {
            $walker = Walker::find(Input::get('id'));
        } else {

            $findWalker = Walker::where('email', Input::get('email'))->first();

            if ($findWalker) {
                Session::put('new_walker', 0);
                $error_messages = "This Email Id is already registered.";
                Session::put('msg', $error_messages);
                $title = ucwords(trans('language_changer.add')." " . trans('language_changer.Provider')); /* 'Add Provider' */
                return View::make('add_walker')
                    ->with('title', $title)
                    ->with('page', 'walkers');
            } else {
                Session::put('new_walker', 1);
                $walker = new Walker;
            }
        }
        if (Input::has('service') != NULL) {
            foreach (Input::get('service') as $key) {
                $serv = ProviderType::where('id', $key)->first();
                $pserv[] = $serv->name;
            }
        }

        $first_name = Input::get('first_name');
        $last_name = Input::get('last_name');

        $email = Input::get('email');
        $phone = Input::get('phone');
        $bio = Input::get('bio');
        $address = Input::get('address');
        $state = Input::get('state');
        $country = Input::get('country');
        $zipcode = Input::get('zipcode');
        $type=Input::get('type');


        if(Input::hasFile('pic')){
            $doc=Input::file('pic');
            $ext = $doc->getClientOriginalExtension();
            $check_array=array('jpg','png','jpeg','PNG','JPEG','JPG');
            if(!in_array($ext,$check_array)) {
                return Redirect::to("/admin/provider/edit/".Input::get('id')."?success=3");
            }
        }

        $validator = Validator::make(
            array(
                'first_name' => $first_name,
                'last_name' => $last_name,

                'email' => $email,
                'phone' => $phone,
                'bio' => $bio,
                'state' => $state,
                'country' => $country,
                'zipcode' => $zipcode,
                'type'=>$type


            ), array(
                'first_name' => 'required',
                'last_name' => 'required',

                'email' => 'required|email',
                'phone' => 'required',
                'bio' => 'required',
                'state' => 'required',
                'country' => 'required',
                'zipcode' => 'required|integer',
                'type'=>'required'
            )
        );



        if ($validator->fails()) {
            $error_messages = $validator->messages()->first();
            if(Config::get('app.locale') == 'spa'){
                $align_format="left";
            }elseif(Config::get('app.locale') == 'en'){
                $align_format="left";
            }
            return Redirect::to("/admin/provider/edit/".Input::get('id')."?success=2")->withInput()->withErrors($validator);

        } else {

            $walker->first_name = Input::get('first_name');
            $walker->last_name = Input::get('last_name');

            $walker->email = Input::get('email');
            $walker->phone = Input::get('phone');
            $walker->bio = Input::get('bio');
            $walker->address = Input::get('address');
            $walker->state = Input::get('state');
            $walker->type = Input::get('type');


            // adding password to new provider

            $new_password = time();
            $new_password .= rand();
            $new_password = sha1($new_password);
            $new_password = substr($new_password, 0, 8);
            /* $walker->password = Hash::make($new_password); */

            $walker->country = Input::get('country');
            $walker->zipcode = Input::get('zipcode');
            $walker->is_approved = 1;
            $walker->email_activation = 1;
            $car_number = trim(Input::get('car_number'));
            if ($car_number != "") {
                $walker->car_number = $car_number;
            }
            $car_model = trim(Input::get('car_model'));
            if ($car_model != "") {
                $walker->car_model = $car_model;
            }


            if (Input::hasFile('pic')) {
                $file_name = time();
                $file_name .= rand();
                $ext = Input::file('pic')->getClientOriginalExtension();
                Input::file('pic')->move(public_path() . "/uploads", $file_name . "." . $ext);
                $local_url = $file_name . "." . $ext;

                // Upload to S3
                if (Config::get('app.s3_bucket') != "") {
                    $s3 = App::make('aws')->get('s3');
                    $pic = $s3->putObject(array(
                        'Bucket' => Config::get('app.s3_bucket'),
                        'Key' => $file_name,
                        'SourceFile' => public_path() . "/uploads/" . $local_url,
                    ));

                    $s3->putObjectAcl(array(
                        'Bucket' => Config::get('app.s3_bucket'),
                        'Key' => $file_name,
                        'ACL' => 'public-read'
                    ));

                    $s3_url = $s3->getObjectUrl(Config::get('app.s3_bucket'), $file_name);
                } else {
                    $s3_url = asset_url() . '/uploads/' . $local_url;
                }

                if (isset($walker->picture)) {
                    if ($walker->picture != "") {
                        $icon = $walker->picture;
                        unlink_image($icon);
                    }
                }
                $walker->picture = $s3_url;
            }
            $walker->save();

            /*if (Input::has('type') != NULL || !empty(Input::has('type'))) {
                $ke = Input::get('type');
                $proviserv = ProviderServices::where('provider_id', $walker->id)->first();
                if ($proviserv != NULL) {
                    DB::delete("delete from walker_services where provider_id = '" . $walker->id . "';");
                }
                $base_price = Input::get('service_base_price');
                $service_price_distance = Input::get('service_price_distance');
                $service_price_time = Input::get('service_price_time');

                $type = Input::get('type');
                $myType = explode(',', $type);
                $cnkey = count($myType);

                if (Input::has('service_base_price')) {
                    $base_price = Input::get('service_base_price');
                    $base_price_array = explode(',', $base_price);
                }

                Log::info('cnkey = ' . print_r($cnkey, true));
                for ($i = 0; $i < $cnkey; $i++) {
                    $key = $myType[$i];
                    $prserv = new ProviderServices;
                    $prserv->provider_id = $walker->id;
                    $prserv->type = $key;
                    Log::info('key = ' . print_r($key, true));

                    if (Input::has('service_base_price')) {

                        $prserv->base_price = $base_price_array[$i];
                    } else {
                        $prserv->base_price = 0;
                    }
                    if (Input::has('service_price_distance')) {
                        $prserv->price_per_unit_distance = $service_price_distance[$i];
                    } else {
                        $prserv->price_per_unit_distance = 0;
                    }
                    if (Input::has('service_price_time')) {
                        $prserv->price_per_unit_time = $service_price_time[$i];
                    } else {
                        $prserv->price_per_unit_distance = 0;
                    }
                    $prserv->save();
                }
            }*/
            if (Session::get('new_walker') == 1) {
                // send email
                $settings = Settings::where('key', 'email_forgot_password')->first();
                $pattern = $settings->value;
                $pattern = "Hi, " . Config::get('app.website_title') . " is Created a New Account for you , Your Username is:" . Input::get('email') . " and Your Password is " . $new_password . ". Please dont forget to change the password once you log in next time.";
                $subject = "Welcome On Board";
                /* email_notification($walker->id, 'walker', $pattern, $subject); */
            }

            if (Input::has('service') != NULL) {
                foreach (Input::get('service') as $ke) {
                    $proviserv = ProviderServices::where('provider_id', $walker->id)->first();
                    if ($proviserv != NULL) {
                        DB::delete("delete from walker_services where provider_id = '" . $walker->id . "';");
                    }
                }
                $base_price = Input::get('service_base_price');
                $service_price_distance = Input::get('service_price_distance');
                $service_price_time = Input::get('service_price_time');
                Log::info('service = ' . print_r(Input::get('service'), true));
                $cnkey = count(Input::get('service'));
                $type_id = trim((Input::get('service')[0]));
                Log::info('cnkey = ' . print_r($cnkey, true));
                /* for ($i = 1; $i <= $cnkey; $i++) { */
                /* foreach (Input::get('service') as $key) { */
                $prserv = new ProviderServices;
                $prserv->provider_id = $walker->id;
                $prserv->type = $type_id;
                $walker->type = $type_id;
                $walker->save();
                /* Log::info('key = ' . print_r($key, true)); */
                if (Input::has('service_base_price')) {
                    $prserv->base_price = $base_price[$type_id];
                } else {
                    $prserv->base_price = 0;
                }
                if (Input::has('service_price_distance')) {
                    $prserv->price_per_unit_distance = $service_price_distance[$type_id];
                } else {
                    $prserv->price_per_unit_distance = 0;
                }
                if (Input::has('service_price_distance')) {
                    $prserv->price_per_unit_time = $service_price_time[$type_id];
                } else {
                    $prserv->price_per_unit_distance = 0;
                }
                $prserv->save();
                /* } */
                /* } */
            }
            Session::flash('message', 'Driver Successfully Updated!');
            return Redirect::to("/admin/providers");
        }
    }

public function change_status_walker()
{
	$id = Request::segment(4);
	$walker = Walker::where('id','=',$id)->first();
	if($walker->is_active)
	{
		$walker->is_active=0;
		$walker->save();
		return Redirect::to("/admin/providers");
	}
	    return Redirect::to("/admin/providers");
	
}
      
    public function approve_walker() {
        $id = Request::segment(4);
        $success = Input::get('success');
        $walker = Walker::find($id);
        $walker->is_approved = 1;

        $txt_approve = "Decline";
        if ($walker->is_approved) {
            $txt_approve = "Approved";
        }
        $response_array = array(
            'unique_id' => 5,
            'success' => true,
            'id' => $walker->id,
            'first_name' => $walker->first_name,
            'last_name' => $walker->last_name,
            'phone' => $walker->phone,
            'email' => $walker->email,
            'picture' => $walker->picture,
            'bio' => $walker->bio,
            'address' => $walker->address,
            'state' => $walker->state,
            'country' => $walker->country,
            'zipcode' => $walker->zipcode,
            'login_by' => $walker->login_by,
            'social_unique_id' => $walker->social_unique_id,
            'device_token' => $walker->device_token,
            'device_type' => $walker->device_type,
            'token' => $walker->token,
            'type' => $walker->type,
            'is_approved' => $walker->is_approved,
            'is_approved_txt' => $txt_approve,
        );
        $title = "You are approved";
        $message = $response_array;
        send_notifications($id, "walker", $title, $message, "imp");
        /* SMS */
        $settings = Settings::where('key', 'sms_walker_approve')->first();
        $pattern = $settings->value;
        $pattern = str_replace('%name%', $walker->first_name . " " . $walker->last_name, $pattern);
        sms_notification($id, 'walker', $pattern, "imp");
        /* SMS END */
        /* EMAIL */
        /* $settings = Settings::where('key', 'email_walker_approve')->first();
          $pattern = $settings->value;
          $pattern = str_replace('%name%', $walker->first_name . " " . $walker->last_name, $pattern); */
        $settings = Settings::where('key', 'admin_email_address')->first();
        $admin_email = $settings->value;
        $pattern = array('walker_name' => $walker->first_name . " " . $walker->last_name, 'admin_eamil' => $admin_email);
        $subject = "Welcome " . $walker->first_name . " " . $walker->last_name . " To " . Config::get('app.website_title') . "";
        email_notification($id, 'walker', $pattern, $subject, 'walker_approve');
        /* EMAIL END */
        $walker->save();
        /*  $pattern = "Hi " . $walker->first_name . ", Your Documents are verified by the Admin and your account is Activated, Please Login to Continue";
          $subject = "Your Account Activated";
          email_notification($walker->id, 'walker', $pattern, $subject); */
        return Redirect::to("/admin/providers");
    }

    public function decline_walker() {
        $id = Request::segment(4);
        $success = Input::get('success');
        $walker = Walker::find($id);

        Session::forget('message');
        if($walker->is_available == 1 ) {
            $walker->is_approved = 0;
            $txt_approve = "Decline";
            if ($walker->is_approved) {
                $txt_approve = "Approved";
            }
            $response_array = array(
                'unique_id' => 5,
                'success' => true,
                'id' => $walker->id,
                'first_name' => $walker->first_name,
                'last_name' => $walker->last_name,
                'phone' => $walker->phone,
                'email' => $walker->email,
                'picture' => $walker->picture,
                'bio' => $walker->bio,
                'address' => $walker->address,
                'state' => $walker->state,
                'country' => $walker->country,
                'zipcode' => $walker->zipcode,
                'login_by' => $walker->login_by,
                'social_unique_id' => $walker->social_unique_id,
                'device_token' => $walker->device_token,
                'device_type' => $walker->device_type,
                'token' => $walker->token,
                'type' => $walker->type,
                'is_approved' => $walker->is_approved,
                'is_approved_txt' => $txt_approve,
            );
            $title = "You are Decline";
            $message = $response_array;
            send_notifications($id, "walker", $title, $message, "imp");
            /* SMS */
            $settings = Settings::where('key', 'sms_walker_decline')->first();
            $pattern = $settings->value;
            $pattern = str_replace('%name%', $walker->first_name . " " . $walker->last_name, $pattern);
            sms_notification($id, 'walker', $pattern);
            /* SMS END */
            /* EMAIL */
            /* $settings = Settings::where('key', 'email_walker_decline')->first();
              $pattern = $settings->value;
              $pattern = str_replace('%name%', $walker->first_name . " " . $walker->last_name, $pattern); */
            $settings = Settings::where('key', 'admin_email_address')->first();
            $admin_email = $settings->value;
            $pattern = array('walker_name' => $walker->first_name . " " . $walker->last_name, 'admin_eamil' => $admin_email);
            $subject = "Welcome " . $walker->first_name . " " . $walker->last_name . " To " . Config::get('app.website_title') . "";
            email_notification($id, 'walker', $pattern, $subject, 'walker_decline');
            /* EMAIL END */
            $walker->save();
        }else{

            Session::put('message',"Driver is on trip cannot Decline");
        }
        /* $pattern = "Hi " . $walker->first_name . ", Your account is deactivated, Please contact admin to Continue";
          $subject = "Your Account Deactivated";
          email_notification($walker->id, 'walker', $pattern, $subject); */
        return Redirect::to("/admin/providers");
    }


    public function walker_state_change(){


        $id = Request::segment(4);
        $walker = Walker::find($id);
        Session::forget('message');
        Session::forget('alert_type');
        
        $alert_type="alert-success";
        
        if($walker->is_approved ==1){

            if($walker->is_available == 1 ) {

                if($walker->is_active ==1){


                    /* Make offline */
                    $walker->is_active=0;
                    $walker->save();

                    $walker_data=Walker::find($id);
                    /*only send push to android*/
                    if(!empty($walker_data) && $walker_data->device_type !='ios' ){

                        $msg_array = array();

                        $msg_array['success'] = True;

                        $msg_array['is_approved'] = $walker_data->is_approved;

                        $msg_array['is_available'] = $walker_data->is_active;

                        $msg_array['in_free'] = $walker_data->is_available;

                        $msg_array['is_active_txt'] = "offline";

                        $msg_array['unique_id'] = 8;

                        $title=trans('language_changer.you_are_offline');
                       
                        send_notifications($walker_data->id, "walker", $title, $msg_array);
                    }

                    Session::put('message',trans('language_changer.driver_offline_success'));
                    Session::put('alert_type', $alert_type);

                }else{


                    /* Make online */

                    $walker->is_active=1;
                    $walker->save();
                    $walker_data = Walker::find($id);

                    /*only send push to android*/
                    if(!empty($walker_data) && $walker_data->device_type !='ios' ) {



                        $msg_array = array();


                        $msg_array['success'] = True;

                        $msg_array['is_approved'] = $walker_data->is_approved;

                        $msg_array['is_available'] = $walker_data->is_active;

                        $msg_array['in_free'] = $walker_data->is_available;

                        $msg_array['is_active_txt'] = "online";

                        $msg_array['unique_id'] = 9;

                        $title=trans('language_changer.you_are_online');

                        send_notifications($walker_data->id, "walker", $title, $msg_array);

                    }


                    Session::put('message',trans('language_changer.driver_online_success'));
                    Session::put('alert_type', $alert_type);
                }

            }else{

                $alert_type="alert-danger";
                Session::put('message',trans('language_changer.cannot_make_driver_offline'));
                Session::put('alert_type', $alert_type);
            }
        }

        return Redirect::to("/admin/providers");

    }



   /*public function walker_trip_earnings(){
       $id = Request::segment(4);

       $request=DB::table("request")
                ->select("id","request_start_time","total","driver_per_payment","pickupDetails as pick_details","dropoffDetails as drop_details","distance")
                ->where("confirmed_walker",$id)
                ->where("is_completed",1)
                ->get();

print_r($request);
       die();

   }*/



    public function delete_walker() {
        $id = Request::segment(4);
        $success = Input::get('success');
        $walkers= Walker::find($id);
       if(!empty($walkers) && $walkers->is_available !=0){
           RequestMeta::where('walker_id', $id)->delete();
           Walker::where('id', $id)->delete();

           $walkers->is_active=0;
           $walkers->save();

       }



        return Redirect::to("/admin/providers");
    }

    public function delete_owner() {
        $id = Request::segment(4);
        $success = Input::get('success');
        Owner::where('id', $id)->delete();
        return Redirect::to("/admin/users");
    }

    public function walker_history() {
        $walker_id = Request::segment(4);
        $walks = DB::table('request')
                ->where('request.confirmed_walker', $walker_id)
                ->where('request.is_completed', 1)
                ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                ->select('owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'request.id as id', 'request.created_at as date', 'request.is_started', 'request.is_walker_arrived', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker', 'request.status', 'request.time', 'request.distance', 'request.total', 'request.is_cancelled', 'request.payment_mode')
                ->orderBy('request.created_at')
                ->paginate(10);
        $title = ucwords(trans('language_changer.Provider') ." ".trans('language_changer.history') ); /* 'Trip History' */
        foreach ($walks as $walk) {
            $title = ucwords(trans('customize.Provider') . ' History' . " : " . $walk->walker_first_name . " " . $walk->walker_last_name);
        }
        $setting = Settings::where('key', 'transfer')->first();

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }



        return View::make('walks')
                        ->with('title', $title)
                        ->with('page', 'walkers')
                        ->with('setting', $setting)
            ->with('align_format',$align_format)
                        ->with('walks', $walks);
    }

    public function walker_documents() {
        $walker_id = Request::segment(4);
        $walker = Walker::find($walker_id);
        $documents = Document::all();
        $walker_document = WalkerDocument::where('walker_id', $walker_id)->get();


        return View::make('walker_document_list')
                        ->with('title', 'Driver Documents')
                        ->with('page', 'walkers')
                        ->with('walker', $walker)
                        ->with('documents', $documents)
                        ->with('walker_document', $walker_document);
    }

    public function walker_upcoming_walks() {
        $walker_id = Request::segment(4);
        $walks = DB::table('request')
                ->where('request.walker_id', $walker_id)
                ->where('request.is_completed', 0)
                ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                ->select('owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'request.id as id', 'request.created_at as date', 'request.is_started', 'request.is_walker_arrived', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker', 'request.status', 'request.time', 'request.distance', 'request.total')
                ->orderBy('request.created_at')
                ->paginate(10);
        $title = ucwords(trans('language_changer.Provider') . " ".trans('language_changer.upcoming') ." ". trans('language_changer.Request') . trans('language_changer.s')); /* 'Upcoming Walks' */
        foreach ($walks as $walk) {
            $title = ucwords(trans('customize.Provider') .  " ".trans('language_changer.upcoming') ." ". trans('language_changer.Request') . trans('language_changer.s') . " : " . $walk->walker_first_name . " " . $walk->walker_last_name);
        }
        return View::make('walks')
                        ->with('title', $title)
                        ->with('page', 'walkers')
                        ->with('walks', $walks);
    }

    public function edit_owner() {
        $id = Request::segment(4);
        $success = Input::get('success');
        $owner = Owner::find($id);
        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }



        if ($owner) {
            $title = ucwords(trans('language_changer.edit') .' '.trans('language_changer.User') . " : " . $owner->first_name . " " . $owner->last_name); /* 'Edit User' */
            return View::make('edit_owner')
                            ->with('title', $title)
                            ->with('page', 'owners')
                            ->with('success', $success)
                            ->with('align_format', $align_format)
                            ->with('owner', $owner);
        } else {
            return View::make('notfound')
                            ->with('title', 'Error Page Not Found')
                            ->with('page', 'Error Page Not Found');
        }
    }

    public function update_owner() {


        if(Input::hasFile('image')){
            $doc=Input::file('image');
            $ext = $doc->getClientOriginalExtension();
            $check_array=array('jpg','png','jpeg','PNG','JPEG','JPG');
            if(!in_array($ext,$check_array)) {
                return Redirect::to("/admin/user/edit/".Input::get('id')."?success=3");
            }
        }

        $owner = Owner::find(Input::get('id'));
        $owner->first_name = Input::get('first_name');
        $owner->last_name = Input::get('last_name');
        //   $owner->country = Input::get('country');
        $owner->address = Input::get('address');
        $owner->state = Input::get('state');
        $owner->zipcode = Input::get('zipcode');
        if (Input::hasFile('image')) {
            // Upload File
            $file_name = time();
            $file_name .= rand();
            $ext = Input::file('image')->getClientOriginalExtension();
            Input::file('image')->move(public_path() . "/uploads", $file_name . "." . $ext);
            $local_url = $file_name . "." . $ext;

            // Upload to S3
            if (Config::get('app.s3_bucket') != "") {
                $s3 = App::make('aws')->get('s3');
                $pic = $s3->putObject(array(
                    'Bucket' => Config::get('app.s3_bucket'),
                    'Key' => $file_name,
                    'SourceFile' => public_path() . "/uploads/" . $local_url,
                ));

                $s3->putObjectAcl(array(
                    'Bucket' => Config::get('app.s3_bucket'),
                    'Key' => $file_name,
                    'ACL' => 'public-read'
                ));

                $s3_url = $s3->getObjectUrl(Config::get('app.s3_bucket'), $file_name);
            } else {
                $s3_url = asset_url() . '/uploads/' . $local_url;
            }

            if (isset($owner->picture)) {
                if ($owner->picture != "") {
                    $icon = $owner->picture;
                    unlink_image($icon);
                }
            }
            $owner->picture = $s3_url;
        }

        $owner->save();
        Session::flash('message', 'User Successfully updated!');
        return Redirect::to("/admin/users");

    }







    public function update_owner_old() {
        $owner = Owner::find(Input::get('id'));
        $owner->first_name = Input::get('first_name');
        $owner->last_name = Input::get('last_name');
        $owner->email = Input::get('email');
        $owner->phone = Input::get('phone');
        $owner->address = Input::get('address');
        $owner->state = Input::get('state');
        $owner->zipcode = Input::get('zipcode');
        $owner->save();
        return Redirect::to("/admin/user/edit/$owner->id?success=1");
    }

    public function owner_history() {
        $setting = Settings::where('key', 'transfer')->first();
        $owner_id = Request::segment(4);
        $owner = Owner::find($owner_id);
        $walks = DB::table('request')
                ->where('request.owner_id', $owner->id)
                ->where('request.is_completed', 1)
                ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                ->select('owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'request.id as id', 'request.created_at as date', 'request.is_started', 'request.is_walker_arrived', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker', 'request.status', 'request.time', 'request.distance', 'request.total', 'request.is_cancelled', 'request.payment_mode')
                ->orderBy('request.created_at')
                ->paginate(10);
        $title = ucwords(trans('language_changer.owner') .' '. trans('language_changer.history')); /* 'Trip History' */
        foreach ($walks as $walk) {
            $title = ucwords(trans('language_changer.owner') .' '.trans('language_changer.history') . " : " . $walk->owner_first_name . " " . $walk->owner_last_name);
        }

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }
        return View::make('walks')
                        ->with('title', $title)
                        ->with('page', 'owners')
                        ->with('setting', $setting)
            ->with('align_format',$align_format)
                        ->with('walks', $walks);
    }

    public function owner_upcoming_walks() {
        $owner_id = Request::segment(4);
        $owner = Owner::find($owner_id);
        $walks = DB::table('request')
                ->where('request.owner_id', $owner->id)
                ->where('request.is_completed', 0)
                ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                ->select('owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'request.id as id', 'request.created_at as date', 'request.is_started', 'request.is_walker_arrived', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker', 'request.status', 'request.time', 'request.distance', 'request.total')
                ->orderBy('request.created_at')
                ->paginate(10);
        $title = ucwords(trans('language_changer.User') . " ".trans('language_changer.upcoming') ." " . trans('language_changer.Request') . trans('language_changer.s')); /* 'Upcoming Walks' */
        foreach ($walks as $walk) {
            $title = ucwords(trans('customize.User') . " ".trans('language_changer.upcoming')." ". trans('language_changer.Request') .  trans('language_changer.s') . " : " . $walk->owner_first_name . " " . $walk->owner_last_name);
        }
        return View::make('walks')
                        ->with('title', $title)
                        ->with('page', 'owners')
                        ->with('walks', $walks);
    }

    public function delete_review() {
        $id = Request::segment(4);
        $walker = WalkerReview::where('id', $id)->delete();
        return Redirect::to("/admin/reviews");
    }

    public function delete_review_owner() {
        $id = Request::segment(4);
        $walker = DogReview::where('id', $id)->delete();
        return Redirect::to("/admin/reviews");
    }

    public function approve_walk() {
        $id = Request::segment(4);
        $walk = Walk::find($id);
        $walk->is_confirmed = 1;
        $walk->save();
        return Redirect::to("/admin/walks");
    }

    public function decline_walk() {
        $id = Request::segment(4);
        $walk = Walk::find($id);
        $walk->is_confirmed = 0;
        $walk->save();
        return Redirect::to("/admin/walks");
    }

    public function view_map() {
        $id = Request::segment(4);
        $request = Requests::find($id);
        $walker = Walker::where('id', $request->confirmed_walker)->first();
        $owner = Owner::where('id', $request->owner_id)->first();
        if ($request->is_paid) {
            $status = trans('language_changer.payment_done');
        } elseif ($request->is_completed) {
            $status = trans('language_changer.payment_done');
        } elseif ($request->is_started) {
            $status = trans('language_changer.request').' '.trans('language_changer.start').trans('language_changer.ed');
        } elseif ($request->is_walker_started) {
            $status = trans('language_changer.provider').' '.trans('language_changer.start').trans('language_changer.ed');;
        } elseif ($request->confirmed_walker) {
            $status = trans('language_changer.provider').' '.trans('language_changer.yet_to_start');
        } else {
            $status = trans('language_changer.provider').' '.trans('language_changer.not_confirmed');
        }
        if ($request->is_cancelled == 1) {
            $status1 = "<span class='badge bg-red'>".trans('language_changer.cancelled')."</span>";
        } elseif ($request->is_completed == 1) {
            $status1 = "<span class='badge bg-green'>".trans('language_changer.completed')."</span>";
        } elseif ($request->is_started == 1) {
            $status1 = "<span class='badge bg-yellow'>".trans('language_changer.start').trans('language_changer.ed')."</span>";
        } elseif ($request->is_walker_arrived == 1) {
            $status1 = "<span class='badge bg-yellow'>".trans('language_changer.walker').' '.trans('language_changer.arrived')."</span>";
        } elseif ($request->is_walker_started == 1) {
            $status1 = "<span class='badge bg-yellow'>".trans('language_changer.walker').' '.trans('language_changer.start').trans('language_changer.ed')."</span>";
        } else {
            $status1 = "<span class='badge bg-light-blue'>".trans('language_changer.yet_to_start')."</span>";
        }
        if ($request->payment_mode == 0) {
            $pay_mode = "<span class='badge bg-orange'>".trans('language_changer.stored').' '.trans('language_changer.card')."</span>";
        } elseif ($request->payment_mode == 1) {
            $pay_mode = "<span class='badge bg-blue'>".trans('language_changer.pay_by_cash')."</span>";
        } elseif ($request->payment_mode == 2) {
            $pay_mode = "<span class='badge bg-purple'>".trans('language_changer.pay_pal')."</span>";
        }
        if ($request->is_paid == 1) {
            $pay_status = "<span class='badge bg-green'>".trans('language_changer.completed')."</span>";
        } elseif ($request->is_paid == 0 && $request->is_completed == 1) {
            $pay_status = "<span class='badge bg-red'>".trans('language_changer.pending')."</span>";
        } else {
            $pay_status = "<span class='badge bg-yellow'>".trans('language_changer.request_not_completed')."</span>";
        }


        if ($request->is_completed) {
            $full_walk = WalkLocation::where('request_id', '=', $id)->orderBy('created_at')->get();
            $walk_location_start = WalkLocation::where('request_id', $id)->orderBy('created_at')->first();
            $walk_location_end = WalkLocation::where('request_id', $id)->orderBy('created_at', 'desc')->first();
            $walker_latitude = $walk_location_start->latitude;
            $walker_longitude = $walk_location_start->longitude;
            $owner_latitude = $walk_location_end->latitude;
            $owner_longitude = $walk_location_end->longitude;
        } else {
            $full_walk = WalkLocation::where('request_id', '=', $id)->orderBy('created_at')->get();
            /* $full_walk = array(); */
            if ($request->confirmed_walker) {
                $walker_latitude = $walker->latitude;
                $walker_longitude = $walker->longitude;
            } else {
                $walker_latitude = 0;
                $walker_longitude = 0;
            }
            $owner_latitude = $owner->latitude;
            $owner_longitude = $owner->longitude;
        }

        $request_meta = DB::table('request_meta')
                ->where('request_id', $id)
                ->leftJoin('walker', 'request_meta.walker_id', '=', 'walker.id')
                ->paginate(10);

        if ($walker) {
            $walker_name = $walker->first_name . " " . $walker->last_name;
            $walker_phone = $walker->phone;
        } else {
            $walker_name = "";
            $walker_phone = "";
        }


        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }



        $title = ucwords(trans('language_changer.map'));
        if ($request->confirmed_walker) {


            return View::make('walk_map')
                            ->with('title', $title)
                            ->with('page', 'walks')
                            ->with('walk_id', $id)
                            ->with('is_started', $request->is_started)
                            ->with('time', $request->time)
                            ->with('start_time', $request->request_start_time)
                            ->with('amount', $request->total)
                            ->with('owner_name', $owner->first_name . " " . $owner->last_name)
                            ->with('walker_name', $walker_name)
                            ->with('walker_latitude', $walker_latitude)
                            ->with('walker_longitude', $walker_longitude)
                            ->with('owner_latitude', $owner_latitude)
                            ->with('owner_longitude', $owner_longitude)
                            ->with('walker_phone', $walker_phone)
                            ->with('owner_phone', $owner->phone)
                            ->with('status', $status)
                            ->with('status1', $status1)
                            ->with('pay_mode', $pay_mode)
                            ->with('pay_status', $pay_status)
                            ->with('full_walk', $full_walk)
                              ->with('align_format',$align_format)
                            ->with('request_meta', $request_meta);
        } else {

            return View::make('walk_map')
                            ->with('title', $title)
                            ->with('page', 'walks')
                            ->with('walk_id', $id)
                            ->with('is_started', $request->is_started)
                            ->with('time', $request->time)
                            ->with('start_time', $request->request_start_time)
                            ->with('amount', $request->total)
                            ->with('owner_name', $owner->first_name . " ", $owner->last_name)
                            ->with('walker_name', "")
                            ->with('walker_latitude', $walker_latitude)
                            ->with('walker_longitude', $walker_longitude)
                            ->with('owner_latitude', $owner_latitude)
                            ->with('owner_longitude', $owner_longitude)
                            ->with('walker_phone', "")
                            ->with('owner_phone', $owner->phone)
                            ->with('request_meta', $request_meta)
                            ->with('full_walk', $full_walk)
                            ->with('status1', $status1)
                            ->with('pay_mode', $pay_mode)
                            ->with('pay_status', $pay_status)
                ->with('align_format',$align_format)
                            ->with('status', $status);
        }
    }

    public function change_walker() {
        $id = Request::segment(4);
        $title = ucwords(trans('language_changer.map_view'));
        return View::make('reassign_walker')
                        ->with('title', $title)
                        ->with('page', 'walks')
                        ->with('walk_id', $id);
    }

    public function alternative_walkers_xml() {
        $id = Request::segment(4);
        $walk = Walk::find($id);
        $schedule = Schedules::find($walk->schedule_id);
        $dog = Dog::find($walk->dog_id);
        $owner = Owner::find($dog->owner_id);
        $current_walker = Walker::find($walk->walker_id);
        $latitude = $owner->latitude;
        $longitude = $owner->longitude;
        $distance = 5;


        // Get Latitude
        $schedule_meta = ScheduleMeta::where('schedule_id', '=', $schedule->id)
                ->orderBy('started_on', 'DESC')
                ->get();

        $flag = 0;
        $date = "0000-00-00";
        $days = array();
        foreach ($schedule_meta as $meta) {
            if ($flag == 0) {
                $date = $meta->started_on;
                $flag++;
            }
            array_push($days, $meta->day);
        }

        $start_time = date('H:i:s', strtotime($schedule->start_time) - (60 * 60));
        $end_time = date('H:i:s', strtotime($schedule->end_time) + (60 * 60));
        $days_str = implode(',', $days);
        $settings = Settings::where('key', 'default_distance_unit')->first();
        $unit = $settings->value;
        if ($unit == 0) {
            $multiply = 1.609344;
        } elseif ($unit == 1) {
            $multiply = 1;
        }

        $query = "SELECT walker.id,walker.bio,walker.first_name,walker.last_name,walker.phone,walker.latitude,walker.longitude from walker where id NOT IN ( SELECT distinct schedules.walker_id FROM `schedule_meta` left join schedules on schedule_meta.schedule_id = schedules.id where schedules.is_confirmed	 != 0 and schedule_meta.day IN ($days_str) and schedule_meta.ends_on >= '$date' and schedule_meta.started_on <= '$date' and ((schedules.start_time > '$start_time' and schedules.start_time < '$end_time') OR ( schedules.end_time > '$start_time' and schedules.end_time < '$end_time' )) ) and "
                . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                . "cos( radians(latitude) ) * "
                . "cos( radians(longitude) - radians('$longitude') ) + "
                . "sin( radians('$latitude') ) * "
                . "sin( radians(latitude) ) ) ) ,8) <= $distance ";

        $walkers = DB::select(DB::raw($query));
        $response = "";
        $response .= '<markers>';

        foreach ($walkers as $walker) {
            $response .= '<marker ';
            $response .= 'name="' . $walker->first_name . " " . $walker->last_name . '" ';
            $response .= 'client_name="' . $walker->first_name . " " . $walker->last_name . '" ';
            $response .= 'contact="' . $walker->phone . '" ';
            $response .= 'amount="' . 0 . '" ';
            $response .= 'lat="' . $walker->latitude . '" ';
            $response .= 'lng="' . $walker->longitude . '" ';
            $response .= 'id="' . $walker->id . '" ';
            $response .= 'type="client" ';
            $response .= '/>';
        }

        // Add Current walker
        if ($current_walker) {
            $response .= '<marker ';
            $response .= 'name="' . $current_walker->first_name . " " . $current_walker->last_name . '" ';
            $response .= 'client_name="' . $current_walker->first_name . " " . $current_walker->last_name . '" ';
            $response .= 'contact="' . $current_walker->phone . '" ';
            $response .= 'amount="' . 0 . '" ';
            $response .= 'lat="' . $current_walker->latitude . '" ';
            $response .= 'lng="' . $current_walker->longitude . '" ';
            $response .= 'id="' . $current_walker->id . '" ';
            $response .= 'type="driver" ';
            $response .= '/>';
        }

        // Add Owner
        $response .= '<marker ';
        $response .= 'name="' . $owner->first_name . " " . $owner->last_name . '" ';
        $response .= 'client_name="' . $owner->first_name . " " . $owner->last_name . '" ';
        $response .= 'contact="' . $owner->phone . '" ';
        $response .= 'amount="' . 0 . '" ';
        $response .= 'lat="' . $owner->latitude . '" ';
        $response .= 'lng="' . $owner->longitude . '" ';
        $response .= 'id="' . $owner->id . '" ';
        $response .= 'type="client_pay_done" ';
        $response .= '/>';

        // Add Busy Walkers

        $walkers = DB::table('request')
                ->where('walk.is_started', 1)
                ->where('walk.is_completed', 0)
                ->join('walker', 'walk.walker_id', '=', 'walker.id')
                ->select('walker.id', 'walker.phone', 'walker.first_name', 'walker.last_name', 'walker.latitude', 'walker.longitude')
                ->distinct()
                ->get();


        foreach ($walkers as $walker) {
            $response .= '<marker ';
            $response .= 'name="' . $walker->first_name . " " . $walker->last_name . '" ';
            $response .= 'client_name="' . $walker->first_name . " " . $walker->last_name . '" ';
            $response .= 'contact="' . $walker->phone . '" ';
            $response .= 'amount="' . 0 . '" ';
            $response .= 'lat="' . $walker->latitude . '" ';
            $response .= 'lng="' . $walker->longitude . '" ';
            $response .= 'id="' . $owner->id . '" ';
            $response .= 'type="client_no_pay" ';
            $response .= '/>';
        }


        $response .= '</markers>';

        $content = View::make('walkers_xml')->with('response', $response);
        return Response::make($content, '200')->header('Content-Type', 'text/xml');
    }

    public function save_changed_walker() {
        $walk_id = Input::get('walk_id');
        $type = Input::get('type');
        $walker_id = Input::get('walker_id');
        $walk = Walk::find($walk_id);
        if ($type == 1) {
            $walk->walker_id = $walker_id;
            $walk->save();
        } else {
            Walk::where('schedule_id', $walk->schedule_id)->where('is_started', 0)->update(array('walker_id' => $walker_id));
            Schedules::where('id', $walk->schedule_id)->update(array('walker_id' => $walker_id));
        }
        return Redirect::to('/admin/walk/change_walker/' . $walk_id);
    }

    public function pay_walker() {
        $walk_id = Input::get('walk_id');
        $amount = Input::get('amount');
        $walk = Walk::find($walk_id);
        $walk->is_paid = 1;
        $walk->amount = $amount;
        $walk->save();

        return Redirect::to('/admin/walk/map/' . $walk_id);
    }

//settings
    public function get_settings() {
        $braintree_environment = Config::get('app.braintree_environment');
        $braintree_merchant_id = Config::get('app.braintree_merchant_id');
        $braintree_public_key = Config::get('app.braintree_public_key');
        $braintree_private_key = Config::get('app.braintree_private_key');
        $braintree_cse = Config::get('app.braintree_cse');
        $twillo_account_sid = Config::get('app.twillo_account_sid');
        $twillo_auth_token = Config::get('app.twillo_auth_token');
        $twillo_number = Config::get('app.twillo_number');
        $timezone = Config::get('app.timezone');
        $stripe_publishable_key = Config::get('app.stripe_publishable_key');
        $url = Config::get('app.url');
        $website_title = Config::get('app.website_title');
        $s3_bucket = Config::get('app.s3_bucket');
        $default_payment = Config::get('app.default_payment');
        $stripe_secret_key = Config::get('app.stripe_secret_key');
        $mail_driver = Config::get('mail.mail_driver');
        $email_name = Config::get('mail.from.name');
        $email_address = Config::get('mail.from.address');
        $mandrill_secret = Config::get('services.mandrill_secret');
        $host = Config::get('mail.host');
        /* DEVICE PUSH NOTIFICATION DETAILS */
        $customer_certy_url = Config::get('app.customer_certy_url');
        $customer_certy_pass = Config::get('app.customer_certy_pass');
        $customer_certy_type = Config::get('app.customer_certy_type');
        $provider_certy_url = Config::get('app.provider_certy_url');
        $provider_certy_pass = Config::get('app.provider_certy_pass');
        $provider_certy_type = Config::get('app.provider_certy_type');
        $gcm_browser_key = Config::get('app.gcm_browser_key');
        /* DEVICE PUSH NOTIFICATION DETAILS END */
        $install = array(
            'braintree_environment' => $braintree_environment,
            'braintree_merchant_id' => $braintree_merchant_id,
            'braintree_public_key' => $braintree_public_key,
            'braintree_private_key' => $braintree_private_key,
            'braintree_cse' => $braintree_cse,
            'twillo_account_sid' => $twillo_account_sid,
            'twillo_auth_token' => $twillo_auth_token,
            'twillo_number' => $twillo_number,
            'stripe_publishable_key' => $stripe_publishable_key,
            'stripe_secret_key' => $stripe_secret_key,
            'mail_driver' => $mail_driver,
            'email_address' => $email_address,
            'email_name' => $email_name,
            'mandrill_secret' => $mandrill_secret,
            'default_payment' => $default_payment,
            /* DEVICE PUSH NOTIFICATION DETAILS */
            'customer_certy_url' => $customer_certy_url,
            'customer_certy_pass' => $customer_certy_pass,
            'customer_certy_type' => $customer_certy_type,
            'provider_certy_url' => $provider_certy_url,
            'provider_certy_pass' => $provider_certy_pass,
            'provider_certy_type' => $provider_certy_type,
            'gcm_browser_key' => $gcm_browser_key,
                /* DEVICE PUSH NOTIFICATION DETAILS END */
        );
        $success = Input::get('success');
        $settings = Settings::all();
        /* $theme = Theme::all(); */
        $theme = Theme::first();
        if (isset($theme->id)) {
            $theme = Theme::first();
        } else {
            $theme = array();
        }
        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }




        $title = ucwords(trans('language_changer.Settings')); /* 'Settings' */
        return View::make('settings')
                        ->with('title', $title)
                        ->with('page', 'settings')
                        ->with('settings', $settings)
                        ->with('success', $success)
                        ->with('install', $install)
                         ->with('align_format',$align_format)
                        ->with('theme', $theme);
    }

    public function edit_keywords() {
        $success = Input::get('success');
        /* $keywords = Keywords::all(); */
        $icons = Icons::all();

        $UIkeywords = array();
		
		$UIkeywords['previlege'] = Lang::get('customize.privilege');
		$UIkeywords['dispatcher'] = Lang::get('customize.dispatcher');
        $UIkeywords['keyProvider'] = Lang::get('customize.Provider');
        $UIkeywords['keyUser'] = Lang::get('customize.User');
        $UIkeywords['keyTaxi'] = Lang::get('customize.Taxi');
        $UIkeywords['keyTrip'] = Lang::get('customize.Trip');
        $UIkeywords['keyWalk'] = Lang::get('customize.Walk');
        $UIkeywords['keyRequest'] = Lang::get('customize.Request');
        $UIkeywords['keyDashboard'] = Lang::get('customize.Dashboard');
        $UIkeywords['keyMap_View'] = Lang::get('customize.map_view');
        $UIkeywords['keyReviews'] = Lang::get('customize.Reviews');
        $UIkeywords['keyInformation'] = Lang::get('customize.Information');
        $UIkeywords['keyTypes'] = Lang::get('customize.Types');
        $UIkeywords['keyDocuments'] = Lang::get('customize.Documents');
        $UIkeywords['keyPromo_Codes'] = Lang::get('customize.promo_codes');
        $UIkeywords['keyCustomize'] = Lang::get('customize.Customize');
        $UIkeywords['keyPayment_Details'] = Lang::get('customize.payment_details');
        $UIkeywords['keySettings'] = Lang::get('customize.Settings');
        $UIkeywords['keyAdmin'] = Lang::get('customize.Admin');
        $UIkeywords['keyAdmin_Control'] = Lang::get('customize.admin_control');
        $UIkeywords['keyLog_Out'] = Lang::get('customize.log_out');
        $title = ucwords(trans('language_changer.Customize')); /* 'Customize' */

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }

        return View::make('keywords')
                        ->with('title', $title)
                        ->with('page', 'keywords')
                        /* ->with('keywords', $keywords) */
                        ->with('align_format',$align_format)
                        ->with('icons', $icons)
                        ->with('Uikeywords', $UIkeywords)
                        ->with('success', $success);
    }

    public function save_keywords() {
        $braintree_cse = $stripe_publishable_key = $url = $timezone = $website_title = $s3_bucket = $twillo_account_sid = $twillo_auth_token = $twillo_number = $default_payment = $stripe_secret_key = $braintree_environment = $braintree_merchant_id = $braintree_public_key = $braintree_private_key = $customer_certy_url = $customer_certy_pass = $customer_certy_type = $provider_certy_url = $provider_certy_pass = $provider_certy_type = $gcm_browser_key = $key_provider = $key_user = $key_taxi = $key_trip = $key_currency = $total_trip = $cancelled_trip = $total_payment = $completed_trip = $card_payment = $credit_payment = $key_ref_pre = $android_client_app_url = $android_provider_app_url = $ios_client_app_url = $ios_provider_app_url = NULL;
        $braintree_environment = Config::get('app.braintree_environment');
        $braintree_merchant_id = Config::get('app.braintree_merchant_id');
        $braintree_public_key = Config::get('app.braintree_public_key');
        $braintree_private_key = Config::get('app.braintree_private_key');
        $braintree_cse = Config::get('app.braintree_cse');
        $twillo_account_sid = Config::get('app.twillo_account_sid');
        $twillo_auth_token = Config::get('app.twillo_auth_token');
        $twillo_number = Config::get('app.twillo_number');
        $timezone = Config::get('app.timezone');
        $stripe_publishable_key = Config::get('app.stripe_publishable_key');
        $url = Config::get('app.url');
        $website_title = Config::get('app.website_title');
        $s3_bucket = Config::get('app.s3_bucket');
        $default_payment = Config::get('app.default_payment');
        $stripe_secret_key = Config::get('app.stripe_secret_key');
        $mail_driver = Config::get('mail.driver');
        $email_name = Config::get('mail.from.name');
        $email_address = Config::get('mail.from.address');
        $mandrill_secret = Config::get('services.mandrill.secret');
        $host = Config::get('mail.host');
        /* DEVICE PUSH NOTIFICATION DETAILS */
        $customer_certy_url = Config::get('app.customer_certy_url');
        $customer_certy_pass = Config::get('app.customer_certy_pass');
        $customer_certy_type = Config::get('app.customer_certy_type');
        $provider_certy_url = Config::get('app.provider_certy_url');
        $provider_certy_pass = Config::get('app.provider_certy_pass');
        $provider_certy_type = Config::get('app.provider_certy_type');
        $gcm_browser_key = Config::get('app.gcm_browser_key');
        /* DEVICE PUSH NOTIFICATION DETAILS END */
        $install = array(
            'braintree_environment' => $braintree_environment,
            'braintree_merchant_id' => $braintree_merchant_id,
            'braintree_public_key' => $braintree_public_key,
            'braintree_private_key' => $braintree_private_key,
            'braintree_cse' => $braintree_cse,
            'twillo_account_sid' => $twillo_account_sid,
            'twillo_auth_token' => $twillo_auth_token,
            'twillo_number' => $twillo_number,
            'stripe_publishable_key' => $stripe_publishable_key,
            'stripe_secret_key' => $stripe_secret_key,
            'mail_driver' => $mail_driver,
            'email_address' => $email_address,
            'email_name' => $email_name,
            'mandrill_secret' => $mandrill_secret,
            'default_payment' => $default_payment,
            /* DEVICE PUSH NOTIFICATION DETAILS */
            'customer_certy_url' => $customer_certy_url,
            'customer_certy_pass' => $customer_certy_pass,
            'customer_certy_type' => $customer_certy_type,
            'provider_certy_url' => $provider_certy_url,
            'provider_certy_pass' => $provider_certy_pass,
            'provider_certy_type' => $provider_certy_type,
            'gcm_browser_key' => $gcm_browser_key,
                /* DEVICE PUSH NOTIFICATION DETAILS END */
        );        // Modifying Database Config
        /* $keywords = Keywords::all();
          foreach ($keywords as $keyword) {
          // Log::info('keyword = ' . print_r(Input::get($keyword->id), true));
          if (Input::get($keyword->id) != NULL) {
          // Log::info('keyword = ' . print_r(Input::get($keyword->id), true));
          $temp = Input::get($keyword->id);
          $temp_setting = Keywords::find($keyword->id);
          $temp_setting->keyword = Input::get($keyword->id);
          $temp_setting->save();
          }
          } */

        if (Input::has('key_provider')) {
            $key_provider = trim(Input::get('key_provider'));
            if ($key_provider != "") {
                /* $keyword = Keywords::find(1);
                  $keyword->keyword = Input::get('key_provider');
                  // $keyword->alias = Input::get('key_provider');
                  $keyword->save(); */
            } else {
                $key_provider = null;
            }
        }
        if (Input::has('key_user')) {
            $key_user = trim(Input::get('key_user'));
            if ($key_user != "") {
                /* $keyword = Keywords::find(2);
                  $keyword->keyword = Input::get('key_user');
                  // $keyword->alias = Input::get('key_user');
                  $keyword->save(); */
            } else {
                $key_user = null;
            }
        }
        if (Input::has('key_taxi')) {
            $key_taxi = trim(Input::get('key_taxi'));
            if ($key_taxi != "") {
                /* $keyword = Keywords::find(3);
                  $keyword->keyword = Input::get('key_taxi');
                  // $keyword->alias = Input::get('key_taxi');
                  $keyword->save(); */
            } else {
                $key_taxi = null;
            }
        }
        if (Input::has('key_trip')) {
            $key_trip = trim(Input::get('key_trip'));
            if ($key_trip != "") {
                /* $keyword = Keywords::find(4);
                  $keyword->keyword = Input::get('key_trip');
                  // $keyword->alias = Input::get('key_trip');
                  $keyword->save(); */
            } else {
                $key_trip = null;
            }
        }
        if (Input::has('key_currency')) {
            $key_currency = trim(Input::get('key_currency'));
            if ($key_currency != '$' || $key_currency != "usd" || $key_currency != "USD") {
                $setransfer = Settings::where('key', 'transfer')->first();
                $setransfer->value = 2;
                $setransfer->save();
            }
            if ($key_currency != "") {
                /* $keyword = Keywords::find(5);
                  $keyword->keyword = Input::get('key_currency');
                  // $keyword->alias = Input::get('key_currency');
                  $keyword->save(); */
            } else {
                $key_currency = null;
            }
        }
        if (Input::has('total_trip')) {
            $total_trip = trim(Input::get('total_trip'));
            if ($total_trip != "") {
                /* $keyword = Keywords::find(6);
                  $keyword->alias = Input::get('total_trip');
                  $keyword->save(); */
            } else {
                $total_trip = null;
            }
        }
        if (Input::has('cancelled_trip')) {
            $cancelled_trip = trim(Input::get('cancelled_trip'));
            if ($cancelled_trip != "") {
                /* $keyword = Keywords::find(7);
                  $keyword->alias = Input::get('cancelled_trip');
                  $keyword->save(); */
            } else {
                $cancelled_trip = null;
            }
        }
        if (Input::has('total_payment')) {
            $total_payment = trim(Input::get('total_payment'));
            if ($total_payment != "") {
                /* $keyword = Keywords::find(8);
                  $keyword->alias = Input::get('total_payment');
                  $keyword->save(); */
            } else {
                $total_payment = null;
            }
        }
        if (Input::has('completed_trip')) {
            $completed_trip = trim(Input::get('completed_trip'));
            if ($completed_trip != "") {
                /* $keyword = Keywords::find(9);
                  $keyword->alias = Input::get('completed_trip');
                  $keyword->save(); */
            } else {
                $completed_trip = null;
            }
        }
        if (Input::has('card_payment')) {
            $card_payment = trim(Input::get('card_payment'));
            if ($card_payment != "") {
                /* $keyword = Keywords::find(10);
                  $keyword->alias = Input::get('card_payment');
                  $keyword->save(); */
            } else {
                $card_payment = null;
            }
        }
        if (Input::has('credit_payment')) {
            $credit_payment = trim(Input::get('credit_payment'));
            if ($credit_payment != "") {
                /* $keyword = Keywords::find(11);
                  $keyword->alias = Input::get('credit_payment');
                  $keyword->save(); */
            } else {
                $credit_payment = null;
            }
        }
        if (Input::has('key_ref_pre')) {
            $key_ref_pre = trim(Input::get('key_ref_pre'));
            if ($key_ref_pre != "") {
                /* $keyword = Keywords::find(11);
                  $keyword->alias = Input::get('credit_payment');
                  $keyword->save(); */
            } else {
                $key_ref_pre = null;
            }
        }
        /* $key_provider $key_user $key_taxi $key_trip $key_currency $total_trip $cancelled_trip $total_payment $completed_trip $card_payment $credit_payment */
        $appfile = fopen(app_path() . "/config/app.php", "w") or die("Unable to open file!");
        /* $appfile_config = generate_app_config($braintree_cse, $stripe_publishable_key, $url, $timezone, $website_title, $s3_bucket, $twillo_account_sid, $twillo_auth_token, $twillo_number, $default_payment, $stripe_secret_key, $braintree_environment, $braintree_merchant_id, $braintree_public_key, $braintree_private_key, $customer_certy_url, $customer_certy_pass, $customer_certy_type, $provider_certy_url, $provider_certy_pass, $provider_certy_type, $gcm_browser_key, $key_provider, $key_user, $key_taxi, $key_trip, $key_currency, $total_trip, $cancelled_trip, $total_payment, $completed_trip, $card_payment, $credit_payment, $key_ref_pre); */
        $appfile_config = generate_app_config($braintree_cse, $stripe_publishable_key, $url, $timezone, $website_title, $s3_bucket, $twillo_account_sid, $twillo_auth_token, $twillo_number, $default_payment, $stripe_secret_key, $braintree_environment, $braintree_merchant_id, $braintree_public_key, $braintree_private_key, $customer_certy_url, $customer_certy_pass, $customer_certy_type, $provider_certy_url, $provider_certy_pass, $provider_certy_type, $gcm_browser_key, $key_provider, $key_user, $key_taxi, $key_trip, $key_currency, $total_trip, $cancelled_trip, $total_payment, $completed_trip, $card_payment, $credit_payment, $key_ref_pre, $android_client_app_url, $android_provider_app_url, $ios_client_app_url, $ios_provider_app_url);
        fwrite($appfile, $appfile_config);
        fclose($appfile);

        return Redirect::to('/admin/edit_keywords?success=1');
    }

    public function save_keywords_UI() {
		$dispatcher = trim(Input::get('val_dispatcher'));
		$privilege = trim(Input::get('val_previlege'));
        $dashboard = trim(Input::get('val_dashboard'));
        $map_view = trim(Input::get('val_map_view'));
        $provider = trim(Input::get('val_provider'));
        $user = trim(Input::get('val_user'));
        $taxi = trim(Input::get('val_taxi'));
        $trip = trim(Input::get('val_trip'));
        $walk = trim(Input::get('val_walk'));
        $request = trim(Input::get('val_request'));
        $reviews = trim(Input::get('val_reviews'));
        $information = trim(Input::get('val_information'));
        $types = trim(Input::get('val_types'));
        $documents = trim(Input::get('val_documents'));
        $promo_codes = trim(Input::get('val_promo_codes'));
        $customize = trim(Input::get('val_customize'));
        $payment_details = trim(Input::get('val_payment_details'));
        $settings = trim(Input::get('val_settings'));
        $val_admin = trim(Input::get('val_admin'));
        $admin_control = trim(Input::get('val_admin_control'));
        $log_out = trim(Input::get('val_log_out'));
		
		 if ($privilege == null || $privilege == "") {
            $privilege = Lang::get('customize.privilege');
        } else {
            $privilege = $privilege;
        }
		
		 if ($dispatcher == null || $dispatcher == "") {
            $dispatcher = Lang::get('customize.dispatcher');
        } else {
            $dispatcher = $dispatcher;
        }

        if ($dashboard == null || $dashboard == "") {
            $dashboard = Lang::get('customize.Dashboard');
        } else {
            $dashboard = $dashboard;
        }
        if ($map_view == null || $map_view == "") {
            $map_view = Lang::get('customize.map_view');
        } else {
            $map_view = $map_view;
        }
        if ($provider == null || $provider == "") {
            $provider = Lang::get('customize.Provider');
        } else {
            $provider = $provider;
        }
        if ($user == null || $user == "") {
            $user = Lang::get('customize.User');
        } else {
            $user = $user;
        }
        if ($taxi == null || $taxi == "") {
            $taxi = Lang::get('customize.Taxi');
        } else {
            $taxi = $taxi;
        }
        if ($trip == null || $trip == "") {
            $trip = Lang::get('customize.Trip');
        } else {
            $trip = $trip;
        }
        if ($walk == null || $walk == "") {
            $walk = Lang::get('customize.Walk');
        } else {
            $walk = $walk;
        }
        if ($request == null || $request == "") {
            $request = Lang::get('customize.Request');
        } else {
            $request = $request;
        }
        if ($reviews == null || $reviews == "") {
            $reviews = Lang::get('customize.Reviews');
        } else {
            $reviews = $reviews;
        }
        if ($information == null || $information == "") {
            $information = Lang::get('customize.Information');
        } else {
            $information = $information;
        }
        if ($types == null || $types == "") {
            $types = Lang::get('customize.Types');
        } else {
            $types = $types;
        }
        if ($documents == null || $documents == "") {
            $documents = Lang::get('customize.Documents');
        } else {
            $documents = $documents;
        }
        if ($promo_codes == null || $promo_codes == "") {
            $promo_codes = Lang::get('customize.promo_codes');
        } else {
            $promo_codes = $promo_codes;
        }
        if ($customize == null || $customize == "") {
            $customize = Lang::get('customize.Customize');
        } else {
            $customize = $customize;
        }
        if ($payment_details == null || $payment_details == "") {
            $payment_details = Lang::get('customize.payment_details');
        } else {
            $payment_details = $payment_details;
        }
        if ($settings == null || $settings == "") {
            $settings = Lang::get('customize.Settings');
        } else {
            $settings = $settings;
        }
        if ($val_admin == null || $val_admin == "") {
            $val_admin = Lang::get('customize.Admin');
        } else {
            $val_admin = $val_admin;
        }
        if ($admin_control == null || $admin_control == "") {
            $admin_control = Lang::get('customize.admin_control');
        } else {
            $admin_control = $admin_control;
        }
        if ($log_out == null || $log_out == "") {
            $log_out = Lang::get('customize.log_out');
        } else {
            $log_out = $log_out;
        }
        $appfile = fopen(app_path() . "/lang/en/customize.php", "w") or die("Unable to open file!");
        $appfile_config = generate_custome_key($dashboard, $map_view, $provider, $user, $taxi, $trip, $walk, $request, $reviews, $information, $types, $documents, $promo_codes, $customize, $payment_details, $settings, $val_admin, $admin_control, $log_out, $privilege, $dispatcher);
        fwrite($appfile, $appfile_config);
        fclose($appfile);

        return Redirect::to('/admin/edit_keywords?success=1');
    }

    public function adminCurrency() {
        $currency_selected = $_POST['currency_selected'];
        /* $keycurrency = Keywords::find(5);
          $original_selection = $keycurrency->keyword; */
        $original_selection = Config::get('app.generic_keywords.Currency');
        if ($original_selection == '$') {
            $original_selection = "USD";
        }
        if ($currency_selected == '$') {
            $currency_selected = "USD";
        }
        if ($currency_selected == $original_selection) {
            // same currency
            $data['success'] = false;
            $data['error_message'] = 'Same Currency.';
        } else {
            $httpAdapter = new \Ivory\HttpAdapter\FileGetContentsHttpAdapter();
            // Create the Yahoo Finance provider
            $yahooProvider = new \Swap\Provider\YahooFinanceProvider($httpAdapter);
            // Create Swap with the provider
            $swap = new \Swap\Swap($yahooProvider);
            $rate = $swap->quote($original_selection . "/" . $currency_selected);
            $rate = json_decode($rate, true);
            $data['success'] = true;
            $data['rate'] = $rate;
        }
        return $data;
    }

    public function save_settings() {

        //Multiple vehicle types selection and store Records in db
        $multipleVechicleTypes = Input::get('multi_select_vechicle_types');
        if(!empty($multipleVechicleTypes)) {
            ProviderType::where('admin_select_vechicle_type', 1)->update(array('admin_select_vechicle_type' => 0));
            foreach ($multipleVechicleTypes as $multipleVechicleType) {
                $providerTypes = ProviderType::find($multipleVechicleType);
                $providerTypes->admin_select_vechicle_type = 1;
                $providerTypes->save();
            }
        }


        
        $settings = Settings::all();
        foreach ($settings as $setting) {
            if (Input::get($setting->id) != NULL) {
                if($setting->id == 62)
                {
                   // Config::set('app.locale', 'en');
                    $data = file(app_path('config/app.php'));

                    $lan=Input::get($setting->id)==1?"arb":"en";

                   foreach ($data as $key=>$line)
                   {
                       if(stristr($line,"'locale' => 'arb',") || stristr($line,"'locale' => 'en',"))
                       {
                           $data[$key]="        'locale' => '".$lan."',
                           
                           ";
                       }
                   }



                    file_put_contents(app_path('config/app.php'), implode('', $data));
                }
                $temp_setting = Settings::find($setting->id);
                $temp_setting->value = Input::get($setting->id);
                $temp_setting->save();
            }
        }
        return Redirect::to('/admin/settings?success=1');
    }

//Installation Settings
    public function installation_settings() {
        $braintree_environment = Config::get('app.braintree_environment');
        $braintree_merchant_id = Config::get('app.braintree_merchant_id');
        $braintree_public_key = Config::get('app.braintree_public_key');
        $braintree_private_key = Config::get('app.braintree_private_key');
        $braintree_cse = Config::get('app.braintree_cse');
        $twillo_account_sid = Config::get('app.twillo_account_sid');
        $twillo_auth_token = Config::get('app.twillo_auth_token');
        $twillo_number = Config::get('app.twillo_number');
        $timezone = Config::get('app.timezone');
        $stripe_publishable_key = Config::get('app.stripe_publishable_key');
        $url = Config::get('app.url');
        $website_title = Config::get('app.website_title');
        $s3_bucket = Config::get('app.s3_bucket');
        $default_payment = Config::get('app.default_payment');
        $stripe_secret_key = Config::get('app.stripe_secret_key');
        $mail_driver = Config::get('mail.driver');
        $email_name = Config::get('mail.from.name');
        $email_address = Config::get('mail.from.address');
        $mandrill_secret = Config::get('services.mandrill.secret');
        $mandrill_username = Config::get('services.mandrill.username');
        $host = Config::get('mail.host');
        /* DEVICE PUSH NOTIFICATION DETAILS */
        $customer_certy_url = Config::get('app.customer_certy_url');
        $customer_certy_pass = Config::get('app.customer_certy_pass');
        $customer_certy_type = Config::get('app.customer_certy_type');
        $provider_certy_url = Config::get('app.provider_certy_url');
        $provider_certy_pass = Config::get('app.provider_certy_pass');
        $provider_certy_type = Config::get('app.provider_certy_type');
        $gcm_browser_key = Config::get('app.gcm_browser_key');
        /* DEVICE PUSH NOTIFICATION DETAILS END */
        $install = array(
            'braintree_environment' => $braintree_environment,
            'braintree_merchant_id' => $braintree_merchant_id,
            'braintree_public_key' => $braintree_public_key,
            'braintree_private_key' => $braintree_private_key,
            'braintree_cse' => $braintree_cse,
            'twillo_account_sid' => $twillo_account_sid,
            'twillo_auth_token' => $twillo_auth_token,
            'twillo_number' => $twillo_number,
            'stripe_publishable_key' => $stripe_publishable_key,
            'stripe_secret_key' => $stripe_secret_key,
            'mail_driver' => $mail_driver,
            'email_address' => $email_address,
            'mandrill_username' => $mandrill_username,
            'email_name' => $email_name,
            'host' => $host,
            'mandrill_secret' => $mandrill_secret,
            'default_payment' => $default_payment,
            /* DEVICE PUSH NOTIFICATION DETAILS */
            'customer_certy_url' => $customer_certy_url,
            'customer_certy_pass' => $customer_certy_pass,
            'customer_certy_type' => $customer_certy_type,
            'provider_certy_url' => $provider_certy_url,
            'provider_certy_pass' => $provider_certy_pass,
            'provider_certy_type' => $provider_certy_type,
            'gcm_browser_key' => $gcm_browser_key,
                /* DEVICE PUSH NOTIFICATION DETAILS END */                );
        $success = Input::get('success');
        $cert_def = 0;
        $cer = Certificates::where('file_type', 'certificate')->where('client', 'apple')->get();
        foreach ($cer as $key) {
            if ($key->default == 1) {
                $cert_def = $key->type;
            }
        }
        $title = ucwords(trans('language_changer.installation')." " . trans('language_changer.Settings')); /* 'Installation Settings' */

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }




        return View::make('install_settings')
                        ->with('title', $title)
                        ->with('success', $success)
                        ->with('page', 'settings')
                        ->with('cert_def', $cert_def)
            ->with('align_format',$align_format)
                        ->with('install', $install);
    }

    public function finish_install() {
        $braintree_cse = $stripe_publishable_key = $url = $timezone = $website_title = $s3_bucket = $twillo_account_sid = $twillo_auth_token = $twillo_number = $default_payment = $stripe_secret_key = $braintree_environment = $braintree_merchant_id = $braintree_public_key = $braintree_private_key = $customer_certy_url = $customer_certy_pass = $customer_certy_type = $provider_certy_url = $provider_certy_pass = $provider_certy_type = $gcm_browser_key = $key_provider = $key_user = $key_taxi = $key_trip = $key_currency = $total_trip = $cancelled_trip = $total_payment = $completed_trip = $card_payment = $credit_payment = $key_ref_pre = $android_client_app_url = $android_provider_app_url = $ios_client_app_url = $ios_provider_app_url = NULL;
        $braintree_environment = Config::get('app.braintree_environment');
        $braintree_merchant_id = Config::get('app.braintree_merchant_id');
        $braintree_public_key = Config::get('app.braintree_public_key');
        $braintree_private_key = Config::get('app.braintree_private_key');
        $braintree_cse = Config::get('app.braintree_cse');
        $twillo_account_sid = Config::get('app.twillo_account_sid');
        $twillo_auth_token = Config::get('app.twillo_auth_token');
        $twillo_number = Config::get('app.twillo_number');
        $timezone = Config::get('app.timezone');
        $stripe_publishable_key = Config::get('app.stripe_publishable_key');
        $url = Config::get('app.url');
        $website_title = Config::get('app.website_title');
        $s3_bucket = Config::get('app.s3_bucket');
        $default_payment = Config::get('app.default_payment');
        $stripe_secret_key = Config::get('app.stripe_secret_key');
        $mail_driver = Config::get('mail.driver');
        $email_name = Config::get('mail.from.name');
        $email_address = Config::get('mail.from.address');
        $mandrill_secret = Config::get('services.mandrill.secret');
        $host = Config::get('mail.host');
        /* DEVICE PUSH NOTIFICATION DETAILS */
        $customer_certy_url = Config::get('app.customer_certy_url');
        $customer_certy_pass = Config::get('app.customer_certy_pass');
        $customer_certy_type = Config::get('app.customer_certy_type');
        $provider_certy_url = Config::get('app.provider_certy_url');
        $provider_certy_pass = Config::get('app.provider_certy_pass');
        $provider_certy_type = Config::get('app.provider_certy_type');
        $gcm_browser_key = Config::get('app.gcm_browser_key');
        /* DEVICE PUSH NOTIFICATION DETAILS END */
        $install = array(
            'braintree_environment' => $braintree_environment,
            'braintree_merchant_id' => $braintree_merchant_id,
            'braintree_public_key' => $braintree_public_key,
            'braintree_private_key' => $braintree_private_key,
            'braintree_cse' => $braintree_cse,
            'twillo_account_sid' => $twillo_account_sid,
            'twillo_auth_token' => $twillo_auth_token,
            'twillo_number' => $twillo_number,
            'stripe_publishable_key' => $stripe_publishable_key,
            'stripe_secret_key' => $stripe_secret_key,
            'mail_driver' => $mail_driver,
            'email_address' => $email_address,
            'email_name' => $email_name,
            'mandrill_secret' => $mandrill_secret,
            'default_payment' => $default_payment,
            /* DEVICE PUSH NOTIFICATION DETAILS */
            'customer_certy_url' => $customer_certy_url,
            'customer_certy_pass' => $customer_certy_pass,
            'customer_certy_type' => $customer_certy_type,
            'provider_certy_url' => $provider_certy_url,
            'provider_certy_pass' => $provider_certy_pass,
            'provider_certy_type' => $provider_certy_type,
            'gcm_browser_key' => $gcm_browser_key,
                /* DEVICE PUSH NOTIFICATION DETAILS END */
        );        // Modifying Database Config
        if (isset($_POST['sms'])) {
            $twillo_account_sid = Input::get('twillo_account_sid');
            $twillo_auth_token = Input::get('twillo_auth_token');
            $twillo_number = Input::get('twillo_number');
if($twillo_account_sid=='' || $twillo_auth_token=='' || $twillo_number=='')
{
	 $temp_setting = Settings::find(55);
                $temp_setting->value = 0;
                $temp_setting->save();
}
            $appfile = fopen(app_path() . "/config/app.php", "w") or die("Unable to open file!");
            /* $appfile_config = generate_app_config($braintree_cse, $stripe_publishable_key, $url, $timezone, $website_title, $s3_bucket, $twillo_account_sid, $twillo_auth_token, $twillo_number, $default_payment, $stripe_secret_key, $braintree_environment, $braintree_merchant_id, $braintree_public_key, $braintree_private_key); */
            $appfile_config = generate_app_config($braintree_cse, $stripe_publishable_key, $url, $timezone, $website_title, $s3_bucket, $twillo_account_sid, $twillo_auth_token, $twillo_number, $default_payment, $stripe_secret_key, $braintree_environment, $braintree_merchant_id, $braintree_public_key, $braintree_private_key, $customer_certy_url, $customer_certy_pass, $customer_certy_type, $provider_certy_url, $provider_certy_pass, $provider_certy_type, $gcm_browser_key, $key_provider, $key_user, $key_taxi, $key_trip, $key_currency, $total_trip, $cancelled_trip, $total_payment, $completed_trip, $card_payment, $credit_payment, $key_ref_pre, $android_client_app_url, $android_provider_app_url, $ios_client_app_url, $ios_provider_app_url);
            fwrite($appfile, $appfile_config);
            fclose($appfile);
        }

        if (isset($_POST['payment'])) {
            $default_payment = Input::get('default_payment');

            if ($default_payment == 'stripe') {
                if ($stripe_secret_key != trim(Input::get('stripe_secret_key')) || $stripe_publishable_key != trim(Input::get('stripe_publishable_key'))) {
                    /* DELETE CUSTOMER CARDS FROM DATABASE */
                    $delete_un_rq = DB::delete("DELETE FROM payment WHERE 1;");
                    /* DELETE CUSTOMER CARDS FROM DATABASE END */
                    $stripe_secret_key = Input::get('stripe_secret_key');
                    $stripe_publishable_key = Input::get('stripe_publishable_key');
                    $braintree_environment = '';
                    $braintree_merchant_id = '';
                    $braintree_public_key = '';
                    $braintree_private_key = '';
                    $braintree_cse = '';
                    $appfile = fopen(app_path() . "/config/app.php", "w") or die("Unable to open file!");
                    /* $appfile_config = generate_app_config($braintree_cse, $stripe_publishable_key, $url, $timezone, $website_title, $s3_bucket, $twillo_account_sid, $twillo_auth_token, $twillo_number, $default_payment, $stripe_secret_key, $braintree_environment, $braintree_merchant_id, $braintree_public_key, $braintree_private_key); */
                    $appfile_config = generate_app_config($braintree_cse, $stripe_publishable_key, $url, $timezone, $website_title, $s3_bucket, $twillo_account_sid, $twillo_auth_token, $twillo_number, $default_payment, $stripe_secret_key, $braintree_environment, $braintree_merchant_id, $braintree_public_key, $braintree_private_key, $customer_certy_url, $customer_certy_pass, $customer_certy_type, $provider_certy_url, $provider_certy_pass, $provider_certy_type, $gcm_browser_key, $key_provider, $key_user, $key_taxi, $key_trip, $key_currency, $total_trip, $cancelled_trip, $total_payment, $completed_trip, $card_payment, $credit_payment, $key_ref_pre, $android_client_app_url, $android_provider_app_url, $ios_client_app_url, $ios_provider_app_url);
                    fwrite($appfile, $appfile_config);
                    fclose($appfile);
                }
            } else {
                if ($braintree_environment != trim(Input::get('braintree_environment')) || $braintree_merchant_id != trim(Input::get('braintree_merchant_id')) || $braintree_public_key != trim(Input::get('braintree_public_key')) || $braintree_private_key != trim(Input::get('braintree_private_key')) || $braintree_cse != trim(Input::get('braintree_cse'))) {
                    /* DELETE CUSTOMER CARDS FROM DATABASE */
                    $delete_un_rq = DB::delete("DELETE FROM payment WHERE 1;");
                    /* DELETE CUSTOMER CARDS FROM DATABASE END */
                    $stripe_secret_key = '';
                    $stripe_publishable_key = '';
                    $braintree_environment = Input::get('braintree_environment');
                    $braintree_merchant_id = Input::get('braintree_merchant_id');
                    $braintree_public_key = Input::get('braintree_public_key');
                    $braintree_private_key = Input::get('braintree_private_key');
                    $braintree_cse = Input::get('braintree_cse');
                    $appfile = fopen(app_path() . "/config/app.php", "w") or die("Unable to open file!");
                    /* $appfile_config = generate_app_config($braintree_cse, $stripe_publishable_key, $url, $timezone, $website_title, $s3_bucket, $twillo_account_sid, $twillo_auth_token, $twillo_number, $default_payment, $stripe_secret_key, $braintree_environment, $braintree_merchant_id, $braintree_public_key, $braintree_private_key); */
                    $appfile_config = generate_app_config($braintree_cse, $stripe_publishable_key, $url, $timezone, $website_title, $s3_bucket, $twillo_account_sid, $twillo_auth_token, $twillo_number, $default_payment, $stripe_secret_key, $braintree_environment, $braintree_merchant_id, $braintree_public_key, $braintree_private_key, $customer_certy_url, $customer_certy_pass, $customer_certy_type, $provider_certy_url, $provider_certy_pass, $provider_certy_type, $gcm_browser_key, $key_provider, $key_user, $key_taxi, $key_trip, $key_currency, $total_trip, $cancelled_trip, $total_payment, $completed_trip, $card_payment, $credit_payment, $key_ref_pre, $android_client_app_url, $android_provider_app_url, $ios_client_app_url, $ios_provider_app_url);
                    fwrite($appfile, $appfile_config);
                    fclose($appfile);
                }
            }
        }

        // Modifying Mail Config File

        if (isset($_POST['mail'])) {
            $mail_driver = Input::get('mail_driver');
            $email_name = Input::get('email_name');
            $email_address = Input::get('email_address');
            $mandrill_secret = Input::get('mandrill_secret');
            $mandrill_hostname = "";
            if ($mail_driver == 'mail') {
                $mandrill_hostname = "localhost";
            } elseif ($mail_driver == 'mandrill') {
                $mandrill_hostname = Input::get('host_name');
            }
            $mailfile = fopen(app_path() . "/config/mail.php", "w") or die("Unable to open file!");
            $mailfile_config = generate_mail_config($mandrill_hostname, $mail_driver, $email_name, $email_address);
            fwrite($mailfile, $mailfile_config);
            fclose($mailfile);

            if ($mail_driver == 'mandrill') {
                $mandrill_username = Input::get('user_name');
                $servicesfile = fopen(app_path() . "/config/services.php", "w") or die("Unable to open file!");
                $servicesfile_config = generate_services_config($mandrill_secret, $mandrill_username);
                fwrite($servicesfile, $servicesfile_config);
                fclose($servicesfile);
            }
        }
        $install = array(
            'braintree_environment' => $braintree_environment,
            'braintree_merchant_id' => $braintree_merchant_id,
            'braintree_public_key' => $braintree_public_key,
            'braintree_private_key' => $braintree_private_key,
            'braintree_cse' => $braintree_cse,
            'twillo_account_sid' => $twillo_account_sid,
            'twillo_auth_token' => $twillo_auth_token,
            'twillo_number' => $twillo_number,
            'stripe_publishable_key' => $stripe_publishable_key,
            'stripe_secret_key' => $stripe_secret_key,
            'mail_driver' => $mail_driver,
            'email_address' => $email_address,
            'email_name' => $email_name,
            'mandrill_secret' => $mandrill_secret,
            'default_payment' => $default_payment,
            /* DEVICE PUSH NOTIFICATION DETAILS */
            'customer_certy_url' => $customer_certy_url,
            'customer_certy_pass' => $customer_certy_pass,
            'customer_certy_type' => $customer_certy_type,
            'provider_certy_url' => $provider_certy_url,
            'provider_certy_pass' => $provider_certy_pass,
            'provider_certy_type' => $provider_certy_type,
            'gcm_browser_key' => $gcm_browser_key,
                /* DEVICE PUSH NOTIFICATION DETAILS END */
        );
        return Redirect::to('/admin/settings?success=1')
                        ->with('install', $install);
    }

    public function addcerti() {
        $braintree_cse = $stripe_publishable_key = $url = $timezone = $website_title = $s3_bucket = $twillo_account_sid = $twillo_auth_token = $twillo_number = $default_payment = $stripe_secret_key = $braintree_environment = $braintree_merchant_id = $braintree_public_key = $braintree_private_key = $customer_certy_url = $customer_certy_pass = $customer_certy_type = $provider_certy_url = $provider_certy_pass = $provider_certy_type = $gcm_browser_key = $key_provider = $key_user = $key_taxi = $key_trip = $key_currency = $total_trip = $cancelled_trip = $total_payment = $completed_trip = $card_payment = $credit_payment = $key_ref_pre = $android_client_app_url = $android_provider_app_url = $ios_client_app_url = $ios_provider_app_url = NULL;
        $is_certy_change = 0;
        $braintree_environment = Config::get('app.braintree_environment');
        $braintree_merchant_id = Config::get('app.braintree_merchant_id');
        $braintree_public_key = Config::get('app.braintree_public_key');
        $braintree_private_key = Config::get('app.braintree_private_key');
        $braintree_cse = Config::get('app.braintree_cse');
        $twillo_account_sid = Config::get('app.twillo_account_sid');
        $twillo_auth_token = Config::get('app.twillo_auth_token');
        $twillo_number = Config::get('app.twillo_number');
        $timezone = Config::get('app.timezone');
        $stripe_publishable_key = Config::get('app.stripe_publishable_key');
        $url = Config::get('app.url');
        $website_title = Config::get('app.website_title');
        $s3_bucket = Config::get('app.s3_bucket');
        $default_payment = Config::get('app.default_payment');
        $stripe_secret_key = Config::get('app.stripe_secret_key');
        $mail_driver = Config::get('mail.driver');
        $email_name = Config::get('mail.from.name');
        $email_address = Config::get('mail.from.address');
        $mandrill_secret = Config::get('services.mandrill.secret');
        $host = Config::get('mail.host');
        /* DEVICE PUSH NOTIFICATION DETAILS */
        $customer_certy_url = Config::get('app.customer_certy_url');
        $customer_certy_pass = Config::get('app.customer_certy_pass');
        $customer_certy_type = Config::get('app.customer_certy_type');
        $provider_certy_url = Config::get('app.provider_certy_url');
        $provider_certy_pass = Config::get('app.provider_certy_pass');
        $provider_certy_type = Config::get('app.provider_certy_type');
        $gcm_browser_key = Config::get('app.gcm_browser_key');
        /* DEVICE PUSH NOTIFICATION DETAILS END */
        $install = array(
            'braintree_environment' => $braintree_environment,
            'braintree_merchant_id' => $braintree_merchant_id,
            'braintree_public_key' => $braintree_public_key,
            'braintree_private_key' => $braintree_private_key,
            'braintree_cse' => $braintree_cse,
            'twillo_account_sid' => $twillo_account_sid,
            'twillo_auth_token' => $twillo_auth_token,
            'twillo_number' => $twillo_number,
            'stripe_publishable_key' => $stripe_publishable_key,
            'stripe_secret_key' => $stripe_secret_key,
            'mail_driver' => $mail_driver,
            'email_address' => $email_address,
            'email_name' => $email_name,
            'mandrill_secret' => $mandrill_secret,
            'default_payment' => $default_payment,
            /* DEVICE PUSH NOTIFICATION DETAILS */
            'customer_certy_url' => $customer_certy_url,
            'customer_certy_pass' => $customer_certy_pass,
            'customer_certy_type' => $customer_certy_type,
            'provider_certy_url' => $provider_certy_url,
            'provider_certy_pass' => $provider_certy_pass,
            'provider_certy_type' => $provider_certy_type,
            'gcm_browser_key' => $gcm_browser_key,
                /* DEVICE PUSH NOTIFICATION DETAILS END */
        );
        $count = 0;

        // apple user
        if (Input::hasFile('user_certi_a') && Input::has('user_pass_a') && Input::has('cert_type_a')) {
            // Upload File
            $certy_password_client = $customer_certy_pass = trim(Input::get('user_pass_a'));
            $customer_certy_type = Input::get('cert_type_a');
            if ($customer_certy_type) {
                $client_certy_type = "ssl";
            } else {
                $client_certy_type = "sandboxSsl";
            }
            $file_name = "Client_certy";
            $ext = Input::file('user_certi_a')->getClientOriginalExtension();
            if ($ext == "PEM" || $ext == "pem") {
                /* Input::file('user_certi_a')->move(app_path() . "/ios_push/iph_cert/", $file_name . "." . $ext); */
                Input::file('user_certi_a')->move(public_path() . "/apps/ios_push/iph_cert", $file_name . "." . $ext);

                /* chmod(app_path() . "/ios_push/iph_cert/" . $file_name . "." . $ext, 0777); */

                $local_url = $file_name . "." . $ext;

                // Upload to S3
                if (Config::get('app.s3_bucket') != "") {
                    $s3 = App::make('aws')->get('s3');
                    $pic = $s3->putObject(array(
                        'Bucket' => Config::get('app.s3_bucket'),
                        'Key' => $file_name,
                        'SourceFile' => app_path() . "/ios_push/iph_cert/" . $local_url,
                    ));

                    $s3->putObjectAcl(array(
                        'Bucket' => Config::get('app.s3_bucket'),
                        'Key' => $file_name,
                        'ACL' => 'public-read'
                    ));

                    $customer_certy_url = $s3->getObjectUrl(Config::get('app.s3_bucket'), $file_name);
                } else {
                    /* $customer_certy_url = app_path() . '/ios_push/iph_cert/' . $local_url; */
                    /* $customer_certy_url = rtrim(asset_url(), 'public') . 'app/ios_push/iph_cert/' . $local_url; */
                }
                $customer_certy_url = asset_url() . '/apps/ios_push/iph_cert/' . $local_url;
                /* if (isset($theme->logo)) {
                  $icon = asset_url() . '/uploads/' . $theme->logo;
                  unlink_image($icon);
                  }
                  $theme->logo = $local_url; */
                $update_client_certy = "<?php

//session_start();

//require_once  'database.php';
//error_reporting(false);

class Apns {

    public \$ctx;
    public \$fp;
    private \$ssl = 'ssl://gateway.push.apple.com:2195';
    private \$passphrase = '" . $certy_password_client . "';
    private \$sandboxCertificate = 'iph_cert/Client_certy.pem';
    private \$sandboxSsl = 'ssl://gateway.sandbox.push.apple.com:2195';
    private \$sandboxFeedback = 'ssl://feedback.sandbox.push.apple.com:2196';
    private \$message = 'ManagerMaster';

    private function getCertificatePath() {
        return asset_url() . '/apps/ios_push/' . \$this->sandboxCertificate;
    }

    public function __construct() {
        \$this->initialize_apns();
    }

    public function initialize_apns() {
        try {
            \$this->ctx = stream_context_create();

            //stream_context_set_option(\$ctx, 'ssl', 'cafile', 'entrust_2048_ca.cer');
            stream_context_set_option(\$this->ctx, 'ssl', 'local_cert', \$this->getCertificatePath());
            stream_context_set_option(\$this->ctx, 'ssl', 'passphrase', \$this->passphrase); // use this if you are using a passphrase
            // Open a connection to the APNS servers
            \$this->fp = stream_socket_client(\$this->" . $client_certy_type . ", \$err, \$errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, \$this->ctx);

            if (\$this->fp) {
                Log::info('Successfully connected to server of APNS');
                //echo 'Successfully connected to server of APNS ckUberForXOwner.pem';
            } else {
                Log::error('Error in connection while trying to connect to APNS');
                //echo 'Error in connection while trying to connect to APNS ckUberForXOwner.pem';
            }
        } catch (Exception \$e) {
            Log::error(\$e);
        }
    }

    public function send_notification(\$devices, \$message) {
        try {
            \$errCounter = 0;
            \$payload = json_encode(array('aps' => \$message));
            \$result = 0;
            \$bodyError = '';
            foreach (\$devices as \$key => \$value) {
                \$msg = chr(0) . pack('n', 32) . pack('H*', str_replace(' ', '', \$value)) . pack('n', (strlen(\$payload))) . \$payload;
                \$result = fwrite(\$this->fp, \$msg);
                \$bodyError .= 'result: ' . \$result . ', devicetoken: ' . \$value;
                if (!\$result) {
                    \$errCounter = \$errCounter + 1;
                }
            }
			//echo 'Result :- '.\$result;
            if (\$result) {
                Log::info('Delivered Message to APNS' . PHP_EOL);
                //echo 'Delivered Message to APNS' . PHP_EOL;
                \$bool_result = true;
            } else {
                Log::info('Could not Deliver Message to APNS' . PHP_EOL);
                //echo 'Could not Deliver Message to APNS' . PHP_EOL;
                \$bool_result = false;
            }

            fclose(\$this->fp);
            return \$bool_result;
        } catch (Exception \$e) {
            Log::error(\$e);
        }
    }

}
";
                $t = file_put_contents(app_path() . '/ios_push/apns.php', $update_client_certy);
                /* chmod(app_path() . '/ios_push/apns.php', 0777); */
                $is_certy_change ++;
            } else {
                return Redirect::to('/admin/settings/installation?success=3')
                                ->with('install', $install);
            }
        }
        if (Input::hasFile('prov_certi_a') && Input::has('prov_pass_a') && Input::has('cert_type_a')) {
            $certy_password_driver = $provider_certy_pass = trim(Input::get('prov_pass_a'));

            $provider_certy_type = Input::get('cert_type_a');
            if ($provider_certy_type) {
                $driver_certy_type = "ssl";
            } else {
                $driver_certy_type = "sandboxSsl";
            }
            // Upload File
            $file_name = "Walker_certy";
            $ext = Input::file('prov_certi_a')->getClientOriginalExtension();
            if ($ext == "PEM" || $ext == "pem") {
                /* Input::file('prov_certi_a')->move(app_path() . "/ios_push/walker/iph_cert/", $file_name . "." . $ext); */
                Input::file('prov_certi_a')->move(public_path() . "/apps/ios_push/walker/iph_cert", $file_name . "." . $ext);

                $local_url = $file_name . "." . $ext;

                /* chmod(app_path() . "/ios_push/walker/iph_cert/" . $file_name . "." . $ext, 0777); */

                // Upload to S3
                if (Config::get('app.s3_bucket') != "") {
                    $s3 = App::make('aws')->get('s3');
                    $pic = $s3->putObject(array(
                        'Bucket' => Config::get('app.s3_bucket'),
                        'Key' => $file_name,
                        'SourceFile' => app_path() . "/ios_push/walker/iph_cert/" . $local_url,
                    ));

                    $s3->putObjectAcl(array(
                        'Bucket' => Config::get('app.s3_bucket'),
                        'Key' => $file_name,
                        'ACL' => 'public-read'
                    ));

                    $provider_certy_url = $s3->getObjectUrl(Config::get('app.s3_bucket'), $file_name);
                } else {
                    /* $provider_certy_url = app_path() . '/ios_push/walker/iph_cert/' . $local_url; */
                    /* $provider_certy_url = rtrim(asset_url(), 'public') . 'app/ios_push/walker/iph_cert/' . $local_url; */
                }
                $provider_certy_url = asset_url() . '/apps/ios_push/walker/iph_cert/' . $local_url;
                /* if (isset($theme->logo)) {
                  $icon = asset_url() . '/uploads/' . $theme->logo;
                  unlink_image($icon);
                  }
                  $theme->logo = $local_url; */
                $update_client_certy = "<?php

//session_start();

//require_once  'database.php';
//error_reporting(false);

class Apns {

    public \$ctx;
    public \$fp;
    private \$ssl = 'ssl://gateway.push.apple.com:2195';
    private \$passphrase = '" . $certy_password_driver . "';
    private \$sandboxCertificate = 'walker/iph_cert/Walker_certy.pem';
    private \$sandboxSsl = 'ssl://gateway.sandbox.push.apple.com:2195';
    private \$sandboxFeedback = 'ssl://feedback.sandbox.push.apple.com:2196';
    private \$message = 'ManagerMaster';

    private function getCertificatePath() {
        return asset_url() . '/apps/ios_push/' . \$this->sandboxCertificate;
    }

    public function __construct() {
        \$this->initialize_apns();
    }

    public function initialize_apns() {
        try {
            \$this->ctx = stream_context_create();

            //stream_context_set_option(\$ctx, 'ssl', 'cafile', 'entrust_2048_ca.cer');
            stream_context_set_option(\$this->ctx, 'ssl', 'local_cert', \$this->getCertificatePath());
            stream_context_set_option(\$this->ctx, 'ssl', 'passphrase', \$this->passphrase); // use this if you are using a passphrase
            // Open a connection to the APNS servers
            \$this->fp = stream_socket_client(\$this->" . $driver_certy_type . ", \$err, \$errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, \$this->ctx);

            if (\$this->fp) {
                Log::info('Successfully connected to server of APNS');
                /*echo 'Successfully connected to server of APNS ckUberForXProvider.pem';*/
            } else {
                Log::error('Error in connection while trying to connect to APNS');
                /*echo 'Error in connection while trying to connect to APNS ckUberForXProvider.pem';*/
            }
        } catch (Exception \$e) {
            Log::error(\$e);
        }
    }

    public function send_notification(\$devices, \$message) {
        try {
            \$errCounter = 0;
            \$payload = json_encode(array('aps' => \$message));
            \$result = 0;
            \$bodyError = '';
			/*print_r(\$devices);*/
            foreach (\$devices as \$key => \$value) {
				/*echo \$value;*/
                \$msg = chr(0) . pack('n', 32) . pack('H*', str_replace(' ', '', \$value)) . pack('n', (strlen(\$payload))) . \$payload;
                \$result = fwrite(\$this->fp, \$msg);
                \$bodyError .= 'result: ' . \$result . ', devicetoken: ' . \$value;
                if (!\$result) {
                    \$errCounter = \$errCounter + 1;
                }
            }
			/*echo 'Result :- '.\$result;*/
            if (\$result) {
                Log::info('Delivered Message to APNS' . PHP_EOL);
                /*echo 'Delivered Message to APNS' . PHP_EOL;*/
                \$bool_result = true;
            } else {
                Log::info('Could not Deliver Message to APNS' . PHP_EOL);
                /*echo 'Could not Deliver Message to APNS' . PHP_EOL;*/
                \$bool_result = false;
            }

            fclose(\$this->fp);
            return \$bool_result;
        } catch (Exception \$e) {
            Log::error(\$e);
        }
    }

}
";
                $t = file_put_contents(app_path() . '/ios_push/walker/apns.php', $update_client_certy);
                /* chmod(app_path() . '/ios_push/walker/apns.php', 0777); */
                $is_certy_change ++;
            } else {
                return Redirect::to('/admin/settings/installation?success=3')
                                ->with('install', $install);
            }
        }
        if (Input::has('gcm_key')) {
            /* "AIzaSyAKe3XmUV93WvHJvII4Qzpf0R052mxb0KI" */
            $app_gcm_key = $gcm_browser_key = trim(Input::get('gcm_key'));
            if ($app_gcm_key != "") {
                $update_client_certy = "<?php

/*array(
    'GOOGLE_API_KEY' => '" . $app_gcm_key . "',
);*/
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GCM
 *
 * @author Ravi Tamada
 */
define('GOOGLE_API_KEY', '" . $app_gcm_key . "');
/*define('GOOGLE_API_KEY', 'AIzaSyAKe3XmUV93WvHJvII4Qzpf0R052mxb0KI');*/
/*define('GOOGLE_API_KEY', 'AIzaSyC0JjF-O72-gUvUmUm_dsHHvG5o3aWosp8');*/

class GCM {

    //put your code here
    // constructor
    function __construct() {
        
    }

    /**
     * Sending Push Notification
     */
    public function send_notification(\$registatoin_ids, \$message) {
        // include config
        include_once 'const.php';
        /* include_once 'config.php'; */
        // Set POST variables
        \$url = 'https://android.googleapis.com/gcm/send';

        \$fields = array(
            'registration_ids' => \$registatoin_ids,
            'data' => \$message,
        );

        \$headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        \$ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt(\$ch, CURLOPT_URL, \$url);

        curl_setopt(\$ch, CURLOPT_POST, true);
        curl_setopt(\$ch, CURLOPT_HTTPHEADER, \$headers);
        curl_setopt(\$ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt(\$ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt(\$ch, CURLOPT_POSTFIELDS, json_encode(\$fields));

        // Execute post
        \$result = curl_exec(\$ch);
        if (\$result === FALSE) {
            //die('Curl failed: ' . curl_error(\$ch));
            Log::error('Curl failed: ' . curl_error(\$ch));
        }
        else{
            //echo \$result;
            Log::error(\$result);
        }

        // Close connection
        /*curl_close(\$ch);
         echo \$result/*.'\n\n'.json_encode(\$fields); */
    }

}
?>
";
                $t = file_put_contents(app_path() . '/gcm/GCM_1.php', $update_client_certy);
                $is_certy_change ++;
            } else {
                return Redirect::to('/admin/settings/installation?success=4')
                                ->with('install', $install);
            }
        }
        /* if (Input::hasFile('user_certi_a')) {
          $certi_user_a = Certificates::where('client', 'apple')->where('user_type', 0)->where('file_type', 'certificate')->where('type', Input::get('cert_type_a'))->first();
          if ($certi_user_a != NULL) {
          //user
          $path = $certi_user_a->name;
          Log::info($path);
          $filename = basename($path);
          Log::info($filename);
          if (file_exists($path)) {
          try {
          unlink(public_path() . "/apps/ios_push/iph_cert/" . $filename);
          } catch (Exception $e) {

          }
          }
          $key = Certificates::where('client', 'apple')->where('user_type', 0)->where('file_type', 'certificate')->first();
          } else {
          $key = new Certificates();
          $key->client = 'apple';
          $key->type = Input::get('cert_type_a');
          $key->user_type = 0;
          $key->file_type = 'certificate';
          }
          // upload image
          $file_name = time();
          $file_name .= rand();
          $file_name = sha1($file_name);

          Log::info(Input::file('user_certi_a'));

          $ext = Input::file('user_certi_a')->getClientOriginalExtension();
          Input::file('user_certi_a')->move(public_path() . "/apps/ios_push/iph_cert", $file_name . "." . $ext);
          $local_url = $file_name . "." . $ext;

          // Upload to S3
          if (Config::get('app.s3_bucket') != "") {
          $s3 = App::make('aws')->get('s3');
          $pic = $s3->putObject(array(
          'Bucket' => Config::get('app.s3_bucket'),
          'Key' => $file_name,
          'SourceFile' => public_path() . "/apps/ios_push/iph_cert/" . $local_url,
          ));
          $s3->putObjectAcl(array(
          'Bucket' => Config::get('app.s3_bucket'),
          'Key' => $file_name,
          'ACL' => 'public-read'
          ));
          $s3_url = $s3->getObjectUrl(Config::get('app.s3_bucket'), $file_name);
          }
          Log::info('path = ' . print_r($local_url, true));
          $key->name = $local_url;
          $count = $count + 1;
          $key->save();
          }

          // User passphrase file.
          if (Input::has('user_pass_a')) {
          $user_key_db = Certificates::where('client', 'apple')->where('user_type', 0)->where('file_type', 'passphrase')->where('type', Input::get('cert_type_a'))->first();
          if ($user_key_db == NULL) {
          $key = new Certificates();
          $key->client = 'apple';
          $key->type = Input::get('cert_type_a');
          $key->user_type = 0;
          $key->file_type = 'passphrase';
          } else {
          $key = Certificates::where('client', 'apple')->where('user_type', 0)->where('file_type', 'passphrase')->first();
          }
          $key->name = Input::get('user_pass_a');
          $count = $count + 1;
          $key->save();
          }

          // apple provider
          if (Input::hasFile('prov_certi_a')) {
          $certi_prov_a = Certificates::where('client', 'apple')->where('user_type', 1)->where('file_type', 'certificate')->where('type', Input::get('cert_type_a'))->first();
          if ($certi_prov_a != NULL) {
          //user
          $path = $certi_prov_a->name;
          Log::info($path);
          $filename = basename($path);
          Log::info($filename);
          try {
          unlink(public_path() . "/apps/ios_push/walker/iph_cert/" . $filename);
          } catch (Exception $e) {

          }
          $key = Certificates::where('client', 'apple')->where('user_type', 1)->where('file_type', 'certificate')->first();
          } else {
          $key = new Certificates();
          $key->client = 'apple';
          $key->type = Input::get('cert_type_a');
          $key->user_type = 1;
          $key->file_type = 'certificate';
          }
          // upload image
          $file_name = time();
          $file_name .= rand();
          $file_name = sha1($file_name);

          $ext = Input::file('prov_certi_a')->getClientOriginalExtension();
          Input::file('prov_certi_a')->move(public_path() . "/apps/ios_push/walker/iph_cert", $file_name . "." . $ext);
          $local_url = $file_name . "." . $ext;

          // Upload to S3
          if (Config::get('app.s3_bucket') != "") {
          $s3 = App::make('aws')->get('s3');
          $pic = $s3->putObject(array(
          'Bucket' => Config::get('app.s3_bucket'),
          'Key' => $file_name,
          'SourceFile' => public_path() . "/apps/ios_push/walker/iph_cert/" . $local_url,
          ));
          $s3->putObjectAcl(array(
          'Bucket' => Config::get('app.s3_bucket'),
          'Key' => $file_name,
          'ACL' => 'public-read'
          ));
          }
          Log::info('path = ' . print_r($local_url, true));
          $key->name = $local_url;
          $count = $count + 1;
          $key->save();
          }

          // Provider passphrase file.
          if (Input::has('prov_pass_a')) {
          $user_key_db = Certificates::where('client', 'apple')->where('user_type', 1)->where('file_type', 'passphrase')->where('type', Input::get('cert_type_a'))->first();
          if ($user_key_db == NULL) {
          $key = new Certificates();
          $key->client = 'apple';
          $key->type = Input::get('cert_type_a');
          $key->user_type = 1;
          $key->file_type = 'passphrase';
          } else {
          $key = Certificates::where('client', 'apple')->where('user_type', 1)->where('file_type', 'passphrase')->first();
          }
          $key->name = Input::get('prov_pass_a');
          $count = $count + 1;
          $key->save();
          }

          // gcm key file.
          if (Input::has('gcm_key')) {
          $gcm_key_db = Certificates::where('client', 'gcm')->first();
          if ($gcm_key_db == NULL) {
          $key = new Certificates();
          $key->client = 'gcm';
          $key->type = Input::get('cert_type_a');
          $key->user_type = 0;
          $key->file_type = 'browser_key';
          } else {
          $key = Certificates::where('client', 'gcm')->first();
          }
          $key->name = Input::get('gcm_key');
          $count = $count + 1;
          $key->save();
          }

          Log::info("count = " . print_r($count, true));

          $cert_def = Input::get('cert_default');
          $certa = Certificates::where('client', 'apple')->get();
          foreach ($certa as $ca) {
          $def = Certificates::where('id', $ca->id)->first();
          $def->default = 0;
          $def->save();
          }
          $certs = Certificates::where('client', 'apple')->where('type', $cert_def)->get();
          foreach ($certs as $defc) {
          $def = Certificates::where('id', $defc->id)->first();
          Log::info('def = ' . print_r($def, true));
          $def->default = 1;
          $def->save();
          } */
        $android_client_app_url = NULL;
        if (Input::has('android_client_app_url')) {
            $android_client_app_url = Input::get('android_client_app_url');
        }
        $android_provider_app_url = NULL;
        if (Input::has('android_provider_app_url')) {
            $android_provider_app_url = Input::get('android_provider_app_url');
        }
        $ios_client_app_url = NULL;
        if (Input::has('ios_client_app_url')) {
            $ios_client_app_url = Input::get('ios_client_app_url');
        }
        $ios_provider_app_url = NULL;
        if (Input::has('ios_provider_app_url')) {
            $ios_provider_app_url = Input::get('ios_provider_app_url');
        }
        $appfile = fopen(app_path() . "/config/app.php", "w") or die("Unable to open file!");
        /* $appfile_config = generate_app_config($braintree_cse, $stripe_publishable_key, $url, $timezone, $website_title, $s3_bucket, $twillo_account_sid, $twillo_auth_token, $twillo_number, $default_payment, $stripe_secret_key, $braintree_environment, $braintree_merchant_id, $braintree_public_key, $braintree_private_key, $customer_certy_url, $customer_certy_pass, $customer_certy_type, $provider_certy_url, $provider_certy_pass, $provider_certy_type, $gcm_browser_key, $android_client_app_url, $android_provider_app_url, $ios_client_app_url, $ios_provider_app_url); */
        $appfile_config = generate_app_config($braintree_cse, $stripe_publishable_key, $url, $timezone, $website_title, $s3_bucket, $twillo_account_sid, $twillo_auth_token, $twillo_number, $default_payment, $stripe_secret_key, $braintree_environment, $braintree_merchant_id, $braintree_public_key, $braintree_private_key, $customer_certy_url, $customer_certy_pass, $customer_certy_type, $provider_certy_url, $provider_certy_pass, $provider_certy_type, $gcm_browser_key, $key_provider, $key_user, $key_taxi, $key_trip, $key_currency, $total_trip, $cancelled_trip, $total_payment, $completed_trip, $card_payment, $credit_payment, $key_ref_pre, $android_client_app_url, $android_provider_app_url, $ios_client_app_url, $ios_provider_app_url);
        fwrite($appfile, $appfile_config);
        fclose($appfile);

        $install = array(
            'braintree_environment' => $braintree_environment,
            'braintree_merchant_id' => $braintree_merchant_id,
            'braintree_public_key' => $braintree_public_key,
            'braintree_private_key' => $braintree_private_key,
            'braintree_cse' => $braintree_cse,
            'twillo_account_sid' => $twillo_account_sid,
            'twillo_auth_token' => $twillo_auth_token,
            'twillo_number' => $twillo_number,
            'stripe_publishable_key' => $stripe_publishable_key,
            'stripe_secret_key' => $stripe_secret_key,
            'mail_driver' => $mail_driver,
            'email_address' => $email_address,
            'email_name' => $email_name,
            'mandrill_secret' => $mandrill_secret,
            'default_payment' => $default_payment,
            /* DEVICE PUSH NOTIFICATION DETAILS */
            'customer_certy_url' => $customer_certy_url,
            'customer_certy_pass' => $customer_certy_pass,
            'customer_certy_type' => $customer_certy_type,
            'provider_certy_url' => $provider_certy_url,
            'provider_certy_pass' => $provider_certy_pass,
            'provider_certy_type' => $provider_certy_type,
            'gcm_browser_key' => $gcm_browser_key,
                /* DEVICE PUSH NOTIFICATION DETAILS END */
        );
        /* echo asset_url();
          echo "<br>";
          echo $provider_certy_url;
          echo $customer_certy_url; */
        if ($is_certy_change > 0) {
            return Redirect::to('/admin/settings/installation?success=1')->with('install', $install);
        } else {
            return Redirect::to('/admin/settings/installation?success=5')
                            ->with('install', $install);
        }
    }

    //Sort Owners
    public function sortur() {

        $valu = $_GET['valu'];
        $type = $_GET['type'];
        Session::put('valu', $valu);
        Session::put('type', $type);
        if($valu =='asc'){
            $valu = trans('language_changer.ascending');
        }
        elseif ($valu =='desc') {
            $valu = trans('language_changer.descending');
        }
            if ($type == 'userid') {
            $typename = trans('language_changer.owner').' '.trans('language_changer.id');
            $query = Owner::orderBy('id', $valu);
            if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                $users = $query->where('first_name', 'not like', '%stranger%')->orderBy('id', 'DESC')->get();
            }else {
                $users = $query->where('first_name', 'not like', '%stranger%')->orderBy('id', 'DESC')->paginate(10);
            }
        } elseif ($type == 'username') {
            $typename = trans('language_changer.owner').' '.trans('language_changer.name');
            $query = Owner::orderBy('first_name', $valu);
            if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                $users = $query->where('first_name', 'not like', '%stranger%')->orderBy('id', 'DESC')->get();
            }else {
                $users = $query->where('first_name', 'not like', '%stranger%')->orderBy('id', 'DESC')->paginate(10);
            }
        } elseif ($type == 'useremail') {
            $typename = trans('language_changer.owner').' '.trans('language_changer.email');
            $query = Owner::orderBy('email', $valu);
            if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                $users = $query->where('first_name', 'not like', '%stranger%')->orderBy('id', 'DESC')->get();
            }else {
                $users = $query->where('first_name', 'not like', '%stranger%')->orderBy('id', 'DESC')->paginate(10);
            }
        }
        $title = ucwords(trans('language_changer.User') . trans('language_changer.s') . " | ".trans('language_changer.sorted_by') . $typename . " ".trans('language_changer.in') . $valu); /* 'Owners | Sorted by ' . $typename . ' in ' . $valu */

        if (Input::get('submit') && Input::get('submit') == 'Download_Report') {

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=Report_users.csv');
            $handle = fopen('php://output', 'w');

            fputcsv($handle, array(Lang::get('constants.id'), Lang::get('constants.name'), Lang::get('constants.email'), Lang::get('constants.phone'), Lang::get('constants.address'), Lang::get('constants.state'), Lang::get('constants.zip_code'), Lang::get('constants.debt'), Lang::get('constants.referred_by')));

            foreach ($users as $user) {

                //Owner Phone
                $ownerPhone =$user->phone;

                //Email
                $ownerEmail =$user->email;

                //Owner Address
                $ownerAddress = ( !empty( $user->address ) ? $user->address : Config::get('app.blank_fiend_val') );

                //Owner State
                $ownerState = ( !empty( $user->state ) ? $user->state : Config::get('app.blank_fiend_val') );

                //Owner Zip code
                $ownerZipCode = ( !empty( $user->zipcode ) ? $user->zipcode : Config::get('app.blank_fiend_val') );

                //Owner Referred By
                $refer = Owner::where('id', $user->referred_by)->first();
                $ownerRefBy = ( !empty( $refer ) ? $refer->first_name . " " . $refer->last_name : Lang::get('constants.none') );


                fputcsv($handle, array(
                    $user->id,
                    $user->first_name . " " . $user->last_name,
                    $ownerEmail,
                    $ownerPhone,
                    $ownerAddress,
                    $ownerState,
                    $ownerZipCode,
                    sprintf2($user->debt, 2),
                    $ownerRefBy,
                ));
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

        } else {


            if(Config::get('app.locale') == 'arb'){
                $align_format="right";
            }elseif(Config::get('app.locale') == 'en'){
                $align_format="left";
            }

            return View::make('owners')
                ->with('title', $title)
                ->with('page', 'owners')
                ->with('align_format',$align_format)
                ->with('owners', $users);
        }
    }

    public function sortpv() {

        $valu = $_GET['valu'];
        $type = $_GET['type'];
        Session::put('valu', $valu);
        Session::put('type', $type);
        Session::forget('message');
        if($valu =='asc'){
            $valu = trans('language_changer.ascending');
        }
        elseif ($valu =='desc'){
            $valu = trans('language_changer.descending');
        }


        if ($type == 'provid') {
            $typename = trans('language_changer.Provider').' '.trans('language_changer.id');
            /* $providers = Walker::orderBy('id', $valu)->paginate(10); */
            $subQuery = DB::table('request_meta')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('walker_id = walker.id and status != 0');
            $subQuery1 = DB::table('request_meta')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('walker_id = walker.id and status=1');

            $query = DB::table('walker')
                    ->select('walker.*', DB::raw("(" . $subQuery->toSql() . ") as 'total_requests'"), DB::raw("(" . $subQuery1->toSql() . ") as 'accepted_requests'"))->where('deleted_at', NULL)
                    /* ->where('walker.is_deleted', 0) */
                    ->orderBy('walker.id', $valu);
                    if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                        $providers = $query->orderBy('walker.created_at', 'DESC')->get();
                    }else {
                        $providers = $query->orderBy('walker.created_at', 'DESC')->paginate(10);
                    }
        } elseif ($type == 'pvname') {
            $typename = trans('language_changer.Provider').' '.trans('language_changer.name');
            /* $providers = Walker::orderBy('first_name', $valu)->paginate(10); */
            $subQuery = DB::table('request_meta')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('walker_id = walker.id and status != 0');
            $subQuery1 = DB::table('request_meta')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('walker_id = walker.id and status=1');

            $query = DB::table('walker')
                    ->select('walker.*', DB::raw("(" . $subQuery->toSql() . ") as 'total_requests'"), DB::raw("(" . $subQuery1->toSql() . ") as 'accepted_requests'"))->where('deleted_at', NULL)
                    /* ->where('walker.is_deleted', 0) */
                    ->orderBy('walker.first_name', $valu);
                    if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                        $providers = $query->orderBy('walker.created_at', 'DESC')->get();
                    }else {
                        $providers = $query->orderBy('walker.created_at', 'DESC')->paginate(10);
                    }
        } elseif ($type == 'pvemail') {
            $typename = trans('language_changer.Provider').' '.trans('language_changer.email');
            /* $providers = Walker::orderBy('email', $valu)->paginate(10); */
            $subQuery = DB::table('request_meta')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('walker_id = walker.id and status != 0');
            $subQuery1 = DB::table('request_meta')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('walker_id = walker.id and status=1');

            $query = DB::table('walker')
                    ->select('walker.*', DB::raw("(" . $subQuery->toSql() . ") as 'total_requests'"), DB::raw("(" . $subQuery1->toSql() . ") as 'accepted_requests'"))->where('deleted_at', NULL)
                    /* ->where('walker.is_deleted', 0) */
                    ->orderBy('walker.email', $valu);
                    if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                        $providers = $query->orderBy('walker.created_at', 'DESC')->get();
                    }else {
                        $providers = $query->orderBy('walker.created_at', 'DESC')->paginate(10);
                    }
        } elseif ($type == 'pvaddress') {
            $typename = trans('language_changer.Provider').' '.trans('language_changer.address');
            /* $providers = Walker::orderBy('address', $valu)->paginate(10); */
            $subQuery = DB::table('request_meta')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('walker_id = walker.id and status != 0');
            $subQuery1 = DB::table('request_meta')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('walker_id = walker.id and status=1');

            $query = DB::table('walker')
                    ->select('walker.*', DB::raw("(" . $subQuery->toSql() . ") as 'total_requests'"), DB::raw("(" . $subQuery1->toSql() . ") as 'accepted_requests'"))->where('deleted_at', NULL)
                    /* ->where('walker.is_deleted', 0) */
                    ->orderBy('walker.address', $valu);
                    if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                        $providers = $query->orderBy('walker.created_at', 'DESC')->get();
                    }else {
                        $providers = $query->orderBy('walker.created_at', 'DESC')->paginate(10);
                    }
        }
        //$title = ucwords( trans('language_changer.s') . " | trans('language_changer.sorted_by') " . $typename . " trans('language_changer.in') " . $valu); /* 'Providers | Sorted by ' . $typename . ' in ' . $valu */
        $title = ucwords(trans('language_changer.Provider') . trans('language_changer.s') . " | " . trans('language_changer.sorted_by')  ." ". $typename ." ".  trans('language_changer.in') ." " . $valu); /* 'Providers | Sorted by ' . $typename . ' in ' . $valu */

        if (Input::get('submit') && Input::get('submit') == 'Download_Report') {

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=Report_drivers.csv');
            $handle = fopen('php://output', 'w');

            fputcsv($handle, array(Lang::get('constants.id'), Lang::get('constants.name'), Lang::get('constants.email'), Lang::get('constants.phone'), Lang::get('constants.bio'), Lang::get('constants.total_requests'), Lang::get('constants.acceptance_rate'), Lang::get('constants.status')));

            foreach ($providers as $provider) {

                //Phone
                $walkerPhone = $provider->phone;
                //Email
                $walkerEmail = $provider->email;

                //Bio
                $walkerBio = ( !empty( $provider->bio ) ? $provider->bio : Config::get('app.blank_fiend_val') );
                //Accept Request
                $walkerAcptRequest = (( $provider->total_requests != 0 ) ? round(( $provider->accepted_requests / $provider->total_requests) * 100, 2) : 0);
                //Is Approved
                $walkerIsApproved = ( !empty( $provider->is_approved )  ? Lang::get('constants.approved') : Lang::get('constants.pending') );

                fputcsv($handle, array(
                    $provider->id,
                    $provider->first_name . " " . $provider->last_name,
                    $walkerEmail,
                    $walkerPhone,
                    $walkerBio,
                    $provider->total_requests,
                    $walkerAcptRequest."%",
                    $walkerIsApproved,
                ));
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

        } else {

            if(Config::get('app.locale') == 'arb'){
                $align_format="right";
            }elseif(Config::get('app.locale') == 'en'){
                $align_format="left";
            }



            return View::make('walkers')
                ->with('title', $title)
                ->with('page', 'walkers')
                ->with('align_format',$align_format)
                ->with('walkers', $providers);
        }
    }

    public function sortpvtype() {
        $valu = $_GET['valu'];
        $type = $_GET['type'];
        Session::put('valu', $valu);
        Session::put('type', $type);
        if($valu =='asc'){
            $valu = trans('language_changer.ascending');
        }
        elseif ($valu =='desc'){
            $valu = trans('language_changer.descending');
        }
        if ($type == 'provid') {
            $typename = trans('language_changer.provider').' '.trans('language_changer.type').' '.trans('language_changer.id');
            $providers = ProviderType::orderBy('id', $valu)->paginate(10);
        } elseif ($type == 'pvname') {
            $typename = trans('language_changer.provider').' '.trans('language_changer.name');
            $providers = ProviderType::orderBy('name', $valu)->paginate(10);
        }
        $settings = Settings::where('key', 'default_distance_unit')->first();
        $unit = $settings->value;
        if ($unit == 0) {
            $unit_set = 'kms';
        } elseif ($unit == 1) {
            $unit_set = 'miles';
        }
        $title = ucwords(trans('language_changer.Provider') . " ".trans('language_changer.type') . " | ".trans('language_changer.sorted_by').' ' . $typename . "  ". trans('language_changer.in') . $valu); /* 'Provider Types | Sorted by ' . $typename . ' in ' . $valu */

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }


        return View::make('list_provider_types')
                        ->with('title', $title)
                        ->with('page', 'provider-type')
                        ->with('unit_set', $unit_set)
            ->with('align_format',$align_format)
                        ->with('types', $providers);
    }

    public function sortreq() {

        $valu = $_GET["valu"];
        $type = $_GET["type"];
        Session::put('valu', $valu);
        Session::put('type', $type);


        if($valu =='asc'){
            $valu = trans('language_changer.ascending');
        }
        elseif ($valu =='desc'){
            $valu = trans('language_changer.descending');
        }
        if ($type == 'reqid') {
            $typename = trans('language_changer.request').' '.trans('language_changer.id');
            $query = DB::table('request')
                    ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                    ->leftJoin('walker', 'request.current_walker', '=', 'walker.id')
                    ->groupBy('request.id')
                    ->select('owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'request.id as id', 'request.created_at as date', 'request.is_started', 'request.is_walker_arrived', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker'
                            , 'request.status', 'request.time', 'request.distance', 'request.total', 'request.is_cancelled', 'request.transfer_amount', 'request.payment_mode')
                    ->orderBy('request.id', $valu);
            if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                 $requests = $query->get();
            }else {
                 $requests = $query->paginate(10);
            }
        } elseif ($type == 'owner') {
            $typename = trans('language_changer.owner').' '.trans('language_changer.name');
            $query = DB::table('request')
                    ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                    ->leftJoin('walker', 'request.current_walker', '=', 'walker.id')
                    ->groupBy('request.id')
                    ->select('owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'request.id as id', 'request.created_at as date', 'request.is_started', 'request.is_walker_arrived', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker'
                            , 'request.status', 'request.time', 'request.distance', 'request.total', 'request.is_cancelled', 'request.transfer_amount', 'request.payment_mode')
                    ->orderBy('owner.first_name', $valu);
            if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                $requests = $query->get();
            }else {
                $requests = $query->paginate(10);
            }
        } elseif ($type == 'walker') {
            $typename = trans('language_changer.Provider').' '.trans('language_changer.name');
            $query = DB::table('request')
                    ->leftJoin('walker', 'request.current_walker', '=', 'walker.id')
                    ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                    ->groupBy('request.id')
                    ->select('owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'request.id as id', 'request.created_at as date', 'request.is_started', 'request.is_walker_arrived', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker'
                            , 'request.status', 'request.time', 'request.distance', 'request.total', 'request.is_cancelled', 'request.transfer_amount', 'request.payment_mode')
                    ->orderBy('walker.first_name', $valu);
            if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                $requests = $query->get();
            }else {
                $requests = $query->paginate(10);
            }
        } elseif ($type == 'payment') {
            $typename = trans('language_changer.payment_mode');
            $query = DB::table('request')
                    ->leftJoin('walker', 'request.current_walker', '=', 'walker.id')
                    ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                    ->groupBy('request.id')
                    ->select('owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'request.id as id', 'request.created_at as date', 'request.is_started', 'request.is_walker_arrived', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker'
                            , 'request.status', 'request.time', 'request.distance', 'request.total', 'request.is_cancelled', 'request.transfer_amount', 'request.payment_mode')
                    ->orderBy('request.payment_mode', $valu);
            if (Input::get('submit') && Input::get('submit') == 'Download_Report') {
                $requests = $query->get();
            }else {
                $requests = $query->paginate(10);
            }
        }
        $setting = Settings::where('key', 'paypal')->first();

        /*if(Config::get('app.locale') == 'arb'){
            $title = ucwords(trans('customize.Request') . "s" . " | Sorted by " . $typename . " in " . $valu);
        }elseif(Config::get('app.locale') == 'en'){
            $title = ucwords(trans('customize.Request') . "s" . " | Sorted by " . $typename . " in " . $valu);
        }*/


        $title = ucwords(trans('language_changer.Request') . trans('language_changer.s') . " | ". trans('language_changer.sorted_by') . $typename .trans('language_changer.in')." ". $valu);

        if (Input::get('submit') && Input::get('submit') == 'Download_Report') {

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=Report_requests.csv');
            $handle = fopen('php://output', 'w');

            fputcsv($handle, array(Lang::get('constants.request_id'), Lang::get('constants.user_name'), Lang::get('constants.driver'), Lang::get('constants.date_time'), Lang::get('constants.status'),Lang::get('constants.amount'), Lang::get('constants.payment_mode'), Lang::get('constants.payment_status')));

            foreach ($requests as $request) {

                //Walkers First name and last Name
                $walkerName = ( !empty( $request->confirmed_walker ) ? $request->walker_first_name . " " . $request->walker_last_name : Lang::get('constants.un_assigned') );

                //WalkersDate
                $walkersDate = ( !empty( $request->date )  ? date('Y-m-d/H:m:s',strtotime($request->date)) : '' );

                $walkerIsCanceled = '';
                //Walker cancel status
                if ($request->is_cancelled == 1) {
                    $walkerIsCanceled = Lang::get('constants.cancelled');
                } elseif ($request->is_completed == 1) {
                    $walkerIsCanceled = Lang::get('constants.completed');
                } elseif ($request->is_started == 1) {
                    $walkerIsCanceled = Lang::get('constants.started');
                } elseif ($request->is_walker_arrived == 1) {
                    $walkerIsCanceled = Lang::get('constants.walker_arrived');
                } elseif ($request->is_walker_started == 1) {
                    $walkerIsCanceled = Lang::get('constants.walker_started');
                } else {
                    $walkerIsCanceled = Lang::get('constants.yet_to_start');
                }

                $walkerPaymentMode = '';
                //Walker Payment Mode
                if ($request->payment_mode == 0) {
                    $walkerPaymentMode = Lang::get('constants.stored_cards');
                } elseif ($request->payment_mode == 1) {
                    $walkerPaymentMode = Lang::get('constants.pay_by_cash');
                } elseif ($request->payment_mode == 2) {
                    $walkerPaymentMode = Lang::get('constants.paypal');
                }

                $walkerIsPaid = '';
                if ($request->is_paid == 1) {
                    $walkerIsPaid = Lang::get('constants.completed');
                } elseif ($request->is_paid == 0 && $request->is_completed == 1) {
                    $walkerIsPaid = Lang::get('constants.pending');
                } else {
                    $walkerIsPaid = Lang::get('constants.request_not_completed');
                }

                fputcsv($handle, array(
                    $request->id,
                    $request->owner_first_name . " " . $request->owner_last_name,
                    $walkerName,
                    $walkersDate,
                    $walkerIsCanceled,
                    sprintf2($request->total, 2),
                    $walkerPaymentMode,
                    $walkerIsPaid,
                ));
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

        } else {
            if(Config::get('app.locale') == 'arb'){
                $align_format="right";
            }elseif(Config::get('app.locale') == 'en'){
                $align_format="left";
            }


            return View::make('walks')
                ->with('title', $title)
                ->with('page', 'walks')
                ->with('walks', $requests)
                ->with('align_format',$align_format)
                ->with('setting', $setting);
        }
    }

    public function sortpromo() {
        $valu = $_GET["valu"];
        $type = $_GET["type"];
        $success = Input::get('success');
        Session::put('valu', $valu);
        Session::put('type', $type);

        if($valu =='asc'){
            $valu = trans('language_changer.ascending');
        }
        elseif ($valu =='desc'){
            $valu = trans('language_changer.descending');
        }
        if ($type == 'promoid') {
            $typename = trans('language_changer.promo_code').' '.trans('language_changer.id');
            $promo_codes = DB::table('promo_codes')
                    ->orderBy('id', $valu)
                    ->paginate(10);
        } elseif ($type == 'promo') {
            $typename = trans('language_changer.promo_code');
            $promo_codes = DB::table('promo_codes')
                    ->orderBy('coupon_code', $valu)
                    ->paginate(10);
        } elseif ($type == 'uses') {
            $typename = trans('language_changer.no_of_uses');
            $promo_codes = DB::table('promo_codes')
                    ->orderBy('uses', $valu)
                    ->paginate(10);
        }
        $setting = Settings::where('key', 'paypal')->first();
        $title = ucwords(trans('language_changer.promo_codes') . " | ".trans('language_changer.sorted_by').' '. $typename . " " .trans('language_changer.in')." ". $valu); /* 'Promocodes | Sorted by ' . $typename . ' in ' . $valu */

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }



        return View::make('list_promo_codes')
                        ->with('title', $title)
                        ->with('page', 'promo_code')
                        ->with('success', $success)
                        ->with('promo_codes', $promo_codes)
                        ->with('align_format',$align_format)
                        ->with('setting', $setting);
    }

    public function searchpromo() {
        $valu = $_GET['valu'];
        $type = $_GET['type'];
        $success = Input::get('success');
        Session::put('valu', $valu);
        Session::put('type', $type);
        if ($type == 'promo_id') {
            $promo_codes = PromoCodes::where('id', $valu)->paginate(10);
        } elseif ($type == 'promo_name') {
            $promo_codes = PromoCodes::where('coupon_code', 'like', '%' . $valu . '%')->paginate(10);
        } elseif ($type == 'promo_type') {
            if ($valu == '%') {
                $promo_codes = PromoCodes::where('type', 1)->paginate(10);
            } elseif ($val = '$') {
                $promo_codes = PromoCodes::where('type', 2)->paginate(10);
            }
        } elseif ($type == 'promo_state') {
            if ($valu == 'active' || $valu == 'Active') {
                $promo_codes = PromoCodes::where('state', 1)->paginate(10);
            } elseif ($val = 'Deactivated' || $val = 'deactivated') {
                $promo_codes = PromoCodes::where('state', 2)->paginate(10);
            }
        }
        $title = ucwords(trans('language_changer.promo_codes') . " | ".trans('language_changer.search').' '.trans('language_changer.result')); /* 'Promo Codes | Search Result' */

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }


        return View::make('list_promo_codes')
                        ->with('title', $title)
                        ->with('page', 'promo_code')
                        ->with('success', $success)
            ->with('align_format',$align_format)
                        ->with('promo_codes', $promo_codes);
    }

// Provider Availability

    public function allow_availability() {
        Settings::where('key', 'allowcal')->update(array('value' => 1));
        return Redirect::to("/admin/providers");
    }

    public function disable_availability() {
        Settings::where('key', 'allowcal')->update(array('value' => 0));
        return Redirect::to("/admin/providers");
    }

    public function availability_provider() {
        $id = Request::segment(4);
        $provider = Walker::where('id', $id)->first();
        if ($provider) {
            $success = Input::get('success');
            $pavail = ProviderAvail::where('provider_id', $id)->paginate(10);
            $prvi = array();
            foreach ($pavail as $pv) {
                $prv = array();
                $prv['title'] = 'available';
                $prv['start'] = date('Y-m-d', strtotime($pv->start)) . "T" . date('H:i:s', strtotime($pv->start));
                $prv['end'] = date('Y-m-d', strtotime($pv->end)) . "T" . date('H:i:s', strtotime($pv->end));
                ;
                array_push($prvi, $prv);
            }
            $pvjson = json_encode($prvi);
            Log::info('Provider availability json = ' . print_r($pvjson, true));
            $title = ucwords(trans('language_changer.Provider') . " ".trans('language_changer.available')); /* 'Provider Availability' */
            return View::make('availability_provider')
                            ->with('title', $title)
                            ->with('page', 'walkers')
                            ->with('success', $success)
                            ->with('pvjson', $pvjson)
                            ->with('provider', $provider);
        } else {
            return View::make('admin.notfound')->with('title', 'Error Page Not Found')->with('page', 'Error Page Not Found');
        }
    }

    public function provideravailabilitySubmit() {
        $id = Request::segment(4);
        $proavis = $_POST['proavis'];
        $proavie = $_POST['proavie'];
        $length = $_POST['length'];
        Log::info('Start end time Array Length = ' . print_r($length, true));
        DB::delete("delete from provider_availability where provider_id = '" . $id . "';");
        for ($l = 0; $l < $length; $l++) {
            $pv = new ProviderAvail;
            $pv->provider_id = $id;
            $pv->start = $proavis[$l];
            $pv->end = $proavie[$l];
            $pv->save();
        }
        Log::info('providers availability start = ' . print_r($proavis, true));
        Log::info('providers availability end = ' . print_r($proavie, true));
        return Response::json(array('success' => true));
    }

    public function view_documents_provider() {
        $id = Request::segment(4);
        $provider = Walker::where('id', $id)->first();
        $provider_documents = WalkerDocument::where('walker_id', $id)->paginate(10);
        if ($provider) {
            $title = ucwords(trans('customize.Provider') . " View Documents : " . $provider->first_name . " " . $provider->last_name); /* 'Provider View Documents' */
            return View::make('view_documents')
                            ->with('title', $title)
                            ->with('page', 'walkers')
                            ->with('docs', $provider_documents)
                            ->with('provider', $provider);
        } else {
            return View::make('admin.notfound')->with('title', trans('language_changer.page_not_found'))->with('page', trans('language_changer.page_not_found'));
        }
    }

    //Providers Who currently walking
    public function current() {
        Session::put('che', 'current');

        $walks = DB::table('request')
                ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->select('walker.id as id', 'walker.first_name as first_name', 'walker.last_name as last_name', 'walker.phone as phone', 'walker.email as email', 'walker.picture as picture', 'walker.merchant_id as merchant_id', 'walker.bio as bio', 'request.total as total_requests', 'walker.is_approved as is_approved')
                ->where('deleted_at', NULL)
                ->where('request.is_started', 1)
                ->where('request.is_completed', 0)
                ->paginate(10);
        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }
        $title = ucwords(trans('language_changer.Provider') . trans('language_changer.s') . " | ". trans('language_changer.currently_providing')); /* 'Providers | Currently Providing' */
        return View::make('walkers')
                        ->with('title', $title)
                        ->with('page', 'walkers')
            ->with('align_format',$align_format)
                        ->with('walkers', $walks);
    }

    public function theme() {
        $th = Theme::all()->count();

        if ($th == 1) {
            $theme = Theme::first();
        } else {
            $theme = new Theme;
        }

        $theme->theme_color = '#' . Input::get('color1');
        $theme->secondary_color = '#' . Input::get('color3');
        $theme->primary_color = '#' . Input::get('color2');
        $theme->hover_color = '#' . Input::get('color4');
        $theme->active_color = '#' . Input::get('color5');

        $css_msg = ".btn-default {
  color: #ffffff;
  background-color: $theme->theme_color;
}
.navbar-nav > li {
  float: left;
}
.btn-info{
    color: #000;
    background: #fff;
    border-radius: 0px;
    border:1px solid $theme->theme_color;
}
.nav-admin .dropdown :hover, .nav-admin .dropdown :hover {
    background: $theme->hover_color;
    color: #000;
}
.navbar-nav > li > a {
  border-radius: 0px;
}
.navbar-nav > li + li {
  margin-left: 2px;
}
.navbar-nav > li.active > a,
.navbar-nav> li.active > a:hover,
.navbar-nav > li.active > a:focus {
  color: #ffffff;
  background-color: $theme->active_color!important;
}
.logo_img_login{
border-radius: 30px;border: 4px solid $theme->theme_color;
}
.btn-success {
  color: #ffffff;
  background-color: $theme->theme_color;
  border-color: $theme->theme_color;
}
.btn-success:hover,
.btn-success:focus,
.btn-success:active,
.btn-success.active,
.open .dropdown-toggle.btn-success {
  color: #ffffff;
  background-color: $theme->theme_color;
  border-color: $theme->theme_color;

}


.btn-success.disabled,
.btn-success[disabled],
fieldset[disabled] .btn-success,
.btn-success.disabled:hover,
.btn-success[disabled]:hover,
fieldset[disabled] .btn-success:hover,
.btn-success.disabled:focus,
.btn-success[disabled]:focus,
fieldset[disabled] .btn-success:focus,
.btn-success.disabled:active,
.btn-success[disabled]:active,
fieldset[disabled] .btn-success:active,
.btn-success.disabled.active,
.btn-success[disabled].active,
fieldset[disabled] .btn-success.active {

  background-color: $theme->theme_color;
  border-color: $theme->theme_color;
}
.btn-success .badge {
  color: $theme->theme_color;
  background-color: #ffffff;
}
.btn-info {
  color: #ffffff;
  background-color: $theme->theme_color;
  border-color: $theme->theme_color;
}
.btn-info:hover,
.btn-info:focus,
.btn-info:active,
.btn-info.active,
.open .dropdown-toggle.btn-info {
  color: #000;
  background-color: #FFFF;
  border-color: $theme->theme_color;
}
.btn-info:active,
.btn-info.active,
.open .dropdown-toggle.btn-info {
  background-image: none;
}
.btn-info.disabled,
.btn-info[disabled],
fieldset[disabled] .btn-info,
.btn-info.disabled:hover,
.btn-info[disabled]:hover,
fieldset[disabled] .btn-info:hover,
.btn-info.disabled:focus,
.btn-info[disabled]:focus,
fieldset[disabled] .btn-info:focus,
.btn-info.disabled:active,
.btn-info[disabled]:active,
fieldset[disabled] .btn-info:active,
.btn-info.disabled.active,
.btn-info[disabled].active,
fieldset[disabled] .btn-info.active {
  background-color: $theme->theme_color;
  border-color: $theme->theme_color;
}
.btn-info .badge {
  color: $theme->theme_color;
  background-color: #029acf;
  border-color: #029acf;
}
.btn-success,
.btn-success:hover {
  background-image: -webkit-linear-gradient($theme->theme_color $theme->theme_color 6%, $theme->theme_color);
  background-image: linear-gradient($theme->theme_color, $theme->theme_color 6%, $theme->theme_color);
  background-repeat: no-repeat;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='$theme->theme_color', endColorstr='$theme->theme_color', GradientType=0);
  filter: none;
  border: 1px solid $theme->theme_color;
}
.btn-info,
.btn-info:hover {
  background-image: -webkit-linear-gradient($theme->theme_color, $theme->theme_color 6%, $theme->theme_color);
  background-image: linear-gradient($theme->theme_color, $theme->theme_color 6%, $theme->theme_color);
  background-repeat: no-repeat;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='$theme->theme_color', endColorstr='$theme->theme_color', GradientType=0);
  filter: none;
  border: 1px solid $theme->theme_color;
}
.logo h3{
    margin: 0px;
    color: $theme->theme_color;
}

.second-nav{
    background: $theme->theme_color;
}
.login_back{background-color: $theme->theme_color;}
.no_radious:hover{background-image: -webkit-linear-gradient($theme->theme_color, $theme->theme_color 6%, $theme->theme_color);background-image: linear-gradient(#5d4dd1, #5d4dd1 6%, #5d4dd1);background-repeat: no-repeat;filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#5d4dd1', endColorstr='#5d4dd1', GradientType=0);filter: none;border: 1px solid #5d4dd1;}
.navbar-nav li:nth-child(1) a{
    background: $theme->primary_color;
}

.navbar-nav li:nth-child(2) a{
    background: $theme->secondary_color;
}

.navbar-nav li:nth-child(3) a{
    background: $theme->primary_color;
}

.navbar-nav li:nth-child(4) a{
    background: $theme->secondary_color;
}

.navbar-nav li:nth-child(5) a{
    background: $theme->primary_color;
}

.navbar-nav li:nth-child(6) a{
    background: $theme->secondary_color;
}

.navbar-nav li:nth-child(7) a{
    background: $theme->primary_color;
}

.navbar-nav li:nth-child(8) a{
    background: $theme->secondary_color;
}

.navbar-nav li:nth-child(9) a{
    background: $theme->primary_color;
}

.navbar-nav li:nth-child(10) a{
    background: $theme->secondary_color;
}

.navbar-nav li a:hover{
    background: $theme->hover_color;
}
.btn-green{

    background: $theme->theme_color;
    color: #fff;
}
.btn-green:hover{
    background: $theme->hover_color;
    color: #fff;
}
";
        $t = file_put_contents(public_path() . '/stylesheet/theme_cus.css', $css_msg);
        /* chmod(public_path() . '/stylesheet/theme_cus.css', 0777); */

        if (Input::hasFile('logo')) {
            // Upload File
            $file_name = time();
            $file_name .= rand();
            $ext = Input::file('logo')->getClientOriginalExtension();

            Input::file('logo')->move(public_path() . "/uploads", $file_name . "." . $ext);
            $local_url = $file_name . "." . $ext;

            /* $new = Image::make(public_path() . "/uploads/" . $local_url)->resize(70, 70)->save(); */

            // Upload to S3
            if (Config::get('app.s3_bucket') != "") {
                $s3 = App::make('aws')->get('s3');
                $pic = $s3->putObject(array(
                    'Bucket' => Config::get('app.s3_bucket'),
                    'Key' => $file_name,
                    'SourceFile' => public_path() . "/uploads/" . $local_url,
                ));

                $s3->putObjectAcl(array(
                    'Bucket' => Config::get('app.s3_bucket'),
                    'Key' => $file_name,
                    'ACL' => 'public-read'
                ));

                $s3_url = $s3->getObjectUrl(Config::get('app.s3_bucket'), $file_name);
            } else {
                $s3_url = asset_url() . '/uploads/' . $local_url;
            }
            if (isset($theme->logo)) {
                $icon = asset_url() . '/uploads/' . $theme->logo;
                unlink_image($icon);
            }
            $theme->logo = $local_url;
        }

        if (Input::hasFile('icon')) {
            // Upload File
            $file_name1 = time();
            $file_name1 .= rand();
            $file_name1 .= 'icon';
            $ext1 = Input::file('icon')->getClientOriginalExtension();
            Input::file('icon')->move(public_path() . "/uploads", $file_name1 . "." . $ext1);
            $local_url1 = $file_name1 . "." . $ext1;

            // Upload to S3
            if (Config::get('app.s3_bucket') != "") {
                $s3 = App::make('aws')->get('s3');
                $pic = $s3->putObject(array(
                    'Bucket' => Config::get('app.s3_bucket'),
                    'Key' => $file_name1,
                    'SourceFile' => public_path() . "/uploads/" . $local_url1,
                ));

                $s3->putObjectAcl(array(
                    'Bucket' => Config::get('app.s3_bucket'),
                    'Key' => $file_name1,
                    'ACL' => 'public-read'
                ));

                $s3_url1 = $s3->getObjectUrl(Config::get('app.s3_bucket'), $file_name1);
            } else {
                $s3_url1 = asset_url() . '/uploads/' . $local_url1;
            }
            if (isset($theme->favicon)) {
                $icon = asset_url() . '/uploads/' . $theme->favicon;
                unlink_image($icon);
            }
            $theme->favicon = $local_url1;
        }
        $theme->save();
        return Redirect::to("/admin/settings");
    }

    public function transfer_amount() {
        $request = Requests::where('id', Input::get('request_id'))->first();
        $walker = Walker::where('id', $request->confirmed_walker)->first();
        $amount = Input::get("amount");

        if (($amount + $request->transfer_amount) <= $request->total && ($amount + $request->transfer_amount) > 0) {
            if (Config::get('app.default_payment') == 'stripe') {
                Stripe::setApiKey(Config::get('app.stripe_secret_key'));
                // dd($amount$request->transfer_amount);
				
				$transfer = Stripe_Transfer::create(array(
                            "amount" => $amount * 100, // amount in cents
                            "currency" => "usd",
                            "destination" => $walker->merchant_id,
							"description" => "Transfer for test@example.com")
                );
               /* $transfer = Stripe_Transfer::create(array(
                            "amount" => $amount * 100, // amount in cents
                            "currency" => "usd",
                            "recipient" => $walker->merchant_id)
                );*/
            } else {
                Braintree_Configuration::environment(Config::get('app.braintree_environment'));
                Braintree_Configuration::merchantId(Config::get('app.braintree_merchant_id'));
                Braintree_Configuration::publicKey(Config::get('app.braintree_public_key'));
                Braintree_Configuration::privateKey(Config::get('app.braintree_private_key'));
                $payment_data = Payment::where('owner_id', $request->owner_id)->first();
                $customer_id = $payment_data->customer_id;
                $result = Braintree_Transaction::sale(
                                array(
                                    'merchantAccountId' => $walker->merchant_id,
                                    'paymentMethodNonce' => $customer_id,
                                    'options' => array(
                                        'submitForSettlement' => true,
                                        'holdInEscrow' => true,
                                    ),
                                    'amount' => $amount
                                )
                );
            }
            $request->transfer_amount += $amount;
            $request->save();
            return Redirect::to("/admin/requests");
        } else {
            Session::put('error', "Amount exceeds the total amount to be paid");
            $title = ucwords(trans('language_changer.transfer')." ".trans('language_changer.amount'));
            return View::make('transfer_amount')
                            ->with('request', $request)
                            ->with('title', $title)
                            ->with('page', 'walkers');
        }
    }

    public function pay_provider($id) {
       // $request = Requests::find($id);
	   $request=DB::table('request')->where('id','=',$id)->first();

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }


        $title = ucwords(trans('language_changer.transfer').' '.trans('language_changer.payment'));
        if (Config::get('app.default_payment') == 'stripe') {

            return View::make('transfer_amount')
                            ->with('request', $request)
                            ->with('title', $title)
                             ->with('align_format',$align_format)
                            ->with('page', 'walkers');
        } else {
            $this->_braintreeConfigure();
            $clientToken = Braintree_ClientToken::generate();
            Session::put('error', 'Manual Transfer is not available in braintree.');
          //  $title = ucwords("Transfer amount");
            return View::make('transfer_amount')
                            ->with('request', $request)
                            ->with('clientToken', $clientToken)
                            ->with('title', $title)
                            ->with('align_format',$align_format)
                            ->with('page', 'walks');
        }
    }

    public function charge_user($id) {
        $request = Requests::find($id);
        Log::info('Charge User from admin');
        $total = $request->total;
        $payment_data = Payment::where('owner_id', $request->owner_id)->first();
        $customer_id = $payment_data->customer_id;
        $setransfer = Settings::where('key', 'transfer')->first();
        $transfer_allow = $setransfer->value;
        if (Config::get('app.default_payment') == 'stripe') {
            //dd($customer_id);
            Stripe::setApiKey(Config::get('app.stripe_secret_key'));
            try {
                $charge = Stripe_Charge::create(array(
                            "amount" => $total * 100,
                            "currency" => "usd",
                            "customer" => $customer_id)
                );
                Log::info('charge stripe = ' . print_r($charge, true));
            } catch (Stripe_InvalidRequestError $e) {
                // Invalid parameters were supplied to Stripe's API
                $ownr = Owner::find($request->owner_id);
                $ownr->debt = $total;
                $ownr->save();
                $response_array = array('error' => $e->getMessage());
                $response_code = 200;
                $response = Response::json($response_array, $response_code);
                return $response;
            }
            $request->is_paid = 1;
            $settng = Settings::where('key', 'service_fee_cash')->first();
            $settng_mode = Settings::where('key', 'payment_mode')->first();
            if ($settng_mode->value == 2 and $transfer_allow == 1) {
                $transfer = Stripe_Transfer::create(array(
                            "amount" => ($total - $settng->value) * 100, // amount in cents
                            "currency" => "usd",
                            "recipient" => $walker_data->merchant_id)
                );
                $request->transfer_amount = ($total - $settng->value);
            }
        } else {
            try {
                Braintree_Configuration::environment(Config::get('app.braintree_environment'));
                Braintree_Configuration::merchantId(Config::get('app.braintree_merchant_id'));
                Braintree_Configuration::publicKey(Config::get('app.braintree_public_key'));
                Braintree_Configuration::privateKey(Config::get('app.braintree_private_key'));
                if ($settng_mode->value == 2 and $transfer_allow == 1) {
                    $sevisett = Settings::where('key', 'service_fee_cash')->first();
                    $service_fee = $sevisett->value;
                    $result = Braintree_Transaction::sale(array(
                                'amount' => $total - $service_fee,
                                'paymentMethodNonce' => $customer_id,
                                'merchantAccountId' => $walker_data->merchant_id,
                                'options' => array(
                                    'submitForSettlement' => true,
                                    'holdInEscrow' => true,
                                ),
                                'serviceFeeAmount' => $service_fee
                    ));
                } else {
                    $result = Braintree_Transaction::sale(array(
                                'amount' => $total,
                                'paymentMethodNonce' => $customer_id
                    ));
                }
                Log::info('result of braintree = ' . print_r($result, true));
                if ($result->success) {
                    $request->is_paid = 1;
                } else {
                    $request->is_paid = 0;
                }
            } catch (Exception $e) {
                Log::info('error in braintree payment = ' . print_r($e, true));
            }
        }
        $request->card_payment = $total;
        $request->ledger_payment = $request->total - $total;
        $request->save();
        return Redirect::to('/admin/requests');
    }

    public function add_request() {
        Log::info('add request from admin panel.');
        $owner_id = Request::segment(3);
        $owner = Owner::find($owner_id);
        $services = ProviderType::where('is_visible', '=', 1)->get();
        $total_services = ProviderType::where('is_visible', '=', 1)->count();
        // Payment options allowed
        $payment_options = array();

        $payments = Payment::where('owner_id', $owner_id)->count();

        if ($payments) {
            $payment_options['stored_cards'] = 1;
        } else {
            $payment_options['stored_cards'] = 0;
        }
        $codsett = Settings::where('key', 'cod')->first();
        if ($codsett->value == 1) {
            $payment_options['cod'] = 1;
        } else {
            $payment_options['cod'] = 0;
        }

        $paypalsett = Settings::where('key', 'paypal')->first();
        if ($paypalsett->value == 1) {
            $payment_options['paypal'] = 1;
        } else {
            $payment_options['paypal'] = 0;
        }

        Log::info('payment_options = ' . print_r($payment_options, true));

        // Promo code allowed
        $promosett = Settings::where('key', 'promo_code')->first();
        if ($promosett->value == 1) {
            $promo_allow = 1;
        } else {
            $promo_allow = 0;
        }
        $settdestination = Settings::where('key', 'get_destination')->first();
        $settdestination = $settdestination->value;
        $title = ucwords( trans('language_changer.add').' '.trans('language_changer.Request')); /* 'Add Request' */
        return View::make('add_request')
                        ->with('owner', $owner)
                        ->with('services', $services)
                        ->with('total_services', $total_services)
                        ->with('payment_option', $payment_options)
                        ->with('settdestination', $settdestination)
                        ->with('title', $title)
                        ->with('page', 'walks');
    }

    //create manual request from admin panel

    public function create_manual_request() {
        $latitude = Input::get('latitude');
        $longitude = Input::get('longitude');
        $d_latitude = Input::get('d_latitude');
        $d_longitude = Input::get('d_longitude');
        $type = Input::get('type');
        $provider = Input::get('provider');
        $user_id = Input::get('owner_id');

        $time = date("Y-m-d H:i:s");

        $provider_details = Walker::where('id', '=', $provider)->first();

        $user = Owner::where('id', '=', $user_id)->first();

        $request = new Requests;
        $request->owner_id = $user_id;
        $request->request_start_time = $time;
        $request->confirmed_walker = $provider;
        if ($d_longitude != '' && $d_latitude != '') {
            $request->D_latitude = $d_latitude;
            $request->D_longitude = $d_longitude;
        }
        $request->current_walker = $provider;
        $request->status = 1;
        $request->save();
        $reqid = $request->id;

        $request_service = new RequestServices;
        $request_service->type = $type;
        $request_service->request_id = $request->id;
        $request_service->save();

        $owner = Owner::find($user_id);
        $owner->latitude = $latitude;
        $owner->longitude = $longitude;
        $owner->save();

        $walkerlocation = new WalkLocation;
        $walkerlocation->request_id = $request->id;
        $walkerlocation->distance = 0.00;
        $walkerlocation->latitude = $latitude;
        $walkerlocation->longitude = $longitude;
        $walkerlocation->save();


        if ($request->save()) {

            $current_request = Requests::where('id', '=', $reqid)->first();
            Session::put('msg', 'A New Request is Created Successfully');
            return Redirect::to('/admin/users');
        }
    }

    public function payment_details() {
        $braintree_environment = Config::get('app.braintree_environment');
        $braintree_merchant_id = Config::get('app.braintree_merchant_id');
        $braintree_public_key = Config::get('app.braintree_public_key');
        $braintree_private_key = Config::get('app.braintree_private_key');
        $braintree_cse = Config::get('app.braintree_cse');
        $twillo_account_sid = Config::get('app.twillo_account_sid');
        $twillo_auth_token = Config::get('app.twillo_auth_token');
        $twillo_number = Config::get('app.twillo_number');
        $stripe_publishable_key = Config::get('app.stripe_publishable_key');
        $default_payment = Config::get('app.default_payment');
        $stripe_secret_key = Config::get('app.stripe_secret_key');
        $mail_driver = Config::get('mail.mail_driver');
        $email_name = Config::get('mail.from.name');
        $email_address = Config::get('mail.from.address');
        $mandrill_secret = Config::get('services.mandrill_secret');
        $install = array(
            'braintree_environment' => $braintree_environment,
            'braintree_merchant_id' => $braintree_merchant_id,
            'braintree_public_key' => $braintree_public_key,
            'braintree_private_key' => $braintree_private_key,
            'braintree_cse' => $braintree_cse,
            'twillo_account_sid' => $twillo_account_sid,
            'twillo_auth_token' => $twillo_auth_token,
            'twillo_number' => $twillo_number,
            'stripe_publishable_key' => $stripe_publishable_key,
            'stripe_secret_key' => $stripe_secret_key,
            'mail_driver' => $mail_driver,
            'email_address' => $email_address,
            'email_name' => $email_name,
            'mandrill_secret' => $mandrill_secret,
            'default_payment' => $default_payment);
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        $submit = Input::get('submit');
        $walker_id = Input::get('walker_id');
        $owner_id = Input::get('owner_id');
        $status = Input::get('status');

        $type1='0'.'&';
        if(Input::has('start_date')){
            $type1.= 'start_date'.'='.$start_date.'&';
        }
        else{
            $type1.= 'start_date'.'='.''.'&';
        }
        if(Input::has('booking_id')){

            $type1.= 'booking_id'.'='.Input::get('booking_id').'&';
        }
        else{
            $type1.= 'booking_id'.'='.''.'&';
        }

        if(Input::has('end_date')){
            $type1.= 'end_date'.'='.$end_date.'&';
        }
        else{
            $type1.= 'end_date'.'='.''.'&';
        }

        if(Input::has('status')){
            $type1.= 'status'.'='.$status.'&';
        }
        else{
            $type1.= 'status'.'='.''.'&';

        }
        if(Input::has('walker_id')){
            $type1.= 'walker_id'.'='.$walker_id.'&';
        }
        else{
            $type1.= 'walker_id'.'='.''.'&';

        }
        if(Input::has('owner_id')){
            $type1.= 'owner_id'.'='.$owner_id.'&';
        }
        else{
            $type1.= 'owner_id'.'='.''.'&';

        }
        if(Input::has('submit')){
            $type1.= 'submit'.'='.$submit;
        }
        else{
            $type1.= 'submit'.'=';

        }


        Session::put('type',$type1);






        $start_time = date("Y-m-d H:i:s", strtotime($start_date));
        $end_time = date("Y-m-d H:i:s", strtotime($end_date));
        $start_date = date("Y-m-d", strtotime($start_date));
        $end_date = date("Y-m-d", strtotime($end_date));

        $query = DB::table('request')
                ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->leftJoin('walker_type', 'walker.type', '=', 'walker_type.id');

        if (Input::get('start_date') && Input::get('end_date')) {
            $query = $query->where('request_start_time', '>=', $start_time)
                    ->where('request_start_time', '<=', $end_time);
        }

        if (Input::get('walker_id') && Input::get('walker_id') != 0) {
            $query = $query->where('request.confirmed_walker', '=', $walker_id);
        }

        if (Input::get('owner_id') && Input::get('owner_id') != 0) {
            $query = $query->where('request.owner_id', '=', $owner_id);
        }

        if (Input::get('status') && Input::get('status') != 0) {
            if ($status == 1) {
                $query = $query->where('request.is_completed', '=', 1);
            } else {
                $query = $query->where('request.is_cancelled', '=', 1);
            }
        } else {

            $query = $query->where(function ($que) {
                $que->where('request.is_completed', '=', 1)
                        ->orWhere('request.is_cancelled', '=', 1);
            });
        }

        /* $walks = $query->select('request.request_start_time', 'walker_type.name as type', 'request.ledger_payment', 'request.card_payment', 'owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'request.id as id', 'request.created_at as date', 'request.*', 'request.is_walker_arrived', 'request.payment_mode', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker'
          , 'request.status', 'request.time', 'request.distance', 'request.total', 'request.is_cancelled');
          $walks = $walks->paginate(10); */


        if(Input::get('submit') && Input::get('submit')=='Download_Report' ){
            $walks = $query->select('request.request_start_time', 'walker_type.name as type', 'request.ledger_payment', 'request.card_payment', 'owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'request.id as id', 'request.created_at as date', 'request.is_started', 'request.is_walker_arrived', 'request.payment_mode', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker', 'request.promo_id', 'request.promo_code'
                , 'request.status', 'request.time', 'request.distance', 'request.total', 'request.is_cancelled', 'request.promo_payment','request.driver_per_payment');
            $walks = $walks->orderBy('id', 'DESC')->get();

        }
        else{
            $walks = $query->select('request.request_start_time', 'walker_type.name as type', 'request.ledger_payment', 'request.card_payment', 'owner.first_name as owner_first_name', 'owner.last_name as owner_last_name', 'walker.first_name as walker_first_name', 'walker.last_name as walker_last_name', 'owner.id as owner_id', 'walker.id as walker_id', 'request.id as id', 'request.created_at as date', 'request.is_started', 'request.is_walker_arrived', 'request.payment_mode', 'request.is_completed', 'request.is_paid', 'request.is_walker_started', 'request.confirmed_walker', 'request.promo_id', 'request.promo_code'
                , 'request.status', 'request.time', 'request.distance', 'request.total', 'request.is_cancelled', 'request.promo_payment','request.driver_per_payment');
            $walks = $walks->orderBy('id', 'DESC')->paginate(10);

        }


        $query = DB::table('request')
                ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->leftJoin('walker_type', 'walker.type', '=', 'walker_type.id');

        if (Input::get('start_date') && Input::get('end_date')) {
            $query = $query->where('request_start_time', '>=', $start_time)
                    ->where('request_start_time', '<=', $end_time);
        }

        if (Input::get('walker_id') && Input::get('walker_id') != 0) {
            $query = $query->where('request.confirmed_walker', '=', $walker_id);
        }

        if (Input::get('owner_id') && Input::get('owner_id') != 0) {
            $query = $query->where('request.owner_id', '=', $owner_id);
        }

        $completed_rides = $query->where('request.is_completed', 1)->count();


        $query = DB::table('request')
                ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->leftJoin('walker_type', 'walker.type', '=', 'walker_type.id');

        if (Input::get('start_date') && Input::get('end_date')) {
            $query = $query->where('request_start_time', '>=', $start_time)
                    ->where('request_start_time', '<=', $end_time);
        }

        if (Input::get('walker_id') && Input::get('walker_id') != 0) {
            $query = $query->where('request.confirmed_walker', '=', $walker_id);
        }

        if (Input::get('owner_id') && Input::get('owner_id') != 0) {
            $query = $query->where('request.owner_id', '=', $owner_id);
        }
        $cancelled_rides = $query->where('request.is_cancelled', 1)->count();


        $query = DB::table('request')
                ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->leftJoin('walker_type', 'walker.type', '=', 'walker_type.id');

        if (Input::get('start_date') && Input::get('end_date')) {
            $query = $query->where('request_start_time', '>=', $start_time)
                    ->where('request_start_time', '<=', $end_time);
        }

        if (Input::get('walker_id') && Input::get('walker_id') != 0) {
            $query = $query->where('request.confirmed_walker', '=', $walker_id);
        }

        if (Input::get('owner_id') && Input::get('owner_id') != 0) {
            $query = $query->where('request.owner_id', '=', $owner_id);
        }
        $card_payment = $query->where('request.is_completed', 1)->sum('request.card_payment');


        $query = DB::table('request')
                ->leftJoin('owner', 'request.owner_id', '=', 'owner.id')
                ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->leftJoin('walker_type', 'walker.type', '=', 'walker_type.id');

        if (Input::get('start_date') && Input::get('end_date')) {
            $query = $query->where('request_start_time', '>=', $start_time)
                    ->where('request_start_time', '<=', $end_time);
        }

        if (Input::get('walker_id') && Input::get('walker_id') != 0) {
            $query = $query->where('request.confirmed_walker', '=', $walker_id);
        }

        if (Input::get('owner_id') && Input::get('owner_id') != 0) {
            $query = $query->where('request.owner_id', '=', $owner_id);
        }
        $credit_payment = $query->where('request.is_completed', 1)->sum('request.ledger_payment');
        $cash_payment = $query->where('request.payment_mode', 1)->sum('request.total');


        if (Input::get('submit') && Input::get('submit') == 'Download_Report') {

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=data.csv');
            $handle = fopen('php://output', 'w');
            $settings = Settings::where('key', 'default_distance_unit')->first();
            $unit = $settings->value;
            if ($unit == 0) {
                $unit_set = 'kms';
            } elseif ($unit == 1) {
                $unit_set = 'miles';
            }
            fputcsv($handle, array('ID', 'Date', 'Type of Service', 'Provider', 'Owner', 'Distance (' . $unit_set . ')', 'Time (Minutes)', 'Payment Mode', 'Earning', 'Referral Bonus', 'Promotional Bonus', 'Card Payment','Driver Percentage','Admin Percentage'));

            foreach ($walks as $request) {
                $pay_mode = "Card Payment";
                if ($request->payment_mode == 1) {
                    $pay_mode = "Cash Payment";
                }

                $AdminPer = "0.00";
                if($request->driver_per_payment != 0.00) {
                    $admin_per_payment = $request->total - $request->driver_per_payment;
                    $AdminPer = $admin_per_payment;
                }

                fputcsv($handle, array(
                    $request->id,
                    date('l, F d Y h:i A', strtotime($request->request_start_time)),
                    $request->type,
                    $request->walker_first_name . " " . $request->walker_last_name,
                    $request->owner_first_name . " " . $request->owner_last_name,
                    sprintf2($request->distance, 2),
                    sprintf2($request->time, 2),
                    $pay_mode,
                    sprintf2($request->total, 2),
                    sprintf2($request->ledger_payment, 2),
                    sprintf2($request->promo_payment, 2),
                    sprintf2($request->card_payment, 2),
                    sprintf2($request->driver_per_payment, 2),
                    sprintf2($AdminPer, 2)
                ));
            }

            fputcsv($handle, array());
            fputcsv($handle, array());
            fputcsv($handle, array('Total Trips', $completed_rides + $cancelled_rides));
            fputcsv($handle, array('Completed Trips', $completed_rides));
            fputcsv($handle, array('Cancelled Trips', $cancelled_rides));
            fputcsv($handle, array('Total Payments', sprintf2(($credit_payment + $card_payment), 2)));
            fputcsv($handle, array('Card Payment', sprintf2($card_payment, 2)));
            fputcsv($handle, array('Credit Payment', $credit_payment));

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
        } else {
            /* $currency_selected = Keywords::where('alias', 'Currency')->first();
              $currency_sel = $currency_selected->keyword; */
            $currency_sel = Config::get('app.generic_keywords.Currency');
            $walkers = Walker::paginate(10);
            $owners = Owner::paginate(10);
            $payment_default = ucfirst(Config::get('app.default_payment'));



            $title = ucwords(trans('language_changer.payment_details')); /* 'Payments' */

            if(Config::get('app.locale') == 'arb'){
                $align_format="right";

                if($payment_default == 'Braintree'){
                    $payment_default='برينتر';
                }elseif ($payment_default == 'Stripe'){

                    $payment_default='شريط';
                }


            }elseif(Config::get('app.locale') == 'en'){
                $align_format="left";



                if($payment_default == 'Braintree'){
                    $payment_default='Braintree';
                }elseif ($payment_default == 'Stripe'){

                    $payment_default='Stripe';
                }
            }

            return View::make('payment')
                            ->with('title', $title)
                            ->with('page', 'payments')
                            ->with('walks', $walks)
                            ->with('owners', $owners)
                            ->with('walkers', $walkers)
                            ->with('completed_rides', $completed_rides)
                            ->with('cancelled_rides', $cancelled_rides)
                            ->with('card_payment', $card_payment)
                            ->with('install', $install)
                            ->with('currency_sel', $currency_sel)
                            ->with('cash_payment', $cash_payment)
                            ->with('align_format',$align_format)
                            ->with('credit_payment', $credit_payment)
                            ->with('payment_default', $payment_default);
        }
    }
	
	
	public function privilege()
	{
		
		$userid=Request::segment(3);

		
		$title = ucwords(trans('language_changer.privilege'));
		$url=DB::table('privilege')
                     ->select(DB::raw('url'))
                     ->where('userid', '=', $userid)
                     ->get();

		 return View::make('privilege')
                            ->with('title', $title)
                            ->with('page', 'privilege')
							->with('userid', $userid)
							->with('urll',$url);
	}
	
	public function addprivilege()
	{
		
		
		$userid=Input::get('userid');
		$url=Input::get('url');
		if(DB::table('privilege')->where('userid', $userid)->where('url', $url)->first())
		{
			DB::table('privilege')->where('userid', $userid)->where('url', $url)->delete();
			echo 'false';
		}
		else
		{
			DB::table('privilege')->insert(
    array('userid' => $userid, 'url' => $url)
);
			echo 'true';
			
		}
	}
	
	public function get_all_users() {
        Session::forget('type');
        Session::forget('valu');
        $admin = Admin::paginate(10);
        $title = ucwords(trans('language_changer.privilege')); /* 'Promo Codes' */
        return View::make('listuser')
                        ->with('title', $title)
                        ->with('page', 'privilege')
                        ->with('admin', $admin);
    }


    //zone division

    public function zonedivision()
    {
        $title = ucwords(trans('language_changer.zonedivision'));
        $walkerTypes = DB::table('walker_type')->get();
        return View::make('zonedivision.zone_add')
            ->with('title', $title)
            ->with('walkerTypes', $walkerTypes)
            ->with('page', 'zonedivision');

    }

    public function zone_division_view()
    {
        $id = Request::segment(3);
        $title = ucwords(trans('language_changer.zonedivision'));
        $walkerTypes = DB::table('walker_type')->get();
        return View::make('zonedivision.zone_division_view')
            ->with('title', $title)
            ->with('walkerTypes', $walkerTypes)
            ->with('zone_id', $id)
            ->with('page', 'zonedivision');

    }


    public function zone_map_view()
    {

        if (Request::ajax()) {

            $data = Input::all();
            $zoneId = $data['zoneId'];

            $zoneRecs = DB::table('zone as z')
                ->where('id', '=', $zoneId)
                ->select('z.*')
                ->whereNull('z.deleted_at')
                ->get();

            $response_code = 200;
            $response_array = array('success' => true, 'zone' => $zoneRecs[0]->zone_json);
            $response = Response::json($response_array, $response_code);
            return $response;
        }

    }


    public function zone_division_add()
    {
        $zone_name = Input::get('zone_name');
        $typeid = Input::get('typeid');
        $service_base_price = Input::get('service_base_price');
        $service_price_distance = Input::get('service_price_distance');
        $service_price_time = Input::get('service_price_time');
        $service_max_size = Input::get('service_max_size');
        $service_base_distance = Input::get('service_base_distance');
        $zone_json = Input::get('zone_json');

        $insert = array();
        $id = DB::table('zone')->insertGetId(array('zone_name' => $zone_name, 'zone_json' => $zone_json));
        for ($i = 0; $i < count($typeid); $i++)
        {
            if (Input::has('visible_' . $typeid[$i])) {
                $is_visible = 1;
            } else {
                $is_visible = 0;
            }
            $data = array('price_per_unit_distance' => $service_price_distance[$i],
                'type' => $typeid[$i],
                'price_per_unit_time' => $service_price_time[$i],
                'base_price' => $service_base_price[$i],
                'max_size' => $service_max_size[$i],
                'base_distance' => $service_base_distance[$i],
                'is_visible' => $is_visible,
                'zone_id' => $id
            );
            array_push($insert, $data);
        }
        DB::table('zone_type')->insert($insert);
        $response_code = 200;
        $response_array = array('success' => true,'type'=>'new');
        $response = Response::json($response_array, $response_code);
        return $response;
    }


    public function zone_division_edit_view()
    {
            $title = ucwords(trans('language_changer.zonedivision'));
            $select="z.id as zone_id,z.zone_name,GROUP_CONCAT(zt.price_per_unit_time) as price_per_unit_time,GROUP_CONCAT(zt.price_per_unit_distance) as price_per_unit_distance, GROUP_CONCAT(zt.base_price) as base_price ,GROUP_CONCAT(zt.base_distance) as base_distance, GROUP_CONCAT(zt.max_size) as max_size , GROUP_CONCAT(zt.is_visible) as is_visible,GROUP_CONCAT(w.name) as type_name,GROUP_CONCAT(w.id) as type_id";
        $zone = DB::table('zone as z')->join('zone_type as zt', 'z.id', '=', 'zt.zone_id')->join('walker_type as w', 'w.id', '=', 'zt.type')->select(DB::raw($select))->where("z.id", "=", Input::segment(3))->groupBy('zt.zone_id')->first();
        return View::make('zonedivision.zone_edit')
            ->with('title', $title)
            ->with('zone', $zone)
            ->with('page', 'zonedivision');

    }

    public function zone_division_edit()
    {
        $zone_name = Input::get('zone_name');
        $typeid = Input::get('typeid');
        $service_base_price = Input::get('service_base_price');
        $service_price_distance = Input::get('service_price_distance');
        $service_price_time = Input::get('service_price_time');
        $service_max_size = Input::get('service_max_size');
        $service_base_distance = Input::get('service_base_distance');
        $zone_id= Input::get('zone_id');
        DB::table('zone')->where('id', '=',$zone_id)->update(array('zone_name' => $zone_name));
        for ($i = 0; $i < count($typeid); $i++)
        {
            if (Input::has('visible_' . $typeid[$i])) {
                $is_visible = 1;
            } else {
                $is_visible = 0;
            }
            $data = array('price_per_unit_distance' => $service_price_distance[$i],
                'type' => $typeid[$i],
                'price_per_unit_time' => $service_price_time[$i],
                'base_price' => $service_base_price[$i],
                'max_size' => $service_max_size[$i],
                'base_distance' => $service_base_distance[$i],
                'is_visible' => $is_visible
            );
            $upate = DB::table('zone_type')->where('zone_id', '=', $zone_id)->where('type', '=', $typeid[$i])->update($data);
        }
        $response_code = 200;
        $response_array = array('success' => true,'type'=>'edit');
        $response = Response::json($response_array, $response_code);
        return $response;

    }

    public function zonedivisions()
    {

        $zoneTypeRecords = DB::table('zone_type as zt')
            ->leftJoin('zone as z', 'zt.zone_id', '=', 'z.id')
            ->select('zt.*', 'z.*')
            ->whereNull('zt.deleted_at')
            ->get();

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }

        /*echo "<pre>";
        print_r($zoneTypeRecords);
        exit;*/
        $title = ucwords(Lang::get('language_changer.zonedivision')); /* 'Category' */
        return View::make('zonedivision.zonedivisions')
            ->with('title', $title)
            ->with('page', 'zonedivision')
            ->with('align_format',$align_format)
            ->with('zoneTypeRecords', $zoneTypeRecords);
    }

    public function zone_division_delete()
    {


        $id = Request::segment(3);
        $zone = Zone::where('id', $id)->delete();
        $zoneType = ZoneType::where('zone_id', $id)->delete();

        if ($zone) {
            return Redirect::to("/admin/zonedivisions");
        }

    }

    public function user_register()
    {

        $countryCodes = DB::table('country_code')
            ->get();


        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }



        $owners = Owner::orderBy('id', 'DESC')->paginate(10);

        $title = ucwords(Lang::get('language_changer.User') . ' ' . Lang::get('language_changer.register')); /* 'Category' */
        return View::make('register.user_register')
            ->with('title', $title)
            ->with('page', 'user_register')
            ->with('align_format',$align_format)
            ->with('countryCodes', $countryCodes)
            ->with('owners', $owners);
    }



    public function user_register_save()
    {


        $first_name = Input::get('first_name');
        $last_name = Input::get('last_name');
        $email = Input::get('email');
        $password = Input::get('password');
        $confirm_password = Input::get('confirm_password');
        $phone = Input::get('phone');
        $country_code = Input::get('country_code');
        $image = Input::file('image');
        $phone_no = "+" . $country_code . "" . $phone;


        $validator = Validator::make(
            array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,     // required and must be unique in the ducks table
                'phone' => $phone_no,
                'country_code' => $country_code,
                'image' => $image,
                'password' => $password,
                'confirm_password' => $confirm_password
            ),
            array(
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:owner',     // required and must be unique in the ducks table
                'phone' => 'required|phone|unique:owner',
                'country_code' => 'required',
                'image' => 'required | mimes:jpeg,jpg,png',
                'password' => 'required',
                'confirm_password' => 'required|same:password'

            )


        );


        if ($validator->fails()) {


            return Redirect::to('/admin/user_register')->withInput()->withErrors($validator);

        } else {

            if (Input::hasFile('image')) {
                // Upload File
                $file_name = time();
                $file_name .= rand();
                $ext = Input::file('image')->getClientOriginalExtension();
                Input::file('image')->move(public_path() . "/uploads", $file_name . "." . $ext);
                $local_url = $file_name . "." . $ext;

                // Upload to S3
                if (Config::get('app.s3_bucket') != "") {
                    $s3 = App::make('aws')->get('s3');
                    $pic = $s3->putObject(array(
                        'Bucket' => Config::get('app.s3_bucket'),
                        'Key' => $file_name,
                        'SourceFile' => public_path() . "/uploads/" . $local_url,
                    ));

                    $s3->putObjectAcl(array(
                        'Bucket' => Config::get('app.s3_bucket'),
                        'Key' => $file_name,
                        'ACL' => 'public-read'
                    ));

                    $s3_url = $s3->getObjectUrl(Config::get('app.s3_bucket'), $file_name);
                } else {
                    $s3_url = asset_url() . '/uploads/' . $local_url;
                }

            }


            $owner = new Owner();
            $owner->first_name = $first_name;
            $owner->last_name = $last_name;
            $owner->email = $email;
            $owner->phone = $phone_no;
            $owner->picture = (!empty($s3_url) ? $s3_url : '');
            $owner->password = Hash::make($password);
            $owner->is_otp = 1;
            $owner->is_referee = 1;
            $owner->save();
            regenerate:
            $referral_code = my_random4_number();
            if (Ledger::where('referral_code', $referral_code)->count()) {
                goto regenerate;
            }

            $ledger = new Ledger;
            $ledger->owner_id = $owner->id;
            $ledger->referral_code = $referral_code;
            $ledger->save();

        }
        $owner = Owner::where('email', $email)->first();
        $settings = Settings::where('key', 'admin_email_address')->first();
        $admin_email = $settings->value;
        $pattern = array('admin_eamil' => $admin_email, 'name' => ucwords($owner->first_name . " " . $owner->last_name), 'web_url' => web_url());
        $subject = "Welcome to " . ucwords(Config::get('app.website_title')) . ", " . ucwords($owner->first_name . " " . $owner->last_name) . "";
        email_notification($owner->id, 'owner', $pattern, $subject, 'user_register', null);

        Session::flash('message', 'User Successfully created!');
        return Redirect::to("/admin/users");

    }

        public function user_register_save_old()
    {

        $data = Input::all();
        $first_name = Input::get('first_name');
        $last_name = Input::get('last_name');
        $email = Input::get('email');
        $password = Input::get('password');
        $confirm_password = Input::get('confirm_password');
        $phone = Input::get('phone');
        $country_code = Input::get('country_code');
        //$image = Input::get('image');
        $phone_no = "+" . $country_code . "" . $phone;

        $rules = array(
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:owner',     // required and must be unique in the ducks table
            'phone' => 'required',
            'country_code' => 'required',
            //'image'   => 'required | mimes:jpeg,jpg,png',
            'password' => 'required',
            'confirm_password' => 'required|same:password'

        );

        $validator = Validator::make(Input::all(), $rules);


        if ($validator->fails()) {
            /* $error_messages = $validator->messages()->all();
             $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
             $response_code = 200;*/

            /*$error_messages = $validator->messages()->all();
            echo "<pre>";
            print_r($error_messages);
            exit;*/

            return Redirect::to('/admin/user_register')->withInput()->withErrors($validator);

        } else {

            /*if (Input::hasFile('image')) {
                // Upload File
                $file_name = time();
                $file_name .= rand();
                $ext = Input::file('image')->getClientOriginalExtension();
                Input::file('image')->move(public_path() . "/uploads", $file_name . "." . $ext);
                $local_url = $file_name . "." . $ext;

                // Upload to S3
                if (Config::get('app.s3_bucket') != "") {
                    $s3 = App::make('aws')->get('s3');
                    $pic = $s3->putObject(array(
                        'Bucket' => Config::get('app.s3_bucket'),
                        'Key' => $file_name,
                        'SourceFile' => public_path() . "/uploads/" . $local_url,
                    ));

                    $s3->putObjectAcl(array(
                        'Bucket' => Config::get('app.s3_bucket'),
                        'Key' => $file_name,
                        'ACL' => 'public-read'
                    ));

                    $s3_url = $s3->getObjectUrl(Config::get('app.s3_bucket'), $file_name);
                } else {
                    $s3_url = asset_url() . '/uploads/' . $local_url;
                }

            }*/


            $owner = new Owner();
            $owner->first_name = $first_name;
            $owner->last_name = $last_name;
            $owner->email = $email;
            $owner->phone = $phone_no;
           // $owner->picture = (!empty($s3_url) ? $s3_url : '');
            $owner->password = Hash::make($password);
            $owner->save();

            //$response_array = array('success' => true);
            //$response_code = 200;

        }

        //$response = Response::json($response_array, $response_code);
        //return $response;
        Session::flash('message', 'Owner Successfully created!');
        return Redirect::to("/admin/users");

    }


    public function provider_register()
    {

        $countryCodes = DB::table('country_code')
            ->get();

        $walkerTypes = DB::table('walker_type')
            ->where('is_visible', '=', 1)
            ->get();


        $title = ucwords(Lang::get('language_changer.Provider') . ' ' . Lang::get('language_changer.register')); /* 'Category' */
        return View::make('register.provider_register')
            ->with('title', $title)
            ->with('page', 'provider_register')
            ->with('walkerTypes', $walkerTypes)
            ->with('countryCodes', $countryCodes);

    }
    public function provider_register_save()
    {

        $first_name = Input::get('first_name');
        $last_name = Input::get('last_name');
        $email = Input::get('email');
        $password = Input::get('password');
        $confirm_password = Input::get('confirm_password');

        $phone = Input::get('phone');
        $country_code = Input::get('country_code');
        $phone_no = "+" . $country_code . "" . $phone;
        $address = Input::get('address');
        $bio = Input::get('bio');
        $zipcode = Input::get('zipcode');
        $car_number = Input::get('car_number');
        $car_model = Input::get('car_model');
        $image = Input::file('image');
        $type = Input::get('type');


        $validator = Validator::make(
            array(
                'first_name' =>$first_name ,
                'last_name' => $last_name,
                'email' => $email,     // required and must be unique in the ducks table
                'phone' =>$phone_no ,
                'country_code' => $country_code,
                'address' =>$address,
                'bio' => $bio,
                'zipcode' => $zipcode,
                'car_number' => $car_number,
                'car_model' => $car_model,
                'type' => $type,
                'image'   => $image,
                'password' => $password,
                'confirm_password' =>$confirm_password

            ),array(
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:walker',     // required and must be unique in the ducks table
                'phone' => 'required|phone|unique:walker',
                'country_code' => 'required',
                'address' => 'required',
                'bio' => 'required',
                'zipcode' => 'required',
                'car_number' => 'required',
                'car_model' => 'required',
                'type' => 'required',
                'image'   => 'required | mimes:jpeg,jpg,png',
                'password' => 'required',
                'confirm_password' => 'required|same:password'
            )



        );


        if ($validator->fails()) {

            return Redirect::to('/admin/provider_register')->withInput()->withErrors($validator);

        } else {

            if (Input::hasFile('image')) {
                // Upload File
                $file_name = time();
                $file_name .= rand();
                $ext = Input::file('image')->getClientOriginalExtension();
                Input::file('image')->move(public_path() . "/uploads", $file_name . "." . $ext);
                $local_url = $file_name . "." . $ext;

                // Upload to S3
                if (Config::get('app.s3_bucket') != "") {
                    $s3 = App::make('aws')->get('s3');
                    $pic = $s3->putObject(array(
                        'Bucket' => Config::get('app.s3_bucket'),
                        'Key' => $file_name,
                        'SourceFile' => public_path() . "/uploads/" . $local_url,
                    ));

                    $s3->putObjectAcl(array(
                        'Bucket' => Config::get('app.s3_bucket'),
                        'Key' => $file_name,
                        'ACL' => 'public-read'
                    ));

                    $s3_url = $s3->getObjectUrl(Config::get('app.s3_bucket'), $file_name);
                } else {
                    $s3_url = asset_url() . '/uploads/' . $local_url;
                }

            }

            $walker = new Walker();
            $walker->first_name = $first_name;
            $walker->last_name = $last_name;
            $walker->email = $email;
            $walker->type = $type;
            $walker->phone = $phone_no;

            $walker->address = $address;
            $walker->bio = $bio;
            $walker->zipcode = $zipcode;
            $walker->car_number = $car_number;
            $walker->car_model = $car_model;
            $walker->picture = (!empty($s3_url) ? $s3_url : '');
            $walker->password = Hash::make($password);
            $walker->save();

        }

        Session::flash('message', 'Driver Successfully created!');


        $walker=Walker::where('email',$email)->first();

        $settings = Settings::where('key', 'admin_email_address')->first();
        $admin_email = $settings->value;
        $pattern = array('admin_eamil' => $admin_email, 'name' => ucwords($walker->first_name . " " . $walker->last_name), 'web_url' => web_url());
        $subject = "Welcome to " . ucwords(Config::get('app.website_title')) . ", " . ucwords($walker->first_name . " " . $walker->last_name) . "";
        email_notification($walker->id, 'walker', $pattern, $subject, 'walker_register', null);




        return Redirect::to("/admin/providers");
    }


    public function zonepopup(){

        $title = ucwords(trans('language_changer.zonedivision'));
        return View::make('zonedivision.zonepopup')
            ->with('title', $title)
            ->with('page', 'Zone Division Popup');

    }

    public function driver_earnings() {

        $startDate = Input::get('start_date'); // 2017-03-21
        $endDate = Input::get('end_date'); //2017-03-11
        $dateDiff = dateDiff($startDate, $endDate);
        $fromDate = $toDate ='';
        if(!empty($startDate) && !empty($endDate)) {
            $fromDate = date("Y-m-d", strtotime($startDate));
            $toDate = date("Y-m-d", strtotime($endDate));
        }

        if($dateDiff == 0) {

            $requestDatas = DB::table('request')
                ->where('request.is_completed', 1)
                ->where('request.is_paid', 1)
                ->where('request_start_time', 'like', '%' .$fromDate. '%' )
                ->Join('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->select('walker.first_name', 'walker.last_name', 'request.confirmed_walker', 'request.id', 'request.request_start_time', 'request.is_completed', 'request.is_paid', 'request.driver_per_payment', 'request.total')
                ->groupBy('request.id')
                ->get();

        } else {

            $query = DB::table('request')
                ->where('request.is_completed', 1)
                ->where('request.is_paid', 1)
                ->Join('walker', 'request.confirmed_walker', '=', 'walker.id')
                ->select('walker.first_name', 'walker.last_name', 'request.confirmed_walker', 'request.id', 'request.request_start_time', 'request.is_completed', 'request.is_paid', 'request.driver_per_payment', 'request.total')
                ->groupBy('request.id');
            if (!empty($fromDate) && !empty($toDate)) {
                $query->where('request_start_time', '>=', $fromDate);
                $query->where('request_start_time', '<=', $toDate);
            }
            $requestDatas = $query->get();

        }

        //$queries = DB::getQueryLog();
        //$last_query = end($queries);

        //echo "<pre>";
        //print_r($last_query);

        
        $requests = array();

        foreach($requestDatas as $key => $requestData) {

            if (!in_array($requestData->confirmed_walker, $requests , true))  {
                $requests['id'][$requestData->confirmed_walker] = $requestData->confirmed_walker;
                $requests['name'][$requestData->confirmed_walker] = $requestData->first_name." ".$requestData->last_name;
            }
            if(!isset($requests['total'][$requestData->confirmed_walker])) {
                $requests['total'][$requestData->confirmed_walker] = 0;
            }
            if(!isset($requests['count'][$requestData->confirmed_walker])) {
                $requests['count'][$requestData->confirmed_walker] = 0;
            }
            $requests['count'][$requestData->confirmed_walker]++;
            $requests['total'][$requestData->confirmed_walker] += $requestData->driver_per_payment;

        }

        $walkers = array();
        if(!empty($requests)) {
            $a1 = $requests['id'];
            $a2 = $requests['name'];
            $a3 = $requests['total'];
            $a4 = $requests['count'];
            $walkers = array_map(null,$a1,$a2,$a3,$a4);
        }

        $title = ucwords(trans('language_changer.driver_earnings'));

        return View::make('driver_earnings')
            ->with('title', $title)
            ->with('walkers', $walkers)
            ->with('page', 'walkers');

    }


    public function get_peak_types() {

        $settings = Settings::where('key', 'default_distance_unit')->first();
        $unit = $settings->value;
        if ($unit == 0) {
            $unit_set = 'kms';
        } elseif ($unit == 1) {
            $unit_set = 'miles';
        }
        $types = ProviderType::paginate(10);
        $title = ucwords(trans('language_changer.Provider') . " " . trans('language_changer.Types')); /* 'Provider Types' */
        return View::make('list_peak_types')
            ->with('title', $title)
            ->with('page', 'peak-type')
            ->with('unit_set', $unit_set)
            ->with('types', $types);
    }


    public function peak_edit_view()
    {
        $title = ucwords(trans('customize.peak_hours'));

        $type_id =  Request::segment(3);

        $peakTypes = DB::table('peak_type as pt')
            ->select('pt.*')
            ->where('type_id', '=',$type_id)
            ->get();

        /* echo "<pre>";
        print_r($peakTypes);
        exit; */
        return View::make('peak_edit')
            ->with('title', $title)
            ->with('type_id', $type_id)
            ->with('peakTypes', $peakTypes)
            ->with('page', 'Peak hours Edit');

    }

    public function peak_edit()
    {

        $type_id= Input::get('type_id');
        $base_price = Input::get('base_price');
        $start_time = Input::get('start_time');
        $end_time = Input::get('end_time');
        $base_distance = Input::get('base_distance');
        $price_per_unit_time = Input::get('price_per_unit_time');

        PeakType::where('type_id', $type_id)->delete();

        $insert = array();
        for ($i = 0; $i < count($base_price); $i++)
        {
            $data = array(
                'type_id' => $type_id,
                'base_price' => $base_price[$i],
                'base_distance' => $base_distance[$i],
                'price_per_unit_time' => $price_per_unit_time[$i],
                'start_time' => $start_time[$i],
                'end_time' => $end_time[$i],
            );
            array_push($insert, $data);
        }

        DB::table('peak_type')->insert($insert);
        Session::flash('message', 'Peak Type Successfully Updated!');

        return Redirect::to("/admin/peak-types");

    }

    public function document_image_edit() {

        $imageUploadName = Input::get('image_upload_name');
        $walkerId = Input::get('walker_id');

        $title = trans('language_changer.edit').' '.trans('language_changer.document').' '.trans('language_changer.image');

        return View::make('document_image_edit')
            ->with('title', $title)
            ->with('page', 'document-type')
            ->with('walkerId', $walkerId)
            ->with('imageUploadName', $imageUploadName)
            ->with('walkerId', $walkerId);
    }


    public function document_image_update() {

        $image_upload_name = Input::get('image_upload_name');
        $walkerId = Input::get('walker_id');

        $rules = array(
            $image_upload_name => 'required | mimes:jpeg,jpg,png',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

           /* echo "<pre>";
            print_r($validator->errors());
            exit; */

            return Redirect::to("/admin/document_image_edit?image_upload_name=$image_upload_name&walker_id=$walkerId")->withInput()->withErrors($validator);

        } else {

             $imageUrl = commonImageUploadReturnPath($image_upload_name);
             DB::table('walker')->where('id', $walkerId)->update([$image_upload_name => $imageUrl]);

       }

        Session::flash('message', 'Document Image Upload has been successfully updated!');

        return Redirect::to("/admin/document-types");

    }


    public function edit_image_type(){

        $title = trans('language_changer.edit').' '.trans('language_changer.document').' '.trans('language_changer.image');
        $id = 1;
        $success = 1;
        $walkerId = 328;
        $name = "rangasa";

        return View::make('edit_image_type')
            ->with('title', $title)
            ->with('success', $success)
            ->with('page', 'document-type')
            ->with('walkerId', $walkerId)
            ->with('name', $name)
           // ->with('imageUploadName', $imageUploadName)
            ->with('walkerId', $walkerId);

    }


    public function update_image_type(){

        $name = Input::get('name');
        $image = Input::get('image');

        $rules = array(
            'name'   => 'required',
            'image'   => 'required | mimes:jpeg,jpg,png',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            /* echo "<pre>";
            print_r($validator->errors());
            exit; */

            return Redirect::to('/admin/document-image/edit/0')->withInput()->withErrors($validator);

        } else {


        }
    }


    public function wallet_information()
    {
        if(!DB::table('settings')->where('key','wallet_limit_per_user')->first())
        {
            DB::table('settings')->insert(
                ['key' => 'wallet_limit_per_user', 'value' => 0,'tool_tip' => 'Wallet Limit per user','page' => 111]
            );
        }
        if(!DB::table('settings')->where('key','wallet_limit_per_add')->first())
        {
            DB::table('settings')->insert(
                ['key' => 'wallet_limit_per_add', 'value' => 0,'tool_tip' => 'Wallet Limit per Add','page' => 111]
            );
        }
        if(!DB::table('settings')->where('key','trip_mini_wallet_amount')->first())
        {
            DB::table('settings')->insert(
                ['key' => 'trip_mini_wallet_amount', 'value' => 0,'tool_tip' => 'Trip Mini Wallet Amount','page' => 111]
            );
        }

        $title = ucwords(trans('language_changer.driver_earnings'));
        //total wallet amount
        $wallet_total=DB::table('wallet_user_state')->sum('total_amount');

        //total wallet spend
        $wallet_spend=DB::table('wallet_user_state')->sum('total_spend');

        //total wallet balance
        $wallet_balance=DB::table('wallet_user_state')->sum('total_balance');

        //wallet limit per user
        $wallet_limit=DB::table('settings')->where('key','wallet_limit_per_user')->first()->value;

        //trip mini wallet amount
        $trip_limit=DB::table('settings')->where('key','trip_mini_wallet_amount')->first()->value;

        $payment=Config::get('app.default_payment');


        return View::make('wallet.wallet-information')
            ->with('title', $title)
            ->with('wallet_total', $wallet_total)
            ->with('wallet_spend', $wallet_spend)
            ->with('wallet_balance', $wallet_balance)
            ->with('wallet_limit', $wallet_limit)
            ->with('trip_limit', $trip_limit)
            ->with('payment',$payment)
            ->with('wallet_limit_per_user',Settings::where('key', 'wallet_limit_per_user')->first()->value)
            ->with('wallet_limit_per_add',Settings::where('key', 'wallet_limit_per_add')->first()->value)
            ->with('page', 'Wallet Information');
    }


    public function update_wallet_information()
    {
        $wallet_limit_per_user=Input::get('wallet_limit_per_user');
        $wallet_limit_per_add=Input::get('wallet_limit_per_add');
        $wallet_limit_per_trip=Input::get('wallet_limit_per_trip');


        $rules = array(
            'wallet_limit_per_user' => 'required|numeric',
            'wallet_limit_per_add' => 'required|numeric',

        );


        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('admin/wallet/information')->withErrors($validator);

        } else {
            $setting=Settings::where('key', 'wallet_limit_per_user')->first();
            $setting->value=$wallet_limit_per_user;
            $setting->save();
            $setting=Settings::where('key', 'wallet_limit_per_add')->first();
            $setting->value=$wallet_limit_per_add;
            $setting->save();
            $setting=Settings::where('key', 'trip_mini_wallet_amount')->first();
            $setting->value=$wallet_limit_per_trip;
            $setting->save();

            Session::flash('message', 'Update Successfully');
            return Redirect::to("admin/wallet/information");

        }

    }


    public function user_wallet_information()
    {
        $user_id=Input::get('user_id');
        $tab=Input::get('tab')?Input::get('tab'):1;
        if((DB::table('wallet_user_state')->where('user_id',$user_id)->first()))
        {
            $wallet_status=WalletStatus::where('user_id',$user_id);
            $wallet_total=$wallet_status->total_amount;
            $wallet_spend=$wallet_status->total_spend;
            $wallet_balance=$wallet_status->total_balance;
        }
        else{

            $wallet_total=0;
            $wallet_spend=0;
            $wallet_balance=0;

        }
        if(Input::get('submit') == 'Filter_Data' && Input::get('start_date') != '' && Input::get('end_date') != '')
        {

            $wallet_add=DB::table('wallet_add_history')
                ->whereBetween('created_at', [date('Y-m-d',strtotime(Input::get('start_date'))), date('Y-m-d',strtotime(Input::get('end_date')))])
                ->where('user_id',$user_id)
                ->paginate(10);
            $wallet_spend_h=DB::table('wallet_spend_history')
                ->whereBetween('created_at', [date('Y-m-d',strtotime(Input::get('start_date'))), date('Y-m-d',strtotime(Input::get('end_date')))])
                ->where('user_id',$user_id)
                ->paginate(10);
        }
        else if(Input::get('submit') == 'Download_Report' && Input::get('start_date') != '' && Input::get('end_date') != '') {
            $wallet_add = DB::table('wallet_add_history')
                ->whereBetween('created_at', [date('Y-m-d', strtotime(Input::get('start_date'))), date('Y-m-d', strtotime(Input::get('end_date')))])
                ->where('user_id', $user_id)
                ->get();
            $wallet_spend_h = DB::table('wallet_spend_history')
                ->whereBetween('created_at', [date('Y-m-d', strtotime(Input::get('start_date'))), date('Y-m-d', strtotime(Input::get('end_date')))])
                ->where('user_id', $user_id)
                ->get();

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=data.csv');
            $handle = fopen('php://output', 'w');

            if ($tab == 1) {
                fputcsv($handle, array('ADDED ID', 'Date', 'Time', 'Amount'));
                foreach ($wallet_add as $wallet) {
                    fputcsv($handle, array(
                        $wallet->wallet_added_id,
                        date('m/d/Y', strtotime($wallet->created_at)),
                        date('h:i:s a', strtotime($wallet->created_at)),
                        $wallet->amount
                    ));
                }
            } else {
                fputcsv($handle, array('Spend ID', 'Date', 'Time', 'Amount'));
                foreach ($wallet_spend_h as $wallet) {
                    fputcsv($handle, array(
                        $wallet->wallet_spend_id,
                        date('m/d/Y', strtotime($wallet->created_at)),
                        date('h:i:s a', strtotime($wallet->created_at)),
                        $wallet->amount
                    ));
                }
            }
            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
        }
        else{
            $wallet_add=DB::table('wallet_add_history')
                ->where('user_id',$user_id)
                ->paginate(10);
            $wallet_spend_h=DB::table('wallet_spend_history')
                ->where('user_id',$user_id)
                ->paginate(10);
        }


        if(Input::get('submit') != 'Download_Report')
        {
            $title = ucwords(trans('language_changer.driver_earnings'));

            return View::make('wallet.user_wallet_information')
                ->with('title', $title)
                ->with('wallet_total', $wallet_total)
                ->with('wallet_balance', $wallet_balance)
                ->with('wallet_add', $wallet_add)
                ->with('wallet_spend_h', $wallet_spend_h)
                ->with('wallet_spend', $wallet_spend)
                ->with('user_id',$user_id)
                ->with('tab',$tab)
                ->with('page', 'Wallet User Information');
        }

    }


    public function package_installer()
    {



        $title = ucwords(trans('language_changer.driver_earnings'));
        return View::make('package.package_installer')
            ->with('title', $title)
            ->with('page', 'Wallet User Information');

    }

    public function package_installer1()
    {


        $file_name = time();
        $file_name .= rand();
        $file_name = sha1($file_name);
        if (Input::file('zip_file')) {
            /* $ext = Input::file('zip_file')->getClientOriginalExtension();
               Input::file('zip_file')->move(public_path() . "/uploads", $file_name . "." . $ext);
               $local_url = $file_name . "." . $ext;
                   $s3_url = asset_url() . '/uploads/' . $local_url;*/


            $za = new ZipArchive();
            //die(public_path() . "/uploads/".$file_name . "." . $ext);

            $za->open('/opt/lampp/htdocs/Taxiappz/public/uploads/a5b68bddaf0c804a19d6229c1812a821af10b23b.zip');


            for ($i=0; $i<$za->numFiles;$i++) {
                print_r($za->statIndex($i)['name']);


                $file_status=explode('/',$za->statIndex($i)['name']);

                echo   count($file_status)."<br>";


                if(count($file_status) != 2)
                {
                    if($file_status[0] == 'app')
                    {


                        if(end($file_status) == '')
                        {
                            unset($file_status[0]);
                            $index=array_search('', $file_status);
                            unset($file_status[$index]);

                            $filename= implode('/',$file_status);
                            $final=app_path().'/'.$filename;
                            //echo $final;die();
                            if(file_exists(trim($final)))
                            {
                                echo "true<br>";

                            }
                            else
                            {
                                mkdir($final);

                            }
                        }
                        else
                        {
                            unset($file_status[0]);
                            $check_name=app_path().'/'.implode('/',$file_status);
                            $custom_filename=end($file_status);
                            $index=array_search($custom_filename, $file_status);
                            unset($file_status[$index]);
                            $app=app_path();
                            $final=str_replace('/app','/',$app);
                            if(file_exists(trim($check_name)))
                            {
                                echo "true<br>";

                            }
                            else
                            {
                                $za->extractTo($final, $za->statIndex($i)['name']);


                            }
                        }



                    }
                    else
                    {
                        if(end($file_status) == '')
                        {
                            unset($file_status[0]);
                            $index=array_search('', $file_status);
                            unset($file_status[$index]);

                            $filename= implode('/',$file_status);
                            $final=public_path().'/'.$filename;
                            //echo $final;die();
                            if(file_exists(trim($final)))
                            {
                                echo "true<br>";

                            }
                            else
                            {
                                mkdir($final);

                            }
                        }
                        else
                        {
                            unset($file_status[0]);
                            $check_name=public_path().'/'.implode('/',$file_status);
                            $custom_filename=end($file_status);
                            $index=array_search($custom_filename, $file_status);
                            unset($file_status[$index]);
                            $app=public_path();
                            $final=str_replace('/public','/',$app);
                            if(file_exists(trim($check_name)))
                            {
                                echo "true<br>";

                            }
                            else
                            {
                                $za->extractTo($final, $za->statIndex($i)['name']);


                            }
                        }
                    }

                }
            }
            //echo "numFile:" . $za->numFiles . "\n";

            $title = ucwords(trans('language_changer.driver_earnings'));
            return View::make('package.package_installer')
                ->with('title', $title)
                ->with('page', 'Wallet User Information');
        }


    }

     public function bankAccounts()
    {
        $id = Request::segment(3);
        /* $bankAccounts = DB::table('walker_banking_details')
            ->select('walker_banking_details.*')
            ->where('walker_banking_details.walker_id','=', $id)
            ->whereNull('walker_banking_details.deleted_at')
            ->orderBy('walker_banking_details.created_at', 'DESC')
            ->paginate(10); */

        $bankAccounts = DB::table('walker_banking_details as wb')
            ->select('wb.*')
            ->where('wb.walker_id','=', $id)
            ->whereNull('wb.deleted_at')
            ->orderBy('wb.created_at', 'DESC')
            ->get();

        //echo "<pre>";
        //print_r($bankAccounts); exit;

        $title = ucwords(trans('customize.bank_accounts') . 's');
        return View::make('bankAccounts.bankaccounts')
            ->with('title', $title)
            ->with('page', 'bankaccounts')
            ->with('walker_id', $id)
            ->with('bankAccounts', $bankAccounts);

    }

    public function edit_bank_provider() {
       $id = Request::segment(3);
        $success = Input::get('success');

        $walkerBankDetails = DB::table('walker_banking_details as wb')
            ->select('wb.*')
            ->where('wb.id','=', $id)
            ->whereNull('wb.deleted_at')
            ->first();

        //echo "<pre>";
        //print_r($walkerBankDetails); exit;

        $title = ucwords(trans('language_changer.banking').' '.trans('language_changer.details') . " : " );
        return View::make('bankAccounts.edit_bank_provider')
                ->with('title', $title)
                ->with('page', 'walkers')
                ->with('success', $success)
                ->with('walkerBankDetails', $walkerBankDetails);

    }

    public function save_bank_provider() {

        $id = Input::get('id');
        $isDefault = WalkerBankingDetails::where('walker_id', '=', Input::get('id'))->where('is_default', '=', 1)->get();
        $dob = Input::get('dob');
        $dob = date("Y-m-d", strtotime($dob));
        $ssn = Input::get('ssn');
        $account_number = Input::get('accountNumber');
        $routing_number = Input::get('routingNumber');
        $is_default = Input::get('is_default');

        $bankingDetails = WalkerBankingDetails::where('id','=', $id)->first();
        $bankingDetails->walker_id = Input::get('id');
        $bankingDetails->dob = $dob;
        $bankingDetails->ssn = (!empty($ssn) ? $ssn : '');
        $bankingDetails->account_number = (!empty($account_number) ? $account_number : '');
        $bankingDetails->routing_number = (!empty($routing_number) ? $routing_number : '');
        if(count($isDefault) > 0 && $is_default == "yes") {
            WalkerBankingDetails::where('walker_id', Input::get('id'))->update(array('is_default' => 0));
            //$bankingDetails->merchant_id = (!empty($result->merchantAccount->id) ? $result->merchantAccount->id : '');
        }
        $bankingDetails->is_default = (!empty($is_default == "yes") ? 1 : 0);
        $bankingDetails->save();
        echo "success";
        exit;


    }


    public function delete_bank_provider()
    {
        $id = Request::segment(3);
        $WalkerBankingDetails = WalkerBankingDetails::where('id', $id)->delete();

        Session::flash('message', 'Successfully Records Deleted!');

        return Redirect::to("/admin/providers");

    }


    public function settlements()
    {
        $settlementRecs = DB::table('request as req')
            ->select('req.*')
            ->where('req.settlement_id','=', 0)
            ->where('req.payment_mode','=', 1)
            //->where('req.confirmed_walker','=', 1)
            ->where('req.is_paid','=', 1)
            ->orderBy('req.created_at', 'DESC')
            ->get();

        $walkers = DB::table('walker as w')
            ->select('w.*')
            ->orderBy('w.id', 'DESC')
            ->get();

        $title = ucwords(trans('customize.bank_accounts'));
        return View::make('settlements.settlements')
            ->with('title', $title)
            ->with('page', 'settlements')
            ->with('walkers', $walkers)
            ->with('settlements', $settlementRecs);

    }



    public function search_settlements() {

        $walker_id = Input::get('walker_id');
        $settlementRecs = DB::table('request as req')
            ->select('req.*')
            ->where('req.settlement_id','=', 0)
            ->where('req.payment_mode','=', 1)
            ->where('req.confirmed_walker','=', $walker_id)
            ->where('req.is_paid','=', 1)
            ->orderBy('req.created_at', 'DESC')
            ->get();

        $walkers = DB::table('walker as w')
            ->select('w.*')
            ->orderBy('w.id', 'DESC')
            ->get();


        $title = ucwords(trans('customize.settlements'));
        return View::make('settlements.settlements')
            ->with('title', $title)
            ->with('page', 'settlements')
            ->with('walkers', $walkers)
            ->with('walker_id', $walker_id)
            ->with('settlements', $settlementRecs);
    }

    public function update_settlements() {

        /* echo "<pre>";
        print_r(Input::all());
        exit; */

        $types = Input::get('type');

        //echo "<pre>";
        //print_r($types[0]); //exit;

        $total = $driverPayment = $walker_id = 0;
        foreach ($types as $key => $type) {
            $value =  key($type);
            if (Input::has('settlements_' . $value)) {

                $walkerHistory = new WalkerWalletHistory();
                $walkerHistory->request_id = $value;
                $walkerHistory->walker_history_id = SettlementUniqueIdGeneration();
                $walkerHistory->walker_id = $walker_id = Input::get('walker_id_' . $value);
                $walkerHistory->amount = Input::get('driver_per_payment_' . $value);
                $walkerHistory->save();

                DB::table('request')->where('id', '=',$value)->update(array('settlement_id' => 1));
                $total += Input::get('total_' . $value);
                $driverPayment += Input::get('driver_per_payment_' . $value);

            }
        }

        $walletTotDriverAmt = DB::table('walker_wallet_state')->where('walker_id', '=', $walker_id)->sum('total_amount');
        $walletTotalAmt = DB::table('walker_wallet_state')->where('walker_id', '=', $walker_id)->sum('total_balance');

        if(!empty($walletTotDriverAmt) && !empty($walletTotalAmt)) {

            DB::table('walker_wallet_state')->where('walker_id', '=',$walker_id)
                                ->update(
                                    array(
                                        'total_amount' => $walletTotDriverAmt + $driverPayment,
                                        'total_balance' => $walletTotalAmt + $total
                                    )
                                );

        } else {

            $walkerState = new WalkerWalletState();
            $walkerState->walker_id = $walker_id;
            $walkerState->total_amount = $driverPayment;
            $walkerState->total_balance = $total;
            $walkerState->save();

        }

        Session::flash('message', 'Successfully Update the Payments!');
        return Redirect::to("admin/search_settlements?walker_id=$walker_id");

    }


    public function driver_wallet_information()
    {

        $walker_id = Request::segment(4);
        $tab = Input::get('tab')?Input::get('tab'):1;
        $walkerState = DB::table('walker_wallet_state')->where('walker_id','=',$walker_id)->first();

        //echo "<pre>";
        //print_r(Input::all()); exit;
        //print_r($walkerState); exit;

        $walletTotal = $walletSpend = $walletBalance = 0;

        if( count( $walkerState ) > 0 ) {
            $walletTotal = $walkerState->total_amount;
            $walletSpend = $walkerState->total_spend;
            $walletBalance = $walkerState->total_balance;
        }

        if (Input::get('submit') && Input::get('submit') == 'Filter_Data') {

            $query = DB::table('walker_wallet_history')
                    ->where('walker_id',$walker_id);

            if (Input::get('start_date') && Input::get('end_date')) {
                $query = $query->where('created_at', '>=', date('Y-m-d',strtotime(Input::get('start_date'))))
                    ->where('created_at', '<=', date('Y-m-d',strtotime(Input::get('end_date'))));
            }
            $walletHistory = $query->select('id','walker_history_id','request_id','walker_id','amount','created_at');
            $walletHistory = $walletHistory->orderBy('id', 'DESC')->paginate(10);

            $query1 = DB::table('walker_spend_history')
                ->where('walker_id',$walker_id);

            if (Input::get('start_date') && Input::get('end_date')) {
                $query1 = $query1->where('created_at', '>=', date('Y-m-d',strtotime(Input::get('start_date'))))
                    ->where('created_at', '<=', date('Y-m-d',strtotime(Input::get('end_date'))));
            }
            $walletSpendHistory = $query1->select('id','spend_history_id','walker_id','amount','created_at');
            $walletSpendHistory = $walletSpendHistory->orderBy('id', 'DESC')->paginate(10);

           /* echo "<pre>";
           print_r($walletHistory);
           exit; */

        } else if(Input::get('submit') && Input::get('submit') == 'Download_Report') {

            $query = DB::table('walker_wallet_history')
                ->where('walker_id',$walker_id);

            if (Input::get('start_date') && Input::get('end_date')) {
                $query = $query->where('created_at', '>=', date('Y-m-d',strtotime(Input::get('start_date'))))
                    ->where('created_at', '<=', date('Y-m-d',strtotime(Input::get('end_date'))));
            }
            $walletHistory = $query->select('id','walker_history_id','request_id','walker_id','amount','created_at');
            $walletHistory = $walletHistory->orderBy('id', 'DESC')->get();

            $query1 = DB::table('walker_spend_history')
                ->where('walker_id',$walker_id);

            if (Input::get('start_date') && Input::get('end_date')) {
                $query1 = $query1->where('created_at', '>=', date('Y-m-d',strtotime(Input::get('start_date'))))
                    ->where('created_at', '<=', date('Y-m-d',strtotime(Input::get('end_date'))));
            }
            $walletSpendHistory = $query1->select('id','spend_history_id','walker_id','amount','created_at');
            $walletSpendHistory = $walletSpendHistory->orderBy('id', 'DESC')->get();

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=data.csv');
            $handle = fopen('php://output', 'w');

            if ($tab == 1) {
                $i = 1;
                fputcsv($handle, array('S.No','Unique ID', 'Amount' ,'Date', 'Time' ));
                foreach ($walletHistory as $wallet) {
                    fputcsv($handle, array(
                        $i,
                        $wallet->walker_history_id,
                        $wallet->amount,
                        date('m/d/Y', strtotime($wallet->created_at)),
                        date('h:i:s a', strtotime($wallet->created_at))
                    ));
                $i++; }
            } else {
                $j = 1;
                fputcsv($handle, array('S.No','Unique ID','Spend Amount', 'Date', 'Time'));
                foreach ($walletSpendHistory as $wallet) {
                    fputcsv($handle, array(
                        $j,
                        $wallet->spend_history_id,
                        $wallet->amount,
                        date('m/d/Y', strtotime($wallet->created_at)),
                        date('h:i:s a', strtotime($wallet->created_at)),
                    ));
                $j++; }
            }
            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
        }
        else{
            $walletHistory = DB::table('walker_wallet_history')
                ->where('walker_id',$walker_id)
                ->paginate(10);
            $walletSpendHistory =DB::table('walker_spend_history')
                ->where('walker_id',$walker_id)
                ->paginate(10);
        }

        if(Input::get('submit') != 'Download_Report') {

            $title = ucwords(trans('customize.driver_earnings'));
            return View::make('wallet.driver_wallet_information')
                ->with('title', $title)
                ->with('wallet_total', $walletTotal)
                ->with('wallet_balance', $walletBalance)
                ->with('wallet_spend', $walletSpend)
                ->with('walletHistory', $walletHistory)
                ->with('walletSpendHistory', $walletSpendHistory)
                ->with('walker_id', $walker_id)
                ->with('tab', $tab)
                ->with('page', 'Wallet Driver Information');
        }

    }


    //add new table


    private static function wallet_user_state() {

        Schema::create('wallet_user_state', function ($table) {
            $table->increments('id');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->double('total_amount', 15, 8);
            $table->double('total_spend', 15, 8);
            $table->double('total_balance', 15, 8);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('owner');
        });
    }

    private static function wallet_spend_history(){
        Schema::create('wallet_spend_history', function ($table) {
            $table->increments('id');
            $table->string('wallet_spend_id');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->double('amount', 15, 8);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('owner');
            $table->integer('request_id')->unsigned();
            $table->foreign('request_id')->references('id')->on('request');

        });
    }

    private static function wallet_add_history()
    {
        Schema::create('wallet_add_history', function ($table) {
            $table->increments('id');
            $table->string('wallet_added_id');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->double('amount', 15, 8);
            $table->integer('user_id')->unsigned();
            $table->integer('payment_id')->unsigned();
        });
        Schema::table('wallet_add_history',function ($table)
        {
            $table->foreign('user_id')->on('owner')->references('id');
            $table->foreign('payment_id')->references('id')->on('payment');
        });
    }

    



}
