<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cases extends BCZ_Controller {

	private $filters = '';

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'cases';
		$this->pageTitle = $this->pageDesc = 'Ticket';
		
		$data['cols'] = array_values($this->caseTableCols);
		$data['mobileCols'] = array(0, 1, 4);
if($_SESSION['filters']!="cases")
		{
			unset($_SESSION['filters_key']);
			unset($_SESSION['filters_fvalue']);
		}
		
		if (isset($_REQUEST['filters'])) {
			$data['sourcePath'] = 'cases/getcasesjson';
			$this->load->view('DataTableView', $data);
		} else {
			$data['filters'] = array('status' => array('col' => 'status', 'alias' => 'case_status'), 
															 'priority' => array('col' => 'priority', 'alias' => 'priority'),
															 'severity' => array('col' => 'severity', 'alias' => 'severity'),'created' => array('col' => 'case_create_date', 'alias' => 'case_create_date', 'type' => 'date'));
			$data['mobFilters'] = array('status', 'company');

			$data['cases'] = $this->getCases();
			$data['content'] = 'CasesView';
			$this->load->view('FirstLayoutView', $data);
		}
	}

	// Get cases json for datatable
	public function getcasesjson() {
		// Check for filters
		if (isset($_GET['filters'])) $this->filters = (array)json_decode($_GET['filters']);

		// Get cases and arrange data for datatable
		$cases = $this->getCases();
		$output = $this->constructDTOutput($cases, array_keys($this->caseTableCols), 'case_id', 'cases/details', 1);

		echo json_encode($output);
	}

	// Export data
	public function export() {
		//$cases = $this->getAllCases();
		$cases = $this->getAllCasesExport($_SESSION['cases_export'],$_SESSION['cases_export_params']);
		$this->exportData($cases, 'tickets_data.xls');
	}

	public function details($id) {
		$this->bodyClass = 'case-details';
		$this->pageTitle = $this->pageDesc = 'Ticket Details';
		$data['content'] = 'CaseDetailsView';
		$data['users'] = $this->getAllUsers();

		// Get case details and arrange that data
		$case = $this->getCaseDetails($id);
		
		
		$org_id=$this->user->org_id;
		if($this->user->demo==0)
		{
			$data['NextId']=$this->getNextIdAndPreviousId("select *, ca.org_id
									from cases ca 
									left join user u on u.user_id = ca.case_owner_id 
									left join products p on ca.case_product_id = p.product_id
									left join contact co on co.contact_id = ca.contact_id 
									left join company c on ca.company_id = c.company_id  
									where (ca.case_id  > ?) AND (ca.org_id = ? OR  ca.org_id = '0' )  ORDER BY ca.case_id ASC LIMIT 1",$id,"case_id",$org_id);

			$data['PreviousId']=$this->getNextIdAndPreviousId("select *, ca.org_id
									from cases ca 
									left join user u on u.user_id = ca.case_owner_id 
									left join products p on ca.case_product_id = p.product_id
									left join contact co on co.contact_id = ca.contact_id 
									left join company c on ca.company_id = c.company_id  
									where (ca.case_id  < ?) AND (ca.org_id = ? OR  ca.org_id = '0' )  ORDER BY ca.case_id desc LIMIT 1",$id,"case_id",$org_id);
		}
		else
		{
			
				$data['NextId']=$this->getNextIdAndPreviousId("select *, ca.org_id
									from cases ca 
									left join user u on u.user_id = ca.case_owner_id 
									left join products p on ca.case_product_id = p.product_id
									left join contact co on co.contact_id = ca.contact_id 
									left join company c on ca.company_id = c.company_id  
									where (ca.case_id  > ?) AND (ca.org_id = ? )  ORDER BY ca.case_id ASC LIMIT 1",$id,"case_id",$org_id);
		
	$data['PreviousId']=$this->getNextIdAndPreviousId("select *, ca.org_id
									from cases ca 
									left join user u on u.user_id = ca.case_owner_id 
									left join products p on ca.case_product_id = p.product_id
									left join contact co on co.contact_id = ca.contact_id 
									left join company c on ca.company_id = c.company_id  
									where (ca.case_id  < ?) AND (ca.org_id = ? )  ORDER BY ca.case_id desc LIMIT 1",$id,"case_id",$org_id);
		}
		
		$this->orgAccessCheck($case->org_id);	// Organization access check

		// Arrange users for view manipulation
		$data['aUsers'] = array();
		foreach ($data['users'] as $user) {
			$data['aUsers'][$user->user_id] = $user;
		}

		// Contact name
		$case->contact_name = ucfirst($case->first_name);
		$case->contact_name .= ($case->contact_name ? ' ' : '') . ucfirst($case->last_name);

		// Created Before
		$startDate = new DateTime(date('Y-m-d', strtotime($case->case_create_date)));
		$endDate = new DateTime(date('Y-m-d'));
		$diff = $startDate->diff($endDate);
		$case->created_before = $this->formatDays($diff->days);

		// Assignee info
		if ($case->assign_to) {
			$assignee = $this->getUserDetails($case->assign_to);
			$case->assignee = ucfirst($assignee->name);
		}

	//	// Get case status list
//		$data['fields'] = $this->getAllUserFields();
//		$allstatus = array();
//		$count = 0;
//		foreach ($data['fields'] as $field) { 
//			if ($field->case_status) {
//				$allstatus[$count] = $field->case_status;
//				if ($case->status == $field->case_status) $caseStatusIndex = $count;
//				$count++;
//			}
//		}
//		
//		
		
// Get case stages
		$data['fields'] = $this->getAllUserFields();
		$stages = array();
		$count = 0;
		foreach ($data['fields'] as $field) { 
			if ($field->case_status) {
				$stages[$field->no] = $field->case_status;
				if ($case->status == $field->case_status) $dealStageIndex = $count;
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
		
		$case->stages =$stages;
		
	
	
 		$data['stage']= $this->getStatusName($case->status,'case_status');
 
		// print_r($data['stage']);

		if ($caseStatusIndex < 3) {
			$case->allstatus = array_slice($allstatus, 0, 5);
		} else if ($caseStatusIndex > (count($allstatus) - 4)) {
			$limitNo = (count($allstatus) < 5) ? count($allstatus) : 5;
			$case->allstatus = array_slice($allstatus, (count($allstatus) - $limitNo));
		} else {
			$case->allstatus = array_slice($allstatus, ($caseStatusIndex-2), ($caseStatusIndex+2));
		}

		// Case notes
		$data['notes'] = $this->getItemNotes('case', $id);

		// Get case tasks and arrange data for datatable construction
		$data['tasks'] = $this->getItemTasks('case', $id);

		// Get case contacts and arrange data for datatable construction
		$data['contacts'] = $this->getCaseContacts($id);

		// Case documents
		$data['docs'] = $this->getItemDocs('case', $id);

		// Case history
		$data['history'] = $this->getItemHistory('case', $id);

		$data['case'] = $case;
		$data['prev_case'] = $this->getPrevCase($id);
		$data['next_case'] = $this->getNextCase($id);
		$data['fields'] = $this->getAllUserFields();
		$data['companies'] = $this->getAllCompanies();	// Get all companies
		$this->load->view('FirstLayoutView', $data);
	}

	public function add($data=array()) {
		$this->bodyClass = 'create-case';
		$this->pageTitle = $this->pageDesc = 'Create Ticket';
		$PrefixSequenc = $this->getPrefixSequenceModule('cases');
		$data['prefixsequence'] = $PrefixSequenc['prefix']."/".$PrefixSequenc['sequence'];
		$data['sequence'] = $PrefixSequenc['sequence'];
		$data['numbering_id']=$PrefixSequenc['numbering_id'];
		$data['content'] = 'CreateCaseView';

		$data['companies'] = $this->getAllCompanies();
		$data['contacts'] = $this->getAllContacts();
		$data['products'] = $this->getAllProducts();
		$data['users'] = $this->getAllUsers();
		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$this->load->view('FirstLayoutView', $data);
	}

	public function submit() {
		
		
		
		
			$numbering_id=$_POST['numbering_id'];
			unset($_POST['numbering_id']);
			$sequence=$_POST['sequence']+1;
			$query = 'UPDATE numbering SET `sequence`= "'.$sequence.'" WHERE numbering_id="'.$numbering_id.'"';
			unset($_POST['sequence']);
			$res = $this->db->query($query);
	
		// Gather form fields
		if($_POST['company_id'])
		{
			unset($_POST['company_id']);
		}
		$data = $formFields = $params = array();
		$currDateTime = $this->getCurrTime();
		$valsStr = '';
		if (isset($_POST['modal_flag'])) {
			$modal_flag = $_POST['modal_flag']; unset($_POST['modal_flag']);
			
			$associate_to = $_POST['associate_to']; unset($_POST['associate_to']);
			$associate_id = $_POST['associate_id']; unset($_POST['associate_id']);
			
		}
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal);
			if($fieldName!="")
			{
			$valsStr .= ($valsStr ? ', ' : '') . '?';
			}
			
		}
		
		
		
		
		
		
		
		$formFields['case_owner_id'] = $this->user->user_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['case_report_to_id'] = $this->user->report_to_id ? $this->user->report_to_id : $this->user->user_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		
		

		//unset($formFields['no_product_id']);

		if ($formFields['contact_id']) {
			$contact = $this->getContactDetails($formFields['contact_id']);
			$formFields['company_id'] = $contact->company_id;
			$valsStr .= ($valsStr ? ', ' : '') . '?';
		}

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Create a case with given details
		$formFields['org_id'] = $this->user->org_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['case_create_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['case_modify_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$caseId = $this->getTableAutoID('cases');
		$query = 'insert into cases (' . implode(', ', array_keys($formFields)) . ') values (' . $valsStr . ')';
		
		//print_r(array_values($formFields));
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			// Log activity
			$activity = array('action' => 'CREATE', 'type' => 'case', 'id' => $caseId);
			$this->logUserActivity($activity);

			// Clear session info
			unset($_SESSION['caseContact']);
			unset($_SESSION['caseContactName']);
			
			if (!$modal_flag) {

			redirect(base_url() . 'cases/details/' . $caseId, 'location', 301);
			}
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while creating this case, please try again after sometime.';
		}
		
		if ($modal_flag) {
			$data['case'][0] = true;
			$data['entityType'] = $associate_to;
			$data['entityId'] = $associate_id;
			$data['entitySourcePath'] = ($associate_to == 'contact') ? 'contacts/getcontactcasesjson' : 'companies/getcompanycasesjson';
			$this->load->view('DataTableView', array('cols' => array_values($this->caseTableCols), 'mobileCols' => array(0, 1, 4), 'sourcePath' => "contacts/getcontactcasesjson?id=$associate_id"));
			
			
		//	$this->load->view('DataTableView', $data);
		} else {
			$this->add($data);
		}
		

	}

	public function edit($id, $data = array()) {
		$this->bodyClass = 'edit-case';
		$this->pageTitle = $this->pageDesc = 'Edit Ticket';
		$data['content'] = 'EditCaseView';

		$case = $this->getCaseDetails($id);
		$case->contact_name = ucfirst($case->first_name);
		$case->contact_name .= ($case->contact_name ? ' ' : '') . ucfirst($case->last_name);

		$data['case'] = $case;
		$data['users'] = $this->getAllUsers();
		$data['companies'] = $this->getAllCompanies();
		$data['contacts'] = $this->getAllContacts();
		$data['products'] = $this->getAllProducts();
		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$this->load->view('FirstLayoutView', $data);
	}

	public function update($id) {
		if($_POST['company_id'])
		{
			unset($_POST['company_id']);
		}
		// Gather form fields
		$data = $formFields = array();
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal);
		}
		
		if ($formFields['contact_id']) {
			$contact = $this->getContactDetails($formFields['contact_id']);
			$formFields['company_id'] = $contact->company_id;
		}

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Update case with given details
		$formFields['case_modify_date'] = $this->getCurrTime();
		$query = 'update cases set ' . implode(' = ?, ', array_keys($formFields)) . ' = ? where case_id = ?';
		$formFields['case_id'] = $id;
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			// Log activity
			$activity = array('action' => 'UPDATE', 'type' => 'case', 'id' => $id);
			$this->logUserActivity($activity);
			redirect(base_url() . "cases/details/$id", 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while updating this case, please try again after sometime.';
		}

		$this->edit($data);
	}

	// Change case status
	public function changestatus() {
		// Update contact
		$updateQuery = 'update cases set status = ? where case_id = ?';
		$params = array($_REQUEST['status'], $_REQUEST['id']);
		$updateRes = $this->db->query($updateQuery, $params);

		$res = array();
		if ($updateRes) {
			$res['success'] = true;
			$res['message'] = 'Case status changed successfully.';
			
			// Log activity
			$activity = array('action' => 'UPDATE', 'type' => 'case', 'id' => $_REQUEST['id'], 'info' => json_encode(array('subaction' => 'change status')));
			$this->logUserActivity($activity);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while changing the case status.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Reassign ticket
	public function reassign() {
		$caseId = $_POST['case_id'];
		$reassignId = $_POST['assign_to'];

		// Reassign case
		$caseQuery = 'update cases set assign_to = ?, case_modify_date = ? where case_id = ?';
		$caseRes = $this->db->query($caseQuery, array($reassignId, $this->getCurrTime(), $caseId));

		$res = array();
		if ($caseRes) {
			$res['success'] = true;
			$res['message'] = 'Successfully reassigned this ticket.';

			// Log activity
			$case = $this->getCaseDetails($caseId);
			$info = array('from' => $case->assign_to, 'to' => $reassignId);
			$activity = array('action' => 'REASSIGN', 'type' => 'case', 'id' => $caseId, 'info' => json_encode($info));
			$this->logUserActivity($activity);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while reassigninig this case, please try again after sometime.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Delete a ticket
	public function delete() {
		$caseId = $_POST['case_id'];
		if (!$caseId) return;

		// Delete case
		$deleteQuery = 'delete from cases where case_id = ?';
		$deleteRes = $this->db->query($deleteQuery, array($caseId));

		$res = array();
		if ($deleteRes) {
			$res['success'] = true;
			$res['redirectUrl'] = base_url() . "cases";
			$res['message'] = 'Successfully deleted this case and dependencies.';

			// Log activity
			$activity = array('action' => 'DELETE', 'type' => 'case', 'id' => $caseId);
			$this->logUserActivity($activity);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while deleting this case or dependences, please try again after sometime.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Add a new task for this case
	public function addTask($id) {
		$_SESSION['taskInfo']['associate_to'] = 'case';
		$_SESSION['taskInfo']['associate_id'] = $id;
		$_SESSION['sourceUrl'] = base_url() . "cases/details/$id";
		redirect(base_url(). 'tasks/add', 'location', 301);
	}

	// Add a new contact for this case
	public function addContact($id) {
		$_SESSION['contactInfo']['associate_to'] = 'case';
		$_SESSION['contactInfo']['associate_id'] = $id;
		$_SESSION['sourceUrl'] = base_url() . "cases/details/$id";
		redirect(base_url(). 'contacts/add', 'location', 301);
	}

	// Get cases
	public function getCases($filters = '') {
		if (!$filters) $filters = $this->filters;

		$query = "select SQL_CALC_FOUND_ROWS *, if (u.name, u.name, u.username) as assignee,ca.severity as severity,uf.case_status as status_name,ca.priority as priority,ca.status as status, c.company_name as company
						  from cases ca 
						  inner join user u on u.user_id = ca.assign_to
						  left join company c on ca.company_id = c.company_id
						  left join user_fields uf on ca.status =uf.no ";

		// Role checkup
		$whereCond = '';
		$params = array();
		if($this->user->demo==0)
		{
		/*if ($this->isManager) {
			$whereCond .= ' where (ca.org_id = ? or ca.org_id = ?) and (ca.case_report_to_id = ? or ca.case_owner_id = ? or ca.assign_to = ? or u.report_to_id = ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ' where (ca.org_id = ? or ca.org_id = ?) and (ca.case_owner_id = ? or ca.assign_to = ? )';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' where (ca.org_id = ? or ca.org_id = ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
		}*/
		$params = array();
		$whereCond .= 'where (ca.org_id = ? or ca.org_id = ?)';
		$params[] = $this->user->org_id;
		$params[] = "0";
		
		}
		else
		{
			/*if ($this->isManager) {
			$whereCond .= ' where ca.org_id = ? and (ca.case_report_to_id = ? or ca.case_owner_id = ? or ca.assign_to = ? or u.report_to_id = ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ' where ca.org_id = ? and (ca.case_owner_id = ? or ca.assign_to = ? )';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' where ca.org_id = ?';
			$params[] = $this->user->org_id;
		}*/
		$params = array();
		$whereCond .= ' where ca.org_id = ?';
		$params[] = $this->user->org_id;
		}
		$countWhereCond = $whereCond;
		$countParams = $params;

		// Apply filters
		if ($filters) {
			$filtersCondition = '';
			$ik=0;
			$_SESSION['filters']="cases";
			foreach ($filters as $fkey => $fvalue) {
				$ik++;
					$_SESSION['filters_key'][$ik]=$fkey ;
					$_SESSION['filters_fvalue'][$ik]=$fvalue ;
					
				if ($fvalue && ($fvalue != 'All')) {
					if ($fkey == 'case_create_date') {
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
					} else if($fkey =='first')
					{
								 $filtersCondition .= ($filtersCondition ? ' and ' : '') . "( case_title LIKE '%".$fvalue."%' or company_name LIKE '%".$fvalue."%' or case_no LIKE '%".$fvalue."%')" ;
						
					} else {
						$filtersCondition .= ($filtersCondition ? ' and ' : '') . ($fkey == 'company' ? 'c.company_name' : $fkey) . " = ?";
						$params[] = $fvalue;
					}
				}
			}
			if ($filtersCondition) $whereCond .= ($whereCond ? ' and ' : ' where ') . $filtersCondition;
		}

		// ======== Datatable adjustments ========
		$aColumns = array_keys($this->caseTableCols);
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
		$_SESSION['cases_export']=$whereCond;
		$_SESSION['cases_export_params']=$params;

		$cases = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->case_create_date = $this->convertDateTime($row->case_create_date);
				$cases[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countQuery = 'SELECT COUNT(`case_id`) as totalRows FROM cases ca  inner join user u on u.user_id = ca.assign_to' . $countWhereCond;
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $cases;
	}

	// Get a case details
	public function getCaseDetails($id) {
		$resObj = $this->db->query("select *, ca.org_id ,ca.assign_to as assign_to ,ca.severity as severity,uf.case_status as status_name,ca.priority as priority
									from cases ca 
									left join user u on u.user_id = ca.case_owner_id 
									left join products p on ca.case_product_id = p.product_id
									left join contact co on co.contact_id = ca.contact_id 
									left join company c on ca.company_id = c.company_id  
									left join user_fields uf on ca.status =uf.no
									where case_id = ?", array($id));
		return $resObj->row();
	}

	// Get next case
	public function getNextCase($id) {
		$resObj = $this->db->query("select * from cases where case_id > ? order by case_id limit 1", array($id));
		return $resObj->row();
	}

	// Get previous case
	public function getPrevCase($id) {
		$resObj = $this->db->query("select * from cases where case_id < ? order by case_id desc limit 1", array($id));
		return $resObj->row();
	}

	// Get case items
	public function getCaseContacts($id) {
		$query = "select * from contact where associate_to = ? and associate_id = ?";
		$resObj = $this->db->query($query, array('case', $id));

		$items = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$contact_name = ucfirst($row->first_name);
				$contact_name .= ($contact_name ? ' ' : '') . ucfirst($row->last_name);
				$row->contact_name = $contact_name;
				$items[] = $row;
			}
		}
		return $items;
	}

	// Get company contacts
	public function getCompanyContactsJSON() {
		$resObj = $this->db->query("select * from contact where company_id = ?", $_GET['company_id']);
		$contacts = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->contact_name = $row->first_name;
				$row->contact_name .= ($row->contact_name ? ' ' : '') . $row->last_name;
				$contacts[] = $row;
			}
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($contacts));
	}
}