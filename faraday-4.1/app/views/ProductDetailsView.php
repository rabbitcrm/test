<div class="clearfix">
  <?php $this->load->view('PageHeaderContentView', array('title' => $product->product_name, 'owner' => $product->owner, 'date' => $product->create_date)); ?>

  <?php
    $this->load->view('PageHeaderActionsView', array('controller' => 'products', 'pageType' => 'product', 'pageId' => $product->product_id, 'prevId' =>$PreviousId, 'nextId' => $NextId)); 
  ?>

  <div class="btn-group pull-right m-t m-r-small">
    <a href="<?=base_url()?>products/edit/<?=$product->product_id?>" class="btn btn-sm btn-white"><i class="icon-edit m-r-mini"></i>Edit</a>
  </div>
</div>

<?php
$colorBlocks = array(
  array('head' => 'Part No', 'body' => $product->partno, 'color' => 'primary', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Category', 'body' => $product->category, 'color' => 'inverse', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Price('.$currency_freeze['currency'].")", 'body' => $product->price, 'color' => 'success', 'bodyCls' => 'bcz-text-ellipsis'),
  array('head' => 'Modified', 'body' => $product->modify_date, 'color' => 'info', 'type' => 'date', 'bodyCls' => 'bcz-text-ellipsis')
);
$this->load->view('PageColorBlocksView', array('colorBlocks' => $colorBlocks));
?>

<div class="row m-t-large padder">
  <?php
  if($product->created_before=="0 Days")
	{
		$created_before='Today';
		$created='Created';
	}
	else if($product->created_before=="1 Day")
	{
		$created_before='Yesterday';
		$created='Created';
	}
	else
	{
		$created_before= $product->created_before;
		$created='Created Before';
	}
  $borderBlocks = array(
    array('head' => 'Created Date', 'cols' => 4, 'body' => $product->create_date, 'type' => 'date'),
   //array('head' => ' Price('.$currency_freeze['currency'].")", 'cols' => 4, 'body' => $product->usd_price),
    array('head' => $created, 'cols' => 4, 'body' => $created_before)
  );
  $this->load->view('PageBorderBlocksView', array('borderBlocks' => $borderBlocks));
  ?>
</div>

<!-- .accordion -->
<div class="panel-group m-b m-t-large" id="accordion2">
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseOne', 'headingText' => 'Notes', 'actionButtons' => false)); ?>

    <div id="collapseOne" class="panel-collapse in">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityNotesView', array('entityType' => 'product', 'entityId' => $product->product_id)); ?>
      </div>
    </div>
  </div>
  <?php /*?><div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseTwo', 'headingText' => 'Tasks', 'actionButtons' => true, 'buttons' => array(array('text' => 'add', 'modalId' => '#create_task_modal', 'associateTo' => 'product', 'associateId' => $product->product_id)))); ?>

    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityTasksView', array('entityType' => 'product', 'entityId' => $product->product_id)); ?>
      </div>
    </div>
  </div><?php */?>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseFour', 'headingText' => 'Tickets', 'actionButtons' => false)); ?>

    <div id="collapseFour" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php if ($cases[0]) { ?>
          <?php $this->load->view('DataTableView', array('cols' => array_values($this->caseTableCols), 'mobileCols' => array(0, 1, 4), 'sourcePath' => "products/getproductcasesjson?id=$product->product_id")); ?>
        <?php } else { ?>
          <p class="bcz-no-data-msg h5">No tickets for this product.</p>
        <?php } ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseFive', 'headingText' => 'Documents', 'actionButtons' => false)); ?>

    <div id="collapseFive" class="panel-collapse collapse">
      <div class="panel-body text-small bcz-docs-container">
        <?php $this->load->view('EntityDocsView', array('btnLabel' => 'Upload', 'actionPath' => 'files/uploadentitydoc', 'associateTo' => 'product', 'associateId' => $product->product_id)); ?>
      </div>
    </div>
  </div>
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseTen', 'headingText' => 'History', 'actionButtons' => false)); ?>

    <div id="collapseTen" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('EntityHistoryView', array('entityType' => 'product', 'entityId' => $product->product_id)); ?>
      </div>
    </div>
  </div>
</div>
<!-- / .accordion -->