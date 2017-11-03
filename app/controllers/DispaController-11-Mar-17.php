<?php


class DispaController extends BaseController {

    public function __construct() {
		
		 $url = URL::current();
         $routeName = Route::currentRouteName();
		if($routeName != 'DispatchLogin')
		 {
		if(Session::get('user_id') == '')
		{
            /* echo "<script>
			 window.location.href='".asset_url()."/dispatch/login'
			 </script>";
			 return false;*/
			 return Redirect::to("/dispatch/login");
			
		}
		else
		{
			if($routeName == 'DispatchUser' || $routeName == 'Dispatchsetting')
			{
				if(Session::get('user_type') == 'staff')
				{
					/*echo "<script>
			 window.location.href='".asset_url()."/dispatch/login'
			 </script>";
			 return false;*/
			 return Redirect::to("/dispatch/login");
				}
			}
		}
		 }
	}
	
	
	public function login()
	{
		if(Input::has('username'))
		{
			
		$username=	Input::get('username');
		$password= Input::get('password');
		$type=Input::get('type');
		 $validator = Validator::make(
                        array(
                    'username' => $username,
                    'password' => $password,
					'type' => $type
                        ), array(
                    'username' => 'required',
                    'password' => 'required',
					'type' => 'required'
                        )
        );
		
		if ($validator->fails()) {
            $error_messages = $validator->messages()->first();
            echo "<script>alert('".$error_messages."');window.location.href='".asset_url()."/dispatch/login'</script>";
			
		}
		else
		{
			$user=DB::table('userdetail')->where('username',$username)->where('type',$type)->where('is_active','1')->first();
			if($user)
			{
				if(Hash::check($password, $user->password))
				{
					Session::put('user_id', $user->id);
					Session::put('user_name', $user->name);
					Session::put('user_type', $user->type);
					return Redirect::to("/dispatch/tracking");
					
				}
				else
				{
			$error_messages = "Invalid username or password";
             echo "<script>alert('".$error_messages."');window.location.href='".asset_url()."/dispatch/login'</script>";
			
				}
			}
			else
			{
			$error_messages = "Invalid username or password";
              echo "<script>alert('".$error_messages."');window.location.href='".asset_url()."/dispatch/login'</script>";
			
			}
			
		}
		
		
		
		}
	  return View::make('dispatch.login');	
	}
	
	
	public function booking()
	{
	
	$resquest=$request=DB::select( DB::raw("SELECT r.*,u.*,d.*,r.id as request_id,d.first_name as dfname,d.last_name as dlname,u.first_name as ufname,u.last_name as ulname,d.id as driver_id FROM request r,walker d,owner u WHERE executive_id=".Session::get('user_id')." AND r.owner_id=u.id AND r.current_walker = d.id ORDER BY r.id DESC"));
        return View::make('dispatch.booking')
				->with('page','booking')
				->with('request',$resquest);
	}
	
	public function schedule_view()
	{
	$request=DB::select( DB::raw("SELECT t.*,u.* FROM temp_assign t,owner u WHERE u.id=t.userid ORDER BY t.assignId DESC"));
	return View::make('dispatch.schedule')
				->with('page','schedule')
				->with('request',$request);
	}
	
		public function logout()
	{
		Session::flush();
	 echo "<script>
			 window.location.href='".asset_url()."/dispatch/login'
			 </script>";
	}
	
	
	public function tracking()
	{
	return View::make('dispatch.tracking')
				  ->with('page','tracking');
	}
	
	public function settings()
	{
		if(Input::get('code'))
		{
			$coe=Input::get('code');
			$update=DB::table('config_settings')->where('configitemName','country_code')->update(['configValue' => $coe]);	
			echo "<script>alert('SuccessFully Country Code Set for dispatcher');</script>";
		}
		$set=DB::table('config_settings')->get();
	return View::make('dispatch.setting')
				  ->with('page','settings')
				  ->with('setting',$set);	
	}
	
	
	public function user()
	{
		$userdata=DB::table('userdetail')->get();
	return View::make('dispatch.user')
				  ->with('page','user')
				  ->with('userdata',$userdata);
	}
	
	
	public function useredit()
	{
		$id=Request::segment(3);
		$userdata=DB::table('userdetail')->where('id',$id)->first();
		return View::make('dispatch.edit_user')
				  ->with('page','user')
				  ->with('user',$userdata);
	}
	
	
	public function action()
	{
		$name=Input::get('uname');
		$dob=Input::get('ubdate');
		$jdate=Input::get('ujdate');
		$age=Input::get('uage');
		$img=Input::file('uimg');
		$doc=Input::file('udoc');
		$email=Input::get('uemail');
		$username=Input::get('uuname');
		$pass=Input::get('upass');
		$type=Input::get('utype');
			    // upload image
                $file_name = time();
                $file_name .= rand();
                $file_name = sha1($file_name);
				$ext = Input::file('uimg')->getClientOriginalExtension();
                Input::file('uimg')->move(public_path() . "/uploads", $file_name . "." . $ext);
                $img = $file_name . "." . $ext;
				
				//upload document
				$file_name = time();
                $file_name .= rand();
                $file_name = sha1($file_name);
				$ext = Input::file('udoc')->getClientOriginalExtension();
                Input::file('udoc')->move(public_path() . "/uploads", $file_name . "." . $ext);
                $doc = $file_name . "." . $ext;
				
				$insert=DB::table('userdetail')->insert(['name' => $name,
				'dateofbirth' => $dob,
				'dateofjoin' => $jdate,
				'age' => $age,
				'img' => $img,
				'doc' => $doc,
				'email' => $email,
				'username' => $username,
				'password' =>Hash::make($pass),
				'type' => $type,
				'is_active' => 1
 				]);
				
				if($insert)
				{
					echo "<script>alert('SuccessFully User Added');window.location.href='".asset_url()."/dispatch/user'</script>";
				}
				else
				{
					echo "<script>alert('Insert Failed');window.location.href='".asset_url()."/dispatch/user'</script>";
				}
				
				
				
				
				
	}
	
	
	public function edit_action()
	{
		$id=Input::get('id');
		$name=Input::get('uname');
		$dob=Input::get('ubdate');
		$jdate=Input::get('ujdate');
		$age=Input::get('uage');
		$img=Input::file('uimg');
		$doc=Input::file('udoc');
		$email=Input::get('uemail');
		$username=Input::get('uuname');
		$pass=Input::get('upass');
		$type=Input::get('utype');
		
		$user=DB::table('userdetail')->where('id',$id)->first();
		
		if($img == '')
		{
			$img=$user->img;
		}
		else
		{
			// upload image
                $file_name = time();
                $file_name .= rand();
                $file_name = sha1($file_name);
				$ext = Input::file('uimg')->getClientOriginalExtension();
                Input::file('uimg')->move(public_path() . "/uploads", $file_name . "." . $ext);
                $img = $file_name . "." . $ext;
		}
		
		
		if($doc == '')
		{
			$doc=$user->doc;
		}
		else
		{
			//upload document
				$file_name = time();
                $file_name .= rand();
                $file_name = sha1($file_name);
				$ext = Input::file('udoc')->getClientOriginalExtension();
                Input::file('udoc')->move(public_path() . "/uploads", $file_name . "." . $ext);
                $doc = $file_name . "." . $ext;
		}
		if($pass != 'vcnbvnzcmmnb' && $pass != '')
		{
			$pass=Hash::make($pass);
		}
		else
		{
			$pass=$user->password;
		}
		
				
				
				
				$insert=DB::table('userdetail')->where('id',$id)->update(['name' => $name,
				'dateofbirth' => $dob,
				'dateofjoin' => $jdate,
				'age' => $age,
				'img' => $img,
				'doc' => $doc,
				'email' => $email,
				'username' => $username,
				'password' =>$pass,
				'type' => $type
 				]);
				
				if($insert)
				{
					echo "<script>alert('Updated Succussfully');window.location.href='".asset_url()."/dispatch/user'</script>";
				}
				else
				{
					echo "<script>alert('Insert Failed');window.location.href='".asset_url()."/dispatch/user'</script>";
				}
				
				
				
				
				
	}
	
	
	public function chckuser()
	{
	$username=Input::get('username');
	$user=DB::table('userdetail')->where('username', $username)->first();
	if($user)
	{
		$response=array('success' => false);
	}
	else
	{
		$response=array('success' => true);
	}
		echo json_encode($response);
	}
	
	
	
	
	
	
	public function user_detail()
	{
		$number = Input::get('num');
		$number="+52".$number;
		$user=DB::table('owner')->where('phone', '=', $number)->first();
		if($user)
		{
		$response=array('success' => true,'fname'=> $user->first_name,'lname' => $user->last_name,'id' => $user->id);
		}
		else
		{
		$response=array('success' => false);
		}
		
		echo json_encode($response);
	}
		
		
public function all_walkers_xml() {
        $walkers = DB::table('walker')
				->join('walker_type', 'walker.type', '=', 'walker_type.id')
                ->select('walker_type.name','walker.*')
                ->get();
				$wal='';
				if($walkers)
				{
					
						foreach($walkers as $walk)
						{
							
							if($walk->is_active == 1 && $walk->is_available == 1 && $walk->is_approved == 1 && $walk->latitude != 0)
							{
							$wal[]=array('id' => $walk->id,'fname' =>$walk->first_name,'lname' =>$walk->last_name,  'lati' => $walk->latitude, 'longi' => $walk->longitude,'type' => 'free','type_name' => $walk->name);
							}
							else if($walk->is_active == 1 && $walk->is_available == 0 && $walk->is_approved == 1 && $walk->latitude != 0)
							{
							$wal[]=array('id' => $walk->id, 'fname' =>$walk->first_name,'lname' =>$walk->last_name, 'lati' => $walk->latitude, 'longi' => $walk->longitude,'type' => 'trip','type_name' => $walk->name);	
							}
							
						}
						if($wal != '')
						{
							$response=array('success' => true);
						$response['walkers']=$wal;
						}
						else
						{
						$response=array('success' => false);	
						}
				}
				else
				{
					$response=array('success' => false);
				}
        
        
        echo json_encode($response);
    }		
		
		
	public function walkers_xml() {
$type=Input::get('type');
        $walkers = DB::table('walker')
                ->join('walker_type', 'walker.type', '=', 'walker_type.id')
                ->select('walker_type.name','walker.*')
				->where('is_active',1)
				->where('is_available',1)
				->where('is_approved',1)
				->where('type',$type)
                ->get();
				
				if($walkers)
				{
						$response=array('success' => true);
						foreach($walkers as $walk)
						{
							$wal[]=array('id' => $walk->id,'fname' =>$walk->first_name,'lname' =>$walk->last_name, 'lati' => $walk->latitude, 'longi' => $walk->longitude,'type_name' => $walk->name);
						}
						
						$response['walkers']=$wal;
				}
				else
				{
					$response=array('success' => false);
				}
        
        
        echo json_encode($response);
    }
	
	
	public function schedule_trip()
	{
		$phone=Input::get('phone');
		$user_id=Input::get('user_id');
		$fname=Input::get('fname');
		$lname=Input::get('lname');
		$address=Input::get('address');
		$pick_location=Input::get('pick_location');
		$pick_lati=Input::get('pick_lati');
		$pick_longi=Input::get('pick_longi');
		$pick_detail=Input::get('pick_detail');
		$drop_location=Input::get('drop_location');
		$drop_lati=Input::get('drop_lati');
		$drop_longi=Input::get('drop_longi');
		$drop_detail=Input::get('drop_detail');
		$adult=Input::get('adult');
		$child=Input::get('child');
		$luggage=Input::get('luggage');
		$type=Input::get('type');
		$ridecomment=Input::get('ridecomment');
		$user_timezone=Input::get('user_timezone');
		$schedule=Input::get('schedule');
		
		
		
		$validator = Validator::make(
                            array(
                        'phone' => $phone,
						'user_id' => $user_id,
						'fname' => $fname,
						'lname' => $lname,
						'address' => $address,
						'pick_location' => $pick_location,
						'pick_lati' => $pick_lati,
						'pick_longi' => $pick_longi,
						'pick_detail' => $pick_detail,
						'drop_location' => $drop_location,
						'drop-lati' => $drop_lati,
						'drop_longi' => $drop_longi,
						'drop_detail' => $drop_detail,
						'adult' => $adult,
						'child' => $child,
						'type' => $type,
						'luggage' => $luggage,
						'ridecomment' => $ridecomment,
						'user_timezone' => $user_timezone,
						'schedule' => $schedule
						
                            ), array(
                        'phone' => 'required',
						'user_id' => '',
						'fname' => 'required',
						'lname' => 'required',
						'address' => '',
						'pick_location' => 'required',
						'pick_lati' => 'required',
						'pick_longi' => 'required',
						'pick_detail' => '',
						'drop_location' => 'required',
						'drop-lati' => 'required',
						'drop_longi' => 'required',
						'drop_detail' => '',
						'adult' => '',
						'child' => '',
						'type' => 'required',
						'luggage' => '',
						'ridecomment' => '',
						'user_timezone' => 'required',
						'schedule' => 'required'
						
                            )
            );
			
			
			if ($validator->fails()) {
            $error_messages = $validator->messages()->all();

            Log::info('Error while during owner registration = ' . print_r($error_messages, true));
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
			$response = Response::json($response_array, $response_code);
        	return $response;
        }
		else
		{
		if($user_id == '')
			{
				$userRegisterURL=asset_url().'/user/register';
			
			$data=array(
			'first_name' 	 =>     $fname,
			'last_name'		 =>		$lname,
			'email' 		 =>		'dispatch'.rand(99,999)."@gmail.com",
			'phone' 		 =>		'+52'.$phone,
			'password'		 =>		"123456789",
			'device_token'	 =>		"ghsjfygyu24574265hvfgsuyt32765fgisg",
			'device_type'	 =>		'android',
			'otp' 			 =>		'false',
			"login_by"		 => 	"manual",
			"otpmode"        =>      true
			);
			$response=send_request($userRegisterURL,$data,'POST');
			$response=json_decode($response,true);
			
			if($response['success'])
			{	
$user_id=$response['id'];
goto mainfuc;

			}
			else
			{	
        	return $response;
			}
			}
			else
			{
			mainfuc:
			DB::table('temp_assign')->insert(
    ['executive_user_id' => Session::get('user_id'), 'userid' => $user_id,'token' => 'sfjghfjghyiuhrti','schedule_datetime' => $schedule,'usrAddress' => $address,
	'pickupAddress' => $pick_location,'pickupDetails' => $pick_detail,'destinationAddress' => $drop_location,'destinationDetails' => $drop_detail,
	'no_of_adults' => $adult,'no_of_children' => $child,'luggages' =>$luggage,'type' => $type,'rideComment' => $ridecomment,'pickupLatitude' => $pick_lati,
	'pickupLongitude' => $pick_longi,'payment_opt' => 1,'user_timezone' => $user_timezone]);
	
	$response['success']=true;
	return $response;
	
			}
			
		}
		
		
	}
	
	
	
	
	
	public function create_trip()
	{
		$phone=Input::get('phone');
		$user_id=Input::get('user_id');
		$fname=Input::get('fname');
		$lname=Input::get('lname');
		$address=Input::get('address');
		$pick_location=Input::get('pick_location');
		$pick_lati=Input::get('pick_lati');
		$pick_longi=Input::get('pick_longi');
		$pick_detail=Input::get('pick_detail');
		$drop_location=Input::get('drop_location');
		$drop_lati=Input::get('drop_lati');
		$drop_longi=Input::get('drop_longi');
		$drop_detail=Input::get('drop_detail');
		$adult=Input::get('adult');
		$child=Input::get('child');
		$luggage=Input::get('luggage');
		$type=Input::get('type');
		$ridecomment=Input::get('ridecomment');
		$method="automatic";
		
		$validator = Validator::make(
                            array(
                        'phone' => $phone,
						'user_id' => $user_id,
						'fname' => $fname,
						'lname' => $lname,
						'address' => $address,
						'pick_location' => $pick_location,
						'pick_lati' => $pick_lati,
						'pick_longi' => $pick_longi,
						'pick_detail' => $pick_detail,
						'drop_location' => $drop_location,
						'drop-lati' => $drop_lati,
						'drop_longi' => $drop_longi,
						'drop_detail' => $drop_detail,
						'adult' => $adult,
						'child' => $child,
						'type' => $type,
						'luggage' => $luggage,
						'ridecomment' => $ridecomment
                            ), array(
                        'phone' => 'required',
						'user_id' => '',
						'fname' => 'required',
						'lname' => 'required',
						'address' => '',
						'pick_location' => 'required',
						'pick_lati' => 'required',
						'pick_longi' => 'required',
						'pick_detail' => '',
						'drop_location' => 'required',
						'drop-lati' => 'required',
						'drop_longi' => 'required',
						'drop_detail' => '',
						'adult' => '',
						'child' => '',
						'type' => 'required',
						'luggage' => '',
						'ridecomment' => ''
                            )
            );
			
			
			if ($validator->fails()) {
            $error_messages = $validator->messages()->all();

            Log::info('Error while during owner registration = ' . print_r($error_messages, true));
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
			$response = Response::json($response_array, $response_code);
        	return $response;
        } 
		else
		{
			if($user_id == '')
			{
				$userRegisterURL=asset_url().'/user/register';
			
			$data=array(
			'first_name' 	 =>     $fname,
			'last_name'		 =>		$lname,
			'email' 		 =>		'dispatch'.rand(99,999)."@gmail.com",
			'phone' 		 =>		'+52'.$phone,
			'password'		 =>		"123456789",
			'device_token'	 =>		"ghsjfygyu24574265hvfgsuyt32765fgisg",
			'device_type'	 =>		'android',
			'otp' 			 =>		'false',
			"login_by"		 => 	"manual"
			);
			$response=send_request($userRegisterURL,$data,'POST');
			$response=json_decode($response,true);
			
			if($response['success'])
			{	
$user_id=$response['id'];
goto mainfuc;
			}
			else
			{
				$response['userid']="";	
        	return $response;
			}
			}
			else
			{
				mainfuc:
			if($method == 'manual')	
			{
				$user_data=DB::table('owner')->where('id',$user_id)->first();
				if($user_data)
				{
				$userRegisterURL=asset_url().'/dispatch/manualdriver';
			
			$data=array(
			'token' 			=> 			$user_data->token,
			'id' 				=>			$user_id,
			'latitude' 			=>  		$pick_lati,
			'longitude' 		=> 			$pick_longi, 
			'd_latitude'		=> 			$drop_lati,
			'd_longitude' 		=> 			$drop_longi,
			'pickupDetails' 	=> 			$pick_detail,
			'dropoffDetails' 	=> 			$drop_detail,
			'adultCount' 		=> 			$adult,
			'childCount' 		=> 			$child,
			'lugaggeCount' 		=> 			$luggage,
			'rideComments' 		=> 			$ridecomment,
			'payment_opt' 		=> 			1,
			'type'				=>			$type,
			'executive_user_id'	=>			Session::get('user_id')
			 );
			$response=send_request($userRegisterURL,$data,'POST');
			$response=json_decode($response,true);
			if($response['success'])
			{
				$response['method']='manual';
				$response['userid']=$user_id;	
				return $response;
			}
			else
			{
				$response['userid']=$user_id;
				return $response;
			}
				}
				else
				{
					$response['userid']="";
					$resp['error_messages']= array("User can't find");
					return $resp['error_messages'];
					
				}
			}
			else
			{
				$user_data=DB::table('owner')->where('id',$user_id)->first();
				if($user_data)
				{
				$userRegisterURL=asset_url().'/user/createrequest';
			
			$data=array(
			'token' 			=> 			$user_data->token,
			'id' 				=>			$user_id,
			'latitude' 			=>  		$pick_lati,
			'longitude' 		=> 			$pick_longi, 
			'd_latitude'		=> 			$drop_lati,
			'd_longitude' 		=> 			$drop_longi,
			'pickupDetails' 	=> 			$pick_detail,
			'dropoffDetails' 	=> 			$drop_detail,
			'adultCount' 		=> 			$adult,
			'childCount' 		=> 			$child,
			'lugaggeCount' 		=> 			$luggage,
			'rideComments' 		=> 			$ridecomment,
			'payment_opt' 		=> 			1,
			'type'				=>			$type,
			'executive_user_id'	=>			Session::get('user_id')
			 );
			$response=send_request($userRegisterURL,$data,'POST');
			$response=json_decode($response,true);
			if($response['success'])
			{
				$response['userid']=$user_id;
				$response['method']='automatic';
				return $response;
			}
			else
			{
				$response['userid']=$user_id;
				return $response;
			}
				}
				else
				{
					$response['userid']="";
					$resp['error_messages']= array("User can't find");
					return $resp['error_messages'];
					
				}
			}
			}
		}
	}
	
	
	public function set_driver_request()
	{
		$request_id=Input::get('request_id');
		$driver_id=Input::get('driver_id');
		$user_id=Input::get('user_id');
		DB::table('request')
            ->where('id', $request_id)
            ->update(['current_walker' => $driver_id]);
			
			$request=DB::table('request')
            ->where('id', $request_id)->first();
			$settings = Settings::where('key', 'provider_timeout')->first();
                        $time_left = $settings->value;

                        // Send Notification
                        $walker = Walker::find($driver_id);
                        if ($walker) {
                            $msg_array = array();
                            $msg_array['unique_id'] = 1;
                            $msg_array['request_id'] = $request_id;
                            $msg_array['time_left_to_respond'] = $time_left;
                            $msg_array['payment_mode'] = $request->payment_mode;
                            $owner = Owner::find($request->owner_id);
                            $request_data = array();
                            $request_data['owner'] = array();
                            $request_data['owner']['name'] = $owner->first_name . " " . $owner->last_name;
                            $request_data['owner']['picture'] = $owner->picture;
                            $request_data['owner']['phone'] = $owner->phone;
                            $request_data['owner']['address'] = $owner->address;
                            $request_data['owner']['latitude'] = $owner->latitude;
                            $request_data['owner']['longitude'] = $owner->longitude;
                            $request_data['owner']['owner_dist_lat'] = $request->D_latitude;
                            $request_data['owner']['owner_dist_long'] = $request->D_longitude;
                            $request_data['owner']['payment_type'] = $request->payment_mode;
                            $request_data['owner']['rating'] = $owner->rate;
                            $request_data['owner']['num_rating'] = $owner->rate_count;
                            /* $request_data['owner']['rating'] = DB::table('review_dog')->where('owner_id', '=', $owner->id)->avg('rating') ? : 0;
                              $request_data['owner']['num_rating'] = DB::table('review_dog')->where('owner_id', '=', $owner->id)->count(); */
                            $request_data['dog'] = array();
                            if ($dog = Dog::find($owner->dog_id)) {

                                $request_data['dog']['name'] = $dog->name;
                                $request_data['dog']['age'] = $dog->age;
                                $request_data['dog']['breed'] = $dog->breed;
                                $request_data['dog']['likes'] = $dog->likes;
                                $request_data['dog']['picture'] = $dog->image_url;
                            }
                            $msg_array['request_data'] = $request_data;

                            $title = "New Request";
                            $message = $msg_array;
                            Log::info('response = ' . print_r($message, true));
                            Log::info('first_walker_id = ' . print_r($driver_id, true));
                            Log::info('New request = ' . print_r($message, true));
                            /* don't do json_encode in above line because if */
                            send_notifications($driver_id, "walker", $title, $message);
						}
			
			$response=array('success'=>true);
			return $response;
			
	}
	
	
	public function canceltrip()
	{
		$request_id=Input::get('request_id');
		$req=$request=DB::table('request')->where('id', $request_id)->first();
		$owner=$request=DB::table('owner')->where('id', $req->owner_id)->first();
		$userRegisterURL=asset_url().'/user/cancellation';
			$data=array(
			'token' 			=> 			$owner->token,
			'id' 				=>			$owner->id,
			'request_id'        =>          $request_id
			 );
			$response=send_request($userRegisterURL,$data,'POST');
			$response=json_decode($response,true);
			if($response['success'])
			{
				$response=array('success'=>true);
			}
			return $response;
			
	}
	
	
	public function statustrip()
	{
		$request_id=Input::get('request_id');
		$driver_id=Input::get('driver_id');
		$request=DB::table('request')->where('id', $request_id)->first();
		//$request=DB::select( DB::raw("SELECT * FROM request WHERE id = ".$id." AND current_walker=0 OR NOT confirmed_walker = 0)"));
		$message='';
			$pty='';
		if($request)
		{
			if($request->current_walker == 0)
			{
				$message="All Driver Are Busy";
				$pty=1;
			}
			else if($request->is_cancelled == 1)
			{
				$message="Last Request Cancelled";
				$pty=2;

			}
			else if($request->confirmed_walker != 0)
			{
				$message="Driver Accepted";
				$pty=3;

			}
			else
			{
			$driver=DB::table('walker')->where('id', $request->current_walker)->first();
			if($driver)
		{
		$response=array('success'=>false,'id' => $driver->id,'fname' => $driver->first_name,'lname' => $driver->last_name,'picture' => $driver->picture);	
		return $response;
		}	
			}
			
		$response=array('success'=>true,'msg' => $message,'pty' => $pty);	
		}
		/*else
		{
		$req=DB::table('request')->where('id', $request_id)->first();
		$driver=DB::table('walker')->where('id', $req->current_walker)->first();
		if($driver)
		{
		$response=array('success'=>false,'id' => $driver->id,'fname' => $driver->first_name,'lname' => $driver->last_name,'picture' => $driver->picture);	
		return $response;
		}
		}*/
		return $response;
	}
	
	
	public function eta_type()
	{
		$type=Input::get('type');
		$distance=Input::get('distance');
		$duration=Input::get('duration');
		$car_type=DB::table('walker_type')->where('id',$type)->first();
		if($car_type->base_distance >= intval($distance))
		{
			$type=1;
			$cal_distance_price=0;
			$cal_time_price=floatval($duration*$car_type->price_per_unit_time);
			$cal_total=floatval($car_type->base_price+$cal_time_price);
		}
		else
		{
			$type=2;
			$dis=floatval($distance-$car_type->base_distance);
			$cal_distance_price=floatval($dis*$car_type->price_per_unit_distance);
			$cal_time_price=floatval($duration*$car_type->price_per_unit_time);
			$cal_total=floatval($car_type->base_price+$cal_time_price+$cal_distance_price);
			
		}
		$response=array('name' => $car_type->name,
		'type' => $type,
		'base_price' => $car_type->base_price,
		'distance_price' => $car_type->price_per_unit_distance,
		'time_price' => $car_type->price_per_unit_time,
		'base_distance' => $car_type->base_distance,
		'cal_distance_price' => $cal_distance_price,
		'cal_time_price' => $cal_time_price,
		'cal_total' => $cal_total
		);
		return $response;
	}
	
	
	


}

   