<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class orders extends BCZ_Controller {

	private $filters = '';

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->bodyClass = 'orders';
		$this->pageTitle = $this->pageDesc = 'sales orders';
		$data['cols'] = array_values($this->orderTableCols);
		$data['mobileCols'] = array(0, 4, 5);
		
		
		if($_SESSION['filters']!="orders")
		{
			unset($_SESSION['filters_key']);
			unset($_SESSION['filters_fvalue']);
		}
		
		

		if (isset($_REQUEST['filters'])) {
			$data['sourcePath'] = 'orders/getordersjson';
			$this->load->view('DataTableView', $data);
		} else {
			$data['filters'] = array('stage' => array('col' => 'so_stage', 'alias' => 'so_stage_name'), 
															 'owner' => array('col' => 'user_id', 'alias' => 'name', 'user_status' => 'user_status'),
															 'Delivery' => array('col' => 'estimated_delivery', 'alias' => 'estimated_delivery', 'type' => 'close_date'),
															 'created' => array('col' => 'so_create_date', 'alias' => 'so_create_date', 'type' => 'date'));
			$data['mobFilters'] = array('so_stage', 'estimated_delivery');
			
			$data['orders'] = $this->getOrders();
			$data['content'] = 'OrdersView';
			$this->load->view('FirstLayoutView', $data);
		}
	}

	// Get orders json for datatable
	public function getordersjson() {
		// Check for filters
		if (isset($_GET['filters'])) $this->filters = (array)json_decode($_GET['filters']);

		// Get orders and arrange data for datatable
		$orders = $this->getOrders();
		$output = $this->constructDTOutput($orders, array_keys($this->orderTableCols), 'so_id', 'orders/details', 1);

		echo json_encode($output);
	}
	
	
	
	
	public function getordersjson1() {
		
		// Get entity quotes and arrange data for datatable
		
		$quotes = $this->getItemOrders($_GET['type'], $_GET['id']);
		$output = $this->constructDTOutput($quotes, array_keys($this->orderTableCols), 'so_id', 'orders/details', 1);
		
		echo json_encode($output);
	}
	
	

	// Export data
	public function export() {
		//$orders = $this->getAllOrders();
		$orders = $this->getAllOrdersExport($_SESSION['orders_export'],$_SESSION['orders_export_params']);
		$this->exportData($orders, 'sales_order_data.xls');
	}

	public function details($id) {
		$this->bodyClass = 'order-details';
		$this->pageTitle = $this->pageDesc = 'Order Details';
		$data['content'] = 'OrderDetailsView';

		// Get order details and arrange that data
		$order = $this->getOrderDetails($id);
		
		$org_id=$this->user->org_id;
		if($this->user->demo==0)
		{
			$data['NextId']=$this->getNextIdAndPreviousId("select o.*, o.org_id, de.deal_name, c.company_name, co.first_name, co.last_name, co.email, u.name, u.user_email
									from sales_order o
									left join deal de on o.deal_id = de.deal_id
									left join company c on o.company_id = c.company_id 
									left join contact co on o.contact_id = co.contact_id 
									left join user u on o.so_owner_id = u.user_id 
									where  (o.so_id > ?) AND (o.org_id = ? OR  o.org_id = '0' )  ORDER BY o.so_id ASC LIMIT 1",$id,"so_id",$org_id);

			$data['PreviousId']=$this->getNextIdAndPreviousId("select o.*, o.org_id, de.deal_name, c.company_name, co.first_name, co.last_name, co.email, u.name, u.user_email
									from sales_order o
									left join deal de on o.deal_id = de.deal_id
									left join company c on o.company_id = c.company_id 
									left join contact co on o.contact_id = co.contact_id 
									left join user u on o.so_owner_id = u.user_id 
									where  (o.so_id < ?) AND (o.org_id = ? OR  o.org_id = '0' )  ORDER BY o.so_id desc LIMIT 1",$id,"so_id",$org_id);
		}
		else
		{
			
				$data['NextId']=$this->getNextIdAndPreviousId("select o.*, o.org_id, de.deal_name, c.company_name, co.first_name, co.last_name, co.email, u.name, u.user_email
									from sales_order o
									left join deal de on o.deal_id = de.deal_id
									left join company c on o.company_id = c.company_id 
									left join contact co on o.contact_id = co.contact_id 
									left join user u on o.so_owner_id = u.user_id 
									where  (o.so_id > ?) AND (o.org_id = ? )  ORDER BY o.so_id ASC LIMIT 1",$id,"so_id",$org_id);
		
	$data['PreviousId']=$this->getNextIdAndPreviousId("select o.*, o.org_id, de.deal_name, c.company_name, co.first_name, co.last_name, co.email, u.name, u.user_email
									from sales_order o
									left join deal de on o.deal_id = de.deal_id
									left join company c on o.company_id = c.company_id 
									left join contact co on o.contact_id = co.contact_id 
									left join user u on o.so_owner_id = u.user_id 
									where  (o.so_id < ?) AND (o.org_id = ? )  ORDER BY o.so_id desc LIMIT 1",$id,"so_id",$org_id);
		}
		$order->contact_name = $order->first_name . ($order->last_name ? " $order->last_name" : '');

		$this->orgAccessCheck($order->org_id);	// Organization access check

		// Created Before
		$startDate = new DateTime(date('Y-m-d', strtotime($order->so_create_date)));
		$endDate = new DateTime(date('Y-m-d'));
		$diff = $startDate->diff($endDate);
		$order->created_before = $this->formatDays($diff->days);

		// Get order stages
		$data['fields'] = $this->getAllUserFields();
		$stages = array();
		$count = 0;
		foreach ($data['fields'] as $field) { 
			if ($field->so_stage) {
				$stages[$field->no] = $field->so_stage;
				if ($order->so_stage == $field->so_stage) $orderStageIndex = $count;
				$count++;
			}
		}
$order->stages= $stages;
		/*if ($orderStageIndex < 3) {
			$order->stages = array_slice($stages, 0, 5);
		} else if ($orderStageIndex > (count($stages) - 4)) {
			$order->stages = array_slice($stages, (count($stages) - 5));
		} else {
			$order->stages = array_slice($stages, ($orderStageIndex-2), ($orderStageIndex+2));
		}*/

		// Arrange items data
		$items = array();
		foreach ($order as $orderKey => $orderVal) {
			if ((substr($orderKey, 0, 4) == 'item')) {
				$itemKey = substr($orderKey, 6);
				if (($itemKey == 'id') && !$orderVal) break;
				$items[substr($orderKey, 4, 1)][$itemKey] = $orderVal;

				if ($itemKey == 'id')	$items[substr($orderKey, 4, 1)]['product'] = $this->getProductDetails($orderVal);
			}
		}
		
		$data['so_stage']= $this->getStatusName($order->so_stage,'so_stage');
		
		$order->items = $items;
	
		$arrangedItems = $irows = array();
		$ik = 0;
		foreach ($items as $item) {
			$irows[$ik]->product = $item['product']->product_name;
			$irows[$ik]->price = $item['price'];
			$irows[$ik]->quantity = $item['qty'];
			$irows[$ik]->discount = $item['discount'];
			$irows[$ik]->vat = $item['vat'];
			$irows[$ik]->amount = $item['amount'];
			$ik++;
		}
		$arrangedItems['cols'] = array('product', 'price', 'quantity', 'discount', 'vat', 'amount');
		$arrangedItems['rows'] = $irows;
		$data['arrangedItems'] = $arrangedItems;

		// Email modal content
		$data['contact_id'] = $order->contact_id;
		$data['company_id'] = $order->company_id;
		$data['deal_id'] = $order->deal_id;
		$data['from'] = $this->user->user_email;
		$data['to'] = $order->email;
		$data['bcc'] = $this->user->user_email;
		//$data['message'] = "Dear {$order->first_name} {$order->last_name},\n\nThanks for your valuable inquiry. I reviewed your requirement carefully and prepared a proposal for you. Also attached necessary catalogue for your review. Please call me if you need any clarifications.\n\n{$this->user->mail_signature}";
		$data['message'] = "Dear {$order->first_name} {$order->last_name},\n\nGreetings of the day!\n\nAfter careful review and taking into accounts all the points that you have specified, I have prepared a proposal for you, please find it attached.\n\nI have also attached the necessary relevant documents related to this deal for your kind perusal.\n\nPlease feel free to call {$this->supportInfo['contact']} or write to {$this->supportInfo['email']} for any further clarifications.\n\nWarmest Regards\n\n{$this->user->mail_signature}";
		$data['type'] = 'order';
		$data['id']	= $order->so_id;

		$data['order'] = $order;
		$data['prev_order'] = $this->getPrevOrder($id);
		$data['next_order'] = $this->getNextOrder($id);
		$data['users'] = $this->getAllUsers();
		$cn= str_replace('&','-',$order->company_name);
		$cn= str_replace(' ','_',$order->company_name);
		$data['pdf_name'] = $cn . '_' . 'Order_' . $order->so_id . '.pdf';
		$this->load->view('FirstLayoutView', $data);

		// Generate PDF for this quotation
		$data['organization'] = $this->getOrganizationDetails();	// Get organization details
		$orderPdfHtml = $this->load->view('OrderPDFView', $data, TRUE);
		$this->generateOrderPdf($data['pdf_name'], $orderPdfHtml);
	}

	// Order PDF
	public function generateOrderPdf($name, $html) {
		$this->load->library('Pdf');
		$pdf = new pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('');
		$pdf->SetTitle('Sales Order');
		$pdf->SetSubject('');
		$pdf->SetKeywords('');

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		//set auto page breaks
		// $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$pdf->setLanguageArray($l);

		// set font
		$pdf->SetFont('helvetica', '', 10);

		// add a page
		$pdf->AddPage();

		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
	// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) {
	// 	$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	// 	$pdf->writeHTMLCell();

		$pdf->lastPage();

		$pdf->Output($this->ordersPath . $name, 'F');
	}

	// Edit order
	public function edit($id, $data = array()) {
		
		
		
		$this->bodyClass = 'edit-order';
		$this->pageTitle = $this->pageDesc = 'Edit Sales Order';
		$data['content'] = 'EditOrderView';

		// Get order details and arrange items data
		$order = $this->getOrderDetails($id);
		$items = array();
		foreach ($order as $orderKey => $orderVal) {
			if ((substr($orderKey, 0, 4) == 'item')) {
				$itemKey = substr($orderKey, 6);
				if (($itemKey == 'id') && !$orderVal) break;
				$items[substr($orderKey, 4, 1)][$itemKey] = $orderVal;
			}
		}
		$order->items = $items;
		$data['order'] = $order;
		
		$data['products'] = $this->getAllProducts();	// Get all products
		$data['contacts'] =  $this->getOneContacts($order->company_id);			// Get contacts
		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$this->load->view('FirstLayoutView', $data);
	}

	// Update order details
	public function update($id) {
		
		if($_POST['estimated_delivery']=="")
		{
			unset($_POST['estimated_delivery']);
		}
		
		if($_POST['committed_date']=="")
		{
			unset($_POST['committed_date']);
		}
		
		if($_POST['dispatch_date']=="")
		{
			unset($_POST['dispatch_date']);
		}
		if($_POST['install_date']=="")
		{
			unset($_POST['install_date']);
		}
		
		
		// Gather form fields
		$data = $formFields = array();
		foreach ($_POST as $fieldName => $fieldVal) {
			if (is_array($fieldVal)) {
				foreach ($fieldVal as $fk => $fval) {
					$formFields['item'.($fk+1).'_'.$fieldName] = $fval;
				}
			} else {
				$formFields[$fieldName] = (in_array($fieldName, array('estimated_delivery', 'committed_date', 'dispatch_date', 'install_date'))) ? date('Y-m-d', strtotime($fieldVal)) : trim($fieldVal);
			}
			
		}
		$formFields['so_owner_id'] = $this->user->user_id;
		$formFields['so_report_to_id'] = $this->user->report_to_id ? $this->user->report_to_id : $this->user->user_id;

		// Unset unwanted fields
		unset($formFields['copy_billing_addr']);

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Update order with given details
		$formFields['so_modify_date'] = $this->getCurrTime();
		$query = 'update sales_order set ' . implode(' = ?, ', array_keys($formFields)) . ' = ? where so_id = ?';
		$formFields['so_id'] = $id;
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			// Log activity
			$activity = array('action' => 'UPDATE', 'type' => 'so', 'id' => $id);
			$this->logUserActivity($activity);
			redirect(base_url() . "orders/details/$id", 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while updating this order, please try again after sometime.';
		}

		$this->edit($data);
	}

	// Change order stage
	public function changeStage() {	
		// Update order
		$updateQuery = 'update sales_order set so_stage = ? where so_id = ?';
		$params = array($_REQUEST['stage'], $_REQUEST['id']);
		$updateRes = $this->db->query($updateQuery, $params);

		$res = array();
		if ($updateRes) {
			$res['success'] = true;
			$res['message'] = 'Order stage changed successfully.';

			// Log activity
			$activity = array('action' => 'UPDATE', 'type' => 'so', 'id' => $_REQUEST['id'], 'info' => json_encode(array('subaction' => 'change stage')));
			$this->logUserActivity($activity);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while changing the order stage.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Get orders
	public function getOrders($filters = '') {
		if (!$filters) $filters = $this->filters;

		$query = "select SQL_CALC_FOUND_ROWS o.*, de.deal_name, c.company_name, co.first_name, co.last_name, u.user_id, u.name, if (u.name, u.name, u.username) as owner , if (so.so_stage, so.so_stage, so.so_stage) as so_stage_name  ,u.user_status as user_status
						  from sales_order o 
						  left join company c on c.company_id = o.company_id 
						  left join contact co on co.contact_id = o.contact_id 
						  left join deal de on de.deal_id = o.deal_id 
						  left join user u on o.so_owner_id = u.user_id
						   left join user_fields so on o.so_stage = so.no ";

		// Role checkup
		$whereCond = '';
		$params = array();
		
		if($this->user->demo==0)
		{
			
		if ($this->isManager) {
			$whereCond .= ' where (o.org_id = ? or o.org_id = ? )  and (o.so_report_to_id = ? or o.so_owner_id = ? or  c.report_to_id = ? or  c.assign_to = ? )';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ' where (o.org_id = ? or o.org_id = ? ) and (o.so_owner_id = ? or  c.assign_to = ? )';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' where (o.org_id = ? or o.org_id = ? )';
			$params[] = $this->user->org_id;
			$params[] = "0";
		}
		}
		else
		{
			if ($this->isManager) {
			$whereCond .= ' where o.org_id = ? and (o.so_report_to_id = ? or o.so_owner_id = ? or  c.report_to_id = ? or  c.assign_to = ? )';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ' where o.org_id = ? and (o.so_owner_id = ? or  c.assign_to = ? )';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' where o.org_id = ?';
			$params[] = $this->user->org_id;
		}
		}
		$countWhereCond = $whereCond;
		$countParams = $params;

		// Apply filters
		if ($filters) {
			$filtersCondition = '';
			
			$ik=0;
			$_SESSION['filters']="orders";
			
			foreach ($filters as $fkey => $fvalue) {
				
				$ik++;
					$_SESSION['filters_key'][$ik]=$fkey ;
					$_SESSION['filters_fvalue'][$ik]=$fvalue ;
				
				if ($fvalue && ($fvalue != 'All')) {
					
						if ($fkey == 'estimated_delivery')  {
						switch ($fvalue) {
							case 'today':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "date($fkey) = ?";
								$params[] = date("Y-m-d");
								break;
							/*
							case 'curr_week':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "YEARWEEK($fkey,1) = YEARWEEK(CURDATE(), 1)";
								break;
							
							case 'curr_month':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "month($fkey) = ? AND YEAR($fkey) = ?";
								$params[] = date('m');
								$params[] = date('Y');
								break;
							default:
								break;*/
								
							/*	case 'today':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "date($fkey) = ?";
								$params[] = date("Y-m-d");
								break;
								*/
							case 'tomorrow':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "date($fkey) = ?";
								$tomorrow = mktime(0, 0, 0, date("m"), date("d")+1, date("Y"));
								$params[] = date("Y-m-d", $tomorrow);
								break;
							case 'curr_week':
							
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "YEARWEEK($fkey,1) = YEARWEEK(CURDATE(), 1)";
								break;
							case 'next_week':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . " $fkey  <= CURDATE( ) + INTERVAL DAYOFWEEK( CURDATE( ) ) +6 DAY AND $fkey > CURDATE( ) + INTERVAL DAYOFWEEK( CURDATE( ) ) +1 DAY";
								break;
							case 'curr_month':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "month($fkey) = ? AND YEAR($fkey) = ?";
								$params[] = date('m');
								$params[] = date('Y');
								break;
							case 'next_month':
								$filtersCondition .= ($filtersCondition ? ' and ' : '') . "month($fkey) = ? AND YEAR($fkey) = ?";
								$params[] = date('m') + 1;
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
					else if ($fkey == 'so_create_date')  {
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
						 $filtersCondition .= ($filtersCondition ? ' and ' : '') . "( subject LIKE '%".$fvalue."%' or company_name LIKE '%".$fvalue."%' or deal_name LIKE '%".$fvalue."%')" ;
						
					} 
					
					else if($fkey=="so_stage"){
						$filtersCondition .= ($filtersCondition ? ' and ' : ' ') . "o."."$fkey = ?";
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

		$query .= " $whereCond group by o.so_id $sOrder $sLimit" ;
		$resObj = $this->db->query($query, $params);
		$_SESSION['orders_export']=$whereCond;
		$_SESSION['orders_export_params']=$params;

		$orders = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->created = $this->convertDateTime($row->so_create_date);
				$row->estimated_delivery = $this->convertDateTime($row->estimated_delivery);
				$orders[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countQuery = 'SELECT COUNT(`so_id`) as totalRows FROM sales_order o left join company c on c.company_id = o.company_id' . $countWhereCond;
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $orders;
	}

	// Get order details
	public function getOrderDetails($id) {
		$resObj = $this->db->query("select o.*, o.org_id, de.deal_name, c.company_name, co.first_name, co.last_name, co.email, u.name, u.user_email
									from sales_order o
									left join deal de on o.deal_id = de.deal_id
									left join company c on o.company_id = c.company_id 
									left join contact co on o.contact_id = co.contact_id 
									left join user u on o.so_owner_id = u.user_id 
									where so_id = ?", array($id));
		return $resObj->row();
	}

	// Get next order
	public function getNextOrder($id) {
		$resObj = $this->db->query("select * from sales_order where so_id > ? order by so_id limit 1", array($id));
		return $resObj->row();
	}

	// Get previous order
	public function getPrevOrder($id) {
		$resObj = $this->db->query("select * from sales_order where so_id < ? order by so_id desc limit 1", array($id));
		return $resObj->row();
	}
}