<div class="clearfix">
  <?php $this->load->view('PageHeaderContentView', array('title' => $company->company_name, 'owner' => $company->name, 'date' => $company->company_create_date)); ?>

  <?php

    $this->load->view('PageHeaderActionsView', array('controller' => 'companies', 'pageType' => 'company', 'pageId' => $company->company_id, 'prevId' => $PreviousId, 'nextId' => $NextId)); 
  ?>

  <div class="btn-group pull-right m-t m-r-small">
    <a href="<?=base_url()?>companies/edit/<?=$company->company_id?>" class="btn btn-sm btn-white"><i class="icon-edit m-r-mini"></i>Edit</a>
  </div>
</div>
    
<?php
$colorBlocks = array(
  array('head' => 'Customer Type', 'body' => $company->com_cust_type, 'color' => 'primary', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Phone', 'body' => $company->phone, 'color' => 'inverse', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Email', 'body' => $company->com_email, 'color' => 'success', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Website', 'body' => $company->website, 'color' => 'info', 'bodyCls' => 'bcz-text-ellipsis')
);
$this->load->view('PageColorBlocksView', array('colorBlocks' => $colorBlocks));
?>

<div class="row m-t-large padder text-center">
  <div class="col-sm-6 text-center no-padder">
    <?php
	  if($company->created_before=="0 Days")
	{
		$company_created='Today';
		$created='Created';
	}
	else if($company->created_before=="1 Day")
	{
		$company_created='Yesterday';
		$created='Created';
	}
	else
	{
		$company_created= $company->created_before;
		$created='Created Before';
	}
    $borderBlocks = array(
      array('head' => 'Campaign', 'body' => $company->com_application, 'link' => "contacts/details/$quote->contact_id"),
      array('head' => 'Industry', 'body' => $company->com_industry),
      array('head' => 'Modified', 'body' => $company->company_modify_date, 'type' => 'date'),
      array('head' => $created, 'body' => $company_created),
      array('head' => 'No Of Employees', 'body' => $company->com_employees),
      array('head' => 'Annual Revenue', 'body' => $company->com_revenue),
      array('head' => 'Assigned To', 'body' => $company_assign_to)
    );
    $this->load->view('PageBorderBlocksView', array('borderBlocks' => $borderBlocks));
    ?> 
  </div>

  <div class="col-sm-6 text-center no-padder m-t-mini">
    <?php $this->load->view('PageAddressInfoView', array('address' => $company->bill_address, 'city' => $company->bill_city, 'state' => $company->bill_state, 'zip' => $company->bill_postal_code, 'country' => $company->bill_country)); ?>
  </div>
</div>

<div class="row m-t-mini padder">
  <div class="col-sm-12 no-padder">
    <div class="panel m-b-none">
      <div class="panel-body">
        <div class="col-sm-2 h4 m-t-mini m-b-mini">Summary:</div>
        <div class="col-sm-10 h5" style="margin: 7px 0;"><?=($company->com_description?$company->com_description:$this->noDataChar)?></div>
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
        <?php $this->load->view('EntityNotesView', array('entityType' => 'company', 'entityId' => $company->company_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseTwo', 'headingText' => 'Tasks', 'actionButtons' => true, 'buttons' => array(array('text' => 'add', 'modalId' => '#create_task_modal', 'associateTo' => 'company', 'associateId' => $company->company_id)))); ?>

    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityTasksView', array('entityType' => 'company', 'entityId' => $company->company_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseThree', 'headingText' => 'Opportunities', 'actionButtons' => true, 'buttons' => array(array('text' => 'add', 'modalId' => '#create_deal_modal', 'associateTo' => 'company', 'associateId' => $company->company_id)))); ?>

    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityDealsView', array('entitySourcePath' => 'companies/getcompanydealsjson', 'entityType' => 'company', 'entityId' => $company->company_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseSeven', 'headingText' => 'Contacts', 'actionButtons' => true, 'buttons' => array(array('text' => 'add', 'modalId' => '#create_contact_modal', 'associateTo' => 'company', 'associateId' => $company->company_id)))); ?>

    <div id="collapseSeven" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('CompanyContactsView', array('entitySourcePath' => 'companies/getcompanycontactsjson', 'entityType' => 'Accounts', 'entityId' => $company->company_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseFive', 'headingText' => 'Documents', 'actionButtons' => false)); ?>

    <div id="collapseFive" class="panel-collapse collapse">
      <div class="panel-body text-small bcz-docs-container">
        <?php $this->load->view('EntityDocsView', array('btnLabel' => 'Upload', 'actionPath' => 'files/uploadentitydoc', 'associateTo' => 'company', 'associateId' => $company->company_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseFour', 'headingText' => 'Tickets', 'actionButtons' => false)); ?>

    <div id="collapseFour" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php if ($cases[0]) { ?>
          <?php $this->load->view('DataTableView', array('cols' => array_values($this->caseTableCols), 'mobileCols' => array(0, 1, 4), 'sourcePath' => "companies/getcompanycasesjson?id=$company->company_id")); ?>
        <?php } else { ?>
          <p class="bcz-no-data-msg h5">No tickets for this company.</p>
        <?php } ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseSix', 'headingText' => 'Quotes', 'actionButtons' => false)); ?>

    <div id="collapseSix" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php if ($quotes[0]) { ?>
            <?php $this->load->view('DataTableView', array('cols' => array_values($this->quoteTableCols), 'mobileCols' => array(0, 4, 5), 'sourcePath' => "quotes/getentityquotesjson?type=company&id=$company->company_id")); ?>
        <?php } else { ?>
          <p class="bcz-no-data-msg h5">No quotes belongs to this company.</p>
        <?php } ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseTen', 'headingText' => 'History', 'actionButtons' => false)); ?>

    <div id="collapseTen" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityHistoryView', array('entityType' => 'company', 'entityId' => $company->company_id)); ?>
      </div>
    </div>
  </div>
</div>
<!-- / .accordion -->