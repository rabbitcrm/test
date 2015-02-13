<div class="clearfix">
  <h3>
  	Opportunities list
    <input type="hidden" class="export" value="0" />
  	<?php if ($this->isAdmin && $deals[0]) { ?><a href="<?=base_url()?>deals/export" class="btn btn-sm btn-inverse pull-right m-l-small exporta" style="margin-top: -2px;"><i class="icon-arrow-up"></i> Export</a><?php } ?>
  	<a href="<?=base_url()?>deals/add" class="btn btn-sm btn-inverse pull-right" style="margin-top: -2px;"><i class="icon-plus"></i> ADD</a></h3>
</div>

<?php if ($deals[0]) { ?>

  <?php $this->load->view('FiltersView', array('actionPath' => 'deals', 'fSource' => $dealsfilters)); ?>
  <div class="bcz-filters-content1">
  <label><input type="text" data-filter-action="<?=base_url()."deals"?>" class="input-sm form-control Opportunities_search" placeholder="Search" value="" id="Opportunities_search"></label>
  </div>
  <div class="bcz-filters-content">
    <?php $this->load->view('DataTableViewOpp', array('sourcePath' => 'deals/getdealsjson')); ?>
  </div>

<?php } else {

    $this->load->view('NoDataView', array('nodata' => 'No Opportunities Available'));

} ?>