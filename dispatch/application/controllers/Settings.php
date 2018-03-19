<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
 
 function __construct() {
        parent::__construct();
		$this->load->model('Common_Model'); 
		$this->load->model('Login_Model'); 
		$this->load->model('Settings_Model'); 
		
    }
	
	public function index()
	{
		$data['base']=$this->config->item('base_url');
		$data['country_code']=$this->Settings_Model->load_countrycode();
		$this->load->view('settings',$data);
	}
	
	public function updateCountrycode()
	{
		$data['base']=$this->config->item('base_url');
		$countryCode=$this->input->post('country_code');
		$updateData=array("configValue"=>$countryCode);
		$this->db->where("configid",1);
		$this->db->update("config_settings",$updateData);   
		$this->config->set_item('country_code', $countryCode);
		
		//$this->config->set_item('country_code', $countryCode);
		redirect($data['base'].'Settings/index','refresh');
		
	}
	
	
	
	
}

?>