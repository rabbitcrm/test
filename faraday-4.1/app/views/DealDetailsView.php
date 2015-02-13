<div class="clearfix">
  <?php $this->load->view('PageHeaderContentView', array('title' => $deal->deal_name, 'owner' => $deal->name, 'date' => $deal->deal_create_date)); ?>

  <?php
    if (in_array(strtolower($stage), array('won', 'lost'))) {
      $pageActions = $this->isAdmin ? array(array('label' => 'Edit', 'icon' => 'icon-edit m-r-mini', 'redirectPath' => "deals/edit/$deal->deal_id")) : array();
      $pageActions[] = array('label' => 'Delete', 'icon' => 'icon-remove-circle', 'class' => 'bcz-confirm-operation');
      $pageActions[] = array('label' => 'Reassign Opportunity', 'icon' => 'icon-reply', 'modalId' => 'reassign_deal_modal');
      $pageActions[] = array('label' => 'Add Quote', 'icon' => 'icon-plus', 'redirectPath' => "deals/addquote/$deal->deal_id");

    } else {
      $pageActions = array(
        array('label' => 'Edit', 'icon' => 'icon-edit m-r-mini', 'redirectPath' => "deals/edit/$deal->deal_id"),
        array('label' => 'Delete', 'icon' => 'icon-remove-circle', 'class' => 'bcz-confirm-operation'),
        array('label' => 'Reassign Opportunity', 'icon' => 'icon-reply', 'modalId' => 'reassign_deal_modal'),
        array('label' => 'Add Quote', 'icon' => 'icon-plus', 'redirectPath' => "deals/addquote/$deal->deal_id")
      );
    }
    $this->load->view('PageHeaderActionsView', array('controller' => 'deals', 'pageType' => 'deal', 'pageId' => $deal->deal_id, 'prevId' => $PreviousId, 'nextId' => $NextId, 'pageActions' => $pageActions)); 
  ?>
</div>

<?php if ($stage) { ?>
  <?php $this->load->view('PageStagesBarView', array('stages' => $deal->stages, 'pageStage' => $stage, 'pageId' => $deal->deal_id)); ?>
<?php } ?>

<?php
$colorBlocks = array(
  array('head' => 'Account', 'body' => $deal->company_name, 'color' => 'primary', 'bodyCls' => 'bcz-text-ellipsis', 'link' => 'companies/details/'.$deal->deal_company_id),
  array('head' => "Value", 'body' => $deal->deal_amount, 'color' => 'inverse', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Contact', 'body' => $deal->contact_name, 'color' => 'success', 'bodyCls' => 'bcz-text-ellipsis', 'link' => 'contacts/details/'.$deal->deal_contact_id),
  array('head' => 'Close Date', 'body' => $deal->exp_close, 'color' => 'info', 'type' => 'date', 'bodyCls' => 'bcz-text-ellipsis')
);
$this->load->view('PageColorBlocksView', array('colorBlocks' => $colorBlocks));
?>

<div class="row m-t-large padder">
  <?php
  $borderBlocks1 = array(
    array('head' => 'Campaign', 'body' => $deal->deal_application, 'cols' => 3),
    array('head' => 'Industry', 'body' => $deal->industry, 'cols' => 3),
    array('head' => 'Source', 'body' => $deal->source, 'cols' => 3),
    array('head' => 'Status', 'body' => $deal->status, 'cols' => 3)
  );
  $this->load->view('PageBorderBlocksView', array('borderBlocks' => $borderBlocks1));
  ?>
</div>

<div class="row m-t-mini padder">
  <?php
  $borderBlocks1 = array(
    array('head' => 'Last Modified', 'body' => $deal->deal_modify_date, 'cols' => 3, 'type' => 'date'),
    array('head' => 'Customer Since', 'body' => $deal->created_before, 'cols' => 3),
    array('head' => 'Quotes Count', 'body' => count($quotes), 'cols' => 3),
    array('head' => 'Assigned To', 'body' => $deal->name, 'cols' => 3)
  );
  $this->load->view('PageBorderBlocksView', array('borderBlocks' => $borderBlocks1));
  ?>
</div>

  <div class="row m-t-mini padder">
  <div class="col-sm-12 no-padder">
    <div class="panel m-b-none">
      <div class="panel-body">
        <div class="col-sm-2 h4 m-t-mini m-b-mini">Summary:</div>
        <div class="col-sm-10 h5" style="margin: 7px 0;"><?=($deal->summary?$deal->summary:$this->noDataChar)?></div>
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
        <?php $this->load->view('EntityNotesView', array('entityType' => 'deal', 'entityId' => $deal->deal_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseTwo', 'headingText' => 'Tasks', 'actionButtons' => true, 'buttons' => array(array('text' => 'add', 'modalId' => '#create_task_modal', 'associateTo' => 'deal', 'associateId' => $deal->deal_id)))); ?>

    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityTasksView', array('entityType' => 'deal', 'entityId' => $deal->deal_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseThree', 'headingText' => 'Accounts', 'actionButtons' => false)); ?>

    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <div class="col-sm-6 text-center no-padder-v m-t-mini">
          <div class="h4 col-sm-6 text-left m-t-small m-b-small">Name</div><div class="h4 col-sm-6 text-left m-t-small m-b-small"><?=($deal->company_name?"<a class='text-primary' href='".base_url()."companies/details/$deal->company_id'>".$deal->company_name."</a>":$this->noDataChar)?></div>
          <div class="h4 col-sm-6 text-left m-b-small">Created On</div><div class="h4 col-sm-6 text-left m-b-small"><?=($deal->company_create_date?convertDateTime($deal->company_create_date):$this->noDataChar)?></div>
          <div class="h4 col-sm-6 text-left m-b-small">Customer Type</div><div class="h4 col-sm-6 text-left m-b-small"><?=($deal->com_cust_type?$deal->com_cust_type:$this->noDataChar)?></div>
          <div class="h4 col-sm-6 text-left m-b-small">Employees</div><div class="h4 col-sm-6 text-left m-b-small"><?=($deal->com_employees?$deal->com_employees:$this->noDataChar)?></div>
          <div class="h4 col-sm-6 text-left m-b-small">Revenue</div><div class="h4 col-sm-6 text-left m-b-small"><?=($deal->com_revenue?$deal->com_revenue:$this->noDataChar)?></div>
        </div>
      </div>
    </div>
  </div>
 
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseFour', 'headingText' => 'Contacts', 'actionButtons' => true, 'buttons' => array(array('text' => 'add', 'modalId' => '#create_contact_modal', 'associateTo' => 'deal', 'account' => $deal->company_id, 'associateId' => $deal->deal_id)))); ?>

    <div id="collapseFour" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityContactsView', array('entityType' => 'deal', 'entityId' => $deal->deal_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseFive', 'headingText' => 'Documents', 'actionButtons' => false)); ?>

    <div id="collapseFive" class="panel-collapse collapse">
      <div class="panel-body text-small bcz-docs-container">
        <?php $this->load->view('EntityDocsView', array('btnLabel' => 'Upload', 'actionPath' => 'files/uploadentitydoc', 'associateTo' => 'deal', 'associateId' => $deal->deal_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseSix', 'headingText' => 'Mails', 'actionButtons' => true, 'buttons' => array(array('text' => 'add', 'modalId' => '#compose_email_modal', 'associateTo' => 'deal', 'associateId' => $deal->deal_id, 'class' => 'bcz-btn-email-modal')))); ?>

    <div id="collapseSix" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityEmailsView', array('entityType' => 'deal', 'entityId' => $deal->deal_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseSeven', 'headingText' => 'Quotes', 'actionButtons' => true, 'buttons' => array(array('text' => 'add', 'path' => 'deals/addquote/'.$deal->deal_id, 'associateTo' => 'deal', 'associateId' => $deal->deal_id)))); ?>

    <div id="collapseSeven" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php if ($quotes[0]) { ?>
            <?php $this->load->view('DataTableView', array('cols' => array_values($this->quoteTableCols), 'mobileCols' => array(0, 4, 5), 'sourcePath' => "quotes/getentityquotesjson?type=deal&id=$deal->deal_id")); ?>
        <?php } else { ?>
          <p class="bcz-no-data-msg h5">No quotes added yet.</p>
        <?php } ?>
      </div>
    </div>
  </div>
  
  
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseSeven1', 'headingText' => 'Sales Orders', 'actionButtons' => false)); ?>

 <div id="collapseSeven1" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php if ($Orders[0]) { ?>
            <?php $this->load->view('DataTableView', array('cols' => array_values($this->orderTableCols), 'mobileCols' => array(0, 4, 5), 'sourcePath' => "orders/getordersjson1?type=sales_order&id=$deal->deal_id")); ?>
        <?php } else { ?>
          <p class="bcz-no-data-msg h5">No Sales Orders added yet.</p>
        <?php } ?>
      </div>
    </div>
  </div>
  
  
  
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseEight', 'headingText' => 'Products', 'actionButtons' => false)); ?>

    <div id="collapseEight" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php if ($products[0]) { ?>
            <?php $this->load->view('DataTableView', array('cols' => array_values($this->productTableCols), 'mobileCols' => array(0, 2, 3), 'sourcePath' => "products/getentityproductsjson?type=deal&id=$deal->deal_id")); ?>
        <?php } else { ?>
          <p class="bcz-no-data-msg h5">No products added yet.</p>
        <?php } ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseTen', 'headingText' => 'History', 'actionButtons' => false)); ?>

    <div id="collapseTen" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityHistoryView', array('entityType' => 'deal', 'entityId' => $deal->deal_id)); ?>
      </div>
    </div>
  </div>
</div>
<!-- / .accordion -->