<div class="clearfix">
  <?php $this->load->view('PageHeaderContentView', array('title' => $contact->contact_name, 'owner' => $contact->name, 'date' => $contact->contact_create_date)); ?>

  <?php
    $this->load->view('PageHeaderActionsView', array('controller' => 'contacts', 'pageType' => 'contact', 'pageId' => $contact->contact_id, 'prevId' =>$PreviousId, 'nextId' => $NextId)); 
  ?>

  <div class="btn-group pull-right m-t m-r-small">
    <a href="<?=base_url()?>contacts/edit/<?=$contact->contact_id?>" class="btn btn-sm btn-white"><i class="icon-edit m-r-mini"></i>Edit</a>
  </div>
</div>
    
<div class="row padder">
  <div class="col-sm-6 text-center no-padder">
    <?php
    $colorBlocks = array(
      array('head' => 'Account', 'body' => $contact->company_name, 'color' => 'primary', 'bodyCls' => 'bcz-text-ellipsis', 'link' => "companies/details/$contact->company_id", 'cols' => 6),
      array('head' => 'Phone', 'body' => $contact->mobile, 'color' => 'inverse', 'bodyCls' => 'bcz-text-ellipsis', 'cols' => 6),
      array('head' => 'Email', 'body' => $contact->email, 'color' => 'success', 'bodyCls' => 'bcz-text-ellipsis', 'cols' => 6),
      array('head' => 'Title', 'body' => $contact->designation, 'color' => 'info', 'bodyCls' => 'bcz-text-ellipsis', 'cols' => 6)
    );
    $this->load->view('PageColorBlocksView', array('colorBlocks' => $colorBlocks));
    ?>

    <?php
    $borderBlocks = array(
      array('head' => 'Modified', 'body' => $contact->contact_modify_date, 'type' => 'date'),
      array('head' => 'Created Before', 'body' => $contact->created_before)
    );
    $this->load->view('PageBorderBlocksView', array('borderBlocks' => $borderBlocks));
    ?> 
  </div>
  <div class="col-sm-6 text-center no-padder">
    <?php $this->load->view('PageAddressInfoView', array('address' => $contact->address, 'city' => $contact->city, 'state' => $contact->state, 'zip' => $contact->postal_code, 'country' => $contact->country)); ?>
  </div>
</div>

<div class="row m-t-mini padder">
  <div class="col-sm-12 no-padder">
    <div class="panel m-b-none">
      <div class="panel-body">
        <div class="col-sm-2 h4 m-t-mini m-b-mini">Summary:</div>
        <div class="col-sm-10 h5" style="margin: 7px 0;"><?=($contact->con_description?$contact->con_description:$this->noDataChar)?></div>
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
        <?php $this->load->view('EntityNotesView', array('entityType' => 'contact', 'entityId' => $contact->contact_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseTwo', 'headingText' => 'Tasks', 'actionButtons' => true, 'buttons' => array(array('text' => 'add', 'modalId' => '#create_task_modal', 'associateTo' => 'contact', 'associateId' => $contact->contact_id)))); ?>

    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityTasksView', array('entityType' => 'contact', 'entityId' => $contact->contact_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseThree', 'headingText' => 'Opportunities', 'actionButtons' => true, 'buttons' => array(array('text' => 'add', 'modalId' => '#create_deal_modal', 'associateTo' => 'contact', 'associateId' => $contact->contact_id)))); ?>

    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityDealsView', array('entitySourcePath' => 'contacts/getcontactdealsjson', 'entityType' => 'contact', 'entityId' => $contact->contact_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseFour', 'headingText' => 'Tickets', 'actionButtons' => true, 'buttons' => array(array('text' => 'add', 'modalId' => '#create_tickets_modal', 'associateTo' => 'contact', 'associateId' => $contact->contact_id)))); ?>

    <div id="collapseFour" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php if ($cases[0]) { ?>
          <?php $this->load->view('DataTableView', array('cols' => array_values($this->caseTableCols), 'mobileCols' => array(0, 1, 4), 'sourcePath' => "contacts/getcontactcasesjson?id=$contact->contact_id")); ?>
        <?php } else { ?>
          <p class="bcz-no-data-msg h5">No tickets for this contact.</p>
        <?php } ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseFive', 'headingText' => 'Documents', 'actionButtons' => false)); ?>

    <div id="collapseFive" class="panel-collapse collapse">
      <div class="panel-body text-small bcz-docs-container">
        <?php $this->load->view('EntityDocsView', array('btnLabel' => 'Upload', 'actionPath' => 'files/uploadentitydoc', 'associateTo' => 'contact', 'associateId' => $contact->contact_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseSix', 'headingText' => 'Quotes', 'actionButtons' => false)); ?>

    <div id="collapseSix" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php if ($quotes[0]) { ?>
            <?php $this->load->view('DataTableView', array('cols' => array_values($this->quoteTableCols), 'mobileCols' => array(0, 4, 5), 'sourcePath' => "quotes/getentityquotesjson?type=contact&id=$contact->contact_id")); ?>
        <?php } else { ?>
          <p class="bcz-no-data-msg h5">No quotes belongs to this contact.</p>
        <?php } ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseTen', 'headingText' => 'History', 'actionButtons' => false)); ?>

    <div id="collapseTen" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityHistoryView', array('entityType' => 'contact', 'entityId' => $contact->contact_id)); ?>
      </div>
    </div>
  </div>
</div>
<!-- / .accordion -->