<div class="clearfix">
  <?php $this->load->view('PageHeaderContentView', array('title' => $order->subject, 'owner' => $order->name, 'date' => $order->so_create_date)); ?>

  <?php
    $pageActions = array(
      array('label' => 'Edit', 'icon' => 'icon-edit m-r-mini', 'redirectPath' => "orders/edit/$order->so_id"),
      array('label' => 'Send SO', 'icon' => 'icon-envelope-alt', 'modalId' => 'compose_email_modal'),
      array('label' => 'Download PDF', 'icon' => 'icon-download-alt',  'href' => "files/view?type=order&name=$pdf_name", 'target' =>"_blank")
    );

    $this->load->view('PageHeaderActionsView', array('controller' => 'orders', 'pageType' => 'order', 'pageId' => $order->so_id, 'prevId' => $PreviousId, 'nextId' => $NextId, 'pageActions' => $pageActions)); 
  ?>
</div>

<?php if ($so_stage) { ?>
  <?php $this->load->view('PageStagesBarView', array('stages' => $order->stages, 'pageStage' => $so_stage, 'pageId' => $order->so_id)); ?>
<?php } ?>

<?php
$colorBlocks = array(
  array('head' => 'Account', 'body' => $order->company_name, 'color' => 'primary', 'bodyCls' => 'bcz-text-ellipsis', 'link' => "companies/details/$order->company_id"),
  array('head' => 'Order Amount', 'body' => $order->total, 'color' => 'inverse', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Opportunity', 'body' => $order->deal_name, 'color' => 'success', 'bodyCls' => 'bcz-text-ellipsis', 'link' => "deals/details/$order->deal_id"),
  array('head' => 'Estimated Delivery', 'body' => $order->estimated_delivery, 'color' => 'info', 'type' => 'date', 'bodyCls' => 'bcz-text-ellipsis')
);
$this->load->view('PageColorBlocksView', array('colorBlocks' => $colorBlocks));
?>

<div class="row m-t-large padder text-center">
  <div class="col-sm-6 text-center no-padder">
    <?php
	 if($order->created_before=="0 Days")
	{
		$created_before='Today';
		$created='Created';
	}
	else if($order->created_before=="1 Day")
	{
		$created_before='Yesterday';
		$created='Created';
	}
	else
	{
		$created_before= $order->created_before;
		$created='Created Before';
	}
    $borderBlocks = array(
      array('head' => 'Contact', 'body' => $order->contact_name, 'link' => "contacts/details/$order->contact_id"),
      array('head' => 'Currency', 'body' => $order->so_currency),
      array('head' => 'Modified', 'body' => $order->so_modify_date, 'type' => 'date'),
      array('head' => $created, 'body' => $created_before),
      array('head' => 'Delivery', 'body' => $order->delivery),
      array('head' => 'Carrier', 'body' => $order->carrier)
    );
    $this->load->view('PageBorderBlocksView', array('borderBlocks' => $borderBlocks));
    ?>
  </div>

  <div class="col-sm-6 text-center no-padder m-t-mini">
    <?php $this->load->view('PageAddressInfoView', array('address' => $order->bill_addr, 'city' => $order->bill_city, 'state' => $order->bill_state, 'zip' => $order->bill_pcode, 'country' => $order->bill_country)); ?>
  </div>
</div>

<div class="row padder">
  <div class="col-sm-6 text-left padder-r-mini no-padder-l m-t-mini">
    <div class="panel m-b-none">
      <div class="panel-body h4">
        <div><strong>Terms & Conditions</strong></div>
        <div class="m-t-small"><?=($order->terms?$order->terms:$this->noDataChar)?></div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 text-left padder-r-mini no-padder-l m-t-mini">
    <div class="panel m-b-none">
      <div class="panel-body h4">
        <div><strong>Remarks</strong></div>
        <div class="m-t-small"><?=($order->so_description?$order->so_description:$this->noDataChar)?></div>
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
          <p class="bcz-no-data-msg h5">No items found for this order.</p>
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
        <div class="h5 col-xs-6 text-left"><?=($order->frieght?$order->frieght:0)?></div>
        <div class="h5 col-xs-6 text-left m-t-small">Installation</div>
        <div class="h5 col-xs-6 text-left m-t-small"><?=($order->install?$order->install:0)?></div>
        <div class="h4 col-xs-6 text-left m-t-large">Total</div>
        <div class="h4 col-xs-6 text-left m-t-large"><?=($order->total?$order->total:0)?></div>
      </div>
    </div>
  </div>
</div>