<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tasks extends BCZ_Controller {

	private $filters = '';

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'tasks';
		$data['cols'] = array_values($this->taskTableCols);
		$data['mobileCols'] = array(0, 2, 3);
		
		if($_SESSION['filters']!="tacks")
		{
			unset($_SESSION['filters_key']);
			unset($_SESSION['filters_fvalue']);
		}
		

		if (isset($_REQUEST['filters'])) {
			$data['sourcePath'] = 'tasks/gettasksjson';
			$this->load->view('DataTableView', $data);
		} else {
			$data['filters'] = array(
			'User' => array('col' => 'task_owner_id', 'alias' => 'name', 'user_status' => 'user_status'),
															 'status' => array('col' => 'status', 'alias' => 'task_status'),
															 'Due Date' => array('col' => 'due_date', 'alias' => 'due_date', 'type' => 'date'),'priority' => array('col' => 'priority', 'alias' => 'priority'));
			$data['mobFilters'] = array('status', 'due_date');

			$data['tasks'] = $this->getTasks();
			$data['content'] = 'TasksView';
			$this->load->view('FirstLayoutView', $data);
		}
	}

	// Get tasks json for datatable
	public function gettasksjson() {
		// Check for filters
		if (isset($_GET['filters'])) $this->filters = (array)json_decode($_GET['filters']);

		// Get tasks and arrange data for datatable
		$tasks = $this->getTasks();
		$output = $this->constructDTOutput($tasks, array_keys($this->taskTableCols), 'task_id', 'tasks/details', 1);

		echo json_encode($output);
	}

	public function getentitytasksjson() {
		// Get entity tasks and arrange data for datatable
		$tasks = $this->getItemTasks($_GET['type'], $_GET['id']);
		$output = $this->constructDTOutput($tasks, array_keys($this->taskTableCols), 'task_id', 'tasks/details', 1);
		
		echo json_encode($output);
	}

	// Export data
	public function export() {
		//$tasks = $this->getAllTasks();
		$tasks = $this->getAllTasksExport($_SESSION['tasks_export'],$_SESSION['tasks_export_params']);
		$this->exportData($tasks, 'tasks_data.xls');
	}

	public function details($id) {
		$this->bodyClass = 'task-details';
		$this->pageTitle = $this->pageDesc = 'Task Details';
		$data['content'] = 'TaskDetailsView';
		$data['users'] = $this->getAllUsers();

		// Get task details and arrange that data
		$task = $this->getTaskDetails($id);
		
		
		
		$org_id=$this->user->org_id;
		if($user->demo==0)
		{
			
			
			$data['NextId']=$this->getNextIdAndPreviousId("select *, t.org_id from task t left join user u on t.task_owner_id = u.user_id where (t.task_id > ?) AND (t.org_id = ? OR  t.org_id = '0' )  ORDER BY t.task_id ASC LIMIT 1",$id,"task_id",$org_id);

			$data['PreviousId']=$this->getNextIdAndPreviousId("select *, t.org_id from task t left join user u on t.task_owner_id = u.user_id where (t.task_id < ?) AND (t.org_id = ? OR  t.org_id = '0' )  ORDER BY t.task_id desc LIMIT 1",$id,"task_id",$org_id);
		}
		else
		{
			
				$data['NextId']=$this->getNextIdAndPreviousId("select *, t.org_id from task t left join user u on t.task_owner_id = u.user_id where  t.task_id > ? AND le.org_id = ?  ORDER BY t.task_id ASC LIMIT 1",$id,"t.task_id",$org_id);
		
	$data['PreviousId']=$this->getNextIdAndPreviousId("select *, t.org_id from task t left join user u on t.task_owner_id = u.user_id where t.task_id < ? AND t.org_id = ?  ORDER BY t.task_id desc LIMIT 1",$id,"t.task_id",$org_id);
		}
		
		
		

		$this->orgAccessCheck($task->org_id);	// Organization access check

		// Get task assignee details
		$task->assignee = $this->getUserDetails($task->assign_to);

		// Arrange users for view manipulation
		$data['aUsers'] = array();
		foreach ($data['users'] as $user) {
			$data['aUsers'][$user->user_id] = $user;
		}

		// Created Before
		$startDate = new DateTime(date('Y-m-d', strtotime($task->task_create_date)));
		$endDate = new DateTime(date('Y-m-d'));
		$diff = $startDate->diff($endDate);
		$task->created_before = $this->formatDays($diff->days);

		// Get task status
		$data['fields'] = $this->getAllUserFields();
		$statusList = array();
		$count = 0;
		foreach ($data['fields'] as $field) { 
			if ($field->task_status) {
				$statusList[$field->no] = $field->task_status;
				if ($task->status == $field->task_status) $taskStatusIndex = $count;
				$count++;
			}
		}
$task->statusList =$statusList;

 $data['stage']= $this->getStatusName($task->status,'task_status');
 
 $data['type']= $this->getStatusName($task->type,'task_type');
 
 
 
		/*if ($taskStatusIndex < 3) {
			$task->statusList = array_slice($statusList, 0, 5);
		} else if ($taskStatusIndex > (count($statusList) - 4)) {
			$task->statusList = array_slice($statusList, (count($statusList) - 5));
		} else {
			$task->statusList = array_slice($statusList, ($taskStatusIndex-2), ($taskStatusIndex+2));
		}*/

		// Task notes
		$data['notes'] = $this->getItemNotes('task', $id);

		// Task documents
		$data['docs'] = $this->getItemDocs('task', $id);

		// Lead history
		$data['history'] = $this->getItemHistory('task', $id);

		// Get associated item
		if (in_array($task->associate_to, array('deal', 'lead', 'contact', 'company', 'campaign'))) {
			$associated = $this->getTaskAssociation($task->associate_to, $task->associate_id);
			
			 $associatedType = $task->associate_to;

			if ($associatedType == 'company') {
				$associated->aname = $associated->company_name;
				$associated->alink = base_url() . "companies/details/".$associated->company_id;
	    } else if ($associatedType == 'contact') { 
				$associated->aname = $associated->first_name . ($associated->last_name ? " $associated->last_name" : '');
				$associated->alink = base_url() . "contacts/details/".$associated->contact_id;
	    } else if ($associatedType == 'deal') {
				$associated->aname = $associated->deal_name;
				$associated->alink = base_url() . "deals/details/".$associated->deal_id;
	    } else if ($associatedType == 'lead') { 
				$associated->aname = $associated->first_name . ($associated->last_name ? " $associated->last_name" : '');
				$associated->alink = base_url() . 'leads/details/'.$associated->lead_id;
	    }
		else if ($associatedType == 'campaign') { 
				$associated->aname = $associated->campaign_name;
				$associated->alink = base_url() . 'campaign/details/'.$associated->campaign_id;

	    }

	    $data['associated'] = $associated;
		}

		$data['task'] = $task;
		$data['prev_task'] = $this->getPrevTask($id);
		$data['next_task'] = $this->getNextTask($id);
		$this->load->view('FirstLayoutView', $data);
	}

	public function add($data=array()) {
		$this->bodyClass = 'create-task';
		$this->pageTitle = $this->pageDesc = 'Create Task';
		$data['content'] = 'CreateTaskView';

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
			$formFields['associate_to'] = 'task';
			$valsStr .= ($valsStr ? ', ' : '') . '?';
		}
		$formFields['task_owner_id'] = $this->user->user_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['task_report_to_id'] = $this->user->report_to_id ? $this->user->report_to_id : $this->user->user_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['status'] = '29';
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['org_id'] = $this->user->org_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['task_create_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['task_modify_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side
		
		// Create a task with given details
		$taskId = $this->getTableAutoID('task');
		$query = 'insert into task (' . implode(', ', array_keys($formFields)) . ') values (' . $valsStr . ')';

		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			// Log activity
			$info = array('associate_to' => $formFields['associate_to'], 'associate_id' => $formFields['associate_id']);
			$activity = array('action' => 'CREATE', 'type' => 'task', 'id' => $taskId, 'info' => json_encode($info));
			$this->logUserActivity($activity);

			if (isset($_SESSION['taskInfo'])) unset($_SESSION['taskInfo']);

			if (isset($_SESSION['sourceUrl'])) {
				$sourceUrl = $_SESSION['sourceUrl'];
				unset($_SESSION['sourceUrl']);
				redirect($sourceUrl, 'location', 301);
			}

			if (!$modal_flag) redirect(base_url() . 'tasks/details/' . $taskId, 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while creating this task, please try again after sometime.';
		}

		if ($modal_flag) {
			$data['tasks'][0] = true;
			$data['entityType'] = $formFields['associate_to'];
			$data['entityId'] = $formFields['associate_id'];
			$this->load->view('EntityTasksView', $data);
		} else {
			$this->add($data);
		}
	}

	public function edit($id, $data = array()) {
		$this->bodyClass = 'edit-task';
		$this->pageTitle = $this->pageDesc = 'Edit Task';
		$data['content'] = 'EditTaskView';

		$data['task'] = $this->getTaskDetails($id);

		$data['users'] = $this->getAllUsers();
		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$this->load->view('FirstLayoutView', $data);
	}

	public function update($id) {
		// Gather form fields
		$data = $formFields = array();
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = ($fieldName == 'due_date') ? date('Y-m-d H:i:s', strtotime($fieldVal)) : trim($fieldVal);
		}
		
		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Update task with given details
		$formFields['task_modify_date'] = $this->getCurrTime();
		$query = 'update task set ' . implode(' = ?, ', array_keys($formFields)) . ' = ? where task_id = ?';
		$formFields['task_id'] = $id;
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			// Log activity
			$activity = array('action' => 'UPDATE', 'type' => 'task', 'id' => $id);
			$this->logUserActivity($activity);
			redirect(base_url() . "tasks/details/$id", 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while updating this task, please try again after sometime.';
		}

		$this->edit($data);
	}

	// Change task status
	public function changestatus() {
		// Update task
		$updateQuery = 'update task set status = ? where task_id = ?';
		$params = array($_REQUEST['status'], $_REQUEST['id']);
		$updateRes = $this->db->query($updateQuery, $params);

		$res = array();
		if ($updateRes) {
			$res['success'] = true;
			$res['message'] = 'Task status changed successfully.';
			
			// Log activity
			$activity = array('action' => 'UPDATE', 'type' => 'task', 'id' => $_REQUEST['id'], 'info' => json_encode(array('subaction' => 'change status')));
			$this->logUserActivity($activity);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while changing the task status.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Reassign task
	public function reassign() {
		$taskId = $_POST['task_id'];
		$reassignId = $_POST['assign_to'];

		// Reassign task
		$taskQuery = 'update task set assign_to = ?, task_modify_date = ? where task_id = ?';
		$taskRes = $this->db->query($taskQuery, array($reassignId, $this->getCurrTime(), $taskId));

		$res = array();
		if ($taskRes) {
			$res['success'] = true;
			$res['message'] = 'Successfully reassigned this task.';

			// Log activity
			$task = $this->getTaskDetails($taskId);
			$info = array('from' => $task->assign_to, 'to' => $reassignId);
			$activity = array('action' => 'REASSIGN', 'type' => 'task', 'id' => $taskId, 'info' => json_encode($info));
			$this->logUserActivity($activity);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while reassigninig this task, please try again after sometime.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Delete a task
	public function delete() {
		$taskId = $_POST['task_id'];
		if (!$taskId) return;

		// Delete task
		$deleteQuery = 'delete from task where task_id = ?';
		$deleteRes = $this->db->query($deleteQuery, array($taskId));

		$res = array();
		if ($deleteRes) {
			$res['success'] = true;
			$res['redirectUrl'] = base_url() . "tasks";
			$res['message'] = 'Successfully deleted this task and dependencies.';

			// Log activity
			$activity = array('action' => 'DELETE', 'type' => 'task', 'id' => $taskId);
			$this->logUserActivity($activity);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while deleting this task or dependences, please try again after sometime.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Get tasks
	public function getTasks($filters = '') {
		if (!$filters) $filters = $this->filters;

		$query = "select SQL_CALC_FOUND_ROWS *, u.user_status as user_status, if (u.name, u.name, u.name) as owner , if (uf.task_status, uf.task_status, uf.task_status) as task_status ,t.priority as priority , if(uf1.task_type,uf1.task_type,uf1.task_type) as task_type
						  from task t 
						  left join user u on t.task_owner_id = u.user_id 
						  left join user_fields uf on t.status = uf.no  
						  left join user_fields uf1 on t.type = uf1.no ";
		$whereCond = ' where t.associate_to != ?';
		
		//where data ex: $params = array('lead');
		$params = array('');

		// Role checkup
			if($this->user->demo==0)
		{
		if ($this->isManager) {
			$whereCond .= ' and (t.org_id = ? or t.org_id = ? ) and (t.task_report_to_id = ? or t.task_owner_id = ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;		
		} elseif ($this->isExecutive) {
			$whereCond .= ' and (t.org_id = ? or t.org_id = ?) and t.task_owner_id = ?';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' and (t.org_id = ? or t.org_id = ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
		}
		}
		else
		{
			if ($this->isManager) {
			$whereCond .= ' and t.org_id = ? and (t.task_report_to_id = ? or t.task_owner_id = ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;		
		} elseif ($this->isExecutive) {
			$whereCond .= ' and t.org_id = ? and t.task_owner_id = ?';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' and t.org_id = ?';
			$params[] = $this->user->org_id;
		}
		}
		$countWhereCond = $whereCond;
		$countParams = $params;

		// Apply filters
		if ($filters) {
			$filtersCondition = '';
			$ik=0;
			$_SESSION['filters']="tacks";
			
			foreach ($filters as $fkey => $fvalue) {
				
				$ik++;
					$_SESSION['filters_key'][$ik]=$fkey ;
					$_SESSION['filters_fvalue'][$ik]=$fvalue ;
					
				if ($fvalue && ($fvalue != 'All')) {
					if ($fkey == 'due_date') {
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
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "$fkey >=  (NOW() - INTERVAL 90 DAY) AND $fkey <= NOW()";
								$params[] = date("Y-m-d");
								break;							
							default:
								break;
						}
					} 	else if($fkey =='first')
					{
						 $filtersCondition .= ($filtersCondition ? ' and ' : '') . "(t.task_name LIKE '%".$fvalue."%' or t.type LIKE '%".$fvalue."%' or t.priority LIKE '%".$fvalue."%' )" ;
						
					}
						else if($fkey =='type')
					{
						$filtersCondition .= ($filtersCondition ? ' and ' : '') . "t."."$fkey = ?";
						$params[] = $fvalue;
						
					}
					
					else if($fkey =='priority')
					{
						$filtersCondition .= ($filtersCondition ? ' and ' : '') . "t."."$fkey = ?";
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

		$query .= " $whereCond $sOrder $sLimit";
		$resObj = $this->db->query($query, $params);


		$_SESSION['tasks_export']=$whereCond;
		$_SESSION['tasks_export_params']=$params;
		
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
		$countQuery = 'SELECT COUNT(`task_id`) as totalRows FROM task t ' . $countWhereCond;
		$countRes = $this->db->query($countQuery, $countParams);

		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;
		
		

		return $tasks;
	}

	// Get a task details
	public function getTaskDetails($id) {
		$resObj = $this->db->query("select *, t.org_id from task t left join user u on t.task_owner_id = u.user_id where task_id = ?", array($id));
		return $resObj->row();
	}

	// Get next task
	public function getNextTask($id) {
		$resObj = $this->db->query("select * from task where task_id > ? order by task_id limit 1", array($id));
		return $resObj->row();
	}

	// Get previous task
	public function getPrevTask($id) {
		$resObj = $this->db->query("select * from task where task_id < ? order by task_id desc limit 1", array($id));
		return $resObj->row();
	}

	// Get task associated item
	public function getTaskAssociation($type, $id) {
		$resObj = $this->db->query("select * from $type where {$type}_id = ?", array($id));
		return $resObj->row();
	}
}