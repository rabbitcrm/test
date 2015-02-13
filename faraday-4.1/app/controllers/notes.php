<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class notes extends BCZ_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index() {
		show_404();
	}

	public function create() {
		// Create a note with given details
		$noteId = $this->getTableAutoID('note');
		$currDateTime = $this->getCurrTime();
		$query = 'insert into note (note, associate_to, associate_id, owner_id, report_to_id, note_create_date, note_modify_date) values (?, ?, ?, ?, ?, ?, ?)';
		$noteRes = $this->db->query($query, array(trim($_POST['note']), $_POST['type'], $_POST['id'], $this->user->user_id, ($this->user->report_to_id ? $this->user->report_to_id : $this->user->user_id), $currDateTime, $currDateTime));

		if ($noteRes) {
			$note = $this->getNoteDetails($noteId);
			$note->name = $this->user->name ? $this->user->name : $this->user->username;
			$note->profile_pic = $this->user->profile_pic;
			$note->fullPicPath = true;

			// Log activity
			$activity = array('action' => 'ADD_NOTE', 'type' => $_POST['type'], 'id' => $_POST['id'], 'info' => json_encode(array('note_id' => $noteId)));
			$this->logUserActivity($activity);

			$this->load->view('SingleNoteView', array('note' => $note));
		}
	}
}