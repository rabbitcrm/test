<div class="clearfix">
  <h3>
  	Quotes list
    <input type="hidden" class="export" value="0" />
  	<?php if ($this->isAdmin && $quotes[0]) { ?><a href="<?=base_url()?>quotes/export" class="btn btn-sm btn-inverse pull-right m-l-small exporta" style="margin-top: -2px;"><i class="icon-arrow-up"></i> Export</a><?php } ?>
  </h3>
</div>

<?php if ($quotes[0]) { ?>

  <?php $this->load->view('FiltersView', array('actionPath' => 'quotes', 'fSource' => $quotes_filters)); ?>
  <div class="bcz-filters-content1">
  <label><input type="text" data-filter-action="<?=base_url()."products"?>" class="input-sm form-control quotes_search" placeholder="Search" value="" id="quotes_search"></label>
  </div>
  <div class="bcz-filters-content">
    <?php $this->load->view('DataTableViewOpp', array('sourcePath' => 'quotes/getquotesjson')); ?>
  </div>

<?php } else {

  $this->load->view('NoDataView', array('nodata' => 'No Quotes Available'));

} ?>