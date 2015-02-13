<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class help extends BCZ_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index($data = array())
	{
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'help';
		$data['content'] = 'HelpView';
		$this->load->view('FirstLayoutView', $data);
	}
	
	public function submit() {
		
		// Gather form fields
		
		$data = $formFields = $params = array();
		$currDateTime = $this->getCurrTime();
		$valsStr = '';
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal);
			$valsStr .= ($valsStr ? ', ' : '') . '?';
		}
		
		$formFields['org_id'] = $this->user->org_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['user_id'] = $this->user->user_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['create_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['modify_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';

		 $cquery = 'insert into support (' . implode(', ', array_keys($formFields)) . ') values (' . $valsStr . ')';
		$cres = $this->db->query($cquery, array_values($formFields));
		
		if ($cres) {
			// Log activity

			redirect(base_url() . 'help/send', 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while creating this Query, please try again after sometime.';
		}
		
	}
	
	
		public function send($data = array())
	{
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'help';
		$data['content'] = 'HelpView';
		$data['messages']['success']='Thank you for sending us your query/feedback. Our experts will get in touch with you shortly.';
		$this->load->view('FirstLayoutView', $data);
	}

}