<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class emails extends BCZ_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index() {
	}

	public function getentityemailsjson() {
		// Get entity emails and arrange data for datatable
		$emails = $this->getItemEmails($_GET['type'], $_GET['id']);
		$output = $this->constructDTOutput($emails, array_keys($this->emailTableCols));
		
		echo json_encode($output);
	}

	// Send email
	public function send() {
		// Get form data
		$from = trim($_POST['from']);
		$to = trim($_POST['to']);
		$cc = trim($_POST['cc']);
		$bcc = trim($_POST['bcc']);
		$subject = trim($_POST['subject']);
		$message = trim($_POST['message']);

		// Upload attachments
		$attachments = array();
		if ($_FILES['attachment']['name']) {
			// Temporary upload
			$config['upload_path'] = $this->uploadPath;
			$config['allowed_types'] = 'gif|jpg|jpeg|png|pdf|doc|txt';
			$config['max_size']	= '10240';
			$this->load->library('upload', $config);

			$uploadRes = $this->upload->do_upload('attachment');
			$fileName = $_FILES['attachment']['name'];
			$attachments[] = $this->uploadPath . $fileName;
		}

		// Default attachment
		if (isset($_POST['defaultAttachment'])) $attachments[] = $_POST['defaultAttachment'];

		$this->from = $from;
		$this->to = $to;
		if ($cc) $this->cc = $cc;
		if ($bcc) $this->bcc = $bcc;
		$this->subject = $subject;
		$this->message = nl2br($message);
		if ($attachments[0]) $this->attachments = $attachments;

		$emailRes = $this->sendBCZEmail();

		if ($emailRes) {
			// Delete attachment uploads
			if ($fileName && file_exists($this->uploadPath . $fileName)) unlink($this->uploadPath . $fileName);

			// Store email info in our DB
				$emailId = $this->getTableAutoID('email');
		    $query  = "insert into email 
		    			(sender, reciever, cc, bcc, subject, message, file_name, file_type, contact_id, company_id, deal_id, quote_id, sales_order_id, send_date) 
		    			values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		   	$dbRes = $this->db->query($query, array($from, $to, $cc, $bcc, $subject, $message, $fileName, ($_FILES['attachment']['type'] ? $_FILES['attachment']['type'] : ''), $_POST['contact_id'], $_POST['company_id'], $_POST['deal_id'], $_POST['quote_id'], $_POST['sales_order_id'], $this->getCurrTime()));

			if ($dbRes) {
					// Log activity
					$info = array('associate_to' => $_POST['type'], 'associate_id' => $_POST['id']);
					$activity = array('action' => 'SEND', 'type' => 'email', 'id' => $emailId, 'info' => json_encode($info));
					$this->logUserActivity($activity);

			   	if (($_POST['type'] == 'quote') || ($_POST['type'] == 'order')) {
			   		$updateQuery = ($_POST['type'] == 'quote') ? "update quote set quote_stage = ? where quote_id = ?" : "update sales_order set so_stage = ? where so_id = ?";
			   		$updateRes = $this->db->query($updateQuery, array('Sent', $_POST['id']));

					$res = array();
					if ($updateRes) {
						$res['success'] = true;
						$res['message'] = 'Successfully sent this email.';
					} else {
						$res['success'] = false;
						$res['message'] = 'Something went wrong while sending this email, please try again after sometime.';			
					}

					$this->output
			    		->set_content_type('application/json')
			    		->set_output(json_encode($res));

			   	} else {
					$data['emails'][0] = true;
					$data['entityType'] = $_POST['type'];
					$data['entityId'] = $_POST['id'];
					$this->load->view('EntityEmailsView', $data);
			   	}
			}
		}
	}
}