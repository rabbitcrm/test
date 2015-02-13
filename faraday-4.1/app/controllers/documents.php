<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class documents extends BCZ_Controller {
	private $folders = array();

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if($_SESSION['filters']!="documents")
		{
			unset($_SESSION['filters_key']);
			unset($_SESSION['filters_fvalue']);
		}
		
		$this->bodyClass = $this->pageTitle = $this->pageDesc = 'docs';
		$data['content'] = 'DocumentsView';

		// Get all directories and files
		$path=$this->docsPath.$this->user->org_id.'/';
		$nodes = $this->getNodes($path);
		// Get all directory paths
		$this->folders[] = trim($path);
		$this->getFolderPaths($path);
		$data['folders'] = $this->folders;

		$data['root'] = end(explode("/", trim($path, '/')));
		
		$data['nodes'] = $nodes;
// echo "<pre>"; print_r($data); exit;		
		$this->load->view('FirstLayoutView', $data);
	}

	// Create a folder
	public function createFolder() {
		$folderPath = $_POST['folder_path'];
		$folderName = trim($_POST['folder_name'], ' ');
		
		$res = array();
		if (is_dir($folderPath.'/'.$folderName)) {
			$res['success'] = false;
			$res['message'] = 'Folder already exist.';			

		} else {
			$dirRes = mkdir($folderPath.'/'.$folderName);

			if ($dirRes) {
				$res['success'] = true;
				$res['message'] = 'Successfully created this folder.';
			} else {
				$res['success'] = false;
				$res['message'] = 'Something went wrong while creating this folder, please try again after sometime.';			
			}
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Upload a document
	public function uploadDoc() {
		// Upload logo if selected
		$folderPath = $_POST['folder_path'];
		$config['upload_path'] = $folderPath;
		$config['allowed_types'] = 'gif|jpg|jpeg|png|bmp|pdf|doc|docx|xls|xlsx|txt';
		$config['max_size']	= '10240';
		$this->load->library('upload', $config);

			$uploadRes = $this->upload->do_upload('new_doc', 0755);
		$res = array();
		if ($uploadRes) {
			$res['success'] = true;
			$res['message'] = 'Successfully uploaded your document.';
		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while uploading your doc, please try again after sometime.';	
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}

	// Delete a doc
	public function deleteDoc() {
		$fileName = $_GET['name'];
		$filePath = $_GET['path'];
		// Delete file from storage
		$fileRes = unlink($this->docsPath.($filePath?$filePath.'/':'').$fileName);

		$res = array();
		if ($fileRes) {
			$res['success'] = true;
			$res['message'] = 'Successfully deleted this file.';

		} else {
			$res['success'] = false;
			$res['message'] = 'Something went wrong while deleting this file, please try again after sometime.';			
		}

		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($res));
	}
	
	// Get all nodes under the specified path	
	public function getNodes($path) {
		$node = array();
	   $dirResource = @opendir($path) or die("Unable to open $path");

	    $index = 0;
	    while (($file = readdir($dirResource)) !== false) {
	        if ($file != "." && $file != ".." && $file != 'Thumbs.db') {
				
	            if (is_dir($path.$file)) {
	            	$node[$file] = $this->getNodes($path.$file.'/');
					

	            } else {
					
	            	$node[$index]['name'] = $file;
	            	$fileSize = filesize($path . $file);
	            	$fileSizeUnits = ' Bytes';
	            	if ($fileSize && $fileSize > 1024) {
	            		$fileSize /= 1024;
	            		$fileSizeUnits = 'KB';
	            	}
	            	if ($fileSize && $fileSize > 1024) {
	            		$fileSize /= 1024;
	            		$fileSizeUnits = 'MB';
	            	}
	            	if ($fileSize && $fileSize > 1024) {
	            		$fileSize /= 1024;
	            		$fileSizeUnits = 'GB';
	            	}
	            	$node[$index]['size'] = round($fileSize, 2) . $fileSizeUnits;
	            }

	            $index++;
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

}