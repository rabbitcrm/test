<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dashboard extends BCZ_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if($_SESSION['filters']!="dashboard")
		{
			unset($_SESSION['filters_key']);
			unset($_SESSION['filters_fvalue']);
		}
		
		//print_r(wincache_ucache_meminfo());
		// Post something...
		if (isset($_POST['post_body'])) {
			$data = $this->postMessage();
		}

		$this->bodyClass = $this->pageTitle = $this->pageDesc = "Home";
		$data['content'] = 'DashboardView';


			// Data for notes block
		$data['notes'] = $this->getRecentNotes();
		
		$data['Lcols'] = array_values($this->HomeLeadTableCols);
		
		$data['topoppcols'] = array_values($this->HomeTopOppCols);
		
		$data['Ccols'] = array_values($this->HomecompanyTableCols);
		
		$data['Ocols'] = array_values($this->HomeOppTableCols);
		
		
		$data['tasks'] = $this->getTodayTask();

		// Data for complet Task block
		$data['complet_tasks'] = $this->getComTask();
		
		// Data for Overdue Task block
		$data['overdue_tasks'] = $this->getOverdueTask('5');

		// Data for coming Task block
		$data['coming_tasks'] = $this->getComingTask();
		
		// Data for recent deals block
		$data['deals'] = $this->getRecentDeals('10');

		// Data for top deals block
		$data['topDeals'] = $this->getTopDeals('10');

		// Get discussions
		$data['posts'] = $this->getTopPosts();
		
		// Get leads
		$data['newleads'] = $this->getlead('10');
		
		// Get companys
		$data['newcompanys'] = $this->getCompanies('10');
		
		// Get deal
		$data['newldeals'] = $this->getDeals('10');
		
		
		
		// GEt Chart
		$data['pipelinechart']=$this->getpipelinechart();
		$data['PieChart']=$this->PieChart();
		$data['datatablecharts']=$this->datatablechart();

		$data['fields'] = $fields;
		$this->load->view('FirstLayoutView', $data);
	}

	// Get deal amounts
	private function getDealAmounts() {
		$query = "select de.stage, sum(de.deal_amount) as amount, uf.stage as stage
				  from deal de left join user_fields uf on de.stage=uf.no";
				  
				  
		$query .= ' WHERE uf.stage != ? and uf.stage != ? and uf.stage != ? '; 
		$params = array(); 
		$params[] = 'Won';
		$params[] = 'Lost';
		$params[] = 'Archived';

		// Role checkup
		if($this->user->demo==0)
		{
		if ($this->isManager) {
			$query .= ' AND (de.org_id = ? or de.org_id = ? ) and (de.report_to_id = ? or de.deal_owner_id = ? ) group by de.stage';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			
			
		} elseif ($this->isExecutive) {
			$query .= ' AND (de.org_id = ? or de.org_id = ? ) and  de.deal_owner_id = ?  group by de.stage';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$query .= ' AND (de.org_id = ? or de.org_id = ? )  group by de.stage';
			$params[] = $this->user->org_id;
			$params[] = "0";
		}
		}
		else
		{
			
		if ($this->isManager) {
			$query .= ' AND de.org_id = ? and (de.report_to_id = ? or de.deal_owner_id = ? ) group by de.stage';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			
			
		} elseif ($this->isExecutive) {
			$query .= ' AND de.org_id = ? and  de.deal_owner_id = ?  group by de.stage';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$query .= ' AND de.org_id = ?  group by de.stage';
			$params[] = $this->user->org_id;
		}
		}
		
		 $query .=" ORDER BY uf.probability ASC";

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
						  from 
						  ";
						  
						 $params = array();
		if($this->user->demo==0)
		{
		
		if ($this->isManager) {
			$query .= " note n 
						  inner join user u on n.owner_id = u.user_id and u.org_id = ?  AND u.user_status!=?
						  left join lead l on l.lead_id = n.associate_id and (l.org_id = ?  or l.org_id = ? )
						  left join deal d on d.deal_id = n.associate_id and (d.org_id = ?  or d.org_id = ? ) where associate_to = ? or associate_to = ? and (lead_id != '' || deal_id != '')
						  order by note_create_date DESC
						  limit $count";
			$params[] = $this->user->org_id;
			$params[] = 'inactive';
			$params[] = $this->user->org_id;
			$params[] = '0';
			$params[] = $this->user->org_id;
			$params[] = '0';
			$params[] = 'lead';
			$params[] = 'deal';
			
		} elseif ($this->isExecutive) {
			$query .= "  note n 
						  inner join user u on n.owner_id = u.user_id and u.org_id = ?  AND u.user_status!=?
						  left join lead l on l.lead_id = n.associate_id and (l.org_id = ?  or l.org_id = ? )
						  left join deal d on d.deal_id = n.associate_id and (d.org_id = ?  or d.org_id = ? ) where associate_to = ? or associate_to = ? and (lead_id != '' || deal_id != '')
						  order by note_create_date DESC
						  limit $count";
			$params[] = $this->user->org_id;
			$params[] = 'inactive';
			$params[] = $this->user->org_id;
			$params[] = '0';
			$params[] = $this->user->org_id;
			$params[] = '0';
			$params[] = 'lead';
			$params[] = 'deal';
			
		} elseif ($this->isAdmin && $this->user->org_id) {
			$query .= "  note n 
						  inner join user u on n.owner_id = u.user_id and u.org_id = ?  AND u.user_status!=?
						  left join lead l on l.lead_id = n.associate_id and (l.org_id = ?  or l.org_id = ? )
						  left join deal d on d.deal_id = n.associate_id and (d.org_id = ?  or d.org_id = ? ) where associate_to = ? or associate_to = ? and (lead_id != '' || deal_id != '')
						  order by note_create_date DESC
						  limit $count";
			$params[] = $this->user->org_id;
			$params[] = 'inactive';
			$params[] = $this->user->org_id;
			$params[] = '0';
			$params[] = $this->user->org_id;
			$params[] = '0';
			$params[] = 'lead';
			$params[] = 'deal';
			
		}
		
		}
		else
		{
				if ($this->isManager) {
			$query .= " note n 
						  inner join user u on n.owner_id = u.user_id and u.org_id = ?  AND u.user_status!=?
						  left join lead l on l.lead_id = n.associate_id and l.org_id = ?
						  left join deal d on d.deal_id = n.associate_id and d.org_id = ? where associate_to = ? or associate_to = ? and (lead_id != '' || deal_id != '')
						  order by note_create_date DESC
						  limit $count";
			$params[] = $this->user->org_id;
			$params[] = 'inactive';
			$params[] = $this->user->org_id;
			$params[] = $this->user->org_id;
			$params[] = 'lead';
			$params[] = 'deal';
			
		} elseif ($this->isExecutive) {
			$query .= "  note n 
						  inner join user u on n.owner_id = u.user_id and u.org_id = ?  AND u.user_status!=?
						  left join lead l on l.lead_id = n.associate_id and l.org_id = ?
						  left join deal d on d.deal_id = n.associate_id and d.org_id = ? where associate_to = ? or associate_to = ? and (lead_id != '' || deal_id != '')
						  order by note_create_date DESC
						  limit $count";
			$params[] = $this->user->org_id;
			$params[] = 'inactive';
			$params[] = $this->user->org_id;
			$params[] = $this->user->org_id;
			$params[] = 'lead';
			$params[] = 'deal';
			
		} elseif ($this->isAdmin && $this->user->org_id) {
			$query .= "  note n 
						  inner join user u on n.owner_id = u.user_id and u.org_id = ? AND u.user_status!=?
						  left join lead l on l.lead_id = n.associate_id and l.org_id = ?
						  left join deal d on d.deal_id = n.associate_id and d.org_id = ? where associate_to = ? or associate_to = ? and (lead_id != '' || deal_id != '')
						  order by note_create_date DESC
						  limit $count";
		
			$params[] = $this->user->org_id;
			$params[] = 'inactive';
			$params[] = $this->user->org_id;
			$params[] = $this->user->org_id;
			$params[] = 'lead';
			$params[] = 'deal';
			
		}
			
		}
		$resObj = $this->db->query($query, $params);

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
		
		
		if($this->user->demo==0)
		{
		
		 if($this->isAdmin)
		 {
			 
		 $query = "select * from task where (org_id = '".$_SESSION['bcz_user']->org_id."' or org_id = '0') AND due_date='".date('Y-m-d')."' order by due_date DESC limit $count";
		
		 }
		  else
		  {
			$query = "select * from task where (org_id = '".$_SESSION['bcz_user']->org_id."' or org_id = '0')  AND due_date='".date('Y-m-d')."' order by due_date DESC limit $count";
		  }
		}
		else
		{
			if($this->isAdmin)
		 {
		 $query = "select * from task where org_id = '".$_SESSION['bcz_user']->org_id."' AND due_date='".date('Y-m-d')."' order by due_date DESC limit $count";
		
		 }
		  else
		  {
			$query = "select * from task where org_id = '".$_SESSION['bcz_user']->org_id."' AND assign_to='".$this->user->user_id."' AND due_date='".date('Y-m-d')."' order by due_date DESC limit $count";
		  }
		}

		$task = array();
		$query = $this->db->query($query);
		if ($query->num_rows()) {
			foreach ($query->result() as $row) {
				
				
				$query1 = "select * from ".$row->associate_to." where ".$row->associate_to."_id = '".$row->associate_id."'";
				$query1 = $this->db->query($query1);
				
				if ($query1->num_rows()) {
					foreach ($query1->result() as $row1) {
						$to=$row->associate_to."_name";
						$row->associate_name = $row1->$to;
					}}
				
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
	
	
	// Overdue Task
	
	private function getOverdueTask($count = '') {
		
		$query="select * from task tk left join user_fields uf on tk.status=uf.no where ";
		
		$sLimit = "";
		if($count!="")
		{
		$sLimit = "LIMIT ".$count;
		}
		
		
		if($this->user->demo==0)
		{
		
		 if($this->isAdmin)
		 {
		
		 $query.= "(tk.org_id = '".$_SESSION['bcz_user']->org_id."' or tk.org_id='0') AND tk.due_date < '".date('Y-m-d')."' AND uf.task_status!='Completed' order by tk.due_date DESC $sLimit";
		
		 }
		  else
		  {
			$query.= "(tk.org_id = '".$_SESSION['bcz_user']->org_id."' or tk.org_id='0')  AND tk.due_date<'".date('Y-m-d')."' AND uf.task_status!='Completed' order by tk.due_date DESC $sLimit";
		  }
		  
		}
		else
		{
			 if($this->isAdmin)
		 {
		
		 $query.= " tk.org_id = '".$_SESSION['bcz_user']->org_id."' AND tk.due_date < '".date('Y-m-d')."' AND uf.task_status!='Completed' order by tk.due_date DESC  $sLimit";
		
		 }
		  else
		  {
			$query.= " tk.org_id = '".$_SESSION['bcz_user']->org_id."' AND tk.assign_to='".$this->user->user_id."' AND uf.task_status!='Completed' AND due_date<'".date('Y-m-d')."' order by tk.due_date DESC  $sLimit";
		  }
		}

		$task = array();
		$query = $this->db->query($query);
		if ($query->num_rows()) {
			foreach ($query->result() as $row) {
				
				$query1 = "select * from ".$row->associate_to." where ".$row->associate_to."_id = '".$row->associate_id."'";
				$query1 = $this->db->query($query1);
				
				if ($query1->num_rows()) {
					foreach ($query1->result() as $row1) {
						 $to=$row->associate_to."_name";
						 $row->associate_name = $row1->$to;
					}}
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
	
	
	//complet Task 
	
	private function getComTask($count = 5) {	
	
	$sLimit = "";
		if($count!="")
		{
		$sLimit = "LIMIT ".$count;
		}
	$query="select * from task tk left join user_fields uf on tk.status=uf.no where ";
		
		
		if($this->user->demo==0)
		{
		
		 if($this->isAdmin)
		 {
		
		 $query.= "(tk.org_id = '".$_SESSION['bcz_user']->org_id."' or tk.org_id='0') AND tk.due_date < '".date('Y-m-d')."' AND uf.task_status='Completed' order by tk.due_date DESC $sLimit";
		
		 }
		  else
		  {
			$query.= "(tk.org_id = '".$_SESSION['bcz_user']->org_id."' or tk.org_id='0')  AND tk.due_date<'".date('Y-m-d')."' AND uf.task_status='Completed' order by tk.due_date DESC $sLimit";
		  }
		  
		}
		else
		{
			 if($this->isAdmin)
		 {
		
		 $query.= " tk.org_id = '".$_SESSION['bcz_user']->org_id."' AND tk.due_date < '".date('Y-m-d')."' AND uf.task_status='Completed' order by tk.due_date DESC $sLimit";
		
		 }
		  else
		  {
			$query.= " tk.org_id = '".$_SESSION['bcz_user']->org_id."' AND tk.assign_to='".$this->user->user_id."' AND uf.task_status='Completed' AND due_date<'".date('Y-m-d')."' order by tk.due_date DESC $sLimit";
		  }
		}

		$task = array();
		$query = $this->db->query($query);
		$query->num_rows();
		
		if ($query->num_rows()) {
			foreach ($query->result() as $row) {
				
			
				
				$query1 = "select * from ".$row->associate_to." where ".$row->associate_to."_id = '".$row->associate_id."'";
				$query1 = $this->db->query($query1);
				
				if ($query1->num_rows()) {
					foreach ($query1->result() as $row1) {
						 $to=$row->associate_to."_name";
						 $row->associate_name = $row1->$to;
					}}
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
		
		if($this->user->demo==0)
		{
		
		 if($this->isAdmin)
		 {
		
		 $query = "select * from task where (org_id = '".$_SESSION['bcz_user']->org_id."' or org_id = '0') AND due_date > '".date('Y-m-d')."' order by due_date DESC limit $count";
		
		 }
		  else
		  {
			$query = "select * from task where (org_id = '".$_SESSION['bcz_user']->org_id."' or org_id = '0') AND assign_to='".$this->user->user_id."' AND due_date>'".date('Y-m-d')."' order by due_date DESC limit $count";
		  }
		  
		}
		else
		{
			 if($this->isAdmin)
		 {
		
		 $query = "select * from task where org_id = '".$_SESSION['bcz_user']->org_id."' AND due_date > '".date('Y-m-d')."' order by due_date DESC limit $count";
		
		 }
		  else
		  {
			$query = "select * from task where org_id = '".$_SESSION['bcz_user']->org_id."' AND assign_to='".$this->user->user_id."' AND due_date>'".date('Y-m-d')."' order by due_date DESC limit $count";
		  }
		}

		$task = array();
		$query = $this->db->query($query);
		if ($query->num_rows()) {
			foreach ($query->result() as $row) {
				
				$query1 = "select * from ".$row->associate_to." where ".$row->associate_to."_id = '".$row->associate_id."'";
				$query1 = $this->db->query($query1);
				
				if ($query1->num_rows()) {
					foreach ($query1->result() as $row1) {
						$to=$row->associate_to."_name";
						$row->associate_name = $row1->$to;
					}}
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
	private function getRecentDeals($count = '',$filters='') {
		
	
		/*$query = "select * 
				  from deal";
				  
				  
				   $params = array();
		
		if($this->user->demo==0)
		{
		
	
		if ($this->isManager) {
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND (org_id = ? or org_id = ?) AND (report_to_id = ? or deal_owner_id = ?)
				  order by deal_id desc
				  limit $count";
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			
		} elseif ($this->isExecutive) {
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND (org_id = ? or org_id = ?)  AND deal_owner_id = ?
				  order by deal_id desc
				  limit $count";
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			
		} elseif ($this->isAdmin && $this->user->org_id) {
		
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND (org_id = ? or org_id = ?) 
				  order by deal_id desc
				  limit $count";
			$params[] = $this->user->org_id;
			$params[] = "0";
			
		}
		}
		else
		{
			
			
		if ($this->isManager) {
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND org_id = ? AND (report_to_id = ? or deal_owner_id = ?)
				  order by deal_id desc
				  limit $count";
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			
		} elseif ($this->isExecutive) {
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND org_id = ? AND deal_owner_id = ?
				  order by deal_id desc
				  limit $count";
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			
		} elseif ($this->isAdmin && $this->user->org_id) {
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND org_id = ?
				  order by deal_id desc
				  limit $count";
			$params[] = $this->user->org_id;
			
		}
			
		}
		

		$resObj = $this->db->query($query,$params);
		
	

		$deals = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->amount=$row->deal_amount;
				$row->deal_amount=$this->convertShares($row->deal_amount);
				$deals[] = $row;
			}
		}

		return $deals;*/
		
		
		
		
		
		
		if (!$filters){ $filters = $this->filters; } if($filters=="") { $filters ='de.deal_id'; }
		
		$params = array();

		$query = "select SQL_CALC_FOUND_ROWS *, if (u.name, u.name, u.name) as owner,u.user_status as user_status, de.deal_owner_id as deal_owner_id  , if(uf.stage,uf.stage,uf.stage) as stage_name , if(de.stage,de.stage,de.stage) as stage , if(de.source,de.source,de.source) as  source
						  from deal de 
						  left join user_fields uf on de.stage =uf.no
						  left join company c on de.deal_company_id = c.company_id
						  left join user u on (de.deal_owner_id = u.user_id) ";
	//	$params[] = '0';
		
		//  left join user u on (c.assign_to = u.user_id AND c.assign_to != ? or c.owner_id=u.user_id) ";
		//$params[] = '0';
		
		
		$whereCond = ''; // ' where de.stage != ? and de.stage != ?';
		 //array('Won', 'Lost');

		$whereCond = ''; // ' where de.stage != ? and de.stage != ?';
		 //array('Won', 'Lost');

		// Role checkup

		if($this->user->demo==0)
		{

		if ($this->isManager) {
			$whereCond .="left join report_to rt on de.deal_owner_id = rt.user_id". ($whereCond ? ' and ' : ' where ') . ' ( de.org_id = ? or de.org_id = ? )and (de.report_to_id = ? or de.deal_owner_id = ?  or  c.report_to_id = ? or  c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .="left join report_to rt on de.deal_owner_id = rt.user_id". ($whereCond ? ' and' : ' where') . ' (de.org_id = ? or de.org_id = ? ) and (de.deal_owner_id = ?  or c.assign_to = ? or rt.report_to_id= ?)';
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
			$whereCond .= "left join report_to rt on de.deal_owner_id = rt.user_id".($whereCond ? ' and ' : ' where ') . ' de.org_id = ? and (de.report_to_id = ? or de.deal_owner_id = ?  or  c.report_to_id = ? or  c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .="left join report_to rt on de.deal_owner_id = rt.user_id". ($whereCond ? ' and' : ' where') . ' de.org_id = ? and (de.deal_owner_id = ?  or c.assign_to = ? or rt.report_to_id= ?)';
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
			
			foreach ($filters as $fkey => $fvalue) {
				
				$ik++;
				
					
				if ($fvalue && ($fvalue != 'All')) {
					if ($fkey == 'deal_create_date' ) {
						
						switch ($fvalue) {
							case 'today':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "date(deal_create_date) = ?";
								$params[] = date("Y-m-d");
								break;
							case 'yesterday':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "date(deal_create_date) = ?";
								$tomorrow = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
								$params[] = date("Y-m-d", $tomorrow);
								break;
							case 'curr_week':
							
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "YEARWEEK(deal_create_date,1) = YEARWEEK(CURDATE(), 1)";
								break;
							case 'last_week':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . " deal_create_date  >= CURDATE( ) - INTERVAL DAYOFWEEK( CURDATE( ) ) +6 DAY AND $fkey < CURDATE( ) - INTERVAL DAYOFWEEK( CURDATE( ) ) -1 DAY";
								break;
							case 'curr_month':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "month($fkey) = ? AND YEAR(deal_create_date) = ?";
								$params[] = date('m');
								$params[] = date('Y');
								break;
							case 'last_month':
								 $filtersCondition .= ($filtersCondition ? ' and ' : '') . "month(deal_create_date) = ? AND YEAR(deal_create_date) = ?";
								$params[] = date('m') - 1;
								$params[] = date('Y');
								break;
							case '90_days':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "deal_create_date >= utc_timestamp() - interval 90 day";
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
		$aColumns = array_keys($this->HomeLatestOppCols);
		// Paginating...
		$sLimit = "";
		if($count!="")
		{
		$sLimit = "LIMIT ".$count;
		}
		
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
		$sOrder = "ORDER BY de.deal_create_date desc";
		$querys=$query;
		$query .= " $whereCond $sOrder $sLimit";
		$resObj = $this->db->query($query, $params);
		
		


		$deals = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				
				$row->amount=$row->deal_amount;
				$row->deal_amount=$this->convertShares($row->deal_amount);
				
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
		$countQuery = 'SELECT COUNT(`deal_id`) as totalRows FROM deal de left join company c on c.company_id = de.deal_company_id ' . $countWhereCond;
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $deals;
	
	
	}

	// Get top deals
	private function getTopDeals($count = '') {
		

		/*		$query = "select * 
				  from deal
				   ";
				  
				  
				  
				  $params = array();
		
		if($this->user->demo==0)
		{
		
		
		if ($this->isManager) {
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND (org_id = ? or org_id = ?) AND (report_to_id = ? or deal_owner_id = ?)
				  order by deal_amount desc
				  limit $count";
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			
		} elseif ($this->isExecutive) {
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND (org_id = ? or org_id = ?) AND deal_owner_id = ?
				  order by deal_amount desc
				  limit $count";
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			
		} elseif ($this->isAdmin && $this->user->org_id) {
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND (org_id = ? or org_id = ?)
				  order by deal_amount desc
				  limit $count";
			$params[] = $this->user->org_id;
			$params[] = "0";
			
		}
		}
		else
		{
			
		if ($this->isManager) {
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND org_id = ? AND (report_to_id = ? or deal_owner_id = ?)
				  order by deal_amount desc
				  limit $count";
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			
		} elseif ($this->isExecutive) {
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND org_id = ? AND deal_owner_id = ?
				  order by deal_amount desc
				  limit $count";
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			
		} elseif ($this->isAdmin && $this->user->org_id) {
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND org_id = ?
				  order by deal_amount desc
				  limit $count";
			$params[] = $this->user->org_id;
			
		}
		}
		

		$resObj = $this->db->query($query,$params);

		$deals = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->amount=$row->deal_amount;
				$row->deal_amount=$this->convertShares($row->deal_amount);
				$deals[] = $row;
			}
		}

		return $deals;
	*/
	
	
	
	
	
	
	
		
	
		/*$query = "select * 
				  from deal";
				  
				  
				   $params = array();
		
		if($this->user->demo==0)
		{
		
	
		if ($this->isManager) {
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND (org_id = ? or org_id = ?) AND (report_to_id = ? or deal_owner_id = ?)
				  order by deal_id desc
				  limit $count";
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			
		} elseif ($this->isExecutive) {
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND (org_id = ? or org_id = ?)  AND deal_owner_id = ?
				  order by deal_id desc
				  limit $count";
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			
		} elseif ($this->isAdmin && $this->user->org_id) {
		
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND (org_id = ? or org_id = ?) 
				  order by deal_id desc
				  limit $count";
			$params[] = $this->user->org_id;
			$params[] = "0";
			
		}
		}
		else
		{
			
			
		if ($this->isManager) {
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND org_id = ? AND (report_to_id = ? or deal_owner_id = ?)
				  order by deal_id desc
				  limit $count";
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			
		} elseif ($this->isExecutive) {
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND org_id = ? AND deal_owner_id = ?
				  order by deal_id desc
				  limit $count";
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			
		} elseif ($this->isAdmin && $this->user->org_id) {
			$query .= " WHERE stage!='24' AND stage!='25' AND stage!='518' AND org_id = ?
				  order by deal_id desc
				  limit $count";
			$params[] = $this->user->org_id;
			
		}
			
		}
		

		$resObj = $this->db->query($query,$params);
		
	

		$deals = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->amount=$row->deal_amount;
				$row->deal_amount=$this->convertShares($row->deal_amount);
				$deals[] = $row;
			}
		}

		return $deals;*/
		
		
		
		
		
	if (!$filters){ $filters = $this->filters; } if($filters=="") { $filters ='de.deal_id'; }
		
		$params = array();
	
		$query = "select SQL_CALC_FOUND_ROWS *, if (u.name, u.name, u.name) as owner,u.user_status as user_status, de.deal_owner_id as deal_owner_id  , if(uf.stage,uf.stage,uf.stage) as stage_name , if(de.stage,de.stage,de.stage) as stage , if(de.source,de.source,de.source) as  source
						  from deal de 
						  left join user_fields uf on de.stage =uf.no
						  left join company c on de.deal_company_id = c.company_id
						  left join user u on (de.deal_owner_id = u.user_id) ";
	//	$params[] = '0';
		
		//  left join user u on (c.assign_to = u.user_id AND c.assign_to != ? or c.owner_id=u.user_id) ";
		//$params[] = '0';
		
		
		$whereCond = ''; // ' where de.stage != ? and de.stage != ?';
		 //array('Won', 'Lost');

		$whereCond = ''; // ' where de.stage != ? and de.stage != ?';
		 //array('Won', 'Lost');

		// Role checkup

		if($this->user->demo==0) 
		{

		if ($this->isManager) {
			$whereCond .="left join report_to rt on de.deal_owner_id = rt.user_id". ($whereCond ? ' and ' : ' where ') . ' ( de.org_id = ? or de.org_id = ? )and (de.report_to_id = ? or de.deal_owner_id = ?  or  c.report_to_id = ? or  c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .="left join report_to rt on de.deal_owner_id = rt.user_id". ($whereCond ? ' and' : ' where') . ' (de.org_id = ? or de.org_id = ? ) and (de.deal_owner_id = ?  or c.assign_to = ? or rt.report_to_id= ?)';
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
			$whereCond .= "left join report_to rt on de.deal_owner_id = rt.user_id".($whereCond ? ' and ' : ' where ') . ' de.org_id = ? and (de.report_to_id = ? or de.deal_owner_id = ?  or  c.report_to_id = ? or  c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .="left join report_to rt on de.deal_owner_id = rt.user_id". ($whereCond ? ' and' : ' where') . ' de.org_id = ? and (de.deal_owner_id = ?  or c.assign_to = ? or rt.report_to_id= ?)';
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
			
			foreach ($filters as $fkey => $fvalue) {
				
				$ik++;
				
					
				if ($fvalue && ($fvalue != 'All')) {
					if (($fkey == 'exp_close') || ($fkey == 'deal_create_date') ) {
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
					else if($fkey =='first')
					{
						 $filtersCondition .= ($filtersCondition ? ' and ' : '') . "(de.deal_name LIKE '%".$fvalue."%' or de.deal_amount LIKE '%".$fvalue."%')" ;
						
					}
					else if($fkey =='stage')
					{
						
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
		$aColumns = array_keys($this->HomeLatestOppCols);
		// Paginating...
		$sLimit = "";
		if($count!="")
		{
		$sLimit = "LIMIT ".$count;
		}
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
				$sOrder = " order by deal_amount desc";
			}
		}
		else
		{
			//$sOrder = "ORDER BY de.deal_create_date asc";
		}
		$sOrder = " order by deal_amount desc";
		$querys=$query;
		$query .= " $whereCond $sOrder $sLimit";
		$resObj = $this->db->query($query, $params);
		
		


		$deals = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				
				$row->amount=$row->deal_amount;
				$row->deal_amount=$this->convertShares($row->deal_amount);
				
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
		$countQuery = 'SELECT COUNT(`deal_id`) as totalRows FROM deal de left join company c on c.company_id = de.deal_company_id ' . $countWhereCond;
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $deals;
	
	
	}

	// Get top posts
	public function getTopPosts($count = 10) {
		$query = "select * 
						  from posts p
						  inner join user u on p.posted_by = u.user_id
						  left join fileupload f on p.post_file = f.file_id
						  where  ";
						  
						   $params = array();
						   
		if($this->user->demo==0)
		{
		 	$query .="p.org_id = ? or p.org_id = ?
						  order by posted_on desc
						  limit $count";
						  
			$params[] = $this->user->org_id;
		 	$params[] = "0";
						  
						  
		}
		else
		{
			 $query .="p.org_id = ?
						  order by posted_on desc
						  limit $count";
						  
			$params[] = $this->user->org_id;
		}
						  
						  
						  
						  
						  
		$resObj = $this->db->query($query, $params);

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
				//$amount=$amount/1000;
				//$amount=$amount.' K';
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
	
	public function getAllprioritys() {
		
		$query = 'SELECT * FROM `user_fields` WHERE `case_status`!="" AND (`org_id`=? or `org_id`=?) AND `case_status`!= ?';
		
		 					$params[] = "0";
						  	$params[] = $this->user->org_id;
							$params[] = 'Closed';
							
		$resObj = $this->db->query($query, $params);
		
		
		$case_status = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				if ($row->case_status) {
					$case_status[] = array($row->case_status);
				}
			}
		}
		return $case_status;
		
		
		
	}

	
	public function PieChart() {
		
		
		
		$fields = $this->getAllprioritys();
		$count=0;
		$priorityCases1= array();
		foreach ($fields as $rows) {
		$query = "select ca.priority,uf.case_status as status,count(ca.case_id) as case_count
						  from cases ca
						  left join user_fields uf on ca.status =uf.no";
						  // Role checkup
		
		$params = array();
		$query .= ' where uf.case_status=? AND ';
		$params[] = $rows[$count];
		if($this->user->demo==0)
		{
		/*if ($this->isManager) {
			$query .= "(ca.org_id = ? or ca.org_id = ? ) and ca.case_report_to_id = ? or ca.case_owner_id = ? group by ca.priority,ca.status ORDER BY FIELD( ca.priority,  'High',  'Medium',  'Low' )";
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$query .= " (ca.org_id = ? or ca.org_id = ? ) and ca.case_report_to_id = ? group by ca.priority,ca.status ORDER BY FIELD( ca.priority,  'High',  'Medium',  'Low' )";
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$query .= " ca.org_id = ? or ca.org_id = ?  group by ca.priority,ca.status ORDER BY FIELD( ca.priority,  'High',  'Medium',  'Low' )";
			$params[] = $this->user->org_id;
			$params[] = "0";
		}
		$params = array();
		$params[] = $rows[$count];*/
		$query .= " ca.org_id = ? or ca.org_id = ?  group by ca.priority,ca.status ORDER BY FIELD( ca.priority,  'High',  'Medium',  'Low' )";
		$params[] = $this->user->org_id;
		$params[] = "0";
		}
		else
		{
			
/*		if ($this->isManager) {
			$query .= "ca.org_id = ? and ca.case_report_to_id = ? or ca.case_owner_id = ? group by ca.priority,ca.status ORDER BY FIELD(ca.priority,  'High',  'Medium',  'Low' )";
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$query .= " ca.org_id = ? and ca.case_report_to_id = ? group by ca.priority,ca.status ORDER BY FIELD(ca.priority,  'High',  'Medium',  'Low' )";
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$query .= " ca.org_id = ? group by ca.priority,ca.status ORDER BY FIELD(ca.priority,  'High',  'Medium',  'Low' )";
			$params[] = $this->user->org_id;
		}*/
		
		
		$query .= " ca.org_id = '".$this->user->org_id."' group by ca.priority,ca.status ORDER BY FIELD(ca.priority,  'High',  'Medium',  'Low' )";
		//$params[] = $this->user->org_id;
		}
		
		
		$resObj = $this->db->query($query, $params);
	
		
		$priorityCases = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				if ($row->priority) {
					$priorityCases[] = array($row->priority, $row->status, $row->case_count);
					
					
				}
			}
		}
		$priorityCases1[$rows[$count]]=$priorityCases;
		}
		//print_r($priorityCases);
		
		return $priorityCases1;
	}
	
	
	
	public function datatablechart() {
		
		$query = "select uf.source as lead_source, count(le.lead_id) as lead_count
						  from lead le
						  left join user_fields uf on le.lead_source =uf.no";
						  
		$query .= ' where ';
		$params = array();
		if($this->user->demo==0)
		{
		if ($this->isManager) {
			$query .= ' (le.org_id = ? or le.org_id = ?) and le.report_to_id = ? or le.lead_owner_id = ? group by le.lead_source';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$query .= ' (le.org_id = ? or le.org_id = ?) and le.lead_owner_id = ? group by le.lead_source';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$query .= ' (le.org_id = ? or le.org_id = ?) group by le.lead_source';
			$params[] = $this->user->org_id;
			$params[] = "0";
		}	
		}
		else
		{
			if ($this->isManager) {
			$query .= ' le.org_id = ? and le.report_to_id = ? or le.lead_owner_id = ? group by le.lead_source';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$query .= ' le.org_id = ? and le.lead_owner_id = ? group by le.lead_source';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$query .= ' le.org_id = ? group by le.lead_source';
			$params[] = $this->user->org_id;
		}
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
public function getlead($count = '') {/*
		$query = "select SQL_CALC_FOUND_ROWS *, trim(concat(le.first_name, ' ', le.last_name)) as lead_name, if (u.name, u.name, u.name) as owner, if(uf.lead_status,uf.lead_status,uf.lead_status) as lead_status
						  from lead le
						  left join user u on le.lead_owner_id = u.user_id 
						   left join user_fields uf on le.lead_status =uf.no";
		
		
	
		$params = array();
		
		if($this->user->demo==0)
		{
		if ($this->isManager) {
			$query .= ' WHERE uf.lead_status!=? AND le.inbox=? AND  (le.org_id = ? or le.org_id = ?)and le.report_to_id = ? or le.lead_owner_id = ? ';
			$params[] = 'Archived';
			$params[] = "0";
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			
		} elseif ($this->isExecutive) {
			$query .= ' WHERE uf.lead_status!=? AND le.inbox=? AND   (le.org_id = ? or le.org_id = ?) and le.lead_owner_id = ? ';
			$params[] = 'Archived';
			$params[] = "0";
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			
		} elseif ($this->isAdmin && $this->user->org_id) {
			$query .= ' WHERE uf.lead_status!=? AND le.inbox=? AND   (le.org_id = ? or le.org_id = ?) ';
			$params[] = 'Archived';
			$params[] = "0";
			$params[] = $this->user->org_id;
			$params[] = "0";
			
		}
		}
		else
		{
			
		if ($this->isManager) {
			$query .= ' WHERE uf.lead_status!=? AND le.inbox=? AND  le.org_id = ? and le.report_to_id = ? or le.lead_owner_id = ? ';
			$params[] = 'Archived';
			$params[] = "0";
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			
		} elseif ($this->isExecutive) {
			$query .= ' WHERE  uf.lead_status!=? AND le.inbox=? AND  le.org_id = ? and le.lead_owner_id = ? ';
			$params[] = 'Archived';
			$params[] = "0";
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			
		} elseif ($this->isAdmin && $this->user->org_id) {
			$query .= ' WHERE uf.lead_status!=? AND le.inbox=? AND  le.org_id = ? ';
			$params[] = 'Archived';
			$params[] = "0";
			$params[] = $this->user->org_id;
			
		}
		}
		
		$query.=' order by le.lead_create_date desc limit '.$count;
		$resObj = $this->db->query($query,$params);

		$lead = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				
				$lead[] = $row; 
			}
		}
// echo "<pre>"; print_r($posts); exit;
		return $lead;
	*/
	
	
		if (!$filters) $filters = $this->filters;

		$query = "select SQL_CALC_FOUND_ROWS *, trim(concat(le.first_name, ' ', le.last_name)) as lead_name, if (u.name, u.name, u.name) as owner, if(uf.lead_status,uf.lead_status,uf.lead_status) as status
						  from lead le
						  left join user_fields uf on le.lead_status =uf.no 
						  left join user_fields uf1 on le.lead_source =uf1.no 
						  left join user u on le.lead_owner_id = u.user_id  ";

		// Role checkup
		$whereCond = '';
		$params = array();
		if($this->user->demo==0)
		{
		if ($this->isManager) {
			$whereCond .= ' where (uf.lead_status!=? AND le.inbox=?) AND (le.org_id = ? and le.report_to_id = ? or le.lead_owner_id = ?) or (le.org_id = ? and le.report_to_id = ? or le.lead_owner_id = ?)';
			$params[] = 'Archived';
			$params[] = "0";
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ' where  (uf.lead_status!=? AND le.inbox=?) AND (le.org_id = ? and le.lead_owner_id = ? ) or (le.org_id = ? and le.lead_owner_id = ? )';
			$params[] = 'Archived';
			$params[] = "0";
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' where  (uf.lead_status!=? AND le.inbox=?) AND (le.org_id = ? or le.org_id = ? )';
			$params[] = 'Archived';
			$params[] = "0";
			$params[] = $this->user->org_id;
			$params[] = "0";
		}
		
		}
		else
		{
			if ($this->isManager) {
			$whereCond .= ' where  uf.lead_status!=? AND le.inbox=? AND le.org_id = ? and le.report_to_id = ? or le.lead_owner_id = ?';
			$params[] = 'Archived';
			$params[] = "0";
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ' where  uf.lead_status!=? AND le.inbox=? AND le.org_id = ? and le.lead_owner_id = ?';
			$params[] = 'Archived';
			$params[] = "0";
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' where  uf.lead_status!=? AND le.inbox=? AND le.org_id = ?';
			$params[] = 'Archived';
			$params[] = "0";
			$params[] = $this->user->org_id;
		}
		
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
					else if($fkey =='lead_status')
					{
						
						 $filtersCondition .= ($filtersCondition ? ' and ' : '') . 'le.'."$fkey = ?";
						$params[] = $fvalue;
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
		$aColumns = array_keys($this->HomeLeadTableCols);
		// Paginating...
		$sLimit = "";
		if($count!="")
		{
		$sLimit = "LIMIT ".$count;
		}
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
			if ( $sOrder == "" )
			{
				$sOrder = " order by le.lead_create_date desc";
			}
		}
		else
		{
			if ( $sOrder == "" )
			{
				$sOrder = " order by le.lead_create_date desc";
			}
		}
		

		$query .= " $whereCond $sOrder $sLimit";
		$resObj = $this->db->query($query, $params);
		
		

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
		$countQuery = 'SELECT COUNT(`lead_id`) as totalRows  from lead le
						  left join user_fields uf on le.lead_status =uf.no 
						  left join user_fields uf1 on le.lead_source =uf1.no 
						  left join user u on le.lead_owner_id = u.user_id  ' . $countWhereCond;
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $leads;
	
	
	
	}
	
	


		// Get top posts
	public function getCompanies($count = '') {
		
		
		
	
			/*$query = "select SQL_CALC_FOUND_ROWS *, if (u.name, u.name, u.name) as owner , if(uf.customer_type,uf.customer_type,uf.customer_type) as com_cust_type
						  from company c 
						  left join user u on c.owner_id = u.user_id
						  
						   left join user_fields uf on c.com_cust_type =uf.no";
	  $whereCond = ' where c.associate_to != ? AND  c.inbox=? ';
	  
	  
	  $params = array();
	  $params[] = 'lead';
	  $params[] = '0';
		// Role checkup
		
		if($this->user->demo==0)
		{
		if ($this->isManager) {
			$whereCond .= ' and (c.org_id = ? or c.org_id = ?) and (c.report_to_id = ? or c.owner_id = ?) ';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ' and (c.org_id = ? or c.org_id = ?) and c.owner_id = ? ';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' and c.org_id = ? or c.org_id = ? ';
			$params[] = $this->user->org_id;
			$params[] = "0";
		}
		}
		else
		{
			if ($this->isManager) {
			$whereCond .= ' and c.org_id = ? and (c.report_to_id = ? or c.owner_id = ?) ';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ' and c.org_id = ? and c.owner_id = ? ';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' and c.org_id = ? ';
			$params[] = $this->user->org_id;
		}
		}
		
		$query.=" ".$whereCond." order by c.company_create_date desc limit $count";
			  
						  
		$resObj = $this->db->query($query, $params);

		$lead = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$company[] = $row;
			}
		}
// echo "<pre>"; print_r($posts); exit;
		return $company;*/
		
		
		
		if (!$filters){ $filters = $this->filters; 
		
		}else {}

		$query = "select SQL_CALC_FOUND_ROWS *, if (u.name, u.name, u.name) as owner , if(uf.customer_type,uf.customer_type,uf.customer_type) as com_cust_type, c.company_create_date as company_create_date
						  from company c 
						  left join user_fields uf on c.com_cust_type =uf.no
						  left join user u on c.owner_id = u.user_id";
	 	

		// Role checkup
		
		if($this->user->demo==0)
		{
			
		if ($this->isManager) {
			$whereCond = ' left join report_to rt on c.assign_to = rt.user_id where c.associate_to != ?';
			$params = array('lead');
			$whereCond .= ' and (c.org_id = ? or c.org_id = ? ) and (c.report_to_id = ? or c.owner_id = ? or c.report_to_id = ? or c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond = ' left join report_to rt on c.assign_to = rt.user_id where c.associate_to != ?';
			$params = array('lead');
			$whereCond .= ' and (c.org_id = ? or c.org_id = ? ) and (c.assign_to = ? or c.owner_id = ? or rt.report_to_id= ?) ';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;

		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond = 'where c.associate_to != ?';
			$params = array('lead');
			$whereCond .= ' and (c.org_id = ? or c.org_id = ? )';
			$params[] = $this->user->org_id;
			$params[] = "0";
		}
		}
		else
		{
			if ($this->isManager) {
			$whereCond = ' left join report_to rt on c.assign_to = rt.user_id where c.associate_to != ?';
			$params = array('lead');
			$whereCond .= ' and c.org_id = ? and (c.report_to_id = ? or c.owner_id = ? or c.report_to_id = ? or c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond = ' left join report_to rt on c.assign_to = rt.user_id where c.associate_to != ?';
			$params = array('lead');
			$whereCond .= ' and c.org_id = ? and (c.assign_to = ? or c.owner_id = ? or rt.report_to_id= ?) ';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;

		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond = 'where c.associate_to != ?';
			$params = array('lead');
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
		
		}

		// ======== Datatable adjustments ========
		$aColumns = array_keys($this->HomecompanyTableCols);
		// Paginating...
		$sLimit = "";
		if($count!="")
		{
		$sLimit = "LIMIT ".$count;
		}
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
			if ( $sOrder == "" )
			{
				$sOrder = " order by c.company_create_date desc";
			}
		}
		else
		{
			if ( $sOrder == "" )
			{
				$sOrder = " order by c.company_create_date desc";
			}
		}
		
		

		$query .= " $whereCond $sOrder $sLimit";
		
		
		
		$resObj = $this->db->query($query, $params);
		
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
	
	
	
				// Get top posts
	public function getDeals($count = '') {/*
		
		$query = "select SQL_CALC_FOUND_ROWS *, if (u.name, u.name, u.name) as owner , if(uf.stage,uf.stage,uf.stage) as stage
						  from deal de 
						  left join company c on de.deal_company_id = c.company_id
						   left join user_fields uf on de.stage =uf.no
						  left join user u on de.deal_owner_id = u.user_id";
						  
					
					$whereCond = ''; // ' where de.stage != ? and de.stage != ?';
		$params = array(); //array('Won', 'Lost');

		// Role checkup
		if($this->user->demo==0)
		{
			
		if ($this->isManager) {
			$whereCond .= ($whereCond ? ' and ' : ' where ') . ' de.inbox=? AND (de.org_id = ? or de.org_id = ?) and (de.report_to_id = ? or de.deal_owner_id = ?)';
			$params[] = "0";
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ($whereCond ? ' and' : ' where') . '  de.inbox=? AND (de.org_id = ? or de.org_id = ?) and de.deal_owner_id = ?';
			$params[] = "0";
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ($whereCond ? ' and' : ' where') . '  de.inbox=? AND (de.org_id = ? or de.org_id = ?)';
			$params[] = "0";
			$params[] = $this->user->org_id;
			$params[] = "0";
		}
		}
		else
		{
			if ($this->isManager) {
			$whereCond .= ($whereCond ? ' and ' : ' where ') . ' de.inbox=? AND de.org_id = ? and (de.report_to_id = ? or de.deal_owner_id = ?)';
			$params[] = "0";
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ($whereCond ? ' and' : ' where') . '  de.inbox=? AND de.org_id = ? and de.deal_owner_id = ?';
			$params[] = "0";
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ($whereCond ? ' and' : ' where') . '  de.inbox=? AND de.org_id = ?';
			$params[] = "0";
			$params[] = $this->user->org_id;
		}
		}
		
		$query.=" ".$whereCond." order by de.deal_create_date desc limit $count";
			  
						  
		$resObj = $this->db->query($query, $params);

		$lead = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$deal[] = $row;
			}
		}
// echo "<pre>"; print_r($posts); exit;
		return $deal;
		
		
	*/
	
	
	
		if (!$filters){ $filters = $this->filters; } if($filters=="") { $filters ='de.deal_id'; }
		
		$params = array();

		$query = "select SQL_CALC_FOUND_ROWS *, if (u.name, u.name, u.name) as owner,u.user_status as user_status, de.deal_owner_id as deal_owner_id ,de.deal_amount  as deal_amount,de.deal_create_date  as deal_create_date , if(uf.stage,uf.stage,uf.stage) as stage_name , if(de.stage,de.stage,de.stage) as stage , if(de.source,de.source,de.source) as  source
						  from deal de 
						  left join user_fields uf on de.stage =uf.no
						  left join company c on de.deal_company_id = c.company_id
						  left join user u on (de.deal_owner_id = u.user_id) ";
	//	$params[] = '0';
		
		//  left join user u on (c.assign_to = u.user_id AND c.assign_to != ? or c.owner_id=u.user_id) ";
		//$params[] = '0';
		
		
		$whereCond = ''; // ' where de.stage != ? and de.stage != ?';
		 //array('Won', 'Lost');

		//$whereCond = ''; // ' where de.stage != ? and de.stage != ?';
		 //array('Won', 'Lost');

		// Role checkup

		if($this->user->demo==0)
		{

		if ($this->isManager) {
			$whereCond .="left join report_to rt on deal_owner_id = rt.user_id". ($whereCond ? ' and ' : ' where ') . ' ( de.org_id = ? or de.org_id = ? )and (de.report_to_id = ? or de.deal_owner_id = ?  or  c.report_to_id = ? or  c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .="left join report_to rt on deal_owner_id = rt.user_id". ($whereCond ? ' and' : ' where') . ' (de.org_id = ? or de.org_id = ? ) and (de.deal_owner_id = ?  or c.assign_to = ? or rt.report_to_id= ?)';
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
			$whereCond .= "left join report_to rt on deal_owner_id = rt.user_id".($whereCond ? ' and ' : ' where ') . ' de.org_id = ? and (de.report_to_id = ? or de.deal_owner_id = ?  or  c.report_to_id = ? or  c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .="left join report_to rt on deal_owner_id = rt.user_id". ($whereCond ? ' and' : ' where') . ' de.org_id = ? and (de.deal_owner_id = ?  or c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ($whereCond ? ' and' : ' where') . ' de.org_id = ?';
			$params[] = $this->user->org_id;
		}
		
		}

		
		$whereCond .= ' AND uf.stage!= ? '; // '  de.stage != ? and de.stage != ?';
		$params[] = 'Won';
		$params[] = 'Lost';

		
		
		
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
					if (($fkey == 'exp_close') || ($fkey == 'deal_create_date') ) {
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
					else if($fkey =='first')
					{
						 $filtersCondition .= ($filtersCondition ? ' and ' : '') . "(de.deal_name LIKE '%".$fvalue."%' or de.deal_amount LIKE '%".$fvalue."%')" ;
						
					}
					else if($fkey =='stage')
					{
						
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
		$aColumns = array_keys($this->HomeOppTableCols);
		// Paginating...
		$sLimit = "";
		if($count!="")
		{
		$sLimit = "LIMIT ".$count;
		}
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
			if ( $sOrder == "" )
			{
				$sOrder = "order by de.deal_create_date desc";
			}
		}
		else
		{
			if ( $sOrder == "" )
			{
				$sOrder = " order by de.deal_create_date desc";
			}
		}
		//$sOrder = "ORDER BY de.deal_create_date desc";
		$querys=$query;
		$query .= " $whereCond $sOrder $sLimit";
		$resObj = $this->db->query($query, $params);
		
		$_SESSION['deal_export'] = $whereCond ;
		$_SESSION['deal_export_params'] = $params;


		$deals = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->deal_amount=$this->convertShares($row->deal_amount);
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
		$countQuery = 'select SQL_CALC_FOUND_ROWS *, COUNT(`deal_id`) as totalRows,if (u.name, u.name, u.name) as owner,u.user_status as user_status, de.deal_owner_id as deal_owner_id  , if(uf.stage,uf.stage,uf.stage) as stage_name , if(de.stage,de.stage,de.stage) as stage , if(de.source,de.source,de.source) as  source
						  from deal de 
						  left join user_fields uf on de.stage =uf.no
						  left join company c on de.deal_company_id = c.company_id
						  left join user u on (de.deal_owner_id = u.user_id) ' . $countWhereCond;
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $deals;
	}
	
		// Get tasks json for datatable
	public function getleadjson() {
		// Check for filters
		if (isset($_GET['filters'])) $this->filters = (array)json_decode($_GET['filters']);

		// Get tasks and arrange data for datatable
		$lead = $this->getlead();
		$output = $this->constructDTOutputHome($lead, array_keys($this->HomeLeadTableCols), 'lead_id', 'leads/details', 1);
		
	
		echo json_encode($output);
	}
	
	// Get companies json for datatable
	public function getnewcompanysjsno() {
		// Check for filters
		if (isset($_GET['filters'])) $this->filters = (array)json_decode($_GET['filters']);

		// Get companies and arrange data for datatable
		$companies = $this->getCompanies();
		$output = $this->constructDTOutputHome($companies, array_keys($this->HomecompanyTableCols), 'company_id', 'companies/details', 1);

		echo json_encode($output);
	}
	
	
		// Get deals json for datatable
	public function getdealsjson() {
		// Check for filters
	
		if (isset($_REQUEST['filters'])) $this->filters = (array)json_decode($_REQUEST['filters']);

		// Get deals and arrange data for datatable
		$deals = $this->getDeals();
		$output = $this->constructDTOutputHome($deals, array_keys($this->HomeOppTableCols), 'deal_id', 'deals/details', 1,"","4");
		echo json_encode($output);
	}
	
			// Get tasks json for datatable
	public function gettopopp() {
		// Check for filters
		if (isset($_REQUEST['filters'])) $this->filters = (array)json_decode($_REQUEST['filters']);
 
		// Get tasks and arrange data for datatable
		$Deals = $this->getTopDeals();
		
		$output = $this->constructDTOutputHome($Deals, array_keys($this->HomeTopOppCols), 'deal_id', 'deals/details', 1,"right");
		
	
		echo json_encode($output);
	}
		public function getlatestopp() {
		// Check for filters
			
		if (isset($_REQUEST['filters'])) $this->filters = (array)json_decode($_REQUEST['filters']);

		// Get tasks and arrange data for datatable
		$Deals = $this->getRecentDeals();
		
		$output = $this->constructDTOutputHome($Deals, array_keys($this->HomeLatestOppCols), 'deal_id','deals/details', 1,"right");
		
	
		echo json_encode($output);
	}
	
	
	
	
	

}