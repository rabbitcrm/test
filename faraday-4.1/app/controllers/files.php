<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class files extends BCZ_Controller {
	private $folders = array();

	public function __construct()
	{
		parent::__construct();
	}

	public function index() {
	}

	// View a file
	public function view() {
		$fileName = $_GET['name'];
		$fileType = $_GET['type'];
		$filePath = $_GET['path'];

		switch ($fileType) {
			case 'doc':
				$file = $this->docsPath . $filePath . ($filePath ? '/' : '') . $fileName;
				break;
			case 'quote':
				$fileName= str_replace('-','&',$fileName);
		$fileName= str_replace('_','_',$fileName);
				$file = $this->quotesPath . $filePath . ($filePath ? '/' : '') . $fileName;
				break;
			case 'order':
		
			
				$file = $this->ordersPath . $filePath . ($filePath ? '/' : '') . $fileName;
				break;
			case 'image':
				$file = $this->imagesPath . $filePath . ($filePath ? '/' : '') . $fileName;
				break;
			case 'post_file':
				$file = $this->postFilesPath . $filePath . ($filePath ? '/' : '') . $fileName;
				break;
			
			default:
				$file = $this->uploadPath . $filePath . ($filePath ? '/' : '') . $fileName;
				break;
		}

		if (!file_exists($file)) show_404();	// 404 page if file not found

		// Log activity
		$info = array('name' => $fileName, 'path' => $filePath);
		$activity = array('action' => 'VIEW_FILE', 'type' => $fileType, 'id' => 0, 'info' => json_encode($info));
		$this->logUserActivity($activity);

		// Set content type header based on file extension
		$ext = end(explode('.', $fileName));
		if ($ext == 'pdf') header('Content-type: application/pdf');
		else header('Content-type: $ext');	

		// Read file to view
		header('Content-Disposition: inline; filename="' . $fileName . '"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($file));
		header('Accept-Ranges: bytes');

		@readfile($file);
	}

	// Download a file
	public function download() {
		$fileName = $_GET['name'];
		$fileType = $_GET['type'];
		$filePath = $_GET['path'];

		switch ($fileType) {
			case 'doc':
				$file = $this->docsPath . $filePath . ($filePath ? '/' : '') . $fileName;
				break;
			case 'quote':
				$fileName= str_replace('-','&',$fileName);
		$fileName= str_replace('_','_',$fileName);
		echo "sadasd";
				$file = $this->quotesPath . $filePath . ($filePath ? '/' : '') . $fileName;
				break;
			case 'order':
			
				$file = $this->ordersPath . $filePath . ($filePath ? '/' : '') . $fileName;
				break;
			case 'image':
				$file = $this->imagesPath . $filePath . ($filePath ? '/' : '') . $fileName;
				break;
			case 'post_file':
				$file = $this->postFilesPath . $filePath . ($filePath ? '/' : '') . $fileName;
				break;
			
			default:
				$file = $this->uploadPath . $filePath . ($filePath ? '/' : '') . $fileName;
				break;
		}

		if (!file_exists($file)) show_404();	// 404 page if file not found

		// Log activity
		$info = array('name' => $fileName, 'path' => $filePath);
		$activity = array('action' => 'DOWNLOAD_FILE', 'type' => $fileType, 'id' => 0, 'info' => json_encode($info));
		$this->logUserActivity($activity);

		// Set content type header based on file extension
		$ext = end(explode('.', $fileName));
		if ($ext == 'pdf') header('Content-type: application/pdf');
		else header('Content-type: $ext');

	   header('Content-Description: File Transfer');
	   // header('Content-Type: '.$result['filetype']);
	   header('Content-Disposition: attachment; filename='.basename($file));
	   header('Content-Transfer-Encoding: binary');
	   header('Expires: 0');
	   header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	   header('Pragma: public');
	   header('Content-Length: ' . filesize($file));
	   ob_clean();
	   flush();
	   readfile($file);
	   exit;
	}
	
	// Get all nodes under the specified path	
	public function getNodes($path) {
		$node = array();
	    $dirResource = @opendir($path) or die("Unable to open $path");

	    while (($file = readdir($dirResource)) !== false) {
	        if ($file != "." && $file != ".." && $file != 'Thumbs.db') {
	            if (is_dir($path."/".$file)) {
	            	$node[$file] = $this->getNodes($path."/".$file);

	            } else {
	            	$node[] = $file;
	            }
	        }
	    }

	    closedir($dirResource);

	    return $node;
	}

	// Get all folder paths
	public function getFolderPaths($path) {
		$path = trim($path, '/');
		$dirResource = @opendir($path) or die("Unable to open $path");
		while (($file = readdir($dirResource)) !== false) {
			if($file != "." && $file != "..") {
				if (is_dir($path."/".$file)) {
					$this->folders[] = $path."/".$file;
					$this->getFolderPaths($path."/".$file);
				}
			}
		}
		closedir($dirResource);
	}

	// Upload an entity doc
	public function uploadEntityDoc() {
		// Upload logo if selected
		$uploadSize = 2048;
		$config['upload_path'] = $this->imagesPath;
		$config['allowed_types'] = 'gif|jpg|jpeg|png|pdf|doc|docx|txt';
		$config['max_size']	= $uploadSize;
		$this->load->library('upload', $config);

		$uploadRes = $this->upload->do_upload('entity_doc');	// Upload file

		if (!$uploadRes) {
			$uploadMessage = $this->upload->display_errors();
			$uploadMessage = str_replace('permitted size.', 'permitted size(2MB).', $uploadMessage);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('message' => $uploadMessage ? $uploadMessage : 'Something went wrong while uploading your file, please try again after sometime.')));
		} else {
			$uploadedFile = $this->upload->data();

			// Insert into our DB
			$fileId = $this->getTableAutoID('fileupload');
			$query = "insert into fileupload 
					  (filename, filetype, size, owner_id, report_to_id, associate_to, associate_id, file_create_date) 
					  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
			$dbRes = $this->db->query($query, array($uploadedFile['file_name'], $uploadedFile['file_type'], $_FILES['entity_doc']['size'], $this->user->user_id, ($this->user->report_to_id ? $this->user->report_to_id : $this->user->user_id), $_POST['associate_to'], $_POST['associate_id'], $this->getCurrTime()));

			if ($dbRes) {
				// Log activity
				$info = array('type' => 'file', 'id' => $fileId);
				$activity = array('action' => 'UPLOAD_FILE', 'type' => $_POST['associate_to'], 'id' => $_POST['associate_id'], 'info' => json_encode($info));
				$this->logUserActivity($activity);

				$file = $this->getFileDetails($fileId);
				$file->name = $this->user->name;
				$this->load->view('SingleDocView', array('doc' => $file));
			}
		}
	}

	// Delete an entity doc
	public function deleteEntityDoc() {
		$fileId = $_GET['id'];
		$fileName = $_GET['name'];

		// Get file details
		$getFileRes = $this->db->query('select * from fileupload where file_id = ?', $fileId);
		$fileData = $getFileRes->row();

		// Delete file details from DB	
		$dbRes = $this->db->query("delete from fileupload where file_id = ?", $fileId);
	
		// Delete file from storage
		$fileRes = unlink($this->imagesPath . $fileName);

		$res = array();
		if ($dbRes && $fileRes) {
			$res['success'] = true;
			$res['message'] = 'Successfully deleted this file.';

			// Log activity
			$info = array('name' => $fileName, 'type' => 'file', 'id' => $fileId);
			$activity = array('action' => 'DELETE_FILE', 'type' => $fileData->associate_to, 'id' => $fileData->associate_id, 'info' => json_encode($info));
			$this->logUserActivity($activity);
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while deleting this file, please try again after sometime.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Get a file details
	private function getFileDetails($id) {
		$fileRes = $this->db->query('select * from fileupload where file_id = ?', $id);
		return $fileRes->row();
	}

}