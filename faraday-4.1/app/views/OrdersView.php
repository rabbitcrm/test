<div class="clearfix">
  <h3>
  	Sales Orders list
    <input type="hidden" class="export" value="0" />
  	<?php if ($this->isAdmin && $orders[0]) { ?><a href="<?=base_url()?>orders/export" class="btn btn-sm btn-inverse pull-right m-l-small exporta" style="margin-top: -2px;"><i class="icon-arrow-up"></i> Export</a><?php } ?>
  </h3>
</div>

<?php if ($orders[0]) { ?>

  <?php $this->load->view('FiltersView', array('actionPath' => 'orders', 'fSource' => $orders)); ?>
  <div class="bcz-filters-content1">
  <label><input type="text" data-filter-action="<?=base_url()."products"?>" class="input-sm form-control orders_search" placeholder="Search" value="" id="orders_search"></label>
  </div>
  <div class="bcz-filters-content">
    <?php $this->load->view('DataTableViewOpp', array('sourcePath' => 'orders/getordersjson')); ?>
  </div>

<?php } else {

 $this->load->view('NoDataView', array('nodata' => 'No Sales Orders Available'));

} ?>