<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dashboard extends BCZ_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		//print_r(wincache_ucache_meminfo());
		// Post something...
		if (isset($_POST['post_body'])) {
			$data = $this->postMessage();
		}

		$this->bodyClass = $this->pageTitle = $this->pageDesc = "Home";
		$data['content'] = 'DashboardView';

		// Data for charts
		// $sales = array();
		// $totalSales = $this->getSalesAmount();
		// $yearSales = $this->getSalesAmount('year');
		// if ($totalSales && $yearSales) {
		// 	$sales['total'] = $totalSales;
		// 	$sales['year'] = $yearSales;
		// 	$sales['yearPercentage'] = round($yearSales * 100 / $totalSales);
		// 	$sales['month'] = $this->getSalesAmount('month');
		// 	$sales['monthPercentage'] = round($sales['month'] * 100 / $totalSales);
		// }
		// $data['sales'] = $sales;

		// Data for snapshot block
		// $fields = $this->getAllUserFields();	// Get all user fields
		// $dealAmounts = $this->getDealAmounts();
		// $dealPercentages = array();
		// foreach ($fields as $stage) {
		// 	if ($stage->stage) {
		// 		$dealPercentages[$stage->stage] = $dealAmounts[$stage->stage] ? round($dealAmounts[$stage->stage] * 100 / $dealAmounts['total']) : 0;
		// 	}
		// }
		// $data['percentages'] = $dealPercentages;
		// $data['amounts'] = $dealAmounts;

		// Data for notes block
		$data['notes'] = $this->getRecentNotes();
		
		// Data for Today Task block
		$data['tasks'] = $this->getTodayTask();

		// Data for Today Task block
		$data['complet_tasks'] = $this->getComTask();

		// Data for Today Task block
		$data['coming_tasks'] = $this->getComingTask();
		
		// Data for recent deals block
		$data['deals'] = $this->getRecentDeals();

		// Data for top deals block
		$data['topDeals'] = $this->getTopDeals();

		// Get discussions
		$data['posts'] = $this->getTopPosts();
		
		// Get leads
		$data['newleads'] = $this->getnewleads();
		
		// Get companys
		$data['newcompanys'] = $this->getnewcompanys();
		
		// Get deal
		$data['newldeals'] = $this->getnewdeals();
		
		
		
		// GEt Chart
		$data['pipelinechart']=$this->getpipelinechart();
		$data['PieChart']=$this->PieChart();
		$data['datatablecharts']=$this->datatablechart();

		$data['fields'] = $fields;
		$this->load->view('FirstLayoutView', $data);
	}

	// Get deal amounts
	private function getDealAmounts() {
		$query = "select stage, sum(deal_amount) as amount
				  from deal ";
				  
				  
		$query .= ' WHERE stage != ? and stage != ? '; 
		$params = array(); 
		$params[] = 'Won';
		$params[] = 'Lost';

		// Role checkup
		if ($this->isManager) {
			$query .= ' AND org_id = ? and (report_to_id = ? or deal_owner_id = ? ) group by stage';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			
			
		} elseif ($this->isExecutive) {
			$query .= ' AND org_id = ? and  deal_owner_id = ?  group by stage';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$query .= ' AND org_id = ?  group by stage';
			$params[] = $this->user->org_id;
		}

		$resObj = $this->db->query($query,$params);

		$deals = array();
		$total = 0;
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$deals[$row->stage] = $row->amount;
				$total += $row->amount;
			}
		}
		$deals['total'] = $total;

		return $deals;
	}

	// Get your tasks
	private function getYourTasks($count = 5) {
		$query = "select * 
				  from task
				  where assign_to = ?
				  order by task_modify_date desc, task_create_date desc
				  limit $count";
		$resObj = $this->db->query($query, $this->user->user_id);

		$tasks = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$tasks[] = $row;
			}
		}

		return $tasks;
	}

	// Get your cases
	private function getYourCases($count = 4) {
		
		$query = "select * 
				  from cases
				  where assign_to = ?
				  order by case_modify_date desc, case_create_date desc
				  limit $count";
		$resObj = $this->db->query($query, $this->user->user_id);

		$cases = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$cases[] = $row;
			}
		}

		return $cases;
	}

	// Get recent notes
	private function getRecentNotes($count = 5) {
		$query = "select * 
						  from note n 
						  inner join user u on n.owner_id = u.user_id and u.org_id = ?
						  left join lead l on l.lead_id = n.associate_id and l.org_id = ?
						  left join deal d on d.deal_id = n.associate_id and d.org_id = ?
						  where associate_to = ? or associate_to = ? and (lead_id != '' || deal_id != '')
						  order by note_create_date DESC
						  limit $count";
		$resObj = $this->db->query($query, array($this->user->org_id, $this->user->org_id, $this->user->org_id, 'lead', 'deal'));

		$notes = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				if ($row->deal_id) {
					$row->item_type = 'deal';
					$row->item_title = $row->deal_name;
					$row->item_id = $row->deal_id;
				} else {
					$row->item_type = 'lead';
					$row->item_title = $row->first_name . ' ' . $row->last_name;
					$row->item_id = $row->lead_id;
				}
				$notes[] = $row;
			}
		}
		return $notes;
	}
	
	//Today Task 
	
	private function getTodayTask($count = 5) {
		
		
		 if($this->isAdmin)
		 {
		 $query = "select * from task where org_id = '".$_SESSION['bcz_user']->org_id."' AND due_date='".date('Y-m-d')."' order by due_date DESC limit $count";
		
		 }
		  else
		  {
			$query = "select * from task where org_id = '".$_SESSION['bcz_user']->org_id."' AND assign_to='".$this->user->user_id."' AND due_date='".date('Y-m-d')."' order by due_date DESC limit $count";
		  }

		$task = array();
		$query = $this->db->query($query);
		if ($query->num_rows()) {
			foreach ($query->result() as $row) {
				$row->item_type = 'task';
				$row->task_id;
				$row->task_name ;
				$row->type;
				$row->assign_to;
				$row->associate_to;
				$row->associate_id ;
				$row->status;
				$row->priority;
				$row->company_name=$this->associate_company($row->associate_id);
				$row->user_name_to=$this->associate_user($row->task_owner_id);
				$row->description;
				$row->task_owner_id;
				$row->task_create_date;
				$row->task_modify_date;
				$tasks[] = $row;
			}
		}
		return $tasks;
	
	}
	
	//complet Task 
	
	private function getComTask($count = 5) {
		
		
		 if($this->isAdmin)
		 {
		
		 $query = "select * from task where org_id = '".$_SESSION['bcz_user']->org_id."' AND due_date < '".date('Y-m-d')."' order by due_date DESC limit $count";
		
		 }
		  else
		  {
			$query = "select * from task where org_id = '".$_SESSION['bcz_user']->org_id."' AND assign_to='".$this->user->user_id."' AND due_date<'".date('Y-m-d')."' order by due_date DESC limit $count";
		  }

		$task = array();
		$query = $this->db->query($query);
		if ($query->num_rows()) {
			foreach ($query->result() as $row) {
				$row->item_type = 'task';
				$row->task_id;
				$row->task_name ;
				$row->type;
				$row->assign_to;
				$row->associate_to;
				$row->associate_id ;
				$row->status;
				$row->priority;
				$row->company_name=$this->associate_company($row->associate_id);
				$row->user_name_to=$this->associate_user($row->task_owner_id);
				$row->description;
				$row->task_owner_id;
				$row->task_create_date;
				$row->task_modify_date;
				$com_tasks[] = $row;
			}
		}
		return $com_tasks;
	
	}
	
	//coming Task 
	
	
	private function getComingTask($count = 5) {
		
		
		 if($this->isAdmin)
		 {
		
		 $query = "select * from task where org_id = '".$_SESSION['bcz_user']->org_id."' AND due_date > '".date('Y-m-d')."' order by due_date DESC limit $count";
		
		 }
		  else
		  {
			$query = "select * from task where org_id = '".$_SESSION['bcz_user']->org_id."' AND assign_to='".$this->user->user_id."' AND due_date>'".date('Y-m-d')."' order by due_date DESC limit $count";
		  }

		$task = array();
		$query = $this->db->query($query);
		if ($query->num_rows()) {
			foreach ($query->result() as $row) {
				$row->item_type = 'task';
				$row->task_id;
				$row->task_name ;
				$row->type;
				$row->assign_to;
				$row->associate_to;
				$row->associate_id ;
				$row->status;
				$row->priority;
				$row->company_name=$this->associate_company($row->associate_id);
				$row->user_name_to=$this->associate_user($row->task_owner_id);
				$row->description;
				$row->task_owner_id;
				$row->task_create_date;
				$row->task_modify_date;
				$coming_tasks[] = $row;
			}
		}
		return $coming_tasks;
	
	}
	
	// company name
	
	private function associate_company($company) {
		
	
	$query = "select * from company where company_id = '".$company."'";
	
	$task = array();
		$query = $this->db->query($query);
		
		if ($query->num_rows()) {
			foreach ($query->result() as $row) {
				 $company_name=$row->company_name;
				
			}
		}
		return $company_name;
	
		
	}
	
	
	private function associate_user($associate_user) {
		
	
	$query = "select * from user where user_id = '".$associate_user."'";
	
	$task = array();
		$query = $this->db->query($query);
		
		if ($query->num_rows()) {
			foreach ($query->result() as $row) {
				 $user_name=$row->name;
				
			}
		}
		return $user_name;
	
		
	}

	// Get recent cases
	private function getRecentCases($count = 5) {
		$query = "select * 
				  from cases
				  order by case_modify_date desc, case_create_date desc
				  limit $count";
		$resObj = $this->db->query($query, $this->user->user_id);

		$cases = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$cases[] = $row;
			}
		}

		return $cases;
	}

	// Get recent deals
	private function getRecentDeals($count = 5) {
		$query = "select * 
				  from deal
				  where stage!='Archieved' AND stage!='Won' AND stage!='Lost' AND org_id = ?
				  order by deal_id desc
				  limit $count";
		$resObj = $this->db->query($query, $this->user->org_id);

		$deals = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$deals[] = $row;
			}
		}

		return $deals;
	}

	// Get top deals
	private function getTopDeals($count = 5) {
		$query = "select * 
				  from deal
				  where stage!='Archieved' AND stage!='Won' AND stage!='Lost' AND org_id = ?
				  order by deal_amount desc
				  limit $count";
		$resObj = $this->db->query($query, $this->user->org_id);

		$deals = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$deals[] = $row;
			}
		}

		return $deals;
	}

	// Get top posts
	public function getTopPosts($count = 10) {
		$query = "select * 
						  from posts p
						  inner join user u on p.posted_by = u.user_id
						  left join fileupload f on p.post_file = f.file_id
						  where  p.org_id = ?
						  order by posted_on desc
						  limit $count";
		$resObj = $this->db->query($query, array($this->user->org_id));

		$posts = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$posts[] = $row;
			}
		}
// echo "<pre>"; print_r($posts); exit;
		return $posts;
	}

	// Get sales amount
	public function getSalesAmount($period = '') {
		$query = "select sum(total) as amount, so_create_date, month(so_create_date) as month, year(so_create_date) as year from sales_order";
		$params = array();
		if ($period == 'month') {
			$query .= " where month(so_create_date) = ?";
			$params[] = Date('n');
		} else if ($period == 'year') {
			$query .= " where year(so_create_date) = ?";
			$params[] = Date('Y');
		}

		$resObj = $this->db->query($query, $params);
		$row = $resObj->row();
		return $row->amount ? $row->amount : 0;
	}

	// Get companies json for datatable
	public function getpipelineinfo() {
		$fields = $this->getAllUserFields();	// Get all user fields
		$dealAmounts = $this->getDealAmounts();
		$salesData = array();
		foreach ($dealAmounts as $stage => $amount) {
			if ($stage && ($stage != 'total')) {
				$sData = array('Department' => $stage, 'Budget' => $amount);
				$salesData[] = $sData;
			}
		}
		
// $salesData = array(array('Department' => 'Lost', 'Budget' => 2500), array('Department' => 'Presentation', 'Budget' => 8000), array('Department' => 'Won', 'Budget' => 6500), array('Department' => 'Purchasing', 'Budget' => 9000), array('Department' => 'Negotiation', 'Budget' => 4000));
		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($salesData));
	}
	
	
	
		// Get companies json for datatable
	public function getpipelinechart() {
		$fields = $this->getAllUserFields();	// Get all user fields
		$dealAmounts = $this->getDealAmounts();
		$salesData ;//= array();
		$i=0;
		foreach ($dealAmounts as $stage => $amount) {
			if ($stage && ($stage != 'total')) {
				$sData = array('Department' => $stage, 'Budget' => $amount);
				$salesData[$i] = $sData;
				$i++;
			}
		}
		
		return $salesData;
// $salesData = array(array('Department' => 'Lost', 'Budget' => 2500), array('Department' => 'Presentation', 'Budget' => 8000), array('Department' => 'Won', 'Budget' => 6500), array('Department' => 'Purchasing', 'Budget' => 9000), array('Department' => 'Negotiation', 'Budget' => 4000));
		//$this->output
    	//	->set_content_type('application/json')
    	//	->set_output(json_encode($salesData));
	}
	
	
	public function PieChart() {
		
		$query = "select priority, count(case_id) as case_count
						  from cases";
						  
						  
						  
						  // Role checkup
		$query .= ' where';
		$params = array();
		if ($this->isManager) {
			$query .= 'org_id = ? and case_report_to_id = ? or case_owner_id = ? group by priority';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$query .= ' org_id = ? and case_report_to_id = ? group by priority';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$query .= ' org_id = ? group by priority';
			$params[] = $this->user->org_id;
		}
		
		
		
		
		$resObj = $this->db->query($query, $params);
		
		
		$priorityCases = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				if ($row->priority) {
					$priorityCases[] = array($row->priority, $row->case_count);
				}
			}
		}
		
		//print_r($priorityCases);
		
		return $priorityCases;
	}
	
	
	
	public function datatablechart() {
		
		$query = "select lead_source, count(lead_id) as lead_count
						  from lead";
						  
		$query .= ' where';
		$params = array();
		if ($this->isManager) {
			$query .= 'org_id = ? and report_to_id = ? or lead_owner_id = ? group by lead_source';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$query .= ' org_id = ? and lead_owner_id = ? group by lead_source';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$query .= ' org_id = ? group by lead_source';
			$params[] = $this->user->org_id;
		}		  
						  
						  
		$resObj = $this->db->query($query,$params);

		$sourceLeads = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				if ($row->lead_source) {
					$sourceLeads[] = array($row->lead_count, $row->lead_source);
				}
			}
		}
		
		//print_r($sourceLeads);
		return $sourceLeads;
	}
	
	
	

	
	
	

	// Get lead source json for datatable
	public function getsourceleads() {
		$query = "select lead_source, count(lead_id) as lead_count
						  from lead
						  where org_id = ?
						  group by lead_source";
		$resObj = $this->db->query($query, $this->user->org_id);

		$sourceLeads = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				if ($row->lead_source) {
					$sourceLeads[] = array($row->lead_count, $row->lead_source);
				}
			}
		}
// $sourceLeads = array(array(10, 'Referral'), array(6, 'Partner'), array(12, 'Indiamart'), array(8, 'Import'), array(2, 'Alibaba'), array(4, 'TradeIndia'), array(9, 'Others'));
		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($sourceLeads));
	}

	// Get priority cases json for datatable
	public function getprioritycases() {
		$query = "select priority, count(case_id) as case_count
						  from cases
						  where org_id = ?
						  group by priority";
		$resObj = $this->db->query($query, $this->user->org_id);

		$priorityCases = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				if ($row->priority) {
					$priorityCases[] = array($row->priority, $row->case_count);
				}
			}
		}
// $priorityCases = array(array('High', 28), array('Low', 3), array('Medium', 15));
		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($priorityCases));
	}

	// Post something...
	public function postMessage() {
		// Gather form fields
		$data = $formFields = array();		
		$currDateTime = $this->getCurrTime();
		$valsStr = '';
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal);
			$valsStr .= ($valsStr ? ', ' : '') . '?';
		}
		$formFields['org_id'] = $this->user->org_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['posted_by'] = $this->user->user_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';

		// TODO: Validation if needed

		// Upload file if attached
		if ($_FILES['post_file']['name']) {
			$uploadSize = 8 * 1024;
			$config['upload_path'] = $this->postFilesPath;
			$config['allowed_types'] = 'gif|jpg|jpeg|png|bmp|doc|docx|pdf|xls|xlsx|txt';
			$config['max_size']	= $uploadSize;
			$this->load->library('upload', $config);

			$uploadRes = $this->upload->do_upload('post_file');	// Upload file

			if (!$uploadRes) {
				$uploadMessage = $this->upload->display_errors();
				$uploadMessage = str_replace('permitted size.', 'permitted size(8MB).', $uploadMessage);
				$data['messages']['error']['upload'] = $uploadMessage;

			} else {
				$uploadedFile = $this->upload->data();

				// Insert into our DB
				$fileId = $this->getTableAutoID('fileupload');
				$fquery = "insert into fileupload 
						  		(filename, filetype, size, owner_id, report_to_id, associate_to, associate_id, file_create_date) 
						  		VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
				$dbRes = $this->db->query($fquery, array($uploadedFile['file_name'], $uploadedFile['file_type'], $_FILES['post_file']['size'], $this->user->user_id, ($this->user->report_to_id ? $this->user->report_to_id : $this->user->user_id), 'post', '', $currDateTime));
				$formFields['post_file'] = $fileId;
				$valsStr .= ($valsStr ? ', ' : '') . '?';
			}
		}

		// Post the message
		if (!count($data['messages']['error'])) {
			$postId = $this->getTableAutoID('posts');			
			$formFields['posted_on'] = $currDateTime;
			$valsStr .= ($valsStr ? ', ' : '') . '?';
			$formFields['updated_on'] = $currDateTime;
			$valsStr .= ($valsStr ? ', ' : '') . '?';
			$query = 'insert into posts (' . implode(', ', array_keys($formFields)) . ') values (' . $valsStr . ')';
			$res = $this->db->query($query, array_values($formFields));

			// Update file 
			if ($res && $fileId) {
				$uquery = "update fileupload set associate_id = ? where file_id = ?";
				$uRes = $this->db->query($uquery, array($postId, $fileId));
			}

			if ($res) {
				// Log activity
				$info = $fileId ? array('type' => 'file', 'id' => $fileId) : array();
				$activity = array('action' => 'POST_MESSAGE', 'type' => 'post', 'id' => $postId, 'info' => json_encode($info));
				$this->logUserActivity($activity);
				$data['messages']['success']['update'] = 'Your message has been posted succesfully.';
			} else {
				$data['messages']['error']['post'] = 'Something went wrong while posting your message, please try again after sometime.';
			}			
		}

		// Return data array
		return $data;
	}
	
	
	
		// Get top posts
	public function getnewleads($count = 10) {
		$query = "select * from lead where inbox=? order by lead_id desc limit $count";
		$resObj = $this->db->query($query, '0');

		$lead = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$lead[] = $row;
			}
		}
// echo "<pre>"; print_r($posts); exit;
		return $lead;
	}
	
	
			// Get top posts
	public function getnewcompanys($count = 10) {
		$query = "select * from company where inbox=? order by company_id desc limit $count";
		$resObj = $this->db->query($query, '0');

		$lead = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$company[] = $row;
			}
		}
// echo "<pre>"; print_r($posts); exit;
		return $company;
	}
	
	
				// Get top posts
	public function getnewdeals($count = 10) {
		$query = "select * from deal where inbox=? order by deal_id desc limit $count";
		$resObj = $this->db->query($query, '0');

		$lead = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$deal[] = $row;
			}
		}
// echo "<pre>"; print_r($posts); exit;
		return $deal;
	}
	
	
	
	

}