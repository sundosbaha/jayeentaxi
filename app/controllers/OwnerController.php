<?php

use Intervention\Image\ImageManager;
use Stripe\Stripe as Stripe;
use Stripe\Customer as Stripe_Customer;

class OwnerController extends BaseController
{

    public function get_braintree_token()
    {

        $token = Input::get('token');
        $owner_id = Input::get('id');
        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id,
            ), array(
                'token' => 'required',
                'owner_id' => 'required|integer'
            )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    if (Config::get('app.default_payment') == 'braintree') {

                        Braintree_Configuration::environment(Config::get('app.braintree_environment'));
                        Braintree_Configuration::merchantId(Config::get('app.braintree_merchant_id'));
                        Braintree_Configuration::publicKey(Config::get('app.braintree_public_key'));
                        Braintree_Configuration::privateKey(Config::get('app.braintree_private_key'));
                        $clientToken = Braintree_ClientToken::generate();
                        $response_array = array('success' => true, 'token' => $clientToken);
                        $response_code = 200;
                    } else {
                        $response_array = array('success' => false, 'error' => 'Please change braintree as default gateway', 'error_code' => 440);
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $var = Keywords::where('id', 2)->first();
                      $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID is not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID is not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function isAdmin($token)
    {
        return false;
    }

    public function eta_calculation()
    {

        $token = Input::get('token');
        $owner_id = Input::get('id');
        $pick_lat=Input::get('pick_latitude');
        $pick_lng=Input::get('pick_longitude');
        $drop_lat=Input::get('drop_latitude');
        $drop_lng=Input::get('drop_longitude');
        $type=Input::get("type");

        if (Input::has('card_id')) {
            $card_id = Input::get('card_id');
            Payment::where('owner_id', $owner_id)->update(array('is_default' => 0));
            Payment::where('owner_id', $owner_id)->where('id', $card_id)->update(array('is_default' => 1));
        }

        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id,
                'pick_lat'=>$pick_lat,
                'pick_lng'=>$pick_lng,
                'drop_lat'=>$drop_lat,
                'drop_lng'=>$drop_lng,
                'type'=>$type,

            ), array(
                'token' => 'required',
                'owner_id' => 'required|integer',
                'pick_lat'=>'required',
                'pick_lng'=>'required',
                'drop_lat'=>'required',
                'drop_lng'=>'required',
                'type'=>'required',

            )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations





                    $points = $pick_lng . " " . $pick_lat;
                    $setting_zone = Settings::where('key', 'zone_division')->first();
                    if ($setting_zone->value == 1) {

                        $zoneRecords = array();
                        $result = array();
                        $zoneRecords = getAllZoneList();


                        if (empty($zoneRecords)) {

                            $response_array = array('success' => false, 'error' => 'No Service for this Area', 'error_messages' => 'No Service for this Area', 'error_code' => 416);

                            $response_code = 200;

                            return Response::json($response_array, $response_code);

                        } else {

                            $zone = false;
                            $result['id'] = '';
                            foreach ($zoneRecords as $key => $zoneList) {
                                $longitudeZoneArray = array();
                                $latitudeZoneArray = array();

                                $longitudeZoneArray = zoneLongitudeArrays($zoneList->zone_json);
                                $latitudeZoneArray = zoneLatitudeArrays($zoneList->zone_json);

                                $zoneCoordinates = array_map("zoneCoordinates", $longitudeZoneArray, $latitudeZoneArray);
                                $pointLocation = new pointLocation();

                                // The last point's coordinates must be the same as the first one's, to "close the loop"
                                if ($pointLocation->pointInPolygon($points, $zoneCoordinates)) {
                                    $result['id'] = $zoneList->id;
                                    $zones = true;
                                    break;

                                }


                            }


                        }


                        if (empty($result['id']))
                        {
                            $response_array = array('success' => false, 'error' => 'No Service for this Area', 'error_messages' => 'No Service for this Area.', 'error_code' => 416);

                            $response_code = 200;

                            return Response::json($response_array, $response_code);
                        }


                        else if (!empty($result['id']))
                        {



                            $zone_type=DB::table("zone_type")->where("type",$type)->where('is_visible',1)->where("zone_id",$result['id'])->whereNull("deleted_at")->first();


                            if(empty($zone_type)){
                                $response_array = array('success' => false, 'error' => 'No Service for this Area', 'error_messages' => 'No Service for this Area.', 'error_code' => 416);

                                $response_code = 200;

                                return Response::json($response_array, $response_code);

                            }else{

                                $car_type=$zone_type;
                                $car_name=DB::table('walker_type')->where('id',$type)->first();

                            }

                        }

                    }

                    if( !empty( $pick_lat ) && !empty( $pick_lng ) && !empty( $drop_lat ) && !empty( $drop_lng ) ) {

                        $origin = $pick_lat . ',' . $pick_lng;
                        $destination = $drop_lat . ',' . $drop_lng;
                        $durationMatrix = returnDurationAndDestination($origin, $destination);

                        /*print_r($durationMatrix);
                        die("u");
                        */

                        $settings = Settings::where('key', 'default_distance_unit')->first();
                        $unit = $settings->value;
                        $retDistance = (string)convert($durationMatrix['distance'], $unit);
                        $retDuration = round($durationMatrix['duration'] / 60);

                        /*print_r($retDuration);
                        die("p")*/;
                        $distance = $retDistance;
                        $duration = $retDuration;

                        if($car_type->base_distance >= intval($distance))
                        {
                            $cal_distance_price=0;
                            $cal_time_price=floatval($duration*$car_type->price_per_unit_time);
                            $cal_total=floatval($car_type->base_price+$cal_time_price);
                        }
                        else
                        {
                            $dis=floatval($distance-$car_type->base_distance);
                            $cal_distance_price=floatval($dis*$car_type->price_per_unit_distance);
                            $cal_time_price=floatval($duration*$car_type->price_per_unit_time);
                            $cal_total=floatval($car_type->base_price+$cal_time_price+$cal_distance_price);

                        }




                        $response_array=array(
                            'success'=>true,
                            'name' => $car_name->name,
                            'type' => $type,
                            'base_price' => $car_type->base_price,
                            'distance_price' => $car_type->price_per_unit_distance,
                            'time_price' => $car_type->price_per_unit_time,
                            'base_distance' => $car_type->base_distance,
                            'cal_distance_price' => $cal_distance_price,
                            'cal_time_price' => $cal_time_price,
                            'cal_total' => $cal_total,
                            'total_duration' => $duration,
                            'total_distance' => $distance,
                        );
                        $response_code=200;
                        $response = Response::json($response_array, $response_code);
                        return $response;
                    }


                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {

                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 621);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function eta_calculation_20180131()
    {
        $token = Input::get('token');
        $owner_id = Input::get('id');
        $pick_lat=Input::get('pick_latitude');
        $pick_lng=Input::get('pick_longitude');
        $drop_lat=Input::get('drop_latitude');
        $drop_lng=Input::get('drop_longitude');
        $type=Input::get("type");

        if (Input::has('card_id')) {
            $card_id = Input::get('card_id');
            Payment::where('owner_id', $owner_id)->update(array('is_default' => 0));
            Payment::where('owner_id', $owner_id)->where('id', $card_id)->update(array('is_default' => 1));
        }

        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id,
                'pick_lat'=>$pick_lat,
                'pick_lng'=>$pick_lng,
                'drop_lat'=>$drop_lat,
                'drop_lng'=>$drop_lng,
                'type'=>$type,

            ), array(
                'token' => 'required',
                'owner_id' => 'required|integer',
                'pick_lat'=>'required',
                'pick_lng'=>'required',
                'drop_lat'=>'required',
                'drop_lng'=>'required',
                'type'=>'required',

            )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations

                    if( !empty( $pick_lat ) && !empty( $pick_lng ) && !empty( $drop_lat ) && !empty( $drop_lng ) ) {

                        $origin = $pick_lat . ',' . $pick_lng;
                        $destination = $drop_lat . ',' . $drop_lng;

                        $durationMatrix = returnDurationAndDestination($origin, $destination);

                        $settings = Settings::where('key', 'default_distance_unit')->first();
                        $unit = $settings->value;
                        $retDistance = (string)convert($durationMatrix['distance'], $unit);
                        $retDuration = round($durationMatrix['duration'] / 60);


                        /* $destination_addresses=$durationMatrix['destination_addresses'];
                         $origin_addresses=$durationMatrix['origin_addresses'];*/

                        $distance = $retDistance;
                        $duration = $retDuration;


                        /*$approx_distance = $retDistance+(rand());
                        $approx_duration = $retDuration+(rand());*/

                        /*print_r(["dis"=>$distance,"dur"=>$duration,"appr_distance"=>$appr_distance,"appr_duration"=>$appr_duration]);
                        die()*/;

                        $car_type=DB::table('walker_type')->where('id',$type)->first();



                        if($car_type->base_distance >= intval($distance))
                        {
                            $cal_distance_price=0;
                            $cal_time_price=floatval($duration*$car_type->price_per_unit_time);
                            $cal_total=floatval($car_type->base_price+$cal_time_price);

                            /*                            $cal_appr_time_price=floatval($appr_duration*$car_type->price_per_unit_time);
                                                        $cal_appr_total=floatval($car_type->base_price+$cal_appr_time_price);*/
                        }
                        else
                        {
                            $dis=floatval($distance-$car_type->base_distance);
                            $cal_distance_price=floatval($dis*$car_type->price_per_unit_distance);
                            $cal_time_price=floatval($duration*$car_type->price_per_unit_time);
                            $cal_total=floatval($car_type->base_price+$cal_time_price+$cal_distance_price);

                            /*                            $appr_dis=floatval($appr_distance-$car_type->base_distance);
                                                        $cal_appr_distance_price=floatval($appr_dis*$car_type->price_per_unit_distance);
                                                        $cal_appr_time_price=floatval($appr_duration*$car_type->price_per_unit_time);
                                                        $cal_appr_total=floatval($car_type->base_price+$cal_appr_time_price+$cal_appr_distance_price);*/
                        }

                        //find approxmate total
                        //
                        $percentage=rand(5,15)/100;
                        $cal_approx_total=$cal_total+($percentage*$cal_total);

                        // $duration=0;
                        $response_array=array(
                            'success'=>true,
                            'name' => $car_type->name,
                            'type' => $type,
                            'base_price' => $car_type->base_price,
                            'distance_price' => $car_type->price_per_unit_distance,
                            'time_price' => $car_type->price_per_unit_time,
                            'base_distance' => $car_type->base_distance,
                            'total_duration' => $duration != 0 ? $duration : 1 ,
                            'total_distance' => $distance,
                            'cal_distance_price' => $cal_distance_price,
                            'cal_time_price' => $cal_time_price,
                            'cal_total' => number_format($cal_total,2),
                            'cal_approx_total' => number_format($cal_approx_total,2),

                        );
                        $response_code=200;
                        $response = Response::json($response_array, $response_code);
                        return $response;
                    }


                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {

                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 621);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }
    public function getOwnerData($owner_id, $token, $is_admin)
    {

        if ($owner_data = Owner::where('token', '=', $token)->where('id', '=', $owner_id)->first()) {
            return $owner_data;
        } elseif ($is_admin) {
            $owner_data = Owner::where('id', '=', $owner_id)->first();
            if (!$owner_data) {
                return false;
            }
            return $owner_data;
        } else {
            return false;
        }
    }

    /* public function apply_referral_code() {
      $referral_code = Input::get('referral_code');
      $token = Input::get('token');
      $owner_id = Input::get('id');

      $validator = Validator::make(
      array(
      'referral_code' => $referral_code,
      'token' => $token,
      'owner_id' => $owner_id,
      ), array(
      'referral_code' => 'required',
      'token' => 'required',
      'owner_id' => 'required|integer'
      )
      );

      if ($validator->fails()) {
      $error_messages = $validator->messages();
      $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401);
      } else {
      $is_admin = $this->isAdmin($token);
      if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
      // check for token validity
      if (is_token_active($owner_data->token_expiry) || $is_admin) {
      if ($ledger = Ledger::where('referral_code', $referral_code)->where('owner_id', '!=', $owner_id)->first()) {
      $referred_by = $ledger->owner_id;
      $settings = Settings::where('key', 'default_referral_bonus_to_refered_user')->first();
      $referral_bonus = $settings->value;

      $ledger = Ledger::find($ledger->id);
      if ($ledger->referral_code != NULL) {
      $ledger->referral_code = $referral_code;
      $ledger->total_referrals = $ledger->total_referrals + 1;
      $ledger->amount_earned = $ledger->amount_earned + $referral_bonus;
      $ledger->save();

      $owner = Owner::find($owner_id);
      $owner->referred_by = $ledger->owner_id;
      $owner->save();

      $response_array = array('success' => true);
      } else {
      $response_array = array('success' => false, 'error' => 'Already applied referral code', 'error_code' => 482);
      }
      } elseif ($ledger = Ledger::where('referral_code', $referral_code)->where('owner_id', $owner_id)->first()) {
      $response_array = array('success' => false, 'error' => 'Can not add your own Referral code', 'error_code' => 483);
      } elseif ($pcode = PromoCodes::where('coupon_code', $referral_code)->where('type', 2)->where('state', 1)->where('uses', '>', 0)->first()) {
      $promohistory = PromoHistory::where('user_id', $owner_id)->where('promo_code', $referral_code)->first();
      if (!$promohistory) {
      $promo_code = $pcode->id;
      $pcode->uses = $pcode->uses - 1;
      $pcode->save();
      $phist = new PromoHistory();
      $phist->user_id = $owner_id;
      $phist->promo_code = $referral_code;
      // Assuming all are absolute discount
      $phist->amount_earned = $pcode->value;
      $phist->save();
      // Add to ledger amount
      $led = Ledger::where('owner_id', $owner_id)->first();
      if ($led) {
      $led->amount_earned = $led->amount_earned + $pcode->value;
      $led->save();
      } else {
      $ledger = new Ledger();
      $ledger->owner_id = $owner_id;
      $ledger->referral_code = "0";
      $ledger->total_referrals = 0;
      $ledger->amount_earned = $pcode->value;
      $ledger->amount_spent = 0;
      $ledger->save();
      }
      $response_array = array('success' => true);
      } else {
      $response_array = array('success' => false, 'error' => 'Promo Code Already Applied.', 'error_code' => 495);
      }
      } elseif ($pcode = PromoCodes::where('coupon_code', $referral_code)->where('uses', 0)->first()) {
      $response_array = array('success' => false, 'error' => 'Invalid promo code', 'error_code' => 496);
      } elseif ($pcode = PromoCodes::where('coupon_code', $referral_code)->where('type', 1)->first()) {
      $response_array = array('success' => false, 'error' => 'Percentage discount can not be applied here.', 'error_code' => 465);
      } elseif ($pcode = PromoCodes::where('coupon_code', '!=', $referral_code)->first()) {
      $response_array = array('success' => false, 'error' => 'Invalid promo code', 'error_code' => 475);
      } elseif ($pcode = PromoCodes::where('coupon_code', $referral_code, 'state', '!=', 1)->first()) {
      $response_array = array('success' => false, 'error' => 'Invalid promo code', 'error_code' => 485);
      } else {
      $response_array = array('success' => false, 'error' => 'Invalid referral code', 'error_code' => 455);
      }
      } else {
      $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
      }
      } else {
      if ($is_admin) {
      // $var = Keywords::where('id', 2)->first();
      // $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID is not Found', 'error_code' => 410);
      $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID is not Found', 'error_code' => 410);
      } else {
      $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
      }
      }
      }
      $response_code = 200;
      $response = Response::json($response_array, $response_code);
      return $response;
      } */

    public function list_favourite_place(){
        $owner_id=Input::get('id');
        $token=Input::get('token');
        $validator=Validator::make(

            array(
                'id'=>$owner_id,
                'token'=>$token,
            ),array(
                'id'=>'required',
                'token'=>'required',
            )
        );
        if($validator->fails()){
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401,'error_message' =>$error_messages);
            $response_code = 200;
        }else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    $favourite=FavouritePlace::where('owner_id',$owner_id)->where('favourite_mode',1)->get();


                    $data=array();
                    if(!empty($favourite)){
                        $data1=array();
                        foreach($favourite as $fkey){

                            $data1['favourite_id']=$fkey->id;
                            $data1['owner_id']=$fkey->owner_id;
                            $data1['key']=$fkey->key;
                            $data1['owner_id']=$fkey->owner_id;
                            $data1['address']=$fkey->address;
                            $data1['latitude']=$fkey->latitude;
                            $data1['longitude']=$fkey->longitude;

                        array_push($data,$data1);
                        }
                    }else{
                        $data="No Record Found";
                    }
                    $response_array = array('success' => true,'data'=>$data);
                    $response_code = 200;

                }else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            }else{
                if ($is_admin) {
                    /* $var = Keywords::where('id', 2)->first();
                      $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID is not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID is not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
                }
        $response = Response::json($response_array, $response_code);
        return $response;
    }
    public function add_favourite_place(){

        $owner_id=Input::get('id');
        $token=Input::get('token');
        $key=Input::get('key');
        $address=Input::get("address");
        $latitude=Input::get('latitude');
        $longitude=Input::get('longitude');
        $favourite_mode=Input::get('favourite_mode',0);
        $favourite_id=Input::get('favourite_id');


$validator=Validator::make(

    array(
        'id'=>$owner_id,
        'token'=>$token,
        'key'=>$key,
        'address'=>$address,
        'latitude'=>$latitude,
        'longitude'=>$longitude,
        'favourite_id'=>$favourite_id
    ),array(
        'id'=>'required',
        'token'=>'required',
        'key'=>($favourite_mode != 0) ?   "required" :"" ,
        'address'=>($favourite_mode != 0) ?   "required" :"" ,
        'latitude'=>($favourite_mode != 0) ?   "required" :"" ,
        'longitude'=>($favourite_mode != 0) ?   "required" :"" ,
        'favourite_id'=>($favourite_mode != 0) ?   "" :"required" ,
    )
);


        if($validator->fails()){
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401,'error_message' =>$error_messages);
            $response_code = 200;
        }else{
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {

                    if($favourite_mode == 1){

                        $favourite_place=new FavouritePlace();
                        $favourite_place->key=$key;
                        $favourite_place->owner_id=$owner_id;
                        $favourite_place->address=$address;
                        $favourite_place->latitude=$latitude;
                        $favourite_place->longitude=$longitude;
                        $favourite_place->favourite_mode=$favourite_mode;
                        $favourite_place->save();

                        $favourite=FavouritePlace::where('owner_id',$owner_id)->orderby('id','Desc')->first();
                        $data=array();
                        $data['favourite_id']=$favourite->id;
                        $data['owner_id']=$favourite->owner_id;
                        $data['key']=$favourite->key;
                        $data['owner_id']=$favourite->owner_id;
                        $data['address']=$favourite->address;
                        $data['latitude']=$favourite->latitude;
                        $data['longitude']=$favourite->longitude;
                        $response_array = array('success' => true,'data'=>$data);
                        $response_code = 200;
                    }else{
                        $favourite_place=FavouritePlace::where('id',$favourite_id)->first();
                        $favourite_place->favourite_mode=$favourite_mode;
                        $favourite_place->save();
                        $response_array = array('success' => true);
                        $response_code = 200;
                    }

                }else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
                }else{
                if ($is_admin) {
                    /* $var = Keywords::where('id', 2)->first();
                      $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID is not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID is not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }

        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }


    public function apply_referral_code()
    {
        $referral_code = Input::get('referral_code');
        $token = Input::get('token');
        $owner_id = Input::get('id');
        $is_skip = Input::get('is_skip');

        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id,
                'is_skip' => $is_skip,
            ), array(
                'token' => 'required',
                'owner_id' => 'required|integer',
                'is_skip' => 'required',
            )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    if ($is_skip != 1) {
                        if ($ledger = Ledger::where('referral_code', $referral_code)->first()) {
                            $referred_by = $ledger->owner_id;
                            if ($referred_by != $owner_id) {
                                if ($owner_data->is_referee) {
                                    $owner = Owner::find($owner_id);
                                    $code_data = Ledger::where('owner_id', '=', $owner->id)->first();
                                    $response_array = array(
                                        'success' => false,
                                        'error' => "Sorry, You have apready apply the refereel code.",
                                        'error_code' => 634,
                                        'id' => $owner->id,
                                        'first_name' => $owner->first_name,
                                        'last_name' => $owner->last_name,
                                        'phone' => $owner->phone,
                                        'email' => $owner->email,
                                        'picture' => $owner->picture,
                                        'bio' => $owner->bio,
                                        'address' => $owner->address,
                                        'state' => $owner->state,
                                        'country' => $owner->country,
                                        'zipcode' => $owner->zipcode,
                                        'login_by' => $owner->login_by,
                                        'social_unique_id' => $owner->social_unique_id,
                                        'device_token' => $owner->device_token,
                                        'device_type' => $owner->device_type,
                                        'token' => $owner->token,
                                        'referral_code' => $code_data->referral_code,
                                        'is_referee' => $owner->is_referee,
                                        'promo_count' => $owner->promo_count,
                                    );
                                    $response_code = 200;
                                } else {
                                    $settings = Settings::where('key', 'default_referral_bonus_to_refered_user')->first();
                                    $refered_user = $settings->value;

                                    $settings = Settings::where('key', 'default_referral_bonus_to_refereel')->first();
                                    $referral = $settings->value;

                                    $ledger = Ledger::find($ledger->id);
                                    $ledger->total_referrals = $ledger->total_referrals + 1;
                                    $ledger->amount_earned = $ledger->amount_earned + $refered_user;
                                    $ledger->save();

                                    $ledger1 = Ledger::where('owner_id', $owner_id)->first();
                                    $ledger1 = Ledger::find($ledger1->id);
                                    $ledger1->amount_earned = $ledger1->amount_earned + $referral;
                                    $ledger1->save();

                                    $owner = Owner::find($owner_id);
                                    $owner->referred_by = $ledger->owner_id;
                                    $owner->is_referee = 1;
                                    $owner->save();
                                    $owner = Owner::find($owner_id);
                                    $code_data = Ledger::where('owner_id', '=', $owner->id)->first();
                                    $response_array = array(
                                        'success' => true,
                                        'error' => 'Referral process successfully completed.',
                                        'error_code' => 636,
                                        'id' => $owner->id,
                                        'first_name' => $owner->first_name,
                                        'last_name' => $owner->last_name,
                                        'phone' => $owner->phone,
                                        'email' => $owner->email,
                                        'picture' => $owner->picture,
                                        'bio' => $owner->bio,
                                        'address' => $owner->address,
                                        'state' => $owner->state,
                                        'country' => $owner->country,
                                        'zipcode' => $owner->zipcode,
                                        'login_by' => $owner->login_by,
                                        'social_unique_id' => $owner->social_unique_id,
                                        'device_token' => $owner->device_token,
                                        'device_type' => $owner->device_type,
                                        'token' => $owner->token,
                                        'referral_code' => $code_data->referral_code,
                                        'is_referee' => $owner->is_referee,
                                        'promo_count' => $owner->promo_count,
                                    );
                                    $response_code = 200;
                                }
                            } else {
                                $owner = Owner::find($owner_id);
                                $code_data = Ledger::where('owner_id', '=', $owner->id)->first();
                                $response_array = array(
                                    'success' => false,
                                    'error' => "Sorry, You can't apply your refereel code.",
                                    'error_code' => 636,
                                    'id' => $owner->id,
                                    'first_name' => $owner->first_name,
                                    'last_name' => $owner->last_name,
                                    'phone' => $owner->phone,
                                    'email' => $owner->email,
                                    'picture' => $owner->picture,
                                    'bio' => $owner->bio,
                                    'address' => $owner->address,
                                    'state' => $owner->state,
                                    'country' => $owner->country,
                                    'zipcode' => $owner->zipcode,
                                    'login_by' => $owner->login_by,
                                    'social_unique_id' => $owner->social_unique_id,
                                    'device_token' => $owner->device_token,
                                    'device_type' => $owner->device_type,
                                    'token' => $owner->token,
                                    'referral_code' => $code_data->referral_code,
                                    'is_referee' => $owner->is_referee,
                                    'promo_count' => $owner->promo_count,
                                );
                                $response_code = 200;
                            }
                        } else {
                            $owner = Owner::find($owner_id);
                            $code_data = Ledger::where('owner_id', '=', $owner->id)->first();
                            $response_array = array(
                                'success' => false,
                                'error' => 'Invalid referral code',
                                'error_code' => 637,
                                'id' => $owner->id,
                                'first_name' => $owner->first_name,
                                'last_name' => $owner->last_name,
                                'phone' => $owner->phone,
                                'email' => $owner->email,
                                'picture' => $owner->picture,
                                'bio' => $owner->bio,
                                'address' => $owner->address,
                                'state' => $owner->state,
                                'country' => $owner->country,
                                'zipcode' => $owner->zipcode,
                                'login_by' => $owner->login_by,
                                'social_unique_id' => $owner->social_unique_id,
                                'device_token' => $owner->device_token,
                                'device_type' => $owner->device_type,
                                'token' => $owner->token,
                                'referral_code' => $code_data->referral_code,
                                'is_referee' => $owner->is_referee,
                                'promo_count' => $owner->promo_count,
                            );
                            $response_code = 200;
                        }
                    } else {
                        $owner = Owner::find($owner_id);
                        $owner->is_referee = 1;
                        $owner->save();
                        $owner = Owner::find($owner_id);
                        $code_data = Ledger::where('owner_id', '=', $owner->id)->first();
                        $response_array = array(
                            'success' => true,
                            'error' => 'You have skipped for referral process',
                            'error_code' => 638,
                            'id' => $owner->id,
                            'first_name' => $owner->first_name,
                            'last_name' => $owner->last_name,
                            'phone' => $owner->phone,
                            'email' => $owner->email,
                            'picture' => $owner->picture,
                            'bio' => $owner->bio,
                            'address' => $owner->address,
                            'state' => $owner->state,
                            'country' => $owner->country,
                            'zipcode' => $owner->zipcode,
                            'login_by' => $owner->login_by,
                            'social_unique_id' => $owner->social_unique_id,
                            'device_token' => $owner->device_token,
                            'device_type' => $owner->device_type,
                            'token' => $owner->token,
                            'referral_code' => $code_data->referral_code,
                            'is_referee' => $owner->is_referee,
                            'promo_count' => $owner->promo_count,
                        );
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    $response_array = array('success' => false, 'error' => 'Owner ID is not Found', 'error_code' => 621);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function apply_promo_code_previously()
    {
        $promo_code = Input::get('promo_code');
        $token = Input::get('token');
        $owner_id = Input::get('id');

        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id,
            ), array(
                'token' => 'required',
                'owner_id' => 'required|integer',
            )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600);
            $response_code = 200;
        } else {

            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {

                                $settings = Settings::where('key', 'promotional_code_activation')->first();
                                $prom_act = $settings->value;
                                if ($prom_act) {

                                            if ($promos = PromoCodes::where('coupon_code', $promo_code)->where('uses', '>', 0)->where('state', '=', 1)->first()) {
                                                if ((date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime(trim($promos->expiry)))) || (date("Y-m-d H:i:s") <= date("Y-m-d H:i:s", strtotime(trim($promos->start_date))))) {
                                                    $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available.', 'error_code' => 614);
                                                    $response_code = 200;
                                                } else {

                                                    $promo_check = UserPromoUse::where('user_id', '=', $owner_id)->where('is_used',0)->count();

                                                    if ($promo_check <= 0) {
                                                    $promo_is_used = UserPromoUse::where('user_id', '=', $owner_id)->where('is_used',1)->where('code_id', '=', $promos->id)->count();

                                                    if ($promo_is_used) {
                                                        $response_array = array('success' => FALSE, 'error' => 'Promotional code already used.', 'error_code' => 615);
                                                        $response_code = 200;
                                                    } else {
                                                        $promo_update_counter = PromoCodes::find($promos->id);
                                                        $promo_update_counter->uses = $promo_update_counter->uses - 1;
                                                        $promo_update_counter->save();

                                                        $user_promo_entry = new UserPromoUse;
                                                        $user_promo_entry->code_id = $promos->id;
                                                        $user_promo_entry->user_id = $owner_id;
                                                        $user_promo_entry->save();

                                                        $owner = Owner::find($owner_id);
                                                        $owner->promo_count = $owner->promo_count + 1;
                                                        $owner->save();

                                                        $user_promocode_add_details=UserPromoUse::where('user_id',$owner_id)->where("code_id",$promos->id)->first();


                                                        $owner = Owner::find($owner_id);
                                                        $code_data = Ledger::where('owner_id', '=', $owner->id)->first();
                                                        $response_array = array(
                                                            'success' => true,
                                                            'error' => 'Promotional code successfully added.',
                                                            'id' => $owner->id,
                                                            'first_name' => $owner->first_name,
                                                            'last_name' => $owner->last_name,
                                                            'phone' => $owner->phone,
                                                            'email' => $owner->email,
                                                            'picture' => $owner->picture,
                                                            'bio' => $owner->bio,
                                                            'address' => $owner->address,
                                                            'state' => $owner->state,
                                                            'country' => $owner->country,
                                                            'zipcode' => $owner->zipcode,
                                                            'login_by' => $owner->login_by,
                                                            'social_unique_id' => $owner->social_unique_id,
                                                            'device_token' => $owner->device_token,
                                                            'device_type' => $owner->device_type,
                                                            'token' => $owner->token,
                                                            'referral_code' => $code_data->referral_code,
                                                            'is_referee' => $owner->is_referee,
                                                            'promo_count' => $owner->promo_count,
                                                            'promo_code_add_id' => $user_promocode_add_details->id,

                                                        );
                                                        $response_code = 200;
                                                    }
                                                }else{
                                                        $response_array = array('success' => FALSE, 'error' => 'Already have Promo code.', 'error_code' => 615);
                                                        $response_code = 200;
                                                    }

                                            }
                                            } else {
                                                $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available.', 'error_code' => 614);
                                                $response_code = 200;
                                            }

                                } else {
                                    $response_array = array('success' => FALSE, 'error' => 'Promotion feature is not active.', 'error_code' => 617);
                                    $response_code = 200;
                                }


                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    $response_array = array('success' => false, 'error' => 'Owner ID is not Found', 'error_code' => 621);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;

    }


        public function apply_promo_code()
    {
        $promo_code = Input::get('promo_code');
        $token = Input::get('token');
        $owner_id = Input::get('id');

        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id,
            ), array(
                'token' => 'required',
                'owner_id' => 'required|integer',
            )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600);
            $response_code = 200;
        } else {
            $request_id = 0;
            $is_apply_on_trip = 0;
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    $request = Requests::where('owner_id', '=', $owner_id)->where('status', '=', 1)->where('is_completed', '=', 0)->where('is_cancelled', '=', 0)->orderBy('created_at', 'desc')->first();
                    if ($request) {
                        if (isset($request->id)) {
                            if ($request->promo_id) {
                                $response_array = array('success' => FALSE, 'error' => 'You can not apply multiple code for single trip.', 'error_code' => 639);
                                $response_code = 200;
                            } else {
                                $settings = Settings::where('key', 'promotional_code_activation')->first();
                                $prom_act = $settings->value;
                                if ($prom_act) {
                                    if ($request->payment_mode == 0) {
                                        $settings = Settings::where('key', 'get_promotional_profit_on_card_payment')->first();
                                        $prom_act_card = $settings->value;
                                        if ($prom_act_card) {
                                            /* if ($ledger = PromotionalCodes::where('promo_code', $promo_code)->first()) { */
                                            if ($promos = PromoCodes::where('coupon_code', $promo_code)->where('uses', '>', 0)->where('state', '=', 1)->first()) {
                                                if ((date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime(trim($promos->expiry)))) || (date("Y-m-d H:i:s") <= date("Y-m-d H:i:s", strtotime(trim($promos->start_date))))) {
                                                    $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available.', 'error_code' => 614);
                                                    $response_code = 200;
                                                } else {
                                                    /* echo $promos->id;
                                                      echo $owner_id;
                                                      $promo_is_used = 0; */
                                                    $promo_is_used = UserPromoUse::where('user_id', '=', $owner_id)->where('code_id', '=', $promos->id)->count();
                                                    /* $promo_is_used = DB::table('user_promo_used')->where('user_id', '=', $owner_id)->where('code_id', '=', $promos->id)->count(); */
                                                    if ($promo_is_used) {
                                                        $response_array = array('success' => FALSE, 'error' => 'Promotional code already used.', 'error_code' => 615);
                                                        $response_code = 200;
                                                    } else {
                                                        $promo_update_counter = PromoCodes::find($promos->id);
                                                        $promo_update_counter->uses = $promo_update_counter->uses - 1;
                                                        $promo_update_counter->save();

                                                        $user_promo_entry = new UserPromoUse;
                                                        $user_promo_entry->code_id = $promos->id;
                                                        $user_promo_entry->user_id = $owner_id;
                                                        $user_promo_entry->save();

                                                        $owner = Owner::find($owner_id);
                                                        $owner->promo_count = $owner->promo_count + 1;
                                                        $owner->save();

                                                        $request = Requests::find($request->id);
                                                        $request->promo_id = $promos->id;
                                                        $request->promo_code = $promos->coupon_code;
                                                        $request->save();

                                                        $owner = Owner::find($owner_id);
                                                        $code_data = Ledger::where('owner_id', '=', $owner->id)->first();
                                                        $response_array = array(
                                                            'success' => true,
                                                            'error' => 'Promotional code successfully applied.',
                                                            'id' => $owner->id,
                                                            'first_name' => $owner->first_name,
                                                            'last_name' => $owner->last_name,
                                                            'phone' => $owner->phone,
                                                            'email' => $owner->email,
                                                            'picture' => $owner->picture,
                                                            'bio' => $owner->bio,
                                                            'address' => $owner->address,
                                                            'state' => $owner->state,
                                                            'country' => $owner->country,
                                                            'zipcode' => $owner->zipcode,
                                                            'login_by' => $owner->login_by,
                                                            'social_unique_id' => $owner->social_unique_id,
                                                            'device_token' => $owner->device_token,
                                                            'device_type' => $owner->device_type,
                                                            'token' => $owner->token,
                                                            'referral_code' => $code_data->referral_code,
                                                            'is_referee' => $owner->is_referee,
                                                            'promo_count' => $owner->promo_count,
                                                            'request_id' => $request->id,
                                                        );
                                                        $response_code = 200;
                                                    }
                                                }
                                            } else {
                                                $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available.', 'error_code' => 614);
                                                $response_code = 200;
                                            }
                                        } else {
                                            $response_array = array('success' => FALSE, 'error' => 'Promotion feature is not active on card payment.', 'error_code' => 616);
                                            $response_code = 200;
                                        }
                                    } else if ($request->payment_mode == 1) {
                                        $settings = Settings::where('key', 'get_promotional_profit_on_cash_payment')->first();
                                        $prom_act_cash = $settings->value;
                                        if ($prom_act_cash) {
                                            /* if ($ledger = PromotionalCodes::where('promo_code', $promo_code)->first()) { */
                                            if ($promos = PromoCodes::where('coupon_code', $promo_code)->where('uses', '>', 0)->where('state', '=', 1)->first()) {
                                                if ((date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime(trim($promos->expiry)))) || (date("Y-m-d H:i:s") <= date("Y-m-d H:i:s", strtotime(trim($promos->start_date))))) {
                                                    $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 614);
                                                    $response_code = 200;
                                                } else {
                                                    /* echo $promos->id;
                                                      echo $owner_id;
                                                      $promo_is_used = 0; */
                                                    $promo_is_used = UserPromoUse::where('user_id', '=', $owner_id)->where('code_id', '=', $promos->id)->count();
                                                    /* $promo_is_used = DB::table('user_promo_used')->where('user_id', '=', $owner_id)->where('code_id', '=', $promos->id)->count(); */
                                                    if ($promo_is_used) {
                                                        $response_array = array('success' => FALSE, 'error' => 'Promotional code already used.', 'error_code' => 615);
                                                        $response_code = 200;
                                                    } else {
                                                        $promo_update_counter = PromoCodes::find($promos->id);
                                                        $promo_update_counter->uses = $promo_update_counter->uses - 1;
                                                        $promo_update_counter->save();

                                                        $user_promo_entry = new UserPromoUse;
                                                        $user_promo_entry->code_id = $promos->id;
                                                        $user_promo_entry->user_id = $owner_id;
                                                        $user_promo_entry->save();

                                                        $owner = Owner::find($owner_id);
                                                        $owner->promo_count = $owner->promo_count + 1;
                                                        $owner->save();

                                                        $request = Requests::find($request->id);
                                                        $request->promo_id = $promos->id;
                                                        $request->promo_code = $promos->coupon_code;
                                                        $request->save();

                                                        $owner = Owner::find($owner_id);
                                                        $code_data = Ledger::where('owner_id', '=', $owner->id)->first();
                                                        $response_array = array(
                                                            'success' => true,
                                                            'error' => 'Promotional code successfully applied.',
                                                            'id' => $owner->id,
                                                            'first_name' => $owner->first_name,
                                                            'last_name' => $owner->last_name,
                                                            'phone' => $owner->phone,
                                                            'email' => $owner->email,
                                                            'picture' => $owner->picture,
                                                            'bio' => $owner->bio,
                                                            'address' => $owner->address,
                                                            'state' => $owner->state,
                                                            'country' => $owner->country,
                                                            'zipcode' => $owner->zipcode,
                                                            'login_by' => $owner->login_by,
                                                            'social_unique_id' => $owner->social_unique_id,
                                                            'device_token' => $owner->device_token,
                                                            'device_type' => $owner->device_type,
                                                            'token' => $owner->token,
                                                            'referral_code' => $code_data->referral_code,
                                                            'is_referee' => $owner->is_referee,
                                                            'promo_count' => $owner->promo_count,
                                                            'request_id' => $request->id,
                                                        );
                                                        $response_code = 200;
                                                    }
                                                }
                                            } else {
                                                $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 614);
                                                $response_code = 200;
                                            }
                                        } else {
                                            $response_array = array('success' => FALSE, 'error' => 'Promotion feature is not active on cash payment.', 'error_code' => 640);
                                            $response_code = 200;
                                        }
                                    } else {
                                        $response_array = array('success' => FALSE, 'error' => 'Payment mode is paypal', 'error_code' => 641);
                                        $response_code = 200;
                                    }
                                } else {
                                    $response_array = array('success' => FALSE, 'error' => 'Promotion feature is not active.', 'error_code' => 617);
                                    $response_code = 200;
                                }
                            }
                        } else {
                            $response_array = array('success' => FALSE, 'error' => 'You can\'t apply promotional code without creating request.', 'error_code' => 642);
                            $response_code = 200;
                        }
                    } else {
                        $response_array = array('success' => FALSE, 'error' => 'You can\'t apply promotional code without creating request.', 'error_code' => 642);
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    $response_array = array('success' => false, 'error' => 'Owner ID is not Found', 'error_code' => 621);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }

    // test
    public function register()
    {
        $first_name = ucwords(trim(Input::get('first_name')));
        $last_name = ucwords(trim(Input::get('last_name')));
        $email = Input::get('email');
        $phone = Input::get('phone');
        $phone = str_replace('-', '', $phone);
        $password = Input::get('password');
        $picture = "";
        if (Input::hasfile('picture')) {
            $picture = Input::file('picture');
        }
        $settings = Settings::where('key', 'otp_verification')->first();
        $otp_val = $settings->value;
        if (Input::hasfile('otpmode')) {
            $otpmode = true;
        } else {
            $otpmode = false;
        }
        if ($otp_val) {
            if ($otpmode) {
                goto no_otp;
            }
            $otp_status = true;
            $otp_number = rand(100000, 999999);
            $otp_date = date("Y-m-d h:i:s", strtotime("+15 minutes"));
            $is_active = 0;
            $is_otp = 0;
        } else {
            no_otp:
            $otp_status = false;
            $otp_number = '';
            $otp_date = date("Y-m-d h:i:s", strtotime("+15 minutes"));
            $is_active = 1;
            $is_otp = 1;
        }

        $device_token = Input::get('device_token');
        $device_type = Input::get('device_type');
        $bio = "";
        if (Input::has('bio')) {
            $bio = Input::get('bio');
        }
        $address = "";
        if (Input::has('address')) {
            $address = ucwords(trim(Input::get('address')));
        }
        $state = "";
        if (Input::has('state')) {
            $state = ucwords(trim(Input::get('state')));
        }
        $country = "";
        if (Input::has('country')) {
            $country = ucwords(trim(Input::get('country')));
        }
        $zipcode = "";
        if (Input::has('zipcode')) {
            $zipcode = Input::get('zipcode');
        }
        $login_by = Input::get('login_by');
        $social_unique_id = Input::get('social_unique_id');

        if ($password != "" and $social_unique_id == "") {
            $validator = Validator::make(
                array(
                    'password' => $password,
                    'email' => $email,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'picture' => $picture,
                    'device_token' => $device_token,
                    'device_type' => $device_type,
                    'bio' => $bio,
                    'address' => $address,
                    'state' => $state,
                    'country' => $country,
                    'zipcode' => $zipcode,
                    'login_by' => $login_by
                ), array(
                    'password' => 'required',
                    'email' => 'required|email',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    /* 'picture' => 'mimes:jpeg,bmp,png', */
                    'picture' => '',
                    'device_token' => 'required',
                    'device_type' => 'required|in:android,ios',
                    'bio' => '',
                    'address' => '',
                    'state' => '',
                    'country' => '',
                    'zipcode' => '',
                    'login_by' => 'required|in:manual,facebook,google',
                )
            );

            $validatorPhone = Validator::make(
                array(
                    'phone' => $phone,
                ), array(
                    'phone' => 'required|phone'
                )
            );
        } elseif ($social_unique_id != "" and $password == "") {
            $validator = Validator::make(
                array(
                    'email' => $email,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'picture' => $picture,
                    'device_token' => $device_token,
                    'device_type' => $device_type,
                    'bio' => $bio,
                    'address' => $address,
                    'state' => $state,
                    'country' => $country,
                    'zipcode' => $zipcode,
                    'login_by' => $login_by,
                    'social_unique_id' => $social_unique_id
                ), array(
                    'email' => 'required|email',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    /* 'picture' => 'mimes:jpeg,bmp,png', */
                    'picture' => '',
                    'device_token' => 'required',
                    'device_type' => 'required|in:android,ios',
                    'bio' => '',
                    'address' => '',
                    'state' => '',
                    'country' => '',
                    'zipcode' => '',
                    'login_by' => 'required|in:manual,facebook,google',
                    'social_unique_id' => 'required|unique:owner'
                )
            );

            $validatorPhone = Validator::make(
                array(
                    'phone' => $phone,
                ), array(
                    'phone' => 'required|phone'
                )
            );
        } elseif ($social_unique_id != "" and $password != "") {
            $response_array = array('success' => false, 'error' => 'Invalid Input - either social_unique_id or password should be passed', 'error_code' => 600);
            $response_code = 200;
            goto response;
        }

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();

            Log::info('Error while during owner registration = ' . print_r($error_messages, true));
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600, 'error_messages' => $error_messages);
            $response_code = 200;
        } else if ($validatorPhone->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid phone number', 'error_code' => 601, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {

            if (Owner::where('email', '=', $email)->first()) {
                $response_array = array('success' => false, 'error' => 'Already Email ID Register', 'error_code' => 602);
                $response_code = 200;
            } else {

                //check phone
                if (Owner::where('phone', '=', $phone)->first()) {
                    $response_array = array('success' => false, 'error' => 'Already Phone Number Register', 'error_code' => 603);
                    $response_code = 200;
                    goto response;
                }

                /* SEND REFERRAL & PROMO INFO */
                $settings = Settings::where('key', 'referral_code_activation')->first();
                $referral_code_activation = $settings->value;
                if ($referral_code_activation) {
                    $referral_code_activation_txt = "referral on";
                } else {
                    $referral_code_activation_txt = "referral off";
                }

                $settings = Settings::where('key', 'promotional_code_activation')->first();
                $promotional_code_activation = $settings->value;
                if ($promotional_code_activation) {
                    $promotional_code_activation_txt = "promo on";
                } else {
                    $promotional_code_activation_txt = "promo off";
                }
                /* SEND REFERRAL & PROMO INFO */
                $owner = new Owner;
                $owner->first_name = $first_name;
                $owner->last_name = $last_name;
                $owner->email = $email;
                $owner->phone = $phone;
                $owner->otp = $otp_number;
                $owner->is_otp = $is_otp;
                $owner->otp_endtime = $otp_date;
                $owner->is_active = $is_active;
                if ($password != "") {
                    $owner->password = Hash::make($password);
                }
                $owner->token = generate_token();
                $owner->token_expiry = generate_expiry();

                // upload image
                $file_name = time();
                $file_name .= rand();
                $file_name = sha1($file_name);
                if ($picture) {
                    $ext = Input::file('picture')->getClientOriginalExtension();
                    Input::file('picture')->move(public_path() . "/uploads", $file_name . "." . $ext);
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
                    $owner->picture = $s3_url;
                }

                $owner->device_token = $device_token;
                $owner->device_type = $device_type;
                $owner->bio = "";
                if (Input::has('bio'))
                    $owner->bio = $bio;
                $owner->address = "";
                if (Input::has('address'))
                    $owner->address = $address;
                $owner->state = "";
                if (Input::has('state'))
                    $owner->state = $state;
                $owner->login_by = $login_by;
                $owner->country = "";
                if (Input::has('country'))
                    $owner->country = $country;
                $owner->zipcode = "0";
                if (Input::has('zipcode'))
                    $owner->zipcode = $zipcode;
                if ($social_unique_id != "") {
                    $owner->social_unique_id = $social_unique_id;
                }
                $owner->timezone = 'UTC';
                If (Input::has('timezone')) {
                    $owner->timezone = Input::get('timezone');
                }
                $owner->is_referee = 0;
                $owner->promo_count = 0;
                $owner->save();

                /* $zero_in_code = Config::get('app.referral_zero_len') - strlen($owner->id);
                  $referral_code = Config::get('app.referral_prefix');
                  for ($i = 0; $i < $zero_in_code; $i++) {
                  $referral_code .= "0";
                  }
                  $referral_code .= $owner->id; */
                regenerate:
                $referral_code = my_random4_number();
                if (Ledger::where('referral_code', $referral_code)->count()) {
                    goto regenerate;
                }
                /* Referral entry */
                $ledger = new Ledger;
                $ledger->owner_id = $owner->id;
                $ledger->referral_code = $referral_code;
                $ledger->save();
                /* Referral entry end */

                // send email
                /* $subject = "Welcome On Board";
                  $email_data['name'] = $owner->first_name;

                  send_email($owner->id, 'owner', $email_data, $subject, 'userregister'); */

                if ($owner->picture == NULL) {
                    $owner_picture = "";
                } else {
                    $owner_picture = $owner->picture;
                }
                if ($owner->bio == NULL) {
                    $owner_bio = "";
                } else {
                    $owner_bio = $owner->bio;
                }
                if ($owner->address == NULL) {
                    $owner_address = "";
                } else {
                    $owner_address = $owner->address;
                }
                if ($owner->state == NULL) {
                    $owner_state = "";
                } else {
                    $owner_state = $owner->state;
                }
                if ($owner->country == NULL) {
                    $owner_country = "";
                } else {
                    $owner_country = $owner->country;
                }
                if ($owner->zipcode == NULL) {
                    $owner_zipcode = "";
                } else {
                    $owner_zipcode = $owner->zipcode;
                }
                if ($owner->timezone == NULL) {
                    $owner_time = Config::get('app.timezone');
                } else {
                    $owner_time = $owner->timezone;
                }
                $settings = Settings::where('key', 'admin_email_address')->first();
                $admin_email = $settings->value;
                $pattern = array('admin_eamil' => $admin_email, 'name' => ucwords($owner->first_name . " " . $owner->last_name), 'web_url' => web_url());
                $subject = "Welcome to " . ucwords(Config::get('app.website_title')) . ", " . ucwords($owner->first_name . " " . $owner->last_name) . "";
                email_notification($owner->id, 'owner', $pattern, $subject, 'user_register', null);
                if ($otp_status) {
                    $settings = Settings::where('key', 'sms_otp')->first();
                    $admin_message = $settings->value;
                    $message = str_replace('%number%', $otp_number, $admin_message);
                    sms_notification($owner->id, 'owner', $message, "imp");
                    $response_array = array(
                        'success' => true,
                        'user' => array('id' => $owner->id,
                            'first_name' => $owner->first_name,
                            'last_name' => $owner->last_name,
                            'phone' => $owner->phone,
                            'email' => $owner->email,
                            'is_otp' => $owner->is_otp ? true : false,
                            'picture' => $owner_picture,
                            'bio' => $owner_bio,
                            'address' => $owner_address,
                            'state' => $owner_state,
                            'country' => $owner_country,
                            'zipcode' => $owner_zipcode,
                            'login_by' => $owner->login_by,
                            'social_unique_id' => $owner->social_unique_id ? $owner->social_unique_id : "",
                            'device_token' => $owner->device_token,
                            'device_type' => $owner->device_type,
                            'otp_status' => $otp_status,
                            'timezone' => $owner_time,
                            'token' => $owner->token,
                            'referral_code' => $referral_code,
                            'is_referee' => $owner->is_referee,
                            'promo_count' => $owner->promo_count,
                            'is_referral_active' => $referral_code_activation,
                            'is_referral_active_txt' => $referral_code_activation_txt,
                            'is_promo_active' => $promotional_code_activation,
                            'is_promo_active_txt' => $promotional_code_activation_txt,)

                    );

                    $response_code = 200;
                    //$response_code = 210; //otp send response


                } else {
                    $response_array = array(
                        'success' => true,
                        'user' => array('id' => $owner->id,
                            'first_name' => $owner->first_name,
                            'last_name' => $owner->last_name,
                            'phone' => $owner->phone,
                            'email' => $owner->email,
                            'is_otp' => $owner->is_otp ? true : false,
                            'picture' => $owner_picture,
                            'bio' => $owner_bio,
                            'address' => $owner_address,
                            'state' => $owner_state,
                            'country' => $owner_country,
                            'zipcode' => $owner_zipcode,
                            'login_by' => $owner->login_by,
                            'social_unique_id' => $owner->social_unique_id ? $owner->social_unique_id : "",
                            'device_token' => $owner->device_token,
                            'device_type' => $owner->device_type,
                            'otp_status' => $otp_status,
                            'timezone' => $owner_time,
                            'token' => $owner->token,
                            'referral_code' => $referral_code,
                            'is_referee' => $owner->is_referee,
                            'promo_count' => $owner->promo_count,
                            'is_referral_active' => $referral_code_activation,
                            'is_referral_active_txt' => $referral_code_activation_txt,
                            'is_promo_active' => $promotional_code_activation,
                            'is_promo_active_txt' => $promotional_code_activation_txt,)

                    );

                    $response_code = 200;
                }

            }
        }

        response:
        $response_array = $this->setnull('user', $response_array);
        $response = Response::json($response_array, $response_code);
        return $response;
    }


    public function setnull($key = null, $response_array)
    {
        if ($key != null) {
            //check if key is present
            if (array_key_exists($key, $response_array)) {
                return $response_array;
            } else {
                $response_array[$key] = null;
                return $response_array;
            }
        } else {
            return null;
        }
    }

    public function login()
    {
        $login_by = Input::get('login_by');
        $device_token = Input::get('device_token');
        $device_type = Input::get('device_type');

        if (Input::has('email') && Input::has('password')) {
            $email = Input::get('email');
            $password = Input::get('password');
            $validator = Validator::make(
                array(
                    'password' => $password,
                    'email' => $email,
                    'device_token' => $device_token,
                    'device_type' => $device_type,
                    'login_by' => $login_by
                ), array(
                    'password' => 'required',
                    'email' => 'required',
                    'device_token' => 'required',
                    'device_type' => 'required|in:android,ios',
                    'login_by' => 'required|in:manual,facebook,google'
                )
            );

            if ($validator->fails()) {
                $error_messages = $validator->messages()->all();
                $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600, 'error_messages' => $error_messages);
                $response_code = 200;
                Log::error('Validation error during manual login for owner = ' . print_r($error_messages, true));
            } else {
                if ($owner = Owner::where('email', '=', $email)->orWhere('phone', 'LIKE', '%' . $email . '%')->first()) {
                    if (!$owner->is_active) {
                        $response_array = array('success' => false, 'error' => 'User Inactive', 'error_code' => 604);
                        $response_code = 200;
                        goto response;
                    }

                    if (Hash::check($password, $owner->password)) {
                        if ($login_by !== "manual") {
                            $response_array = array('success' => false, 'error' => 'Login by mismatch', 'error_code' => 605);
                            $response_code = 200;
                        } else {
                            if ($owner->device_type != $device_type) {
                                $owner->device_type = $device_type;
                            }
                            if ($owner->device_token != $device_token) {
                                $owner->device_token = $device_token;
                            }
                            $owner->token = generate_token();
                            $owner->token_expiry = generate_expiry();
                            $owner->save();
                            /* SEND REFERRAL & PROMO INFO */
                            $settings = Settings::where('key', 'referral_code_activation')->first();
                            $referral_code_activation = $settings->value;
                            if ($referral_code_activation) {
                                $referral_code_activation_txt = "referral on";
                            } else {
                                $referral_code_activation_txt = "referral off";
                            }

                            $settings = Settings::where('key', 'promotional_code_activation')->first();
                            $promotional_code_activation = $settings->value;
                            if ($promotional_code_activation) {
                                $promotional_code_activation_txt = "promo on";
                            } else {
                                $promotional_code_activation_txt = "promo off";
                            }
                            /* SEND REFERRAL & PROMO INFO */
                            $code_data = Ledger::where('owner_id', '=', $owner->id)->first();

                            $response_array = array(
                                'success' => true,
                                'user' => array('id' => $owner->id,
                                    'first_name' => $owner->first_name,
                                    'last_name' => $owner->last_name,
                                    'phone' => $owner->phone,
                                    'email' => $owner->email,
                                    'is_otp' => $owner->is_otp ? true : false,
                                    'picture' => $owner->picture,
                                    'bio' => $owner->bio,
                                    'address' => $owner->address,
                                    'state' => $owner->state,
                                    'country' => $owner->country,
                                    'zipcode' => $owner->zipcode,
                                    'login_by' => $owner->login_by,
                                    'social_unique_id' => $owner->social_unique_id,
                                    'device_token' => $owner->device_token,
                                    'device_type' => $owner->device_type,
                                    'timezone' => $owner->timezone,
                                    'token' => $owner->token,
                                    'referral_code' => $code_data->referral_code,
                                    'is_referee' => $owner->is_referee,
                                    'promo_count' => $owner->promo_count,
                                    'is_referral_active' => $referral_code_activation,
                                    'is_referral_active_txt' => $referral_code_activation_txt,
                                    'is_promo_active' => $promotional_code_activation,
                                    'is_promo_active_txt' => $promotional_code_activation_txt)

                            );

                            $dog = Dog::find($owner->dog_id);
                            if ($dog !== NULL) {
                                $response_array = array_merge($response_array, array(
                                    'dog_id' => $dog->id,
                                    'age' => $dog->age,
                                    'name' => $dog->name,
                                    'breed' => $dog->breed,
                                    'likes' => $dog->likes,
                                    'image_url' => $dog->image_url,
                                ));
                            }

                            $response_code = 200;
                        }
                    } else {
                        $response_array = array('success' => false, 'error' => 'Invalid Username or Password', 'error_code' => 606);
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Invalid Username or Password', 'error_code' => 606);
                    $response_code = 200;
                }
            }

        } elseif (Input::has('social_unique_id')) {
            $social_unique_id = Input::get('social_unique_id');
            $socialValidator = Validator::make(
                array(
                    'social_unique_id' => $social_unique_id,
                    'device_token' => $device_token,
                    'device_type' => $device_type,
                    'login_by' => $login_by
                ), array(
                    'social_unique_id' => 'required|exists:owner,social_unique_id',
                    'device_token' => 'required',
                    'device_type' => 'required|in:android,ios',
                    'login_by' => 'required|in:manual,facebook,google'
                )
            );

            if ($socialValidator->fails()) {
                $error_messages = $socialValidator->messages();
                Log::error('Validation error during social login for owner = ' . print_r($error_messages, true));
                $error_messages = $socialValidator->messages()->all();
                $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600, 'error_messages' => $error_messages);
                $response_code = 200;
            } else {


                if ($owner = DB::table('owner')->where('social_unique_id', $social_unique_id)->first()) {
                    if (!$owner->is_active) {
                        $response_array = array('success' => false, 'error' => 'User Inactive', 'error_code' => 604);
                        $response_code = 200;
                        goto response;
                    }
                    if (!in_array($login_by, array('facebook', 'google'))) {
                        $response_array = array('success' => false, 'error' => 'Login by mismatch', 'error_code' => 605);
                        $response_code = 200;
                    } else {
                        if ($owner->device_type != $device_type) {
                            $owner->device_type = $device_type;
                            $devic_typ = $device_type;
                        }
                        if ($owner->device_token != $device_token) {
                            $owner->device_token = $device_token;
                            $devic_token = $device_token;
                        }
                        $owner->token_expiry = generate_expiry();
                        $token_ex = generate_expiry();
                        DB::table('owner')
                            ->where('social_unique_id', $social_unique_id)
                            ->update(['token_expiry' => $token_ex]);
                        //$owner->save();

                        $response_array = array(
                            'success' => true,
                            'user' => array('id' => $owner->id,
                                'first_name' => $owner->first_name,
                                'last_name' => $owner->last_name,
                                'phone' => $owner->phone,
                                'email' => $owner->email,
                                'is_otp' => $owner->is_otp ? true : false,
                                'picture' => $owner->picture,
                                'bio' => $owner->bio,
                                'address' => $owner->address,
                                'state' => $owner->state,
                                'country' => $owner->country,
                                'zipcode' => $owner->zipcode,
                                'login_by' => $owner->login_by,
                                'social_unique_id' => $owner->social_unique_id,
                                'device_token' => $owner->device_token,
                                'device_type' => $owner->device_type,
                                'timezone' => $owner->timezone,
                                'token' => $owner->token,
                                'is_referee' => $owner->is_referee)

                        );

                        $dog = Dog::find($owner->dog_id);
                        if ($dog !== NULL) {
                            $response_array = array_merge($response_array, array(
                                'dog_id' => $dog->id,
                                'age' => $dog->age,
                                'name' => $dog->name,
                                'breed' => $dog->breed,
                                'likes' => $dog->likes,
                                'image_url' => $dog->image_url,
                            ));
                        }

                        $response_code = 200;
                    }
                } else {

                    $response_array = array('success' => false, 'error' => 'Not a valid social registration User', 'error_code' => 607);
                    $response_code = 200;
                }
            }
        } else {
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600);
            $response_code = 200;
        }
        response:
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function details()
    {
        if (Request::isMethod('post')) {
            $address = Input::get('address');
            $state = Input::get('state');
            $zipcode = Input::get('zipcode');
            $token = Input::get('token');
            $owner_id = Input::get('id');

            $validator = Validator::make(
                array(
                    'address' => $address,
                    'state' => $state,
                    'zipcode' => $zipcode,
                    'token' => $token,
                    'owner_id' => $owner_id,
                ), array(
                    'address' => 'required',
                    'state' => 'required',
                    'zipcode' => 'required|integer',
                    'token' => 'required',
                    'owner_id' => 'required|integer'
                )
            );

            if ($validator->fails()) {
                $error_messages = $validator->messages()->all();
                $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
                $response_code = 200;
            } else {
                $is_admin = $this->isAdmin($token);
                if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                    // check for token validity
                    if (is_token_active($owner_data->token_expiry) || $is_admin) {
                        // Do necessary operations

                        $owner = Owner::find($owner_data->id);
                        $owner->address = $address;
                        $owner->state = $state;
                        $owner->zipcode = $zipcode;
                        $owner->save();

                        $response_array = array('success' => true);
                        $response_code = 200;
                    } else {
                        $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                        $response_code = 200;
                    }
                } else {
                    if ($is_admin) {
                        /* $var = Keywords::where('id', 2)->first();
                          $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                        $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                    } else {
                        $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                    }
                    $response_code = 200;
                }
            }
        } else {
            //handles get request
            $token = Input::get('token');
            $owner_id = Input::get('id');
            $validator = Validator::make(
                array(
                    'token' => $token,
                    'owner_id' => $owner_id,
                ), array(
                    'token' => 'required',
                    'owner_id' => 'required|integer'
                )
            );

            if ($validator->fails()) {
                $error_messages = $validator->messages()->all();
                $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
                $response_code = 200;
            } else {

                $is_admin = $this->isAdmin($token);
                if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                    // check for token validity
                    if (is_token_active($owner_data->token_expiry) || $is_admin) {

                        $response_array = array(
                            'success' => true,
                            'address' => $owner_data->address,
                            'state' => $owner_data->state,
                            'zipcode' => $owner_data->zipcode,
                        );
                        $response_code = 200;
                    } else {
                        $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                        $response_code = 200;
                    }
                } else {
                    if ($is_admin) {
                        /* $var = Keywords::where('id', 2)->first();
                          $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                        $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                    } else {
                        $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                    }
                    $response_code = 200;
                }
            }
        }

        response:
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function addcardtoken()
    {
        $payment_token = Input::get('payment_token');
        $last_four = Input::get('last_four');
        $token = Input::get('token');
        $owner_id = Input::get('id');
        if (Input::has('card_type')) {
            $card_type = strtoupper(Input::get('card_type'));
        } else {
            $card_type = strtoupper("VISA");
        }
        $validator = Validator::make(
            array(
                'last_four' => $last_four,
                'payment_token' => $payment_token,
                'token' => $token,
                'owner_id' => $owner_id,
            ), array(
                'last_four' => 'required',
                'payment_token' => 'required',
                'token' => 'required',
                'owner_id' => 'required|integer'
            )
        );
        $payments = array();

        if ($validator->fails()) {
            $error_messages = $validator->messages();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600, 'payments' => $payments);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);

            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {

                    try {

                        if (Config::get('app.default_payment') == 'stripe') {

                            Stripe::setApiKey(Config::get('app.stripe_secret_key'));

                            $customer = Stripe_Customer::create(array(
                                    "card" => $payment_token,
                                    "description" => $owner_data->email)
                            );

                            /* Log::info('customer = ' . print_r($customer, true)); */
                            if ($customer) {
                                $card_count = DB::table('payment')->where('owner_id', '=', $owner_id)->count();

                                $customer_id = $customer->id;
                                $payment = new Payment;
                                $payment->owner_id = $owner_id;
                                $payment->customer_id = $customer_id;
                                $payment->last_four = $last_four;
                                $payment->card_type = $card_type;
                                $payment->card_token = $customer->sources->data[0]->id;
                                if ($card_count > 0) {
                                    $payment->is_default = 0;
                                } else {
                                    $payment->is_default = 1;
                                }
                                $payment->save();

                                $payment_data = Payment::where('owner_id', $owner_id)->orderBy('is_default', 'DESC')->get();
                                foreach ($payment_data as $data1) {
                                    $default = $data1->is_default;
                                    if ($default == 1) {
                                        $data['is_default_text'] = "default";
                                    } else {
                                        $data['is_default_text'] = "not_default";
                                    }
                                    $data['id'] = $data1->id;
                                    $data['owner_id'] = $data1->owner_id;
                                    $data['customer_id'] = $data1->customer_id;
                                    $data['last_four'] = $data1->last_four;
                                    $data['card_token'] = $data1->card_token;
                                    $data['card_type'] = $data1->card_type;
                                    $data['card_id'] = $data1->card_token;
                                    $data['is_default'] = $default ? true : false;
                                    array_push($payments, $data);
                                }
                                $response_array = array(
                                    'success' => true,
                                    'payments' => $payments,
                                );
                                $response_code = 200;
                            } else {
                                $payment_data = Payment::where('owner_id', $owner_id)->orderBy('is_default', 'DESC')->get();
                                foreach ($payment_data as $data1) {
                                    $default = $data1->is_default;
                                    if ($default == 1) {
                                        $data['is_default_text'] = "default";
                                    } else {
                                        $data['is_default_text'] = "not_default";
                                    }
                                    $data['id'] = $data1->id;
                                    $data['owner_id'] = $data1->owner_id;
                                    $data['customer_id'] = $data1->customer_id;
                                    $data['last_four'] = $data1->last_four;
                                    $data['card_token'] = $data1->card_token;
                                    $data['card_type'] = $data1->card_type;
                                    $data['card_id'] = $data1->card_token;
                                    $data['is_default'] = $default ? true : false;
                                    array_push($payments, $data);
                                }
                                $response_array = array(
                                    'success' => false,
                                    'error' => 'Could not create client ID',
                                    'error_code' => 618,
                                    'payments' => $payments,
                                );
                                $response_code = 200;
                            }
                        } else {

                            Braintree_Configuration::environment(Config::get('app.braintree_environment'));
                            Braintree_Configuration::merchantId(Config::get('app.braintree_merchant_id'));
                            Braintree_Configuration::publicKey(Config::get('app.braintree_public_key'));
                            Braintree_Configuration::privateKey(Config::get('app.braintree_private_key'));
                            $result = Braintree_Customer::create(array(
                                'paymentMethodNonce' => $payment_token
                            ));
                            Log::info('result = ' . print_r($result, true));
                            if ($result->success) {
                                $card_count = DB::table('payment')->where('owner_id', '=', $owner_id)->count();

                                $customer_id = $result->customer->id;
                                $payment = new Payment;
                                $payment->owner_id = $owner_id;
                                $payment->customer_id = $customer_id;
                                $payment->last_four = $last_four;
                                $payment->card_type = $card_type;
                                $payment->card_token = $result->customer->creditCards[0]->token;
                                if ($card_count > 0) {
                                    $payment->is_default = 0;
                                } else {
                                    $payment->is_default = 1;
                                }
                                $payment->save();

                                $payment_data = Payment::where('owner_id', $owner_id)->orderBy('is_default', 'DESC')->get();
                                foreach ($payment_data as $data1) {
                                    $default = $data1->is_default;
                                    if ($default == 1) {
                                        $data['is_default_text'] = "default";
                                    } else {
                                        $data['is_default_text'] = "not_default";
                                    }
                                    $data['id'] = $data1->id;
                                    $data['owner_id'] = $data1->owner_id;
                                    $data['customer_id'] = $data1->customer_id;
                                    $data['last_four'] = $data1->last_four;
                                    $data['card_token'] = $data1->card_token;
                                    $data['card_type'] = $data1->card_type;
                                    $data['card_id'] = $data1->card_token;
                                    $data['is_default'] = $default ? true : false;
                                    array_push($payments, $data);
                                }

                                $response_array = array(
                                    'success' => true,
                                    'payments' => $payments,
                                );
                                $response_code = 200;
                            } else {
                                $payment_data = Payment::where('owner_id', $owner_id)->orderBy('is_default', 'DESC')->get();
                                foreach ($payment_data as $data1) {
                                    $default = $data1->is_default;
                                    if ($default == 1) {
                                        $data['is_default_text'] = "default";
                                    } else {
                                        $data['is_default_text'] = "not_default";
                                    }
                                    $data['id'] = $data1->id;
                                    $data['owner_id'] = $data1->owner_id;
                                    $data['customer_id'] = $data1->customer_id;
                                    $data['last_four'] = $data1->last_four;
                                    $data['card_token'] = $data1->card_token;
                                    $data['card_type'] = $data1->card_type;
                                    $data['card_id'] = $data1->card_token;
                                    $data['is_default'] = $default ? true : false;
                                    array_push($payments, $data);
                                }
                                $response_array = array(
                                    'success' => false,
                                    'error' => 'Could not create client ID',
                                    'error_code' => 618,
                                    'payments' => $payments,
                                );
                                $response_code = 200;
                            }
                        }
                    } catch (Exception $e) {
                        $response_array = array('success' => false, 'error' => $e, 'error_code' => 619);
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $var = Keywords::where('id', 2)->first();
                      $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 621);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function deletecardtoken()
    {
        $card_id = Input::get('card_id');
        $token = Input::get('token');
        $owner_id = Input::get('id');

        $validator = Validator::make(
            array(
                'card_id' => $card_id,
                'token' => $token,
                'owner_id' => $owner_id,
            ), array(
                'card_id' => 'required',
                'token' => 'required',
                'owner_id' => 'required|integer'
            )
        );

        /* $var = Keywords::where('id', 2)->first(); */

        if ($validator->fails()) {
            $error_messages = $validator->messages();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    if ($payment = Payment::find($card_id)) {
                        if ($payment->owner_id == $owner_id) {

                            $pdn = Payment::where('id', $card_id)->first();
                            $check = trim($pdn->is_default);
                            Payment::find($card_id)->delete();
                            if ($check == 1) {
                                $card_count = DB::table('payment')->where('owner_id', '=', $owner_id)->count();
                                if ($card_count) {
                                    $paymnt = Payment::where('owner_id', $owner_id)->first();
                                    $paymnt->is_default = 1;
                                    $paymnt->save();
                                }
                            }

                            $payments = array();
                            $card_count = DB::table('payment')->where('owner_id', '=', $owner_id)->count();
                            if ($card_count) {
                                $paymnt = Payment::where('owner_id', $owner_id)->orderBy('is_default', 'DESC')->get();
                                foreach ($paymnt as $data1) {
                                    $default = $data1->is_default;
                                    if ($default == 1) {
                                        $data['is_default_text'] = "default";
                                    } else {
                                        $data['is_default_text'] = "not_default";
                                    }
                                    $data['id'] = $data1->id;
                                    $data['customer_id'] = $data1->customer_id;
                                    $data['card_id'] = $data1->card_token;
                                    $data['last_four'] = $data1->last_four;
                                    $data['is_default'] = $default;
                                    array_push($payments, $data);
                                }
                                $response_array = array(
                                    'success' => true,
                                    'payments' => $payments,
                                );
                                $response_code = 200;
                            } else {
                                $response_code = 200;
                                $response_array = array(
                                    'success' => true,
                                    'error' => 'No Card Found',
                                    'error_code' => 623,
                                );
                            }
                        } else {
                            /* $response_array = array('success' => false, 'error' => 'Card ID and ' . $var->keyword . ' ID Doesnot matches', 'error_code' => 440); */
                            $response_array = array('success' => false, 'error' => 'Card ID and ' . Config::get('app.generic_keywords.User') . ' ID Doesnot matches', 'error_code' => 440);
                            $response_code = 200;
                        }
                    } else {
                        $response_array = array('success' => false, 'error' => 'Card not found', 'error_code' => 441);
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function set_referral_code()
    {
        $code = Input::get('referral_code');
        $token = Input::get('token');
        $owner_id = Input::get('id');

        $validator = Validator::make(
            array(
                /* 'code' => $code, */
                'token' => $token,
                'owner_id' => $owner_id,
            ), array(
                /* 'code' => 'required', */
                'token' => 'required',
                'owner_id' => 'required|integer'
            )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations

                    /* $ledger_count = Ledger::where('referral_code', $code)->count();
                      if ($ledger_count > 0) {
                      $response_array = array('success' => false, 'error' => 'This Code already is taken by another user', 'error_code' => 484);
                      } else {
                      $led = Ledger::where('owner_id', $owner_id)->first();
                      if ($led) {
                      $ledger = Ledger::where('owner_id', $owner_id)->first();
                      } else {
                      $ledger = new Ledger;
                      $ledger->owner_id = $owner_id;
                      }
                      $ledger->referral_code = $code;
                      $ledger->save();

                      $response_array = array('success' => true);
                      } */
                    /* $zero_in_code = Config::get('app.referral_zero_len') - strlen($owner_id);
                      $referral_code = Config::get('app.referral_prefix');
                      for ($i = 0; $i < $zero_in_code; $i++) {
                      $referral_code .= "0";
                      }
                      $referral_code .= $owner_id; */
                    regenerate:
                    $referral_code = my_random4_number();
                    if (Ledger::where('referral_code', $referral_code)->count()) {
                        goto regenerate;
                    }
                    /* $referral_code .= my_random4_number(); */
                    if (Ledger::where('owner_id', $owner_id)->count()) {
                        Ledger::where('owner_id', $owner_id)->update(array('referral_code' => $referral_code));
                    } else {
                        $ledger = new Ledger;
                        $ledger->owner_id = $owner_id;
                        $ledger->referral_code = $referral_code;
                        $ledger->save();
                    }
                    /* $ledger = Ledger::where('owner_id', $owner_id)->first();
                      $ledger->referral_code = $code;
                      $ledger->save(); */
                    /* SEND REFERRAL & PROMO INFO */
                    $settings = Settings::where('key', 'referral_code_activation')->first();
                    $referral_code_activation = $settings->value;
                    if ($referral_code_activation) {
                        $referral_code_activation_txt = "referral on";
                    } else {
                        $referral_code_activation_txt = "referral off";
                    }

                    $settings = Settings::where('key', 'promotional_code_activation')->first();
                    $promotional_code_activation = $settings->value;
                    if ($promotional_code_activation) {
                        $promotional_code_activation_txt = "promo on";
                    } else {
                        $promotional_code_activation_txt = "promo off";
                    }
                    /* SEND REFERRAL & PROMO INFO */
                    $response_array = array(
                        'success' => true,
                        'id' => $owner_data->id,
                        'first_name' => $owner_data->first_name,
                        'last_name' => $owner_data->last_name,
                        'phone' => $owner_data->phone,
                        'email' => $owner_data->email,
                        'picture' => $owner_data->picture,
                        'bio' => $owner_data->bio,
                        'address' => $owner_data->address,
                        'state' => $owner_data->state,
                        'country' => $owner_data->country,
                        'zipcode' => $owner_data->zipcode,
                        'login_by' => $owner_data->login_by,
                        'social_unique_id' => $owner_data->social_unique_id,
                        'device_token' => $owner_data->device_token,
                        'device_type' => $owner_data->device_type,
                        'timezone' => $owner_data->timezone,
                        'token' => $owner_data->token,
                        'referral_code' => $referral_code,
                        'is_referee' => $owner_data->is_referee,
                        'promo_count' => $owner_data->promo_count,
                        'is_referral_active' => $referral_code_activation,
                        'is_referral_active_txt' => $referral_code_activation_txt,
                        'is_promo_active' => $promotional_code_activation,
                        'is_promo_active_txt' => $promotional_code_activation_txt,
                    );

                    $response_code = 200;
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $var = Keywords::where('id', 2)->first();
                      $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 621);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function get_referral_code()
    {

        $token = Input::get('token');
        $owner_id = Input::get('id');

        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id,
            ), array(
                'token' => 'required',
                'owner_id' => 'required|integer'
            )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations

                    $ledger = Ledger::where('owner_id', $owner_id)->first();
                    if ($ledger) {
                        $response_array = array(
                            'success' => true,
                            'referral_code' => $ledger->referral_code,
                            'total_referrals' => $ledger->total_referrals,
                            'amount_earned' => $ledger->amount_earned,
                            'amount_spent' => $ledger->amount_spent,
                            'balance_amount' => $ledger->amount_earned - $ledger->amount_spent,
                        );
                    } else {
                        $response_array = array('success' => false, 'error' => 'This user does not have a referral code', 'error_code' => 636);
                    }


                    $response_code = 200;
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $var = Keywords::where('id', 2)->first();
                      $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 621);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function get_cards()
    {

        $token = Input::get('token');
        $owner_id = Input::get('id');
        if (Input::has('card_id')) {
            $card_id = Input::get('card_id');
            Payment::where('owner_id', $owner_id)->update(array('is_default' => 0));
            Payment::where('owner_id', $owner_id)->where('id', $card_id)->update(array('is_default' => 1));
        }

        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id,
            ), array(
                'token' => 'required',
                'owner_id' => 'required|integer'
            )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations
                    $payments = array();
                    $card_count = DB::table('payment')->where('owner_id', '=', $owner_id)->count();
                    if ($card_count) {
                        $paymnt = Payment::where('owner_id', $owner_id)->orderBy('is_default', 'DESC')->get();
                        foreach ($paymnt as $data1) {
                            $default = $data1->is_default;
                            if ($default == 1) {
                                $data['is_default_text'] = "default";
                            } else {
                                $data['is_default_text'] = "not_default";
                            }
                            $data['id'] = $data1->id;
                            $data['owner_id'] = $data1->owner_id;
                            $data['customer_id'] = $data1->customer_id;
                            $data['last_four'] = $data1->last_four;
                            $data['card_token'] = $data1->card_token;
                            $data['card_type'] = $data1->card_type;
                            $data['card_id'] = $data1->card_token;
                            $data['is_default'] = $default ? true : false;
                            array_push($payments, $data);
                        }
                        $response_array = array(
                            'success' => true,
                            'payments' => $payments
                        );
                    } else {
                        $response_array = array(
                            'success' => false,
                            'error' => 'No Card Found',
                            'error_code' => 623
                        );
                    }


                    $response_code = 200;
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $var = Keywords::where('id', 2)->first();
                      $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 621);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function card_selection()
    {

        $token = Input::get('token');
        $owner_id = Input::get('id');
        $default_card_id = Input::get('default_card_id');
        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id,
                'default_card_id' => $default_card_id,
            ), array(
                'token' => 'required',
                'owner_id' => 'required|integer',
                'default_card_id' => 'required'
            )
        );
        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $payments = array();
            /* $payments['none'] = ""; */
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {

                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations
                    Payment::where('owner_id', $owner_id)->update(array('is_default' => 0));
                    Payment::where('owner_id', $owner_id)->where('id', $default_card_id)->update(array('is_default' => 1));
                    $payment_data = Payment::where('owner_id', $owner_id)->orderBy('is_default', 'DESC')->get();
                    foreach ($payment_data as $data1) {
                        $default = $data1->is_default;
                        if ($default == 1) {
                            $data['is_default_text'] = "default";
                        } else {
                            $data['is_default_text'] = "not_default";
                        }
                        $data['id'] = $data1->id;
                        $data['owner_id'] = $data1->owner_id;
                        $data['customer_id'] = $data1->customer_id;
                        $data['last_four'] = $data1->last_four;
                        $data['card_token'] = $data1->card_token;
                        $data['card_type'] = $data1->card_type;
                        $data['is_default'] = $default ? true : false;
                        array_push($payments, $data);
                    }
                    $owner = Owner::find($owner_id);

                    $response_array = array(
                        'success' => true,
                        'id' => $owner->id,
                        'first_name' => $owner->first_name,
                        'last_name' => $owner->last_name,
                        'phone' => $owner->phone,
                        'email' => $owner->email,
                        'picture' => $owner->picture,
                        'bio' => $owner->bio,
                        'address' => $owner->address,
                        'state' => $owner->state,
                        'country' => $owner->country,
                        'zipcode' => $owner->zipcode,
                        'login_by' => $owner->login_by,
                        'social_unique_id' => $owner->social_unique_id,
                        'device_token' => $owner->device_token,
                        'device_type' => $owner->device_type,
                        'token' => $owner->token,
                        'default_card_id' => $default_card_id,
                        'payment_type' => 0,
                        'is_referee' => $owner->is_referee,
                        'promo_count' => $owner->promo_count,
                        'payments' => $payments
                    );


                    $response_code = 200;
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    $response_array = array('success' => false, 'error' => 'Owner ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function get_completed_requests()
    {

        $token = Input::get('token');
        $owner_id = Input::get('id');
        $from = Input::get('from_date'); // 2015-03-11 07:45:01
        $to_date = Input::get('to_date') . ' 23:59:59'; //2015-03-11 07:45:01

        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id,
            ), array(
                'token' => 'required',
                'owner_id' => 'required|integer'
            )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations
                    if ($from != "" && $to_date != "") {
                        $request_data = DB::table('request')
                            ->where('request.owner_id', $owner_id)
                            ->where('is_completed', 1)
                            ->where('is_cancelled', 0)
                            ->whereBetween('request_start_time', array($from, $to_date))
                            ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                            ->leftJoin('walker_services', 'walker.id', '=', 'walker_services.provider_id')
                            ->leftJoin('walker_type', 'walker_type.id', '=', 'walker_services.type')
                            ->leftJoin('request_services', 'request_services.request_id', '=', 'request.id')
                            ->select('request.*', 'request.request_start_time', 'request.promo_code', 'walker.first_name', 'walker.id as walker_id', 'walker.last_name', 'walker.phone', 'walker.email', 'walker.picture', 'walker.bio', 'walker.rate', 'walker_type.name as type', 'walker_type.icon', 'request.distance', 'request.time', 'request_services.base_price as req_base_price', 'request_services.distance_cost as req_dis_cost', 'request_services.time_cost as req_time_cost', 'request_services.type as req_typ', 'request.total','request_services.service_tax')
                            ->groupBy('request.id')
                            ->get();
                    } else {
                        $request_data = DB::table('request')
                            ->where('request.owner_id', $owner_id)
                            ->where('is_completed', 1)
                            ->where('is_cancelled', 0)
                            ->leftJoin('walker', 'request.confirmed_walker', '=', 'walker.id')
                            ->leftJoin('walker_services', 'walker.id', '=', 'walker_services.provider_id')
                            ->leftJoin('walker_type', 'walker_type.id', '=', 'walker_services.type')
                            ->leftJoin('request_services', 'request_services.request_id', '=', 'request.id')
                            ->select('request.*', 'request.request_start_time', 'request.promo_code', 'walker.first_name', 'walker.id as walker_id', 'walker.last_name', 'walker.phone', 'walker.email', 'walker.picture', 'walker.bio', 'walker.rate', 'walker_type.name as type', 'walker_type.icon',  'request.distance', 'request.time', 'request_services.base_price as req_base_price', 'request_services.distance_cost as req_dis_cost', 'request_services.time_cost as req_time_cost', 'request_services.type as req_typ', 'request.total','request_services.service_tax')
                            ->groupBy('request.id')
                            ->get();
                    }

                    $requests = array();

                    $settings = Settings::where('key', 'default_distance_unit')->first();
                    $unit = $settings->value;
                    if ($unit == 0) {
                        $unit_set = 'kms';
                    } elseif ($unit == 1) {
                        $unit_set = 'miles';
                    }

                    /* echo "<pre>";
                     print_r($request_data);
                     exit;

                    /* $currency_selected = Keywords::find(5); */



                    foreach ($request_data as $data) {
                        $request_typ = ProviderType::where('id', '=', $data->req_typ)->first();

                        /*echo "<pre>";
                        print_r($request_typ->price_per_unit_distance);
                        die();*/


                        $setbase_distance = $request_typ->base_distance;
                        $setbase_price = $request_typ->base_price;
                        $setdistance_price = $request_typ->price_per_unit_distance;
                        $settime_price = $request_typ->price_per_unit_time;

                        $locations = WalkLocation::where('request_id', $data->id)->orderBy('id')->get();
                        $count = round(count($locations) / 50);
                        $start = $end = $map = "";
                        $id = $data->id;
                        if (count($locations) >= 1) {
                            $start = WalkLocation::where('request_id', $id)
                                ->orderBy('id')
                                ->first();
                            $end = WalkLocation::where('request_id', $id)
                                ->orderBy('id', 'desc')
                                ->first();
                            $map = "https://maps-api-ssl.google.com/maps/api/staticmap?size=249x249&scale=2&markers=shadow:true|scale:2|icon:http://d1a3f4spazzrp4.cloudfront.net/receipt-new/marker-start@2x.png|$start->latitude,$start->longitude&markers=shadow:false|scale:2|icon:http://d1a3f4spazzrp4.cloudfront.net/receipt-new/marker-finish@2x.png|$end->latitude,$end->longitude&path=color:0x2dbae4ff|weight:4";
                            $skip = 0;
                            foreach ($locations as $location) {
                                if ($skip == $count) {
                                    $map .= "|$location->latitude,$location->longitude";
                                    $skip = 0;
                                }
                                $skip++;
                            }
                            /* $map.="&key=" . Config::get('app.gcm_browser_key'); */
                        }
                        $request['start_lat'] = "";
                        if (isset($start->latitude)) {
                            $request['start_lat'] = $start->latitude;
                        }
                        $request['start_long'] = "";
                        if (isset($start->longitude)) {
                            $request['start_long'] = $start->longitude;
                        }
                        $request['end_lat'] = "";
                        if (isset($end->latitude)) {
                            $request['end_lat'] = $end->latitude;
                        }
                        $request['end_long'] = "";
                        if (isset($end->longitude)) {
                            $request['end_long'] = $end->longitude;
                        }
                        $request['map_url'] = $map;

                        $walker = Walker::where('id', $data->walker_id)->first();

                        if ($walker != NULL) {
                            $user_timezone = $walker->timezone;
                        } else {
                            $user_timezone = 'UTC';
                        }

                        $default_timezone = Config::get('app.timezone');

                        $date_time = get_user_time($default_timezone, $user_timezone, $data->request_start_time);

                        $dist = number_format($data->distance, 2, '.', '');
                        $request['id'] = $data->id;
                        $request['date'] = $date_time;
                        $request['distance'] = (string)$dist;
                        $request['unit'] = $unit_set;
                        $request['time'] = $data->time;
                        $discount = 0;
                        if ($data->promo_code != "") {
                            if ($data->promo_code != "") {
                                $promo_code = PromoCodes::where('id', $data->promo_code)->first();
                                if ($promo_code) {
                                    $promo_value = $promo_code->value;
                                    $promo_type = $promo_code->type;
                                    if ($promo_type == 1) {
                                        // Percent Discount
                                        $discount = $data->total * $promo_value / 100;
                                    } elseif ($promo_type == 2) {
                                        // Absolute Discount
                                        $discount = $promo_value;
                                    }
                                }
                            }
                        }

                        $request['promo_discount'] = currency_converted($discount);



                        $is_multiple_service = Settings::where('key', 'allow_multiple_service')->first();
                        if ($is_multiple_service->value == 0) {

                            if ($data->req_base_price) {
                                $request['base_price'] = currency_converted($data->req_base_price);
                            } else {
                                /* $request['base_price'] = currency_converted($setbase_price->value); */
                                $request['base_price'] = currency_converted($setbase_price);
                            }

                            if ($data->req_dis_cost) {
                                $request['distance_cost'] = currency_converted($data->req_dis_cost);
                            } else {
                                /* $request['distance_cost'] = currency_converted($setdistance_price->value * $data->distance); */
                                if ($data->distance <= $setbase_distance) {
                                    $request['distance_cost'] = 0;
                                } else {
                                    $request['distance_cost'] = currency_converted($setdistance_price * ($data->distance - $setbase_distance));
                                }
                            }

                            if ($data->req_time_cost) {
                                $request['time_cost'] = currency_converted($data->req_time_cost);
                            } else {
                                /* $request['time_cost'] = currency_converted($settime_price->value * $data->time); */
                                $request['time_cost'] = currency_converted($settime_price * $data->time);
                            }
                            $request['setbase_distance'] = $setbase_distance;
                            $request['total'] = $data->total > 0 ? currency_converted($data->total) : "0.00";

                            //Owner spend the Amount of Tips
                            $extraPayment = ExtraPayment::where('request_id', '=', $data->id)->first();
                            $request['tips'] = ( !empty($extraPayment->amount) ? $extraPayment->amount : 0);
                            //Owner spend the Waiting Charge Payment
                            $waitingChargePayment = RequestServices::where('request_id', '=',  $data->id)->first();
                            $request['waiting_price'] = ( !empty($waitingChargePayment->waiting_cost) ? $waitingChargePayment->waiting_cost : 0);
                            $request['waiting_time'] = ( !empty($waitingChargePayment->waiting_time) ? $waitingChargePayment->waiting_time : 0);
                            $request['total_waiting_time'] = ( !empty($waitingChargePayment->total_waiting_time) ? $waitingChargePayment->total_waiting_time : 0);
                            $request['actual_total'] = currency_converted($data->total + $data->ledger_payment + $discount);
                            $request['type'] = $data->type;
                            $request['type_icon'] = $data->icon;
                        } else {
                            $rserv = RequestServices::where('request_id', $data->id)->get();
                            $typs = array();
                            $typi = array();
                            $typp = array();
                            $total_price = 0;

                            foreach ($rserv as $typ) {
                                $typ1 = ProviderType::where('id', $typ->type)->first();
                                $typ_price = ProviderServices::where('provider_id', $data->confirmed_walker)->where('type', $typ->type)->first();

                                if ($typ_price->base_price > 0) {
                                    $typp1 = 0.00;
                                    $typp1 = $typ_price->base_price;
                                } elseif ($typ_price->price_per_unit_distance > 0) {
                                    $typp1 = 0.00;
                                    foreach ($rserv as $key) {
                                        $typp1 = $typp1 + $key->distance_cost;
                                    }
                                } else {
                                    $typp1 = 0.00;
                                }
                                $typs['name'] = $typ1->name;
                                $typs['price'] = currency_converted($typp1);
                                $total_price = $total_price + $typp1;
                                array_push($typi, $typs);
                            }
                            $request['type'] = $typi;
                            $base_price = 0;
                            $distance_cost = 0;
                            $time_cost = 0;
                            foreach ($rserv as $key) {
                                $base_price = $base_price + $key->base_price;
                                $distance_cost = $distance_cost + $key->distance_cost;
                                $time_cost = $time_cost + $key->time_cost;
                            }
                            $request['base_price'] = currency_converted($base_price);
                            $request['distance_cost'] = currency_converted($distance_cost);
                            $request['time_cost'] = currency_converted($time_cost);
                            $request['total'] = currency_converted($total_price);
                        }

                        $rate = WalkerReview::where('request_id', $data->id)->where('walker_id', $data->confirmed_walker)->first();
                        if ($rate != NULL) {
                            $request['walker']['rating'] = $rate->rating;
                        } else {
                            $request['walker']['rating'] = '0.0';
                        }

                        /* $request['currency'] = $currency_selected->keyword; */

                        //Zone Division enable checking
                        $setting_zone = Settings::where('key', 'zone_division')->first();
                        if ($setting_zone->value == 1) {

                            /*$walkerZoneId = (!empty ($data->zone_id) ? $data->zone_id : 0);
                            $zoneTypesRecs = getTypeBesedZoneTyeRecords($data->req_typ,$walkerZoneId);*/

                            $walkLocation = WalkLocation::where('request_id', $data->id)->orderBy('id', 'DESC')->first();
                            $longitude = $walkLocation->longitude;
                            $latitude = $walkLocation->latitude;

                            $walkerZoneId = isCheckAllZonesTypesFindZoneId($longitude,$latitude);

                            //without Walker location table finding zone get the request table zone id field
                            if(empty($walkerZoneId)) {
                                $walkerZoneId = $data->zone_id;
                            }
                            $zoneTypesRecs = getTypeBesedZoneTyeRecords($data->req_typ,$walkerZoneId);

                            $request['base_distance'] = currency_converted($zoneTypesRecs[0]->base_distance);
                            $request['base_price'] = currency_converted($zoneTypesRecs[0]->base_price);
                            $request['distance_price'] = currency_converted($zoneTypesRecs[0]->price_per_unit_distance);
                            $request['price_per_unit_time'] = currency_converted($zoneTypesRecs[0]->price_per_unit_time);

                        } else {

                            $request['base_distance'] = currency_converted($request_typ->base_distance);
                            $request['base_price'] = currency_converted($request_typ->base_price);
                            $request['distance_price'] = currency_converted($request_typ->price_per_unit_distance);
                            $request['price_per_unit_time'] = currency_converted($request_typ->price_per_unit_time);

                        }

                       // $request['distance_price'] = currency_converted($request_typ->price_per_unit_distance);
                        //$request['price_per_unit_time'] = currency_converted($request_typ->price_per_unit_time);
                       // $request['base_price'] = currency_converted($data->req_base_price);

                        $request['distance_cost'] = currency_converted($data->req_dis_cost);
                        $request['time_cost'] = currency_converted($data->req_time_cost);
                        $request['total'] = currency_converted($data->total - $data->ledger_payment - $data->promo_payment);
                        $request['service_tax'] = (!empty($data->service_tax) ? $data->service_tax : 0);
                        $request['main_total'] = currency_converted($data->total);
                        $request['referral_bonus'] = currency_converted($data->ledger_payment);
                        $request['promo_bonus'] = currency_converted($data->promo_payment);
                        $request['payment_type'] = $data->payment_mode;
                        $request['is_paid'] = $data->is_paid;
                        $request['promo_id'] = $data->promo_id;
                        $request['promo_code'] = $data->promo_code;
                        $request['currency'] = Config::get('app.generic_keywords.Currency');
                        $request['walker']['first_name'] = $data->first_name;
                        $request['walker']['last_name'] = $data->last_name;
                        $request['walker']['phone'] = $data->phone;
                        $request['walker']['email'] = $data->email;
                        $request['walker']['picture'] = $data->picture;
                        $request['walker']['bio'] = $data->bio;
                        $request['walker']['type'] = $data->type;
                        /* $request['walker']['rating'] = $data->rate; */
                        array_push($requests, $request);
                    }

                    $response_array = array(
                        'success' => true,
                        'requests' => $requests
                    );

                    $response_code = 200;
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $var = Keywords::where('id', 2)->first();
                      $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 621);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function update_profile()
    {

        $token = Input::get('token');
        $owner_id = Input::get('id');
        $first_name = $last_name = $phone = $password = $picture = $bio = $address = $state = $country = $zipcode = 0;
        if (Input::has('first_name'))
            $first_name = Input::get('first_name');
        if (Input::has('last_name'))
            $last_name = Input::get('last_name');
        if (Input::has('phone'))
            $phone = Input::get('phone');
        if (Input::has('password'))
            $password = Input::get('password');
        if (Input::hasFile('picture'))
            $picture = Input::file('picture');
        if (Input::has('bio'))
            $bio = Input::get('bio');
        if (Input::has('address'))
            $address = Input::get('address');
        if (Input::has('state'))
            $state = Input::get('state');
        if (Input::has('country'))
            $country = Input::get('country');
        if (Input::has('zipcode'))
            $zipcode = Input::get('zipcode');
        $new_password = Input::get('new_password');
        $old_password = Input::get('old_password');
        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id,
                'picture' => $picture,
                'zipcode' => $zipcode
            ), array(
                'token' => 'required',
                'owner_id' => 'required|integer',
                /* 'picture' => 'mimes:jpeg,bmp,png', */
                'picture' => '',
                'zipcode' => 'integer'
            )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    if (Input::get('new_password')) {
                        if (Input::get('old_password') != "") {
                            if (Hash::check($old_password, $owner_data->password)) {
                                // Do necessary operations
                                $owner = Owner::find($owner_id);
                                if ($first_name) {
                                    $owner->first_name = $first_name;
                                }
                                if ($last_name) {
                                    $owner->last_name = $last_name;
                                }
                                if ($phone) {
                                    $owner->phone = $phone;
                                }
                                if ($bio) {
                                    $owner->bio = $bio;
                                }
                                if ($address) {
                                    $owner->address = $address;
                                }
                                if ($state) {
                                    $owner->state = $state;
                                }
                                if ($country) {
                                    $owner->country = $country;
                                }
                                if ($zipcode) {
                                    $owner->zipcode = $zipcode;
                                }
                                if ($new_password) {
                                    $owner->password = Hash::make($new_password);
                                }
                                if (Input::hasFile('picture')) {
                                    if ($owner->picture != "") {
                                        $path = $owner->picture;
                                        Log::info($path);
                                        $filename = basename($path);
                                        Log::info($filename);
                                        if (file_exists($path)) {
                                            unlink(public_path() . "/uploads/" . $filename);
                                        }
                                    }
                                    // upload image
                                    $file_name = time();
                                    $file_name .= rand();
                                    $file_name = sha1($file_name);

                                    $ext = Input::file('picture')->getClientOriginalExtension();
                                    Input::file('picture')->move(public_path() . "/uploads", $file_name . "." . $ext);
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
                                If (Input::has('timezone')) {
                                    $owner->timezone = Input::get('timezone');
                                }
                                $owner->save();
                                $code_data = Ledger::where('owner_id', '=', $owner->id)->first();

                                /* SEND REFERRAL & PROMO INFO */
                                $settings = Settings::where('key', 'referral_code_activation')->first();
                                $referral_code_activation = $settings->value;
                                if ($referral_code_activation) {
                                    $referral_code_activation_txt = "referral on";
                                } else {
                                    $referral_code_activation_txt = "referral off";
                                }

                                $settings = Settings::where('key', 'promotional_code_activation')->first();
                                $promotional_code_activation = $settings->value;
                                if ($promotional_code_activation) {
                                    $promotional_code_activation_txt = "promo on";
                                } else {
                                    $promotional_code_activation_txt = "promo off";
                                }
                                /* SEND REFERRAL & PROMO INFO */

                                $response_array = array(
                                    'success' => true,
                                    'user' => array(
                                        'id' => $owner->id,
                                        'first_name' => $owner->first_name,
                                        'last_name' => $owner->last_name,
                                        'phone' => $owner->phone,
                                        'email' => $owner->email,
                                        'picture' => $owner->picture,
                                        'bio' => $owner->bio,
                                        'address' => $owner->address,
                                        'state' => $owner->state,
                                        'country' => $owner->country,
                                        'zipcode' => intval($owner->zipcode),
                                        'login_by' => $owner->login_by,
                                        'social_unique_id' => $owner->social_unique_id,
                                        'device_token' => $owner->device_token,
                                        'device_type' => $owner->device_type,
                                        'timezone' => $owner->timezone,
                                        'token' => $owner->token,
                                        'referral_code' => $code_data->referral_code,
                                        'is_referee' => $owner->is_referee,
                                        'promo_count' => $owner->promo_count,
                                        'is_referral_active' => $referral_code_activation ? true : false,
                                        'is_referral_active_txt' => $referral_code_activation_txt,
                                        'is_promo_active' => $promotional_code_activation ? true : false,
                                        'is_promo_active_txt' => $promotional_code_activation_txt,)
                                );
                                $response_code = 200;
                            } else {
                                $response_array = array('success' => false, 'error' => 'Invalid Old Password', 'error_code' => 655);
                                $response_code = 200;
                            }
                        } else {
                            $response_array = array('success' => false, 'error' => 'Old Password must not be blank', 'error_code' => 647);
                            $response_code = 200;
                        }
                    } else {
                        // Do necessary operations
                        $owner = Owner::find($owner_id);
                        if ($first_name) {
                            $owner->first_name = $first_name;
                        }
                        if ($last_name) {
                            $owner->last_name = $last_name;
                        }
                        if ($phone) {
                            $owner->phone = $phone;
                        }
                        if ($bio) {
                            $owner->bio = $bio;
                        }
                        if ($address) {
                            $owner->address = $address;
                        }
                        if ($state) {
                            $owner->state = $state;
                        }
                        if ($country) {
                            $owner->country = $country;
                        }
                        if ($zipcode) {
                            $owner->zipcode = $zipcode;
                        }
                        if (Input::hasFile('picture')) {
                            if ($owner->picture != "") {
                                $path = $owner->picture;
                                Log::info($path);
                                $filename = basename($path);
                                Log::info($filename);
                                if (file_exists($path)) {
                                    unlink(public_path() . "/uploads/" . $filename);
                                }
                            }
                            // upload image
                            $file_name = time();
                            $file_name .= rand();
                            $file_name = sha1($file_name);

                            $ext = Input::file('picture')->getClientOriginalExtension();
                            Input::file('picture')->move(public_path() . "/uploads", $file_name . "." . $ext);
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
                        If (Input::has('timezone')) {
                            $owner->timezone = Input::get('timezone');
                        }
                        $owner->save();
                        $code_data = Ledger::where('owner_id', '=', $owner->id)->first();

                        /* SEND REFERRAL & PROMO INFO */
                        $settings = Settings::where('key', 'referral_code_activation')->first();
                        $referral_code_activation = $settings->value;
                        if ($referral_code_activation) {
                            $referral_code_activation_txt = "referral on";
                        } else {
                            $referral_code_activation_txt = "referral off";
                        }

                        $settings = Settings::where('key', 'promotional_code_activation')->first();
                        $promotional_code_activation = $settings->value;
                        if ($promotional_code_activation) {
                            $promotional_code_activation_txt = "promo on";
                        } else {
                            $promotional_code_activation_txt = "promo off";
                        }
                        /* SEND REFERRAL & PROMO INFO */

                        $response_array = array(
                            'success' => true,
                            'user' => array('id' => $owner->id,
                            'first_name' => $owner->first_name,
                            'last_name' => $owner->last_name,
                            'phone' => $owner->phone,
                            'email' => $owner->email,
                            'picture' => $owner->picture,
                            'bio' => $owner->bio,
                            'address' => $owner->address,
                            'state' => $owner->state,
                            'country' => $owner->country,
                            'zipcode' => $owner->zipcode,
                            'login_by' => $owner->login_by,
                            'social_unique_id' => $owner->social_unique_id,
                            'device_token' => $owner->device_token,
                            'device_type' => $owner->device_type,
                            'timezone' => $owner->timezone,
                            'token' => $owner->token,
                            'referral_code' => $code_data->referral_code,
                            'is_referee' => $owner->is_referee,
                            'promo_count' => $owner->promo_count,
                            'is_referral_active' => $referral_code_activation,
                            'is_referral_active_txt' => $referral_code_activation_txt,
                            'is_promo_active' => $promotional_code_activation,
                            'is_promo_active_txt' => $promotional_code_activation_txt,)
                        );
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $var = Keywords::where('id', 2)->first();
                      $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 610);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function payment_type()
    {
        $token = Input::get('token');
        $owner_id = Input::get('id');
        $request_id = Input::get('request_id');
        $cash_or_card = Input::get('cash_or_card');
        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id,
                'cash_or_card' => $cash_or_card,
                'request_id' => $request_id,
            ), array(
                'token' => 'required',
                'owner_id' => 'required|integer',
                'cash_or_card' => 'required',
                'request_id' => 'required',
            )
        );
        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $payments = array();
            /* $payments['none'] = ""; */
            $def_card = 0;
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    if ($cash_or_card != 1) {
                        $card_count = Payment::where('owner_id', '=', $owner_id)->count();
                        if ($card_count <= 0) {
                            $response_array = array('success' => false, 'error' => "Please add card first for payment.", 'error_code' => 417);
                            $response_code = 200;
                            $response = Response::json($response_array, $response_code);
                            return $response;
                        }
                    }
                    // Do necessary operations
                    $owner = Owner::find($owner_id);
                    $payment_data = Payment::where('owner_id', $owner_id)->orderBy('is_default', 'DESC')->get();
                    foreach ($payment_data as $data1) {
                        $default = $data1->is_default;
                        if ($default == 1) {
                            $def_card = $data1->id;
                            $data['is_default_text'] = "default";
                        } else {
                            $data['is_default_text'] = "not_default";
                        }
                        $data['id'] = $data1->id;
                        $data['owner_id'] = $data1->owner_id;
                        $data['customer_id'] = $data1->customer_id;
                        $data['last_four'] = $data1->last_four;
                        $data['card_token'] = $data1->card_token;
                        $data['card_type'] = $data1->card_type;
                        $data['card_id'] = $data1->card_token;
                        $data['is_default'] = $default;
                        array_push($payments, $data);
                    }
                    if ($request = Requests::find($request_id)) {
                        $request->payment_mode = $cash_or_card;
                        $request->save();

                        $walker = Walker::where('id', $request->confirmed_walker)->first();
                        if ($walker) {
                            $msg_array = array();
                            $msg_array['unique_id'] = 3;
                            $response_array = array(
                                'success' => true,
                                'id' => $owner->id,
                                'first_name' => $owner->first_name,
                                'last_name' => $owner->last_name,
                                'phone' => $owner->phone,
                                'email' => $owner->email,
                                'picture' => $owner->picture,
                                'bio' => $owner->bio,
                                'address' => $owner->address,
                                'state' => $owner->state,
                                'country' => $owner->country,
                                'zipcode' => $owner->zipcode,
                                'login_by' => $owner->login_by,
                                'social_unique_id' => $owner->social_unique_id,
                                'device_token' => $owner->device_token,
                                'device_type' => $owner->device_type,
                                'token' => $owner->token,
                                'default_card_id' => $def_card,
                                'payment_type' => $request->payment_mode,
                                'is_referee' => $owner->is_referee,
                                'promo_count' => $owner->promo_count,
                                'payments' => $payments,
                            );
                            $response_array['unique_id'] = 3;
                            $response_code = 200;
                            $msg_array['owner_data'] = $response_array;
                            $title = "Payment Type Change";
                            $message = $msg_array;
                            if ($request->confirmed_walker == $request->current_walker) {
                                send_notifications($request->confirmed_walker, "walker", $title, $message);
                            }
                        } else {
                            $response_array = array('success' => false, 'error' => 'Driver Not Found', 'error_code' => 408);
                            $response_code = 200;
                        }
                    } else {
                        $response_array = array('success' => false, 'error' => 'Solicitud ID no encontrada', 'error_code' => 408);
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    $response_array = array('success' => false, 'error' => 'Owner ID not Found', 'error_code' => 610);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function select_card()
    {
        $token = Input::get('token');
        $owner_id = Input::get('id');
        $card_token = Input::get('card_id');

        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id,
                'card' => $card_token
            ), array(
                'token' => 'required',
                'owner_id' => 'required|integer',
                'card' => 'required|integer'
            )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {

                    Payment::where('owner_id', $owner_id)->update(array('is_default' => 0));
                    Payment::where('owner_id', $owner_id)->where('id', $card_token)->update(array('is_default' => 1));

                    $payments = array();
                    $card_count = DB::table('payment')->where('owner_id', '=', $owner_id)->count();
                    if ($card_count) {
                        $paymnt = Payment::where('owner_id', $owner_id)->orderBy('is_default', 'DESC')->get();
                        foreach ($paymnt as $data1) {
                            $default = $data1->is_default;
                            if ($default == 1) {
                                $data['is_default_text'] = "default";
                            } else {
                                $data['is_default_text'] = "not_default";
                            }
                            $data['id'] = $data1->id;
                            $data['owner_id'] = $data1->owner_id;
                            $data['customer_id'] = $data1->customer_id;
                            $data['last_four'] = $data1->last_four;
                            $data['card_token'] = $data1->card_token;
                            $data['card_type'] = $data1->card_type;
                            $data['card_id'] = $data1->card_token;
                            $data['is_default'] = $default;
                            array_push($payments, $data);
                        }
                        $response_array = array(
                            'success' => true,
                            'payments' => $payments
                        );
                    } else {
                        $response_array = array(
                            'success' => false,
                            'error' => 'No Card Found'
                        );
                    }
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $var = Keywords::where('id', 2)->first();
                      $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function pay_debt()
    {
        $token = Input::get('token');
        $owner_id = Input::get('id');

        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id
            ), array(
                'token' => 'required',
                'owner_id' => 'required|integer'
            )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    $total = $owner_data->debt;
                    if ($total == 0) {
                        $response_array = array('success' => true);
                        $response_code = 200;
                        $response = Response::json($response_array, $response_code);
                        return $response;
                    }
                    $payment_data = Payment::where('owner_id', $owner_id)->where('is_default', 1)->first();
                    if (!$payment_data)
                        $payment_data = Payment::where('owner_id', $request->owner_id)->first();

                    if ($payment_data) {
                        $customer_id = $payment_data->customer_id;

                        if (Config::get('app.default_payment') == 'stripe') {
                            Stripe::setApiKey(Config::get('app.stripe_secret_key'));

                            try {
                                Stripe_Charge::create(array(
                                        "amount" => $total * 100,
                                        "currency" => "usd",
                                        "customer" => $customer_id)
                                );
                            } catch (Stripe_InvalidRequestError $e) {
                                // Invalid parameters were supplied to Stripe's API
                                $ownr = Owner::find($owner_id);
                                $ownr->debt = $total;
                                $ownr->save();
                                $response_array = array('error' => $e->getMessage());
                                $response_code = 200;
                                $response = Response::json($response_array, $response_code);
                                return $response;
                            }
                            $owner_data->debt = 0;
                            $owner_data->save();
                        } else {
                            $amount = $total;
                            Braintree_Configuration::environment(Config::get('app.braintree_environment'));
                            Braintree_Configuration::merchantId(Config::get('app.braintree_merchant_id'));
                            Braintree_Configuration::publicKey(Config::get('app.braintree_public_key'));
                            Braintree_Configuration::privateKey(Config::get('app.braintree_private_key'));
                            $card_id = $payment_data->card_token;
                            $result = Braintree_Transaction::sale(array(
                                'amount' => $amount,
                                'paymentMethodToken' => $card_id
                            ));

                            Log::info('result = ' . print_r($result, true));
                            if ($result->success) {
                                $owner_data->debt = $total;
                            } else {
                                $owner_data->debt = 0;
                            }
                            $owner_data->save();
                        }
                    }
                    $response_array = array('success' => true);
                    $response_code = 200;
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $var = Keywords::where('id', 2)->first();
                      $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function paybypaypal()
    {
        $token = Input::get('token');
        $owner_id = Input::get('id');
        $request_id = Input::get('request_id');
        $paypal_id = Input::get('paypal_id');

        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id,
                'paypal_id' => $paypal_id
            ), array(
                'token' => 'required',
                'owner_id' => 'required|integer',
                'paypal_id' => 'required'
            )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    Log::info('paypal_id = ' . print_r($paypal_id, true));
                    $req = Requests::find($request_id);
                    Log::info('req = ' . print_r($req, true));
                    $req->is_paid = 1;
                    $req->payment_id = $paypal_id;
                    $req->save();
                    $response_array = array('success' => true);
                    $response_code = 200;
                }
            }
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function paybybitcoin()
    {
        // $token = Input::get('token');
        // $owner_id = Input::get('id');
        // $request_id = Input::get('request_id');
        // $validator = Validator::make(
        // 	array(
        // 		'token' => $token,
        // 		'owner_id' => $owner_id,
        // 	),
        // 	array(
        // 		'token' => 'required',
        // 		'owner_id' => 'required|integer',
        // 	)
        // );
        // if ($validator->fails()) {
        // 	$error_messages = $validator->messages()->all();
        // 		$response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages );
        // 		$response_code = 200;
        // } else {
        // 	$is_admin = $this->isAdmin($token);
        // 	if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
        // 		// check for token validity
        // 		if (is_token_active($owner_data->token_expiry) || $is_admin) {
        $coinbaseAPIKey = Config::get('app.coinbaseAPIKey');
        $coinbaseAPISecret = Config::get('app.coinbaseAPISecret');
        // coinbase
        $coinbase = Coinbase::withApiKey($coinbaseAPIKey, $coinbaseAPISecret);
        // $balance = $coinbase->getBalance() . " BTC";
        $user = $coinbase->getUser();
        // $contacts = $coinbase->getContacts("user");
        // $currencies = $coinbase->getCurrencies();
        // $rates = $coinbase->getExchangeRate();
        // $paymentButton = $coinbase->createButton(
        //     "Request ID",
        //     "19.99", 
        //     "USD", 
        //     "TRACKING_CODE_1", 
        //     array(
        //            "description" => "My 19.99 USD donation to PL",
        //            "cancel_url" => "http://localhost:8000/user/acceptbitcoin",
        //            "success_url" => "http://localhost:8000/user/acceptbitcoin"
        //        )
        // );

        Log::info('user = ' . print_r($user, true));

        $response_array = array('success' => true);
        // 		}else{
        // 			$response_array = array('success' => false);
        // 			Log::error('1');
        // 		}
        // 	}else{
        // 		$response_array = array('success' => false);
        // 		Log::error('2');
        // 	}
        // }
        $response_code = 200;
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function acceptbitcoin()
    {
        $response = Input::get('response');
        /*
          Sample Response
          {
          "order": {
          "id": "5RTQNACF",
          "created_at": "2012-12-09T21:23:41-08:00",
          "status": "completed",
          "event": {
          "type": "completed"
          },
          "total_btc": {
          "cents": 100000000,
          "currency_iso": "BTC"
          },
          "total_native": {
          "cents": 1253,
          "currency_iso": "USD"
          },
          "total_payout": {
          "cents": 2345,
          "currency_iso": "USD"
          },
          "custom": "order1234",
          "receive_address": "1NhwPYPgoPwr5hynRAsto5ZgEcw1LzM3My",
          "button": {
          "type": "buy_now",
          "name": "Alpaca Socks",
          "description": "The ultimate in lightweight footwear",
          "id": "5d37a3b61914d6d0ad15b5135d80c19f"
          },
          "transaction": {
          "id": "514f18b7a5ea3d630a00000f",
          "hash": "4a5e1e4baab89f3a32518a88c31bc87f618f76673e2cc77ab2127b7afdeda33b",
          "confirmations": 0
          },
          "refund_address": "1HcmQZarSgNuGYz4r7ZkjYumiU4PujrNYk"
          },
          "customer": {
          "email": "coinbase@example.com",
          "shipping_address": [
          "John Smith",
          "123 Main St.",
          "Springfield, OR 97477",
          "United States"
          ]
          }
          }
         */
        Log::info('response = ' . print_r($response, true));
        return Response::json(200, $response);
    }

    public function send_eta()
    {
        $token = Input::get('token');
        $owner_id = Input::get('id');
        $phones = Input::get('phone');
        $request_id = Input::get('request_id');
        $eta = Input::get('eta');

        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id,
                'phones' => $phones,
                'eta' => $eta,
            ), array(
                'token' => 'required',
                'phones' => 'required',
                'owner_id' => 'required|integer',
                'eta' => 'required'
            )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // If phones is not an array
                    if (!is_array($phones)) {
                        $phones = explode(',', $phones);
                    }

                    Log::info('phones = ' . print_r($phones, true));

                    foreach ($phones as $key) {

                        $owner = Owner::where('id', $owner_id)->first();
                        $secret = str_random(6);

                        $request = Requests::where('id', $request_id)->first();
                        $request->security_key = $secret;
                        $request->save();
                        $msg = $owner->first_name . ' ' . $owner->last_name . ' ETA : ' . $eta;
                        send_eta($key, $msg);
                        Log::info('Send ETA MSG  = ' . print_r($msg, true));
                    }

                    $response_array = array('success' => true);
                    $response_code = 200;
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $var = Keywords::where('id', 2)->first();
                      $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function payment_options_allowed()
    {
        $token = Input::get('token');
        $owner_id = Input::get('id');

        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id,
            ), array(
                'token' => 'required',
                'owner_id' => 'required|integer'
            )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry)) {
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
                    /* SEND REFERRAL & PROMO INFO */
                    $settings = Settings::where('key', 'referral_code_activation')->first();
                    $referral_code_activation = $settings->value;
                    if ($referral_code_activation) {
                        $referral_code_activation_txt = "referral on";
                    } else {
                        $referral_code_activation_txt = "referral off";
                    }

                    $settings = Settings::where('key', 'promotional_code_activation')->first();
                    $promotional_code_activation = $settings->value;
                    if ($promotional_code_activation) {
                        $promotional_code_activation_txt = "promo on";
                    } else {
                        $promotional_code_activation_txt = "promo off";
                    }
                    /* SEND REFERRAL & PROMO INFO */

                    // Promo code allowed
                    /* $promosett = Settings::where('key', 'promo_code')->first(); */
                    if ($promotional_code_activation == 1) {
                        $promo_allow = 1;
                    } else {
                        $promo_allow = 0;
                    }

                    $response_array = array(
                        'success' => true,
                        'payment_options' => $payment_options,
                        'promo_allow' => $promo_allow,
                        'is_referral_active' => $referral_code_activation,
                        'is_referral_active_txt' => $referral_code_activation_txt,
                        'is_promo_active' => $promotional_code_activation,
                        'is_promo_active_txt' => $promotional_code_activation_txt,
                    );
                } else {
                    /* $var = Keywords::where('id', 2)->first();
                      $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                }
            } else {
                $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
            }
            $response_code = 200;
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function get_credits()
    {
        $token = Input::get('token');
        $owner_id = Input::get('id');
        $validator = Validator::make(
            array(
                'token' => $token,
                'owner_id' => $owner_id,
            ), array(
                'token' => 'required',
                'owner_id' => 'required|integer'
            )
        );
        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry)) {
                    /* $currency_selected = Keywords::find(5); */
                    $ledger = Ledger::where('owner_id', $owner_id)->first();
                    if ($ledger) {
                        $credits['balance'] = currency_converted($ledger->amount_earned - $ledger->amount_spent);
                        /* $credits['currency'] = $currency_selected->keyword; */
                        $credits['currency'] = Config::get('app.generic_keywords.Currency');
                        $response_array = array('success' => true, 'credits' => $credits);
                    } else {
                        $response_array = array('success' => false, 'error' => 'No Credit Found', 'error_code' => 475);
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                }
            } else {
                $response_array = array('success' => false, 'error' => 'User Not Found', 'error_code' => 402);
            }
            $response_code = 200;
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function logout()
    {
        if (Request::isMethod('post')) {
            $token = Input::get('token');
            $owner_id = Input::get('id');

            $validator = Validator::make(
                array(
                    'token' => $token,
                    'owner_id' => $owner_id,
                ), array(
                    'token' => 'required',
                    'owner_id' => 'required|integer'
                )
            );

            if ($validator->fails()) {
                $error_messages = $validator->messages()->all();
                $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 600, 'error_messages' => $error_messages);
                $response_code = 200;
            } else {
                $is_admin = $this->isAdmin($token);
                if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                    // check for token validity
                    if (is_token_active($owner_data->token_expiry) || $is_admin) {

                        $owner_data->latitude = 0;
                        $owner_data->longitude = 0;
                        $owner_data->device_token = 0;
                        /* $owner_data->is_login = 0; */
                        $owner_data->save();

                        $response_array = array('success' => true, 'error' => 'Successfully Log-Out');
                        $response_code = 200;
                    } else {
                        $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 620);
                        $response_code = 200;
                    }
                } else {
                    if ($is_admin) {
                        $response_array = array('success' => false, 'error' => 'Owner ID not Found', 'error_code' => 621);
                    } else {
                        $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 622);
                    }
                    $response_code = 200;
                }
            }
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function checkotp()
    {
        $ownerid = Input::get('ownerid');
        $otpkey = Input::get('otpkey');
        $owner = DB::table('owner')->where('id', $ownerid)->first();
        if ($owner) {

            if ($owner->otp == $otpkey) {
                $date = date('Y-m-d h:i:s');
                $datetime1 = new DateTime($date);
                $datetime2 = new DateTime($owner->otp_endtime);
                $interval = $datetime2->diff($datetime1);
                $elapsed = $interval->format('%i');

                /*print_r($datetime1);

                print_r($datetime2);

                print_r($interval);

                print_r($elapsed);
                die();*/

                if ($elapsed <= 15) {

                    DB::table('owner')
                        ->where('id', $ownerid)
                        ->update(array('is_active' => 1, 'is_otp' => 1));

                    /* SEND REFERRAL & PROMO INFO */
                    $settings = Settings::where('key', 'referral_code_activation')->first();
                    $referral_code_activation = $settings->value;
                    if ($referral_code_activation) {
                        $referral_code_activation_txt = "referral on";
                    } else {
                        $referral_code_activation_txt = "referral off";
                    }

                    $settings = Settings::where('key', 'promotional_code_activation')->first();
                    $promotional_code_activation = $settings->value;
                    if ($promotional_code_activation) {
                        $promotional_code_activation_txt = "promo on";
                    } else {
                        $promotional_code_activation_txt = "promo off";
                    }
                    /* SEND REFERRAL & PROMO INFO */
                    $ledger = DB::table('ledger')->where('owner_id', $ownerid)->first();
                    if ($ledger) {
                        $referral_code = $ledger->referral_code;
                    } else {
                        $referral_code = '';
                    }

                    $response_array = array(
                        'success' => true,
                        'user' => array('id' => $owner->id,
                            'first_name' => $owner->first_name,
                            'last_name' => $owner->last_name,
                            'phone' => $owner->phone,
                            'email' => $owner->email,
                            'is_otp' => true,
                            'picture' => $owner->picture,
                            'bio' => $owner->bio,
                            'address' => $owner->address,
                            'state' => $owner->state,
                            'country' => $owner->country,
                            'zipcode' => $owner->zipcode,
                            'login_by' => $owner->login_by,
                            'social_unique_id' => $owner->social_unique_id ? $owner->social_unique_id : "",
                            'device_token' => $owner->device_token,
                            'device_type' => $owner->device_type,
                            'timezone' => $owner->timezone,
                            'token' => $owner->token,
                            'referral_code' => $referral_code,
                            'is_referee' => $owner->is_referee,
                            'promo_count' => $owner->promo_count,
                            'is_referral_active' => $referral_code_activation,
                            'is_referral_active_txt' => $referral_code_activation_txt,
                            'is_promo_active' => $promotional_code_activation,
                            'is_promo_active_txt' => $promotional_code_activation_txt)

                    );

                } else {
                    $response_array = array('success' => false, 'error' => 'OTP code time exceeds', 'error_code' => 608); //otp time exceeds
                }
            } else {
                $response_array = array('success' => false, 'error' => 'OTP code is incorrect', 'error_code' => 609);  //otp wrong
            }
        } else {
            $response_array = array('success' => false, 'error' => 'Owner ID not Found', 'error_code' => 610);  //owner not found
        }
        $response_code = 200;
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function deleteuser()
    {
        $ownerid = Input::get('ownerid');
        if (DB::table('owner')->where('id', '=', $ownerid)->delete()) {
            $response_array = array('success' => true);
        } else {
            $response_array = array('success' => false, 'error' => "User doesn't Exit", 'error_code' => 610);
        }
        $response_code = 200;
        $response = Response::json($response_array, $response_code);
        return $response;
    }


    public function otpresend()
    {
        $ownerid = Input::get('ownerid');
        $otp_number = rand(100000, 999999);
        $otp_date = date("Y-m-d h:i:s", strtotime("+15 minutes"));
        DB::table('owner')
            ->where('id', $ownerid)
            ->update(array('otp' => $otp_number, 'otp_endtime' => $otp_date));
        $settings = Settings::where('key', 'sms_otp')->first();
        $admin_message = $settings->value;
        $message = str_replace('%number%', $otp_number, $admin_message);
        sms_notification($ownerid, 'owner', $message, "imp");

        $response_array = array('success' => true);
        $response_code = 200;
        $response = Response::json($response_array, $response_code);
        return $response;
    }


    public function splash()
    {
        $ownerid = Input::get('ownerid');
        $type = Input::get('type');
        $theme = DB::table('theme')->first();


        if ($type == 'Android') {

            if ($owner = DB::table('owner')->whereRaw('(cur_splash = "" OR NOT(cur_splash="' . $theme->splash_screen_and . '")) AND id = "' . $ownerid . '" ')->first()) {
                $imagedata = file_get_contents("uploads/" . $theme->splash_screen_and);
                $base64 = base64_encode($imagedata);
                $src = 'data: ' . mime_content_type("uploads/" . $theme->splash_screen_and) . ';base64,' . $base64;
                $response_array = array('success' => true, 'image' => $src, 'type' => mime_content_type("uploads/" . $theme->splash_screen_and), 'string' => $base64);
                $response_code = 200;
                DB::table('owner')
                    ->where('id', $owner->id)
                    ->update(['cur_splash' => $theme->splash_screen_and]);

            } else {
                $response_array = array('success' => false);
                $response_code = 200;
            }
        } else {

            if ($owner = DB::table('owner')->whereRaw('(cur_splash = "" OR NOT(cur_splash="' . $theme->splash_screen_and . '")) AND id = "' . $ownerid . '" ')->first()) {
                $imagedata = file_get_contents("uploads/" . $theme->splash_screen_and);
                $base64 = base64_encode($imagedata);
                $src = 'data: ' . mime_content_type("uploads/" . $theme->splash_screen_and) . ';base64,' . $base64;
                $response_array = array('success' => true, 'image' => $src, 'type' => mime_content_type("uploads/" . $theme->splash_screen_and), 'string' => $base64);
                $response_code = 200;
                DB::table('owner')
                    ->where('id', $owner->id)
                    ->update(['cur_splash' => $theme->splash_screen_and]);

            } else {
                $response_array = array('success' => false);
                $response_code = 200;
            }

        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function  tips(){

        $token = Input::get('token');
        $owner_id = Input::get('id');
        $request_id = Input::get('request_id');
        $tips = Input::get('tips');

        $validator = Validator::make(
            array(
                'owner_id' => $owner_id,
                'token' => $token,
                'request_id' => $request_id,
                'tips' => $tips,
            ), array(
                'owner_id' => 'required|integer',
                'token' => 'required',
                'request_id' => 'required|integer',
                'tips' => 'required|integer',
            )
        );

        if ($validator->fails()) {

            $errorMessages = $validator->messages()->all();

            $responseArray = array('success' => false, 'error' => Config::get('app.errorKeywords.input_invalid'), 'error_code' => 401, 'error_messages' => $errorMessages);

            $responseCode = 200;

        } else {

            $isAdmin = $this->isAdmin($token);

            if ($owner_data = $this->getOwnerData($owner_id, $token, $isAdmin))  {

                if ( is_token_active($owner_data->token_expiry) || $isAdmin ) {

                    $request = Requests::where('id', '=', $request_id)->first();

                    // Payment Mode : 1 = CASH, 0 = CARD
                    if($request->payment_mode == 1) {

                        $extraPayment = new ExtraPayment;
                        $extraPayment->request_id = $request_id;
                        $extraPayment->reaseon = "Tips";
                        $extraPayment->amount = $tips;
                        $extraPayment->save();

                        $responseCode = 200;
                        $responseArray = array(
                            'success' => true,
                        );

                    } else {

                        $payment_data = Payment::where('owner_id', $request->owner_id)->where('is_default', 1)->first();
                        if (!$payment_data)
                            $payment_data = Payment::where('owner_id', $request->owner_id)->first();

                        if ($payment_data) {
                            $customer_id = $payment_data->customer_id;

                            if (Config::get('app.default_payment') == 'stripe') {

                                Stripe::setApiKey(Config::get('app.stripe_secret_key'));
                                try {

                                    $charge = \Stripe\Charge::create(array(
                                            "amount" => $tips,
                                            "currency" => "usd",
                                            "customer" => $customer_id)
                                    );

                                    if ($charge->paid) {

                                        $extraPayment = new ExtraPayment;
                                        $extraPayment->request_id = $request_id;
                                        $extraPayment->reaseon = "Tips";
                                        $extraPayment->amount = $tips;
                                        $extraPayment->save();

                                        $responseCode = 200;
                                        $responseArray = array(
                                            'success' => true,
                                        );

                                        $response = Response::json($responseArray, $responseCode);
                                        return $response;

                                    } else {

                                        $responseCode = 200;
                                        $responseArray = array(
                                            'success' => false,
                                            'error' => 'Credit Card Payment Fail',
                                            'error_code' => 405,
                                        );

                                        $response = Response::json($responseArray, $responseCode);
                                        return $response;
                                    }

                                } catch (Stripe_InvalidRequestError $e) {
                                    $responseCode = 200;
                                    $responseArray = array(
                                        'success' => false,
                                        'error' => 'Credit Card Payment Fail',
                                        'error_code' => 405,
                                    );

                                    $response = Response::json($responseArray, $responseCode);
                                    return $response;
                                }

                            } else {
                                try {
                                    Braintree_Configuration::environment(Config::get('app.braintree_environment'));
                                    Braintree_Configuration::merchantId(Config::get('app.braintree_merchant_id'));
                                    Braintree_Configuration::publicKey(Config::get('app.braintree_public_key'));
                                    Braintree_Configuration::privateKey(Config::get('app.braintree_private_key'));

                                    $result = Braintree_Transaction::sale(array(
                                        'amount' => $tips,
                                        'customerId' => $customer_id,
                                        //'paymentMethodNonce' => $nonce
                                    ));

                                    /* echo $result->success."<br/>";
                                    echo $tips."|".$customer_id."<br/>";
                                    echo "<pre>";
                                    print_r($result);
                                    exit; */

                                    if ($result->success == 1) {

                                        $extraPayment = new ExtraPayment;
                                        $extraPayment->request_id = $request_id;
                                        $extraPayment->reaseon = "Tips";
                                        $extraPayment->amount = $tips;
                                        $extraPayment->save();

                                        $responseCode = 200;
                                        $responseArray = array(
                                            'success' => true,
                                        );

                                        $response = Response::json($responseArray, $responseCode);
                                        return $response;

                                    } else {

                                        $responseCode = 200;
                                        $responseArray = array(
                                            'success' => false,
                                            'error' => 'Credit Card Payment Fail',
                                            'error_code' => 405,
                                        );

                                        $response = Response::json($responseArray, $responseCode);
                                        return $response;
                                    }

                                } catch (Exception $e) {
                                    $responseCode = 200;
                                    $responseArray = array(
                                        'success' => false,
                                        'error' => 'Credit Card Payment Fail',
                                        'error_code' => 405,
                                    );

                                    $response = Response::json($responseArray, $responseCode);
                                    return $response;
                                }

                            } //Brain tree Payment

                        }

                    }

                } else {

                    $responseArray = array('success' => false, 'error' => Config::get('app.errorKeywords.token_expired'), 'error_code' => 405);

                    $responseCode = 200;

                } //Token Expired

            } else {

                if ($isAdmin) {

                    $responseArray = array('success' => false, 'error' => Config::get('app.errorKeywords.owner_id_not_found'), 'error_code' => 410);

                } else {

                    $responseArray = array('success' => false, 'error' => Config::get('app.errorKeywords.token_invalid'), 'error_code' => 406);

                }

                $responseCode = 200;

            } // Get Owner data checking

        } //Validation Fails

        $response = Response::json($responseArray, $responseCode);

        return $response;

    }

}
