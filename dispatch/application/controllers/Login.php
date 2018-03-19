<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {


 function __construct() {
        parent::__construct();
		$this->load->model('Common_Model'); 
		$this->load->model('Login_Model');     
    }
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		header('location: http://www.taxiappz.com/taxidemo/public/dispatch/login');
		$data['base']=$this->config->item('base_url');
		if($this->input->post())
		{
			$username=$this->input->post('username');
			$password=$this->input->post('password');
			$type=$this->input->post('type');
			$response=$this->Login_Model->userchck($username, $password, $type);
			if($response)
			{
				redirect('/Booking/booking');
			}
			else
			{
				echo "<script>
				alert('Invalid Username or Password');
				</script>";
			}
		}
		$this->load->view('login',$data);
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('/Login');
	}
	
}
