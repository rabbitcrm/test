<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user extends BCZ_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'user';
		$data['content'] = 'UserView';
		//$this->load->view('LoginView');
	}

	public function add($data=array())
	{
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'signup';
		$this->load->view('SignupView', $data);
	}

	// Create an user
	public function create() {
		// if (isset($_POST['terms'])) unset($_POST['terms']);
		// else redirect(base_url().'signup', '301');

		if (!isset($_POST['name'])) redirect(base_url().'signup', '301');

		// Gather form fields
		$formFields = array();
		$valsStr = '';
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal);
			$valsStr .= ($valsStr ? ', ' : '') . '?';
		}
		$password = $formFields['password'];
		$formFields['password'] = sha1(md5($formFields['password']));

		$formFields['user_designation'] = 'Admin';
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		// $formFields['report_to_id'] = 1;	// TODO: Need to confirm
		// $valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['user_status'] = 'active';
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['user_login'] = 'yes';
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['is_active'] = 'N';
		$valsStr .= ($valsStr ? ', ' : '') . '?';

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Password validation
		
		
		
		
		
		
		if (strlen($password) < 4) {
			$data['messages']['error']['password1'] = 'Password should be at least 4 characters.';
			$validationFailed = true;
		}
		if (!$validationFailed && ($password == $formFields['name'])) {
			$data['messages']['error']['password2'] = 'name and Password should not be same.';
			$validationFailed = true;
		}

		// name duplication check
		/*if (!$validationFailed) {
			$user = $this->getUserDetailsByUsername($formFields['username']);
			if ($user->user_id) {
				$data['messages']['error']['username'] = 'Username is already in use, please try with another one or check <a href="'.base_url().'/reset">Forgot Password</a>.';
				$validationFailed = true;
			}
		}
*/
		// Email duplication check
		if (!$validationFailed) {
			$user = $this->getUserDetailsByEmail($formFields['user_email']);
			if ($user->user_id) {
				$data['messages']['error']['user_email'] = 'Email is already in use, please try with another one or check <a href="'.base_url().'/reset">Forgot Password</a>.';
				$validationFailed = true;
			}
		}

		if (!$validationFailed) {
			// Create an organization with given details
			$orgId = $this->getTableAutoID('organizations');
			$uniqueStr = $orgId . $formFields['name'] . $formFields['user_email'];
			$orgUniqueSHA = sha1(md5($uniqueStr));
			$orgUniqueId = substr($orgUniqueSHA, 0, (10 - strlen($orgId))) . $orgId;

			$orgQuery = "insert into organizations (id, uid, name, email) values (?, ?, ?, ?)";
			$orgRes = $this->db->query($orgQuery, array($orgId, $orgUniqueId, $formFields['name'], $formFields['user_email']));

			// Create an user with given details
			$data = array();
			if ($orgRes) {
				$formFields['org_id'] = $orgId;
				$valsStr .= ($valsStr ? ', ' : '') . '?';
				
				$userId = $this->getTableAutoID('user');
				$userQuery = 'insert into user (' . implode(', ', array_keys($formFields)) . ') values (' . $valsStr . ')';
				$userRes = $this->db->query($userQuery, array_values($formFields));

				if ($userRes) {
					$activationKey = md5(sha1($userId.$formFields['name'].$formFields['user_email']));
					$updateQuery = 'update user set activation_key = ? where user_id = ?';
					$updateRes = $this->db->query($updateQuery, array($activationKey, $userId));
				$this->load->helper('directory');	
					
		 $folderPath = './assets/uploads/docs/'.$orgId;
		
		if (!is_dir($folderPath)) {
    mkdir($folderPath, 7777, true);
	
        }
		
					// Send an email to user with details
					$this->from = 'noreply@Rabbit.com';
					$this->to = $formFields['user_email'];
					$this->bcc = "swami@skyzon.com";
					$this->subject = 'Welcome to Rabbit!';
					// $this->attachments = array(base_url().'assets/img/welcome_email_logo.png');
					// $this->attachmentType = 'inline';

					//$this->message = "Hello ".$formFields['name'].",<br><br>Your Skyzon CRM account details are specified below.<br><br>name*: ".$formFields['name']."<br>Password*: ".$password."<br><br>*Please note that this is a case-sensitive password.<br><br>Now you can explore the <a href=".base_url().">Skyzon CRM</a>.<br><br>Sincerely,<br>Skyzon CRM Customer Service.";
					/*$this->message .= '<div style="text-align: center;"><img src="'.base_url().'assets/img/welcome_email_logo.png" width="187" height="122" /></div><br>';
					$this->message .= 'Hi'.$formFields['name'].'<br><br> &nbsp;
					We are extremely delighted to welcome you to our SkyzonCRM family!<br><br>';
					$this->message .= " &nbsp;Please click on the \"Activate SkyzonCRM\" icon below to complete signup process. The validity period for activation is 30 days. In the event you are unable to validate during this period, please feel free to write to us @ {$this->supportInfo['email']} or call {$this->supportInfo['contact']}.<br><br><br>";
					$this->message .= '<div style="text-align: center;">&nbsp;<a href="'.base_url().'activate?key='.$activationKey.'" target="_blank" style="font-size: 28px; color: #000; background-color: #93b656; padding: 10px 15px; border: 3px solid #ccc; border-radius: 5px; text-decoration: none;">Activate SkyzonCRM</a></div><br><br>';
					$this->message .= " &nbsp; For any other queries feel free to write to us @ {$this->supportInfo['email']} or call {$this->supportInfo['contact']}.<br><br>";
					$this->message .= '&nbsp; Looking forward to be a part of your success...<br><br>';
					$this->message .= '&nbsp; Thank you<br><br>SkyzonCRM Team<br><br>';
					$this->message .= '&nbsp; Skyzon Technologies<br>06/4C Revenue Nagar<br>Saravanampatti North<br>Coimbatore 641035<br>www.skyzon.com<br>Tel: +91 85080 80000';*/
					
					$this->message .='<style>
    @import url(http://192.168.0.6/crm/assets/css/Constance.ttf);
   /* All your usual CSS here */
</style><center>
<table border="0" cellpadding="0" cellspacing="0" width="800px">
	<tbody>
		<tr>
			<td bgcolor="#FFF" colspan="4" style="height:30px;text-align:center">
			<p><span style="font-family:Helvetica,Verdana,sans-serif;font-size:11px;color:#aaa">We &nbsp;would love to hear your feedback.</span></p>
			</td>
		</tr>
	</tbody>
</table>

<table bgcolor="#FFF" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #efefef;font-family:Helvetica,Verdana,sans-serif;font-size:12px" width="800px">
	<tbody>
		<tr>
			<td bgcolor="#F7F7F7" style="width:20px"></td>
			<td bgcolor="#F7F7F7"><a href="'.base_url().'" style="border-width:0px;border-style:solid;float:left;text-decoration: none;" target="_blank"><div style="text-align: center;"><div class="Section1">

<p class="MsoNormal"><span class="SpellE"><span style="font-size:36.0pt;line-height:
115%;font-family:Constance;mso-bidi-font-family:Consolas;color:#222222;
">Rabbit</span></span><span style="font-size:36.0pt;line-height:
115%;font-family:Constance;mso-bidi-font-family:Consolas;color:#018EC3;
">CRM</span><span style="font-size:36.0pt;line-height:
115%;font-family:Constance"><o:p></o:p></span></p>



</div></div><br> </a></td>
			<td bgcolor="#F7F7F7"></td>
			<td bgcolor="#F7F7F7" style="width:20px"></td>
		</tr>
		<tr>
			<td bgcolor="#FFF" colspan="4">
			<table border="0" cellpadding="0" cellspacing="0">
				<tbody>
					<tr>
						<td colspan="3" height="30"></td>
					</tr>
					<tr>
						<td width="30"></td>
						<td style="text-align:left;padding:0;font-family:Helvetica,Tahoma,sans-serif;font-size:16px;color:#333" valign="top">
						<div><span style="color:#696969"><span style="font-size:16px"><span style="font-family:arial,helvetica,sans-serif">Dear '.$formFields['name'].',</span></span></span><br>
						&nbsp;</div>

						<div></div>

						<div style="color:rgb(0,0,0);line-height:normal">
						<div style="color:rgb(0,0,0);font-family:"Times New Roman";font-size:medium"><span style="font-size:16px"><span style="font-family:arial,helvetica,sans-serif"><span style="color:#696969">Thank you for signing up!</span><span style="color:#808080"></span></span></span></div>

						<div style="color:rgb(0,0,0);font-family:"Times New Roman";font-size:medium"></div>

						<div style="color:rgb(0,0,0);font-family:"Times New Roman";font-size:medium"><span style="color:#696969"><span style="font-size:16px"><span style="font-family:arial,helvetica,sans-serif"><p>We are extremely delighted to welcome you to RabbitCRM family!  &nbsp; Please click on&nbsp; \'Activate RabbitCRM\' icon below to complete signup process. The validity period for activation is 30 days. In the event you are unable to validate during this period, please feel free to write to @ '.$this->supportInfo["email"].' or  call  '.$this->supportInfo["contact"].'.</p><br><br><br></span></span></span></div>
                        <div style="color:rgb(0,0,0);font-family:"Times New Roman";font-size:medium"><span style="font-size:16px"><span style="font-family:arial,helvetica,sans-serif"><span style="color:#696969">Thank you</span><span style="color:#808080"></span></span></span></div>
                        
                        <div style="color:rgb(0,0,0);font-family:"Times New Roman";font-size:medium"><span style="font-size:16px"><span style="font-family:arial,helvetica,sans-serif"><span style="color:#696969">RabbitCRM Team <br />www.rabbitcrm.com</span><span style="color:#808080"></span></span></span></div>
                        
                        
						<br><div style="text-align: center;">&nbsp;<a href="'.base_url().'activate?key='.$activationKey.'" target="_blank" style="font-size: 28px; color: #000; background-color: #93b656; padding: 10px 15px; border: 3px solid #ccc; border-radius: 5px; text-decoration: none;">Activate RabbitCRM</a></div><br>
						</div>
						</td>
						<td width="30"></td>
					</tr>
					<tr>
						<td colspan="3" height="30"></td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
		<tr>
			<td bgcolor="#2C3C4C" colspan="2" style="height:30px;color:#fff;width:50%">
			<div style="margin-left:10px">&nbsp; RabbitCRM</div>
			</td>
			<td bgcolor="#2C3C4C" colspan="2" style="height:30px;color:#fff;text-align:right;width:50%">
			<div style="margin-right:10px">Tel: +91 85080 80000&nbsp;</div>
			</td>
		</tr>
		<tr>
			<td bgcolor="#F7F7F7" colspan="4" height="8px"></td>
		</tr>
		
		<tr>
			<td bgcolor="#F7F7F7" colspan="4" height="8px"></td>
		</tr>
	</tbody>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="800px">
	<tbody>
		<tr>
			<td colspan="4" style="font-family:Helvetica,Verdana,sans-serif;font-size:11px;color:#aaa;height:30px;text-align:center" valign="middle">
			
			</td>
		</tr>
	</tbody>
</table>
</center>';

					$emailRes = $this->sendBCZEmail();

					if ($emailRes) {
						$data['messages']['success']['email'] = "&nbsp; Successfully created your account, please check your email for activation.";
						//redirect(base_url() . "login", 'location', 301);
					} else {
						$data['messages']['error']['fail'] = '&nbsp; Error sending user creation email';
					}

				} else {
					$data['messages']['error']['fail'] = '&nbsp; Error in user creation.';
				}
			} else {
				$data['messages']['error']['fail'] = '&nbsp; Error in user organization creation.';
			}
		}

		if (isset($data['messages']['error'])) {
			$data['name'] = $formFields['name'];
			$data['user_email'] = $formFields['user_email'];
			$data['terms'] = true;
		}

		$data['submit'] = true;
		$this->add($data);
	}

	public function activate() {
		$activationKey = $this->input->get('key', TRUE);
	
		// Check for user existence to activate
		$resObj = $this->db->query("select * from user where activation_key = ? and is_active = ?", array($activationKey, 'N'));
		if ($resObj->num_rows()) {
			$user = $resObj->row();
			 $updateQuery = 'update user set is_active = ? where activation_key = ? and user_id = ?';
			$updateRes = $this->db->query($updateQuery, array('Y', $activationKey, $user->user_id));
			if ($updateRes) $status = 'activated';
		} else {
			$status = 'no_user';
		}

		redirect(base_url().'login?status='.$status, '301');
	}

	public function profile()
	{
		// Update user
		if (isset($_POST['user_email'])) {
			$data = $this->update($_POST);
		}

		$users = $this->getAllUsers();

		if ($this->isAdmin && $this->user->report_to_id) {
			foreach ($users as $user) {
				if ($user->user_id == $this->user->report_to_id) $data['reporter'] = $user->name ? $user->name : $user->name;
			}
		}
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'profile';
		$data['content'] = 'ProfileView';
		$data['users'] = $users;
		$data['fields'] = $this->getAllUserFields();	// Get all user fields

		// Timezones
		$data['timezones'] = $this->timezoneList();
		
		$this->load->view('FirstLayoutView', $data);
	}

	public function update($postData)
	{
		// Gather form fields
		$data = $formFields = $settings = array();
		$valsStr = '';
		foreach ($postData as $fieldName => $fieldVal) {
			if (substr($fieldName, 0, 4) == 'set_') {
				$settings[str_replace('set_', '', $fieldName)] = $fieldVal;
				$valsStr .= ($valsStr ? ', ' : '') . '?';
			} else {
				$formFields[$fieldName] = trim($fieldVal);
			}
		}

		// Validation for password fields
		$currPassword = $formFields['curr_password'];
		$confPassword = $formFields['conf_password'];
		$password = $formFields['password'];
		unset($formFields['curr_password']);
		unset($formFields['conf_password']);
		unset($formFields['password']);

		if ($currPassword) {
			if (!$password) {
				$data['messages']['error']['password'] = 'Please specify your new password.';
			} else if ($password != $confPassword) {
				$data['messages']['error']['password'] = 'Passwords not matched.';
			} else {
				$userDetails = $this->getUserDetails($this->user->user_id);

				$currPassword = sha1(md5($currPassword));
				$password = sha1(md5($password));
				if ($currPassword != $userDetails->password) {
					$data['messages']['error']['password'] = 'Current password is incorrect.';
				} else if ($password == $currPassword) {
					$data['messages']['error']['password'] = 'New password should not be same as the current one.';
				} else {
					$formFields['password'] = $password;
				}
			}
		}

		// Don't update the mail signature to blank value
		if (!$formFields['mail_signature'])	unset($formFields['mail_signature']);

		// Update the profile picture if specified
		if ($_FILES['profile_pic']['name']) {
			$config['upload_path'] = $this->imagesPath;
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['max_size']	= '10240';
			$this->load->library('upload', $config);

			$uploadRes = $this->upload->do_upload('profile_pic');
			if ($uploadRes) {
				$uploadedFile = $this->upload->data();
				$formFields['profile_pic'] = $uploadedFile['file_name'];
			} else {
				$data['messages']['error']['upload'] = $this->upload->display_errors();
			}
		}

		// Update user settings
		if ($valsStr) {
			$userSettings = $this->getUserSettings($this->user->user_id);

			if (isset($userSettings->id)) {
				$query = 'update user_settings set ' . implode(' = ?, ', array_keys($settings)) . ' = ? where user_id = ?';
				$settings['user_id'] = $this->user->user_id;
				$settingsRes = $this->db->query($query, array_values($settings));
			} else {
				$settings['user_id'] = $this->user->user_id;
				$valsStr .= ($valsStr ? ', ' : '') . '?';
				$query = 'insert into user_settings (' . implode(', ', array_keys($settings)) . ') values (' . $valsStr . ')';
				
				$settingsRes = $this->db->query($query, array_values($settings));
			}

			if (!$settingsRes) {
				$data['messages']['error']['settings'] = 'Something went wrong while updating your settings, please try again after sometime.';
			}
		}

		// Update profile with new data
		if (!count($data['messages']['error'])) {
			$query = 'update user set ' . implode(' = ?, ', array_keys($formFields)) . ' = ? where user_id = ?';
			$formFields['user_id'] = $this->user->user_id;
			$res = $this->db->query($query, array_values($formFields));

			if ($res) {
				//redirect(base_url() . "deals/details/$id", 'location', 301);
				$data['messages']['success']['update'] = 'Your profile has been updated succesfully.';

				// Update user session
				foreach ($formFields as $fk => $fv) {
					if ($fk == 'profile_pic')	$fv = base_url() . $this->imagesPath . $fv;
					$_SESSION['bcz_user']->$fk = $fv;
				}
				// Update user session settings
				foreach ($settings as $sk => $sv) {
					$_SESSION['bcz_user']->settings->$sk = $sv;
				}
				$this->user = $_SESSION['bcz_user'];
			} else {
				$data['messages']['error']['fail'] = 'Something went wrong while updating your profile, please try again after sometime.';
			}
		}

		return $data;
	}

}