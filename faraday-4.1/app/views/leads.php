<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class leads extends BCZ_Controller {

	private $filters = '';

	public function __construct()
	{
		parent::__construct();

// echo "<pre>"; print_r($this->user); exit;		
	}

	public function index()
	{
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'leads';
		$data['cols'] = array_values($this->leadTableCols);
		$data['mobileCols'] = array(0, 2, 5);
		
		
		
		if($_SESSION['filters']!="leads")
		{
			unset($_SESSION['filters_key']);
			unset($_SESSION['filters_fvalue']);
		}

		if (isset($_REQUEST['filters'])) {
			
			$data['sourcePath'] = 'leads/getleadsjson';
			$this->load->view('DataTableView', $data);
		} else {
			$data['filters'] = array('owner' => array('col' => 'user_id', 'alias' => 'owner'), 
															 'status' => array('col' => 'lead_status', 'alias' => 'lead_status'),													 														 'created' => array('col' => 'lead_create_date', 'alias' => 'lead_create_date', 'type' => 'date'),															 'Modified' => array('col' => 'lead_modify_date', 'alias' => 'lead_modify_date', 'type' => 'date'));
															 
															 
			$data['mobFilters'] = array('user_id', 'lead_create_date');
			$data['users'] = $this->getAllUsers();

			$data['leads'] = $this->getLeads();
			$data['content'] = 'LeadsView';
			$this->load->view('FirstLayoutView', $data);
		}
	}

	// Get leads json for datatable
	public function getleadsjson() {
		// Check for filters
		
		
		if (isset($_GET['filters'])) $this->filters = (array)json_decode($_GET['filters']);

		// Get leads and arrange data for datatable
		$leads = $this->getLeads();
		$output = $this->constructDTOutput($leads, array_keys($this->leadTableCols), 'lead_id', 'leads/details', 1);

		echo json_encode($output);
	}

	// Export data
	public function export() {
		//$leads = $this->getAllLeads();
		$leads = $this->getAllLeadsExport($_SESSION['leads_export'],$_SESSION['leads_export_params']);
		$this->exportData($leads, 'leads_data.xls');
	}

	// Import data
	public function import() {
	$this->bodyClass ='LeadsImportView';
	$this->pageTitle = $this->pageDesc = 'Leads Import View';
	$data['content'] = 'LeadsImportView';
	$this->load->view('FirstLayoutView', $data);
	}
	
	public function importcsv() {
		
		
		$this->bodyClass ='LeadsImportMapping';
		 
		$this->pageTitle = $this->pageDesc = 'Leads Import Mapping';
		$lead_tmp_TableCols="`org_id` ,`title` ,`first_name` ,`last_name` ,`designation` ,`phone` ,`email`,`company_name` ,`bill_addr1` ,`bill_city` ,`bill_state` ,`bill_postal_code` ,`bill_country` ";
		$TableCols=$lead_tmp_TableCols;
		$table = 'lead_tmp1';
		$data = array();
		$validationFailed = false;
		if ($_FILES['import_file']['type'] != 'application/vnd.ms-excel') {
			$data['success'] = false;
			$data['message'] = "Please upload a valid file";
		}

		if (!$validationFailed) {
			$res = $this->importData($_FILES['import_file']['tmp_name'], $table,$TableCols);
			$data['success'] = true;
			$data['message'] = "Import operation is successfull.";
			redirect(base_url() . 'leads/mapping/' , 'location', 301);
		} 
		
		//$this->output
		//->set_content_type('application/json')
		//->set_output(json_encode($data));
	/*	
		if($data['success']==true){
			$data['content'] = 'LeadsImportView';
	$this->load->view('LeadsImportMapping', $data);
		}
		*/
		
	else {
			$data['messages']['error']['fail'] = 'Something went wrong while creating this lead, please try again after sometime.';
		}

		
		 
		//$this->importData('leads_data.xls', 'lead');
	}
	
	
	public function mapping() {
		
		$this->bodyClass = "LeadsImportMappingView";
		$this->pageTitle = $this->pageDesc = 'Leads Import Mapping View';
		$data['content'] = 'LeadsImportMappingView';
		$query = "select *  from lead_tmp1 where org_id = ? ORDER BY lead_id ASC limit 1";
		$params[] = $this->user->org_id;
		$resObj = $this->db->query($query, $params);
		$mapping = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$mapping['title'] = $row->title;
				$mapping['first_name']= $row->first_name;
				$mapping['last_name'] = $row->last_name;
				$mapping['designation'] = $row->designation;
				$mapping['mobile'] = $row->mobile;
				$mapping['email'] = $row->email;
				$mapping['company_name'] = $row->company_name;
				$mapping['bill_addr1'] = $row->bill_addr1;
				$mapping['bill_city'] = $row->bill_city;
				$mapping['bill_state'] = $row->bill_state;
				$mapping['bill_postal_code'] = $row->bill_postal_code;
				$mapping['bill_country'] = $row->bill_country;
			}
		}
		$data['lead_table_data']=array (array ('name' => 'title','col' => 'title'),array ('name' => 'First Name','col' => 'first_name'),array ('name' => 'Last Name','col' => 'last_name'),array ('name' => 'Designation','col' => 'designation'),array ('name' => 'Mobile','col' => 'mobile'),array ('name' => 'Email','col' => 'email'),array ('name' => 'Company Name','col' => 'company_name'),array ('name' => 'bill_addr1','col' => 'bill_addr1'),array ('name' => 'bill_city','col' => 'bill_city'),array ('name' => 'bill_state','col' => 'bill_state'),array ('name' => 'bill_postal_code','col' => 'bill_postal_code'),array ('name' => 'bill_country','col' => 'bill_country'));
		$data['mapping'] =$mapping;
	
		$this->load->view('FirstLayoutView', $data);
	}
	
	
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
	public function details($id) {
		$this->bodyClass = 'lead-details';
		$this->pageTitle = $this->pageDesc = 'Lead Details';
		$data['content'] = 'LeadDetailsView';
		$data['users'] = $this->getAllUsers();
		$data['fields'] = $this->getAllUserFields();








// Get task status
		$data['fields'] = $this->getAllUserFields();
		$statusList = array();
		$count = 0;
	
		foreach ($data['fields'] as $field) { 
		
			if ($field->lead_status) {
				 $statusList[$count] = $field->lead_status;
				if ($lead->status == $field->lead_status) $leadStatusIndex = $count;
				$count++;
				
			}
		}
		
		
		

		if ($leadStatusIndex < 3) {
			$lead1->statusList = array_slice($statusList, 0, 5);
		} else if ($leadStatusIndex > (count($statusList) - 4)) {
			$lead1->statusList = array_slice($statusList, (count($statusList) - 5));
		} else {
			$lead1->statusList = array_slice($statusList, ($leadStatusIndex-2), ($leadStatusIndex+2));
		}
		
		$data['lead1'] = $lead1;
		





		// Arrange users for view manipulation
		$data['aUsers'] = array();
		foreach ($data['users'] as $user) {
			$data['aUsers'][$user->user_id] = $user;
		}

		// Get lead details and arrange that data
		$lead = $this->getLeadDetails($id);
		
		$lead->lead_application=$this->campaignname($lead->lead_application);
		

		$this->orgAccessCheck($lead->org_id);	// Organization access check

		// Name
		$lead->name = $lead->title;
		$lead->name .= ($lead->name ? ' ' : '') . ucfirst($lead->first_name);
		$lead->name .= ($lead->name ? ' ' : '') . ucfirst($lead->last_name);

		// Created Before
		$startDate = new DateTime(date('Y-m-d', strtotime($lead->lead_create_date)));
		$endDate = new DateTime(date('Y-m-d'));
		$diff = $startDate->diff($endDate);
		$lead->created_before = $this->formatDays($diff->days);

		// Lead owner
		if ($lead->lead_owner_id) {
			$owner = $this->getUserDetails($lead->lead_owner_id);
			$lead->owner = ucfirst($owner->name);
		}

		// Lead reporter
		if ($lead->report_to_id) {
			$reporter = $this->getUserDetails($lead->report_to_id);
			$lead->reporter = ucfirst($reporter->name);
		}

		// Reassigned from
		if ($lead->lead_assigned_from) {
			$reassignee = $this->getUserDetails($lead->lead_assigned_from);
			$lead->reassignee = ucfirst($reassignee->name);
		}
	 	$data['status']= $lead->lead_status;
		// Lead notes
		$data['notes'] = $this->getItemNotes('lead', $id);

		// Get lead tasks
		$data['tasks'] = $this->getItemTasks('lead', $id);

		// Lead documents
		$data['docs'] = $this->getItemDocs('lead', $id);

		// Lead history
		$data['history'] = $this->getItemHistory('lead', $id);

		$data['lead'] = $lead;
		
		
	
// echo "<pre>"; print_r($data['history']); exit;		
		$data['prev_lead'] = $this->getPrevLead($id);
		$data['next_lead'] = $this->getNextLead($id);
		$this->load->view('FirstLayoutView', $data);
	}

	public function add($data=array()) {
		$this->bodyClass = 'create-lead';
		$this->pageTitle = $this->pageDesc = 'Create Lead';
		$data['content'] = 'CreateLeadView';

		$data['users'] = $this->getAllUsers();					// Get all users
		$data['companies'] = $this->getAllCompanies();	// Get all companies
		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$data['campaigns'] = $this->getAllcampaigns();	// Get all campaigns fields
	
		$this->load->view('FirstLayoutView', $data);
	}

	public function submit() {
		$company_exists = $_POST['company_exists']; unset($_POST['company_exists']);
		
		$updatecampaign = $_POST['lead_application']; 
		$type='lead_count';
		$cam=$this->campaigncount($updatecampaign,$type);

		// Gather form fields
		$data = $formFields = $params = array();
		$currDateTime = $this->getCurrTime();
		$valsStr = '';
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal);
			$valsStr .= ($valsStr ? ', ' : '') . '?';
		}
		$formFields['report_to_id'] = $this->getUserReporter($formFields['lead_owner_id']);	// Get reporter id
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['org_id'] = $this->user->org_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['lead_create_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['lead_modify_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';

		// Create a new company flow
		if ($company_exists != $formFields['company_name']) {
			$compFields['company_name'] = $formFields['company_name'];
			$compValsStr .= ($compValsStr ? ', ' : '') . '?';
			$compFields['com_cust_type'] = $formFields['customer_type'];
			$compValsStr .= ($compValsStr ? ', ' : '') . '?';
			$compFields['com_application'] = $formFields['lead_application'];
			$compValsStr .= ($compValsStr ? ', ' : '') . '?';
			$compFields['associate_to'] = 'lead';
			$compValsStr .= ($compValsStr ? ', ' : '') . '?';
			$compFields['owner_id'] = $this->user->user_id;
			$compValsStr .= ($compValsStr ? ', ' : '') . '?';
			$compFields['report_to_id'] = $this->user->report_to_id ? $this->user->report_to_id : $this->user->user_id;
			$compValsStr .= ($compValsStr ? ', ' : '') . '?';
			$compFields['org_id'] = $this->user->org_id;
			$compValsStr .= ($compValsStr ? ', ' : '') . '?';
			$compFields['company_create_date'] = $currDateTime;
			$compValsStr .= ($valsStr ? ', ' : '') . '?';
			$compFields['company_modify_date'] = $currDateTime;
			$compValsStr .= ($valsStr ? ', ' : '') . '?';

			// Create a company with given details
			$cquery = 'insert into company (' . implode(', ', array_keys($compFields)) . ') values (' . $compValsStr . ')';
			$cres = $this->db->query($cquery, array_values($compFields));
		}

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Create a lead with given details
		$leadId = $this->getTableAutoID('lead');
		$query = 'insert into lead (' . implode(', ', array_keys($formFields)) . ') values (' . $valsStr . ')';
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			// Log activity
			$activity = array('action' => 'CREATE', 'type' => 'lead', 'id' => $leadId);
			$this->logUserActivity($activity);

			redirect(base_url() . 'leads/details/' . $leadId, 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while creating this lead, please try again after sometime.';
		}

		$this->add($data);
	}

	public function edit($id, $data = array()) {
		$this->bodyClass = 'edit-lead';
		$this->pageTitle = $this->pageDesc = 'Edit Lead';
		$data['content'] = 'EditLeadView';

		$data['lead'] = $this->getLeadDetails($id);

		$data['users'] = $this->getAllUsers();					// Get all users
		$data['companies'] = $this->getAllCompanies();	// Get all companies

		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$data['campaigns']=$this->getAllcampaigns();
		
		
		$this->load->view('FirstLayoutView', $data);
	}

	public function update($id) {
		
				
	$campaign=$_POST['campaign'];
	$updatecampaign = $_POST['lead_application']; 
	
	unset($_POST['campaign']);
	
	if($updatecampaign!=$campaign)
	{
		$type='lead_count';
		$cam=$this->campaigncountsub($campaign,$updatecampaign,$type);
	}
	
	
		$company_exists = $_POST['company_exists']; unset($_POST['company_exists']);
		$currDateTime = $this->getCurrTime();

		// Gather form fields
		$data = $formFields = array();
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal);
		}
		$formFields['report_to_id'] = $this->getUserReporter($formFields['lead_owner_id']);	// Get reporter id
		$formFields['lead_modify_date'] = $currDateTime;
		
		// Create a new company flow
		if ($company_exists != $formFields['company_name']) {
			$compFields['company_name'] = $formFields['company_name'];
			$compValsStr .= ($compValsStr ? ', ' : '') . '?';
			$compFields['com_cust_type'] = $formFields['customer_type'];
			$compValsStr .= ($compValsStr ? ', ' : '') . '?';
			$compFields['com_application'] = $formFields['lead_application'];
			$compValsStr .= ($compValsStr ? ', ' : '') . '?';
			$compFields['associate_to'] = 'lead';
			$compValsStr .= ($compValsStr ? ', ' : '') . '?';
			$compFields['owner_id'] = $this->user->user_id;
			$compValsStr .= ($compValsStr ? ', ' : '') . '?';
			$compFields['report_to_id'] = $this->user->report_to_id ? $this->user->report_to_id : $this->user->user_id;
			$compValsStr .= ($compValsStr ? ', ' : '') . '?';
			$compFields['org_id'] = $this->user->org_id;
			$compValsStr .= ($compValsStr ? ', ' : '') . '?';
			$compFields['company_create_date'] = $currDateTime;
			$compValsStr .= ($compValsStr ? ', ' : '') . '?';
			$compFields['company_modify_date'] = $currDateTime;
			$compValsStr .= ($compValsStr ? ', ' : '') . '?';

			// Create a company with given details
			$cquery = 'insert into company (' . implode(', ', array_keys($compFields)) . ') values (' . $compValsStr . ')';
			$cres = $this->db->query($cquery, array_values($compFields));
		}

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Update lead with given details
		$query = 'update lead set ' . implode(' = ?, ', array_keys($formFields)) . ' = ? where lead_id = ?';
		$formFields['lead_id'] = $id;
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			// Log activity
			$activity = array('action' => 'UPDATE', 'type' => 'lead', 'id' => $id);
			$this->logUserActivity($activity);

			redirect(base_url() . "leads/details/$id", 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while updating this lead, please try again after sometime.';
		}

		$this->edit($data);
	}

	// Reassign lead
	public function reassign() {
		$leadId = $_POST['lead_id'];
		$reassignId = $_POST['lead_owner_id'];
		$lead = $this->getLeadDetails($leadId);
		$reassignee = $this->getUserDetails($reassignId);

		// Reassign lead
		$leadQuery = 'update lead set lead_assigned_from = ?, lead_modify_date = ?, lead_owner_id = ?, report_to_id = ? , inbox = 0  where lead_id = ?';
		$leadRes = $this->db->query($leadQuery, array($lead->lead_owner_id, $this->getCurrTime(), $reassignId, ($reassignee->report_to_id ? $reassignee->report_to_id : $reassignee->user_id), $leadId));

		// Reassign notes
		$noteQuery = 'update note set owner_id = ?, report_to_id = ? where associate_to = ? and associate_id = ? and owner_id = ? and report_to_id = ?';
		$noteRes = $this->db->query($noteQuery, array($reassignId, ($reassignee->report_to_id ? $reassignee->report_to_id : $reassignee->user_id), 'lead', $leadId, $lead->lead_owner_id, $lead->report_to_id));

		// Reassign tasks
		$taskQuery = 'update task set task_owner_id = ?, task_report_to_id = ? where associate_to = ? and associate_id = ? and task_owner_id = ? and task_report_to_id = ?';
		$taskRes = $this->db->query($taskQuery, array($reassignId, ($reassignee->report_to_id ? $reassignee->report_to_id : $reassignee->user_id), 'lead', $leadId, $lead->lead_owner_id, $lead->report_to_id));

		// Reassign docs
		$docQuery = 'update fileupload set owner_id = ?, report_to_id = ? where associate_to = ? and associate_id = ? and owner_id = ? and report_to_id = ?';
		$docRes = $this->db->query($docQuery, array($reassignId, ($reassignee->report_to_id ? $reassignee->report_to_id : $reassignee->user_id), 'lead', $leadId, $lead->lead_owner_id, $lead->report_to_id));

		$res = array();
		if ($leadRes && $noteRes && $taskRes && $docRes) {
			$res['success'] = true;
			$res['message'] = 'Successfully reassigned this lead.';

			// Log activity
			$info = array('from' => $lead->lead_owner_id, 'to' => $reassignId);
			$activity = array('action' => 'REASSIGN', 'type' => 'lead', 'id' => $leadId, 'info' => json_encode($info));
			$this->logUserActivity($activity);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while reassigninig this lead, please try again after sometime.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Convert lead to deal
	public function convert() {
		$leadId = $_POST['lead_id'];
		$lead = $this->getLeadDetails($leadId);
		$dealAutoId = $this->getTableAutoID('deal');
		$companyAutoId = $this->getTableAutoID('company');
		$contactAutoId = $this->getTableAutoID('contact');
		$probability = $this->getStageProbability($_POST['stage']);
		$currDateTime = $this->getCurrTime();

		// Create deal
		$dealQuery = 'insert into deal (org_id, deal_name, deal_company_id, deal_contact_id, deal_amount, deal_application, industry, stage,
										source, probability, status, exp_close, summary, deal_create_date, deal_modify_date, deal_owner_id, report_to_id) 
					  			  values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$dealRes = $this->db->query($dealQuery, array($this->user->org_id, $_POST['deal_name'], $companyAutoId, $contactAutoId, $_POST['deal_amount'], 
													  $lead->lead_application, $lead->lead_industry, $_POST['stage'], $lead->lead_source, 
													  $probability, $lead->lead_status, date('Y-m-d', strtotime($_POST['exp_close'])), $lead->lead_description, $currDateTime, $currDateTime,
													  $this->user->user_id, ($this->user->report_to_id ? $this->user->report_to_id : $this->user->user_id)));

		// Create company
		$companyQuery = 'insert into company(org_id, company_name, com_cust_type, phone, bill_address, bill_city, bill_state, bill_postal_code,
											 fax, bill_country, website, associate_to, owner_id, report_to_id, company_create_date) 
									 values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$companyRes = $this->db->query($companyQuery, array($this->user->org_id, $lead->company_name, $lead->customer_type, $lead->phone,  $lead->bill_addr, 
															$lead->bill_city, $lead->bill_state, $lead->bill_postal_code, $lead->fax, 
															$lead->bill_country, $lead->website, 'deal', $this->user->user_id, ($this->user->report_to_id ? $this->user->report_to_id : $this->user->user_id), $currDateTime));

		// Create contact
		$contactQuery = 'insert into contact(org_id, title, first_name, last_name, designation, mobile, phone, email, alt_email, address, city, state,
											 postal_code, country, company_id, associate_to, associate_id, owner_id, report_to_id, contact_create_date) 
									 values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$contactRes = $this->db->query($contactQuery, array($this->user->org_id, $lead->title, $lead->first_name, $lead->last_name, $lead->designation, $lead->mobile, $lead->phone, 
															$lead->email, $lead->alt_email, $lead->bill_addr, $lead->bill_city, $lead->bill_state, $lead->bill_postal_code, 
															$lead->bill_country, $companyAutoId, 'deal', $dealAutoId, $this->user->user_id, ($this->user->report_to_id ? $this->user->report_to_id : $this->user->user_id), $currDateTime));

		// Update docs
		$docQuery = 'update fileupload set associate_to = ?, associate_id = ? where associate_id = ? and associate_to = ?';
		$docRes = $this->db->query($docQuery, array('deal', $dealAutoId, $leadId, 'lead'));

		// Update notes
		$noteQuery = 'update note set associate_to = ?, associate_id = ? where associate_id = ? and associate_to = ?';
		$noteRes = $this->db->query($noteQuery, array('deal', $dealAutoId, $leadId, 'lead'));

		// Update tasks
		$taskQuery = 'update task set associate_to = ?, associate_id = ? where associate_id = ? and associate_to = ?';
		$taskRes = $this->db->query($taskQuery, array('deal', $dealAutoId, $leadId, 'lead'));

		// Delete lead
		$deleteQuery = 'delete from lead where lead_id = ?';
		$deleteRes = $this->db->query($deleteQuery, array($leadId));

		$res = array();
		if ($dealRes && $companyRes && $contactRes && $docRes && $noteRes && $taskRes) {
			$res['success'] = true;
			$res['redirectUrl'] = base_url() . "deals/details/$dealAutoId";
			$res['message'] = 'Successfully converted this lead to a deal.';

			// Log activity
			$info = array('to' => 'deal', 'to_id' => $dealAutoId);
			$activity = array('action' => 'CONVERT', 'type' => 'lead', 'id' => $leadId, 'info' => json_encode($info));
			$this->logUserActivity($activity);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while converting this lead to deal, please try again after sometime.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Delete a lead
	public function delete() {
		$leadId = $_POST['lead_id'];
		if (!$leadId) return;

		// Delete lead docs
		$docQuery = 'delete from fileupload where associate_id = ? and associate_to = ?';
		$docRes = $this->db->query($docQuery, array($leadId, 'lead'));

		// Delete lead notes
		$noteQuery = 'delete from note where associate_id = ? and associate_to = ?';
		$noteRes = $this->db->query($noteQuery, array($leadId, 'lead'));

		// Delete lead tasks
		$taskQuery = 'delete from task where associate_id = ? and associate_to = ?';
		$taskRes = $this->db->query($taskQuery, array($leadId, 'lead'));

		// Delete lead
		$deleteQuery = 'delete from lead where lead_id = ?';
		$deleteRes = $this->db->query($deleteQuery, array($leadId));

		$res = array();
		if ($docRes && $noteRes && $taskRes && $deleteRes) {
			$res['success'] = true;
			$res['redirectUrl'] = base_url() . "leads";
			$res['message'] = 'Successfully deleted this lead and dependencies.';

			// Log activity
			$activity = array('action' => 'DELETE', 'type' => 'lead', 'id' => $leadId);
			$this->logUserActivity($activity);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while deleting this lead or dependences, please try again after sometime.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Get leads
	public function getLeads($filters = '') {
		if (!$filters) $filters = $this->filters;

		$query = "select SQL_CALC_FOUND_ROWS *, trim(concat(le.first_name, ' ', le.last_name)) as lead_name, if (u.name, u.name, u.name) as owner
						  from lead le
						  left join user u on le.lead_owner_id = u.user_id";

		// Role checkup
		$whereCond = '';
		$params = array();
		if ($this->isManager) {
			$whereCond .= ' where le.org_id = ? and le.report_to_id = ? or le.lead_owner_id = ?';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ' where le.org_id = ? and le.lead_owner_id = ?';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' where le.org_id = ?';
			$params[] = $this->user->org_id;
		}
		$countWhereCond = $whereCond;
		$countParams = $params;

		// Apply filters
		if ($filters) {
			$filtersCondition = '';
			$ik=0;
			$_SESSION['filters']="leads";
			foreach ($filters as $fkey => $fvalue) {
					$ik++;
					$_SESSION['filters_key'][$ik]=$fkey ;
					$_SESSION['filters_fvalue'][$ik]=$fvalue ;
					
				if ($fvalue && ($fvalue != 'All')) {
					if ($fkey == 'lead_create_date') {
						switch ($fvalue) {
							case 'today':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "date($fkey) = ?";
								$params[] = date("Y-m-d");
								break;
							case 'yesterday':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "date($fkey) = ?";
								$tomorrow = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
								$params[] = date("Y-m-d", $tomorrow);
								break;
							case 'curr_week':
							
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "YEARWEEK($fkey,1) = YEARWEEK(CURDATE(), 1)";
								break;
							case 'last_week':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . " $fkey  >= CURDATE( ) - INTERVAL DAYOFWEEK( CURDATE( ) ) +6 DAY AND $fkey < CURDATE( ) - INTERVAL DAYOFWEEK( CURDATE( ) ) -1 DAY";
								break;
							case 'curr_month':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "month($fkey) = ? AND YEAR($fkey) = ?";
								$params[] = date('m');
								$params[] = date('Y');
								break;
							case 'last_month':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "month($fkey) = ? AND YEAR($fkey) = ?";
								$params[] = date('m') - 1;
								$params[] = date('Y');
								break;
							case '90_days':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "$fkey >= utc_timestamp() - interval 90 day";
								$params[] = date("Y-m-d");
								break;	
												
							default:
								break;
						}
					}else if($fkey == 'lead_modify_date'){
						
					
						switch ($fvalue) {
							case 'today':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "date($fkey) = ?";
								$params[] = date("Y-m-d");
								break;
							case 'yesterday':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "date($fkey) = ?";
								$tomorrow = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
								$params[] = date("Y-m-d", $tomorrow);
								break;
							case 'curr_week':
							
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "YEARWEEK($fkey,1) = YEARWEEK(CURDATE(), 1)";
								break;
							case 'last_week':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . " $fkey  >= CURDATE( ) - INTERVAL DAYOFWEEK( CURDATE( ) ) +6 DAY AND $fkey < CURDATE( ) - INTERVAL DAYOFWEEK( CURDATE( ) ) -1 DAY";
								break;
							case 'curr_month':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "month($fkey) = ? AND YEAR($fkey) = ?";
								$params[] = date('m');
								$params[] = date('Y');
								break;
							case 'last_month':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "month($fkey) = ? AND YEAR($fkey) = ?";
								$params[] = date('m') - 1;
								$params[] = date('Y');
								break;
							case '90_days':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "$fkey >= utc_timestamp() - interval 90 day";
								$params[] = date("Y-m-d");
								break;							
							default:
								break;
						}
						
					}
					else if($fkey =='first_name')
					{
						
						 $filtersCondition .= ($filtersCondition ? ' and ' : '') . " (CONCAT( $fkey, ' ', last_name ) LIKE '%".$fvalue."%' or company_name  LIKE '%".$fvalue."%')" ;
						
						
						
					}
					
					else {
						$filtersCondition .= ($filtersCondition ? ' and ' : '') . "$fkey = ?";
						$params[] = $fvalue;
					}
									}
			}
			if ($filtersCondition) $whereCond .= ($whereCond ? ' and ' : ' where ') . $filtersCondition;
		}

		// ======== Datatable adjustments ========
		$aColumns = array_keys($this->leadTableCols);
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
		
		/* 
		 * Filtering...
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		// $sWhere = "";
		// if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		// {
		// 	$sWhere = "WHERE (";
		// 	for ( $i=0 ; $i<count($aColumns) ; $i++ )
		// 	{
		// 		$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
		// 	}
		// 	$sWhere = substr_replace( $sWhere, "", -3 );
		// 	$sWhere .= ')';
		// }
		
		/* Individual column filtering */
		// for ( $i=0 ; $i<count($aColumns) ; $i++ )
		// {
		// 	if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		// 	{
		// 		if ( $sWhere == "" )
		// 		{
		// 			$sWhere = "WHERE ";
		// 		}
		// 		else
		// 		{
		// 			$sWhere .= " AND ";
		// 		}
		// 		$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		// 	}
		// }

		$query .= " $whereCond $sOrder $sLimit";
		$resObj = $this->db->query($query, $params);
		
		$_SESSION['leads_export'] = $whereCond;
		$_SESSION['leads_export_params'] = $params;

		$leads = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->lead_create_date = $this->convertDateTime($row->lead_create_date);
				$leads[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countQuery = 'SELECT COUNT(`lead_id`) as totalRows FROM lead le ' . $countWhereCond;
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $leads;
	}

	// Get a lead details
	public function getLeadDetails($id) {
		$resObj = $this->db->query("select le.*, u.name from lead le left join user u on le.lead_owner_id = u.user_id where le.lead_id = ?", array($id));
		return $resObj->row();
	}

	// Get reporter id
	// public function getReporterId($user_id = '') {
	// 	if (!$user_id) $user_id = $this->user->user_id;
	// 	$resObj = $this->db->query("select report_to_id from user where user_id = ?", array($user_id));
	// 	if ($resObj->num_rows()) {
	// 		$row = $resObj->row();
	// 	}
	// 	return $row->report_to_id;
	// }

	// Get next lead
	public function getNextLead($id) {
		$resObj = $this->db->query("select * from lead where lead_id > ? order by lead_id limit 1", array($id));
		return $resObj->row();
	}

	// Get previous lead
	public function getPrevLead($id) {
		$resObj = $this->db->query("select * from lead where lead_id < ? order by lead_id desc limit 1", array($id));
		return $resObj->row();
	}
	
	
	
	public function changestatus() {
		// Update task
		$updateQuery = 'update lead set lead_status = ? where lead_id = ?';
		$params = array($_REQUEST['status'], $_REQUEST['id']);
		$updateRes = $this->db->query($updateQuery, $params);

		$res = array();
		if ($updateRes) {
			$res['success'] = true;
			$res['message'] = 'Lead status changed successfully.';
			
			// Log activity
			$activity = array('action' => 'UPDATE', 'type' => 'lead', 'id' => $_REQUEST['id'], 'info' => json_encode(array('subaction' => 'change status')));
			$this->logUserActivity($activity);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while changing the lead status.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}
	
		
	
}