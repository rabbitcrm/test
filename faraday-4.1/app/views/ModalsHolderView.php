<div class="modals-holder">
	<?php 
		if ($this->bodyClass == 'lead-details') {
			$this->load->view("ReassignLeadModal");
			$this->load->view("ConvertLeadModal");
		}
	 if ($this->bodyClass == 'deal-details') $this->load->view("ReassignDealModal");
	 
	  if ($this->bodyClass == 'task-details') $this->load->view("ReassignTaskModal"); 
	  
	  if ($this->bodyClass == 'case-details') $this->load->view("ReassignCaseModal");
	  
		if ($this->bodyClass == 'settings') {
			$this->load->view("AddUserModal");
			echo '<div id="edit_user_modal" class="modal fade"></div>';
	//		$this->load->view("DeEditUserModal");
			$this->load->view("DeleteUserConfirmationModal");
			$this->load->view("DeleteUserConfirmationModal1");
			$this->load->view("DeactivateUserConfirmationModal");
			$this->load->view("ActivateUserConfirmationModal");
			$this->load->view("ResetUserConfirmationModal");
			$this->load->view("DeleteModal");
			
		}
		if($this->bodyClass=='advancedsettings')
		{
			$this->load->view("AddSettingModal");
			$this->load->view("EditSettingModal");
			$this->load->view("DeleteSettingConfirmationModal");
			$this->load->view("NotDeleteSettingConfirmationModal");
			
		}
	
	 if ($this->bodyClass == 'quote-details') $this->load->view("GenerateSoModal");
	 
		if ($this->bodyClass == 'docs') {
			$this->load->view("CreateFolderModal");
			$this->load->view("UploadDocModal");
		}
		
		if ($this->bodyClass == 'contact-details') {
			$this->load->view("CreateTicketsModal");
		}
		if ($this->bodyClass == 'create-deal') {
			$this->load->view("CreateContactdealModal");
			$this->load->view("CreateAccountdealModal");
			
		}
	
	 $this->load->view("AddFileModal"); 
	 $this->load->view("CreateTaskModal"); 
	 $this->load->view("CreateDealModal");
	 $this->load->view("CreateContactModal");
	 $this->load->view("ConfirmationModal");
	 $this->load->view("UpgradeMessageModal");
	 $this->load->view("ComposeEmailModal"); 
	 $this->load->view("CreateProductModal"); ?>
</div>