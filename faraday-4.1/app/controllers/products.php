<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class products extends BCZ_Controller {

	private $filters = '';

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'products';
		
		$data['currency_freeze']=$this->getOrganizationcurrency_base();
		$data['cols'] = array_values($this->productTableCols);
		$data['mobileCols'] = array(0, 2, 3);
		
		if($_SESSION['filters']!="products")
		{
			unset($_SESSION['filters_key']);
			unset($_SESSION['filters_fvalue']);
		}
		

		if (isset($_REQUEST['filters'])) {
			$data['sourcePath'] = 'products/getproductsjson';
			$this->load->view('DataTableView', $data);
		} else {
			$data['filters'] = array('Name' => array('col' => 'product_name', 'alias' => 'product_name'),'part No' => array('col' => 'partno', 'alias' => 'partno'),'category' => array('col' => 'category', 'alias' => 'category_name'),'created' => array('col' => 'create_date', 'alias' => 'create_date', 'type' => 'date')
			);
			$data['products'] = $this->getProducts();
			$data['content'] = 'ProductsView';
			$this->load->view('FirstLayoutView', $data);
		}
	}

	// Get products json for datatable
	public function getproductsjson() {
		// Check for filters
		if (isset($_GET['filters'])) $this->filters = (array)json_decode($_GET['filters']);

		// Get products and arrange data for datatable
		$products = $this->getProducts();
		$output = $this->constructDTOutput($products, array_keys($this->productTableCols), 'product_id', 'products/details', 1);

		echo json_encode($output);
	}

	public function getentityproductsjson() {
		// Get entity products and arrange data for datatable
		$products = $this->getDealProducts($_GET['id']);
		$output = $this->constructDTOutput($products, array_keys($this->productTableCols), 'product_id', 'products/details', 1);
		
		echo json_encode($output);
	}

	public function getproductcasesjson() {
		// Get product cases and arrange data for datatable
		$cases = $this->getProductItems('cases', $_GET['id'], 'case');
		$output = $this->constructDTOutput($cases, array_keys($this->caseTableCols), 'case_id', 'cases/details', 1);
		
		echo json_encode($output);
	}

	// Export data
	public function export() {
		//$products = $this->getAllProducts();
		$products = $this->getAllProductsExport($_SESSION['product_export'],$_SESSION['product_export_params']);
		$this->exportData($products, 'products_data.xls');
	}





	// Import data
	public function import() {
		$params[] = $this->user->org_id;
		$query="DELETE FROM `products_tmp` WHERE org_id = ?";
			
		$resObj = $this->db->query($query, $params);
		
	$this->bodyClass ='ProductsImportView';
	$this->pageTitle = $this->pageDesc = 'Products Import View';
	$data['content'] = 'ProductsImportView';
	$this->load->view('FirstLayoutView', $data);
	}
	
	public function importcsv() {
		
		
		$this->bodyClass ='ProductsMapping';
		 
		$this->pageTitle = $this->pageDesc = 'Products Import Mapping';
		$companies_tmp_TableCols="`org_id`, `product_name`,`price`, `create_date`, `modify_date`";
		$TableCols=$companies_tmp_TableCols;
		$table = 'products_tmp';
		$count='5';
		$data = array();
		$validationFailed = false;
		if ($_FILES['import_file']['type'] != 'application/vnd.ms-excel') {
			$data['success'] = false;
			$data['message'] = "Please upload a valid file";
		}

		if (!$validationFailed) {
			$res = $this->importDatasproducts($_FILES['import_file']['tmp_name'], $table,$TableCols,$count);
			$data['success'] = true;
			$data['message'] = "Import operation is successfull.";
			redirect(base_url() . 'products/mapping/' , 'location', 301);
		} 
	
		
	else {
			$data['messages']['error']['fail'] = 'Something went wrong while creating this companies, please try again after sometime.';
		}

		
		 
		//$this->importData('leads_data.xls', 'lead');
	}
	
	
	public function mapping() {
		
		$params = array();
		$this->bodyClass = "ProductsImportMappingView";
		$this->pageTitle = $this->pageDesc = 'Companies Import Mapping View';
		
		$data['fields'] = $this->getAllUserFields();	// Get all user fields	
		$data['content'] = 'ProductsImportMappingView';
		$query = "select *  from products_tmp where org_id = ? ORDER BY product_id ASC limit 1";
		$params[] = $this->user->org_id;
		$resObj = $this->db->query($query, $params);
		$mapping = array();
		if ($resObj->num_rows()) {
			
				
			foreach ($resObj->result() as $row) {
				$data['product_table_data']=array (array ('name' => 'Product Name','col' => 'product_name','value'=>$row->product_name),array ('name' => 'Price','col' => 'price','value'=>$row->price));
			}
		}
		$this->load->view('FirstLayoutView', $data);
	}
	
	public function importmapping() {
		
	
	$category=$_POST['category'];
	unset($_POST['category']);
	$query = 'update products_tmp set category= ? where org_id = ? ' ;
		$res = $this->db->query($query, array($category,$this->user->org_id));
		
		
		$formFields = $params = array();
		foreach ($_POST as $fieldName => $fieldVal) {
			if($fieldVal!="")
			{
				$formFields[$fieldName] = trim($fieldVal);
				$table_data .= ($fieldName ? ', ' : '') . $fieldName;
				$valsStr .= (trim($fieldVal) ? ', ' : '') . trim($fieldVal);
			}
			
		}
		$query="INSERT INTO products(org_id,category,create_date,modify_date".$valsStr.") (SELECT org_id,category,create_date,modify_date".$table_data." from products_tmp WHERE org_id = ?) ";
		
		$params[] = $this->user->org_id;
		$resObj = $this->db->query($query, $params);
		
		$query="DELETE FROM `products_tmp` WHERE org_id = ?";
			
		$resObj = $this->db->query($query, $params);
		
		if($resObj)
		{
		redirect(base_url() . 'products/' , 'location', 301);
		}
	}
	
	
	
	
	
	public function details($id) {
		$this->bodyClass = 'product-details';
		$this->pageTitle = $this->pageDesc = 'Product Details';
		$data['content'] = 'ProductDetailsView';
		$data['currency_freeze']=$this->getOrganizationcurrency_base();

		// Get product details and arrange that data
		$product = $this->getProductDetails($id);
		
		$org_id=$this->user->org_id;
		
		if($this->user->demo==0)
		{
			
			
			$data['NextId']=$this->getNextIdAndPreviousId("select * from products where product_id > ? AND (org_id = ? OR org_id = '0' )  ORDER BY product_id ASC LIMIT 1",$id,"product_id",$org_id);

			$data['PreviousId']=$this->getNextIdAndPreviousId("select * from products where product_id < ? AND (org_id = ? OR org_id = '0' )  ORDER BY product_id desc LIMIT 1",$id,"product_id",$org_id);
		}
		else
		{
			
				$data['NextId']=$this->getNextIdAndPreviousId("select * from products where product_id > ? AND (org_id = ?)  ORDER BY product_id ASC LIMIT 1",$id,"product_id",$org_id);
		
	$data['PreviousId']=$this->getNextIdAndPreviousId("select * from products where product_id < ? AND (org_id = ? )  ORDER BY product_id desc LIMIT 1",$id,"product_id",$org_id);
		}
		
		$category_id= $product->category;
		
		 $resObj2 = $this->db->query("select * from user_fields where no = $category_id");
		 if ($resObj2->num_rows()) {
			foreach ($resObj2->result() as $row2) {
			$product->category= $row2->product_category ;
				
			}
		}
		
		//$categoryName = $this->getcategoryDetails($category_id);
		
	//	$product->category=$categoryName->product_category;

		$this->orgAccessCheck($product->org_id);	// Organization access check

		// Arrange users for view manipulation
		$data['users'] = $this->getAllUsers();
		$data['aUsers'] = array();
		foreach ($data['users'] as $user) {
			$data['aUsers'][$user->user_id] = $user;
		}

		// Created Before
		$startDate = new DateTime(date('Y-m-d', strtotime($product->create_date)));
		$endDate = new DateTime(date('Y-m-d'));
		$diff = $startDate->diff($endDate);
		$product->created_before = $this->formatDays($diff->days);

		// Product notes
		$data['notes'] = $this->getItemNotes('product', $id);

		// Get product tasks and arrange data for datatable construction
		$data['tasks'] = $this->getItemTasks('product', $id);

		// Get contact tickets and arrange data for datatable construction
		$data['cases'] = $this->getProductItems('cases', $id, 'case');

		// Product documents
		$data['docs'] = $this->getItemDocs('product', $id);

		// Product history
		$data['history'] = $this->getItemHistory('product', $id);

		$data['product'] = $product;
		$data['prev_product'] = $this->getPrevProduct($id);
		$data['next_product'] = $this->getNextProduct($id);
		$data['fields'] = $this->getAllUserFields();
		$this->load->view('FirstLayoutView', $data);
	}

	public function add($data=array()) {
		$data['currency_freeze']=$this->getOrganizationcurrency_base();
		$this->bodyClass = 'create-product';
		$this->pageTitle = $this->pageDesc = 'Create Product';
		$data['content'] = 'CreateProductView';

		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$this->load->view('FirstLayoutView', $data);
	}

	public function submit() {
		$data['currency_freeze']=$this->getOrganizationcurrency_base();
		// Check for the task creation through modal
		$from=$_POST['currency'];
		unset($_POST['currency']);
		if (isset($_POST['modal_flag'])) {
			$modal_flag = $_POST['modal_flag'];
			unset($_POST['modal_flag']);
			
			
		}

		// Gather form fields
		$data = $formFields = $params = array();
		$currDateTime = $this->getCurrTime();
		$valsStr = '';
		foreach ($_POST as $fieldName => $fieldVal) {
		$formFields[$fieldName] = trim($fieldVal);
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		}
		$formFields['create_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['modify_date'] = $currDateTime;
		$valsStr .= ($valsStr ? ', ' : '') . '?';
		$formFields['org_id'] = $this->user->org_id;
		$valsStr .= ($valsStr ? ', ' : '') . '?';

		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Create a product with given details
		$productId = $this->getTableAutoID('products');
		$query = 'insert into products (' . implode(', ', array_keys($formFields)) . ') values (' . $valsStr . ')';
		$res = $this->db->query($query, array_values($formFields));
		$insert_id = $this->db->insert_id();
		
		
		$formFields['org_id'];
		
		
		$currency=$this->currency($from, $to, $amount);

		if ($res) {
			// Log activity
			$activity = array('action' => 'CREATE', 'type' => 'product', 'id' => $productId);
			$this->logUserActivity($activity);

			if (!$modal_flag) redirect(base_url() . 'products/details/' . $productId, 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while creating this product, please try again after sometime.';
		}

		if ($modal_flag) {
			$product = $this->getProductDetails($productId);
			$option = '<option value="'.$productId.'" data-price="'.$product->price.'" data-price-usd="'.$product->usd_price.'">'.$product->category . ' - ' . $product->product_name . ' - ' . $product->partno.'</option>';
			$this->output
	    		->set_content_type('application/json')
	    		->set_output(json_encode(array('product_id' => $productId, 'option' => $option)));
		} else {
			$this->add($data);
		}
	}

	public function edit($id, $data = array()) {
		$data['currency_freeze']=$this->getOrganizationcurrency_base();
		$this->bodyClass = 'edit-product';
		$this->pageTitle = $this->pageDesc = 'Edit Product';
		$data['content'] = 'EditProductView';

		$data['product'] = $this->getProductDetails($id);

		$data['fields'] = $this->getAllUserFields();	// Get all user fields
		$this->load->view('FirstLayoutView', $data);
	}

	public function update($id) {
		$data['currency_freeze']=$this->getOrganizationcurrency_base();
		// Gather form fields
		$data = $formFields = array();
		foreach ($_POST as $fieldName => $fieldVal) {
			$formFields[$fieldName] = trim($fieldVal);
		}
		
		// TODO: Add validation if needed, skipping perhaps we are already doing this at the client side

		// Update product with given details
		$formFields['modify_date'] = $this->getCurrTime();
		$query = 'update products set ' . implode(' = ?, ', array_keys($formFields)) . ' = ? where product_id = ?';
		$formFields['product_id'] = $id;
		$res = $this->db->query($query, array_values($formFields));

		if ($res) {
			// Log activity
			$activity = array('action' => 'UPDATE', 'type' => 'product', 'id' => $id);
			$this->logUserActivity($activity);
			redirect(base_url() . "products/details/$id", 'location', 301);
		} else {
			$data['messages']['error']['fail'] = 'Something went wrong while updating this product, please try again after sometime.';
		}

		$this->edit($data);
	}

	// Add a new task for this product
	public function addTask($id) {
		$_SESSION['taskInfo']['associate_to'] = 'product';
		$_SESSION['taskInfo']['associate_id'] = $id;
		$_SESSION['sourceUrl'] = base_url() . "products/details/$id";
		redirect(base_url(). 'tasks/add', 'location', 301);
	}

	// Get products
	public function getProducts($filters = '') {
		if (!$filters) $filters = $this->filters;

		//$query = "select SQL_CALC_FOUND_ROWS * from products";
		
		
		$query = "select SQL_CALC_FOUND_ROWS *, if(us.product_category,us.product_category,us.product_category) as category_name, if(us.no,us.no,us.no) as category
						  from products pr
						  left join user_fields us on pr.category = us.no";
						  
						  

		// Role checkup
		$whereCond = '';
		$params = array();
		if($user->demo==0)
		{
		if ($this->isManager) {
			$whereCond .= ' where (pr.org_id = ? or pr.org_id = ? ) ';
			$params[] = $this->user->org_id;
			$params[] = "0";
		} elseif ($this->isExecutive) {
			$whereCond .= ' where (pr.org_id = ? or pr.org_id = ? )';
			$params[] = $this->user->org_id;
			$params[] = "0";
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' where (pr.org_id = ? or pr.org_id = ? )';
			$params[] = $this->user->org_id;
			$params[] = "0";
		}
		}
		else
		{
			if ($this->isManager) {
			$whereCond .= ' where pr.org_id = ? ';
			$params[] = $this->user->org_id;
		} elseif ($this->isExecutive) {
			$whereCond .= ' where pr.org_id = ? ';
			$params[] = $this->user->org_id;
		} elseif ($this->isAdmin && $this->user->org_id) {
			$whereCond .= ' where pr.org_id = ? ';
			$params[] = $this->user->org_id;
		}
		}
		$countWhereCond = $whereCond;
		$countParams = $params;

		// Apply filters
		if ($filters) {
			$filtersCondition = '';
			
			$ik=0;
			$_SESSION['filters']="products";
			foreach ($filters as $fkey => $fvalue) {
				$ik++;
					$_SESSION['filters_key'][$ik]=$fkey ;
					$_SESSION['filters_fvalue'][$ik]=$fvalue ;
				if ($fvalue && ($fvalue != 'All')) {
					if (($fkey == 'create_date') || ($fkey == 'modify_date')) {
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
						$filtersCondition .= ($filtersCondition ? ' and ' : '') . "(pr.product_name LIKE '%".$fvalue."%' or pr.partno LIKE '%".$fvalue."%')" ;
						
					}  else {
						$filtersCondition .= ($filtersCondition ? ' and ' : '') . "$fkey = ?";
						$params[] = $fvalue;
					}
				}
			}
			if ($filtersCondition) $whereCond .= ($whereCond ? ' and ' : ' where ') . $filtersCondition;
		}

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

		$query .= " $whereCond $sOrder $sLimit";
		$resObj = $this->db->query($query, $params);
		$_SESSION['product_export']=$whereCond;
		$_SESSION['product_export_params']=$params;

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
		$countQuery = 'SELECT COUNT(`product_id`) as totalRows FROM products pr' . $countWhereCond;
		$countRes = $this->db->query($countQuery, $countParams);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $products;
	}

	// Get a product details
	public function getProductDetails($id) {
		$resObj = $this->db->query("select * from products where product_id = ?", array($id));
		return $resObj->row();
	}

	// Get next product
	public function getNextProduct($id) {
		$resObj = $this->db->query("select * from products where product_id > ? order by product_id limit 1", array($id));
		return $resObj->row();
	}

	// Get previous product
	public function getPrevProduct($id) {
		$resObj = $this->db->query("select * from products where product_id < ? order by product_id desc limit 1", array($id));
		return $resObj->row();
	}

	// Get product items
	public function getProductItems($type, $id, $colPrefix="") {
		$query = "select SQL_CALC_FOUND_ROWS *, if (u.name, u.name, u.username) as assignee
							from $type i
							inner join user u on u.user_id = i.assign_to
							where ".($colPrefix?$colPrefix.'_':'')."product_id = ?";

		// ======== Datatable adjustments ========
		$aColumns = array_keys($this->caseTableCols);
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
				$row->case_create_date = $this->convertDateTime($row->case_create_date);
				$items[] = $row;
			}
		}

		/* Data set length after filtering */
		$countRes = $this->db->query('SELECT FOUND_ROWS() as displayRows');
		$countResRow = $countRes->row();
		$this->dtDisplayCount = $countResRow->displayRows;
		
		/* Total data set length */
		$countRes = $this->db->query("SELECT COUNT(`{$colPrefix}_id`) as totalRows FROM $type WHERE ".($colPrefix?$colPrefix.'_':'')."product_id = ?", $id);
		$countResRow = $countRes->row();
		$this->dtTotalCount = $countResRow->totalRows;

		return $items;
	}
	
	
	// Get Organization Settings;
	
	function getOrganizationcurrency_base ()
	{
		
		$currency_freeze= $this -> db-> select('*')-> where('org_id',$this->user->org_id)-> limit(1)-> get('organization_settings');
		$cur=$currency_freeze->row_array();
		foreach($cur as $freeze)
		{
			 $currency=$freeze->currency_freeze;
		}
		return $currency_freeze->row_array(); 

	} 
	
		//currency
	function currency($from, $to, $amount)
	{
	   $content = file_get_contents('https://www.google.com/finance/converter?a='.$amount.'&from='.$from.'&to='.$to);
	
	   $doc = new DOMDocument;
	   @$doc->loadHTML($content);
	   $xpath = new DOMXpath($doc);
	
	   $result = $xpath->query('//*[@id="currency_converter_result"]/span')->item(0)->nodeValue;
	
	   return str_replace(' '.$to, '', $result);
	}
}
		function getcategoryDetails ($id)
	{
	
		 $resObj = $this->db->query("select * from user_fields where no = $id");
		return $resObj->row();
		

	}