<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends BCZ_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		// Track user session
		$updateRes = $this->db->query('update user_audit set last_logout = NOW(), duration = (duration + (unix_timestamp(NOW()) - unix_timestamp(last_login))) where user_id = ? and date = CURDATE()', $this->user->user_id);

		session_unset();
		session_destroy();
		//redirect(base_url().'login/loggedout', 'location', 301);
				$this->pageTitle = $this->pageDesc = 'Logged Out';
		$this->bodyClass = 'login';
		$data['messages']['info']['logout'] = 'You are now signed out';
		?><?php /*?>$location='http://rabbitcrm.com';
		echo '<script>window.location = "'.$location.'"</script>';<?php */?>
		<?php 	$this->load->view('LoginView', $data);
	}

}