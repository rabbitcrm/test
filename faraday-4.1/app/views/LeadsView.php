<div class="clearfix">
  <h3>
  	Leads list
    <input type="hidden" class="export" value="0" />
  	<?php if ($this->isAdmin ) { ?>
     <a  href="<?=base_url()?>leads/import" class="btn btn-sm btn-inverse pull-right m-l-small import " style="margin-top: -2px;"><i class="icon-arrow-down"></i>Import</a>
     <?php } ?>
     <?php if ($this->isAdmin && $leads[0]) { ?>
    <a  href="<?=base_url()?>leads/export" class="btn btn-sm btn-inverse pull-right m-l-small exporta " style="margin-top: -2px;"><i class="icon-arrow-up"></i> Export</a><?php } ?>
  	<a href="<?=base_url()?>leads/add" class="btn btn-sm btn-inverse pull-right" style="margin-top: -2px;"><i class="icon-plus"></i> ADD</a>
  </h3>
</div>

<?php if ($leads[0]) { ?>

  <?php $this->load->view('FiltersView', array('actionPath' => 'leads', 'fSource' => $leads_filters)); ?>
  <div class="bcz-filters-content1">
  <label><input type="text" data-filter-action="<?=base_url().$actionPath?>" class="input-sm form-control lead_search" placeholder="Search" value="" id="lead_search"></label>
  </div>
 <div class="bcz-filters-content">
    <?php $this->load->view('DataTableViewOpp', array('sourcePath' => 'leads/getleadsjson')); ?>
  </div>

<?php } else {

  $this->load->view('NoDataView', array('nodata' => 'No Leads Available'));

} ?>