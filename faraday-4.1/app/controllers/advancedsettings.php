<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class advancedsettings extends BCZ_Controller {

	public function __construct()
	{
		parent::__construct();

		// Accessible to only ADMINs
		if (!$this->isAdmin) show_404();
	}

	public function index()
	{
		$this->bodyClass = "advancedsettings";
		$this->pageTitle = $this->pageDesc = 'Advanced Settings';
		
		$data['content'] = 'AdvancedSettingsView';

		// Organization details
		
		
		if($this->user->org_id!="")
		{
			$organization_Details = $this->getOrganizationcurrency_freeze();
		}
		
		
	
		$data['terms_conditions']= $this->getOrganizationTermsConditions();
		$data['currency_freeze'] = $organization_Details;
		$data['PrefixSequence'] = $this->getPrefixSequence();
		
		// Handle data for users section
		

		
		$data['mobileCols'] = array(1, 2, 3);

		$data['tables'] = array('lead' => 'Leads',
														'deal' => 'Deals',
														'task' => 'Tasks',
														'contact' => 'Contacts',
														'company' => 'Companies',
														'product' => 'Products',
														'quote' => 'Quotes',
														'sales_order' => 'Sale Orders',
														'cases' => 'Tickets',
														
														);
														
		
		//$data['currency_freeze'] =$this->currency_freeze(); 
		
		$data['fields'] = $this->getAllUserFields();
		$this->load->view('FirstLayoutView', $data);
	}

	// Get users json for datatable
	public function getusersjson() {
		$users = $this->getUsers();
		$output = $this->constructDTOutput1($users, array_merge(array_keys($this->userTableCols), array('actions')), 'user_id','super_admin');

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
	

	// Add a new setting
	public function addSetting() {
		// Gather form fields
		$data = $formFields = array();
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal);
		}

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Dupicate checkup
		$settingType = $formFields['setting_type'];
		$settingVal = $formFields['setting_val'];
		$selQuery = "select * from user_fields where (`org_id`='".$this->user->org_id."' or `org_id`='0') and $settingType is not null and $settingType != '' order by sort_order";
		$selRes = $this->db->query($selQuery, $formFields['setting_val']);
		$settings = array();
		$settingExist = false;
		if ($selRes->num_rows()) {
			foreach ($selRes->result() as $row) {
				$settings[] = $row;
				if ($row->$settingType == $settingVal) $settingExist = true;
			}
		}

		if ($settingExist) {
			$this->output
	    		->set_content_type('application/json')
	    		->set_output(json_encode(array('success' => false, 'message' => 'This setting already exists, please check and try with another.')));

		} else {
			// Create a setting with given details
			$settingId = $this->getTableAutoID('user_fields');
			$isStage = ($formFields['setting_type'] == 'stage');
			$query = 'insert into user_fields (' . $formFields['setting_type'] . ($isStage ? ', probability' : '') . ', sort_order) values (?' . ($isStage ? ', ?' : '') . ', ?)';
			$params = array($formFields['setting_val']);
			if ($isStage) $params[] = $formFields['probability'];
			$params[] = $settings[count($settings)-1]->sort_order + 1;
			$settingRes = $this->db->query($query, $params);
			$insert_id=$this->db->insert_id();
			$data = array('org_id' => $this->user->org_id);
			$this->db->where('no', $insert_id);
			$this->db->update('user_fields', $data); 

			if ($settingRes) {
				$this->load->view('SingleSettingView', array('id' => $settingId, 'text' => $formFields['setting_val']));
			}
		}
	}

	// Update a setting
	public function updateSetting() {
		// Gather form fields
		$data = $formFields = array();
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal);
		}
		
		if($_POST['setting_type']=='stage')
		{
			$stage='stage';
		}

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Update setting with new details
		$isStage = ($formFields['setting_type'] == 'stage');
		
		$query1 = 'select * from user_fields Where org_id="'.$this->user->org_id.'" AND no='.$_POST['setting_id'];

		// Role checkup
		$settingSelect = $this->db->query($query1);
		
		if ($settingSelect->num_rows()) {
			
		if ($isStage)$formFields['probability'] = str_replace('%', '', $formFields['probability']);
			
			$query = 'update user_fields set ' . $formFields['setting_type'] . ' = ?' . ($isStage ? ', probability = ?' : '') . ' where no = ?';
			$params = array($formFields['setting_val']);
			if ($isStage) $params[] = $formFields['probability'];
			$params[] = $formFields['setting_id'];
			$settingRes = $this->db->query($query, $params);
		
		}
		else
		{
			$query1 = 'select sort_order from user_fields Where no='.$_POST['setting_id'];

		// Role checkup
		$settingSelect = $this->db->query($query1);
		if ($settingSelect->num_rows()) {
			foreach ($settingSelect->result() as $row) {
				$sort_order = $row->sort_order;
			}
		}
			
		
		$settingType = $formFields['setting_type'];
		$settingVal = $formFields['setting_val'];
		$selQuery = "select * from user_fields where  `no`!='".$_POST['setting_id']."' and $settingType is not null and $settingType != ''  order by sort_order";
		$selRes = $this->db->query($selQuery, $formFields['setting_val']);
		$settings = array();
		$settingExist = false;
		if ($selRes->num_rows()) {
			foreach ($selRes->result() as $row) {
				$settings[] = $row;
				if ($row->$settingType == $settingVal) $settingExist = true;
			}
		}

		if ($settingExist) {
			$this->output
	    		->set_content_type('application/json')
	    		->set_output(json_encode(array('success' => false, 'message' => 'This setting already exists, please check and try with another.')));

		} else {
			
			// Create a setting with given details
			$settingId = $this->getTableAutoID('user_fields');
			if($stage== 'stage')
			{
			$probability = str_replace('%', '', $formFields['probability']);
			$query = 'insert into user_fields (stage, probability, sort_order,org_id) values (?,?, ?,?)';
			$params = array($formFields['setting_val']);
			$params[] = $probability;
			$params[] = $sort_order;
			$params[] = $this->user->org_id;
			$settingRes = $this->db->query($query, $params);
			 $insert_id=$this->db->insert_id();
			if($_POST['setting_type']=="lead_status")
			{
				
				$query = 'update lead set lead_status = ? where lead_status = ? AND org_id= ?';
		$res = $this->db->query($query, array( $insert_id,$_POST['setting_id'],$this->user->org_id));
		
	
			}
			}
			else
			{
			 
			$query = 'insert into user_fields (' . $formFields['setting_type'] .', sort_order,org_id) values (?, ?, ?)';
			$params = array($formFields['setting_val']);
			$params[] = $sort_order;
			$params[] = $this->user->org_id;
			$settingRes = $this->db->query($query, $params);
			
			 $insert_id=$this->db->insert_id();
			
			
			}
			
			$values=array($formFields['setting_id'],$this->user->org_id,$this->user->user_id,$this->getCurrTime());
			$query = 'insert into user_delete_settings (`user_settings_id`,`org_id`,`user_id`,`create_date`)values(?,?,?,?) ';
			$settingRes = $this->db->query($query,$values);
			
			if($_POST['setting_type']=="lead_status")
			{
				
				$query1 = 'update lead set lead_status = ? where lead_status = ? AND org_id= ?';
		$settingRes = $this->db->query($query1, array( $insert_id,$_POST['setting_id'],$this->user->org_id));
		
		
			}elseif($_POST['setting_type']=="stage")
			{
				
				$query1 = 'update deal set stage = ? where stage = ? AND org_id= ?';
		$settingRes = $this->db->query($query1, array( $insert_id,$_POST['setting_id'],$this->user->org_id));
		
			}elseif($_POST['setting_type']=="task_status")
			{
				
				$query1 = 'update task set status = ? where status = ? AND org_id= ?';
		$settingRes = $this->db->query($query1, array( $insert_id,$_POST['setting_id'],$this->user->org_id));
		
		}elseif($_POST['setting_type']=="customer_type")
			{
				
				$query1 = 'update company set com_cust_type = ? where com_cust_type = ? AND org_id= ?';
		$settingRes = $this->db->query($query1, array( $insert_id,$_POST['setting_id'],$this->user->org_id));
		
		}elseif($_POST['setting_type']=="quote_stage")
			{
				
				$query1 = 'update quote set quote_stage = ? where quote_stage = ? AND org_id= ?';
		$settingRes = $this->db->query($query1, array( $insert_id,$_POST['setting_id'],$this->user->org_id));
		
		}elseif($_POST['setting_type']=="so_stage")
			{
				
				$query1 = 'update sales_order set so_stage = ? where so_stage = ? AND org_id= ?';
		$settingRes = $this->db->query($query1, array( $insert_id,$_POST['setting_id'],$this->user->org_id));
		
			}
			
			}
		}
		
		
		

		$res = array();
		$res['action'] = 'edit';
		if ($settingRes) {
			$res['success'] = true;
			$res['message'] = 'Successfully updated this setting.';
			$res['setting_type'] = $formFields['setting_type'];
			$res['setting'] = $formFields['setting_val'];
			if($insert_id)
			{
				$res['insert_id'] = $insert_id;
			}
			if ($isStage) $res['probability'] = $formFields['probability'];
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while updating this setting, please try again after sometime.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Delete a setting
	public function deleteSetting() {
		// Gather form fields
		$data = $formFields = array();
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal);
		}

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Delete a setting
		//echo	$query = 'delete from user_fields where no = ?';
		
		$settingType = $formFields['setting_type'];
		$settingVal = $formFields['setting_val'];
		$selQuery = "select * from user_fields where (`org_id`='".$this->user->org_id."' ) and $settingType is not null and $settingType != '' order by sort_order";
		$selRes = $this->db->query($selQuery, $formFields['setting_val']);
		$settings = array();
		$settingExist = false;
		if ($selRes->num_rows()) {
			
		$query = 'delete from user_fields where no = ?';
		$settingRes = $this->db->query($query, $formFields['setting_id']);
		$res['action'] = 'delete';
			
		}
		else
		{
		
		
		$values=array($formFields['setting_id'],$this->user->org_id,$this->user->user_id,$this->getCurrTime());
		$query = 'insert into user_delete_settings (`user_settings_id`,`org_id`,`user_id`,`create_date`)values(?,?,?,?) ';
		$settingRes = $this->db->query($query,$values);
		$res['action'] = 'delete';
		}
		//$settingRes = $this->db->query($query,$formFields['setting_id']);

		// Re-order settings
	/*	if ($settingRes) {
			$settingType = $formFields['setting_type'];
			$query = "update 
								user_fields
								join ( 
									SELECT no, @curRow := @curRow +1 AS row_no
									FROM user_fields
									JOIN (SELECT @curRow :=0) r
									WHERE $settingType is not null and $settingType != ''
									ORDER BY no
								) AS tempTable ON user_fields.no = tempTable.no
								SET sort_order = tempTable.row_no";
			$settingRes = $this->db->query($query);
		}
		*/

		$res = array();
		$res['action'] = 'delete';
		if ($settingRes) {
			$res['success'] = true;
			$res['message'] = 'Successfully deleted this setting.';
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while deleting this setting, please try again after sometime.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Move a setting up/down
	public function moveSetting() {
		// Update a setting order
		$column = $_GET['column'];
		$query = "update user_fields set sort_order = if(no = ?, sort_order-1, sort_order), sort_order = if(no = ?, sort_order+1, sort_order) where $column is not null and $column != ''";
		$params = ($_GET['move'] == 'up') ? array($_GET['id'], $_GET['otherId']) : array($_GET['otherId'], $_GET['id']);
		$settingRes = $this->db->query($query, $params);

		$res = array();
		$res['action'] = 'move'.$_GET['move'];
		if ($settingRes) {
			$res['success'] = true;
			$res['message'] = 'Successfully updated the settings order.';
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while updating the setting order, please try again after sometime.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
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

	public function numbering ()
{

   
		//if (!$_POST['org_id']) show_404();

		// Gather form fields
		if($_POST['numbering_id']=="0")
		{
			$numbering_id=$_POST['numbering_id'];
			unset($_POST['numbering_id']);
		}
		else
		{
			$numbering_id=$_POST['numbering_id'];
		}
		$_POST['org_id']=$this->user->org_id;
		$data = $formFields = $orgSettings = array();
		$valsStr = $settingValsStr = '';
		foreach ($_POST as $fieldName => $fieldVal) {
				$formFields[$fieldName] = trim($fieldVal);
				$valsStr .= ($valsStr ? ', ' : '') . '?';
		}

		 	$table="numbering";
			
			
			if($numbering_id=="0")
			{
				$valsStr = substr($valsStr, 3);
				$valsStr .= ($valsStr ? ', ' : '') . '?';
				$query = 'insert into ' . $table  . ' (' . implode(', ', array_keys($formFields)) . ',numbering_create_date) values (' . $valsStr . ',"'.$this->getCurrTime().'")';
				$res = $this->db->query($query, array_values($formFields));
			}
			else
			{
				$numbering = array();
				$numbering[]=$_POST['prefix'];
				$numbering[]=$_POST['sequence'];
				$numbering[]=$this->getCurrTime();
				$numbering[]=$_POST['numbering_id'];
				
				
				$query = 'UPDATE ' . $table  . ' SET `prefix`= ? ,`sequence`= ? ,`numbering_modify_date`= ? WHERE numbering_id=?';
				$res = $this->db->query($query, $numbering);
			}

			if ($res) {
				$data['messages']['success']['create'] = 'Your Settings have been changed.';
			} else {
				$data['messages']['error']['create'] = 'Something went wrong while inserting Settings info, please try again after sometime.';
			}
		

		$data['fields'] = $this->getPrefixSequence();
		$this->load->view('MessagesView', $data);
	
} 

public function termsandconditions ()
{
	$table='organizations';
	$query = 'UPDATE ' . $table  . ' SET `terms_conditions`= ? WHERE id=?';
	
	$Query= array();
	$Query[] = $_POST['terms_conditions'];
	$Query[] = $this->user->org_id;
	$res = $this->db->query($query, $Query);
	
	
	if ($res) {
				$data['messages']['success']['create'] = 'Your Terms and Conditions have been changed.';
			} else {
				$data['messages']['error']['create'] = 'Something went wrong while inserting Settings info, please try again after sometime.';
			}
	$this->load->view('MessagesView', $data);



}


}