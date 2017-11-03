<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller {

	 function __construct() {
        parent::__construct();
		$this->load->model('Common_Model');
		$this->load->model('User_Model');      
		$this->load->model('Common_Model');      
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
			'scheduleDatetime'	=>			$scheduleVal		
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
			$dropLocation	=		$this->input->post("dropoffLocation");
			$dropoffDetails = 		$this->input->post("dropoffDetails");
			
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
			'userid' 			=>			$usrId,
			'usrAddress'		=>			$usrAddress,
			'pickupAddress'		=>			$pickupAddress,
			'pickupDetails'		=>			$pickupDetails,
			'destinationAddress'=>			$dropLocation,
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
			'type'				=>			$type	
			 ); 
		//$Rideresponse=$this->Common_Model->send_request($rideRequestURL,$requestData,'POST');
		
	}
	
	public function scheduleBooking()
	{
		
		
	}
	
	
	
	
	
}
