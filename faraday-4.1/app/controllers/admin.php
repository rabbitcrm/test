<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin extends BCZ_Controller {

	public function __construct()
	{
		parent::__construct();

		// Login checkup
		if (!isset($_SESSION['bcz_admin'])) {
			redirect(base_url().'login', 'location', 301);
		}

		// Accessible to only Super Admin
		if ($_SESSION['bcz_admin']->org_id || ($_SESSION['bcz_admin']->user_designation != 'SupAdmin')) show_404();
	}

	public function index()
	{
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'Admin';

		// Get organizations info
		$organizations = $this->getOrganizations();
		$data['organizations'] = $organizations;
		$data['cols'] = array_values($this->orgTableCols);
		$data['mobileCols'] = array(1, 2, 3);


		// Get users info
		$users = $this->getUsers();
		$adminCnt = $managerCnt = $executiveCnt = $activeCnt = 0;
		foreach ($users as $ui => $user) {
			if ($user->is_active == 'Y')	$activeCnt++;
			if ($user->user_designation == 'Admin')	$adminCnt++;
			if ($user->user_designation == 'Manager')	$managerCnt++;
			if ($user->user_designation == 'Executive')	$executiveCnt++;
		}
		$data['users'] = $users;
		$data['adminCnt'] = $adminCnt;
		$data['managerCnt'] = $managerCnt;
		$data['executiveCnt'] = $executiveCnt;
		$data['activeCnt'] = $activeCnt;

		// Active or logged in user counts
		$data['active_users_day'] = $this->getActiveUserCounts('day');
		$data['active_users_week'] = $this->getActiveUserCounts('week');
		$data['active_users_month'] = $this->getActiveUserCounts('month');

		// Get deals info
		$deals = $this->getDeals();
		$dealAmt = $wonDeals = $wonDealsAmt = 0;
		foreach ($deals as $di => $deal) {
			$dealAmt += $deal->deal_amount;
			if ($deal->stage == 'Won') {
				$wonDeals++;
				$wonDealsAmt += $deal->deal_amount;
			}
		}
		$data['deals'] = $deals;
		$data['dealAmt'] = $dealAmt;
		$data['wonDeals'] = $wonDeals;
		$data['wonDealsAmt'] = $wonDealsAmt;

		$this->load->view('AdminPanelView', $data);
	}

	// Get organizations json for datatable
	public function getorgsjson() {
		$organizations = $this->getOrganizations();
		$output = $this->constructDTOutput($organizations, array_keys($this->orgTableCols), '', '', 10);

		echo json_encode($output);
	}

	// Get organizations info
	public function getOrganizations() {
		$query = 'select o.name, count(u.user_id) as users_cnt, o.email, o.website, o.country from organizations o
							left join user u on o.id = u.org_id';

		// Role checkup
		$whereCond = '';
		$groupCond = 'group by u.org_id';
		$params = array();

		// ======== Datatable adjustments ========
		$aColumns = array_keys($this->orgTableCols);
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

		$query .= " $whereCond $groupCond $sOrder $sLimit";
		$resObj = $this->db->query($query, $params);

		$organizations = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$organizations[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countQuery = 'SELECT COUNT(`id`) as totalRows FROM organizations ';
		if ($whereCond) {
			$countQuery .= $whereCond;
			$countParams = $params;
		}
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $organizations;
	}

	// Get users info
	public function getUsers() {
		$query = "select user_id, user_designation, is_active
							from user
							where org_id != '' and user_designation != ?";
		$resObj = $this->db->query($query, 'SupAdmin');

		$users = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$users[] = $row;
			}
		}

		return $users;
	}

	// Get active user counts
	public function getActiveUserCounts($duration) {
		$query = "select count(distinct user_id) as users
							from user_audit";

		$params = array();
		if ($duration == 'day') {
			$query .= ' where date(date) = ?';
			$params = $this->getCurrTime('Y-m-d');
		} else if ($duration == 'week') {
			$query .= ' where week(date) = week(now())';
		} else if ($duration == 'month') {
			$query .= ' where month(date) = ?';
			$params = $this->getCurrTime('m');
		}

		$resObj = $this->db->query($query, $params);
		return $resObj->row();
	}

	// Get deals info
	public function getDeals() {
		$query = "select deal_id, stage, deal_amount from deal";
		$resObj = $this->db->query($query);

		$deals = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$deals[] = $row;
			}
		}

		return $deals;
	}

}