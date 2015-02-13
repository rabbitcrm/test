

<div class="clearfix">


  <?php $this->load->view('PageHeaderContentView', array('title' => $campaign->name, 'owner' => $campaign->owner, 'date' => $campaign->create_date)); ?>

  <?php
    $pageActions = array(
      array('label' => 'Edit', 'icon' => 'icon-edit m-r-mini', 'redirectPath' => "campaign/edit/$campaign->campaign_id"));
    $this->load->view('PageHeaderActionsView', array('controller' => 'Campaign', 'pageType' => 'Campaign', 'pageId' => $campaign->campaign_id, 'prevId' => $prev_campaign->campaign_id, 'nextId' => $campaignt_lead->campaign_id, 'pageActions' => $pageActions)); 
	
	
  ?>
  
  
</div>








<?php if ($status) {?>
  <?php $this->load->view('PageStagesBarView', array('stages' => $campaign->stages, 'pageStage' => $status, 'pageId' =>$campaign->campaign_id)); ?>
<?php } ?>












<?php
$colorBlocks = array(
  array('head' => 'Name', 'body' => $campaign->campaign_name, 'color' => 'primary', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Type', 'body' => $campaign->campaign_type, 'color' => 'inverse', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Audience', 'body' => $campaign->target_audience, 'color' => 'success', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Modified', 'body' => $campaign->modify_date, 'color' => 'info', 'type' => 'date', 'bodyCls' => 'bcz-text-ellipsis')
);
$this->load->view('PageColorBlocksView', array('colorBlocks' => $colorBlocks));
?>

<div class="row m-t-large padder text-center">
  <div class="col-sm-6 text-center no-padder">

    <?php
	
	if($campaign->created_before=="0 Days")
	{
		$lead_created='Today';
		$created='Created';
	}
	else if($campaign->created_before=="1 Day")
	{
		$lead_created='Yesterday';
		$created='Created';
	}
	else
	{
		$lead_created=$campaign->created_before;
		$created='Created Before';
	}
    $borderBlocks = array(
      array('head' => 'Sponsor', 'body' => $campaign->sponsor),
      array('head' => $created, 'body' => $lead_created),
      array('head' => 'Product','body' => $campaign->product),
      array('head' => ' Close Date', 'body' => $campaign->close_date),
      array('head' => 'Target Size', 'body' => $campaign->target_size),
      array('head' => 'Budget Cost', 'body' => $campaign->cost) ,
      array('head' => 'Num Sent', 'body' => $campaign->num_sent) ,
    array('head' => 'Assigned To', 'body' => $campaign->owner),
    );
    $this->load->view('PageBorderBlocksView', array('borderBlocks' => $borderBlocks));
    ?>
  
  <?php   $Current=$campaign->lead_count+$campaign->opp_count; ?>
</div>

<div class="col-sm-6 text-center no-padder m-t-mini">
    <?php $this->load->view('PageExpectedInfoView', array('ExpectedResponse' => $campaign->response, 'ExpectedResponseCount' => $campaign->response_count, 'CurrentResponse' => $Current, 'ExpectedSalesCount' => $campaign->sales_count, 'ExpectedROI' => $campaign->roi)); ?>
  </div>
</div>
<div class="row m-t-mini padder">
  <div class="col-sm-12 no-padder">
    <div class="panel m-b-none">
      <div class="panel-body">
        <div class="col-sm-2 h4 m-t-mini m-b-mini">Summary:</div>
        <div class="col-sm-10 h5" style="margin: 7px 0;"><?=($campaign->description?$campaign->description:$this->noDataChar)?></div>
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
        <?php $this->load->view('EntityNotesView', array('entityType' => 'campaign', 'entityId' => $campaign->campaign_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseTwo', 'headingText' => 'Tasks', 'actionButtons' => true, 'buttons' => array(array('text' => 'add', 'modalId' => '#create_task_modal', 'associateTo' => 'campaign', 'associateId' => $campaign->campaign_id)))); ?>

    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityTasksView', array('entityType' => 'campaign', 'entityId' => $campaign->campaign_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseFive', 'headingText' => 'Documents', 'actionButtons' => false, 'buttons' => array(array('text' => 'add', 'actionPath' => 'files/uploadentitydoc', 'associateTo' => 'campaign', 'associateId' => $campaign->campaign_id)))); ?>

    <div id="collapseFive" class="panel-collapse collapse">
      <div class="panel-body text-small bcz-docs-container">
        <?php $this->load->view('EntityDocsView', array('btnLabel' => 'Upload', 'actionPath' => 'files/uploadentitydoc', 'associateTo' => 'campaign', 'associateId' => $campaign->campaign_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseTen', 'headingText' => 'History', 'actionButtons' => false)); ?>

    <div id="collapseTen" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityHistoryView', array('entityType' => 'campaign', 'entityId' => $campaign->campaign_id)); ?>
      </div>
    </div>
  </div>
</div>
<!-- / .accordion -->