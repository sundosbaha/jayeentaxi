<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller {

	 function __construct() {
        parent::__construct();
		$this->load->model('Common_Model');
		$this->load->model('User_Model');      
		$this->load->model('Common_Model'); 
		$this->load->model('Booking_Model');   
		$this->load->model('Settings_Model'); 
    }
	
	public function booking()
	{
		if($this->Common_Model->session_chck())
		{
			if($this->session->flashdata('noaccess'))
			{
				$data['accessmsg']=true;
			}
		$data['base']=$this->config->item('base_url');
		$data['country_code']=$this->Settings_Model->load_countrycode();
		$this->load->view('booking',$data);
		}
	}
	
	public function getuserDetails()
	{
		$mobileval=$this->input->post("mobileval");
		
		if($this->User_Model->chkUsrByMobile($mobileval) > 0)
		{
			
			$customerRow=$this->User_Model->getUsrByMobile($mobileval);
			$customerArray=array('cExists'=>1,'cName'=>$customerRow->first_name,'cLastname' => $customerRow->last_name);
			echo json_encode($customerArray);
		}
		else
		{
			$customerArray=array('cExists'=>0,'cName'=>NULL);
			echo json_encode($customerArray);
		}
	}
	
	public function addBooking()
	{
			$rideRequestURL=API_ENDPOINT_URL.'/user/createrequest';
			
			$latitude 		= 		$this->input->post("pickupLatitude");
			$longitude		= 		$this->input->post("pickupLongitude");
			$d_latitude 	= 		$this->input->post("dropoffLatitude");
			$d_longitude 	= 		$this->input->post("dropoffLongitude");
			$pickupDetails 	= 		$this->input->post("pickupDetails");
			$dropoffDetails = 		$this->input->post("dropoffDetails");
			$adultCount 	= 		$this->input->post("numberOfadults");
			$childCount		= 		$this->input->post("noOfchildren");
			$luggageCount 	= 		$this->input->post("luggageCount");
			$rideComment 	= 		$this->input->post("rideComment");
			$type			=		$this->input->post("typeofCar");
			$scheduleVal	=		$this->input->post("schedule");
			$token_Val		=		0;
			$usrId			=		0;
			$executive_user_id	=		$this->input->post("executive_user_id");
			
			if($scheduleVal!=0)
			{
			$scheduleVal	= 		$this->input->post("booking_schedule");
			
			}
			else
			{
				
			}

		//echo "test";
		if($this->User_Model->chkUsrByMobile($this->input->post("mobileval")) == 0)
		{
			$userRegisterURL=API_ENDPOINT_URL.'user/register';
			
			$data=array(
			'first_name' 	 =>     $this->input->post("first_name"),
			'last_name'		 =>		$this->input->post("last_name"),
			'email' 		 =>		$this->input->post("email"),
			'phone' 		 =>		$this->input->post("phone"),
			'password'		 =>		$this->input->post("password"),
			'device_token'	 =>		$this->input->post("device_token"),
			'device_type'	 =>		$this->input->post("device_type"),
			'otp' 			 =>		'false',
			"login_by"		 => 	"manual"
			);
			$response=$this->Common_Model->send_request($userRegisterURL,$data,'POST');
			$json_obj=json_decode ($response);
			//echo $response;
			//$usrId=$json_obj->{'id'};
			//$token_Val		=		$json_obj->{'token'};
			
		}
		else
		{
			$userDetails=$this->User_Model->getUsrByMobile($this->input->post("mobileval"));
			$usrId 		=	$userDetails->id;
			$token_Val	=	$userDetails->token;
		}

		$requestData=array(
			'token' 			=> 			$token_Val,
			'id' 				=>			$usrId,
			'latitude' 			=>  		$latitude,
			'longitude' 		=> 			$longitude, 
			'd_latitude'		=> 			$d_latitude,
			'd_longitude' 		=> 			$d_longitude,
			'pickupDetails' 	=> 			$pickupDetails,
			'dropoffDetails' 	=> 			$dropoffDetails,
			'adultCount' 		=> 			$adultCount,
			'childCount' 		=> 			$childCount,
			'lugaggeCount' 		=> 			$luggageCount,
			'rideComments' 		=> 			$rideComment,
			'payment_opt' 		=> 			1,
			'type'				=>			$type,
			'scheduleDatetime'	=>			$scheduleVal,
			'executive_user_id'	=>			$executive_user_id
			 );
			 
		$Rideresponse=$this->Common_Model->send_request($rideRequestURL,$requestData,'POST');
		echo $Rideresponse;
	}
	
	

	
	public function addSchedule()
	{
			$rideRequestURL=API_ENDPOINT_URL.'/user/createrequest';
			
			$usrAddress 	=		$this->input->post("customerAddress");
			$latitude 		= 		$this->input->post("pickupLatitude");
			$longitude		= 		$this->input->post("pickupLongitude");
			$d_latitude 	= 		$this->input->post("dropoffLatitude");
			$d_longitude 	= 		$this->input->post("dropoffLongitude");

			$adultCount 	= 		$this->input->post("numberOfadults");
			$childCount		= 		$this->input->post("noOfchildren");
			$luggageCount 	= 		$this->input->post("luggageCount");
			$rideComment 	= 		$this->input->post("rideComment");
			
			$type			=		$this->input->post("typeofCar");
			$token_Val		=		0;
			$usrId			=		0;
			
			$pickupAddress	=		$this->input->post("pickupLocation");
			$pickupDetails 	= 		$this->input->post("pickupDetails");
			$dropLocation	=		$this->input->post("dropoffLoc");
			$dropoffDetails = 		$this->input->post("dropoffDetails");
			$scheduleTime	=		$this->input->post("schedule");
			$user_timezone	=		$this->input->post("user_timezone");
			$executive_user_id	=	$this->input->post("executive_user_id");
		//echo "test";
		if($this->User_Model->chkUsrByMobile($this->input->post("mobileval")) == 0)
		{
			$userRegisterURL=API_ENDPOINT_URL.'user/register';
			// values from ajax request
			$data=array(
			'first_name' 	 =>     $this->input->post("first_name"),
			'last_name'		 =>		$this->input->post("last_name"),
			'email' 		 =>		$this->input->post("email"),
			'phone' 		 =>		$this->input->post("phone"),
			'password'		 =>		$this->input->post("password"),
			'device_token'	 =>		$this->input->post("device_token"),
			'device_type'	 =>		$this->input->post("device_type"),
			'otp' 			 =>		'false',
			"login_by"		 => 	"manual"
			);
			
			$response=$this->Common_Model->send_request($userRegisterURL,$data,'POST');
			$json_obj=json_decode ($response);
			$usrId=$json_obj->{'id'};
			$token_Val		=		$json_obj->{'token'};
			
		}
		else
		{
			$userDetails=$this->User_Model->getUsrByMobile($this->input->post("mobileval"));
			$usrId 		=	$userDetails->id;
			$token_Val	=	$userDetails->token;
		}

		$requestData=array(
			'token' 			=> 			$token_Val,
			'schedule_datetime' =>			$scheduleTime,
			'userid' 			=>			$usrId,
			'usrAddress'		=>			$usrAddress,
			'pickupAddress'		=>			$pickupAddress,
			'pickupDetails'		=>			$pickupDetails,
			'destinationAddress'=>			$dropLocation,
			'destinationDetails'=>			$dropoffDetails,
			'no_of_adults' 		=> 			$adultCount,
			'no_of_children' 	=> 			$childCount,
			'luggages' 			=> 			$luggageCount,
			'type'				=>			$type,
			'rideComment' 		=> 			$rideComment,
			'pickupLatitude' 	=>  		$latitude,
			'pickupLongitude' 	=> 			$longitude, 
			'drop_latitude'		=> 			$d_latitude,
			'drop_longitude' 	=> 			$d_longitude,
			'dropoffDetails' 	=> 			$dropoffDetails,
			'payment_opt' 		=> 			1,
			'user_timezone'		=>			$user_timezone,
			'executive_user_id'		=>		$executive_user_id
			 ); 
			
			$tempResponse= $this->Booking_Model->Add_Temp_Booking($requestData);
			//$tempResponse["post_array"][]=print_r($_POST);
	
			if($tempResponse)
			{
			$tempResponse=array('success' => true);
			}
			else
			{
			$tempResponse=array('success' => false);	
			}
			echo  json_encode($tempResponse);
		//$Rideresponse=$this->Common_Model->send_request($rideRequestURL,$requestData,'POST');
		
	}
	
	public function scheduleBooking()
	{
		
		
	}
	
	public function booking_details()
	{
		$executiveId=$this->session->userdata('userid');
		$data['base']=$this->config->item('base_url');
		$data['all_bookings']=$this->Booking_Model->getallBookings($executiveId);
		
		$this->load->view('booking_details',$data);
	}
	
	public function schedule_details()
	{
		$executiveId=$this->session->userdata('userid');
		$data['base']=$this->config->item('base_url');
		$data['get_Schedules']=$this->Booking_Model->getallSchedules($executiveId);
		$this->load->view('schedule_details',$data);
	}
	
	public function getUserSchedules()
	{
		$userId=$this->input->post("userId");
		$modelResponse=$this->Booking_Model->getuserSchedules($userId);
		echo $modelResponse;
	}
	
	public function cancelSchedules()
	{
		$id=$this->input->post("id");
		$modelresponse=$this->Booking_Model->cancelSchedules($id);
		echo json_encode($modelresponse);
	}

	
	
	
}
