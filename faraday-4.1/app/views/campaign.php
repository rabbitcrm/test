<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class campaign extends BCZ_Controller {

	private $filters = '';

	public function __construct()
	{
		parent::__construct();

// echo "<pre>"; print_r($this->user); exit;		
	}

	public function index()
	{
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'campaign';
		
			if($_SESSION['filters']!="campaign")
		{
			unset($_SESSION['filters_key']);
			unset($_SESSION['filters_fvalue']);
		}
		
		
		$data['cols'] = array_values($this->CampaignTableCols);
		$data['mobileCols'] = array(0, 2, 5);

		if (isset($_REQUEST['filters'])) {
			$data['sourcePath'] = 'campaign/getcampaignjson';
			$this->load->view('DataTableView', $data);
		} else {
			$data['filters'] = array('owner' => array('col' => 'user_id', 'alias' => 'owner'), 
															 'Type' => array('col' => 'campaign_type', 'alias' => 'campaign_type_name'),
															 
										'Status' => array('col' => 'campaign_status', 'alias' => 'campaignstatus'),					 
															 'created' => array('col' => 'create_date', 'alias' => 'create_date', 'type' => 'date'));
			$data['mobFilters'] = array('user_id', 'create_date');

			$data['campaigns'] = $this->getCampaign();
			$data['content'] = 'CampaignsView';
			$this->load->view('FirstLayoutView', $data);
		}
	}

	// Get campaigns json for datatable
	public function getcampaignjson() {
		// Check for filters
		if (isset($_GET['filters'])) $this->filters = (array)json_decode($_GET['filters']);

		// Get campaigns and arrange data for datatable
		$campaign = $this->getCampaign();
		$output = $this->constructDTOutput($campaign, array_keys($this->CampaignTableCols), 'campaign_id', 'campaign/details', 1);

		echo json_encode($output);
	}

	// Export data
	public function export() {
		//$campaign = $this->getCampaign();
		$campaign = $this->getAllgetCampaignExport($_SESSION['campaign_export'],$_SESSION['campaign_export_params']);
		$this->exportData($campaign, 'campaigns_data.xls');
	}

	// Import data
	public function import() {
		$this->importData('campaigns_data.xls', 'campaign');
	}

	public function details($id) {
		$this->bodyClass = 'campaign-details';
		$this->pageTitle = $this->pageDesc = 'Campaign Details';
		$data['content'] = 'CampaignDetailsView';
		$data['users'] = $this->getAllUsers();
		$data['fields'] = $this->getAllUserFields();
		
		$statusList = array();
		$count = 0;
		foreach ($data['fields'] as $field) { 
		
			if ($field->campaign_status) {
				 $statusList[$count] = $field->campaign_status;
				if ($campaign->status == $field->campaign_status) $campaignStatusIndex = $count;
				$count++;
				
			}
		}
			// Arrange users for view manipulation
				$data['aUsers'] = array();
		foreach ($data['users'] as $user) {
			$data['aUsers'][$user->user_id] = $user;
		}
		
		// Get campaign details and arrange that data
		$campaign = $this->getCampaignDetails($id);
		
			
		$org_id=$this->user->org_id;
		if($this->user->demo==0)
		{
			$data['NextId']=$this->getNextIdAndPreviousId("select ca.*, u.name, uf.campaign_type  as campaign_type ,uf1.campaign_status as campaign_status from campaign ca left join user u on ca.owner_id = u.user_id left join user_fields uf on ca.campaign_type = uf.no left join user_fields uf1 on ca.campaign_status = uf1.no where  (ca.campaign_id  > ?) AND (ca.org_id = ? OR  ca.org_id = '0' )  ORDER BY ca.campaign_id ASC LIMIT 1",$id,"campaign_id",$org_id);

			$data['PreviousId']=$this->getNextIdAndPreviousId("select ca.*, u.name, uf.campaign_type  as campaign_type ,uf1.campaign_status as campaign_status from campaign ca left join user u on ca.owner_id = u.user_id left join user_fields uf on ca.campaign_type = uf.no left join user_fields uf1 on ca.campaign_status = uf1.no where  (ca.campaign_id  < ?) AND (ca.org_id = ? OR  ca.org_id = '0' )  ORDER BY ca.campaign_id desc LIMIT 1",$id,"campaign_id",$org_id);
		}
		else
		{
				$data['NextId']=$this->getNextIdAndPreviousId("select ca.*, u.name, uf.campaign_type  as campaign_type ,uf1.campaign_status as campaign_status from campaign ca left join user u on ca.owner_id = u.user_id left join user_fields uf on ca.campaign_type = uf.no left join user_fields uf1 on ca.campaign_status = uf1.no where  (ca.campaign_id  > ?) AND (ca.org_id = ? )  ORDER BY ca.campaign_id ASC LIMIT 1",$id,"campaign_id",$org_id);

			$data['PreviousId']=$this->getNextIdAndPreviousId("select ca.*, u.name, uf.campaign_type  as campaign_type ,uf1.campaign_status as campaign_status from campaign ca left join user u on ca.owner_id = u.user_id left join user_fields uf on ca.campaign_type = uf.no left join user_fields uf1 on ca.campaign_status = uf1.no where  (ca.campaign_id  < ?) AND (ca.org_id = ? )  ORDER BY ca.campaign_id desc LIMIT 1",$id,"campaign_id",$org_id);
			
		}
		
		$this->orgAccessCheck($campaign->org_id);
		
		$campaign->name = $campaign->campaign_name;
		
		// Created Before
		$startDate = new DateTime(date('Y-m-d', strtotime($campaign->create_date)));
		$endDate = new DateTime(date('Y-m-d'));
		$diff = $startDate->diff($endDate);
		$campaign->created_before = $this->formatDays($diff->days);

		// campaign owner
		if ($campaign->owner_id) {
			$owner = $this->getUserDetails($campaign->owner_id);
			$campaign->owner = ucfirst($owner->name);
		}

		// campaign reporter
		if ($campaign->report_to_id) {
			$reporter = $this->getUserDetails($campaign->report_to_id);
			$campaign->reporter = ucfirst($reporter->name);
		}

		// Reassigned from
		if ($campaign->assigned_from) {
			$reassignee = $this->getUserDetails($campaign->assigned_from);
			$campaign->reassignee = ucfirst($reassignee->name);
		}
		
		
		$data['fields'] = $this->getAllUserFields();
		$stages = array();
		$count = 0;
		foreach ($data['fields'] as $field) { 
			if ($field->campaign_status) {
				$stages[$field->no] = $field->campaign_status;
				if ($campaign->campaign_status == $field->campaign_status) $campaignStageIndex = $count;
				$count++;
			}
		}
		
		 $campaign->stages =$stages;
	
		 $data['status']= $this->getStatusName($campaign->campaign_status,'campaign_status');
print_r($data['status']);
		//$data['status']= $campaign->campaign_status;
		// campaign notes
		$data['notes'] = $this->getItemNotes('campaign', $id);

		// Get campaign tasks
		$data['tasks'] = $this->getItemTasks('campaign', $id);

		// campaign documents
		$data['docs'] = $this->getItemDocs('campaign', $id);

		// campaign history
		$data['history'] = $this->getItemHistory('campaign', $id);

		$data['campaign'] = $campaign;
		
		$this->load->view('FirstLayoutView', $data);
	}

	public function add($data=array()) {
		$this->bodyClass = 'create-campaign';
		$this->pageTitle = $this->pageDesc = 'Create Campaign';
		$data['content'] = 'CreateCampaignView';

		$data['users'] = $this->getAllUsers();					// Get all users
		$data['campaign_types'] = $this->getAllcampaign_types();	// Get all campaign_types
		$data['campaign_status'] = $this->getAllcampaignstatus();	// Get all campaign_status
		$data['responses'] = $this->getAllcampaignresponse();	// Get all campaign_types
		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$this->load->view('FirstLayoutView', $data);
	}

	public function submit() {
	
		// Gather form fields
		$data = $formFields = $params = array();
		$currDateTime = $this->getCurrTime();
		$valsStr = '';
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = ($fieldName == 'closedate') ? date('Y-m-d', strtotime($fieldVal)) : trim($fieldVal);
			$valsStr .= ($valsStr ? ', ' : '') . '?';
		}
		$formFields['report_to_id'] = $this->getUserReporter($formFields['assigned_to']);	// Get reporter id
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['owner_id'] = $formFields['assigned_to'];
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['org_id'] = $this->user->org_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['create_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['modify_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$compFields['associate_to'] = 'campaign';
		$compValsStr .= ($compValsStr ? ', ' : '') . '?';


		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Create a campaign with given details
		$campaignId = $this->getTableAutoID('campaign');
		$query = 'insert into campaign (' . implode(', ', array_keys($formFields)) . ') values (' . $valsStr . ')';
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			// Log activity
			$activity = array('action' => 'CREATE', 'type' => 'campaign', 'id' => $campaignId);
			$this->logUserActivity($activity);

			redirect(base_url() . 'campaign/details/' . $campaignId, 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while creating this campaign, please try again after sometime.';
		}

		$this->add($data);
	}

	public function edit($id, $data = array()) {
		
		$this->bodyClass = 'edit-campaign';
		$this->pageTitle = $this->pageDesc = 'Edit Campaign';
		 $data['content'] = 'EditCampaignView';


		$data['campaign'] = $this->getCampaignDetails($id);
		
		
		$data['users'] = $this->getAllUsers();					// Get all users
		$data['campaign_types'] = $this->getAllcampaign_types();	// Get all campaign_types
		$data['campaign_status'] = $this->getAllcampaignstatus();	// Get all campaign_status
		$data['responses'] = $this->getAllcampaignresponse();	// Get all campaign_types


		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$this->load->view('FirstLayoutView', $data);
	}

	public function update($id) {
	
	
		$currDateTime = $this->getCurrTime();

		// Gather form fields
		$data = $formFields = array();
		foreach ($_POST as $fieldName => $fieldVal) {
				$formFields[$fieldName] = ($fieldName == 'closedate') ? date('Y-m-d', strtotime($fieldVal)) : trim($fieldVal);
			$valsStr .= ($valsStr ? ', ' : '') . '?';
		}
		$formFields['report_to_id'] = $this->getUserReporter($formFields['owner_id']);	// Get reporter id
		$formFields['modify_date'] = $currDateTime;
		$formFields['owner_id'] = $formFields['owner_id'];
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		// Create a new company flow
	
		// Update campaign with given details
		$query = 'update campaign set ' . implode(' = ?, ', array_keys($formFields)) . ' = ? where campaign_id = ?';
		$formFields['campaign_id'] = $id;
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			// Log activity
			$activity = array('action' => 'UPDATE', 'type' => 'campaign', 'id' => $id);
			 
			$this->logUserActivity($activity);

			redirect(base_url() . "campaign/details/$id", 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while updating this campaign, please try again after sometime.';
		}

		$this->edit($data);
	}


	// Delete a campaign
	public function delete() {
		echo "sdfdsfsd";
		echo $campaignId = $_REQUEST['campaign_id'];
		if (!$campaignId) return;

		// Delete lead docs
		$docQuery = 'delete from fileupload where associate_id = ? and associate_to = ?';
		$docRes = $this->db->query($docQuery, array($campaignId, 'campaign'));

		// Delete lead notes
		$noteQuery = 'delete from note where associate_id = ? and associate_to = ?';
		$noteRes = $this->db->query($noteQuery, array($campaignId, 'campaign'));

		// Delete lead tasks
		$taskQuery = 'delete from task where associate_id = ? and associate_to = ?';
		$taskRes = $this->db->query($taskQuery, array($campaignId, 'campaign'));

		// Delete lead
		$deleteQuery = 'delete from campaign where campaign_id = ?';
		$deleteRes = $this->db->query($deleteQuery, array($campaignId));

		$res = array();
		if ($docRes && $noteRes && $taskRes && $deleteRes) {
			$res['success'] = true;
			$res['redirectUrl'] = base_url() . "campaign";
			$res['message'] = 'Successfully deleted this lead and dependencies.';

			// Log activity
			$activity = array('action' => 'DELETE', 'type' => 'campaign', 'id' => $campaignId);
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
	public function getCampaign($filters = '') {
		if (!$filters) $filters = $this->filters;
						  	$query = "select SQL_CALC_FOUND_ROWS *, if (u.name, u.name, u.name) as owner , uf.campaign_type  as campaign_type_name ,ca.campaign_type as  campaign_type , uf1.campaign_status as campaignstatus,ca.campaign_status as campaign_status
						  from campaign ca 
						  left join user u on ca.owner_id = u.user_id left join user_fields uf on ca.campaign_type = uf.no left join user_fields uf1 on ca.campaign_status = uf1.no";

		// Role checkup
		$whereCond = '';
		$params = array();
		if($this->user->demo==0)
		{
		if ($this->isManager) {
			$whereCond .= ' where (ca.org_id = ? or ca.org_id = ?) and ca.report_to_id = ? or ca.owner_id = ?';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ' where (ca.org_id = ? or ca.org_id = ?) and ca.owner_id = ?';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' where (ca.org_id = ? or ca.org_id = ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
		}
		}
		else
		{
			
		if ($this->isManager) {
			$whereCond .= ' where ca.org_id = ? and ca.report_to_id = ? or ca.owner_id = ?';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ' where ca.org_id = ? and ca.owner_id = ?';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' where ca.org_id = ?';
			$params[] = $this->user->org_id;
		}
		}
		$countWhereCond = $whereCond;
		$countParams = $params;

		// Apply filters
		if ($filters) {
			$filtersCondition = '';
			$ik=0;
			$_SESSION['filters']="campaign";
			
			foreach ($filters as $fkey => $fvalue) {
				
				$ik++;
				$_SESSION['filters_key'][$ik]=$fkey ;
				$_SESSION['filters_fvalue'][$ik]=$fvalue ;
					
				if ($fvalue && ($fvalue != 'All')) {
					if ($fkey == 'create_date') {
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
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "week($fkey) = week(now())";
								break;
							case 'last_week':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "week($fkey) = week(now())-1";
								break;
							case 'curr_month':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "month($fkey) = ?";
								$params[] = date('m');
								break;
							case 'last_month':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "month($fkey) = ?";
								$params[] = date('m') - 1;
								break;
							case '90_days':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "$fkey >= utc_timestamp() - interval 90 day";
								$params[] = date("Y-m-d");
								break;							
							default:
								break;
						}
					}
					else if($fkey=='campaign_type')
					{
						
							$filtersCondition .= ($filtersCondition ? ' and ' : '') . "ca."."$fkey = ?";
						$params[] = $fvalue;
					}
					else if($fkey=='campaign_status')
					{
						
							$filtersCondition .= ($filtersCondition ? ' and ' : '') . "ca."."$fkey = ?";
						$params[] = $fvalue;
					}
					else if($fkey=='campaign_name')
					{
					
							$filtersCondition .= ($filtersCondition ? ' and ' : '') . "ca."."$fkey LIKE '%".$fvalue."%'";
						
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
		$aColumns = array_keys($this->CampaignTableCols);
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
		
		$_SESSION['campaign_export'] = $whereCond;
		$_SESSION['campaign_export_params'] = $params;

		$companies = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->create_date = $this->convertDateTime($row->create_date);
				$companies[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countQuery = 'SELECT COUNT(`campaign_id`) as totalRows FROM campaign ca ' . $countWhereCond;
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $companies;
	}

	// Get a lead details
	public function getCampaignDetails($id) {
		$resObj = $this->db->query("select ca.*, u.name, uf.campaign_type  as campaign_type ,uf1.campaign_status as campaign_status from campaign ca left join user u on ca.owner_id = u.user_id left join user_fields uf on ca.campaign_type = uf.no left join user_fields uf1 on ca.campaign_status = uf1.no where ca.campaign_id = ?", array($id));
		
		
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

	// Get next Campaign
	public function getNextCampaign($id) {
		$resObj = $this->db->query("select * from campaign where lead_id > ? order by campaign_id limit 1", array($id));
		return $resObj->row();
	}

	// Get previous Campaign
	public function getPrevCampaign($id) {
		$resObj = $this->db->query("select * from campaign where lead_id < ? order by campaign_id desc limit 1", array($id));
		return $resObj->row();
	}
	
	
	
	public function changestatus() {
		// Update task
		$updateQuery = 'update campaign set campaign_status = ? where lead_id = ?';
		$params = array($_REQUEST['status'], $_REQUEST['id']);
		$updateRes = $this->db->query($updateQuery, $params);

		$res = array();
		if ($updateRes) {
			$res['success'] = true;
			$res['message'] = 'campaign status changed successfully.';
			
			// Log activity
			$activity = array('action' => 'UPDATE', 'type' => 'campaign', 'id' => $_REQUEST['id'], 'info' => json_encode(array('subaction' => 'change status')));
			$this->logUserActivity($activity);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while changing the campaign status.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

}