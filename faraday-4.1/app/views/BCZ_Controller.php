<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once "BCZUtills.php";

class BCZ_Controller extends CI_Controller {
	public $layout, $pageTitle, $pageDesc, $bodyClass, $user, $isSupAdmin, $isAdmin, $isExecutive, $isManager;
	public $noDataChar='-', $noDataMsg='No Opportunities found!', $chooseOption = '-- Choose Option --', $chooseDate = '-- Choose Date --', $mandatoryFieldIndicator = '<span class="text-danger text-mini">*</span>';
	public $uploadPath = 'assets/uploads/', $imagesPath = 'assets/uploads/images/', $docsPath = 'assets/uploads/docs/', $quotesPath = 'assets/uploads/quotes/', $ordersPath = 'assets/uploads/orders/', $importPath = 'assets/uploads/import/', $postFilesPath = 'assets/uploads/posts/';
	public $quotePrefix = 'Q/FA/', $soPrefix = 'SO/FA/';
	public $from, $to, $cc, $bcc, $subject, $message, $attachments=array(), $attachmentType, $dateFormat = 'd-m-Y', $today, $insertDateFormat = 'Y-m-d h:i:s';

	// Company customer care details
	
	
	public $supportInfo = array('name'=>'RabbitCRM','contact' => '+91 85080 80000', 'fax' => '999888777', 'email' => 'support@rabbitcrm.com', 'website' => 'www.rabbitcrm.com','footer'=>'RabbitCRM','footer-color'=>'Rabbit<font style="color:#018EC3">CRM</font>');
	
	//Label Name
	public $labelinfo = array('home'=>'Home','leads' => 'Leads', 'deals' => 'Opportunities', 'tasks' => 'Tasks', 'contacts' => 'Contacts', 'companies' => 'Account', 'products' => 'Products', 'quotes' => 'Quotes', 'orders' => 'Orders', 'tickets' => 'Tickets', 'docs' => 'Docs', 'target ' => 'Close');

	// Datatable columns
	public $leadTableCols = array('lead_name' => 'name', 'company_name' => 'Account', 'owner' => 'owner', 'lead_status_name' => 'status', 'lead_source' => 'Lead Source', 'lead_create_date' => 'created');
	
		public $CampaignTableCols = array('campaign_name' => 'name', 'campaign_type_name' => 'type', 'owner' => 'owner', 'campaignstatus' => 'status', 'target_audience' => 'Audience', 'create_date' => 'created');
	
	public $dealTableCols = array('deal_name' => 'name', 'owner' => 'owner','stage_name' => 'stage', 'deal_amount' => 'amount', 'source' => 'source',  'exp_close' => 'Close Date', 'deal_create_date' => 'created');
	public $taskTableCols = array('task_name' => 'name','owner' => 'user', 'task_type' => 'type', 'task_status' => 'status', 'due_date' => 'due Date', 'priority' => 'priority', 'task_create_date' => 'created');
	public $contactTableCols = array('contact_name' => 'name', 'mobile' => 'phone', 'email' => 'email', 'company_name' => 'Account', 'owner' => 'owner', 'contact_create_date' => 'created');
	
	public $companyTableCols = array('company_name' => 'name', 'owner' => 'owner', 'customer_type' => 'type', 'phone' => 'phone', 'website' => 'website', 'company_create_date' => 'created');
	public $productTableCols = array('product_name' => 'name', 'partno' => 'part No', 'category_name' => 'category', 'price' => 'price', 'create_date' => 'created');
	public $caseTableCols = array('case_title' => 'title', 'case_status' => 'status', 'priority' => 'priority', 'severity' => 'severity', 'company' => 'Account', 'case_create_date' => 'created');
	public $quoteTableCols = array('subject' => 'subject', 'deal_name' => 'Opportunity', 'company_name' => 'Account', 'total' => 'total', 'quote_stage_name' => 'stage', 'quote_create_date' => 'created');
	public $orderTableCols = array('subject' => 'subject', 'deal_name' => 'Opportunity', 'company_name' => 'Account', 'total' => 'total', 'so_stage_name' => 'stage', 'estimated_delivery' => 'Delivery');
	public $userTableCols = array('name' => 'name', 'user_email' => 'email', 'user_designation' => 'role', 'user_status' => 'status', 'report_to' => 'report to');
	public $emailTableCols = array('subject' => 'subject', 'sender' => 'sender', 'reciever' => 'reciever', 'deal_name' => 'Opportunity', 'send_date' => 'sent');
	public $orgTableCols = array('name' => 'name', 'users_cnt' => 'users', 'email' => 'email', 'website' => 'website', 'country' => 'country');
	
	//upload csv or excel
	//public $lead_tmp_TableCols="`org_id` ,`title` ,`first_name` ,`last_name` ,`designation` ,`mobile` ,`phone` ,`email` ,`alt_email` ,`company_name` ,`customer_type` ,`fax` ,`website` ,`bill_addr` ,`bill_addr1` ,`bill_city` ,`bill_state` ,`bill_postal_code` ,`bill_country` ,`lead_description` ,`lead_source` ,`status_name` ,`lead_application` ,`lead_industry` ,`lead_create_date` ,`lead_modify_date` ,`lead_owner_id` ,`report_to_id` ,`lead_assigned_from` ,`inbox`";
	
	

	// public $actions = array('CREATE', 'UPDATE', 'REASSIGN', 'CONVERT', 'DELETE', 'ADD_NOTE', 'GENERATE', 'SEND', 'VIEW_FILE', 'DOWNLOAD_FILE', 'UPLOAD');

	public $dtDisplayCount = 0, $dtTotalCount = 0;

	function __construct() {
		parent::__construct();
//		session_start();

		// Set default timezone
		date_default_timezone_set('UTC');

		// DEV: for debug purpose only
		ini_set('error_reporting', E_ALL ^ E_NOTICE ^ E_WARNING);
		ini_set('error_log', '/tmp/logs/php_errors.log');

		// Load required modules		
		$this->load->helper('url');	
		$this->load->helper('form');
		$this->load->database();
			
		// User session checkup and redirection to correspnding page
		$accessWithoutLogin = array('admin', 'login', 'reset', 'signup', 'createuser', 'activate');
		if (!$_SESSION['bcz_user']->user_id && !in_array($this->uri->segment(1), $accessWithoutLogin)) {
			redirect(base_url().'login', 'location', 301);
		}

		$this->layout = 'FirstLayoutView';	// Specify the layout template

		// Get organization logo
		if (!isset($_SESSION['bcz_org_logo'])) {
			$organization = $this->getOrganizationDetails();
			$orgLogo = $this->imagesPath . $organization->logo;
			if ($organization->logo && file_exists($orgLogo)) $_SESSION['bcz_org_logo'] = base_url() . $orgLogo;
		}

		// Set user info and roles
		$this->user = $_SESSION['bcz_user'];
		$this->isSupAdmin = !$this->user->org_id || ($this->user->user_designation == 'SupAdmin');
		$this->isAdmin = ($this->user->user_designation == 'Admin');
		$this->isManager = ($this->user->user_designation == 'Manager');
		$this->isExecutive = ($this->user->user_designation == 'Executive');

		// Date handling
		$this->today = date($this->dateFormat);
	}


	/****** ===== ***** START: fetch ALL ***** ===== *****/

	// Get all users
	public function getAllUsers() {
		$query = 'select user_id, org_id, name, username, user_email, user_designation, report_to_id, user_status from user';
		$params = array();
		if ($this->user->org_id) {
			$query .= ' where user_status="active" AND org_id = ?';
			$params[] = $this->user->org_id;
		}
		$resObj = $this->db->query($query, $params);

		$users = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				if (!$row->name) $row->name = $row->username;
				$users[] = $row;
			}
		}
		return $users;
	}
	
	
	// Get all users
	public function getAllInactiveUsers() {
		$query = 'select user_id, org_id, name, username, user_email, user_designation, report_to_id, user_status from user';
		$params = array();
		if ($this->user->org_id) {
			$query .= ' where user_status="inactive" AND org_id = ?';
			$params[] = $this->user->org_id;
		}
		$resObj = $this->db->query($query, $params);

		$users = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				if (!$row->name) $row->name = $row->username;
				$users[] = $row;
			}
		}
		return $users;
	}
	

	// Get all leads
	public function getAllLeads() {
		$query = 'select * from lead';
		$params = array();
		if ($this->user->org_id) {
			$query .= ' where org_id = ?';
			$params[] = $this->user->org_id;
		}
		$resObj = $this->db->query($query, $params);

		$leads = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$leads[] = $row;
			}
		}

		return $leads;
	}


	public function getAllLeadsExport($query,$params) {
		
		
		$query = str_replace('user_id', 'lead_owner_id', $query);
		//$query = str_replace('le.', ' ', $query);
		
		$query="select trim(concat(title,'',le.first_name, ' ', le.last_name)) as Name,company_name as Account, if (u.name, u.name, u.name) as Owner , if(uf.lead_status,uf.lead_status,uf.lead_status) as Status,lead_source as Source ,lead_create_date as Created
						  from lead le
						  left join user_fields uf on le.lead_status =uf.no
						  left join user u on le.lead_owner_id = u.user_id   ".$query;
		
		$resObj = $this->db->query($query, $params);

		$leads = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$leads[] = $row;
			}
		}

		return $leads;
	}
		
		public function getAllgetCampaignExport($query,$params) {
			
			//$query = str_replace('ca.', ' ', $query);
			

		$query="select ca.campaign_name as Name , uf.campaign_type as Type , if (u.name, u.name, u.name) as Owner , uf1.campaign_status as status ,ca.target_audience as Audience ,ca.create_date as created
						  from campaign ca 
						  left join user u on ca.owner_id = u.user_id left join user_fields uf on ca.campaign_type = uf.no left join user_fields uf1 on ca.campaign_status = uf1.no ".$query;
		
		$resObj = $this->db->query($query, $params);

		$campaign = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$campaign[] = $row;
			}
		}

		return $campaign;
	}
		
		
	// Get all deals
	public function getAllDeals() {
		$query = 'select * from deal';
		$params = array();
		if ($this->user->org_id) {
			$query .= ' where org_id = ?';
			$params[] = $this->user->org_id;
		}
		$resObj = $this->db->query($query, $params);

		$deals = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$deals[] = $row;
			}
		}

		return $deals;
	}
	
	public function getAllDealsExport($query,$params) {
		


						   
						   	$query="select deal_name as Name, if (u.name, u.name, u.name) as Owner, if(uf.stage,uf.stage,uf.stage) as Stage ,  de.deal_amount as Amount,if(de.source,de.source,de.source) as  Source ,de.actual_close as actual_close,de.deal_create_date as CloseDate
						  from deal de 
						  left join user_fields uf on de.stage =uf.no
						  left join company c on de.deal_company_id = c.company_id
						  left join user u on (c.assign_to = u.user_id AND c.assign_to != ?) ".$query;
						   
						   
		
		$resObj = $this->db->query($query, $params);

		$deals = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$deals[] = $row;
			}
		}

		return $deals;
	}
	
		// Get all campaign types
	public function getAllcampaign_types() {
		$query = 'SELECT SQL_CALC_FOUND_ROWS * 
FROM user_fields uf
LEFT JOIN user_delete_settings ud ON ( ud.user_settings_id != uf.no
AND uf.org_id != uf.org_id )   where (uf.`org_id`= ? or uf.`org_id`= ? ) AND uf.`campaign_type` !=  ""';
		
		
		$params = array();
		$params[] = "0";
		$params[] = $this->user->org_id;
		$resObj = $this->db->query($query, $params);
		$user_fields = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$user_fields[] = $row;
			}
		}
		return $user_fields;
	}
	
		// Get all campaign types
	public function getAllcampaignresponse() {
		$query = 'SELECT SQL_CALC_FOUND_ROWS * 
FROM user_fields uf
LEFT JOIN user_delete_settings ud ON ( ud.user_settings_id != uf.no
AND uf.org_id != uf.org_id )  where (uf.`org_id`= ? or uf.`org_id`= ? ) AND uf.`campaign_response` !="" ';
		
		
		$params = array();
		$params[] = "0";
		$params[] = $this->user->org_id;
		$resObj = $this->db->query($query, $params);
		$user_fields = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$user_fields[] = $row;
			}
		}
		return $user_fields;
	}


	// Get all campaign types
	public function getAllcampaignstatus() {
		$query = 'SELECT SQL_CALC_FOUND_ROWS * 
FROM user_fields uf
LEFT JOIN user_delete_settings ud ON ( ud.user_settings_id != uf.no
AND uf.org_id != uf.org_id )   where (uf.`org_id`= ? or uf.`org_id`= ? ) AND uf.`campaign_status` != "" ';
		
		
		$params = array();
		$params[] = "0";
		$params[] = $this->user->org_id;
		$resObj = $this->db->query($query, $params);
		$user_fields = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$user_fields[] = $row;
			}
		}
		return $user_fields;
	}



	// Get all tasks
	public function getAllTasks() {
		$query = 'select * from task';
		$params = array();
		if ($this->user->org_id) {
			$query .= ' where org_id = ?';
			$params[] = $this->user->org_id;
		}
		$resObj = $this->db->query($query, $params);

		$tasks = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$tasks[] = $row;
			}
		}

		return $tasks;
	}
	
	
		public function getAllTasksExport($query,$params) {
	
		
		$query="select task_name as Name, if (u.name, u.name, u.name) as Owner , if(uf1.task_type,uf1.task_type,uf1.task_type) as Type, if (uf.task_status, uf.task_status, uf.task_status) as status ,due_date as Due_Date,t.priority as priority , task_create_date as created
						  from task t  left join user u on t.task_owner_id = u.user_id 
						  left join user_fields uf on t.status = uf.no  
						  left join user_fields uf1 on t.type = uf1.no  ".$query;
		
		$resObj = $this->db->query($query, $params);

		$tasks = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$tasks[] = $row;
			}
		}

		return $tasks;
	}


	// Get all companies
	public function getAllCompanies() {
		
		$query = 'select * from company where associate_to != ?';
		$params = array('lead');
			if($this->user->demo==0)
		{
		if ($this->user->org_id) {
			$query .= ' and org_id = ? or  org_id = ?';
			$params[] = $this->user->org_id;
			$params[] = '0';
		}
		}
		else
		{
			if ($this->user->org_id) {
			$query .= ' and org_id = ?';
			$params[] = $this->user->org_id;
		}
		}
		$resObj = $this->db->query($query, $params);

		$companies = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$companies[] = $row;
			}
		}
		return $companies;
	}

	public function getAllCompaniesExport($query,$params) {
		
		$query="select c.company_name as Name, if (u.name, u.name, u.name) as owner ,  if(uf.customer_type,uf.customer_type,uf.customer_type) as Type,c.phone as Phone , c.website as Website , company_create_date as created
						  from company c 
						  left join user_fields uf on c.com_cust_type =uf.no
						  left join user u on c.assign_to = u.user_id ".$query;
		
			
		$resObj = $this->db->query($query, $params);

		$companies = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$companies[] = $row;
			}
		}
		return $companies;
	}
	// Get all contacts
	public function getAllContacts() {
		$query = 'select *, trim(concat(first_name, " ", last_name)) as name from contact';
		$params = array();
		if($this->user->demo==0)
		{
		if ($this->user->org_id) {
			$query .= ' where org_id = ? or org_id = ? ';
			$params[] = $this->user->org_id;
			$params[] = "0";
			
		}
		}
		else
		{
			if ($this->user->org_id) {
			$query .= ' where org_id = ?';
			$params[] = $this->user->org_id;
			}
		}
		
		
		
		$resObj = $this->db->query($query, $params);

		$contacts = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$contacts[] = $row;
			}
		}
		return $contacts;		
	}
	
		// Get all contacts
	public function getOneContacts($id) {
		$query = 'select *, trim(concat(first_name, " ", last_name)) as name from contact';
		$params = array();
		
		if($this->user->demo==0)
		{
		if ($this->user->org_id) {
			$query .= ' where (org_id = ? or org_id = ?) AND (company_id=?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $id;
			
		}
		}
		else
		{
			if ($this->user->org_id) {
			$query .= ' where org_id = ?  AND company_id=?';
			$params[] = $this->user->org_id;
			$params[] = $id;
			}
		}
		
		
		$resObj = $this->db->query($query, $params);

		$contacts = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$contacts[] = $row;
			}
		}
		return $contacts;		
	}

	
	
	public function getAllContactsExport($query,$params) {
		
		
		$query="select trim(concat(co.title,'',co.first_name, ' ', co.last_name)) as Name,co.mobile as Phone,co.email as Email, c.company_name as Account, if (u.name, u.name, u.name) as owner, co.contact_create_date as created
						  from contact co 
						  left join company c on co.company_id = c.company_id 
						  left join user u on co.owner_id = u.user_id ".$query;
		
		

		
			
		$resObj = $this->db->query($query, $params);

		$contact = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$contact[] = $row;
			}
		}
		return $contact;
	}
	
	public function getAllContactsExport1() {
		
		$query = 'select *, trim(concat(first_name, " ", last_name)) as name from contact co 
							left join company c on c.company_id =co.company_id';
		
			
		$resObj = $this->db->query($query);

		$contact = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$contact[] = $row;
			}
		}
		return $contact;
	}
	// Get all products
	public function getAllProducts() {
		$query = 'select * from products';
		$params = array();
		
		
		if($this->user->demo==0)
		{
		if ($this->user->org_id) {
			$query .= ' where org_id = ? or org_id = ? ';
			$params[] = $this->user->org_id;
			$params[] = "0";
			
		}
		}
		else
		{
			if ($this->user->org_id) {
			$query .= ' where org_id = ?';
			$params[] = $this->user->org_id;
			}
		}
		
		
		
		
		$resObj = $this->db->query($query, $params);

		$products = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$products[] = $row;
			}
		}
		return $products;
	}
	
	public function getAllProductsExport($query,$params) {
		
		$query = 'select pr.product_name as Name,pr.partno as Partno , if(us.product_category,us.product_category,us.product_category) as Category, pr.price as Price ,pr.create_date as created
						  from products pr
						  left join user_fields us on pr.category = us.no '.$query;
		
			
		$resObj = $this->db->query($query, $params);

		$products = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$products[] = $row;
			}
		}
		return $products;
	}

	// Get all quotes
	public function getAllQuotes() {
		$query = 'select * from quote';
		$params = array();
		if ($this->user->org_id) {
			$query .= ' where org_id = ?';
			$params[] = $this->user->org_id;
		}
		$resObj = $this->db->query($query, $params);

		$quotes = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$quotes[] = $row;
			}
		}

		return $quotes;
	}
	
		public function getAllQuotesExport($query,$params) {
			
		
		$query = 'select q.quote_no as Quoteno ,q.subject as Subject, de.deal_name as Opportunity, c.company_name as Account, q.total, if(uf.quote_stage,uf.quote_stage,uf.quote_stage) as stage , q.quote_create_date as created 
						  from quote q 
						  left join company c on c.company_id = q.company_id 
						  left join contact co on co.contact_id = q.contact_id 
						  left join deal de on de.deal_id = q.deal_id
						  
						  left join user_fields uf on q.quote_stage =uf.no
						  left join user u on q.quote_owner_id = u.user_id '.$query;
		
			
		$resObj = $this->db->query($query, $params);

		$quote = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$quote[] = $row;
			}
		}
		return $quote;
	}

	// Get all orders
	public function getAllOrders() {
		$query = 'select * from sales_order';
		$params = array();
		if ($this->user->org_id) {
			$query .= ' where org_id = ?';
			$params[] = $this->user->org_id;
		}
		$resObj = $this->db->query($query, $params);

		$orders = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$orders[] = $row;
			}
		}

		return $orders;
	}
	
	public function getAllOrdersExport($query,$params) {
		

		$query = 'select o.subject as Subject,de.deal_name as Opportunity , c.company_name as Account, o.total, if (so.so_stage, so.so_stage, so.so_stage) as Stage , o.estimated_delivery as Delivery
						  from sales_order o 
						  left join company c on c.company_id = o.company_id 
						  left join contact co on co.contact_id = o.contact_id 
						  left join deal de on de.deal_id = o.deal_id 
						  left join user u on o.so_owner_id = u.user_id
						   left join user_fields so on o.so_stage = so.no  '.$query;
		
			
		$resObj = $this->db->query($query, $params);

		$sales_order = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$sales_order[] = $row;
			}
		}
		return $sales_order;
	}

	// Get all cases
	public function getAllCases() {
		$query = 'select * from cases';
		$params = array();
		if ($this->user->org_id) {
			$query .= ' where org_id = ?';
			$params[] = $this->user->org_id;
		}
		$resObj = $this->db->query($query, $params);

		$cases = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$cases[] = $row;
			}
		}

		return $cases;
	}
	
		public function getAllCasesExport($query,$params) {
			
		
		$query = 'select ca.case_title as title,ca.status as Status , ca.priority as Priority , ca.severity as Severity , c.company_name as Account , ca.case_create_date as created
						  from cases ca 
						  inner join user u on u.user_id = ca.assign_to
						  left join company c on ca.company_id = c.company_id '.$query;
		
			
		$resObj = $this->db->query($query, $params);

		$cases = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$cases[] = $row;
			}
		}
		return $cases;
	}

	// Get all the user fields
	public function getAllUserFields() {
		 // class = controller

	//	echo "SELECT * FROM `user_delete_settings` AS ud INNER JOIN  `user_fields` AS uf  ON ud.user_settings_id != uf.no  WHERE uf.org_id='".$this->user->org_id."' OR  uf.org_id='0' group by uf.no";
		 $resObj=$this->db->query("SELECT * FROM `user_delete_settings` AS ud INNER JOIN  `user_fields` AS uf  ON ud.user_settings_id != uf.no  WHERE uf.org_id='".$this->user->org_id."' OR  uf.org_id='0' group by uf.no");
		
		 $resObj1=$this->db->query("SELECT user_settings_id FROM `user_delete_settings` WHERE  org_id= '".$this->user->org_id."'");
		
		  //print_r($resObj1->result());
		 foreach ($resObj1->result() as $row1) {
			
		 $fields1[] = $row1;
		
		 }
		// print_r($fields1);
		 $y=$jk;
		 $numcount=count($fields1);
		 $userwhere="";
		 $jk=0;
		 $usercount=count($fields1);
		 foreach ($fields1 as $fields2) {
			
			
			 
			  $jk++;
			 if($usercount>=$jk)
			 {
			  if($usercount!=$jk)
			 {
				  
				 $userwhere.=" no!=".$fields2->user_settings_id." AND ";
			 }
			 else
			 {
				 
				 $userwhere.=" no!=".$fields2->user_settings_id;
			 }
			 
			 }
		 }
		 if($userwhere=="")
		 {
			 $userwhere="1=1";
		 }
		 
		  $myq="SELECT * FROM `user_fields`  WHERE (org_id='".$this->user->org_id."' OR  org_id='0') AND (".$userwhere.")";
		
		 $resObj=$this->db->query($myq);
		 
	//	$resObj = $this->db->select('*')
       //                 ->from('user_delete_settings')
     //                   ->join('user_fields', 'user_delete_settings.user_settings_id != user_fields.no','user_fields.no="0" or user_fields.org_id='.$this->user->org_id,'inner')
         //               ->get();
						
						
		//$resObj = $this->db->query("select * from user_fields where (`org_id`='".$this->user->org_id."' or `org_id`='0')");
		$fields = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$fields[] = $row;
			}
		}
		return $fields;
	}

	/****** ===== ***** END: fetch ALL ***** ===== *****/


	/****** ===== ***** START: fetch an ENTITY specific data ***** ===== *****/

	// Get notes
	public function getItemNotes($type, $id) {
		$query = "select * 
						  from note n 
						  inner join user u on n.owner_id = u.user_id 
						  where associate_id = ? and associate_to = ? 
						  order by note_create_date DESC";
		$resObj = $this->db->query($query, array($id, $type));

		$notes = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$notes[] = $row;
			}
		}
		return $notes;
	}

	// Get docs
	public function getItemDocs($type, $id) {
		$query = "select * 
						  from fileupload f
						  inner join user u on f.owner_id = u.user_id 
						  where associate_id = ? and associate_to = ? 
						  order by file_create_date DESC";
		$resObj = $this->db->query($query, array($id, $type));

		$docs = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$docs[] = $row;
			}
		}
		return $docs;
	}

	// Get tasks
	public function getItemTasks($type, $id) {
		$query = "select SQL_CALC_FOUND_ROWS * , if (u.name, u.name, u.name) as owner, if (uf.task_status, uf.task_status, uf.task_status) as task_status , if(uf1.task_type,uf1.task_type,uf1.task_type) as task_type  , if(t.priority,t.priority,t.priority) as priority
						  from task t
						  inner join user u on t.task_owner_id = u.user_id 
						   left join user_fields uf on t.status = uf.no  
						  left join user_fields uf1 on t.type = uf1.no
						  where associate_id = ? and associate_to = ?";

		// ======== Datatable adjustments ========
		$aColumns = array_keys($this->taskTableCols);
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
		$resObj = $this->db->query($query, array($id, $type));

		$tasks = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->due_date = $this->convertDateTime($row->due_date);
				$row->task_create_date = $this->convertDateTime($row->task_create_date);
				$tasks[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countRes = $this->db->query('SELECT COUNT(`task_id`) as totalRows FROM task WHERE associate_id = ? and associate_to = ?', array($id, $type));
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $tasks;
	}

	// Get contacts
	public function getItemContacts($type, $id) {
		$query = "select SQL_CALC_FOUND_ROWS *, c.phone as cphone, co.phone, trim(concat(co.first_name, ' ', co.last_name)) as contact_name, if (u.name, u.name, u.name) as owner, co.owner_id as owner_id
						  from contact co 
						  left join company c on co.company_id = c.company_id 
						  left join user u on co.owner_id = u.user_id 
						  where co.associate_id = ? && co.associate_to = ?";

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

		$query .= " $sOrder $sLimit";
		$resObj = $this->db->query($query, array($id, $type));

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
		$countRes = $this->db->query('SELECT COUNT(`contact_id`) as totalRows FROM contact WHERE associate_id = ? and associate_to = ?', array($id, $type));
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $contacts;
	}

	// Get item quotes
	public function getItemQuotes($type, $id) {
		$query = "select SQL_CALC_FOUND_ROWS q.*, de.deal_name, c.company_name, co.first_name, co.last_name, u.user_id, u.name , if (u.name, u.name, u.username) as owner  , if(uf.quote_stage,uf.quote_stage,uf.quote_stage) as quote_stage_name, if(q.quote_stage,q.quote_stage,q.quote_stage) as quote_stage
						  from quote q 
						  left join company c on c.company_id = q.company_id 
						  left join contact co on co.contact_id = q.contact_id 
						  left join deal de on de.deal_id = q.deal_id 
						  left join user u on q.quote_owner_id = u.user_id
						  left join user_fields uf on q.quote_stage =uf.no
						  
						  where q.{$type}_id = ?";

		// ======== Datatable adjustments ========
		$aColumns = array_keys($this->quoteTableCols);
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
		$resObj = $this->db->query($query, array($id));

		$quotes = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->quote_create_date = $this->convertDateTime($row->quote_create_date);
				$quotes[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countRes = $this->db->query("SELECT COUNT(`quote_id`) as totalRows FROM quote WHERE {$type}_id = ?", array($id));
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $quotes;
	}
	
	
	
	
	
	
	
	
	// Get item orders
	public function getItemOrders($type, $id) {
		$query = "select SQL_CALC_FOUND_ROWS o.*, de.deal_name, c.company_name, co.first_name, co.last_name, u.user_id, u.name, if (u.name, u.name, u.username) as owner , if (so.so_stage, so.so_stage, so.so_stage) as so_stage_name 
						  from sales_order o 
						  left join company c on c.company_id = o.company_id 
						  left join contact co on co.contact_id = o.contact_id 
						  left join deal de on de.deal_id = o.deal_id 
						  left join user u on o.so_owner_id = u.user_id
						   left join user_fields so on o.so_stage = so.no 
						  where o.deal_id = ?";

		// ======== Datatable adjustments ========
		$aColumns = array_keys($this->orderTableCols);
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
		$resObj = $this->db->query($query, array($id));

		$quotes = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->so_create_date = $this->convertDateTime1($row->so_create_date);
				$order[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countRes = $this->db->query("SELECT COUNT(`deal_id`) as totalRows FROM sales_order WHERE deal_id = ?", array($id));
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $order;
	}
	

	// Get deal/quote products
	public function getDealProducts($id) {
		$query = "select SQL_CALC_FOUND_ROWS p.*, if(us.product_category,us.product_category,us.product_category) as category_name, if(us.no,us.no,us.no) as category
							from quote q
							inner join products p on p.org_id = q.org_id and p.product_id in (q.item1_id, q.item2_id, q.item3_id, q.item4_id, q.item5_id) 
							left join user_fields us on p.category = us.no
							where q.deal_id = ?
							group by p.product_id";
		$params = array($id);

		// ======== Datatable adjustments ========
		$aColumns = array_keys($this->productTableCols);
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
		$resObj = $this->db->query($query, $params);

		$products = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->create_date = $this->convertDateTime($row->create_date);
				$products[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countQuery = "select count(distinct p.product_id) as totalRows
									 from quote q
									 inner join products p on p.org_id = q.org_id and p.product_id in (q.item1_id, q.item2_id, q.item3_id, q.item4_id, q.item5_id) 
									 where q.deal_id = ?";
		$countRes = $this->db->query($countQuery, array($id));
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $products;
	}

	// Get emails
	public function getItemEmails($type, $id) {
		$query = "select SQL_CALC_FOUND_ROWS e.*, d.deal_name
						  from email as e
						  inner join deal d on e.deal_id = d.deal_id
						  where e.{$type}_id = ?";

		if (!in_array($type, array('quote', 'order'))) {
			 $query .= ' and e.quote_id = 0 and e.sales_order_id = 0';
		}

		// ======== Datatable adjustments ========
		$aColumns = array_keys($this->emailTableCols);
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
		$resObj = $this->db->query($query, array($id));

		$emails = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->send_date = $this->convertDateTime($row->send_date);
				$emails[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countQuery = "SELECT COUNT({$type}_id) as totalRows FROM email WHERE {$type}_id = ?";
		if (!in_array($type, array('quote', 'order'))) {
			$countQuery .= ' and quote_id = 0 and sales_order_id = 0';
		}
		$countRes = $this->db->query($countQuery, array($id));
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;
		
		return $emails;
	}

	// Get history
	public function getItemHistory($type, $id) {
		$query = "select * 
						  from user_activity ua
						  inner join user u on u.user_id = ua.user_id 
						  where associate_id = ? and associate_to = ?
						  order by create_date ASC";
						  
						  
		$resObj = $this->db->query($query, array($id, $type));

		$activity = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				if ($row->extra_info) $row->info = json_decode($row->extra_info);

				// Get note data if needed
				if ($row->info->note_id) {
					$row->info->note_data = $this->getNoteDetails($row->info->note_id);
				}

				if (!isset($activity[$row->action])) $activity[$row->action] = array();
				$activity[$row->action][] = $row;
			}
		}

		return $activity;
	}

	/****** ===== ***** END: fetch an ENTITY specific data ***** ===== *****/


	/****** ===== ***** START: fetch ONE ***** ===== *****/


		// Get an user details
	public function getUserDetails($user_id) {
		$resObj = $this->db->query("select * from user where user_id = ?", array($user_id));
		return $resObj->row();
	}

	// Get an user settings
	public function getUserSettings($user_id) {
		$resObj = $this->db->query("select * from user_settings where user_id = ?", array($user_id));
		return $resObj->row();
	}

	// Get an user report to ID
	public function getUserReporter($user_id = '') {
		if (!$user_id) $user_id = $this->user->user_id;
		$resObj = $this->db->query("select report_to_id from user where user_id = ?", array($user_id));
		$user = $resObj->row();
		return $user->report_to_id ? $user->report_to_id : $user_id;
	}

	// Get an user details by email ID
	public function getUserDetailsByEmail($email) {
		$resObj = $this->db->query("select * from user where user_email = ?", array($email));
		return $resObj->row();
	}

	// Get an user details by username
	public function getUserDetailsByUsername($username) {
		$resObj = $this->db->query("select * from user where username = ?", array($username));
		return $resObj->row();
	}

	// Get the organization details
	public function getOrganizationDetails($orgId = '') {
		$query = $orgId ? 'select *, o.id as id , o.terms_conditions as terms_conditions from organizations o left join organization_settings os on os.org_id = o.id where o.id='.$orgId : 'select * from organization order by sno desc limit 1';
		$resObj = $this->db->query($query);
		return $resObj->row();
	}
	
	public function getOrganizationTermsConditions() {
		$query =  'select terms_conditions from organizations where id="'.$this->user->org_id.'" limit 1';
		$resObj = $this->db->query($query);
		
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$TermsConditions = $row->terms_conditions;
			}
		}


		return $TermsConditions;
	}

	// Get the organization settings
	public function getOrganizationSettings($orgId = '') {
		$query = $orgId ? 'select * from organization_settings where org_id='.$orgId : 'select * from organization_settings order by id desc limit 1';
		$resObj = $this->db->query($query);
		return $resObj->row();
	}

	// Get deal details
	public function getDealDetails($id) {
		$resObj = $this->db->query("select *, de.org_id, co.designation as contact_designation, co.email as contact_email , c.company_name as company_name 
									from deal de 
									left join company c on de.deal_company_id = c.company_id 
									left join contact co on de.deal_contact_id = co.contact_id 
									left join user u on de.deal_owner_id = u.user_id 
									where deal_id = ?", array($id));
		return $resObj->row();
	}
	
	// Get deal details
	public function getStatusDetails($id) {
		$resObj = $this->db->query("select * from user_fields where no = ?", array($id));
		return $resObj->row();
	}	

	// Get a product details
	public function getProductDetails($id) {
		$resObj = $this->db->query("select * from products where product_id = ?", array($id));
		return $resObj->row();
	}

	// Get a contact details
	public function getContactDetails($id) {
		
			if($this->user->demo==0)
		{
		if ($this->user->org_id) {
			$query .= ' and org_id = ? or  org_id = ?';
			$params[] = $this->user->org_id;
			$params[] = '0';
		}
		}
		else
		{
			if ($this->user->org_id) {
			$query .= ' and org_id = ? ';
			$params[] = $this->user->org_id;
		
		}
		}
		
		$resObj = $this->db->query("select *, co.org_id, c.phone as cphone, co.phone 
		from contact co 
		left join company c on co.company_id = c.company_id 
		join user u on co.owner_id = u.user_id  where contact_id = ?", array($id));
		return $resObj->row();
	}

	// Get a note details
	public function getNoteDetails($id) {
		$noteRes = $this->db->query('select note_id, note, note_create_date from note where note_id = ?', $id);
		return $noteRes->row();
	}

	/****** ===== ***** END: fetch ONE ***** ===== *****/


	/****** ===== ***** START: MISCELLANEOUS ***** ===== *****/

	// Construct output for datatable
	public function constructDTOutput($rows, $cols, $indexCol = '', $linkPrefix = '', $linkColIndex = 0) {
		// Arrange data
		$dataRows = array();
		if ($rows[0]) {
			foreach ($rows as $row) {
				$dataRow = array();
				foreach ($cols as $colIndex => $col) {
					
					if ($col == 'actions') {
						$actionsHtml = '<a href="#" data-href="'.base_url().'settings/edituser/'.$row->$indexCol.'" data-bczajax-modal="true" data-action="edit"><i class="icon-edit-sign icon-large m-r-small"></i></a>';
						$actionsHtml .='<a href="#reset_user_confirmation_modal" data-toggle="modal" data-action="reset" data-id="'. $row->$indexCol .'"><i class="icon-undo icon-large"></i></a><a href="#deactivate_user_confirmation_modal" data-toggle="modal" data-action="deactivate" data-id="'. $row->$indexCol .'"><i class="icon-lock icon-large"></i></a>';
						if($row->$designation!='Admin')
						{
						$actionsHtml .= '<a href="#delete_user_confirmation_modal" data-toggle="modal" data-action="delete" data-id="'. $row->$indexCol .'" data-username="'.$row->$name.'"><i class="icon-remove-sign icon-large m-r-small"></i></a>';
						}
						
						
          	$dataRow[] = $actionsHtml;
					} else {
						$colLink = ($colIndex == ($linkColIndex - 1)) ? base_url().$linkPrefix.'/'.$row->$indexCol : '';
						$dataRow[] = ($colLink ? '<a class="text-primary" href="'.$colLink.'">' : '') . $row->$col . (!$colIndex ? '</a>' : '');
					}
				}
				$dataRows[] = $dataRow;
			}
		}

		// Output data
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $this->dtTotalCount,
			"iTotalDisplayRecords" => $this->dtDisplayCount,
			"aaData" => $dataRows
		);

		return $output;
	}
	
	public function getAllcampaigns() {
		
		$resObj = $this->db->query("select * from campaign where status = ? AND org_id = ?", array(0,$this->user->org_id));
		$campaign = array();
			if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$campaign[]= $row;
				
			}
		}
	
	return $campaign;
	}

	public function campaigncount($id,$type) {
		
		$resObj = $this->db->query("select ".$type." from campaign where campaign_id = ?", array($id));
		
		foreach ($resObj->result() as $row) {
				 $campaign_count= $row->$type;
			}
			$campaign_count++;
		
		$query = 'update campaign set '.$type.'= ? where campaign_id = ?' ;

		$res = $this->db->query($query, array($campaign_count,$id));

		
		}
		public function campaigncountsub($sub,$id,$type) {
		
		$resObj = $this->db->query("select ".$type." from campaign where campaign_id = ?", array($id));
		
		foreach ($resObj->result() as $row) {
				 $campaign_count= $row->$type;
			}
			$campaign_count++;
		
		$query = 'update campaign set '.$type.'= ? where campaign_id = ?' ;

		$res = $this->db->query($query, array($campaign_count,$id));
		
		$resObj = $this->db->query("select ".$type." from campaign where campaign_id = ?", array($sub));
		
		foreach ($resObj->result() as $row) {
				 $campaign_count= $row->$type;
			}
			$campaign_count--;
		
		$query = 'update campaign set '.$type.'= ? where campaign_id = ?' ;

		$res = $this->db->query($query, array($campaign_count,$sub));

		
		}
		
		
		
		public function campaignname($id) {
		
		$resObj = $this->db->query("select campaign_name from campaign where campaign_id = ?", array($id));
		
		foreach ($resObj->result() as $row) {
				 $campaign_name= $row->campaign_name;
			}
			
		return $campaign_name;
		}
	
	
	public function constructDTOutput1($rows, $cols, $indexCol = '',$super_admin="",$name="", $linkPrefix = '', $linkColIndex = 0) {
		// Arrange data
		$dataRows = array();
		if ($rows[0]) {
			foreach ($rows as $row) {
				$dataRow = array();
				foreach ($cols as $colIndex => $col) {
					
					if ($col == 'actions') {
						$actionsHtml = '<a class="edit_users" href="#" data-href="'.base_url().'settings/edituser/'.$row->$indexCol.'" data-bczajax-modal="true" data-action="edit"><i class="icon-edit-sign icon-large m-r-small"></i></a>';
						$actionsHtml .='<a class="reset_users" href="#reset_user_confirmation_modal" data-toggle="modal" data-action="reset" data-id="'. $row->$indexCol .'"><i class="icon-undo icon-large"></i></a><a href="#deactivate_user_confirmation_modal" data-toggle="modal" data-action="deactivate" data-id="'. $row->$indexCol .'"  data-username="'.$row->$name.'"><i class="icon-lock icon-large" ></i></a>';
						if($row->$super_admin!='1')
						{
						$actionsHtml .= '<a class="delete_users" href="#delete_user_confirmation_modal" data-toggle="modal" data-action="delete" data-id="'. $row->$indexCol .'" data-username="'.$row->$name.'"><i class="icon-remove-sign icon-large m-r-small" ></i></a>';
						}
						else
						{
							$actionsHtml .= '<a class="delete_users_modal" href="#delete_modal" data-toggle="modal" data-action="delete"  data-username="'.$row->$name.'"><i class="icon-remove-sign icon-large m-r-small"></i></a>';
						}
						
						
          	$dataRow[] = $actionsHtml;
					} else {
						$colLink = ($colIndex == ($linkColIndex - 1)) ? base_url().$linkPrefix.'/'.$row->$indexCol : '';
						$dataRow[] = ($colLink ? '<a class="text-primary" href="'.$colLink.'">' : '') . $row->$col . (!$colIndex ? '</a>' : '');
					}
				}
				$dataRows[] = $dataRow;
			}
		}

		// Output data
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $this->dtTotalCount,
			"iTotalDisplayRecords" => $this->dtDisplayCount,
			"aaData" => $dataRows
		);

		return $output;
	}
	
	
	
	
	//inactive Users
	public function constructinactiveDTOutput($rows, $cols, $indexCol = '',$super_admin="",$name="", $linkPrefix = '', $linkColIndex = 0) {
		// Arrange data
		$dataRows = array();
		if ($rows[0]) {
			foreach ($rows as $row) {
				$dataRow = array();
				foreach ($cols as $colIndex => $col) {
					
					if ($col == 'actions') {
						$actionsHtml = '<a class="edit_users" href="#" data-href="'.base_url().'settings/editUser1/'.$row->$indexCol.'" data-bczajax-modal="true" data-action="edit"><i class="icon-edit-sign icon-large m-r-small"></i></a>';
						$actionsHtml .='<a href="#reset_user_confirmation_modal" data-toggle="modal" data-action="reset" data-id="'. $row->$indexCol .'"><i class="icon-undo icon-large"></i></a><a href="#activate_user_confirmation_modal" data-toggle="modal" data-action="activate" class="inclass" data-id="'. $row->$indexCol .'"  data-username="'.$row->$name.'"><i class="icon-unlock icon-large"></i></a>';
						if($row->$super_admin!='1')
						{
						$actionsHtml .= '<a href="#delete_user_confirmation_modal1" data-toggle="modal" data-action="delete1" data-id="'. $row->$indexCol .'" data-username="'.$row->$name.'"><i class="icon-remove-sign icon-large m-r-small"></i></a>';
						}
						else
						{
							$actionsHtml .= '<a href="#delete_modal" data-toggle="modal" data-action="delete" data-username="'.$row->$name.'"><i class="icon-remove-sign icon-large m-r-small"></i></a>';
						}
						
						
          	$dataRow[] = $actionsHtml;
					} else {
						$colLink = ($colIndex == ($linkColIndex - 1)) ? base_url().$linkPrefix.'/'.$row->$indexCol : '';
						$dataRow[] = ($colLink ? '<a class="text-primary" href="'.$colLink.'">' : '') . $row->$col . (!$colIndex ? '</a>' : '');
					}
				}
				$dataRows[] = $dataRow;
			}
		}

		// Output data
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $this->dtTotalCount,
			"iTotalDisplayRecords" => $this->dtDisplayCount,
			"aaData" => $dataRows
		);

		return $output;
	}
	

	// Log user activity
	public function logUserActivity($data) {
		$params = array($this->user->user_id, $data['action'], $data['type'], $data['id'], $data['info'], $this->getCurrTime());
		$this->db->query('insert into user_activity (user_id, action, associate_to, associate_id, extra_info, create_date) values (?, ?, ?, ?, ?, ?)', $params);
	}

	// Construct settings block
	public function getSettingsBlock($fields, $type) {
    $items = array();
    foreach ($fields as $field) { 
      if ($field->$type)	$items[] = $field;
    }

    return $items;
	}

	// Format Date
	public function formatDate($date, $format = '') {
		if (!$format) {
			$format = $this->user->settings->date_format ? $this->user->settings->date_format : ($this->user->organization->date_format ? $this->user->organization->date_format : 'd/m/Y');
		}
		return date($format, strtotime($date));
	}

	// Generate a random password
	public function randPass($length = 8, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') {
    return substr(str_shuffle($chars), 0, $length);
	}

	// Get next auto increment id of a table
	public function getTableAutoID($table="user") {
		$resObj = $this->db->query("show table status like ?", array($table));
		if ($resObj->num_rows()) {
			$row = $resObj->row();
		}
		return $row->Auto_increment;
	}

	// Get organization logo
	public function getOrgLogo() {
		return ($_SESSION['bcz_org_logo']?$_SESSION['bcz_org_logo']:(base_url().'assets/img/logo.jpg'));
	}

	// Send email
	public function sendBCZEmail($from = '', $to = '' , $cc = '', $bcc = '', $subject = '', $message = '', $attachments = array()) {
		// Get global values
		if (!$from) $from = $this->from ? $this->from : $this->user->user_email;
		if (!$to) $to = $this->to;
		if (!$cc) $cc = $this->cc;
		if (!$bcc) $bcc = $this->bcc;
		if (!$subject) $subject = $this->subject;
		if (!$message) $message = $this->message;
		if (!$attachments[0]) $attachments = $this->attachments;

		// Set email properties and send
		$this->load->library('email');
		$this->email->set_newline("\r\n");
		if ($from) $this->email->from($from);
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);

		if ($cc) $this->email->cc($cc);
		if ($bcc) $this->email->bcc($bcc); 

		if ($attachments[0]) {
			foreach ($attachments as $aid => $attachment) {
				// $attachmentPath = $this->uploadPath . $attachment;
				if (file_exists($attachment)) {
					$this->attachmentType ? $this->email->attach($attachment, $this->attachmentType) : $this->email->attach($attachment);
				}
			}
		}

		return $this->email->send();
	}

	// Get quote validity
	public function getQuoteValidity() {
		$resObj = $this->db->query("select quote_validity from user_fields where quote_validity is not null and quote_validity != '' order by sort_order limit 1");
		return $resObj->row();
	}

	// Get a stage probablity
	public function getStageProbability($stage) {
		$resObj = $this->db->query("select probability from user_fields where stage = ?", $stage);
		$resRow = $resObj->row();
		return $resRow->probability;
	}

	// Export data
	public function exportData($data, $filename='exported_date.xls') {
		// Download file
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header("Content-Type: application/vnd.ms-excel");

		// Write data to file
		foreach ($data as $key => $row) {
			$row = (array) $row;

			// Set the field/column names as first row
			if(!$key) {
				echo implode("\t", array_keys($row)) . "\r\n";
			}
			echo implode("\t", array_values($row)) . "\r\n";
		}
	}

	// Import data
	
	
	public function importData($file, $table,$Cols) {
    
    $handle = fopen($file,"r"); 
	$count=0;
	$s=0;
	$datas1="";
	$datas2=-1;
  //loop through the csv file and insert into database 
    do { 
	$datas2++;
	$s++;
	$datas="";
	$currDateTime = $this->getCurrTime();
        if ($data[0]) { 
		for($i=0;$i<12;$i++)
		{
			if($i==11)
			{
				$datas.="'".str_replace("'","\'",str_replace('"','\"',addslashes($data[$i])))."'" ;
			}
			else
			{
            $datas.="'".str_replace('"','\"',str_replace('"','\"',addslashes($data[$i])))."'," ;
			}
		}
		if($datas2==1){
			$datas1.="values('".$this->user->org_id."',".$datas.",'".$currDateTime."','".$currDateTime."')";
		}
		else
		{
			$datas1.=",('".$this->user->org_id."',".$datas.",'".$currDateTime."','".$currDateTime."')";
		}
			
        } 
    } while (($data = fgetcsv($handle,10000))&& ($s < 1000)); 
	
	
	$cquery = 'insert into '.$table.' (' . $Cols. ')'.$datas1;
			$cres = $this->db->query($cquery);

fclose($file);


	}
	
	public function importDatas($file, $table,$Cols,$count) {
    $handle = fopen($file,"r"); 
	$count;
	$s=0;
	$datas1="";
	$datas2=-1;
  //loop through the csv file and insert into database 
    do { 
	$datas2++;
	$s++;
	$datas="";
	$currDateTime = $this->getCurrTime();
        if ($data[0]) { 
		for($i=0;$i<$count-3;$i++)
		{
			if($i==$count-4)
			{
				$tb_data=str_replace("'","\'",str_replace('"','\"',addslashes($data[$i])));
				$datas.="'".$tb_data."'" ;
			}
			else
			{
				$tb_data=str_replace("'","\'",str_replace('"','\"',addslashes($data[$i])));
            $datas.="'".$tb_data."'," ;
			}
		}
		
		if($datas2==1){
			$datas1.="values('".$this->user->org_id."',".$datas.",'".$currDateTime."','".$currDateTime."')";
		}
		else
		{
			$datas1.=",('".$this->user->org_id."',".$datas.",'".$currDateTime."','".$currDateTime."')";
		}
			
        } 
    } while (($data = fgetcsv($handle,10000))); 
	
	
	$cquery = 'insert into '.$table.' (' . $Cols. ')'.$datas1;
			$cres = $this->db->query($cquery);

fclose($file);


	}
	
	
	
	public function importDatasproducts($file, $table,$Cols,$count) {
    $handle = fopen($file,"r"); 
	$count;
	$s=0;
	$datas1="";
	$datas2=-1;
  //loop through the csv file and insert into database 
    do { 
	$datas2++;
	$s++;
	$datas="";
	$currDateTime = $this->getCurrTime();
        if ($data[0]) { 
		for($i=0;$i<$count-3;$i++)
		{
			if($i==$count-4)
			{
				$tb_data=str_replace("'","\'",str_replace('"','\"',addslashes($data[$i])));
				$datas.="'".$tb_data."'" ;
			}
			else
			{
				$tb_data=str_replace("'","\'",str_replace('"','\"',addslashes($data[$i])));
            $datas.="'".$tb_data."'," ;
			}
		}
		
		if($datas2==1){
			$datas1.="values('".$this->user->org_id."',".$datas.",'".$currDateTime."','".$currDateTime."')";
		}
		else
		{
			$datas1.=",('".$this->user->org_id."',".$datas.",'".$currDateTime."','".$currDateTime."')";
		}
			
        } 
    } while (($data = fgetcsv($handle,10000))); 
	
	
	$cquery = 'insert into '.$table.' (' . $Cols. ')'.$datas1;
			$cres = $this->db->query($cquery);

fclose($file);


	}
	
	

	// Organization access checkup
	public function orgAccessCheck($entityOrgId) {
// echo $entityOrgId . " === " . $this->user->org_id; exit;	
		if (($this->user->org_id != $entityOrgId)&&($entityOrgId!=0)&&($this->user->demo==0))
		{
			 show_404();
		}
		
	}

	// Get current date time
	public function getCurrTime($format = 'Y-m-d h:i:s') {
		$dObj = new DateTime();
		//$dFormat = $this->insertDateFormat ? $this->insertDateFormat : $format;
		return $dObj->format($format);
	}

	// Convert timezone
	
	public function convertDateTime1($dateStr) {
		
		
		$dateStr=explode(" ",$dateStr);
		
		$date=explode("-",$dateStr[0]);
		
		
		
		return $date[2]."/".$date[1]."/".$date[0];
		
	}
	public function convertDateTime($dateStr, $format = '') {
		if (!$dateStr || in_array($dateStr, array('0000-00-00 00:00:00', '0000-00-00'))) {
			return $this->noDataChar;
		}
		

		if (!$format) $format = $this->user->settings->date_format ? $this->user->settings->date_format : ($this->user->organization->date_format ? $this->user->organization->date_format : 'd/m/Y');

		$timezone = $this->user->settings->timezone ? $this->user->settings->timezone : 'UTC';
		$dateObj = new DateTime($dateStr);
		$dateObj->setTimezone(new DateTimeZone($timezone));

		return $dateObj->format($format);
	}

	// Fomat days count
	public function formatDays($d) {
		$y = 0;
		if ($d > 365) {
			$y = round($d/365, 0);
			$d = $d % 365;
		}

		return ($y ? $y.' Year'.($y>1?'s ':' ') : '') . $d . ' Day' . ($d==1?'':'s');
		// return array($years, $days);
	}

	/**
 	* Return an array of timezones
 	* 
 	* @return array
 	*/
	function timezoneList()
	{
    $timezoneIdentifiers = DateTimeZone::listIdentifiers();
    $utcTime = new DateTime('now', new DateTimeZone('UTC'));

    $tempTimezones = array();
    foreach ($timezoneIdentifiers as $timezoneIdentifier) {
      $currentTimezone = new DateTimeZone($timezoneIdentifier);

      $tempTimezones[] = array(
        'offset' => (int)$currentTimezone->getOffset($utcTime),
        'identifier' => $timezoneIdentifier
      );
    }

    // Sort the array by offset,identifier ascending
    usort($tempTimezones, function($a, $b) {
		return ($a['offset'] == $b['offset'])
			? strcmp($a['identifier'], $b['identifier'])
			: $a['offset'] - $b['offset'];
    });

		$timezoneList = array();
    foreach ($tempTimezones as $tz) {
			$sign = ($tz['offset'] > 0) ? '+' : '-';
			$offset = gmdate('H:i', abs($tz['offset']));
			// $timezoneList[$tz['identifier']] = '(UTC ' . $sign . $offset . ') ' .
			// $tz['identifier'];

			// get current time in this timezone
			$time = new DateTime(NULL, new DateTimeZone($tz['identifier']));

			// convert to am and pm
			$ampm = ' ('. $time->format('g:ia'). ')';

			//create the display string
			$timezoneList[$tz['identifier']] = '(UTC ' . $sign . $offset . ') ' .$tz['identifier'].' '. $time->format('H:i') . $ampm;
    }

    return $timezoneList;
	}
	
		public function getPrefixSequence() {
		$resObj = $this->db->query("select * from numbering where org_id='".$this->user->org_id."' ");
		
				$Sequence = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				
				$Sequence[] = $row;
			}
		}
		
		
		return $Sequence;
	}
	
	public function getPrefixSequenceModule($where) {
		$resObj = $this->db->query("select * from numbering where module='".$where."' AND  org_id='".$this->user->org_id."' ");
		
				$Sequence = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				
				$Sequence['prefix'] = $row->prefix;
				$Sequence['sequence'] = $row->sequence;
				$Sequence['numbering_id'] = $row->numbering_id;
			}
		}
		
		
		return $Sequence;
	}
	
	
	public function getStatusName($no,$status) {
		
		
		$resObj = $this->db->query("select * from user_fields where  no='".$no."' ");
		if ($resObj->num_rows()) {
			
			foreach ($resObj->result() as $row) {
				 $StatusName = $row->$status;
		}
		}
		
		return $StatusName;
	
	}
	
	public function getNextIdAndPreviousId($query,$id,$name_id,$org_id) {
	$resObj = $this->db->query($query, array($id,$org_id));
		if ($resObj->num_rows()) {
			
			foreach ($resObj->result() as $row) {
				 $id_name = $row->$name_id;
		}
		}
		else
		{
			$id_name =0;
		}
		
		return $id_name;
		
	}
	
	
public function convertShares($shareValue){
    if($shareValue < 1000){
    return $shareValue;
    }elseif($shareValue > 100000 ){
          return number_format($shareValue/1000000,2) . " M";
    }else{
        return number_format($shareValue/1000,2) . " K";
    }
  } 
	
	
	
	
	/****** ===== ***** END: MISCELLANEOUS ***** ===== *****/
	
}