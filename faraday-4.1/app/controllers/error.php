<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error extends BCZ_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
	
	$this->load->view('404-page');
	
	}

	
}