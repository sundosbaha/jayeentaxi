<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Model extends CI_Model {

function __construct() {
        parent::__construct();
		$this->load->model('Common_Model');
		      
    }

public function insertuser($name, $ubdate, $ujdate, $uage, $uimg, $udoc, $uemail, $uuname, $upass, $utype)
{
	$upass=$this->encrypt->encode($upass);
		$insert=array('name' => $name,'dateofbirth' => $ubdate,'dateofjoin' => $ujdate, 'age' => $uage, 'img' => $uimg, 'doc' => $udoc,'email' => $uemail, 'username' => $uuname, 'password' => $upass, 'type' => $utype);
		if($this->db->insert('userdetail', $insert))
		{
			return true;
		}
		else
		{
			return false;
		}
}

public function updateuser($name, $ubdate, $ujdate, $uage, $uimg, $udoc, $uemail, $uuname, $upass, $utype, $id)
{
	$upass=$this->encrypt->encode($upass);
		$insert=array('name' => $name,'dateofbirth' => $ubdate,'dateofjoin' => $ujdate, 'age' => $uage, 'img' => $uimg, 'doc' => $udoc,'email' => $uemail, 'username' => $uuname, 'password' => $upass, 'type' => $utype);
		$this->db->where('id',$id);
		if($this->db->insert('userdetail', $insert))
		{
			return true;
		}
		else
		{
			return false;
		}
}

public function chkUsrByMobile($mobileval)
{
	$chkmobileSql="SELECT * FROM owner WHERE phone LIKE '%".$mobileval."%'";
		$chkmobileExec=$this->db->query($chkmobileSql);
		$rowsReturned=$chkmobileExec->num_rows();
	return $rowsReturned;
}


public function getUsrByMobile($mobileval)
{
	$chkmobileSql="SELECT * FROM owner WHERE phone LIKE '%".$mobileval."%'";
	$chkmobileExec=$this->db->query($chkmobileSql);
	return $chkmobileExec->row();
}

public function chckname($username)
{
	$query=$this->db->get_where('userdetail', array('username' => $username));
	if($query->num_rows() == 1)
	{
		return 'false';
	}
	else
	{
		return 'true';
	}
}

public function img($id)
{
	$query=$this->db->get_where('userdetail', array('id' => $id));
	 $row=$query->row();
	 return $row->img;
	
}

public function doc($id)
{
	$query=$this->db->get_where('userdetail', array('id' => $id));
	$row=$query->row();
	return $row->doc;
	
}



}
