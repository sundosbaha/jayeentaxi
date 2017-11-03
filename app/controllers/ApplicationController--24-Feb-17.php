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

    public function all_types()
    {
        $latitude = Input::get('user_lat');
        $longitude = Input::get('user_long');
        $types = ProviderType::where('is_visible', '=', 1)->get();
        /* $setbase_price = Settings::where('key', 'base_price')->first();
          $base_price = $setbase_price->value;
          $setdistance_price = Settings::where('key', 'price_per_unit_distance')->first();
          $distance_price = $setdistance_price->value;
          $settime_price = Settings::where('key', 'price_per_unit_time')->first();
          $time_price = $settime_price->value; */
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
            $data['is_default'] = $type->is_default;
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

    public function types()
    {

        //Zone Division enable checking
        $setting_zone = Settings::where('key', 'zone_division')->first();

        if ($setting_zone->value == 1) {

            $latitude = Input::get('latitude');
            $longitude = Input::get('longitude');

            $zoneRecords = getAllZoneList();

            $zoneName = array_map(function ($zoneRecord) {
                return array(
                    'id' => $zoneRecord->id,
                    'name' => $zoneRecord->zone_name
                );
            }, $zoneRecords);


            if (!empty($latitude) && !empty($latitude)) {

                $results['id'] = '';
                $result = array();
                $points = $longitude . " " . $latitude;

                if (empty($zoneRecords)) {

                    $response_array = array('success' => false, 'error' => 'Tipo de Servicio no encontrado', 'error_messages' => 'No Service for this Area', 'error_code' => 416);

                    $response_code = 200;

                    return Response::json($response_array, $response_code);

                } else {

                    $zone = false;

                    foreach ($zoneRecords as $key => $zoneList) {

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
                } //zone Records Checking

                //var_dump($result);
                //exit;

                if (!empty($result['id'])) {

                    $zone_id = $result['id'];

                    $types = DB::table('zone_type')
                        ->join('zone', 'zone_type.zone_id', '=', 'zone.id')
                        ->Where('zone_type.zone_id', '=', $zone_id)
                        ->Where('zone_type.is_visible', '=', 1)
                        ->whereNull('zone_type.deleted_at')
                        ->whereNull('zone.deleted_at')
                        ->get();

                    $zoneTypesResponse = zoneTypeResponse($types);
                    $response_array = array();
                    $response_array['success'] = true;
                    $response_array['types'] = $zoneTypesResponse;
                    $response_array['zone_status'] = (!empty($setting_zone->value == 1) ? true : false);
                    $response_array['zones'] = $zoneName;

                } else {

                    $zoneTypesResponse = typeResponse();
                    $response_array = array();
                    $response_array['success'] = true;
                    $response_array['types'] = $zoneTypesResponse;
                    $response_array['zone_status'] = (!empty($setting_zone->value == 1) ? true : false);
                    $response_array['zones'] = $zoneName;

                }

            } else {

                $zoneTypesResponse = typeResponse();
                $response_array = array();
                $response_array['success'] = true;
                $response_array['types'] = $zoneTypesResponse;
                $response_array['zone_status'] = (!empty($setting_zone->value == 1) ? true : false);
                $response_array['zones'] = $zoneName;

            }

        } else { //Zone Disable

            $zoneTypesResponse = typeResponse();
            $response_array = array();
            $response_array['success'] = true;
            $response_array['types'] = $zoneTypesResponse;
            $response_array['zone_status'] = (!empty($setting_zone->value == 1) ? true : false);
        }

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

}
