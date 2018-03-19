<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_Model extends CI_Model {

public function userchck($username, $password, $type)
{
	$query=$this->db->get_where('userdetail', array('username' => $username, 'is_active' => 1, 'type' => $type));
	if($query->num_rows() == 1)
	{
		$row=$query->row();
		
		if($this->encrypt->decode($row->password) == $password)
		{
			$session=array('userid' => $row->id,'name' => $row->name, 'img' => $row->img, 'email' => $row->email, 'usertype' => $row->type);
			$this->session->set_userdata($session);
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}
}
