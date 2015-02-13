<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class companies extends BCZ_Controller {

	private $filters = '';

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'Accounts';
		$data['cols'] = array_values($this->companyTableCols);
		$data['mobileCols'] = array(0, 1, 4);
		
		if($_SESSION['filters']!="companies")
		{
			unset($_SESSION['filters_key']);
			unset($_SESSION['filters_fvalue']);
		}
					
		if (isset($_REQUEST['filters'])) {
			$data['sourcePath'] = 'companies/getcompaniesjson';
			$this->load->view('DataTableView', $data);
		} else {
			$data['filters'] = array('owner' => array('col' => 'assign_to', 'alias' => 'owner', 'user_status' => 'user_status'), 															 'Customer Type' => array('col' => 'com_cust_type', 'alias' => 'customer_type'),'created' => array('col' => 'company_create_date', 'alias' => 'company_create_date', 'type' => 'date'),'Modified' => array('col' => 'company_modify_date', 'alias' => 'company_modify_date', 'type' => 'date'));

			$data['companies'] = $this->getCompanies();
			$data['content'] = 'CompaniesView';
			$this->load->view('FirstLayoutView', $data);
		}
	}

	// Get companies json for datatable
	public function getcompaniesjson() {
		// Check for filters
		if (isset($_GET['filters'])) $this->filters = (array)json_decode($_GET['filters']);

		// Get companies and arrange data for datatable
		$companies = $this->getCompanies();
		$output = $this->constructDTOutput($companies, array_keys($this->companyTableCols), 'company_id', 'companies/details', 1);

		echo json_encode($output);
	}

	public function getcompanycasesjson() {
		// Get company cases and arrange data for datatable
		$cases = $this->getCompanyItems('cases', $_GET['id']);
		$output = $this->constructDTOutput($cases, array_keys($this->caseTableCols), 'case_id', 'cases/details', 1);
		
		echo json_encode($output);
	}

	public function getcompanydealsjson() {
		// Get company deals and arrange data for datatable
		$deals = $this->getCompanyItems('deal', $_GET['id'], 'deal');
		$output = $this->constructDTOutput($deals, array_keys($this->dealTableCols), 'deal_id', 'deals/details', 1);
		
		echo json_encode($output);
	}

	public function getcompanycontactsjson() {
		// Get company contacts and arrange data for datatable
		
		$contacts = $this->getCompanyItems('contact', $_GET['id']);
	//print_r($contacts);
		$output = $this->constructDTOutput($contacts, array_keys($this->contactTableCols), 'contact_id', 'contacts/details', 1);

		echo json_encode($output);
	}

	// Get suggestions
	public function suggestions() {
		$companies = $this->getAllCompanies();
		$comps = array();
		$index = 0;
		foreach ($companies as $company) {
			$comps[$index]["key"] = $company->company_id;
			$comps[$index]["value"] = $company->company_name;
			$index++;
		}
		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($comps));
	}

	// Export data
	public function export() {
	//$companies = $this->getAllCompanies();
		$companies = $this->getAllCompaniesExport($_SESSION['companies_export'],$_SESSION['companies_export1']);
		
		$this->exportData($companies, 'companies_data.xls');
	}
	
	// Import data
	public function import() {
		$params[] = $this->user->org_id;
		$query="DELETE FROM `company_tmp` WHERE org_id = ?";
			
		$resObj = $this->db->query($query, $params);
		
	$this->bodyClass ='CompaniesImportView';
	$this->pageTitle = $this->pageDesc = 'Companies Import View';
	$data['content'] = 'CompaniesImportView';
	$this->load->view('FirstLayoutView', $data);
	}
	
	public function importcsv() {
		
		
		$this->bodyClass ='CompaniesMapping';
		 
		$this->pageTitle = $this->pageDesc = 'Companies Import Mapping';
		$companies_tmp_TableCols="`org_id`, `company_name`,`phone`, `bill_address1`, `bill_city`, `bill_state`, `bill_postal_code`, `bill_country`, `website`, `company_create_date`, `company_modify_date`";
		$TableCols=$companies_tmp_TableCols;
		$table = 'company_tmp';
		$count='11';
		$data = array();
		$validationFailed = false;
		if ($_FILES['import_file']['type'] != 'application/vnd.ms-excel') {
			$data['success'] = false;
			$data['message'] = "Please upload a valid file";
		}

		if (!$validationFailed) {
			$res = $this->importDatas($_FILES['import_file']['tmp_name'], $table,$TableCols,$count);
			$data['success'] = true;
			$data['message'] = "Import operation is successfull.";
			redirect(base_url() . 'companies/mapping/' , 'location', 301);
		} 
	
		
	else {
			$data['messages']['error']['fail'] = 'Something went wrong while creating this companies, please try again after sometime.';
		}

		
		 
		//$this->importData('leads_data.xls', 'lead');
	}
	
	
	public function mapping() {
		
		$params = array();
		$this->bodyClass = "CompaniesImportMappingView";
		$this->pageTitle = $this->pageDesc = 'Companies Import Mapping View';
		$data['users'] = $this->getAllUsers();	
		$data['fields'] = $this->getAllUserFields();	// Get all user fields	
		$data['content'] = 'CompaniesImportMappingView';
		$query = "select *  from company_tmp where org_id = ? ORDER BY company_id ASC limit 1";
		$params[] = $this->user->org_id;
		$resObj = $this->db->query($query, $params);
		$mapping = array();
		if ($resObj->num_rows()) {
				$companies_tmp_TableCols="`org_id`, `company_name`,`phone`, `bill_address1`, `bill_city`, `bill_state`, `bill_postal_code`, `bill_country`, `website`, `company_create_date`, `company_modify_date`";
			foreach ($resObj->result() as $row) {
				$data['company_table_data']=array (array ('name' => 'Company Name','col' => 'company_name','value'=>$row->company_name),array ('name' => 'Phone','col' => 'phone','value'=>$row->phone),array ('name' => 'Address','col' => 'bill_address','value'=>$row->bill_address),array ('name' => 'City','col' => 'bill_city','value'=>$row->bill_city),array ('name' => 'State','col' => 'bill_state','value'=>$row->bill_state),array ('name' => 'Postal Code','col' => 'bill_postal_code','value'=>$row->bill_postal_code),array ('name' => 'Country','col' => 'bill_country','value'=>$row->bill_country),array ('name' => 'Website','col' => 'website','value'=>$row->website));
			}
		}
		$this->load->view('FirstLayoutView', $data);
	}
	
	public function importmapping() {
		
		//echo print_r(array_values($_POST['table']));
		
		
		$report_to_id = $this->getUserReporter($_POST['owner_id']);	// Get reporter id
		$owner_id = $_POST['owner_id'];
		 unset( $_POST['owner_id']);
		$query = 'update company_tmp set owner_id= ? , report_to_id= ? ,associate_to= ? , assign_to= ? where org_id = ? ' ;

		$res = $this->db->query($query, array($owner_id,$report_to_id,"company",$owner_id,$this->user->org_id));

$valsStr = '';
		$formFields = $params = array();
		foreach ($_POST as $fieldName => $fieldVal) {
			if($fieldVal!="")
			{
				$formFields[$fieldName] = trim($fieldVal);
				$table_data .= ($fieldName ? ', ' : '') . $fieldName;
				$valsStr .= (trim($fieldVal) ? ', ' : '') . trim($fieldVal);
			}
			
		}

		
		
		$query="INSERT INTO company (owner_id,report_to_id,org_id,company_create_date,company_modify_date,associate_to,assign_to".$valsStr.") (SELECT owner_id,report_to_id,org_id,company_create_date,company_modify_date,associate_to,assign_to".$table_data." from company_tmp WHERE org_id = ?) ";
		
		$params[] = $this->user->org_id;
		$resObj = $this->db->query($query, $params);
		
		$query="DELETE FROM `company_tmp` WHERE org_id = ?";
			
		$resObj = $this->db->query($query, $params);
		
		if($resObj)
		{
		redirect(base_url() . 'companies/' , 'location', 301);
		}
	}
	
	

	public function details($id) {
		$this->bodyClass = 'company-details';
		$this->pageTitle = $this->pageDesc = 'Company Details';
		$data['content'] = 'CompanyDetailsView';
		$data['users'] = $this->getAllUsers();

		// Get company details and arrange that data
		$company = $this->getCompanyDetails($id);
		
		
			
		$org_id=$this->user->org_id;
		if($this->user->demo==0)
		{
			
			
			$data['NextId']=$this->getNextIdAndPreviousId("select *, c.org_id from company c join user u on c.owner_id = u.user_id where (c.company_id > ?) AND (c.org_id = ? OR  c.org_id = '0' )  ORDER BY c.company_id ASC LIMIT 1",$id,"company_id",$org_id);

			$data['PreviousId']=$this->getNextIdAndPreviousId("select *, c.org_id from company c join user u on c.owner_id = u.user_id where (c.company_id < ?) AND (c.org_id = ? OR  c.org_id = '0' )  ORDER BY c.company_id desc LIMIT 1",$id,"company_id",$org_id);
		}
		else
		{
			
				$data['NextId']=$this->getNextIdAndPreviousId("select *, c.org_id from company c join user u on c.owner_id = u.user_id where (c.company_id > ?) AND (c.org_id = ? )  ORDER BY c.company_id ASC LIMIT 1",$id,"company_id",$org_id);
		
	$data['PreviousId']=$this->getNextIdAndPreviousId("select *, c.org_id from company c join user u on c.owner_id = u.user_id where (c.company_id < ?) AND (c.org_id = ?)  ORDER BY c.company_id desc LIMIT 1",$id,"company_id",$org_id);
		}
		
		
		
		$company->com_cust_type= $this->getStatusName($company->com_cust_type,'customer_type');
		
		$this->orgAccessCheck($company->org_id);	// Organization access check

		// Arrange users for view manipulation
		$data['aUsers'] = array();
		foreach ($data['users'] as $user) {
			$data['aUsers'][$user->user_id] = $user;
		}

		// Created Before
		$startDate = new DateTime(date('Y-m-d', strtotime($company->company_create_date)));
		$endDate = new DateTime(date('Y-m-d'));
		$diff = $startDate->diff($endDate);
		$company->created_before = $this->formatDays($diff->days);
		
		if($company->assign_to!="0")
		{
			
					$data['company_assign_to'] = $this->assign_to1($company->assign_to);
		}
		elseif($company->report_to_id!="0")
		{
				$data['company_assign_to'] = $this->assign_to1($company->report_to_id);
		}
		else
		{
				$data['company_assign_to'] = $this->assign_to1($company->owner_id);
		}
				
				

		// Company notes
		$data['notes'] = $this->getItemNotes('company', $id);

		// Get company tasks and arrange data for datatable construction
		$data['tasks'] = $this->getItemTasks('company', $id);

		// Get company deals and arrange data for datatable construction
		$data['deals'] = $this->getCompanyItems('deal', $id, 'deal');

		// Get company contacts and arrange data for datatable construction
		$data['contacts'] = $this->getCompanyItems('contact', $id);

		// Get company cases and arrange data for datatable construction
		$data['cases'] = $this->getCompanyItems('cases', $id);

		// Get company documents
		$data['docs'] = $this->getItemDocs('company', $id);

		// Get company quotes
		$data['quotes'] = $this->getCompanyItems('quote', $id);

		// Lead history
		$data['history'] = $this->getItemHistory('company', $id);

		$data['company'] = $company;
		$data['add_deal_company_id'] = $company->company_id;
		$data['prev_company'] = $this->getPrevCompany($id);
		$data['next_company'] = $this->getNextCompany($id);
		$data['fields'] = $this->getAllUserFields();
		$data['companies'] = $this->getAllCompanies();	// Get all companies
		$data['contacts'] = $this->getOneContacts($id);		// Get contacts
		$this->load->view('FirstLayoutView', $data);
	}

	public function add($data=array()) {
		$this->bodyClass = 'create-company';
		$this->pageTitle = $this->pageDesc = 'Create Company';
		$data['content'] = 'CreateCompanyView';

		$data['users'] = $this->getAllUsers();
		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$this->load->view('FirstLayoutView', $data);
	}

	public function submit() {
		
		if (isset($_POST['modal_flag'])) {
			$modal_flag = $_POST['modal_flag'];
			 $_POST['assign_to']=$this->user->user_id;
			unset($_POST['modal_flag']);
		}
		
		$company_name=$_POST['company_name'];
		if (isset($_POST['copy_billing_addr'])) unset($_POST['copy_billing_addr']);

		// Gather form fields
		$data = $formFields = $params = array();
		$currDateTime = $this->getCurrTime();
		$valsStr = '';
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal);//($fieldName == 'com_application') ? implode(',', $fieldVal) : trim($fieldVal);
			$valsStr .= ($valsStr ? ', ' : '') . '?';
		}
		//$formFields['com_description'] = wordwrap($formFields['com_description'], 20, "<br />\n");	// Breaking description text
		
		
		$formFields['associate_to'] = 'company';
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['owner_id'] = $this->user->user_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		
		$formFields['report_to_id'] = $this->getUserReporter($_POST['assign_to']);	// Get reporter id
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['org_id'] = $this->user->org_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['company_create_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['company_modify_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';

		// unset($formFields['copy_billing_addr']);
		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Create a company with given details
		$companyId = $this->getTableAutoID('company');
		$query = 'insert into company (' . implode(', ', array_keys($formFields)) . ') values (' . $valsStr . ')';
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			// Log activity
			$info = array('associate_to' => $formFields['associate_to'], 'associate_id' => $formFields['associate_id']);
			$activity = array('action' => 'CREATE', 'type' => 'company', 'id' => $companyId, 'info' => json_encode($info));
			$this->logUserActivity($activity);
			
			if (isset($_SESSION['sourceUrl'])) {
				$sourceUrl = $_SESSION['sourceUrl'];
				unset($_SESSION['sourceUrl']);
				
				
				if ($modal_flag) {
				
				$res['success'] = true;
				$res['opp_company_name'] = $company_name;
				$res['deal_company_id'] = $companyId;
			
			$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
				
			}
			
			
				if ($sourceUrl == base_url().'deals/add') $_SESSION['dealCompany'] = $companyId;
				else if ($sourceUrl == base_url().'contacts/add') $_SESSION['contactCompany'] = $companyId;

				redirect($sourceUrl, 'location', 301);
			}
			redirect(base_url() . 'companies/details/' . $companyId, 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while creating this company, please try again after sometime.';
		}
		
			if ($modal_flag) {
				
				$res['success'] = true;
				$res['opp_company_name'] = $company_name;
				$res['deal_company_id'] = $companyId;

			
					$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
				
			}
			else
			{
				
				$this->add($data);
			}
		

	
	}




	public function modalsubmit() {
		
		if (isset($_POST['modal_flag'])) {
			$modal_flag = $_POST['modal_flag'];
			 $_POST['assign_to']=$this->user->user_id;
			unset($_POST['modal_flag']);
		}
		
		$company_name=$_POST['company_name'];
		if (isset($_POST['copy_billing_addr'])) unset($_POST['copy_billing_addr']);

		// Gather form fields
		$data = $formFields = $params = array();
		$currDateTime = $this->getCurrTime();
		$valsStr = '';
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal);//($fieldName == 'com_application') ? implode(',', $fieldVal) : trim($fieldVal);
			$valsStr .= ($valsStr ? ', ' : '') . '?';
		}
		//$formFields['com_description'] = wordwrap($formFields['com_description'], 20, "<br />\n");	// Breaking description text
		
		
		$formFields['associate_to'] = 'company';
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['owner_id'] = $this->user->user_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		
		$formFields['report_to_id'] = $this->getUserReporter($_POST['assign_to']);	// Get reporter id
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['org_id'] = $this->user->org_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['company_create_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['company_modify_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';

		// unset($formFields['copy_billing_addr']);
		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Create a company with given details
		$companyId = $this->getTableAutoID('company');
		$query = 'insert into company (' . implode(', ', array_keys($formFields)) . ') values (' . $valsStr . ')';
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			// Log activity
			$info = array('associate_to' => $formFields['associate_to'], 'associate_id' => $formFields['associate_id']);
			$activity = array('action' => 'CREATE', 'type' => 'company', 'id' => $companyId, 'info' => json_encode($info));
			$this->logUserActivity($activity);
			
		}
			$this->output
	    		->set_content_type('application/json')
	    		->set_output(json_encode(array('opp_company_name' => $company_name, 'deal_company_id' => $companyId)));
				
				
				
		
			
		

	
	}
	
	
	public function edit($id, $data = array()) {
		$this->bodyClass = 'edit-company';
		$this->pageTitle = $this->pageDesc = 'Edit Company';
		$data['content'] = 'EditCompanyView';

		$company = $this->getCompanyDetails($id);
		//$company->com_application = $company->com_application ? explode(',', $company->com_application) : array();
		$data['company'] = $company;

		$data['users'] = $this->getAllUsers();
		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$this->load->view('FirstLayoutView', $data);
	}

	public function update($id) {
		if (isset($_POST['copy_billing_addr'])) unset($_POST['copy_billing_addr']);
		
		

		// Gather form fields
		$data = $formFields = array();
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal); //($fieldName == 'com_application') ? implode(',', $fieldVal) : trim($fieldVal);
		}
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		
		 $formFields['report_to_id'] = $this->getUserReporter($_POST['assign_to']);
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		
		 $formFields['owner_id'] =$_POST['assign_to'];
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		

			// Get reporter id

		if (!$formFields['is_supplier']) {
			$formFields['is_supplier'] = 0;
		}

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Update company with given details
		$formFields['company_modify_date'] = $this->getCurrTime();
		$query = 'update company set ' . implode(' = ?, ', array_keys($formFields)) . ' = ? where company_id = ?';
		$formFields['company_id'] = $id;
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			// Log activity
			$activity = array('action' => 'UPDATE', 'type' => 'company', 'id' => $id);
			$this->logUserActivity($activity);
			redirect(base_url() . "companies/details/$id", 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while updating this company, please try again after sometime.';
		}

		$this->edit($data);
	}

	// Add a new task for this company
	public function addTask($id) {
		$_SESSION['taskInfo']['associate_to'] = 'company';
		$_SESSION['taskInfo']['associate_id'] = $id;
		$_SESSION['sourceUrl'] = base_url() . "companies/details/$id";
		redirect(base_url(). 'tasks/add', 'location', 301);
	}

	// Add a new deal for this company
	public function addDeal($id) {
		$_SESSION['dealCompany'] = $id;
		$_SESSION['sourceUrl'] = base_url() . "companies/details/$id";
		redirect(base_url(). 'deals/add', 'location', 301);
	}

	// Add a new contact for this company
	public function addContact($id) {
		$_SESSION['contactCompany'] = $id;
		$_SESSION['sourceUrl'] = base_url() . "companies/details/$id";
		redirect(base_url(). 'contacts/add', 'location', 301);
	}

	// Get companies
	public function getCompanies($filters = '') {
		if (!$filters){ $filters = $this->filters; 
		
		}else {}

		$query = "select SQL_CALC_FOUND_ROWS *, if (u.name, u.name, u.name) as owner , if(uf.customer_type,uf.customer_type,uf.customer_type) as customer_type,if(c.com_cust_type,c.com_cust_type,c.com_cust_type) as com_cust_type ,u.user_status as user_status
						  from company c 
						  left join user_fields uf on c.com_cust_type =uf.no
						  left join user u on c.assign_to = u.user_id";
	  $whereCond = ' where c.associate_to != ?';
		$params = array('lead');

		// Role checkup
		
		if($this->user->demo==0)
		{
			
		if ($this->isManager) {
			$whereCond .= ' and (c.org_id = ? or c.org_id = ? ) and (c.report_to_id = ? or c.owner_id = ? or c.report_to_id = ? or c.assign_to = ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {

			$whereCond .= ' and (c.org_id = ? or c.org_id = ? ) and (c.assign_to = ? or c.owner_id = ?) ';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;

		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' and (c.org_id = ? or c.org_id = ? )';
			$params[] = $this->user->org_id;
			$params[] = "0";
		}
		}
		else
		{
			if ($this->isManager) {
			$whereCond .= ' and c.org_id = ? and (c.report_to_id = ? or c.owner_id = ? or c.report_to_id = ? or c.assign_to = ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {

			$whereCond .= ' and c.org_id = ? and (c.assign_to = ? or c.owner_id = ?) ';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;

		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' and c.org_id = ?';
			$params[] = $this->user->org_id;
		}
		}
		$countWhereCond = $whereCond;
		$countParams = $params;

		// Apply filters
		if ($filters) {
			$filtersCondition = '';
			$ik=0;
			$_SESSION['filters']="companies";
			foreach ($filters as $fkey => $fvalue) {
					$ik++;
					$_SESSION['filters_key'][$ik]=$fkey ;
					$_SESSION['filters_fvalue'][$ik]=$fvalue ;
					
					
					
				if ($fvalue && ($fvalue != 'All')) {
					
					if (($fkey == 'company_create_date') || ($fkey == 'company_modify_date')) {
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
						
						
						 $filtersCondition .= ($filtersCondition ? ' and ' : '') . "(c.bill_address LIKE '%".$fvalue."%' OR c.ship_address LIKE '%".$fvalue."%' OR c.company_name LIKE '%".$fvalue."%' OR c.bill_city LIKE '%".$fvalue."%' or c.bill_state LIKE '%".$fvalue."%' or c.ship_city LIKE '%".$fvalue."%' or c.ship_state LIKE '%".$fvalue."%') " ;
						
					}
					else {
						$filtersCondition .= ($filtersCondition ? ' and ' : '') . "$fkey = ?";
						$params[] = $fvalue;
					}
				}
			}
			if ($filtersCondition) $whereCond .= ($whereCond ? ' and ' : ' where ') . $filtersCondition;
		}
		else
		{
		$_SESSION['companies_export'] = "where org_id=?";
		$_SESSION['companies_export1'] = $this->user->org_id; 
		}

		// ======== Datatable adjustments ========
		$aColumns = array_keys($this->companyTableCols);
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
		$_SESSION['companies_export'] = $whereCond;
		$_SESSION['companies_export1'] = $params;

		$companies = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
/*				if($row->assign_to!=0)
				{
				$row->company_assign_to = $this->assign_to($row->assign_to);
				}
				else
				{
					 $row->company_assign_to =$row->owner;
				}*/
				$row->company_create_date = $this->convertDateTime($row->company_create_date);
				$companies[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countQuery = 'SELECT COUNT(`company_id`) as totalRows FROM company c ' . $countWhereCond;
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $companies;
	}


public function assign_to($id) {
		$resObj = $this->db->query("select *, c.org_id from user c where user_id = ?", array($id));
		
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$name = $this->name;
				
			}
		}
		
		return $name;
	}
	
	public function assign_to1($id) {
		$resObj = $this->db->query("select * from user c where c.user_id = ?", array($id));
		
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				 $name = $row->name;
				
			}
		}
		
		return $name;
	}
	// Get a company details
	public function getCompanyDetails($id) {
		$resObj = $this->db->query("select *, c.org_id from company c join user u on c.owner_id = u.user_id where c.company_id = ?", array($id));
		return $resObj->row();
	}

	// Get next company
	public function getNextCompany($id) {
		$resObj = $this->db->query("select * from company where company_id > ? order by company_id limit 1", array($id));
		return $resObj->row();
	}

	// Get previous company
	public function getPrevCompany($id) {
		$resObj = $this->db->query("select * from company where company_id < ? order by company_id desc limit 1", array($id));
		return $resObj->row();
	}

	// Get company items
	public function getCompanyItems($type, $id, $colPrefix="") {
		$join = "";
		$extraCols = "";
		switch ($type) {
			case 'cases':
					$userJoinCol = 'assign_to';
					$keyCol = 'case_id';
					$aCols = $this->caseTableCols;
					$extraCols=", cy.company_name as company,uf.case_status as status_nam ,i.priority as priority ,i.severity as severity";
					$join = ' left join company cy on cy.company_id =i.company_id
					left join user_fields uf on i.status =uf.no ';
				break;
			case 'deal':
					$userJoinCol = 'deal_owner_id';
					$keyCol = 'deal_id';
					$extraCols = ", if(uf.stage,uf.stage,uf.stage) as stage_name , if(i.stage,i.stage,i.stage) as stage , if(i.source,i.source,i.source) as  source";
					$join = ' left join user_fields uf on i.stage =uf.no';
					$aCols = $this->dealTableCols;
				break;
			case 'contact':
					$userJoinCol = 'owner_id';
					$keyCol = 'contact_id';
					$extraCols = ", c.phone as cphone, i.phone, trim(concat(i.first_name, ' ', i.last_name)) as contact_name, c.assign_to as assign_to";
					$join = 'left join company c on i.company_id = c.company_id';
					$aCols = $this->contactTableCols;
				break;
				
			case 'quote':
					$userJoinCol = 'quote_owner_id';
					$keyCol = 'quote_id';
					$aCols = $this->quoteTableCols;
				break;		
				case 'quote':
					$userJoinCol = 'quote_owner_id';
					$keyCol = 'quote_id';
					$aCols = $this->quoteTableCols;
				break;			
			default:
				# code...
				break;
		}
		// $userJoinCol = ($type == 'cases') ? 'assign_to' : 'deal_owner_id';
		$query = "select SQL_CALC_FOUND_ROWS *, if (u.name, u.name, u.name) as owner, if (u.name, u.name, u.name) as assignee $extraCols
							from $type i
							$join
							inner join user u on u.user_id = i.$userJoinCol
							where i.".($colPrefix?$colPrefix.'_':'')."company_id = ?";

if($type=="contact")
{
	//echo $query;
}
		// ======== Datatable adjustments ========
		$aColumns = array_keys($aCols); //($type == 'cases') ? array_keys($this->caseTableCols) : array_keys($this->dealTableCols);
		
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

		$query .= " $sOrder $sLimit";
		$resObj = $this->db->query($query, $id);

		$items = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				if ($row->case_create_date) $row->case_create_date = $this->convertDateTime($row->case_create_date);
				if ($row->deal_create_date) $row->deal_create_date = $this->convertDateTime($row->deal_create_date);
				if ($row->exp_close) $row->exp_close = $this->convertDateTime($row->exp_close);
				if ($row->contact_create_date) $row->contact_create_date = $this->convertDateTime($row->contact_create_date);
				if ($row->quote_create_date) $row->quote_create_date = $this->convertDateTime($row->quote_create_date);
				$items[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		// $keyCol = ($type == 'cases') ? 'case_id' : 'deal_id';
		$countRes = $this->db->query("SELECT COUNT(`$keyCol`) as totalRows FROM $type WHERE ".($colPrefix?$colPrefix.'_':'')."company_id = ?", $id);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $items;
	}
}