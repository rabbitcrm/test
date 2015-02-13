<div class="clearfix">
  <h3>
  	Campaigns list
       <input type="hidden" class="export" value="0" />
  	<?php if ($this->isAdmin && $campaigns[0]) { ?><a href="<?=base_url()?>campaign/export" class="btn btn-sm btn-inverse pull-right m-l-small exporta" style="margin-top: -2px;"><i class="icon-arrow-up"></i> Export</a><?php } ?>
  	<a href="<?=base_url()?>campaign/add" class="btn btn-sm btn-inverse pull-right" style="margin-top: -2px;"><i class="icon-plus"></i> ADD</a>
  </h3>
</div>

<?php if ($campaigns[0]) { ?>

  <?php $this->load->view('FiltersView', array('actionPath' => 'campaign', 'fSource' => $campaigns)); ?>
  <div class="bcz-filters-content1">
  <label><input type="text" data-filter-action="<?=base_url().$actionPath?>" class="input-sm form-control campaign_search" placeholder="Search" value="" id="campaign_search"></label>
  </div>
 <div class="bcz-filters-content">
    <?php $this->load->view('DataTableViewOpp', array('sourcePath' => 'campaign/getcampaignjson')); ?>
  </div>

<?php } else {

  $this->load->view('NoDataView', array('nodata' => 'No Campaign Available'));

} ?>