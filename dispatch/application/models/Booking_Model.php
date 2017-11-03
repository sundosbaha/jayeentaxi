<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_Model extends CI_Model {
	
	function __construct() {
        parent::__construct();
		$this->load->model('Common_Model');   
    }
	
	public function Add_Temp_Booking($insertData)
	{
	$this->db->insert('temp_assign', $insertData); 	
	$assignId=$this->db->insert_id();
	$this->db->select('*');
    $this->db->from('temp_assign');
	$this->db->where('assignId',$assignId);
    $query = $this->db->get();
	$responseArray=array('success'=>true,'drop'=>$this->input->post("dropoffLocation"),'dropDet' => $this->input->post("pickupLocation"));
    return $responseArray;
	
	
	}
	
	public function getallBookings($executiveId)
	{
		$getBookingsSql="";
		
		$getBookingsSql="SELECT owner.first_name as owner_first_name, owner.last_name as owner_last_name, walker.first_name as walker_first_name, walker.last_name as walker_last_name, owner.id as owner_id, walker.id as walker_id, walker.merchant_id as walker_merchant, request.id as id, request.created_at as date, request.payment_mode, request.is_started, request.is_walker_arrived, request.payment_mode, request.is_completed, request.is_paid, request.is_walker_started, request.confirmed_walker, request.status as request_status, request.time, request.distance, request.total, request.is_cancelled,request.transfer_amount,executives.name as execName FROM request,walker,owner,userdetail executives WHERE request.confirmed_walker= walker.id AND request.owner_id=owner.id";
		if(!$this->isExecutiveAdmin($executiveId))
		{
			$getBookingsSql.=" AND request.executive_id != 0 AND request.executive_id=executives.id   AND request.executive_id =".$executiveId;
		}
		$getBookingsSql.=" GROUP BY request.id";
		
		$getBookingsSql.="  ORDER BY request.created_at DESC";
		$BookingExec=$this->db->query($getBookingsSql);
		return $BookingExec->result();
		
	}
	
	public function isExecutiveAdmin($executiveId)
	{
		$getExecutiveSql="SELECT type FROM userdetail WHERE id=".$executiveId;
		$getExecutiveExec=$this->db->query($getExecutiveSql);
		$getExecutive=$getExecutiveExec->row();
		if($getExecutive->type == 'admin')
		{
			return true;
		}
		else
		{
			return false;
		}
	}

    public function getallSchedules($executiveId)
    {
        $getschedulesSql = "SELECT assign.*,owner.first_name ,owner.last_name,DATE_FORMAT(schedule_datetime,'%h:%i %p') as scheduleTime,DATE_FORMAT(schedule_datetime,'%b %d, %Y') as scheduleDate FROM temp_assign assign,owner,userdetail executives WHERE assign.userid =owner.id ";
        if (!$this->isExecutiveAdmin($executiveId)) {
            $getschedulesSql .= " AND executives.id = assign.generated_by AND executives.id = " . $executiveId;
        }
        $getschedulesSql .= " GROUP BY assignId";
        //die($getschedulesSql);
        $scheduleExec = $this->db->query($getschedulesSql);
        return $scheduleExec->result();
    }
	
		public function getExecutiveName($executiveId)
	{
		$getExecutiveSql="SELECT * FROM userdetail WHERE id=".$executiveId;
		$getExecutiveExec=$this->db->query($getExecutiveSql);
		return $getExecutive=$getExecutiveExec->row();
	
	}
	
	public function getuserSchedules($userID)
	{
		$getschedulesSql="SELECT assign.*,owner.first_name ,walker_type.id, walker_type.name as car_type,owner.last_name,DATE_FORMAT(schedule_datetime,'%H:%i') as scheduleTime,DATE_FORMAT(schedule_datetime,'%b %d, %Y') as scheduleDate FROM temp_assign assign,owner,walker_type WHERE assign.userid =owner.id AND walker_type.id=assign.type  AND assign.userid=".$userID." ORDER BY assign.schedule_datetime ASC, is_cancelled DESC";
		$scheduleExec=$this->db->query($getschedulesSql);
		$resultVal=$scheduleExec->result();
		
		$response2=array();
		foreach($resultVal as $result)
		{
			if($result->scheduleDate != '')
			{
				$scheduledate=$result->scheduleDate;
			}
			else
			{
				$scheduledate="";
			}
			
			if($result->scheduleTime != '')
			{
				$scheduleTime=$result->scheduleTime;
			}
			else
			{
				$scheduleTime="";
			}
			
			if($result->pickupAddress != '')
			{
				$pickupAddress=$result->pickupAddress;
			}
			else
			{
				$pickupAddress="";
			}
			
			if($result->destinationAddress != '')
			{
				$dropAddress=$result->destinationAddress;
			}
			else
			{
				$dropAddress="";
			}
			
			if($result->assignId != '')
			{
				$assignId=$result->assignId;
			}
			else
			{
				$assignId="";
			}
			$temparray= array('scheduleDate'  => $scheduledate,
			'scheduleTime'  => $scheduleTime, 
			'pickupAddress' => $pickupAddress,
			'droppAddress'  => $dropAddress,
			'car_type' => $result->car_type,
                'is_cancelled' => $result->is_cancelled ? true : false,
			'id' => $assignId
			);
			array_push($response2,$temparray);
	
		}
		$response=array('success' => true,
            'requests' => $response2
		);
	return json_encode($response);
		
		
	}
	
	public function getPickup($reqId)
	{
		$getPickupSql="SELECT * FROM walk_location WHERE request_id=".$reqId." ORDER BY created_at DESC";
		$getPickExec=$this->db->query($getPickupSql);
		$getPickResult=$getPickExec->row();
		return $getPickResult;
	}
	
	public function getDrop($reqId)
	{
		$getDropSql="SELECT * FROM walk_location WHERE request_id=".$reqId." ORDER BY created_at ASC";
		$getDropexec=$this->db->query($getDropSql);
		$getDropRow=$getDropexec->row();
		return $getDropRow;
	}

		public function get_address_trip($lat, $long)
{
	try
					{
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://maps.googleapis.com/maps/api/geocode/json?address='.$lat.','.$long.'&key= AIzaSyAPy4Wb80pw8VSbLMIP_8BZUjpAaTdxrSc ',
    CURLOPT_USERAGENT => ''
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
$address=json_decode($resp, true);
$result=$address['results'][0]['formatted_address'];
// Close request to clear up some resources
curl_close($curl);
return $result;
					}
					catch(Exception $e)
					{
						
					$result="Api Error";
					return $result;	
					}
}

public function get_location($latitude,$longitude)
{
	$geolocation = $latitude.','.$longitude;
$request = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&sensor=false'; 
$file_contents = file_get_contents($request);
$json_decode = json_decode($file_contents);
if(isset($json_decode->results[0])) {
    $response = array();
    foreach($json_decode->results[0]->address_components as $addressComponet) {
        if(in_array('political', $addressComponet->types)) {
                $response[] = $addressComponet->long_name; 
        }
    }

    if(isset($response[0])){ $first  =  $response[0];  } else { $first  = 'null'; }
    if(isset($response[1])){ $second =  $response[1];  } else { $second = 'null'; } 
    if(isset($response[2])){ $third  =  $response[2];  } else { $third  = 'null'; }
    if(isset($response[3])){ $fourth =  $response[3];  } else { $fourth = 'null'; }
    if(isset($response[4])){ $fifth  =  $response[4];  } else { $fifth  = 'null'; }

    if( $first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth != 'null' ) {
        echo "<br/>Address:: ".$first;
        echo "<br/>City:: ".$second;
        echo "<br/>State:: ".$fourth;
        echo "<br/>Country:: ".$fifth;
    }
    else if ( $first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth == 'null'  ) {
        echo "<br/>Address:: ".$first;
        echo "<br/>City:: ".$second;
        echo "<br/>State:: ".$third;
        echo "<br/>Country:: ".$fourth;
    }
    else if ( $first != 'null' && $second != 'null' && $third != 'null' && $fourth == 'null' && $fifth == 'null' ) {
        echo "<br/>City:: ".$first;
        echo "<br/>State:: ".$second;
        echo "<br/>Country:: ".$third;
    }
    else if ( $first != 'null' && $second != 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null'  ) {
        echo "<br/>State:: ".$first;
        echo "<br/>Country:: ".$second;
    }
    else if ( $first != 'null' && $second == 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null'  ) {
        echo "<br/>Country:: ".$first;
    }
  }
	
	
	
}

public function cancelSchedules($id)
{
	if($userdata=$this->db->get_where('temp_assign', array('assignId' => $id)))
	{
		$update=array('is_cancelled' => 1);
		$this->db->where('assignId',$id);
		$this->db->update('temp_assign', $update);
		return array('success' => true);
	}
	else
	{
		return array('success' => false);
	}
}


	
}
	