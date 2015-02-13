<div class="clearfix">
  <h3>
  	Tickets list
  	<?php if ($this->isAdmin && $cases[0]) { ?><a href="<?=base_url()?>cases/export" class="btn btn-sm btn-inverse pull-right m-l-small" style="margin-top: -2px;"><i class="icon-arrow-up"></i> Export</a><?php } ?>
  	<a href="<?=base_url()?>cases/add" class="btn btn-sm btn-inverse pull-right" style="margin-top: -2px;"><i class="icon-plus"></i> ADD</a>
  </h3>
</div>

<?php if ($cases[0]) { ?>

  <?php $this->load->view('FiltersView', array('actionPath' => 'cases', 'fSource' => $cases)); ?>
  <div class="bcz-filters-content1">
  <label><input type="text" data-filter-action="<?=base_url()."products"?>" class="input-sm form-control cases_search" placeholder="Search" value="" id="cases_search"></label>
  </div>
  <div class="bcz-filters-content">
    <?php $this->load->view('DataTableViewOpp', array('sourcePath' => 'cases/getcasesjson')); ?>
  </div>

<?php } else {

 $this->load->view('NoDataView', array('nodata' => 'No Tickets Available'));

} ?>