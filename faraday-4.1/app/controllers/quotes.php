<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class quotes extends BCZ_Controller {

	private $filters = '';

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'quotes';
		$data['cols'] = array_values($this->quoteTableCols);
		$data['mobileCols'] = array(0, 4, 5);
		
		if($_SESSION['filters']!="quotes")
		{
			unset($_SESSION['filters_key']);
			unset($_SESSION['filters_fvalue']);
		}

		if (isset($_REQUEST['filters'])) {
			$data['sourcePath'] = 'quotes/getquotesjson';
			$this->load->view('DataTableView', $data);
		} else {
			$data['filters'] = array('stage' => array('col' => 'quote_stage', 'alias' => 'quote_stage_name'), 
															 'owner' => array('col' => 'user_id', 'alias' => 'name' , 'user_status' => 'user_status'),
															 'created' => array('col' => 'quote_create_date', 'alias' => 'quote_create_date', 'type' => 'date'),
															 'Modified' => array('col' => 'quote_modify_date', 'alias' => 'quote_modify_date', 'type' => 'date'));
			$data['mobFilters'] = array('quote_stage', 'quote_create_date');

			$data['quotes'] = $this->getQuotes();
			$data['quotes_filters'] = $this->getQuotesfilters();
			$data['content'] = 'QuotesView';
			$this->load->view('FirstLayoutView', $data);
		}
	}

	// Get quotes json for datatable
	public function getquotesjson() {
		// Check for filters
		if (isset($_GET['filters'])) $this->filters = (array)json_decode($_GET['filters']);

		// Get quotes and arrange data for datatable
		$quotes = $this->getQuotes();
		$output = $this->constructDTOutput($quotes, array_keys($this->quoteTableCols), 'quote_id', 'quotes/details', 1);

		echo json_encode($output);
	}

	public function getentityquotesjson() {
		// Get entity quotes and arrange data for datatable
		$quotes = $this->getItemQuotes($_GET['type'], $_GET['id']);
		$output = $this->constructDTOutput($quotes, array_keys($this->quoteTableCols), 'quote_id', 'quotes/details', 1);
		
		echo json_encode($output);
	}

	// Export data
	public function export() {
		//$quotes = $this->getAllQuotes();
		$quotes = $this->getAllQuotesExport($_SESSION['quotes_export'],$_SESSION['quotes_export_params']);
		$this->exportData($quotes, 'quotes_data.xls');
	}

	public function details($id) {
		$this->bodyClass = 'quote-details';
		$this->pageTitle = $this->pageDesc = 'Quote Details';
		$data['content'] = 'QuoteDetailsView';
		


		// Get quote details and arrange that data
		$quote = $this->getQuoteDetails($id);
		
		
		$org_id=$this->user->org_id;
		if($this->user->demo==0)
		{
		
			 $data['NextId']=$this->getNextIdAndPreviousId("select qo.*, qo.org_id, de.deal_name, c.company_name, co.first_name, co.last_name, co.email, u.name, u.user_email
									from quote qo
									left join deal de on qo.deal_id = de.deal_id
									left join company c on qo.company_id = c.company_id 
									left join contact co on qo.contact_id = co.contact_id 
									left join user u on qo.quote_owner_id = u.user_id 
									where  (qo.quote_id > ?) AND (qo.org_id = ? OR  qo.org_id = '0' )  ORDER BY qo.quote_id ASC LIMIT 1",$id,"quote_id",$org_id);

			$data['PreviousId']=$this->getNextIdAndPreviousId("select qo.*, qo.org_id, de.deal_name, c.company_name, co.first_name, co.last_name, co.email, u.name, u.user_email
									from quote qo
									left join deal de on qo.deal_id = de.deal_id
									left join company c on qo.company_id = c.company_id 
									left join contact co on qo.contact_id = co.contact_id 
									left join user u on qo.quote_owner_id = u.user_id 
									where  (qo.quote_id < ?) AND (qo.org_id = ? OR  qo.org_id = '0' )  ORDER BY qo.quote_id desc LIMIT 1",$id,"quote_id",$org_id);
		}
		else
		{
			
				$data['NextId']=$this->getNextIdAndPreviousId("select qo.*, qo.org_id, de.deal_name, c.company_name, co.first_name, co.last_name, co.email, u.name, u.user_email
									from quote qo
									left join deal de on qo.deal_id = de.deal_id
									left join company c on qo.company_id = c.company_id 
									left join contact co on qo.contact_id = co.contact_id 
									left join user u on qo.quote_owner_id = u.user_id 
									where  (qo.quote_id > ?) AND (qo.org_id = ? )  ORDER BY qo.quote_id ASC LIMIT 1",$id,"quote_id",$org_id);
		
	$data['PreviousId']=$this->getNextIdAndPreviousId("select qo.*, qo.org_id, de.deal_name, c.company_name, co.first_name, co.last_name, co.email, u.name, u.user_email
									from quote qo
									left join deal de on qo.deal_id = de.deal_id
									left join company c on qo.company_id = c.company_id 
									left join contact co on qo.contact_id = co.contact_id 
									left join user u on qo.quote_owner_id = u.user_id 
									where  (qo.quote_id < ?) AND (qo.org_id = ? )  ORDER BY qo.quote_id desc LIMIT 1",$id,"quote_id",$org_id);
		}
		
		
		
		$quote->contact_name = $quote->first_name . ($quote->last_name ? " $quote->last_name" : '');

		$this->orgAccessCheck($quote->org_id);	// Organization access check

		// Created Before
		$startDate = new DateTime(date('Y-m-d', strtotime($quote->quote_create_date)));
		$endDate = new DateTime(date('Y-m-d'));
		$diff = $startDate->diff($endDate);
		$quote->created_before = $this->formatDays($diff->days);

		// Get quote stages
		$data['fields'] = $this->getAllUserFields();
		$stages = array();
		$count = 0;
		foreach ($data['fields'] as $field) { 
			if ($field->quote_stage) {

				if(($field->quote_stage!="Won")&&($field->quote_stage!="Lost"))
				{
				$stages[$field->no] = $field->quote_stage;
				if ($quote->quote_stage == $field->quote_stage) $quoteStageIndex = $count;
				$count++;
				}
			}
		}
		
	$quote->stages =$stages;
	
	 $data['stage']= $this->getStatusName($quote->quote_stage,'quote_stage');
	
	
		/*if ($quoteStageIndex < 3) {
			$quote->stages = array_slice($stages, 0, 5);
		} else if ($quoteStageIndex > (count($stages) - 4)) {
			$quote->stages = array_slice($stages, (count($stages) - 5));
		} else {
			$quote->stages = array_slice($stages, ($quoteStageIndex-2), ($quoteStageIndex+2));
		}*/

		// Arrange quote items data
		$items = array();
		foreach ($quote as $quoteKey => $quoteVal) {
			if ((substr($quoteKey, 0, 4) == 'item')) {
				$itemKey = substr($quoteKey, 6);
				if (($itemKey == 'id') && !$quoteVal) break;
				$items[substr($quoteKey, 4, 1)][$itemKey] = $quoteVal;

				if ($itemKey == 'id')	$items[substr($quoteKey, 4, 1)]['product'] = $this->getProductDetails($quoteVal);
			}
		}
		$quote->items = $items;
	
		$arrangedItems = $irows = array();
		$ik = 0;
		foreach ($items as $item) {
			$irows[$ik]->product = $item['product']->product_name;
			$irows[$ik]->price = $item['price'];
			$irows[$ik]->quantity = $item['qty'];
			$irows[$ik]->discount = $item['discount'];
			$irows[$ik]->vat = $item['vat'];
			$irows[$ik]->tax_type = $item['tax_type'];
			$irows[$ik]->amount = $item['amount'];
			$ik++;
		}
		$arrangedItems['cols'] = array('product', 'price', 'quantity', 'discount', 'tax_type', 'vat', 'amount');
		$arrangedItems['rows'] = $irows;
		$data['arrangedItems'] = $arrangedItems;

		// Email modal content
		$data['contact_id'] = $quote->contact_id;
		$data['company_id'] = $quote->company_id;
		$data['deal_id'] = $quote->deal_id;
		$data['from'] = $this->user->user_email;
		$data['to'] = $quote->email;
		$data['bcc'] = $this->user->user_email;
		//$data['message'] = "Dear {$quote->first_name} {$quote->last_name},\n\nThanks for your valuable inquiry. I reviewed your requirement carefully and prepared a proposal for you. Also attached necessary catalogue for your review. Please call me if you need any clarifications.\n\n{$this->user->mail_signature}";
		$data['message'] = "Dear {$quote->first_name} {$quote->last_name},\n\nGreetings of the day!\n\nAfter careful review and taking into accounts all the points that you have specified, I have prepared a proposal for you, please find it attached.\n\nI have also attached the necessary relevant documents related to this deal for your kind perusal.\n\nPlease feel free to call {$this->supportInfo['contact']} or write to {$this->supportInfo['email']} for any further clarifications.\n\nWarmest Regards\n\n{$this->user->mail_signature}";
		$data['type'] = 'quote';
		$data['id']	= $quote->quote_id;

		$data['quote'] = $quote;
		$data['prev_quote'] = $data['PreviousId'];
		$data['next_quote'] = $data['NextId'];
		$data['users'] = $this->getAllUsers();
		$cn= str_replace('&','-',$quote->company_name);
		$data['pdf_name'] = $cn . '_' . 'Quote_' . $quote->quote_id . '.pdf';
		$organization = $this->user->org_id ? $this->user->organization : $this->getOrganizationDetails();
		
		
		$data['organizations'] =$organization;
		
		$this->load->view('FirstLayoutView', $data);

		// Generate PDF for this quotation
		//$data['organization'] = $this->getOrganizationDetails();	// Get organization details
		
		$quotePdfHtml = $this->load->view('QuotePDFView', $data, TRUE);
		$this->generateQuotePdf($data['pdf_name'], $quotePdfHtml);
	}

	// Quote PDF
	public function generateQuotePdf($name, $html) {
		// ==== create new PDF document ====
		$this->load->library('Pdf');
		$pdf = new pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($this->supportInfo['name']);
		$pdf->SetTitle('Quotation');
		$pdf->SetSubject('');
		$pdf->SetKeywords($this->supportInfo['website']);

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

		$pdf->lastPage();

		$pdf->Output($this->quotesPath . $name, 'F');
	}

	public function add()
	{
		$this->bodyClass = 'create-quote';
		
		
		$this->pageTitle = $this->pageDesc = 'Create Quote';
		$data['terms_conditions']= $this->getOrganizationTermsConditions();
		
		if($this->user->org_id=='1')
		{
		$quoteId = $this->getTableAutoID('quote');
		$organization = $this->getOrganizationDetails();
		$financeDate = strtotime($organization->financeDate);
		$financeDay = date("d", $financeDate);
		$financeMonth = date("m", $financeDate);
		if((date("m") >= $financeMonth) && (date("m") <= 12)) {
			$startYear = date("Y", mktime(0, 0, 0, $financeMonth, $financeDay, date("Y")));
			$endYear = date("Y", mktime(0, 0, 0, $financeMonth, $financeDay, date("Y")+1));			
		
		} else {
			$startYear = date("Y", mktime(0, 0, 0, $financeMonth, $financeDay, date("Y")-1));
			$endYear = date("Y", mktime(0, 0, 0, $financeMonth, $financeDay, date("Y")));
		}
		$data['prefixsequence'] = $this->quotePrefix.$quoteId."/".$startYear."-".$endYear;
		
		
		}
		else
		{
		
		$PrefixSequenc = $this->getPrefixSequenceModule('quote');
		$data['prefixsequence'] = $PrefixSequenc['prefix']."/".$PrefixSequenc['sequence'];
		
		}
		$data['content'] = 'CreateQuoteView';
		$data['currency_freeze']=$this->getOrganizationcurrency_freeze();
		$data['deal'] = $this->getDealDetails($_SESSION['quote_deal']);
		$deal = $this->getDealDetails($_SESSION['quote_deal']);
		$data['products'] = $this->getAllProducts();	// Get all products
		//$data['contacts'] = $this->getAllContacts();		// Get contacts
		$data['contacts'] = $this->getoneContacts($deal->deal_company_id);		// Get contacts
		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$this->load->view('FirstLayoutView', $data);
	}

	public function submit() {
		if (isset($_POST['copy_billing_addr'])) unset($_POST['copy_billing_addr']);

		// Gather form fields
		$data = $formFields = array();
		$currDateTime = $this->getCurrTime();
		$valsStr = '';
		foreach ($_POST as $fieldName => $fieldVal) {
			if (is_array($fieldVal)) {
				foreach ($fieldVal as $fk => $fval) {
					$formFields['item'.($fk+1).'_'.$fieldName] = $fval;
					$valsStr .= ($valsStr ? ', ' : '') . '?';
				}
			} else {
				$formFields[$fieldName] = trim($fieldVal); //($fieldName == 'valid_till') ? date('Y-m-d', strtotime($fieldVal)) : trim($fieldVal);
				$valsStr .= ($valsStr ? ', ' : '') . '?';
			}
		}
		$formFields['quote_create_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['quote_modify_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['quote_owner_id'] = $this->user->user_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['quote_report_to_id'] = $this->user->report_to_id ? $this->user->report_to_id : $this->user->user_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		

		// Quote validity from settings
		$validityRow = $this->getQuoteValidity();
		$validity = $validity->quote_validity ? $validity->quote_validity : 30;
		$formFields['valid_till'] = date('Y-m-d H:i:s', strtotime("+{$validity} days"));
		$valsStr .= ($valsStr ? ', ' : '') . '?';

if($this->user->org_id=='1')
{
		// Quote no calculation
		$quoteId = $this->getTableAutoID('quote');
		$organization = $this->getOrganizationDetails();
		$financeDate = strtotime($organization->financeDate);
		$financeDay = date("d", $financeDate);
		$financeMonth = date("m", $financeDate);
		if((date("m") >= $financeMonth) && (date("m") <= 12)) {
			$startYear = date("Y", mktime(0, 0, 0, $financeMonth, $financeDay, date("Y")));
			$endYear = date("Y", mktime(0, 0, 0, $financeMonth, $financeDay, date("Y")+1));			
		
		} else {
			$startYear = date("Y", mktime(0, 0, 0, $financeMonth, $financeDay, date("Y")-1));
			$endYear = date("Y", mktime(0, 0, 0, $financeMonth, $financeDay, date("Y")));
		}
		$quoteNo = $this->quotePrefix.$quoteId."/".$startYear."-".$endYear;
		$formFields['quote_no'] = $quoteNo;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
}
else
{
		$PrefixSequenc = $this->getPrefixSequenceModule('quote');
		$formFields['quote_no'] = $PrefixSequenc['prefix']."/".$PrefixSequenc['sequence'];
		$valsStr .= ($valsStr ? ', ' : '') . '?';
}
		$PrefixSequenc['sequence']++;
		$query = 'UPDATE numbering SET `sequence`= "'.$PrefixSequenc['sequence'].'" WHERE numbering_id="'.$PrefixSequenc['numbering_id'].'"';
		$res = $this->db->query($query);
				
		

		// Unset unwanted fields
		//unset($formFields['copy_billing_addr']);

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Create a quote with given details
		$formFields['org_id'] = $this->user->org_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$quoteId = $this->getTableAutoID('quote');
		$query = 'insert into quote (' . implode(', ', array_keys($formFields)) . ') values (' . $valsStr . ')';
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			// Log activity
			$activity = array('action' => 'CREATE', 'type' => 'quote', 'id' => $quoteId);
			$this->logUserActivity($activity);

			unset($_SESSION['quote_deal']);

			if (isset($_SESSION['sourceUrl'])) {
				$sourceUrl = $_SESSION['sourceUrl'];
				unset($_SESSION['sourceUrl']);
				redirect($sourceUrl, 'location', 301);
			}
			
			redirect(base_url(). 'quotes/details/' . $quoteId, 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while creating this quote, please try again after sometime.';
		}

		$this->add($data);
	}
	//revised quote
	
	public function revisequote($id, $data = array()) {
		$this->bodyClass = 'edit-quote';
		$this->pageTitle = $this->pageDesc = 'Revise Quote';
		$data['content'] = 'ReviseQuote';

		// Get quote details and arrange items data
		$quote = $this->getQuoteDetails($id);
		$items = array();
		foreach ($quote as $quoteKey => $quoteVal) {
			if ((substr($quoteKey, 0, 4) == 'item')) {
				$itemKey = substr($quoteKey, 6);
				if (($itemKey == 'id') && !$quoteVal) break;
				$items[substr($quoteKey, 4, 1)][$itemKey] = $quoteVal;
			}
			
		}
		
		foreach ($quote as $quotes)
		{
			$company_id=$quotes-company_id;
			$data['contact_id'] =$quotes-contact_id;
		}
		
		
		$quote->items = $items;
		$data['quote'] = $quote;
		
		$data['products'] = $this->getAllProducts();	// Get all products
		//$data['contacts'] = $this->getAllContacts();
		
		$data['contacts'] = $this->getOneContacts($quote->company_id);	
			// Get contacts
		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$this->load->view('FirstLayoutView', $data);
	}

	// Edit quote
	public function edit($id, $data = array()) {
		$this->bodyClass = 'edit-quote';
		$this->pageTitle = $this->pageDesc = 'Edit Quote';
		$data['content'] = 'EditQuoteView';

		// Get quote details and arrange items data
		$quote = $this->getQuoteDetails($id);
		$items = array();
		foreach ($quote as $quoteKey => $quoteVal) {
			if ((substr($quoteKey, 0, 4) == 'item')) {
				$itemKey = substr($quoteKey, 6);
				if (($itemKey == 'id') && !$quoteVal) break;
				$items[substr($quoteKey, 4, 1)][$itemKey] = $quoteVal;
			}
		}
		$quote->items = $items;
		$data['quote'] = $quote;
		
		$data['products'] = $this->getAllProducts();	// Get all products
	
	$data['contacts'] = $this->getOneContacts($quote->company_id);		// Get contacts
		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$this->load->view('FirstLayoutView', $data);
	}
	
	
	// revised update quote details
	public function revisedupdate($id) {if (isset($_POST['copy_billing_addr'])) unset($_POST['copy_billing_addr']);

		// Gather form fields
		$revised_quote_id=$_POST['revised_quote_id'];
		$data = $formFields = array();
		$currDateTime = $this->getCurrTime();
		$valsStr = '';
		foreach ($_POST as $fieldName => $fieldVal) {
			if (is_array($fieldVal)) {
				foreach ($fieldVal as $fk => $fval) {
					$formFields['item'.($fk+1).'_'.$fieldName] = $fval;
					$valsStr .= ($valsStr ? ', ' : '') . '?';
				}
			} else {
				$formFields[$fieldName] = trim($fieldVal); //($fieldName == 'valid_till') ? date('Y-m-d', strtotime($fieldVal)) : trim($fieldVal);
				$valsStr .= ($valsStr ? ', ' : '') . '?';
			}
		}
		$formFields['quote_create_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		 $formFields['quote_modify_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		
		$formFields['quote_owner_id'] = $this->user->user_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['quote_report_to_id'] = $this->user->report_to_id ? $this->user->report_to_id : $this->user->user_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		
		// Quote validity from settings
		$validityRow = $this->getQuoteValidity();
		$validity = $validity->quote_validity ? $validity->quote_validity : 30;
		$formFields['valid_till'] = date('Y-m-d H:i:s', strtotime("+{$validity} days"));
		//$valsStr .= ($valsStr ? ' )' : '') . '?';

		// Quote no calculation
	
		// Unset unwanted fields
		//unset($formFields['copy_billing_addr']);

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Create a quote with given details
		$formFields['org_id'] = $this->user->org_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		//echo count($formFields);
		$quoteId = $this->getTableAutoID('quote');
		 count($formFields);
		 count($valsStr);
		 $query = 'insert into quote (' . implode(', ', array_keys($formFields)) . ') values (' . $valsStr . ')';
		
		$res = $this->db->query($query, array_values($formFields));
		
		
		$query = 'update quote set revised_quote_no = ? where quote_id = "'.$revised_quote_id.'"';
	
		$res = $this->db->query($query, $_POST['revised_quote_no']);
		

		if ($res) {
			// Log activity
			$activity = array('action' => 'CREATE', 'type' => 'quote', 'id' => $quoteId);
			$this->logUserActivity($activity);

			unset($_SESSION['quote_deal']);

			if (isset($_SESSION['sourceUrl'])) {
				$sourceUrl = $_SESSION['sourceUrl'];
				unset($_SESSION['sourceUrl']);
				redirect($sourceUrl, 'location', 301);
			}
			
			redirect(base_url(). 'quotes/details/' . $quoteId, 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while creating this quote, please try again after sometime.';
		}

		$this->add($data);}
	
	

	// Update quote details
	public function update($id) {
		if (isset($_POST['copy_billing_addr'])) unset($_POST['copy_billing_addr']);

		// Gather form fields
		$data = $formFields = array();
		foreach ($_POST as $fieldName => $fieldVal) {
			if (is_array($fieldVal)) {
				foreach ($fieldVal as $fk => $fval) {
					$formFields['item'.($fk+1).'_'.$fieldName] = $fval;
				}
			} else {
				$formFields[$fieldName] = ($fieldName == 'valid_till') ? date('Y-m-d', strtotime($fieldVal)) : trim($fieldVal);
			}
		}
		$formFields['quote_owner_id'] = $this->user->user_id;
		$formFields['quote_report_to_id'] = $this->user->report_to_id ? $this->user->report_to_id : $this->user->user_id;

		// Unset unwanted fields
		//unset($formFields['copy_billing_addr']);

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Update quote with given details
		$formFields['quote_modify_date'] = $this->getCurrTime();
		$query = 'update quote set ' . implode(' = ?, ', array_keys($formFields)) . ' = ? where quote_id = ?';
		$formFields['quote_id'] = $id;
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			// Log activity
			$activity = array('action' => 'UPDATE', 'type' => 'quote', 'id' => $id);
			$this->logUserActivity($activity);
			redirect(base_url() . "quotes/details/$id", 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while updating this quote, please try again after sometime.';
		}

		$this->edit($data);
	}

	// Change quote stage
	public function changeStage() {	
		// Update quote
		$updateQuery = 'update quote set quote_stage = ? where quote_id = ?';
		$params = array($_REQUEST['stage'], $_REQUEST['id']);
		$updateRes = $this->db->query($updateQuery, $params);

		$res = array();
		if ($updateRes) {
			$res['success'] = true;
			$res['message'] = 'Quote stage changed successfully.';

			// Log activity
			$activity = array('action' => 'UPDATE', 'type' => 'quote', 'id' => $_REQUEST['id'], 'info' => json_encode(array('subaction' => 'change stage')));
			$this->logUserActivity($activity);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while changing the quote stage.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Generate a sales order for quote
	public function generateSO($id) {
		// SO number calculation
		$soId = $this->getTableAutoID('sales_order');
		$organization = $this->getOrganizationDetails();
		$financeDate = strtotime($organization->financeDate);
		$financeDay = date("d", $financeDate);
		$financeMonth = date("m", $financeDate);
		if((date("m") >= $financeMonth) && (date("m") <= 12)) {
			$startYear = date("Y", mktime(0, 0, 0, $financeMonth, $financeDay, date("Y")));
			$endYear = date("Y", mktime(0, 0, 0, $financeMonth, $financeDay, date("Y")+1));			
		
		} else {
			$startYear = date("Y", mktime(0, 0, 0, $financeMonth, $financeDay, date("Y")-1));
			$endYear = date("Y", mktime(0, 0, 0, $financeMonth, $financeDay, date("Y")));
		}
		//$soNo = $this->soPrefix.$soId."/".$startYear."-".$endYear;
		
		$PrefixSequenc = $this->getPrefixSequenceModule('sales_order');
		$soNo = $PrefixSequenc['prefix']."/".$PrefixSequenc['sequence'];
	
		$PrefixSequenc['sequence']++;
		$query = 'UPDATE numbering SET `sequence`= "'.$PrefixSequenc['sequence'].'" WHERE numbering_id="'.$PrefixSequenc['numbering_id'].'"';
		$res = $this->db->query($query);
		
		
		//$soNo = $this->soPrefix.$soId."/".$startYear."-".$endYear;

		// Get order stages
		$fields = $this->getAllUserFields();
		$soStages = array();
		foreach ($fields as $field) { 
			if ($field->no) {
				$soStages[] = $field;
			}
		}
		usort($soStages, function ($a, $b) {
	    return strcmp($a->sort_order, $b->sort_order);
		});
		$soFirstStage = $soStages[0]->so_stage ? $soStages[0]->so_stage : '439';

		// Generate sales order
		$query = "insert into sales_order (org_id, so_no, subject, deal_id, company_id, contact_id, so_stage, bill_addr, bill_pobox, bill_city, bill_state, bill_pcode, bill_country, ship_addr, ship_pobox, ship_city, ship_state, ship_pcode, ship_country, terms, so_description, delivery, carrier, payment, so_currency, so_reference_po, item1_id, item1_desc, item1_qty, item1_discount, item1_tax_type,item1_vat, item1_price, item1_amount, item2_id, item2_desc, item2_qty, item2_discount,item2_tax_type, item2_vat, item2_price, item2_amount, item3_id, item3_desc, item3_qty, item3_discount,item3_tax_type, item3_vat, item3_price, item3_amount, item4_id, item4_desc, item4_qty, item4_discount,item4_tax_type, item4_vat, item4_price, item4_amount, item5_id, item5_desc, item5_qty, item5_discount,item5_tax_type, item5_vat, item5_price, item5_amount, install, frieght, total, so_create_date, so_owner_id, so_report_to_id)
				  (select ?, ?, subject, deal_id, company_id, contact_id, ?, bill_addr, bill_pobox, bill_city, bill_state, bill_pcode, bill_country, ship_addr, ship_pobox, ship_city, ship_state, ship_pcode, ship_country, terms, q_description, delivery, carrier, payment, quote_currency, ?, item1_id, item1_desc, item1_qty, item1_discount,item1_tax_type, item1_vat, item1_price, item1_amount, item2_id, item2_desc, item2_qty, item2_discount,item2_tax_type, item2_vat, item2_price, item2_amount, item3_id, item3_desc, item3_qty, item3_discount,item3_tax_type, item3_vat, item3_price, item3_amount, item4_id, item4_desc, item4_qty, item4_discount,item4_tax_type, item4_vat, item4_price, item4_amount, item5_id, item5_desc, item5_qty, item5_discount, item5_tax_type,item5_vat, item5_price, item5_amount, install, frieght, total, ?, quote_owner_id, quote_report_to_id from quote where quote_id = ?)";
	  $soRes = $this->db->query($query, array($this->user->org_id, $PrefixSequenc['prefix']."/".$PrefixSequenc['sequence'], $soFirstStage, '', $this->getCurrTime(), $id)); //$_POST['po_reference'], $_POST['quote_id']));

		$res = array();
		if ($soRes) {
			$res['success'] = true;
			$res['redirectUrl'] = base_url() . "orders/details/$soId";
			$res['message'] = 'Successfully generate SO for this quotation.';

			// Log activity
			$info = array('from' => 'quote', 'from_id' => $id); //$_POST['quote_id']);
			$activity = array('action' => 'GENERATE', 'type' => 'so', 'id' => $soId, 'info' => json_encode($info));
			$this->logUserActivity($activity);

			redirect(base_url() . "orders/details/$soId", 'location', 301);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while generating SO for this quotation, please try again after sometime.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Get quotes
	public function getQuotes($filters = '') {
		if (!$filters) $filters = $this->filters;
		
		$whereCond1 = ' AND uf.quote_stage != "Won" and uf.quote_stage != "Lost" and uf.quote_stage != "Archived"';

		$query = "select SQL_CALC_FOUND_ROWS q.*, u.user_status as user_status, de.deal_name, c.company_name, co.first_name, co.last_name, u.user_id, u.name , if (u.name, u.name, u.username) as owner  , if(uf.quote_stage,uf.quote_stage,uf.quote_stage) as quote_stage_name, if(q.quote_stage,q.quote_stage,q.quote_stage) as quote_stage
						  from quote q 
						  left join company c on c.company_id = q.company_id 
						  left join contact co on co.contact_id = q.contact_id 
						  left join deal de on de.deal_id = q.deal_id
						  
						  left join user_fields uf on q.quote_stage =uf.no
						  left join user u on q.quote_owner_id = u.user_id";

		// Role checkup
		$whereCond = '';
		$params = array();
		if($this->user->demo==0)
		{
		if ($this->isManager) {
			$whereCond .= 'left join report_to rt on q.quote_owner_id = rt.user_id where (q.org_id = ? or q.org_id = ?) and ( q.quote_report_to_id = ? or q.quote_owner_id = ? or  c.report_to_id = ? or  c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= 'left join report_to rt on q.quote_owner_id = rt.user_id where (q.org_id = ? or q.org_id = ?)  and (q.quote_owner_id = ? or c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' where (q.org_id = ? or q.org_id = ?) ';
			$params[] = $this->user->org_id;
			$params[] = "0";
		}
		}
		else
		{
			if ($this->isManager) {
			$whereCond .= 'left join report_to rt on q.quote_owner_id = rt.user_id where q.org_id = ? and ( q.quote_report_to_id = ? or q.quote_owner_id = ? or  c.report_to_id = ? or  c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= 'left join report_to rt on q.quote_owner_id = rt.user_id where q.org_id = ? and (q.quote_owner_id = ? or c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' where q.org_id = ?';
			$params[] = $this->user->org_id;
		}
		}
		$countWhereCond = $whereCond;
		$countParams = $params;

		// Apply filters
		if ($filters) {
			$filtersCondition = '';
			
			
			$ik=0;
			$_SESSION['filters']="quotes";
			foreach ($filters as $fkey => $fvalue) {
				
				$ik++;
					$_SESSION['filters_key'][$ik]=$fkey ;
					$_SESSION['filters_fvalue'][$ik]=$fvalue ;
					
				if ($fvalue && ($fvalue != 'All')) {
					if (($fkey == 'quote_create_date')||($fkey == 'quote_modify_date')) {
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
					else if($fkey =='quote_stage')
					{
						$whereCond1 ="";
						
						 $filtersCondition .= ($filtersCondition ? ' and ' : '') . 'q.'."$fkey = ?";
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

		$query .= " $whereCond $whereCond1 $sOrder $sLimit";
		$resObj = $this->db->query($query, $params);
		$_SESSION['quotes_export']=$whereCond;
		$_SESSION['quotes_export_params']=$params;

		$quotes = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->valid_till = $this->convertDateTime($row->valid_till);
				$row->quote_create_date = $this->convertDateTime($row->quote_create_date);
				$quotes[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countQuery = 'SELECT COUNT(`quote_id`) as totalRows FROM quote q left join company c on c.company_id = q.company_id' . $countWhereCond;
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $quotes;
	}
	
	
	// Get quotes
	public function getQuotesfilters($filters = '') {
		if (!$filters) $filters = $this->filters;

		$query = "select SQL_CALC_FOUND_ROWS q.*, u.user_status as user_status, de.deal_name, c.company_name, co.first_name, co.last_name, u.user_id, u.name , if (u.name, u.name, u.username) as owner  , if(uf.quote_stage,uf.quote_stage,uf.quote_stage) as quote_stage_name, if(q.quote_stage,q.quote_stage,q.quote_stage) as quote_stage
						  from quote q 
						  left join company c on c.company_id = q.company_id 
						  left join contact co on co.contact_id = q.contact_id 
						  left join deal de on de.deal_id = q.deal_id
						  
						  left join user_fields uf on q.quote_stage =uf.no
						  left join user u on q.quote_owner_id = u.user_id";

		// Role checkup
		$whereCond = '';
		$params = array();
		if($this->user->demo==0)
		{
		if ($this->isManager) {
			$whereCond .= 'left join report_to rt on q.quote_owner_id = rt.user_id where (q.org_id = ? or q.org_id = ?) and ( q.quote_report_to_id = ? or q.quote_owner_id = ? or  c.report_to_id = ? or  c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= 'left join report_to rt on q.quote_owner_id = rt.user_id where (q.org_id = ? or q.org_id = ?)  and (q.quote_owner_id = ? or c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = "0";
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' where (q.org_id = ? or q.org_id = ?) ';
			$params[] = $this->user->org_id;
			$params[] = "0";
		}
		}
		else
		{
			if ($this->isManager) {
			$whereCond .= 'left join report_to rt on q.quote_owner_id = rt.user_id where q.org_id = ? and ( q.quote_report_to_id = ? or q.quote_owner_id = ? or  c.report_to_id = ? or  c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isExecutive) {
			$whereCond .= 'left join report_to rt on q.quote_owner_id = rt.user_id where q.org_id = ? and (q.quote_owner_id = ? or c.assign_to = ? or rt.report_to_id= ?)';
			$params[] = $this->user->org_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
			$params[] = $this->user->user_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' where q.org_id = ?';
			$params[] = $this->user->org_id;
		}
		}
		$countWhereCond = $whereCond;
		$countParams = $params;

		$sLimit="";
		$sOrder = "ORDER BY uf.sort_order desc";
		$query .= " $whereCond $sOrder $sLimit";
		$resObj = $this->db->query($query, $params);

		$quotes = array();
		if ($resObj->num_rows()) {
			foreach ($resObj->result() as $row) {
				$row->valid_till = $this->convertDateTime($row->valid_till);
				$row->quote_create_date = $this->convertDateTime($row->quote_create_date);
				$quotes[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countQuery = 'SELECT COUNT(`quote_id`) as totalRows FROM quote q left join company c on c.company_id = q.company_id' . $countWhereCond;
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $quotes;
	}
	
	

	// Get quote details
	public function getQuoteDetails($id) {
		$resObj = $this->db->query("select qo.*, qo.org_id, de.deal_name, c.company_name, co.first_name, co.last_name, co.email, u.name, u.user_email
									from quote qo
									left join deal de on qo.deal_id = de.deal_id
									left join company c on qo.company_id = c.company_id 
									left join contact co on qo.contact_id = co.contact_id 
									left join user u on qo.quote_owner_id = u.user_id 
									where quote_id = ?", array($id));
		return $resObj->row();
	}

	// Get next quote
	public function getNextQuote($id) {
		$resObj = $this->db->query("select * from quote where quote_id > ? order by quote_id limit 1", array($id));
		return $resObj->row();
	}

	// Get previous quote
	public function getPrevQuote($id) {
		$resObj = $this->db->query("select * from quote where quote_id < ? order by quote_id desc limit 1", array($id));
		return $resObj->row();
	}
		public function getOrganizationcurrency_freeze ()
	{
		
		$currency_freeze= $this -> db-> select('*')-> where('org_id',$this->user->org_id)-> limit(1)-> get('organization_settings');
		$cur=$currency_freeze->row_array();
		foreach($cur as $freeze)
		{
			 $currency=$freeze->currency_freeze;
		}
		return $currency_freeze->row_array(); 

	} 
}