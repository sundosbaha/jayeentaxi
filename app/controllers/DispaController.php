<?php



class DispaController extends BaseController {

    /*public function __construct() {
		
		 $url = URL::current();
         $routeName = Route::currentRouteName();
		if($routeName != 'DispatchLogin')
		 {
			 
		if(Session::get('user_id') == ' ')
		{
		return Redirect::to("/dispatch/login");
		}
		else
		{
			if($routeName == 'DispatchUser' || $routeName == 'Dispatchsetting')
			{
				if(Session::get('user_type') == 'staff' || Session::get('user_type') == '')
				{
			 return Redirect::to("/dispatch/login");
				}
			}
		}
		 }
		 die(Session::get('user_id'));
	}*/


    public function __construct()
    {
        $this->beforeFilter(function () {

            if (!Auth::dispatcher()->check()) {

                $url = URL::current();

                $routeName = Route::currentRouteName();

                Log::info('current route =' . print_r(Route::currentRouteName(), true));

                if ($routeName != "DispatchLogin") {

                    // Session::put('pre_admin_login_url', $url);

                }

                return Redirect::to("/dispatch/login");

            }

        }, array('except' => array('login', 'chckuser', 'user_detail', 'all_walkers_xml', 'walkers_xml', 'schedule_trip', 'create_trip', 'set_driver_request', 'canceltrip', 'statustrip', 'eta_type', 'trip_status','cron_job_schdule')));

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


            if (Auth::dispatcher()->attempt(array('username' => $username, 'password' => $password, 'type' => $type, 'is_active' => 1)))
			{
                $user = DB::table('userdetail')->where('username', $username)->first();
					Session::put('user_id', $user->id);
					Session::put('user_name', $user->name);
					Session::put('user_type', $user->type);
					return Redirect::to("/dispatch/tracking");


            }
			else
			{
			$error_messages = "Invalid username or password";
                return Redirect::to("/dispatch/login");
			
			}
			
		}



		
		}
/*
        print_r(Input::all());
        die('x');*/

	  return View::make('dispatch.login');
	}


    public function booking()
    {



        if(Input::get('submit') && Input::get('submit')=='search'){

            $start_date = Input::get('start_date');
            $end_date = Input::get('end_date');
            $start_time = date("Y-m-d H:i:s", strtotime($start_date));
            $end_time = date("Y-m-d H:i:s", strtotime($end_date));
            $submit=Input::get('submit');

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
            if(Input::has('submit')){
                $type1.= 'submit'.'='.$submit;
            }
            else{
                $type1.= 'submit'.'=';

            }
            Session::put('type',$type1);


            $query=DB::table('request')
                ->join('walker', 'request.current_walker', '=', 'walker.id')
                ->join('owner', 'request.owner_id', '=', 'owner.id')
                ->select(DB::raw('request.*,owner.*,walker.*,request.id as request_id,request.is_completed as request_completed,walker.first_name as dfname,walker.last_name as dlname,owner.first_name as ufname,owner.last_name as ulname,walker.id as driver_id'));

            if (Input::has('start_date') && Input::has('end_date')) {
                $request=$query->where('request_start_time', '>=', $start_time)
                    ->where('request_start_time', '<=', $end_time)->orderBy('request_id','DESC')->paginate(10);
                goto  res;
            }


        }


        if (Input::get('submit') && Input::get('submit') == 'Download_Report') {


            $start_date = Input::get('start_date');
            $end_date = Input::get('end_date');
            $start_time = date("Y-m-d H:i:s", strtotime($start_date));
            $end_time = date("Y-m-d H:i:s", strtotime($end_date));
            $submit=Input::get('submit');


            $query=DB::table('request')
                ->join('walker', 'request.current_walker', '=', 'walker.id')
                ->join('owner', 'request.owner_id', '=', 'owner.id')
                ->select(DB::raw('request.*,owner.*,walker.*,request.id as request_id,request.is_completed as request_completed,walker.first_name as dfname,walker.last_name as dlname,owner.first_name as ufname,owner.last_name as ulname,walker.id as driver_id'));


            if (Input::has('start_date') && Input::has('end_date')) {
                $request=$query->where('request_start_time', '>=', $start_time)
                    ->where('request_start_time', '<=', $end_time)->orderBy('request_id','DESC')->get();
            }
            else {
                $request=$query->orderBy('request_id','DESC')->get();
            }

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=booking.csv');
            $handle = fopen('php://output', 'w');
            fputcsv($handle, array('Request ID', 'Customer Name', 'Driver ID', 'Driver Name', 'Status', 'Amount', 'Payment Status'));


            foreach ($request as $request_book) {

                if($request_book->is_completed == 1)
                {
                    $request_book_tripstatus= Lang::get('dispatcher.completed');
                }
                else if($request_book->is_cancelled == 1)
                {
                    $request_book_tripstatus=Lang::get('dispatcher.cancelled');
                }
                else if($request_book->confirmed_walker 	== 1)
                {
                    $request_book_tripstatus=Lang::get('dispatcher.on_trip');
                }
                elseif($request_book->is_started == 0)
                {
                    $request_book_tripstatus=Lang::get('dispatcher.not')." ".Lang::get('dispatcher.started');
                }
                else{
                    $request_book_tripstatus=Lang::get('dispatcher.request_in_progress');
                }


                if($request_book->is_paid == 1)
                {
                    $request_book_paymentstatus=Lang::get('dispatcher.payment').' '.Lang::get('dispatcher.completed');
                }

                else{
                    $request_book_paymentstatus= Lang::get('dispatcher.trip')." ".Lang::get('dispatcher.not')." ".Lang::get('dispatcher.completed');
                }


                fputcsv($handle, array(
                    $request_book->request_id,
                    $request_book->ufname.' '.$request_book->ulname,
                    $request_book->driver_id,
                    $request_book->dfname.' '.$request_book->dlname,
                    $request_book_tripstatus,
                    $request_book->total,
                    $request_book_paymentstatus,
                ));
            }


//die("hge");


            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

            goto end;
        }

        else{
            $start_date="";
            $end_date="";
            $submit="";
            $type1 = '0' . '&';
            if (Input::has('start_date')) {
                $type1 .= 'start_date' . '=' . $start_date . '&';
            } else {
                $type1 .= 'start_date' . '=' . '' . '&';
            }

            if (Input::has('end_date')) {
                $type1 .= 'end_date' . '=' . $end_date . '&';
            } else {
                $type1 .= 'end_date' . '=' . '' . '&';
            }
            if (Input::has('submit')) {
                $type1 .= 'submit' . '=' . $submit;
            } else {
                $type1 .= 'submit' . '=';

            }
            Session::put('type', $type1);


            $request=DB::table('request')
                ->join('walker', 'request.current_walker', '=', 'walker.id')
                ->join('owner', 'request.owner_id', '=', 'owner.id')
                ->select(DB::raw('request.*,owner.*,walker.*,request.id as request_id,walker.first_name as dfname,walker.last_name as dlname,owner.first_name as ufname,owner.last_name as ulname,walker.id as driver_id'))
                ->orderBy('request_id','DESC')
                ->paginate(10);
            goto res;

        }

        res:


        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }
        return View::make('dispatch.booking')
            ->with('page','booking')
            ->with('align_format',$align_format)
            ->with('request',$request);

        end:
    }

    public function schedule_view()
    {
        //$request=DB::select( DB::raw("SELECT t.*,u.*,DATE_FORMAT(t.schedule_datetime,'%h:%i %p') as newtime FROM temp_assign t,owner u WHERE u.id=t.userid ORDER BY t.assignId DESC"));


        if (Input::get('submit') && Input::get('submit') == 'search') {


            $start_date = Input::get('start_date');
            $end_date = Input::get('end_date');
            $start_time = date("Y-m-d H:i:s", strtotime($start_date));
            $end_time = date("Y-m-d H:i:s", strtotime($end_date));
            $submit = Input::get('submit');

            $type1 = '0' . '&';
            if (Input::has('start_date')) {
                $type1 .= 'start_date' . '=' . $start_date . '&';
            } else {
                $type1 .= 'start_date' . '=' . '' . '&';
            }

            if (Input::has('end_date')) {
                $type1 .= 'end_date' . '=' . $end_date . '&';
            } else {
                $type1 .= 'end_date' . '=' . '' . '&';
            }
            if (Input::has('submit')) {
                $type1 .= 'submit' . '=' . $submit;
            } else {
                $type1 .= 'submit' . '=';

            }
            Session::put('type', $type1);



            if (Input::has('start_date') && Input::has('end_date')) {

                $request = DB::table('temp_assign')
                    ->join('owner', 'temp_assign.userid', '=', 'owner.id')
                    ->select(DB::raw("temp_assign.*,owner.*,DATE_FORMAT(temp_assign.schedule_datetime,'%h:%i %p') as newtime,temp_assign.assignId as assign_id "))
                    ->where('temp_assign.schedule_datetime', '>=', $start_time)
                    ->where('temp_assign.schedule_datetime', '<=', $end_time)
                    ->paginate(10);

                goto  res;

            }


        }


        if (Input::get('submit') && Input::get('submit') == 'Download_Report') {


            $start_date = Input::get('start_date');
            $end_date = Input::get('end_date');
            $start_time = date("Y-m-d H:i:s", strtotime($start_date));
            $end_time = date("Y-m-d H:i:s", strtotime($end_date));
            $submit = Input::get('submit');


            if (Input::has('start_date') && Input::has('end_date')) {

                $request = DB::table('temp_assign')
                    ->join('owner', 'temp_assign.userid', '=', 'owner.id')
                    ->select(DB::raw("temp_assign.*,owner.*,DATE_FORMAT(temp_assign.schedule_datetime,'%h:%i %p') as newtime,temp_assign.assignId as assign_id "))
                    ->where('temp_assign.schedule_datetime', '>=', $start_time)
                    ->where('temp_assign.schedule_datetime', '<=', $end_time)
                    ->get();

            }else{
                $request = DB::table('temp_assign')
                    ->join('owner', 'temp_assign.userid', '=', 'owner.id')
                    ->select(DB::raw("temp_assign.*,owner.*,DATE_FORMAT(temp_assign.schedule_datetime,'%h:%i %p') as newtime,temp_assign.assignId as assign_id "))
                    ->orderBy('assign_id', ' DESC')
                    ->get();
            }


            $cntrr=1;


            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=schedule.csv');
            $handle = fopen('php://output', 'w');
            fputcsv($handle, array('S.No', 'Customer Name', 'Date', 'Time', 'Pickup'));

            foreach($request as $req)
            {
                fputcsv($handle, array(
                    $cntrr,
                    $req->first_name.' '.$req->last_name,
                    date('d-m-Y', strtotime($req->schedule_datetime)),
                    $req->newtime,
                    $req->pickupAddress,
                ));

                $cntrr++;
            }


            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );


            goto end;
        }
        else{

            $start_date="";
            $end_date="";
            $submit="";
            $type1 = '0' . '&';
            if (Input::has('start_date')) {
                $type1 .= 'start_date' . '=' . $start_date . '&';
            } else {
                $type1 .= 'start_date' . '=' . '' . '&';
            }

            if (Input::has('end_date')) {
                $type1 .= 'end_date' . '=' . $end_date . '&';
            } else {
                $type1 .= 'end_date' . '=' . '' . '&';
            }
            if (Input::has('submit')) {
                $type1 .= 'submit' . '=' . $submit;
            } else {
                $type1 .= 'submit' . '=';

            }
            Session::put('type', $type1);
            $request = DB::table('temp_assign')
                ->join('owner', 'temp_assign.userid', '=', 'owner.id')
                ->select(DB::raw("temp_assign.*,owner.*,DATE_FORMAT(temp_assign.schedule_datetime,'%h:%i %p') as newtime,temp_assign.assignId as assign_id "))
                ->orderBy('assign_id', ' DESC')
                ->paginate(10);
            goto res;



        }

        res:

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }
        return View::make('dispatch.schedule')
            ->with('page', 'schedule')
            ->with('align_format',$align_format)
            ->with('request', $request);


        end:
    }






    /*
    public function booking()
    {

    $resquest=$request=DB::select( DB::raw("SELECT r.*,u.*,d.*,r.id as request_id,d.first_name as dfname,d.last_name as dlname,u.first_name as ufname,u.last_name as ulname,d.id as driver_id FROM request r,walker d,owner u WHERE executive_id=".Session::get('user_id')." AND r.owner_id=u.id AND r.current_walker = d.id ORDER BY r.id DESC"));
        return View::make('dispatch.booking')
                ->with('page','booking')
                ->with('request',$resquest);
    }

    public function schedule_view()
    {
        $request = DB::select(DB::raw("SELECT t.*,u.*,DATE_FORMAT(t.schedule_datetime,'%h:%i %p') as newtime FROM temp_assign t,owner u WHERE u.id=t.userid ORDER BY t.assignId DESC"));
    return View::make('dispatch.schedule')
                ->with('page','schedule')
                ->with('request',$request);
    }*/
	
		public function logout()
	{

        Session::flush();
        Auth::dispatcher()->logout();
        return Redirect::to('/dispatch/login');
	}
	
	
	public function tracking()
	{

        if(Config::get('app.locale') == 'arb'){
            $align_format="right";
        }elseif(Config::get('app.locale') == 'en'){
            $align_format="left";
        }
        return View::make('dispatch.tracking')
            ->with('align_format',$align_format)
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

        if ($img != '') {
			    // upload image
                $file_name = time();
                $file_name .= rand();
                $file_name = sha1($file_name);
				$ext = Input::file('uimg')->getClientOriginalExtension();
                Input::file('uimg')->move(public_path() . "/uploads", $file_name . "." . $ext);
                $img = $file_name . "." . $ext;
        } else {
            $img = "";
        }

        if ($doc != '') {
				//upload document
				$file_name = time();
                $file_name .= rand();
                $file_name = sha1($file_name);
				$ext = Input::file('udoc')->getClientOriginalExtension();
                Input::file('udoc')->move(public_path() . "/uploads", $file_name . "." . $ext);
                $doc = $file_name . "." . $ext;
        } else {
            $doc = "";
        }
				
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
					echo "<script>alert('". Lang::get('dispatcher.user_added_successfully')."');window.location.href='".asset_url()."/dispatch/user'</script>";
				}
				else
				{
					echo "<script>alert('". Lang::get('dispatcher.insert_fail')."');window.location.href='".asset_url()."/dispatch/user'</script>";
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
					echo "<script>alert('". Lang::get('dispatcher.update_success')."');window.location.href='".asset_url()."/dispatch/user'</script>";
				}
				else
				{
					echo "<script>alert('". Lang::get('dispatcher.insert_fail')."');window.location.href='".asset_url()."/dispatch/user'</script>";
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
                                $lat = $walk->latitude;
                                $longi = $walk->longitude;
                                /*$request=DB::table('request')->where('confirmed_walker',$walk->id)->where('is_completed','!=',1)->orderby('id','desc')->first();
                                if($request)
                                {
                                    $trip=DB::table('walk_location')->where('request_id',$request->id)->orderby('id','desc')->first();
                                    if($trip)
                                    {
                                    $lat=$trip->latitude;
                                    $longi=$trip->longitude;
                                    }
                                }*/

                                $wal[] = array('id' => $walk->id, 'fname' => $walk->first_name, 'lname' => $walk->last_name, 'lati' => $lat, 'longi' => $longi, 'type' => 'trip', 'type_name' => $walk->name);
								
								
							
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
                "login_by" => "manual"
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
               $user_id= ( !empty($response['user']['id']) ? $response['user']['id'] : $response['id'] );
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
                DB::table('owner')
                    ->where('id', $user_id)
                    ->update(array('token' => generate_token(), 'token_expiry' => generate_expiry()));
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



        if(DB::table('request')->where('id',$request_id)->first()->is_started == 1){

            $response=array('success'=>false);
        }
        else{

            $req=$request=DB::table('request')->where('id', $request_id)->first();
            $owner=$request=DB::table('owner')->where('id', $req->owner_id)->first();
            $userRegisterURL = asset_url() . '/user/cancelrequest';
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
            $response = array('success' => false, 'id' => $driver->id, 'fname' => $driver->first_name, 'lname' => $driver->last_name, 'phone' => $driver->phone, 'picture' => $driver->picture);
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
        $distance = floor(Input::get('distance') / 1000);
        $duration = floor(Input::get('duration') / 60);
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


    public function trip_status()
    {
        $driver_id = Input::get('id');
        $request_data = DB::table('request')
            ->where('confirmed_walker', $driver_id)
            ->orderBy('id', 'desc')
            ->first();
        $driver_data = DB::table('walker')
            ->where('id', $driver_id)
            ->first();
        $owner = DB::table('owner')
            ->where('id', $request_data->owner_id)
            ->first();
        $type = DB::table('walker_type')
            ->where('id', $driver_data->type)
            ->first();
        $driver_res = array('fname' => $driver_data->first_name, 'lname' => $driver_data->last_name, 'pic' => $driver_data->picture);
        $owner_res = array('fname' => $owner->first_name, 'lname' => $owner->last_name, 'pic' => $owner->picture, 'lati' => $owner->latitude, 'longi' => $owner->longitude);
        $type = array('name' => $type->name);
        $request_res = array(
            'is_walker_started' => $request_data->is_walker_started,
            'is_walker_arrived' => $request_data->is_walker_arrived,
            'is_started' => $request_data->is_started,
            'is_completed' => $request_data->is_completed,
            'is_cancelled' => $request_data->is_cancelled,
            'lati' => $request_data->D_latitude,
            'longi' => $request_data->D_longitude

        );
        $response = array('success' => true, 'driver' => $driver_res, 'user' => $owner_res, 'type' => $type, 'request' => $request_res);
        return $response;

    }


    public function cron_job_schdule()
    {
        $Users = DB::select(DB::raw("SELECT *,DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 30 MINUTE),'%m-%d-%Y %h:%i'),
DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 30 MINUTE),'%m-%d-%Y %h:%i')
FROM temp_assign WHERE DATE_FORMAT(schedule_datetime,'%m-%d-%Y %h:%i') 
BETWEEN
DATE_FORMAT(DATE_SUB(CONVERT_TZ(NOW(),@@session.time_zone,user_timezone), INTERVAL 30 MINUTE),'%m-%d-%Y %h:%i')
AND  
DATE_FORMAT(DATE_ADD(CONVERT_TZ(NOW(),@@session.time_zone,user_timezone), INTERVAL 30 MINUTE),'%m-%d-%Y %h:%i') and is_cancelled=0"));

        foreach ($Users as $user) {
            if ($user->tolerance <= 5) {
                if ($user->request_id == 0) {
                    newrequest:
                    //$user_data = DB::table('owner')->where('id', $user->userid)->first();
                   // DB::table('owner')->where('id', $user->userid)->update(array('token' => generate_token(), 'token_expiry' => generate_expiry()));
                    $user_data = DB::table('owner')->where('id', $user->userid)->first();
                    $requestData = array(
                        'token' => $user_data->token,
                        'id' => $user->userid,
                        'latitude' => $user->pickupLatitude,
                        'longitude' => $user->pickupLongitude,
                        'd_latitude' => $user->drop_latitude,
                        'd_longitude' => $user->drop_longitude,
                        'pickupDetails' => $user->pickupDetails,
                        'dropoffDetails' => $user->dropoffDetails,
                        'adultCount' => $user->no_of_adults,
                        'childCount' => $user->no_of_children,
                        'lugaggeCount' => $user->luggages,
                        'rideComments' => $user->rideComment,
                        'payment_opt' => 1,
                        'type' => $user->type,
                        'scheduleDatetime' => $user->schedule_datetime
                    );

                    $response = $this->create_request($requestData);
                    $resp = json_decode($response, true);
                    if ($resp[0]['success']) {
                        DB::table('temp_assign')
                            ->where('assignId', $user->assignId)
                            ->update(['request_id' => $resp[0]['request_id'], 'tolerance' => $user->tolerance + 1]);
                    } else {
                        DB::table('temp_assign')
                            ->where('assignId', $user->assignId)
                            ->update(['request_id' => 0, 'tolerance' => $user->tolerance + 1]);
                    }
                } else {
                    $req = DB::table('request')->where('id', $user->request_id)->first();
                    if ($req->current_walker == 0) {
                        DB::table('temp_assign')
                            ->where('assignId', $user->assignId)
                            ->update(['request_id' => 0, 'tolerance' => $user->tolerance + 1]);
                        goto newrequest;
                    } else if ($req->current_walker != 0 && $req->confirmed_walker != 0) {
                        DB::table('temp_assign')->where('assignId', $user->assignId)->delete();
                    }
                }
            } else {
                if ($user->tolerance != 101) {
                    $pattern = "Can't Schedule for this id - " . $user->assignId;
                    sms_notification(1, 'admin', $pattern, "imp");
                    DB::table('temp_assign')
                        ->where('assignId', $user->assignId)
                        ->update(['tolerance' => 101, 'is_cancelled' => 1]);
                }
            }


        }

    }

    public function create_request($request)
    {


        $token = $request['token'];

        $owner_id = $request['id'];

        $latitude = $request['latitude'];

        $longitude = $request['longitude'];

        $d_latitude = $request['d_latitude'];

        $d_longitude = $request['d_longitude'];

        $pickupDetails = $request['pickupDetails'];

        $dropoffDetails = $request['dropoffDetails'];

        $adultCount = $request['adultCount'];

        $childCount = $request['childCount'];

        $lugaggeCount = $request['lugaggeCount'];

        $rideComments = $request['rideComments'];

        //$executive_id=$request['executive_user_id'];


        $payment_opt = 0;

        $request_type=$request['type'];




        $payment_opt = $request['payment_opt'];


        $validator = Validator::make(

            array(

                'token' => $token,

                'owner_id' => $owner_id,

                'latitude' => $latitude,

                'longitude' => $longitude,

            ), array(

                'token' => 'required',

                'owner_id' => 'required|integer',

                'latitude' => 'required',

                'longitude' => 'required',

            )

        );


        /* $var = Keywords::where('id', 2)->first(); */


        if ($validator->fails()) {

            $error_messages = $validator->messages()->all();

            $response_array = array('success' => false, 'error' => 'Entrada invalida', 'error_code' => 401, 'error_messages' => $error_messages);

            $response_code = 200;

        } else {


            $is_admin = $this->isAdmin($token);

            $unit = "";

            $driver_data = "";




            if (true) {

                // check for token validity


                if (true) {


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

                    // Do necessary operations

                    recheck:

                    $request = DB::table('request')->where('owner_id', $owner_id)
                        ->where('is_completed', 0)
                        ->where('is_cancelled', 0)
                        ->where('current_walker', '!=', 0)
                        ->first();


                    if ($request) {

                        DB::table('request')->where('id', $request->id)->update(['is_cancelled' => 1, 'owner_reason' => 'network problem']);

                        goto recheck;

                        //goto DontcreateReq;


                    } else {

                        /* SEND REFERRAL & PROMO INFO */


                        if ($payment_opt != 1) {

                            $card_count = Payment::where('owner_id', '=', $owner_id)->count();

                            if ($card_count <= 0) {

                                $response_array = array('success' => false, 'error' => "Please add card first for payment.", 'error_code' => 417);

                                $response_code = 200;

                                $response = Response::json($response_array, $response_code);

                                return $response;

                            }

                        }

                        /* if ($owner_data->debt > 0) {

                          $response_array = array('success' => false, 'error' => "You are already in \$$owner_data->debt debt", 'error_code' => 417);

                          $response_code = 200;

                          $response = Response::json($response_array, $response_code);

                          return $response;

                          } */

                        if ($request['type']) {


                            Log::info('out');

                            $type = $request['type'];

                            if (!$type) {

                                // choose default type

                                $provider_type = ProviderType::where('is_default', 1)->first();


                                if (!$provider_type) {

                                    $type = 1;

                                } else {

                                    $type = $provider_type->id;

                                }

                            }


                            $typequery = "SELECT distinct provider_id from walker_services where type IN($type)";

                            $typewalkers = DB::select(DB::raw($typequery));


                            Log::info('typewalkers = ' . print_r($typewalkers, true));


                            if (count($typewalkers) > 0) {


                                foreach ($typewalkers as $key) {


                                    $types[] = $key->provider_id;

                                }


                                $typestring = implode(",", $types);

                                Log::info('typestring = ' . print_r($typestring, true));

                            } else {

                                /* $driver = Keywords::where('id', 1)->first();

                                  send_notifications($owner_id, "owner", 'No ' . $driver->keyword . ' Found', 'No ' . $driver->keyword . ' found matching the service type.'); */

                                send_notifications($owner_id, "owner", 'No ' . Config::get('app.generic_keywords.Provider') . ' Found', 'No ' . Config::get('app.generic_keywords.Provider') . ' found matching the service type.');


                                /* $response_array = array('success' => false, 'error' => 'No ' . $driver->keyword . ' found matching the service type.', 'error_code' => 416); */

                                $response_array = array('success' => false, 'error' => 'Tipo de Servicio no encontrado', 'error_messages' => 'No ' . Config::get('app.generic_keywords.Provider') . ' found matching the service type.', 'error_code' => 416);

                                $response_code = 200;

                                return Response::json($response_array, $response_code);

                            }


                            $settings = Settings::where('key', 'default_search_radius')->first();

                            $distance = $settings->value;

                            $settings = Settings::where('key', 'default_distance_unit')->first();

                            $unit = $settings->value;

                            if ($unit == 0) {

                                $multiply = 1.609344;

                            } elseif ($unit == 1) {

                                $multiply = 1;

                            }

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

                                . "walker.id IN($typestring) "

                                . "order by distance";

                            $walkers = DB::select(DB::raw($query));

                            $walker_list = array();


                            $owner = Owner::find($owner_id);

                            $owner->latitude = $latitude;

                            $owner->longitude = $longitude;

                            $owner->save();


                            $request = new Requests;

                            $request->owner_id = $owner_id;

                            $request->payment_mode = $payment_opt;

                            $request->pickupDetails = $pickupDetails;

                            $request->dropoffDetails = $dropoffDetails;

                            $request->adultCount = $adultCount;

                            $request->childCount = $childCount;

                            $request->lugaggeCount = $lugaggeCount;

                            $request->rideComments = $rideComments;

                            $request->executive_id = $executive_id;

                            if (Input::has('promo_code')) {

                                $promo_code = Input::get('promo_code');

                                $payment_mode = 0;

                                $payment_mode = $payment_opt;


                                $settings = Settings::where('key', 'promotional_code_activation')->first();

                                $prom_act = $settings->value;

                                if ($prom_act) {

                                    if ($payment_mode == 0) {

                                        $settings = Settings::where('key', 'get_promotional_profit_on_card_payment')->first();

                                        $prom_act_card = $settings->value;

                                        if ($prom_act_card) {

                                            if ($promos = PromoCodes::where('coupon_code', $promo_code)->where('uses', '>', 0)->where('state', '=', 1)->first()) {

                                                if ((date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime(trim($promos->expiry)))) || (date("Y-m-d H:i:s") <= date("Y-m-d H:i:s", strtotime(trim($promos->start_date))))) {

                                                    $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 505);

                                                    $response_code = 200;

                                                    return Response::json($response_array, $response_code);

                                                } else {

                                                    $promo_is_used = UserPromoUse::where('user_id', '=', $owner_id)->where('code_id', '=', $promos->id)->count();

                                                    if ($promo_is_used) {

                                                        $response_array = array('success' => FALSE, 'error' => 'Promotional code already used.', 'error_code' => 512);

                                                        $response_code = 200;

                                                        return Response::json($response_array, $response_code);

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


                                                        $request->promo_id = $promos->id;

                                                        $request->promo_code = $promos->coupon_code;

                                                    }

                                                }

                                            } else {

                                                $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 505);

                                                $response_code = 200;

                                                return Response::json($response_array, $response_code);

                                            }

                                        } else {

                                            $response_array = array('success' => FALSE, 'error' => 'Promotion feature is not active on card payment.', 'error_code' => 505);

                                            $response_code = 200;

                                            return Response::json($response_array, $response_code);

                                        }

                                    } else if (($payment_mode == 1)) {

                                        $settings = Settings::where('key', 'get_promotional_profit_on_cash_payment')->first();

                                        $prom_act_cash = $settings->value;

                                        if ($prom_act_cash) {

                                            if ($promos = PromoCodes::where('coupon_code', $promo_code)->where('uses', '>', 0)->where('state', '=', 1)->first()) {

                                                if ((date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime(trim($promos->expiry)))) || (date("Y-m-d H:i:s") <= date("Y-m-d H:i:s", strtotime(trim($promos->start_date))))) {

                                                    $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 505);

                                                    $response_code = 200;

                                                    return Response::json($response_array, $response_code);

                                                } else {

                                                    $promo_is_used = UserPromoUse::where('user_id', '=', $owner_id)->where('code_id', '=', $promos->id)->count();

                                                    if ($promo_is_used) {

                                                        $response_array = array('success' => FALSE, 'error' => 'Promotional code already used.', 'error_code' => 512);

                                                        $response_code = 200;

                                                        return Response::json($response_array, $response_code);

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


                                                        $request->promo_id = $promos->id;

                                                        $request->promo_code = $promos->coupon_code;

                                                    }

                                                }

                                            } else {

                                                $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 505);

                                                $response_code = 200;

                                                return Response::json($response_array, $response_code);

                                            }

                                        } else {

                                            $response_array = array('success' => FALSE, 'error' => 'Promotion feature is not active on cash payment.', 'error_code' => 505);

                                            $response_code = 200;

                                            return Response::json($response_array, $response_code);

                                        }

                                    }/* else {

                                      $response_array = array('success' => FALSE, 'error' => 'Payment mode is paypal', 'error_code' => 505);

                                      $response_code = 200;

                                      return Response::json($response_array, $response_code);

                                      } */

                                } else {

                                    $response_array = array('success' => FALSE, 'error' => 'Promotion feature is not active.', 'error_code' => 505);

                                    $response_code = 200;

                                    return Response::json($response_array, $response_code);

                                }


                                /* $pcode = PromoCodes::where('coupon_code', Input::get('promo_code'))->first();



                                  if ($pcode) {

                                  // promo history

                                  $promohistory = PromoHistory::where('user_id', $owner_id)->where('promo_code', Input::get('promo_code'))->first();

                                  if (!$promohistory) {

                                  if (date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime(trim($pcode->expiry)))) {

                                  $response_array = array('success' => false, 'Promo Code already Expired', 'error_code' => 425);

                                  $response_code = 200;

                                  return Response::json($response_array, $response_code);

                                  } else {

                                  $request->promo_code = $pcode->id;

                                  if ($pcode->uses == 1) {

                                  $pcode->status = 3;

                                  }

                                  $pcode->uses = $pcode->uses - 1;

                                  $pcode->save();

                                  $phist = new PromoHistory();

                                  $phist->user_id = $owner_id;

                                  $phist->promo_code = Input::get('promo_code');

                                  $phist->amount_earned = $pcode->value;

                                  $phist->save();

                                  if ($pcode->type == 2) {

                                  // Absolute discount

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

                                  }

                                  }

                                  } else {

                                  $response_array = array('success' => false, 'Promo Code already Used', 'error_code' => 425);

                                  $response_code = 200;

                                  return Response::json($response_array, $response_code);

                                  }

                                  } else {

                                  $response_array = array('success' => false, 'Invalid Promo Code', 'error_code' => 415);

                                  $response_code = 200;

                                  return Response::json($response_array, $response_code);

                                  } */

                            }


                            $user_timezone = $owner->timezone;

                            $default_timezone = Config::get('app.timezone');

                            /* $offset = $this->get_timezone_offset($default_timezone, $user_timezone); */

                            $date_time = get_user_time($default_timezone, $user_timezone, date("Y-m-d H:i:s"));

                            $request->D_latitude = 0;

                            if (isset($d_latitude)) {

                                $request->D_latitude = $request['d_latitude'];

                            }

                            $request->D_longitude = 0;

                            if (isset($d_longitude)) {

                                $request->D_longitude = $request['d_longitude'];

                            }

                            /* $request->request_start_time = date("Y-m-d H:i:s"); */

                            $request->request_start_time = $date_time;

                            $request->save();


                            $reqserv = new RequestServices;

                            $reqserv->request_id = $request->id;

                            $reqserv->type = ''.$request_type;

                            $reqserv->save();

                        } else {

                            Log::info('in');

                            $settings = Settings::where('key', 'default_search_radius')->first();

                            $distance = $settings->value;

                            $settings = Settings::where('key', 'default_distance_unit')->first();

                            $unit = $settings->value;

                            if ($unit == 0) {

                                $multiply = 1.609344;

                            } elseif ($unit == 1) {

                                $multiply = 1;

                            }

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

                                . "walker.deleted_at IS NULL  "

                                . "order by distance";

                            $walkers = DB::select(DB::raw($query));

                            $walker_list = array();


                            $owner = Owner::find($owner_id);

                            $owner->latitude = $latitude;

                            $owner->longitude = $longitude;

                            $owner->save();


                            $request = new Requests;

                            $request->owner_id = $owner_id;

                            $request->payment_mode = $payment_opt;


                            if (Input::has('promo_code')) {

                                $promo_code = Input::get('promo_code');

                                $payment_mode = 0;

                                $payment_mode = $payment_opt;

                                $settings = Settings::where('key', 'promotional_code_activation')->first();

                                $prom_act = $settings->value;

                                if ($prom_act) {

                                    if ($payment_mode == 0) {

                                        $settings = Settings::where('key', 'get_promotional_profit_on_card_payment')->first();

                                        $prom_act_card = $settings->value;

                                        if ($prom_act_card) {

                                            if ($promos = PromoCodes::where('coupon_code', $promo_code)->where('uses', '>', 0)->where('state', '=', 1)->first()) {

                                                if ((date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime(trim($promos->expiry)))) || (date("Y-m-d H:i:s") <= date("Y-m-d H:i:s", strtotime(trim($promos->start_date))))) {

                                                    $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 505);

                                                    $response_code = 200;

                                                    return Response::json($response_array, $response_code);

                                                } else {

                                                    $promo_is_used = UserPromoUse::where('user_id', '=', $owner_id)->where('code_id', '=', $promos->id)->count();

                                                    if ($promo_is_used) {

                                                        $response_array = array('success' => FALSE, 'error' => 'Promotional code already used.', 'error_code' => 512);

                                                        $response_code = 200;

                                                        return Response::json($response_array, $response_code);

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


                                                        $request->promo_id = $promos->id;

                                                        $request->promo_code = $promos->coupon_code;

                                                    }

                                                }

                                            } else {

                                                $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 505);

                                                $response_code = 200;

                                                return Response::json($response_array, $response_code);

                                            }

                                        } else {

                                            $response_array = array('success' => FALSE, 'error' => 'Promotion feature is not active on card payment.', 'error_code' => 505);

                                            $response_code = 200;

                                            return Response::json($response_array, $response_code);

                                        }

                                    } else if (($payment_mode == 1)) {

                                        $settings = Settings::where('key', 'get_promotional_profit_on_cash_payment')->first();

                                        $prom_act_cash = $settings->value;

                                        if ($prom_act_cash) {

                                            if ($promos = PromoCodes::where('coupon_code', $promo_code)->where('uses', '>', 0)->where('state', '=', 1)->first()) {

                                                if ((date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime(trim($promos->expiry)))) || (date("Y-m-d H:i:s") <= date("Y-m-d H:i:s", strtotime(trim($promos->start_date))))) {

                                                    $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 505);

                                                    $response_code = 200;

                                                    return Response::json($response_array, $response_code);

                                                } else {

                                                    $promo_is_used = UserPromoUse::where('user_id', '=', $owner_id)->where('code_id', '=', $promos->id)->count();

                                                    if ($promo_is_used) {

                                                        $response_array = array('success' => FALSE, 'error' => 'Promotional code already used.', 'error_code' => 512);

                                                        $response_code = 200;

                                                        return Response::json($response_array, $response_code);

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


                                                        $request->promo_id = $promos->id;

                                                        $request->promo_code = $promos->coupon_code;

                                                    }

                                                }

                                            } else {

                                                $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 505);

                                                $response_code = 200;

                                                return Response::json($response_array, $response_code);

                                            }

                                        } else {

                                            $response_array = array('success' => FALSE, 'error' => 'Promotion feature is not active on cash payment.', 'error_code' => 505);

                                            $response_code = 200;

                                            return Response::json($response_array, $response_code);

                                        }

                                    }/* else {

                                      $response_array = array('success' => FALSE, 'error' => 'Payment mode is paypal', 'error_code' => 505);

                                      $response_code = 200;

                                      return Response::json($response_array, $response_code);

                                      } */

                                } else {

                                    $response_array = array('success' => FALSE, 'error' => 'Promotion feature is not active.', 'error_code' => 505);

                                    $response_code = 200;

                                    return Response::json($response_array, $response_code);

                                }

                                /* $pcode = PromoCodes::where('coupon_code', Input::get('promo_code'))->first();



                                  if ($pcode) {



                                  $request->promo_code = $pcode->id;



                                  if ($pcode->uses == 1) {

                                  $pcode->status = 3;

                                  }

                                  $pcode->uses = $pcode->uses - 1;

                                  $pcode->save();

                                  } else {

                                  $response_array = array('success' => false, 'Invalid Promo Code', 'error_code' => 415);

                                  $response_code = 200;

                                  return Response::json($response_array, $response_code);

                                  } */

                            }


                            $request->request_start_time = date("Y-m-d H:i:s");

                            $request->save();


                            $reqserv = new RequestServices;

                            $reqserv->request_id = $request->id;

                            $reqserv->type = $request_type;


                            $reqserv->save();

                        }

                        $i = 0;

                        $first_walker_id = 0;


                        if( !empty( $latitude ) && !empty( $longitude ) && !empty( $d_latitude ) && !empty( $d_longitude ) ) {

                            $origin = $latitude . ',' . $longitude;
                            $destination = $d_latitude . ',' . $d_longitude;
                            $durationMatrix = returnDurationAndDestination($origin, $destination);
                            $settings = Settings::where('key', 'default_distance_unit')->first();
                            $unit = $settings->value;
                            $retDistance = (string)convert($durationMatrix['distance'], $unit);
                            $retDuration = round($durationMatrix['duration'] / 60);
                            $reqServices = RequestServices::where('request_id', $request->id)->first();
                            $etaCalculation = estimatedCalculationTotal($reqServices, $retDistance, $retDuration);
                            $estimation_total = $etaCalculation['actual_total'];
                            //$price_per_unit_time = $etaCalculation['price_per_unit_time'];
                            //$price_per_unit_distance = $etaCalculation['price_per_unit_distance'];

                        }

                        $originLocation = $latitude . ',' . $longitude;
                        $destinationLoation = ( !empty( $d_latitude ) ? $d_latitude . ',' . $d_longitude : '' );
                        $gmapGenerationId = lastGenerationId('gmap');
                        $gmapUrl = staticMapGeneration($gmapGenerationId,$originLocation,$destinationLoation,$request->id);
                        $estimation['estimation'] = ( !empty( $estimation_total ) ? $estimation_total : 0 );
                        $estimation['gmap_url'] = ( !empty( $gmapUrl ) ? $gmapUrl : '' );
                        $estimation['pickup_address'] = ( !empty( $pickupDetails ) ? $pickupDetails : '' );
                        $estimation['drop_address'] = ( !empty( $dropoffDetails ) ? $dropoffDetails : '' );

                        foreach ($walkers as $walker) {

                            $request_meta = new RequestMeta;

                            $request_meta->request_id = $request->id;

                            $request_meta->walker_id = $walker->id;

                            if ($i == 0) {

                                $first_walker_id = $walker->id;

                                $driver_data = array();

                                $driver_data['unique_id'] = 1;

                                $driver_data['id'] = "" . $first_walker_id;

                                $driver_data['first_name'] = "" . $walker->first_name;

                                $driver_data['last_name'] = "" . $walker->last_name;

                                $driver_data['phone'] = "" . $walker->phone;

                                /*  $driver_data['email'] = "" . $walker->email; */


                                $driver_data['picture'] = "" .  $walker->picture ;

                                $driver_data['bio'] = "" . $walker->bio;

                                /* $driver_data['address'] = "" . $walker->address;

                                  $driver_data['state'] = "" . $walker->state;

                                  $driver_data['country'] = "" . $walker->country;

                                  $driver_data['zipcode'] = "" . $walker->zipcode;

                                  $driver_data['login_by'] = "" . $walker->login_by;

                                  $driver_data['social_unique_id'] = "" . $walker->social_unique_id;

                                  $driver_data['is_active'] = "" . $walker->is_active;

                                  $driver_data['is_available'] = "" . $walker->is_available; */

                                $driver_data['latitude'] = "" . $walker->latitude;

                                $driver_data['longitude'] = "" . $walker->longitude;

                                /* $driver_data['is_approved'] = "" . $walker->is_approved; */

                                $driver_data['type'] = "" . $walker->type;

                                $driver_data['car_model'] = "" . $walker->car_model;

                                $driver_data['car_number'] = "" . $walker->car_number;

                                $driver_data['rating'] = $walker->rate;

                                $driver_data['num_rating'] = $walker->rate_count;

                                /* $driver_data['rating'] = DB::table('review_walker')->where('walker_id', '=', $first_walker_id)->avg('rating') ? : 0;

                                  $driver_data['num_rating'] = DB::table('review_walker')->where('walker_id', '=', $first_walker_id)->count(); */

                                $i++;

                            }

                            $request_meta->save();

                        }

                        $req = Requests::find($request->id);

                        $req->current_walker = $first_walker_id;

                        $req->save();


                        $settings = Settings::where('key', 'provider_timeout')->first();

                        $time_left = $settings->value;


                        // Send Notification

                        $walker = Walker::find($first_walker_id);

                        if ($walker) {

                            $msg_array = array();

                            $msg_array['unique_id'] = 1;

                            $msg_array['request_id'] = $request->id;

                            $msg_array['time_left_to_respond'] = $time_left;

                            $msg_array['payment_mode'] = $payment_opt;

                            $owner = Owner::find($owner_id);

                            $request_data = array();

                            $request_data['estimation'] = ( !empty($estimation ) ? $estimation : 0 );

                            $request_data['owner'] = array();

                            $request_data['owner']['name'] = $owner->first_name . " " . $owner->last_name;

                            $request_data['owner']['picture'] = $owner->picture;

                            $request_data['owner']['phone'] = $owner->phone;

                            $request_data['owner']['address'] = $owner->address;

                            $request_data['owner']['latitude'] = $owner->latitude;

                            $request_data['owner']['longitude'] = $owner->longitude;

                            if ($d_latitude != NULL) {

                                $request_data['owner']['d_latitude'] = $d_latitude;

                                $request_data['owner']['d_longitude'] = $d_longitude;

                            }

                            $request_data['owner']['owner_dist_lat'] = $request->D_latitude;

                            $request_data['owner']['owner_dist_long'] = $request->D_longitude;

                            $request_data['owner']['payment_type'] = $payment_opt;

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

                            Log::info('first_walker_id = ' . print_r($first_walker_id, true));

                            Log::info('New request = ' . print_r($message, true));

                            /* don't do json_encode in above line because if */

                            send_notifications($first_walker_id, "walker", $title, $message);

                        } else {

                            Log::info('No provider found in your area');


                            /* $driver = Keywords::where('id', 1)->first();

                              send_notifications($owner_id, "owner", 'No ' . $driver->keyword . ' Found', 'No ' . $driver->keyword . ' found for the selected service in your area currently'); */

                            send_notifications($owner_id, "owner", 'No ' . Config::get('app.generic_keywords.Provider') . ' Found', 'No ' . Config::get('app.generic_keywords.Provider') . ' found for the selected service in your area currently');


                            /* $response_array = array('success' => false, 'error' => 'No ' . $driver->keyword . ' found for the selected service in your area currently', 'error_code' => 415); */

                            $response_array = array('success' => false, 'error' => 'No ' . Config::get('app.generic_keywords.Provider') . ' found for the selected service in your area currently', 'error_messages' => 'Tipo de Servicio no encontrado', 'error_code' => 415);

                            $response_code = 200;

                            return Response::json($response_array, $response_code);

                        }

                        // Send SMS

                        $owner_data=Owner::find($owner_id);

                        $settings = Settings::where('key', 'sms_request_created')->first();

                        $pattern = $settings->value;

                        $pattern = str_replace('%user%', $owner_data->first_name . " " . $owner_data->last_name, $pattern);

                        $pattern = str_replace('%id%', $request->id, $pattern);

                        $pattern = str_replace('%user_mobile%', $owner_data->phone, $pattern);

                        sms_notification(1, 'admin', $pattern);


                        // send email

                        /* $settings = Settings::where('key', 'email_new_request')->first();

                          $pattern = $settings->value;

                          $pattern = str_replace('%id%', $request->id, $pattern);

                          $pattern = str_replace('%url%', web_url() . "/admin/request/map/" . $request->id, $pattern);

                          $subject = "New Request Created";

                          email_notification(1, 'admin', $pattern, $subject); */

                        $settings = Settings::where('key', 'admin_email_address')->first();

                        $admin_email = $settings->value;

                        $follow_url = web_url() . "/user/signin";

                        $pattern = array('admin_eamil' => $admin_email, 'trip_id' => $request->id, 'follow_url' => $follow_url);

                        $subject = "Ride Booking Request";

                        email_notification(1, 'admin', $pattern, $subject, 'new_request', null);

                        if (!empty($driver_data)) {

                            $response_array = array(

                                'success' => true,

                                'unique_id' => 1,

                                'is_referral_active' => $referral_code_activation,

                                'is_referral_active_txt' => $referral_code_activation_txt,

                                'is_promo_active' => $promotional_code_activation,

                                'is_promo_active_txt' => $promotional_code_activation_txt,

                                'request_id' => $request->id,

                                'walker' => $driver_data,

                                'estimation' => ( !empty($estimation ) ? $estimation : 0 ),

                            );

                        } else {

                            $response_array = array(

                                'success' => false,

                                'unique_id' => 1,

                                'error' => 'Tipo de Servicio no encontrado.',

                                'error_messasges' => 'Tipo de Servicio no encontrado.',

                                'is_referral_active' => $referral_code_activation,

                                'is_referral_active_txt' => $referral_code_activation_txt,

                                'is_promo_active' => $promotional_code_activation,

                                'is_promo_active_txt' => $promotional_code_activation_txt,

                                'request_id' => $request->id,

                                'error_code' => 411,

                                'walker' => $driver_data,

                            );

                        }

                        $response_code = 200;

                    }

                } else {

                    $response_array = array('success' => false, 'error' => 'Token Expirado', 'error_code' => 405);

                    $response_code = 200;

                }

            } else {

                if ($is_admin) {

                    /* $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */

                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);

                } else {

                    $response_array = array('success' => false, 'error' => 'No es un token valido', 'error_code' => 406);

                }

                $response_code = 200;

            }

        }


        // $response = Response::json($response_array, $response_code);
        $response = json_encode(array($response_array, $response_code));
        return $response;


        DontcreateReq:

        Log::info('Request not created ');


    }

    public function isAdmin($token)
    {

        return false;

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
	


}

   