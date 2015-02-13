<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class reset extends BCZ_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index($data = array())
	{
		$this->bodyClass = 'reset-password';
		$this->pageTitle = $this->pageDesc = 'Password Recovery';
		$this->load->view('ResetPasswordView', $data);
	}
	
	public function activate() {
	
	$activationKey = $this->input->get('key', TRUE);
	
		$resObj = $this->db->query("select * from user where activation_key = ? ", array($activationKey));
		if ($resObj->num_rows()) {
			
			$user				=	$resObj->row();
			
			 $_SESSION['bcz_admin_user_id']	=	$user->user_id;
			$this->load->view('ChangePasswordView');
		
			} else {
		show_404();
			
		}
	}
		public function change()
	{
		 $password=sha1(md5(trim($_POST['password'])));
		
		$updateQuery = 'update user set password = ? , activation_key = ? where user_id = ?';
					$updateRes = $this->db->query($updateQuery, array($password,"",$_SESSION['bcz_admin_user_id']));
					
				
					
					// Login user
		if ($updateQuery) {
			$query = "select user_id, org_id, name, username, user_email, user_designation, report_to_id, mail_signature, demo,profile_pic, is_active from user where user_id = ? and user_status = ? and user_login = ?";
			$params = array($_SESSION['bcz_admin_user_id'],'active', 'yes');
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

	public function submit()
	{
		 $email = trim($_POST['email']);
		$data = array();
		
		// Email field validation
		if (!$email) {
			$data['messages']['error']['email'] = 'Email is required';
		}
		
		
		// Reset password
		if (!count($data['messages']['error'])) {
			
			$query = "select user_id, username,user_email, name from user where user_email = ?";
			$resObj = $this->db->query($query, array($email));

			if ($resObj->num_rows()) {
				
				$user = $resObj->row();
				
	
				$userId=$user->user_id;
				$userId.=$user->org_id;
				$userId.=$user->user_email;
					$activationKey = md5(sha1($userId));
					$updateQuery = 'update user set activation_key = ? where user_id = ?';
					$updateRes = $this->db->query($updateQuery, array($activationKey,$user->user_id));
					

				
				$password = $this->randPass();
				//$resetPassRes = $this->db->query("update user set password = ? where user_email = ?", array(sha1(md5($password)), $email));

				if ($updateRes) {
					$this->from = 'noreply@Rabbit.com';
					$this->to = $email;
					$this->subject = "Change Password";
					$this->message = '<center>
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
			<table border="0" cellpadding="0" cellspacing="0" style="
    width: 100%;
">
				<tbody>
					<tr>
						<td colspan="3" height="30"></td>
					</tr>
					<tr>
						<td width="30"></td>
						<td style="text-align:left;padding:0;font-family:Helvetica,Tahoma,sans-serif;font-size:16px;color:#333" valign="top">
						<div ><span style="color:#696969"><span style="font-size:16px"><span style="font-family:arial,helvetica,sans-serif">Dear '.$user->name.',</span></span></span>
						&nbsp;</div>

						<div></div>

						<div style="color:rgb(0,0,0);line-height:normal">
						

						<div style="color:rgb(0,0,0);font-family:"Times New Roman";font-size:medium"><span style="color:#696969"><span style="font-size:16px"><span style="font-family:arial,helvetica,sans-serif"><p><br>We have received a request to change your password on RabbitCRM.<br/><br/>Click the link below to set a new password.</p><br><br>
                       
                        
						<br><div style="text-align: center;">&nbsp;<a href="'.base_url().'reset/activate?key='.$activationKey.'" target="_blank" style="font-size: 28px; color: #000; background-color: #93b656; padding: 10px 15px; border: 3px solid #ccc; border-radius: 5px; text-decoration: none;">Change Password</a></div><br>
						 <br><br>
                         <div style="color:rgb(0,0,0);font-family:"Times New Roman";font-size:medium"><span style="font-size:16px"><span style="font-family:arial,helvetica,sans-serif"><span style="color:#696969">Thank you</span><span style="color:#808080"></span></span></span></div>
                        <div style="color:rgb(0,0,0);font-family:"Times New Roman";font-size:medium"><span style="font-size:16px"><span style="font-family:arial,helvetica,sans-serif"><span style="color:#696969">RabbitCRM Team <br />www.rabbitcrm.com</span><span style="color:#808080"></span></span></span></div>
                        
                        
						
                        
                        
                        <br></span></span></span></div>
						<br>
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
			<div style="margin-left:10px">&nbsp; RabbitCRM </div>
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
						$data['messages']['success']['email'] = 'Successfully sent an email with your password';

					} else {
						$data['resetFailed'] = true;
						$data['messages']['error']['fail'] = 'Error sending email';
					}
				} else {
					$data['resetFailed'] = true;
					$data['messages']['error']['fail'] = 'Error reset password';
				}
			} else {
				$data['resetFailed'] = true;
				$data['messages']['error']['fail'] = 'Please enter your registered email id only';
			}
		}

		$data['submit'] = true;
		$data['email'] = $email;
		$this->index($data);
	}

}