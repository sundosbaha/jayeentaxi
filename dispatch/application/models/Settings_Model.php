<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_Model extends CI_Model {
	
	public function load_countrycode()
	{
		$this->db->select('*');
        $this->db->from('config_settings');
        $query = $this->db->get();
        $result = $query->row();
		return $result-> configValue;
	}
}