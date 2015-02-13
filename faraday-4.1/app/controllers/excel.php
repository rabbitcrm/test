<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class excel extends BCZ_Controller {

	private $filters = '';

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'contacts';
		$data['cols'] = array_values($this->contactTableCols);
		$data['mobileCols'] = array(0, 2, 4);

if($_SESSION['filters']!="contacts")
		{
			unset($_SESSION['filters_key']);
			unset($_SESSION['filters_fvalue']);
		}
		
		if (isset($_REQUEST['filters'])) {
			$data['sourcePath'] = 'contacts/getcontactsjson';
			
			$this->load->view('DataTableView', $data);
		}else if (isset($_REQUEST['filters_search'])) {
			
			$data['sourcePath'] = 'contacts/getcontactssearchjson';
			
			$this->load->view('DataTableView', $data);
		} 
		 else {
			$data['filters'] = array('owner' => array('col' => 'assign_to', 'alias' => 'owner'), 'created' => array('col' => 'contact_create_date', 'alias' => 'contact_create_date', 'type' => 'date'),															 'Modified' => array('col' => 'contact_modify_date', 'alias' => 'contact_modify_date', 'type' => 'date'));
			$data['contacts'] = $this->getContacts();
			$data['content'] = 'ContactsView';
			$this->load->view('FirstLayoutView', $data);
		}
	}

	// Get contacts json for datatable
	public function getcontactsjson() {
		// Check for filters
		if (isset($_GET['filters'])) $this->filters = (array)json_decode($_GET['filters']);
		
		if (isset($_REQUEST['filters_search'])) $this->filters_search=json_decode($_REQUEST['filters_search']);
		
		if (isset($_REQUEST['filters_search']))
		{
			 echo "<script>alert('".$_REQUEST['filters_search']."');</script>";
		}

		// Get contacts and arrange data for datatable
		$contacts = $this->getContacts();
		$output = $this->constructDTOutput($contacts, array_keys($this->contactTableCols), 'contact_id', 'contacts/details', 1);

		echo json_encode($output);
	}
	
	
	public function getcontactssearchjson() {
		// Check for filters
		
		if (isset($_REQUEST['filters_search'])) $this->filters_search = (array)json_decode($_REQUEST['filters_search']);

		// Get contacts and arrange data for datatable
		$contacts = $this->getsearchContacts($_REQUEST['filters_search']);
		
		$output = $this->constructDTOutput($contacts, array_keys($this->contactTableCols), 'contact_id', 'contacts/details', 1);

	echo json_encode($output);
	
	
	
	}
	
	
	
	
	

	public function getentitycontactsjson() {
		// Get entity contacts and arrange data for datatable
		$contacts = $this->getItemContacts($_GET['type'], $_GET['id']);
		$output = $this->constructDTOutput($contacts, array_keys($this->contactTableCols), 'contact_id', 'contacts/details', 1);
		
		echo json_encode($output);
	}

	public function getcontactcasesjson() {
		// Get contact cases and arrange data for datatable
		$cases = $this->getContactItems('cases', $_GET['id']);
		$output = $this->constructDTOutput($cases, array_keys($this->caseTableCols), 'case_id', 'cases/details', 1);
		
		echo json_encode($output);
	}

	public function getcontactdealsjson() {
		// Get contact deals and arrange data for datatable
		$deals = $this->getContactItems('deal', $_GET['id'], 'deal');
		$output = $this->constructDTOutput($deals, array_keys($this->dealTableCols), 'deal_id', 'deals/details', 1);
		
		echo json_encode($output);
	}

	// Export data
	public function export() {
		$contacts = $this->getAllContacts();
		$contacts = $this->getAllContactsExport($_SESSION['contacts_export'],$_SESSION['contacts_export_params']);
		$this->exportData($contacts, 'contacts_data.xls');
	}
	
		// Export data
	public function export1() {
		$contacts = $this->getAllContacts();
		$contacts = $this->getAllContactsExport1($_SESSION['contacts_export'],$_SESSION['contacts_export_params']);
		$this->exportData($contacts, 'contacts_data.xls');
	}
	
	// Import data
	public function import() {
		
		$params[] = $this->user->org_id;
		$query="DELETE FROM `contact_tmp` WHERE org_id = ?";
			
		$resObj = $this->db->query($query, $params);
		
		
	$this->bodyClass ='ContactsImportView';
	$this->pageTitle = $this->pageDesc = 'Companies Import View';
	$data['content'] = 'ContactsImportView';
	$this->load->view('FirstLayoutView', $data);
	}
	
	public function importcsv() {
		
		
		$this->bodyClass ='ContactssMapping';
		 
		$this->pageTitle = $this->pageDesc = 'Contacts Import Mapping';
		$contacts_tmp_TableCols="`org_id`, `title`,`first_name`, `last_name`, `mobile`, `phone`, `email`, `address`, `city`, 				`state`,`postal_code`,`country`,`company_name`,`contact_create_date`, `contact_modify_date`";
		$TableCols=$contacts_tmp_TableCols;
		$table = 'contact_tmp';
		$count='15';
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
			redirect(base_url() . 'contacts/mapping/' , 'location', 301);
		} 
	
		
	else {
			$data['messages']['error']['fail'] = 'Something went wrong while creating this contacts, please try again after sometime.';
		}

		
		 
		//$this->importData('leads_data.xls', 'lead');
	}
	
	
	public function mapping() {
		
		
		$params = array();
		$this->bodyClass = "ContactsImportMappingView";
		$this->pageTitle = $this->pageDesc = 'Contacts Import Mapping View';
		$data['users'] = $this->getAllUsers();	
		$data['fields'] = $this->getAllUserFields();	// Get all user fields	
		$data['content'] = 'ContactsImportMappingView';
		$query = "select *  from contact_tmp where org_id = ? ORDER BY company_id ASC limit 1";
		$params[] = $this->user->org_id;
		$resObj = $this->db->query($query, $params);
		$mapping = array();
		if ($resObj->num_rows()) {
			
			foreach ($resObj->result() as $row) {
				$data['contact_table_data']=array (array ('name' => 'Title','col' => 'title','value'=>$row->title),array ('name' => 'First Name','col' => 'first_name','value'=>$row->first_name),array ('name' => 'Last Name','col' => 'last_name','value'=>$row->last_name),array ('name' => 'Company Name','col' => 'company_id','value'=>$row->company_name),array ('name' => 'Mobile','col' => 'mobile','value'=>$row->mobile),array ('name' => 'Phone','col' => 'phone','value'=>$row->phone),array ('name' => 'Address','col' => 'address','value'=>$row->address),array ('name' => 'City','col' => 'city','value'=>$row->city),array ('name' => 'State','col' => 'state','value'=>$row->state),array ('name' => 'Postal Code','col' => 'postal_code','value'=>$row->postal_code),array ('name' => 'Country','col' => 'country','value'=>$row->country));
			}
		}
		$this->load->view('FirstLayoutView', $data);
	}
	
	public function importmapping() {
		
		//echo print_r(array_values($_POST['table']));
		
		
		$report_to_id = $this->getUserReporter($_POST['owner_id']);	// Get reporter id
		$owner_id = $_POST['owner_id'];unset($_POST['owner_id']);
		$query = 'update contact_tmp set owner_id= ? , report_to_id= ? ,associate_to= ? , assign_to= ? where org_id = ? ' ;

		$res = $this->db->query($query, array($owner_id,$report_to_id,"company",$owner_id,$this->user->org_id));

		
$valsStr = '';
		$formFields = $params = array();
		foreach ($_POST as $fieldName => $fieldVal) {
			if($fieldVal!="")
			{
				$formFields[$fieldName] = trim($fieldVal);
				$valsStr .= (trim($fieldVal) ? ', ' : '') . trim($fieldVal);
				
if(trim($fieldVal)=='company_id')
{
	$table_data .= (trim($fieldName) ? ', ' : '') . trim('company_id');
}
else
{
	$table_data .= ($fieldName ? ', ' : '') . $fieldName;

				
}
				echo $fieldVal;
			
				if(trim($fieldVal)=="company_id")
				{
					$datas=$fieldName;
					
					$query = "select * FROM contact_tmp where org_id = ?";
					
					$resObj = $this->db->query($query,$this->user->org_id);

		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				
				$companies_name = $row->$datas;
				 $contact_id		= $row->contact_id;
				
				$query1 = "select * FROM company where org_id = ? AND company_name= '".$companies_name."' ";
				$resObj1 = $this->db->query($query1,$this->user->org_id);
				
				if ($resObj1->num_rows()) {
			foreach ($resObj1->result() as $row1) {
				
				$companies_id = $row1->company_id;
				
				$query2 = 'update contact_tmp set company_id= ? where contact_id = "'.$contact_id.'" ' ;

		$res = $this->db->query($query2, array($row1->company_id));
				
			}
				}
				else
		{
			
		}
				
			}
			
		}
		else
		{
			
		}
		
				}
				else
				{
					
				}
			}
			
		}
		
$params = array();
		
		
		$query="INSERT INTO contact (owner_id,report_to_id,org_id,contact_create_date,contact_modify_date,associate_to,assign_to".$valsStr.") (SELECT owner_id,report_to_id,org_id,contact_create_date,contact_modify_date,associate_to,assign_to".$table_data." from contact_tmp WHERE org_id = ?) ";
		
		$params[] = $this->user->org_id;
		$resObj = $this->db->query($query, $params);
		
		$query="DELETE FROM `contact_tmp` WHERE org_id = ?";
			
		$resObj = $this->db->query($query, $params);
		
		
		redirect(base_url() . 'contacts/' , 'location', 301);
	}
	
	

	public function details($id) {
		$this->bodyClass = 'contact-details';
		$this->pageTitle = $this->pageDesc = 'Contact Details';
		$PrefixSequenc = $this->getPrefixSequenceModule('cases');
		$data['prefixsequence'] = $PrefixSequenc['prefix']."/".$PrefixSequenc['sequence'];
		$data['sequence'] = $PrefixSequenc['sequence'];
		
		$data['content'] = 'ContactDetailsView';
		$data['users'] = $this->getAllUsers();
		
		$data['products'] = $this->getAllProducts();


		// Get contact details and arrange that data
		$contact = $this->getContactDetails($id);

	$org_id=$this->user->org_id;
		if($this->user->demo==0)
		{
			
			
			$data['NextId']=$this->getNextIdAndPreviousId("select *, co.org_id, c.phone as cphone, co.phone from contact co left join company c on co.company_id = c.company_id join user u on co.owner_id = u.user_id where (co.contact_id > ? ) AND (co.org_id = ? OR  co.org_id = '0' )  ORDER BY co.contact_id ASC LIMIT 1",$id,"contact_id",$org_id);

			$data['PreviousId']=$this->getNextIdAndPreviousId("select *, co.org_id, c.phone as cphone, co.phone from contact co left join company c on co.company_id = c.company_id join user u on co.owner_id = u.user_id where (co.contact_id < ? ) AND (co.org_id = ? OR  co.org_id = '0' )  ORDER BY co.contact_id  desc LIMIT 1",$id,"contact_id",$org_id);
		}
		else
		{
			
				$data['NextId']=$this->getNextIdAndPreviousId("select *, co.org_id, c.phone as cphone, co.phone from contact co left join company c on co.company_id = c.company_id join user u on co.owner_id = u.user_id where co.contact_id > ? AND co.org_id = ? ORDER BY co.contact_id ASC LIMIT 1",$id,"contact_id",$org_id);
		
	$data['PreviousId']=$this->getNextIdAndPreviousId("select *, co.org_id, c.phone as cphone, co.phone from contact co left join company c on co.company_id = c.company_id join user u on co.owner_id = u.user_id where co.contact_id < ?  AND co.org_id = ? ORDER BY co.contact_id  desc LIMIT 1",$id,"t.contact_id",$org_id);
		}

		$this->orgAccessCheck($contact->org_id);	// Organization access check

		// Arrange users for view manipulation
		$data['aUsers'] = array();
		foreach ($data['users'] as $user) {
			$data['aUsers'][$user->user_id] = $user;
		}

		// Contact name
		$contact->contact_name = $contact->title ? $contact->title : '';
		$contact->contact_name .= ($contact->contact_name ? ' ' : '') . ucfirst($contact->first_name);
		$contact->contact_name .= ($contact->contact_name ? ' ' : '') . ucfirst($contact->last_name);

		// Created Before
		$startDate = new DateTime(date('Y-m-d', strtotime($contact->contact_create_date)));
		$endDate = new DateTime(date('Y-m-d'));
		$diff = $startDate->diff($endDate);
		$contact->created_before = $this->formatDays($diff->days);

		// Contact notes
		$data['notes'] = $this->getItemNotes('contact', $id);

		// Get contact tasks and arrange data for datatable construction
		$data['tasks'] = $this->getItemTasks('contact', $id);

		// Get contact deals and arrange data for datatable construction
		$data['deals'] = $this->getContactItems('deal', $id, 'deal');

		// Get contact tickets and arrange data for datatable construction
		$data['cases'] = $this->getContactItems('cases', $id);

		// Contact documents
		$data['docs'] = $this->getItemDocs('contact', $id);

		// Deal quotes
		$data['quotes'] = $this->getItemQuotes('contact', $id);

		// Contact history
		$data['history'] = $this->getItemHistory('contact', $id);

		$data['contact'] = $contact;
		$data['add_deal_contact_id'] = $contact->contact_id;
		$data['add_deal_company_id'] = $contact->company_id;
		$data['prev_contact'] = $this->getPrevContact($id);
		$data['next_contact'] = $this->getNextContact($id);
		$data['fields'] = $this->getAllUserFields();
		$data['companies'] = $this->getAllCompanies();	// Get all companies
		$data['contacts'] = $this->getAllContacts();		// Get contacts
		$this->load->view('FirstLayoutView', $data);
	}

	public function add($data=array()) {
		$this->bodyClass = 'create-contact';
		$this->pageTitle = $this->pageDesc = 'Create Contact';
		$data['content'] = 'CreateContactView';

		$data['companies'] = $this->getAllCompanies();
		$data['users'] = $this->getAllUsers();
		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$this->load->view('FirstLayoutView', $data);
	}

	public function submit() {
		// Check for the task creation through modal
		if (isset($_POST['modal_flag'])) {
			$modal_flag = $_POST['modal_flag'];
			unset($_POST['modal_flag']);
		}

		// Gather form fields
		$data = $formFields = $params = array();
		$currDateTime = $this->getCurrTime();
		$valsStr = '';
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = ($fieldName == 'due_date') ? date('Y-m-d H:i:s', strtotime($fieldVal)) : trim($fieldVal);
			$valsStr .= ($valsStr ? ', ' : '') . '?';
		}
		if (!$formFields['associate_to']) {
			$formFields['associate_to'] = 'company';
			$valsStr .= ($valsStr ? ', ' : '') . '?';
		}
		$formFields['owner_id'] = $this->user->user_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['report_to_id'] = $this->user->report_to_id ? $this->user->report_to_id : $this->user->user_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['org_id'] = $this->user->org_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['contact_create_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['contact_modify_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Create a contact with given details
		$contactId = $this->getTableAutoID('contact');
		$query = 'insert into contact (' . implode(', ', array_keys($formFields)) . ') values (' . $valsStr . ')';
		$res = $this->db->query($query, array_values($formFields));

		// TODO: NEED CONFIRMATION FOR THIS OPERATION Create a company for this contact
		// $companyName = $formFields['first_name'] . $formFields['last_name'];
		// $comQuery = 'insert into company (company_name, owner_id, report_to_id, company_create_date) values (?, ?, ?, now())';
		// $comRes = $this->db->query($comQuery, array($companyName, $this->user->user_id, $this->user->report_to_id));

		if ($res) { # && $comRes) {
			// Log activity
			$info = array('associate_to' => $formFields['associate_to'], 'associate_id' => $formFields['associate_id']);
			$activity = array('action' => 'CREATE', 'type' => 'contact', 'id' => $contactId, 'info' => json_encode($info));
			$this->logUserActivity($activity);
			
			// Clear session info
			if (isset($_SESSION['contactCompany'])) unset($_SESSION['contactCompany']);
			if (isset($_SESSION['contactInfo'])) unset($_SESSION['contactInfo']);

			if (isset($_SESSION['sourceUrl'])) {
				$sourceUrl = $_SESSION['sourceUrl'];
				unset($_SESSION['sourceUrl']);
				if ($sourceUrl == base_url().'deals/add') $_SESSION['dealContact'] = $contactId;
				redirect($sourceUrl, 'location', 301);
			}

			if (!$modal_flag) redirect(base_url() . 'contacts/details/' . $contactId, 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while creating this contact, please try again after sometime.';
		}

		if ($modal_flag) {
			$data['contacts'][0] = true;
			$data['entityType'] = $formFields['associate_to'];
			$data['entityId'] = $formFields['associate_id'];
			$view = 'EntityContactsView';
			if ($formFields['associate_to'] == 'company') {
				$data['entitySourcePath'] = 'companies/getcompanycontactsjson';
				$view = 'CompanyContactsView';
			}
			$this->load->view($view, $data);
		} else {
			$this->add($data);
		}
	}

	public function edit($id, $data = array()) {
		$this->bodyClass = 'edit-contact';
		$this->pageTitle = $this->pageDesc = 'Edit Contact';
		$data['content'] = 'EditContactView';

		$data['contact'] = $this->getContactDetails($id);

		$data['companies'] = $this->getAllCompanies();
		$data['users'] = $this->getAllUsers();
		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$this->load->view('FirstLayoutView', $data);
	}

	public function update($id) {
		// Gather form fields
		$data = $formFields = array();
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal);
		}
		
		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Update contact with given details
		$formFields['contact_modify_date'] = $this->getCurrTime();
		$query = 'update contact set ' . implode(' = ?, ', array_keys($formFields)) . ' = ? where contact_id = ?';
		$formFields['contact_id'] = $id;
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			// Log activity
			$activity = array('action' => 'UPDATE', 'type' => 'contact', 'id' => $id);
			$this->logUserActivity($activity);
			redirect(base_url() . "contacts/details/$id", 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while updating this contact, please try again after sometime.';
		}

		$this->edit($data);
	}

	// Add a new task for this contact
	public function addTask($id) {
		$_SESSION['taskInfo']['associate_to'] = 'contact';
		$_SESSION['taskInfo']['associate_id'] = $id;
		$_SESSION['sourceUrl'] = base_url() . "contacts/details/$id";
		redirect(base_url(). 'tasks/add', 'location', 301);
	}

	// Add a new deal for this contact
	public function addDeal($id) {
		$_SESSION['dealContact'] = $id;
		$_SESSION['sourceUrl'] = base_url() . "contacts/details/$id";
		redirect(base_url(). 'deals/add', 'location', 301);
	}

	// Add a new ticket for this contact
	public function addCase($id) {
		$contact = $this->getContactDetails($id);
		// Contact name
		$contact->contact_name = $contact->first_name;
		$contact->contact_name .= ($contact->contact_name ? ' ' : '') . $contact->last_name;

		$_SESSION['caseContact'] = $id;
		$_SESSION['caseContactName'] = $contact->contact_name;
		redirect(base_url(). 'cases/add', 'location', 301);
	}

	// Add a new quote for this contact
	public function addQuote($id) {
		$contact = $this->getContactDetails($id);
		// Contact name
		$contact->contact_name = $contact->first_name;
		$contact->contact_name .= ($contact->contact_name ? ' ' : '') . $contact->last_name;
		
		$_SESSION['quoteContact'] = $id;
		$_SESSION['quoteContactName'] = $contact->contact_name;
		$_SESSION['sourceUrl'] = base_url() . "contacts/details/$id";
		redirect(base_url(). 'quotes/add', 'location', 301);
	}

	// Add a new company
	public function addCompany() {
		$_SESSION['sourceUrl'] = base_url() . 'contacts/add';
		redirect(base_url(). 'companies/add', 'location', 301);
	}

	// Get contacts
	public function getContacts($filters = '',$filters_search = '') {
		if (!$filters) $filters = $this->filters;
		
		if (!$filters_search) $filters_search = $this->filters_search;

		$query = "select SQL_CALC_FOUND_ROWS *, c.phone as cphone, co.phone, trim(concat(co.first_name, ' ', co.last_name)) as contact_name, if (u.name, u.name, u.name) as owner, c.assign_to as assign_to
						  from contact co 
						  left join company c on co.company_id = c.company_id 
						  left join user u on c.assign_to = u.user_id";
		$whereCond = ' where co.associate_to != ?';
		$params = array('lead');

		// Role checkup
		
		if($this->user->demo==0)
		{
			
		if ($this->isManager) {
			$whereCond .= ' and (co.org_id = ? or co.org_id = ? ) and (co.report_to_id = ? or co.owner_id = ? or  c.report_to_id = ? or  c.assign_to = ? ) ';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ' and (co.org_id = ? or co.org_id = ? ) and (co.owner_id = ? or c.assign_to = ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' and (co.org_id = ?or co.org_id = ? ) ';
			$params[] = $this->user->org_id;
			$params[] = "0";
		}
		}
		else
		{
			if ($this->isManager) {
			$whereCond .= ' and co.org_id = ? and (co.report_to_id = ? or co.owner_id = ? or  c.report_to_id = ? or  c.assign_to = ? ) ';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ' and co.org_id = ? and (co.owner_id = ? or c.assign_to = ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' and co.org_id = ?';
			$params[] = $this->user->org_id;
		}
		}
		$countWhereCond = $whereCond;
		$countParams = $params;

		// Apply filters
		if ($filters) {
			$filtersCondition = '';
			$ik=0;
			$_SESSION['filters']="contacts";
			
			foreach ($filters as $fkey => $fvalue) {
				$ik++;
					$_SESSION['filters_key'][$ik]=$fkey ;
					$_SESSION['filters_fvalue'][$ik]=$fvalue ;
					
					
				if ($fvalue && ($fvalue != 'All')) {
					if (($fkey == 'contact_create_date') || ($fkey == 'contact_modify_date')) {
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
					}else if($fkey =='first')
					{
						 $filtersCondition .= ($filtersCondition ? ' and ' : '') . " (CONCAT( co.first_name, ' ', co.last_name ) LIKE '%".$fvalue."%' or c.company_name LIKE '%".$fvalue."%')" ;
						
					} else {
						$filtersCondition .= ($filtersCondition ? ' and ' : '') . "co.$fkey = ?";
						$params[] = $fvalue;
					}
				}	
			}
			if ($filtersCondition) $whereCond .= ($whereCond ? ' and ' : ' where ') . $filtersCondition;
		}
		
		

		// ======== Datatable adjustments ========
		$aColumns = array_keys($this->contactTableCols);
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
		
		$_SESSION['contacts_export']=$whereCond;
		$_SESSION['contacts_export_params']=$params;

		$contacts = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->contact_create_date = $this->convertDateTime($row->contact_create_date);
				$contacts[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countQuery = 'SELECT COUNT(`contact_id`) as totalRows FROM contact co  left join company c on co.company_id = c.company_id ' . $countWhereCond;
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $contacts;
	}
	
	
	
	
	
	
	
	
	
	
	// Get contacts
	public function getsearchContacts($filters) {
		
		$query = "select SQL_CALC_FOUND_ROWS *, c.phone as cphone, co.phone, trim(concat(co.first_name, ' ', co.last_name)) as contact_name, if (u.name, u.name, u.name) as owner, co.owner_id as owner_id
						  from contact co 
						  left join company c on co.company_id = c.company_id 
						  left join user u on co.owner_id = u.user_id";
		$whereCond = ' where co.associate_to != ? ';
		$params = array('lead');

		// Role checkup
		if ($this->isManager) {
			$whereCond .= ' and co.org_id = ? and (co.report_to_id = ? or co.owner_id = ? ) AND (co.first_name like "%'.$filters.'%" OR co.last_name like "%'.$filters.'%"  OR co.designation like "%'.$filters.'%" OR co.mobile like "%'.$filters.'%") ';
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ' and co.org_id = ? and co.owner_id = ? AND (co.first_name like "%'.$this->filters.'%" OR co.last_name like "%'.$filters.'%"  OR co.designation like "%'.$filters.'%" OR co.mobile like "%'.$filters.'%")  ';
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' and co.org_id = ? AND (co.first_name like "%'.$filters.'%" OR co.last_name like "%'.$filters.'%"  OR co.designation like "%'.$filters.'%" OR co.mobile like "%'.$filters.'%")  ';
			$params[] = $this->user->org_id;
		}
		
		
		$query .= " $whereCond $sOrder $sLimit";
		$resObj = $this->db->query($query, $params);

		$contacts = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->contact_create_date = $this->convertDateTime($row->contact_create_date);
				$contacts[] = $row;
				
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countQuery = 'SELECT COUNT(`contact_id`) as totalRows FROM contact co ' . $countWhereCond;
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;
		
		

		return $contacts;
	}





	// Get next contact
	public function getNextContact($id) {
		$resObj = $this->db->query("select * from contact where contact_id > ? order by contact_id limit 1", array($id));
		return $resObj->row();
	}

	// Get previous contact
	public function getPrevContact($id) {
		$resObj = $this->db->query("select * from contact where contact_id < ? order by contact_id desc limit 1", array($id));
		return $resObj->row();
	}

	// Get contact items
	public function getContactItems($type, $id, $colPrefix="") {
		$userJoinCol = ($type == 'cases') ? 'assign_to' : 'deal_owner_id';
		if($type=='deal')
		{
			$extraCols = ", if(uf.stage,uf.stage,uf.stage) as stage_name , if(i.stage,i.stage,i.stage) as stage , if(i.source,i.source,i.source) as  source";
					$join = ' left join user_fields uf on i.stage =uf.no';
		}
		else
		{
			
			$extraCols = "";
			$join = '';
		}
		
		$query = "select SQL_CALC_FOUND_ROWS *, if (u.name, u.name, u.username) as owner, if (u.name, u.name, u.username) as assignee $extraCols
							from $type i
							inner join user u on u.user_id = i.$userJoinCol
							$join
							where ".($colPrefix?$colPrefix.'_':'')."contact_id = ?";

		// ======== Datatable adjustments ========
		$aColumns = ($type == 'cases') ? array_keys($this->caseTableCols) : array_keys($this->dealTableCols);
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
				$items[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$keyCol = ($type == 'cases') ? 'case_id' : 'deal_id';
		$countRes = $this->db->query("SELECT COUNT(`$keyCol`) as totalRows FROM $type WHERE ".($colPrefix?$colPrefix.'_':'')."contact_id = ?", $id);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $items;
	}
}