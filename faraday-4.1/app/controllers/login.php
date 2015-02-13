<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class login extends BCZ_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index($data = array())
	{
		 hash(md5('c37b773897b9194377bee9ad0683be92e53981dd'));
		// Check for the user session existence and redirect to the HOME page if user already logged in
		if ($_SESSION['bcz_user']->user_id) {
			

			redirect(base_url(), 'location', 301);
		} 

		// Check for any status messages from the query string params
		$status = $this->input->get('status', TRUE);
		switch ($status) {
			case 'activated':
				$data['messages']['success']['activate'] = 'Your account is successfully activated, now you can login and explore.';
				break;

			case 'no_user':
				$data['messages']['error']['activate'] = 'No account found to activate using this key, please check your email once again and try with the correct activation link.';
				break;
			
			default:
				break;
		}

		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'login';
		$this->load->view('LoginView', $data);
	}

	public function submit()
	{
		$email = trim($_POST['email']);
		$password = trim($_POST['password']);
		$data = array();
		
		// Email field validation
		if (!$email) {
			$data['messages']['error']['email'] = 'Email is required';
		}
		
		// Password field validation
		if (!$password) {
			$data['messages']['error']['password'] = 'Password is required';
		}

		// Login user
		if (!count($data['messages']['error'])) {
			$query = "select user_id, org_id, name, username, user_email, user_designation, report_to_id, mail_signature, demo,profile_pic, is_active from user where user_email = ? and password = ? and user_status = ? and user_login = ?";
			$params = array($email, sha1(md5($password)), 'active', 'yes');
			$resObj = $this->db->query($query, $params);

			if ($resObj->num_rows()) {
				$user = $resObj->row();
				
				

				// Super admin checkup
				if (!$user->org_id || ($user->user_designation == 'SupAdmin')) {
					$_SESSION['bcz_admin'] = $user;
					redirect(base_url().'admin', 'location', 301);
				}

				// User activation checkup
				if ($user->is_active == 'N') {
					$data['loginFailed'] = true;
					$data['messages']['error']['fail'] = 'Please activate your account to access, check out the welcome email for activation process.';
				
				} else {
					// Organization details
					$organization = $this->getOrganizationDetails($user->org_id);
					$orgLogo = $this->imagesPath . $organization->logo;
					if ($organization->logo && file_exists($orgLogo)) $user->org_logo = base_url() . $orgLogo;
					$user->organization = $organization;

					// Profile picture
					$profilePic = $this->imagesPath.$user->profile_pic;
					if ($user->profile_pic && file_exists($profilePic)) $user->profile_pic = base_url() . $profilePic;

					// User settings
					$user->settings = $this->getUserSettings($user->user_id);

					unset($_SESSION['bcz_admin']);
					$_SESSION['bcz_user'] = $user;
					$currDateTime = $this->getCurrTime();
					$currDate = $this->getCurrTime('Y-m-d');

					// TODO: Update user login time
					$auditQuery = $this->db->query('select * from user_audit where user_id = ? and date = ?', array($user->user_id, $currDate));
					$userAudit = $auditQuery->row();

					if ($userAudit->id) {
						$updateRes = $this->db->query('update user_audit set last_login = ? where user_id = ? and date = ?', array($currDateTime, $user->user_id, $currDate));
					}	else {
						$insertRes = $this->db->query('insert into user_audit (user_id, date, last_login) values (?, ?, ?)', array($user->user_id, $currDate, $currDateTime));					
					}

					redirect(base_url(), 'location', 301);
				}

			} else {
				$data['loginFailed'] = true;
				$data['messages']['error']['fail'] = 'The Email or password you entered is incorrect.';
			}
		}

		$data['submit'] = true;
		$data['email'] = $email;
		$this->index($data);
	}

	public function loggedout()
	{
		$this->pageTitle = $this->pageDesc = 'Logged Out';
		$this->bodyClass = 'login';
		$data['messages']['info']['logout'] = 'You are now signed out';
		$this->load->view('LoginView', $data);
	}

}