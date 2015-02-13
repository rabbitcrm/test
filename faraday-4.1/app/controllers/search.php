<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class search extends BCZ_Controller {

	private $filters = '';

	public function __construct()
	{
		parent::__construct();
	}

	public function index() {
		$str = trim($_GET['q']);
		if ($str) $results = $this->getMatches($str);

		$data['content'] = "SearchView";
		$data['matches'] = $results;
		$this->load->view('FirstLayoutView', $data);
	}

	public function matchesJson() {
		$str = trim($_GET['query']);
		$results = $this->getMatches($str);

		$res = array();
		if (count($results)) {
			$res['success'] = true;
			$res['results'] = $results; //array_slice($results, 0, 10);	// Send only first 10 results
		} else {
			$res['success'] = false;
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	public function getMatches($str) {
		
		
		
		// Get matched contacts
		$contacts = $this->searchContacts($str);

		// Get matched deals
		$deals = $this->searchDeals($str);

		// Get matched companies
		$companies = $this->searchCompanies($str);

		// Get matched leads
		$leads = $this->searchLeads($str);
		
		// Get matched ticket
		$ticket = $this->searchcases($str);
	
		// Combine all results
		$results = array();
		$results = array_merge($contacts, $deals, $companies, $leads,$ticket);

		// usort($results, array($this, 'sortResults'));	// Sort results by date

		return $results;
	}

	// Get matched contacts
	private function searchContacts($queryString, $limit = 8) {
				if($this->user->demo==0)
		{
		
		$query = "select contact_id as id, first_name as name, last_name, email, company_id,contact_modify_date as date, contact_create_date, con_description as summary from contact where (org_id='". $this->user->org_id."' or org_id='0') AND (CONCAT(first_name, ' ', last_name ) LIKE '%$queryString%' or email like '%$queryString%' or mobile like '%$queryString%' or phone like '%$queryString%' ) limit $limit";
		
		}
		else
		{
			$query = "select contact_id as id, first_name as name, last_name, email, company_id,contact_modify_date as date, contact_create_date, con_description as summary from contact where (org_id='". $this->user->org_id."') AND (CONCAT(first_name, ' ', last_name ) LIKE '%$queryString%' or email like '%$queryString%' or mobile like '%$queryString%' or phone like '%$queryString%') limit $limit";
			
		}

		 
		$resObj = $this->db->query($query);

		$contacts = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				
				$query1 = "select company_name from company where company_id='". $row->company_id."'";
				$resObj1 = $this->db->query($query1);
				foreach ($resObj1->result() as $row1) {
					
					$company_name=$row1->company_name;
					
				}
				
				
				
				$remail = $row->email; unset($row->email);

				$row->name .= ($row->name ? ' ' : '') . $row->last_name; unset($row->last_name);
				if (!$row->date) $row->date = $row->contact_create_date;
				unset($row->contact_create_date);
				$row->type = 'contact';
				$row->urlPrefix = 'contacts/details';
				//$contacts[] = $row;

				// Email matches will appear as one more contact entry
				$drow = new stdClass();
				$drow->id = $row->id;
				$drow->name = $row->name."<br/>".$company_name ;
				$drow->summary = $row->summary;
				$drow->date = $row->date;
				$drow->type = $row->type;
				$drow->urlPrefix = $row->urlPrefix;
				$contacts[] = $drow;
			}
		}

		return $contacts;
	}

	// Get matched deals
	private function searchDeals($queryString, $limit = 8) {
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	/*	
		$params = array();

		$query = "select SQL_CALC_FOUND_ROWS *, if (u.name, u.name, u.name) as owner, de.deal_owner_id as deal_owner_id ,u.user_status as user_status  , if(uf.stage,uf.stage,uf.stage) as stage_name , if(de.stage,de.stage,de.stage) as stage , if(de.source,de.source,de.source) as  source
						  from deal de 
						  left join user_fields uf on de.stage =uf.no
						  left join company c on de.deal_company_id = c.company_id
						  left join user u on (de.deal_owner_id = u.user_id) ";
		//$params[] = '0';
		$whereCond = ''; // ' where de.stage != ? and de.stage != ?';
		 //array('Won', 'Lost');
		 
		 
		

		$addwhereCond="";
		$whereCond = ''; // ' where de.stage != ? and de.stage != ?';
		 //array('Won', 'Lost');

		// Role checkup

		if($this->user->demo==0)
		{

		if ($this->isManager) {
			$addwhereCond="left join user u on (de.deal_owner_id = u.user_id)";
			$whereCond .= ($whereCond ? ' and ' : ' where ') . ' ( de.org_id = ? or de.org_id = ? )and (u.report_to_id = ? or de.deal_owner_id = ? )';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			//$params[] = $this->user->user_id;
			//$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ($whereCond ? ' and' : ' where') . ' (de.org_id = ? or de.org_id = ? ) and (de.deal_owner_id = ?  or c.assign_to = ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
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
				$addwhereCond="left join user u on (de.deal_owner_id = u.user_id)";
			$whereCond .= ($whereCond ? ' and ' : ' where ') . ' de.org_id = ? and (u.report_to_id = ? or de.deal_owner_id = ? )';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			//$params[] = $this->user->user_id;
			//$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ($whereCond ? ' and' : ' where') . ' de.org_id = ? and (de.deal_owner_id = ?  or c.assign_to = ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ($whereCond ? ' and' : ' where') . ' de.org_id = ?';
			$params[] = $this->user->org_id;
		}
		
		}
		$sLimit = "limit $limit";
		$sOrder = "ORDER BY deal_id desc";
		$query .= " $whereCond $sOrder $sLimit";
		$resObj = $this->db->query($query, $params);
		
		
		
		
		
		*/
		
		
		
		
		
		
		
		
		
		
		
		if($this->user->demo==0)
		{
		$query = "select deal_id as id, deal_name as name, deal_modify_date as date, deal_create_date, summary from deal de left join user u on (de.deal_owner_id = u.user_id)  where (de.org_id='". $this->user->org_id."' or de.org_id='0') AND ( de.deal_name like '%$queryString%') limit $limit";
		}
		else
		{
			$query = "select deal_id as id, deal_name as name, deal_modify_date as date, deal_create_date, summary from deal de left join user u on (de.deal_owner_id = u.user_id)   where (de.org_id='". $this->user->org_id."') AND ( de.deal_name like '%$queryString%') limit $limit";
		}
		$resObj = $this->db->query($query);

		$deals = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->type = 'Opportunity';
				if (!$row->date) $row->date = $row->deal_create_date;
				unset($row->deal_create_date);
				$row->urlPrefix = 'deals/details';
				$deals[] = $row;
			}
		}

		return $deals;
	}

	// Get matched contacts
	private function searchCompanies($queryString, $limit = 8) {
		
		if($this->user->demo==0)
		{
			
		$query = "select company_id as id, company_name as name, company_modify_date as date, company_create_date, com_description as summary from company where (org_id='". $this->user->org_id."' or org_id='0') AND  (company_name like '%$queryString%') AND   (associate_to != 'lead') limit $limit";
		}
		else
		{
				$query = "select company_id as id, company_name as name, company_modify_date as date, company_create_date, com_description as summary from company where (org_id='". $this->user->org_id."') AND  (company_name like '%$queryString%')  AND   (associate_to != 'lead') limit $limit";
		}
		$resObj = $this->db->query($query);

		$companies = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				if (!$row->date) $row->date = $row->company_create_date;
				unset($row->company_create_date);
				$row->type = 'Account';
				$row->urlPrefix = 'companies/details';
				$companies[] = $row;
			}
		}

		return $companies;
	}

	// Get matched leads
	private function searchLeads($queryString, $limit = 8) {
		
		if($this->user->demo==0)
		{
			
		 $query = "select lead_id as id, first_name as name, last_name, company_name, lead_modify_date as date, lead_create_date, lead_description as summary from lead where (org_id='". $this->user->org_id."' or org_id='0'  ) AND  (CONCAT(first_name, ' ', last_name ) LIKE '%$queryString%' or email like '%$queryString%' ) limit $limit";
		
		}
		else
		{
			$query = "select lead_id as id, first_name as name, last_name, company_name, lead_modify_date as date, lead_create_date, lead_description as summary from lead where (org_id='". $this->user->org_id."') AND  (CONCAT(first_name, ' ', last_name ) LIKE '%$queryString%' or email like '%$queryString%'  ) limit $limit";
		}
		$resObj = $this->db->query($query);

		$leads = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				

				// Lead row
				
				$lcomp = $row->company_name;
				
				$row->name .= ($row->name ? ' ' : '') . $row->last_name; unset($row->last_name);
				
				
				 unset($row->company_name);
				
				if (!$row->date) $row->date = $row->lead_create_date;
				unset($row->lead_create_date);
				$row->type = 'lead';
				$row->urlPrefix = 'leads/details';
			//	$leads[] = $row;

				// Company matches will appear as one more contact entry
				$crow = new stdClass();
				$crow->id = $row->id;
				$crow->name = $row->name;
				$crow->summary = $row->summary;
				$crow->date = $row->date;
				$crow->type = $row->type;
				$crow->urlPrefix = $row->urlPrefix;
				$leads[] = $crow;
			}
		}

		return $leads;
	}
	
	// Get matched Ticket
	private function searchcases($queryString, $limit = 8) {
		if($this->user->demo==0)
		{
		$query = "select case_id as id,case_no, case_title as name, case_title, case_create_date, case_description as summary from cases where  (org_id='". $this->user->org_id."' or org_id='0') AND (case_title like '%$queryString%' or case_no like '%$queryString%') limit $limit";
		}
		else
		{
		  $query = "select case_id as id,case_no, case_title as name, case_title, case_create_date, case_description as summary from cases where  (org_id='". $this->user->org_id."') AND (case_title like '%$queryString%' or case_no like '%$queryString%') limit $limit";
		  
		}
		  
		$resObj = $this->db->query($query);
$case = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				
				//$lcomp = $row->case_title; unset($row->case_title);
				if (!$row->date) $row->date = $row->case_create_date;
				unset($row->case_create_date);
			//	$row->type = 'Ticket';
				//$row->urlPrefix = 'cases/details';
				//$case[] = $row;
				// Company matches will appear as one more contact entry
				$crow = new stdClass();
				$crow->id = $row->id;
				$crow->name = $row->case_title." ".$row->case_no;
				$crow->summary = $row->summary;
				$crow->date = $row->date;
				$crow->type = 'Ticket';
				$crow->urlPrefix = 'cases/details';
				$case[] = $crow;
				
			}
		}
		

		return $case;
	}

	// Sort search results
	public function sortResults($a, $b) {
		return strcmp($b->date, $a->date);
	}
/*
	// Get matched deals
	private function searchDeals($queryString, $limit = 10) {
		$query = "select deal_id as id, deal_name as name, deal_modify_date as date, deal_create_date from deal where deal_name like '%$queryString%' order by deal_modify_date desc, deal_create_date desc limit $limit";
		$resObj = $this->db->query($query);

		$deals = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->type = 'deal';
				if (!$row->date) $row->date = $row->deal_create_date;
				unset($row->deal_create_date);
				$row->urlPrefix = 'deals/details';
				$deals[] = $row;
			}
		}

		return $deals;
	}

	// Get matched contacts
	private function searchContacts($queryString, $limit = 10) {
		$query = "select contact_id as id, first_name as name, last_name, contact_modify_date as date, contact_create_date from contact where (last_name like '%$queryString%' or first_name like '%$queryString%') order by contact_modify_date desc, contact_create_date desc limit $limit";
		$resObj = $this->db->query($query);

		$contacts = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->name .= ($row->name ? ' ' : '') . $row->last_name; unset($row->last_name);
				if (!$row->date) $row->date = $row->contact_create_date;
				unset($row->contact_create_date);
				$row->type = 'contact';
				$row->urlPrefix = 'contacts/details';
				$contacts[] = $row;
			}
		}

		return $contacts;
	}

	// Get matched contacts
	private function searchCompanies($queryString, $limit = 10) {
		$query = "select company_id as id, company_name as name, company_modify_date as date, company_create_date from company where company_name like '%$queryString%' order by company_modify_date desc, company_create_date desc limit $limit";
		$resObj = $this->db->query($query);

		$companies = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				if (!$row->date) $row->date = $row->company_create_date;
				unset($row->company_create_date);
				$row->type = 'company';
				$row->urlPrefix = 'companies/details';
				$companies[] = $row;
			}
		}

		return $companies;
	}

	// Get matched leads
	private function searchLeads($queryString, $limit = 10) {
		$query = "select lead_id as id, first_name as name, last_name, lead_modify_date as date, lead_create_date from lead where (last_name like '%$queryString%' or first_name like '%$queryString%') order by lead_modify_date desc, lead_create_date desc limit $limit";
		$resObj = $this->db->query($query);

		$leads = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				// Lead row
				$row->name .= ($row->name ? ' ' : '') . $row->last_name; unset($row->last_name);
				if (!$row->date) $row->date = $row->lead_create_date;
				unset($row->lead_create_date);
				$row->type = 'lead';
				$row->urlPrefix = 'leads/details';
				$leads[] = $row;


			}
		}

		return $leads;
	}
*/	


public function matchescompanyJson() {
		$str = trim($_GET['query']);
		$results = $this->getcompanyMatches($str);

		$res = array();
		if (count($results)) {
			$res['success'] = true;
			$res['results'] = $results; //array_slice($results, 0, 10);	// Send only first 10 results
		} else {
			$res['success'] = false;
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}
	
	
	private function getcompanyMatches($queryString, $limit = 8) {
		 $query = "select company_id as id, company_name as name from company where  (org_id='". $this->user->org_id."') AND (company_name like '%$queryString%') AND (associate_to != 'lead') limit $limit";
		$resObj = $this->db->query($query);

		$company_name = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$lcomp = $row->name; 
				$crow = new stdClass();
				$crow->id = $row->id;
				$crow->name = $lcomp;
				$crow->type = $row->type;
				$crow->urlPrefix = $row->id;
				$company_name[] = $crow;
				
			}
		}
		

		return $company_name;
	}
	
	public function matchesfullcontactJson() {
		$deal_company_id = trim($_GET['deal_company_id']);
		$opp_company_name = trim($_GET['opp_company_name']);
		$str = trim($_GET['query']);
		$results = $this->getfullcontactMatches($str,$deal_company_id,$opp_company_name);

		$res = array();
		if (count($results)) {
			$res['success'] = true;
			$res['results'] = $results; //array_slice($results, 0, 10);	// Send only first 10 results
		} else {
			$res['success'] = false;
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}
	
		private function getfullcontactMatches($queryString,$company_id,$company_name, $limit = 8) {
		
		if($company_id=="")
		{
			$company_name=$company_name;
				$query = 'select company_id as id  from company where org_id=? AND company_name=? ORDER BY company_id DESC limit 0,1';
				$params[] = $this->user->org_id;
				$params[] = $company_name;
				$resObj = $this->db->query($query, $params);
				$row = $resObj->row();
				$id = $row->id;
				$company_id = $id;
		}
		
		else
		{
		 $query = "select contact_id as id, last_name as lname, first_name  as fname from contact where  (org_id='". $this->user->org_id."') AND (company_id='".$company_id."')  limit $limit";
		$resObj = $this->db->query($query);

		$contact_name = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$lcomp = $row->fname." ".$row->lname." "; 
				$crow = new stdClass();
				$crow->id = $row->id;
				$crow->name = $lcomp;
				$crow->type = $row->type;
				$crow->urlPrefix = $row->id;
				$contact_name[] = $crow;
				
			}
		}
		}

		return $contact_name;
	}
	
	
	public function matchescontactJson() {
		$deal_company_id = trim($_GET['deal_company_id']);
		$opp_company_name = trim($_GET['opp_company_name']);
		$str = trim($_GET['query']);
		$results = $this->getcontactMatches($str,$deal_company_id,$opp_company_name);

		$res = array();
		if (count($results)) {
			$res['success'] = true;
			$res['results'] = $results; //array_slice($results, 0, 10);	// Send only first 10 results
		} else {
			$res['success'] = false;
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}
	
	
		private function getcontactMatches($queryString,$company_id,$company_name, $limit = 8) {
		
		
		
		
		
		
		if($company_id=="")
		{
			$company_name=$company_name;
				$query = 'select company_id as id  from company where org_id=? AND company_name=? ORDER BY company_id DESC limit 0,1';
				$params[] = $this->user->org_id;
				$params[] = $company_name;
				$resObj = $this->db->query($query, $params);
				$row = $resObj->row();
				$id = $row->id;
				$company_id = $id;
		}
		else
		{
		
		
		 $query = "select contact_id as id, last_name as lname, first_name  as fname from contact where  (org_id='". $this->user->org_id."') AND (company_id='".$company_id."') AND (first_name like '%$queryString%' or last_name like '%$queryString%') limit $limit";
		$resObj = $this->db->query($query);

		$contact_name = array();
		if ($resObj->num_rows()) {
			
			foreach ($resObj->result() as $row) {
				$lcomp = $row->fname." ".$row->lname." "; 
				$crow = new stdClass();
				$crow->id = $row->id;
				$crow->name = $lcomp;
				$crow->type = $row->type;
				$crow->urlPrefix = $row->id;
				$contact_name[] = $crow;
				
			
		}
		}
		}

		return $contact_name;
	}
	
	
	
}