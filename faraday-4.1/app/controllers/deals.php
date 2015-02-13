<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class deals extends BCZ_Controller {

	private $filters = '';

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'Opportunities';
		$data['cols'] = array_values($this->dealTableCols);
		$data['mobileCols'] = array(0, 4, 5);
		$data['currency_freeze']=$this->getOrganizationcurrency_freeze();
		
		
		if($_SESSION['filters']!="opportunities")
		{
			unset($_SESSION['filters_key']);
			unset($_SESSION['filters_fvalue']);
		}


		if (isset($_REQUEST['filters'])) {
			$data['sourcePath'] = 'deals/getdealsjson';
			$this->load->view('DataTableView', $data);
		} else {
			
			
			$data['filters'] = array(						 'Owner ' => array('col' => 'deal_owner_id', 'alias' => 'name', 'user_status' => 'user_status'), 
															 'stage' => array('col' => 'stage', 'alias' => 'stage_name'),
															 'Created' => array('col' => 'deal_create_date', 'alias' => 'deal_create_date', 'type' => 'date'),
															 
'Close Date' => array('col' => 'exp_close', 'alias' => 'exp_close', 'type' => 'close_date'));
			$data['mobFilters'] = array('stage', 'exp_close');
			
			$data['deals'] = $this->getDeals();
			$data['dealsfilters'] = $this->getDealsfilters();
			
			$data['content'] = 'DealsView';
			$this->load->view('FirstLayoutView', $data);
		}
	}
	
	
	
	public function lost()
	{
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'Opportunities';
		$data['cols'] = array_values($this->dealTableCols);
		$data['mobileCols'] = array(0, 4, 5);

		if (isset($_REQUEST['filters'])) {
			$data['sourcePath'] = 'deals/getdealsjson';
			$this->load->view('DataTableView', $data);
		} else {
			$data['filters'] = array('source' => array('col' => 'source', 'alias' => 'source'), 
				
				 
													 'stage' => array('col' => 'stage', 'alias' => 'stage'),
															 'expected date' => array('col' => 'exp_close', 'alias' => 'exp_close', 'type' => 'close_date'));
			$data['mobFilters'] = array('stage', 'exp_close');
			
			$data['deals'] = $this->getDeals();
			$data['content'] = 'DealsView';
			$data['stage']='lost';
			$this->load->view('FirstLayoutView', $data);
		}
	}
	
	

	// Get deals json for datatable
	public function getdealsjson() {
		// Check for filters
	
		if (isset($_GET['filters'])) $this->filters = (array)json_decode($_GET['filters']);

		// Get deals and arrange data for datatable
		$deals = $this->getDeals();
		$output = $this->constructDTOutput($deals, array_keys($this->dealTableCols), 'deal_id', 'deals/details', 1);
		echo json_encode($output);
	}

	// Export data
	public function export() {
		//$deals = $this->getAllDeals();
		$deals = $this->getAllDealsExport($_SESSION['deal_export'],$_SESSION['deal_export_params']);
		$this->exportData($deals, 'Opportunities_data.xls');
	}

	public function details($id) {
		$this->bodyClass = 'deal-details';
		$this->pageTitle = $this->pageDesc = 'Opportunities Details';
		$data['content'] = 'DealDetailsView';
		$data['users'] = $this->getAllUsers();
		

		

		// Arrange users for view manipulation
		$data['aUsers'] = array();
		foreach ($data['users'] as $user) {
			$data['aUsers'][$user->user_id] = $user;
		}

		// Get deal details and arrange that data
		 $deal = $this->getDealDetails($id);
		 
		 $StatusDetails = $this->getStatusDetails($deal->status);

		 $deal->status=$StatusDetails->lead_status;
		 $org_id=$this->user->org_id;
		if($this->user->demo==0)
		{
		
			 $data['NextId']=$this->getNextIdAndPreviousId("select *, de.org_id, co.designation as contact_designation, co.email as contact_email , c.company_name as company_name 
									from deal de 
									left join company c on de.deal_company_id = c.company_id 
									left join contact co on de.deal_contact_id = co.contact_id 
									left join user u on de.deal_owner_id = u.user_id 
									where (de.deal_id> ?) AND (de.org_id = ? OR  de.org_id = '0' )  ORDER BY de.deal_id ASC LIMIT 1",$id,"deal_id",$org_id);


			$data['PreviousId']=$this->getNextIdAndPreviousId("select *, de.org_id, co.designation as contact_designation, co.email as contact_email , c.company_name as company_name 
									from deal de 
									left join company c on de.deal_company_id = c.company_id 
									left join contact co on de.deal_contact_id = co.contact_id 
									left join user u on de.deal_owner_id = u.user_id 
									where (de.deal_id< ?) AND (de.org_id = ? OR  de.org_id = '0' )  ORDER BY de.deal_id desc LIMIT 1",$id,"deal_id",$org_id);
									
									
		}
		else
		{
			
				$data['NextId']=$this->getNextIdAndPreviousId("select *, de.org_id, co.designation as contact_designation, co.email as contact_email , c.company_name as company_name 
									from deal de 
									left join company c on de.deal_company_id = c.company_id 
									left join contact co on de.deal_contact_id = co.contact_id 
									left join user u on de.deal_owner_id = u.user_id 
									where de.deal_id< ? AND de.org_id = ?  ORDER BY de.deal_id desc LIMIT 1",$id,"deal_id",$org_id);
		
	$data['PreviousId']=$this->getNextIdAndPreviousId("select *, de.org_id, co.designation as contact_designation, co.email as contact_email , c.company_name as company_name 
									from deal de 
									left join company c on de.deal_company_id = c.company_id 
									left join contact co on de.deal_contact_id = co.contact_id 
									left join user u on de.deal_owner_id = u.user_id 
									where de.deal_id< ? AND de.org_id = ?  ORDER BY de.deal_id desc LIMIT 1",$id,"deal_id",$org_id);
		}
		 
		 
		 
		// print_r($this->getDealDetails($id));
		// $deal = $this->getUserDetails($deal[]) 
		$deal->contact_name = $deal->first_name . ($deal->last_name ? " $deal->last_name" : '');

		$this->orgAccessCheck($deal->org_id);	// Organization access check

		// Created Before
		$startDate = new DateTime(date('Y-m-d', strtotime($deal->deal_create_date)));
		$endDate = new DateTime(date('Y-m-d'));
		$diff = $startDate->diff($endDate);
		$deal->created_before = $this->formatDays($diff->days);

		// Reassigned from
		if ($deal->deal_assigned_from) {
			$reassignee = $this->getUserDetails($deal->deal_assigned_from);
			$deal->reassignee = ucfirst($reassignee->name);
		}
		
		$deal->deal_application=$this->campaignname($deal->deal_application);

		// Get deal stages
		$data['fields'] = $this->getAllUserFields();
		$stages = array();
		$count = 0;
		foreach ($data['fields'] as $field) { 
			if ($field->stage) {
				$stages[$field->no] = $field->stage;
				if ($deal->stage == $field->stage) $dealStageIndex = $count;
				$count++;
			}
		}

	/*	if ($dealStageIndex < 3) {
			$deal->stages = array_slice($stages, 0, 5);
		} else if ($dealStageIndex > (count($stages) - 4)) {
			$deal->stages = array_slice($stages, (count($stages) - 5));
		} else {
			$deal->stages = array_slice($stages, ($dealStageIndex-2), ($dealStageIndex+2));
		}
		*/
		
		//$deal->stages = array_slice($stages, 0,10);
		 $deal->stages =$stages;
	
 $data['stage']= $this->getStatusName($deal->stage,'stage');
		// Deal notes
		$data['notes'] = $this->getItemNotes('deal', $id);

		// Get deal tasks and arrange data for datatable construction
		$data['tasks'] = $this->getItemTasks('deal', $id);

		// Get deal contacts and arrange data for datatable construction
		$data['contacts'] = $this->getItemContacts('deal', $id);

		// Deal documents
		$data['docs'] = $this->getItemDocs('deal', $id);

		// Deal quotes
		$data['quotes'] = $this->getItemQuotes('deal', $id);
		
		$data['Orders'] = $this->getItemOrders('deal', $id);

		// Deal(quote) products
		$data['products'] = $this->getDealProducts($id);

		// Deal emails
		$data['emails'] = $this->getItemEmails('deal', $id);

		// Deal history
		$data['history'] = $this->getItemHistory('deal', $id);

		// Email modal content
		$data['contact_id'] = $deal->deal_contact_id;
		$data['company_id'] = $deal->deal_company_id;
		$data['deal_id'] = $deal->deal_id;
		$data['from'] = $this->user->user_email;
		$data['to'] = $deal->email;
		$data['bcc'] = $this->user->user_email;
		//$data['message'] = "Dear {$deal->first_name} {$deal->last_name},\n\nThanks for your valuable inquiry. I reviewed your requirement carefully and prepared a proposal for you. Also attached necessary catalogue for your review. Please call me if you need any clarifications.\n\n{$this->user->mail_signature}";
		$data['message'] = "Dear {$deal->first_name} {$deal->last_name},\n\nGreetings of the day!\n\nThank you for your valuable inquiry.  We completely understand your requirement and will get back to you on the same as early as possible.\n\nPlease feel free to call {$this->supportInfo['contact']} or write to {$this->supportInfo['email']} for any further clarifications.\n\nWarmest Regards\n\n{$this->user->mail_signature}";
		$data['type'] = 'deal';
		$data['id']	= $deal->deal_id;

		$data['deal'] = $deal;
		$data['prev_deal'] = $this->getPrevDeal($id);
		$data['next_deal'] = $this->getNextDeal($id);
		$data['companies'] = $this->getAllCompanies();	// Get all companies
		$this->load->view('FirstLayoutView', $data);		
	}

	public function add()
	{
		$this->bodyClass = 'create-deal';
		$this->pageTitle = $this->pageDesc = 'Add Opportunities';
		$this->pageTitle = 'Opportunities';
		
		$data['currency_freeze']=$this->getOrganizationcurrency_freeze();
		
		$data['content'] = 'CreateDealView';

		$data['companies'] = $this->getAllCompanies();	// Get all companies
		$data['contacts'] = $this->getAllContacts();		// TODO: Get contacts
		$data['users'] = $this->getAllUsers();			// Get all users
		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$data['campaigns'] = $this->getAllcampaigns();	// Get all campaigns fields

		$this->load->view('FirstLayoutView', $data);
	}

	public function submit() {
		// Check for the task creation through modal
		
		
		if(isset($_POST['deal_company_id']))
		{
			$updatecampaign = $_POST['deal_application']; 
		$type='opp_count';
		$cam=$this->campaigncount($updatecampaign,$type);
		
			if($_POST['deal_company_id']=="")
			{
				$company_name=$_POST['opp_company_name'];
				$query = 'select MAX(company_id) as maxid  from company where org_id=? AND company_name=? ';
				$params[] = $this->user->org_id;
				$params[] = $_POST['opp_company_name'];
				$resObj = $this->db->query($query, $params);
				$row = $resObj->row();
				$max_id = $row->maxid;
				$_POST['deal_company_id'] = $max_id;
				
				unset($_POST['opp_company_name']);
			}
			else
			{
				unset($_POST['opp_company_name']);
			}
		}
		
		
		if(isset($_POST['deal_contact_id']))
		{
			if($_POST['deal_contact_id']=="")
			{
				$company_name=$_POST['opp_contact_name'];
				$query = 'select MAX(company_id) as maxid  from contact where org_id=? AND (last_name=? or first_name=?)';
				$params[] = $this->user->org_id;
				$params[] = $_POST['opp_contact_name'];
				$params[] = $_POST['opp_contact_name'];
				$resObj = $this->db->query($query, $params);
				$row = $resObj->row();
				$max_id = $row->maxid;
				$_POST['deal_contact_id'] = $max_id;
				
				unset($_POST['opp_contact_name']);
			}
			else
			{
				unset($_POST['opp_contact_name']);
			}
		}
		
		
		
		if (isset($_POST['modal_flag'])) {
			$modal_flag = $_POST['modal_flag']; unset($_POST['modal_flag']);
			$associate_to = $_POST['associate_to']; unset($_POST['associate_to']);
			$associate_id = $_POST['associate_id']; unset($_POST['associate_id']);
		}

		// Gather form fields
		$data = $formFields = array();
		$currDateTime = $this->getCurrTime();
		$valsStr = '';
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = ($fieldName == 'exp_close') ? date('Y-m-d', strtotime($fieldVal)) : trim($fieldVal);
			$valsStr .= ($valsStr ? ', ' : '') . '?';
		}
		$formFields['org_id'] = $this->user->org_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';

		// Deal report to handling
		if ($formFields['deal_owner_id']) {
			$reporterId = ($formFields['deal_owner_id'] == $this->user->user_id) ? ($this->user->report_to_id ? $this->user->report_to_id : $this->user->user_id) : '';
			if (!$reporterId) $reporterId = $this->getUserReporter($formFields['deal_owner_id']);
			$formFields['report_to_id '] = $reporterId;
			$valsStr .= ($valsStr ? ', ' : '') . '?';
		}

		// Stage specific probability
		$formFields['probability'] = $this->getStageProbability($formFields['stage']);
		$valsStr .= ($valsStr ? ', ' : '') . '?';

		$formFields['deal_create_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['deal_modify_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Create a deal with given details
		$dealId = $this->getTableAutoID('deal');
		$query = 'insert into deal (' . implode(', ', array_keys($formFields)) . ') values (' . $valsStr . ')';
		$res = $this->db->query($query, array_values($formFields));

		// Update contact
		$updateQuery = 'update contact set associate_to = ?, associate_id = ? where contact_id = ?';
		$params = array('deal', $dealId, $formFields['deal_contact_id']);
		$updateRes = $this->db->query($updateQuery, $params);

		if ($res && $updateRes) {
			// Log activity
			$activity = array('action' => 'CREATE', 'type' => 'deal', 'id' => $dealId);
			$this->logUserActivity($activity);

			// Clear session info
			if (isset($_SESSION['dealContact'])) unset($_SESSION['dealContact']);
			if (isset($_SESSION['dealCompany'])) unset($_SESSION['dealCompany']);

			if (isset($_SESSION['sourceUrl']) && !$modal_flag) {
				$sourceUrl = $_SESSION['sourceUrl'];
				unset($_SESSION['sourceUrl']);
				redirect($sourceUrl, 'location', 301);
			}

			if (!$modal_flag) redirect(base_url(). 'deals/details/' . $dealId, 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while creating this deal, please try again after sometime.';
		}

		if ($modal_flag) {
			$data['deals'][0] = true;
			$data['entityType'] = $associate_to;
			$data['entityId'] = $associate_id;
			$data['entitySourcePath'] = ($associate_to == 'contact') ? 'contacts/getcontactdealsjson' : 'companies/getcompanydealsjson';
			$this->load->view('EntityDealsView', $data);
		} else {
			$this->add($data);
		}
	}

	public function edit($id, $data = array()) {
		$this->bodyClass = 'edit-deal';
		$this->pageTitle = $this->pageDesc = 'Edit Opportunities';
		$data['content'] = 'EditDealView';

 $data['stage']= $this->getStatusName($deal->stage,'stage');
 
		$data['companies'] = $this->getAllCompanies();	// Get all companies
		$data['contacts'] = $this->getAllContacts();		// TODO: Get contacts
		$data['users'] = $this->getAllUsers();			// Get all users
		$data['fields'] = $this->getAllUserFields();	// Get all user fields

		$data['deal'] = $this->getDealDetails($id); 	// Get lead details
		$data['campaigns']=$this->getAllcampaigns();	// Get campaigns details
		
		$this->load->view('FirstLayoutView', $data);
	}

	public function update($id) {
		
		
	$campaign=$_POST['campaign'];
	$updatecampaign = $_POST['deal_application']; 
	
	unset($_POST['campaign']);
	
	if($updatecampaign!=$campaign)
	{
		$type='opp_count';
		$cam=$this->campaigncountsub($campaign,$updatecampaign,$type);
	}
		
		if(isset($_POST['deal_company_id']))
		{
			if($_POST['deal_company_id']=="")
			{
				$company_name=$_POST['opp_company_name'];
				$query = 'select MAX(company_id) as maxid  from company where org_id=? AND company_name=? ';
				$params[] = $this->user->org_id;
				$params[] = $_POST['opp_company_name'];
				$resObj = $this->db->query($query, $params);
				$row = $resObj->row();
				$max_id = $row->maxid;
				$_POST['deal_company_id'] = $max_id;
				
				unset($_POST['opp_company_name']);
			}
			else
			{
				unset($_POST['opp_company_name']);
			}
		}
		
		
		if(isset($_POST['deal_contact_id']))
		{
			if($_POST['deal_contact_id']=="")
			{
				$company_name=$_POST['opp_contact_name'];
				$query = 'select MAX(company_id) as maxid  from contact where org_id=? AND (last_name=? or first_name=?)';
				$params[] = $this->user->org_id;
				$params[] = $_POST['opp_contact_name'];
				$params[] = $_POST['opp_contact_name'];
				$resObj = $this->db->query($query, $params);
				$row = $resObj->row();
				$max_id = $row->maxid;
				$_POST['deal_contact_id'] = $max_id;
				
				unset($_POST['opp_contact_name']);
			}
			else
			{
				unset($_POST['opp_contact_name']);
			}
		}
		
		
		// Gather form fields
		$data = $formFields = array();
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = ($fieldName == 'exp_close') ? date('Y-m-d', strtotime($fieldVal)) : trim($fieldVal);
		}

		// Stage specific probability
		$formFields['probability'] = $this->getStageProbability($formFields['stage']);
		$formFields['deal_modify_date'] = $this->getCurrTime();
		
		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Update deal with given details
		$query = 'update deal set ' . implode(' = ?, ', array_keys($formFields)) . ' = ? where deal_id = ?';
		$formFields['deal_id'] = $id;
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			// Log activity
			$activity = array('action' => 'UPDATE', 'type' => 'deal', 'id' => $id);
			$this->logUserActivity($activity);
			redirect(base_url() . "deals/details/$id", 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while updating this deal, please try again after sometime.';
		}

		$this->edit($data);
	}

	// Re-assign deal
	public function reassign() {
		$dealId = $_POST['deal_id'];
		$reassignId = $_POST['deal_owner_id'];
		$deal = $this->getDealDetails($dealId);
		$reassignee = $this->getUserDetails($reassignId);
		$currDateTime = $this->getCurrTime();

		// Reassign deal
		$dealQuery = 'update deal set deal_assigned_from = ?, deal_modify_date = ?, deal_owner_id = ?, report_to_id = ? , inbox = 0  where deal_id = ?';
		$dealRes = $this->db->query($dealQuery, array($deal->deal_owner_id, $currDateTime, $reassignId, ($reassignee->report_to_id ? $reassignee->report_to_id : $reassignee->user_id), $dealId));

		// Reassign contact
		$contactQuery = 'update contact set owner_id = ?, report_to_id = ? where (associate_to = ? and associate_id = ?) or (associate_to = ? and associate_id = ?) and owner_id = ? and report_to_id = ?';
		$contactRes = $this->db->query($contactQuery, array($reassignId, ($reassignee->report_to_id ? $reassignee->report_to_id : $reassignee->user_id), 'deal', $dealId, 'company', $deal->deal_company_id, $deal->deal_owner_id, $deal->report_to_id));

		// Reassign company
		$companyQuery = 'update company set owner_id = ?, report_to_id = ? where company_id = ? and owner_id = ? and report_to_id = ?';
		$companyRes = $this->db->query($companyQuery, array($reassignId, ($reassignee->report_to_id ? $reassignee->report_to_id : $reassignee->user_id), $deal->deal_company_id, $deal->deal_owner_id, $deal->report_to_id));

		// Reassign quote
		$quoteQuery = 'update quote set quote_owner_id = ?, quote_report_to_id = ? where deal_id = ? and quote_owner_id = ? and quote_report_to_id = ?';
		$quoteRes = $this->db->query($quoteQuery, array($reassignId, ($reassignee->report_to_id ? $reassignee->report_to_id : $reassignee->user_id), $dealId, $deal->deal_owner_id, $deal->report_to_id));

		// Reassign notes
		$noteQuery = 'update note set owner_id = ?, report_to_id = ? where associate_to = ? and associate_id = ? and owner_id = ? and report_to_id = ?';
		$noteRes = $this->db->query($noteQuery, array($reassignId, ($reassignee->report_to_id ? $reassignee->report_to_id : $reassignee->user_id), 'deal', $dealId, $deal->deal_owner_id, $deal->report_to_id));

		// Reassign tasks
		$taskQuery = 'update task set task_owner_id = ?, task_report_to_id = ? where associate_to = ? and associate_id = ? and task_owner_id = ? and task_report_to_id = ?';
		$taskRes = $this->db->query($taskQuery, array($reassignId, ($reassignee->report_to_id ? $reassignee->report_to_id : $reassignee->user_id), 'deal', $dealId, $deal->deal_owner_id, $deal->report_to_id));

		// Reassign docs
		$docQuery = 'update fileupload set owner_id = ?, report_to_id = ? where associate_to = ? and associate_id = ? and owner_id = ? and report_to_id = ?';
		$docRes = $this->db->query($docQuery, array($reassignId, ($reassignee->report_to_id ? $reassignee->report_to_id : $reassignee->user_id), 'deal', $dealId, $deal->deal_owner_id, $deal->report_to_id));

		$res = array();
		if ($dealRes && $contactRes && $companyRes && $quoteRes && $noteRes && $taskRes && $docRes) {
			$res['success'] = true;
			$res['message'] = 'Successfully reassigned this Opportunities.';

			// Log activity
			$info = array('from' => $deal->deal_owner_id, 'to' => $reassignId);
			$activity = array('action' => 'REASSIGN', 'type' => 'deal', 'id' => $dealId, 'info' => json_encode($info));
			$this->logUserActivity($activity);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while reassigninig this Opportunities, please try again after sometime.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Re-assign deal
	public function changeStage() {
		// Update contact
		$updateQuery = 'update deal set stage = ? where deal_id = ?';
		$params = array($_REQUEST['stage'], $_REQUEST['id']);
		$updateRes = $this->db->query($updateQuery, $params);

		$res = array();
		if ($updateRes) {
			$res['success'] = true;
			$res['message'] = 'Opportunities stage changed successfully.';
			
			// Log activity
			$activity = array('action' => 'UPDATE', 'type' => 'deal', 'id' => $_REQUEST['id'], 'info' => json_encode(array('subaction' => 'change stage')));
			$this->logUserActivity($activity);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while changing the Opportunities status.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}
	
	
	public function changestagemenu() {
		
		// Get deal stages
		$data['fields'] = $this->getAllUserFields();
		$stages = array();
		$count = 0;
		foreach ($data['fields'] as $field) { 
			if ($field->stage) {
				$stages[$count] = $field->stage;
				if ($deal->stage == $field->stage) $dealStageIndex = $count;
				$count++;
			}
		}

		if ($dealStageIndex < 3) {
			$deal->stages = array_slice($stages, 0, 5);
		} else if ($dealStageIndex > (count($stages) - 4)) {
			$deal->stages = array_slice($stages, (count($stages) - 5));
		} else {
			$deal->stages = array_slice($stages, ($dealStageIndex-2), ($dealStageIndex+2));
		}
		
		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($deal->stages));
		
	}

	// Delete a deal
	public function delete() {
		$dealId = $_POST['deal_id'];
		if (!$dealId) return;

		// Delete deal docs
		$docQuery = 'delete from fileupload where associate_id = ? and associate_to = ?';
		$docRes = $this->db->query($docQuery, array($dealId, 'deal'));

		// Delete deal notes
		$noteQuery = 'delete from note where associate_id = ? and associate_to = ?';
		$noteRes = $this->db->query($noteQuery, array($dealId, 'deal'));

		// Delete deal tasks
		$taskQuery = 'delete from task where associate_id = ? and associate_to = ?';
		$taskRes = $this->db->query($taskQuery, array($dealId, 'deal'));

		// TODO: handle deal company also

		// Delete deal contacts
		$contactQuery = 'delete from contact where associate_id = ? and associate_to = ?';
		$contactRes = $this->db->query($contactQuery, array($dealId, 'deal'));

		// Delete deal email
		$emailQuery = 'delete from email where deal_id = ?';
		$emailRes = $this->db->query($emailQuery, array($dealId));

		// Delete deal quotes
		$quoteQuery = 'delete from quote where deal_id = ?';
		$quoteRes = $this->db->query($quoteQuery, array($dealId));

		// Delete deal orders
		$orderQuery = 'delete from sales_order where deal_id = ?';
		$orderRes = $this->db->query($orderQuery, array($dealId));

		// Delete deal
		$deleteQuery = 'delete from deal where deal_id = ?';
		$deleteRes = $this->db->query($deleteQuery, array($dealId));

		$res = array();
		if ($docRes && $noteRes && $taskRes && $contactRes && $emailRes && $quoteRes && $orderRes && $deleteRes) {
			$res['success'] = true;
			$res['redirectUrl'] = base_url() . "deals";
			$res['message'] = 'Successfully deleted this Opportunities and dependencies.';

			// Log activity
			$activity = array('action' => 'DELETE', 'type' => 'deal', 'id' => $dealId);
			$this->logUserActivity($activity);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while deleting this Opportunities or dependences, please try again after sometime.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Add a new company
	public function addCompany() {
		$_SESSION['sourceUrl'] = base_url() . 'deals/add';
		redirect(base_url(). 'companies/add', 'location', 301);
	}

	// Add a new contact
	public function addContact() {
		$_SESSION['sourceUrl'] = base_url() . 'deals/add';
		redirect(base_url(). 'contacts/add', 'location', 301);
	}

	// Get contacts
	private function getContacts($company_id) {
		$query = 'select * 
				  from contact co 
				  left join company c on co.company_id = c.company_id';
		$params = array();

		if ($this->isAdmin) {
			$query .= ' join user u on co.owner_id = u.user_id where co.company_id = ?';
			$params[] = $company_id;

		} else if ($this->isManager) {
			$query .= ' where co.company_id = ? and (co.report_to_id = ? or co.owner_id = ?)';
			$params[] = $company_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		
		} else {
			$query .= ' where co.company_id = ? and co.owner_id = ?';
			$params[] = $company_id;
			$params[] = $this->user->user_id;
		}
		$resObj = $this->db->query($query, $params);

		$contacts = array();
		if ($resObj->num_rows() > 0) {
			foreach ($resObj->result() as $row) {
				$contacts[] = $row;
		   	}
		}
		return $contacts;
	}

	// Get deals
	public function getDeals($filters = '') {
		$params = array();
		$whereCond = '';
		if (!$filters){ $filters = $this->filters; }
		 if($filters=="") { 
		
		$filters ='de.deal_id'; }
		
		$whereCond1 = ' AND uf.stage != "Won" and uf.stage != "Lost" and uf.stage != "Archived"';

		$query = "select SQL_CALC_FOUND_ROWS *, if (u.name, u.name, u.name) as owner,u.user_status as user_status, de.deal_owner_id as deal_owner_id  , if(uf.stage,uf.stage,uf.stage) as stage_name , if(de.stage,de.stage,de.stage) as stage , if(de.source,de.source,de.source) as  source
						  from deal de 
						  left join user_fields uf on de.stage =uf.no
						  left join company c on de.deal_company_id = c.company_id
						  left join user u on (de.deal_owner_id = u.user_id) ";
	//	$params[] = '0';
		
		//  left join user u on (c.assign_to = u.user_id AND c.assign_to != ? or c.owner_id=u.user_id) ";
		//$params[] = '0';
		
		

		 // ' where de.stage != ? and de.stage != ?';
		 //array('Won', 'Lost');

		// Role checkup

		if($this->user->demo==0)
		{

		if ($this->isManager) {
			$whereCond .="left join report_to rt on de.assign_to = rt.user_id". ($whereCond ? ' and ' : ' where ') . ' ( de.org_id = ? or de.org_id = ? )and (de.report_to_id = ? or de.deal_owner_id = ?  or  c.report_to_id = ? or  c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .="left join report_to rt on de.assign_to = rt.user_id". ($whereCond ? ' and' : ' where') . ' (de.org_id = ? or de.org_id = ? ) and (de.deal_owner_id = ?  or c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ($whereCond ? ' and' : ' where') . ' ( de.org_id = ? or de.org_id = ? )';
			$params[] = $this->user->org_id;
			$params[] = "0";
		}
	
		
		}
		else
		{
			if ($this->isManager) {
			$whereCond .= "left join report_to rt on de.assign_to = rt.user_id".($whereCond ? ' and ' : ' where ') . ' de.org_id = ? and (de.report_to_id = ? or de.deal_owner_id = ?  or  c.report_to_id = ? or  c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .="left join report_to rt on de.assign_to = rt.user_id". ($whereCond ? ' and' : ' where') . ' de.org_id = ? and (de.deal_owner_id = ?  or c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ($whereCond ? ' and' : ' where') . ' de.org_id = ?';
			$params[] = $this->user->org_id;
		}
		
		}
		
		
		

				
		
		$countWhereCond = $whereCond;
		$countParams = $params;

		// Apply filters
		if ($filters) {
			$filtersCondition = '';
			$ik=0;
			$_SESSION['filters']="opportunities";
			foreach ($filters as $fkey => $fvalue) {
				
				$ik++;
					$_SESSION['filters_key'][$ik]=$fkey ;
					$_SESSION['filters_fvalue'][$ik]=$fvalue ;
					
				if ($fvalue && ($fvalue != 'All')) {
					if (($fkey == 'deal_create_date') ) {
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
					else if (($fkey == 'exp_close')) {
						switch ($fvalue) {
							case 'today':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "date($fkey) = ?";
								$params[] = date("Y-m-d");
								break;
							case 'tomorrow':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "date($fkey) = ?";
								$tomorrow = mktime(0, 0, 0, date("m"), date("d")+1, date("Y"));
								$params[] = date("Y-m-d", $tomorrow);
								break;
							case 'curr_week':
							
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "YEARWEEK($fkey,1) = YEARWEEK(CURDATE(), 1)";
								break;
							case 'next_week':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . " $fkey  <= CURDATE( ) + INTERVAL DAYOFWEEK( CURDATE( ) ) +6 DAY AND $fkey > CURDATE( ) + INTERVAL DAYOFWEEK( CURDATE( ) ) +1 DAY";
								break;
							case 'curr_month':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "month($fkey) = ? AND YEAR($fkey) = ?";
								$params[] = date('m');
								$params[] = date('Y');
								break;
							case 'next_month':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "month($fkey) = ? AND YEAR($fkey) = ?";
								$params[] = date('m') + 1;
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
					else if($fkey =='first')
					{
						 $filtersCondition .= ($filtersCondition ? ' and ' : '') . "(de.deal_name LIKE '%".$fvalue."%' or de.deal_amount LIKE '%".$fvalue."%')" ;
						
					}
					else if($fkey =='stage')
					{
						$whereCond1="";
						
						 $filtersCondition .= ($filtersCondition ? ' and ' : '') . 'de.'."$fkey = ?";
						$params[] = $fvalue;
					}
					else if($fkey =='source')
					{
						
						 $filtersCondition .= ($filtersCondition ? ' and ' : '') . 'de.'."$fkey = ?";
						$params[] = $fvalue;
					}
					
					
					 else {
						$filtersCondition .= ($filtersCondition ? ' and ' : ' ') . "$fkey = ?";
						$params[] = $fvalue;
					}
				}
				
			}
			if ($filtersCondition) $whereCond .= ($whereCond ? ' and ' : ' where ') . $filtersCondition;
		}
		
		// ======== Datatable adjustments ========
		$aColumns = array_keys($this->dealTableCols);
		// Paginating...
		$sLimit = "";
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
				intval( $_GET['iDisplayLength'] );
		}
		
		// Sorting...
		$sOrder = "";
		$_GET['iSortCol_0'];
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
				$sOrder = "ORDER BY deal_id desc";
			}
		}
		else
		{
			//$sOrder = "ORDER BY de.deal_create_date asc";
		}
		//$sOrder = "ORDER BY de.deal_create_date desc";
		$querys=$query;
		$query .= " $whereCond $whereCond1 $sOrder $sLimit";
		$resObj = $this->db->query($query, $params);
		
		$_SESSION['deal_export'] = $whereCond ;
		$_SESSION['deal_export_params'] = $params;


		$deals = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				
				$row->exp_close = $this->convertDateTime($row->exp_close);
				$row->deal_create_date = $this->convertDateTime($row->deal_create_date);
				
				$deals[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countQuery = 'SELECT COUNT(`deal_id`) as totalRows  FROM deal de left join user_fields uf on de.stage =uf.no left join company c on c.company_id = de.deal_company_id ' . $countWhereCond;
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $deals;
	}
	
	
	
	
		// Get deals
	public function getDealsfilters() {
		
		$params = array();

		$query = "select SQL_CALC_FOUND_ROWS *, if (u.name, u.name, u.name) as owner,u.user_status as user_status, de.deal_owner_id as deal_owner_id  , if(uf.stage,uf.stage,uf.stage) as stage_name , if(de.stage,de.stage,de.stage) as stage , if(de.source,de.source,de.source) as  source
						  from deal de 
						  left join user_fields uf on de.stage =uf.no
						  left join company c on de.deal_company_id = c.company_id
						  left join user u on (de.deal_owner_id = u.user_id)  ";
	//	$params[] = '0';
		
		//  left join user u on (c.assign_to = u.user_id AND c.assign_to != ? or c.owner_id=u.user_id) ";
		//$params[] = '0';
		
		
		$whereCond = ''; // ' where de.stage != ? and de.stage != ?';
		 //array('Won', 'Lost');

	
		// Role checkup

		if($this->user->demo==0)
		{

		if ($this->isManager) {
			$whereCond .="left join report_to rt on de.assign_to = rt.user_id". ($whereCond ? ' and ' : ' where ') . ' ( de.org_id = ? or de.org_id = ? )and (de.report_to_id = ? or de.deal_owner_id = ?  or  c.report_to_id = ? or  c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .="left join report_to rt on de.assign_to = rt.user_id". ($whereCond ? ' and' : ' where') . ' (de.org_id = ? or de.org_id = ? ) and (de.deal_owner_id = ?  or c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ($whereCond ? ' and' : ' where') . ' ( de.org_id = ? or de.org_id = ? )';
			$params[] = $this->user->org_id;
			$params[] = "0";
		}
	
		
		}
		else
		{
			if ($this->isManager) {
			$whereCond .= "left join report_to rt on de.assign_to = rt.user_id".($whereCond ? ' and ' : ' where ') . ' de.org_id = ? and (de.report_to_id = ? or de.deal_owner_id = ?  or  c.report_to_id = ? or  c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .="left join report_to rt on de.assign_to = rt.user_id". ($whereCond ? ' and' : ' where') . ' de.org_id = ? and (de.deal_owner_id = ?  or c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ($whereCond ? ' and' : ' where') . ' de.org_id = ?';
			$params[] = $this->user->org_id;
		}
		
		}
		$sLimit="";
		$sOrder = "ORDER BY uf.sort_order desc";
		$querys=$query;
		$query .= " $whereCond $sOrder $sLimit";
		$resObj = $this->db->query($query, $params);

		$deals = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$deals[] = $row;
			}
		}

		return $deals;
	}
	
	
	
	
	
	
	// Get next deal
	public function getNextDeal($id) {
		$resObj = $this->db->query("select * from deal where deal_id > ? order by deal_id limit 1", array($id));
		return $resObj->row();
	}

	// Get previous deal
	public function getPrevDeal($id) {
		$resObj = $this->db->query("select * from deal where deal_id < ? order by deal_id desc limit 1", array($id));
		return $resObj->row();
	}

	// Create a quote for this deal
	public function addQuote($id) {
		$_SESSION['quote_deal'] = $id;
		$_SESSION['sourceUrl'] = base_url() . "deals/details/$id";
		redirect(base_url(). 'quotes/add', 'location', 301);
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
}