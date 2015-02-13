<div class="clearfix">
  <h3>
  	Products list
    <input type="hidden" class="export" value="0" />
    <?php if ($this->isAdmin ) { ?>
     <a  href="<?=base_url()?>products/import" class="btn btn-sm btn-inverse pull-right m-l-small import " style="margin-top: -2px;"><i class="icon-arrow-down"></i>Import</a>
     <?php } ?>
  	<?php if ($this->isAdmin && $products[0]) { ?><a href="<?=base_url()?>products/export" class="btn btn-sm btn-inverse pull-right m-l-small exporta" style="margin-top: -2px;"><i class="icon-arrow-up"></i> Export</a><?php } ?>
  	<a href="<?=base_url()?>products/add" class="btn btn-sm btn-inverse pull-right" style="margin-top: -2px;"><i class="icon-plus"></i> ADD</a>
  </h3>
</div>

<?php if ($products[0]) { ?>

<?php $this->load->view('FiltersView', array('actionPath' => 'products', 'fSource' => $products)); ?>
  <div class="bcz-filters-content1">
  <label><input type="text" data-filter-action="<?=base_url()."products"?>" class="input-sm form-control products_search" placeholder="Search" value="" id="products_search"></label>
  </div>
  <div class="bcz-filters-content">
    <?php $this->load->view('ProductDataTableView', array('sourcePath' => 'products/getproductsjson')); ?>
  </div>

<?php } else {

$this->load->view('NoDataView', array('nodata' => 'No Products Available'));

} ?>