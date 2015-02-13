<div class="clearfix">
  <?php $this->load->view('PageHeaderContentView', array('title' => $case->case_title, 'owner' => $case->name, 'date' => $case->case_create_date)); ?>

  <?php
    $pageActions = array(
      array('label' => 'Edit', 'icon' => 'icon-edit m-r-mini', 'redirectPath' => "cases/edit/$case->case_id"),
      array('label' => 'Delete', 'icon' => 'icon-remove-circle', 'class' => 'bcz-confirm-operation'),
      array('label' => 'Reassign Ticket', 'icon' => 'icon-reply', 'modalId' => 'reassign_case_modal')
    );
    $this->load->view('PageHeaderActionsView', array('controller' => 'cases', 'pageType' => 'case', 'pageId' => $case->case_id, 'prevId' =>$PreviousId, 'nextId' => $NextId, 'pageActions' => $pageActions)); 
  ?>
</div>

<?php if ($stage) { ?>

  <?php $this->load->view('PageStagesBarView', array('stages' => $case->stages, 'pageStage' => $stage, 'pageId' => $case->case_id)); ?>
<?php } ?>

<?php
$colorBlocks = array(
  array('head' => 'Account', 'body' => $case->company_name, 'color' => 'primary', 'bodyCls' => 'bcz-text-ellipsis', 'link' => 'companies/details/'.$case->company_id),
  array('head' => 'Contact', 'body' => $case->contact_name, 'color' => 'inverse', 'bodyCls' => 'bcz-text-ellipsis', 'link' => 'contacts/details/'.$case->contact_id),
  array('head' => 'Ticket No', 'body' => $case->case_no, 'color' => 'success', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Priority', 'body' => $case->priority, 'color' => 'info', 'bodyCls' => 'bcz-text-ellipsis')
);
$this->load->view('PageColorBlocksView', array('colorBlocks' => $colorBlocks));
?>

<div class="row m-t-large padder">
  <?php
  $borderBlocks1 = array(
    array('head' => 'Created On', 'body' => $case->case_create_date, 'cols' => 3, 'type' => 'date'),
    array('head' => 'Status', 'body' => $case->status_name, 'cols' => 3),
    array('head' => 'Modified', 'body' => $case->case_modify_date, 'cols' => 3, 'type' => 'date'),
    array('head' => 'Created Before', 'body' => $case->created_before, 'cols' => 3)
  );
  $this->load->view('PageBorderBlocksView', array('borderBlocks' => $borderBlocks1));
  ?>
</div>

<div class="row m-t-mini padder">
  <div class="col-sm-6 text-center padder-r-mini no-padder-l m-t-mini">
    <div class="panel m-b-none">
      <div class="panel-body" style="min-height: 75px;">
        <div class="col-sm-2 h4 m-t-mini m-b-mini">Product:</div>
        <div class="col-sm-10 h5" style="margin: 7px 0;"><a href="<?=base_url()?>products/details/<?=$case->case_product_id;?>"><?=$case->product_name?></a></div>
      </div>
    </div>
  </div>

  <?php
  $borderBlocks2 = array(
    array('head' => 'Severity', 'body' => $case->severity, 'cols' => 3),
    array('head' => 'Assigned To', 'body' => $case->assignee, 'cols' => 3)
  );
  $this->load->view('PageBorderBlocksView', array('borderBlocks' => $borderBlocks2));
  ?>
</div>


<div class="row m-t-mini padder">
 <div class="col-sm-12 no-padder">
    <div class="panel m-b-none">
      <div class="panel-body" style="min-height: 75px;">
        <div class="col-sm-2 h4 m-t-mini m-b-mini">Summary:</div>
        <div class="col-sm-10 h5" style="margin: 7px 0;"><?=($case->case_description?$case->case_description:$this->noDataChar)?></div>
      </div>
    </div>
  </div>

<?php if ($case->case_solution) { ?>
<div class="row m-t-mini padder">
  <div class="col-sm-12 no-padder">
    <div class="panel m-b-none">
      <div class="panel-body">
        <div class="col-sm-2 h4 m-t-mini m-b-mini">Solution:</div>
        <div class="col-sm-10 h5" style="margin: 7px 0;"><?=$case->case_solution?></div>
      </div>
    </div>
  </div>
</div>
<?php } ?>



 
</div>
<!-- .accordion -->
<div class="panel-group m-b m-t-large" id="accordion2">
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseOne', 'headingText' => 'Notes', 'actionButtons' => false)); ?>

    <div id="collapseOne" class="panel-collapse in">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityNotesView', array('entityType' => 'case', 'entityId' => $case->case_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseTwo', 'headingText' => 'Tasks', 'actionButtons' => true, 'buttons' => array(array('text' => 'add', 'modalId' => '#create_task_modal', 'associateTo' => 'case', 'associateId' => $case->case_id)))); ?>

    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityTasksView', array('entityType' => 'case', 'entityId' => $case->case_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseSeven', 'headingText' => 'Contact', 'actionButtons' => false, 'buttons' => array(array('text' => 'add', 'modalId' => '#create_contact_modal', 'associateTo' => 'case', 'associateId' => $case->case_id)))); ?>

    <div id="collapseSeven" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <div class="col-sm-8 text-center no-padder-v m-t-mini">
          <div class="h4 col-sm-6 text-left m-t-small m-b-small">Name</div><div class="h4 col-sm-6 text-left m-t-small m-b-small"><?=($case->contact_name?"<a class='text-primary' href='".base_url()."contacts/details/$case->contact_id'>".$case->contact_name."</a>":$this->noDataChar)?></div>
          <div class="h4 col-sm-6 text-left m-b-small">Created On</div><div class="h4 col-sm-6 text-left m-b-small"><?=($case->contact_create_date?convertDateTime($case->contact_create_date):$this->noDataChar)?></div>
          <div class="h4 col-sm-6 text-left m-b-small">Phone</div><div class="h4 col-sm-6 text-left m-b-small"><?=($case->mobile?$case->mobile:$this->noDataChar)?></div>
          <div class="h4 col-sm-6 text-left m-b-small">Email</div><div class="h4 col-sm-6 text-left m-b-small"><?=($case->email?$case->email:$this->noDataChar)?></div>
        </div>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseThree', 'headingText' => 'Account', 'actionButtons' => false)); ?>

    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <div class="col-sm-8 text-center no-padder-v m-t-mini">
          <div class="h4 col-sm-6 text-left m-t-small m-b-small">Name</div><div class="h4 col-sm-6 text-left m-t-small m-b-small"><?=($case->company_name?"<a class='text-primary' href='".base_url()."companies/details/$case->company_id'>".$case->company_name."</a>":$this->noDataChar)?></div>
          <div class="h4 col-sm-6 text-left m-b-small">Created On</div><div class="h4 col-sm-6 text-left m-b-small"><?=($case->company_create_date?convertDateTime($case->company_create_date):$this->noDataChar)?></div>
          <div class="h4 col-sm-6 text-left m-b-small">Customer Type</div><div class="h4 col-sm-6 text-left m-b-small"><?=($case->com_cust_type?$case->com_cust_type:$this->noDataChar)?></div>
          <div class="h4 col-sm-6 text-left m-b-small">Employees</div><div class="h4 col-sm-6 text-left m-b-small"><?=($case->com_employees?$case->com_employees:$this->noDataChar)?></div>
          <div class="h4 col-sm-6 text-left m-b-small">Revenue</div><div class="h4 col-sm-6 text-left m-b-small"><?=($case->com_revenue?$case->com_revenue:$this->noDataChar)?></div>
        </div>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseFour', 'headingText' => 'Product', 'actionButtons' => false)); ?>

    <div id="collapseFour" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <div class="col-sm-8 text-center no-padder-v m-t-mini">
          <div class="h4 col-sm-6 text-left m-t-small m-b-small">Name</div><div class="h4 col-sm-6 text-left m-t-small m-b-small"><?=($case->product_name?"<a class='text-primary' href='".base_url()."products/details/$case->product_id'>".$case->product_name."</a>":$this->noDataChar)?></div>
          <div class="h4 col-sm-6 text-left m-b-small">Part No</div><div class="h4 col-sm-6 text-left m-b-small"><?=($case->partno?$case->partno:$this->noDataChar)?></div>
          <div class="h4 col-sm-6 text-left m-b-small">Category</div><div class="h4 col-sm-6 text-left m-b-small"><?=($case->category?$case->category:$this->noDataChar)?></div>
          <div class="h4 col-sm-6 text-left m-b-small">Price</div><div class="h4 col-sm-6 text-left m-b-small"><?=($case->price?$case->price:$this->noDataChar)?></div>
        </div>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseFive', 'headingText' => 'Documents', 'actionButtons' => false)); ?>

    <div id="collapseFive" class="panel-collapse collapse">
      <div class="panel-body text-small bcz-docs-container">
        <?php $this->load->view('EntityDocsView', array('btnLabel' => 'Upload', 'actionPath' => 'files/uploadentitydoc', 'associateTo' => 'case', 'associateId' => $case->case_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseTen', 'headingText' => 'History', 'actionButtons' => false)); ?>

    <div id="collapseTen" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityHistoryView', array('entityType' => 'case', 'entityId' => $case->case_id)); ?>
      </div>
    </div>
  </div>
</div>
<!-- / .accordion -->