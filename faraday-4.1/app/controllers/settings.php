<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class settings extends BCZ_Controller {

	public function __construct()
	{
		parent::__construct();

		// Accessible to only ADMINs
		if (!$this->isAdmin) show_404();
	}

	public function index()
	{
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'settings';
		$data['content'] = 'SettingsView';

		// Organization details
		$organization = $this->user->org_id ? $this->user->organization : $this->getOrganizationDetails();
		
		if($this->user->org_id!="")
		{
			$organization_Details = $this->getOrganizationcurrency_freeze();
		}
		
		if ($organization->logo) {
			$orgLogo = $this->imagesPath . $organization->logo;
			$organization->logo_path = ($organization->logo && file_exists($orgLogo)) ? (base_url() . $orgLogo) : '';
		}
		$data['organization'] = $organization;
		
		$data['currency_freeze'] = $organization_Details;
		$data['currency_freeze'];
		$data['fields'] = $this->getAllUserFields();
		// Handle data for users section
		$users = $this->getUsers();
		$data['users'] = $users;
		$data['cols'] = array_values($this->userTableCols);
		$data['mobileCols'] = array(1, 2, 3);

		
		$this->load->view('FirstLayoutView', $data);
	}

	// Get users json for datatable
	public function getusersjson() {
		$users = $this->getUsers();
		$output = $this->constructDTOutput1($users, array_merge(array_keys($this->userTableCols), array('actions')), 'user_id','super_admin','name');
		

		echo json_encode($output);
	}

	// Export data
	public function export() {
		$table = $_REQUEST['export_table'];
		$items = array();
		$fileName = 'export_data.xls';
		switch ($table) {
			case 'lead':
				$items = $this->getAllLeads();
				$fileName = 'leads_data.xls';
				break;
			case 'deal':
				$items = $this->getAllDeals();
				$fileName = 'deals_data.xls';
				break;
			case 'task':
				$items = $this->getAllTasks();
				$fileName = 'tasks_data.xls';
				break;
			case 'contact':
				$items = $this->getAllContacts();
				$fileName = 'contacts_data.xls';
				break;
			case 'company':
				$items = $this->getAllCompanies();
				$fileName = 'companies_data.xls';
				break;
			case 'product':
				$items = $this->getAllProducts();
				$fileName = 'products_data.xls';
				break;
			case 'quote':
				$items = $this->getAllQuotes();
				$fileName = 'quotes_data.xls';
				break;
			case 'sales_order':
				$items = $this->getAllOrders();
				$fileName = 'sales_order_data.xls';
				break;
			case 'cases':
				$items = $this->getAllCases();
				$fileName = 'tickets_data.xls';
				break;
			
			default:
				# code...
				break;
		}

		$this->exportData($items, $fileName);
	}

	// Import data
	public function import() {
		$table = $_POST['import_table'];
		$data = array();
		$validationFailed = false;
		if ($_FILES['import_file']['type'] != 'application/vnd.ms-excel') {
			$data['success'] = false;
			$data['message'] = "Please upload a valid file";
		}

		if (!$validationFailed) {
			$res = $this->importData($_FILES['import_file']['tmp_name'], $table);
			$data['success'] = true;
			$data['message'] = "Import operation is successfull.";
		}
		
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}

	// Download format file
	public function formatfiles() {
		$table = $_REQUEST['format_table'];
		$file = $this->importPath . $table . '_structure.xls';

	  header('Content-Description: File Transfer');
	  header('Content-Disposition: attachment; filename='.basename($file));
	  header('Content-Transfer-Encoding: binary');
	  header('Expires: 0');
	  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	  header('Pragma: public');
	  header('Content-Length: ' . filesize($file));
	  ob_clean();
	  flush();
	  readfile($file);
	  exit;
	}

	// Save organization details
	public function saveOrg() {
		if (!$_POST['org_id']) show_404();

		// Gather form fields
		
		$data = $formFields = $orgSettings = array();
		$valsStr = $settingValsStr = '';

		foreach ($_POST as $fieldName => $fieldVal) {
			if (substr($fieldName, 0, 4) == 'set_') {
				$orgSettings[str_replace('set_', '', $fieldName)] = $fieldVal;
				$settingValsStr .= ($settingValsStr ? ', ' : '') . '?';
			} else {
				$formFields[$fieldName] = trim($fieldVal);
				$valsStr .= ($valsStr ? ', ' : '') . '?';
			}
		}

		// Upload logo if selected
		if ($_FILES['org_logo']['name']) {
			$config['upload_path'] = $this->imagesPath;
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['max_size']	= '10240';
			$this->load->library('upload', $config);

			$uploadRes = $this->upload->do_upload('org_logo');
			if ($uploadRes) {
				$uploadedFile = $this->upload->data();
				$formFields['logo'] = $uploadedFile['file_name'];
				$valsStr .= ($valsStr ? ', ' : '') . '?';
			} else {
				$data['messages']['error']['upload'] = $this->upload->display_errors();
			}
		}

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Update the organization info with given details if already exist
		if (isset($formFields['org_logo'])) unset($formFields['org_logo']);
		$orgId = $formFields['org_id']; unset($formFields['org_id']);
		 $table = $this->user->org_id ? 'organizations' : 'organization';
		$keyCol = $this->user->org_id ? 'id' : 'sno';
		if ($orgId) {
			$formFields['finance_date'] = $this->getCurrTime();
			$query = 'update ' . $table  . ' set ' . implode(' = ?, ', array_keys($formFields)) . ' = ? where ' . $keyCol . ' = ?';
			$formFields['lead_id'] = $orgId;
			$res = $this->db->query($query, array_values($formFields));
			if($_POST['hidden_crrency']=="")
			{
				if($this->user->org_id!="")
				{
					if($_POST['set_currency']!="")
		{
			
					$query_currency = 'update organization_settings set currency_freeze="1" where org_id="'.$this->user->org_id.'"' ;
					
					$res = $this->db->query($query_currency);
		}
				}
			}

			if ($res) {
				$data['messages']['success']['update'] = 'Organization info is updated successfully.';
			} else {
				$data['messages']['error']['update'] = 'Something went wrong while updating organization info, please try again after sometime.';
			}

		// Create organization if not exist
		} else {
			$valsStr = substr($valsStr, 3);
			$formFields['finance_date'] = $this->getCurrTime();
			$valsStr .= ($valsStr ? ', ' : '') . '?';
			$query = 'insert into ' . $table  . ' (' . implode(', ', array_keys($formFields)) . ') values (' . $valsStr . ')';
			$res = $this->db->query($query, array_values($formFields));

			if ($res) {
				$data['messages']['success']['create'] = 'Organization info is inserted successfully.';
			} else {
				$data['messages']['error']['create'] = 'Something went wrong while inserting organization info, please try again after sometime.';
			}
		}

		// Update organization settings
		if ($settingValsStr) {
			$organizationSettings = $this->getOrganizationSettings($this->user->org_id);

			if (isset($organizationSettings->id)) {
				$query = 'update organization_settings set ' . implode(' = ?, ', array_keys($orgSettings)) . ' = ? where org_id = ?';
				$orgSettings['org_id'] = $this->user->org_id;
				$settingsRes = $this->db->query($query, array_values($orgSettings));
			} else {
				$orgSettings['org_id'] = $this->user->org_id;
				$settingValsStr .= ($settingValsStr ? ', ' : '') . '?';
				$query = 'insert into organization_settings (' . implode(', ', array_keys($orgSettings)) . ') values (' . $settingValsStr . ')';
				$settingsRes = $this->db->query($query, array_values($orgSettings));
			}
		}

		if ($this->user->org_id) {
			$organization = $this->getOrganizationDetails($this->user->org_id);
			$orgLogo = $this->imagesPath . $organization->logo;
			if ($organization->logo && file_exists($orgLogo)) $_SESSION['bcz_user']->org_logo = base_url() . $orgLogo;
			$data['organization'] = $organization;
			$_SESSION['bcz_user']->organization = $organization;
		} else {
			$data['organization'] = $this->getOrganizationDetails();
			$orgLogo = $this->imagesPath . $data['organization']->logo;
			$data['organization']->logo_path = ($data['organization']->logo && file_exists($orgLogo)) ? (base_url() . $orgLogo) : '';
			if (isset($_SESSION['bcz_org_logo'])) $_SESSION['bcz_org_logo'] = $data['organization']->logo_path ? $data['organization']->logo_path : '';
		}

		$data['fields'] = $this->getAllUserFields();
		$this->load->view('OrganizationInfoView', $data);
	}

	// Add a new setting
	

	// Update a setting


	// Delete a setting
	

	// Move a setting up/down


	// Add a new user
	public function addUser() {
		// Gather form fields
		$data = $formFields = array();
		$valsStr = '';
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal);
			$valsStr .= ($valsStr ? ', ' : '') . '?';
		}
		$formFields['user_status'] = 'active';
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['user_login'] = 'yes';
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$randomPass = $this->randPass();
		$formFields['password'] = sha1(md5($randomPass));
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['org_id'] = $this->user->org_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['is_active'] = 'Y';
		$valsStr .= ($valsStr ? ', ' : '') . '?';


		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		//Email duplication check
		$user = $this->getUserDetailsByEmail($formFields['user_email']);
		if ($user->user_id) {
			$validationFailed = true;
		}

		if ($validationFailed) {
			$data['messages']['error']['fail'] = 'Email is already in use, please try with another one.';
		} else {
			// Create an user with given details
			$query = 'insert into user (' . implode(', ', array_keys($formFields)) . ') values (' . $valsStr . ')';
			$res = $this->db->query($query, array_values($formFields));
			if ($res) {
				// Send an email to user with password
				$this->from = 'noreplay@Rabbit.com';
				$this->to = $formFields['user_email'];
				$this->subject = "Rabbit CRM - Login Details";
				//$this->message = "Hello ".$formFields['name'].",<br><br>Below is your Skyzon CRM account password.<br><br>Password*: ".$randomPass."<br><br>*Please note that this is a case-sensitive password.<br>Want to change your password? Simply visit the Profile menu using the password provided above.<br><br>Sincerely,<br>Skyzon CRM Customer Service.";
				
				
				
				
				$this->message .='<center>
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
			<td bgcolor="#F7F7F7"><a href="'.base_url().'" style="border-width:0px;border-style:solid;float:left" target="_blank"><div style="text-align: center;"><div class="Section1">

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
						
						<div style="color:rgb(0,0,0);font-family:"Times New Roman";font-size:medium"></div>

						<div style="color:rgb(0,0,0);font-family:"Times New Roman";font-size:medium"><span style="color:#696969"><span style="font-size:16px"><span style="font-family:arial,helvetica,sans-serif"><p>Below is your RabbitCRM account password.<br>Website: '.base_url().'<br>Email: '.$formFields['user_email'].'<br>Password: '.$randomPass.'<br><br>*Please note that this is a case-sensitive password.<br>Want to change your password? Simply visit the Profile menu using the password provided above.<br><br>Sincerely,<br>RabbitCRM Customer Service.</p><br></span></span></span></div>
				<br><br>
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
					$data['messages']['success']['email'] = 'Successfully created an user and notified with temporary password.';
				} else {
					$data['messages']['error']['fail'] = 'Error sending user creation email';
					$validationFailed = true;
				}
			} else {
				$data['messages']['error']['fail'] = 'Error in user creation.';
				$validationFailed = true;
			}
		}

		if ($validationFailed) {
			$this->output
	    		->set_content_type('application/json')
	    		->set_output(json_encode(array('success' => false, 'message' => $data['messages']['error']['fail'])));
		} else {
			$this->load->view('UsersListingView', $data);
		}
	}

	// Edit user form page
	public function editUser($id) {
		$this->bodyClass = 'edit-user';
		$this->pageTitle = $this->pageDesc = 'Edit User';

		$data['user'] = $this->getUserDetails($id);

		// Get all users and fields
		$data['users'] = $this->getAllUsers();
		$data['fields'] = $this->getAllUserFields();
		$this->load->view('EditUserModal', $data);
		
	}
	
		// Edit user form page
	public function editUser1($id) {
		$this->bodyClass = 'edit-user';
		$this->pageTitle = $this->pageDesc = 'Edit User';

		$data['user'] = $this->getUserDetails($id);

		// Get all users and fields
		$data['users'] = $this->getAllUsers();
		$data['fields'] = $this->getAllUserFields();
		$this->load->view('DeEditUserModal', $data);
		
	}

	// Update an user details
	public function updateUser() {
		// Gather form fields
		
		$data = $formFields = array();
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal);
		}

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Update user with given details
		$userId = $formFields['user_id']; unset($formFields['user_id']);
		$query = 'update user set ' . implode(' = ?, ', array_keys($formFields)) . ' = ? where user_id = ?';
		$formFields['user_id'] = $userId;
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			$data['messages']['success']['update'] = 'Successfully updated the user details';
		} else {
			$data['messages']['error']['fail'] = 'Error while updating this user details.';
		}

		// Handle data for users section
		$users = $this->getAllUsers();
		foreach ($users as $usr)	$userNames[$usr->user_id] = $usr->name;		
		$rows = array();
		foreach ($users as $lk => $user) {
			
		}

		$data['users'] = $users;

		$data['cols'] = array('name', 'email', 'role', 'status', 'reporter', 'actions');
		$data['rows'] = $rows;
		$this->load->view('UsersListingView', $data);
	}
	
	
	// Update an user details
	public function updateinactive() {
		// Gather form fields
		
		$data = $formFields = array();
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal);
		}

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Update user with given details
		$userId = $formFields['user_id']; unset($formFields['user_id']);
		$query = 'update user set ' . implode(' = ?, ', array_keys($formFields)) . ' = ? where user_id = ?';
		$formFields['user_id'] = $userId;
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			$data['messages']['success']['update'] = 'Successfully updated the user details';
		} else {
			$data['messages']['error']['fail'] = 'Error while updating this user details.';
		}

		// Handle data for users section
		$users = $this->getAllInactiveUsers();
		foreach ($users as $usr)	$userNames[$usr->user_id] = $usr->name;		
		$rows = array();
		foreach ($users as $lk => $user) {
			
		}

		$data['users'] = $users;

		$data['cols'] = array('name', 'email', 'role', 'status', 'reporter', 'actions');
		$data['rows'] = $rows;
		$this->load->view('UsersListingInactiveView', $data);
	}
	

	// Delete an user
	public function deleteuser() {
		// Delete an user
		$query = 'delete from user where user_id = ?';
		$res = $this->db->query($query, $_POST['user_id']);

		if ($res) {
			$data['messages']['success']['delete'] = 'User deleted successfully.';
		} else {
			$data['messages']['error']['fail'] = 'Error in user creation.';
		}

		// Handle data for users section
		$users = $this->getAllUsers();
		foreach ($users as $usr)	$userNames[$usr->user_id] = $usr->name;		
		$rows = array();
		foreach ($users as $lk => $user) {
			
		
			
		}
		
		$users = $this->getUsers();

		$data['users'] = $users;
		$data['cols'] = array('name', 'email', 'role', 'status', 'reporter', 'actions');
		$data['rows'] = $rows;
		$this->load->view('UsersListingView', $data);
	}
	
		// Delete an user
	public function deactivateuserdelete() {
		// Delete an user
		$query = 'delete from user where user_id = ?';
		$res = $this->db->query($query, $_POST['user_id']);

		if ($res) {
			$data['messages']['success']['delete'] = 'User deleted successfully.';
		} else {
			$data['messages']['error']['fail'] = 'Error in user creation.';
		}

		// Handle data for users section
		$users = $this->getAllUsers();
		foreach ($users as $usr)	$userNames[$usr->user_id] = $usr->name;		
		$rows = array();
		foreach ($users as $lk => $user) {
			
		
			
		}
		
		$users = $this->getUsers();

		$data['users'] = $users;
		$data['cols'] = array('name', 'email', 'role', 'status', 'reporter', 'actions');
		$data['rows'] = $rows;
		$this->load->view('UsersListingInactiveView', $data);
	}
	
	
	public function deactivateuser() {
		// Delete an user

		 $query = 'UPDATE `user` SET  `user_status`="inactive" where user_id = '.$_REQUEST['user_id'];
		$res = $this->db->query($query);

		if ($res) {
		$data['messages']['success']['inactive'] = 'User inactive successfully.';
		} else {
		$data['messages']['error']['fail'] = 'Error in user creation.';
		}

		// Handle data for users section
		
		
		$users = $this->getAllInactiveUsers();
		foreach ($users as $usr)	$userNames[$usr->user_id] = $usr->name;		
		$rows = array();
		foreach ($users as $lk => $user) {
			
		
			
		}

		
		$data['users'] = $users;
		$data['cols'] = array('name', 'email', 'role', 'status', 'reporter', 'actions');
		$data['rows'] = $rows;
		$this->load->view('UsersListingView', $data);

	
	
	}
	
	
	
		public function activateuser() {
		// Delete an user

		 $query = 'UPDATE `user` SET  `user_status`="active" where user_id = '.$_REQUEST['user_id'];
		$res = $this->db->query($query);

		if ($res) {
		$data['messages']['success']['inactive'] = 'User inactive successfully.';
		} else {
		$data['messages']['error']['fail'] = 'Error in user creation.';
		}

		// Handle data for users section
		
		
		$users = $this->getAllInactiveUsers();
		foreach ($users as $usr)	$userNames[$usr->user_id] = $usr->name;		
		$rows = array();
		foreach ($users as $lk => $user) {
			
		
			
		}

		
		$data['users'] = $users;
		$data['cols'] = array('name', 'email', 'role', 'status', 'reporter', 'actions');
		$data['rows'] = $rows;
		$this->load->view('UsersListingInactiveView', $data);

	}

	// Reset an user password
	public function resetuser() {
		$userId = $_POST['user_id'];
		$user = $this->getUserDetails($userId);

		// Reset password
		$password = $this->randPass();
		$resetPassRes = $this->db->query("update user set activation_key = ? where user_id = ?", array(sha1(md5($password)), $userId));
		
		$activationKey=sha1(md5($password));
		

		$data = array();
		if ($resetPassRes) {
			$this->from = 'noreplay@rabbitcrm.com';
			$this->to = $user->user_email;
			$this->subject = "Password Reset";
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
			<td bgcolor="#F7F7F7"><a href="'.base_url().'" style="border-width:0px;border-style:solid;float:left" target="_blank"><div style="text-align: center;"><div class="Section1">

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
						


						<div style="color:rgb(0,0,0);line-height:normal">
						

						<div style="color:rgb(0,0,0);font-family:"Times New Roman";font-size:medium"></div>

						<div style="color:rgb(0,0,0);font-family:"Times New Roman";font-size:medium"><span style="color:#696969"><span style="font-size:16px"><span style="font-family:arial,helvetica,sans-serif"><p>Dear '.$user->name.',<br><br> Administrator has reset your RabbitCRM password, please click the button below to update your new password.</p><br></span></span></span></div>
					<br><div style="text-align: center;">&nbsp;<a href="'.base_url().'reset/activate?key='.$activationKey.'" target="_blank" style="font-size: 28px; color: #000; background-color: #93b656; padding: 10px 15px; border: 3px solid #ccc; border-radius: 5px; text-decoration: none;">Reset Password</a></div><br>
						 <br><br>
                         <div style="color:rgb(0,0,0);font-family:"Times New Roman";font-size:medium"><span style="font-size:16px"><span style="font-family:arial,helvetica,sans-serif"><span style="color:#696969">Thank you</span><span style="color:#808080"></span></span></span></div>
                        <div style="color:rgb(0,0,0);font-family:"Times New Roman";font-size:medium"><span style="font-size:16px"><span style="font-family:arial,helvetica,sans-serif"><span style="color:#696969">RabbitCRM Team <br />www.rabbitcrm.com</span><span style="color:#808080"></span></span></span></div>
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
			
		
		} else {
			$data['resetFailed'] = true;
			$data['messages']['error']['fail'] = 'Error reset password';
		}
		
		if ($emailRes) {
		$data['messages']['success']['inactive'] = 'Successfully sent an email with your password';
		} else {
		$data['messages']['error']['fail'] = 'Error reset password';
		}

		
			// Handle data for users section
		$users = $this->getAllUsers();
		foreach ($users as $usr)
		$userNames[$usr->user_id] = $usr->name;		
		$rows = array();
		foreach ($users as $lk => $user) {
			
		
			
		}
		
		$users = $this->getUsers();

		$data['users'] = $users;
		$data['cols'] = array('name', 'email', 'role', 'status', 'reporter', 'actions');
		$data['rows'] = $rows;
		$this->load->view('UsersListingView', $data);
		
		
	}

	// Get users
	public function getUsers() {
		$query = 'select user_id, org_id, name, username, user_email, user_designation,super_admin, report_to_id, user_status, report_to_id as report_to from user';

		// Role checkup
		$whereCond = '';
		$params = array();
		if ($this->isManager) {
			$whereCond .= ' where org_id = ? AND user_status=? ';
			$params[] = $this->user->org_id;
			$params[] = 'active';
		} elseif ($this->isExecutive) {
			$whereCond .= ' where org_id = ? AND user_status=?';
			$params[] = $this->user->org_id;
			$params[] = 'active';
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' where org_id = ? AND user_status=?';
			$params[] = $this->user->org_id;
			$params[] = 'active';
		}

		// ======== Datatable adjustments ========
		$aColumns = array_keys($this->userTableCols);
		// Paginating...
		$sLimit = "";
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
				intval( $_GET['iDisplayLength'] );
		}
		
		// Sorting...
		$sOrder = "";
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
						($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
				}
			}
			
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
		}

		$query .= " $whereCond $sOrder $sLimit";
		$resObj = $this->db->query($query, $params);

		// Report to user
		$userNames = array();
		$allUsers = $this->getAllUsers();
		foreach ($allUsers as $usr)	$userNames[$usr->user_id] = $usr->name;

		$users = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->report_to = $userNames[$row->report_to_id];
				if (!$row->name) $row->name = $row->username;
				$users[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countQuery = 'SELECT COUNT(`user_id`) as totalRows FROM user ';
		if ($whereCond) {
			$countQuery .= $whereCond;
			$countParams = $params;
		}
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $users;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
		public function getinactiveUsers() {
		$query = 'select user_id, org_id, name, username, user_email, user_designation,super_admin, report_to_id, user_status, report_to_id as report_to from user';

		// Role checkup
		$whereCond = '';
		$params = array();
		if ($this->isManager) {
			$whereCond .= ' where org_id = ? AND user_status=? ';
			$params[] = $this->user->org_id;
			$params[] = "inactive";
		} elseif ($this->isExecutive) {
			$whereCond .= ' where org_id = ? AND user_status=?';
			$params[] = $this->user->org_id;
			$params[] = "inactive";
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' where org_id = ? AND user_status=?';
			$params[] = $this->user->org_id;
			$params[] = "inactive";
		}

		// ======== Datatable adjustments ========
		$aColumns = array_keys($this->userTableCols);
		// Paginating...
		$sLimit = "";
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
				intval( $_GET['iDisplayLength'] );
		}
		
		// Sorting...
		$sOrder = "";
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
						($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
				}
			}
			
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
		}

		$query .= " $whereCond $sOrder $sLimit";
			
		$resObj = $this->db->query($query, $params);

		// Report to user
		$userNames = array();
		$allUsers = $this->getAllInactiveUsers();
		foreach ($allUsers as $usr)	$userNames[$usr->user_id] = $usr->name;

		$users = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->report_to = $userNames[$row->report_to_id];
				if (!$row->name) $row->name = $row->username;
				$users[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countQuery = 'SELECT COUNT(`user_id`) as totalRows FROM user ';
		if ($whereCond) {
			$countQuery .= $whereCond;
			$countParams = $params;
		}
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $users;
		
		
	}
	
	
	
	
	
	
	public function getOrganizationcurrency_freeze ()
{

    $currency_freeze= $this -> db-> select('*')-> where('org_id',$this->user->org_id)-> limit(1)-> get('organization_settings');
	$cur=$currency_freeze->row_array();
	 foreach($cur as $freeze)
	{
		$currency=$freeze->currency_freeze;
	}
	return $currency_freeze->row_array(); 

} 

public function getinactiveusersjson() {
		$users = $this->getinactiveUsers();
		$output = $this->constructinactiveDTOutput($users, array_merge(array_keys($this->userTableCols), array('actions')), 'user_id','super_admin','name');

		echo json_encode($output);
	}





}