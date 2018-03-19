<?php

class ApplicationController extends BaseController
{

    public function pages()
    {
        $informations = Information::all();
        $informations_array = array();
        foreach ($informations as $information) {
            $data = array();
            $data['id'] = $information->id;
            $data['title'] = $information->title;
            $data['content'] = $information->content;
            $data['icon'] = $information->icon;
            array_push($informations_array, $data);
        }
        $response_array = array();
        $response_array['success'] = true;
        $response_array['informations'] = $informations_array;
        $response_code = 200;
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function get_page()
    {
        $id = Request::segment(3);
        $information = Information::find($id);
        $response_array = array();
        if ($information) {
            $response_array['success'] = true;
            $response_array['title'] = $information->title;
            $response_array['content'] = $information->content;
            $response_array['icon'] = $information->icon;
        } else {
            $response_array['success'] = false;
        }
        $response_code = 200;
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function zonetypes() {

        $latitude=Input::get('latitude');
        $longitude=Input::get('longitude');

        $validator=Validator::make(
            array(
                'latitude'=>$latitude,
                'longitude'=>$longitude

            ),array(
            'latitude'=>'required',
            'longitude'=>'required'

        ));



        if($validator->fails()){
            $response_array = array();
            $response_array['success'] = false;
            $response_array['error'] =$validator->messages()->first();
            $response_array['error_code'] = 600;
            $response_code = 200;
            $response = Response::json($response_array, $response_code);
            return $response;
        }else{


            $points = array();
            $points = $longitude . " " . $latitude;

            //Zone Division enable checking
            $setting_zone = Settings::where('key', 'zone_division')->first();

            if ($setting_zone->value == 1) {
                $zoneRecords = array();
                $result = array();
                $zoneRecords = getAllZoneList();

                /*  echo "<pre>";
                  print_r($zoneRecords);
                  die("s");*/

                if (empty($zoneRecords)) {

                    $response_array = array('success' => false, 'error' => 'No Service Available','error_code' => 416);

                    $response_code = 200;

                    return Response::json($response_array, $response_code);

                } else {

                    $zone = false;

                    foreach ($zoneRecords as $key => $zoneList) {
                        $results['id'] = '';
                        $longitudeZoneArray = array();
                        $latitudeZoneArray = array();

                        $longitudeZoneArray = zoneLongitudeArrays($zoneList->zone_json);
                        $latitudeZoneArray = zoneLatitudeArrays($zoneList->zone_json);

                        $zoneCoordinates = array_map("zoneCoordinates", $longitudeZoneArray, $latitudeZoneArray);

                        /*print_r($zoneCoordinates);
                        die();*/

                        $pointLocation = new pointLocation();


                        // The last point's coordinates must be the same as the first one's, to "close the loop"

                        /*                        print_r($pointLocation->pointInPolygon($points, $zoneCoordinates));
                                                die("m");*/


                        if ($pointLocation->pointInPolygon($points, $zoneCoordinates)) {

                            $result['id'] = $zoneList->id;
                            $zones = true;
                            break;

                        }/*else{
                            echo "s".' '."<br>";

                        }*/
                    }
                    /*print_r($result['id']);

                    die("l");*/
                    if(!empty($result['id'])){
                        $walker_type=DB::table('walker_type')
                            ->join('zone_type','walker_type.id','=','zone_type.type');

                        // $zone_type=ZoneType::where('zone_id',$result['id'])->whereNull('deleted_at')->get();

                        $zone_type=$walker_type
                            ->select(DB::raw('walker_type.id,walker_type.name,walker_type.icon,walker_type.is_default,zone_type.max_size,zone_type.price_per_unit_time,zone_type.price_per_unit_distance,zone_type.base_price,zone_type.base_distance'))
                            ->where('zone_type.zone_id',$result['id'])
                            ->where('zone_type.is_visible',1)
                            ->whereNull('zone_type.deleted_at')->get();


                        if(count($zone_type) < 1 || !empty($zone_type)){


                            $types=$zone_type;
                            $type_array = array();
                            $settunit = Settings::where('key', 'default_distance_unit')->first();
                            $unit = $settunit->value;
                            if ($unit == 0) {
                                $unit_set = 'kms';
                            } elseif ($unit == 1) {
                                $unit_set = 'miles';
                            }
                            /* $currency_selected = Keywords::find(5); */


                            $settings = Settings::where('key', 'default_search_radius')->first();
                            $distance = $settings->value;
                            $settings = Settings::where('key', 'default_distance_unit')->first();
                            $unit = $settings->value;
                            if ($unit == 0) {
                                $multiply = 1.609344;
                            } elseif ($unit == 1) {
                                $multiply = 1;
                            }


                            foreach ($types as $type) {
                                $data = array();
                                $data['id'] = $type->id;
                                $data['name'] = $type->name;
                                $data['min_fare'] = $type->base_price;
                                $data['max_size'] = $type->max_size;
                                $data['icon'] = $type->icon;
                                $data['is_default'] = (!empty($type->is_default == 1) ? true : false);
                                $data['price_per_unit_time'] = currency_converted($type->price_per_unit_time);
                                $data['price_per_unit_distance'] = currency_converted($type->price_per_unit_distance);
                                $data['base_price'] = currency_converted($type->base_price);
                                $data['base_distance'] = currency_converted($type->base_distance);
                                /* $data['currency'] = $currency_selected->keyword; */
                                $data['currency'] = Config::get('app.generic_keywords.Currency');
                                $data['unit'] = $unit_set;

                                $query = "SELECT walker.*, "
                                    . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                                    . "cos( radians(latitude) ) * "
                                    . "cos( radians(longitude) - radians('$longitude') ) + "
                                    . "sin( radians('$latitude') ) * "
                                    . "sin( radians(latitude) ) ) ,8) as distance "
                                    . "FROM walker "
                                    . "where is_available = 1 and "
                                    . "is_active = 1 and "
                                    . "is_approved = 1 and "
                                    . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                                    . "cos( radians(latitude) ) * "
                                    . "cos( radians(longitude) - radians('$longitude') ) + "
                                    . "sin( radians('$latitude') ) * "
                                    . "sin( radians(latitude) ) ) ) ,8) <= $distance and "
                                    . "walker.deleted_at IS NULL and "
                                    . "walker.type IN($type->id) "
                                    . "order by distance DESC";

                                $walk = DB::select(DB::raw($query));

                                if ($walk) {

                                    $origin = $latitude . ',' . $longitude;
                                    $desti = $walk[0]->latitude . ',' . $walk[0]->longitude;



                                    //for distance
                                    // Get cURL resource
                                    try {
                                        $curl = curl_init();
// Set some options - we are passing in a useragent too here
                                        curl_setopt_array($curl, array(
                                            CURLOPT_RETURNTRANSFER => 1,
                                            //CURLOPT_URL => 'https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=' . $origin . '&destinations=' . $desti . '&key=AIzaSyAXatDcDqW3hERFA-zpeX86juvvWQr8ycM',
                                            CURLOPT_URL => 'https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=' . $origin . '&destinations=' . $desti . '&key=AIzaSyD17Rw0_wNiulS8Cp8jduFh4-KGoUtrvSM',
                                            CURLOPT_USERAGENT => 'Codular Sample cURL Request'
                                        ));
// Send the request & save response to $resp
                                        $resp = curl_exec($curl);
                                        $address = json_decode($resp, true);
                                        $data['duration'] = $address['rows'][0]['elements'][0]['duration']['text'];
// Close request to clear up some resources
                                        curl_close($curl);
                                    } catch (Exception $e) {
                                        $data['duration'] = "1 mins";
                                    }
                                } else {
                                    $data['duration'] = "-:-";
                                }



                                array_push($type_array, $data);
                            }
                            $response_array = array();
                            $response_array['success'] = true;
                            $response_array['types'] = $type_array;
                            $response_code = 200;
                            $response = Response::json($response_array, $response_code);
                            return $response;
                        }else{
                            $response_array = array('success' => false, 'error' => 'No Service Available','error_code' => 416);
                            $response_code = 200;
                            return Response::json($response_array, $response_code);
                        }
                    }else{
                        $response_array = array('success' => false, 'error' => 'No Service Available','error_code' => 416);
                        $response_code = 200;
                        return Response::json($response_array, $response_code);

                    } } } }

    }
    public function all_types()
    {
        $latitude = Input::get('user_lat');
        $longitude = Input::get('user_long');
        $types = ProviderType::where('is_visible', '=', 1)->get();
        $type_array = array();
        $settunit = Settings::where('key', 'default_distance_unit')->first();
        $unit = $settunit->value;
        if ($unit == 0) {
            $unit_set = 'kms';
        } elseif ($unit == 1) {
            $unit_set = 'miles';
        }
        /* $currency_selected = Keywords::find(5); */

        $settings = Settings::where('key', 'default_search_radius')->first();
        $distance = $settings->value;
        $settings = Settings::where('key', 'default_distance_unit')->first();
        $unit = $settings->value;
        if ($unit == 0) {
            $multiply = 1.609344;
        } elseif ($unit == 1) {
            $multiply = 1;
        }

        foreach ($types as $type) {
            $data = array();
            $data['id'] = $type->id;
            $data['name'] = $type->name;
            $data['min_fare'] = $type->base_price;
            $data['max_size'] = $type->max_size;
            $data['icon'] = $type->icon;
            $data['is_default'] = (!empty($type->is_default == 1) ? true : false);
            $data['price_per_unit_time'] = currency_converted($type->price_per_unit_time);
            $data['price_per_unit_distance'] = currency_converted($type->price_per_unit_distance);
            $data['base_price'] = currency_converted($type->base_price);
            $data['base_distance'] = currency_converted($type->base_distance);
            /* $data['currency'] = $currency_selected->keyword; */
            $data['currency'] = Config::get('app.generic_keywords.Currency');
            $data['unit'] = $unit_set;
            $query = "SELECT walker.*, "
                . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                . "cos( radians(latitude) ) * "
                . "cos( radians(longitude) - radians('$longitude') ) + "
                . "sin( radians('$latitude') ) * "
                . "sin( radians(latitude) ) ) ,8) as distance "
                . "FROM walker "
                . "where is_available = 1 and "
                . "is_active = 1 and "
                . "is_approved = 1 and "
                . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                . "cos( radians(latitude) ) * "
                . "cos( radians(longitude) - radians('$longitude') ) + "
                . "sin( radians('$latitude') ) * "
                . "sin( radians(latitude) ) ) ) ,8) <= $distance and "
                . "walker.deleted_at IS NULL and "
                . "walker.type IN($type->id) "
                . "order by distance DESC";

            $walk = DB::select(DB::raw($query));

            if ($walk) {

                $origin = $latitude . ',' . $longitude;
                $desti = $walk[0]->latitude . ',' . $walk[0]->longitude;
                //for distance
                // Get cURL resource
                try {
                    $curl = curl_init();
// Set some options - we are passing in a useragent too here
                    curl_setopt_array($curl, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => 'https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=' . $origin . '&destinations=' . $desti . '&key=AIzaSyD17Rw0_wNiulS8Cp8jduFh4-KGoUtrvSM',
                        CURLOPT_USERAGENT => 'Codular Sample cURL Request'
                    ));
// Send the request & save response to $resp
                    $resp = curl_exec($curl);
                    $address = json_decode($resp, true);
                    $data['duration'] = $address['rows'][0]['elements'][0]['duration']['text'];
// Close request to clear up some resources
                    curl_close($curl);
                } catch (Exception $e) {
                    $data['duration'] = "1 mins";
                }
            } else {
                $data['duration'] = "";
            }

            array_push($type_array, $data);
        }
        $response_array = array();
        $response_array['success'] = true;
        $response_array['types'] = $type_array;
        $response_code = 200;
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function types() {

        //$types = ProviderType::where('is_visible', '=', 1)->get();
        $types = ProviderType::get();
        $type_array = array();
        $settunit = Settings::where('key', 'default_distance_unit')->first();
        $unit = $settunit->value;
        if ($unit == 0) {
            $unit_set = 'kms';
        } elseif ($unit == 1) {
            $unit_set = 'miles';
        }
        /* $currency_selected = Keywords::find(5); */
        foreach ($types as $type) {
            $data = array();
            $data['id'] = $type->id;
            $data['name'] = $type->name;
            $data['min_fare'] = $type->base_price;
            $data['max_size'] = $type->max_size;
            $data['icon'] = !empty($type->icon)? $type->icon : asset_url().'/image/car_default.jpg' ;
            $data['is_default'] = (!empty($type->is_default == 1) ? true : false);
            $data['price_per_unit_time'] = currency_converted($type->price_per_unit_time);
            $data['price_per_unit_distance'] = currency_converted($type->price_per_unit_distance);
            $data['base_price'] = currency_converted($type->base_price);
            $data['base_distance'] = currency_converted($type->base_distance);
            /* $data['currency'] = $currency_selected->keyword; */
            $data['currency'] = Config::get('app.generic_keywords.Currency');
            $data['unit'] = $unit_set;
            array_push($type_array, $data);
        }
        $response_array = array();
        $response_array['success'] = true;
        $response_array['types'] = $type_array;
        $response_code = 200;
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function forgot_password()
    {

        $type = Input::get('type');
        $email = Input::get('email');
        if ($type == 1) {
            // Walker
            $walker_data = Walker::where('email', $email)->first();
            if ($walker_data) {
                $walker = Walker::find($walker_data->id);
                $new_password = time();
                $new_password .= rand();
                $new_password = sha1($new_password);
                $new_password = substr($new_password, 0, 8);
                $walker->password = Hash::make($new_password);
                $walker->save();

                /* $subject = "Your New Password";
                  $email_data = array();
                  $email_data['password'] = $new_password;
                  send_email($walker->id, 'walker', $email_data, $subject, 'forgotpassword'); */
                $settings = Settings::where('key', 'admin_email_address')->first();
                $admin_email = $settings->value;
                $login_url = web_url() . "/provider/signin";
                $pattern = array('name' => $walker->first_name . " " . $walker->last_name, 'admin_eamil' => $admin_email, 'new_password' => $new_password, 'login_url' => $login_url);
                $subject = "Your New Password";
                email_notification($walker->id, 'walker', $pattern, $subject, 'forgot_password', "imp");

                $response_array = array();
                $response_array['success'] = true;
                $response_code = 200;
                $response = Response::json($response_array, $response_code);
                return $response;
            } else {
                $response_array = array('success' => false, 'error' => 'This Email is not Registered', 'error_code' => 633);
                $response_code = 200;
                $response = Response::json($response_array, $response_code);
                return $response;
            }
        } else {
            $owner_data = Owner::where('email', $email)->first();
            if ($owner_data) {

                $owner = Owner::find($owner_data->id);
                $new_password = time();
                $new_password .= rand();
                $new_password = sha1($new_password);
                $new_password = substr($new_password, 0, 8);
                $owner->password = Hash::make($new_password);
                $owner->save();

                /* $subject = "Your New Password";
                  $email_data = array();
                  $email_data['password'] = $new_password;
                  send_email($owner->id, 'owner', $email_data, $subject, 'forgotpassword'); */
                $settings = Settings::where('key', 'admin_email_address')->first();
                $admin_email = $settings->value;
                $login_url = web_url() . "/user/signin";
                $pattern = array('name' => $owner->first_name . " " . $owner->last_name, 'admin_eamil' => $admin_email, 'new_password' => $new_password, 'login_url' => $login_url);
                $subject = "Your New Password";
                email_notification($owner->id, 'owner', $pattern, $subject, 'forgot_password', "imp");


                $response_array = array();
                $response_array['success'] = true;
                $response_code = 200;
                $response = Response::json($response_array, $response_code);
                return $response;
            } else {
                $response_array = array('success' => false, 'error' => 'This Email is not Registered', 'error_code' => 633);
                $response_code = 200;
                $response = Response::json($response_array, $response_code);
                return $response;
            }
        }
    }

    public function token_braintree()
    {
        $this->_braintreeConfigure();
        $clientToken = Braintree_ClientToken::generate();
        $response_array = array('success' => true, 'clientToken' => $clientToken);
        $response_code = 200;
        return Response::json($response_array, $response_code);
    }

    private function _braintreeConfigure()
    {
        Braintree_Configuration::environment(Config::get('app.braintree_environment'));
        Braintree_Configuration::merchantId(Config::get('app.braintree_merchant_id'));
        Braintree_Configuration::publicKey(Config::get('app.braintree_public_key'));
        Braintree_Configuration::privateKey(Config::get('app.braintree_private_key'));
    }

    public function init()
    {
        $this->_braintreeConfigure();
       $token= $this->token_braintree();
        $card= Braintree_CreditCard::create(array(
            'firstName' => 'test',

            'lastName' => 'mr',


            'customerId'  => $token,

            'cvv' => '123',


            'expirationDate' => '12'

        ));
    }

    public function test_notifi()
    {
        send_ios_push("11F1530C543DA98EF4BC013D28FF91B4906BE0EA0523DD4B0A04732CC91B4570", "test", "test msg", "owner");
    }
}
