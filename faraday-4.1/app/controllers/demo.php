<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class demo extends BCZ_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$updateQuery = 'update user set demo = ? where org_id = ?';
		$params = array('1',$this->user->org_id);
		$updateRes = $this->db->query($updateQuery, $params);
		$this->user->demo=1;
		redirect(base_url(), 'location', 301);
		
		
	}

	
	
	

}