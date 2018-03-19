<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {


 function __construct() {
        parent::__construct();
		$this->load->model('Common_Model');  
		$this->load->model('User_Model');      
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
		if($this->Common_Model->session_chck())
		{
		$data['base']=$this->config->item('base_url');

		if($this->input->post())
		{
		$name=$this->input->post('uname');
		$ubdate=$this->input->post('ubdate');
		$ujdate=$this->input->post('ujdate');
		$uage=$this->input->post('uage');
		
		if($_FILES['uimg']['name'] != '')
		{
		//insert image
		$config['upload_path'] = './assets/image/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['file_name'] = 'img_'.uniqid();
		$config['max_size']	= '10000';
		$this->upload->initialize($config);
		if($this->upload->do_upload('uimg'))
		{
		$uimg=$this->upload->data();
		$uimg='assets/image/'.$uimg['orig_name'];
		
		}
		else
		{
		$data['error'] = array('error' => $this->upload->display_errors());
		goto end1;	
		}
		}else
		{
		$uimg='assets/image/userdef.jpg';	
		}
		
		if($_FILES['udoc']['name'] != '')
		{
		$config['upload_path'] = './assets/image/';
		$config['allowed_types'] = 'doc|docx|pdf|js';
		$config['file_name'] = 'doc_'.uniqid();
		$config['max_size']	= '10000';
		$this->upload->initialize($config);
		if($this->upload->do_upload('udoc'))
		{
		$udoc= $this->upload->data();
		$udoc='assets/image/'.$udoc['orig_name'];
		}
		else
		{
		$data['error'] =  $this->upload->display_errors();
		//$data['error']=$error['error'];
		goto end1;	
		}
		}
		else
		{
		$udoc='';	
		}
		
		$uemail=$this->input->post('uemail');
		$uuname=$this->input->post('uuname');	
		$upass=$this->input->post('upass');
		$utype=$this->input->post('utype');
		$response=$this->User_Model->insertuser($name, $ubdate, $ujdate, $uage, $uimg, $udoc, $uemail, $uuname, $upass, $utype);
		
		if($response)
		{
		echo "<script>
		alert('Added Successfully');
		window.location.href('Users/index');
		</script>";
			//redirect('Users/index','refresh');
		}
		else
		{
		echo "<script>
		alert('Insert Failed');
		window.location.href('Users/index');
		</script>";
		//redirect('Users/index','refresh');
		}
		}
		end1:
		$select=$this->db->get('userdetail');
		$data['user']=$select->result();
		$this->load->view('users',$data);
		}
	}
	
	public function chckname()
	{
		if($this->Common_Model->session_chck())
		{
		$username=$this->input->post('username');
		$user=$this->User_Model->chckname($username);
		die($user);
		
		}
	}
	
	public function Update($id)
	{
		if($this->Common_Model->session_chck())
		{
		$data['base']=$this->config->item('base_url');

		if($this->input->post())
		{
		$name=$this->input->post('uname');
		$ubdate=$this->input->post('ubdate');
		$ujdate=$this->input->post('ujdate');
		$uage=$this->input->post('uage');
		
		if($_FILES['uimg']['name'] != '')
		{
		//insert image
		$config['upload_path'] = './assets/image/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['file_name'] = 'img_'.uniqid();
		$config['max_size']	= '10000';
		$this->upload->initialize($config);
		if($this->upload->do_upload('uimg'))
		{
		$uimg=$this->upload->data();
		$uimg='assets/image/'.$uimg['orig_name'];
		
		}
		else
		{
		$data['error'] = array('error' => $this->upload->display_errors());
		goto end1;	
		}
		}else
		{
		//$uimg='assets/image/userdef.jpg';	
		$uimg=$this->User_Model->img($id);
		}
		
		if($_FILES['udoc']['name'] != '')
		{
		$config['upload_path'] = './assets/image/';
		$config['allowed_types'] = 'doc|docx|pdf|js';
		$config['file_name'] = 'doc_'.uniqid();
		$config['max_size']	= '10000';
		$this->upload->initialize($config);
		if($this->upload->do_upload('udoc'))
		{
		$udoc= $this->upload->data();
		$udoc='assets/image/'.$udoc['orig_name'];
		}
		else
		{
		$data['error'] =  $this->upload->display_errors();
		goto end1;	
		}
		}
		else
		{
		$udoc=$this->User_Model->doc($id);	
		}
		
		$uemail=$this->input->post('uemail');
		$uuname=$this->input->post('uuname');	
		$upass=$this->input->post('upass');
		$utype=$this->input->post('utype');
		$response=$this->User_Model->insertuser($name, $ubdate, $ujdate, $uage, $uimg, $udoc, $uemail, $uuname, $upass, $utype, $id);
		
		if($response)
		{
		echo "<script>
		alert('Added Successfully');
		window.location.href('Users/index');
		</script>";
		//redirect('Users/index');
		}
		else
		{
		echo "<script>
		alert('Insert Failed');
		window.location.href('Users/index');
		</script>";
		//redirect('Users/index');
		}
		}
		end1:
		$select=$this->db->get_where('userdetail',array('id' => $id));
		$data['user']=$select->row();
		$this->load->view('edit_users',$data);
		}
	}
	
	
	public function changestatus()
	{
		$id=$this->input->post('id');
		$query=$this->db->get_where('userdetail',array('id' => $id));
		$row=$query->row();
		if($row->is_active == 1)
		{
			$update=array('is_active' => 0);
		}
		else
		{
			$update=array('is_active' => 1);
		}
		$this->db->where('id',$id);
		$this->db->update('userdetail',$update);
		echo "success";
		
	}
	
}
