<div class="clearfix">
  <?php $this->load->view('PageHeaderContentView', array('title' => $task->task_name, 'owner' => $task->name, 'date' => $task->task_create_date)); ?>

  <?php
    $pageActions = array(
      array('label' => 'Edit', 'icon' => 'icon-edit m-r-mini', 'redirectPath' => "tasks/edit/$task->task_id"),
      array('label' => 'Delete', 'icon' => 'icon-remove-circle', 'class' => 'bcz-confirm-operation'),
      array('label' => 'Reassign Task', 'icon' => 'icon-reply', 'modalId' => 'reassign_task_modal')
    );

    $this->load->view('PageHeaderActionsView', array('controller' => 'tasks', 'pageType' => 'task', 'pageId' => $task->task_id, 'prevId' => $PreviousId, 'nextId' => $NextId, 'pageActions' => $pageActions)); 
  ?>
</div>

<?php if ($stage) { ?>
  <?php $this->load->view('PageStagesBarView', array('stages' => $task->statusList, 'pageStage' => $stage, 'pageId' => $task->task_id)); ?>
<?php } ?>

<?php
$colorBlocks = array(
  array('head' => 'Type', 'body' => $type, 'color' => 'primary', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Status', 'body' => $stage, 'color' => 'inverse', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Due Date', 'body' => $task->due_date, 'color' => 'success', 'type' => 'date', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Priority', 'body' => $task->priority, 'color' => 'info', 'bodyCls' => 'bcz-text-ellipsis')
);
$this->load->view('PageColorBlocksView', array('colorBlocks' => $colorBlocks));
?>

<div class="row m-t-large padder">
  <?php
  if($task->created_before=="0 Days")
	{
		$lead_created='Today';
		$created='Created';
	}
	else if($task->created_before=="1 Day")
	{
		$lead_created='Yesterday';
		$created='Created';
	}
	else
	{
		$lead_created= $task->created_before;
		$created='Created Before';
	}
	if($task->associate_to=="deal")
	{
		$task->associate_to='opportunity';
	}
	


  $borderBlocks = array(
    array('head' => 'Associated To', 'cols' => 3, 'body' => ($associated->aname?"<a title='".strtoupper($task->associate_to)." - $associated->aname' href='".$associated->alink." ' class='text-danger'>".strtoupper($task->associate_to)." - $associated->aname</a>":($task->associate_to ? ucfirst($task->associate_to) : $this->noDataChar)), 'bodyCls' => 'bcz-text-ellipsis'),
    array('head' => 'Last Modified', 'cols' => 3, 'body' => $task->task_modify_date, 'type' => 'date'),
    array('head' => 'Assigned To', 'cols' => 3, 'body' => $task->assignee->name),
    array('head' => $created, 'cols' => 3, 'body' =>$lead_created)
  );
  $this->load->view('PageBorderBlocksView', array('borderBlocks' => $borderBlocks));
  ?>
</div>

<div class="row m-t-mini padder">
  <div class="col-sm-12 no-padder">
    <div class="panel m-b-none">
      <div class="panel-body">
        <div class="col-sm-2 h4 m-t-mini m-b-mini">Summary:</div>
        <div class="col-sm-10 h5" style="margin: 7px 0;"><?=($task->description?$task->description:$this->noDataChar)?></div>
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
        <?php $this->load->view('EntityNotesView', array('entityType' => 'task', 'entityId' => $task->task_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseThree', 'headingText' => 'Associated To', 'actionButtons' => false)); ?>

    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityAssociatedView', array('associatedType' => $task->associate_to)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseFive', 'headingText' => 'Documents', 'actionButtons' => false)); ?>

    <div id="collapseFive" class="panel-collapse collapse">
      <div class="panel-body text-small bcz-docs-container">
        <?php $this->load->view('EntityDocsView', array('btnLabel' => 'Upload', 'actionPath' => 'files/uploadentitydoc', 'associateTo' => 'task', 'associateId' => $task->task_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseTen', 'headingText' => 'History', 'actionButtons' => false)); ?>

    <div id="collapseTen" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityHistoryView', array('entityType' => 'task', 'entityId' => $task->task_id)); ?>
      </div>
    </div>
  </div>
</div>
<!-- / .accordion -->