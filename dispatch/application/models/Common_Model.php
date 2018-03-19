<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_Model extends CI_Model {

public function access_chck()
{
	$class=$this->router->fetch_class();
	$function=$this->router->fetch_method();
	
}

public function session_chck()
{
	if($this->session->userdata('userid'))
	{
		$class=$this->router->fetch_class();
		$page=array('Users');
		if($this->session->userdata('usertype') == 'staff' && in_array($class, $page))
		{
			$this->session->set_flashdata('noaccess', true);
		redirect('/Booking/booking');	
		}
	}
	else
	{
		redirect('/Login');	
	}
	return true;
}

public function send_request($url,$data,$requestType)
{	
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url,
    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => $data
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);
 return  $resp;
}


}