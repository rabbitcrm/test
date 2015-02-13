<div class="clearfix">
  <?php $this->load->view('PageHeaderContentView', array('title' => $quote->subject, 'owner' => $quote->name, 'date' => $quote->quote_create_date)); ?>

  <?php
    $pageActions = array(
      array('label' => 'Edit', 'icon' => 'icon-edit m-r-mini', 'redirectPath' => "quotes/edit/$quote->quote_id"),
	  array('label' => 'Revise Quote', 'icon' => 'icon-edit m-r-mini', 'redirectPath' => "quotes/revisequote/$quote->quote_id"),
	  
      array('label' => 'Convert To Sales Order', 'icon' => 'icon-briefcase', 'redirectPath' => "quotes/generateso/$quote->quote_id"), //'modalId' => 'generate_so_modal'),
      array('label' => 'Send Quote', 'icon' => 'icon-envelope-alt', 'modalId' => 'compose_email_modal'),
      array('label' => 'Download PDF', 'icon' => 'icon-download-alt', 'href' => "files/view?type=quote&name=$pdf_name" ,'target' =>"_blank")
    );
	
    $this->load->view('PageHeaderActionsView', array('controller' => 'quotes', 'pageType' => 'quote', 'pageId' => $quote->quote_id, 'prevId' =>$PreviousId, 'nextId' => $NextId, 'pageActions' => $pageActions)); 
  ?>
</div>



<?php if ($quote->quote_stage) { ?>
  <?php $this->load->view('PageStagesBarView', array('stages' => $quote->stages, 'pageStage' => $stage, 'pageId' => $quote->quote_id)); ?>
<?php } ?>

<?php
$colorBlocks = array(
  array('head' => 'Account', 'body' => $quote->company_name, 'color' => 'primary', 'bodyCls' => 'bcz-text-ellipsis', 'link' => "companies/details/$quote->company_id"),
  array('head' => 'Quote Amount', 'body' => $quote->total, 'color' => 'inverse', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Opportunity', 'body' => $quote->deal_name, 'color' => 'success', 'bodyCls' => 'bcz-text-ellipsis', 'link' => "deals/details/$quote->deal_id"),
  array('head' => 'Valid Till', 'body' => $quote->valid_till, 'color' => 'info', 'type' => 'date', 'bodyCls' => 'bcz-text-ellipsis')
);
$this->load->view('PageColorBlocksView', array('colorBlocks' => $colorBlocks));
?>

<div class="row m-t-large padder text-center">
  <div class="col-sm-6 text-center no-padder">
    <?php
	  if($quote->created_before=="0 Days")
	{
		$created_before='Today';
		$created='Created';
	}
	else if($quote->created_before=="1 Day")
	{
		$created_before='Yesterday';
		$created='Created';
	}
	else
	{
		$created_before= $quote->created_before;
		$created='Created Before';
	}
  
    $borderBlocks = array(
      array('head' => 'Contact', 'body' => $quote->contact_name, 'link' => "contacts/details/$quote->contact_id"),
      array('head' => 'Currency', 'body' => $quote->quote_currency),
      array('head' => 'Modified', 'body' => $quote->quote_modify_date, 'type' => 'date'),
      array('head' => $created, 'body' => $created_before),
      array('head' => 'Delivery', 'body' => $quote->delivery),
      array('head' => 'Carrier', 'body' => $quote->carrier)
    );
    $this->load->view('PageBorderBlocksView', array('borderBlocks' => $borderBlocks));
    ?>
  </div>

  <div class="col-sm-6 text-center no-padder m-t-mini">
    <?php $this->load->view('PageAddressInfoView', array('address' => $quote->bill_addr, 'city' => $quote->bill_city, 'state' => $quote->bill_state, 'zip' => $quote->bill_pcode, 'country' => $quote->bill_country)); ?>
  </div>
</div>

<div class="row padder">
  <div class="col-sm-6 text-left padder-r-mini no-padder-l m-t-mini">
    <div class="panel m-b-none">
      <div class="panel-body h4">
        <div><strong>Terms & Conditions</strong></div>
        <div class="m-t-small"><?=($quote->terms?$quote->terms:$this->noDataChar)?></div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 text-left padder-r-mini no-padder-l m-t-mini">
    <div class="panel m-b-none">
      <div class="panel-body h4">
        <div><strong>Remarks</strong></div>
        <div class="m-t-small"><?=($quote->q_description?$quote->q_description:$this->noDataChar)?></div>
      </div>
    </div>
  </div>
</div>

<!-- .accordion -->
<div class="panel-group m-b m-t-large" id="accordion2">
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseOne', 'headingText' => 'Items', 'actionButtons' => false)); ?>

    <div id="collapseOne" class="panel-collapse in">
      <div class="panel-body text-small">
        <?php if ($arrangedItems['rows'][0]) { ?>
          <?php $this->load->view('DataTableView', array('cols' => $arrangedItems['cols'], 'mobileCols' => array(0, 1, 5), 'rows' => $arrangedItems['rows'], 'urlFlag' => false)); ?>
        <?php } else { ?>
          <p class="bcz-no-data-msg h5">No items found for this quote.</p>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<!-- / .accordion -->

<div class="row padder">
  <div class="col-sm-4 text-center no-padder-l no-padder-r m-t-mini pull-right">
    <div class="panel m-b-none">
      <div class="panel-body">
        <div class="h5 col-xs-6 text-left">Frieght</div>
        <div class="h5 col-xs-6 text-left"><?=($quote->frieght?$quote->frieght:0)?></div>
        <div class="h5 col-xs-6 text-left m-t-small">Installation</div>
        <div class="h5 col-xs-6 text-left m-t-small"><?=($quote->install?$quote->install:0)?></div>
        <div class="h4 col-xs-6 text-left m-t-large">Total</div>
        <div class="h4 col-xs-6 text-left m-t-large"><?=($quote->total?$quote->total:0)?></div>
      </div>
    </div>
  </div>
</div>