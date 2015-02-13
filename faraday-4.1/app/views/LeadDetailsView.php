<div class="clearfix">
  <?php $this->load->view('PageHeaderContentView', array('title' => $lead->name, 'owner' => $lead->owner, 'date' => $lead->lead_create_date)); ?>

  <?php
  if($lead->org_id=="0")
  {
    $pageActions = array(
      array('label' => 'Edit', 'icon' => 'icon-edit m-r-mini', 'redirectPath' => "leads/edit/$lead->lead_id"),
      array('label' => 'Delete', 'icon' => 'icon-remove-circle', 'class' => 'bcz-confirm-operation'),
      array('label' => 'Reassign Lead', 'icon' => 'icon-reply', 'modalId' => 'reassign_lead_modal'),
      array('label' => 'Convert To Opportunity', 'icon' => 'icon-thumbs-up-alt', 'modalId' => 'convert_lead_modal')
    );
  }
  else
  {
	  $pageActions = array(
      array('label' => 'Edit', 'icon' => 'icon-edit m-r-mini', 'redirectPath' => "leads/edit/$lead->lead_id"),
      array('label' => 'Delete', 'icon' => 'icon-remove-circle', 'class' => 'bcz-confirm-operation'),
      array('label' => 'Reassign Lead', 'icon' => 'icon-reply', 'modalId' => 'reassign_lead_modal'),
      array('label' => 'Convert To Opportunity', 'icon' => 'icon-thumbs-up-alt', 'modalId' => 'convert_lead_modal')
    );
  }

    $this->load->view('PageHeaderActionsView', array('controller' => 'leads', 'pageType' => 'lead', 'pageId' => $lead->lead_id, 'prevId' => $PreviousId, 'nextId' => $NextId, 'pageActions' => $pageActions)); 
  ?>
</div>






<?php 
 if ($lead1->statusList) {?>
  <?php $this->load->view('PageStagesBarView', array('stages' => $lead1->statusList, 'pageStage' => $status, 'pageId' =>$lead->lead_id)); ?>
<?php } ?>













<?php
$colorBlocks = array(
  array('head' => 'Account', 'body' => $lead->company_name, 'color' => 'primary', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Email', 'body' => $lead->email, 'color' => 'inverse', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Phone', 'body' => $lead->mobile, 'color' => 'success', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Modified', 'body' => $lead->lead_modify_date, 'color' => 'info', 'type' => 'date', 'bodyCls' => 'bcz-text-ellipsis')
);
$this->load->view('PageColorBlocksView', array('colorBlocks' => $colorBlocks));
?>

<div class="row m-t-large padder text-center">
  <div class="col-sm-6 text-center no-padder">
    <?php
	
	if($lead->created_before=="0 Days")
	{
		$lead_created='Today';
		$created='Created';
	}
	else if($lead->created_before=="1 Day")
	{
		$lead_created='Yesterday';
		$created='Created';
	}
	else
	{
		$lead_created=$lead->created_before;
		$created='Created Before';
	}
    $borderBlocks = array(
      array('head' => 'Campaign', 'body' => $lead->lead_application),
      array('head' => $created, 'body' => $lead_created),
      array('head' => 'Source', 'body' => $lead->lead_source),
      array('head' => 'Status', 'body' => $status),
      array('head' => 'Customer Type', 'body' => $customer_type),
      array('head' => 'Alternate Email', 'body' => $lead->alt_email)
    );
    $this->load->view('PageBorderBlocksView', array('borderBlocks' => $borderBlocks));
    ?>
  </div>

  <div class="col-sm-6 text-center no-padder m-t-mini">
    <?php $this->load->view('PageAddressInfoView', array('address' => $lead->bill_addr, 'city' => $lead->bill_city, 'state' => $lead->bill_state, 'zip' => $lead->bill_postal_code, 'country' => $lead->bill_country)); ?>
  </div>
</div>

<div class="row m-t-mini padder">
  <div class="col-sm-12 no-padder">
    <div class="panel m-b-none">
      <div class="panel-body">
        <div class="col-sm-2 h4 m-t-mini m-b-mini">Summary:</div>
        <div class="col-sm-10 h5" style="margin: 7px 0;"><?=($lead->lead_description?$lead->lead_description:$this->noDataChar)?></div>
      </div>
    </div>
  </div>
</div>

<!-- .accordion -->
<div class="panel-group m-b m-t-large" id="accordion2">
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseOne', 'headingText' => 'Notes', 'actionButtons' => false)); ?>

    <div id="collapseOne" class="panel-collapse in">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityNotesView', array('entityType' => 'lead', 'entityId' => $lead->lead_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseTwo', 'headingText' => 'Tasks', 'actionButtons' => true, 'buttons' => array(array('text' => 'add', 'modalId' => '#create_task_modal', 'associateTo' => 'lead', 'associateId' => $lead->lead_id)))); ?>

    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityTasksView', array('entityType' => 'lead', 'entityId' => $lead->lead_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseFive', 'headingText' => 'Documents', 'actionButtons' => false, 'buttons' => array(array('text' => 'add', 'actionPath' => 'files/uploadentitydoc', 'associateTo' => 'lead', 'associateId' => $lead->lead_id)))); ?>

    <div id="collapseFive" class="panel-collapse collapse">
      <div class="panel-body text-small bcz-docs-container">
        <?php $this->load->view('EntityDocsView', array('btnLabel' => 'Upload', 'actionPath' => 'files/uploadentitydoc', 'associateTo' => 'lead', 'associateId' => $lead->lead_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseTen', 'headingText' => 'History', 'actionButtons' => false)); ?>

    <div id="collapseTen" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityHistoryView', array('entityType' => 'lead', 'entityId' => $lead->lead_id)); ?>
      </div>
    </div>
  </div>
</div>
<!-- / .accordion -->